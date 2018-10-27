<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<div class="head">
	<div>
		<strong>检查系统运行环境</strong><br/>
		检查当前服务器环境配置是否支持DESTOON B2B正常运行
	</div>
</div>
<div class="body">
<div>
	<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#DDDDDD">
	<tr bgcolor="#F1F1F1" align="center">
	<td>检查项目</td>
	<td>当前环境</td>
	<td>要求环境</td>
	<td>推荐环境</td>
	<td>检测结果</td>
	</tr>
	<tr bgcolor="#FFFFFF" align="center">
	<td>PHP版本</td>
	<td><?php echo $PHP_VERSION;?></td>
	<td>4.3.0及以上</td>
	<td>5.0.0及以上</td>
	<td><?php echo $php_pass ? '<span style="color:#007AFF;">通过</span>' : '<span style="color:#CE3C39;">PHP版本过低</span>';?></td>
	</tr>
	<tr bgcolor="#FFFFFF" align="center">
	<td>MySQL版本</td>
	<td><?php echo $PHP_MYSQL;?></td>
	<td>4.0.0及以上</td>
	<td>5.0.0及以上</td>
	<td><?php echo $mysql_pass ? '<span style="color:#007AFF;">通过</span>' : '<span style="color:#CE3C39;">MySQL版本过低</span>';?></td>
	</tr>
	<tr bgcolor="#FFFFFF" align="center">
	<td>GD库</td>
	<td><?php echo $PHP_GD;?></td>
	<td>jpg gif png</td>
	<td>jpg gif png</td>
	<td><?php echo $gd_pass ? '<span style="color:#007AFF;">通过</span>' : '<span style="color:#CE3C39;">无法处理图片</span>';?></td>
	</tr>
	<tr bgcolor="#FFFFFF" align="center">
	<td>URL打开文件</td>
	<td><?php echo $PHP_URL ? '支持' : '不支持';?></td>
	<td>支持</td>
	<td>支持</td>
	<td><?php echo $url_pass ? '<span style="color:#007AFF;">通过</span>' : '<span style="color:#CE3C39;">建议开启</span>';?></td>
	</tr>
	</table>
	<br/>
	<br/>
	<?php
	if($pass) {
		echo '&nbsp;&nbsp;服务器环境配置通过检测，请点 下一步(N) 继续安装';
	} else {
		echo '&nbsp;&nbsp;<span style="color:red;">服务器环境配置未通过检测，安装无法进行!</span> <br/><br/>&nbsp;&nbsp;请按提示配置好服务器环境后重新运行本安装向导。';
	}
	?>
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
<input type="hidden" name="step" value="3"/>
<input type="button" value="上一步(P)" onclick="history.back(-1);"/>
<input type="submit" value="下一步(N)"<?php if(!$pass) echo ' disabled';?>/>
&nbsp;&nbsp;
<?php
	if($pass) {
?>
<input type="button" value="取消(C)" onclick="if(confirm('您确定要退出安装向导吗？')) window.close();"/>
<?php
	} else {
?>
<input type="button" value="刷新(R)" onclick="window.location.reload();"/>
<?php
	}
?>
</form>
<?php
include IN_ROOT.'/footer.tpl.php';
?>