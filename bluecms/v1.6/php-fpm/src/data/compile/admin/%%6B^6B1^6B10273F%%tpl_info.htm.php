<?php /* Smarty version 2.6.22, created on 2018-11-20 11:07:04
         compiled from tpl_info.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<div class="main-div">
<form method="post" action="tpl_manage.php" name="tpl_form">
<div id="clean_cache">编辑过后请更新缓存</div>
<table>
  <tr>
    <td>模板文件名称:</td>
    <td><?php echo $this->_tpl_vars['tpl']['name']; ?>
</td>
  </tr>
  <tr>
    <td>模板内容:</td>
    <td><textarea rows="40" cols="85" name="tpl_content"><?php echo $this->_tpl_vars['tpl']['content']; ?>
</textarea></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" class="button" />
      <input type="reset" value="重置" class="button" />
      <input type="hidden" name="tpl_name" value="<?php echo $this->_tpl_vars['tpl']['name']; ?>
"/>
      <input type="hidden" name="act" value="do_edit" />
    </td>
  </tr>
</table>
</form>
</div>
<br>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>