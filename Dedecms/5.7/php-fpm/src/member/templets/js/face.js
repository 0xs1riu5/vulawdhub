  //显示表情
  function showFace() {
	  if($('#share_textarea').val() == '来,说点啥吧...'){
		  $('#share_textarea').val('');
	  }
	  //采用普通样式
	  //$('#mood_msg_menu').css('display', 'block');
	  var leftpos = $(".share02").position().left;
	  //获取位置并且决定表情框弹出位置
	  $('#mood_msg_menu').css('left', leftpos+'px');
	  $('#mood_msg_menu').show('normal');
	  //$('#mood_add').
	  if($('#mood_face_bg')) {$('#mood_face_bg').remove();}
	  var modDiv = '<div id="mood_face_bg" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 788px; z-index: 10000; opacity: 0;" onclick="hideFace()"/>'
	  $('#baseParent').append(modDiv); 
  }
  
  //隐藏表情
  function hideFace() {
	  //alert($('#share_textarea').val());
	  if($('#share_textarea').val() == ''){
		  $('#share_textarea').val('来,说点啥吧...');
	  }
	  $('#mood_msg_menu').css('display', 'none');
	  if($('#mood_face_bg')) {$('#mood_face_bg').remove();}
  }
  
  //增加表情
  function addFace(faceid) {
	  //通过faceid解析为表情代码添加到编辑框
	  var facecode;
	  facecode = '[face:' + faceid + ']';
	  $('#share_textarea').val($('#share_textarea').val() + facecode); 
  }