<?php
include_once 'admin.inc.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="images/css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top" class="main_left">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_title">
          <tr>
            <td>文章管理</td>
          </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_menu01">
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" /> <a href="article.add.php?act=add" target="mainFrame">添加文章</a></td>
        </tr>
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" /> <a href="article.php" target="mainFrame">文章管理</a><a href="admin/invoice.php" target="mainFrame"></a></td>
        </tr>
        
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" /> <a href="category.php" target="mainFrame">栏目管理</a><a href="admin/invoice.refund.php" target="mainFrame"></a></td>
        </tr>
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" /> <a href="article.rubbish.php" target="mainFrame">垃圾箱管理</a><a href="admin/taxpayer.php" target="mainFrame"></a></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_title">
        <tr>
          <td>网站管理</td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_menu01">
        
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" /> <a href="page.php" target="mainFrame">页面管理</a><a href="admin/view.day.dt.php" target="mainFrame"></a></td>
        </tr>
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" />
              <a href="notice.php" target="mainFrame">公告管理</a><a href="admin/view.day.dt.php" target="mainFrame"></a></td>
        </tr>
        
                <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" />
              <a href="friendlink.php" target="mainFrame">友情链接</a><a href="admin/view.day.dt.php" target="mainFrame"></a></td>
        </tr>
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" />
            <a href="message.php" target="mainFrame">留言管理</a><a href="admin/view.day.collect.php" target="mainFrame"></a></td>
        </tr>        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" />
            <a href="file.php" target="mainFrame">文件管理</a><a href="admin/view.day.collect.php" target="mainFrame"></a></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_title">
        <tr>
          <td>系统管理</td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_menu01">
        <tr>
          <td height="26"><img src="images/ico_03.gif" width="7" height="7" /> <a href="user.php" target="mainFrame">管理员账号</a><a href="admin/db_export.php" target="mainFrame"></a></td>
        </tr>
      </table>
      <br />
      </td>
  </tr>
</table>
</body>
</html>
