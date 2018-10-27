<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<script type="text/javascript">var _del = 0;</script>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="10" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<select name="mid">
<option value="0">模块</option>
<?php 
foreach($MODULE as $v) {
	if(($v['moduleid'] > 0 && $v['moduleid'] < 4) || $v['islink']) continue;
	echo '<option value="'.$v['moduleid'].'"'.($mid == $v['moduleid'] ? ' selected' : '').'>'.$v['name'].'</option>';
} 
?>
</select>&nbsp;
起价：<input type="text" size="5" name="minprice" value="<?php echo $minprice;?>"/> ~ <input type="text" size="5" name="maxprice" value="<?php echo $maxprice;?>"/>&nbsp;
<input type="checkbox" name="empty" value="1"<?php echo $empty ? ' checked' : '';?>/> 空关键词&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="page" value="<?php echo $page;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th>模块</th>
<th>关键词</th>
<th>起价</th>
<th>添加时间</th>
<th>操作人</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr align="center">
<td><input name="post[<?php echo $v['itemid'];?>][delete]" type="checkbox" value="1"/></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&mid=<?php echo $v['mid'];?>"><?php echo $MODULE[$v['mid']]['name'];?></a></td>
<td><?php echo $v['word'];?></td>
<td><input name="post[<?php echo $v['itemid'];?>][price]" type="text" size="10" value="<?php echo $v['price'];?>"/><input name="post[<?php echo $v['itemid'];?>][oldprice]" type="hidden" value="<?php echo $v['price'];?>"/></td>
<td class="px12"><?php echo $v['edittime'];?></td>
<td><?php echo $v['editor'];?></td>
</td>
</tr>
<?php } ?>
<tr align="center">
<td class="f_green">新增</td>
<td>
<select name="post[0][mid]">
<?php 
foreach($MODULE as $v) {
	if(($v['moduleid'] > 0 && $v['moduleid'] < 4) || $v['islink']) continue;
	echo '<option value="'.$v['moduleid'].'">'.$v['name'].'</option>';
} 
?>
</select>
</td>
<td><input name="post[0][word]" type="text" size="10" value=""/></td>
<td><input name="post[0][price]" type="text" size="10" value=""/></td>
<td></td>
<td></td>
</tr>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td height="30" colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个价格项目，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<span class="f_gray">关键词留空代表对应模块的默认起价</span></td>
</tr>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>