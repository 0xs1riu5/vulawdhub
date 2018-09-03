<?php
/**
 * 会员模型编辑
 *
 * @version        $Id: member_model_edit.php 1 11:20 2010年7月20日Z tianya $
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
$id = (empty($id) ? 0 : intval($id));

/*----------------
function __SaveEdit()
-----------------*/
if($dopost=="save")
{
    $state = isset($state) && is_numeric($state) ? $state : 1;
    $description = htmlspecialchars($description);
    $name = htmlspecialchars($name);
    $query = "UPDATE `#@__member_model` SET name = '$name', description = '$description', state='$state' WHERE id='$id' ";
    $dsql->ExecuteNoneQuery($query);
    //更新会员模型缓存
    UpDateMemberModCache();
    ShowMsg("成功更改一个会员模型！", "member_model_main.php");
    exit();
}
/*----------------
function __Disabled()
-----------------*/
else if($dopost=="disabled")
{
    @set_time_limit(0);
    CheckPurview('c_Del');
    $row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$id'");
    $statenum = ($row['state']==0)? 1 : 0;
    $statestr = ($row['state']==0)? '启用' : '禁用';
    //禁用模型
    $dsql->ExecuteNoneQuery("UPDATE #@__member_model SET state={$statenum} WHERE id='$id' ");
    //更新会员模型缓存
    UpDateMemberModCache();
    ShowMsg("成功{$statestr}一个会员模型！","member_model_main.php");
    exit();
}
/*----------------
function __Copy()
-----------------*/
else if($dopost=="copy")
{
    @set_time_limit(0);
    CheckPurview('c_Del');
    $row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$id'");
    $thisname = $row['name'];
    $thistable = $row['table'];
    $thisinfo = $row['info'];
    $row = $dsql->GetOne("SELECT id From #@__member_model ORDER BY id DESC LIMIT 0,1 ");
    if(is_array($row)) $newid = $row['id'] + 1;
    else $newid = 1;
    if(empty($job)) $job="";
    
    //确认提示
    if($job=="")
    {
        $wintitle = "会员模型管理-复制会员模型";
        $wecome_info = "<a href='member_model_main.php'>会员模型管理</a>::复制会员模型";
        $win = new OxWindow();
        $win->Init("member_model_edit.php","js/blank.js","POST");
        $win->AddHidden("job","yes");
        $win->AddHidden("dopost",$dopost);
        $win->AddHidden("id",$id);
        $win->AddTitle("你确实要复制 \"".$thisname."\" 这个会员模型？");
        $msg ="";
        $msg.="<table width='460' border='0' cellspacing='0' cellpadding='0'>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td width='170' height='24' align='center'>新模型ID：</td>\r\n";
    $msg.=" <td width='230'><input name='newid' type='text' id='newid' size='6' value='{$newid}'/></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>新模型名称：</td>\r\n";
    $msg.=" <td><input name='newname' type='text' id='newname' value='{$thisname}{$newid}' style='width:250px'/></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>模型数据表：</td>\r\n";
    $msg.=" <td><input name='newtable' type='text' id='newtable' value='{$thistable}{$newid}' style='width:250px'/></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>模型字段：</td>\r\n";
    $msg.=" <td><font color='red'>模型字段默认已经复制,请在编辑当前模型中查看</font></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>新模型描述：</td>\r\n";
    $msg.=" <td><label>\r\n";
    $msg.=" <textarea name=\"description\" id=\"description\" cols=\"45\" rows=\"5\" onClick=\"this.value=''\">新模型描述</textarea>\r\n";
    $msg.=" </label></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>模型状态：</td>\r\n";
    $msg.=" <td><input name='state' type='radio' id='copytemplet' value='1' class='np' checked='checked'/>\r\n";
    $msg.=" 启用\r\n";
    $msg.=" &nbsp;\r\n";
    $msg.=" <input name='state' type='radio' id='copytemplet' class='np' value='0'/>\r\n";
    $msg.=" 禁用</td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="</table>";
    $win->AddMsgItem("<div style='padding:20px;line-height:300%'>$msg</div>");
        $winform = $win->GetWindow("ok");
        $win->Display();
        exit();
    }

    //操作
    else if($job=="yes")
    {
        if(preg_match("#[^0-9-]#", $newid) || empty($newid))
        {
            ShowMsg("<font color=red>'会员模型ID'</font>必须为数字！","-1");
            exit();
        }
        if($newtable=="")
        {
            ShowMsg("表名不能为空！", "-1");
            exit();
        }
        $state = isset($state) && is_numeric($state) ? $state : 0;
        $newname = htmlspecialchars($newname);
        $row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$newid' OR `table` LIKE '$newtable' OR name LIKE '$newname' ");
        if(is_array($row))
        {
            ShowMsg("可能会员模型的‘ID’、‘名称’在数据库中已存在，不能重复使用！","-1");
            exit();
        }
        //拷贝数据表
        if(!$dsql->IsTable($newtable))
        {
            $dsql->Execute('me',"SHOW CREATE TABLE {$dsql->dbName}.{$thistable}");
            $row = $dsql->GetArray('me', MYSQL_BOTH);
            $tableStruct = $row[1];
            $tb = str_replace('#@__', $cfg_dbprefix, $thistable);
            $tableStruct = preg_replace("/CREATE TABLE `$thistable`/iU","CREATE TABLE `$newtable`",$tableStruct);
            $dsql->ExecuteNoneQuery($tableStruct);
        }
        $query = "INSERT INTO #@__member_model (`id`, `name`, `table`, `description`, `issystem`, `state`, `info`) VALUES ('$newid', '$newname', '$newtable', '$description', 0, '$state','$thisinfo')";
        $rs = $dsql->ExecuteNoneQuery($query);
        if($rs)
        {
            UpDateMemberModCache();
            ShowMsg("成功复制会员模型，现转到详细参数页... ","member_model_edit.php?id={$newid}&dopost=edit");
            exit();
        }
        else
        {
            $errv = $dsql->GetError();
            ShowMsg("系统出错，请把错误代码发送到官方论坛，以检查原因！<br /> 错误代码：member_model_edit.php?dopost=copy $errv","javascript:;");
            exit();
        }
        //echo "Do it!";exit();
    }
}
/*----------------
function __Delete()
-----------------*/
else if($dopost=="delete")
{
    @set_time_limit(0);
    CheckPurview('c_Del');
    $row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$id'");
    if($row['issystem'] == 1)
    {
        ShowMsg("系统模型,禁止删除!","-1");
        exit();
    }
    if(empty($job)) $job="";

    //确认提示
    if($job=="")
    {
        $wintitle = "会员模型管理-删除会员模型";
        $wecome_info = "<a href='member_model_main.php'>会员模型管理</a>::删除会员模型";
        $win = new OxWindow();
        $win->Init("member_model_edit.php","js/blank.js","POST");
        $win->AddHidden("job","yes");
        $win->AddHidden("dopost",$dopost);
        $win->AddHidden("id",$id);
        $win->AddTitle("！将删除所有与该会员模型相关的文件和数据<br />你确实要删除 \"".$row['name']."\" 这个会员模型？");
        $winform = $win->GetWindow("ok");
        $win->Display();
        exit();
    }

    //操作
    else if($job=="yes")
    {
        $row = $dsql->GetOne("SELECT `table` FROM `#@__member_model` WHERE id='$id'",MYSQL_ASSOC);
        if(!is_array($row))
        {
            ShowMsg("你所指定的会员模型信息不存在!","-1");
            exit();
        }

        //删除表
        $dsql->ExecuteNoneQuery("DROP TABLE IF EXISTS `{$row['table']}`;");

        //删除频道配置信息
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__member_model` WHERE id='$id'");
        UpDateMemberModCache();
        ShowMsg("成功删除一个会员模型！","member_model_main.php");
        exit();
    }
}

/*----------------
function edit()
-----------------*/
$row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$id'");
include DEDEADMIN."/templets/member_model_edit.htm";