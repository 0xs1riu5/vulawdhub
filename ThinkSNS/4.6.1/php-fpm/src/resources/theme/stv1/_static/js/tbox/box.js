// ThinkSNS ui.box

jQuery.extend(ui, {
	box:function(element, options){
	   
	    
	}
});


jQuery.extend(ui.box, {
                
    WRAPPER:     '<div class="wrap-layer" id="tsbox">'+
            	'<div class="content-layer">'+
                '<div class="layer-content clearfix" id="layer-content"></div>'+
                '</div></div>',
              
    inited:             false,
    IE6:                (jQuery.browser.msie && jQuery.browser.version < 7),
    init:function(title,callback){
    	
    	//edit by yangjs 避免意外情况重载
		if( !this.inited ){
		$('body').prepend( this.WRAPPER );
			this.inited = true;
		}else{
			return false;
		}
		
		if("undefined" != typeof(title)){
			$("<div class='hd'>"+title+"<a class='ico-close' href='#'></a></div>").insertBefore($('#tsbox .layer-content'));
		}
		$('#tsbox').show();
		
		jQuery('<div class="boxy-modal-blackout"></div>')
        .css(jQuery.extend(ui.box._cssForOverlay(), {
            zIndex: 991, opacity: 0.6
        })).appendTo(document.body);
		
		
		$('#tsbox').stop().css({width: '', height: ''});
		
		jQuery(document.body).bind('keypress.tsbox', function(event) {
			var key = event.keyCode?event.keyCode:event.which?event.which:event.charCode;
            if (key == 27) {
            	jQuery(document.body).unbind('keypress.tsbox');
            	ui.box.close(callback);
                return false;
            }
        });

		$('#tsbox').find('.ico-close').click(function() {
        	ui.box.close(callback);
            return false;
        });
		
	},
	
	setcontent:function(content){
		$('#layer-content').html(content);
	},
	
	close:function(fn){
		this.inited = false;
		$('#ui-fs .ui-fs-all .ui-fs-allinner div.list').find("a").die("click");
		$('.talkPop').remove();
		$('#tsbox').remove();
        jQuery('.boxy-modal-blackout').remove();
        if("undefined" != typeof(fn)){
        	eval(fn);	//edit by yangjs
        }
	},
	
    alert:function(data,title,callback){
		this.init(title,callback);
		this.setcontent('<div class="question">'+data+'</div>');
		this.center();
	},
	
	show:function(content,title,callback){
		this.init(title,callback);
		this.setcontent(content);
		this.center();
	},
	//requreUrl 请求地址
	//title 弹窗标题
	//callback 窗口关闭后的回调事件
	//requestData 请求附带的参数
	//type ajax请求协议 默认为GET
	//edit by yangjs
	
	load:function(requestUrl,title,callback,async,requestData,type){
	   
//	   if("undefined" != typeof(_UID_)){ //需要才判断
//		   //增加判断未登录时的登录操作,可能有些地方需要排除.允许弹窗.
//		   if(_UID_<=0 && option.title!='快速登录'){
//				option.title = '快速登录';
//				data = U('home/Public/quick_login');
//		   }
//	   }
	   if(  "undefined" == typeof(async) ) async = true;
	   this.init(title,callback);
	   if(  "undefined" != typeof(type) ){
		   var ajaxType = type;
	   }else{
		   var ajaxType = "GET";
	   }
       this.setcontent('<div style="width:150px;height:70px;text-align:center"><div class="load">&nbsp;</div></div>');
       this.center();
       
       var obj = this;
       
       if("undefined" == requestData){
    	   var requestData = {};
       }
       
       jQuery.ajax({url:requestUrl,
    	   	  type:ajaxType,
    	   	  data:requestData,
    	   	  cache:false,
    	   	  dataType:'html',
    	   	  success:function(html){
    	   		  obj.setcontent(html);
    	   		  obj.center();
       		}
       	});
       
	},	
	
	
    _viewport: function() {
        var d = document.documentElement, b = document.body, w = window;
        return jQuery.extend(
            jQuery.browser.msie ?
                { left: b.scrollLeft || d.scrollLeft, top: b.scrollTop || d.scrollTop } :
                { left: w.pageXOffset, top: w.pageYOffset },
            !ui.box._u(w.innerWidth) ?
                { width: w.innerWidth, height: w.innerHeight } :
                (!ui.box._u(d) && !ui.box._u(d.clientWidth) && d.clientWidth != 0 ?
                    { width: d.clientWidth, height: d.clientHeight } :
                    { width: b.clientWidth, height: b.clientHeight }) );
    },	
    
    _u: function() {
        for (var i = 0; i < arguments.length; i++)
            if (typeof arguments[i] != 'undefined') return false;
        return true;
    },
	
	 _cssForOverlay: function() {
        if (ui.box.IE6) {
            return ui.box._viewport();
        } else {
            return {width: '100%', height: jQuery(document).height()};
        }
    },
    
		center: function(axis) {
    	    var v = ui.box._viewport();
    	    var o =  [v.left, v.top];
    	    if (!axis || axis == 'x') this.centerAt(o[0] + v.width / 2 , null);
    	    if (!axis || axis == 'y') this.centerAt(null, o[1] + v.height / 2);
    	    return this;
    	},
    	
        
        moveToX: function(x) {
        	
            if (typeof x == 'number') $('#tsbox').css({left: x});
            else this.centerX();
            return this;
        },
        
        // Move this dialog (y-coord only)
        moveToY: function(y) {
            if (typeof y == 'number') $('#tsbox').css({top: y});
            else this.centerY();
            return this;
        },      
        centerAt: function(x, y) {
            var s = this.getSize();
            //alert(s);
            if (typeof x == 'number') this.moveToX(x - s[0]/2 );
            if (typeof y == 'number') this.moveToY(y - s[1]/2 );
            return this;
        },
        
        centerAtX: function(x) {
            return this.centerAt(x, null);
        },
        
        centerAtY: function(y) {
            return this.centerAt(null, y);
        },
        
        getSize: function() {
            return [$('#tsbox').width(), $('#tsbox').height()];
        },
        
        getContent: function() {
            return $('#tsbox').find('.boxy-content');
        },
        
        getPosition: function() {
            var b = $('#tsbox');
            return [b.offsetLeft, b.offsetTop];
        },        
        
        getContentSize: function() {
            var c = this.getContent();
            return [c.width(), c.height()];
        },
        
        _getBoundsForResize: function(width, height) {
            var csize = this.getContentSize();
            var delta = [width - csize[0], height - csize[1]];
            var p = this.getPosition();
            return [Math.max(p[0] - delta[0] / 2, 0),
                    Math.max(p[1] - delta[1] / 2, 0), width, height];
        }
	
});