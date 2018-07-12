if("undefined" == typeof(initNums)){
	var initNums = "140";
}
if("undefined" == typeof(maxId)){
	var maxId = 0;
}
if("undefined" == typeof(loadId)){
	var loadId = 0;
}
if("undefined" == typeof(feedType)){
	var feedType = 'following';	// 默认的分享类型(关注的)
}
if("undefined" == typeof(feed_type)){
	var feed_type ='';
}
if("undefined" == typeof(feed_key)){
	var feed_key = '';
}
if("undefined" == typeof(loadmore)){
	var loadmore = 0;
}
if("undefined" == typeof(loadnew)){
	var loadnew = 0;
}

if("undefinde" == typeof(fgid)){
	var fgid = '';
}

if("undefined" == typeof(topic_id)) {
	var topic_id = 0;
}

if("undefinde" == typeof(gid)){
	var gid = 0;
}

var _doc = document;
var feedbtnlock = 0;
var args = new Array();
args['initNums'] 	= initNums;
args['maxId']		= maxId;
args['loadId']		= loadId;
args['feedType']   	= feedType;
args['loadmore']   	= loadmore;
args['loadnew']   	= loadnew;
args['uid']			= UID;
args['feed_type']   = feed_type;
args['feed_key']	= feed_key;
args['topic_id'] 	= topic_id;
args['gid'] 	= gid;

if("undefined" == typeof(core.groupfeed)){	//只init一次
	core.plugFunc('groupfeed',function(){
		core.groupfeed.init(args);	
	});
}
/**
 * 事件绑定器
 */
