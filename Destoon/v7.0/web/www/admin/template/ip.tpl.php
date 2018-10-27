<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<?php if(!isset($js)) { ?><div class="tt"><?php echo $ip;?></div><?php } ?>
<table cellspacing="0" class="tb">
<tr>
<td style="border:none;">&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://www.baidu.com/s?wd=<?php echo $ip;?>" target="_blank"><?php echo ip2area($ip);?></a></td>
</tr>
</table>
<?php include tpl('footer');?>