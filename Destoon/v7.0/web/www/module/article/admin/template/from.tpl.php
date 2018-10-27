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
<table cellspacing="0" class="tb ls">
<tr>
<th>来源</th>
<th>网址</th>
<th width="40">选择</th>
</tr> 
<?php foreach($lists as $k=>$v) { ?>
<tr>
<td>&nbsp;<a href="javascript:TopUseBack('<?php echo $v['copyfrom'];?>','<?php echo $v['fromurl'];?>');"><?php echo $v['copyfrom'];?></a></td>
<td>&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=<?php echo urlencode(fix_link($v['fromurl']));?>" target="_blank"><?php echo $v['fromurl'];?></a></td>
<td align="center"><a href="javascript:TopUseBack('<?php echo $v['copyfrom'];?>','<?php echo $v['fromurl'];?>');" class="t">[选择]</a></td>
</tr>
<?php } ?>
</table>
<script type="text/javascript">
function TopUseBack(v, u) {
	parent.Dd('copyfrom').value = v;
	parent.Dd('fromurl').value = u;
	parent.cDialog();
}
</script>
<?php include tpl('footer');?>