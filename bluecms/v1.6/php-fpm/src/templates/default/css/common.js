function createQueryString(oText){
	var sInput = document.getElementById(oText).value;
	var queryString = oText+"="+sInput;
	return queryString;
}

function getData(oServer, oText, oSpan, act){
	var xmlHttp;
	if(window.ActiveXObject)
		xmlHttp = new ActiveXObject("Microsoft.XMLHttp");
	else if(window.XMLHttpRequest)
		xmlHttp = new XMLHttpRequest();
	var queryString = oServer + "?";
	queryString += createQueryString(oText) + "&timestamp=" + new Date().getTime() + "&act=" + act;
	//document.write(queryString);
	xmlHttp.onreadystatechange = function(){
		if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
			var responseSpan = document.getElementById(oSpan);
			//alert(decodeURI(xmlHttp.responseText));
			//alert(xmlHttp.responseText);
			responseSpan.innerHTML = xmlHttp.responseText;
			delete xmlHttp;
			xmlHttp = null;
		}
	}
	xmlHttp.open("GET", queryString);
	xmlHttp.send(null);
}