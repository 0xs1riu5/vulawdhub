/**
 * 扩展核心评论对象
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
core.comment = {
	// 给工厂调用的接口
	_init:function(attrs) {
		if(attrs.length == 3) {
			core.comment.init(attrs[1], attrs[2]);
		} else {
			return false;
		}
	},
	// 初始化评论对象
	init: function(attrs, commentListObj) {
		// 这些参数必须传入
		this.app_uid = attrs.app_uid,
		this.row_id  = attrs.row_id,
		this.to_comment_id = attrs.to_comment_id,
		this.to_uid = attrs.to_uid;
		this.app_row_id = attrs.app_row_id;//原文ID
		this.app_row_table = attrs.app_row_table;
		this.sourceObject  = "undefined" == typeof(attrs.sourceObject) ? null : attrs.sourceObject;
		this.addToEnd = "undefined" == typeof(attrs.addToEnd) ? 0 : attrs.addToEnd;
		this.canrepost = "undefined" == typeof(attrs.canrepost) ? 1 : attrs.canrepost;
		this.cancomment = "undefined" == typeof(attrs.cancomment) ?  1 : attrs.cancomment;
		this.cancomment_old = "undefined" == typeof(attrs.cancomment_old) ?  1 : attrs.cancomment_old;
		this.talkbox = "undefined" == typeof(attrs.talkbox) ? 0 : attrs.talkbox;
		if("undefined" != typeof(attrs.app_name)) {
			this.app_name = attrs.app_name;
		} else {
			this.app_name = "public";	//默认应用
		}
		if("undefined" != typeof(attrs.table)) {
			this.table = attrs.table;
		} else {
			this.table = 'feed';	//默认表
		}
		if("undefined" != typeof(attrs.to_comment_uname)) {
			this.to_comment_uname = attrs.to_comment_uname;
		}
		if("undefined" != typeof(commentListObj)) {
			this.commentListObj = commentListObj;
		}
		if("undefined" != typeof(attrs.app_detail_summary)) {
			this.app_detail_summary = attrs.app_detail_summary;
		}		
	},
	// 显示回复块
	display: function(callback) {	
		var commentListObj = this.commentListObj;
		var infopen = $(commentListObj).parent().find('.infopen:first');
		var forwardBox = $(commentListObj).parent().find('.forward_box:first');
		if("undefined" == typeof this.table) {
			this.table = 'feed';
		}
		if(forwardBox.size()) forwardBox.hide();
		if(commentListObj.style.display == 'none') {
			if(infopen.size()) {
				infopen.show();
				infopen.find('.trigon').css('left',
				 $(this.sourceObject).position().left
				  + ($(this.sourceObject).width()/2));
			}
			if(commentListObj.innerHTML !=''){
				//commentListObj.style.display = 'block';
				$(commentListObj).stop().slideDown(200);
				var _textarea = $(commentListObj).find('textarea');
				if(_textarea.size() == 0) {
					_textarea = $(commentListObj).find('input:eq(0)');
				}
				_textarea.focus(); callback && callback('show');
			}else{
				var rowid = this.row_id;
				var appname = this.app_name;
				var table = this.table;
				var cancomment = this.cancomment;
				commentListObj.style.display = 'block';
				commentListObj.innerHTML = '<img src="'+THEME_URL+'/image/load.gif" style="text-align:center;display:block;margin:0 auto;"/>';
				$.post(U('widget/Comment/render'),{app_uid:this.app_uid,row_id:this.row_id,app_row_id:this.app_row_id,app_row_table:this.app_row_table,isAjax:1,showlist:0,
						cancomment:this.cancomment,cancomment_old:this.cancomment_old,app_name:this.app_name,table:this.table,
						canrepost:this.canrepost },function(html){
							if(html.status =='0'){
								commentListObj.style.display = 'none';
								ui.error(html.data)
							}else{
								commentListObj.style.display = 'none';
								commentListObj.innerHTML = html.data;
								$('#commentlist_'+rowid).html('<img src="'+THEME_URL+'/image/load.gif" style="text-align:center;display:block;margin:0 auto;"/>');
								$.post(U('widget/Comment/getCommentList'),{app_name:appname,table:table,row_id:rowid,cancomment:cancomment},function (res){
									$('#commentlist_'+rowid).html(res);
									M($('#commentlist_'+rowid).get(0));
								});
								$(commentListObj).stop().slideDown(200);
								M(commentListObj);
								//@评论框
								var _textarea = $(commentListObj).find('textarea');
								if(_textarea.size() == 0) {
									_textarea = $(commentListObj).find('input:eq(0)');
								}
								atWho(_textarea); _textarea.focus();
								callback && callback('show');
							}
				},'json');
			}
		}else{
			$(commentListObj).stop().slideUp(200, function(){
				if(infopen.size()) infopen.hide();
				callback && callback('hide');
			});
			//commentListObj.style.display = 'none';
		}
	},
	// 初始化回复操作
	initReply: function() {
		this.comment_textarea = this.commentListObj.childModels['comment_textarea'][0];
		var mini_editor = this.comment_textarea.childModels['mini_editor'][0];
		var _textarea = $(mini_editor).find('textarea');
		if(_textarea.size() == 0) _textarea = $(mini_editor).find('input:eq(0)');
		var html = L('PUBLIC_RESAVE')+'@'+this.to_comment_uname+' ：';
		//清空输入框
		_textarea.val('');
		//_textarea.focus();
		_textarea.inputToEnd(html);
		_textarea.focus();
	},
	// 发表评论
	addComment:function(afterComment,obj) {
		var commentListObj = this.commentListObj;
		this.comment_textarea = commentListObj.childModels['comment_textarea'][0];
		var mini_editor = this.comment_textarea.childModels['mini_editor'][0];
		var _textarea = $(mini_editor).find('textarea');
		if(_textarea.size() == 0) {
			_textarea = $(mini_editor).find('input:eq(0)');
		}
		_textarea = _textarea.get(0);
		var strlen = core.getLength(_textarea.value);
		var leftnums = initNums - strlen;
		if(leftnums < 0 || leftnums == initNums) {
			flashTextarea(_textarea);
			return false;
		}
		// 如果转发到自己的分享
		if(this.canrepost == 1){
			var ischecked = $(this.comment_textarea).find("input[name='shareFeed']").get(0).checked;
			if(ischecked == true) {
				var ifShareFeed = 1;
			} else {
				var ifShareFeed = 0;
			}
		}else{
			var ifShareFeed = 0;
		}
		var isold = $(this.comment_textarea).find("input[name='comment']");
		var comment_old = 0;
		if( isold.get(0) != undefined) {
			if ( isold.get(0).checked == true  ){
				var comment_old = 1;
			}
		}
		var content = _textarea.value;	
		if(content == '') {
			ui.error(L('PUBLIC_CONCENT_TIPES'));
		}
		if("undefined" != typeof(this.addComment) && (this.addComment == true)) {
			return false;	//不要重复评论
		}
		var addcomment = this.addComment;
		var addToEnd = this.addToEnd;

		var _this = this;
		obj.innerHTML = '回复中..';
		$.post(U('widget/Comment/addcomment'),{
			app_name:this.app_name,
			table_name:this.table,
			app_uid:this.app_uid,
			row_id:this.row_id,
			to_comment_id:this.to_comment_id,
			to_uid:this.to_uid,
			app_row_id:this.app_row_id,
			app_row_table:this.app_row_table,
			content:content,
			ifShareFeed:ifShareFeed,
			comment_old:comment_old,
			app_detail_summary:$("#app_detail_summary").val(),
			app_detail_url:document.location.href,
			talkbox:this.talkbox
			},function(msg){
				if ( obj != undefined ){
					obj.innerHTML = '回复';
				}
				//alert(msg);return false;
				if(msg.status == "0"){
					ui.error(msg.data);
				}else{
					if("undefined" != typeof(commentListObj.childModels['comment_list']) ){
						if(addToEnd == 1){
							// $(commentListObj).find('.comment_lists').eq(0).append(msg.data);
							$(msg.data).insertAfter($(commentListObj.childModels['comment_list']).last());
						}else{
							$(msg.data).insertBefore($(commentListObj.childModels['comment_list'][0]));
						}
					}else{
						$(commentListObj).find('.comment_lists').eq(0).html(msg.data);
					}
					M(commentListObj);
					//重置
					_textarea.value = '';
					_this.to_comment_id = 0;
					_this.to_uid = 0;
					if("function" == typeof(afterComment)){
						afterComment();
					}
					// 动态添加字数
					var commentDom = $('#feed'+core.comment.row_id).find('a[event-node="comment"]');
					var oldHtml = commentDom.html();
					//alert(oldHtml);
					if (oldHtml != null) {
						var commentVal = oldHtml.replace(/\(\d+\)$/, function (num) {
							num = '(' + (parseInt(num.slice(1, -1)) + 1) + ')';
							return num;
						});
						//alert(commentVal);
						if (oldHtml === commentVal) {
							commentVal += '(1)';
						}
						commentDom.html(commentVal);
					}
				}
				addComment = false;
			//});
			},'json');
	},
	delComment:function(comment_id){
		$.post(U('widget/Comment/delcomment'),{comment_id:comment_id},function(msg){
			// 动态添加字数
			var commentDom = $('#feed'+core.comment.row_id).find('a[event-node="comment"]');
			var oldHtml = commentDom.html();
			if (oldHtml != null) {
				var commentVal = oldHtml.replace(/\(\d+\)$/, function (num) {
					var cnum = parseInt(num.slice(1, -1)) - 1;
					if (cnum <= 0) {
						return '';
					}
					num = '(' + cnum + ')';
					return num;
				});
				commentDom.html(commentVal);
			}
		});
	}
};
