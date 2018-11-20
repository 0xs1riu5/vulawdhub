<?php /* Smarty version 2.6.22, created on 2018-11-20 11:07:34
         compiled from nav.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="nav.php?act=add">添加新链接</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="datalist_title">导航名称</td>
    <td class="datalist_title">导航链接</td>
    <td class="datalist_title">是否打开新窗口</td>
    <td class="datalist_title">类型</td>
    <td class="datalist_title">顺序</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['nav']);
$this->_sections['nav']['name'] = 'nav';
$this->_sections['nav']['loop'] = is_array($_loop=$this->_tpl_vars['navlist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['nav']['show'] = true;
$this->_sections['nav']['max'] = $this->_sections['nav']['loop'];
$this->_sections['nav']['step'] = 1;
$this->_sections['nav']['start'] = $this->_sections['nav']['step'] > 0 ? 0 : $this->_sections['nav']['loop']-1;
if ($this->_sections['nav']['show']) {
    $this->_sections['nav']['total'] = $this->_sections['nav']['loop'];
    if ($this->_sections['nav']['total'] == 0)
        $this->_sections['nav']['show'] = false;
} else
    $this->_sections['nav']['total'] = 0;
if ($this->_sections['nav']['show']):

            for ($this->_sections['nav']['index'] = $this->_sections['nav']['start'], $this->_sections['nav']['iteration'] = 1;
                 $this->_sections['nav']['iteration'] <= $this->_sections['nav']['total'];
                 $this->_sections['nav']['index'] += $this->_sections['nav']['step'], $this->_sections['nav']['iteration']++):
$this->_sections['nav']['rownum'] = $this->_sections['nav']['iteration'];
$this->_sections['nav']['index_prev'] = $this->_sections['nav']['index'] - $this->_sections['nav']['step'];
$this->_sections['nav']['index_next'] = $this->_sections['nav']['index'] + $this->_sections['nav']['step'];
$this->_sections['nav']['first']      = ($this->_sections['nav']['iteration'] == 1);
$this->_sections['nav']['last']       = ($this->_sections['nav']['iteration'] == $this->_sections['nav']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
    <td><?php echo $this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['navname']; ?>
</td>
      <td><?php echo $this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['navlink']; ?>
</td>
      <td><?php if ($this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['opennew'] == 0): ?>否<?php else: ?>是<?php endif; ?></td>
	  <td><?php if ($this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['type'] == '1'): ?>顶部<?php else: ?>底部<?php endif; ?></td>
      <td><?php echo $this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['showorder']; ?>
</td>
	  <td align="center"><a href="nav.php?act=edit&navid=<?php echo $this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['navid']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="nav.php?act=del&navid=<?php echo $this->_tpl_vars['navlist'][$this->_sections['nav']['index']]['navid']; ?>
">删除</a></td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="6">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
</table>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>