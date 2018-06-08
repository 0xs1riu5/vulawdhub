<?php
include ("admin.inc.php");
include ("../include/fckeditor/fckeditor.php");

$id		 = trim($_GET ['id'])?trim($_GET ['id']):0;
$act			 = trim($_GET ['act'])?trim($_GET ['act']):'add';

$actName = $act == 'add'?'添加':'修改';

$page = $db->getOneRow ( "select * from cms_page where id=" . $id );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.getElementById('title').focus()">
<form action="page.action.php" method="post" name="form1">
  <input type="hidden" name="act" value="<?php echo $act;?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td valign="top" style="padding:10px;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
          <tr>
            <td height="31"><strong><?php echo $actName;?>公告</strong></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td width="60" height="40" class="form_list">标题：</td>
            <td class="form_list"><input name="title" type="text" class="form" style="width: 300px" value="<?php echo $page ['title'];?>"></td>
          </tr>
          <tr>
            <td height="40" class="form_list">别称：</td>
            <td class="form_list"><input name="code" type="text" class="form" id="code" style="width: 300px" value="<?php echo $page ['code'];?>">
          </tr>
          <tr>
            <td height="40" colspan="2" align="center" class="form_list"><?php
        $oFCKeditor = new FCKeditor ( 'content' );
        $oFCKeditor->BasePath = "../include/fckeditor/";
        $oFCKeditor->ToolbarSet = 'MyToolbar';
        $oFCKeditor->Value = $page ['content'];
        $oFCKeditor->Height = 350;
        $oFCKeditor->Create ();
    ?></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
          <tr>
            <td height="31" align="center"><input name="id" type="hidden" value="<?php echo $id;?>">
              <input type="submit" name="button" id="button" value="提交">
              <input type="button" value="返回" onClick="window.history.go(-1)">
              &nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
  h
</form>
</body>
</html>
