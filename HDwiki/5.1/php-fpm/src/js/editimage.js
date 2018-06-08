var CANVAS_WIDTH = 250; //画布的宽
var CANVAS_HEIGHT = 250; //画布的高
var ICON_WIDTH = 90; //截取框的宽
var ICON_HEIGHT = 90; //截取框的高
var LEFT_EDGE = (CANVAS_WIDTH - ICON_WIDTH) / 2;
var TOP_EDGE = (CANVAS_HEIGHT - ICON_HEIGHT) / 2;

var scaleFactor;
var factor;
var minFactor;
var oldWidth;
var oldHeight;
$(document).ready(function() {
	run(image_run_width,image_run_height);
	$(".child").draggable({
		cursor: "move",
		containment: $("#bar"),
		drag: function(e, ui) {
			var left = parseInt($(this).css("left"));
			scaleFactor = Math.pow(factor, (left / 100 - 1));
			if (scaleFactor < minFactor) {
				scaleFactor = minFactor;
			}
			if (scaleFactor > factor) {
				scaleFactor = factor;
			}
			//以下代码同初始化过程，因为用到局部变量所以没有
			var iconElement = $("#ImageIcon");
			var imagedrag = $("#ImageDrag");

			var image = new Image();
			image.src = iconElement.attr("src");
			var realWidth = image.width;
			var realHeight = image.height;
			image = null;

			//图片实际尺寸
			var currentWidth = Math.round(scaleFactor * realWidth);
			var currentHeight = Math.round(scaleFactor * realHeight);

			//图片相对CANVAS的初始位置
			var originLeft = parseInt(iconElement.css("left"));
			var originTop = parseInt(iconElement.css("top"));

			originLeft -= Math.round((currentWidth - oldWidth) / 2);
			originTop -= Math.round((currentHeight - oldHeight) / 2);
			dragleft = originLeft - LEFT_EDGE;
			dragtop = originTop - TOP_EDGE;

			//图片当前尺寸和位置
			iconElement.css({
				width: currentWidth + "px",
				height: currentHeight + "px",
				left: originLeft + "px",
				top: originTop + "px"
			});
			imagedrag.css({
				width: currentWidth + "px",
				height: currentHeight + "px",
				left: dragleft + "px",
				top: dragtop + "px"
			});
			
			valuewrite(originLeft,originTop,currentWidth,currentHeight);
			valuewrite(dragleft,dragtop,currentWidth,currentHeight);
			oldWidth = currentWidth;
			oldHeight = currentHeight;
		}
	});
	var	SilderSetValue = function(i) {
		var left = parseInt($(".child").css("left"));
		left += i;

		if (left < 0) {
			left = 0;
		}
		if (left > 200) {
			left = 200;
		}

		scaleFactor = Math.pow(factor, (left / 100 - 1));
		if (scaleFactor < minFactor) {
			scaleFactor = minFactor;
		}
		if (scaleFactor > factor) {
			scaleFactor = factor;
		}
		var iconElement = $("#ImageIcon");
		var imagedrag = $("#ImageDrag");

		var image = new Image();
		image.src = iconElement.attr("src");
		var realWidth = image.width;
		var realHeight = image.height;
		image = null;

		//图片实际尺寸
		var currentWidth = Math.round(scaleFactor * realWidth);
		var currentHeight = Math.round(scaleFactor * realHeight);

		//图片相对CANVAS的初始位置
		var originLeft = parseInt(iconElement.css("left"));
		var originTop = parseInt(iconElement.css("top"));

		originLeft -= Math.round((currentWidth - oldWidth) / 2);
		originTop -= Math.round((currentHeight - oldHeight) / 2);
		dragleft = originLeft - LEFT_EDGE;
		dragtop = originTop - TOP_EDGE;

		//图片当前尺寸和位置
		$(".child").css("left", left + "px");
		iconElement.css({
			width: currentWidth + "px",
			height: currentHeight + "px",
			left: originLeft + "px",
			top: originTop + "px"
		});
		imagedrag.css({
			width: currentWidth + "px",
			height: currentHeight + "px",
			left: dragleft + "px",
			top: dragtop + "px"
		});

		valuewrite(originLeft,originTop,currentWidth,currentHeight);
		valuewrite(dragleft,dragtop,currentWidth,currentHeight);
		oldWidth = currentWidth;
		oldHeight = currentHeight;
	}
	//点击加减号
	$("#moresmall").click(function() {
		SilderSetValue(-5);
	});
	$("#morebig").click(function() {
		SilderSetValue(5);
	});
});


