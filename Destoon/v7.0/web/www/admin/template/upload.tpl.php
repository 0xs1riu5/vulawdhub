<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="id" value="<?php echo $id;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="15" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<span data-hide="1200"><?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;</span>
<select name="mid">
<option value="0">模块</option>
<?php foreach($MODULE as $m) { if(!$m['islink']) { ?>
<option value="<?php echo $m['moduleid'];?>"<?php echo $mid == $m['moduleid'] ? ' selected' : '';?>><?php echo $m['name'];?></option>
<?php } } ?>
</select>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="checkbox" name="thumb" value="1"<?php echo $thumb ? ' checked' : '';?>/> 图片&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>&id=<?php echo $id;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="id" value="<?php echo $id;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="20"></th>
<th width="90">缩略图</th>
<th>大小</th>
<th data-hide="1200">宽度</th>
<th data-hide="1200">高度</th>
<th>模块</th>
<th>信息ID</th>
<th>表名</th>
<th data-hide="1200">来源</th>
<th>会员名</th>
<th width="150">时间</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center" title="<?php echo $v['fileurl'];?>">
<td><input name="itemid[]" type="checkbox" value="<?php echo $v['pid'];?>"/></td>
<td><a href="<?php echo $v['fileurl'];?>" target="_blank"><img src="<?php echo DT_PATH.'file/ext/'.$v['ext'].'.gif';?>"/></a></td>
<td>
<?php if($v['image']) { ?>
<a href="javascript:_preview('<?php echo $v['fileurl'];?>');"><img src="<?php echo $v['fileurl'];?>" width="80" style="margin:5px;" onerror="$.get('?file=<?php echo $file;?>&id=<?php echo $id;?>&action=delete&itemid=<?php echo $v['pid'];?>&ajax=1');this.src='<?php echo DT_SKIN;?>image/nopic.gif';"/></a>
<?php } else if($v['video']) { ?>
<a href="javascript:_play('<?php echo $v['fileurl'];?>');"><img src="admin/image/video.gif" width="80" style="margin:5px;"/></a>
<?php } else { ?>
<a href="<?php echo $v['fileurl'];?>" target="_blank"><img src="<?php echo DT_SKIN;?>image/nopic.gif" width="80" style="margin:5px;"/></a>
<?php } ?>
</td>
<td><?php echo $v['size'];?></td>
<td data-hide="1200"><?php echo $v['width'] ? $v['width'] : '';?></td>
<td data-hide="1200"><?php echo $v['height'] ? $v['height'] : '';?></td>
<td><a href="?file=<?php echo $file;?>&mid=<?php echo $v['moduleid'];?>&id=<?php echo $id;?>"><?php echo $MODULE[$v['moduleid']]['name'];?></a></td>
<td><a href="<?php echo DT_PATH;?>api/redirect.php?mid=<?php echo $v['moduleid'];?>&itemid=<?php echo $v['itemid'];?>&tb=<?php echo $v['tb'];?>" target="_blank"><?php echo $v['itemid'];?></a></td>
<td><a href="?file=<?php echo $file;?>&tb=<?php echo $v['tb'];?>&id=<?php echo $id;?>"><?php echo $v['tb'];?></a></td>
<td data-hide="1200"><a href="?file=<?php echo $file;?>&upfrom=<?php echo $v['upfrom'];?>&id=<?php echo $id;?>"><?php echo $v['upfrom'];?></a></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td class="px12"><?php echo $v['addtime'];?></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input title="删除记录和对应文件" type="submit" value="删除文件" class="btn-r" onclick="if(confirm('确定要删除选中记录吗？系统同时会删除对应文件，此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&id=<?php echo $id;?>&action=delete'}else{return false;}"/>&nbsp;&nbsp;
<input title="仅删除记录" type="submit" value="删除记录" class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&id=<?php echo $id;?>&action=delete_record'}else{return false;}"/>&nbsp;&nbsp;
<?php if(!$lists && $kw) {?>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="f_red">未找到记录</span>
<?php }?>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">
function _play(url) {
	Dwidget('?file=<?php echo $file;?>&id=<?php echo $id;?>&action=play&video='+url, '视频播放', 480, 360, 'no');
}
</script>
<?php include tpl('footer');?>