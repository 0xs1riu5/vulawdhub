<?php
function smarty_modifier_getarticlenum($string)
{
	global $db;
	$sql="select count(*) from lucks_article where catid='$string'";
	$result=$db->select($sql);
	return $result[0]['count(*)'];
}
?>