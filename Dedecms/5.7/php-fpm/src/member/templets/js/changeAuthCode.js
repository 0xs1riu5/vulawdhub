<!--
function changeAuthCode() {
	var num = 	new Date().getTime();
	var rand = Math.round(Math.random() * 10000);
	num = num + rand;
	var leftpos = $("#vdcode").position().left;
	var toppos = $("#vdcode").position().top - 42;
	$('#ver_code').css('left', leftpos+'px');
	$('#ver_code').css('top', toppos+'px');
	$('#ver_code').css('visibility','visible');
	if ($("#vdimgck")[0]) {
		$("#vdimgck")[0].src = "../include/vdimgck.php?tag=" + num;
	}
	return false;	
}

function hideVc()
{
	$('#ver_code').css('visibility','hidden');
}


$(document).ready(function(){ 
	$("#vdcode").focus(function(){
	  var leftpos = $("#vdcode").position().left;
	  var toppos = $("#vdcode").position().top - 42;
	  $('#ver_code').css('left', leftpos+'px');
	  $('#ver_code').css('top', toppos+'px');
	  $('#ver_code').css('visibility','visible');
	});
	$("input[type='password']").click(function(){
	  hideVc()
	});
	$("#txtUsername").click(function(){
	  hideVc()
	});
	$("input[type='radio']").focus(function(){
	  hideVc()
	});
})

-->