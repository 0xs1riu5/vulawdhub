<?php
require_once ("admin.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理后台</title>
</head>
<frameset rows="40,*" cols="*" frameborder="no" border="0" framespacing="0">
  <frame src="header.php" name="topFrame"  id="topFrame" scrolling="no" noresize="noresize"/>
  <frameset cols="162,*" frameborder="no" border="0" framespacing="0">
    <frame src="menu.php" name="leftFrame" id="leftFrame" scrolling="yes" noresize="noresize"/>
    <frame src="category.php" name="mainFrame" id="mainFrame" scrolling="auto"/>
  </frameset>
</frameset>
<noframes>
<body>
</body>
</noframes>
</html>
