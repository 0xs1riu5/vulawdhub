/**
 * 插入视频
 */
core.video = {
	//给工厂调用的接口
	_init:function(attrs){
		if(attrs.length == 4){
			core.video.init(attrs[1],attrs[2],attrs[3]);
		}else if(attrs.length == 3){
			core.video.init(attrs[1],attrs[2]);
		}else if(attrs.length == 2){
			core.video.init(attrs[1]);
		}else{
			return false;
		}
	},
	init:function(videoObj,textarea,postfeed){
		this.videoObj = videoObj;
		this.postfeed = postfeed;
		this.textarea = textarea;
		core.video.createDiv();
	},
	createDiv:function(){
		// 定位属性
		var pos = $(this.videoObj).offset();
		// 异步获取弹窗结构
		$.get(U('public/Feed/videoBox'), {}, function (res) {
			
			// 弹窗的HTML结构
			var tab = '';
			var upload_html = '';
			var online_html = '';
			if(res.weibo_uploadvideo_open==1){
				tab += '<a id="tab_video_upload" href="javascript:void(0);" onclick="core.video.tab_video_upload()">自己上传</a>';
				tab += '<a id="tab_video_online" href="javascript:void(0);" onclick="core.video.tab_video_online()" class="current left">互联网视频</a>';
				upload_html += '<div id="video_upload" style="display:none;"><div class="video_txt">视频格式:'+res.video_ext+';文件大小:'+res.video_size+'M之内</div>'+res.html+'</div>';
				online_html += '<div id="video_online"><div class="video_txt">请输入<a href="http://www.youku.com" target="_blank">优酷网</a>、<a href="http://www.tudou.com" target="_blank">土豆网</a>、<a href="http://www.yinyuetai.com/" target="_blank">音悦台</a>播放页的链接</div><div class="video-box" id="video_content"><input type="text" style="width: 320px;" id="videourl" class="s-txt left"/><input type="button" onclick="core.video.video_add();" value="添加" class="btn-add-video"/></div></div>';
			}else{
				tab += '互联网视频';
				online_html += '<div id="video_online"><div class="video_txt">请输入<a href="http://www.youku.com" target="_blank">优酷网</a>、<a href="http://www.tudou.com" target="_blank">土豆网</a>、<a href="http://www.yinyuetai.com/" target="_blank">音悦台</a>播放页的链接</div><div class="video-box" id="video_content"><input type="text" style="width: 320px;" id="videourl" class="s-txt left"/><input type="button" onclick="core.video.video_add();" value="添加" class="btn-add-video"/></div></div>';
			}
			var html =  '<div class="talkPop alL share_adds" id="videos" style="*padding-top:20px;" event-node="uploadvideo">\
									<div class="wrap-layer">\
										<div class="arrow arrow-t"></div>\
											<div class="talkPop_box" style="width:450px;" id="talkPop_box_video">\
												<div class="close hd"><a onclick="core.video.hasDispalyDiv(\'hide\')" class="ico-close" href="javascript:;" title="'+L('PUBLIC_CLOSE')+'"></a><span>'+tab+'</span></div>'+upload_html+''+online_html+'</div>\
									</div></div>';
			// 插入到body底部
			$('body').append(html);
			
			$('#videos').css({top:(pos.top+5)+"px",left:(pos.left-5)+"px","z-index":1001});
		}, 'json');
		
		// body点击事件绑定
		$('body').bind('click',function(event){
			var obj = ('undefined' !== typeof event.srcElement) ? event.srcElement : event.target;
			if($(obj).hasClass('video-block')){
				return false;
			}
			if($(obj).parents("div[event-node='uploadvideo']").get(0) == undefined){
				core.video.hasDispalyDiv('hide');
			}
			
		});
		
	},
	hasDispalyDiv: function (obj) {
		if (obj == 'hide') {
			$('#videos').hide();
		} else {
			$('#videos').show();
		}
		
	},
	tab_video_upload:function(){
		$('#tab_video_upload').addClass('current');
		$('#tab_video_online').removeClass('current');
		$('#video_online').css('display','none');
		$('#video_upload').css('display','block');
	},
	
	tab_video_online:function(){
		var video_id = $('#video_id').val();
		if(video_id){
			if(!confirm('选择互联网视频，将删除您先前自己上传的视频，确定要继续吗？')){
				return false;
			}
		}
		core.video.deleteVideo();
		$('#tab_video_online').addClass('current');
		$('#tab_video_upload').removeClass('current');
		$('#video_upload').css('display','none');
		$('#video_online').css('display','block');
	},
	
	video_add:function(){
		var url = $('#videourl').val();
		
		var _this = this;
		$.post(U('widget/Video/paramUrl'),{url:url},function(res){
			console.log(res); // # medz
			eval("var data="+res);
			if(data.boolen==1 && data.title==1){
				$('#postvideourl').val(url);
				_this.textarea.inputToEnd( data.data+' ' );
				$('#videos').remove();
				var args = $(_this.postfeed).attr('event-args');
				var setargs = args.replace('type=post','type=postvideo');
				M.setArgs(_this.postfeed,setargs);
			}else{
				ui.error(data.message);
			}
		});
		
		if("undefined" != typeof(core.weibo)){
			core.weibo.checkNums(this.textarea.get(0));
		}
		return false;
	},
	
	/**
	 * 移除视频窗口
	 * @return void
	 */
	removeDiv: function(){
		$('#videos').remove();
		$('#video_id').remove();
	},
	
	/**
	 * 添加loading效果
	 * @param string unid 唯一ID
	 * @return void
	 */
	addLoading: function (unid) {
		var loadingHtml = '<li id="loading_'+unid+'" class="load"><span><img src="'+THEME_URL+'/image/loading.gif" /></span></li>';
		$('#btn_'+unid).before(loadingHtml);
	},
	
	/**
	 * 移除loading效果
	 * @param string unid 唯一ID
	 * @return void
	 */
	removeLoading: function (unid) {
		$('#loading_'+unid).remove();
	},
	
	deleteVideo: function(){
		$.get(U('public/Feed/videoBox'), {}, function (res) {
			$('#video_button').remove();
			$('#video_upload').append(res.html);
			$('#video_id').remove();
			// $('#videos').css({top:(pos.top+5)+"px",left:(pos.left-5)+"px","z-index":1001});
		}, 'json');
	}
	
};
