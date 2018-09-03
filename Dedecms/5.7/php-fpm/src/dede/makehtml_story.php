<?php

/**
 * Enter description here...
 *
 * @author Administrator
 * @package defaultPackage
 * @rcsfile 	$RCSfile: makehtml_story.php,v $
 * @revision 	$Revision: 1.1 $
 * @date 	$Date: 2008/12/11 02:09:55 $
 */

require_once(dirname(__FILE__)."/config.php");
require_once(DEDEROOT.'/book/include/story.view.class.php');
CheckPurview('sys_MakeHtml');
if(!isset($action)) $action = '';
if(empty($startid)) $startid = 0;

//更新HTML操作
if($action=='make')
{
	if(empty($start)) $start = 0;
	$addquery = " bid>='$startid' ";
	if(!empty($endid) && $endid>$startid ) $addquery .= " And bid<='$endid' ";
	if(!empty($catid)) $addquery .= " And (catid='$catid' Or bcatid='$catid') ";
	if(empty($makenum)) $makenum = 50;
	$dsql->SetQuery("Select SQL_CALC_FOUND_ROWS bid From #@__story_books where $addquery limit $start,$makenum ");
	$dsql->Execute();
	$n = 0;
	$row = $dsql->GetOne("SELECT FOUND_ROWS() as dd ");
	$limitrow = $row['dd'];
	while($row = $dsql->GetObject()){
		$start++;
		$bv = new BookView($row->bid,'book');
		$artUrl = $bv->MakeHtml(false);
		//echo "更新: <a href='$artUrl' target='_blank'>{$bv->Fields['bookname']}</a> OK！<br />\r\n";
		//echo $row->bid." - ";
	}
	if($start>=$limitrow){
		ShowMsg("完成所有HTML的更新！","javascript:;");
	}
	else{
		$hasMake = $limitrow - $start;
		if($limitrow>0) $proportion = 100 - ceil(($hasMake / $limitrow) * 100);
		ShowMsg("已更新至：{$proportion}% 继续更新其它内容...","makehtml_story.php?start={$start}&action=make&startid={$startid}&endid={$endid}&catid={$catid}&makenum={$makenum}");
	}
	exit();
}
//读取所有栏目
$dsql->SetQuery("Select id,classname,pid,rank,booktype From #@__story_catalog order by rank asc");
$dsql->Execute();
$ranks = Array();
$btypes = Array();
$stypes = Array();
$booktypes = Array();
while($row = $dsql->GetArray()){
	if($row['pid']==0) $btypes[$row['id']] = $row['classname'];
	else $stypes[$row['pid']][$row['id']] = $row['classname'];
	$ranks[$row['id']] = $row['rank'];
	if($row['booktype']=='0') $booktypes[$row['id']] = '小说';
	else $booktypes[$row['id']] = '漫画';
}
$lastid = $row['id'];

require_once(dirname(__FILE__)."/templets/makehtml_story.htm");

?>