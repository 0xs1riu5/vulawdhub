<?php /* Smarty version 2.6.22, created on 2018-11-20 11:02:16
         compiled from setting.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<div id="clean_cache">编辑过后请更新缓存</div>
<form method="post" action="setting.php" name="config_form">
<table>
  <tr>
    <td>网站名称:</td>
    <td><input type="text" name="site_name" value="<?php echo $this->_tpl_vars['config']['site_name']; ?>
" /></td>
    <td></td>
  </tr>
  <tr>
    <td>网站链接:</td>
    <td><input type="text" name="site_url" size="30" value="<?php echo $this->_tpl_vars['config']['site_url']; ?>
" /></td>
    <td>网站URL,作为链接显示在页面</td>
  </tr>
	<tr>
    <td>description:</td>
    <td><input type="text" name="description" size="30" value="<?php echo $this->_tpl_vars['config']['description']; ?>
" /></td>
  </tr>
  <tr>
    <td>keywods:</td>
    <td><input type="text" name="keywords" size="30" value="<?php echo $this->_tpl_vars['config']['keywords']; ?>
" /></td>
  </tr>
  <tr>
    <td>热线电话:</td>
    <td><input type="text" name="tel" size="30" value="<?php echo $this->_tpl_vars['config']['tel']; ?>
" /></td>
    <td>多项请用‘|’隔开</td>
  </tr>
  <tr>
    <td>客服QQ:</td>
    <td><input type="text" name="qq" size="30" value="<?php echo $this->_tpl_vars['config']['qq']; ?>
" /></td>
    <td>多项请用‘|’隔开</td>
  </tr>
  <tr>
    <td>网站QQ群:</td>
    <td><input type="text" name="qq_group" size="30" value="<?php echo $this->_tpl_vars['config']['qq_group']; ?>
" /></td>
    <td>多项请用‘|’隔开</td>
  </tr>
  <tr>
    <td>备案号:</td>
    <td><input type="text" name="icp" size="30" value="<?php echo $this->_tpl_vars['config']['icp']; ?>
" /></td>
  </tr>
  <tr>
    <td>版权信息:</td>
    <td><textarea name="right" rows="6" cols="40"><?php echo $this->_tpl_vars['config']['right']; ?>
</textarea></td>
  </tr>
  <tr>
    <td>第三方统计代码:</td>
    <td><textarea name="count" rows="6" cols="40"><?php echo $this->_tpl_vars['config']['count']; ?>
</textarea></td>
  </tr>
  <tr>
  	<td>cookie加密码:</td>
  	<td><input type="text" name="cookie_encode" value="<?php echo $this->_tpl_vars['config']['cookie_encode']; ?>
" /></td>
  </tr>
  <tr>
  	<td>启用伪静态:</td>
  	<td><input type="radio" name="urlrewrite" value = "0" class="input_radio" <?php if ($this->_tpl_vars['config']['urlrewrite'] == 0): ?> checked<?php endif; ?>>否
		<input type="radio" name="urlrewrite" value = "1" class="input_radio" <?php if ($this->_tpl_vars['config']['urlrewrite'] == 1): ?> checked<?php endif; ?>>是</td>
  </tr>
  <tr>
  	<td>关闭站点:</td>
  	<td><input type="radio" name="isclose" value = "0" class="input_radio" <?php if ($this->_tpl_vars['config']['isclose'] == 0): ?> checked<?php endif; ?>>否
	<input type="radio" name="isclose" value = "1" class="input_radio" <?php if ($this->_tpl_vars['config']['isclose'] == 1): ?> checked<?php endif; ?>>是
		</td>
  </tr>
  <tr>
    <td>关闭原因:</td>
    <td><textarea name="reason" rows="6" cols="40"><?php echo $this->_tpl_vars['config']['reason']; ?>
</textarea></td>
  </tr>
  <tr>
  	<td>新闻是否需要审核:</td>
  	<td><input type="radio" name="news_is_check" value = "0" class="input_radio" <?php if ($this->_tpl_vars['config']['news_is_check'] == 0): ?> checked<?php endif; ?>>否
		<input type="radio" name="news_is_check" value = "1" class="input_radio" <?php if ($this->_tpl_vars['config']['news_is_check'] == 1): ?> checked<?php endif; ?>>是</td>
  </tr>
  <tr>
  	<td>分类信息是否需要审核:</td>
  	<td><input type="radio" name="info_is_check" value = "0" class="input_radio" <?php if ($this->_tpl_vars['config']['info_is_check'] == 0): ?> checked<?php endif; ?>>否
		<input type="radio" name="info_is_check" value = "1" class="input_radio" <?php if ($this->_tpl_vars['config']['info_is_check'] == 1): ?> checked<?php endif; ?>>是</td>
  </tr>
  <tr>
  	<td>评论是否需要审核:</td>
  	<td><input type="radio" name="comment_is_check" value = "0" class="input_radio" <?php if ($this->_tpl_vars['config']['comment_is_check'] == 0): ?> checked<?php endif; ?>>否
		<input type="radio" name="comment_is_check" value = "1" class="input_radio" <?php if ($this->_tpl_vars['config']['comment_is_check'] == 1): ?> checked<?php endif; ?>>是</td>
  </tr>
  <tr>
  	<td>是否开启页面压缩:</td>
  	<td><input type="radio" name="is_gzip" value = "0" class="input_radio" <?php if ($this->_tpl_vars['config']['is_gzip'] == 0): ?> checked<?php endif; ?>>否
		<input type="radio" name="is_gzip" value = "1" class="input_radio" <?php if ($this->_tpl_vars['config']['is_gzip'] == 1): ?> checked<?php endif; ?>>是</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="提交" class="button" />
      <input type="reset" value="重置" class="button" />
      <input type="hidden" name="act" value="set" />
   </td>
  </tr>
</table>
</form>
<br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>