<?php
require '../../common.inc.php';
header("Content-type:image/png");
$_userid or dheader('image/qrcode_error.png');
$auth = isset($auth) ? decrypt($auth, DT_KEY.'WXQR') : '';
$auth == $_username.md5(DT_IP.$_SERVER['HTTP_USER_AGENT']) or dheader('image/qrcode_error.png');
$t = $db->get_one("SELECT itemid FROM {$DT_PRE}weixin_user WHERE username='$_username'");
if($t) dheader('image/qrcode_error.png');
$sid = random(9, '123456789');
$db->query("REPLACE INTO {$DT_PRE}weixin_bind (username,sid,addtime) VALUES ('$_username','$sid','$DT_TIME')");
$expire = $DT_TIME - 1800;
$db->query("DELETE FROM {$DT_PRE}weixin_bind WHERE addtime<$expire");
require DT_ROOT.'/api/weixin/init.inc.php';
$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
$par = '{"expire_seconds": 1800,"action_name": "QR_SCENE","action_info": {"scene": {"scene_id":'.$sid.'}}}';
$arr = $wx->http_post($url, $par);
if(isset($arr['ticket']) && $arr['ticket']) dheader('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($arr['ticket']));
if(is_file(DT_ROOT.'/api/weixin/image/qrcode.png')) dheader('image/qrcode.png');
?>