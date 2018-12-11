$(document).ready(function (e) {

    //菜单高亮显示
	light_nav();

	//选择全部
    $("#selectall").on("click", function () {
       $("#selectitem input:checkbox").prop("checked", true);
        
    })
    
    //反选
    $("#invselect").on("click", function () {
       $("#selectitem input:checkbox").each(function() {
    	   if($(this).prop("checked")){
				$(this).prop("checked",false);
			}else{
				$(this).prop("checked",true);
			}
       })		
    })
    
    //勾选方式选择全部
    $("#checkall").on("click", function () {
    	if($(this).prop("checked")){
    		$(".checkitem:enabled").prop("checked", true);
    	}else{
    		$(".checkitem").prop("checked", false);
    	}
        
    })
    
    var i=0;
    $('.menu-ico').click(function(){
    	if($(window).width()>750){
			if(i==0){//隐藏
				$(".layui-side").animate({width:'toggle'});
				$(".layui-body").animate({left:'0px'});
				$(".layui-footer").animate({left:'0px'});
				i=1
			}else{//显示
				$(".layui-side").animate({width:'toggle'});
				$(".layui-body").animate({left:'200px'});
				$(".layui-footer").animate({left:'200px'});
				i=0
			}		
	    }else{
	    	$(".layui-side").animate({width:'toggle'});
	    }
	});
    
    
    $(window).resize(function(){
    	if($(window).width()>750){ //大屏幕根据情况判断
	    	if(i==0){ //等于0，说明处于显示状态，全屏以后保持显示出来
				$(".layui-layout-admin .layui-side").show();
			}else{ //等于1，说明处于隐藏状态，全屏以后保持隐藏出来
				$(".layui-layout-admin .layui-side").hide();
			}
    	}

    	if($(window).width()<750){//小屏幕，直接隐藏
    		$(".layui-layout-admin .layui-side").hide();
    	}
    })

})

//对菜单进行高亮显示
function light_nav(){
	 
	//二级菜单标记当前栏目
    var url = $('#url').data('url').toLowerCase();
    var controller = $('#controller').data('controller').toLowerCase();
    var mcode = $('#mcode').data('mcode');
    var aobj= $('#nav .nav-item').find('a');
    var flag = false;

   
    //第一种情况，url完全一致
    aobj.each(function (index, element) {
        var aUrl = $(element).attr('href').toLowerCase();
        if (url==aUrl) {
            $(element).parent("dd").addClass("layui-this");
            flag = true;
        }
		if(flag) return false;
    });

    url = url.replace('.html','');
    
    //第二种情况，菜单的子页面，如翻页
    if(!flag){
    	aobj.each(function (index, element) {
            var aUrl = $(element).attr('href').toLowerCase();
            aUrl = aUrl.replace('.html','');
            if (url.indexOf(aUrl)>-1) {
            	$(element).parent("dd").addClass("layui-this");
                flag = true;
            }
            if(flag) return false;
        });
    }
   
  //第三种情况，只匹配到模型，如模型栏目内容的修改操作页面
    if(!flag){
    	aobj.each(function (index, element) {
            var aUrl = $(element).attr("href").toLowerCase();
            if (mcode && aUrl.indexOf('/mcode/'+mcode)>-1) {
            	$(element).parent("dd").addClass("layui-this");
                flag = true;
            }
            if(flag) return false;
        });
    }
	
	//第四种情况，只匹配到控制器，如增、改的操作页面
    if(!flag){
    	aobj.each(function (index, element) {
            var aUrl = $(element).attr("href").toLowerCase();
            if (controller!='index' && aUrl.indexOf('/'+controller+'/')>-1) {
            	$(element).parent("dd").addClass("layui-this");
                flag = true;
            }
            if(flag) return false;
        });
    }
    
	//默认高亮
	if(!flag){
		$('#nav').find('.nav-item').eq(2).addClass('layui-nav-itemed');
	}
}
