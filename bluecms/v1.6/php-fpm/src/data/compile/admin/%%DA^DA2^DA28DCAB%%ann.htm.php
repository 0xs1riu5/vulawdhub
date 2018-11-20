<?php /* Smarty version 2.6.22, created on 2018-11-20 11:05:08
         compiled from ann.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'ann.htm', 37, false),array('modifier', 'date_format', 'ann.htm', 39, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="ann.php?act=add_cat">添加信息分类</a>&nbsp;&nbsp;<a href="ann.php?act=add">添加新信息</a></p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="datalist_title">分类编号</td>
    <td class="datalist_title">分类名称</td>
	<td class="datalist_title">顺序</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['c']);
$this->_sections['c']['name'] = 'c';
$this->_sections['c']['loop'] = is_array($_loop=$this->_tpl_vars['ann_c_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <td><?php echo $this->_tpl_vars['ann_c_list'][$this->_sections['c']['index']]['cid']; ?>
</td>
      <td><a href="../ann.php?cid=<?php echo $this->_tpl_vars['ann_c_list'][$this->_sections['c']['index']]['cid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['ann_c_list'][$this->_sections['c']['index']]['cat_name']; ?>
</a></td>
	  <td><?php echo $this->_tpl_vars['ann_c_list'][$this->_sections['c']['index']]['show_order']; ?>
</td>
	  <td align="center"><a href="ann.php?act=edit_cat&cid=<?php echo $this->_tpl_vars['ann_c_list'][$this->_sections['c']['index']]['cid']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="ann.php?act=del_cat&cid=<?php echo $this->_tpl_vars['ann_c_list'][$this->_sections['c']['index']]['cid']; ?>
">删除</a></td>
  </tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="3">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
</table>
<br/>
<br/>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="datalist_title">信息标题</td>
    <td class="datalist_title">信息内容</td>
    <td class="datalist_title">作者</td>
    <td class="datalist_title">添加时间</td>
    <td class="datalist_title">点击数</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['ann']);
$this->_sections['ann']['name'] = 'ann';
$this->_sections['ann']['loop'] = is_array($_loop=$this->_tpl_vars['ann_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ann']['show'] = true;
$this->_sections['ann']['max'] = $this->_sections['ann']['loop'];
$this->_sections['ann']['step'] = 1;
$this->_sections['ann']['start'] = $this->_sections['ann']['step'] > 0 ? 0 : $this->_sections['ann']['loop']-1;
if ($this->_sections['ann']['show']) {
    $this->_sections['ann']['total'] = $this->_sections['ann']['loop'];
    if ($this->_sections['ann']['total'] == 0)
        $this->_sections['ann']['show'] = false;
} else
    $this->_sections['ann']['total'] = 0;
if ($this->_sections['ann']['show']):

            for ($this->_sections['ann']['index'] = $this->_sections['ann']['start'], $this->_sections['ann']['iteration'] = 1;
                 $this->_sections['ann']['iteration'] <= $this->_sections['ann']['total'];
                 $this->_sections['ann']['index'] += $this->_sections['ann']['step'], $this->_sections['ann']['iteration']++):
$this->_sections['ann']['rownum'] = $this->_sections['ann']['iteration'];
$this->_sections['ann']['index_prev'] = $this->_sections['ann']['index'] - $this->_sections['ann']['step'];
$this->_sections['ann']['index_next'] = $this->_sections['ann']['index'] + $this->_sections['ann']['step'];
$this->_sections['ann']['first']      = ($this->_sections['ann']['iteration'] == 1);
$this->_sections['ann']['last']       = ($this->_sections['ann']['iteration'] == $this->_sections['ann']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
    <td><a href="../ann.php?ann_id=<?php echo $this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['ann_id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['title']; ?>
</a></td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['content'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 20) : smarty_modifier_mb_substr($_tmp, 0, 20)); ?>
</td>
	  <td><?php echo $this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['author']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
      <td><?php echo $this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['click']; ?>
</td>
	  <td align="center"><a href="ann.php?act=edit&ann_id=<?php echo $this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['ann_id']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="ann.php?act=del&ann_id=<?php echo $this->_tpl_vars['ann_list'][$this->_sections['ann']['index']]['ann_id']; ?>
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