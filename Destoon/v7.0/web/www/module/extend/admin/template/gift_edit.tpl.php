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
<td class="tl"><span class="f_red">*</span> 礼品分类</td>
<td><span id="type_box"><?php echo type_select('gift', 1, 'post[typeid]', '请选择分类', $typeid, 'id="typeid"');?></span> <a href="javascript:var type_item='gift',type_name='post[typeid]',type_default='请选择分类',type_id=<?php echo $typeid;?>,type_interval=setInterval('type_reload()',500);Dwidget('?file=type&item=<?php echo $file;?>', '礼品分类');"><img src="<?php echo $MODULE[2]['linkurl'];?>image/img_add.gif" width="12" height="12" title="管理分类"/></a> <span id="dtypeid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 礼品标题</td>
<td><input name="post[title]" type="text" id="title" size="60" value="<?php echo $title;?>"/> <?php echo dstyle('post[style]', $style);?>&nbsp; <?php echo level_select('post[level]', '级别', $level);?> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 标题图片</td>
<td>
	<input type="hidden" name="post[thumb]" id="thumb" value="<?php echo $thumb;?>"/>
	<table width="130" class="ctb">
	<tr align="center" height="120" class="c_p">
	<td width="130"><img src="<?php echo $thumb ? $thumb : DT_SKIN.'image/waitpic.gif';?>" width="100" height="100" id="showthumb" title="预览图片" alt="" onclick="if(this.src.indexOf('waitpic.gif') == -1){_preview(Dd('showthumb').src, 1);}else{Dalbum('',<?php echo $moduleid;?>,120, 90, Dd('thumb').value, true);}"/></td>
	</tr>
	<tr align="center" height="25">
	<td><span onclick="Dalbum('',<?php echo $moduleid;?>,100, 100, Dd('thumb').value, true);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="delAlbum('','wait');" class="jt">[删除]</span></td>
	</tr>
	</table>
	<span id="dthumb" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 单价</td>
<td><input type="text" size="10" name="post[credit]" value="<?php echo $credit;?>" id="credit"/> <?php echo $DT['credit_name'];?> <span id="dcredit" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 库存</td>
<td><input type="text" size="10" name="post[amount]" value="<?php echo $amount;?>" id="amount"/> <span id="damount" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员组</td>
<td><?php echo group_checkbox('post[groupid][]', $groupid, '1,2,3,4');?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 兑换次数</td>
<td><input type="text" size="10" name="post[maxorder]" value="<?php echo $maxorder;?>" id="maxorder"/> <?php echo tips('同一个帐号最多兑换次数，填0代表不限制');?> <span id="dmaxorder" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 有效期</td>
<td><?php echo dcalendar('post[fromtime]', $fromtime);?> 至 <?php echo dcalendar('post[totime]', $totime);?> <?php echo tips('不填表示不限时间');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 详细说明</td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', 'Destoon', '100%', 350);?><br/><span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr title="请保持时间格式">
<td class="tl"><span class="f_hid">*</span> 添加时间</td>
<td><?php echo dcalendar('post[addtime]', $addtime, '-', 1);?></td>
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
		Dmsg('请选择礼品分类', f);
		return false;
	}
	f = 'title';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('标题最少2字，当前已输入'+l+'字', f);
		return false;
	}
	f = 'thumb';
	l = Dd(f).value.length;
	if(l < 10) {
		Dmsg('请上传标题图片', f);
		return false;
	}
	f = 'credit';
	l = Dd(f).value;
	if(l < 1) {
		Dmsg('请填写单价', f);
		return false;
	}
	f = 'amount';
	l = Dd(f).value;
	if(l < 1) {
		Dmsg('请填写名额', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>