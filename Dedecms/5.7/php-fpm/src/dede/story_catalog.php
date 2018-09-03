<?php
/**
 * @version        $Id: story_catalog.php 1 9:02 2010年9月25日Z 蓝色随想 $
 * @package        DedeCMS.Module.Book
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__). "/config.php");
CheckPurview('story_Catalog');
if(!isset($action)) $action = '';

if(!isset($stypes)) $stypes = '';


//统计图书数量
function TjBookNum($cid,&$dsql)
{
    $row = $dsql->GetOne("SELECT count(bid) AS dd FROM #@__story_books WHERE catid='$cid' OR bcatid='$cid' ");
    return $row['dd'];
}

//增加栏目
/*
function SaveNew();
*/
if($action=='add')
{
    $inQuery = "INSERT INTO #@__story_catalog(classname,pid,rank,listrule,viewrule,booktype,keywords,description)
    VALUES('$classname','$pid','$rank','','','$booktype','$keywords','$description')";
    $rs = $dsql->ExecuteNoneQuery($inQuery);
    if($rs)
    {
        $msg = "成功增加一个栏目：{$classname} ！";
    }
    else
    {
        $msg = "增加栏目时失败：{$classname} ！";
    }
}

//保存修改
/*
function SaveEdit();
*/
else if($action=='editsave')
{
    $inQuery = "UPDATE #@__story_catalog SET
             classname='$classname',pid='$pid',rank='$rank',booktype='$booktype',
             keywords='$keywords',description='$description'
          WHERE id='$catid' ";
    $dsql->ExecuteNoneQuery($inQuery);
    $msg = "成功修改栏目：{$catid} = {$classname} ！";
    if(isset($ranks[$catid]))
    {
        $ranks[$catid] = $rank;
    }
    if(isset($btypes[$catid]))
    {
        $btypes[$catid] = $classname;
    }
    else
    {
        if(is_array($stypes))
        {
            foreach($stypes as $kk=>$vv)
            {
                if(isset($vv[$catid]))
                {
                    $stypes[$kk][$catid] = $classname;
                    break;
                }
            }
        }
    }
}

//删除栏目
/*---------------------
function DelCatalog()
-----------------------*/
else if($action=='del')
{
    $dsql->SetQuery("SELECT id FROM #@__story_catalog WHERE pid='{$catid}' ");
    $dsql->Execute();
    $ids = $catid;
    while($row = $dsql->GetArray())
    {
        $ids .= ','.$row['id'];
    }
    $dsql->ExecuteNoneQuery("DELETE FROM #@__story_books WHERE catid in ($ids) ");
    $dsql->ExecuteNoneQuery("DELETE FROM #@__story_chapter WHERE catid in ($ids) ");
    $dsql->ExecuteNoneQuery("DELETE FROM #@__story_content WHERE catid in ($ids) ");
    $dsql->ExecuteNoneQuery("DELETE FROM #@__story_catalog WHERE id in ($ids) ");
    $msg = "删除栏目：{$catid} ！OK";
}

//更新排序
/*---------------------
function UpRanks();
-----------------------*/
else if($action=='uprank')
{
    foreach($_POST as $rk=>$rv)
    {
        if(ereg('rank',$rk))
        {
            $catid = str_replace('rank_','',$rk);
            $dsql->ExecuteNoneQuery("UPDATE #@__story_catalog SET rank='{$rv}' WHERE id='$catid' ");
            $ranks[$catid] = $rv;
        }
    }
    ShowMsg("成功更新排序！","story_catalog.php");
    exit();
}

//读取所有栏目
$dsql->SetQuery("SELECT id,classname,pid,rank FROM #@__story_catalog ORDER BY rank ASC");
$dsql->Execute();
$ranks = Array();
$btypes = Array();
$stypes = Array();
while($row = $dsql->GetArray())
{
    if($row['pid']==0)
    {
        $btypes[$row['id']] = $row['classname'];
    }
    else
    {
        $stypes[$row['pid']][$row['id']] = $row['classname'];
    }
    $ranks[$row['id']] = $row['rank'];
}
$lastid = $row['id'];
$msg = '';

//载入栏目（用于修改，Ajax模式载入）
/*
function LoadEdit();
*/
if($action=='editload')
{
    $row = $dsql->GetOne("SELECT * FROM #@__story_catalog WHERE id='$catid'");
    AjaxHead();
?>
<form name='editform' action='story_catalog.php' method='get'>
<input type='hidden' name='action' value='editsave' />
<input type='hidden' name='catid' value='<?php echo $catid; ?>' />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td width="90" height="28">栏目名称：</td>
     <td width="101"><input name="classname" type="text" id="classname" value="<?php echo $row['classname']; ?>" /></td>
     <td width="20" align="right" valign="top"><a href="javascript:CloseEditCatalog()"><img src="images/close.gif" width="12" height="12" border="0" /></a></td>
   </tr>
   <tr>
     <td height="28">隶属栏目：</td>
     <td colspan="2">
     <select name="pid" id="pid">
       <option value="0">顶级栏目</option>
       <?php
       foreach($btypes as $k=>$v)
       {
           if($row['pid']==$k)
           {
               echo "<option value='$k' selected>{$v}</option>\r\n";
           }
           elseif($v != $row['classname'])
           {
               echo "<option value='$k'>{$v}</option>\r\n";
           }
       }
       ?>
     </select>
     </td>
   </tr>
   <tr>
     <td height="28">排序级别：</td>
     <td colspan="2"><input name="rank" type="text" id="rank" size="5" value="<?php echo $row['rank']; ?>" />
       （数值小靠前）</td>
   </tr>
   <tr>
     <td height="28">连载类型：</td>
     <td colspan="2">
         <input name="booktype" type="radio"  value="0"<?php if($row['booktype']==0) echo " checked='checked'";?> />
       小说
      <input type="radio" name="booktype" value="1" <?php if($row['booktype']==1) echo " checked='checked'";?> />
     漫画
     </td>
   </tr>
   <tr>
     <td height="28">关键字：</td>
     <td colspan="2"><input name="keywords" type="text" id="keywords" value="<?php echo $row['keywords']; ?>" /></td>
   </tr>
   <tr>
     <td>摘　要：</td>
     <td colspan="2">
         <textarea name="description" id="description" style="width:180px;height:45px"><?php echo $row['description']; ?></textarea>
     </td>
   </tr>
   <tr>
     <td height="43">&nbsp;</td>
     <td colspan="2"><input type="submit" name="Submit" value="保存更改" style="width:80px"/></td>
   </tr>
</table>
</form>
<?php
exit();
}
require_once(dirname(__FILE__). "/templets/story_catalog.htm");
