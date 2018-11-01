<?php include(pe_tpl('header.html'));?>
<div class="content">
	<div class="now">您现在的位置：<a href="<?php echo $pe['host_root'] ?>">首页</a> <?php echo $nowpath ?></div>
	<div class="danye_left">
		<div class="danye_help">
			<div class="danye_tt">资讯中心</div>
			<div class="danye_list">
				<ul>
				<?php foreach((array)$cache_class as $v):?>
				<li><a href="<?php echo pe_url('article-list-'.$v['class_id']) ?>" title="<?php echo $v['class_name'] ?>"><?php echo $v['class_name'] ?></a></li>
				<?php endforeach;?>
				</ul>
			</div>
		</div>
		<div class="danye_help mat8">
			<div class="danye_tt">帮助中心</div>
			<div class="danye_list">
				<h3>用户指南</h3>
				<ul>
					<li><a href="<?php echo pe_url('page-1') ?>" title="<?php echo $cache_page[1]['page_name'] ?>"><?php echo $cache_page[1]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-2') ?>" title="<?php echo $cache_page[2]['page_name'] ?>"><?php echo $cache_page[2]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-3') ?>" title="<?php echo $cache_page[3]['page_name'] ?>"><?php echo $cache_page[3]['page_name'] ?></a></li>
				</ul>
				<h3>配送方式</h3>
				<ul>
					<li><a href="<?php echo pe_url('page-4') ?>" title="<?php echo $cache_page[4]['page_name'] ?>"><?php echo $cache_page[4]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-5') ?>" title="<?php echo $cache_page[5]['page_name'] ?>"><?php echo $cache_page[5]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-6') ?>" title="<?php echo $cache_page[6]['page_name'] ?>"><?php echo $cache_page[6]['page_name'] ?></a></li>
				</ul>
				<h3>售后服务</h3>
				<ul>
					<li><a href="<?php echo pe_url('page-7') ?>" title="<?php echo $cache_page[7]['page_name'] ?>"><?php echo $cache_page[7]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-8') ?>" title="<?php echo $cache_page[8]['page_name'] ?>"><?php echo $cache_page[8]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-9') ?>" title="<?php echo $cache_page[9]['page_name'] ?>"><?php echo $cache_page[9]['page_name'] ?></a></li>
				</ul>
				<h3>关于我们</h3>
				<ul>
					<li><a href="<?php echo pe_url('page-10') ?>" title="<?php echo $cache_page[10]['page_name'] ?>"><?php echo $cache_page[10]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-11') ?>" title="<?php echo $cache_page[11]['page_name'] ?>"><?php echo $cache_page[11]['page_name'] ?></a></li>
					<li><a href="<?php echo pe_url('page-12') ?>" title="<?php echo $cache_page[12]['page_name'] ?>"><?php echo $cache_page[12]['page_name'] ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="danye_right">
		<?php if($mod=='page'):?>
		<h3 class="cred"><?php echo $info['page_name'] ?></h3>
		<div class="danye_main"><?php echo $info['page_text'] ?></div>
		<?php elseif($mod=='article' && $act=='list'):?>
		<ul class="wenzhang_list">
			<?php foreach($info_list as $v):?>
			<li><a href="<?php echo pe_url('article-'.$v['article_id']) ?>" title="<?php echo $v['article_name'] ?>"><?php echo $v['article_name'] ?></a><span class="fl c888">(浏览量：<?php echo $v['article_clicknum'] ?>)</span> <span class="fr"><?php echo pe_date($v['article_atime']) ?></span><div class="clear"></div></li>
			<?php endforeach;?>
		</ul>
		<div class="fenye mat8"><?php echo $db->page->html ?></div>
		<?php elseif($mod=='article'):?>
		<h3 class="cred"><?php echo $info['article_name'] ?></h3>
		<p class="c888 mat5" style="text-align:center;">发布日期：<?php echo pe_date($info['article_atime']) ?>　浏览量：<?php echo $info['article_clicknum'] ?></p>
		<div class="danye_main"><?php echo $info['article_text'] ?></div>
		<?php endif;?>
	</div>
</div>
<?php include(pe_tpl('footer.html'));?>