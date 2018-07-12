  M.addModelFns({
	  add_comment:{
		  click:function(){
			if(this.clicked == true){
				this.clicked = false;
				this.parentModel.childModels['share_message'][0].style.display = 'none';
			}else{
				this.parentModel.childModels['share_message'][0].style.display = 'block';
				this.clicked = true;
				var mini_editor = this.parentModel.childModels['share_message'][0].childModels['weibo_post_box'][0].childModels['mini_editor'][0];
				$(mini_editor).find('textarea').focus();
			}
		  }
	  }
  }).addEventFns({
  		share_insert_face:{
			click:function(){
				var _parentModel = this.parentModel.childModels["weibo_post_box"][0];
				var textarea = _parentModel.childModels['mini_editor'][0].childModels["mini_editor_textarea"][0];
				var _faceDiv = _parentModel.childModels['mini_editor'][0].childModels['facediv'][0];
				core.plugInit('face',this,textarea,_faceDiv);
			}	
		},
		post_share:{//发布分享
			click:function(){
				var _this = this;
				var weibo_post_box = this.parentModel.parentModel.childModels['weibo_post_box'][0];
				var mini_editor = weibo_post_box.childModels['mini_editor'][0];
				var textarea = $(mini_editor).find('textarea').get(0);
				core.share.post_share(_this,mini_editor,textarea);
			},
			load:function(){
				core.plugInit('share');//载入分享js
			}
		},
		share_menu:{
			click:function(){
				var attrs = M.getEventArgs(this);
				$(this).parent().parent().find('li').removeClass('current');
				$(this).parent().addClass('current');
				var share_input = this.parentModel.childModels['share_input'];
				for(var i in share_input){
					var _attrs = M.getEventArgs(share_input[i]);
					if(_attrs.to == attrs.to){
						share_input[i].style.display = 'block';
					}else{
						share_input[i].style.display = 'none';
					}
				}
			}
		},
	  share_message:{
		  click:function(){
			  	if("undefined" == typeof(this.share) || this.share == false){
			  		this.share = true	
			  	}else{
			  		return false; //避免重复提交
			  	}
				var _this = this;
				var mini_editor = this.parentModel.childModels['share_comment_box'][0].childModels['share_message'][0].childModels['weibo_post_box'][0].childModels['mini_editor'][0];
				var textarea = $(mini_editor).find('textarea').get(0);
				var data = textarea.value;
				var attrs =M.getEventArgs(this);
				var uids = $('#search_uids').val();
				
				if(uids ==''){
					ui.error(L('PUBLIC_PLEASE_PARENTER'));
					this.share = false;	
					return false;
				}
				
				//验证字数有没有超过
				var _checkNums = function(obj){
					var str = obj.value;

					var _length = core.getLength(str);
					
					if((initNums - _length) < 0){
						return false;
					}else{
						return true;
					}	
				}
				if(_checkNums(textarea) == false){
					flashTextarea(textarea);
					this.share = false;
					return false;
				}
				
				$.post(U('public/Share/shareMessage'),{content:data,type:attrs.type,app_name:attrs.app_name,sid:attrs.sid,curid:attrs.curid,curtable:attrs.curtable,uids:uids},function(msg){
					_this.share = false;
					if(msg.status == 1){
						ui.box.close();
						ui.success( L('PUBLIC_SHARE_SUCCESS') );
					}else{
						ui.error(msg.data);
						ui.box.close();
					}
					
				},'json');
				return false;
		  }
	  }
  });