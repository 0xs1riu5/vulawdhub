<?php if ($this->_var['index_ad'] == 'sys'): ?>
  <script type="text/javascript">
  var swf_width=484;
  var swf_height=200;
  </script>
  <script type="text/javascript" src="data/flashdata/<?php echo $this->_var['flash_theme']; ?>/cycle_image.js"></script>
<?php elseif ($this->_var['index_ad'] == 'cus'): ?>
  <?php if ($this->_var['ad']['ad_type'] == 0): ?>
    <a href="<?php echo $this->_var['ad']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['content']; ?>" width="484" height="200" border="0"></a>
  <?php elseif ($this->_var['ad']['ad_type'] == 1): ?>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="484" height="200">
      <param name="movie" value="<?php echo $this->_var['ad']['content']; ?>" />
      <param name="quality" value="high" />
      <embed src="<?php echo $this->_var['ad']['content']; ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="484" height="200"></embed>
    </object>
  <?php elseif ($this->_var['ad']['ad_type'] == 2): ?>
    <?php echo $this->_var['ad']['content']; ?>
  <?php elseif ($this->_var['ad']['ad_type'] == 3): ?>
    <a href="<?php echo $this->_var['ad']['url']; ?>" target="_blank"><?php echo $this->_var['ad']['content']; ?></a>
  <?php endif; ?>
<?php else: ?>
<?php endif; ?>