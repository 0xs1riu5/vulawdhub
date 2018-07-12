$(function(){
	
	var note = $('#note'),
	ts = (new Date(2017, 0, 25, 23, 59, 59)).getTime(),
	newYear = true;
	$('#countdown').countdown({
		timestamp	: ts,
		format: "dd:hh:mm:ss",
		callback	: function(days, hours, minutes, seconds){
			
			var message = "春节将至，ThinkSNS年度福利限时放送！<a href='http://demo.thinksns.com/ts4/index.php?app=weiba&mod=Index&act=postDetail&post_id=4740'>点击</a>参与赢红包";
			note.html(message);
		}
	});

	$('.cd_close').click(function(){
		$('.countdown').hide();
	})
	
});
