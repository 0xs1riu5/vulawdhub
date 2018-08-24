<?php
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<?php
$go=0;
$action=isset($_GET["action"])?$_GET["action"]:'';
$id=isset($_REQUEST["id"])?$_REQUEST["id"]:0;
checkid($id,1);

if ($action=="savedata" ){
checkadminisdo("bottomlink");
	
	if ($saveas=="add"){
	query("insert into zzcms_about (title,content)VALUES('$title','$content') ");
	$go=1;
	//echo "<script>location.href='about_manage.php'<//script>";	
	}elseif ($saveas=="modify"){
	query("update zzcms_about set title='$title',content='$content',link='$link' where id='". $id."' ");
	$go=1;
	//echo "<script>location.href='about_manage.php'<//script>";
	}
}
?>
<script type="text/javascript" src="/3/ckeditor/ckeditor.js"></script>
<script language="JavaScript">
function CheckForm(){
if (document.myform.title.value=="")
  {
    alert("标题不能为空！");
	document.myform.title.focus();
	return false;
  }
} 
</script>
</head>
<body>
<?php 
if ($action=="add") {
?>
<div class="admintitle">添加公司信息</div>
<form action="?action=savedata&saveas=add" method="POST" name="myform" id="myform" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="23%" align="right" class="border">名称</td>
      <td width="77%" class="border"><input name="title" type="text" id="title"></td>
    </tr>
    <tr> 
      <td align="right" class="border">内容</td>
      <td class="border"> <textarea name="content" id="content" ></textarea> 
       	<script type="text/javascript">CKEDITOR.replace('content');	</script>
      </td>
    </tr>
    <tr> 
      <td align="right" class="border"><input name="link" type="hidden" value=""></td>
      <td class="border"> 
        <input type="submit" name="Submit" value="提 交" ></td>
    </tr>
</table>
 </form>
<?php
}
if ($action=="modify") {
$sql="select * from zzcms_about where id='".$id."'";
$rs=query($sql);
$row=fetch_array($rs);
?>
<div class="admintitle">修改公司信息</div>  
<form action="?action=savedata&saveas=modify" method="POST" name="myform" id="myform" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="23%" align="right" class="border">名称</td>
      <td width="77%" class="border"><input name="title" type="text" id="title" value="<?php echo $row["title"]?>"></td>
    </tr>
    <tr> 
      <td align="right" class="border">内容</td>
      <td class="border"> <textarea name="content" id="content" ><?php echo stripfxg($row["content"])?></textarea> 
	  	<script type="text/javascript">CKEDITOR.replace('content');	</script>
        </td>
    </tr>
    <tr> 
      <td align="right" class="border">链接地址：</td>
      <td class="border"><input name="link" type="text"  value="<?php if ($row["link"]<>"") { echo $row["link"]; }else{ echo "/one/siteinfo.php?id=".$row["id"]."";}?>"> </td>
    </tr>
    <tr>
      <td align="right" class="border"><input name="id" type="hidden"  value="<?php echo $row["id"]?>"></td>
      <td class="border">
<input type="submit" name="Submit2" value="提 交"></td>
    </tr>
</table>
  </form>
<?php
}
if ($go==1){
echo "<script>location.href='about_manage.php'</script>";
}
?>
</body>
</html>