<?php
/**
 *  圈子分类设置
 *
 * @version        $Id: group_store.php 1 15:34 2011-1-21 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('group_Store');
require_once(DEDEINC.'/datalistcp.class.php');
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

$id = isset($id) && is_numeric($id) ? $id : 0;
$action = isset($action) ? trim($action) : '';

if($action=="add")
{
    $storename = cn_substrR(HtmlReplace($storename, 2),20);
    $tops = preg_replace("#[^0-9]#","",$tops);
    $orders = preg_replace("#[^0-9]#","",$orders);
    if($tops < 1)
    {
        $tops = 0;
    }
    if($orders < 1)
    {
        $orders = 0;
    }
    if(empty($storename))
    {
        $msg = "错误,分类名不能为空!";
    }
    else
    {
        $db->ExecuteNoneQuery("INSERT INTO #@__store_groups(storename,tops,orders) VALUES('".$storename."','".$tops."','".$orders."');");
        $msg = "成功添加分类";
    }
}
else if($action=="del"&&isset($id))
{
    $db->ExecuteNoneQuery("DELETE FROM #@__store_groups WHERE storeid='$id'");
    $msg = "删除分类：{$id} ！";
}
$btypes = array();
$db->SetQuery("SELECT * FROM #@__store_groups WHERE tops=0");
$db->Execute();
$options = '';
while($rs = $db->GetArray())
{
    array_push ($btypes,$rs);
}
foreach($btypes as $k=>$v)
{
    $options .= "<option value='".$v['storeid']."'>".$v['storename']."</option>\r\n";
}

/*
function LoadEdit();
*/

if($action=='editload')
{
    $row = $db->GetOne("Select * From #@__store_groups where storeid='$catid'");
    AjaxHead();
?>
<form name='editform' action='group_store.php' method='get'>
<input type='hidden' name='action' value='editsave' />
<input type='hidden' name='catid' value='<?php echo $catid; ?>' />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="90" height="28">栏目名称：</td>
<td width="101"><input name="storename" type="text" id="storename" value="<?php echo $row['storename']; ?>" /></td>
<td width="20" align="right" valign="top"></td>
</tr>
<tr>
<td height="28">隶属栏目：</td>
<td colspan="2">
<select name="tops" id="tops">
<option value="0">顶级栏目</option>
<?php
foreach($btypes as $k=>$v)
{
    if($row['tops']==$v['storeid'])
    {
        echo "<option value='".$v['storeid']."' selected>".$v['storename']."</option>\r\n";
    }
    else
    {
        echo "<option value='".$v['storeid']."'>".$v['storename']."</option>\r\n";
    }
}
?>
</select>
</td>
</tr>
<tr>
<td height="28">排序级别：</td>
<td colspan="2"><input name="orders" type="text" id="orders" size="5" value="<?php echo $row['orders']; ?>" />
（数值小靠前）</td>
</tr>
<tr>
<td height="43">&nbsp;</td>
<td colspan="2"><input type="submit" name="Submit" value="保存更改"  class="np coolbg" style="width:80px"/></td>
</tr>
</table>
</form>
<?php

exit();
}
else if($action=='editsave')
{
    $db->ExecuteNoneQuery("UPDATE #@__store_groups SET storename='$storename',tops='$tops',orders='$orders' WHERE storeid='$catid'");
    $msg = "成功修改栏目：{$catid} = {$storename} ！";
}
else if($action=='uprank')
{
    foreach($_POST as $rk=>$rv)
    {
        if(preg_match('#rank#i',$rk))
        {
            $catid = str_replace('rank_','',$rk);
            $db->ExecuteNoneQuery("UPDATE #@__store_groups SET orders='{$rv}' WHERE storeid='{$catid}'");
        }
    }
    $msg = "成功更改排序 ！";
}

$sql = "SELECT storeid,storename,tops,orders FROM #@__store_groups WHERE tops=0 ORDER BY orders ASC";

$dl = new DataListCP();
$dl->pageSize = 20;

//这两句的顺序不能更换
$dl->SetTemplate(DEDEADMIN."/templets/group_store.htm");      //载入模板
$dl->SetSource($sql);            //设定查询SQL
$dl->Display();                  //显示

?>