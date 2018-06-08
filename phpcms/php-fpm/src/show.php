<?php 
include_once 'header.php';
?>
<?php
$arc = getArticleInfo();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $arc['title'];?></title>
</head>

<body>
<table width="990" align="center">
  <tr>
    <th height="40" style="color:#FFF""><?php echo $arc['title'];?>&nbsp;</th>
  </tr>
  <tr>
    <td align="left" class="white"><?php echo $arc['content'];?>&nbsp;</td>
  </tr>
</table>
<?php 
include_once 'footer.php';
?>

