<feed app='public' type='postimage' info='发图片分享'>
	<title> 
		<![CDATA[{$actor}]]>
	</title>
	<body>
		<![CDATA[ 
			{$body|t|replaceUrl}
			<br/>
			<div class="feed_img_lists" rel='small' >
			<ul class="small">
			<php>if(count($attachInfo) == 1):</php>
			<volist name='attachInfo' id='vo'>
				<li ><a href="javascript:void(0)" event-node='img_small'>
					<img class="imgicon" src='{$vo.attach_small}' title='点击放大' width="100" height="100"></a>
				</li> 
			</volist>
			<php>else:</php>
			<volist name='attachInfo' id='vo'>
				<li ><a onclick="core.weibo.showBigImage({$feedid}, {$i})" href="javascript:void(0)">
					<img class="imgicon" src='{$vo.attach_small}' title='点击放大' width="100" height="100"></a>
				</li> 
			</volist>
			<php>endif;</php>
			</ul>
			</div>
			<div class="feed_img_lists" rel='big' style='display:none'>
			<ul class="feed_img_list big" >
			<span class='tools'>
				<a href="javascript:;" event-node='img_big'><i class="ico-pack-up"></i>收起</a>
				<a target="_blank" href="{$vo.attach_url}"><i class="ico-show-big"></i>查看大图</a>
				<a href="javascript:;" onclick="revolving('left', {$feedid})"><i class="ico-turn-l"></i>向左转</a>
				<a href="javascript:;" onclick="revolving('right', {$feedid})"><i class="ico-turn-r"></i>向右转</a>
			</span>
			<volist name='attachInfo' id='vo'>
			<li title='{$vo.attach_url}'>
				<!-- <a onclick="core.weibo.showBigImage({$feedid});" target="_blank" class="ico-show-big" title="查看大图" ></a> -->
				<a href="javascript:void(0)" event-node='img_big'><img maxwidth="557" id="image_index_{$feedid}" class="imgsmall" src='{$vo.attach_middle}' title='点击缩小' ></a>
			</li>
			</volist>
			</ul>
			</div>
		 ]]>
	</body>
	<feedAttr comment="true" repost="true" like="false" favor="true" delete="true" />
</feed>