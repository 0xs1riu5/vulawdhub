<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v3.0.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />
<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS|<?php echo $this->_var['page_title']; ?>" href="<?php echo $this->_var['feed_url']; ?>" />
<link rel="stylesheet" type="text/css" href="themes/default/images/swiper.min.css">
<script language='javascript' src='themes/default/js/swiper.min.js' type='text/javascript' charset='utf-8'></script>

<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,index.js')); ?>
</head>
<body>
<?php echo $this->fetch('library/page_header.lbi'); ?>
<script>
if (Object.prototype.toJSONString){
      var oldToJSONString = Object.toJSONString;
      Object.prototype.toJSONString = function(){
        if (arguments.length > 0){
          return false;
        }else{
          return oldToJSONString.apply(this, arguments);
        }
}}</script>
<div class="indexpage clearfix">
  <div class="index-cat">
    <?php echo $this->fetch('library/index_category_tree.lbi'); ?> 
  </div>
  <div class="index-banner"><?php echo $this->fetch('library/index_banner.lbi'); ?> </div>
</div>
<div class="indexpage clearfix index-ad">
  <div class="ad-tg">
    
<?php echo $this->fetch('library/ad_position.lbi'); ?>

  </div>
  <div class="ad-lb">
    <?php echo $this->fetch('library/index_lad.lbi'); ?>
  </div>
</div>
<div class="index-body">
  <div class="indexpage">
    <div class="body-goods">
      <div class="goods-title">1F 家用电器</div>
      <div class="clearfix goods-wrap">
        <div class="goods-leftad">
          <?php echo $this->fetch('library/f1_ad.lbi'); ?>
        </div>
        <div class="goods-right">
            
<?php echo $this->fetch('library/cat_goods.lbi'); ?>

        </div>
      </div>
      <div class="goods-title">2F 数码时尚</div>
      <div class="clearfix goods-wrap">
        <div class="goods-leftad">
          <?php echo $this->fetch('library/f2_ad.lbi'); ?>
        </div>
        <div class="goods-right">
            
<?php echo $this->fetch('library/cat_goods.lbi'); ?>

        </div>
      </div>
      <div class="goods-title">3F 家居生活</div>
      <div class="clearfix goods-wrap">
        <div class="goods-leftad">
          <?php echo $this->fetch('library/f3_ad.lbi'); ?>
        </div>
        <div class="goods-right">
            
<?php echo $this->fetch('library/cat_goods.lbi'); ?>

        </div>
      </div>
      <div class="goods-title">热门商品推荐</div>
      <div class="clearfix goods-wrap hot-goods">
            
<?php echo $this->fetch('library/recommend_hot.lbi'); ?>

      </div>

    </div>
  </div>
</div>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>