M.addEventFns({
	post_group_feed:{	//发布普通|图片分享 
		click:function(){
			if($('.upload_tips').length >0){
				wap_error( L('PUBLIC_ATTACH_UPLOADING_NOSENT') );
				return false;
			}
			var _this = this;
			var mini_editor = this.parentModel.parentModel.childModels['mini_editor'][0];			
			var textarea = $(mini_editor).find('textarea').get(0);
			core.groupfeed.post_feed(_this,mini_editor,textarea,0);
		}
	},
	group_comment:{	
		click:function(){	//点击评论的时候
			var attrs = M.getEventArgs(this);
			///console.log(attrs);console.log(2222222);return false;
			var comment_list = this.parentModel.childModels['comment_detail'][0];
			if("undefined" == typeof(core.groupcomment)){
				core.plugInit('groupcomment',attrs,comment_list);
				core.setTimeout("core.groupcomment.display()",150);
			}else{
				core.groupcomment.init(attrs,comment_list);
				core.groupcomment.display();
			}
			return false;
		}
	},
	group_reply_comment:{	//点某条回复
		click:function(){
			var attrs = M.getEventArgs(this);
			var comment_list = this.parentModel.parentModel;
			var docomment = comment_list.childModels['comment_textarea'][0].childEvents['group_do_comment'][0];
			$(docomment).attr('to_comment_id',attrs.to_comment_id);
			$(docomment).attr('to_uid',attrs.to_uid);
			$(docomment).attr('to_comment_uname',attrs.to_comment_uname);
			core.plugFunc('groupcomment',function(){
				core.groupcomment.init(attrs,comment_list);
				core.groupcomment.initReply();
			});
			//core.plugInit('comment',attrs,comment_list);
			//core.setTimeout("core.groupcomment.initReply()",150);
		}
	},
	group_comment_del:{
		click:function(){
			var attrs = M.getEventArgs(this);
			$(this.parentModel).fadeOut();
			if("undefined"==typeof(core.groupcomment)){
				core.plugFunc('groupcomment',function(){
					core.groupcomment.delComment(attrs.comment_id,attrs.gid);
				});
			}else{
				core.groupcomment.delComment(attrs.comment_id,attrs.gid);	
			}
		}
	},
	group_do_comment:{	//回复操作
		click:function(){
			if ( this.noreply == 1 ){
				return;
			}
			var attrs = M.getEventArgs(this);
			attrs.to_comment_id = $(this).attr('to_comment_id');
			attrs.to_uid = $(this).attr('to_uid');
			attrs.to_comment_uname = $(this).attr('to_comment_uname');
			attrs.addToEnd = $(this).attr('addtoend');
			var comment_list = this.parentModel.parentModel;

			var _this = this;
			var after = function(){
				$(_this).attr('to_uid','0');
				$(_this).attr('to_comment_id','0');
				$(_this).attr('to_comment_uname','');
				if(attrs.closeBox == 1){
					ui.box.close();
					wap_success( L('PUBLIC_CENTSUCCESS') );
				}
			}
			if("undefined"==typeof(core.groupcomment)){
				core.plugFunc('groupcomment',function(){
					core.groupcomment.init(attrs,comment_list);
					core.groupcomment.addComment(after,this);
				});
			} else {
				core.groupcomment.addComment(after,this);
			}
			this.noreply = 1;
			setTimeout(function (){
				_this.noreply = 0;
			},5000);
		}
	},
	group_feed_share:{//分享操作
		click : function(){
			var attrs =M.getEventArgs(this);
			//alert(typeof(attrs));exit;
			// if(attrs.appname == 'weiba' && attrs.feedtype == 'weiba_post'){
			// 	var sid = attrs.curid;
			// }else{
			var sid = attrs.sid;
			//}
			var stable = attrs.stable;
			var initHTML = attrs.initHTML;
			var curtable =attrs.curtable;
			var curid = attrs.curid;
			var appname = attrs.appname;
			var cancomment = attrs.cancomment;
			var is_repost = attrs.is_repost;
			var gid = attrs.gid;
			if("undefined" == typeof(cancomment)){
				cancomment = 0;
			}
			var url = U('group/Group/shareFeed')+'&sid='+sid+'&stable='+stable+'&curid='+curid+'&curtable='+curtable+'&appname='+appname+'&initHTML='+initHTML+'&cancomment='+cancomment+'&is_repost='+is_repost+'&gid='+gid;
			if($('#tsbox').length>0){
				return false;
			}
			ui.box.load(url,L('PUBLIC_SHARE'),function(){
				$('#at-view').hide();
				var share_id="feed"+curid;
				window.location.hash=share_id;
			});
			
			return false;
		}
	},
	group_post_share:{//发布分享
		click:function(){
			var _this = this;
			var weibo_post_box = this.parentModel.parentModel.childModels['weibo_post_box'][0];
			var mini_editor = weibo_post_box.childModels['mini_editor'][0];
			var textarea = $(mini_editor).find('textarea').get(0);
			
			var obj = this;
			if( core.groupfeed.checkNums(textarea,'post') == false){
				flashTextarea(textarea);
				return false;
			}
			var data = textarea.value;
			if(data == '' || data.length<0){
				wap_error( L('PUBLIC_CENTE_ISNULL') );
				return false;
			}
			// 获取评论checkbox值
			var comment_input = $(_this.parentModel).find('input');

			if( comment_input.attr('checked') == 'checked' ){
				var ifcomment = 1;
			}else{
				var ifcomment = 0;
			}
			var attrs =M.getEventArgs(_this);
			$.post(U('group/Group/doShareFeed'),{body:data,type:attrs.type,gid:attrs.gid,app_name:attrs.app_name,sid:attrs.sid,content:'',comment:ifcomment,curid:attrs.curid,curtable:attrs.curtable},
				function(msg){
				if(msg.status == 1){
					if(MID == UID){
						core.groupfeed.insertToList(msg.data);
					}
					
					wap_success( L('PUBLIC_SHARE_SUCCESS') );
				}else{
					wap_error(msg.data);
				}
			},'json');
			ui.box.close();
		}
	},
	group_delFeed:{
		click:function(){
			
			var attrs = M.getEventArgs(this);

			var _this = this;
			var delFeed =  function(){
				$.post(U('group/Group/removeFeed'),{feed_id:attrs.feed_id,gid:attrs.gid},function(msg){
					if(msg.status == 1){
						if($('#feed'+attrs.feed_id).length > 0){
							$('#feed'+attrs.feed_id).fadeOut();
						}else{
							$(_this.parentModel).fadeOut();
						}
						if(attrs.isrefresh == 1){    //在分享详情页删除后跳转到首页
							window.location.href = U('w3g/Group/index')+'&gid='+attrs.gid;
						}
					}else{
						wap_error( L('PUBLIC_DELETE_ERROR') );
					}
				},'json');
			};
			if(confirm(L('PUBLIC_DELETE_THISNEWS'))){
				delFeed();
			}
			//ui.confirm(this,L('PUBLIC_DELETE_THISNEWS'),delFeed);
		}
	}
})
var groupatwho = function (obj){
	obj.atWho("@",{
        tpl:"<li id='${id}' data-value='${searchkey}' input-value='${name}'><img src='${faceurl}'  height='20' width='20' /> ${name}</li>"
            ,callback:function(query,callback) {
            	if ( keyname.text !='' ){
	            	$.ajax({
	                    url:U('group/Member/searchuser')
	                    ,type:'POST'
	                    ,data: "gid="+$(this).attr('gid')+"&key="+keyname.text
	                    ,dataType: "json"
	                    ,success:function(res) {
	                    	if ( res.data == null ){
	                    		$('#at-view').hide();
	                    		return;
	                    	} else {
	    	                    datas = $.map(res.data,function(value,i){
	    	                        return {'id':value.uid,'key':value.uname+":",'name':value.uname,'faceurl':value.avatar_small,'searchkey':value.search_key}
	    	                        })
	                    	}
	                        callback(datas)
	                    }
	                })
            	}
            }
     })
}