<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：update_info.php
 * $author：lucks
 */
if(!defined('IN_BLUE'))	{
	die('Access Denied!');
}

$db->query("UPDATE ".table('post')." SET is_recommend = 0, rec_start = '', rec_time = '' WHERE is_recommend=1 and rec_start + rec_time*24*3600 < $timestamp");

$db->query("UPDATE ".table('post')." SET top_type = 0, top_start='', top_time = '' WHERE top_type != 0 and top_start + top_time*24*3600 < $timestamp");

$db->query("UPDATE ".table('post')." SET is_head_line = 0, head_line_start='', head_line_time='' WHERE is_head_line = 1 and head_line_start + head_line_time*24*3600 < $timestamp");











 ?>