//追加核心widget选择 临时放在这里 
core.widget = {
		//给工厂调用的接口
		_init:function(attrs){
			return false;	//只是未了加载文件
		},
		//移除
		removeWidget:function(obj,args,pobj){
			var doremove = function(){
				//移出操作
				var url = U('widget/Diy/del')+'&diyId='+args.diyId+'&appname='+args.appname+'&widget_name='+args.widget_name;
				$.get(U('widget/Diy/del'),{diyId:args.diyId,appname:args.appname,widget_name:args.widget_name},function(data){
					if(data.status == 0){
						ui.error( L('PUBLIC_DELETE_ERROR') );
					}else{
						ui.success( L('PUBLIC_DELETE_SUCCESS') );
						$(pobj).remove();
					}			
				},'json');
			};
			ui.confirm(obj,L('PUBLIC_MOVE_WEIGET'),doremove);
		},
		addWidget:function(args){
			var url = U('widget/Diy/addWidget')+'&widget_user_id='+args.widget_user_id+'&diyId='+args.diyId;
			ui.box.load(url,L('PUBLIC_ADD_WEIGET'));
		},
		afterSet:function(data){
			if(data.status == 0){
				ui.error(data.info);
			}else{
				ui.success(data.info);
				//todo 以后优化成局部刷新
				setTimeout("location.href = location.href",1000);	
			}
		},
		//添加操作
		doadd:function(diyId,selected){
			$.post(U('widget/Diy/doadd'),{diyId:diyId,selected:selected},function(data){
				if(data.status == 0){
					ui.error(data.info);
				}else{
					ui.success(data.info);
					//todo 以后优化成局部刷新
					setTimeout("location.href = location.href",1000);	
				}
			},'json');
		},
		doconfig:function(diyId,selected){
			$.post(U('widget/Diy/doconfig'),{diyId:diyId,selected:selected},function(data){
				if(data.status == 0){
					ui.error(data.info);
				}else{
					ui.success(data.info);
					setTimeout("location.href = location.href",1000);	
				}
			},'json');
		},
		dosort:function(args,obj){
			var id = args.diyId;
			M(obj);
			var child = obj.childModels['widget_box']; 	//重新获取子节点
			var targets = new Array();
			for(var i in child){
				var a = M.getModelArgs(child[i]);
				targets[i] = a.appname+':'+a.widget_name;
			}
			targets = targets.join(',');
			$.post(U('widget/Diy/dosort'),{diyId:id,targets:targets},function(){});
		}
};		