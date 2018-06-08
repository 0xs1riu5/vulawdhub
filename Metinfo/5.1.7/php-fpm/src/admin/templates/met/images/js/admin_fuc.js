function controle(t){
	if(t==1){
		$("input[name='admin_pop']").attr("checked",false);
		$(".admin_poplistdiv").find('input').attr("checked",false);
		$("input[name='admin_pop1301']").parents('div.admin_poplistdiv').find("input").attr("checked",true);
		$("input[name='admin_pop1201']").parents('div.admin_poplistdiv').find("input").attr("checked",true);
		$("input[name='admin_op0']").attr("checked",true);
		$("input[name='admin_op1']").attr("checked",true);
		$("input[name='admin_op2']").attr("checked",true);
		$("input[name='admin_op3']").attr("checked",true);
		$("input[name='admin_issue']").attr("checked",false);
		$(".tradpoplist").find("input").attr("disabled","disabled");
	}
	if(t==2){
		$("input[name='admin_pop']").attr("checked",false);
		$(".admin_poplistdiv").find('input').attr("checked",false);
		$("input[name='admin_pop1301']").parents('div.admin_poplistdiv').find("input").attr("checked",true);
		$("input[name='admin_pop1201']").parents('div.admin_poplistdiv').find("input").attr("checked",true);
		$("input[name='admin_pop1401']").parents('div.admin_poplistdiv').find("input").attr("checked",true);
		$("input[name='admin_pop1001']").attr("checked",true);
		$("input[name='admin_op0']").attr("checked",true);
		$("input[name='admin_op1']").attr("checked",true);
		$("input[name='admin_op2']").attr("checked",true);
		$("input[name='admin_op3']").attr("checked",true);
		$("input[name='admin_issue']").attr("checked",false);
		$(".tradpoplist").find("input").attr("disabled","disabled");
	}
	if(t==3){
		$("input[name='admin_pop']").attr("checked",true);
		$(".admin_poplistdiv").find('input').attr("checked",true);
		$("input[name='admin_op0']").attr("checked",true);
		$("input[name='admin_op1']").attr("checked",true);
		$("input[name='admin_op2']").attr("checked",true);
		$("input[name='admin_op3']").attr("checked",true);
		$("input[name='admin_issue']").attr("checked",false);
		$(".tradpoplist").find("input").attr("disabled","disabled");
	}
	if(t==0){
		$(".tradpoplist").find("input").attr("disabled",false);
			if(apop_type!='' && apop_type!='metinfo'){
				$(".admin_poplistdiv").find("input").attr("checked",false);
				var apop_types=apop_type.split('-');
				for(var i=0;i<apop_types.length;i++){
					if(apop_types[i]!=''){
						$("input[name='admin_pop"+apop_types[i]+"']").attr("checked",true);
					}
				}
			}else{
				$("input[name='admin_pop']").attr("checked",true);
				$(".admin_poplistdiv").find("input").attr("checked",true);
			}
	}
}

var a1301=$("input[name='admin_pop1301']"),apop=$("input[name='admin_pop']");
function adpopcnk(d,y){
	if(d.size()>0){
		d.attr('checked')?y.find("input").not("input:disabled").attr("checked",true):y.find("input").not("input:disabled").attr("checked",false);
	}
}
$("input[name='langok']").click(function(){
	if($(this).attr('checked')){
		adpopcnk($(this),$("#adpoplangok"));
		$(".adpopcnkdiv").find("input").attr("checked",true);
		suoyoulist();
		if($("input[name='admin_group']:checked").val()==0){
			$(".adpopcnkdiv").find("input").attr("disabled",false);
		}
	}
});
$("input[name='admin_op0']").click(function(){
	adpopcnk($(this),$("#adminrate"));
});
a1301.click(function(){
	adpopcnk($(this),$(".adpopcnkdiv"));
});
apop.click(function(){
	adpopcnk($(this),$(".admin_poplistdiv"));
});
$('#adminrate').find('input').click(function(){
	if($(this).attr('name')!='admin_op0' && !$(this).attr('checked'))$("input[name='admin_op0']").attr("checked",false);
});
adpopcnk(apop,$(".admin_poplistdiv"));
adpopcnk(a1301,$(".adpopcnkdiv"));
$(".adpopcnkdiv").find("input").click(function(){
	$(this).attr('checked')?a1301.attr("checked",true):'';
});
controle($("input[name='admin_group']:checked").val());
$("h2").click(function(){
	var int = $(this).parent('div').find('input');
	if(!int.attr("disabled")){
		if($("input[name='admin_pop']").attr('checked')){
			if(int.eq(0).attr('checked')){
				int.attr("checked",false);
			}else{
				int.attr("checked",true);
			}
		}else{
			if(int.eq(0).attr('checked')){
				int.attr("checked",false);
			}else{
				int.attr("checked",true);
			}
		}
		suoyoulist();
	}
});
function suoyoulist(){
	var tnr = $(".admin_poplistdiv").find("input").not("input:checked").length==0?'checked':false;
	$("input[name='admin_pop']").attr("checked",tnr);
}
$(".admin_poplistdiv").find('input').change(function(){
	if(!$(this).attr("checked")){
		$("input[name='admin_pop']").attr("checked",false);
	}
	suoyoulist();
});
function youmeiyou(){
	var m=0;
	$('.langok_list').each(function(){
		if($(this).attr('checked'))m=1;
	});
	return m;
}
$('.langok_list').change(function(){
	if(!$(this).attr('checked')){
		var m=youmeiyou();
		if(m==1){
			$("input[name='langok']").attr("checked",false);
			$(".poplang_"+$(this).val()).find("input").attr("checked",false).attr("disabled",true);
			$("input[name='admin_pop']").attr("checked",false);
		}else{
			alert(user_msg['jsx2']);
			$(this).attr('checked',true);
		}
	}else{
		$(".poplang_"+$(this).val()).find("input").attr("checked",true);
		if($("input[name='admin_group']:checked").val()==0)$(".poplang_"+$(this).val()).find("input").attr("disabled",false);
		suoyoulist();
	}
	if($(".admin_poplistdiv").find("input[disabled=true]").length>0){
		$("input[name=admin_pop]").attr("disabled",true);
	}else{
		$("input[name=admin_pop]").attr("disabled",false);
	}
});
$('.langok_list').each(function(){
	if(!$(this).attr('checked')){
		$(".poplang_"+$(this).val()).find("input").attr("checked",false).attr("disabled",true);
	}
});