function del_pic(num) {
	v=$("input[@name=pic"+num+"]").val();
	$("input[@name=pic"+num+"]").val('');
	$("#upload_iframe").css({height:46});
	$.get("publish.php",{ act: "del_pic", id: v } );
	show_pic(1);
}
function add_pic(file) {
	for(i=0;i<4;i++) {
		t=$("input[@name=pic"+i+"]").val();
		if (t=='') break;
	}
	$("input[@name=pic"+i+"]").val(file);
	show_pic(1);
}
function show_pic(act) {
	$('#pic_show').html('');
	for(i=3;i>0;i--) {
		j=i-1;
		if ($("input[@name=pic"+j+"]").val()=='' && $("input[@name=pic"+i+"]").val()!='') {
			$("input[@name=pic"+j+"]").val($("input[@name=pic"+i+"]").val());
			$("input[@name=pic"+i+"]").val('');
		}
	}
	var n1=0;
	for(i=0;i<4;i++) {
		t=$("input[@name=pic"+i+"]").val();
		if (t!='') {
			$('#pic_show').html($('#pic_show').html()+" <span style=\"float:left;margin-left:5px;\"><a href='javascript:del_pic("+i+")' title='去除图片'><img src='"+t+"' width=100 border=0 /><br>删除</a></span>");
			n1++;
		}
	}
	if (act==1) {
		n2=4-n1;
		$('#imgTips').show();
		if (n1==4) {
			$("#upload_iframe").css({height:0});
			$('#imgTips').html("您已经上传了4张图片了，达到上限了");
		} else $('#imgTips').html("您已经成功上传了 "+n1+" 张图片，还可以上传 "+n2+"张");
	}
}

$(document).ready(function(){
	$("#is_recommend").change(function(){
		if($("select[name='is_recommend'] option:selected").val()== 0) {
			$("#rec_time").attr({readonly:"readonly", value:""});
			$("#rec_warning").html('');
		}
		else $("#rec_time").removeAttr("readonly");
	});
	
	$("#top_type").change(function(){
		if($("select[name='top_type'] option:selected").val()== 0) {
			$("#top_time").attr({readonly:"readonly", value:""});
			$("#top_warning").html('');
		}
		else $("#top_time").removeAttr("readonly");
	});

	$("#is_head_line").change(function(){
		if($("select[name='is_head_line'] option:selected").val()== 0) {
			$("#head_line_time").attr({readonly:"readonly", value:""});
			$("#head_line_warning").html('');
		}
		else $("#head_line_time").removeAttr("readonly");
	});
	
	$("input[name='rec_time']").keyup(function(){
			var pattern = /^[1-9][0-9]*$/;
			if(!pattern.test($(this).val())){
				$("#rec_warning").html('<span style="color:red">请输入正确的格式</span>');
				return false;
			}
			var data = check_total_price();
			$("#rec_warning").html(data);
			//$.get('user.php?act=check_price',{type:'info', service:'rec', exp:$("#rec_time").val()}, function(data){
			//	$("#rec_warning").html(data);
			//});
	});
	$("input[name='top_time']").keyup(function(){
		var pattern = /^[1-9][0-9]*$/;
		if(!pattern.test($(this).val())){
			$("#top_warning").html('<span style="color:red">请输入正确的格式</span>');
			return false;
		}
		var service_type = $("#top_type").val();
		if(service_type == 1){
			top_service = 'top1';
		} else {
			top_service = 'top2';
		}
		var data = check_total_price(top_service);
		$("#top_warning").html(data);
		//$.get('user.php?act=check_price', {type:'info', service:top_service, exp:$("#top_time").val()}, function(data){
		//	$("#top_warning").html(data);
		//});
	});
	$("input[name='head_line_time']").keyup(function(){
		var pattern = /^[1-9][0-9]*$/;
		if(!pattern.test($(this).val())){
			$("#head_line_warning").html('<span style="color:red">请输入正确的格式</span>');
			return false;
		}
		var data = check_total_price();
		$("#head_line_warning").html(data);
		//$.get('user.php?act=check_price',{type:'info', service:'rec', exp:$("#head_line_time").val()}, function(data){
		//	$("#head_line_warning").html(data);
		//});
	});
});

function check_total_price(type){
	var total_money = $("#total").val();
	var service = $("#service_arr").val();
	var service_price = service.split(",");
	var rec_time = !($("#rec_time").val() == '') ? $("#rec_time").val() : 0;
	var top_time = !($("#top_time").val() == '') ? $("#top_time").val() : 0;
	var head_line_time = !($("#head_line_time").val() == '') ? $("#head_ling_time").val() : 0;
	var rec_price =service_price[2];
	if(type == 'top1')
		var top_price = service_price[1];
	else
		var top_price = service_price[0];
	var head_line_price = service_price[3];
	if(total_money >= rec_price * rec_time + top_price * top_time + head_line_price * head_line_time) {
		return '<span style="color:blue">您的金币还很充裕!</span>';
	} else {
		return '<span style="color:red">您的金币不足啦!</span>';
	}
}

