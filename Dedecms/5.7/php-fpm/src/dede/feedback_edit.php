<?php
/**
 * 评论编辑
 *
 * @version        $Id: feedback_edit.php 1 19:09 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Feedback');
$id = isset($id) && is_numeric($id) ? $id : 0;
$ENV_GOBACK_URL = empty($_COOKIE['ENV_GOBACK_URL'])? "feedback_main.php" : $_COOKIE['ENV_GOBACK_URL'];
if(empty($dopost)) $dopost = "";

if($dopost=='edit')
{
    $msg = cn_substrR($msg, 2500);
    $adminmsg = trim($adminmsg);
    if($adminmsg!="")
    {
        $adminmsg = cn_substrR($adminmsg, 1500);
        $adminmsg = str_replace("<","&lt;", $adminmsg);
        $adminmsg = str_replace(">","&gt;", $adminmsg);
        $adminmsg = str_replace("  ","&nbsp;&nbsp;", $adminmsg);
        $adminmsg = str_replace("\r\n","<br/>\n", $adminmsg);
        $msg = $msg."<br/>\n"."<font color=red>管理员回复： $adminmsg</font>\n";
    }
    $query = "UPDATE `#@__feedback` SET username='$username',msg='$msg',ischeck=1 WHERE id=$id";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg("成功回复一则留言！",$ENV_GOBACK_URL);
    exit();
}
$query = "SELECT * FROM `#@__feedback` WHERE id=$id";
$row = $dsql->GetOne($query);
include DedeInclude('templets/feedback_edit.htm');