<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="text" size="40" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<div class="tt">作者列表</div>
<table cellspacing="0" class="tb">
<?php 
foreach($lists as $k=>$v) {
	if($k%5==0) { echo '<tr>';}
?>
<td width="20%">&nbsp;&nbsp;<a href="javascript:TopUseBack('<?php echo $v['author'];?>');"><?php echo $v['author'];?></a></td>
<?php 
	if($k%5==4) { echo '</tr>';}
}
?>
</table>
<script type="text/javascript">
function TopUseBack(v) {
	parent.Dd('author').value = v;
	parent.cDialog();
}
</script>
<?php include tpl('footer');?>