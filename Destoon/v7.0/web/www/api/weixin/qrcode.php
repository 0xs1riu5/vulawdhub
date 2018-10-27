<?php
require '../../common.inc.php';
header("Content-type:image/png");
if(is_file(DT_ROOT.'/api/weixin/image/qrcode.png')) dheader('image/qrcode.png');
require DT_ROOT.'/api/weixin/init.inc.php';
$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
$par = '{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"scene_id":1}}}';
$arr = $wx->http_post($url, $par);
if(isset($arr['ticket']) && $arr['ticket']) dheader('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($arr['ticket']));
?>