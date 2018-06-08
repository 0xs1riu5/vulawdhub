 
 
function getPosition() {
	var top    = document.documentElement.scrollTop;
	var left   = document.documentElement.scrollLeft;
	var height = document.documentElement.clientHeight;
	var width  = document.documentElement.clientWidth;
	return {top:top,left:left,height:height,width:width};
}

function showPop(width,height){
	var width=width? width:150;
	var height=height? height:100;
	var pop = document.getElementById("pop");
	pop.style.display  = "block";
	pop.style.position = "absolute";
	pop.style.zindex   = "999";
	pop.style.width   = width + "px";
	pop.style.height  = height + "px";
	var Position = getPosition();
	leftadd = (Position.width-width)/2;
	topadd  = (Position.height-height)/2;
	pop.style.top  = (Position.top  + topadd)  + "px";
	pop.style.left = (Position.left + leftadd) + "px";
	window.onscroll = function (){
		var Position   = getPosition();
		pop.style.top  = (Position.top  + topadd)  +"px";
		pop.style.left = (Position.left + leftadd) +"px";
	}
}

 
 
function hidePop(){
	document.getElementById("pop").style.display = "none";
}

function setPopTitle(title){
	document.getElementById("poptitle").innerHTML= title;
}

function setPopMsg(popmsg){
	document.getElementById("popmsg").innerHTML= popmsg;
}

var dance=0;
function divDance(divname) {
	var div=document.getElementById(divname);
	if(div.style.color=='red'){
		div.style.color='black'
	}else{
		div.style.color='red'
	}
	dance++;
	timer=setTimeout("divDance('"+divname+"')",0.2*1000);
	if(dance>10){
		clearTimeout(timer);
		dance=0;
		div.style.color='red'
	}
}

function bytes(str){
 var len=0;
 for(var i=0;i<str.length;i++){
 	if(str.charCodeAt(i)>127){
 		len++;
 	}
 	len++;
 	}
   return len;
 }