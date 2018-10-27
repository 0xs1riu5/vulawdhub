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
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 姓名</td>
<td class="tr"><input type="text" size="20" name="post[truename]" id="truename" value="<?php echo $truename;?>"/> <?php echo dstyle('post[style]', $style);?> <span id="dtruename" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 会员名</td>
<td class="tr"><input type="text" size="20" name="post[username]" id="username" value="<?php echo $username;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司名称</td>
<td class="tr"><input type="text" size="40" name="post[company]" id="company" value="<?php echo $company;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 职位</td>
<td class="tr"><input type="text" size="20" name="post[career]" id="career" value="<?php echo $career;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 电话</td>
<td class="tr"><input type="text" size="20" name="post[telephone]" id="telephone" value="<?php echo $telephone;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 手机</td>
<td class="tr"><input type="text" size="20" name="post[mobile]" id="mobile" value="<?php echo $mobile;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 主页</td>
<td class="tr"><input type="text" size="40" name="post[homepage]" id="homepage" value="<?php echo $homepage;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> Email</td>
<td class="tr"><input type="text" size="30" name="post[email]" id="email" value="<?php echo $email;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> QQ</td>
<td class="tr"><input type="text" size="20" name="post[qq]" id="qq" value="<?php echo $qq;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信</td>
<td class="tr"><input type="text" size="20" name="post[wx]" id="wx" value="<?php echo $wx;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 阿里旺旺</td>
<td class="tr"><input type="text" size="20" name="post[ali]" id="ali" value="<?php echo $ali;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> Skype</td>
<td class="tr"><input type="text" size="20" name="post[skype]" id="skype" value="<?php echo $skype;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 备注</td>
<td class="tr"><input type="text" size="40" name="post[note]" id="note" value="<?php echo $note;?>"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	if(Dd('truename').value == '') {
		Dmsg('请填写姓名', 'truename');
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>