<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="pollid" value="<?php echo $pollid;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&itemid=<?php echo $itemid;?>');"/>
</form>
</div>
<form method="post" action="?" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="pollid" value="<?php echo $pollid;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th>排序</th>
<th>标题</th>
<th>链接</th>
<th>简介</th>
<th>图片</th>
<th>票数</th>
<th>记录</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr align="center">
<td><input name="post[<?php echo $v['itemid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][listorder]" type="text" size="3" value="<?php echo $v['listorder'];?>"/>
<td><input name="post[<?php echo $v['itemid'];?>][title]" type="text" size="15" value="<?php echo $v['title'];?>" title="<?php echo $v['title'];?>" style="color:<?php echo $v['style'];?>"/>
<?php echo dstyle('post['.$v['itemid'].'][style]', $v['style']);?></td>
<td><input name="post[<?php echo $v['itemid'];?>][linkurl]" type="text" size="15" value="<?php echo $v['linkurl'];?>" title="<?php echo $v['linkurl'];?>"/>
<a href="<?php echo $v['linkurl'] ? $v['linkurl'] : 'javascript:return false;';?>" target="_blank"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_select.gif" width="12" height="12" title="新窗口打开链接"/></a>
</td>
<td><input name="post[<?php echo $v['itemid'];?>][introduce]" type="text" size="15" value="<?php echo $v['introduce'];?>" title="<?php echo $v['introduce'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][thumb]" type="text" size="15" value="<?php echo $v['thumb'];?>" id="thumb_<?php echo $v['itemid'];?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $P['thumb_width'];?>,<?php echo $P['thumb_height'];?>,Dd('thumb_<?php echo $v['itemid'];?>').value,0,'thumb_<?php echo $v['itemid'];?>');" class="jt"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_upload.gif" width="12" height="12" title="上传"/></span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb_<?php echo $v['itemid'];?>').value);" class="jt"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_preview.gif" width="12" height="12" title="预览"/></span>&nbsp;&nbsp;<span onclick="Dd('thumb_<?php echo $v['itemid'];?>').value='';" class="jt"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_delete.gif" width="12" height="12" title="删除"/></span></td>
<td><input name="post[<?php echo $v['itemid'];?>][polls]" type="text" size="3" value="<?php echo $v['polls'];?>"/></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=record&pollid=<?php echo $v['pollid'];?>&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/poll.png" width="16" height="16" title="投票记录" alt=""/></a></td>
</tr>
<?php } ?>
<tr>
<th></th>
<th>排序</th>
<th>标题</th>
<th>链接</th>
<th>简介</th>
<th>图片</th>
<th>票数</th>
<th>记录</th>
</tr>
<tr align="center">
<td class="f_green">新增</td>
<td><input name="post[0][listorder]" type="text" size="3" value="0"/></td>
<td><input name="post[0][title]" type="text" size="15"/>
<?php echo dstyle('post[0][style]');?></td>
<td><input name="post[0][linkurl]" type="text" size="15"/> ..</td>
<td><input name="post[0][introduce]" type="text" size="15"/></td>
<td><input name="post[0][thumb]" type="text" size="15" id="thumb"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $P['thumb_width'];?>,<?php echo $P['thumb_height'];?>, Dd('thumb').value);" class="jt"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_upload.gif" width="12" height="12" title="上传"/></span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_preview.gif" width="12" height="12" title="预览"/></span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_delete.gif" width="12" height="12" title="删除"/></span></td>
<td><input name="post[0][polls]" type="text" size="3"/></td>
<td></td>
</tr>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td height="30" colspan="8">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个选项，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/>
</td>
</tr>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<?php load('clear.js'); ?>
<script type="text/javascript">
Menuon(0);
$(function(){
	if($('body').width()<900) $('body').width(900);
});
</script>
<?php include tpl('footer');?>