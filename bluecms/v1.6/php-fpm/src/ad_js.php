<?php
/**
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：ad_js.php
 * $author：lucks
 */
define('IN_BLUE', true);
require_once dirname(__FILE__) . '/include/common.inc.php';

$ad_id = !empty($_GET['ad_id']) ? trim($_GET['ad_id']) : '';
if(empty($ad_id))
{
	echo 'Error!';
	exit();
}

$ad = $db->getone("SELECT * FROM ".table('ad')." WHERE ad_id =".$ad_id);
if($ad['time_set'] == 0)
{
	$ad_content = $ad['content'];
}
else
{
	if($ad['end_time'] < time())
	{
		$ad_content = $ad['exp_content'];
	}
	else
	{
		$ad_content = $ad['content'];
	}
}
$ad_content = str_replace('"', '\"',$ad_content);
$ad_content = str_replace("\r", "\\r",$ad_content);
$ad_content = str_replace("\n", "\\n",$ad_content);
echo "<!--\r\ndocument.write(\"".$ad_content."\");\r\n-->\r\n";

?>