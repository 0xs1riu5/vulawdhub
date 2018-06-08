<?php
require_once ("admin.inc.php");
$page 			= $_GET ['page'] ? $_GET ['page'] : 1;
$page_size 		= 20;

$sql_string = "select * from cms_file order by id desc";
$total_nums = $db->getRowsNum ( $sql_string );
$mpurl 	= "file.php";
$file_list = $db->selectLimit ( $sql_string, $page_size, ($page - 1) * $page_size );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文章管理</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
<script src="../include/js/jquery.js" type="text/javascript" ></script>
<script type="text/javascript">
function doAction(a,id){
	if(a=='deleteAll'){
		if(confirm('请确认是否删除！')){
			$.ajax({
				url:'file.action.php',
				type: 'POST',
				data:'act=delete&id='+getCheckedIds('checkbox'),
				success: function(data){
					if(data) alert(data);
					window.location.href = window.location.href;
				}
			});
		}
	}
	if(a=='delete'){
		if(confirm('请确认是否删除！')){
			$.ajax({
				url:'file.action.php',
				type: 'POST',
				data:'act=delete&id='+id,
				success: function(data){
					if(data) alert(data);
					window.location.href = window.location.href;
				}
			});
		}
	}
}
//全选/取消
function checkAll(o,checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(o.checked){
			oc[i].checked=true;	
		}else{
			oc[i].checked=false;	
		}
	}
	checkDeleteStatus(checkBoxName)
}

//检查有选择的项，如果有删除按钮可操作
function checkDeleteStatus(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			document.getElementById('DeleteCheckboxButton').disabled=false;
			return;
		}
	}
	document.getElementById('DeleteCheckboxButton').disabled=true;
}

//获取所有被选中项的ID组成字符串
function getCheckedIds(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	var CheckedIds = "";
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			if(CheckedIds==''){
				CheckedIds = oc[i].value;	
			}else{
				CheckedIds +=","+oc[i].value;	
			}
			
		}
	}
	return CheckedIds;
}
</script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top" style="padding:10px;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_head">
        <tr>
          <td width="200" height="30">文件管理 
            &nbsp;&nbsp;&nbsp;</td>
          <td>
          <form action="file.action.php" method="post" enctype="multipart/form-data" style="margin:0">
           <input type="hidden" name="act" value="add">
          <input type="file" name="file" id="file">
          <input name="button" type="submit" class="submit1" id="button" value="上传">
          </form>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_form">
        <tr>
          <th width="32"><input type="checkbox" name="checkbox11" value="checkbox" onClick="checkAll(this,'checkbox')"></th>
          <th width="80">缩略图</th>
          <th>文件路径</th>
          <th width="100">文件大小</th>
          <th width="120">上传日期</th>
          <th width="54" height="26">操作</th>
        </tr>
        <?php
	foreach ($file_list as $list){
  ?>
        <tr onMouseOver="this.className='relow'" onMouseOut="this.className='row'" class="row">
          <td height="40" align="center" ><input type="checkbox" name="checkbox" value="<?php echo $list['id'];?>" onClick="checkDeleteStatus('checkbox')"></td>
          <td align="center" >
          <img src="../<?php echo $list['path'];?>" width="70" height="40" onMouseOver="document.getElementById('bigPic<?php echo $list['id'];?>').style.display=''" onMouseOut="document.getElementById('bigPic<?php echo $list['id'];?>').style.display='none'">
          <div id="bigPic<?php echo $list['id'];?>" style="display:none; position:absolute;"><img src="../<?php echo $list['path'];?>"></div>
          </td>
          <td align="center" ><?php echo $list['path'];?>&nbsp;</td>
          <td align="center" ><?php echo round($list['size']/1024,2);?> K</td>
          <td align="center"><?php echo $list['upload_date'];?>&nbsp;</td>
          <td height="40" align="center"><a href="page.add.php?act=edit&id=<?php echo $list['id'];?>"></a> <img src="images/del.gif" alt="删除" onClick="doAction('delete',<?php echo $list['id'];?>)" style="cursor:pointer"></td>
        </tr>
        <?php
	}
  ?>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_footer">
        <tr>
          <td height="29" style="text-align:left; padding-left:10px"><div style=" float:left">
              <input type="button" id="DeleteCheckboxButton" value="删 除" disabled="disabled" onClick="doAction('deleteAll')">
            </div>
            <div style="float: right; padding-right: 50px"> <?php echo multi ( $total_nums, $page_size, $page, $mpurl, 0, 5 );?> </div>
            </td>
        </tr>
        <tr>
          <td height="3" colspan="2" background="admin/images/20070907_03.gif"></td>
        </tr>
    </table></td>
  </tr>
</table>


</body>
</html>
