<?php if ($this->_var['invoice_list']): ?>
<style type="text/css">
.boxCenterList form{display:inline;}
.boxCenterList form a{color:#404040; text-decoration:underline;}
</style>
<div class="box">
 <div class="box_1">
  <h3><span><?php echo $this->_var['lang']['shipping_query']; ?></span></h3>
  <div class="boxCenterList">
    <?php $_from = $this->_var['invoice_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'invoice');if (count($_from)):
    foreach ($_from AS $this->_var['invoice']):
?>
   <?php echo $this->_var['lang']['order_number']; ?> <?php echo $this->_var['invoice']['order_sn']; ?><br />
   <?php echo $this->_var['lang']['consignment']; ?> <?php echo $this->_var['invoice']['invoice_no']; ?>
   <div class="blank"></div>
   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </div>
 </div>
</div>
<div class="blank5"></div>
<?php endif; ?>