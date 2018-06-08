<?php
include ("admin.inc.php");
include ("../include/fckeditor/fckeditor.php");

$id		 = trim($_GET ['id'])?trim($_GET ['id']):0;
$act			 = trim($_GET ['act'])?trim($_GET ['act']):'add';

$actName = $act == 'add'?'添加':'修改';

$friendlink = $db->getOneRow ( "select * from cms_friendlink where id=" . $id );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.getElementById('name').focus()">
<table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0">
	<tr>
		<td width="*" height="1299" valign="top" style="padding: 10px;">
		<form action="friendlink.action.php" method="post" name="form1">
		<input type="hidden" name="act" value="<?php echo $act;?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td height="829" valign="top" style="padding:10px;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
                  <tr>
                    <td height="31"><?php echo $actName;?>链接</td>
                </tr>
                </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td height="40" align="right" class="form_list">网站名称 ：</td>
					<td class="form_list"><input name="name" type="text" class="form" style="width: 300px" value="<?php echo $friendlink ['name'];?>"></td>
				  </tr>
					<tr>
						<td height="40" align="right" class="form_list">网站地址：</td>
						<td class="form_list"><input name="url" type="text" class="form" style="width: 300px" value="<?php echo $friendlink ['url'];?>"></td>
					</tr>
					<tr>
						<td height="40" align="right" class="form_list">LOGO：</td>
						<td class="form_list"><input name="logo" type="text" class="form" style="width:300px" value="<?php echo $friendlink ['logo'];?>"></td>
					</tr>
					<tr>
						<td height="40" align="right" class="form_list">说明：</td>
					<td class="form_list"><textarea name="description" class="form" style="width:300px; height: 50px; overflow: auto"><?php echo trim ( $friendlink ['description'] );?></textarea>						</tr>
					
				  <tr>
				    <td height="40" align="right">排序：</td>
				      <td height="40">
                    <input name="seq" type="text" class="form" style="width: 50px" value="<?php echo $friendlink ['seq']?$friendlink ['seq']:0;?>"></td>
                  </tr>
                </table>
              <table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
                  <tr>
                    <td height="31" align="center"><input name="id" type="hidden" value="<?php echo $id;?>"> 
                            <input type="submit" name="button" id="button" value="提交"> 
                      <input type="button" value="返回" onClick="window.history.go(-1)">&nbsp;</td>
                  </tr>
              </table></td>
          </tr>
        </table>
		<p>&nbsp;</p>
		</form>
		</td>
	</tr>
</table>
</body>
</html>
