 function FeedDel()
 {
	if(confirm("你确定删除该动态信息?"))
    	return true;
   	else
		return false;
 }
 $(function(){
        $('#arcticle').click(function() {
            $.ajax({
			  type: "GET",
			  url: "feed.php",
			  dataType: "json",
			  success : function(data){
			         $('#FeedText').empty();
					  var html = '<div class="newarticlelist"><ul>';
					  $.each( data  , function(commentIndex, comment) {
						  html += '<li><span>' + comment['senddate'] + '</span><span><a href="/member/index.php?uid='+ comment['userid'] +'">'+ comment['userid'] +'</a></span><a href="' + comment['htmlurl'] + '" target="_blank">' + comment['title'] + '</a></li>';
					  })
					 html +="</ul></div>";
					 $('#FeedText').html(html);
					 $("#arcticle").addClass("thisTab");
					 $("#myfeed").removeClass("thisTab");
					 $("#allfeed").removeClass("thisTab");
					 $("#score").removeClass("thisTab");
					 $("#mood").removeClass("thisTab");
			  }
			}); 
        });
   })
   $(function(){
        $('#allfeed').click(function() {
            $.ajax({
			  type: "GET",
			  url: "feed.php?type=allfeed",
			  dataType: "json",
			  success : function(data){
			         $('#FeedText').empty();
					  var html = '';
					  $.each( data  , function(commentIndex, comment) {
						  html += '<div class="feeds_title ico' + comment['type'] + '"><span><a href="/member/index.php?uid='+ comment['uname'] +'">'+ comment['uname'] +'</a>' + comment['title'] + ' <em>' + comment['dtime'] + '</em></span><p>' + comment['note'] + '</p></div>';
					  })
					 $('#FeedText').html(html);
					 $("#allfeed").addClass("thisTab");
					 $("#myfeed").removeClass("thisTab");
					 $("#arcticle").removeClass("thisTab");
					 $("#score").removeClass("thisTab");
					 $("#mood").removeClass("thisTab");
			  }
			}); 
        });
   })
   //
   $(function(){
        $('#myfeed').click(function() {
            $.ajax({
			  type: "GET",
			  url: "feed.php?type=myfeed",
			  dataType: "json",
			  success : function(data){
			         $('#FeedText').empty();
					  var html = '';
					  $.each( data  , function(commentIndex, comment) {
						  html += '<div class="feeds_title ico' + comment['type'] + '"><span><a href="index.php?uid='+ comment['uname'] +'&action=feeddel&fid=' + comment['fid'] + '" onclick="return FeedDel()" class="act">删除</a><a href="/member/index.php?uid='+ comment['uname'] +'">'+ comment['uname'] +'</a>' + comment['title'] + ' <em>' + comment['dtime'] + '</em></span><p>' + comment['note'] + '</p></div>';
					  })
					 $('#FeedText').html(html);
					 $("#myfeed").addClass("thisTab");
					 $("#allfeed").removeClass("thisTab");
					 $("#arcticle").removeClass("thisTab");
					 $("#score").removeClass("thisTab");
					 $("#mood").removeClass("thisTab");
			  }
			}); 
        });
   })
   //我的动态
  $(function(){
            $.ajax({
			  type: "GET",
			  url: "feed.php?type=myfeed",
			  dataType: "json",
			  success : function(data){
			         $('#FeedText').empty();
					  var html = '';
					  $.each( data  , function(commentIndex, comment) {
						 html += '<div class="feeds_title ico' + comment['type'] + '"><span><a href="index.php?uid='+ comment['uname'] +'&action=feeddel&fid=' + comment['fid'] + '" onclick="return FeedDel()" class="act">删除</a><a href="/member/index.php?uid='+ comment['uname'] +'">'+ comment['uname'] +'</a>' + comment['title'] + ' <em>' + comment['dtime'] + '</em></span><p>' + comment['note'] + '</p></div>';
					  })
					 $('#FeedText').html(html);
			  }
			}); 
   })
  