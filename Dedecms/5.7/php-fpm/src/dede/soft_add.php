<?php
/**
 * 软件发布
 *
 * @version        $Id: soft_add.php 1 16:09 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_New,a_AccNew');
require_once(DEDEINC.'/customfields.func.php');
require_once(DEDEADMIN.'/inc/inc_archives_functions.php');
if(empty($dopost)) $dopost = '';

if($dopost != 'save')
{
    require_once(DEDEINC.'/dedetag.class.php');
    require_once(DEDEADMIN.'/inc/inc_catalog_options.php');
    ClearMyAddon();
    $channelid = empty($channelid) ? 0 : intval($channelid);
    $cid = empty($cid) ? 0 : intval($cid);

    //获得频道模型ID
    if($cid>0 && $channelid==0)
    {
        $row = $dsql->GetOne("SELECT channeltype FROM `#@__arctype` WHERE id='$cid'; ");
        $channelid = $row['channeltype'];
    } else {
        if($channelid==0) $channelid = 1;
    }

    $softconfig = $dsql->GetOne("SELECT * FROM `#@__softconfig` ");
    //获得频道模型信息
    $cInfos = $dsql->GetOne(" SELECT * FROM  `#@__channeltype` WHERE id='$channelid' ");
    $channelid = $cInfos['id'];
    
    //获取文章最大id以确定当前权重
    $maxWright = $dsql->GetOne("SELECT COUNT(*) AS cc FROM #@__archives");
    include DedeInclude("templets/soft_add.htm");
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
    if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);
    
    if(!isset($typeid2)) $typeid2 = 0;
    if(!isset($autokey)) $autokey = 0;
    if(!isset($remote)) $remote = 0;
    if(!isset($dellink)) $dellink = 0;
    if(!isset($autolitpic)) $autolitpic = 0;

    if($typeid==0)
    {
        ShowMsg("请指定文档的栏目！","-1");
        exit();
    }
    if(empty($channelid))
    {
        ShowMsg("文档为非指定的类型，请检查你发布内容的表单是否合法！","-1");
        exit();
    }
    if(!CheckChannel($typeid,$channelid) )
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
    
    if($ishtml==0) $ismake = -1;
    else $ismake = 0;
    
    if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);

    $title = preg_replace('#"#', '＂', $title);
    $title = cn_substrR($title, $cfg_title_maxlen);
    $shorttitle = cn_substrR($shorttitle, 36);
    $color =  cn_substrR($color, 7);
    $writer =  cn_substrR($writer, 20);
    $source = cn_substrR($source, 30);
    $description = cn_substrR($description, $cfg_auot_description);
    $keywords = cn_substrR($keywords, 60);
    $filename = trim(cn_substrR($filename, 40));
    $userip = GetIP();
    $isremote  = (empty($isremote)? 0  : $isremote);
    $serviterm=empty($serviterm)? "" : $serviterm;
    if(!TestPurview('a_Check,a_AccCheck,a_MyCheck'))
    {
        $arcrank = -1;
    }
    $adminid = $cuserLogin->getUserID();

    //处理上传的缩略图
    if(empty($ddisremote)) $ddisremote = 0;
    $litpic = GetDDImage('none', $picname, $ddisremote);

    //生成文档ID
    $arcID = GetIndexKey($arcrank, $typeid, $sortrank, $channelid, $senddate, $adminid);
    if(empty($arcID))
    {
        ShowMsg("无法获得主键，因此无法进行后续操作！","-1");
        exit();
    }

    //处理body字段自动摘要、自动提取缩略图等
    $body = AnalyseHtmlBody($body, $description, $litpic, $keywords, 'htmltext');

    //分析处理附加表数据
    $inadd_f = '';
    $inadd_v = '';
    if(!empty($dede_addonfields))
    {
        $addonfields = explode(';', $dede_addonfields);
        $inadd_f = '';
        $inadd_v = '';
        if(is_array($addonfields))
        {
            foreach($addonfields as $v)
            {
                if($v=='')
                {
                    continue;
                }
                $vs = explode(',',$v);
                if(!isset(${$vs[0]}))
                {
                    ${$vs[0]} = '';
                }
                else if($vs[1]=='htmltext'||$vs[1]=='textdata') //HTML文本特殊处理
                {
                    ${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
                }
                else
                {
                    if(!isset(${$vs[0]}))
                    {
                        ${$vs[0]} = '';
                    }
                    ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$arcID);
                }
                $inadd_f .= ','.$vs[0];
                $inadd_v .= " ,'".${$vs[0]}."' ";
            }
        }
    }

    //处理图片文档的自定义属性
    if($litpic!='' && !preg_match('#p#', $flag))
    {
        $flag = ($flag=='' ? 'p' : $flag.',p');
    }
    if($redirecturl!='' && !preg_match('#j#', $flag))
    {
        $flag = ($flag=='' ? 'j' : $flag.',j');
    }

    //跳转网址的文档强制为动态
    if(preg_match('#j#', $flag)) $ismake = -1;
    //保存到主表
    $inQuery = "INSERT INTO `#@__archives`(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
    color,writer,source,litpic,pubdate,senddate,mid,notpost,description,keywords,filename,dutyadmin,weight)
    VALUES ('$arcID','$typeid','$typeid2','$sortrank','$flag','$ismake','$channelid','$arcrank','$click','$money','$title','$shorttitle',
    '$color','$writer','$source','$litpic','$pubdate','$senddate','$adminid','$notpost','$description','$keywords','$filename','$adminid','$weight');";
    if(!$dsql->ExecuteNoneQuery($inQuery))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
        ShowMsg("把数据保存到数据库主表 `#@__archives` 时出错，请把相关信息提交给DedeCms官方。".str_replace('"','',$gerr),"javascript:;");
        exit();
    }

    //软件链接列表
    $urls = '';

    //本地链接处理
    $softurl1 = stripslashes($softurl1);
    $nsoftsize = '';
    if($softurl1 != '')
    {
        $urls .= "{dede:link islocal='1' text='{$servermsg1}'} $softurl1 {/dede:link}\r\n";
        $autosize = empty($autosize) ? FALSE : TRUE;
        if($autosize && empty($softsize))
        {
            $nsoftsize = @filesize($cfg_basedir.$softurl1);
            if(empty($nsoftsize)) $nsoftsize = '未知';
            else
            {
                $nsoftsize = trim(sprintf("%0.2f", $nsoftsize / 1024 / 1024));
                $nsoftsize = $nsoftsize." MB";
            }
        }
    }
    
    //软件大小
    if(!empty($nsoftsize)) $softsize = $nsoftsize;
    else if(empty($softsize)) $softsize = '未知';
    else $softsize = $softsize.' '.$unit;
    
    //其它链接处理
    for($i=2; $i<=30; $i++)
    {
        if(!empty(${'softurl'.$i}))
        {
            $forconfig = empty(${'forconfig'.$i}) ? FALSE : TRUE;
            if($forconfig)
            {
                if(empty(${'need'.$i})) continue;
                $serverUrl = stripslashes(${'softurlfirst'.$i});
                $serverUrl = preg_replace("#\/$#", "", $serverUrl);
                $softurl = stripslashes(${'softurl'.$i});
                if( cn_substr($softurl, 1) != '/' ) $softurl = '/'.$softurl;
                $softurl = $serverUrl.$softurl;
            }
            else
            {
                $softurl = stripslashes(${'softurl'.$i});
            }
            $servermsg = str_replace("'", "", stripslashes(${'servermsg'.$i}));
            if($servermsg=='') $servermsg = '下载地址'.$i;
            if($softurl != 'http://')
            {
                $urls .= "{dede:link text='$servermsg'} $softurl {/dede:link}\r\n";
            }
        }
    }
    $urls = addslashes($urls);

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
    $daccess = isset($daccess) && is_numeric($daccess) ? $daccess : 0;
    $useip = GetIP();
    $inQuery = "INSERT INTO `$addtable`(aid,typeid,redirecturl,userip,filetype,language,softtype,accredit,
    os,softrank,officialUrl,officialDemo,softsize,softlinks,introduce,daccess,needmoney{$inadd_f})
    VALUES ('$arcID','$typeid','$redirecturl','$useip','$filetype','$language','$softtype','$accredit',
    '$os','$softrank','$officialUrl','$officialDemo','$softsize','$urls','$body','$daccess','$needmoney'{$inadd_v});";
    if(!$dsql->ExecuteNoneQuery($inQuery))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__archives` WHERE id='$arcID'");
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
        ShowMsg("把数据保存到数据库附加表 `{$addtable}` 时出错，请把相关信息提交给DedeCms官方。".str_replace('"','',$gerr),"javascript:;");
        exit();
    }

    //生成HTML
    InsertTags($tags, $arcID);
    if($cfg_remote_site=='Y' && $isremote=="1")
    { 
        if($serviterm!="")
        {
            list($servurl, $servuser, $servpwd) = explode(',',$serviterm);
            $config = array( 'hostname' => $servurl, 'username' => $servuser, 
                           'password' => $servpwd,'debug' => 'TRUE');
        } else {
            $config = array();
        }
        if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
    }

    $arcUrl = MakeArt($arcID, TRUE, TRUE, $isremote);
    if($arcUrl=='')
    {
        $arcUrl = $cfg_phpurl."/view.php?aid=$arcID";
    }
    ClearMyAddon($arcID, $title);
    //返回成功信息
    $msg = "
    　　请选择你的后续操作：
    <a href='soft_add.php?cid=$typeid'><u>继续发布软件</u></a>
    &nbsp;&nbsp;
    <a href='$arcUrl' target='_blank'><u>查看软件</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$arcID."&dopost=editArchives'><u>更改软件</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>已发布软件管理</u></a>
    &nbsp;&nbsp;
    <a href='catalog_main.php'><u>网站栏目管理</u></a>
   ";
    $msg = "<div style=\"line-height:36px;height:36px\">{$msg}</div>".GetUpdateTest();

    $wintitle = "成功发布一个软件！";
    $wecome_info = "文章管理::发布软件";
    $win = new OxWindow();
    $win->AddTitle("成功发布软件：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand", "&nbsp;", FALSE);
    $win->Display();
}