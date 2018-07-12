//追加核心图片展示插件

var imgScroll  = function(id){
	this.id = id;
	this.init();
}; //图片滚动
var imgTab	   = function(){}; //图片直接切换	
var imgAnimate = function(){}; //图片切换（带移动）

imgScroll.prototype={
	init:function(){
	var id = this.id
	var sWidth = $("#"+id).width(); 
		var len = $("#"+id+" ul li").length; 
		var index = 0;
		var picTimer;

		$("#"+id).find('img').css({'width':sWidth+'px'});

		var btn = "<div class='btn'>";
		for(var i=0; i < len; i++) {
			btn += "<span></span>";
		}
		btn += "</div><div class='move'><div class='preNext pre'></div><div class='preNext next'></div></div>";
		
		$("#"+id).append(btn);
		$('#'+id+' .move').css({'width':sWidth+'px'});
		
		var t = $("#"+id).height()/2 - $('#'+id+' .preNext').height();
		
		$('#'+id+' .preNext').css({'top':t+'px'});

		$("#"+id+" .btnBg").css("opacity",0.5);

		$("#"+id+" .btn span").css("opacity",0.4).mouseenter(function() {
			index = $("#"+id+" .btn span").index(this);
			showPics(index);
		}).eq(0).trigger("mouseenter");

		$("#"+id+"  .preNext").css("opacity",0.8).hover(function() {
			$(this).stop(true,false).animate({"opacity":"0.5"},300);
		},function() {
			$(this).stop(true,false).animate({"opacity":"0.8"},300);
		});

		$("#"+id+"  .pre").click(function() {
			index -= 1;
			if(index == -1) {
				index = len - 1;
			}
			showPics(index);
		});

		$("#"+id+"  .next").click(function() {
			index += 1;
			if(index == len) {index = 0;}
			showPics(index);
		});

		$("#"+id+"  ul").css("width",sWidth * (len));
		
		$("#"+id).hover(function() {
			clearInterval(picTimer);
		},function() {
			picTimer = setInterval(function() {
				showPics(index);
				index++;
				if(index == len) {index = 0;}
			},4000); //´Ë4000´ú±í×Ô¶¯²¥·ÅµÄ¼ä¸ô£¬µ¥Î»£ººÁÃë
		}).trigger("mouseleave");
		
		function showPics(index) { 
			var nowLeft = -index*sWidth; 
			$("#"+id+" ul").stop(true,false).animate({"left":nowLeft},300); 
			$("#"+id+" .btn span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300); //
		}
	}	
};
core.imgshow ={
		//给工厂调用的接口
		_init:function(attrs){
			if(attrs.length == 3){

			}else{
				return false;	//只是未了加载文件
			}
		},
		loginImg:function(t){
			//登录页面图片切换
			var sWidth = $(".slide-con").width(); //获取焦点图的宽度（显示面积）
			var len = $(".slide-con ul.slide li").length; //获取焦点图个数
			var index = 0;
			var picTimer;
			if("undefined" == typeof(t)){
				t = 4;
			}
			
			$("#slide-title ul li").css("opacity",0.4).mouseenter(function() {
				index = $("#slide-title li").index(this);
				showPics(index);
			}).eq(0).trigger("mouseenter");

			//本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
			$(".slide-con ul.slide").css("width",sWidth * (len));
			
		    var setPicTimer = function(){
		        picTimer = setInterval(function() {
		            showPics(index);
		            index++;
		            if(index == len) {index = 0;}
		        },t*1000); //此4000代表自动播放的间隔，单位：毫秒
		    };

			//鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
			$("#focus").hover(function() {
				clearInterval(picTimer);
		        picTimer = null;
			},function() {
		        setPicTimer();
		    });
		    
			//显示图片函数，根据接收的index值显示相应的内容
			function showPics(index) {
				var nowLeft = -index*sWidth;
				$(".slide-con ul.slide").stop(true,false).animate({"left":nowLeft},300); 
				$("#slide-title li").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300);
		        switch(index) {
		            case 0:
		                $('#focus').addClass('bg-blue');
		                $('#focus').removeClass('bg-black');
		                break;
		            case 1:
		                $('#focus').addClass('bg-black');
		                $('#focus').removeClass('bg-blue');
		                break;
		        }
			}

		    setPicTimer();
		},
		scrollImg:function(id){
			var t = new imgScroll(id);
		}
}		