<?php /* Smarty version 2.6.22, created on 2018-11-20 11:02:43
         compiled from database.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count_data', 'database.htm', 15, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['act'] == backup): ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="database.php?act=restore">数据库还原</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<form name="data_form" method="post" action="database.php">
  <tr>
  	<td class="datalist_title">选择</td>
    <td class="datalist_title">数据表名称</td>
    <td class="datalist_title">记录数</td>
  </tr>
  <?php unset($this->_sections['database']);
$this->_sections['database']['name'] = 'database';
$this->_sections['database']['loop'] = is_array($_loop=$this->_tpl_vars['database_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['database']['show'] = true;
$this->_sections['database']['max'] = $this->_sections['database']['loop'];
$this->_sections['database']['step'] = 1;
$this->_sections['database']['start'] = $this->_sections['database']['step'] > 0 ? 0 : $this->_sections['database']['loop']-1;
if ($this->_sections['database']['show']) {
    $this->_sections['database']['total'] = $this->_sections['database']['loop'];
    if ($this->_sections['database']['total'] == 0)
        $this->_sections['database']['show'] = false;
} else
    $this->_sections['database']['total'] = 0;
if ($this->_sections['database']['show']):

            for ($this->_sections['database']['index'] = $this->_sections['database']['start'], $this->_sections['database']['iteration'] = 1;
                 $this->_sections['database']['iteration'] <= $this->_sections['database']['total'];
                 $this->_sections['database']['index'] += $this->_sections['database']['step'], $this->_sections['database']['iteration']++):
$this->_sections['database']['rownum'] = $this->_sections['database']['iteration'];
$this->_sections['database']['index_prev'] = $this->_sections['database']['index'] - $this->_sections['database']['step'];
$this->_sections['database']['index_next'] = $this->_sections['database']['index'] + $this->_sections['database']['step'];
$this->_sections['database']['first']      = ($this->_sections['database']['iteration'] == 1);
$this->_sections['database']['last']       = ($this->_sections['database']['iteration'] == $this->_sections['database']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';" onmouseout="javascript:this.bgColor='#FFFFFF';">
  	<td><input type="checkbox" name="tables[]" value="<?php echo $this->_tpl_vars['database_list'][$this->_sections['database']['index']]['0']; ?>
" checked="true" /></td>
	<td><?php echo $this->_tpl_vars['database_list'][$this->_sections['database']['index']]['0']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['database_list'][$this->_sections['database']['index']]['0'])) ? $this->_run_mod_handler('count_data', true, $_tmp) : smarty_modifier_count_data($_tmp)); ?>
&nbsp;行</td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="4">没有找到任何记录</td>
  </tr>
  <?php endif; ?>
  <tr>
  	<td colspan="4">分卷备份：&nbsp;&nbsp;<input type="text" name="limit_size" size="10" value="2048" />&nbsp;kb</td>
  </tr>
  <tr>
	<td>
		<input type="radio" name="mysql_type" value="" checked />默认<br/>
		<input type="radio" name="mysql_type" value="mysql40" />MySQL 3.23/4.0.x<br/>
		<input type="radio" name="mysql_type" value="mysql41" />MySQL 4.1.x/5.x
	</td>
  </tr>
  <tr>
  	<td><input type="submit" name="submit" value="开始备份" /><input type="hidden" name="act" value="do_backup" /></td>
  </tr>
  </form>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['act'] == restore): ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="database.php?act=backup">数据库备份</a></p>
<form name="data_form" method="post" action="database.php">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
  	<td class="datalist_title">备份文件名</td>
    <td class="datalist_title">备份时间</td>
    <td class="datalist_title">BlueCMS版本</td>
    <td class="datalist_title">MySQL版本</td>
    <td class="datalist_title">大小</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['file']);
$this->_sections['file']['name'] = 'file';
$this->_sections['file']['loop'] = is_array($_loop=$this->_tpl_vars['file_info']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['file']['show'] = true;
$this->_sections['file']['max'] = $this->_sections['file']['loop'];
$this->_sections['file']['step'] = 1;
$this->_sections['file']['start'] = $this->_sections['file']['step'] > 0 ? 0 : $this->_sections['file']['loop']-1;
if ($this->_sections['file']['show']) {
    $this->_sections['file']['total'] = $this->_sections['file']['loop'];
    if ($this->_sections['file']['total'] == 0)
        $this->_sections['file']['show'] = false;
} else
    $this->_sections['file']['total'] = 0;
if ($this->_sections['file']['show']):

            for ($this->_sections['file']['index'] = $this->_sections['file']['start'], $this->_sections['file']['iteration'] = 1;
                 $this->_sections['file']['iteration'] <= $this->_sections['file']['total'];
                 $this->_sections['file']['index'] += $this->_sections['file']['step'], $this->_sections['file']['iteration']++):
$this->_sections['file']['rownum'] = $this->_sections['file']['iteration'];
$this->_sections['file']['index_prev'] = $this->_sections['file']['index'] - $this->_sections['file']['step'];
$this->_sections['file']['index_next'] = $this->_sections['file']['index'] + $this->_sections['file']['step'];
$this->_sections['file']['first']      = ($this->_sections['file']['iteration'] == 1);
$this->_sections['file']['last']       = ($this->_sections['file']['iteration'] == $this->_sections['file']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';" onmouseout="javascript:this.bgColor='#FFFFFF';">
  	<td><?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['file_name']; ?>
</td>
  	<td><?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['add_time']; ?>
</td>
  	<td><?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['bluecms_ver']; ?>
</td>
	<td><?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['mysql_ver']; ?>
</td>
    <td><?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['file_size']; ?>
KB</td>
    <td align="center"><a href="database.php?act=import&file_name=<?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['file_name']; ?>
">导入</a>|<a href="database.php?act=del&file_name=<?php echo $this->_tpl_vars['file_info'][$this->_sections['file']['index']]['file_name']; ?>
">删除</a></td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="4">没有找到任何记录</td>
  </tr>
  <?php endif; ?>
</table>
</form>
<?php endif; ?>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>