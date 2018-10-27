<?php
require '../../../common.inc.php';
if($DT_MOB['browser'] == 'weixin' && $EXT['weixin']) dheader(DT_MOB.'api/weixin.php?action=connect');
require 'init.inc.php';
#dheader(WX_CONNECT_URL.'?appid='.WX_ID.'&redirect_uri='.urlencode(WX_CALLBACK).'&response_type=code&scope=snsapi_login#wechat_redirect');
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=<?php echo DT_CHARSET;?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信登录<?php echo $DT['seo_delimiter'];?><?php echo $DT['sitename'];?></title>
	<style type="text/css">
	* {word-break:break-all;font-family:"Segoe UI","Lucida Grande",Helvetica,Arial,Verdana,"Microsoft YaHei";}
	body {margin:0;font-size:14px;color:#333333;background:#EFEFF4;-webkit-user-select:none;}
	</style>
</head>
<body>
	<div style="width:100%;text-align:center;padding-top:30px;">
		<div id="weixin_qrcode"></div>
		<div style="padding:16px;font-size:16px;color:#999999;">
		<?php if(DT_TOUCH) { ?>
		<a href="http://app.destoon.com/scan/" rel="external" style="color:#2E7DC6;text-decoration:none;">如何扫描？</a>&nbsp;&nbsp;|&nbsp;&nbsp;
		<?php } ?>
		<a href="<?php echo $MODULE[2]['linkurl'].$DT['file_login'];?>" style="color:#2E7DC6;text-decoration:none;">取消并返回</a>
		</div>
	</div>
	<script src="//res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
	<script type="text/javascript">
	var obj = new WxLogin({
		id:"weixin_qrcode", 
		appid: "<?php echo WX_ID;?>", 
		scope: "snsapi_login", 
		redirect_uri: "<?php echo urlencode(WX_CALLBACK);?>",
		state: "",
		style: "",
		href: ""
	});
	</script>
</body>
</html>