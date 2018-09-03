//创建多组对话框
  function createDialog(options) {
	  options = $.extend({title: "对话框"}, options || {});
	  var dialog = new Boxy("<div><p>这是一个对话框 <a href='#nogo' onclick='Boxy.get(this).hide(); return false'>单击我！</a></p></div>", options);
	  return false;
  } 