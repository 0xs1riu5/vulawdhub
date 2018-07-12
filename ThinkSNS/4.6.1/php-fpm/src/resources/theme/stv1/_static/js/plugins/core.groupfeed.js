core.groupfeed = {
		_init:function(){
			return true;
		},
		// 分享初始化
		init:function(agrs) {
			this.initNums = agrs.initNums;		// 分享字数
			this.maxId = args.maxId,			// 最大分享ID
			this.loadId = args.loadId,			// 载入的分享ID
			this.feedType = args.feedType,		// 分享类型
			this.loadmore = args.loadmore,		// 是否载入更多
			this.uid = args.uid,				// 当前分享列表对应的UID
			this.loadnew = args.loadnew;		// 是否载入最新
			this.feed_type = args.feed_type;
			this.feed_key = args.feed_key;
			this.firstId = 0;	
			this.topic_id = args.topic_id;		// 是否为话题
			this.gid = args.gid;
			//this.pre_page = "undefined" == typeof(pre_page) ? 1 :pre_page;//分页用到的前一页
			if("undefined" == typeof(this.loadCount)) {
				this.loadCount = 1;
			}
			if(this.loadmore == 1) {	
				this.canLoading = true;		// 当前是否允许加载
				core.groupfeed.bindScroll();
			} else {	
				this.canLoading = false;	// 当前是否允许加载
			}
			if($('#group-feed-lists').length > 0 && this.canLoading){
				$('#group-feed-lists').append("<div class='loading' id='loadMore'>" + L('PUBLIC_LOADING') + "<img src='" + THEME_URL + "/image/ico-load.png' class='load'></div>");
				core.groupfeed.loadMoreFeed();
			}
		},
		// 页底加载分享
		bindScroll: function() {	
			var _this = this;
			$(window).bind('scroll resize', function() {
				// 加载3次后，将不能自动加载分享
				if(_this.loadCount >= 4 || _this.canLoading == false){
					return false;
				}
				var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
				var bodyHeight = $(document.body).height();
				if(bodyTop + $(window).height() >= bodyHeight - 250) {
					_this.loadCount = _this.loadCount + 1;
					if($('#group-feed-lists').length > 0 && _this.loadId != null){
						$('#group-feed-lists').append("<div class='loading' id='loadMore'>" + L('PUBLIC_LOADING') + "<img src='" + THEME_URL + "/image/ico-load.png' class='load'></div>");
						core.groupfeed.loadMoreFeed();
					}
				}
			});
		},
		// 加载更多分享
		loadMoreFeed: function() {
			var _this = this;
			_this.canLoading = false;
			// 获取分享数据
			$.get(U('group/GroupFeedList/loadMore'), {'loadId':_this.loadId, 'type':_this.feedType, 'uid':_this.uid, 'feed_type':_this.feed_type, 'feed_key':_this.feed_key, 'fgid':fgid, 'topic_id':_this.topic_id, 'load_count':_this.loadCount, 'gid':_this.gid}, function(msg) {
				// 加载失败
				if(msg.status == "0" || msg.status == "-1") {
					$('#loadMore').remove();
					if(msg.status == 0 && ("undefined" != typeof(msg.msg)) && _this.loadmore > 0) {
						$('#group-feed-lists').append('<div class="loading" id="loadMore">' + msg.msg + '</div>');
					}
				}
				// 加载成功
				if(msg.status == "1") {
					if(msg.firstId > 0 && _this.loadnew == 1) {
						_this.firstId = msg.firstId;
						// 启动查找最新的loop
						_this.startNewLoop();
					}
					$('#loadMore').remove();
					if(_this.loadCount >= 4) {
						var $lastDl = $('<div></div>');
						$lastDl.html(msg.html);
						msg.html = $lastDl.find('dl').filter('.feed_list').slice(30);
					}
					$('#group-feed-lists').append(msg.html);
					_this.canLoading = true;
					_this.loadId = msg.loadId;
					if(_this.loadCount >= 4) {
						$('#group-feed-lists').append('<div id="page" class="page" style="display:none;">' + msg.pageHtml + '</div>');
						if($('#group-feed-lists .page').find('a').size() > 2) {
							// 4ping + next 说明还有30个以上
							var href = false;
							$('#group-feed-lists .page').find('a').each(function() {
								href = $(this).attr('href');
							});
							// 重组分页结构
							$('#group-feed-lists .page').html(msg.pageHtml).show();
							$('#group-feed-lists .page').find('a').each(function() {
								var href = $(this).attr('href');
								if(href) {
									$(this).attr('href', 'javascript:;');
									$(this).click(function() {
										core.groupfeed.loadMoreByPage(href);
									});
								}
							});
						} else {
							if($('#group-feed-lists').find('dl').size() > 0) {
								$('#group-feed-lists').append('<div class="loading" id="loadMore">' + L('PUBLIC_ISNULL') + '</div>');
							}
						}
					} else {
						core.weibo.bindScroll();
					}
					M(document.getElementById('group-feed-lists'));
				}
			}, 'json')
			return false;
		},
		// 分页加载更多数据
		loadMoreByPage: function(href) {
			var obj = this;
			obj.canLoading = false;
			$('#group-feed-lists').html("<div class='loading' id='loadMore'>" + L('PUBLIC_LOADING') + "<img src='" + THEME_URL + "/image/ico-load.png' class='load'></div>");
			scrolltotop.scrollup();
			$.get(href,{},function(msg){
				if(msg.status == "0" || msg.status == "-1"){
					$('#group-feed-lists').append("<div class='load' id='loadMore'>'+L('PUBLIC_ISNULL')+'</div>");
				}else{
					$('#group-feed-lists').html(msg.html);
					$('#group-feed-lists').append('<div id="page" class="page" >'+msg.pageHtml+'</div>');
					
					$('#group-feed-lists .page').find('a').each(function(){
						var href = $(this).attr('href');
						if(href){
							$(this).attr('href','javascript:void(0);');
							$(this).click(function(){
								core.groupfeed.loadMoreByPage(href);
							});
						}
					});
					//core.weibo.bindScroll();
					M(document.getElementById('group-feed-lists'));
				}
			},'json');
			return false;
		},
		// 加载最新分享
		startNewLoop: function() {
			var _this = this;
			var searchNew = function() {
				if(_this.firstId < 1) {
					return false;
				}
				// 加载最新的数据
				$.post(U('group/FeedList/loadNew'), {maxId:_this.firstId, type:'new' + _this.feedType, uid:_this.uid}, function(msg) {
					if(msg.status == 1 && msg.count > 0) {
						_this.showNew(msg.count);
						_this.tempHtml = msg.html;
						_this.tmpfirstId = msg.maxId;
					}
				}, 'json');
			};
			// 每2分钟查找一次最新分享
			var loop = setInterval(searchNew, 120000);
		},
		// 提示有多少新分享数据
		showNew: function(nums) {
			if($('#group-feed-lists').find('.notes').length > 0) {
				$('#group-feed-lists').find('.notes').html(L('PUBLIC_WEIBO_NUM',{'sum':nums}));
			} else {
				var html = '<a href="javascript:core.weibo.showNewList()" class="notes">'+L('PUBLIC_WEIBO_NUM',{'sum':nums})+'</a>';
				$('#group-feed-lists').prepend(html);	
			}
		},
		showNewList:function(){
			$('#group-feed-lists').find('.notes').remove();
			$('#group-feed-lists').prepend(this.tempHtml);
			this.firstId  = this.tmpfirstId;
			this.tempHtml = '';
			M(document.getElementById('group-feed-lists'));
		},
		// 发布分享之后操作
		afterPost: function(obj, textarea, topicHtml, close) {
			if(topicHtml == ''){
				textarea.value = '';
			}else{
				textarea.value = topicHtml;
			}

            var $numsLeft = {};
            var full = true;
            if (typeof obj.parentModel.parentModel.childModels['numsLeft'] != 'undefined') {
                $numsLeft = obj.parentModel.parentModel.childModels['numsLeft'];
            } else if (typeof obj.childModels['numsLeft'] != 'undefined') {
                $numsLeft = obj.childModels['numsLeft'];
                full = false;
            } else {
                return true;
            }

            if (full) {
                $numsLeft[0].innerHTML = L('PUBLIC_INPUT_TIPES',{'sum':'<span>'+initNums+'</span>'});
            } else {
                $numsLeft[0].innerHTML = '<span>'+initNums+'</span>';
            }

//			obj.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = L('PUBLIC_INPUT_TIPES',{'sum':'<span>'+initNums+'</span>'});
			var fadeOutObj = function() {
				textarea.ready = null;	
			};
			
			$(obj.childModels['post_ok'][0]).fadeOut(500,fadeOutObj);
			// 修改分享数目
			if("undefined" == typeof(close) || !close) {
				updateUserData('weibo_count',1);
			}
			if("undefined" != typeof(core.uploadFile)) {
				core.uploadFile.removeParentDiv();
			}
			if("undefined" != typeof core.contribute) {
				core.contribute.resetBtn();
			}
		},
		// 将json数据插入到feed-lists中
		insertToList: function(html, feedId) {
			//alert(html);exit;
			if("undefined" == typeof(html) || html == '') {
				return false;
			}
			//alert(123);exit;
			if($('#group-feed-lists').length > 0) {
				var before = $('#group-feed-lists dl').eq(0);
				$dl = $('<dl></dl>');
				$dl.attr('model-node', 'feed_list');
				$dl.attr('id', 'feed'+feedId);
				$dl.addClass('feed_list');
				$dl.html(html);
				if(before.length > 0) {
					$dl.insertBefore(before);
				} else {
					if($('#group-feed-lists').find('dl').size() > 0) {
						$('#group-feed-lists').append($dl);
					} else {
						$('#group-feed-lists').html($dl);
					}
				}
				M($dl[0]);
			}
			//DIY专用
			if($('#group-feed-lists-d').length > 0) {
				var before = $('#group-feed-lists-d dl').eq(0);
				var _dl = document.createElement('dl');
				_dl.setAttribute('class', 'feed_list');
				_dl.setAttribute('model-node', 'feed_list');
				_dl.setAttribute('id', 'feed'+feedId);
				_dl.innerHTML = html;
				if(before.length > 0) {
					$(_dl).insertBefore(before);
				} else {
					if($('#group-feed-lists-d').find('dl').size() > 0) {
						$('#group-feed-lists-d').append(_dl);
					} else {
						$('#group-feed-lists-d').html(_dl);
					}
				}
				M(_dl);
			}
		},
		// 检验分享内容，obj = 要验证的表单对象，post = 表示是否发布
		checkNums: function(obj, post) {
			if("undefined" == typeof(obj.parentModel.parentModel.parentModel.childModels['numsLeft'])) {
				return true;
			}
			// 获取输入框中还能输入的数字个数
			var strlen = core.getLength(obj.value , true);
			var leftNums = initNums - strlen;
			if(leftNums == initNums && 'undefined' != typeof(post)) {
				return false;
			}
			// 获取按钮对象
			var objInput = '';
			if($(obj.parentModel.parentModel.childModels['send_action']).html() != null) {
				objInput = $(obj.parentModel.parentModel.childModels['send_action'][0]).find('a').eq(0);
			}
			// 获取剩余字数
			if(leftNums >= 0) {
				var html = (leftNums == initNums) ? L('PUBLIC_INPUT_TIPES', {'sum':'<span>'+leftNums+'</span>'}) : L('PUBLIC_PLEASE_INPUT_TIPES', {'sum':'<span>'+leftNums+'</span>'});
				obj.parentModel.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = html;
				$(obj).removeClass('fb');
				if(leftNums == initNums && $(obj).find('img').size() == 0) {
					if(typeof(objInput) == 'object') {
						objInput[0].className = 'btn-grey-white';
					}
					return false;	// 没有输入内容
				}
				if(typeof(objInput) == 'object') {
					objInput[0].className = 'btn-green-big';
				}
				return true;
			} else {
				var html = L('PUBLIC_INPUT_ERROR_TIPES', {'sum':'<span style="color:red">' + Math.abs(leftNums) + '</span>'});
				$(obj).addClass('fb');
				obj.parentModel.parentModel.parentModel.childModels['numsLeft'][0].innerHTML = html;
				if(typeof(objInput) == 'object') {
					objInput[0].className = 'btn-grey-white';
				}
				return false;
			}
		},
		// 发布分享
		post_feed: function(_this, mini_editor, textarea, isbox) {	
			var obj = this;
			// 避免重复发送
			if("undefined" == typeof(obj.isposting)) {
				obj.isposting = true;
			} else {
				if(obj.isposting == true) {
					return false;
				}
			}
			
			if("undefined" == typeof(isbox)) {
				isbox = false;
			}
			// 分享类型在此区分
			var args = $(_this).attr('event-args');
			var setargs = args.replace('type=postvideo','type=post');
			
			var attrs = M.getEventArgs(_this);
			var attachobj = $(_this.parentModel).find('.attach_ids');
			if(attachobj.length > 0) {
				var type = (attachobj.attr('feedtype') == 'image') ? 'postimage' : 'postfile';
				var attach_id = attachobj.val();
			} else {
				var attach_id = '';
				var type = attrs.type;
			}
			var videourl = $('#postvideourl').val();
			var app_name = attrs.app_name;
			if(obj.checkNums(textarea,'post') == false) {
				if(type == 'postimage') {
					textarea.value = L('PUBLIC_SHARE_IMAGES');
				} else if(type == 'postfile') {
					textarea.value = L('PUBLIC_SHARE_FILES');
				} else {
					flashTextarea(textarea);
					obj.isposting = false;
					return false;
				}
			}
			// 为空处理
			var data = textarea.value;
			if(data == '' || data.length < 0) {
				// TODO 只有一次情况才会执行到这里面 一般是不会的
				ui.error( L('PUBLIC_CENTE_ISNULL') );
				obj.isposting = false;
				return false;
			}
			data = removeHTMLTag(data);
			if(data == '' || data.length < 0) {
				ui.error('请勿输入非法与敏感字符');
				obj.isposting = false;
				return false;
			}
			// 发布分享
			$.post(U('group/GroupFeed/PostFeed'), {body:data, type:type, app_name:app_name, content:'', attach_id:attach_id,videourl:videourl,  source_url:attrs.source_url, gid:attrs.gid}, function(msg) {
				obj.isposting = false;
				_this.className = 'btn-grey-white';
				$(_this).html('<span>' + L('PUBLIC_SHARE_BUTTON') + '</span>');
				if(msg.status == 1) {
					if("undefined" != typeof(core.uploadFile)) {
						core.uploadFile.clean();
					}
					var postOk = mini_editor.childModels['post_ok'][0];
					$(postOk).fadeIn('fast');
					core.groupfeed.afterPost(mini_editor,textarea,attrs.topicHtml);
					if(!isbox) {
						core.groupfeed.insertToList(msg.data, msg.feedId);
					} else {
						ui.box.close();
						var mini = M.getModelArgs(mini_editor);
						ui.success(mini.prompt);
						if(document.getElementById('group-feed-lists') != null && channel_id == 0) {
							setTimeout(function() {
								core.groupfeed.insertToList(msg.data, msg.feedId);
							}, 1500);	
						}
					}
					M.setArgs(_this,setargs);
				} else {
					ui.error(msg.data);
				}
			}, 'json');
			return false;
		},
		friendlyDate: function(sTime, cTime)
		{
			var formatTime = function(num)
			{
				return (num < 10) ? '0' + num : num;
			};

			if(!sTime) {
				return '';
			}

			var cDate = new Date(cTime * 1000);
			var sDate = new Date(sTime * 1000);
			var dTime = cTime - sTime;
			var dDay = parseInt(cDate.getDate()) - parseInt(sDate.getDate());
			var dMonth = parseInt(cDate.getMonth() + 1) - parseInt(sDate.getMonth() + 1);
			var dYear = parseInt(cDate.getFullYear()) - parseInt(sDate.getFullYear());

			if(dTime < 60) {
				if(dTime < 10) {
					return '刚刚';
				} else {
					return parseInt(Math.floor(dTime / 10) * 10) + '秒前';
				}
			} else if(dTime < 3600) {
				return parseInt(Math.floor(dTime / 60)) + '分钟前';
			} else if(dYear === 0 && dMonth === 0 && dDay === 0) {
				return '今天' + formatTime(sDate.getHours()) + ':' + formatTime(sDate.getMinutes());
			} else if(dYear === 0) {
				return formatTime(sDate.getMonth() + 1) + '月' + formatTime(sDate.getDate()) + '日 ' + formatTime(sDate.getHours()) + ':' + formatTime(sDate.getMinutes());
			} else {
				return sDate.getFullYear() + '-' + formatTime(sDate.getMonth() + 1) + '-' + formatTime(sDate.getDate()) + ' ' + formatTime(sDate.getHours()) + ':' + formatTime(sDate.getMinutes());
			}
		}
}