
function lTrim(str) 
  {if (str.charAt(0) == " ") 
   {
    
     str = str.slice(1);
     str = lTrim(str);
      } 
  return str; 
  } 

function rTrim(str) 
{var iLength; 
  iLength = str.length; 
  
if(str.charAt(iLength - 1)==" ") 
{str = str.slice(0, iLength - 1);
 str = rTrim(str);
} 
return str; 
}

function trim(str) 
{str=lTrim(rTrim(str));
return str; 


}


function isValidEmail(email)
{
var result=email.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/);
if(result==null) return false;
return true;
}

 function isDate(str)
{
 
 var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/); 

if(r==null)
 {
  return false;
 } 
var d= new Date(r[1], r[3]-1, r[4]); 
if(d.getMonth()==0)
     return true;
else	 
{if(!(d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]))
 {return false;
 }
 else
 {return true;}
 }
}
function mOvr(src,clrOver) { if (!src.contains(event.fromElement)){ src.style.cursor = 'hand'; src.bgColor = clrOver; }}
function mOut(src,clrIn){ if (!src.contains(event.toElement)) { src.style.cursor = 'hand'; src.bgColor = clrIn; }}
function mClk(src) { if(event.srcElement.tagName=='TD'){src.children.tags('A')[0].click();} }

function values(n)
{ var str="" 
 ,i=1;
 for(i=1;i<=n;i++)
  { if(eval("checkbox"+i).checked)
     if(str=="")
	 str=eval("checkbox"+i).value;
	 else
	 str=str+","+eval("checkbox"+i).value; 
  }
return str;
}

function values(objname,n)
{ var str="" 
 ,i=1;
 for(i=1;i<=n;i++)
  { if(eval(objname+i).checked)
     if(str=="")
	 str=eval(objname+i).value;
	 else
	 str=str+","+eval(objname+i).value; 
  }
return str;
}
function formCheck() 
    {if(/[^0-9]/g.test(document.theform.parampage.value))
		  {alert("请输入数字!");
		   document.theform.parampage.focus();
		   document.theform.parampage.select();
		   return false;
		   }
		  else 
		  return true;
    }
function init() 
{document.onkeydown=keyDown; 
//document.oncontextmenu = function() { return false;} 
} 
function keyDown(e) {  
if((event.keyCode==13) &&(event.srcElement.type != 'textarea')&&(event.srcElement.type != 'submit'))
  {event.keyCode=9 ;}
  else if(event.keyCode==34)
   {if(theform.b_yisubmit!=null)
	theform.b_yisubmit.click();}
	else if(event.keyCode==8)
	{if(event.srcElement.readOnly==true)
	event.keyCode=0;
	}
}

function out_str(mystr)
{ var str='';
  str=trim(mystr);
  while(str.indexOf("'")>=0)
   {str=str.replace("'","");
   }
  while(str.indexOf("\r")>=0)
   {str=str.replace("\r","");
   }
  while(str.indexOf("\n")>=0)
   {str=str.replace("\n","");
   }

   
   return str;
 }
function allreplace_str(mystr,old_str,new_str)
{ var str='';
  str=trim(mystr);
  while(str.indexOf(old_str)>=0)
   {str=str.replace(old_str,new_str);
   }
   return str;
 }

function page_updown(my_flag,url)
{var i=document.theform.parampage.selectedIndex;
if(my_flag==0)
  {if(document.theform.parampage!=null)
  	document.theform.parampage.selectedIndex=i-1;
   document.theform.action=url;
   document.theform.method="post";
   document.theform.submit();
   }
  if(my_flag==1)
  {if(document.theform.parampage!=null)
  	document.theform.parampage.selectedIndex=i+1;
   document.theform.action=url;
   document.theform.method="post";
   document.theform.submit();
   }
 }

 function row_select(n)
 {eval("window.checkbox"+n).checked=!(eval("window.checkbox"+n).checked);
 }

function selectall(form)  
 {
  for (var i=0;i<form.elements.length;i++) 
   { var e = form.elements[i];
     if((e.name != 'allcheck')&&(e.type=="checkbox"))    
        {e.checked = form.allcheck.checked; 
		}
   }
 }

//=====取checkbox中的值======
function fetch_checkvalue(form)  
 {var s="";
  for (var i=0;i<form.elements.length;i++) 
   { var e = form.elements[i];
     if ((e.name != 'allcheck')&&(e.type=="checkbox"))    
        if(e.checked)
		   {if(s=="")
		    s=e.value;
			else
			s=s+","+e.value;
		   }
    }
 return s;  
 }
