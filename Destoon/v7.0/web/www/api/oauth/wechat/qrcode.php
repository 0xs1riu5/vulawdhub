<?php
require '../../../common.inc.php';
require 'init.inc.php';
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=<?php echo DT_CHARSET;?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信登录<?php echo $DT['seo_delimiter'];?><?php echo $DT['sitename'];?></title>
	<style type="text/css">
	* {word-break:break-all;}
	body {margin:0;font-size:14px;color:#333333;background:#FFFFFF;-webkit-user-select:none;}
	</style>
</head>
<body>
	<div style="width:100%;text-align:center;">
		<div id="weixin_qrcode"></div>
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