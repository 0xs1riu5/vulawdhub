/**
 * @Me操作Js插件
 * @example
 * 工厂调用：core.plugInit('at',$(this)); 其中 $(this)为可编辑域对象
 * @author jason <yangjs@yeah.net>
 * @version TS3.0
 */
core.at = {
	/**
	 * 给工厂调用的接口
	 * @param array attrs 配置数组
	 * @return void
	 * @private
	 */
	_init: function(attrs) {
		if(attrs.length == 1) {
			return false; 		// 只是为了加载此文件
		} 
		if(attrs.length == 3) {
			core.at.init(attrs[1], attrs[2]);
		} else {
			core.at.init(attrs[1]);
		}
	},
	/**
	 * 初始化插件
	 * @param object textarea 查询的对象域
	 * @param function callback 回调函数
	 * @return void
	 */
	init: function(textarea, objat, callback) {
		this.searchModel = 'at', 			// 查询模式at、user
		this.notuser = '',					// 上次查询的无结果用户
		this.olduser = '',					// 上次查询的用户名
		this.searchTime = 0, 				// 当前搜索用户记录的次数
		this.textarea = textarea,			// 查询的对象域
		this.objat = objat;
		if(this.onsearch!=1){
			this.inputor = textarea;
		}
		this.atsetintval = '',				// 查询at的轮询
		this.userserintval = '', 			// 查询用户的轮询
		this.userList = '';					// 用户列表对象
		if("undefined" != typeof(callback)) {
			this.callback = callback;
		} else {
			this.callback = '';
		}
		
		this._startAt();					// 启动at查询
	},
	/**
	 * 插入@数据
	 * @param object textarea 查询的对象域
	 * @return void
	 */
	insertAt: function(url,textarea) {
		if(!url){
			url = U('public/Feed/getFriendGroup');
		}
		if("undefined" == typeof(textarea)) {
			textarea = this.textarea;
		}
		var val = textarea.val();
		
		if($('#atchoose').length>0){
			return false;
		}
		var html = '<div class="talkPop alL share_adds" id="atchoose" style="*padding-top:20px;" event-node="atdiv">'
			 + '<div class="wrap-layer">'
			 + '<div class="arrow arrow-t">'
			 + '</div>'
			 + '<div class="talkPop_box" style="width: 380px;min-width: 380px;">'
			 + '<div class="close hd"><a onclick=" $(\'#atchoose\').remove()" class="ico-close" href="javascript:void(0)" title="'+L('PUBLIC_CLOSE')+'"> </a><div class="at_search"><input id="at_search" type="text" value="搜索要@的好友" class="f9"/></div></div>'
			 + '<div class="faces_box" id="at_content"><img src="'+ THEME_URL+'/image/load.gif" class="alM"></div></div></div></div>';
		
		//$(this.parentDiv).append(html);
		$('body').append(html);
		var pos = $(this.objat).offset();
		
		$('#atchoose').css({top:(pos.top+5)+"px",left:(pos.left-5)+"px","z-index":1001});
		
		
		$.post(url , {} , function(res){
			$('#at_content').html(res);
		});
//		core.createImageHtml();
		
		$('body').bind('click',function(event){
			var obj = "undefined" != typeof(event.srcElement) ? event.srcElement : event.target;
			if($(obj).hasClass('face')){
				return false;
			}
			if($(obj).parents("div[event-node='atdiv']").get(0) == undefined){
				core.at._stopUser();
				$('#atchoose').remove();
			}
		});
		_this = this;
		_this.firstsearch = 0;
		$('#at_search').click(function(){
			if ( _this.firstsearch == 1 ) return;
			$(this).val('');
			_this.firstsearch = 1;
		}).blur(function (){
			if ( $(this).val() == '' ){
				$(this).val('搜索要@的好友');
				_this.firstsearch = 0;
			}
		});
		//textarea.val(val+'@');
//		textarea.inputToEnd('@');
//		textarea.mouseup();
		this.onsearch = 1;
		core.at.init($('#at_search'));
	},
	/**
	 * 开始@Me查询
	 * @return void
	 * @private
	 */
	_startAt: function() {
		this.searchModel = 'at';
		var _this = this;
		// 轮询获取@的位置，验证是否插入@查询
		var loopSearchAt = function() {
			if(_this.searchModel != 'at') {
				core.at._stopAt();
				return false;
			}
			if ( _this.firstsearch == 0 ) return;
			var str = _this.textarea.val();
			if(str.length > 0) {
				core.at._startUser();		// 进入预查找用户模式
			} else {
				$('#atUserList').remove();
				$('#friendchoose').show();
			}
		};
		this.atsetintval = setInterval(loopSearchAt, 250);
	},
	/**
	 * 插入用户数据，插入到查询对象域中
	 * @param integer uid 用户ID
	 * @param string uname 用户昵称
	 * @return void
	 */
	insertUser: function(uname) {
//		var str = this.textarea.value();
//		var findAt = str.lastIndexOf('@');
//		var oldHtml = str.substring(0, findAt);
////		this.searchModel = '';
////		this._stopUser();
////		this._removeUserList();
//	    this.textarea.blur();
//		//this.textarea.text(oldHtml+'<label><a href="###" data="@{uid='+uid+'|'+uname+'}">@'+uname+'</a></label>&nbsp;');
//	    this.textarea.click();
//		core.bindkey.unbind();
		this.inputor.inputToEnd('@'+uname+' ');
//		this._startAt();
	},
	/**
	 * 停止@查询
	 * @return void
	 * @private
	 */
	_stopAt: function() {
		if(this.atsetintval != '') {
			clearInterval(this.atsetintval);
			this.atsetintval = '';
			this._removeUserList();
		}
		this.searchModel = '';
	},
	/**
	 * 选择用户
	 * @return void
	 */
	selectUser: function() {
		var curUser = this.userList.find('.mod-at-list').find('.current');
		if(curUser.length > 0) {
			var uid = curUser.attr('uid');
			var uname = curUser.attr('uname');
			this.insertUser(uname);
		}
		return true;
	},
	/**
	 * 开始查询用户
	 * @return void
	 * @private
	 */
	_startUser: function() {
		this._stopAt();					// 关掉进程中的轮询
		this.searchModel = 'user';
		var _this = this;
		var loopSearchUser = function() {
			// 验证下@是否正常
			var str = _this.textarea.val();
			
			var searchUser = function(searchTime, user) {
				$.post(U('widget/SearchUser/search'), {key:user, type:'at'}, function(msg) {
					// 超时判断
					if(searchTime != _this.searchTime) {
						return false;
					}
					// 判断数据正确性
					if(msg.status == 0 || msg.data == null || msg.data == "" || msg.data.length == 0) {
						_this.notuser = user;
						core.at._stopUser();
					} else {
						_this.notuser = '';
						if("function" == typeof(_this.callback)){
							_this.callback(msg.data);
							core.at._stopUser();
							return false;
						}
						var data = msg.data;
						_this.notuser = '';
						// 组装列表数据
						if(data.length > 0) {
							var html = '<ul class="at-user-list">';
							for(var i in data) {
								var current = i==0 ? 'class="current"' : '';
									html += '<li onclick ="core.at.insertUser('+'\''+data[i].uname+'\')" uid="'+data[i].uid+'" uname="'+data[i].uname+'" '+current+'>\
											<div class="face"><img src="'+data[i].avatar_small+'" width="20px" height="20px" /></div>\
				 	 						<div class="content"><a href="javascript:void(0)">'+data[i].uname+'</a></div></li>';//<span>'+data[i].email+'</span>
				 	 			}
				 	 			html += '</ul>';
				 	 			// 插入数据
				 	 			if ( _this.userList == '' ){
				 	 				var userhtml = "<div class='mod-at-wrap' id='atUserList'><div class='mod-at'><div class='mod-at-list'>\
										<div class='mod-at-tips'>"+L('PUBLIC_AT_FOLLOWING')+"</div>\
										</div></div></div>";
				 	 				_this.userList = $(userhtml);
				 	 				
				 	 				$('#friendchoose').hide();
				 	 				$('#at_content').append(_this.userList);
//				 	 				_this.userList.appendTo('body');
//				 	 				_this._showUserList();
				 	 			}
				 	 			_this.userList.find('.mod-at-list').html(html);
				 	 			// 绑定选中事件
				 	 			_this.userList.find('.mod-at-list').find('li').hover(function(){
									$(this).addClass('hover');	
								},function(){
									$(this).removeClass('hover');
								});
				 	 			// TODO:方向键控制
//				 	 			core.plugInit('bindkey',$(_this.userList.find('.mod-at-list')),'li','current','core.at.selectUser()');
				 	 		} else {
				 	 			// 直接添加
				 	 			core.at.insertUser(data.uname);
				 	 			core.at._stopUser();
				 	 		}
				 	 	}
				}, 'json'); 
			};
			if( str ) {
				var user = str;
				// 移除列表
				if(user == '') {
					_this.olduser = '';
					_this._removeUserList();
//					core.at._createUserlistDiv(user);
					return false;
				}
				// 创建列表
				if(user != "" && user != _this.olduser){
					_this.olduser = user;
					_this.searchTime++;
//					core.at._createUserlistDiv(user);
					searchUser(_this.searchTime, user);
				}
			} else {
				core.at._stopUser();
			}
		}
		// 轮询事件绑定
		this.userserintval = setInterval(loopSearchUser, 250);
	},
	/**
	 * 停止用户查询
	 * @return void
	 * @private
	 */
	_stopUser: function() {
		if(this.userserintval != '') {
			clearInterval(this.userserintval);
			this.userserintval = "";
		}
		this.searchModel = "";
		this._removeUserList();
		core.at._startAt();
	},
	/**
	 * 创建用户DIV窗口
	 * @return void
	 * @private
	 */
	_createUserlistDiv: function(user) {
		// 验证数据正确性
		if(typeof(this.userList) != "string" || this.userList.length > 0) {
			return false;
		}
		// 数据模板
		if(!user && document.getElementById('atUserList') == null){   //@后跟了查找不到用户的字符时不显示提示框2012/10/12
			var html = "<div class='mod-at-wrap' id='atUserList'><div class='mod-at'><div class='mod-at-list'>\
						<div class='mod-at-tips'>"+L('PUBLIC_AT_FOLLOWING')+"</div>\
						</div></div></div>";
	        this.userList = $(html);
	        this.userList.appendTo('body');
	       	this._showUserList();
       	}
	},
	/**
	 * 显示用户查询列表
	 * @return void
	 * @private
	 */
	_showUserList: function() {
		// 定位问题
		var x = this.textarea.offset();
        this.userList.css({'left':(x.left-1)+'px','top':(x.top+this.textarea.height()+10)+'px','width':this.textarea.width()+10+'px','display':'block'});
        return false;
	},
	/**
	 * 移除用户查询列表
	 * @return void
	 * @private
	 */
	_removeUserList: function() {
		if(document.getElementById('atUserList') !== null) {
			$('#atUserList').remove();
		}
		this.userList = '';
		return false;
	}
 };