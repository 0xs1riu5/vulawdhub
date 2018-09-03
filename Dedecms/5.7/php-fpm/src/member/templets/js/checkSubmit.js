function checkSubmit()
{

	if(document.addcontent.title.value==""){
		alert("名称不能为空！");
		document.addcontent.title.focus();
		return false;
	}

	if(document.addcontent.typeid.value==0){
		alert("隶属栏目必须选择！");
		return false;
	}

	if(document.addcontent.typeid.options && document.addcontent.typeid.options[document.addcontent.typeid.selectedIndex].className!='option3')
	{
		alert("隶属栏目必须选择白色背景的项目！");
		return false;
	}

	if(document.addcontent.vdcode.value==""){
		document.addcontent.vdcode.focus();
		alert("验证码不能为空！");
		return false;
	}

}