<?php
/**
 * 编辑自定义表单
 *
 * @version        $Id: diy_add.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_Edit');
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/oxwindow.class.php");

if(empty($dopost)) $dopost="";
$diyid = (empty($diyid) ? 0 : intval($diyid));

/*----------------
function __SaveEdit()
-----------------*/
if($dopost=="save")
{
    $public = isset($public) && is_numeric($public) ? $public : 0;
    $name = htmlspecialchars($name);
    $query = "UPDATE `#@__diyforms` SET name = '$name', listtemplate='$listtemplate', viewtemplate='$viewtemplate', posttemplate='$posttemplate', public='$public' WHERE diyid='$diyid' ";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg("成功更改一个自定义表单！","diy_main.php");
    exit();
}
/*----------------
function __Delete()
-----------------*/
else if($dopost=="delete")
{
    @set_time_limit(0);
    CheckPurview('c_Del');
    $row = $dsql->GetOne("SELECT * FROM #@__diyforms WHERE diyid='$diyid'");
    if(empty($job)) $job = "";

    //确认提示
    if($job=="")
    {
        $wintitle = "自定义表单管理-删除自定义表单";
        $wecome_info = "<a href='diy_main.php'>自定义表单管理</a>::删除自定义表单";
        $win = new OxWindow();
        $win->Init("diy_edit.php", "js/blank.js", "POST");
        $win->AddHidden("job", "yes");
        $win->AddHidden("dopost", $dopost);
        $win->AddHidden("diyid", $diyid);
        $win->AddTitle("！将删除所有与该自定义表单相关的文件和数据<br />你确实要删除 \"".$row['name']."\" 这个自定义表单？");
        $winform = $win->GetWindow("ok");
        $win->Display();
        exit();
    }

    //操作
    else if($job=="yes")
    {
        $row = $dsql->GetOne("SELECT `table` FROM `#@__diyforms` WHERE diyid='$diyid'",MYSQL_ASSOC);
        if(!is_array($row))
        {
            ShowMsg("你所指定的自定义表单信息不存在!","-1");
            exit();
        }

        //删除表
        $dsql->ExecuteNoneQuery("DROP TABLE IF EXISTS `{$row['table']}`;");

        //删除频道配置信息
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__diyforms` WHERE diyid='$diyid'");
        ShowMsg("成功删除一个自定义表单！","diy_main.php");
        exit();
    }
}

/*----------------
function edit()
-----------------*/
$row = $dsql->GetOne("Select * From #@__diyforms where diyid='$diyid'");
include DEDEADMIN."/templets/diy_edit.htm";