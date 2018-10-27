<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="60" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>域名</th>
<th>编码</th>
<th>网站</th>
<th width="150">修改时间</th>
<th width="60">管理</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input name="itemid[]" type="checkbox" value="<?php echo $v['itemid'];?>"/></td>
<td><?php echo $v['domain'];?></td>
<td><?php echo $v['encode'];?></td>
<td><a href="<?php echo DT_PATH;?>api/redirect.php?url=http://<?php echo $v['domain'];?>" target="_blank"><?php echo $v['sitename'];?></a></td>
<td class="px12" title="<?php echo $v['editor'];?>"><?php echo $v['edittime'];?></td>
<td><a href="?file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="批量删除" class="btn-r" onclick="if(confirm('确定要删除选中规则吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>