<?php
/**
 * 导出采集规则
 * 统一转换为unicode编码然后再base64加密
 *
 * @version        $Id: co_export_corule.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('co_EditNote');
require_once(DEDEINC."/oxwindow.class.php");
$nid = ereg_replace('[^0-9]','',$nid);
$row = $dsql->GetOne("Select * From `#@__co_note` where nid='$nid'");
$noteconfig = "{dede:listconfig}\r\n".$row['listconfig']."\r\n{/dede:listconfig}\r\n\r\n";
$noteconfig .= "{dede:itemconfig}\r\n".$row['itemconfig']."\r\n{/dede:itemconfig}";
if(empty($extype) || $extype=='base64')
{
    $noteconfig = "BASE64:".base64_encode($noteconfig).":END";
    $exmsg =  " &nbsp; <a href='co_export_corule.php?nid={$nid}&extype=text'>【导出为普通格式】</a> ";
}
else
{
    $exmsg =  " &nbsp; <a href='co_export_corule.php?nid={$nid}&extype=base64'>【导出为Base64格式】</a> ";
}
$wintitle = "导出采集规则";
$wecome_info = "<a href='co_main.php'><u>采集节点管理</u></a>::导出采集规则 $exmsg";
$win = new OxWindow();
$win->Init();
$win->AddTitle("以下为规则 [{$row['notename']}] 的文本配置，你可以共享给你的朋友：");
$winform = $win->GetWindow("hand","<textarea name='config' style='width:100%;height:450px;word-wrap: break-word;word-break:break-all;'>".$noteconfig."</textarea>");
$win->Display();

?>