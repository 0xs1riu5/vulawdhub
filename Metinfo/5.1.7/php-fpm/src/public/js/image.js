//本js依赖jquery
//	www.zeroplace.cn
//	高灰
//	2011/08/09
function ResizeImage_metshow(obj){
	// img=new Image();
	if(obj.length>1){
		alert('select error');
		return ;
	}
	
	var width=obj.attr('oldwidth');
	var height=obj.attr('oldheight');
	
	if(width==undefined){
		width=obj.attr('width');
		height=obj.attr('height');
	}
	var pos=obj.attr('pos');
	
	if(pos==undefined){
		pos=0;
	}
	
	var src=obj.attr('src');
	var img=new Image();
	img.src=src;
	var oldwidth=img.width;
	var oldheight=img.height;
	
	var pw=width/oldwidth;
	var ph=height/oldheight;
	var left=0;
	var top=0;
	
	if(pw<ph){
		img.width=width;
		img.height=oldheight*pw;
		top=parseInt((height-img.height)/2);
	}else{
		img.height=height;
		img.width=oldwidth*ph;
		left=parseInt((width-img.width)/2);
	}
	obj.attr('width',img.width);
	obj.attr('height',img.height);
	obj.attr('oldwidth',width);
	obj.attr('oldheight',height);
	obj.attr('src',img.src);
	obj.css('position','absolute');
	obj.css('left',left);
	obj.attr('data-left',left);
	obj.attr('data-top',top);
	if(pos==1)
	{
		obj.css('bottom',0);
	}
	if(pos==0){
		obj.css('top',top);
	}
	if(pos==-1){
		obj.css('top',0)
	}
	
	//包裹
	if(obj.parent("div[autosize_metshow='yes']").length==0){
		var div=obj.parent();
		$(div).css('width',width);
		$(div).css('height',height);
		$(div).css('position','relative');
		$(div).css('border','none');
		$(div).css('display','block');
	}
	delete img;
}

jQuery.fn.extend({
	autosize_metshow:function(){
		this.each(function(){
			ResizeImage_metshow($(this));	
		})
	}
});

$(function(){
		$("img[autosize_metshow='yes']").autosize_metshow('');
	});