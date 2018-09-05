<?php if ($this->_var['promotion_info']): ?>

<div class="box">
 <div class="box_1">
  <h3><span><?php echo $this->_var['lang']['promotion_info']; ?></span></h3>
  <div class="boxCenterList RelaArticle">
    <?php $_from = $this->_var['promotion_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
    <?php if ($this->_var['item']['type'] == "snatch"): ?>
    <a href="snatch.php" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?>"><?php echo $this->_var['lang']['snatch_promotion']; ?></a>
    <?php elseif ($this->_var['item']['type'] == "group_buy"): ?>
    <a href="group_buy.php" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?>"><?php echo $this->_var['lang']['group_promotion']; ?></a>
    <?php elseif ($this->_var['item']['type'] == "auction"): ?>
    <a href="auction.php" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?>"><?php echo $this->_var['lang']['auction_promotion']; ?></a>
    <?php elseif ($this->_var['item']['type'] == "favourable"): ?>
    <a href="activity.php" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?>"><?php echo $this->_var['lang']['favourable_promotion']; ?></a>
    <?php elseif ($this->_var['item']['type'] == "package"): ?>
    <a href="package.php" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?>"><?php echo $this->_var['lang']['package_promotion']; ?></a>
    <?php endif; ?>
    <a href="<?php echo $this->_var['item']['url']; ?>" title="<?php echo $this->_var['lang'][$this->_var['item']['type']]; ?> <?php echo $this->_var['item']['act_name']; ?><?php echo $this->_var['item']['time']; ?>" style="background:none; padding-left:0px;"><?php echo $this->_var['item']['act_name']; ?></a><br />
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </div>
 </div>
</div>
<div class="blank5"></div>
<?php endif; ?>