//=====通过转换取checkbox中的值======
function fetch_allvalue(form)  
 {var s="";
  for (var i=0;i<form.elements.length;i++) 
   { var e = form.elements[i];
      if ((e.name != 'allcheck')&&(e.type=="checkbox"))    
          {if(s=="")
		    {if(e.checked)
			s=e.name+"-1";
			else
			s=e.name+"-0";
			}
			else
			{if(e.checked)
			s=s+","+e.name+"-1";
			else
			s=s+","+e.name+"-0";
			}
		 }
    }
 return s;  
 } 
//===========
function fetch_qxlist_value(form)  
 {var s="";
  var pre_str="";
  for (var i=0;i<form.elements.length;i++) 
   { var e = form.elements[i];
     var str=e.name.substr(0,e.name.indexOf('_'));
      if ((e.name != 'allcheck')&&(e.type=="checkbox"))    
          {if(s=="")
		    {if(e.checked)
			s=str+"-1";
			else
			s=str+"-0";
			}
			else
			{if(pre_str==str)
				{if(e.checked)
			      s=s+"-1";
			    else
			    s=s+"-0";
				}
			  else
			  {if(e.checked)
			   s=s+","+str+"-1";
			   else
			   s=s+","+str+"-0";
			   }
			}
		 }
	pre_str=e.name.substr(0,e.name.indexOf('_'));
	
    }
	
 return s;  
 } 
//=====全部选中========
function selectall() {  
 for(var i=0;i<document.all.length;i++)
    {var mycheck=document.all(i);
      if((mycheck.type=='checkbox')&&(mycheck.name!='allcheck'))
        mycheck.checked=document.all("allcheck").checked;}
   }

//======给select赋值======
function select_value(sel_obj,str)
{ for(var i=0;i<sel_obj.length;i++)
    {if(sel_obj.options[i].value==str)
          sel_obj.selectedIndex=i;
     }
 
}
//=====控制dis_myfind显示状态====
function dis_myfind()
{if(myfind.style.visibility=="visible")
   {myfind.style.visibility = "hidden";	
    myfind.style.display = "none";
   }
 else
 {myfind.style.visibility = "visible";	
  myfind.style.display = "block";
 }
  }
//====只能输入数字
function onlyNum() 
{ 
 if ( !(((event.keyCode >= 48)&&(event.keyCode <=57))||(event.keyCode==13)||(event.keyCode==46)||(event.keyCode==45))) 
 { 
  event.keyCode =0 ; 
}
}
function show_ggsearch(tablename)
{
 var winWidth=450;
 var winHeight=300;
 var left=screen.availWidth/2; 
 var top=screen.availHeight/2-100;
 window.open("../dagl/ggfind.jsp?tablename="+tablename,"childWindow","toolbar=no,width="+ winWidth  +",height="+ winHeight  +",top="+top+",left="+left+",scrollbars=yes,resizable=no,center:yes,statusbars=yes"); 
}
function ywupload(tablename,fid,flag)
{var winWidth=400 ; 
 var winHeight=screen.availHeight/2;
 window.open('../sys/ywck.jsp?tablename='+tablename+'&fid='+fid+'&flag='+flag+'&button_disflag=1',"tuwindow","toolbar=no,width="+ winWidth  +",height="+ winHeight  +",top=150,left=150,scrollbars=yes,resizable=no,center:yes,statusbars=yes"); 
}  

function view_yw(tablename,fid,dh,flag)
{
 /*window.open("../jg_dagl/ld_chakan_view.jsp?tablename="+tablename+"&fid="+fid+"&dh="+dh+"&flag="+flag,"tuwindow","toolbar=no,width="+ winWidth  +",height="+ winHeight  +",top=150,left=150,scrollbars=yes,resizable=yes,center:yes,statusbars=yes"); 
 */
 callDialog("../jg_dagl/ld_chakan_view.jsp?tablename="+tablename+"&fid="+fid+"&dh="+dh+"&flag="+flag,0);
}


function callDialog(url,flag,width,height) {
 if(width==null)
   width=780;
    if(height==null)
    height=650;
var sFeatures="dialogWidth:"+width+"px;dialogHeight:"+height+"px;status:0;center:1;help:0;edge:sunken;resizable:yes";
var return_value;
if(flag==0)
 return_value=showModalDialog(url,"",sFeatures);
 else
 return_value=showModelessDialog(url,"",sFeatures);
 if(return_value==null||return_value=="undefined")
 return_value="";
 return return_value;
}

