<div class="box">
 <div class="box_1">
  <h3><span><a href="<?php echo $this->_var['goods_brand']['url']; ?>" class="f6"><?php echo htmlspecialchars($this->_var['goods_brand']['name']); ?></a></span></h3>
    <div class="centerPadd">
    <div class="clearfix goodsBox" style="border:none;">
      <?php $_from = $this->_var['brand_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
      <div class="goodsItem">
           <a href="<?php echo $this->_var['goods']['url']; ?>"><img src="<?php echo $this->_var['goods']['thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['name']); ?>" class="goodsimg" /></a><br />
           <p><a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods']['name']); ?>"><?php echo htmlspecialchars($this->_var['goods']['short_name']); ?></a></p>
            <?php if ($this->_var['goods']['promote_price'] != ""): ?>
            <font class="shop_s"><?php echo $this->_var['goods']['promote_price']; ?></font>
            <?php else: ?>
            <font class="shop_s"><?php echo $this->_var['goods']['shop_price']; ?></font>
            <?php endif; ?>
        </div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      <div class="more"><a href="<?php echo $this->_var['goods_brand']['url']; ?>"><img src="themes/default/images/more.gif" /></a></div>
    </div>
    </div>
 </div>
</div>
<div class="blank5"></div>
