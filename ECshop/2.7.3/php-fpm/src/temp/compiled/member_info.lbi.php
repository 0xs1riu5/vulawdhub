<div id="append_parent"></div>
<?php if ($this->_var['user_info']): ?>
<font style="position:relative; top:10px;">
<?php echo $this->_var['lang']['hello']; ?>，<font class="f4_b"><?php echo $this->_var['user_info']['username']; ?></font>, <?php echo $this->_var['lang']['welcome_return']; ?>！
<a href="user.php"><?php echo $this->_var['lang']['user_center']; ?></a>|
 <a href="user.php?act=logout"><?php echo $this->_var['lang']['user_logout']; ?></a>
</font>
<?php else: ?>
 <?php echo $this->_var['lang']['welcome']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
 <a href="user.php"><img src="themes/default/images/bnt_log.gif" /></a>
 <a href="user.php?act=register"><img src="themes/default/images/bnt_reg.gif" /></a>
<?php endif; ?>