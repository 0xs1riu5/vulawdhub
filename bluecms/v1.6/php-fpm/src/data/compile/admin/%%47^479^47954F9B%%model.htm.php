<?php /* Smarty version 2.6.22, created on 2018-11-20 11:02:06
         compiled from model.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="model.php?act=add">添加新模型</a></p>
  <table width="100%" cellpadding="3" cellspacing="1">
  <tr>
      <td class="datalist_title">模型编号</td>
      <td class="datalist_title">模型名称</td>
      <td class="datalist_title">顺序</td>
      <td class="datalist_title" align="center">操作</td>
  </tr>
    <?php unset($this->_sections['model']);
$this->_sections['model']['name'] = 'model';
$this->_sections['model']['loop'] = is_array($_loop=$this->_tpl_vars['model_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['model']['show'] = true;
$this->_sections['model']['max'] = $this->_sections['model']['loop'];
$this->_sections['model']['step'] = 1;
$this->_sections['model']['start'] = $this->_sections['model']['step'] > 0 ? 0 : $this->_sections['model']['loop']-1;
if ($this->_sections['model']['show']) {
    $this->_sections['model']['total'] = $this->_sections['model']['loop'];
    if ($this->_sections['model']['total'] == 0)
        $this->_sections['model']['show'] = false;
} else
    $this->_sections['model']['total'] = 0;
if ($this->_sections['model']['show']):

            for ($this->_sections['model']['index'] = $this->_sections['model']['start'], $this->_sections['model']['iteration'] = 1;
                 $this->_sections['model']['iteration'] <= $this->_sections['model']['total'];
                 $this->_sections['model']['index'] += $this->_sections['model']['step'], $this->_sections['model']['iteration']++):
$this->_sections['model']['rownum'] = $this->_sections['model']['iteration'];
$this->_sections['model']['index_prev'] = $this->_sections['model']['index'] - $this->_sections['model']['step'];
$this->_sections['model']['index_next'] = $this->_sections['model']['index'] + $this->_sections['model']['step'];
$this->_sections['model']['first']      = ($this->_sections['model']['iteration'] == 1);
$this->_sections['model']['last']       = ($this->_sections['model']['iteration'] == $this->_sections['model']['total']);
?>
	<tr class="datalist">
      <td><?php echo $this->_tpl_vars['model_list'][$this->_sections['model']['index']]['model_id']; ?>
</td>
      <td><?php echo $this->_tpl_vars['model_list'][$this->_sections['model']['index']]['model_name']; ?>
</td>
      <td><?php echo $this->_tpl_vars['model_list'][$this->_sections['model']['index']]['show_order']; ?>
</td>
      <td align="center"><a href="model.php?act=edit&model_id=<?php echo $this->_tpl_vars['model_list'][$this->_sections['model']['index']]['model_id']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="model.php?act=del&model_id=<?php echo $this->_tpl_vars['model_list'][$this->_sections['model']['index']]['model_id']; ?>
">删除</a></td>
    </tr>
    <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="4">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
  </table>
  <br/>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>