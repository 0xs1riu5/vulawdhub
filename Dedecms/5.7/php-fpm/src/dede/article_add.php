<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('a_New,a_AccNew');
require_once(DEDEINC.'/customfields.func.php');
require_once(DEDEADMIN.'/inc/inc_archives_functions.php');
if(file_exists(DEDEDATA.'/template.rand.php'))
{
    require_once(DEDEDATA.'/template.rand.php');
}
if(empty($dopost)) $dopost = '';

if($dopost!='save')
{
    require_once(DEDEINC."/dedetag.class.php");
    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    ClearMyAddon();
    $channelid = empty($channelid) ? 0 : intval($channelid);
    $cid = empty($cid) ? 0 : intval($cid);

    if(empty($geturl)) $geturl = '';
    
    $keywords = $writer = $source = $body = $description = $title = '';

    //采集单个网页
    if(preg_match("#^http:\/\/#", $geturl))
    {
        require_once(DEDEADMIN."/inc/inc_coonepage.php");
        $redatas = CoOnePage($geturl);
        extract($redatas);
    }

    //获得频道模型ID
    if($cid>0 && $channelid==0)
    {
        $row = $dsql->GetOne("Select channeltype From `#@__arctype` where id='$cid'; ");
        $channelid = $row['channeltype'];
    }
    else
    {
        if($channelid==0)
        {
            $channelid = 1;
        }
    }

    //获得频道模型信息
    $cInfos = $dsql->GetOne(" Select * From  `#@__channeltype` where id='$channelid' ");
    
    //获取文章最大id以确定当前权重
    $maxWright = $dsql->GetOne("SELECT COUNT(*) AS cc FROM #@__archives");
    
    include DedeInclude("templets/article_add.htm");
    exit();
}

