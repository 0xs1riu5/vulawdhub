<!--
if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}

function delArc(mid){
	var qstr=getCheckboxItem();
	if(mid==0) mid = getOneItem();
	location="member_do.php?id="+qstr+"&dopost=delmembers";
}

//获得选中文件的文件名
function getCheckboxItem()
{
	var allSel="";
	if(document.form2.mid.value) return document.form2.mid.value;
	for(i=0;i<document.form2.mid.length;i++)
	{
		if(document.form2.mid[i].checked)
		{
			if(allSel=="")
				allSel=document.form2.mid[i].value;
			else
				allSel=allSel+"`"+document.form2.mid[i].value;
		}
	}
	return allSel;
}

//获得选中其中一个的id
function getOneItem()
{
	var allSel="";
	if(document.form2.mid.value) return document.form2.mid.value;
	for(i=0;i<document.form2.mid.length;i++)
	{
		if(document.form2.mid[i].checked)
		{
				allSel = document.form2.mid[i].value;
				break;
		}
	}
	return allSel;
}

function selAll()
{
	for(i=0;i<document.form2.mid.length;i++)
	{
		if(!document.form2.mid[i].checked)
		{
			document.form2.mid[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.mid.length;i++)
	{
		if(document.form2.mid[i].checked)
		{
			document.form2.mid[i].checked=false;
		}
	}
}
-->
