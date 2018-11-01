<?php
/**
 * KindEditor PHP
 * 
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 * 
 */

include('../../../../common.php');
if ($_FILES['imgFile']['size']) {
	pe_lead('include/class/upload.class.php');
	$upload = new upload($_FILES['imgFile']);
	echo json_encode(array('error' => 0, 'url' => $upload->filehost));
}
?>