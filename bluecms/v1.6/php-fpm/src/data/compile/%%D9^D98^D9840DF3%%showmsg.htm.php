<?php /* Smarty version 2.6.22, created on 2018-11-20 12:14:12
         compiled from showmsg.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<title>提示信息</title>
<style type="text/css">
<!--
*{
	margin:0;
	padding:0;
}
body{
	font-size:12px;
}
.showmsg{
	padding:9px 20px 20px;
	text-align:left;
}
h3{
	color:#0099CC;
	font-size:14px;
	margin-bottom:10px;
}
.msg{
	color:#009900;
	font-size:14px;
	font-weight:700;
	margin-bottom:10px;
}
.lightlink {
color:#666666;
text-decoration:underline;
}
.msgtitle{
	font-weight:bolder;
	color:#52B6E8;
	font-size:14px;
	text-align:left;
}
.msgcontent{
	-moz-background-clip:border;
	-moz-background-inline-policy:continuous;
	-moz-background-origin:padding;
	background:#F2F9FD none repeat scroll 0 0;
	border-bottom:4px solid #DEEEFA;
	border-top:4px solid #DEEFFA;
	clear:both;
	margin-bottom:10px;
	padding:30px;
	text-align:center;
}
.marginbot {
margin-bottom:10px;
}
-->
</style>
</head>
<body>
<div class="showmsg">
<h3>BlueCMS提示信息</h3>
<div class="msgcontent">
  <h4 class="msg"><?php echo $this->_tpl_vars['msg']; ?>
</h4>
  <?php if ($this->_tpl_vars['gourl'] == 'goback'): ?><p class="marginbot"><a class="lightlink" href="javascript:history.back();">点击这里返回</a></p>
		<?php else: ?><p><a class="lightlink" href="<?php echo $this->_tpl_vars['gourl']; ?>
">如果您的浏览器没有反应，请点击这里</a></p><script language="javascript" type="text/javascript">setTimeout("location.replace('<?php echo $this->_tpl_vars['gourl']; ?>
')",'2000');</script>
		<?php endif; ?>
</div>
</div>
</body>
</html>