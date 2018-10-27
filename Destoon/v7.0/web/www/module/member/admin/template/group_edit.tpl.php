<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="groupid" value="<?php echo $groupid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 会员组名称</td>
<td><input type="text" size="20" name="groupname" id="groupname" value="<?php echo $groupname;?>"/> <span id="dgroupname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员类型</td>
<td>
<input type="radio" name="setting[type]" value="1" <?php if($type) echo 'checked';?>/> 企业会员&nbsp;&nbsp;
<input type="radio" name="setting[type]" value="0" <?php if(!$type) echo 'checked';?>/> 个人会员
</td>
</tr>
<tr id="mode" style="display:;">
<td class="tl"><span class="f_red">*</span> 会员模式</td>
<td>
<input type="radio" name="setting[fee_mode]" value="1" <?php if($fee_mode) echo 'checked';?> onclick="Ds('mode_1');Dh('mode_0');"/> 收费会员&nbsp;&nbsp;
<input type="radio" name="setting[fee_mode]" value="0" <?php if(!$fee_mode) echo 'checked';?> onclick="Ds('mode_0');Dh('mode_1');"/> 免费会员
</td>
</tr>
<tbody id="mode_1" style="display:<?php echo $fee_mode ? '' : 'none';?>">
<tr>
<td class="tl"><span class="f_red">*</span> 收费设置</td>
<td><input type="text" size="20" name="setting[fee]" id="fee" value="<?php echo $fee;?>"/> <?php echo $DT['money_unit'];?>/年 <span class="f_gray">免费会员请填0</span> <span id="dfee" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> <?php echo VIP;?>指数</td>
<td><input type="text" size="2" name="vip" id="vip" value="<?php echo $vip;?>"/> <span class="f_gray">免费会员请填0，收费会员请填1-9数字</span> <span id="dvip" class="f_red"></span></td>
</tr>
</tbody>
<tr id="mode_0" style="display:<?php echo $fee_mode ? 'none' : '';?>">
<td class="tl"><span class="f_red">*</span> 享受折扣</td>
<td><input type="text" size="2" name="setting[discount]" id="discount" value="<?php echo $discount;?>"/> % <span class="f_gray">折扣仅限系统收费，不针对会员产品</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 交易佣金</td>
<td><input type="text" size="2" name="setting[commission]" id="commission" value="<?php echo $commission;?>"/> % <span class="f_gray">会员通过商城、供应、团购完成交易后，系统扣除交易额一定比例作为网站服务费用</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 显示顺序</td>
<td><input type="text" size="2" name="listorder" id="listorder" value="<?php echo $listorder;?>"/>  <span class="f_gray">数字越小越靠前</span></td>
</tr>
</table>
<div class="tt">会员权限</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">设置说明</td>
<td>数量限制填 <strong>0</strong> 则表示不限&nbsp;&nbsp;&nbsp;填 <strong>-1</strong> 表示禁止使用</td>
</tr>
<tr>
<td class="tl">允许进入商户后台</td>
<td>
<input type="radio" name="setting[biz]" value="1" <?php if($biz) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[biz]" value="0" <?php if(!$biz) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">允许在会员升级页面显示</td>
<td>
<input type="radio" name="setting[grade]" value="1" <?php if($grade) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[grade]" value="0" <?php if(!$grade) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">允许在会员注册页面显示</td>
<td>
<input type="radio" name="setting[reg]" value="1" <?php if($reg) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[reg]" value="0" <?php if(!$reg) echo 'checked';?>> 否 此设置对<?php echo VIP;?>会员无效
</td>
</tr>
<tr>
<td class="tl">编辑器工具按钮</td>
<td>
<select name="setting[editor]">
<option value="Default"<?php if($editor == 'Default') echo ' selected';?>>全部</option>
<option value="Destoon"<?php if($editor == 'Destoon') echo ' selected';?>>精简</option>
<option value="Simple"<?php if($editor == 'Simple') echo ' selected';?>>简洁</option>
<option value="Basic"<?php if($editor == 'Basic') echo ' selected';?>>基础</option>
</select>&nbsp;
<?php tips('全部按钮允许会员编辑源代码和插入FLASH和视频文件<br/>为了防止被恶意利用，建议仅对受信任的会员组开启');?>
</td>
</tr>
<tr>
<td class="tl">允许上传文件</td>
<td>
<input type="radio" name="setting[upload]" value="1" <?php if($upload) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[upload]" value="0" <?php if(!$upload) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">允许上传的文件类型</td>
<td><input name="setting[uploadtype]" type="text" value="<?php echo $uploadtype;?>" size="60"/> <?php tips('用|号隔开文件后缀，留空表示继承网站设置');?></td>
</tr>
<tr>
<td class="tl">允许上传大小限制</td>
<td><input name="setting[uploadsize]" type="text" value="<?php echo $uploadsize;?>" size="10"/> Kb (1024Kb=1M) 不填或填0表示继承网站设置</td>
</tr>
<tr>
<td class="tl">单条信息上传数量限制</td>
<td><input name="setting[uploadlimit]" type="text" value="<?php echo $uploadlimit;?>" size="5"/> <?php tips('一条信息内最多上传文件数量限制，0为不限制');?></td>
</tr>
<tr>
<td class="tl">24小时上传数量限制</td>
<td><input name="setting[uploadday]" type="text" value="<?php echo $uploadday;?>" size="5"/> <?php tips('24小时内最大文件上传数量限制，0为不限制<br/>此项会增加服务器压力，且在开启上传记录的情况下有效');?></td>
</tr>
<tr>
<td class="tl">上传一张图片扣积分</td>
<td><input name="setting[uploadcredit]" type="text" value="<?php echo $uploadcredit;?>" size="5"/> <?php tips('积分不足时将无法上传，0为不限制');?></td>
</tr>
<tr>
<td class="tl">产品图片数量限制</td>
<td>
<input type="radio" name="setting[uploadpt]" value="1" <?php if($uploadpt) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[uploadpt]" value="0" <?php if(!$uploadpt) echo 'checked';?>> 否 <?php tips('如果选择是，产品图片只允许传1张，如果不限可以传3张，此项不针对'.VIP.'会员');?>
</td>
</tr>
<tr>
<td class="tl">发布信息需要审核</td>
<td>
<input type="radio" name="setting[check]" value="1" <?php if($check) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check]" value="0" <?php if(!$check) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">发布信息启用验证码</td>
<td>
<input type="radio" name="setting[captcha]" value="1" <?php if($captcha) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha]" value="0" <?php if(!$captcha) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">发布信息启用验证问题</td>
<td>
<input type="radio" name="setting[question]" value="1" <?php if($question) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question]" value="0" <?php if(!$question) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许申请提现</td>
<td>
<input type="radio" name="setting[cash]" value="1" <?php if($cash) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[cash]" value="0" <?php if(!$cash) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用客服中心</td>
<td>
<input type="radio" name="setting[ask]" value="1" <?php if($ask) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ask]" value="0" <?php if(!$ask) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用商机订阅</td>
<td>
<input type="radio" name="setting[mail]" value="1" <?php if($mail) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[mail]" value="0" <?php if(!$mail) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用手机短信</td>
<td>
<input type="radio" name="setting[sms]" value="1" <?php if($sms) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[sms]" value="0" <?php if(!$sms) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许发送电子邮件</td>
<td>
<input type="radio" name="setting[sendmail]" value="1" <?php if($sendmail) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[sendmail]" value="0" <?php if(!$sendmail) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许管理订单</td>
<td>
<input type="radio" name="setting[trade_order]" value="1" <?php if($trade_order) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[trade_order]" value="0" <?php if(!$trade_order) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许管理团购订单</td>
<td>
<input type="radio" name="setting[group_order]" value="1" <?php if($group_order) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[group_order]" value="0" <?php if(!$group_order) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许竞价排名</td>
<td>
<input type="radio" name="setting[spread]" value="1" <?php if($spread) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[spread]" value="0" <?php if(!$spread) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许广告预定</td>
<td>
<input type="radio" name="setting[ad]" value="1" <?php if($ad) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad]" value="0" <?php if(!$ad) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">允许发起聊天请求</td>
<td>
<input type="radio" name="setting[chat]" value="1" <?php if($chat) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[chat]" value="0" <?php if(!$chat) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">收件箱数量限制</td>
<td>
<input type="text" name="setting[inbox_limit]" size="5" value="<?php echo $inbox_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">商友数量限制</td>
<td>
<input type="text" name="setting[friend_limit]" size="5" value="<?php echo $friend_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">商机收藏数量限制</td>
<td>
<input type="text" name="setting[favorite_limit]" size="5" value="<?php echo $favorite_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">贸易提醒数量限制</td>
<td>
<input type="text" name="setting[alert_limit]" size="5" value="<?php echo $alert_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">收货地址数量限制</td>
<td>
<input type="text" name="setting[address_limit]" size="5" value="<?php echo $address_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">运费模板数量限制</td>
<td>
<input type="text" name="setting[express_limit]" size="5" value="<?php echo $express_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">优惠活动数量限制</td>
<td>
<input type="text" name="setting[promo_limit]" size="5" value="<?php echo $promo_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">每日可发站内信限制</td>
<td>
<input type="text" name="setting[message_limit]" size="5" value="<?php echo $message_limit;?>"/> <?php echo tips('询盘和报价为特殊的站内信，发送一次询盘或者报价会消耗一次站内信发送机会');?>
</td>
</tr>

