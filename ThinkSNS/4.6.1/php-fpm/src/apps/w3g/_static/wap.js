/**
 * 异步提交表单
 * @param object form 表单DOM对象
 * @return void
 */
var ajaxSubmit = function(form) {
	var args = M.getModelArgs(form);
	M.getJS(THEME_URL + '/js/jquery.form.js', function() {
        var options = {
        	dataType: "json",
            success: function(txt) {		
        		if(1 == txt.status) {
                    if("function" ===  typeof form.callback) {
        				form.callback(txt);
        			} else {
        				if("string" == typeof(args.callback)) {
        					eval(args.callback+'()');
        				} else {
        					wap_success(txt.info);
        				}
        			}
        		} else {
        			wap_error(txt.info);
        			$('#wait_submit').show();	
					$('#submiting').hide();
        		}
            }
        };
        $(form).ajaxSubmit(options);
	});
};

/**
 * 处理ajax返回数据之后的刷新操作
 */
var ajaxReload = function(obj,callback){
    if("undefined" == typeof(callback)){
        callback = "location.href = location.href";
    }else{
        callback = 'eval('+callback+')';
    }
    if(obj.status == 1){
        setTimeout(callback,1500);
     }else{
        wap_error(obj.data);
    }
};

var getChecked = function() {
    var ids = new Array();
    $.each($('#list input:checked'), function(i, n){
        if($(n).val() !='0' && $(n).val()!='' ){
            ids.push( $(n).val() );    
        }
    });
    return ids;
};

var checkAll = function(o){
    if( o.checked == true ){
        $('#list input[name="checkbox"]').attr('checked','true');
    }else{
        $('#list input[name="checkbox"]').removeAttr('checked');
    }
};

M.addModelFns({
	group_topic_post:{  //发布帖子
		callback:function(txt){
			wap_success('帖子发布成功');
			setTimeout(function() {
				location.href = U('w3g/Group/topicDetail')+'&tid='+txt.data.tid+'&gid='+txt.data.gid;
			}, 1500);
		}
	},
	group_add:{  //发布帖子
		callback:function(txt){
			wap_success('微吧创建成功');
			setTimeout(function() {
				location.href = U('w3g/Group/detail')+'&gid='+txt.data;
			}, 1500);
		}
	},		
	weiba_post:{  //发布帖子
		callback:function(txt){
			wap_success('发布成功');
			setTimeout(function() {
				location.href = U('w3g/Weiba/postDetail')+'&post_id='+txt.data;
			}, 1500);
		}
	},	
	weiba_post_edit:{  //编辑帖子
		callback:function(txt){
			wap_success('编辑成功');
			setTimeout(function() {
				location.href = U('w3g/Weiba/postDetail')+'&post_id='+txt.data;
			}, 1500);
		}
	},
	drop_weiba_search:{
		load:function(){
			var _this = this;
			search.init();
			$(this.childEvents['searchKey'][0]).click(function(){
				search.searchInit(this);
			});	
		}
	},
	weiba_reply_edit:{   //编辑帖子回复
		callback:function(txt){
			wap_success('编辑成功');
			setTimeout(function() {
				location.href = U('w3g/Weiba/postDetail')+'&post_id='+txt.data;
			}, 1500);
		}
	},
	weiba_apply:{   //申请圈主
		callback:function(txt){
			wap_success('申请成功，请等待管理员审核');
			setTimeout(function() {
				location.href = U('w3g/Weiba/detail')+'&weiba_id='+txt.data;
			}, 1500);
		}
	},
	topics_add:{
		callback:function(txt){
			wap_success('发表成功');
			setTimeout(function() {
				location.href = txt.data;
			}, 1500);
		}
	}
});

