<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cfg_soft_lang; ?>">
<title>专题管理</title>
<link rel="stylesheet" type="text/css" href="css/base.css">
<script language="javascript" src="js/context_menu.js"></script>
<script language="javascript" src="js/ieemu.js"></script>
<script language="javascript">
if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}
function viewArc(aid){
	if(aid==0) aid = getOneItem();
	window.open("archives_do.php?aid="+aid+"&dopost=viewArchives");
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
function adArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=commendArchives&qstr="+qstr;
}
function delArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?qstr="+qstr+"&aid="+aid+"&dopost=delArchives";
}
//上下文菜单
function ShowMenu(evt,obj,aid,atitle)
{
  var eobj,popupoptions
  popupoptions = [
    new ContextItem("浏览文档",function(){ viewArc(aid); }),
    new ContextItem("编辑文档",function(){ editArc(aid); }),
    new ContextSeperator(),
    new ContextItem("更新HTML",function(){ updateArc(aid); }),
    new ContextItem("审核文档",function(){ checkArc(aid); }),
    new ContextItem("推荐文档",function(){ adArc(aid); }),
    new ContextSeperator(),
    new ContextItem("删除文档",function(){ delArc(aid); }),
    new ContextSeperator(),
    new ContextItem("全部选择",function(){ selAll(); }),
    new ContextItem("取消选择",function(){ noSelAll(); }),
    new ContextSeperator(),
    new ContextItem("关闭菜单",function(){})
  ]
  ContextMenu.display(evt,popupoptions)
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
</script>
</head>
<body leftmargin="8" topmargin="8" background='images/allbg.gif' onLoad="ContextMenu.intializeContextMenu()">
<table width='98%' border='0' align='center' cellpadding='0' cellspacing='0'>
<tr> 
<td align='center' valign='top'>
	<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#D6D6D6">
		<tr> 
    <td height="26" background="images/newlinebg3.gif">
    <table width="98%" border="0" cellspacing="0" cellpadding="0">
     <tr> 
     <td align="center">
   <input type='button' class="coolbg np" onClick="location='makehtml_spec.php';" value='更新专题HTML' />
   <input type='button' class="coolbg np" onClick="location='spec_add.php';" value='添加专题' />
   <?php echo $CheckUserSend?> 
   <input type='button' class="coolbg np" onClick="location='catalog_main.php';" value='频道管理' />
       </td>
     </tr>
    </table>
    </td>
    </tr>
  </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr bgcolor="#FFFFFF"><td height="4"></td></tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#cfcfcf">
<tr bgcolor="#E7E7E7"> 
<td height="24" colspan="9" background="images/tbg.gif" style="padding-left:10px;">
	<strong>文章列表</strong>
	(在文章标题处使用鼠标右键进行常用操作)
	</td>
</tr>
<form name="form2">
<tr align="center" bgcolor="#FBFCE2" height="25"> 
<td width="6%">ID</td>
<td width="4%">选择</td>
<td width="30%">文章标题</td>
<td width="12%">更新时间</td>
<td width="11%">类目</td>
<td width="8%">点击</td>
<td width="7%">HTML</td>
<td width="9%">权限</td>
<td width="12%">属性</td>
</tr>
<?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
<tr align='center' bgcolor="#FFFFFF"  onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" height="25" oncontextmenu="ShowMenu(event,this,<?php echo $fields['id']; ?>,'<?php echo urlencode($fields['title'])?>')"> 
<td><?php echo $fields['id']; ?></td>
<td><input name="arcID" type="checkbox" id="arcID" value="<?php echo $fields['id']; ?>" class="np"></td>
<td align='left'>
<a href='archives_do.php?aid=<?php echo $fields['id']; ?>&dopost=editArchives' oncontextmenu="ShowMenu(event,this,<?php echo $fields['id']; ?>,'<?php echo urlencode($fields['title'])?>')">
	<u><?php echo $fields['title']; ?></u>
</a>
</td>
<td><?php echo MyDate("Y-m-d H:i",$fields['senddate'])?></td>
<td><?php echo GetTypename($fields['typeid']); ?></td>
<td><?php echo $fields['click']; ?></td>
<td><?php echo IsHtmlArchives($fields['ismake'])?></td>
<td><?php echo GetRankName($fields['arcrank'])?></td>
<td><?php echo IsCommendArchives($fields['flag'])?></td>
</tr>
<?php
}
}?>
<tr bgcolor="#ffffff"> 
<td height="24" colspan="9">
&nbsp;
<a href="javascript:selAll()" class="coolbg">全选</a> 
<a href="javascript:noSelAll()" class="coolbg">取消</a> 
<a href="javascript:viewArc(0)" class="coolbg">&nbsp;查看&nbsp;</a> 
<a href="javascript:editArc(0)" class="coolbg">&nbsp;更改&nbsp;</a> 
<a href="javascript:updateArc(0)" class="coolbg">&nbsp;更新&nbsp;</a> 
<a href="javascript:checkArc(0)" class="coolbg">&nbsp;审核&nbsp;</a> 
<a href="javascript:adArc(0)" class="coolbg">&nbsp;推荐&nbsp;</a> 
<a href="javascript:delArc(0)" class="coolbg">&nbsp;删除&nbsp;</a> 
</td>
</tr>
</form>
<tr align="right" bgcolor="#F9FCEF"> 
<td height="20" colspan="9" align="center">
	<?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>

</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="4"></td>
</tr>
<tr bgcolor="#FFFFFF"> 
<td height="28">
<table width='100%'  border='0' cellpadding='1' cellspacing='1' bgcolor='#cfcfcf'>
   <tr bgcolor='#EEF4EA'> 
          <form name='form3' action='content_s_list.php' method='get'>
          <input type='hidden' name='dopost' value='listArchives'>
          <td background='images/wbg.gif'>
          	<table width='600' border='0' cellpadding='0' cellspacing='0'>
          	<tr>
          	<td width='90' align='center'>请选择类目：</td>
          	<td width='160'>
          <select name='cid' style='width:150'>
          <option value='0'>选择分类...</option>
          	<?php echo $optionarr?>
          </select>
        </td>
        <td width='70'>
          关键字：
        </td>
          <td width='160'>
          	<input type='text' name='keyword' value='<?php echo $keyword?>' style='width:150'>
          </td>
          <td>
          <input name="imageField" type="image" src="images/button_search.gif" width="60" height="22" border="0" class="np">
         </td>
        </tr>
        </table>
          </td>
        </form>
        </tr>
      </table>
      
</td>
</tr>
<tr> 
<td colspan="2" height="4"></td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>