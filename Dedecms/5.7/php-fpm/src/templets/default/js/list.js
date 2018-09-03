//topnav  list_top

startList = function() {

navRoot = document.getElementById("g_nav");

for (i=0; i<navRoot.childNodes.length; i++) {

node = navRoot.childNodes[i];

if (node.nodeName=="LI") {

node.onmouseover=function() {

this.className+=" over";

}

node.onmouseout=function() {

this.className=this.className.replace(" over", "");

}

}

}

}

//topnav  list_top end



function writedelay(id,idd){var obj_id=document.getElementById(id);var obj_idd=document.getElementById(idd);obj_id.innerHTML=obj_idd.innerHTML;}

//focus_tab

var waitInterval

var DelayTime=80

function delaytab(m,n,count,hght){

clearTimeout(waitInterval);

waitInterval=window.setTimeout("showtab("+m+","+n+","+count+","+hght+");",DelayTime);}

function showtab(m,n,count,hght){

for(var i=1;i<=count;i++){

if (i==n){getObject('tab_'+m+'_'+i).style.display='';}

else {getObject('tab_'+m+'_'+i).style.display='none';}}

if ((m==2) || (m==3) || (m==4) || (m==5) || (m==6) || (m==7)){

var positiony=-(n-1)*hght+"px";

getObject('tt'+m).style.backgroundPosition="0px "+positiony;

}else

var positiony=-(n-1)*hght+"px";

getObject('tt'+m).style.backgroundPosition="0px "+positiony;}

function getObject(objectId) {

if(document.getElementById && document.getElementById(objectId)){

return document.getElementById(objectId);

} else if (document.all && document.all(objectId)) {

return document.all(objectId);

} else if (document.layers && document.layers[objectId]) {

return document.layers[objectId];

} else {return false;}}

//layer

function MM_findObj(n, d) {

var p,i,x;  

if(!d) d=document; 

if((p=n.indexOf("?"))>0&&parent.frames.length) {

d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}

if(!(x=d[n])&&d.all) x=d.all[n];

if(!(x)&&d.getElementById) x=d.getElementById(n);

for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];

for(i=0;!x&&d.layers&&i<d.layers.length;i++)

x=MM_findObj(n,d.layers[i].document); return x;}

function MM_showHideLayers() {

var i,p,v,obj,args=MM_showHideLayers.arguments;

for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];

if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }

obj.visibility=v;}}

//bighot