M.addEventFns({
	doFollowWeiba: {
		click: function() {	
			followWeiba.doFollow( this );
			return false;
		},
		load: function() {
			followWeiba.createBtn( this );
		}
	},
	unFollowWeiba: {
		click: function() {
			followWeiba.unFollow( this );
			return false;
		},
		load: function() {
			followWeiba.createBtn( this );
		}
	},
	submit_btn: {
		click: function(){
			var args  = M.getEventArgs(this);
			if ( args.info && ! confirm( args.info )) {
				return false;
			}
			try{
				(function( node ) {
					var parent = node.parentNode;

					// 判断node 类型，防止意外循环
					if ( "FORM" === parent.nodeName ) {

						if ( "false" === args.ajax ) {
							( ( "function" !== typeof parent.onsubmit ) || ( false !== parent.onsubmit() ) ) && parent.submit();
						} else {
							if(parent.name == 'weibaPost'){
								if($('#weiba_id_select').val()==0){
									wap_error('请选择完整的版块');
								   $('#wait_submit').show();	
								   $('#submiting').hide();									
									return false;
								}
								if($('#title').val()=="" || $("#content").val()==""){
									wap_error('标题和内容不能为空');
								   $('#wait_submit').show();	
								   $('#submiting').hide();									
									return false;
								}
								//E.sync();
							}
							if(parent.name == 'reply_edit'){
								$('#content').val($('.s-textarea').html());
								var strlen = getLength($('#content').val())
								var leftnums = initNums - strlen;
								if(leftnums < 0){
									wap_error('不能超过'+initNums+'个字');
									return false;
								}
							}
							
							ajaxSubmit(parent);
						}
					} else if ( 1 === parent.nodeType ) {	
					     $('#wait_submit').hide();	
						 $('#submiting').show();
						arguments.callee( parent );
					}
				})(this);
			}catch(e){
				return true;
			}
			return false;
		}
	},
	do_weiba_reply:{	//回复帖子操作
		click:function(){
			if ( this.noreply == 1 ){
				return;
			}else{
				this.noreply = 1;
			}
			var _this = this;
			setTimeout(function (){
				_this.noreply = 0;
			},5000);
			var attrs = M.getEventArgs(this);
			attrs.weiba_id = $(this).attr('weiba_id');
			attrs.post_id = $(this).attr('post_id');
			attrs.post_uid = $(this).attr('post_uid');
			attrs.to_reply_id = $(this).attr('to_reply_id');
			attrs.to_uid = $(this).attr('to_uid');
			attrs.feed_id = $(this).attr('feed_id');
			attrs.addtoend = $(this).attr('addtoend');
			var comment_list = this.parentModel.parentModel;
			
			var content = $('#comment_inputor').val();
			
			var image_ids = $('#image_ids').val();
			
			$(_this).html('<span>回复中...</span>');
			// if(getLength(content) == '') {
			// 	flashTextarea(_textarea);
			// 	return false;
			// }
			if("undefined" != typeof(this.addComment) && (this.addComment == true)) {
				return false;	//不要重复评论
			}
			// 如果转发到自己的分享
//			var ischecked = $(this.comment_textarea).find("input[name='shareFeed']").get(0).checked;
//			if(ischecked == true) {
//				var ifShareFeed = 1;
//			} else {
//				var ifShareFeed = 0;
//			}
var ifShareFeed = 0;
     		$.post(U('widget/WeibaReply/addReply'),{image_ids:image_ids,is_wap:'1',widget_appname:'weiba',weiba_id:attrs.weiba_id,post_id:attrs.post_id,post_uid:attrs.post_uid,to_reply_id:attrs.to_reply_id,to_uid:attrs.to_uid,feed_id:attrs.feed_id,content:content,ifShareFeed:ifShareFeed},function(msg){

				if(msg.status == "0"){
					wap_error(msg.data);
				}else{
					window.location.href=msg.url;
					
//					if("undefined" != typeof(commentListObj.childModels['comment_list']) ){
//						wap_success('评论成功');
//						if(attrs.addtoend == 1){
//							$(commentListObj).find('.comment_lists').eq(0).append(msg.data);
//						}else{
//							$(msg.data).insertBefore($(commentListObj.childModels['comment_list'][0]));
//						}
//					}else{
//						wap_success('评论成功');
//						$(commentListObj).find('.comment_lists').eq(0).html(msg.data);
//					}
//					M(commentListObj);
//					//重置
//					_textarea.value = '';
//					this.to_reply_id = 0;
//					this.to_uid = 0;
					// if("function" == typeof(afterComment)){
					// 	afterComment();
					// }
				}
				$(_this).html('<span>回复</span>');
				addComment = false;
			},'json');
		}
	},
	reply_del:{
		click:function(){
			if(!confirm('确认删除？')) return false;
			
			var attrs = M.getEventArgs(this);
			$(this.parentModel).fadeOut('normal', function () {
				var $commentList = $(this).parent();
				if ($commentList.length > 0) {
					// 获取分享ID
					var wid = parseInt($commentList.attr('id').split('_')[1]);
					var $commentListVisible = $commentList.find('dl:visible');
					//var len = $commentListVisible.length;
					// $commentListVisible.each(function (i, n) {
					// 	$(this).find('span.floor').html((len - i)+'楼');
					// });
				}
			});
			$.post(U('widget/WeibaReply/delReply'),{widget_appname:'weiba',reply_id:attrs.reply_id},function(msg){
			//什么也不做吧
		});
		}
	},
	reply_reply:{	//点某条回复
		click:function(){ 
			var attrs = M.getEventArgs(this);
			
			window.location.href = U('widget/WeibaReply/reply_reply')+'&widget_appname=weiba'+'&weiba_id='+attrs.weiba_id+'&post_id='+attrs.post_id+'&post_uid='+attrs.post_uid+'&to_reply_id='+attrs.to_reply_id+'&to_uid='+attrs.to_uid+'&to_comment_uname='+attrs.to_comment_uname+'&feed_id='+attrs.feed_id+'&addtoend='+attrs.addtoend+'&comment_id='+attrs.comment_id+'&tpl=wap_reply_reply';
//			ui.box.load(U('widget/WeibaReply/reply_reply')+'&widget_appname=weiba'+'&weiba_id='+attrs.weiba_id+'&post_id='+attrs.post_id+'&post_uid='+attrs.post_uid+'&to_reply_id='+attrs.to_reply_id+'&to_uid='+attrs.to_uid+'&to_comment_uname='+attrs.to_comment_uname+'&feed_id='+attrs.feed_id+'&addtoend='+attrs.addtoend+'&comment_id='+attrs.comment_id,L('PUBLIC_RESAVE'),function(){
//				$('#at-view').hide();
//			});
		}
	},
	post_del:{
		click:function(){
			var attrs = M.getEventArgs(this);
			var _this = this;
			var post_del = function(){
				$.post(U('w3g/Weiba/postDel'),{post_id:attrs.post_id,weiba_id:attrs.weiba_id,log:attrs.log},function(res){
				if(res == 1){
					wap_success('删除成功');
					location.href=U('w3g/Weiba/detail') + '&weiba_id='+ attrs.weiba_id;
				}else{
					wap_error('删除失败');
				}
				});
			}		
			ui.confirm(this,L('PUBLIC_DELETE_THISNEWS'),post_del);
		}
	},
	post_set:{
		click:function(){
			var attrs = M.getEventArgs(this);
			$.post(U('w3g/Weiba/postSet'),{post_id:attrs.post_id,type:attrs.type,currentValue:attrs.currentValue,targetValue:attrs.targetValue},function(res){
				if(res == 1){
					wap_success('设置成功');
					setTimeout("location.reload()",1000);
				}else{
					wap_error('设置失败');
				}
			});
		}
	},
	post_favorite:{
		click:function(){
			var attrs = M.getEventArgs(this);
			$.post(U('w3g/Weiba/favorite'),{post_id:attrs.post_id,weiba_id:attrs.weiba_id,post_uid:attrs.post_uid},function(res){
				if(res == 1){
					wap_success('收藏成功');
					setTimeout("location.reload()",1000);
				}else{
					wap_error('收藏失败');
				}
			});
		}	
	},
	post_unfavorite:{
		click:function(){
			var attrs = M.getEventArgs(this);
			$.post(U('w3g/Weiba/unfavorite'),{post_id:attrs.post_id,weiba_id:attrs.weiba_id,post_uid:attrs.post_uid},function(res){
				if(res == 1){
					wap_success('取消成功');
					setTimeout("location.reload()",1000);
				}else{
					wap_error('取消失败');
				}
			});
		}	
	},
	delFeedWap:{
		click:function(){
			var attrs = M.getEventArgs(this);

			var _this = this;
			var delFeed =  function(){
				$.post(U('public/Feed/removeFeed'),{feed_id:attrs.feed_id},function(msg){
					if(msg.status == 1){
						if($('#feed'+attrs.feed_id).length > 0){
							$('#feed'+attrs.feed_id).fadeOut();
						}else{
							$(_this.parentModel).fadeOut();
						}
						updateUserData('weibo_count',-1,attrs.uid);
						if(attrs.isrefresh == 1){    //在分享详情页删除后跳转到首页
							window.location.href = SITE_URL;
						}
					}else{
						ui.error( L('PUBLIC_DELETE_ERROR') );
					}
				},'json');
			};
			var title = L('PUBLIC_DELETE_THISNEWS');
			switch (attrs.type) {
				case 'weiba_post':
					title += '（删除后，将同步删除对应微吧帖子）';
					break;
			}
			if(confirm(L('PUBLIC_DELETE_THISNEWS'))){
				delFeed();
			}
			// ui.confirm(this, title, delFeed);
		}
	}
});

	/**
 * 关注操作Js对象
 */
