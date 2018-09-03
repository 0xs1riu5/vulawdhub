<?php
//安全提示问题，这两句不要修改
$safequestions = array();
$safequestions[0] = '没安全提示问题';

//下面的设置可以手工修改
//start****************************

$safequestions[1] = '你最喜欢的格言什么？';
$safequestions[2] = '你家乡的名称是什么？';
$safequestions[3] = '你读的小学叫什么？';
$safequestions[4] = '你的父亲叫什么名字？';
$safequestions[5] = '你的母亲叫什么名字？';
$safequestions[6] = '你最喜欢的偶像是谁？';
$safequestions[7] = '你最喜欢的歌曲是什么？';

//end****************************



//以下不要修改
function GetSafequestion($selid=0,$formname='safequestion')
{
	global $safequestions;
	$safequestions_form = "<select name='$formname' id='$formname'>";
	foreach($safequestions as $k=>$v)
	{
	 	if($k==$selid) $safequestions_form .= "<option value='$k' selected>$v</option>\r\n";
	 	else $safequestions_form .= "<option value='$k'>$v</option>\r\n";
	}
	$safequestions_form .= "</select>\r\n";
	return $safequestions_form;
}

?>