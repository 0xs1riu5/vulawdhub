<?php
!defined('IN_HDWIKI') && exit('Access Denied');

function csrf_token() {

    if (!isset($_SESSION['CSRF_TOKEN'])) {
        $_SESSION['CSRF_TOKEN'] = util::random(32);
    }

    return $_SESSION['CSRF_TOKEN'];
}

function csrf_field() {
    return '<input type="hidden" name="_token" value="'.csrf_token().'">';
}
/*
 *@desc  htmlspecialchars升级版本
 *       解决htmlspecialchars函数在较高php版本中处理GBK版本的字符串转为空bug
 *@params string $str
 *@return string $str
 **/
function htmlspecial_chars($str){
	if($str){
		if (WIKI_CHARSET === 'UTF-8') {
			$str=htmlspecialchars($str);
		} else {
			// $str=htmlspecialchars($str,ENT_QUOTES,'gb2312');
			$str=htmlspecialchars($str,ENT_QUOTES,'ISO-8859-1');
		}
	}
	return $str;
}