function openck(url)
{//var winWidth=screen.availWidth/-100 ; 
  winWidth=800;
  winHeight=500;
  
 var newwin=window.open(url,"ckwindow","toolbar=no,width="+ winWidth  +",height="+ winHeight  +",top=150,left=150,scrollbars=yes,resizable=no,center:yes,statusbars=yes"); 
 return newwin;
}



//====my_flag 0:表示加序号,1:表示不加序号第一个是顺序号
function insert_row_new(obj_table,my_flag)
{var trs = obj_table.getElementsByTagName("tr");
	var sTr =trs[1];
  	var tr =sTr.cloneNode(true);
	tr.style.display="block";
    // curtr.cells[0].innerHTML="<input type='text' name='xh' size=3 value='"+curRowIndex+"' readonly>";
   	obj_table.firstChild.appendChild(tr);
   	if(my_flag=="0")
   	tr.cells[0].innerHTML=trs.length-2;
  var inputs=tr.getElementsByTagName("input");
   for(var i=0;i<inputs.length;i++)
    {if(my_flag=="1")
     inputs[0].value=trs.length-2;
    if(inputs[i].name=="id")
    inputs[i].value="0";
    }

var colnums=obj_table.cells.length/obj_table.rows.length;
tr.cells[colnums-1].innerHTML="<a href='javascript:delete_row_new("+obj_table.id+","+tr.rowIndex+","+my_flag+")'>删除</a>";
} 

//====my_flag 0:表示加序号,1:表示不加序号第一个是顺序号
function delete_row_new(obj_table,rowIndex,my_flag)
{if(rowIndex==0)
	obj_table.deleteRow();
	else
	obj_table.deleteRow(rowIndex);
  var colnums=obj_table.cells.length/obj_table.rows.length;
  for(var i=2;i<obj_table.rows.length;i++)
	{if(my_flag=="0")
	obj_table.rows[i].cells[0].innerText=i-1;
	else if(my_flag=="1")
	obj_table.rows[i].cells[0].children(0).value=i-1;
	
	 var tr_input=obj_table.rows[i].getElementsByTagName("input");
	 for(var j=0;j<tr_input.length;j++){
	 	if(tr_input[j].name=='id')
	 	 {myid=tr_input[j].value;
	 	 break;
	 	 }
	  }
   if(myid=="0")
	obj_table.rows[i].cells[colnums-1].innerHTML="<a href='javascript:delete_row_new("+obj_table.id+","+i+","+my_flag+")'>删除</a>";
  }
		
}
//====给明细表中的日期强制赋付值====
function fz_date(obj_table,rowIndex,date_name,fgf)
{var trs = obj_table.getElementsByTagName("tr");
	var sTr =trs[rowIndex];
   // curtr.cells[0].innerHTML="<input type='text' name='xh' size=3 value='"+curRowIndex+"' readonly>";
    var inputs=sTr.getElementsByTagName("input");
   for(var i=0;i<inputs.length;i++)
    {if(inputs[i].name==date_name)
      inputs[i].value=getdate(inputs[i],fgf);
     // break;
    } 
 } 
//====给明细表中列表项赋付值====
function fz_mx(obj_table,rowIndex,myname,myvalue)
{var trs = obj_table.getElementsByTagName("tr");
 var return_str="";
 var sTr =trs[rowIndex];
  // curtr.cells[0].innerHTML="<input type='text' name='xh' size=3 value='"+curRowIndex+"' readonly>";
    var inputs=sTr.getElementsByTagName("input");
   for(var i=0;i<inputs.length;i++)
    {if(inputs[i].name==myname)
      {if(myvalue!=null)
      	inputs[i].value=myvalue;
      	return_str=trim(inputs[i].value);
       break;
      }
    }
 return return_str;
 } 


function getdate(obj,gs)
{today=new Date();
var str=today.getYear()+gs+((today.getMonth()+101)+'').substring(1,3)+gs+today.getDate();
if(obj!=null)
obj.value=str;
return str;
}
//===============
function FormatNumber(srcStr,nAfterDot){ 
   var srcStr,nAfterDot; 
   var resultStr,nTen; 
    srcStr = ""+srcStr+""; 
    strLen = srcStr.length; 
    dotPos = srcStr.indexOf(".",0); 
    if (dotPos == -1){ 
       resultStr = srcStr+"."; 
          for (i=0;i<nAfterDot;i++){ 
           resultStr = resultStr+"0"; 
           } 
       return resultStr; 
      } 
    else{ 
       if ((strLen - dotPos - 1) >= nAfterDot){ 
        nAfter = dotPos + nAfterDot + 1; 
        nTen =1; 
        for(j=0;j<nAfterDot;j++){ 
        nTen = nTen*10; 
       } 
      resultStr = Math.round(parseFloat(srcStr)*nTen)/nTen; 
      return resultStr; 
      } 
    else{ 
      resultStr = srcStr; 
    for (i=0;i<(nAfterDot - strLen + dotPos + 1);i++){ 
     resultStr = resultStr+"0"; 
     } 
     return resultStr; 
     } 
   } 
}

