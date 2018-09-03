//左侧菜单效果
  function menuShow(mid)
  {
	  if($("#"+mid).css("display") == 'block') {
		  $("#"+mid).hide(200);
		  $("#"+mid+"_t b").removeClass();
		  $("#"+mid+"_t b").addClass("showMenu");
	  }
	  else {
		  $("#"+mid).show(200);
		  $("#"+mid+"_t b").removeClass();
		  $("#"+mid+"_t b").addClass("hideMenu");
	  }
  }