<?php
require '../../common.inc.php';
$auth = isset($auth) ? decrypt($auth, DT_KEY.'PUSH') : '';
$auth or exit('E001');
$touser = substr($auth, 0, strpos($auth, '|'));
check_name($touser) or exit('E002');
$word = substr($auth, strlen($touser) + 1);
strlen($word) > 2 or exit('E003');
$user = $db->get_one("SELECT openid,push FROM {$DT_PRE}weixin_user WHERE username='$touser'");
$user or exit('E004');
$user['push'] or exit('E005');
$openid = $user['openid'];
$type = 'text';
require DT_ROOT.'/api/weixin/init.inc.php';
$arr = $wx->send($openid, $type, $word);
if($arr['errcode'] != 0) {
	if($arr['errcode'] == 45015) exit('E006');
	exit('W'.$arr['errcode'].'-'.$arr['errmsg']);
}
$post = array();
$post['content'] = $word;
$post['type'] = 'push';
$post['openid'] = $openid;
$post['editor'] = 'system';
$post['addtime'] = $DT_TIME;
$post['misc']['type'] = $type;
$post['misc'] = '';
$post = daddslashes($post);
$sql = '';
foreach($post as $k=>$v) {
	$sql .= ",$k='$v'";
}
$db->query("INSERT INTO {$DT_PRE}weixin_chat SET ".substr($sql, 1));
exit('ok');
?>