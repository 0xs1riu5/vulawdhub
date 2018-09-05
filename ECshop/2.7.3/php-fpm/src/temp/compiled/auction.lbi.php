<?php if ($this->_var['auction_list']): ?>
<div class="box">
 <div class="box_1">
  <h3><span><?php echo $this->_var['lang']['auction_goods']; ?></span><a href="auction.php"><img src="themes/default/images/more.gif"></a></h3>
    <div class="centerPadd">
    <div class="clearfix goodsBox" style="border:none;">
      <?php $_from = $this->_var['auction_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'auction');if (count($_from)):
    foreach ($_from AS $this->_var['auction']):
?>
      <div class="goodsItem">
           <a href="<?php echo $this->_var['auction']['url']; ?>"><img src="<?php echo $this->_var['auction']['thumb']; ?>" alt="<?php echo htmlspecialchars($this->_var['auction']['goods_name']); ?>" class="goodsimg" /></a><br />
           <p><a href="<?php echo $this->_var['auction']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['auction']['goods_name']); ?>"><?php echo htmlspecialchars($this->_var['auction']['short_style_name']); ?></a></p>
           <font class="shop_s"><?php echo $this->_var['auction']['formated_start_price']; ?></font>
        </div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    </div>
 </div>
</div>
<div class="blank5"></div>
<?php endif; ?>