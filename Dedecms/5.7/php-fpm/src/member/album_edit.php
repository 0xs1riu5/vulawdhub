<?php
/**
 * 图集编辑
 * 
 * @version        $Id: album_edit.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
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
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 2;
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
$menutype = 'content';
if(empty($formhtml)) $formhtml = 0;

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
    //读取归档信息
    $arcQuery = "SELECT arc.*,ch.addtable,ch.fieldset,ch.arcsta
       FROM `#@__archives` arc LEFT JOIN `#@__channeltype` ch ON ch.id=arc.channel
       WHERE arc.id='$aid' AND arc.mid='".$cfg_ml->M_ID."'; ";
    $row = $dsql->GetOne($arcQuery);
    if(!is_array($row))
    {
        ShowMsg("读取文档信息出错!","-1");
        exit();
    }
    else if($row['arcrank']>=0)
    {
        $dtime = time();
        $maxtime = $cfg_mb_editday * 24 *3600;
        if($dtime - $row['senddate'] > $maxtime)
        {
            ShowMsg("这篇文档已经锁定，你不能再修改它！","-1");
            exit();
        }
    }
    $addRow = $dsql->GetOne("SELECT * FROM `{$row['addtable']}` WHERE aid='$aid'; ");
    $dtp = new DedeTagParse();
    $dtp->LoadSource($addRow['imgurls']);
    $abinfo = $dtp->GetTagByName('pagestyle');
    include(DEDEMEMBER."/templets/album_edit.htm");
    exit();
}
/*------------------------------
function _Save(){  }
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

    include(DEDEMEMBER.'/inc/archives_check_edit.php');
    $imgurls = "{dede:pagestyle maxwidth='$maxwidth' pagepicnum='$pagepicnum'
    ddmaxwidth='$ddmaxwidth' row='$prow' col='$pcol' value='$pagestyle'/}\r\n";
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

    //检查已上传或直接上传的图片
    for($i=1;$i<=120;$i++)
    {
        //含有图片的条件
        if(isset(${'imgurl'.$i}) || (isset($_FILES['imgfile'.$i]['tmp_name']) && is_uploaded_file($_FILES['imgfile'.$i]['tmp_name'])))
        {
            $iinfo = str_replace("'","`",stripslashes(${'imgmsg'.$i}));
            if(!is_uploaded_file($_FILES['imgfile'.$i]['tmp_name']))
            {
                $iurl = stripslashes(${'imgurl'.$i});

                //如果有旧图
                if(isset(${'imgurl'.$i}))
                {
                    $litpicname = $iurl;
                    $filename = $iurl;

                    //缩图
                    if($pagestyle > 2)
                    {
                        $litpicname = GetImageMapDD($filename,$ddmaxwidth);
                        if($litpicname != '')
                        {
                            SaveUploadInfo($title.' 小图',$litpicname,1);
                        }
                    }
                }
                else
                {
                    continue;
                }
            }
            else
            {
                $sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
                if(!in_array($_FILES['imgfile'.$i]['type'],$sparr))
                {
                    continue;
                }
                if(isset(${'imgurl'.$i}))
                {
                    $filename = ${'imgurl'.$i};
                }
                else
                {
                    $filename = '';
                }
                $filename = MemberUploads('imgfile'.$i,$filename,$cfg_ml->M_ID,'image','',0,0,false);
                if($filename!='')
                {
                    SaveUploadInfo($title,$filename,1);
                }
                $litpicname = $filename;

                //缩图
                if($pagestyle > 2)
                {
                    $litpicname = GetImageMapDD($filename,$ddmaxwidth);
                    if($litpicname != '')
                    {
                        SaveUploadInfo($title.' 小图',$litpicname,1);
                    }
                }
            }
            $imgfile = $cfg_basedir.$filename;
            if(is_file($imgfile))
            {
                $iurl = $filename;
                $info = '';
                $imginfos = @getimagesize($imgfile,$info);
                $imgurls .= "{dede:img ddimg='$litpicname' text='$iinfo' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
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
    $inadd_f = '';
    if(!empty($dede_addonfields))
    {
        $addonfields = explode(';',$dede_addonfields);
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
                ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$aid);
                $inadd_f .= ','.$vs[0]." ='".${$vs[0]}."' ";
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
	$description = HtmlReplace($description, -1);//2011.06.30 增加html过滤 （by:织梦的鱼）
    //处理图片文档的自定义属性
    if($litpic!='') $flag = 'p';

    //更新数据库的SQL语句
    //更新数据库的SQL语句
    $upQuery = "UPDATE `#@__archives` SET
             ismake='$ismake',
             arcrank='$arcrank',
             typeid='$typeid',
             title='$title',
             litpic='$litpic',
             description='$description',
             keywords='$keywords',
             mtype='$mtypesid',            
             flag='$flag'
        WHERE id='$aid' AND mid='$mid'; ";
    if(!$dsql->ExecuteNoneQuery($upQuery))
    {
        ShowMsg("把数据保存到数据库主表时出错，请联系管理员！".$dsql->GetError(),"-1");
        exit();
    }

    $isrm = 0;

    if($addtable!='')
    {
        $query = "UPDATE `$addtable`
      set typeid='$typeid',
      pagestyle='$pagestyle',
      maxwidth = '$maxwidth',
      ddmaxwidth = '$ddmaxwidth',
      pagepicnum = '$pagepicnum',
      imgurls='$imgurls',
      row='$prow',
      col='$pcol',
       userip='$userip',
      isrm='$isrm'{$inadd_f}
    WHERE aid='$aid'; ";
        if(!$dsql->ExecuteNoneQuery($query))
        {
            ShowMsg("更新附加表 `$addtable`  时出错，请联系管理员！".$dsql->GetError(),"javascript:;");
            exit();
        }
    }

    UpIndexKey($aid, $arcrank, $typeid, $sortrank, $tags);
    $artUrl = MakeArt($aid, TRUE);
    if($artUrl=='') $artUrl = $cfg_phpurl."/view.php?aid=$aid";

    //---------------------------------
    //返回成功信息
    //----------------------------------
    $msg = "　　请选择你的后续操作：
<a href='album_add.php?cid=$typeid'><u>发布新图集</u></a>
&nbsp;&nbsp;
<a href='archives_do.php?channelid=$channelid&aid=".$aid."&dopost=edit'><u>查看更改</u></a>
&nbsp;&nbsp;
<a href='$artUrl' target='_blank'><u>查看图集</u></a>
&nbsp;&nbsp;
<a href='content_list.php?channelid=$channelid'><u>管理图集</u></a> ";

    $wintitle = "成功更改图集！";
    $wecome_info = "图集管理::更改图集";
    $win = new OxWindow();
    $win->AddTitle("成功更改图集：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",false);
    $win->Display();
}