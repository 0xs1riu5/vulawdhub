<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 票选分类</td>
<td><span id="type_box"><?php echo type_select('poll', 1, 'post[typeid]', '请选择分类', $typeid, 'id="typeid"');?></span> <a href="javascript:var type_item='poll',type_name='post[typeid]',type_default='请选择分类',type_id=<?php echo $typeid;?>,type_interval=setInterval('type_reload()',500);Dwidget('?file=type&item=<?php echo $file;?>', '票选分类');"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_add.gif" width="12" height="12" title="管理分类"/></a> <span id="dtypeid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 票选标题</td>
<td><input name="post[title]" type="text" id="title" size="50" value="<?php echo $title;?>"/> <?php echo dstyle('post[style]', $style);?>&nbsp; <?php echo level_select('post[level]', '级别', $level);?> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 票选有效期</td>
<td><?php echo dcalendar('post[fromtime]', $fromtime);?> 至 <?php echo dcalendar('post[totime]', $totime);?> <?php echo tips('不填表示不限时间');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 票选说明</td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', 'Destoon', '100%', 350);?><br/><span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 限制会员</td>
<td><?php echo group_checkbox('post[groupid][]', $groupid);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 验证方式</td>
<td>
<select name="post[verify]">
<option value="0"<?php if($verify == 0) echo ' selected';?>>不验证</option>
<option value="1"<?php if($verify == 1) echo ' selected';?>>验证码</option> 
<option value="2"<?php if($verify == 2) echo ' selected';?>>验证问题</option> 
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 投票选项限制</td>
<td>
<input name="post[poll_max]" type="text" id="poll_max" size="5" value="<?php echo $poll_max;?>"/> <?php echo tips('填0表示所有项目都可以投票一次，填数字表示最多可以对N个项目投票一次');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 每页显示项目</td>
<td>
<input name="post[poll_page]" type="text" id="poll_page" size="5" value="<?php echo $poll_page;?>"/> <?php echo tips('前台显示时，每页显示的项目数量');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 每行显示项目</td>
<td>
<input name="post[poll_cols]" type="text" id="poll_cols" size="5" value="<?php echo $poll_cols;?>"/> <?php echo tips('前台显示时，每行显示的项目数量');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 前台排序方式</td>
<td>
<select name="post[poll_order]">
<option value="0">默认排序</option>
<option value="1"<?php echo $poll_order == 1 ? ' selected' : '';?>>投票次数排序</option>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 标题图片大小</td>
<td>
<input name="post[thumb_width]" type="text" id="thumb_width" size="5" value="<?php echo $thumb_width;?>"/> X <input name="post[thumb_height]" type="text" id="thumb_height" size="5" value="<?php echo $thumb_height;?>"/> px
</td>
</tr>
<tr title="请保持时间格式">
<td class="tl"><span class="f_hid">*</span> 添加时间</td>
<td><?php echo dcalendar('post[addtime]', $addtime, '-', 1);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 票选模板</td>
<td><?php echo tpl_select('poll', 'chip', 'post[template_poll]', '默认模板', $template_poll);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 结果模板</td>
<td><?php echo tpl_select('poll', $module, 'post[template]', '默认模板', $template);?></td>
</tr>
<?php if($DT['city']) { ?>
<tr style="display:<?php echo $_areaids ? 'none' : '';?>;">
<td class="tl"><span class="f_hid">*</span> 地区(分站)</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?></td>
</tr>
<?php } ?>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'typeid';
	l = Dd(f).value;
	if(l == 0) {
		Dmsg('请选择票选分类', f);
		return false;
	}
	f = 'title';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('标题最少2字，当前已输入'+l+'字', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>