var followWeiba = {
	// 按钮样式
	btnClass: {
		doFollow: "",
		unFollow: "",
		haveFollow: ""
	},
	// 按钮图标
	flagClass: {
		doFollow: "",
		unFollow: "",
		haveFollow: ""
	},
	// 按钮文字
	btnText: {
		doFollow: '收藏',
		unFollow: '取消收藏',
		haveFollow: '已收藏'
	},
	/**
	 * 创建关注按钮
	 * @param object node 按钮节点对象
	 * @param string btnType 按钮类型，4种
	 * @return void
	 */
	createBtn: function(node, btnType) {
		var args = M.getEventArgs(node);
		//alert(args.following);
		var btnType = (0 == args.following) ? "doFollow" : "haveFollow";
		var btnClass = this.btnClass[btnType];
		var flagClass = this.flagClass[btnType];
		var btnText = this.btnText[btnType];
		var btnHTML = ['<span><b class="', flagClass, '"></b>', btnText, '</span>'].join( "" );
		// 按钮节点添加HTML与样式
		node.innerHTML = btnHTML;
		node.className = btnClass;
		// 选择按钮类型
		switch(btnType) {
			case "haveFollow":
				M.addListener(node, {
					mouseenter: function() {
						var b = this.getElementsByTagName( "b" )[0];
						var text = b.nextSibling;
						this.className = followWeiba.btnClass.unFollow;
						b.className = followWeiba.flagClass.unFollow;
						text.nodeValue = followWeiba.btnText.unFollow;
						
					},
					mouseleave: function() {
						var b = this.getElementsByTagName( "b" )[0];
						var text = b.nextSibling;
						this.className = btnClass;
						b.className = flagClass;
						text.nodeValue = btnText;
					}
				});
				break;
			default:
				M.addListener(node, {
					mouseleave: function() {
					}
				});
			break;
		}
	},
	/**
	 * 添加关注操作
	 * @param object node 关注按钮的DOM对象
	 * @return void
	 */
	doFollow: function(node) {
		var _this = this;
		var args = M.getEventArgs(node);
		var url = node.getAttribute("href") || [U('w3g/Weiba/doFollowWeiba'), '&weiba_id=', args.weiba_id].join("");
		$.post(url, {}, function(txt) {
			if(1 == txt.status ) {
				wap_success('操作成功');
				if(args.isrefresh==1){
					setTimeout("location.reload()",1000);
				}else{
					node.setAttribute("event-node", "unFollowWeiba");
					node.setAttribute("href", [U('w3g/Weiba/unFollowWeiba'), '&weiba_id=', args.weiba_id].join(""));
					M.setEventArgs(node, ["weiba_id=", args.weiba_id, "&following=1"].join(""));
					M.removeListener(node);
					M(node);
				}
			} else {
				wap_error(txt.info);
			}
		}, 'json');
	},
	/**
	 * 取消关注操作
	 * @param object node 关注按钮的DOM对象
	 * @return void
	 */
	unFollow: function(node) {
		var _this = this;
		var args = M.getEventArgs(node);
		var url = node.getAttribute("href") || [U('w3g/Weiba/unFollowWeiba'), '&weiba_id=', args.weiba_id].join("");
		$.post(url, {}, function(txt) {
			if(1 == txt.status ) {
				wap_success('取消成功');
				if(args.isrefresh==1){
					setTimeout("location.reload()",1000);
				}else{
					node.setAttribute("event-node", "doFollowWeiba");
					node.setAttribute("href", [U('w3g/Weiba/doFollowWeiba'), '&weiba_id=', args.weiba_id].join(""));
					M.setEventArgs(node, ["weiba_id=", args.weiba_id, "&following=0"].join(""));
					M.removeListener(node);
					M(node);
				}
			} else {
				wap_error(txt.info);
			}
		}, 'json');
	}
};

