<feed app='public' type='postvideo' info='发视频分享'>
	<title> 
		<![CDATA[{$actor}]]>
	</title>
	<body>
		<![CDATA[
			{$body|t|replaceUrl}
			<br/>
			<div class="feed_img" id="video_mini_show_{$feedid}">
        <a href="javascript:void(0);" onclick="switchVideo({$feedid},'open','{$host}','{$flashvar}')">
          <img src="{$flashimg}" style="width:150px;height:113px;overflow:hidden" />
        </a>
        <div class="video_play" ><a href="javascript:void(0);" onclick="switchVideo({$feedid},'open','{$host}','{$flashvar}')"></a>
        </div>
    </div>
    <div class="feed_quote" style="display:none;" id="video_show_{$feedid}"> 
      <div class="q_tit">
        <img class="q_tit_l" onclick="switchVideo({$feedid},'open','{$host}','{$flashvar}')" src="__THEME__/image/zw_img.gif" />
      </div>
      <div class="q_con"> 
        <p style="margin:0;margin-bottom:5px" class="cGray2 f12">
        <a href="javascript:void(0)" onclick="switchVideo({$feedid},'close')"><i class="ico-pack-up"></i>收起</a>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="{$source}" target="_blank">
          <i class="ico-show-all"></i>{$title}</a>
        </p>
        <div id="video_content_{$feedid}"></div>
      </div>
      <div class="q_btm"><img class="q_btm_l" src="__THEME__/image/zw_img.gif" /></div>
    </div>
		 ]]>
	</body>
	<feedAttr comment="true" repost="true" like="false" favor="true" delete="true" />
</feed>