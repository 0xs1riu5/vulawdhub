
<div class="category_info">
  <div id="category_tree">
    <?php $_from = $this->_var['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
    <div class="cat-box">
      <div class="cat1"><a href="<?php echo $this->_var['cat']['url']; ?>"><?php echo htmlspecialchars($this->_var['cat']['name']); ?></a></div>
      <?php if ($this->_var['cat']['cat_id']): ?>
      <div class="cat2-box">
      
        <?php $_from = $this->_var['cat']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
        <div class="cat2 clearfix">
          <a class="cat2-link" href="<?php echo $this->_var['child']['url']; ?>"><?php echo htmlspecialchars($this->_var['child']['name']); ?></a>
          <?php if ($this->_var['child']['cat_id']): ?>
          <div class="cat3-block"></div>
          <div class="cat3-box">
            
            <?php $_from = $this->_var['child']['cat_id']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'childer');if (count($_from)):
    foreach ($_from AS $this->_var['childer']):
?>
              <a href="<?php echo $this->_var['childer']['url']; ?>"><?php echo htmlspecialchars($this->_var['childer']['name']); ?></a>&nbsp;&nbsp;
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        
      </div>
      <?php endif; ?>

    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <div class="clear0"></div>
  </div>
  <div class="clear0"></div>
</div>

 
