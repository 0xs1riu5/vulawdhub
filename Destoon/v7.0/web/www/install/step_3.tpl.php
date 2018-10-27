<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<div class="head">
	<div>
		<strong>检查目录/文件属性</strong><br/>
		检查需要写操作的目录/文件是否有写操作权限
	</div>
</div>
<div class="body">
<div>
	<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#DDDDDD">
	<tr bgcolor="#F1F1F1" align="center">
	<td width="15%">目录/文件</td>
	<td width="8%">属性</td>
	<td width="15%">目录/文件</td>
	<td width="8%">属性</td>
	<td width="15%">目录/文件</td>
	<td width="8%">属性</td>
	</tr>
	<?php foreach($FILES as $k=>$v) { ?>
	<?php if($k%3 == 0) { ?>
	<tr bgcolor="#FFFFFF" align="center">
	<?php } ?>
	<td align="left">&nbsp;<?php echo $v['name'];?></td>
	<td><?php echo $v['write'] ? '<span style="color:#007AFF;">可写</span>' : '<span style="color:#CE3C39;">不可写</span>';?></td>
	<?php if($k%3 == 2) { ?>
	</tr>
	<?php } ?>
	<?php } ?>
	</table>
	<br/>
	<?php
	if($pass) {
		echo '&nbsp;&nbsp;目录/文件属性通过检测，请点 下一步(N) 继续安装';
	} else {
		echo '<br/>&nbsp;&nbsp;<span style="color:red;">目录/文件属性未通过检测，安装无法进行!</span> <br/><br/>&nbsp;&nbsp;';
		if($ISWIN) {
			echo '请设置不可写目录/文件(含子目录及文件)写入权限';
		} else {
			echo '请设置不可写目录/文件(含子目录及文件)属性为可写('.sprintf('%o', DT_CHMOD).')';
		}
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
<input type="hidden" name="step" value="4"/>
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