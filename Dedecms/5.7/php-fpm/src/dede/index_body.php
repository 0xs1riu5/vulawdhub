<?php
/**
 * 管理后台首页主体
 *
 * @version        $Id: index_body.php 1 11:06 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__).'/config.php');
require(DEDEINC.'/image.func.php');
require(DEDEINC.'/dedetag.class.php');
$defaultIcoFile = DEDEDATA.'/admin/quickmenu.txt';
$myIcoFile = DEDEDATA.'/admin/quickmenu-'.$cuserLogin->getUserID().'.txt';
if(!file_exists($myIcoFile)) $myIcoFile = $defaultIcoFile;

//默认主页
if(empty($dopost))
{
    require(DEDEINC.'/inc/inc_fun_funAdmin.php');
    $verLockFile = DEDEDATA.'/admin/ver.txt';
    $fp = fopen($verLockFile,'r');
    $upTime = trim(fread($fp,64));
    fclose($fp);
    $oktime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);
    $offUrl = SpGetNewInfo();
    $dedecmsidc = DEDEDATA.'/admin/idc.txt';
    $fp = fopen($dedecmsidc,'r');
    $dedeIDC = fread($fp,filesize($dedecmsidc));
    fclose($fp);
    $myMoveFile = DEDEDATA.'/admin/move-'.$cuserLogin->getUserID().'.txt';
    if(file_exists($myMoveFile))
    {
        $fp = fopen($myMoveFile,'r');
        $movedata= fread($fp,filesize($myMoveFile));
        $movedata = unserialize($movedata);
        $column1 = array();
        $column2 = array();
        foreach ($movedata['items'] as $key => $value) {
            if($value['column'] == 'column1') $column1 = $column1 + array($key => $value['id']);
            else if($value['column'] == 'column2') $column2 = $column2 + array($key => $value['id']);
        }
        include DedeInclude('templets/index_body_move.htm');
    }else{  
        include DedeInclude('templets/index_body.htm');
    }
    exit();
}
/*-----------------------
增加新项
function _AddNew() {   }
-------------------------*/
else if($dopost=='addnew')
{
    if(empty($link) || empty($title))
    {
        ShowMsg("链接网址或标题不能为空！","-1");
        exit();
    }

    $fp = fopen($myIcoFile,'r');
    $oldct = trim(fread($fp, filesize($myIcoFile)));
    fclose($fp);

    $link = preg_replace("#['\"]#", '`', $link);
    $title = preg_replace("#['\"]#", '`', $title);
    $ico = preg_replace("#['\"]#", '`', $ico);
    $oldct .= "\r\n<menu:item ico=\"{$ico}\" link=\"{$link}\" title=\"{$title}\" />";

    $myIcoFileTrue = DEDEDATA.'/admin/quickmenu-'.$cuserLogin->getUserID().'.txt';
    $fp = fopen($myIcoFileTrue, 'w');
    fwrite($fp, $oldct);
    fclose($fp);

    ShowMsg("成功增加一个项目！","index_body.php?".time());
    exit();
}
/*---------------------------
保存修改的项
function _EditSave() {   }
----------------------------*/
else if($dopost=='editsave')
{
    $quickmenu = stripslashes($quickmenu);

    $myIcoFileTrue = DEDEDATA.'/admin/quickmenu-'.$cuserLogin->getUserID().'.txt';
    $fp = fopen($myIcoFileTrue,'w');
    fwrite($fp,$quickmenu);
    fclose($fp);

    ShowMsg("成功修改快捷操作项目！","index_body.php?".time());
    exit();
}
/*---------------------------
保存修改的项
function _EditSave() {   }
----------------------------*/
else if($dopost=='movesave')
{   
    $movedata = str_replace('\\',"",$sortorder);
    $movedata = json_decode($movedata,TRUE);
    $movedata = serialize($movedata);
    $myIcoFileTrue = DEDEDATA.'/admin/move-'.$cuserLogin->getUserID().'.txt';
    $fp = fopen($myIcoFileTrue,'w');
    fwrite($fp,$movedata);
    fclose($fp);
}
/*-----------------------------
显示修改表单
function _EditShow() {   }
-----------------------------*/
else if($dopost=='editshow')
{
    $fp = fopen($myIcoFile,'r');
    $oldct = trim(fread($fp,filesize($myIcoFile)));
    fclose($fp);
?>
<form name='editform' action='index_body.php' method='post'>
<input type='hidden' name='dopost' value='editsave' />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td height='28' background="images/tbg.gif">
         <div style='float:left'><b>修改快捷操作项</b></div>
      <div style='float:right;padding:3px 10px 0 0;'>
             <a href="javascript:CloseTab('editTab')"><img src="images/close.gif" width="12" height="12" border="0" /></a>
      </div>
     </td>
   </tr>
      <tr><td style="height:6px;font-size:1px;border-top:1px solid #8DA659">&nbsp;</td></tr>
   <tr>
     <td>
         按原格式修改/增加XML项。
     </td>
   </tr>
   <tr>
     <td align='center'>
         <textarea name="quickmenu" rows="10" cols="50" style="width:94%;height:220px"><?php echo $oldct; ?></textarea>
     </td>
   </tr>
   <tr>
     <td height="45" align="center">
         <input type="submit" name="Submit" value="保存项目" class="np coolbg" style="width:80px;cursor:pointer" />
         &nbsp;
         <input type="reset" name="reset" value="重设" class="np coolbg" style="width:50px;cursor:pointer" />
     </td>
   </tr>
  </table>
</form>
<?php
exit();
}
/*---------------------------------
载入右边内容
function _getRightSide() {   }
---------------------------------*/
else if($dopost=='getRightSide')
{
    $query = " SELECT COUNT(*) AS dd FROM `#@__member` ";
    $row1 = $dsql->GetOne($query);
    $query = " SELECT COUNT(*) AS dd FROM `#@__feedback` ";
    $row2 = $dsql->GetOne($query);
    
    $chArrNames = array();
    $query = "SELECT id, typename FROM `#@__channeltype` ";
    $dsql->Execute('c', $query);
    while($row = $dsql->GetArray('c'))
    {
        $chArrNames[$row['id']] = $row['typename'];
    }
    
    $query = "SELECT COUNT(channel) AS dd, channel FROM `#@__arctiny` GROUP BY channel ";
    $allArc = 0;
    $chArr = array();
    $dsql->Execute('a', $query);
    while($row = $dsql->GetArray('a'))
    {
        $allArc += $row['dd'];
        $row['typename'] = $chArrNames[$row['channel']];
        $chArr[] = $row;
    }
?>
    <table width="100%" class="dboxtable">
    <tr>
        <td width='50%' class='nline'  style="text-align:left"> 会员数： </td>
        <td class='nline' style="text-align:left"> <?php echo $row1['dd']; ?> </td>
    </tr>
    <tr>
        <td class='nline' style="text-align:left"> 文档数： </td>
        <td class='nline' style="text-align:left"> <?php echo $allArc; ?> </td>
    </tr>
    <?php
    foreach($chArr as $row)
    {
    ?>
    <tr>
        <td class='nline' style="text-align:left"> <?php echo $row['typename']; ?>： </td>
        <td class='nline' style="text-align:left"> <?php echo $row['dd']; ?>&nbsp; </td>
    </tr>
    <?php
    }
    ?>
    <tr>
        <td style="text-align:left"> 评论数： </td>
        <td style="text-align:left"> <?php echo $row2['dd']; ?> </td>
    </tr>
    </table>
<?php
exit();
} else if ($dopost=='getRightSideNews')
{
    $query = "SELECT arc.id, arc.arcrank, arc.title, arc.channel, ch.editcon  FROM `#@__archives` arc
            LEFT JOIN `#@__channeltype` ch ON ch.id = arc.channel
             WHERE arc.arcrank<>-2 ORDER BY arc.id DESC LIMIT 0, 6 ";
    $arcArr = array();
    $dsql->Execute('m', $query);
    while($row = $dsql->GetArray('m'))
    {
        $arcArr[] = $row;
    }
    AjaxHead();
?>
    <table width="100%" class="dboxtable">
    <?php
    foreach($arcArr as $row)
    {
        if(trim($row['editcon'])=='') {
            $row['editcon'] = 'archives_edit.php';
        }
        $linkstr = "·<a href='{$row['editcon']}?aid={$row['id']}&channelid={$row['channel']}'>{$row['title']}</a>";
        if($row['arcrank']==-1) $linkstr .= "<font color='red'>(未审核)</font>";
    ?>
    <tr>
        <td class='nline'>
            <?php echo $linkstr; ?>
        </td>
    </tr>
    <?php
    }
    ?>
    </table>
<?php
exit;
} else if ($dopost=='showauth')
{
    include('templets/index_body_showauth.htm');
    exit;
} else if ($dopost=='showad')
{
    include('templets/index_body_showad.htm');
    exit;
}


?>
       
    

