<?php
/**
 * 图集发布
 *
 * @version        $Id: album_add.php 1 8:26 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_New,a_AccNew');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");

if(empty($dopost)) $dopost = '';

if($dopost != 'save')
{
    require_once(DEDEINC."/dedetag.class.php");
    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    ClearMyAddon();
    $channelid = empty($channelid) ? 0 : intval($channelid);
    $cid = empty($cid) ? 0 : intval($cid);

    //获得频道模型ID
    if($cid>0 && $channelid==0)
    {
        $row = $dsql->GetOne("SELECT channeltype FROM `#@__arctype` WHERE id='$cid'; ");
        $channelid = $row['channeltype'];
    }
    else
    {
        if($channelid==0) $channelid = 2;
    }

    //获得频道模型信息
    $cInfos = $dsql->GetOne(" SELECT * FROM  `#@__channeltype` WHERE id='$channelid' ");
    $channelid = $cInfos['id'];
    
    //获取文章最大id以确定当前权重
    $maxWright = $dsql->GetOne("SELECT COUNT(*) AS cc FROM #@__archives");
    include DedeInclude("templets/album_add.htm");
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
    if(!isset($formhtml)) $formhtml = 0;
    if(!isset($formzip)) $formzip = 0;
    if(!isset($ddisfirst)) $ddisfirst = 0;
    if(!isset($delzip)) $delzip = 0;
    if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);

    if($typeid==0)
    {
        ShowMsg("请指定文档的栏目！", "-1");
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
    $ismake = $ishtml==0 ? -1 : 0;
    $title = preg_replace("#\"#", '＂', $title);
    $title = cn_substrR($title,$cfg_title_maxlen);
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
    $litpic = GetDDImage('none',$picname,$ddisremote);

    //使用第一张图作为缩略图
    if($ddisfirst==1 && $litpic=='')
    {
        if(isset($imgurl1))
        {
            $litpic = GetDDImage('ddfirst', $imgurl1, $isrm);
        }
    }
    

    //生成文档ID
    $arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$adminid);
    if(empty($arcID))
    {
        ShowMsg("无法获得主键，因此无法进行后续操作！","-1");
        exit();
    }

    $imgurls = "{dede:pagestyle maxwidth='$maxwidth' pagepicnum='$pagepicnum' ddmaxwidth='$ddmaxwidth' row='$row' col='$col' value='$pagestyle'/}\r\n";
    $hasone = FALSE;

    //处理并保存从网上复制的图片
    /*---------------------
    function _getformhtml()
    ------------------*/
    if($formhtml==1)
    {
        $imagebody = stripslashes($imagebody);
        $imgurls .= GetCurContentAlbum($imagebody,$copysource,$litpicname);
        if($ddisfirst==1 && $litpic=='' && !empty($litpicname))
        {
            $litpic = $litpicname;
            $hasone = TRUE;
        }
    }
    /*---------------------
    function _getformzip()
    处理从ZIP中解压的图片
    ---------------------*/
    if($formzip==1)
    {
        include_once(DEDEINC."/zip.class.php");
        include_once(DEDEADMIN."/file_class.php");
        $zipfile = $cfg_basedir.str_replace($cfg_mainsite,'',$zipfile);
        $tmpzipdir = DEDEDATA.'/ziptmp/'.cn_substr(md5(ExecTime()),16);
        $ntime = time();
        if(file_exists($zipfile))
        {
            @mkdir($tmpzipdir,$GLOBALS['cfg_dir_purview']);
            @chmod($tmpzipdir,$GLOBALS['cfg_dir_purview']);
            $z = new zip();
            $z->ExtractAll($zipfile,$tmpzipdir);
            $fm = new FileManagement();
            $imgs = array();
            $fm->GetMatchFiles($tmpzipdir,"jpg|png|gif",$imgs);
            $i = 0;
            foreach($imgs as $imgold)
            {
                $i++;
                $savepath = $cfg_image_dir."/".MyDate("Y-m",$ntime);
                CreateDir($savepath);
                $iurl = $savepath."/".MyDate("d",$ntime).dd2char(MyDate("His",$ntime).'-'.$adminid."-{$i}".mt_rand(1000,9999));
                $iurl = $iurl.substr($imgold,-4,4);
                $imgfile = $cfg_basedir.$iurl;
                copy($imgold,$imgfile);
                unlink($imgold);

                if(is_file($imgfile))
                {
                    $litpicname = $pagestyle > 2 ? GetImageMapDD($iurl,$cfg_ddimg_width) : $iurl;
                    //指定了提取第一张为缩略图的情况强制使用第一张缩略图
                    if($i=='1')
                    {
                        if(!$hasone && $ddisfirst==1 && $litpic=='' && empty($litpicname))
                        {
                            $litpicname = GetImageMapDD($iurl,$cfg_ddimg_width);
                        }
                    }
                    $info = '';
                    $imginfos = GetImageSize($imgfile,$info);
                    $imgurls .= "{dede:img ddimg='$litpicname' text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";

                    //把图片信息保存到媒体文档管理档案中
                    $inquery = "
                   INSERT INTO #@__uploads(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
                    VALUES ('{$title}','{$iurl}','1','".$imginfos[0]."','".$imginfos[1]."','0','".filesize($imgfile)."','".$ntime."','$adminid');
                 ";
                    $dsql->ExecuteNoneQuery($inquery);
                    $fid = $dsql->GetLastID();
                    AddMyAddon($fid, $iurl);
                    
                    WaterImg($imgfile, 'up');

                    if(!$hasone && $ddisfirst==1 && $litpic=='')
                    {
                        if(empty($litpicname))
                        {
                            $litpicname = $iurl;
                            $litpicname = GetImageMapDD($iurl, $cfg_ddimg_width);
                        }
                        $litpic = $litpicname;
                        $hasone = TRUE;
                    }
                }
            }
            if($delzip==1) unlink($zipfile);
            $fm->RmDirFiles($tmpzipdir);
        }
    }
    /*---------------------
    function _getformupload()
    通过swfupload正常上传的图片
    ---------------------*/
    if(is_array($_SESSION['bigfile_info']))
    {
        foreach($_SESSION['bigfile_info'] as $k=>$v)
        {
            $truefile = $cfg_basedir.$v;
            if(strlen($v)<2 || !file_exists($truefile)) continue;
            $info = '';
            $imginfos = GetImageSize($truefile, $info);
            $litpicname = $pagestyle > 2 ? GetImageMapDD($v, $cfg_ddimg_width) : '';
            if(!$hasone && $ddisfirst==1 && $litpic=='')
            {
                 $litpic = empty($litpicname) ? GetImageMapDD($v, $cfg_ddimg_width) : $litpicname;
                 $hasone = TRUE;
            }
            $imginfo =  !empty(${'picinfook'.$k}) ? ${'picinfook'.$k} : '';
            $imgurls .= "{dede:img ddimg='$v' text='$imginfo' width='".$imginfos[0]."' height='".$imginfos[1]."'} $v {/dede:img}\r\n";
        }
    }

    $imgurls = addslashes($imgurls);
    
    //处理body字段自动摘要、自动提取缩略图等
    $body = AnalyseHtmlBody($body,$description,$litpic,$keywords,'htmltext');

    //分析处理附加表数据
    $inadd_f = '';
    $inadd_v = '';
    if(!empty($dede_addonfields))
    {
        $addonfields = explode(';',$dede_addonfields);
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
    //加入主档案表
    $query = "INSERT INTO `#@__archives`(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
     color,writer,source,litpic,pubdate,senddate,mid,notpost,description,keywords,filename,dutyadmin,weight)
    VALUES ('$arcID','$typeid','$typeid2','$sortrank','$flag','$ismake','$channelid','$arcrank','$click','$money','$title','$shorttitle',
    '$color','$writer','$source','$litpic','$pubdate','$senddate','$adminid','$notpost','$description','$keywords','$filename','$adminid','$weight'); ";
    if(!$dsql->ExecuteNoneQuery($query))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery(" DELETE FROM `#@__arctiny` WHERE id='$arcID' ");
        ShowMsg("把数据保存到数据库主表 `#@__archives` 时出错，请把相关信息提交给DedeCms官方。".str_replace('"','',$gerr),"javascript:;");
        exit();
    }

    //加入附加表
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
    $query = "INSERT INTO `$addtable`(aid,typeid,redirecturl,userip,pagestyle,maxwidth,imgurls,row,col,isrm,ddmaxwidth,pagepicnum,body{$inadd_f})
         Values('$arcID','$typeid','$redirecturl','$useip','$pagestyle','$maxwidth','$imgurls','$row','$col','$isrm','$ddmaxwidth','$pagepicnum','$body'{$inadd_v}); ";
    if(!$dsql->ExecuteNoneQuery($query))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__archives` WHERE id='$arcID'");
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
        ShowMsg("把数据保存到数据库附加表 `{$addtable}` 时出错，请把相关信息提交给DedeCMS官方。".str_replace('"','',$gerr),"javascript:;");
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
    $artUrl = MakeArt($arcID, TRUE, TRUE, $isremote);
    if($artUrl=='')
    {
        $artUrl = $cfg_phpurl."/view.php?aid=$arcID";
    }
    ClearMyAddon($arcID, $title);
    //返回成功信息
    $msg = "
    　　请选择你的后续操作：
    <a href='album_add.php?cid=$typeid'><u>继续发布图片</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$arcID."&dopost=editArchives'><u>更改图集</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>预览文档</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>已发布图片管理</u></a>
    &nbsp;&nbsp;
    $backurl
   ";
  $msg = "<div style=\"line-height:36px;height:36px\">{$msg}</div>".GetUpdateTest();
    
    $wintitle = "成功发布一个图集！";
    $wecome_info = "文章管理::发布图集";
    $win = new OxWindow();
    $win->AddTitle("成功发布一个图集：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",FALSE);
    $win->Display();
}