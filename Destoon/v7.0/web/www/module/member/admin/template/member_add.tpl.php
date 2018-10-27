<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return Dcheck();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 会员组</td>
<td>
<input type="radio" name="post[regid]" value="6" id="g_6"onclick="reg(1);" checked/><label for="g_6"> <?php echo $GROUP['6']['groupname'];?></label>&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(is_array($GROUP)) { foreach($GROUP as $k => $v) { ?>
<?php if($k>6 && $v['vip']==0) { ?><input type="radio" name="post[regid]" value="<?php echo $k;?>" id="g_<?php echo $k;?>"onclick="reg(<?php echo $v['type'] ? 1 : 0;?>);"/><label for="g_<?php echo $k;?>"> <?php echo $GROUP[$k]['groupname'];?></label>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
<?php } } ?>
<input type="radio" name="post[regid]" value="5" id="g_5"onclick="reg(0);"/><label for="g_5"> <?php echo $GROUP['5']['groupname'];?></label>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员登录名</td>
<td><input type="text" size="20" name="post[username]" id="username" onblur="validator('username');"/>&nbsp;<span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 昵称</td>
<td><input type="text" size="20" name="post[passport]" id="passport" onblur="validator('passport');"/> <span class="f_gray">[支持中文]</span>&nbsp;<span id="dpassport" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 登录密码</td>
<td><input type="password" size="20" name="post[password]" id="password" onblur="validator('password');" autocomplete="off"/>&nbsp;<span id="dpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 重复输入密码</td>
<td><input type="password" size="20" name="post[cpassword]" id="cpassword" autocomplete="off"/>&nbsp;<span id="dcpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 电子邮件</td>
<td><input type="text" size="30" name="post[email]" id="email" onblur="validator('email');"/> <span class="f_gray">[不公开]</span>&nbsp;<span id="demail" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 真实姓名</td>
<td><input type="text" size="20" name="post[truename]" id="truename"/>&nbsp;<span id="dtruename" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 性别</td>
<td>
<input type="radio" name="post[gender]" value="1" checked="checked"/> 先生&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[gender]" value="2"/> 女士
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 所在地区</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', 0, '', 2);?>&nbsp;<span id="dareaid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 部门</td>
<td><input type="text" size="20" name="post[department]" id="department"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 职位</td>
<td><input type="text" size="20" name="post[career]" id="career"/></td>
</tr><tr>
<td class="tl"><span class="f_hid">*</span> 手机号码</td>
<td><input type="text" size="20" name="post[mobile]" id="mobile"/></td>
</tr>
<?php if($DT['im_qq']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> QQ</td>
<td><input type="text" size="20" name="post[qq]" id="qq"/></td>
</tr>
<?php } ?>
<?php if($DT['im_wx']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信</td>
<td><input type="text" size="20" name="post[wx]" id="wx"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信二维码</td>
<td><input name="post[wxqr]" type="text" size="60" id="wxqr"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>, 128, 128, Dd('wxqr').value, true, 'wxqr');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('wxqr').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('wxqr').value='';" class="jt">[删除]</span></td>
</tr>
<?php } ?>
<?php if($DT['im_ali']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 阿里旺旺</td>
<td><input type="text" size="20" name="post[ali]" id="ali"/></td>
</tr>
<?php } ?>
<?php if($DT['im_skype']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> Skype</td>
<td><input type="text" size="20" name="post[skype]" id="skype"/></td>
</tr>
<?php } ?>
<?php echo $MFD ? fields_html('<td class="tl">', '<td>', array(), $MFD) : '';?>
</table>
<div id="company_detail">
<div class="tt">公司资料</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 公司名称</td>
<td><input type="text" size="60" name="post[company]" id="company" onblur="validator('company');"/>&nbsp;<span id="dcompany" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司类型</td>
<td><?php echo dselect($COM_TYPE, 'post[type]', '请选择', '', 'id="type"', 0);?>&nbsp;<span id="dtype" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 形象图片</td>
<td><input name="post[thumb]" type="text" size="60" id="thumb"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, Dd('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt">[删除]</span><br/>
<span class="f_gray">建议使用LOGO、办公环境等标志性图片，最佳大小为<?php echo $MOD['thumb_width'];?>px*<?php echo $MOD['thumb_height'];?>px</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 主营行业</td>
<td>
<style type="text/css">
#cate-box {width:380px;margin-top:12px;background:#EEEEEE;padding:6px 12px;}
#cate-box div {height:24px;;line-height:24px;overflow:hidden;}
#cate-box span {float:right;}
</style>
<div id="catesch"></div>
<span id="cate"><?php echo ajax_category_select('', '请选择行业', 0, 4);?></span> &nbsp; <a href="javascript:cate_add(<?php echo $MOD['cate_max'];?>);" class="b">添加</a><?php if($DT['schcate_limit']) { ?> &nbsp; <a href="javascript:schcate(4);" class="t">搜索</a><?php } ?>
<div id="cate-box">
<div id="cate-tip">请添加主营行业，最多可添加 <strong class="f_red"><?php echo $MOD['cate_max'];?></strong> 个</div>
</div>
<input type="hidden" name="post[catid]" value="" id="catid"/>
<span id="dcatid" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 主要经营范围</td>
<td><input type="text" size="80" name="post[business]" id="business"/>&nbsp;<span id="dbusiness" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 经营模式</td>
<td>
<span id="com_mode"><?php echo dcheckbox($COM_MODE, 'post[mode][]', '', 'onclick="check_mode(this,'.$MOD['mode_max'].');"', 0);?></span> <span class="f_gray">(最多可选<?php echo $MOD['mode_max'];?>种)</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司规模</td>
<td><?php echo dselect($COM_SIZE, 'post[size]', '请选择规模', '', '', 0);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 注册资本</td>
<td><?php echo dselect($MONEY_UNIT, 'post[regunit]', '', '', '', 0);?> <input type="text" size="6" name="post[capital]" id="capital"/> 万</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司成立年份</td>
<td><input type="text" size="15" name="post[regyear]" id="regyear"/>&nbsp;<span id="dregyear" class="f_red"></span> <span class="f_gray">(年份，如：2004)</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司地址</td>
<td><input type="text" size="60" name="post[address]" id="address"/>&nbsp;<span id="daddress" class="f_red"></span></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> 邮政编码</td>
<td><input type="text" size="8" name="post[postcode]" id="postcode"/></td>
</tr>

<tr>
<td class="tl"><span class="f_red">*</span> 公司电话</td>
<td><input type="text" size="20" name="post[telephone]" id="telephone"/>&nbsp;<span id="dtelephone" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司传真</td>
<td><input type="text" size="20" name="post[fax]" id="fax"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司Email</td>
<td><input type="text" size="30" name="post[mail]" id="mail"/> <span class="f_gray">[公开]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司网址</td>
<td><input type="text" size="30" name="post[homepage]" id="homepage"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信公众号</td>
<td><input type="text" size="20" name="post[gzh]" id="gzh"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公众号二维码</td>
<td><input name="post[gzhqr]" type="text" size="60" id="gzhqr"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>, 128, 128, Dd('gzhqr').value, true, 'gzhqr');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('gzhqr').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('gzhqr').value='';" class="jt">[删除]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 销售的产品（服务）</td>
<td><input type="text" size="50" name="post[sell]" id="sell"/> <span class="f_gray">多个产品或服务请用'|'号隔开</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 采购的产品（服务）</td>
<td><input type="text" size="50" name="post[buy]" id="buy"/> <span class="f_gray">多个产品或服务请用'|'号隔开</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司介绍</td>
<td><textarea name="post[content]" id="content" class="dsn"></textarea>
<?php echo deditor($moduleid, 'content', $MOD['editor'], '100%', 300);?><br/><span id="dcontent" class="f_red"></span></td>
</tr>
<?php echo $CFD ? fields_html('<td class="tl">', '<td>', array(), $CFD) : '';?>
</table>
</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 会员资料是否完整</td>
<td>
<input type="radio" name="post[edittime]" value="1"  checked/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[edittime]" value="0"/> 否&nbsp;&nbsp;
<span class="f_gray">如果选择是，系统将不再提示会员完善资料</span>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="确 定" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check_mode(c, m) {
	if($('#com_mode input:checkbox:checked').length > m) {confirm('最多可选'+m+'种经营模式'); c.checked = false;}
}
function cate_del(id) {
	$('#cate-'+id).remove();
	var cids = $('#catid').val().replace(','+id+',', ',');
	$('#catid').val(cids);
	if($('#cate-box').html().indexOf('span') == -1) {
		$('#cate-tip').show();
	} else {
		$('#cate-tip').hide();
	}
}
function cate_add(max) {
	if($('#cate-box div').length > max) {
		Dmsg('最多可以添加'+max+'个行业', 'catid');
		return;
	}
	var cid = $('#catid_1').val();
	if(cid == 0) {
		Dmsg('请选择行业', 'catid');
		return;
	}
	if($('#cate-box').html().indexOf('cate-'+cid) != -1) {
		Dmsg('所选行业已经存在', 'catid');
		return;
	}
	var str = '';
	$('#cate option:selected').each(function() {
		if($(this).val()) str += $(this).text()+'/';
	});
	if(str) {
		str = str.replace('&amp;', '&');
		str = str.replace('请选择行业/', '');
		str = str.substring(0, str.length-1);
		$('#cate-box').append('<div id="cate-'+cid+'"><span><a href="javascript:cate_del('+cid+');" class="b">删除</a></span>'+str+'</div>');
		var cids = $('#catid').val() ? $('#catid').val() +cid+',' : ','+cid+',';
		$('#catid').val(cids);
		$('#cate-tip').hide();
	} else {
		Dmsg('请选择行业', 'catid');
	}
}
var vid = '';
function validator(id) {
	if(!Dd(id).value) return false;
	vid = id;
	$.post(AJPath, 'moduleid=<?php echo $moduleid;?>&action=member&job='+id+'&value='+Dd(id).value, function(data) {
		Dd('d'+vid).innerHTML = data ? '<img src="'+DTPath+'file/image/check-ko.png" width="16" height="16" align="absmiddle"/> '+data : '';
	});
}
function reg(type) {
	if(type) {
		Ds('company_detail');
	} else {
		Dh('company_detail');
	}
}
function Dcheck() {
	if(Dd('username').value == '') {
		Dmsg('请填写会员登录名', 'username');
		return false;
	}
	if(Dd('password').value == '') {
		Dmsg('请填写会员登录密码', 'password');
		return false;
	}
	if(Dd('cpassword').value == '') {
		Dmsg('请重复输入密码', 'cpassword');
		return false;
	}
	if(Dd('password').value != Dd('cpassword').value) {
		Dmsg('两次输入的密码不一致', 'password');
		return false;
	}
	if(Dd('email').value == '') {
		Dmsg('请填写电子邮箱', 'email');
		return false;
	}
	if(Dd('truename').value == '') {
		Dmsg('请填写真实姓名', 'truename');
		return false;
	}
	if(Dd('areaid_1').value == 0) {
		Dmsg('请选择所在地', 'areaid');
		return false;
	}
	<?php echo $MFD ? fields_js($MFD) : '';?>
	if(Dd('company_detail').style.display != 'none') {
		<?php echo $CFD ? fields_js($CFD) : '';?>
		if(Dd('company').value == '') {
			Dmsg('请填写公司名称', 'company');
			return false;
		}
		if(Dd('type').value == '') {
			Dmsg('请选择公司类型', 'type');
			return false;
		}
		if(Dd('catid').value.length < 2) {
			Dmsg('请选择公司主营行业', 'catid');
			return false;
		}
		if(Dd('business').value.length < 2) {
			Dmsg('请填写主要经营范围', 'business');
			return false;
		}
		if(Dd('regyear').value.length < 4) {
			Dmsg('请填写公司成立年份', 'regyear');
			return false;
		}
		if(Dd('address').value.length < 2) {
			Dmsg('请填写公司地址', 'address');
			return false;
		}
		if(Dd('telephone').value.length < 6) {
			Dmsg('请填写公司电话', 'telephone');
			return false;
		}
		if(FCKLen('content') < 10) {
			Dmsg('公司介绍最少10字，当前已经输入'+FCKLen('content')+'字', 'content');
			return false;
		}
	}
	return true;
}
Menuon(0);
</script>
<?php include tpl('footer');?>