<?php 
include_once 'header.php';
?>
<?php
$id = is_numeric($_GET['id'])?$_GET['id']:0;
$page = getPageInfoById($id);
?>
<table width="990" align="center">
  <tr>
    <td height="200" align="left" valign="top" class="white" style="padding-left:5px">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $page['content'];?></td>
  </tr>
</table>
<?php 
include_once 'footer.php';
?>