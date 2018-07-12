$(function(){
	$(document).on('click','#login_submit',function(){
		window.location.href=$(this).attr('link');
	});
	$('#load_tip').hide();
});