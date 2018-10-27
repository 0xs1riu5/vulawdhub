<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="runcode_form" target="_blank">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="runcode"/>
<input type="hidden" name="codes" id="codes" value=""/>
</form>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="pid" value="<?php echo $pid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 广告位ID</td>
<td><input name="place[pid]" type="text" size="5" value="<?php echo $pid;?>"/> <a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>" target="_blank" class="t">[查看]</a>
<br/><span class="f_gray">[注意]修改广告位ID可以恢复误删除的广告位。但如果填写的ID存在，可能导致一个SQL错误</span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 广告位名称</td>
<td><input name="place[name]" id="name" type="text" size="30" value="<?php echo $name;?>"/> <?php echo dstyle('place[style]', $style);?> <span id="dname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告位示意图</td>
<td><input name="place[thumb]" id="thumb" type="text" size="60" value="<?php echo $thumb;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,0,0, Dd('thumb').value,true);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt">[删除]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告位介绍</td>
<td><input name="place[introduce]" type="text" size="60" value="<?php echo $introduce;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 广告位类型</td>
<td>
<?php foreach($TYPE as $k=>$v) {
	if($k) echo '<input name="place[typeid]" type="radio" value="'.$k.'" '.($k == $typeid ? 'checked' : '').' id="p'.$k.'" onclick="sh('.$k.');"/> <label for="p'.$k.'">'.$v.'&nbsp;</label>';
}
?>
<br/><span class="f_gray">[注意] 如果修改了广告位类型，请务必修改此广告位下所有广告</span>
</td>
</tr>
<tr id="wh" style="display:<?php echo $typeid == 3 || $typeid == 4 || $typeid == 5 ? '' : 'none';?>">
<td class="tl"><span class="f_red">*</span> 广告位大小</td>
<td><input name="place[width]" id="width" type="text" size="5" value="<?php echo $width;?>"/> X <input name="place[height]" id="height" type="text" size="5" value="<?php echo $height;?>"/> <span class="f_gray">[宽 X 高 px]</span> <span id="dsize" class="f_red"></span>
</td>
</tr>
<tr id="md" style="display:<?php echo $typeid == 6 || $typeid == 7 ? '' : 'none';?>">
<td class="tl"><span class="f_red">*</span> 所属模块</td>
<td><?php echo module_select('place[moduleid]', '请选择', $mid, 'id="mids"');?> <span id="dmids" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 广告位价格</td>
<td><input name="place[price]" type="text" size="5" value="<?php echo $price;?>"/> <?php echo $unit;?>/月 <span class="f_gray">[0或不填表示待议]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 默认广告代码</td>
<td><textarea name="place[code]" id="code" style="width:98%;height:50px;overflow:visible;font-family:Fixedsys,verdana;"><?php echo $code;?></textarea><br/>
<input type="button" value=" 运行代码 " class="btn" onclick="runcode();"/><span class="f_gray">&nbsp;当广告位下无广告时，显示此代码，支持html、css、js 如果广告位采用js调用，此处不建议使用js代码</span><span id="dcode" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 网站前台显示</td>
<td>
<input type="radio" name="place[open]" value="1" <?php if($open) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="place[open]" value="0" <?php if(!$open) echo 'checked';?>/> 否
<span class="f_gray">如果选择否，将不在前台广告列表里显示，此时会员不能在线订购，并非不显示广告</span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告代码模板</td>
<td><?php echo tpl_select('ad', 'chip', 'place[template]', '默认模板', $template, 'id="template"');?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function sh(id) {
	if(id == 6 || id == 7) {
		Ds('md');Dh('wh');
	} else if(id == 3 || id == 4 || id == 5) {
		Dh('md');Ds('wh');
	} else {
		Dh('md');Dh('wh');
	}
}
function check() {
	var l;
	var f;
	f = 'name';
	l = Dd(f).value.length;
	if(l < 1) {
		Dmsg('请填写广告位名称', f);
		return false;
	}
	if(Dd('p3').checked || Dd('p4').checked || Dd('p5').checked) {
		if(Dd('width').value.length < 2 || Dd('height').value.length < 2) {
			Dmsg('请填写广告位大小', 'size');
			return false;
		}
	}
	if(Dd('p6').checked || Dd('p7').checked) {
		if(Dd('mids').value == 0) {
			Dmsg('请选择所属模块', 'mids');
			return false;
		}
	}
	return true;
}
function runcode() {
	if(Dd('code').value.length < 3) {
		Dmsg('请填写代码', 'code');
		return false;
	}
	Dd('codes').value = Dd('code').value;
	Dd('runcode_form').submit();
}
</script>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>