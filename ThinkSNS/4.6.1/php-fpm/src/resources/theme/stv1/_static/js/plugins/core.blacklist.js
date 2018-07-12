/**
 * 黑名单模型
 */
core.blacklist = {	
			_init:function(attrs){
				if(attrs.length == 5){
					core.blacklist.init(attrs[1],attrs[2],attrs[3],attrs[4]);
				}else{
					return false
				}
				
			},
			init:function(obj,type,fid,isrefresh){
				//alert(isrefresh);exit;
				this.obj = obj;
				this.fid = fid;
				this.isrefresh = isrefresh;
				switch(type){ //操作类型
					case 'btn': //按钮操作
						core.blacklist.btn();
						break;
					case 'list':
						core.blacklist.list();
						break;
				}
				return false;
			},
			btn:function(){
				var obj = this.obj;
				var fid = this.fid;
				var isrefresh = this.isrefresh;
				if($(obj).attr('rel') =='add'){
					ui.confirm(obj,L('PUBLIC_ADD_PASSUSER_TIPES'),function(){
						$.post(U('widget/Blacklist/addUser'),{fid:fid},function(msg){
							if(msg.status == 0){
								ui.error(msg.data);
							}else{
								ui.success(msg.data);
								$(obj).attr('rel','remove');
								$('#follower_'+fid).fadeOut('slow');  //在我的粉丝页面用到
								//$(obj).html('<i class="ico-black"></i> '+L('PUBLIC_MOVE_PASSUSER_TIPES'));
								$(obj).html(L('PUBLIC_MOVE_PASSUSER_TIPES'));
								if(isrefresh==1) setTimeout("location.reload()",1000);
							}
						},'json');
					});
					return false;
				}
				if($(obj).attr('rel') =='remove'){
					ui.confirm(obj,L('PUBLIC_MOVE_PASSUSER_TIPES'),function(){
						$.post(U('widget/Blacklist/removeUser'),{fid:fid},function(msg){
							if(msg.status == 0){
								ui.error(msg.data);
							}else{
								$(obj).attr('rel','add');
								//$(obj).html('<i class="ico-black"></i> '+L('PUBLIC_ADD_PASSUDER'));
								$(obj).html(L('PUBLIC_ADD_PASSUDER'));
								if(isrefresh==1) setTimeout("location.reload()",1000);
							}
						},'json');
					});
					return false;
				}
				//异常
			},
			list:function(){
				var obj = this.obj;
				var fid = this.fid;
				ui.confirm(obj,L('PUBLIC_MOVE_PASSUSER_TIPES'),function(){
					$.post(U('widget/Blacklist/removeUser'),{fid:fid},function(msg){
						if(msg.status == 0){
							ui.error(msg.data);
						}else{
							$(obj).parent().fadeOut('slow');
						}
					},'json');
				});
				return false;
			}
			
	};