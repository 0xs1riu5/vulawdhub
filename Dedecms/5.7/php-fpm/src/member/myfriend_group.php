<?php
/**
 * 我的收藏夹
 * 
 * @version        $Id: myfriend_group.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
$menutype = 'mydede';
$dopost = isset($dopost) ? trim($dopost) : '';
if($dopost == '')
{
    $query = "SELECT * FROM `#@__member_group` WHERE mid='{$cfg_ml->M_ID}'";
    $dsql->SetQuery($query);
    $dsql->Execute();
    while($row = $dsql->GetArray())
    {
        $mtypearr[] = $row;
    }
    $GLOBALS['mtypearr'] =empty($GLOBALS['mtypearr'] )? '' : $GLOBALS['mtypearr'] ;
    $tpl = new DedeTemplate();
    $tpl->LoadTemplate(DEDEMEMBER.'/templets/myfriend_group.htm');
    $tpl->Display();
    exit();
}
elseif ($dopost == 'add')
{   $mtypename = HtmlReplace(trim($groupname));
    $row = $dsql->GetOne("SELECT * FROM `#@__member_group` WHERE groupname LIKE '$groupname' AND mid='{$cfg_ml->M_ID}'");
    if(is_array($row))
    {
        ShowMsg('分组名称已经存在', '-1');
        exit();
    }
    else if(strlen($groupname)=="")
    {
        ShowMsg('分组名称不能为空', '-1');
        exit();
    }
    $query = "INSERT INTO `#@__member_group`(groupname, mid) VALUES ('$groupname', '$cfg_ml->M_ID'); ";
    if($dsql->ExecuteNoneQuery($query))
    {
        ShowMsg('增加分类成功', 'myfriend_group.php');
    }
    else
    {
        ShowMsg('增加分类失败', '-1');
    }
    exit();
}elseif ($dopost == 'save'){
    if(isset($mtypeidarr) && is_array($mtypeidarr))
    {
        $delids = '0';
        $mtypeidarr = array_filter($mtypeidarr, 'is_numeric');
        foreach($mtypeidarr as $delid)
        {
            $delids .= ','.$delid;
            unset($groupname[$delid]);
        }
        $query = "DELETE FROM `#@__member_group` WHERE id in ($delids) AND mid='$cfg_ml->M_ID'";
        $dsql->ExecNoneQuery($query);
        $sql="SELECT id FROM `#@__member_friends` WHERE groupid in ($delids) AND mid='$cfg_ml->M_ID'";
        $db->SetQuery($sql);
        $db->Execute();
        while($row = $db->GetArray())
        {
            $query2 = "UPDATE `#@__member_friends` SET groupid='1' WHERE id='{$row['id']}' AND mid='$cfg_ml->M_ID'";
            $dsql->ExecNoneQuery($query2);
        }
    }
    foreach ($groupname as $id => $name)
    {
        $name = HtmlReplace($name);
        $query = "UPDATE `#@__member_group` SET groupname='$name' WHERE id='$id' AND mid='$cfg_ml->M_ID'";
        $dsql->ExecuteNoneQuery($query);
    }
    ShowMsg('分组修改完成(删除分组中的会员会转移到默认分组中)','myfriend_group.php');
}