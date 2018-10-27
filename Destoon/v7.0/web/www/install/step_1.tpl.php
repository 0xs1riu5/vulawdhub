<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<noscript><br/><br/><center><h3>您的浏览器不支持JavaScript,请更换支持JavaScript的浏览器</h1></center><br/><br/></noscript>
<div class="head">
	<div>
		<strong>欢迎使用，DESTOON B2B网站管理系统V<?php echo DT_VERSION;?> <?php echo strtoupper($CFG['charset']);?> 安装向导</strong><br/>
		请仔细阅读以下软件使用协议，在理解并同意协议的基础上安装本软件
	</div>
</div>
<div class="body">
<div style="padding:24px;">
<textarea style="width:760px;height:224px;border:#CCCCCC 1px solid;margin-bottom:16px;padding:10px;">
<?php echo $license;?>
</textarea>
<span style="color:red;">&nbsp;&nbsp;注意：本软件仅限个人免费使用，非个人用户(公司、协会等组织机构)必须购买授权后正式建站</span>
</div>
</div>
<div class="foot">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width="220">
<div class="progress">
<div id="progress"></div>
</div>
</td>
<td id="percent"></td>
<td height="40" align="right">

<form action="index.php" method="post" id="dform">
<input type="hidden" name="step" value="2"/>
<input type="submit" value="我同意(10)" id="read" disabled/>
<input type="button" value="打印(P)" onclick="Print();"/>
&nbsp;&nbsp;
<input type="button" value="取消(C)" onclick="if(confirm('您确定要退出安装向导吗？')) window.close();"/>
</form>
<textarea style="display:none;" id="license">
<?php echo nl2br($license);?>
</textarea>
<script type="text/javascript">
function Print() {
	var w = window.open('','','');
	w.opener = null;
	w.document.write('<html><head><meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset'];?>" /></head><body><div style="width:650px;font-size:10pt;line-height:19px;font-family:Verdana,Arial;">'+$('license').value+'</div></body></html>');
	w.window.print();
}
var i = 9;
var interval=window.setInterval(
	function() {
		if(i == 0) {
			$('read').value = '我同意(I)';
			$('read').disabled = false;
		} else {
			$('read').value = '我同意('+i+')';
			i--;
		}
	}, 
1000);
</script>
<?php
include IN_ROOT.'/footer.tpl.php';
?>
<script type="text/javascript" src="http://www.destoon.com/install.php?release=<?php echo DT_RELEASE;?>&charset=<?php echo $CFG['charset'];?>&domain=<?php echo urlencode(get_env('url'));?>"></script>