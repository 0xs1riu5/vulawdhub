<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：index.fun.php
 * $author：lucks
 */
if(!defined('IN_BLUE'))
{
	die('Access Denied!');
}
//栏目
 function cat_nav(){
 	global $db;
 	$row_arr = array();
 	$result = $db->query("SELECT cat_id, cat_name FROM ".table('category')." WHERE parentid=0 ORDER BY show_order");
 	while($row = $db->fetch_array($result)){
 		$row['url'] = url_rewrite('category', array('cid'=>$row['cat_id']));
 		$row_arr[] = $row;
 	}
	return $row_arr;
 }
//后台添加顶部导航
 function add_nav_list(){
 	global $db;
 	$row_arr = array();
 	$row_arr = $db->getall("SELECT * FROM ".table('navigate')." WHERE type='1' ORDER BY showorder");
 	return $row_arr;
 }
//后台添加底部导航
 function bot_nav(){
 	global $db;
 	$row_arr = array();
 	$row_arr = $db->getall("SELECT navid, navname, navlink, opennew FROM ".table('navigate')." WHERE type=2 ORDER BY showorder");
 	return $row_arr;
 }
//首页flash
 function flash_list(){
 	global $db;
 	$row_arr = array();
 	$result = $db->query("SELECT * FROM ".table('flash_image')." ORDER BY show_order limit 5");
	while($row = $db->fetch_array($result)){
		$flash_list['pics'][] = $row['image_path'];
		$flash_list['links'][] = $row['image_link'];
	}
	return $flash_list;
 }
 /**
   *首页电话广告
   */
 function ad_phone_list(){
 	global $db;
 	$row_arr = array();
 	$time_arr = getdate();
	 $time_unix = mktime(0, 0, 0, $time_arr['mon'], $time_arr['mday'], $time_arr['year']);
 	$row_arr = $db->getall("SELECT * FROM ".table('ad_phone')." WHERE " .
 		"start_time<='$time_unix' and end_time>='$time_unix' or end_time=0 ORDER BY is_show DESC,show_order ASC LIMIT 30");
 	return $row_arr;
 }

 /**
  * 推荐新闻
  */
 function get_arc_rec($num = ''){
 	global $db;
 	$row_arr = array();
 	if($num){
		$condition = " LIMIT ".intval($num);
 	}else{
		$condition = '';
 	}
	$row_arr = $db->getall("SELECT id, title, pub_date FROM ".table('article')." WHERE is_check = 1 and is_recommend = 1 ORDER BY pub_date DESC".$condition);
 	for($i=0;$i<count($row_arr);$i++){
 		$row_arr[$i]['url'] = url_rewrite('news', array('id'=>$row_arr[$i]['id']));
 	}
 	return $row_arr;
 }


 function get_ann($offset, $perpage, $cid = ''){
 	global $db;
 	$row_arr = array();
	if(!empty($cid)){
		$condition1 = " WHERE a.cid = $cid ";
	} else {
		$condition1 = '';
	}
 	if(isset($offset) && isset($perpage)){
 		$condition2 = " LIMIT $offset,$perpage ";
 	}else{
 		$condition2 = '';
 	}
 	$sql = "SELECT a.ann_id, a.cid, a.title, a.color, a.author, a.content, a.add_time, a.click, b.cat_name FROM ".table('ann')." AS a LEFT JOIN ".table('ann_cat')." AS b ON a.cid=b.cid ".$condition1." ORDER BY a.add_time DESC".$condition2;
 	return $db->getall($sql);
 }

 function get_index_ann ($cid = '', $num = '') {
	global $db;
	if ($cid != '') {
		$condition1 = " WHERE cid=$cid ";
	} else {
		$condition1 = '';
	}
	if ($num != '') {
		$condition2 = " LIMIT $num ";
	} else {
		$condition2 = '';
	}
	$sql = "SELECT ann_id, cid, title FROM ".table('ann').$condition1." ORDER BY ann_id DESC ".$condition2;
	return $db->getall($sql);
 }

 /**
  * 图文列表
  */
 function get_arc_pic($num = ''){
 	global $db;
 	$row_arr = array();
 	if($num){
		$condition = " LIMIT ".intval($num);
 	}else{
		$condition = "";
 	}
	$row_arr = $db->getall("SELECT id, title, lit_pic, descript  FROM ".table('article')." WHERE is_check = 1 and lit_pic != '' ORDER BY pub_date DESC".$condition);
 	for($i=0;$i<count($row_arr);$i++){
 		$row_arr[$i]['url'] = url_rewrite('news', array('id'=>$row_arr[$i]['id']));
 	}
 	return $row_arr;
 }

 function get_info($cid = '', $offset, $perpage) {
	 global $db;
	 $info_arr = array();
	 if (!empty($cid)) {
		 $condition1 = " AND cat_id = $cid ";
	 } else {
		 $condition1 = '';
	 }
	 if(isset($offset) && isset($perpage)){
 		$condition2 = " LIMIT $offset,$perpage ";
 	}else{
 		$condition2 = '';
 	}
	 $sql = "SELECT post_id, cat_id, title FROM ".table('post')." WHERE is_check =1 ".$condition1." ORDER BY pub_date DESC ".$condition2;
	 $result = $db->query($sql);
	 while ($row = $db->fetch_array($result)) {
		 $row['url'] = url_rewrite('post', array('id'=>$row['post_id']));
		 $info_arr[] = $row;
	 }
	 return $info_arr;
 }

 function get_hot_info ($cid = '', $num = '') {
	 global $db;
	 $hot_info = array();
	 if (!empty($cid)) {
		 $condition1 = " AND cat_id = ".intval($cid) ;
	 } else {
		 $condition1 = '';
	 }
	 if (!empty($num)) {
		 $condition2 = " LIMIT ".intval($num);
	 } else {
		 $condition2 = '';
	 }
	 $sql = "SELECT post_id, cat_id, title FROM ".table('post')." WHERE is_check = 1 ".$condition1." ORDER BY click DESC ".$condition2;
	 $result = $db->query($sql);
	 while ($row = $db->fetch_array($result)) {
		 $row['url'] = url_rewrite('post', array('id'=>$row['post_id']));
		 $hot_info[] = $row;
	 }
	 return $hot_info;
 }