var search = {
	init: function(){
		this.searchKey = '';
		return true;
	},

	searchInit: function(obj){
		$(obj).keyup(function(){
			search.displayList(obj);
		});
	},

	displayList: function(obj){
		this.searchKey = obj.value.replace(/(^\s*)|(\s*$)/g,"");
		if(getLength(this.searchKey)>0){
			var html = '<div class="search-box" id="search-box"><dd id="s_2" class="current" onclick="search.dosearch(2);" onmouseover="$(\'#s_2\').addClass(\'current\');$(\'#s_1\').removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关主题&raquo;</dd>'
						+'<dd id="s_1" onclick="search.dosearch(1);" onmouseover="$(\'#s_1\').addClass(\'current\');$(\'#s_2\').removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关版块&raquo;</dd></div>';
					//+'<dd class="more"><a href="#"" onclick="core.search.dosearch();">点击查看更多结果&raquo;</a></dd>';
		}else{
			var html = '';
		}
		$(obj).parent().nextAll().remove();
		$(html).insertAfter($(obj).parent());
	},

	//查找数据
	dosearch:function(type){
		 var url = U('w3g/Weiba/search')+'&k='+this.searchKey;
		 if("undefined" != typeof(type)){
		 	url+='&type='+type;	
		 }
		 location.href = url;
	}
};


