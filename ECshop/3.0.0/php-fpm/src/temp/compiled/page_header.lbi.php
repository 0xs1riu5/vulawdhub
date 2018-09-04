
<script type="text/javascript">
var process_request = "<?php echo $this->_var['lang']['process_request']; ?>";
</script>
<div class="top-bar">
  <div class="fd_top fd_top1">
    <div class="bar-left">
          <div class="top_menu1"> <?php echo $this->smarty_insert_scripts(array('files'=>'transport.js,utils.js')); ?> <font id="ECS_MEMBERZONE"><?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?> </font> </div>
    </div>
    <div class="bar-cart">
      <div class="fl cart-yh">
        <a href="user.php" class="">用户中心</a>
      </div>
       <?php echo $this->smarty_insert_scripts(array('files'=>'transport.js')); ?>
      <div class="cart" id="ECS_CARTINFO"> <?php 
$k = array (
  'name' => 'cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?> </div>
    </div>
  </div>
</div>
<div class="nav-menu">
  <div class="wrap">
    <div class="logo"><a href="index.php" name="top"><img src="themes/default/images/logo.jpg" /></a></div>
    <div id="mainNav" class="clearfix maxmenu">
      <div class="m_left">
      <ul>
        <li><a href="index.php"<?php if ($this->_var['navigator_list']['config']['index'] == 1): ?> class="cur"<?php endif; ?>><?php echo $this->_var['lang']['home']; ?></a></li>
        <?php $_from = $this->_var['navigator_list']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['nav_middle_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_middle_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['nav_middle_list']['iteration']++;
?>
        <?php if (($this->_foreach['nav_middle_list']['iteration'] == $this->_foreach['nav_middle_list']['total'])): ?>
        <li><a href="<?php echo $this->_var['nav']['url']; ?>" 
        
          <?php if ($this->_var['nav']['opennew'] == 1): ?>
          target="_blank"
          <?php endif; ?>
          ><?php echo $this->_var['nav']['name']; ?></a></li>
        <?php else: ?>
        <li><a href="<?php echo $this->_var['nav']['url']; ?>" 
        
          <?php if ($this->_var['nav']['opennew'] == 1): ?>
          target="_blank"
          <?php endif; ?>
          ><?php echo $this->_var['nav']['name']; ?></a></li>
        <?php endif; ?>
        <?php if ($this->_var['nav']['active'] == 1): ?>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      </ul>
      </div>
    </div>
    <div class="serach-box">
      <form id="searchForm" name="searchForm" method="get" action="search.php" onSubmit="return checkSearchForm()" class="f_r">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="135"><input name="keywords" type="text" id="keyword" value="<?php echo htmlspecialchars($this->_var['search_keywords']); ?>" class="B_input"  /></td>
            <td><input name="imageField" type="submit" value="搜索" class="go" style="cursor:pointer;" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<div class="clear0 "></div>
