<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="specialid" value="<?php echo $specialid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="post[specialid]" value="<?php echo $specialid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 选择信息源</td>
<td><select id="s_mid" onchange="if(this.value){select_item(this.value, 'special');$('#s_mid').val(0);}">
<option value="0">请选择</option>
<?php
foreach($MODULE as $m) {
	if(!$m['islink'] && $m['moduleid'] > 3 && $m['moduleid'] != $moduleid) echo '<option value="'.$m['moduleid'].'">'.$m['name'].'</option>';
}
?>
</select></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属分类</td>
<td><span id="type_box"><?php echo type_select($tid, 0, 'post[typeid]', '请选择分类', $typeid, 'id="typeid"');?></span> <a href="javascript:var type_item='<?php echo $tid;?>',type_name='post[typeid]',type_default='请选择分类',type_id=<?php echo $typeid;?>,type_interval=setInterval('type_reload()',500);Dwidget('?file=type&item=<?php echo $tid;?>', '[<?php echo $special['title'];?>] 专题信息分类');"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_add.gif" width="12" height="12" title="管理分类"/></a> <span id="dtypeid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 信息标题</td>
<td><input name="post[title]" type="text" id="title" size="60" value="<?php echo $title;?>"/> <?php echo level_select('post[level]', '级别', $level, 'id="level"');?> <?php echo dstyle('post[style]', $style);?> <br/><span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 链接地址</td>
<td><input name="post[linkurl]" type="text" id="linkurl" size="60" value="<?php echo $linkurl;?>"/> <br/><span id="dlinkurl" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 标题图片</td>
<td><input name="post[thumb]" id="thumb" type="text" size="60" value="<?php echo $thumb;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, Dd('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt">[删除]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 内容摘要</td>
<td>
<textarea rows="5" cols="90" name="post[introduce]" id="introduce"><?php echo $introduce;?></textarea>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 添加时间</td>
<td><?php echo dcalendar('post[addtime]', $addtime, '-', 1);?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&special=<?php echo $special;?>');"/></div>
</div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'title';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('标题最少2字，当前已输入'+l+'字', f);
		return false;
	}
	f = 'linkurl';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请填写链接地址', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>