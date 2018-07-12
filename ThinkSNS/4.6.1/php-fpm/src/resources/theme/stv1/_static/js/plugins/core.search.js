core.search = {};
core.search._init = function(){
	this.searchKey = '';
	return true;
}
core.search.hideMenu=function(){
	if($('#search_menu').attr('ison') =="yes"){
		return false;
	}else{
		$('#search_menu').hide();
	}
}
core.search.dohide = function(){
	$('#search_menu').hide();
	$('#search_menu').attr('ison','no');
}
core.search.doshow = function(){
	$('#search_menu').show();
	$('#search_menu').attr('ison','yes');
}
core.search.showCurMenu = function(curArgs){
	$('#search_menu').find('li').each(function(){
		if($(this).attr('a') == curArgs.a && $(this).attr('t') == curArgs.t){
			$('#search_cur_menu').html($(this).attr('typename')+'<i class="ico-more"></i>');
		}
	})
}
core.search.doShowCurMenu = function(obj){
	$('#search_cur_menu').html($(obj).attr('typename')+'<i class="ico-more"></i>');
	$('#search_a').val($(obj).attr('a'));
	$('#search_t').val($(obj).attr('t'));
	this.dohide();
}
//初始化下拉项数据
core.search.searchInit = function(obj){
	var _this = this;
	if("undefined" == typeof(this.listdata)){
		$.post(U('public/Search/getSearchList'),{},function(data){
			_this.listdata = data;
		},'json');
	}

	$(obj).unbind('keyup');
	$(obj).keyup(function(){
		core.search.displayList(obj);
	});
}
core.search.displayList = function(obj){
	this.searchKey = stripscript(obj.value.replace(/(^\s*)|(\s*$)/g,""));
	if(getLength(this.searchKey)>0){
		var html = '<div class="search-box" style="margin:0px 1px 0 -1px;" id="search-box"><dd id="s_1" class="current" onclick="core.search.dosearch(\'public\',2);" onmouseover="$(this).addClass(\'current\');" onmouseout="$(this).removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关分享&raquo;</dd>'
					+'<dd id="s_2" onclick="core.search.dosearch(\'public\',1);" onmouseover="$(this).addClass(\'current\');" onmouseout="$(this).removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关人&raquo;</dd>'
					+'<dd id="s_3" onclick="core.search.dosearch(\'public\',3);" onmouseover="$(this).addClass(\'current\');" onmouseout="$(this).removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关微吧&raquo;</dd>'
					// +'<dd id="s_4" onclick="core.search.dosearch(\'public\',4);" onmouseover="$(this).addClass(\'current\');" onmouseout="$(this).removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关知识&raquo;</dd>'
					+'<dd id="s_5" onclick="core.search.dosearch(\'public\',5);" onmouseover="$(this).addClass(\'current\');" onmouseout="$(this).removeClass(\'current\');">搜“<span>'+this.searchKey+'</span>”相关帖子&raquo;</dd>'
					+'</div>';
				//+'<dd class="more"><a href="#"" onclick="core.search.dosearch();">点击查看更多结果&raquo;</a></dd>';
	}else{
		var html = '';
	}
	$(obj).parent().nextAll().remove();
	$(html).insertAfter($(obj).parent());
}
//查找数据
core.search.dosearch = function(app,type){
	var url = U('public/Search/index')+'&k='+encodeURIComponent(this.searchKey);
	if("undefined" != typeof(app)){
		url+='&a='+app;
	}
	if("undefined" != typeof(type)){
		url+='&t='+type;	
	}
	location.href = url;
}