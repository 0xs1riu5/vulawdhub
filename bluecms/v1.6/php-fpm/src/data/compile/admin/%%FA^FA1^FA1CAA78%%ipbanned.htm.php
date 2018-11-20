<?php /* Smarty version 2.6.22, created on 2018-11-20 12:27:37
         compiled from ipbanned.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ipbanned.htm', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="ipbanned.php?act=add">添加禁止IP</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="datalist_title">禁止IP</td>
    <td class="datalist_title">禁止开始时间</td>
	<td class="datalist_title">禁止周期</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['b']);
$this->_sections['b']['name'] = 'b';
$this->_sections['b']['loop'] = is_array($_loop=$this->_tpl_vars['bannedip_arr']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['b']['show'] = true;
$this->_sections['b']['max'] = $this->_sections['b']['loop'];
$this->_sections['b']['step'] = 1;
$this->_sections['b']['start'] = $this->_sections['b']['step'] > 0 ? 0 : $this->_sections['b']['loop']-1;
if ($this->_sections['b']['show']) {
    $this->_sections['b']['total'] = $this->_sections['b']['loop'];
    if ($this->_sections['b']['total'] == 0)
        $this->_sections['b']['show'] = false;
} else
    $this->_sections['b']['total'] = 0;
if ($this->_sections['b']['show']):

            for ($this->_sections['b']['index'] = $this->_sections['b']['start'], $this->_sections['b']['iteration'] = 1;
                 $this->_sections['b']['iteration'] <= $this->_sections['b']['total'];
                 $this->_sections['b']['index'] += $this->_sections['b']['step'], $this->_sections['b']['iteration']++):
$this->_sections['b']['rownum'] = $this->_sections['b']['iteration'];
$this->_sections['b']['index_prev'] = $this->_sections['b']['index'] - $this->_sections['b']['step'];
$this->_sections['b']['index_next'] = $this->_sections['b']['index'] + $this->_sections['b']['step'];
$this->_sections['b']['first']      = ($this->_sections['b']['iteration'] == 1);
$this->_sections['b']['last']       = ($this->_sections['b']['iteration'] == $this->_sections['b']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $this->_tpl_vars['bannedip_arr'][$this->_sections['b']['index']]['ip']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['bannedip_arr'][$this->_sections['b']['index']]['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
	  <td><?php echo $this->_tpl_vars['bannedip_arr'][$this->_sections['b']['index']]['exp']; ?>
&nbsp;天</td>
	  <td align="center"><a href="ipbanned.php?act=edit&ip=<?php echo $this->_tpl_vars['bannedip_arr'][$this->_sections['b']['index']]['ip']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="ipbanned.php?act=del&ip=<?php echo $this->_tpl_vars['bannedip_arr'][$this->_sections['b']['index']]['ip']; ?>
">删除</a></td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="4">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
</table>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>