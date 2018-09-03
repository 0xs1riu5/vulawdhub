<?php
/**
 * 图集发布
 * 
 * @version        $Id: album_add.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");

//考虑安全原因不管是否开启游客投稿功能，都不允许用户对图集投稿
CheckRank(0,0);
if($cfg_mb_lit=='Y')
{
    ShowMsg("由于系统开启了精简版会员空间，你访问的功能不可用！","-1");
    exit();
}
if($cfg_mb_album=='N')
{
    ShowMsg("对不起，由于系统关闭了图集功能，你访问的功能不可用！","-1");
    exit();
}
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/userlogin.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 2;
$typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
$menutype = 'content';
if(empty($formhtml)) $formhtml = 0;

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
    $query = "SELECT * FROM `#@__channeltype` WHERE id='$channelid'; ";
    $cInfos = $dsql->GetOne($query);
    if(!is_array($cInfos))
    {
        ShowMsg('模型参数不正确', '-1');
        exit();
    }

    //检查会员等级和类型限制
    if($cInfos['sendrank'] > $cfg_ml->M_Rank)
    {
        $row = $dsql->GetOne("Select membername From `#@__arcrank` where rank='".$cInfos['sendrank']."' ");
        ShowMsg("对不起，需要[".$row['membername']."]才能在这个频道发布文档！","-1","0",5000);
        exit();
    }
    if($cInfos['usertype']!='' && $cInfos['usertype'] != $cfg_ml->M_MbType)
    {
        ShowMsg("对不起，需要[".$cInfos['usertype']."帐号]才能在这个频道发布文档！","-1","0",5000);
        exit();
    }
    include(DEDEMEMBER."/templets/album_add.htm");
    exit();
}

/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
    $svali = GetCkVdValue();
    if(preg_match("/1/",$safe_gdopen)){
        if(strtolower($vdcode)!=$svali || $svali=='')
        {
            ResetVdValue();
            ShowMsg('验证码错误！', '-1');
            exit();
        }
        
    }
    $cInfos = $dsql->GetOne("Select * From `#@__channeltype`  where id='$channelid'; ");
    $maxwidth = isset($maxwidth) && is_numeric($maxwidth) ? $maxwidth : 800;
    $pagepicnum = isset($pagepicnum) && is_numeric($pagepicnum) ? $pagepicnum : 12;
    $ddmaxwidth = isset($ddmaxwidth) && is_numeric($ddmaxwidth) ? $ddmaxwidth : 200;
    $prow = isset($prow) && is_numeric($prow) ? $prow : 3;
    $pcol = isset($pcol) && is_numeric($pcol) ? $pcol : 3;
    $pagestyle = in_array($pagestyle,array('1','2','3')) ? $pagestyle : 2;
    include(DEDEMEMBER.'/inc/archives_check.php');
    $imgurls = "{dede:pagestyle maxwidth='$maxwidth' pagepicnum='$pagepicnum' ddmaxwidth='$ddmaxwidth' row='$prow' col='$pcol' value='$pagestyle'/}\r\n";
    $hasone = false;
    $ddisfirst=1;

    //处理并保存所指定的图片从网上复制
    if($formhtml==1)
    {
        $imagebody = stripslashes($imagebody);
        $imgurls .= GetCurContentAlbum($imagebody,$copysource,$litpicname);
        if($ddisfirst==1 && $litpic=='' && !empty($litpicname))
        {
            $litpic = $litpicname;
            $hasone = true;
        }
    }
    $info = '';

    //正常上传
    for($i=1;$i<=120;$i++)
    {
        //含有图片的条件
        if(isset($_FILES['imgfile'.$i]['tmp_name']) && is_uploaded_file($_FILES['imgfile'.$i]['tmp_name']) )
        {
            $iinfo = str_replace("'","`",stripslashes(${'imgmsg'.$i}));
            if(!is_uploaded_file($_FILES['imgfile'.$i]['tmp_name']))
            {
                continue;
            }
            else
            {
                $sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
                if(!in_array($_FILES['imgfile'.$i]['type'],$sparr))
                {
                    continue;
                }
                $filename = MemberUploads('imgfile'.$i,'',$cfg_ml->M_ID,'image','',0,0,false);
                if($filename!='')
                {
                    SaveUploadInfo($title,$filename,1);
                }

                //缩图
                if($pagestyle > 2)
                {
                    $litpicname = GetImageMapDD($filename,$ddmaxwidth);
                    if($litpicname != '')
                    {
                        SaveUploadInfo($title.' 小图',$litpicname,1);
                    }
                }
                else
                {
                    $litpicname = $filename;
                }
                $imgfile = $cfg_basedir.$filename;
                if(is_file($imgfile))
                {
                    $iurl = $filename;
                    $info = '';
                    $imginfos = @getimagesize($imgfile,$info);
                    $imgurls .= "{dede:img ddimg='$litpicname' text='$iinfo' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
                }
            }
            if(!$hasone && $litpic=='' && !empty($litpicname))
            {
                $litpic = $litpicname;
                $hasone = true;
            }
        }
    }//循环结束
    $imgurls = addslashes($imgurls);

    //分析处理附加表数据
    $isrm = 1;
    if(!isset($formhtml))
    {
        $formhtml = 0;
    }
    $inadd_f = $inadd_v = '';
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
                ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],0);
                $inadd_f .= ','.$vs[0];
                $inadd_v .= " ,'".${$vs[0]}."' ";
            }
        }
        
        if (empty($dede_fieldshash) || $dede_fieldshash != md5($dede_addonfields.$cfg_cookie_encode))
        {
            showMsg('数据校验不对，程序返回', '-1');
            exit();
        }
        
        // 这里对前台提交的附加数据进行一次校验
        $fontiterm = PrintAutoFieldsAdd($cInfos['fieldset'],'autofield', FALSE);
        if ($fontiterm != $inadd_f)
        {
            ShowMsg("提交表单同系统配置不相符,请重新提交！", "-1");
            exit();
        }
    }

    //处理图片文档的自定义属性
    if($litpic!='')
    {
        $flag = 'p';
    }

    //生成文档ID
    $arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$mid);
    if(empty($arcID))
    {
        ShowMsg("无法获得主键，因此无法进行后续操作！","-1");
        exit();
    }
	$description = HtmlReplace($description, -1);//2011.06.30 增加html过滤 （by:织梦的鱼）
    //保存到主表
    $inQuery = "INSERT INTO `#@__archives`(id,typeid,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
color,writer,source,litpic,pubdate,senddate,mid,description,keywords,mtype)
VALUES ('$arcID','$typeid','$sortrank','$flag','$ismake','$channelid','$arcrank','0','$money','$title','$shorttitle',
'$color','$writer','$source','$litpic','$pubdate','$senddate','$mid','$description','$keywords','$mtypesid'); ";
    if(!$dsql->ExecuteNoneQuery($inQuery))
    {
        $gerr = $dsql->GetError();
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID' ");
        ShowMsg("把数据保存到数据库主表 `#@__archives` 时出错，请联系管理员。","javascript:;");
        exit();
    }

    //保存到附加表
    $addtable = trim($cInfos['addtable']);
    if(empty($addtable))
    {
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__archives` WHERE id='$arcID'");
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
        ShowMsg("没找到当前模型[{$channelid}]的主表信息，无法完成操作！。","javascript:;");
        exit();
    }
    else
    {
        $query = "INSERT INTO `$addtable`(aid,typeid,userip,redirecturl,templet,pagestyle,maxwidth,imgurls,row,col,isrm,ddmaxwidth,pagepicnum{$inadd_f})
     Values('$arcID','$typeid','$userip','','','$pagestyle','$maxwidth','$imgurls','$prow','$pcol','$isrm','$ddmaxwidth','$pagepicnum'{$inadd_v}); ";
        if(!$dsql->ExecuteNoneQuery($query))
        {
            $gerr = $dsql->GetError();
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__archives` WHERE id='$arcID'");
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
            ShowMsg("把数据保存到数据库附加表 `{$addtable}` 时出错，请联系管理员！".$gerr,"javascript:;");
            exit();
        }
    }

    //增加积分
    $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET scores=scores+{$cfg_sendarc_scores} WHERE mid='".$cfg_ml->M_ID."' ; ");
    //更新统计
    countArchives($channelid);

    //生成HTML
    InsertTags($tags,$arcID);
    $artUrl = MakeArt($arcID,true);
    if($artUrl=='') $artUrl = $cfg_phpurl."/view.php?aid=$arcID";

    
    #api{{
    if(defined('UC_API') && @include_once DEDEROOT.'/api/uc.func.php')
    {
        //推送事件
        $feed['icon'] = 'thread';
        $feed['title_template'] = '<b>{username} 在网站发布了一篇图集</b>';
        $feed['title_data'] = array('username' => $cfg_ml->M_UserName);
        $feed['body_template'] = '<b>{subject}</b><br>{message}';
        $url = !strstr($artUrl,'http://') ? ($cfg_basehost.$artUrl) : $artUrl;
        $feed['body_data'] = array('subject' => "<a href=\"".$url."\">$title</a>", 'message' => cn_substr(strip_tags(preg_replace("/\[.+?\]/is", '', $description)), 150));        
        $feed['images'][] = array('url' => $cfg_basehost.'/images/scores.gif', 'link'=> $cfg_basehost);
        uc_feed_note($cfg_ml->M_LoginID,$feed);

        $row = $dsql->GetOne("SELECT `scores`,`userid` FROM `#@__member` WHERE `mid`='".$cfg_ml->M_ID."' AND `matt`<>10");
        uc_credit_note($row['userid'], $cfg_sendarc_scores);
    }
    #/aip}}

    //会员动态记录
    $cfg_ml->RecordFeeds('add', $title, $description, $arcID);
    
    ClearMyAddon($arcID, $title);
    
    //返回成功信息
    $msg = "
　　请选择你的后续操作：
    <a href='album_add.php?cid=$typeid'><u>继续发布图集</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>查看图集</u></a>
    &nbsp;&nbsp;
    <a href='album_edit.php?aid=".$arcID."&channelid=$channelid'><u>更改图集</u></a>
    &nbsp;&nbsp;
    <a href='content_list.php?channelid={$channelid}'><u>已发布图集管理</u></a>
    ";
    $wintitle = "成功发布图集！";
    $wecome_info = "图集管理::发布图集";
    $win = new OxWindow();
    $win->AddTitle("成功发布图集：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",false);
    $win->Display();
}