if(typeof PicFocusManager == "undefined")

	{

		PicFocusManager =[];

	}

	function PicFocus(imageContainerID,textContainerID,buttonContainerID,intervarTime){

		this.$ = function (id){return document.getElementById(id)}

		this.index = PicFocusManager.length;

		PicFocusManager[PicFocusManager.length] = this;

		this.imageContainer = this.$(imageContainerID);

		this.textContainer = this.$(textContainerID);

		this.buttonContainer = this.$(buttonContainerID);

		this.firstShow = 0; //默认显示项

		this.interval = (isNaN(intervarTime)?0:intervarTime) || 5000;

		 //切换时间

		this.canAutoPlay = true; //是否可以自动切换

		this.currentPosition = this.firstShow;

		this.timer;

		this.images = [];

		this.texts = [];

		this.buttons = [];

		this.callback=function(){};

		this.bindEvent = function(){

			var _self = this;

			for(var i=0;i<this.images.length;i++){

				this.images[i].onmouseover = function(){

					_self.stop();

				}

				this.images[i].onmouseout = function(){

					_self.timer = setTimeout('PicFocusManager[' + _self.index + '].play()' , _self.interval )

				}

			}

			for(var i=0;i<this.buttons.length;i++){

				this.buttons[i].onclick = function(){

					_self.focus(this);

				}

			}

		}

		this.play = function(){

			this.stop();

			if(this.canAutoPlay){

				this.setFocus(this.currentPosition ++ )

				if(this.currentPosition >= this.images.length)this.currentPosition =0 ;

			}

			this.timer = setTimeout('PicFocusManager[' + this.index + '].play()' , this.interval )

		}

		this.stop = function(){

			clearTimeout( this.timer );

		}

		this.focus = function(button){

			for(var i=0;i<this.buttons.length;i++){

				if(this.buttons[i] == button){

					this.currentPosition = i;

					this.setFocus(this.currentPosition);

					break;

				}

			}

		}

		this.setFocus = function(i){

			if(/Microsoft/.test(navigator.appName)){

				this.imageContainer.filters[0].apply();

				this.imageContainer.filters[0].play();

			}

			for(var j=0;j<this.images.length;j++){

				this.images[j].style.display = (i==j)?"":"none";

			}

			for(var j=0;j<this.texts.length;j++){

				this.texts[j].style.display = (i==j)?"":"none";

			}

			for(var j=0;j<this.buttons.length;j++){

				this.buttons[j].className = (i==j)? this.buttons[j].getAttribute("focusClass") :this.buttons[j].getAttribute("normalClass");

			}

			if(/Microsoft/.test(navigator.appName)){  //滤镜版本

				new ActiveXObject("DXImageTransform.Microsoft.Fade");

				this.imageContainer.filters[0].play();

			}

			this.callback(i);

		}

		this.init = function(){

			if(this.imageContainer && this.buttonContainer){

				//init

				this.images=this.imageContainer.getElementsByTagName("img");

				if(this.textContainer) this.texts=this.textContainer.getElementsByTagName("b");//学院频道焦点图下文字

				this.buttons=this.buttonContainer.getElementsByTagName("a");

				this.bindEvent();

				for(var i=0;i<this.images.length;i++){

					this.images[i].style.display = "none";

					if(i<this.texts.length) this.texts[i].style.display = "none";

					this.buttons[i].className = this.buttons[i].getAttribute("normalClass");

					this.buttons[i].target="_self";

				}

				this.images[this.firstShow].style.display = "";

				if(this.firstShow<this.texts.length) this.texts[this.firstShow].style.display = "";

				this.buttons[this.firstShow].className = this.buttons[this.firstShow].getAttribute("focusClass");

			}else{

				alert("no provide correct parameter")

			}

		}

	}

	

//search

function getNavigatorType(){

	if(navigator.appName == "Microsoft Internet Explorer")

		return 1;  

	else if(navigator.appName == "Netscape")

		return 2;	

	else 

		return 0;

}

function wValChg(idx,sts){

	var s = "http://search.it.com.cn/index.php";

	if(idx == "2") getObject("wn_"+ sts +"").innerHTML = "搜索产品";

	if(idx == "1") getObject("wn_"+ sts +"").innerHTML = "搜索资讯";

	if(idx == "3") getObject("wn_"+ sts +"").innerHTML = "搜索软件";

	if(idx == "4") getObject("wn_"+ sts +"").innerHTML = "搜索论坛";

	if(sts == "h"){

		document.search.tp.value = idx;

	}

	getObject("sbArea_"+ sts +"").style.display = "none";

}

function wValDisp(sts,idx){

	if(getObject("sbArea_"+ sts +"").style.display == "none"){

		getObject("sbArea_"+ sts +"").style.display = "";

	}else{

		getObject("sbArea_"+ sts +"").style.display = "none";

	}

}

function setSelBox(event){

	var _event;

	switch (getNavigatorType()) {

		case 1 : // IE

			_event = window.event;

			node = _event.srcElement;

			nodeName = _event.srcElement.className;

			break;

		case 2 : // Netscape

			_event = event;

			node = _event.target;

			nodeName = _event.target.className;

			break;

		default :

			nodeName = "None"; 

			break;

	}

	if(nodeName == "dselObj"){

		

	}else{

		try{

			document.getElementById("sbArea_h").style.display = "none";

		}catch(e){}

	}

}



//hotsoftwear



 function GetObj(objName){

     if(document.getElementById){

      return eval('document.getElementById("' + objName + '")');

     }else if(document.layers){

      return eval("document.layers['" + objName +"']");

     }else{

      return eval('document.all.' + objName);

     }

    }

    function pc(preFix, idx){

     for(var i=0;i<10;i++){

      if(GetObj(preFix+"_a_"+i)) GetObj(preFix+"_a_"+i).className = "none";

      if(GetObj(preFix+"_b_"+i)) GetObj(preFix+"_b_"+i).style.display = "none";

     }

     GetObj(preFix+"_a_"+idx).className = "edu_tt_aon";

     GetObj(preFix+"_b_"+idx).style.display = "block";

    }