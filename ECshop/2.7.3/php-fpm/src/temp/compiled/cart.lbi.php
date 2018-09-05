<?php echo $this->smarty_insert_scripts(array('files'=>'transport.js')); ?>
<div class="cart" id="ECS_CARTINFO">
 <?php 
$k = array (
  'name' => 'cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
</div>
<div class="blank5"></div>
