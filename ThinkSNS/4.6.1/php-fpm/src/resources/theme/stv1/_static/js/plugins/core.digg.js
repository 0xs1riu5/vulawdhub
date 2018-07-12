/**
 * 赞核心Js
 * @type {Object}
 */
core.digg = {
	// 给工厂调用的接口
	_init: function (attrs) {
		core.digg.init();
	},
	init: function () {
		core.digg.digglock = 0;
	},
	addDigg: function (feed_id) {
		// 未登录弹出弹出层
		if(MID == 0){
			ui.quicklogin();
			return;
		}
		
		if (core.digg.digglock == 1) {
			return false;
		}
		core.digg.digglock = 1;
		$.post(U('widget/Digg/addDigg'), {feed_id:feed_id}, function (res) {
			if (res.status == 1) {
				$digg = {};
				if (typeof $('#digg'+feed_id)[0] === 'undefined') {
					$digg = $('#digg_'+feed_id);
				} else {
					$digg = $('#digg'+feed_id);
				}
				var num = $digg.attr('rel');
				num++;
				$digg.attr('rel', num);
				//$('#digg'+feed_id).html('<a href="javascript:;" class="like-h" onclick="core.digg.delDigg('+feed_id+')">已赞('+num+')</a>');
//				$('#digg_'+feed_id).html('<a href="javascript:;" class="like-h" onclick="core.digg.delDigg('+feed_id+')">已赞('+num+')</a>');
				$('#digg'+feed_id).html('<a href="javascript:;" class="like-h digg-like-yes" title="取消赞" onclick="core.digg.delDigg('+feed_id+')"><i class="digg-like"></i>('+num+')</a>');
				$('#digg_'+feed_id).html('<a href="javascript:;" class="like-h digg-like-yes" title="取消赞" onclick="core.digg.delDigg('+feed_id+')"><i class="digg-like"></i>('+num+')</a>');
			} else{
				ui.error(res.info);
			}
			core.digg.digglock = 0;
		}, 'json');
	},
	delDigg: function (feed_id) {
		if (core.digg.digglock == 1) {
			return false;
		}
		core.digg.digglock = 1;
		$.post(U('widget/Digg/delDigg'), {feed_id:feed_id}, function(res) {
			if (res.status == 1) {
				$digg = {};
				if (typeof $('#digg'+feed_id)[0] === 'undefined') {
					$digg = $('#digg_'+feed_id);
				} else {
					$digg = $('#digg'+feed_id);
				}
				var num = $digg.attr('rel');
				num--;
				$digg.attr('rel', num);
				var content;
				//if (num == 0) {
//					content = '<a href="javascript:;" onclick="core.digg.addDigg('+feed_id+')">赞</a>';
//				} else {
//					content = '<a href="javascript:;" onclick="core.digg.addDigg('+feed_id+')">赞('+num+')</a>';
//				}
                if (num == 0) {
					content = '<a href="javascript:;" onclick="core.digg.addDigg('+feed_id+')" title="赞"><i class="digg-like"></i></a>';
				} else {
					content = '<a href="javascript:;" onclick="core.digg.addDigg('+feed_id+')" title="赞"><i class="digg-like"></i>('+num+')</a>';
				}
				$('#digg'+feed_id).html(content);
				$('#digg_'+feed_id).html(content);
			} else {
				ui.error(res.info);
			}
			core.digg.digglock = 0;
		}, 'json');
	}
};