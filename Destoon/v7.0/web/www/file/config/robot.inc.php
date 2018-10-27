<?php
$ROBOT = array(
	'baidu' => '百度',
	'google' => 'Google',
	'yahoo' => 'Yahoo',
	'bing' => 'Bing',
	'360' => '好搜',
	'soso' => '搜搜',
	'sogou' => '搜狗',
	'other' => '其他'
);
function get_robot() {
	global $ROBOT;
	if(is_robot()) {
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		foreach($ROBOT as $k=>$v) {
			if(strpos($agent, $k) !== false) return $k;
		}
		return 'other';
	}
	return '';
}
?>