/**
  *首页推荐信息
  */
 function get_rec_info($cid = '', $num = ''){
 	global $db, $timestamp;
 	$row_arr = array();
	if (!empty($cid)) {
		 $condition1 = " AND cat_id = $cid ";
	 } else {
		 $condition1 = '';
	 }
 	if(!empty($num)){
		$condition2 = ' LIMIT '.intval($num);
 	}else{
		$condition2 = '';
 	}
	$row_arr = $db->getall("SELECT post_id, title, content FROM ".table('post')." WHERE is_check = 1 and is_recommend = 1 and rec_start + rec_time*24*3600 > $timestamp ".$condition1." ORDER BY pub_date DESC".$condition2);
 	for($i=0;$i<count($row_arr);$i++){
 		$row_arr[$i]['url'] = url_rewrite('post', array('id'=>$row_arr[$i]['post_id']));
 	}
 	return $row_arr;
 }

 function get_cat_list($pid){
 	global $db;
 	$row_arr = array();
 	$result = $db->query("SELECT cat_id, cat_name, title, keywords, description FROM ".table('category')." WHERE parentid = ".intval($pid)." ORDER BY show_order");
 	while($row = $db->fetch_array($result)){
 		$row['url'] = url_rewrite('category', array('cid'=>$row['cat_id']));
 		$row_arr[] = $row;
 	}
 	return $row_arr;
 }

 function get_news($offset, $perpage, $cid= '', $admin = FALSE, $user_id = ''){
 	global $db;
 	$row_arr = array();
 	if(empty($cid)){
 		$condition = '';
 	}else{
 		$condition = ' and a.cid='.intval($cid).' ';
 	}
	if ($admin === TRUE){
		$condition .= '';
	} else {
		$condition .= ' and a.is_check =1';
	}
	if (empty($user_id))
	{
	    $condition .= '';
	}
	else
	{
	    $condition .= ' and a.user_id = '.intval($user_id);
	}

 	if(isset($offset)&&!empty($perpage))
 	{
 		$result = $db->query("SELECT a.id, a.title, a.color, a.author, a.source, a.pub_date, a.descript, 
 									a.content, a.click, a.comment, b.user_name 
 							FROM ".table('article')." AS a 
 							LEFT JOIN ".table('user')." AS b 
 							ON a.user_id=b.user_id 
 							WHERE 1=1 ".$condition.
 							" ORDER BY pub_date DESC 
 							LIMIT ".$offset.','.$perpage);
 		while($row = $db->fetch_array($result))
 		{
 			$row['url'] = url_rewrite('news', array('id'=>$row['id']));
 			$row_arr[] = $row;
 		}
 	}else{
 		$result = $db->query("SELECT a.id, a.title, a.color, a.author, a.source, a.pub_date, a.descript, 
 									a.content, a.click, a.comment, b.user_name 
 							FROM ".table('article')." AS a 
 							LEFT JOIN ".table('user')." AS b 
 							ON a.user_id=b.user_id WHERE 1=1 ".$condition.
 							" ORDER BY pub_date DESC");
 		while($row = $db->fetch_array($result))
 		{
 			$row['url'] = url_rewrite('news', array('id'=>$row['id']));
 			$row_arr[] = $row;
 		}
 	}
 	return $row_arr;
}

function get_area_list($cid)
{
 	global $db;
 	$row_arr = array();
 	$result = $db->query("SELECT area_id, area_name 
 							FROM ".table('area').
 							" ORDER BY show_order");
 	while($row = $db->fetch_array($result))
 	{
 		$row['url'] = url_rewrite('category', array('cid'=>$cid, 'aid'=>$row['area_id']));
 		$row_arr[] = $row;
 	}
 	return $row_arr;
}

function get_info_total($cid, $pid = '', $aid = '', $admin = FALSE)
{
 	global $db;
	if ($admin === TRUE)
	{
		$condition = '';
	}
	else
	{
		$condition = ' and a.is_check =1';
	}
 	if ($cid && $pid == 0)
 	{
 		if (!empty($aid))
 		{
 			$condition .= " and a.area_id = '$aid' ";
 		}
 		$row = $db->getfirst("SELECT COUNT(*) FROM ".table('post')." AS a,".table('category')." AS b WHERE a.cat_id IN(SELECT cat_id FROM ".table('category')." WHERE b.parentid=".$cid.") and a.cat_id = b.cat_id ".$condition);
 		return $row;
 	}
 	elseif ($cid && $pid != 0)
 	{
 		if(!empty($aid))
 		{
 			$condition .= " and a.area_id = '$aid' ";
 		}
 		$row = $db->getfirst("SELECT COUNT(*) 
 								FROM ".table('post')." AS a,".table('category')." AS b 
 								WHERE a.cat_id = '$cid' and a.cat_id = b.cat_id ".$condition);
 		return $row;
 	}
}

function get_article_total($cid = '')
{
 	global $db;
	if(empty($cid))
	{
		$condition = '';
	}
	else
	{
		$condition = " and cid=".$cid;
	}
 	$row = $db->getfirst("SELECT COUNT(*) 
 							FROM ".table('article').
 							" WHERE 1=1 ".$condition);
 	return $row;
}

function get_rec_news($cid='', $offset = '', $perpage = '')
{
 	global $db;
 	$row_arr = array();
	if ($cid)
	{
		$condition1 = " and cid=$cid ";
	}
	else
	{
		$condition1 = '';
	}
 	if(isset($offset)&&!empty($perpage))
 	{
 		$condition2 = " LIMIT $offset, $perpage ";
 	}
 	else
 	{
 		$condition2 = '';
 	}
 	$result = $db->query("SELECT id, title, color 
 							FROM ".table('article').
 							" WHERE is_check=1 and is_recommend = 1 ".$condition1."
 							 ORDER BY pub_date DESC".$condition2);
 	while($row = $db->fetch_array($result))
 	{
 		$row['url'] = url_rewrite('news', array('id'=>$row['id']));
 		$row_arr[] = $row;
 	}
 	return $row_arr;
}

function get_hot_news($cid='', $num='')
{
 	global $db;
 	$row_arr = array();
	if ($cid)
	{
		$condition1 = " and cid=$cid ";
	}
	else
	{
		$condition1 = '';
	}
 	if($num)
 	{
 		$condition2 = ' LIMIT '.intval($num);
 	}
 	else
 	{
 		$condition2 = '';
 	}
 	$result = $db->query("SELECT id, title, color 
 							FROM ".table('article').
 							" WHERE is_check = 1 ".$condition1.
 							" ORDER BY click DESC".$condition2);
 	while($row = $db->fetch_array($result))
 	{
 		$row['url'] = url_rewrite('news', array('id'=>$row['id']));
 		$row_arr[] = $row;
 	}
 	return $row_arr;
}

function get_ann_total($cid = '')
{
 	global $db;
	if(!empty($cid))
	{
		$condition = " WHERE cid= $cid ";
	}
	else
	{
		$condition = '';
	}
 	$row = $db->getfirst("SELECT COUNT(*) 
 							FROM ".table('ann').$condition);
	return $row;
}

function get_hot_ann($num = '')
{
 	global $db;
 	$row_arr = array();
 	if($num)
 	{
 		$condition = ' LIMIT '.intval($num);
 	}
 	else
 	{
 		$condition = '';
 	}
 	$result = $db->query("SELECT ann_id, title, color 
 							FROM ".table('ann').
 							" ORDER BY click DESC".$condition);
 	while($row = $db->fetch_array($result))
 	{
 		$row['url'] = url_rewrite('ann', array('ann_id'=>$row['ann_id']));
 		$row_arr[] = $row;
 	}
 	return $row_arr;
}

/**
  * 获取首页分类信息显示HTML
  */
function get_index_info(){
	global $db, $_CFG;
 	$html = '';
 	$sql1 = "SELECT cat_id, cat_name, is_havechild, title_color 
 			FROM ".table('category').
 			" WHERE parentid = 0 
 			ORDER BY show_order";
 	$result1 = $db->query($sql1);
	$i = 0;
 	while($row1 = $db->fetch_array($result1))
 	{
		if($row1['title_color'])
		{
			$color = ' style="color:'.$row1['title_color'].';" ';
		}
		else
		{
			$color = '';
		}
		if (++$i % 2 ==0)
		{
			$html .= "<div class=\"index_cat right\"><div class=\"title1\"".$color."><span class=\"more\"><a href=\"".url_rewrite('category',
 			array('cid'=>$row1['cat_id']))."\"><img src=\"templates/default/images/more.gif\" border=\"0\" /></a></span>".$row1['cat_name']."</div><div class=\"content1\"><ul>";
		}
		else
		{
 			$html .= "<div class=\"index_cat\"><div class=\"title1\"".$color."><span class=\"more\"><a href=\"".url_rewrite('category',
 			array('cid'=>$row1['cat_id']))."\"><img src=\"templates/default/images/more.gif\" border=\"0\" /></a></span>".$row1['cat_name']."</div><div class=\"content1\"><ul>";
		}

 		$sql2 = "SELECT post_id, title, pub_date 
 				FROM ".table('post').
 				" WHERE is_check = 1 and cat_id 
 				IN(SELECT cat_id 
 					FROM ".table('category').
 					" WHERE parentid=".$row1['cat_id'].") 
 					ORDER BY pub_date DESC 
 					LIMIT 6";
 		$result2 = $db->query($sql2);
 		while($row2 = $db->fetch_array($result2))
 		{
 			$html .= "<li><span class=\"date\">".date("Y-m-d",$row2['pub_date'])."</span><a href=\"".
		 			url_rewrite('post', array('id'=>$row2['post_id']))."\">".mb_sub($row2['title'], 0, 16)."</a></li>";
 		}
 		$html .="</ul></div></div>";
 	}
 	return $html;
}

function get_head_line ($type = 'info', $num = '') {
	 global $db, $timestamp;
	 $head_line_arr = array();
	 if ($type == 'info')
	 {
		 $table = 'post';
		 $act = 'post';
		 $id = 'post_id';
	 }
	 else
	 {
		 $table = 'company';
		 $act = 'view_c_detail';
		 $id = 'id';
	 }
	 $num = intval($num);
	 if	(!empty($num))
	 {
		$condition = " LIMIT $num ";
	 }
	 else
	 {
		 $condition = '';
	 }
	 $result = $db->query("SELECT * 
	 						FROM ".table($table).
	 						" WHERE is_check = 1 and is_head_line = 1 
	 								and head_line_time*24*3600 + head_line_start > $timestamp 
	 						ORDER BY $id DESC ".$condition);
	 while ($row = $db->fetch_array($result))
	 {
		 $row['url'] = url_rewrite($act, array('id'=>$row[$id]));
		 $head_line_arr[] = $row;
	 }
	 return $head_line_arr;
 }

?>