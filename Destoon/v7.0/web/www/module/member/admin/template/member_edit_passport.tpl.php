<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<form method="post" action="?" onsubmit="return Dcheck();" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 当前昵称</td>
<td><input type="text" name="cpassport" id="cpassport" size="20" value="<?php echo $passport;?>" readonly/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 新昵称</td>
<td><input type="text" name="npassport" id="npassport" size="20"/>&nbsp;<span id="dnpassport" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 系统提示</td>
<td>如无特殊情况，请不要频繁修改会员昵称</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="parent.cDialog();"/></div>
</form>
<script type="text/javascript">
function Dcheck() {
	if(Dd('npassport').value.length < 2) {
		Dmsg('请填写新昵称', 'npassport');
		return false;
	}
	return confirm('确定要将会员昵称'+Dd('cpassport').value+'修改为'+Dd('npassport').value+'吗？');
}
<?php if(isset($success)) {?>
setTimeout(function() {
	parent.window.location.reload();
}, 2000);
<?php } ?>
</script>
<?php include tpl('footer');?>