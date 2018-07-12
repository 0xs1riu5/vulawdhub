/*


 */
core.department ={
		//给工厂调用的接口
		_init:function(attrs){
			if(attrs.length == 7){
				//必须要定义一个选择框的对象ID和回调函数名称
				core.department.init(attrs[1],attrs[2],attrs[3],attrs[4],attrs[5],attrs[6]);
				this.departmentHash = new Array();// 数据缓存
				this.curId = 0;
				this.curText = '';
			}else if(attrs.length==6){
				//必须要定义一个选择框的对象ID和回调函数名称
				core.department.init(attrs[1],attrs[2],attrs[3],attrs[4],attrs[5]);
				this.departmentHash = new Array();// 数据缓存
				this.curId = 0;
				this.curText = '';
			}else{
				return false;
			}
		},
		init:function(selectid,callback,mod,sid,nosid,notop){
			
			if("undefined" == typeof(notop)){
				this.notop = 0;
			}else{
				this.notop = notop;
			}
			this.selectid = selectid;	

			this.callback = callback;		// 选中之后的回调函数
			
			this.mod	  = mod;			// 显示模型

			this.sid 	  = sid;

			this.nosid	  = nosid; 
			
			//绑定x
			this.bindSelected(0);	
			
		},
		bindSelected:function(pid){

			if(this.mod == 'select'){//下拉选择框
				$('#'+this.selectid).find('select').change(function(){
					core.department.selectDepart(pid,this,this.value,$(this).find('option:selected').text());
				});
			}	

		},
		selectDepart:function(pid,obj,sid,text){   

			$(obj).nextAll().remove();

			this.curId = sid ;

		   	if(sid < 1){
		   		this.curId = pid;
		   		this.curText = '';
		   		return false;
		   	}else{
		   		this.curText = text;
		   	}
		   	
		   	eval(this.callback+'('+this.curId+','+"'"+this.curText+"'"+')');

		    var _this = this;
		  	if("undefined" != typeof(this.departmentHash[sid])){
		  		this.displaySelect(sid,this.departmentHash[sid],obj);
		  		
		  		return false;
		  	}
		 

	        $.post(U('widget/Department/selectDepartment'),{pid:sid,sid:this.sid,nosid:this.nosid},function(msg){
	          if(msg.status == 1 && msg.data !=""){
	          	  _this.departmentHash[sid] = msg.data;
	          	  _this.displaySelect(sid,msg.data,obj);
	          	  
	          }
	          
	        },'json');
	        return false;
		},
		displaySelect:function(sid,data,prevObj){
			$(prevObj).nextAll().remove();
			
			if(data.length < 2){
				return false;
			}

			if(this.mod == 'select'){//下拉的显示方式
				var html = '<select style="height:200px" size="1000" name="select_'+sid+'" pid="'+sid+'">';
	    		for(var i in data){
	    			  html += '<option value='+i+' >'+data[i]+'</option>';
	    		}
	    		html +="</select>";
	    		$(html).insertAfter($(prevObj));	
			}

			this.bindSelected(sid);
			return false;
		}

};