<?php /* Smarty version 2.6.22, created on 2018-11-20 11:02:36
         compiled from set_cache.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<div id="clean_cache">编辑过后请更新缓存</div>
<form action="cache.php?act=do_save" method="post">
	<table border="0">
	    <tr>
	      <td width="100">缓存页面</td>
		  <td>缓存是否开启</td>
	      <td>缓存时间</td>
	    </tr>
	    <tr>
	      <td>首页:</td>
		  <td><input type="radio" name="index_pow" value="0" checked />关<input type="radio" name="index_pow" value="1" />开</td>
	      <td><input name="index" value="<?php echo $this->_tpl_vars['cache_arr']['index']; ?>
" />&nbsp;秒</td>
	    </tr>
	    <tr>
	      <td>发布信息分类页:</td>
		  <td><input type="radio" name="publish1_pow" value="0" checked />关<input type="radio" name="publish1_pow" value="1" />开</td>
	      <td><input name="publish1" value='<?php echo $this->_tpl_vars['cache_arr']['publish1']; ?>
' />&nbsp;秒</td>
	    </tr>
	    <tr>
	      <td>发布信息页:</td>
		  <td><input type="radio" name="publish2_pow" value="0" checked />关<input type="radio" name="publish2_pow" value="1" />开</td>
	      <td><input name="publish2" value='<?php echo $this->_tpl_vars['cache_arr']['publish2']; ?>
' />&nbsp;秒</td>
	    </tr>
	    <tr>
	      <td>分类信息列表页:</td>
		  <td><input type="radio" name="list_pow" value="0" checked />关<input type="radio" name="list_pow" value="1" />开</td>
	      <td><input name="list" value='<?php echo $this->_tpl_vars['cache_arr']['list']; ?>
' />&nbsp;秒</td>
	    </tr>
	    <tr>
	      <td>分类信息内容页:</td>
		  <td><input type="radio" name="info_pow" value="0" checked />关<input type="radio" name="info_pow" value="1" />开</td>
	      <td><input name="info" value='<?php echo $this->_tpl_vars['cache_arr']['info']; ?>
' />&nbsp;秒</td>
	    </tr>
		<tr>
	      <td>新闻列表页:</td>
		  <td><input type="radio" name="list_news" value="0" checked />关<input type="radio" name="list_news" value="1" />开</td>
	      <td><input name="list_news_v" value='<?php echo $this->_tpl_vars['cache_arr']['list_news_v']; ?>
' />&nbsp;秒</td>
	    </tr>
		<tr>
	      <td>新闻内容页:</td>
		  <td><input type="radio" name="news" value="0" checked />关<input type="radio" name="news" value="1" />开</td>
	      <td><input name="news_v" value='<?php echo $this->_tpl_vars['cache_arr']['news_v']; ?>
' />&nbsp;秒</td>
	    </tr>
    </table>
<div style="margin:10px;">
	<input value="更新设置" type="submit" />
	<input type="button" onclick="history.back()" value="返回" />
</div>
</form>
<br/>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>