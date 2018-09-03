<?php
/**
 * 单表模型文档发布
 *
 * @version        $Id: archives_sg_add.php 1 8:26 2010年7月12日Z tianya $
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

if($dopost!='save')
{
    require_once(DEDEINC."/dedetag.class.php");
    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    ClearMyAddon();
    $channelid = empty($channelid) ? 0 : intval($channelid);
    $cid = empty($cid) ? 0 : intval($cid);

    //获得频道模型ID
    if($cid > 0 && $channelid == 0)
    {
        $row = $dsql->GetOne("SELECT channeltype FROM `#@__arctype` WHERE id='$cid'; ");
        $channelid = $row['channeltype'];
    }
    else
    {
        if($channelid==0)
        {
            ShowMsg("无法识别模型信息，因此无法操作！","-1");
            exit();
        }
    }

    //获得频道模型信息
    $cInfos = $dsql->GetOne(" SELECT * FROM  `#@__channeltype` WHERE id='$channelid' ");
    $channelid = $cInfos['id'];
    include DedeInclude("templets/archives_sg_add.htm");
    exit();
}
/*--------------------------------
function __save(){  }
-------------------------------*/
else if($dopost=='save')
{
    require_once(DEDEINC.'/image.func.php');
    require_once(DEDEINC.'/oxwindow.class.php');

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
    if(empty($flags)) $flag = '';
    else $flag = join(',', $flags);
    $senddate = time();
    $title = cn_substrR($title,$cfg_title_maxlen);
    $isremote  = (empty($isremote)? 0  : $isremote);
    $serviterm=empty($serviterm)? "" : $serviterm;
    if(!TestPurview('a_Check,a_AccCheck,a_MyCheck')) $arcrank = -1;

    $adminid = $cuserLogin->getUserID();
    $userip = GetIP();

    if(empty($ddisremote)) $ddisremote = 0;

    $litpic = GetDDImage('none', $picname, $ddisremote);

    //生成文档ID
    $arcID = GetIndexKey($arcrank, $typeid, $senddate, $channelid, $senddate, $adminid);

    if(empty($arcID))
    {
        ShowMsg("无法获得主键，因此无法进行后续操作！","-1");
        exit();
    }

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
                if($vs[1]=='htmltext'||$vs[1]=='textdata') //HTML文本特殊处理
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

    //保存到附加表
    $cts = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$channelid' ");
    $addtable = trim($cts['addtable']);
    if(!empty($addtable))
    {
        $query = "INSERT INTO `{$addtable}`(aid,typeid,channel,arcrank,mid,click,title,senddate,flag,litpic,userip{$inadd_f})
                       VALUES('$arcID','$typeid','$channelid','$arcrank','$adminid','0','$title','$senddate','$flag','$litpic','$userip'{$inadd_v})";
        if(!$dsql->ExecuteNoneQuery($query))
        {
            $gerr = $dsql->GetError();
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$arcID'");
            ShowMsg("把数据保存到数据库附加表 `{$addtable}` 时出错，请把相关信息提交给DedeCms官方。".str_replace('"','',$gerr),"javascript:;");
            exit();
        }
    }

    //生成HTML
    if($cfg_remote_site=='Y' && $isremote=="1")
    {    
        if($serviterm!="")
        {
            list($servurl, $servuser, $servpwd) = explode(',', $serviterm);
            $config=array( 'hostname' => $servurl, 'username' => $servuser, 'password' => $servpwd,'debug' => 'TRUE');
        } else {
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
    <a href='archives_sg_add.php?cid=$typeid'><u>继续发布文档</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>查看文档</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$arcID."&dopost=editArchives'><u>更改文档</u></a>
    &nbsp;&nbsp;
    <a href='content_sg_list.php?cid=$typeid&channelid={$channelid}&dopost=listArchives'><u>已发布文档管理</u></a>
    &nbsp;&nbsp;
    <a href='catalog_main.php'><u>网站栏目管理</u></a>
    ";

    $wintitle = "成功发布文档！";
    $wecome_info = "文档管理::发布文档";
    $win = new OxWindow();
    $win->AddTitle("成功发布文档：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",false);
    $win->Display();
}