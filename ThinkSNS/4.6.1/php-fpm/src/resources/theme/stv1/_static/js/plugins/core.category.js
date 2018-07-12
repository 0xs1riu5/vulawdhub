// 分类展示模型
core.category = {	
	_init: function(attrs) {
		return false;
	},
	init:function(model,app,method,inputname){
		this.inputname = inputname;
		this.model_name = model;
		this.app_name = app;
		this.method_name = method;
		this.callback = '';
	},
	//点击选择分类
	loadSelect:function(obj,model,app,method,id,inputname,callback){
		
		var url = U('widget/Category/selectBox')+'&model_name='+model+'&app_name='+app+'&method='+method+'&id='+id;

		this.selectobj = obj;
		
		this.init(model,app,method,inputname);

		this.callback = callback;

		ui.box.load(url,'选择分类');
	},
	//选择分类弹窗里面确定
	select:function(){
		var _this = this;
		if(typeof(this.curId) === 'undefined') {
			ui.error('请选择分类');
			return false;
		}
		$.get(U('widget/Category/getCatePath')+'&model_name='+this.model_name+'&app_name='+this.app_name+'&method='+this.method_name,
			   {id:this.curId},function(msg){
			 	$('#'+_this.inputname+'_show').html(msg);
			 	$('#'+_this.inputname).val(_this.curId);
			 	
			 	if( _this.callback != ''){
			 		if("function" == typeof(_this.callback)){
			 			_this.callback();
			 		}else{
			 			eval(_this.callback+'()');
			 		}
			 	}

			 	ui.box.close();
		});

	},
	//分类选择弹窗里面修改值
	changeCate:function(obj){
		this.curId = $(obj).val();
		var _this = this;
		$(obj).next().remove();
		$.get(U('widget/Category/getChild')+'&model_name='+this.model_name+'&app_name='+this.app_name+'&method='+this.method_name,
			   {id:this.curId},function(msg){
			   		if(msg.status == 1){
			   			var html = '<select id="pid_'+_this.curId+'" size=1000 onchange="core.category.changeCate(this,this.value)">';			
			   			for(var i in msg.data){
			   				html += '<option value="'+i+'">'+msg.data[i]+'</option>';
			   			}
			   			html +='</select>';
			   			var _tmp = $(html);
			   			_tmp.insertAfter(obj);
			   		}
			   },'json');
	},
	clickCate:function(id){
		this.curId = id;
	},
	getCurId:function(){
		return this.curId;
	}
};		