/*--------------------------------
function __save(){  }
-------------------------------*/
else if($dopost=='save')
{
    require_once(DEDEINC.'/image.func.php');
    require_once(DEDEINC.'/oxwindow.class.php');
    $flag = isset($flags) ? join(',',$flags) : '';
    $notpost = isset($notpost) && $notpost == 1 ? 1: 0;
    
    if(empty($typeid2)) $typeid2 = '';
    if(!isset($autokey)) $autokey = 0;
    if(!isset($remote)) $remote = 0;
    if(!isset($dellink)) $dellink = 0;
    if(!isset($autolitpic)) $autolitpic = 0;
    if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);
    
    if(empty($typeid))
    {
        ShowMsg("请指定文档的栏目！","-1");
        exit();
    }
    if(empty($channelid))
    {
        ShowMsg("文档为非指定的类型，请检查你发布内容的表单是否合法！","-1");
        exit();
    }
    if(!CheckChannel($typeid,$channelid))
    {
        ShowMsg("你所选择的栏目与当前模型不相符，请选择白色的选项！","-1");
        exit();
    }
    if(!TestPurview('a_New'))
    {
        CheckCatalog($typeid,"对不起，你没有操作栏目 {$typeid} 的权限！");
    }

    //对保存的内容进行处理
    if(empty($writer))$writer=$cuserLogin->getUserName();
    if(empty($source))$source='未知';
    $pubdate = GetMkTime($pubdate);
    $senddate = time();
    $sortrank = AddDay($pubdate,$sortup);
    $ismake = $ishtml==0 ? -1 : 0;
    $title = preg_replace("#\"#", '＂', $title);
    $title = htmlspecialchars(cn_substrR($title,$cfg_title_maxlen));
    $shorttitle = cn_substrR($shorttitle,36);
    $color =  cn_substrR($color,7);
    $writer =  cn_substrR($writer,20);
    $source = cn_substrR($source,30);
    $description = cn_substrR($description,$cfg_auot_description);
    $keywords = cn_substrR($keywords,60);
    $filename = trim(cn_substrR($filename,40));
    $userip = GetIP();
    $isremote  = (empty($isremote)? 0  : $isremote);
    $serviterm=empty($serviterm)? "" : $serviterm;

    if(!TestPurview('a_Check,a_AccCheck,a_MyCheck'))
    {
        $arcrank = -1;
    }
    $adminid = $cuserLogin->getUserID();

    //处理上传的缩略图
    if(empty($ddisremote))
    {
        $ddisremote = 0;
    }
    
    $litpic = GetDDImage('none', $picname, $ddisremote);

    //生成文档ID
    $arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$adminid);
    
    if(empty($arcID))
    {
        ShowMsg("无法获得主键，因此无法进行后续操作！","-1");
        exit();
    }
    if(trim($title) == '')
    {
        ShowMsg('标题不能为空', '-1');
        exit();
    }

    //处理body字段自动摘要、自动提取缩略图等
    $body = AnalyseHtmlBody($body,$description,$litpic,$keywords,'htmltext');

    //自动分页
    if($sptype=='auto')
    {
        $body = SpLongBody($body,$spsize*1024,"#p#分页标题#e#");
    }

    //分析处理附加表数据
    $inadd_f = $inadd_v = '';
    if(!empty($dede_addonfields))
    {
        $addonfields = explode(';',$dede_addonfields);
        if(is_array($addonfields))
        {
            foreach($addonfields as $v)
            {
                if($v=='') continue;
                $vs = explode(',',$v);
                if($vs[1]=='htmltext'||$vs[1]=='textdata')
                {
                    ${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
                }
                else
                {
                    if(!isset(${$vs[0]})) ${$vs[0]} = '';
                    ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$arcID);
                }
                $inadd_f .= ','.$vs[0];
                $inadd_v .= " ,'".${$vs[0]}."' ";
            }
        }
    }

    //处理图片文档的自定义属性
    if($litpic!='' && !preg_match("#p#", $flag))
    {
        $flag = ($flag=='' ? 'p' : $flag.',p');
    }
    if($redirecturl!='' && !preg_match("#j#", $flag))
    {
        $flag = ($flag=='' ? 'j' : $flag.',j');
    }
    
    //跳转网址的文档强制为动态
    if(preg_match("#j#", $flag)) $ismake = -1;

    //保存到主表
    $query = "INSERT INTO `#@__archives`(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
    color,writer,source,litpic,pubdate,senddate,mid,voteid,notpost,description,keywords,filename,dutyadmin,weight)
    VALUES ('$arcID','$typeid','$typeid2','$sortrank','$flag','$ismake','$channelid','$arcrank','$click','$money',
    '$title','$shorttitle','$color','$writer','$source','$litpic','$pubdate','$senddate',
    '$adminid','$voteid','$notpost','$description','$keywords','$filename','$adminid','$weight');";

    if(!$dsql->ExecuteNoneQuery($query))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
        ShowMsg("把数据保存到数据库主表 `#@__archives` 时出错，请把相关信息提交给DedeCms官方。".str_replace('"','',$gerr),"javascript:;");
        exit();
    }

    //保存到附加表
    $cts = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$channelid' ");
    $addtable = trim($cts['addtable']);
    if(empty($addtable))
    {
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__archives` WHERE id='$arcID'");
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
        ShowMsg("没找到当前模型[{$channelid}]的主表信息，无法完成操作！。","javascript:;");
        exit();
    }
    $useip = GetIP();
    $templet = empty($templet) ? '' : $templet;
    $query = "INSERT INTO `{$addtable}`(aid,typeid,redirecturl,templet,userip,body{$inadd_f}) Values('$arcID','$typeid','$redirecturl','$templet','$useip','$body'{$inadd_v})";
    if(!$dsql->ExecuteNoneQuery($query))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
        $dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
        ShowMsg("把数据保存到数据库附加表 `{$addtable}` 时出错，请把相关信息提交给DedeCms官方。".str_replace('"','',$gerr),"javascript:;");
        exit();
    }

    //生成HTML
    InsertTags($tags,$arcID);
    if($cfg_remote_site=='Y' && $isremote=="1")
    {    
        if($serviterm!=""){
            list($servurl,$servuser,$servpwd) = explode(',',$serviterm);
            $config=array( 'hostname' => $servurl, 'username' => $servuser, 'password' => $servpwd,'debug' => 'TRUE');
        }else{
            $config=array();
        }
        if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
    }
    $artUrl = MakeArt($arcID,true,true,$isremote);
    if($artUrl=='')
    {
        $artUrl = $cfg_phpurl."/view.php?aid=$arcID";
    }
    ClearMyAddon($arcID, $title);
    //返回成功信息
    $msg = "    　　请选择你的后续操作：
    <a href='article_add.php?cid=$typeid'><u>继续发布文章</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>查看文章</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$arcID."&dopost=editArchives'><u>更改文章</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>已发布文章管理</u></a>
    &nbsp;&nbsp;
    $backurl
  ";
  $msg = "<div style=\"line-height:36px;height:36px\">{$msg}</div>".GetUpdateTest();
    $wintitle = "成功发布文章！";
    $wecome_info = "文章管理::发布文章";
    $win = new OxWindow();
    $win->AddTitle("成功发布文章：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",false);
    $win->Display();
}

?>