<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<div class="head">
	<div>
		<strong>提示信息</strong><br/>
		如果对此提示信息有疑问，请访问官网 www.destoon.com
	</div>
</div>
<div class="body">
<p><?php echo $msg;?></p>
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
<input type="button" value=" 返回(R) " onclick="history.back(-1);"/>
<input type="button" value=" 官网(W) " onclick="window.open('http://www.destoon.com/');"/>&nbsp;&nbsp;
<input type="button" value=" 关闭(C) " onclick="window.close();"/>
<?php
include IN_ROOT.'/footer.tpl.php';
?>