//========取tr中文本框的值=======
function fetch_value_intr(obj_table,rowIndex) {
	var trs = obj_table.getElementsByTagName("tr");
    var tr =trs[rowIndex];
    var tinput=tr.getElementsByTagName("input");
    for(var i=0;i<tinput.length;i++)
    if(tinput[i].name=="sxh")
	alert("tr:==="+tinput[i].value);
	//tr.getElementById("sxh").value="1111";
	//tr.getElementById("jh_fkrq").value="2222";

}
//======跳转=====
function do_tz(form)
{form.submit();
}
//=====清空表单中的值========
function do_clear(form)
{for(var j=0;j<form.elements.length;j++)
  {var obj=form.elements[j];
   if(obj.type=="text")
   obj.value="";
  }
}

//=====rz_search=====
function rz_search()
{ var str1="",str="";

    str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and rq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and rq <='"+str+"' ";


   str=out_str(theform.yhid.value);
   if(str!="")
   str1=str1+" and yhid like '%"+str+"%' ";
   
   str=out_str(theform.ip.value);
   if(str!="")
   str1=str1+" and ip like '%"+str+"%' ";

   str=out_str(theform.mk.value);
   if(str!="")
   str1=str1+" and mk like '%"+str+"%' ";
   
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=====yh_search=====
function yh_search()
{ var str1="",str="";
  
  str=out_str(theform.yhid.value);
  if(str!="")
   str1=str1+" and yhid like '%"+str+"%' ";

   str=out_str(theform.yhmc.value);
   if(str!="")
   str1=str1+" and yhmc like '%"+str+"%' ";

   str=out_str(theform.lxdh.value);
   if(str!="")
   str1=str1+" and lxdh like '%"+str+"%' ";

   str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and zcrq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and zcrq <='"+str+"' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;

}

//===qz_search
function qz_search()
{ var str1="",str="";

   str=out_str(theform.zw.value);
   if(str!="")
   str1=str1+" and zw like '%"+str+"%' ";

   str=out_str(theform.xl.value);
   if(str!="")
   str1=str1+" and xl = '"+str+"' ";
   
   str=out_str(theform.xm.value);
   if(str!="")
   str1=str1+" and xm like '%"+str+"%' ";

   str=out_str(theform.byyx.value);
   if(str!="")
   str1=str1+" and byyx like '%"+str+"%' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=====zp_search=====
function zp_search()
{ var str1="",str="";

   str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and fbrq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and fbrq <='"+str+"' ";

   str=out_str(theform.gzdd.value);
   if(str!="")
   str1=str1+" and gzdd like '%"+str+"%' ";
   
   str=out_str(theform.zw.value);
   if(str!="")
   str1=str1+" and zw like '%"+str+"%' ";

   str=out_str(theform.bm.value);
   if(str!="")
   str1=str1+" and bm like '%"+str+"%' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=========xx_search============
function xx_search()
{ var str1="",str="";

   str=out_str(theform.bt.value);
   if(str!="")
   str1=str1+" and bt like '%"+str+"%' ";

   str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and fbrq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and fbrq <='"+str+"' ";

   str=out_str(theform.isfb.value);
   if(str!="")
   str1=str1+" and isfb = '"+str+"' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=========qwjs_search============
function qwjs_search()
{ var str1="",str="";

   str=out_str(theform.qxid.value);
   if(str!="")
   str1=str1+" and qxid = '"+str+"' ";

   str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and fbrq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and fbrq <='"+str+"' ";
   
   str=out_str(theform.bt.value);
   if(str!="")
   str1=str1+" and bt like '%"+str+"%' ";
   
   str=out_str(theform.isfb.value);
   if(str!="")
   str1=str1+" and isfb = '"+str+"' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=======dgzb_search============
function dgzb_search()
{ var str1="",str="";

   str=out_str(theform.ddh.value);
   if(str!="")
   str1=str1+" and ddh like '%"+str+"%'";

   str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and ddrq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and ddrq <='"+str+"' ";
   
   str=out_str(theform.xm.value);
   if(str!="")
   str1=str1+" and xm = '"+str+"' ";
   
   str=out_str(theform.dgzt.value);
   if(str!="")
   str1=str1+" and dgzt = '"+str+"' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=======wp_search===============
function wp_search()
{ var str1="",str="";

   str=out_str(theform.wpid.value);
   if(str!="")
   str1=str1+" and wpid like '%"+str+"%'";

   str=out_str(theform.startdate.value);
   if(str!="")
   str1=str1+" and rq >= '"+str+"' ";
   
   str=out_str(theform.enddate.value);
   if(str!="")
   str1=str1+" and rq <='"+str+"' ";
   
   str=out_str(theform.wpmc.value);
   if(str!="")
   str1=str1+" and wpmc like '%"+str+"%' ";
   
   str=out_str(theform.isfb.value);
   if(str!="")
   str1=str1+" and isfb = '"+str+"' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//======lltj_search====
function lltj_search()
{ var str1="",str="";

   str=out_str(theform.yhid.value);
   if(str!="")
   str1=str1+" and yhid like '%"+str+"%'";

   if(theform.startdate!=null&&theform.enddate!=null)
    {str=out_str(theform.startdate.value);
     if(str!="")
     str1=str1+" and rq >= '"+str+"' ";
     
     str=out_str(theform.enddate.value);
     if(str!="")
     str1=str1+" and rq <='"+str+"' ";
   }
   str=out_str(theform.qxmk.value);
   if(str!="")
   str1=str1+" and qxid like '%"+str+"%' ";
  
   if(theform.start_sj!=null&&theform.end_sj!=null)
    {str=out_str(theform.start_sj.value);
     if(str!="")
     str1=str1+" and TIME(rq) >= '"+str+"' ";
     
     str=out_str(theform.end_sj.value);
     if(str!="")
     str1=str1+" and TIME(rq) <='"+str+"' ";
   }
   
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;

}
//==========dczt_search=========
function dczt_search()
{ var str1="",str="";
   str=out_str(theform.ztmc.value);
   if(str!="")
   str1=str1+" and ztmc like '%"+str+"%'";

   str=out_str(theform.isfb.value);
   if(str!="")
   str1=str1+" and isfb ='"+str+"' ";
   
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=========dctm_search==========
function dctm_search()
{ var str1="",str="";
   str=out_str(theform.tmmc.value);
   if(str!="")
   str1=str1+" and tmmc like '%"+str+"%'";

   str=out_str(theform.isfb.value);
   if(str!="")
   str1=str1+" and isfb ='"+str+"' ";
   
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//========dcxx_search===========
function dcxx_search()
{ var str1="",str="";
   str=out_str(theform.xxmc.value);
   if(str!="")
   str1=str1+" and xxmc like '%"+str+"%'";
  
   str=out_str(theform.tps.value);
   if(str!="")
   str1=str1+" and tps ='"+str+"' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//=======cp_search========
function cp_search()
{ var str1="",str="";
   str=out_str(theform.lbid.value);
   if(str!="")
   str1=str1+" and lbid ='"+str+"'";
  
   str=out_str(theform.cpxh.value);
   if(str!="")
   str1=str1+" and cpxh like '%"+str+"%' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//======cplb_search======
function cplb_search()
{ var str1="",str="";
   str=out_str(theform.lbdm.value);
   if(str!="")
   str1=str1+" and lbdm ='"+str+"'";
  
   str=out_str(theform.lbmc.value);
   if(str!="")
   str1=str1+" and lbmc like '%"+str+"%' ";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//======pic_zl_search======
function pic_zl_search()
{ var str1="",str="";
   str=out_str(theform.wjmc.value);
   if(str!="")
   str1=str1+" and wjmc like '%"+str+"%'";
   
   str=out_str(theform.lbid.value);
   if(str!="")
   str1=str1+" and lbid ='"+str+"'";
   
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//======pd_search======
function pd_search()
{ var str1="",str="";
   str=out_str(theform.qxm.value);
   if(str!="")
   str1=str1+" and qxm like '%"+str+"%'";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
}
//======model_search======
function model_search()
{ var str1="",str="";
   str=out_str(theform.modelmc.value);
  if(str!="")
   str1=str1+" and modelmc = '"+str+"'";
   document.theform.parampage.selectedIndex=0;
   theform.findSQL.value=str1;
 
}
