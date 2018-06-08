<?php
require_once ("admin.inc.php");
require_once ("admin.function.php");

$id 			= trim ( $_GET ['id'] ) ? trim ( $_GET ['id'] ) : 0;
$keywords 		= trim($_GET['keywords']);
$page 			= $_GET ['page'] ? $_GET ['page'] : 1;
$page_size 		= 10;


$where="a.delete_session_id is not null";

$sql_string = "select a.*,b.name as cname,c.username from cms_article a 
					left outer join cms_category b on a.cid=b.id
					left outer join cms_users c on a.created_by=c.userid
					 where ".$where." order by a.id desc";
$total_nums = $db->getRowsNum ( $sql_string );
$mpurl 	= "article.rubbish.php?id=" . $id."&keywords=".$keywords;
$article_list = $db->selectLimit ( $sql_string, $page_size, ($page - 1) * $page_size );
//========================


$name = $db->getOneField ( "select name from cms_category where id =" . $id );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文章管理</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
<script src="../include/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
function doAction(a,id){
	ids = 0;
	if(a=='cdelete'){
		if(confirm('请确认是否彻底删除！')){
			$.ajax({
				url:'article.action.php',
				type: 'POST',
				data:'act=cdelete&id='+getCheckedIds('checkbox'),
				success: function(data){
					window.location.href = window.location.href;
				}
			});
		}
	}
	if(a=='revert'){
		if(confirm('请确认是否还原！')){
			$.ajax({
				url:'article.action.php',
				type: 'POST',
				data:'act=revert&id='+getCheckedIds('checkbox'),
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
			document.getElementById('updateCategoryButton').disabled=false;
			
			return;
		}
	}
	document.getElementById('DeleteCheckboxButton').disabled=true;
	document.getElementById('updateCategoryButton').disabled=true;
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
    <td valign="top" style="padding:10px;"><table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0" class="table_head">
        <tr>
          <td height="30">垃圾箱管理
          &nbsp;&nbsp;&nbsp; </td>
        </tr>
      </table>
      <table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0" class="table_form">
        <tr>
          <th width="40"><input type="checkbox" name="checkbox11"
			value="checkbox" onClick="checkAll(this,'checkbox')"></th>
          <th height="26">文章标题</th>
          <th width="90" height="26">发布人</th>
          <th width="150" height="26">发布时间</th>
          <th width="80">所属栏目</th>
        </tr>
        <?php
		foreach ( $article_list as $al ) {
			?>
        <tr onMouseOver="this.className='relow'"
		onMouseOut="this.className='row'" class="row">
          <td align="center"><input type="checkbox" name="checkbox"
			value="<?php echo $al ['id'];?>"
			onClick="checkDeleteStatus('checkbox')"></td>
          <td height="26"><?php echo $al ['title'];?>&nbsp;</td>
          <td height="26" align="center"><?php echo $al ['username'];?> &nbsp;</td>
          <td height="26" align="center"><?php echo $al ['created_date'];?> &nbsp;</td>
          <td align="center"><?php echo $al ['cname'];?> &nbsp;</td>
        </tr>
        <?php
		}
		?>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0"
	class="table_footer">
        <tr>
          <td height="29" style="text-align: left; padding-left: 10px"><div style="float: left;">
              <input type="button" id="DeleteCheckboxButton" value="彻底删除" disabled="disabled" onClick="doAction('cdelete')">
              &nbsp;
              <input id="updateCategoryButton" type="button" value="批量还原" disabled="disabled" onClick="doAction('revert')">
            </div>
            <div style="float: right; padding-right: 50px"> <?php echo multi ( $total_nums, $page_size, $page, $mpurl, 0, 5 );?> </div></td>
        </tr>
        <tr>
          <td height="3" colspan="2" background="admin/images/20070907_03.gif"></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
