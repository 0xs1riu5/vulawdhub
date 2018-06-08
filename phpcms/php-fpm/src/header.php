<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'include/config.inc.php';
include_once 'include/common.function.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>文章管理系统</title>
<link href="images/css.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="443" height="100" align="center" bgcolor="#182E43"><a href="http://www.test.com" target="_blank"><img src="images/logo.gif" width="396" height="78" border="0" /></a></td>
    <td align="right" valign="top" bgcolor="#182E43" class="white" style="padding-top:5px">
    <a href="http://www.test.com" onClick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.test.com/');return(false);" style="behavior: url(#default#homepage)" class="white">设为首页 </a>｜ 
    <a href="javascript:window.external.addFavorite('http://www.test.com/',文章管理系统');" class="white">加入收藏 </a>｜ 
    <a href="admin/" class="white">后台管理&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="navBg">
  <tr>
    <td align="center" valign="top">
      <div class="nav"><a href="index.php">首  页</a></div>
      <?php foreach(getCategoryList() as $list){?>
      <div class="nav"><a href="list.php?id=<?php echo $list['id']?>"><?php echo $list['name']?></a></div>
      <?php }?>
      
      <?php foreach(getPageList() as $list){?>
      <div class="nav"><a href="page.php?id=<?php echo $list['id']?>"><?php echo $list['title']?></a></div>
      <?php }?>
      
      <div class="nav"><a href="message.php">留言板</a></div></td>
  </tr>
</table>
