<?php /* Smarty version 2.6.22, created on 2018-11-20 11:05:09
         compiled from flash.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="flash.php?act=add">添加新flash</a></p>
  <table width="100%" cellpadding="3" cellspacing="1">
  <tr>
      <td class="datalist_title" width="250">Flash图片路径</td>
      <td class="datalist_title" width="250">链接</td>
      <td class="datalist_title">显示顺序</td>
      <td class="datalist_title" align="center">操作</td>
  </tr>
    <?php unset($this->_sections['flash']);
$this->_sections['flash']['name'] = 'flash';
$this->_sections['flash']['loop'] = is_array($_loop=$this->_tpl_vars['flash_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['flash']['show'] = true;
$this->_sections['flash']['max'] = $this->_sections['flash']['loop'];
$this->_sections['flash']['step'] = 1;
$this->_sections['flash']['start'] = $this->_sections['flash']['step'] > 0 ? 0 : $this->_sections['flash']['loop']-1;
if ($this->_sections['flash']['show']) {
    $this->_sections['flash']['total'] = $this->_sections['flash']['loop'];
    if ($this->_sections['flash']['total'] == 0)
        $this->_sections['flash']['show'] = false;
} else
    $this->_sections['flash']['total'] = 0;
if ($this->_sections['flash']['show']):

            for ($this->_sections['flash']['index'] = $this->_sections['flash']['start'], $this->_sections['flash']['iteration'] = 1;
                 $this->_sections['flash']['iteration'] <= $this->_sections['flash']['total'];
                 $this->_sections['flash']['index'] += $this->_sections['flash']['step'], $this->_sections['flash']['iteration']++):
$this->_sections['flash']['rownum'] = $this->_sections['flash']['iteration'];
$this->_sections['flash']['index_prev'] = $this->_sections['flash']['index'] - $this->_sections['flash']['step'];
$this->_sections['flash']['index_next'] = $this->_sections['flash']['index'] + $this->_sections['flash']['step'];
$this->_sections['flash']['first']      = ($this->_sections['flash']['iteration'] == 1);
$this->_sections['flash']['last']       = ($this->_sections['flash']['iteration'] == $this->_sections['flash']['total']);
?>
	<tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
      <td><?php echo $this->_tpl_vars['flash_list'][$this->_sections['flash']['index']]['image_path']; ?>
</td>
      <td><?php if ($this->_tpl_vars['flash_list'][$this->_sections['flash']['index']]['image_link']): ?><?php echo $this->_tpl_vars['flash_list'][$this->_sections['flash']['index']]['image_link']; ?>
<?php else: ?>无<?php endif; ?></td>
      <td><?php echo $this->_tpl_vars['flash_list'][$this->_sections['flash']['index']]['show_order']; ?>
</td>
      <td align="center"><a href="flash.php?act=edit&image_id=<?php echo $this->_tpl_vars['flash_list'][$this->_sections['flash']['index']]['image_id']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="flash.php?act=del&image_id=<?php echo $this->_tpl_vars['flash_list'][$this->_sections['flash']['index']]['image_id']; ?>
">删除</a></td>
    </tr>
    <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="5">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
  </table>
<br/>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>