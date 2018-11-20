<?php /* Smarty version 2.6.22, created on 2018-11-20 12:27:39
         compiled from card.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'card.htm', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="card.php?act=add">添加一种充值卡</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="datalist_title">充值卡名称</td>
    <td class="datalist_title">面值</td>
    <td class="datalist_title">价格</td>
    <td class="datalist_title">是否启用</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['card_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['c']['show'] = true;
$this->_sections['c']['max'] = $this->_sections['c']['loop'];
$this->_sections['c']['step'] = 1;
$this->_sections['c']['start'] = $this->_sections['c']['step'] > 0 ? 0 : $this->_sections['c']['loop']-1;
if ($this->_sections['c']['show']) {
    $this->_sections['c']['total'] = $this->_sections['c']['loop'];
    if ($this->_sections['c']['total'] == 0)
        $this->_sections['c']['show'] = false;
} else
    $this->_sections['c']['total'] = 0;
if ($this->_sections['c']['show']):

            for ($this->_sections['c']['index'] = $this->_sections['c']['start'], $this->_sections['c']['iteration'] = 1;
                 $this->_sections['c']['iteration'] <= $this->_sections['c']['total'];
                 $this->_sections['c']['index'] += $this->_sections['c']['step'], $this->_sections['c']['iteration']++):
$this->_sections['c']['rownum'] = $this->_sections['c']['iteration'];
$this->_sections['c']['index_prev'] = $this->_sections['c']['index'] - $this->_sections['c']['step'];
$this->_sections['c']['index_next'] = $this->_sections['c']['index'] + $this->_sections['c']['step'];
$this->_sections['c']['first']      = ($this->_sections['c']['iteration'] == 1);
$this->_sections['c']['last']       = ($this->_sections['c']['iteration'] == $this->_sections['c']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['card_list'][$this->_sections['c']['index']]['name'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 10) : smarty_modifier_mb_substr($_tmp, 0, 10)); ?>
</td>
      <td><?php echo $this->_tpl_vars['card_list'][$this->_sections['c']['index']]['value']; ?>
</td>
      <td><?php echo $this->_tpl_vars['card_list'][$this->_sections['c']['index']]['price']; ?>
</td>
      <td><?php if ($this->_tpl_vars['card_list'][$this->_sections['c']['index']]['is_close'] == 0): ?>启用<?php else: ?>禁用<?php endif; ?></td>
	  <td align="center"><a href="card.php?act=edit&id=<?php echo $this->_tpl_vars['card_list'][$this->_sections['c']['index']]['id']; ?>
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