function run(i_width,i_height){
	$("#Canvas").css({
		width:CANVAS_WIDTH+ "px",
		height:CANVAS_HEIGHT+ "px"
	});
	$("#ImageDragContainer").css({
		width:ICON_WIDTH+ "px",
		height:ICON_HEIGHT+ "px",
		top:TOP_EDGE+1+ "px",
		left:LEFT_EDGE-1+ "px"
	});
	$("#IconContainer").css({
		top:"-"+ICON_HEIGHT+"px"
	});
	obj_imagedrag = $("#ImageDrag");
	obj_iconElement = $("#ImageIcon");
	var image = new Image();
	image.src = obj_iconElement.attr("src");
	if(image.width==0 && image.height==0){
		var realWidth = i_width;
		var realHeight = i_height;
	}else{
		var realWidth = image.width;
		var realHeight = image.height;
	}
	image=null;
	minFactor = Math.min(ICON_WIDTH / realWidth,ICON_HEIGHT/realHeight);
	if (ICON_WIDTH > realWidth && ICON_HEIGHT > realHeight) {
		minFactor = 1;
	}
	factor = minFactor > 0.25 ? 8.0 : 4.0 / Math.sqrt(minFactor);

	scaleFactor = 1;
	if (realWidth > CANVAS_WIDTH && realHeight > CANVAS_HEIGHT) {
		if (realWidth / CANVAS_WIDTH > realHeight / CANVAS_HEIGHT) {
			scaleFactor = CANVAS_HEIGHT / realHeight;
		}
		else {
			scaleFactor = CANVAS_WIDTH / realWidth;
		}
	}
	$(".child").css("left", 100 * (Math.log(scaleFactor * factor) / Math.log(factor)) + "px");

	var currentWidth = Math.round(scaleFactor * realWidth);
	var currentHeight = Math.round(scaleFactor * realHeight);
	var originLeft = Math.round((CANVAS_WIDTH - currentWidth) / 2) ;
	var originTop = Math.round((CANVAS_HEIGHT - currentHeight) / 2);

	//计算截取框中的图片的位置
	var dragleft = originLeft - LEFT_EDGE;
	var dragtop = originTop - TOP_EDGE;

	//设置图片当前尺寸和位置
	obj_imagedrag.css({
		width: currentWidth + "px",
		height: currentHeight + "px",
		left: dragleft + "px",
		top: dragtop + "px",
		border: '1px #fff solid'
	});

	obj_iconElement.css({
		width: currentWidth + "px",
		height: currentHeight + "px",
		left: originLeft + "px",
		top: originTop + "px"
	});
	
	oldWidth = currentWidth;
	oldHeight = currentHeight;
	valuewrite(dragleft,dragtop,oldWidth,oldHeight);


	$("#ImageDrag").draggable({
		cursor: 'move',
		drag: function(e, ui) {
			var self = $(this).data("draggable");
			var drop_img = $("#ImageIcon");
			var top = drop_img.css("top").replace(/px/, ""); //取出截取框到顶部的距离
			var left = drop_img.css("left").replace(/px/, ""); //取出截取框到左边的距离
			drop_img.css({
				left: (parseInt(self.position.left) + LEFT_EDGE) + "px",
				top: (parseInt(self.position.top) + TOP_EDGE) + "px"
			});	//同时移动
			valuewrite(parseInt(self.position.left),parseInt(self.position.top),oldWidth,oldHeight);
		}
	});
	//设置图片可拖拽，并且拖拽一张图片时，同时移动另外一张图片
	$("#ImageIcon").draggable({
		cursor: 'move',
		drag: function(e, ui) {
			var self = $(this).data("draggable");
			var drop_img = $("#ImageDrag");
			var top = drop_img.css("top").replace(/px/,""); //取出截取框到顶部的距离
			var left = drop_img.css("left").replace(/px/,""); //取出截取框到左边的距离
			drop_img.css({
				left: (parseInt(self.position.left) - LEFT_EDGE) + "px",
				top: (parseInt(self.position.top) - TOP_EDGE) + "px"
			}); //同时移动
			valuewrite(parseInt(self.position.left) - LEFT_EDGE,parseInt(self.position.top) - TOP_EDGE,oldWidth,oldHeight);
		}
	});
}


