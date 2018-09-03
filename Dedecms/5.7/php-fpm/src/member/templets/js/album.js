<!--

function checkSubmit()
{

	if(document.form1.title.value=='') {
		alert("图集标题不能为空！");
		document.form1.title.focus();
		return false;
	}

	if(document.form1.typeid.value==0) {
		alert("隶属栏目必须选择！");
		return false;
	}

	if(document.form1.typeid.options[document.form1.typeid.selectedIndex].className!='option3')
	{
		alert("隶属栏目必须选择白色背景的项目！");
		return false;
	}

	document.form1.imagebody.value = $Obj('copyhtml').innerHTML;

	$Obj('postloader').style.display = 'block';

}

function CheckSelTable(nnum){
	var cbox = $Obj('isokcheck'+nnum);
	var seltb = $Obj('seltb'+nnum);
	if(!cbox.checked) seltb.style.display = 'none';
	else seltb.style.display = 'block';
}

var startNum = 1;
function MakeUpload(mnum)
{
	var endNum = 0;
	var upfield = document.getElementById("uploadfield");
	var pnumObj = document.getElementById("picnum");
	var fhtml = "";
	var dsel = " checked='checked' ";
	var dplay = "display:none";

	if(mnum==0) endNum = startNum + Number(pnumObj.value);
	else endNum = mnum;
	if(endNum>120) endNum = 120;

	//$Obj('handfield').style.display = 'block';

	for(startNum;startNum < endNum;startNum++)
	{
		if(startNum==1){
			dsel = " checked='checked' ";
			dplay = "block";
		}else
		{
			dsel = " ";
			dplay = "display:none";
		}
		fhtml = '';
		fhtml += "<table width='100%'><tr><td><input type='checkbox' name='isokcheck"+startNum+"' id='isokcheck"+startNum+"' value='1' class='np' "+dsel+" onClick='CheckSelTable("+startNum+")' />显示图片 "+startNum+" 的上传框</td></tr></table>";
		fhtml += "<table width='610' border=\"0\" id=\"seltb"+startNum+"\" cellpadding=\"1\" cellspacing=\"1\" bgcolor=\"#E8F5D6\" style=\"margin-bottom:6px;margin-left:10px;"+dplay+"\"><tobdy>";
		fhtml += "<tr bgcolor=\"#F7F7F7\">\r\n";
		fhtml += "<td height=\"25\" colspan=\"2\">　<strong>图片"+startNum+"：</strong></td>";
		fhtml += "</tr>";
		fhtml += "<tr bgcolor=\"#FFFFFF\"> ";
		fhtml += "<td width=\"510\" height=\"25\"> 　本地上传： ";
		fhtml += "<input type=\"file\" name='imgfile"+startNum+"' style=\"width:200px\" class=\"intxt\" onChange=\"SeePicNew('divpicview"+startNum+"',this);\" /> <nobr>可填远程网址</nobr></td>";
		fhtml += "<td width=\"100\" rowspan=\"2\" align=\"center\"><div id='divpicview"+startNum+"' class='divpre'></div></td>";
		fhtml += "</tr>";
		fhtml += "<tr bgcolor=\"#FFFFFF\"> ";
		fhtml += "<td height=\"56\" valign=\"top\">　图片简介： ";
		fhtml += "<textarea name='imgmsg"+startNum+"' style=\"height:46px;width:330px\"></textarea></td>";
		fhtml += "</tr></tobdy></table>\r\n";
		upfield.innerHTML += fhtml;
	}
}

function TestGet()
{
	LoadTestDiv();
}

var vcc = 0;
function LoadTestDiv()
{
	var posLeft = 100; var posTop = 100;
	var newobj = $Obj('_myhtml');
	$Obj('imagebody').value = $Obj('copyhtml').innerHTML;
	var dfstr = '粘贴到这里...';
	if($Obj('imagebody').value.length <= dfstr.length)
	{
		alert('你还没有粘贴任何东西都编辑框哦！');
		return;
	}
	if(!newobj){
		newobj = document.createElement("DIV");
		newobj.id = '_myhtml';
		newobj.style.position='absolute';
		newobj.className = "dlg2";
		newobj.style.top = posTop;
		newobj.style.left = posLeft;
		document.body.appendChild(newobj);
	}
	else{
		newobj.style.display = "block";
	}
	var myajax = new DedeAjax(newobj,false,true,'-','-','...');
	var v = $Obj('imagebody').value;
	vcc++;

	//utf8
	myajax.AddKeyUtf8('myhtml',v);
	myajax.AddKeyUtf8('vcc',vcc);
	myajax.SendPost2('album_testhtml.php');

	//gbk
	//myajax.SendGet2("album_testhtml.php?vcc="+vcc+"&myhtml="+v);

	DedeXHTTP = null;
}

function checkMuList(psid,cmid)
{
	if($Obj('pagestyle3').checked)
	{
		$Obj('spagelist').style.display = 'none';
	}
	else if($Obj('pagestyle1').checked)
	{
		$Obj('spagelist').style.display = 'block';
	}
	else
	{
		$Obj('spagelist').style.display = 'none';
	}
}

//图集，显示与隐藏zip文件选项
function ShowZipField(formitem,zipid,upid)
{
	if(formitem.checked){
		$Obj(zipid).style.display = 'block';
		$Obj(upid).style.display = 'none';
		//$Obj('handfield').style.display = 'none';
		$Obj('formhtml').checked = false;
		$Obj('copyhtml').innerHTML = '';
	}else
	{
		$Obj(zipid).style.display = 'none';
		//$Obj('handfield').style.display = 'block';
	}
}

//图集，显示与隐藏Html编辑框
function ShowHtmlField(formitem,htmlid,upid)
{
	if($Nav()!="IE"){
		alert("该方法不适用于非IE浏览器！");
		return ;
	}
	if(formitem.checked){
		$Obj(htmlid).style.display = 'block';
		//$Obj(upid).style.display = 'none';
		//$Obj('handfield').style.display = 'none';
		//$Obj('formzip').checked = false;
	}else
	{
		$Obj(htmlid).style.display = 'none';
		//$Obj('handfield').style.display = 'block';
		$Obj('copyhtml').innerHTML = '';
	}
}

-->