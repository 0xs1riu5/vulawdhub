<?php
require_once ("admin.inc.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="images/css.css" rel="stylesheet" type="text/css">
<script src="../include/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
function doAction(a,id){
	if(a=='delete'){
		if(confirm('将删除栏目下的所有文章，请确认真的要删除吗？')){
			$.ajax({
				url:'category.action.php',
				type: 'POST',
				data: '&act=delete&id='+id,//对页面所有input元素进行序列化
				success: function(data){if(data) alert(data);
					window.location.href = window.location.href;
				}
			});			
		}
	}
	
}
</script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top" style="padding:10px;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_head">
          <tr>
            <td width="39%" height="31">栏目管理</td>
            <td width="61%" align="right"></td>
            <td width="61%" align="right"><input type="button" value="添加顶层栏目"
			onClick="location.href='category.add.php?act=add'" class="submit1"></td>
          </tr>
        </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_form">
          <tr>
            <th>栏目名称</th>
            <th width="50">文章数</th>
            <th width="50">排序</th>
            <th width="200">操作</th>
          </tr>
          <?php
getCategoryList ();
?>
        </table>
      </td>
  </tr>
</table>
</body>
</html>



<?php
/*=======================================================================================*/
//获取栏目列表信息
function getCategoryList($id = 0, $level = 0) {
	global $db;
	$category_arr = $db->getList ( "SELECT * FROM cms_category WHERE pid = " . $id . " order by seq,id" );
	for($lev = 0; $lev < $level * 2 - 1; $lev ++) {
		$level_nbsp .= "　";
	}
	$level++;
	$level_nbsp .= "<font style=\"font-size:12px;font-family:wingdings\">".$level."</font>";
	foreach ( $category_arr as $category ) {
		$id = $category ['id'];
		$name = $category ['name'];
		echo "
<tr onMouseOver=\"this.className='relow'\" onMouseOut=\"this.className='row'\" class=\"row\">
	<td height=\"26\" ><a href=\"article.php?id=" . $id . "\"> " . $level_nbsp . " &nbsp; " . $name . "</a>&nbsp;&nbsp;(cid: $id)</td>
	<td height=\"26\" align=\"center\" style=\"color:#FF0000\">" . getArticleNumOfCategory ( $id ) . "&nbsp;</td>
	<td height=\"26\" align=\"center\">" . $category ['seq'] . "&nbsp;</td>
	<td height=\"26\" align=\"center\">
		<a href='category.add.php?act=add&pid=" . $id . "'>添加子栏目</a> | 
		<a href='article.add.php?act=add&id=" . $id . "'>添加文章</a> | 
		<a href='category.add.php?act=edit&id=" . $id . "'>修改</a> | 
		<a href=\"javascript:doAction('delete'," . $id . ")\">删除</a></td>
</tr> ";
		getCategoryList ( $id, $level );
	}
}

//栏目下文章数
function getArticleNumOfCategory($id) {
	global $db;
	$sql = "SELECT id FROM cms_article WHERE cid=" . $id . " AND delete_session_id IS NULL";
	return $db->getRowsNum ( $sql );
}
?>