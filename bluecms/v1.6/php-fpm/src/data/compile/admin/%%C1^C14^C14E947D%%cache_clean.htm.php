<?php /* Smarty version 2.6.22, created on 2018-11-20 11:05:06
         compiled from cache_clean.htm */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p class="action_nav">BlueCMS管理中心 - <?php echo $this->_tpl_vars['current_act']; ?>
</p>
<div id="clean_cache">
	<h4>技巧提示</h4>
	<ul><li>当系统进行了数据恢复、升级或者出现异常的时候，您可以使用本功能重新生成缓存。更新缓存的时候，可能让服务器负载升高，请尽量避开会员访问的高峰时间，以免出现不可预知的错误</li>
				<li>数据缓存：更新系统的全局设置、附加属性、栏目列表、地区列表等缓存</li>
				<li>页面缓存：更新系统模板等界面类缓存文件，当您修改了模板的时候执行</li>
				</ul>
</div>
<div>
<form method="post" action="cache.php?act=do_clean"><br />
<h4><input type="checkbox" name="type[]" value="data" id="data_cache" class="checkbox" checked /><label for="data_cache">数据缓存</label>
<input type="checkbox" name="type[]" value="tpl" id="tpl_cache" class="checkbox" checked /><label for="tpl_cache">页面缓存</label></h4><br />
<p class="submit"><input type="submit" class="btn" name="confirmed" value="确定"> &nbsp;
<input type="button" class="btn" value="取消" onClick="history.go(-1);"></p></form><br /></div>
<br/>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>