var	upload = function(type,obj){
	    if("undefined"  != typeof(core.uploadFile)){
	        core.uploadFile.filehash = new Array();
	    }
		core.plugInit('uploadFile',obj,function(data){
			//alert(data.src);
	        //$('.input-content').remove();
	        $('#show_'+type).html('<img src="'+data.src+'" width="150" height="150">');
	        $('#form_'+type).val(data.attach_id);    
	    },'image');
	};

/**
 * 修改吧内成员等级
 * @param integer weiba_id 微吧ID
 * @param integer follower_uid 当前成员UID
 * @param integer targetLevel 目标等级
 * @return void
 */
var editLevel = function(weiba_id,follower_uid,targetLevel){
	$.post(U('weiba/Manage/editLevel'), {weiba_id:weiba_id,follower_uid:follower_uid,targetLevel:targetLevel}, function(msg) {
		ajaxReload(msg);
	},'json');
};

/**
 * 将用户移出微吧
 * @param integer weiba_id 微吧ID
 * @param integer follower_uid 微吧成员UID
 * @return void
 */
var moveOut = function(weiba_id,follower_uid){
	if("undefined" == typeof(follower_uid) || follower_uid=='') follower_uid = getChecked();
    if(follower_uid==''){
        wap_error('请选择要移出的用户');return false;
    }
	$.post(U('weiba/Manage/moveOut'), {weiba_id:weiba_id,follower_uid:follower_uid}, function(msg) {
		ajaxReload(msg);
	},'json');
};

