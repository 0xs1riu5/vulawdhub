<!--

function ShowAddCatalog(){
	$Obj('addCatalog').style.display='block';
}

function CloseAddCatalog(){
	$Obj('addCatalog').style.display='none';
}

function CloseEditCatalog(){
	$Obj('editCatalog').style.display='none';
}

function DelCatalog(cid){
	if(window.confirm("你确实要删除这个分类么？"))
	{
		location.href='story_catalog.php?catid='+cid+'&action=del';
	}
}

function DelStory(bid){
	if(window.confirm("你确实要删除这本图书么？")){
		location.href='story_do.php?bid='+bid+'&action=delbook';
	}
}

function DelStoryContent(cid){
	if(window.confirm("删除内容后章节的其它内容排列序号不会发生变化，\r\n这可能导致管理混乱，你确实要删除这篇内容么？")){
		location.href='story_do.php?cid='+cid+'&action=delcontent';
	}
}

function CloseLayer(layerid){
	$Obj(layerid).style.display='none';
}

//预览内容
function PreViewCt(cid,booktype){
	if(booktype==0){
		window.open("../book/story.php?id="+cid);
	}else{
		window.open("../book/show-photo.php?id="+cid);
	}
}

//编辑栏目
function EditCatalog(cid){
	$Obj('editCatalog').style.display='block';
	var myajax = new DedeAjax($Obj('editCatalogBody'),false,true,"","","请稍候，正在载入...");
	myajax.SendGet2('story_catalog.php?catid='+cid+'&action=editload');
	DedeXHTTP = null;
}

//图书章节，反向选择
function ReSelChapter(){
	var ems = document.getElementsByName('ids[]');
	for(var i=0;i<ems.length;i++){
		if(!ems[i].checked) ems[i].checked = true;
		else ems[i].checked = false;
	}
}

//删除整章节图书内容
function DelStoryChapter(cid){
	if(window.confirm("删除章节会删除章节下的所有内容，你确实要删除么？")){
		location.href='story_do.php?cid='+cid+'&action=delChapter';
	}
}

//增加图书的检查
function checkSubmitAdd()
{
	if(document.form1.catid.value==0){
		alert("请选择连载内容的栏目！");
		document.form1.bookname.focus();
		return false;
	}
	if(document.form1.bookname.value==""){
		alert("连载图书名称不能为空！");
		document.form1.bookname.focus();
		return false;
	}
}

//增加小说内容的检查
function checkSubmitAddCt()
{
	if(document.form1.title.value==0){
		alert("文章标题不能为空！");
		document.form1.title.focus();
		return false;
	}
	if(document.form1.chapterid.selectedIndex==-1 && document.form1.chapternew.value==''){
		alert("文章所属章节和新章节名称不能同时为空！");
		return false;
	}
}

//增加漫画内容的检查
function checkSubmitAddPhoto()
{
	if(document.form1.chapterid.selectedIndex==-1 && document.form1.chapternew.value==''){
		alert("文章所属章节和新章节名称不能同时为空！");
		return false;
	}
	document.form1.photonum.value = endNum;
}

//显示选择框与新增章节选项
function ShowHideSelChapter(selfield,newfield)
{
	if(document.form1.addchapter.checked){
		$Obj(selfield).style.display = 'none';
		$Obj(newfield).style.display = 'block';
	}else{
		$Obj(selfield).style.display = 'block';
		$Obj(newfield).style.display = 'none';
	}
}

function selAll()
{
	for(i=0;i<document.form2.ids.length;i++)
	{
		if(!document.form2.ids[i].checked){
			document.form2.ids[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.ids.length;i++)
	{
		if(document.form2.ids[i].checked){
			document.form2.ids[i].checked=false;
		}
	}
}
//获得选中文件的文件名
function getCheckboxItem()
{
	var allSel="";

	if(document.form2.ids.value) return document.form2.ids.value;
	for(i=0;i<document.form2.ids.length;i++)
	{
		if(document.form2.ids[i].checked){
			allSel += (allSel=='' ? document.form2.ids[i].value : ","+document.form2.ids[i].value);
		}
	}
	return allSel;
}
//删除多选
function DelAllBooks()
{
	if(window.confirm("你确实要删除这些图书么？")){
		var selbook = getCheckboxItem();
		location.href='story_do.php?bid='+selbook+'&action=delbook';
	}
}

-->