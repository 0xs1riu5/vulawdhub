<?php
function smarty_modifier_mb_substr($string,$sta,$len,$encoding='gb2312')
{
	$string = str_replace('&nbsp;', ' ', $string);
	if(strlen($string)<$len*2)
		return $string;
	$str=mb_substr($string, $sta, $len, $encoding);
	$str = str_replace(' ', '&nbsp;', $str);
	return $str;
}
?>