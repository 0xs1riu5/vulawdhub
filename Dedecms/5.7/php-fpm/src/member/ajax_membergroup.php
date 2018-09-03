<?php
/**
 * @version        $Id: ajax_membergroup.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
$mid = isset($mid) && is_numeric($mid) ? $mid : 0;
AjaxHead();

//显示分组
if($action == 'show')
{
    $sql = "SELECT * FROM #@__member_group WHERE mid={$cfg_ml->M_ID} ORDER BY id DESC";
    $dsql->Execute('me',$sql);
    echo "<select name='membergroup' id='m_{$mid}' style='width:100px'>";
    while($arr = $dsql->GetArray('me'))
    {
      echo "        <option value='{$arr['id']}'>{$arr['groupname']}</option>\r\n";
    }
    echo "</select>";
    echo '<button onclick="postMemberGroup(\''.$mid.'\')" class="bt3">提交</button> <button type="button" onclick="location.reload();" class="bt3">取消</button>';
}

//编辑分组
elseif($action == 'post')
{
    if(empty($membergroup)){
        echo "您还没有设置分组！";
        exit;
    }
    $sql = "UPDATE `#@__member_friends` SET `groupid`='{$membergroup}' WHERE `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}';";
    $dsql->ExecuteNoneQuery($sql);
    $row = $dsql->GetOne("SELECT groupname FROM #@__member_group WHERE mid = {$cfg_ml->M_ID} AND id={$membergroup}");
    echo "&nbsp;".$row['groupname']."&nbsp;&nbsp;<a href='#' onclick='EditMemberGroup($mid);return false;'>修改</a>";
}

//显示描述
elseif($action == 'desshow')
{
    $sql = "SELECT * FROM  #@__member_friends WHERE `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}'";
    $row = $dsql->getone($sql);
    echo '<input id="m_'.$mid.'" name="mdescription" value="'.$row['description'].'" class="intxt" style="width:100px;"/>';
    echo '<button onclick="postDescription(\''.$mid.'\')" class="bt3">提交</button> <button type="button" onclick="location.reload();" class="bt3">取消</button>';  
}

//编辑描述
elseif($action == 'despost')
{
    $sql = "UPDATE `#@__member_friends` SET `description`='{$mdescription}' WHERE `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}'";
    $dsql->ExecuteNoneQuery($sql);
    $row = $dsql->GetOne("SELECT description FROM #@__member_friends WHERE  `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}'");
    echo "&nbsp;".$row['description']."&nbsp;&nbsp;<a href='#' onclick='EditDescription($mid);return false;'>修改</a>";
}