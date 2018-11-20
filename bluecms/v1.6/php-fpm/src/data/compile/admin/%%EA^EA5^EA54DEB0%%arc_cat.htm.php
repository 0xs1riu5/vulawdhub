<?php /* Smarty version 2.6.22, created on 2018-11-20 11:01:59
         compiled from arc_cat.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="arc_cat.php?act=add">添加栏目</a></p>
  <table width="100%" cellpadding="3" cellspacing="1">
  <tr>
      <td class="datalist_title">分类名称</td>
      <td class="datalist_title">管理</td>
      <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php if ($this->_tpl_vars['parentid'] != 0): ?><tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';"><td colspan="4"><a href="arc_cat.php?pid=<?php echo $this->_tpl_vars['dparentid']; ?>
">返回上级</a></td></tr><?php endif; ?>
    <?php unset($this->_sections['cat']);
$this->_sections['cat']['name'] = 'cat';
$this->_sections['cat']['loop'] = is_array($_loop=$this->_tpl_vars['cat_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat']['show'] = true;
$this->_sections['cat']['max'] = $this->_sections['cat']['loop'];
$this->_sections['cat']['step'] = 1;
$this->_sections['cat']['start'] = $this->_sections['cat']['step'] > 0 ? 0 : $this->_sections['cat']['loop']-1;
if ($this->_sections['cat']['show']) {
    $this->_sections['cat']['total'] = $this->_sections['cat']['loop'];
    if ($this->_sections['cat']['total'] == 0)
        $this->_sections['cat']['show'] = false;
} else
    $this->_sections['cat']['total'] = 0;
if ($this->_sections['cat']['show']):

            for ($this->_sections['cat']['index'] = $this->_sections['cat']['start'], $this->_sections['cat']['iteration'] = 1;
                 $this->_sections['cat']['iteration'] <= $this->_sections['cat']['total'];
                 $this->_sections['cat']['index'] += $this->_sections['cat']['step'], $this->_sections['cat']['iteration']++):
$this->_sections['cat']['rownum'] = $this->_sections['cat']['iteration'];
$this->_sections['cat']['index_prev'] = $this->_sections['cat']['index'] - $this->_sections['cat']['step'];
$this->_sections['cat']['index_next'] = $this->_sections['cat']['index'] + $this->_sections['cat']['step'];
$this->_sections['cat']['first']      = ($this->_sections['cat']['iteration'] == 1);
$this->_sections['cat']['last']       = ($this->_sections['cat']['iteration'] == $this->_sections['cat']['total']);
?>
	<tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
      <td><?php echo $this->_tpl_vars['cat_list'][$this->_sections['cat']['index']]['cat_name']; ?>
</td>
      <td><?php if ($this->_tpl_vars['cat_list'][$this->_sections['cat']['index']]['is_havechild'] == 1): ?><a href="arc_cat.php?pid=<?php echo $this->_tpl_vars['cat_list'][$this->_sections['cat']['index']]['cat_id']; ?>
">进入下级</a><?php else: ?>没有下级分类了<?php endif; ?></td>
      <td align="center"><a href="arc_cat.php?act=edit&cid=<?php echo $this->_tpl_vars['cat_list'][$this->_sections['cat']['index']]['cat_id']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="arc_cat.php?act=del&cid=<?php echo $this->_tpl_vars['cat_list'][$this->_sections['cat']['index']]['cat_id']; ?>
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