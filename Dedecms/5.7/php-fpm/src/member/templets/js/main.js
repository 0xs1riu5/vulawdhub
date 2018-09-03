<!--

function $Nav(){
	if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
	else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
	else return "OT";
}

function $Obj(objname){
	return document.getElementById(objname);
}

function ShowColor(){
   if(document.all){
     var posLeft = window.event.clientY-100;
     var posTop = window.event.clientX-400;
   }
   else{
     var posLeft = 100;
     var posTop = 100;
   }
	var fcolor=showModalDialog("img/color.htm?ok",false,"dialogWidth:106px;dialogHeight:110px;status:0;dialogTop:"+posTop+";dialogLeft:"+posLeft);
	if(fcolor!=null && fcolor!="undefined") document.form1.color.value = fcolor;
}

function ShowHide(objname){
	var obj = $Obj(objname);
	if(obj.style.display == "block" || obj.style.display == ""){  obj.style.display = "none"; }
	else{  obj.style.display = "block"; }
}

function ShowObj(objname){
	var obj = $Obj(objname);
	obj.style.display = "block";
}

function HideObj(objname){
	var obj = $Obj(objname);
	obj.style.display = "none";
}

function ShowItem1(){
	ShowObj('head1'); ShowObj('needset'); HideObj('head2'); HideObj('adset');
}

function ShowItem2(){
	ShowObj('head2'); ShowObj('adset'); HideObj('head1'); HideObj('needset');
}

function SeePic(img,f){
	if ( f.value != "" ) { img.src = f.value; }
}

function SeePicNew(imgdid,f) {
	if(f.value=='') return ;
	var newPreview = document.getElementById(imgdid);
	var filepath = 'file:///'+f.value.replace(/\\/g,"/").replace(/\:/,"|");
	var image = new Image(); var ImgD = new Image();
	ImgD.src = filepath;
	image.src = ImgD.src; FitWidth = 150; FitHeight = 100;
	if(image.width>0 && image.height>0)
	{
		 if(image.width/image.height>= FitWidth/FitHeight)
		 {
			 if(image.width>FitWidth)
			 {
				 ImgD.width=FitWidth;
				 ImgD.height=(image.height*FitWidth)/image.width;
			 }
			 else
			 {
				 ImgD.width=image.width; 
				ImgD.height=image.height;
			 }
		 }
		 else
		 {
			if(image.height>FitHeight)
			{
				 ImgD.height=FitHeight;
				 ImgD.width=(image.width*FitHeight)/image.height;
			}
			else
			{
				 ImgD.width=image.width; 
				ImgD.height=image.height;
			} 
		}
	}
	
	newPreview.style.width = ImgD.width+"px";
	newPreview.style.height = ImgD.height+"px";

	if(window.navigator.userAgent.indexOf("MSIE") < 1)
	{
		newPreview.style.background = "url('"+ImgD.src+"') no-repeat";	
	}
	else
	{
		newPreview.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ImgD.src+"',sizingMethod='scale')";
	}
	
	ImgD = image = null;
	//newPreview.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = f.value;
}

function SelectFlash(){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-300; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	window.open("uploads_select.php?mediatype=2&f=form1.flashurl", "popUpFlashWin", "scrollbars=yes,resizable=yes,statebar=no,width=500,height=350,left="+posLeft+", top="+posTop);
}

function SelectMedia(fname){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-200; var posTop = window.event.clientY; }
	else{ var posLeft = 100;var posTop = 100; }
	window.open("uploads_select.php?mediatype=3&f="+fname, "popUpFlashWin", "scrollbars=yes,resizable=yes,statebar=no,width=500,height=350,left="+posLeft+", top="+posTop);
}

function SelectSoft(fname){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-200; var posTop = window.event.clientY-50; }
	else{ var posLeft = 100; var posTop = 100; }
	window.open("uploads_select.php?mediatype=4&f="+fname, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=400,left="+posLeft+", top="+posTop);
}

function SelectImage(fname,stype){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-100; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	if(!fname) fname = 'form1.picname';
	if(!stype) stype = '';
	window.open("uploads_select.php?mediatype=1&f="+fname+"&imgstick="+stype, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=400,left="+posLeft+", top="+posTop);
}

function SelectImageN(fname,stype,vname){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-100; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	if(!fname) fname = 'form1.picname';
	if(!stype) stype = '';
	window.open("uploads_select.php?mediatype=1&f="+fname+"&imgstick="+stype+"&v="+vname, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=400,left="+posLeft+", top="+posTop);
}

function SelectKeywords(f){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-350; var posTop = window.event.clientY-200; }
	else{ var posLeft = 100; var posTop = 100; }
	window.open("article_keywords_select.php?f="+f, "popUpkwWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=450,left="+posLeft+", top="+posTop);
}

function InitPage(){
	var selsource = $Obj('selsource');
	var selwriter = $Obj('selwriter');
	if(selsource){ selsource.onmousedown=function(e){ SelectSource(e); } }
	if(selwriter){ selwriter.onmousedown=function(e){ SelectWriter(e); } }
}

function OpenMyWin(surl){
	window.open(surl, "popUpMyWin", "scrollbars=yes,resizable=yes,statebar=no,width=500,height=350,left=200, top=100");
}

function PutSource(str){
	var osource = $Obj('source');
	if(osource) osource.value = str;
}

function PutWriter(str){
	var owriter = $Obj('writer');
	if(owriter) owriter.value = str;
}

function SelectSource(e){
	LoadNewDiv(e,'article_select_sw.php?t=source&k=8','_mysource');
}

function SelectWriter(e){
	LoadNewDiv(e,'article_select_sw.php?t=writer&k=8','_mywriter');
}

function LoadNewDiv(e,surl,oname){
	if($Nav()=='IE'){ var posLeft = window.event.clientX-20; var posTop = window.event.clientY-20; }
	else{ var posLeft = e.pageX-20; var posTop = e.pageY-20; }
	var newobj = $Obj(oname);
	if(!newobj){
		newobj = document.createElement("DIV");
		newobj.id = oname;
		newobj.style.position='absolute';
		newobj.className = "dlg";
		newobj.style.top = posTop;
		newobj.style.left = posLeft;
		document.body.appendChild(newobj);
	}
	else{
		newobj.style.display = "block";
	}
	if(newobj.innerHTML.length<10){
		var myajax = new DedeAjax(newobj); myajax.SendGet(surl);
	}
}

function ShowUrlTr(){
	var jumpTest = $Obj('isjump');
	var jtr = $Obj('redirecturltr');
	if(jumpTest.checked) jtr.style.display = "block";
	else jtr.style.display = "none";
}

function ShowUrlTrEdit(){
	ShowUrlTr();
	var jumpTest = $Obj('isjump');
	var rurl = $Obj('redirecturl');
	if(!jumpTest.checked) rurl.value="";
}

function CkRemote(ckname,fname){
	var ckBox = $Obj(ckname);
	var fileBox = $Obj(fname);
	if(ckBox.checked){
		fileBox.style.display = 'none';
	}else{
		fileBox.style.display = 'block';
	}
}

-->