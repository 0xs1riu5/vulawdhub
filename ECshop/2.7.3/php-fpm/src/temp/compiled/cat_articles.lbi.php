<div class="box">
 <div class="box_1">
  <h3>
  <span><a href="<?php echo $this->_var['articles_cat']['url']; ?>"><?php echo htmlspecialchars($this->_var['articles_cat']['name']); ?></a></span>
  <a href="<?php echo $this->_var['articles_cat']['url']; ?>"><img src="themes/default/images/more.gif" alt="more" /></a>
  </h3>
  <div class="boxCenterList RelaArticle">
  <?php $_from = $this->_var['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article_item');if (count($_from)):
    foreach ($_from AS $this->_var['article_item']):
?>
  <a href="<?php echo $this->_var['article_item']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['article_item']['title']); ?>"><?php echo $this->_var['article_item']['short_title']; ?></a> <?php echo $this->_var['article_item']['add_time']; ?><br />
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </div>
 </div>
</div>
<div class="blank5"></div>
