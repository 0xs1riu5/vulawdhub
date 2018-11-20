<?php /* Smarty version 2.6.22, created on 2018-11-20 11:06:05
         compiled from link.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
&nbsp;&nbsp;<a href="link.php?act=add">添加新链接</a></p>
  <table width="100%" cellpadding="3" cellspacing="1">
  <tr>
      <td class="datalist_title">友情链接名称</td>
      <td class="datalist_title">网址</td>
      <td class="datalist_title">logo</td>
      <td class="datalist_title">显示顺序</td>
      <td class="datalist_title" align="center">操作</td>
  </tr>
    <?php unset($this->_sections['link']);
$this->_sections['link']['name'] = 'link';
$this->_sections['link']['loop'] = is_array($_loop=$this->_tpl_vars['linklist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['link']['show'] = true;
$this->_sections['link']['max'] = $this->_sections['link']['loop'];
$this->_sections['link']['step'] = 1;
$this->_sections['link']['start'] = $this->_sections['link']['step'] > 0 ? 0 : $this->_sections['link']['loop']-1;
if ($this->_sections['link']['show']) {
    $this->_sections['link']['total'] = $this->_sections['link']['loop'];
    if ($this->_sections['link']['total'] == 0)
        $this->_sections['link']['show'] = false;
} else
    $this->_sections['link']['total'] = 0;
if ($this->_sections['link']['show']):

            for ($this->_sections['link']['index'] = $this->_sections['link']['start'], $this->_sections['link']['iteration'] = 1;
                 $this->_sections['link']['iteration'] <= $this->_sections['link']['total'];
                 $this->_sections['link']['index'] += $this->_sections['link']['step'], $this->_sections['link']['iteration']++):
$this->_sections['link']['rownum'] = $this->_sections['link']['iteration'];
$this->_sections['link']['index_prev'] = $this->_sections['link']['index'] - $this->_sections['link']['step'];
$this->_sections['link']['index_next'] = $this->_sections['link']['index'] + $this->_sections['link']['step'];
$this->_sections['link']['first']      = ($this->_sections['link']['iteration'] == 1);
$this->_sections['link']['last']       = ($this->_sections['link']['iteration'] == $this->_sections['link']['total']);
?>
	<tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
      <td><?php echo $this->_tpl_vars['linklist'][$this->_sections['link']['index']]['linkname']; ?>
</td>
      <td><?php echo $this->_tpl_vars['linklist'][$this->_sections['link']['index']]['linksite']; ?>
</td>
      <td><?php if ($this->_tpl_vars['linklist'][$this->_sections['link']['index']]['linklogo']): ?><img src="../<?php echo $this->_tpl_vars['linklist'][$this->_sections['link']['index']]['linklogo']; ?>
" widtd="88" height="31" /><?php else: ?>无图片<?php endif; ?></td>
      <td><?php echo $this->_tpl_vars['linklist'][$this->_sections['link']['index']]['showorder']; ?>
</td>
      <td align="center"><a href="link.php?act=edit&linkid=<?php echo $this->_tpl_vars['linklist'][$this->_sections['link']['index']]['linkid']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="link.php?act=del&linkid=<?php echo $this->_tpl_vars['linklist'][$this->_sections['link']['index']]['linkid']; ?>
">删除</a></td>
    </tr>
    <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="5">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
  </table>
  <div class="page"><?php echo $this->_tpl_vars['page']; ?>
</div>
<br/>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>