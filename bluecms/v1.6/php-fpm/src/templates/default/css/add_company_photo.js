function del_pic(num) {
	v=$("input[@name=pic"+num+"]").val();
	$("input[@name=pic"+num+"]").val('');
	$("#upload_iframe").css({height:46});
	$.get("user.php",{ act: "del_pic", id: v } );
	show_pic(1);
}
function add_pic(file) {
	for(i=0;i<10;i++) {
		t=$("input[@name=pic"+i+"]").val();
		if (t=='') break;
	}
	$("input[@name=pic"+i+"]").val(file);
	show_pic(1);
}
function show_pic(act) {
	$('#pic_show').html('');
	for(i=9;i>0;i--) {
		j=i-1;
		if ($("input[@name=pic"+j+"]").val()=='' && $("input[@name=pic"+i+"]").val()!='') {
			$("input[@name=pic"+j+"]").val($("input[@name=pic"+i+"]").val());
			$("input[@name=pic"+i+"]").val('');
		}
	}
	var n1=0;
	for(i=0;i<10;i++) {
		t=$("input[@name=pic"+i+"]").val();
		if (t!='') {
			$('#pic_show').html($('#pic_show').html()+" <span><a href='javascript:del_pic("+i+")' title='去除图片'><img src='"+t+"' width=100 border=0 /><br>删除</a></span>");
			n1++;
		}
	}
	if (act==1) {
		n2=10-n1;
		$('#imgTips').show();
		if (n1==10) {
			$("#upload_iframe").css({height:0});
			$('#imgTips').html("您已经上传了10张图片了，达到上限了");
		} else $('#imgTips').html("您已经成功上传了 "+n1+" 张图片，还可以上传 "+n2+"张");
	}
}
