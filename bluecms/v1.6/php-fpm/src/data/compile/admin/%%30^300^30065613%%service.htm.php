<?php /* Smarty version 2.6.22, created on 2018-11-20 12:27:40
         compiled from service.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'service.htm', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="datalist_title">服务名称</td>
    <td class="datalist_title">服务对象</td>
    <td class="datalist_title">服务类型</td>
    <td class="datalist_title">价格</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['p']);
$this->_sections['p']['name'] = 'p';
$this->_sections['p']['loop'] = is_array($_loop=$this->_tpl_vars['price_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['p']['show'] = true;
$this->_sections['p']['max'] = $this->_sections['p']['loop'];
$this->_sections['p']['step'] = 1;
$this->_sections['p']['start'] = $this->_sections['p']['step'] > 0 ? 0 : $this->_sections['p']['loop']-1;
if ($this->_sections['p']['show']) {
    $this->_sections['p']['total'] = $this->_sections['p']['loop'];
    if ($this->_sections['p']['total'] == 0)
        $this->_sections['p']['show'] = false;
} else
    $this->_sections['p']['total'] = 0;
if ($this->_sections['p']['show']):

            for ($this->_sections['p']['index'] = $this->_sections['p']['start'], $this->_sections['p']['iteration'] = 1;
                 $this->_sections['p']['iteration'] <= $this->_sections['p']['total'];
                 $this->_sections['p']['index'] += $this->_sections['p']['step'], $this->_sections['p']['iteration']++):
$this->_sections['p']['rownum'] = $this->_sections['p']['iteration'];
$this->_sections['p']['index_prev'] = $this->_sections['p']['index'] - $this->_sections['p']['step'];
$this->_sections['p']['index_next'] = $this->_sections['p']['index'] + $this->_sections['p']['step'];
$this->_sections['p']['first']      = ($this->_sections['p']['iteration'] == 1);
$this->_sections['p']['last']       = ($this->_sections['p']['iteration'] == $this->_sections['p']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['price_list'][$this->_sections['p']['index']]['name'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 10) : smarty_modifier_mb_substr($_tmp, 0, 10)); ?>
</td>
      <td><?php if ($this->_tpl_vars['price_list'][$this->_sections['p']['index']]['type'] == 'company'): ?>黄页<?php else: ?>分类信息<?php endif; ?></td>
      <td><?php echo $this->_tpl_vars['price_list'][$this->_sections['p']['index']]['service']; ?>
</td>
      <td><?php echo $this->_tpl_vars['price_list'][$this->_sections['p']['index']]['price']; ?>
</td>
	  <td align="center"><a href="service.php?act=edit&id=<?php echo $this->_tpl_vars['price_list'][$this->_sections['p']['index']]['id']; ?>
">编辑</a></td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="5">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
</table>
</form>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>