<?php
/**
 * 单表模型文档编辑
 *
 * @version        $Id: archives_sg_edit.php 1 8:26 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_Edit,a_AccEdit,a_MyEdit');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");

if(empty($dopost)) $dopost = '';

if($dopost!='save')
{
    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    require_once(DEDEINC."/dedetag.class.php");
    ClearMyAddon();
    $aid = intval($aid);

    //读取归档信息
    $arcQuery = "SELECT ch.*,arc.* FROM `#@__arctiny` arc
    LEFT JOIN `#@__channeltype` ch on ch.id=arc.channel WHERE arc.id='$aid' ";

    $cInfos = $dsql->GetOne($arcQuery);
    if(!is_array($cInfos))
    {
        ShowMsg("读频道模型信息出错！","-1");
        exit();
    }

    $addtable = $cInfos['addtable'];
    $addRow = $dsql->GetOne("SELECT arc.*,ar.membername as rankname FROM `$addtable` arc LEFT JOIN `#@__arcrank` ar on ar.rank=arc.arcrank WHERE arc.aid='$aid'");
    $channelid = $cInfos['channel'];
    $tags = GetTags($aid);
    include DedeInclude('templets/archives_sg_edit.htm');
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
    if(!CheckChannel($typeid,$channelid))
    {
        ShowMsg("你所选择的栏目与当前模型不相符，请选择白色的选项！","-1");
        exit();
    }
    if(!TestPurview('a_Edit'))
    {
        if(TestPurview('a_AccEdit'))
        {
            CheckCatalog($typeid, "对不起，你没有操作栏目 {$typeid} 的文档权限！");
        }
        else
        {
            CheckArcAdmin($id,$cuserLogin->getUserID());
        }
    }
    //对保存的内容进行处理
    if(empty($flags)) $flag = '';
    else $flag = join(',', $flags);
    $title = cn_substrR($title, $cfg_title_maxlen);
    $isremote  = (empty($isremote)? 0  : $isremote);
    $serviterm=empty($serviterm)? "" : $serviterm;
    if(!TestPurview('a_Check,a_AccCheck,a_MyCheck')) $arcrank = -1;

    $adminid = $cuserLogin->getUserID();
    
    //处理上传的缩略图
    if(empty($ddisremote)) $ddisremote = 0;
    $litpic = GetDDImage('none', $picname, $ddisremote);
    
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
                if($vs[1]=='htmltext'||$vs[1]=='textdata') //HTML文本特殊处理
                {
                    ${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
                }else
                {
                    if(!isset(${$vs[0]}))
                    {
                        ${$vs[0]} = '';
                    }
                    ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$id);
                }
                $inadd_f .= ",`{$vs[0]}` = '".${$vs[0]}."'";
            }
        }
    }
    
    //处理图片文档的自定义属性
    if($litpic!='' && !preg_match("#p#", $flag))
    {
        $flag = ($flag=='' ? 'p' : $flag.',p');
    }

    $cts = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$channelid' ");
    $addtable = trim($cts['addtable']);
    if($addtable!='')
    {
        $iquery = "UPDATE `$addtable` SET typeid='$typeid',arcrank='$arcrank',title='$title',flag='$flag',litpic='$litpic'{$inadd_f} WHERE aid='$id' ";
        if(!$dsql->ExecuteNoneQuery($iquery))
        {
            ShowMsg("更新附加表 `$addtable`  时出错，请检查原因！","javascript:;");
            exit();
        }
    }

    //生成HTML
    UpIndexKey($id, $arcrank, $typeid, $sortrank, '');
    if($cfg_remote_site=='Y' && $isremote=="1")
    {    
        if($serviterm!="")
        {
            list($servurl, $servuser, $servpwd) = explode(',', $serviterm);
            $config = array( 'hostname' => $servurl, 'username' => $servuser, 
                                      'password' => $servpwd,'debug' => 'TRUE');
        } else {
            $config = array();
        }
        if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
    }

    $artUrl = MakeArt($id, TRUE, TRUE, $isremote);
    if($artUrl=='') $artUrl = $cfg_phpurl."/view.php?aid=$id";

    ClearMyAddon($id, $title);
    //返回成功信息
    $msg = "
    　　请选择你的后续操作：
    <a href='archives_sg_add.php?cid=$typeid'><u>发布新文档</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$id."&dopost=editArchives'><u>查看更改</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>查看文档</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&channelid={$channelid}&dopost=listArchives'><u>管理文档</u></a>
    &nbsp;&nbsp;
    <a href='catalog_main.php'><u>网站栏目管理</u></a>
    ";

    $wintitle = "成功更改文档！";
    $wecome_info = "文档管理::更改文档";
    $win = new OxWindow();
    $win->AddTitle("成功更改文档：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",false);
    $win->Display();
}