/**
 * 解散微吧
 * @param integer weiba_id 微吧ID
 * @return void
 */
var delWeiba = function(weiba_id){
	if(confirm('确定要解散此微吧吗？')){
        $.post(U('weiba/Manage/delWeiba'),{weiba_id:weiba_id},function(msg){
            if(msg == 1) {
            	wap_success('解散成功');
            	location.href = U('w3g/Weiba/index');
            }else if(msg == -1){
            	wap_error('微吧ID不能为空');
            }else{
            	wap_error('解散失败');
            }
        });
    }
};

/**
 * 检查是否有发帖权限
 * @param integer weiba_id 微吧ID
 * @param boolean who_can_post 发帖权限 0：所有人  1：关注本吧的人
 */
var check_post = function(weiba_id, who_can_post){
//	if(who_can_post){
//		$.post(U('w3g/Weiba/checkPost'),{weiba_id:weiba_id},function(txt){
//			if(txt==1){
//				location.href = U('w3g/Weiba/post')+'&weiba_id='+weiba_id;
//			}else{
//				ui.box.load(U('w3g/Weiba/joinWeiba')+'&weiba_id='+weiba_id,'您没有发帖权限');
//			}
//		});
//	}else{
		location.href = U('w3g/Weiba/post')+'&weiba_id='+weiba_id;
//	}
};

var weiba_apply = function(weiba_id,type){
	$.post(U('w3g/Weiba/checkApply'),{weiba_id:weiba_id,type:type},function(txt){
		if(txt==1){
			location.href = U('w3g/Weiba/apply')+'&weiba_id='+weiba_id+'&type='+type;
		}else if(txt==-1){
			wap_error('您已经提交了申请，请等待审核');
		}else if(txt==-2){
			wap_error('您已经是圈主，不能重复申请');
		}else if(txt==2){
			wap_error('该吧已经设置了圈主');
			setTimeout("location.reload()",2000);
		}else{
			wap_error('您需要发布5篇以上帖子才能申请');
		}
	});
};

/**
 * 处理申请圈主或小主申请
 */
var verify = function(weiba_id, uid, value){
	$.post(U('weiba/Manage/verify'),{weiba_id:weiba_id,uid:uid,value:value},function(msg){
		ajaxReload(msg);
	},'json');
};

var saveWeibaInfo = function(){
	var weiba_id = $('#weiba_id').val();
	var weiba_name = $('#weiba_name').val();
	var cid = $('#cid').val();
	var intro = $('#intro').val();
	var logo = $('#form_logo').val();
	var who_can_post = $('input:checked[name="who_can_post"]').val();
	$.post(U('weiba/Manage/doWeibaEdit'),{weiba_id:weiba_id,cid:cid,weiba_name:weiba_name,intro:intro,logo:logo,who_can_post:who_can_post},function(msg){
		if(msg=='1'){
			wap_success('保存成功');
		}else{
			wap_error(msg);
		}
	});
}
function wap_success(msg){
	$('.top_tip').html(msg).show();
	setTimeout("$('.top_tip').hide()",3000);
}
function wap_error(msg){
	$('.top_tip').html(msg).show();
	setTimeout("$('.top_tip').hide()",3000);
}
//旋转图片
function revolving (type, id) {
  var img = $("#image_index_"+id);
  img.rotate(type);
}


