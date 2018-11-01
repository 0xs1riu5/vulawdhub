<?php include(pe_tpl('header.html'));?>
<div class="content">
	<div class="width980">
		<div class="jdt fl">
			<script type="text/javascript" src="<?php echo $pe['host_root'] ?>include/js/jquery.slide.js"></script>
			<script type="text/javascript">
			$(function(){
				$("#jdtslide").KinSlideshow({
					moveStyle:"left",
					intervalTime:3,
					mouseEvent:"mouseover",
					titleBar:{"titleBar_bgColor":""},
					titleFont:{TitleFont_size:14,TitleFont_color:"#ffffff"}
				});
			})
			</script>
			<div id="jdtslide" style="visibility:hidden;">
			<?php if(is_array($cache_ad['index_jdt'])):?>
			<?php foreach($cache_ad['index_jdt'] as $v):?>
			<a href="<?php echo $v['ad_url'] ?>"><img src="<?php echo pe_thumb($v['ad_logo']) ?>" alt="" width="728" height="300" /></a>
			<?php endforeach;?>
			<?php endif;?>
			</div>
		</div>
		<div class="fr action_list">
			<div class="action_tt"><h3><?php echo $cache_class[1]['class_name'] ?></h3></div>
			<ul>
				<?php foreach($notice_list as $v):?>
				<li><a href="<?php echo pe_url('article-'.$v['article_id']) ?>" title="<?php echo $v['article_name'] ?>" target="_blank"><?php echo $v['article_name'] ?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	<div class="index_main">
		<div class="pro_tuijian">
			<div class="index_fenlei_tt" style="border:0;">
				<h3><span class="cred1">商品推荐</span></h3>
			</div>
			<div class="tuijian_list">
				<?php foreach($product_tuijian as $k=>$v):?>
				<div class="prolist_1" <?php if($k==4):?>style="background:none"<?php endif?>>
					<p class="prolist_img"><a href="<?php echo pe_url('product-'.$v['product_id']) ?>" title="<?php echo $v['product_name'] ?>" target="_blank"><img src="<?php echo pe_thumb($v['product_logo'], 150, 150) ?>" title="<?php echo $v['product_name'] ?>" /></a></p>
					<p class="prolist_name"><a href="<?php echo pe_url('product-'.$v['product_id']) ?>" title="<?php echo $v['product_name'] ?>" target="_blank"><?php echo $v['product_name'] ?></a></p>
					<p><span class="num cred strong">¥</span><span class="num1 cred strong"><?php echo $v['product_smoney'] ?></span> <s class="num c888">¥ <?php echo $v['product_mmoney'] ?></s></p>
				</div>
				<?php endforeach;?>
				<div class="clear"></div>
			</div>
		</div>
		<!--分类1 Start-->
		<?php foreach((array)$category_indexlist as $k => $v):?>
		<div class="index_fenlei mat10">
			<div class="index_fenlei_tt">
				<h3 class="fl"><?php echo $v['category_name'] ?></h3>
				<span class="fr"><a href="<?php echo pe_url('product-list-'.$v['category_id']) ?>" title="<?php echo $v['category_name'] ?>" target="_blank">更多>></a></span>
			</div>
			<div class="index_prolist">
				<div class="fl prolist_left">
					<?php foreach($v['product_newlist'] as $vv):?>
					<div class="prolist_1">
						<p class="prolist_img"><a href="<?php echo pe_url('product-'.$vv['product_id']) ?>" title="<?php echo $vv['product_name'] ?>" target="_blank"><img src="<?php echo pe_thumb($vv['product_logo'], 150, 150) ?>" title="<?php echo $vv['product_name'] ?>" /></a></p>
						<p class="prolist_name"><a href="<?php echo pe_url('product-'.$vv['product_id']) ?>" title="<?php echo $vv['product_name'] ?>" target="_blank"><?php echo $vv['product_name'] ?></a></p>
						<p><span class="cred num strong">¥</span><span class="num1 cred strong"><?php echo $vv['product_smoney'] ?></span> <s class="num c888">¥ <?php echo $vv['product_mmoney'] ?></s></p>
					</div>
					<?php endforeach;?>
				</div>
				<div class="fr prolist_right">
					<div class="prolist_right_tt"><h3><?php echo $v['category_name'] ?>热销排行</h3></div>
					<ul class="hotlist index_hot" style="height:386px;">
					<?php foreach($v['product_selllist'] as $vv):?>
					<li>
						<span class="fl hotimg">
							<a href="<?php echo pe_url('product-'.$vv['product_id']) ?>" title="<?php echo $vv['product_name'] ?>" target="_blank"><img src="<?php echo pe_thumb($vv['product_logo'], 60, 60) ?>" title="<?php echo $vv['product_name'] ?>" /></a>
						</span>
						<span class="fl hotname hotname_index">
							<a href="<?php echo pe_url('product-'.$vv['product_id']) ?>" title="<?php echo $vv['product_name'] ?>" target="_blank"><?php echo $vv['product_name'] ?></a>
							<span class="lh20">商城价：<span class="cred num strong">¥</span><span class="num1 cred strong"><?php echo $vv['product_smoney'] ?></span></span>
						</span>
						<div class="clear"></div>
					</li>
					<?php endforeach;?>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php endforeach;?>
		<!--分类1 End-->
	</div>	
</div>
<?php include(pe_tpl('footer.html'));?>