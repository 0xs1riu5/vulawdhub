  $(document).ready(function(){ 
	  //表格奇偶行不同样式	
	  $(".list tbody tr:even").addClass("row0");//偶行
	  $(".list tbody tr:odd").addClass("row1");//奇行
  
	  $(".submit tbody tr:even").addClass("row0");//偶行
	  $(".submit tbody tr:odd").addClass("row1");//奇行
	  
	   //修正IE6下hover Bug
	  if ( $.browser.msie ){
	  	if($.browser.version == '6.0'){
	  		$("#menuBody li").hover(
	  			function(){
	  				//进行判断,是否存在
	  				//先设置所有.act为隐藏
	  				$(".act").each(function(){this.style.visibility='hidden'});
	  				if($(this).find(".act").length != 0)
	  				{
	  					$(this).children(".act").css("visibility","visible");
	  				} else {
	  					$(".act").css("visibility","hidden");
	  				}
	  			}
	  		)
	  	}
	  }	   

  })