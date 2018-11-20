<?php /* Smarty version 2.6.22, created on 2018-11-20 09:44:37
         compiled from step2.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
" />
<title>安装程序 - BlueCMS―地方门户专用CMS！</title>
<link href="templates/css/install.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="top">
	<div class="logo">
		<h2>BlueCMS―地方门户专用CMS！ 安装程序</h2>
	</div>
	<div class="link">
		<ul>
			<li><a href="http://www.bluecms.net" target="_blank">官方网站</a></li>
			<li><a href="http://www.bluecms.net/bbs" target="_blank">技术论坛</a></li>
			<li><a href="" target="_blank">帮助</a></li>
		</ul>
	</div>
	<div class="version">
		<h2>BlueCMS</h2>
	</div>
</div>

<div class="main">
	<div class="m_l">
		<dl class="step">
			<dt>安装步骤</dt>
			<dd>
				<ul>
					<li class="succeed">许可协议</li>
					<li class="current">环境检测</li>
					<li>参数配置</li>
					<li>正在安装</li>
					<li>安装完成</li>
				</ul>
			</dd>
		</dl>
	</div>
	<div class="m_r">
		<div class="title"><h3>服务器信息</h3></div>
		<div class="content">
		<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="data">
			<tr>
					<td>服务器操作系统</td>
					<td><?php echo $this->_tpl_vars['system_info']['os']; ?>
</td>
			</tr>
			<tr>
					<td>服务器解译引擎</td>
					<td><?php echo $this->_tpl_vars['system_info']['web_server']; ?>
</td>
			</tr>
			<tr>
					<td>PHP版本</td>
					<td><?php echo $this->_tpl_vars['system_info']['php_ver']; ?>
</td>
			</tr>
			<tr>
					<td>上传附件最大值</td>
					<td><?php echo $this->_tpl_vars['system_info']['max_filesize']; ?>
</td>
			</tr>
		</table></div>
		<div class="title"><h3>目录权限检测</h3></div>
		<div class="content">
		<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="data">
			<tr>
				<th align="center" width="100"><strong>目录名</strong></th>
				<th><strong>读取权限</strong></th>
				<th><strong>写入权限</strong></th>
			</tr>
			<?php unset($this->_sections['dir']);
$this->_sections['dir']['name'] = 'dir';
$this->_sections['dir']['loop'] = is_array($_loop=$this->_tpl_vars['dir_check']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dir']['show'] = true;
$this->_sections['dir']['max'] = $this->_sections['dir']['loop'];
$this->_sections['dir']['step'] = 1;
$this->_sections['dir']['start'] = $this->_sections['dir']['step'] > 0 ? 0 : $this->_sections['dir']['loop']-1;
if ($this->_sections['dir']['show']) {
    $this->_sections['dir']['total'] = $this->_sections['dir']['loop'];
    if ($this->_sections['dir']['total'] == 0)
        $this->_sections['dir']['show'] = false;
} else
    $this->_sections['dir']['total'] = 0;
if ($this->_sections['dir']['show']):

            for ($this->_sections['dir']['index'] = $this->_sections['dir']['start'], $this->_sections['dir']['iteration'] = 1;
                 $this->_sections['dir']['iteration'] <= $this->_sections['dir']['total'];
                 $this->_sections['dir']['index'] += $this->_sections['dir']['step'], $this->_sections['dir']['iteration']++):
$this->_sections['dir']['rownum'] = $this->_sections['dir']['iteration'];
$this->_sections['dir']['index_prev'] = $this->_sections['dir']['index'] - $this->_sections['dir']['step'];
$this->_sections['dir']['index_next'] = $this->_sections['dir']['index'] + $this->_sections['dir']['step'];
$this->_sections['dir']['first']      = ($this->_sections['dir']['iteration'] == 1);
$this->_sections['dir']['last']       = ($this->_sections['dir']['iteration'] == $this->_sections['dir']['total']);
?>
			<tr>
				<td><?php echo $this->_tpl_vars['dir_check'][$this->_sections['dir']['index']]['dir']; ?>
</td>
				<td align="center"><?php echo $this->_tpl_vars['dir_check'][$this->_sections['dir']['index']]['read']; ?>
</td>
				<td align="center"><?php echo $this->_tpl_vars['dir_check'][$this->_sections['dir']['index']]['write']; ?>
</td>
			</tr>
			<?php endfor; endif; ?>
		</table></div>
		<div class="btn_sub">
			<input type="button" class="btn_back" value="后退" onclick="window.location.href='index.php';" />
			<input type="button" class="btn_next" value="继续" onclick="window.location.href='index.php?act=step3';" />
		</div>
	</div>
</div>

<div class="foot">

</div>

</body>
</html>