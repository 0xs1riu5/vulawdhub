<?php /* Smarty version 2.6.22, created on 2018-11-20 11:02:10
         compiled from comment.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'mb_substr', 'comment.htm', 17, false),array('modifier', 'date_format', 'comment.htm', 20, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<form name="comment_form" method="post" action="comment.php" onsubmit="return check();">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
  	<td class="datalist_title">选择</td>
    <td class="datalist_title">评论内容</td>
    <td class="datalist_title">评论类型</td>
    <td class="datalist_title">评论人</td>
    <td class="datalist_title">评论时间</td>
    <td class="datalist_title">是否审核</td>
    <td class="datalist_title" align="center">操作</td>
  </tr>
  <?php unset($this->_sections['comment']);
$this->_sections['comment']['name'] = 'comment';
$this->_sections['comment']['loop'] = is_array($_loop=$this->_tpl_vars['comment_list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['comment']['show'] = true;
$this->_sections['comment']['max'] = $this->_sections['comment']['loop'];
$this->_sections['comment']['step'] = 1;
$this->_sections['comment']['start'] = $this->_sections['comment']['step'] > 0 ? 0 : $this->_sections['comment']['loop']-1;
if ($this->_sections['comment']['show']) {
    $this->_sections['comment']['total'] = $this->_sections['comment']['loop'];
    if ($this->_sections['comment']['total'] == 0)
        $this->_sections['comment']['show'] = false;
} else
    $this->_sections['comment']['total'] = 0;
if ($this->_sections['comment']['show']):

            for ($this->_sections['comment']['index'] = $this->_sections['comment']['start'], $this->_sections['comment']['iteration'] = 1;
                 $this->_sections['comment']['iteration'] <= $this->_sections['comment']['total'];
                 $this->_sections['comment']['index'] += $this->_sections['comment']['step'], $this->_sections['comment']['iteration']++):
$this->_sections['comment']['rownum'] = $this->_sections['comment']['iteration'];
$this->_sections['comment']['index_prev'] = $this->_sections['comment']['index'] - $this->_sections['comment']['step'];
$this->_sections['comment']['index_next'] = $this->_sections['comment']['index'] + $this->_sections['comment']['step'];
$this->_sections['comment']['first']      = ($this->_sections['comment']['iteration'] == 1);
$this->_sections['comment']['last']       = ($this->_sections['comment']['iteration'] == $this->_sections['comment']['total']);
?>
  <tr class="datalist" onmousemove="javascript:this.bgColor='#F7FBFE';"onmouseout="javascript:this.bgColor='#FFFFFF';">
  	<td><input type="checkbox" name="checkboxes[]" value="<?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['com_id']; ?>
" /></td>
    <td onclick="get_detail('detail<?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['com_id']; ?>
');" style="cursor:hand;width:200px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['content'])) ? $this->_run_mod_handler('mb_substr', true, $_tmp, 0, 10) : smarty_modifier_mb_substr($_tmp, 0, 10)); ?>
</td>
      <td><?php if ($this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['type'] == 0): ?>分类信息<?php else: ?>新闻<?php endif; ?></td>
	  <td><?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['user_name']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['pub_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
      <td><?php if ($this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['is_check'] == 0): ?>未审核<?php else: ?>已审核<?php endif; ?></td>
	  <td align="center"><a href="comment.php?act=edit&type=<?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['type']; ?>
&com_id=<?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['com_id']; ?>
">编辑</a>&nbsp;|&nbsp;<a href="comment.php?act=del&com_id=<?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['com_id']; ?>
">删除</a></td>
  </tr>
  <tr><td></td><td id="detail<?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['com_id']; ?>
" style="display:none" colspan="6"><?php echo $this->_tpl_vars['comment_list'][$this->_sections['comment']['index']]['content']; ?>
</td></tr>
  <?php endfor; else: ?>
  <tr>
    <td class="datalist" colspan="6">没有找到任何记录</td>
  </tr>
    <?php endif; ?>
  <tr>
  	<td><input type="checkbox" name="selectall" onClick="select_all(this, 'checkboxes')" /></td>
  	<td><select name="act">
  			<option value="">批量操作</option>
  			<option value="check">审核</option>
  			<option value="del">删除</option>
  		</select></td>
  </tr>
  <tr><td>&nbsp;</td><td><input type="submit" value="提交" /></td></tr>
</table>
</form>
<br>
<div class="page"><?php echo $this->_tpl_vars['page']; ?>
</div>
<script language="javascript">
	function $(objectId)
	{
		 return document.getElementById(objectId);
	}

	function get_detail(objname)
	{
	    var obj = $(objname);
	    if(obj.style.display == "none")
	    {
	        obj.style.display = "block";
	    }
	    else
	    {
	        obj.style.display = "none";
	    }

	    return false;
	}
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
	function check(){
		if(comment_form.act.value==''){
			alert('请选择您所需要的操作！');
			comment_form.act.focus();
			return false;
		}
	}
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>