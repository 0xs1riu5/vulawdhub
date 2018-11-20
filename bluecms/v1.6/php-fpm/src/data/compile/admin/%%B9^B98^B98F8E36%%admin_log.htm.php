<?php /* Smarty version 2.6.22, created on 2018-11-20 11:02:37
         compiled from admin_log.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'admin_log.htm', 15, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<form name="log_form" method="post" action="admin_log.php">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
  	<td class="datalist_title">选择</td>
    <td class="datalist_title">操作用户</td>
    <td class="datalist_title">日志时间</td>
    <td class="datalist_title">日志事件</td>
  </tr>
  <?php unset($this->_sections['log']);
$this->_sections['log']['name'] = 'log';
$this->_sections['log']['loop'] = is_array($_loop=$this->_tpl_vars['log_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['log']['show'] = true;
$this->_sections['log']['max'] = $this->_sections['log']['loop'];
$this->_sections['log']['step'] = 1;
$this->_sections['log']['start'] = $this->_sections['log']['step'] > 0 ? 0 : $this->_sections['log']['loop']-1;
if ($this->_sections['log']['show']) {
    $this->_sections['log']['total'] = $this->_sections['log']['loop'];
    if ($this->_sections['log']['total'] == 0)
        $this->_sections['log']['show'] = false;
} else
    $this->_sections['log']['total'] = 0;
if ($this->_sections['log']['show']):

            for ($this->_sections['log']['index'] = $this->_sections['log']['start'], $this->_sections['log']['iteration'] = 1;
                 $this->_sections['log']['iteration'] <= $this->_sections['log']['total'];
                 $this->_sections['log']['index'] += $this->_sections['log']['step'], $this->_sections['log']['iteration']++):
$this->_sections['log']['rownum'] = $this->_sections['log']['iteration'];
$this->_sections['log']['index_prev'] = $this->_sections['log']['index'] - $this->_sections['log']['step'];
$this->_sections['log']['index_next'] = $this->_sections['log']['index'] + $this->_sections['log']['step'];
$this->_sections['log']['first']      = ($this->_sections['log']['iteration'] == 1);
$this->_sections['log']['last']       = ($this->_sections['log']['iteration'] == $this->_sections['log']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';" onmouseout="javascript:this.bgColor='#FFFFFF';">
  	<td><input type="checkbox" name="checkboxes[]" value="<?php echo $this->_tpl_vars['log_list'][$this->_sections['log']['index']]['log_id']; ?>
" /></td>
	<td><?php echo $this->_tpl_vars['log_list'][$this->_sections['log']['index']]['admin_name']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['log_list'][$this->_sections['log']['index']]['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%m:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%m:%S")); ?>
</td>
    <td><?php echo $this->_tpl_vars['log_list'][$this->_sections['log']['index']]['log_value']; ?>
</td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="4">没有找到任何记录</td>
  </tr>
  <?php endif; ?>
  <tr>
  	<td><input type="checkbox" name="selectall" onClick="select_all(this, 'checkboxes')" /></td><td><input type="submit" value="删除" /><input type="hidden" name="act" value="del" /></td>
  </tr>
</table>
<div class="page"><?php echo $this->_tpl_vars['page']; ?>
</div>
</form>
<script type="text/javascript">
function select_all(obj, check){
	check = "checkboxes";
	var elems = obj.form.getElementsByTagName("INPUT");
 	for (var i=0; i < elems.length; i++)
 		{
 			if (elems[i].name == check || elems[i].name == check + "[]")
 				{
 					elems[i].checked = obj.checked;
 				}
		 }
}
</script>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>