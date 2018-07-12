// ThinkSNS ui.box

jQuery.extend(ui, {
	box:function(element, options){
	   
	    
	}
});


jQuery.extend(ui.box, {
    
    WRAPPER:    "<table cellspacing='0' id='tsbox' cellpadding='0' border='0' style='display:none;z-Index:1000000;' class='boxy-wrapper'>" +
                "<tr><td class='boxy-top-left'></td><td class='boxy-top'></td><td class='boxy-top-right'></td></tr>" +
                "<tr><td class='boxy-left'></td><td class='boxy-inner'>"+
                "<div class='hd'>jj</div>"+
                "<div class='boxy-content' id='tsbox_content'></div>"+
                "</td><td class='boxy-right'></td></tr>" +
                "<tr><td class='boxy-bottom-left'></td><td class='boxy-bottom'></td><td class='boxy-bottom-right'></td></tr>" +
                "</table>",
              
    inited:             false,
    IE6:                (jQuery.browser.msie && jQuery.browser.version < 7),
    init:function(option){
		if( !this.inited ){
			$('body').prepend( this.WRAPPER );
		}

		if(option.title)
			$('#tsbox').find('.title-bar').html("<h2>"+option.title+"</h2><a href='#' class='close'></a>");
		
		$('#tsbox').show();
		//$('#tsbox').css({
		//	top:	pageScroll[1]	+	(this.getPageHeight() / 10),
		//	left:	pageScroll[0]	+	document.body.clientWidth/2 - $('#tsbox').width()/2
		//}).show();
		jQuery('<div id="boxy-modal-blackout" class="boxy-modal-blackout"><iframe style="position:absolute;_filter:alpha(opacity=0);opacity=0;z-index:-1;width:100%;height:100%;top:0;left:0;scrolling:no;" frameborder="0" src="about:blank"></iframe></div>')
        .css(jQuery.extend(ui.box._cssForOverlay(), {
            zIndex: 999999, opacity: 0.3
        })).appendTo(document.body);
		
		
		$('#tsbox').stop().css({width: '', height: ''});
		jQuery(document.body).bind('keypress.tsbox', function(event) {
			var key = event.keyCode?event.keyCode:event.which?event.which:event.charCode;
            if (key == 27) {
            	jQuery(document.body).unbind('keypress.tsbox');
            	ui.box.close(option.callback);
                return false;
            }
        });

		$('#tsbox').find('.close').click(function() {
            ui.box.close(option.callback);
            return false;
        });
		
	},
	
	setcontent:function(content){
		$('#tsbox_content').html(content);
		
	},
	
	close:function(fn){
		$('#ui-fs .ui-fs-all .ui-fs-allinner div.list').find("a").die("click");
		$('.talkPop').remove();
		$('#tsbox').remove();
        jQuery('.boxy-modal-blackout').remove();
        if(fn)
            fn();
	},
	
    alert:function(data,option){
		this.init(option);
		this.setcontent('<div class="question">'+data+'</div>');
		this.center();
	},
	
	show:function(content,option){
		this.init(option);
		ui.box.setcontent(content);
		this.center();
	},
	
	load:function(data,option,type,requestData){
	   
	   if("undefined" != typeof(_MID_)){ //需要才判断
		   //增加判断未登录时的登录操作,可能有些地方需要排除.允许弹窗.
		   // if(_MID_<=0 && option.title!='快速登录'){
           if(_MID_<=0){
				option.title = '快速登录';
				data = U('home/Public/quick_login');
		   }
	   }
	   this.init(option);
	   var ajaxType = type || "GET";
       var ajax = {
               url: data, type: type,data:requestData, dataType: 'html', cache: false, success: function(html) {
                   ui.box.setcontent(html);
                   ui.box.center();
               }
           };
       ui.box.setcontent('<div style="width:150px;height:70px;text-align:center"><div class="load">&nbsp;</div></div>');
       this.center();
       jQuery.ajax(ajax);
       
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
            // return ui.box._viewport();
            return {width: '100%', height: jQuery(document).height() - 5};
        } else {
            return {width: '100%', height: jQuery(document).height() - 5};
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