<!--
function selAll()
{
	var celements = document.getElementsByName('aids[]');
	for(i=0;i<celements.length;i++)
	{
		if(!celements[i].checked) celements[i].checked = true;
		else celements[i].checked = false;
	}
}

function noselAll()
{
	var celements = document.getElementsByName('aids[]');
	for(i=0;i<celements.length;i++)
	{
		if(celements[i].checked = true) 
		{
			celements[i].checked = false;
		}
	}
}

function delkey()
	{
		if(window.confirm("你确实要删除选定的关键字么？"))
		{
			document.form3.dopost.value = 'del';
			document.form3.submit();
		}
	}
	
function diskey()
	{
		if(window.confirm("你确实要禁用选定的关键字么？"))
		{
			document.form3.dopost.value = 'dis';
			document.form3.submit();
		}
	}
	
function enakey()
	{
		if(window.confirm("你确实要启用选定的关键字么？"))
		{
			document.form3.dopost.value = 'ena';
			document.form3.submit();
		}
	}
	
function urlkey()
	{
		if(window.confirm("你确实要更新选定的关键字的网址么？"))
		{
			document.form3.dopost.value = 'url';
			document.form3.submit();
		}
	}
	
function rankey()
	{
		if(window.confirm("你确实要改变选定的关键字的频率么？"))
		{
			document.form3.dopost.value = 'ran';
			document.form3.submit();
		}
	}
<!--批量删除搜多关键字-->
function delall()
	{
		if(window.confirm("你确实要删除选定的关键字么？"))
		{
			document.form3.dopost.value = 'delall';
			document.form3.submit();
		}
	}
	

-->