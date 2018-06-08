<?php
include ("admin.inc.php");
include ("../include/fckeditor/fckeditor.php");

$id		 = trim($_GET ['id'])?trim($_GET ['id']):0;
$act			 = trim($_GET ['act'])?trim($_GET ['act']):'add';

$actName = $act == 'add'?'添加':'修改';

$notice = $db->getOneRow ( "select * from cms_notice where id=" . $id );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="document.getElementById('title').focus()">
<form action="notice.action.php" method="post" name="form1">
  <input type="hidden" name="act" value="<?php echo $act;?>">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
    <tr>
      <td height="31"><strong><?php echo $actName;?>公告</strong></td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td height="40" align="right" class="form_list">标题 ：</td>
      <td class="form_list"><input name="title" type="text" class="form" style="width: 300px" value="<?php echo $notice ['title'];?>"></td>
    </tr>
    <tr>
      <td height="40" align="right" class="form_list">内容：</td>
      <td class="form_list"><textarea name="content" class="form" style="width:300px; height: 50px; overflow: auto"><?php echo trim ( $notice ['content'] );?></textarea>
    </tr>
    <tr>
      <td height="40" align="right" class="form_list">状态：</td>
      <td height="40" class="form_list"><label>
          <input type="radio" name="state" id="state" value="0" <?php echo empty($notice['state'])?"checked":"";?>>
          可用</label>
        <input type="radio" name="state" id="state" value="1" <?php echo $notice['state']==1?"checked":"";?>>
        禁用</td>
    </tr>
  </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
    <tr>
      <td height="31" align="center"><input name="id" type="hidden" value="<?php echo $id;?>">
        <input type="submit" name="button" id="button" value="提交">
        <input type="button" value="返回" onClick="window.history.go(-1)">
        &nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
