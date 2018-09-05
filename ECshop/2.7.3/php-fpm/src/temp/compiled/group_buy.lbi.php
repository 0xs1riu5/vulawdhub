<?php if ($this->_var['group_buy_goods']): ?>
<div class="box">
 <div class="box_1">
  <h3><span><?php echo $this->_var['lang']['group_buy_goods']; ?></span><a href="group_buy.php"><img src="themes/default/images/more.gif"></a></h3>
    <div class="centerPadd">
    <div class="clearfix goodsBox" style="border:none;">
      <?php $_from = $this->_var['group_buy_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
      <div class="goodsItem">
           <a href="<?php echo $this->_var['goods']['url']; ?>"><img src="<?php echo $this->_var['goods']['thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" class="goodsimg" /></a><br />
					 <p><a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><?php echo htmlspecialchars($this->_var['goods']['short_style_name']); ?></a></p>
           <font class="shop_s"><?php echo $this->_var['goods']['last_price']; ?></font>
        </div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    </div>
 </div>
</div>
<div class="blank5"></div>
<?php endif; ?>