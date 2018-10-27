<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<div class="head">
	<div>
		<strong>安装正在进行</strong><br/>
		安装正在进行，请稍候...
	</div>
</div>
<div class="body">
<div>
<textarea style="width:760px;height:240px;border:#CCCCCC 1px solid;padding:10px;" id="msgbox"></textarea>
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
<input type="hidden" name="step" value="6"/>
<input type="hidden" name="url" value="<?php echo $url;?>"/>
<input type="hidden" name="username" value="<?php echo $username;?>"/>
<input type="hidden" name="password" value="<?php echo $password;?>"/>
<input type="hidden" name="step" value="6"/>
<input type="button" value="上一步(P)" onclick="history.back(-1);" disabled/>
<input type="submit" value="下一步(N)"/>
&nbsp;&nbsp;
<input type="button" value="取消(C)" onclick="if(confirm('您确定要退出安装向导吗？')) window.close();"/>
</form>
<?php
include IN_ROOT.'/footer.tpl.php';
?>
<script type="text/javascript">
$('msgbox').value = '';
<?php
$time = 400;
foreach($msgs as $v) {
?>
setTimeout("$('msgbox').value += ' # <?php echo $v;?>\\n';", <?php echo $time;?>);
<?php
	$time += 200;
}
$time += 200;
?>
setTimeout("$('dform').submit();", <?php echo $time;?>);
</script>