<tr>
<td class="tl">每日询盘次数限制</td>
<td>
<input type="text" name="setting[inquiry_limit]" size="5" value="<?php echo $inquiry_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">每日报价次数限制</td>
<td>
<input type="text" name="setting[price_limit]" size="5" value="<?php echo $price_limit;?>"/>
</td>
</tr>


<tr>
<td class="tl">自定义分类限制</td>
<td>
<input type="text" name="setting[type_limit]" size="5" value="<?php echo $type_limit;?>"/>
</td>
</tr>
</table>

<div class="tt">公司主页</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">拥有公司主页</td>
<td>
<input type="radio" name="setting[homepage]" value="1" <?php if($homepage) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[homepage]" value="0" <?php if(!$homepage) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">默认公司模板</td>
<td>
<?php echo homepage_select('setting[styleid]', '请选择', $groupid, $styleid, 'id="styleid"');?>&nbsp;&nbsp;
<a href="javascript:" onclick="if(Dd('styleid').value>0)window.open('?moduleid=2&file=style&action=show&itemid='+Dd('styleid').value);" class="t">[预览]</a>
</td>
</tr>
<tr>
<td class="tl">允许自定义主页设置</td>
<td>
<input type="radio" name="setting[home]" value="1" <?php if($home) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[home]" value="0" <?php if(!$home) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">允许自定义菜单</td>
<td>
<input type="radio" name="setting[home_menu]" value="1" <?php if($home_menu) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[home_menu]" value="0" <?php if(!$home_menu) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">可用菜单</td>
<td>
<ul class="mods">
<?php
	$_menu_c = ','.$menu_c.',';
	foreach($HMENU as $k=>$m) {
		echo '<li><input type="checkbox" name="setting[menu_c][]" value="'.$k.'" '.(strpos($_menu_c, ','.$k.',') !== false ? 'checked' : '').' id="menu_c_'.$k.'"/><label for="menu_c_'.$k.'"> '.$m.'</label></li>';
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">默认菜单</td>
<td>
<ul class="mods">
<?php
	$_menu_d = ','.$menu_d.',';
	foreach($HMENU as $k=>$m) {
		echo '<li><input type="checkbox" name="setting[menu_d][]" value="'.$k.'" '.(strpos($_menu_d, ','.$k.',') !== false ? 'checked' : '').' id="menu_d_'.$k.'"/><label for="menu_d_'.$k.'"> '.$m.'</label></li>';
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">允许自定义侧栏</td>
<td>
<input type="radio" name="setting[home_side]" value="1" <?php if($home_side) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[home_side]" value="0" <?php if(!$home_side) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">可用侧栏</td>
<td>
<ul class="mods">
<?php
	$_side_c = ','.$side_c.',';
	foreach($HSIDE as $k=>$m) {
		echo '<li><input type="checkbox" name="setting[side_c][]" value="'.$k.'" '.(strpos($_side_c, ','.$k.',') !== false ? 'checked' : '').' id="side_c_'.$k.'"/><label for="side_c_'.$k.'"> '.$m.'</label></li>';
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">默认侧栏</td>
<td>
<ul class="mods">
<?php
	$_side_d = ','.$side_d.',';
	foreach($HSIDE as $k=>$m) {
		echo '<li><input type="checkbox" name="setting[side_d][]" value="'.$k.'" '.(strpos($_side_d, ','.$k.',') !== false ? 'checked' : '').' id="side_d_'.$k.'"/><label for="side_d_'.$k.'"> '.$m.'</label></li>';
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">允许自定义首页</td>
<td>
<input type="radio" name="setting[home_main]" value="1" <?php if($home_main) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[home_main]" value="0" <?php if(!$home_main) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">可用首页</td>
<td>
<ul class="mods">
<?php
	$_main_c = ','.$main_c.',';
	foreach($HMAIN as $k=>$m) {
		echo '<li><input type="checkbox" name="setting[main_c][]" value="'.$k.'" '.(strpos($_main_c, ','.$k.',') !== false ? 'checked' : '').' id="main_c_'.$k.'"/><label for="main_c_'.$k.'"> '.$m.'</label></li>';
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">默认首页</td>
<td>
<ul class="mods">
<?php
	$_main_d = ','.$main_d.',';
	foreach($HMAIN as $k=>$m) {
		echo '<li><input type="checkbox" name="setting[main_d][]" value="'.$k.'" '.(strpos($_main_d, ','.$k.',') !== false ? 'checked' : '').' id="main_d_'.$k.'"/><label for="main_d_'.$k.'"> '.$m.'</label></li>';
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">允许选择模板</td>
<td>
<input type="radio" name="setting[style]" value="1" <?php if($style) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[style]" value="0" <?php if(!$style) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用第三方地图</td>
<td>
<input type="radio" name="setting[map]" value="1" <?php if($map) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[map]" value="0" <?php if(!$map) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用第三方统计</td>
<td>
<input type="radio" name="setting[stats]" value="1" <?php if($stats) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[stats]" value="0" <?php if(!$stats) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用第三方客服</td>
<td>
<input type="radio" name="setting[kf]" value="1" <?php if($kf) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[kf]" value="0" <?php if(!$kf) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">公司新闻数量限制</td>
<td>
<input type="text" name="setting[news_limit]" size="5" value="<?php echo $news_limit;?>"/>
</td>
</tr>


<tr>
<td class="tl">公司单页数量限制</td>
<td>
<input type="text" name="setting[page_limit]" size="5" value="<?php echo $page_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">荣誉资质数量限制</td>
<td>
<input type="text" name="setting[honor_limit]" size="5" value="<?php echo $honor_limit;?>"/>
</td>
</tr>
<tr>
<td class="tl">友情链接数量限制</td>
<td>
<input type="text" name="setting[link_limit]" size="5" value="<?php echo $link_limit;?>"/>
</td>
</tr>
</table>
<div class="tt">信息发布</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">允许发布信息的模块</td>
<td>
<ul class="mods">
<?php
	$moduleids = explode(',', $moduleids);
	foreach($MODULE as $m) {
		if($m['moduleid'] > 4 && is_file(DT_ROOT.'/module/'.$m['module'].'/my.inc.php')) {
			echo '<li><input type="checkbox" name="setting[moduleids][]" value="'.$m['moduleid'].'" '.(in_array($m['moduleid'], $moduleids) ? 'checked' : '').' id="mod_'.$m['moduleid'].'"/><label for="mod_'.$m['moduleid'].'"> '.$m['name'].'</label></li>';
		}
	}
?>
</ul>
</td>
</tr>
<tr>
<td class="tl">允许发布个人简历</td>
<td>
<input type="radio" name="setting[resume]" value="1" <?php if($resume){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[resume]" value="0" <?php if(!$resume){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
如果没有开启人才模块，请选否
</td>
</tr>

<tr>
<td class="tl">开启强制邮件认证</td>
<td>
<input type="radio" name="setting[vemail]" value="1" <?php if($vemail){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vemail]" value="0" <?php if(!$vemail){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
开启之后，邮件认证成功才可以发布信息
</td>
</tr>
<tr>
<td class="tl">开启强制手机认证</td>
<td>
<input type="radio" name="setting[vmobile]" value="1" <?php if($vmobile){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vmobile]" value="0" <?php if(!$vmobile){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
开启之后，手机认证成功才可以发布信息
</td>
</tr>
<tr>
<td class="tl">开启强制姓名认证</td>
<td>
<input type="radio" name="setting[vtruename]" value="1" <?php if($vtruename){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vtruename]" value="0" <?php if(!$vtruename){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
开启之后，姓名认证成功才可以发布信息
</td>
</tr>
<tr>
<td class="tl">开启强制公司认证</td>
<td>
<input type="radio" name="setting[vcompany]" value="1" <?php if($vcompany){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vcompany]" value="0" <?php if(!$vcompany){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
开启之后，公司认证成功才可以发布信息
</td>
</tr>
<tr>
<td class="tl">开启强制缴纳保证金</td>
<td>
<input type="radio" name="setting[vdeposit]" value="1" <?php if($vdeposit){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vdeposit]" value="0" <?php if(!$vdeposit){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
开启之后，公司缴纳保证金才可以发布信息
</td>
</tr>
<tr>
<td class="tl">开启强制关注微信公众号</td>
<td>
<input type="radio" name="setting[vweixin]" value="1" <?php if($vweixin){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vweixin]" value="0" <?php if(!$vweixin){ ?>checked <?php } ?>/> 否&nbsp;&nbsp;
开启之后，关注网站微信号才可以发布信息
</td>
</tr>
<tr>
<td class="tl">允许删除信息</td>
<td>
<input type="radio" name="setting[delete]" value="1" <?php if($delete) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[delete]" value="0" <?php if(!$delete) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许复制信息</td>
<td>
<input type="radio" name="setting[copy]" value="1" <?php if($copy) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[copy]" value="0" <?php if(!$copy) echo 'checked';?>> 否 

复制信息可显著提高发布信息效率
</td>
</tr>

<tr>
<td class="tl">发布信息时间间隔</td>
<td>
<input type="text" name="setting[add_limit]" size="5" value="<?php echo $add_limit;?>"/>
&nbsp;&nbsp;单位： 秒&nbsp;&nbsp;填 0 表示不限制&nbsp;&nbsp;填正数表示发布两次发布时间间隔
</td>
</tr>

<tr>
<td class="tl">1小时发布信息数量</td>
<td>
<input type="text" name="setting[hour_limit]" size="5" value="<?php echo $hour_limit;?>"/>
&nbsp;&nbsp;填 0 表示不限制&nbsp;&nbsp;填正数表示1小时内在单模块发布信息数量限制（防灌水）
</td>
</tr>

<tr>
<td class="tl">24小时发布信息数量</td>
<td>
<input type="text" name="setting[day_limit]" size="5" value="<?php echo $day_limit;?>"/>
&nbsp;&nbsp;填 0 表示不限制&nbsp;&nbsp;填正数表示24小时内在单模块发布信息数量限制（防灌水）
</td>
</tr>

<tr>
<td class="tl">刷新信息时间间隔</td>
<td>
<input type="text" name="setting[refresh_limit]" size="5" value="<?php echo $refresh_limit;?>"/>
&nbsp;&nbsp;单位： 秒&nbsp;&nbsp;填 -1 表示不允许刷新&nbsp;&nbsp;填 0 表示不限制时间间隔&nbsp;&nbsp;填正数表示限制两次刷新时间
</td>
</tr>

<tr>
<td class="tl">允许修改信息时间</td>
<td>
<input type="text" name="setting[edit_limit]" size="5" value="<?php echo $edit_limit;?>"/>
&nbsp;&nbsp;单位： 天&nbsp;&nbsp;填 -1 表示不允许修改&nbsp;&nbsp;填 0 表示不限制时间修改&nbsp;&nbsp;填正数表示发布时间超出后不可修改
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'groupname';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请填写会员组名称', f);
		return false;
	}
	return true;
}
<?php if($groupid == 5 || $groupid == 6) { ?>
Dh('mode');
<?php } ?>
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>