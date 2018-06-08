<?php 
include_once 'header.php';

?>

<table width="99%" border="0" cellpadding="0" cellspacing="0" class="news">
  <tr>
  	<td align="left">搜索关键字：<font style="color:#F00""><?php echo $_GET['keywords'];?></font></td>
    <td></td>
  </tr>
  <?php foreach(getArticleList("cid=".$_GET['id']."|keywords=".$_GET['keywords']."|row=2") as $list){?>
  <tr>
    <td height="30" align="left"><a href="show.php?id=<?php echo $list['id']?>" target="_blank"><?php echo $list['title']?></a>&nbsp;</td>
    <td width="120" align="left"><?php echo $list['pubdate']?>&nbsp;</td>
  </tr>
  <?php }?>
  <tr>
    <td height="30" colspan="2" align="center" style="padding-right:20px"><?php echo getPagination("search.php?id=".$_GET['id']."&keywords=".urlencode($_GET['keywords']));?></td>
  </tr>
</table>
<?php 
include_once 'footer.php';
?>
