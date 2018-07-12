//追加核心地区选择对象 临时放在这里 
core.area ={
		//给工厂调用的接口
		_init:function(attrs){
			if(attrs.length == 3){
				core.area.init(attrs[1],attrs[2]);
			}else{
				return false;	//只是未了加载文件
			}
		},
		init:function(xxx,bbb){
			
		}

		
};		