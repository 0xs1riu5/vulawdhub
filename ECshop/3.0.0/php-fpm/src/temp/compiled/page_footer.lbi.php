<div class="foot-body">
  <div class="bads"><img src="themes/default/images/bottom.jpg"></div>
  <div class="clear10"></div>
  
 <?php if ($this->_var['helps']): ?>
    <div class="foot-help">
      <?php $_from = $this->_var['helps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'help_cat');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['help_cat']):
        $this->_foreach['foo']['iteration']++;
?>
        <?php if ($this->_foreach['foo']['iteration'] < 5): ?>
        <dl>
          <dt class="xs-<?php echo $this->_foreach['foo']['iteration']; ?>"><?php echo $this->_var['help_cat']['cat_name']; ?></dt>
            <?php $_from = $this->_var['help_cat']['article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
          <dd><a href="<?php echo $this->_var['item']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['item']['title']); ?>"><?php echo $this->_var['item']['short_title']; ?></a></dd>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        </dl>
        <?php endif; ?> 
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        <div class="foot-weixin">
          <div class="weixin-txt">关注demo微信</div>
          <div class="weixin-pic">
            <img src="themes/default/images/weixin.jpg">
          </div>
        </div>
    </div>
    <?php endif; ?> 
    
   
  
  <div class="blank"></div>
  
<div class="footer_info"> <?php echo $this->_var['copyright']; ?>
      <?php echo $this->_var['shop_address']; ?> <?php echo $this->_var['shop_postcode']; ?><br />
      <?php $_from = $this->_var['lang']['p_y']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pv');if (count($_from)):
    foreach ($_from AS $this->_var['pv']):
?><?php echo $this->_var['pv']; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?><?php echo $this->_var['licensed']; ?><br />
      <?php if ($this->_var['stats_code']): ?>
      <div ><?php echo $this->_var['stats_code']; ?></div>
      <?php endif; ?>
    </div>
  <div class="clear10"></div>
</div>
 

 