function valuewrite(left,top,currentWidth,currentHeight){

	var img_x=left>0 && left<ICON_WIDTH?0:0-left;
	var dst_x=left<=0 || left>=ICON_WIDTH?0:left;

	var img_y=top>0 && top<ICON_HEIGHT?0:0-top;
	var dst_y=top<=0 || top>=ICON_HEIGHT?0:top;

	var img_w='';
	var dst_w='';

	if(ICON_WIDTH>currentWidth){
		if(left>0 && left<ICON_WIDTH-currentWidth){
			img_w=currentWidth;
			dst_w=currentWidth;
		}else if(left>ICON_WIDTH || left<-currentWidth){
			img_w=0;
			dst_w=ICON_WIDTH;
		}else if(left>0 && left<ICON_WIDTH){
			img_w=ICON_WIDTH-left;

			dst_w=ICON_WIDTH-left;
		}else{
			img_w=currentWidth+left;

			dst_w=currentWidth+left;
		}
	}else{
		if(left<=0 && left>=0-(currentWidth-ICON_WIDTH)){
			img_w=ICON_WIDTH;
			dst_w=ICON_WIDTH;
		}else if(left>ICON_WIDTH || left<0-currentWidth){
			img_w=0;
			dst_w=ICON_WIDTH;
		}else if(left>0 && left<ICON_WIDTH){
			img_w=ICON_WIDTH-left;

			dst_w=ICON_WIDTH-left;
		}else{
			img_w=currentWidth+left;

			dst_w=currentWidth+left;
		}
	}

	var img_h='';
	var dst_h='';

	if(ICON_HEIGHT>currentHeight){
		if(top>0 && top<ICON_HEIGHT-currentHeight){
			img_h=currentHeight;
			dst_h=currentHeight;
		}else if(top>ICON_WIDTH || top<0-currentHeight){
			img_h=0;
			dst_h=ICON_HEIGHT;
		}else if(top>0 && top<ICON_HEIGHT){
			img_h=ICON_HEIGHT-top;

			dst_h=ICON_HEIGHT-top;
		}else{
			img_h=currentHeight+top;

			dst_h=currentHeight+top;
		}
	}else{
		if(top<= 0 && top>=0-(currentHeight-ICON_HEIGHT)){
			img_h=ICON_HEIGHT;
			dst_h=ICON_HEIGHT;
		}else if(top>ICON_WIDTH || top<0-currentHeight){
			img_h=0;
			dst_h=ICON_HEIGHT;
		}else if(top>0 && top<ICON_HEIGHT){
			img_h=ICON_HEIGHT-top;

			dst_h=ICON_HEIGHT-top;
		}else{
			img_h=currentHeight+top;

			dst_h=currentHeight+top;
		}
	}

	$("#left").val(left);
	$("#top").val(top);
	$("#f").val(scaleFactor);
	$("#width").val(currentWidth);
	$("#height").val(currentHeight);

	$("#img_x").val(img_x/scaleFactor);
	$("#img_y").val(img_y/scaleFactor);
	$("#img_w").val(img_w/scaleFactor);
	$("#img_h").val(img_h/scaleFactor);
	$("#dst_x").val(dst_x);
	$("#dst_y").val(dst_y);
	$("#dst_w").val(dst_w);
	$("#dst_h").val(dst_h);
}