/**
 * 图集
 *
 * @version        $Id: album.js 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

function checkSubmitAlb(){
	if(document.form1.title.value==''){
	    alert("档案标题不能为空！");
		return false;
	}
	if(document.form1.typeid.value==0){
		alert("请选择档案的主类别！");
		return false;
	}
	document.form1.imagebody.value = $Obj('copyhtml').innerHTML;
	return true;
}

function testGet(){
    LoadTestDiv();
}

function checkMuList(psid,cmid){
    if($Obj('pagestyle3').checked){
	    $Obj('cfgmulist').style.display = 'block';
		$Obj('spagelist').style.display = 'none';
	} else if($Obj('pagestyle1').checked) {
		$Obj('cfgmulist').style.display = 'none';
		$Obj('spagelist').style.display = 'block';
	} else	{
		$Obj('cfgmulist').style.display = 'none';
		$Obj('spagelist').style.display = 'none';
	}
}

//图集，显示与隐藏zip文件选项
function showZipField(formitem,zipid,upid){
    if(formitem.checked){
	    $Obj(zipid).style.display = 'block';
		$Obj(upid).style.display = 'none';
		//$Obj('handfield').style.display = 'none';
		$Obj('formhtml').checked = false;
		$Obj('copyhtml').innerHTML = '';
	} else {
		$Obj(zipid).style.display = 'none';
		//$Obj('handfield').style.display = 'block';
	}
}

//图集，显示与隐藏Html编辑框
function showHtmlField(formitem,htmlid,upid){
    if($Nav()!="IE"){
		alert("该方法不适用于非IE浏览器！");
		return ;
	}
	if(formitem.checked){
		$Obj(htmlid).style.display = 'block';
		$Obj(upid).style.display = 'none';
		//$Obj('handfield').style.display = 'none';
		$Obj('formzip').checked = false;
	} else 	{
		$Obj(htmlid).style.display = 'none';
		//$Obj('handfield').style.display = 'block';
		$Obj('copyhtml').innerHTML = '';
	}
}

function seePicNewAlb(f, imgdid, frname, hpos, acname){
    var newobj = null;
	if(f.value=='') return ;
	vImg = $Obj(imgdid);
	picnameObj = document.getElementById('picname');
	nFrame = $Nav()=='IE' ? eval('document.frames.'+frname) : $Obj(frname);
	nForm = f.form;
	//修改form的action等参数
	if(nForm.detachEvent) nForm.detachEvent("onsubmit", checkSubmitAlb);
    else nForm.removeEventListener("submit", checkSubmitAlb, false);
	nForm.action = 'archives_do.php';
	nForm.target = frname;
	nForm.dopost.value = 'uploadLitpic';
	nForm.submit();
	
	picnameObj.value = '';
	newobj = $Obj('uploadwait');
	if(!newobj){
        newobj = document.createElement("DIV");
		newobj.id = 'uploadwait';
		newobj.style.position = 'absolute';
		newobj.className = 'uploadwait';
		newobj.style.width = 120;
		newobj.style.height = 20;
		newobj.style.top = hpos;
		newobj.style.left = 100;
		document.body.appendChild(newobj);
		newobj.innerHTML = '<img src="images/loadinglit.gif" width="16" height="16" alit="" />上传中...';
	}
	newobj.style.display = 'block';
	//提交后还原form的action等参数
	nForm.action = acname;
	nForm.dopost.value = 'save';
	nForm.target = '';
	nForm.litpic.disabled = true;
	//nForm.litpic = null;
	//if(nForm.attachEvent) nForm.attachEvent("onsubmit", checkSubmit);
    //else nForm.addEventListener("submit", checkSubmit, true);
}

//删除已经上传的图片
function delAlbPic(pid){
	var tgobj = $Obj('albCtok'+pid);
	var myajax = new DedeAjax(tgobj);
	myajax.SendGet2('swfupload.php?dopost=del&id='+pid);
	$Obj('thumbnails').removeChild(tgobj);
}

//删除已经上传的图片(编辑时用)
function delAlbPicOld(picfile, pid){
	var tgobj = $Obj('albold'+pid);
	var myajax = new DedeAjax(tgobj);
	myajax.SendGet2('swfupload.php?dopost=delold&picfile='+picfile);
	$Obj('thumbnailsEdit').removeChild(tgobj);
}