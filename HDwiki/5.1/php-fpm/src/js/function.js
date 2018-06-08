var dance=0;
function divDance(divname) {
	var div=document.getElementById(divname);
	if(div.style.color=='red'){
		div.style.color='black';
	}else{
		div.style.color='red';
	}
	dance++;
	timer=setTimeout("divDance('"+divname+"')",200);
	if(dance>10){
		clearTimeout(timer);
		dance=0;
		div.style.color='red';
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