$.fn.rotate = function(p){

  var img = $(this)[0],
    n = img.getAttribute('step');
  // 保存图片大小数据
  if (!this.data('width') && !$(this).data('height')) {
    this.data('width', img.width);
    this.data('height', img.height);
  };
  this.data('maxWidth',img.getAttribute('maxWidth'))

  if(n == null) n = 0;
  if(p == 'left'){
    (n == 0)? n = 3 : n--;
  }else if(p == 'right'){
    (n == 3) ? n = 0 : n++;
  };
  img.setAttribute('step', n);

  // IE浏览器使用滤镜旋转
  if(document.all) {
    if(this.data('height')>this.data('maxWidth') && (n==1 || n==3) ){
      if(!this.data('zoomheight')){
        this.data('zoomwidth',this.data('maxWidth'));
        this.data('zoomheight',(this.data('maxWidth')/this.data('height'))*this.data('width'));
      }
      img.height = this.data('zoomwidth');
      img.width  = this.data('zoomheight');
      
    }else{
      img.height = this.data('height');
      img.width  = this.data('width');
    }
    
    img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ n +')';
    // IE8高度设置
    if ($.browser.version == 8) {
      switch(n){
        case 0:
          this.parent().height('');
          //this.height(this.data('height'));
          break;
        case 1:
          this.parent().height(this.data('width') + 10);
          //this.height(this.data('width'));
          break;
        case 2:
          this.parent().height('');
          //this.height(this.data('height'));
          break;
        case 3:
          this.parent().height(this.data('width') + 10);
          //this.height(this.data('width'));
          break;
      };
    };
  // 对现代浏览器写入HTML5的元素进行旋转： canvas
  }else{
    var c = this.next('canvas')[0];
    if(this.next('canvas').length == 0){
      this.css({'visibility': 'hidden', 'position': 'absolute'});
      c = document.createElement('canvas');
      c.setAttribute('class', 'maxImg canvas');
      img.parentNode.appendChild(c);
    }
    var canvasContext = c.getContext('2d');
    switch(n) {
      default :
      case 0 :
        img.setAttribute('height',this.data('height'));
        img.setAttribute('width',this.data('width'));
        c.setAttribute('width', img.width);
        c.setAttribute('height', img.height);
        canvasContext.rotate(0 * Math.PI / 180);
        canvasContext.drawImage(img, 0, 0);
        break;
      case 1 :
        if(img.height>this.data('maxWidth') ){
          h = this.data('maxWidth');
          w = (this.data('maxWidth')/img.height)*img.width;
        }else{
          h = this.data('height');
          w = this.data('width');
        }
        c.setAttribute('width', h);
        c.setAttribute('height', w);
        canvasContext.rotate(90 * Math.PI / 180);
        canvasContext.drawImage(img, 0, -h, w ,h );
        break;
      case 2 :
        img.setAttribute('height',this.data('height'));
        img.setAttribute('width',this.data('width'));
        c.setAttribute('width', img.width);
        c.setAttribute('height', img.height);
        canvasContext.rotate(180 * Math.PI / 180);
        canvasContext.drawImage(img, -img.width, -img.height);
        break;
      case 3 :
        if(img.height>this.data('maxWidth') ){
          h = this.data('maxWidth');
          w = (this.data('maxWidth')/img.height)*img.width;
        }else{
          h = this.data('height');
          w = this.data('width');
        }
        c.setAttribute('width', h);
        c.setAttribute('height', w);
        canvasContext.rotate(270 * Math.PI / 180);
        canvasContext.drawImage(img, -w, 0,w,h);
        break;
    };
  };
};