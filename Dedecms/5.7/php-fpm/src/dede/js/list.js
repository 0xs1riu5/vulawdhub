<!--
if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}
function viewArc(aid){
	if(aid==0) aid = getOneItem();
	window.open("archives_do.php?aid="+aid+"&dopost=viewArchives");
}
function kwArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	if(qstr=='')
	{
		alert('必须选择一个或多个文档！');
		return;
	}
	location="archives_do.php?aid="+aid+"&dopost=makekw&qstr="+qstr;
}
function editArc(aid){
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=editArchives";
}
function updateArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=makeArchives&qstr="+qstr;
}
function checkArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=checkArchives&qstr="+qstr;
}
function moveArc(e, obj, cid){
	var qstr=getCheckboxItem();
	if(qstr=='')
	{
		alert('必须选择一个或多个文档！');
		return;
	}
	LoadQuickDiv(e, 'archives_do.php?dopost=moveArchives&qstr='+qstr+'&channelid='+cid+'&rnd='+Math.random(), 'moveArchives', '450px', '180px');
	ChangeFullDiv('show');
}
function adArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=commendArchives&qstr="+qstr;
}

function cAtts(jname, e, obj)
{
	var qstr=getCheckboxItem();
    var screeheight = document.body.clientHeight + 20;
	if(qstr=='')
	{
		alert('必须选择一个或多个文档！');
		return;
	}
	LoadQuickDiv(e, 'archives_do.php?dopost=attsDlg&qstr='+qstr+'&dojob='+jname+'&rnd='+Math.random(), 'attsDlg', '450px', '160px');
	ChangeFullDiv('show', screeheight);
}

function delArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?qstr="+qstr+"&aid="+aid+"&dopost=delArchives";
}

function QuickEdit(aid, e, obj)
{
	LoadQuickDiv(e, 'archives_do.php?dopost=quickEdit&aid='+aid+'&rnd='+Math.random(), 'quickEdit', '450px', '300px');
	ChangeFullDiv('show');
}
//上下文菜单
function ShowMenu(evt,obj,aid,atitle)
{
  var popupoptions
  popupoptions = [
    new ContextItem("浏览文档",function(){ viewArc(aid); }),
		new ContextItem("编辑属性",function(){ QuickEdit(aid, evt, obj); }),
    new ContextItem("编辑文档",function(){ editArc(aid); }),
    new ContextSeperator(),
    new ContextItem("更新HTML",function(){ updateArc(aid); }),
    new ContextItem("审核文档",function(){ checkArc(aid); }),
    new ContextItem("推荐文档",function(){ adArc(aid); }),
    new ContextItem("删除文档",function(){ delArc(aid); }),
    new ContextSeperator(),
    new ContextItem("复制(<u>C</u>)",function(){ copyToClipboard(atitle); }),
    new ContextItem("重载页面",function(){ location.reload(); }),
    new ContextSeperator(),
    new ContextItem("全部选择",function(){ selAll(); }),
    new ContextItem("取消选择",function(){ noSelAll(); }),
    new ContextSeperator(),
    new ContextItem("关闭菜单",function(){})
  ]
  ContextMenu.display(evt,popupoptions);
  //location="catalog_main.php";
}
//获得选中文件的文件名
function getCheckboxItem()
{
	var allSel="";
	if(document.form2.arcID.value) return document.form2.arcID.value;
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
			if(allSel=="")
				allSel=document.form2.arcID[i].value;
			else
				allSel=allSel+"`"+document.form2.arcID[i].value;
		}
	}
	return allSel;
}

//获得选中其中一个的id
function getOneItem()
{
	var allSel="";
	if(document.form2.arcID.value) return document.form2.arcID.value;
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
				allSel = document.form2.arcID[i].value;
				break;
		}
	}
	return allSel;
}

function selAll()
{
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(!document.form2.arcID[i].checked)
		{
			document.form2.arcID[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
			document.form2.arcID[i].checked=false;
		}
	}
}
-->