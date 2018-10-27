<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>
&nbsp;<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $level_select;?>&nbsp;
<select name="ontop">
<option value="0">置顶</option>
<option value="1"<?php if($ontop == 1) echo ' selected';?>>本圈</option>
<option value="2"<?php if($ontop == 2) echo ' selected';?>>全局</option>
</select>&nbsp;
<select name="style">
<option value="0">高亮</option>
<?php
foreach($COLOR as $k=>$v) {
?>
<option value="<?php echo $k;?>" style="color:#<?php echo $k;?>;"<?php if($style == '#'.$k) echo ' selected';?>><?php echo $v;?></option>
<?php
}
?>
</select>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&gid=<?php echo $gid;?>');"/>
</td>
</tr>
<tr>
<td>
&nbsp;<select name="datetype">
<option value="addtime" <?php if($datetype == 'addtime') echo 'selected';?>>发布日期</option>
<option value="replytime" <?php if($datetype == 'replytime') echo 'selected';?>>回复日期</option>
<option value="edittime" <?php if($datetype == 'edittime') echo 'selected';?>>修改日期</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate, '');?> 至 <?php echo dcalendar('todate', $todate, '');?>&nbsp;
<?php echo category_select('catid', '不限分类', $catid, $moduleid);?>&nbsp;
<?php echo $DT['city'] ? ajax_area_select('areaid', '不限地区', $areaid).'&nbsp;' : '';?>
商圈ID：<input type="text" name="gid" value="<?php echo $gid;?>" size="4"/>&nbsp;
帖子ID：<input type="text" size="4" name="itemid" value="<?php echo $itemid;?>"/>&nbsp;
<input type="checkbox" name="thumb" value="1"<?php echo $thumb ? ' checked' : '';?>/>图片&nbsp;
<input type="checkbox" name="guest" value="1"<?php echo $guest ? ' checked' : '';?>/>游客&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<input type="hidden" name="gid" value="<?php echo $gid;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>商圈</th>
<th width="25"> </th>
<th>标题</th>
<th>会员</th>
<th width="130"><?php echo $timetype == 'add' ? '添加' : '回复';?>时间</th>
<th>浏览</th>
<th>回复</th>
<th width="50">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><a href="<?php echo $v['groupurl'];?>" target="_blank"><?php echo $v['groupname'];?></a></td>
<td>
<?php if($v['ontop']) { ?>
<img src="<?php echo DT_SKIN;?>image/club_ontop_<?php echo $v['ontop'];?>.gif" alt="" title="<?php if($v['ontop']==1) { ?>本圈<?php } else { ?>全局<?php } ?>
置顶"/>
<?php } else if($v['level']) { ?>
<img src="<?php echo DT_SKIN;?>image/club_level_<?php echo $v['level'];?>.gif" alt="" title="精华<?php echo $v['level'];?>"/>
<?php } else if($v['thumb']) { ?>
<img src="<?php echo DT_SKIN;?>image/club_thumb.gif" alt="" title="有图片"/>
<?php } else if($v['video']) { ?>
<img src="<?php echo DT_SKIN;?>image/club_video.gif" alt="" title="有视频"/>
<?php } else { ?>
&nbsp;
<?php } ?>
</td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['title'];?></a><?php if($v['thumb']) {?> <a href="javascript:_preview('<?php echo $v['thumb'];?>');"><img src="admin/image/img.gif" width="10" height="10" title="标题图,点击预览" alt=""/></a><?php } ?></td>
<td>
<?php if($v['username']) { ?>
<a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['passport'];?></a>
<?php } else { ?>
	<a href="javascript:_ip('<?php echo $v['ip'];?>');" title="游客"><?php echo $v['ip'];?></a>
<?php } ?>
</td>
<?php if($timetype == 'add') {?>
<td class="px12" title="回复时间<?php echo $v['replydate'];?>"><?php echo $v['adddate'];?></td>
<?php } else { ?>
<td class="px12" title="添加时间<?php echo $v['adddate'];?>"><?php echo $v['replydate'];?></td>
<?php } ?>
<td class="px12"><?php echo $v['hits'];?></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=reply&tid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 回复管理');"><?php echo $v['reply'];?></a></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>&gid=<?php echo $gid;?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>&gid=<?php echo $gid;?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<?php include tpl('notice_chip');?>
<div class="btns">

<?php if($action == 'check') { ?>

<input type="submit" value="通过审核" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check';"/>&nbsp;
<input type="submit" value="拒 绝" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=reject';"/>&nbsp;
<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中<?php echo $MOD['name'];?>吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>

<?php } else if($action == 'reject') { ?>

<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中<?php echo $MOD['name'];?>吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>

<?php } else if($action == 'recycle') { ?>

<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中<?php echo $MOD['name'];?>吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<input type="submit" value="还 原" class="btn" onclick="if(confirm('确定要还原选中<?php echo $MOD['name'];?>吗？状态将被设置为已通过')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=restore'}else{return false;}"/>&nbsp;
<input type="submit" value="清 空" class="btn-r" onclick="if(confirm('确定要清空回收站吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=clear';}else{return false;}"/>

<?php } else { ?>

<input type="submit" value="更新信息" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=update';"/>&nbsp;
<?php if($MOD['show_html']) { ?><input type="submit" value=" 生成网页 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=tohtml';"/>&nbsp; <?php } ?>
<input type="submit" value="回收站" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&recycle=1';"/>&nbsp;
<input type="submit" value="彻底删除" class="btn-r" onclick="if(confirm('确定要删除选中<?php echo $MOD['name'];?>吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<input type="submit" value="移动帖子" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=move';"/>&nbsp;

<select name="level" onchange="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=level';this.form.submit();"><option value="0">加精</option><option value="0">取消</option><option value="1">精华1</option><option value="2">精华2</option><option value="3">精华3</option></select>&nbsp;

<select name="ontop" onchange="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=ontop';this.form.submit();"><option value="0">置顶</option><option value="0">取消</option><option value="1">本圈</option><option value="2">全局</option></select>&nbsp;

<select name="style" onchange="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=style';this.form.submit();"><option value="0">高亮</option><option value="0">取消</option>
<?php
foreach($COLOR as $k=>$v) {
?>
<option value="<?php echo $k;?>" style="color:#<?php echo $k;?>;"><?php echo $v;?></option>
<?php
}
?>
</select>

<?php } ?>

</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>