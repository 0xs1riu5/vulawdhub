//主要是对加载进来的iframe元素的操作，拖动、删除、编辑、保存，都在这儿。

$.IE6 = (function(){
	var userAgent = navigator.userAgent;
	if (/MSIE [7891]\d?/.test(userAgent)) return false;
	if (/MSIE [56]/.test(userAgent)) return true;
	return false;
})();
var Action={
	obj:null,//代表的是某一个已经添加的区块,一般是正在操作的区块。
	draging:0,
	contents:'',
	offsetBody:0,
	offsetClick:{width:0, height:0},
	isview:1,
	confirm:{
		set:function(msg){
			window.onbeforeunload = function(){
				window.frames['themeFrame'].onbeforeunload = function(){};
				return '您的修改尚未保存，点击 确定 将丢失所有修改！\n\n继续编辑请点 取消 按钮。';
			}
			window.frames['themeFrame'].onbeforeunload = function(){
				return '您的修改尚未保存，点击 确定 将丢失所有修改！\n\n继续编辑请点 取消 按钮。';
			}
		},
		clear : function(){
			window.onbeforeunload = function(){};
			window.frames['themeFrame'].onbeforeunload = function(){};
		}
	},
	
	view:function(){//显示或者不显示可添加区域。
		if(this.isview>0){
			area.addClass('view');
			$("#view").val('去掉虚线框');
		}else{
			area.removeClass('view');
			$("#view").val('查看可添加区域');
		}
		this.isview*=-1;
	},
	
	//二次封装 $.ajax 
	post: function(url, data, callback, dataType){
		$.ajax({
			url:url,
			type:'POST',
			data:data,
			dateType:dataType,
			success: function(data){
				if (/xml/i.test(dataType)){
					if('object' == typeof data){
						callback(data);
					}else{
						//意外，比如登录超时等。返回的不是XML信息，而是没有权限的html页面
						alert("提示：程序发生错误或者您的登录已经超时，请关闭当前窗口重新载入。");
					}
				}else{
					callback(data);
				}
			},
			error: function(XMLHttpRequest, textStatus){
				alert('提示：服务器出现异常，请稍候再试。');
			}
		});
	},
	
	del:function(){
		if(confirm('确认将此区块删除?')){
			var self=this.obj;
			/*
			//这种方式，在遇到异常时不能给出提示。
			//顾在一定需要返回结果的情况下，最好使用$.ajax()方式
			//同时，为了简化$.ajax()，可对其进行二次封装
			$.post("?admin_theme-delblock",{bid:self.attr('bid')},function(xml){
				var message=xml.lastChild.firstChild.nodeValue;
				if(message=='ok'){
					self.remove();
					toolbar.hide();
					bindblk();
				}
			},'xml');
			*/
			this.post("?admin_theme-delblock",{bid:self.attr('bid')},function(xml){
				var message=xml.lastChild.firstChild.nodeValue;
				if(message=='ok'){
					self.remove();
					toolbar.hide();
					bindblk();
				}
			},'xml');
			
			Action.confirm.set();
		}
	},
	exit: function(){
		window.close();
	},
	edit:function(){
		var bid=this.obj.attr('bid');
		this.post("?admin_theme-getconfig",{bid:bid},function(xml){
			var params=xml.getElementsByTagName("params")[0].childNodes[0].nodeValue;
			var contents=xml.getElementsByTagName("contents")[0].childNodes[0].nodeValue;
			$.dialog({
				id:'edit_block',
				position:'center',
				title:'编辑版块',
				width:550,
				height:500,
				content:'<form><div id="editconfig" class="main2"></div></from><p class="col-p m-l140"><input id="editbtn" type="button" class="btn" value="编辑" /></p>',
				callback:function(){
					$("#config").html('');
					if(contents==''){
						contents='本版块没有要设置的参数！';
						params='';
						$("#editbtn").val('完成').click(function(){
							$.dialog.close('edit_block');
							Action.confirm.set();
							block.istpl = 1;
						});
					}else{
						$("#editbtn").val('编辑').click(function(){
							block.complete(bid);
							Action.confirm.set();
							block.istpl = 1;
						});
					}
					$("#editconfig").html(contents);
					Action.contents = $('#tplcontent').val();
					if(params!=''){
						eval("params="+params);
						var paramsObj=$("[name^='params']");
						$.each(paramsObj,function(i,n){
							var s = $(n).attr('name');
							var name=s.slice(s.indexOf('[')+1,s.lastIndexOf(']'));
							name=name.replace(/[\'\"]/g,'');
							$(n).val(params[name]);
						});
					}
				}
			});
		},'xml'); 
	},
	drag:function(e){
		var self=this, obj=this.obj, offset=obj.offset();//self=this  就是将Action对象传给了self。
		space.height(obj.height()-5).show();
		obj.before(space);//block前面加上space
		obj.unbind('mouseover').css('opacity', 0.8);//卸掉block的mouseover事件，并将其透明度降到60.
		toolbar.hide();//隐藏toolbar
		obj.css('width', obj.width()).addClass('draging').css({left:offset.left-Action.offsetBody, top:offset.top-10});
		Action.draging=1;
		
		self.offsetClick.width=e.pageX - offset.left;
		self.offsetClick.height=e.pageY - offset.top;
		
		//按住左键，移动鼠标
		$(Fdocument).mousemove(function(e){
			var x=e.clientX + Fdocument.documentElement.scrollLeft, y=e.clientY + Fdocument.documentElement.scrollTop;//得到当前的鼠标值，包括滚动条。
			var left = x, top = y-15;
			obj.css({left:left -Action.offsetBody -self.offsetClick.width, top:top});//block随着鼠标移动。
			self.move(x,y);//将鼠标坐标传给self.move.
			return false;
		});
		
		//放开鼠标左键，将拖动元素放置到新位置
		$(Fdocument).one('mouseup',function(){
			self.draging=0;
			$(Fdocument).unbind("mousemove");
			obj.removeClass('draging').removeAttr("style");
			space.before(obj).hide();
			
			obj.mouseover(function(){
				self.showToolbar($(this));
			});
			Action.confirm.set();
		});
	},
	
	/*
	* 计算鼠标坐标，在哪个板块区域。
	* 这里不能使用 onmouseover 事件，因为被拖动到板块会遮挡触发其他板块的 onmouseover 事件。
	*/
	move: function(x,y){
		var self=this, width, height, left, top, thisArea, thisBlk;
		for(var i=0; i<area.length; i++){//循环area对象数组。
			var div=$(area[i]);
			left = div.offset().left;
			top = div.offset().top;
			if(x > left && x < left + div.width() && y > top && y < top + div.height()){//鼠标属于这个范围了。
				thisArea=div;
				break;
			}
		}
		if(!thisArea) return;//鼠标不在任何区域，返回。
		var thisBlks=thisArea.children("div[bid!='"+self.obj.attr('bid')+"']");//得到当前区域的下的已有区块（去掉了P标签的space和正在拖动的区域）。
		if(thisBlks.length==0){//没有区块，则直接在这个区域添加space,返回。
			thisArea.append(space);
			return;
		}
		for(var i=0; i<thisBlks.length; i++){
			var blk=$(thisBlks[i]);
			left = blk.offset().left;
			top = blk.offset().top;
			if(x > left && x < left + blk.width() && y > top && y < top + blk.height()){//鼠标属于这个区块范围了。
				thisBlk = blk;
				break;
			}
		}
		if(thisBlk){//鼠标在某一个区域，执行下面操作。
			var method= (y < thisBlk.offset().top + thisBlk.height()/2)? 'before': 'after';
			thisBlk[method](space);
		}
	},
	
	showToolbar: function(block){
		if(!Action.draging){ //如果并非拖动状态。
			Action.obj=block;//将obj赋值。
			var offset=block.offset(), width=block.width(), height=block.height();
			var left=offset.left- Action.offsetBody;
			if($.IE6){
				left+=10;
			}
			if(toolbar.css('display') == 'none'){
				toolbar.css({left:left, top:offset.top, width:width, height:height}).show();
			}else{
				toolbar.stop().animate({left:left, top:offset.top, width:width, height:height}, 100);
			}
		}
	}
	
}

