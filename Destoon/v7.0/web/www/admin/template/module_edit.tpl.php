<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="modid" value="<?php echo $modid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 模块类型</td>
<td><?php echo $islink ? '外部链接' : $modulename.'模型';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 模块名称</td>
<td><input name="post[name]" type="text" id="name" size="10" value="<?php echo $name;?>"/> <?php echo dstyle('post[style]', $style);?> <span id="dname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 导航菜单</td>
<td><input type="radio" name="post[ismenu]" value="1" <?php if($ismenu) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="post[ismenu]" value="0"  <?php if(!$ismenu) echo 'checked';?>/> 否</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 新窗口打开</td>
<td><input type="radio" name="post[isblank]" value="1" <?php if($isblank) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="post[isblank]" value="0"  <?php if(!$isblank) echo 'checked';?>/> 否</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 独立LOGO</td>
<td><input type="radio" name="post[logo]" value="1"  <?php if($logo) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="post[logo]" value="0"  <?php if(!$logo) echo 'checked';?>/> 否 <?php tips('如果选择是，请将LOGO图片命名为logo_'.$modid.'.gif<br/>上传至skin/'.$CFG['skin'].'/image/目录');?></td>
</tr>
<?php if($islink) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 链接地址</td>
<td><input name="post[linkurl]" type="text" id="linkurl" size="40" value="<?php echo $linkurl;?>"/> <span id="dlinkurl" class="f_red"></span></td>
</tr>
<?php } else { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 安装目录</td>
<td><input name="post[moduledir]" type="text" id="moduledir" size="30" value="<?php echo $moduledir;?>"<?php if($modid == 4) {?> disabled<?php } ?>/> <?php tips('限英文、数字、中划线、下划线');?> <span id="dmoduledir" class="f_red"></span>
<br/>提示:如果不是十分必要，建议不要频繁更改安装目录
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 电脑版绑定域名</td>
<td><input name="post[domain]" type="text" id="domain" size="30" value="<?php echo $domain;?>"/><?php tips('例如http://sell.destoon.com/,以 / 结尾<br/>如果不绑定请勿填写');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 手机版绑定域名</td>
<td><input name="post[mobile]" type="text" id="mobile" size="30" value="<?php echo $mobile;?>"/><?php tips('例如http://m.sell.destoon.com/,以 / 结尾<br/>如果不绑定请勿填写');?></td>
</tr>
<?php } ?>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'name';
	l = Dd(f).value;
	if(l == '') {
		Dmsg('请填写模块名称', f);
		return false;
	}
<?php if($islink) { ?>
	f = 'linkurl';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请填写链接地址', f);
		return false;
	}
<?php } else { ?>
	f = 'moduledir';
	l = Dd(f).value;
	if(l == '') {
		Dmsg('请填写安装目录', f);
		return false;
	}
<?php } ?>
	return true;
}
</script>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>