<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="send" value="1"/>
<input type="hidden" name="preview" id="preview" value="0"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 收信人</td>
<td>
	<input type="radio" name="sendtype" value="1" id="s1" onclick="ck(1);"<?php echo $sendtype == 1 ? ' checked' : '';?>/> <label for="s1">单收信人</label>&nbsp;&nbsp;
	<input type="radio" name="sendtype" value="2" id="s2" onclick="ck(2);"<?php echo $sendtype == 2 ? ' checked' : '';?>/> <label for="s2">多收信人</label>&nbsp;&nbsp;
	<input type="radio" name="sendtype" value="3" id="s3" onclick="ck(3);"<?php echo $sendtype == 3 ? ' checked' : '';?>/> <label for="s3">列表群发</label>
</td>
</tr>
<tbody id="t1" style="display:;">
<tr>
<td class="tl"><span class="f_red">*</span> 接收号码</td>
<td><input type="text" size="35" name="mobile" value=""/></td>
</tr>
</tbody>
<tbody id="t2" style="display:none;">
<tr>
<td class="tl"><span class="f_red">*</span> 接收号码</td>
<td class="f_gray"><textarea name="mobiles" rows="4" cols="35"><?php echo $mobiles;?></textarea><br/>[一行一个接收号码]</td>
</tr>
</tbody>
<tbody id="t3" style="display:none;">
<tr>
<td class="tl"><span class="f_red">*</span> 号码列表</td>
<td class="f_red">
<?php
	echo '<select name="mobilelist" id="mobilelist"><option value="0">请选择号码列表</option>';
	$mails = glob(DT_ROOT.'/file/mobile/*.txt');
	if($mails) {
		foreach($mails as $m) {
			$tmp = basename($m);
			echo '<option value="'.$tmp.'">'.$tmp.'</option>';
		}
	} else {
		echo '<option value="">无号码列表</option>';
	}
	echo '</select>';
?>
&nbsp;&nbsp;<a href="javascript:" onclick="if(Dd('mobilelist').value != 0){window.open('file/mobile/'+Dd('mobilelist').value);}else{alert('请先选择号码列表');Dd('mobilelist').focus();}" class="t">[查看选中]</a>&nbsp;&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=make" class="t">[获取列表]</a>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 每轮发送短信数</td>
<td><input type="text" size="5" name="pernum" id="pernum" value="5"/></td>
</tr>
</tbody>
<tr>
<td class="tl"><span class="f_red">*</span> 短信内容</td>
<td>
<table cellpadding="0" cellspacing="0" width="100%" class="ctb">
<tr>
<td valign="top" width="250"><textarea name="content" id="content" rows="15" cols="35" onkeyup="S();" onblur="S();"></textarea></td>
<td valign="top" class="f_gray lh20">
- 当前已输入<strong id="len1">0</strong>字，签名<strong id="len2">0</strong>字，共<strong id="len3" class="f_red">0</strong>字，分<strong id="len4" class="f_blue">0</strong>条短信 (<?php echo $DT['sms_len'];?>字/条)<br/>
- 以上分条仅为系统估算，实际分条以运营商返回数据为准<br/>
- 内容支持变量，会员资料保存于$user数组<br/>
- 例 {$user[username]} 表示会员名<br/>
- 例 {$user[company]} 表示公司名<br/>
- 如果是给非会员发送短信，请不要使用变量<br/>
<?php if(!$DT['sms'] || !DT_CLOUD_UID || !DT_CLOUD_KEY) { ?>
<span class="f_red">- 注意：无法发送，未设置发送参数</span> <a href="?file=setting&tab=7" class="t">点此设置</a><br/>
<?php } else { ?>

<span class="f_red">
- 由于政策原因，并非所有内容都可以正常发送...<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://www.destoon.com/doc/use/29.html%23faq" target="_blank" class="t">了解详情</a><br/>
- 发送任何违法信息，帐号会被禁用且不退款<br/>
</span><br/>
<?php } ?>
<span id="dcontent" class="f_red"></span>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 短信签名</td>
<td><input type="text" size="35" name="sign" id="sign" value="<?php echo $DT['sms_sign'];?>" onkeyup="S();" onblur="S();"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="发 送" class="btn-g" onclick="Dd('preview').value=0;this.form.target='';"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="预 览" class="btn" onclick="Dd('preview').value=1;this.form.target='_blank';"/></div>
</form>
<script type="text/javascript">
var sms_len = <?php echo $DT['sms_len'];?>;
function S() {
	var sms_sign = Dd('sign').value;
	var len_1 = Dd('content').value.length;
	var len_2 = sms_sign.length;
	Dd('len1').innerHTML = len_1;
	Dd('len2').innerHTML = len_2;
	Dd('len3').innerHTML = len_1+len_2;
	Dd('len4').innerHTML = Math.ceil((len_1+len_2)/sms_len);
}
S();
var i = 1;
function ck(id) {
	Dd('t'+i).style.display='none';
	Dd('t'+id).style.display='';
	i = id;
}
ck(<?php echo $sendtype;?>);
function check() {
	var l;
	var f;
	f = 'content';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('内容不能为空', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>