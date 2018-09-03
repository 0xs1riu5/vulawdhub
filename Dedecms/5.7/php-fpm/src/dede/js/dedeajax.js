/**
 * 
 * @version        $Id: dedeajax.js 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

//xmlhttp和xmldom对象
DedeXHTTP = null;
DedeXDOM = null;
DedeContainer = null;

//获取指定ID的元素
function $(eid){
    return document.getElementById(eid);
}

function $DE(id) {
	return document.getElementById(id);
}

//参数 gcontainer 是保存下载完成的内容的容器

function DedeAjax(gcontainer){

    DedeContainer = gcontainer;

    //post或get发送数据的键值对
    this.keys = Array();
    this.values = Array();
    this.keyCount = -1;

    //http请求头
    this.rkeys = Array();
    this.rvalues = Array();
    this.rkeyCount = -1;
    //请求头类型
    this.rtype = 'text';

    //初始化xmlhttp
    if(window.ActiveXObject){//IE6、IE5
        try { DedeXHTTP = new ActiveXObject("Msxml2.XMLHTTP");} catch (e) { }
    if (DedeXHTTP == null) try { DedeXHTTP = new ActiveXObject("Microsoft.XMLHTTP");} catch (e) { }
    }
    else{
        DedeXHTTP = new XMLHttpRequest();
    }

    DedeXHTTP.onreadystatechange = function(){
	if(DedeXHTTP.readyState == 4){
    if(DedeXHTTP.status == 200){
       DedeContainer.innerHTML = DedeXHTTP.responseText;
       DedeXHTTP = null;
    }else DedeContainer.innerHTML = "下载数据失败";
  }else DedeContainer.innerHTML = "正在下载数据...";
};

//增加一个POST或GET键值对
this.AddKey = function(skey,svalue){
	this.keyCount++;
	this.keys[this.keyCount] = skey;
	this.values[this.keyCount] = escape(svalue);
};

//增加一个Http请求头键值对
this.AddHead = function(skey,svalue){
	this.rkeyCount++;
	this.rkeys[this.rkeyCount] = skey;
	this.rvalues[this.rkeyCount] = svalue;
};

//清除当前对象的哈希表参数
this.ClearSet = function(){
	this.keyCount = -1;
	this.keys = Array();
	this.values = Array();
	this.rkeyCount = -1;
	this.rkeys = Array();
	this.rvalues = Array();
};

//发送http请求头
this.SendHead = function(){
	if(this.rkeyCount!=-1){ //发送用户自行设定的请求头
  	for(;i<=this.rkeyCount;i++){
  		DedeXHTTP.setRequestHeader(this.rkeys[i],this.rvalues[i]); 
  	}
  }
　if(this.rtype=='binary'){
  	DedeXHTTP.setRequestHeader("Content-Type","multipart/form-data");
  }else{
  	DedeXHTTP.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  }
};

//用Post方式发送数据
this.SendPost = function(purl){
	var pdata = "";
	var i=0;
	this.state = 0;
	DedeXHTTP.open("POST", purl, true); 
	this.SendHead();
  if(this.keyCount!=-1){ //post数据
  	for(;i<=this.keyCount;i++){
  		if(pdata=="") pdata = this.keys[i]+'='+this.values[i];
  		else pdata += "&"+this.keys[i]+'='+this.values[i];
  	}
  }
  DedeXHTTP.send(pdata);
};

//用GET方式发送数据
this.SendGet = function(purl){
	var gkey = "";
	var i=0;
	this.state = 0;
	if(this.keyCount!=-1){ //get参数
  	for(;i<=this.keyCount;i++){
  		if(gkey=="") gkey = this.keys[i]+'='+this.values[i];
  		else gkey += "&"+this.keys[i]+'='+this.values[i];
  	}
  	if(purl.indexOf('?')==-1) purl = purl + '?' + gkey;
  	else  purl = purl + '&' + gkey;
  }
	DedeXHTTP.open("GET", purl, true); 
	this.SendHead();
  DedeXHTTP.send(null);
};

} // End Class DedeAjax

//初始化xmldom
function InitXDom(){
  if(DedeXDOM!=null) return;
  var obj = null;
  if (typeof(DOMParser) != "undefined") { // Gecko、Mozilla、Firefox
    var parser = new DOMParser();
    obj = parser.parseFromString(xmlText, "text/xml");
  } else { // IE
    try { obj = new ActiveXObject("MSXML2.DOMDocument");} catch (e) { }
    if (obj == null) try { obj = new ActiveXObject("Microsoft.XMLDOM"); } catch (e) { }
  }
  DedeXDOM = obj;
};
 