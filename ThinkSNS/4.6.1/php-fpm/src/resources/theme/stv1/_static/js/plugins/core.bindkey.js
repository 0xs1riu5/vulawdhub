core.bindkey = {	//绑定键盘事件
			_init:function(attrs){
				if(attrs.length == 1){
					return false; // 意思是执行插件 只是为了加载此文件
				} 
				if(attrs.length == 5){
					core.bindkey.init(attrs[1],attrs[2],attrs[3],attrs[4]);
				}else if(attrs.length == 4){
					core.bindkey.init(attrs[1],attrs[2],attrs[3]);
				}else{
					core.bindkey.init(attrs[1],attrs[2]);
				}
			},
			init:function(obj,childName,curClass,callback){
				this.obj = obj;
				this.childName = "undefined" == typeof(childName) ? 'li' : childName;
				this.curClass = "undefined" == typeof(curClass) ? 'current' : curClass;
				if("undefined" != typeof(callback)) this.callback = callback;
				var type = $.browser.opera ? "keypress" : "keydown";
				try{
					$('body').unbind(type,core.bindkey.bindfuc);
				}catch(e){
					var ex = 1;
				}
				$('body').bind( type,core.bindkey.bindfunc);
				this.curNodeSize = 0;
			},
			bindfunc:function(event){
				var  e= event ? event : window.event; 
				var keycode = e.which||e.keyCode;  
			    switch(keycode){  
			        case 1:  
			        case 38:  
			        case 269: //up  
			            core.bindkey.up();  
			            break;  
			        case 40:  
			        case 2:  
			        case 270: //down  
			            core.bindkey.down(); 
			            break;  
			        case 13: //enter  
			            core.bindkey.enter();  
			            break;  
			    }  
			    return true;
			},
			unbind:function(){
				var type = $.browser.opera ? "keypress" : "keydown";
				$('body').unbind(type,core.bindkey.bindfuc);
			},
			getcurNodeSize:function(){
				var curNode = this.obj.find('.'+this.curClass);
				if(curNode.length < 1){
					curNode = this.obj.find(''+this.childName+'').eq(0);
					curNode.addClass(this.curClass);
				}
				var _this = this;
				this.obj.find('li').each(function(i){
					if($(this).hasClass(this.curClass)){
						_this.curNodeSize = i;
					}
				});
				return _this.curNodeSize;
			},
			up:function(){
				var curNode = this.obj.find(''+this.childName+'').eq(this.curNodeSize);
				var prevNode = curNode.prev();
				if(prevNode.length < 1){
					return false;
				}
				if(prevNode[0].tagName.toLocaleLowerCase() != this.childName.toLocaleLowerCase()){
					return false;
				}
				//TODO  滚动条
				this.curNodeSize -=1;
				curNode.removeClass(this.curClass);
				prevNode.addClass(this.curClass);
				return false;
			},
			down:function(){
				var curNode = this.obj.find(''+this.childName+'').eq(this.curNodeSize);
				var nextNode = curNode.next();
				if(nextNode.length < 1){
					return false;
				}
				if(nextNode[0].tagName.toLocaleLowerCase() != this.childName.toLocaleLowerCase()){
					return false;
				}
				//TODO 滚动条
				this.curNodeSize +=1;
				curNode.removeClass(this.curClass);
				nextNode.addClass(this.curClass);
				return false;
			},
			left:function(){
				return false;
				alert('left');
			},
			right:function(){
				return false;
				alert('right');
			},
			enter:function(){
				if("undefined" == typeof(this.callback))
				{
					return false;
				}	
				if("function" == typeof(this.callback)){
					this.callback();
					return false;
				}
				if('string' == typeof(this.callback)){
					var backFunc = this.callback;
					eval(" "+backFunc+" ");					
				}
				return false;
			},
			back:function(){
				return false;
				alert('back');
			}
	};