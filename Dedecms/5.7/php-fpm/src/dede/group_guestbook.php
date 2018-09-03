<?php
/**圈子留言管理
 *
 * @version        $Id: group_guestbook.php 1 15:34 2011-1-21 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC.'/datalistcp.class.php');
CheckPurview('group_Edit');

$gid = isset($gid) && is_numeric($gid) ? $gid : 0;
$id = isset($id) && is_numeric($id) ? $id : 0;
$action = isset($action) ? trim($action) : '';

$keyword = isset($keyword) ? trim($keyword) : '';
$keyword = stripslashes($keyword);
$keyword = preg_replace("#[\"\r\n\t\*\?\(\)\$%']#", " ", trim($keyword));
$keyword = addslashes($keyword);

$username = isset($username) ? trim($username) : '';
$username = stripslashes($username);
$username = preg_replace("#[\"\r\n\t\*\?\(\)\$%']#", " ", trim($username));
$username = addslashes($username);

if($gid < 1)
{
    ShowMsg("含有非法操作!.","-1");
    exit();
}

if($action=="del")
{
    if($id > 0)
    {
        $db->ExecuteNoneQuery("DELETE FROM #@__group_guestbook WHERE bid='$id'");
    }
}
else if($action=="save")
{
    if($id > 0)
    {
        $row = $db->GetOne("SELECT * FROM #@__group_guestbook WHERE bid='$id'");
        if(empty($message))
        {
            $message = $row['message'];
        }
        if(empty($title))
        {
            $title = $row['title'];
        }
        $db->ExecuteNoneQuery("UPDATE #@__group_guestbook SET message='".$message."',title='".$title."' WHERE bid='$id'");
    }
}
else if($action=="edit")
{
    $row = $db->GetOne("SELECT * FROM #@__group_guestbook WHERE bid='$id'");
    $title = $row['title'];
    $message = $row['message'];
}


//列表加载模板
$wheresql = "WHERE gid='{$gid}'";
if(!empty($keyword))
{
    $wheresql .= " AND    (title like '%".$keyword."%' OR message like '%".$keyword."%')";
}
if(!empty($username))
{
    $wheresql .= " AND uname like '%".$username."%'";
}
$sql = "SELECT * FROM #@__group_guestbook $wheresql ORDER BY stime DESC";

$dl = new DataListCP();
$dl->pageSize = 20;
$dl->SetParameter("keyword",$keyword);
$dl->SetParameter("username",$username);
$dl->SetParameter("gid",$gid);

//这两句的顺序不能更换
$dl->SetTemplate(DEDEADMIN."/templets/group_guestbook.htm");      //载入模板
$dl->SetSource($sql);            //设定查询SQL
$dl->Display();                  //显示

?>