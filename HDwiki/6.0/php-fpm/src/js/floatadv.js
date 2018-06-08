var winWidth = 0;
var winHeight = 0;
var offsetBody=0;
function findDimensions(){
	 if (window.innerWidth)
		   winWidth = window.innerWidth;
	 else if ((document.body) && (document.body.clientWidth))
		   winWidth = document.body.clientWidth;
	 if (window.innerHeight)
		   winHeight = window.innerHeight;
	 else if ((document.body) && (document.body.clientHeight))
		   winHeight = document.body.clientHeight;
	 if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth){
		 winHeight = document.documentElement.clientHeight;
		 winWidth = document.documentElement.clientWidth;
	 }
	 offsetBody=Math.floor((document.documentElement.clientWidth-document.body.clientWidth)/2)
}
findDimensions();
window.onresize=findDimensions;//窗口调整时执行。得到浏览器宽度高度。

var delta=0.25;
var collection;
var closeB=false;
function floaters(winHeight) {
	this.items = [];
	this.addItem = function(id,x,y,content)	{
		document.write('<div id=' + id + ' style="z-index: 10; position: absolute;  left:' + (typeof(x) == 'string' ? eval(x) : x) + ';top:' + (typeof(y) == 'string'? eval(y) : y) + '">' + content + '</div>');
		var newItem = {};
		newItem.object = document.getElementById(id);
		newItem.x = x;
		newItem.y = y;
		this.items[this.items.length] = newItem;
	}
	this.play = function() {
		collection = this.items;
		setInterval('play()',30);
	}
}
function play() {
	if(winWidth<= 950 || closeB) {
		for(var i = 0;i < collection.length;i++) {
			collection[i].object.style.display = 'none';
		}
		return;
	}
	for(var i = 0;i < collection.length;i++) {
		var followObj = collection[i].object;
		var followObj_x = (typeof(collection[i].x) == 'string' ? eval(collection[i].x) : collection[i].x);
		var followObj_y = (typeof(collection[i].y) == 'string' ? eval(collection[i].y) : collection[i].y);
		

		if(followObj.offsetLeft != (document.documentElement.scrollLeft + followObj_x - offsetBody)) {
			var dx = (document.documentElement.scrollLeft + followObj_x - followObj.offsetLeft-offsetBody) * delta;
			dx = (dx > 0 ? 1 : -1) * Math.ceil(Math.abs(dx));
			followObj.style.left = (followObj.offsetLeft + dx) + 'px';
		}
		if(followObj.offsetTop != (document.documentElement.scrollTop + followObj_y)) {
			var dy = (document.documentElement.scrollTop + followObj_y - followObj.offsetTop) * delta;
			dy = (dy > 0 ? 1 : -1) * Math.ceil(Math.abs(dy));
			followObj.style.top = (followObj.offsetTop + dy) + 'px';

		}
		followObj.style.display = '';
	}
}
function closeBanner() {
	closeB = true;
	return;
}
var theFloaters = new floaters(winHeight);