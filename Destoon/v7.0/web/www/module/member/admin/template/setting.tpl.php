<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
$menus = array (
  array('基本设置'),
  array('公司相关'),
  array('财务相关'),
  array('支付接口'),
  array($DT['credit_name'].'规则'),
  array('会员整合'),
  array('一键登录'),
);
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
<div id="Tabs0" style="display:">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">新用户注册</td>
<td>
<input type="radio" name="setting[enable_register]" value="1" <?php if($enable_register) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[enable_register]" value="0" <?php if(!$enable_register) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">用户名长度</td>
<td>
<input type="text" size="3" name="setting[minusername]" value="<?php echo $minusername;?>"/>
至
<input type="text" size="3" name="setting[maxusername]" value="<?php echo $maxusername;?>"/>
字符<?php tips('建议设置为4-20个字符之间');?>
</td>
</tr>
<tr>
<td class="tl">用户名保留关键字</td>
<td><textarea name="setting[banusername]" style="width:96%;height:30px;overflow:visible;"><?php echo $banusername;?></textarea><?php tips('含有保留的关键字的用户名将被禁止注册<br/>多个保留关键字请用|隔开');?>
</td>
</tr>
<tr>
<td class="tl">保留关键字匹配模式</td>
<td>
<input type="radio" name="setting[banmodeu]" value="1" <?php if($banmodeu) echo 'checked';?>/> 相同&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[banmodeu]" value="0" <?php if(!$banmodeu) echo 'checked';?>/> 包含<?php tips('选择包含时，当用户名中含有关键字时即禁止注册<br/>选择相同时，当用户名和关键字相同时才禁止注册');?>
</td>
</tr>
<tr>
<td class="tl">用户密码长度</td>
<td>
<input type="text" size="3" name="setting[minpassword]" value="<?php echo $minpassword;?>"/>
至
<input type="text" size="3" name="setting[maxpassword]" value="<?php echo $maxpassword;?>"/>
字符<?php tips('过短的密码不利于用户的帐户安全<br/>建议设置为6-20个字符之间，不要超过30位');?>
</td>
</tr>
<tr>
<td class="tl">用户密码强度</td>
<td>
<input type="checkbox" name="setting[mixpassword][]" value="1"<?php echo strpos(','.$mixpassword.',', ',1,') !== false ? ' checked' : '';?>/> 数字&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="setting[mixpassword][]" value="2"<?php echo strpos(','.$mixpassword.',', ',2,') !== false ? ' checked' : '';?>/> 小写字母&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="setting[mixpassword][]" value="3"<?php echo strpos(','.$mixpassword.',', ',3,') !== false ? ' checked' : '';?>/> 大写字母&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="setting[mixpassword][]" value="4"<?php echo strpos(','.$mixpassword.',', ',4,') !== false ? ' checked' : '';?>/> 标点符号&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
<tr>
<td class="tl">公司名保留关键字</td>
<td><textarea name="setting[bancompany]" style="width:96%;height:30px;overflow:visible;"><?php echo $bancompany;?></textarea><?php tips('含有保留的关键字的公司名将被禁止注册<br/>多个保留关键字请用|隔开');?>
</td>
</tr>
<tr>
<td class="tl">保留关键字匹配模式</td>
<td>
<input type="radio" name="setting[banmodec]" value="1" <?php if($banmodec) echo 'checked';?>/> 相同&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[banmodec]" value="0" <?php if(!$banmodec) echo 'checked';?>/> 包含<?php tips('选择包含时，当公司名中含有关键字时即禁止注册<br/>选择相同时，当公司名和关键字相同时才禁止注册');?>
</td>
</tr>
<tr>
<td class="tl">电子邮件禁止域名</td>
<td><textarea name="setting[banemail]" style="width:96%;height:30px;overflow:visible;"><?php echo $banemail;?></textarea><?php tips('例如禁止abc@xxx.com的邮件注册，可以填写xxx.com<br/>多个域名请用|隔开');?>
</td>
</tr>
<tr>
<td class="tl">新用户注册验证</td>
<td>
<input type="radio" name="setting[checkuser]" value="0" <?php if(!$checkuser) echo 'checked';?>> 不验证&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[checkuser]" value="1" <?php if($checkuser==1) echo 'checked';?>> 人工审核&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[checkuser]" value="2" <?php if($checkuser==2) echo 'checked';?>> 邮件验证&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[checkuser]" value="3" <?php if($checkuser==3) echo 'checked';?>> 短信验证&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[checkuser]" value="4" <?php if($checkuser==4) echo 'checked';?>> 邮件或短信验证
</td>
</tr>

<tr>
<td class="tl">注册发送欢迎站内信件</td>
<td>
<input type="radio" name="setting[welcome_message]" value="1" <?php if($welcome_message) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[welcome_message]" value="0" <?php if(!$welcome_message) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">注册发送欢迎电子邮件</td>
<td>
<input type="radio" name="setting[welcome_email]" value="1" <?php if($welcome_email) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[welcome_email]" value="0" <?php if(!$welcome_email) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">注册发送欢迎手机短信</td>
<td>
<input type="radio" name="setting[welcome_sms]" value="1" <?php if($welcome_sms) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[welcome_sms]" value="0" <?php if(!$welcome_sms) echo 'checked';?>/> 关闭<?php tips('短信费用由网站支付，建议开启邮件验证码注册后，再开启此功能，以过滤恶意注册');?>
</td>
</tr>

<tr>
<td class="tl">注册验证码</td>
<td>
<input type="radio" name="setting[captcha_register]" value="1" <?php if($captcha_register) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[captcha_register]" value="0" <?php if(!$captcha_register) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">注册验证问题</td>
<td>
<input type="radio" name="setting[question_register]" value="1" <?php if($question_register) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[question_register]" value="0" <?php if(!$question_register) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">注册赠送<?php echo $DT['money_name'];?></td>
<td>
<input type="text" size="5" name="setting[money_register]" value="<?php echo $money_register;?>"/> <?php echo $DT['money_unit'];?>
</td>
</tr>
<tr>
<td class="tl">注册赠送<?php echo $DT['credit_name'];?></td>
<td>
<input type="text" size="5" name="setting[credit_register]" value="<?php echo $credit_register;?>"/> <?php echo $DT['credit_unit'];?>
</td>
</tr>
<tr>
<td class="tl">注册赠送短信</td>
<td>
<input type="text" size="5" name="setting[sms_register]" value="<?php echo $sms_register;?>"/> 条
</td>
</tr>

<tr>
<td class="tl">禁止代理服务器注册</td>
<td>
<input type="radio" name="setting[defend_proxy]" value="1" <?php if($defend_proxy) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[defend_proxy]" value="0" <?php if(!$defend_proxy) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">注册客户端屏蔽</td>
<td><textarea name="setting[banagent]" style="width:96%;height:30px;overflow:visible;"><?php echo $banagent;?></textarea><?php tips('群发软件可以伪造IP，但是部分软件发送的客户端信息相同<br/>例如某群发软件的客户端信息全部包含 .NET CLR 1.0.3705<br/>可在此直接屏蔽含有此类特征码的客户端注册<br/>多个特征码请用 | 分隔');?>
</td>
</tr>
<tr>
<td class="tl">IP注册间隔限制(小时)</td>
<td>
<input type="text" size="3" name="setting[iptimeout]" value="<?php echo $iptimeout;?>"/><?php tips('同一IP在本时间间隔内将只能注册一个帐号，填0为不限制');?>
</td>
</tr>
<tr>
<td class="tl">会员便签默认值</td>
<td><textarea name="setting[usernote]" style="width:96%;height:30px;overflow:visible;"><?php echo $usernote;?></textarea><?php tips('会员便签没有填写时，默认显示此值');?>
</td>
</tr>
<tr>
<td class="tl">会员资料修改需审核</td>
<td>
<?php
$ECK = array(
	'thumb' => '形象图片',
	'areaid' => '所在地区',
	'type' => '公司类型',
	'business' => '经营范围',
	'regyear' => '成立年份',
	'capital' => '注册资本',
	'address' => '公司地址',
	'telephone' => '联系电话',
	'gzh' => '微信公众号',
	'gzhqr' => '公众号二维码',
	'content' => '公司介绍',
);
foreach($ECK as $k=>$v) {
	echo '<input type="checkbox" name="setting[edit_check][]" value="'.$k.'" id="check_'.$k.'" '.(strpos(','.$edit_check.',', ','.$k.',') !== false ? ' checked' : '').'/><label for="check_'.$k.'"> '.$v.' </label>';
}
?>
</td>
</tr>
<tr>
<td class="tl">登录失败次数限制</td>
<td><input type="text" size="3" name="setting[login_times]" value="<?php echo $login_times;?>"/> 次登录失败后锁定登录 <input type="text" size="3" name="setting[lock_hour]" value="<?php echo $lock_hour;?>"/> 小时
</td>
</tr>
<tr>
<td class="tl">用户登录启用验证码</td>
<td>
<input type="radio" name="setting[captcha_login]" value="1" <?php if($captcha_login) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[captcha_login]" value="0" <?php if(!$captcha_login) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">用户登录有效期</td>
<td><input type="text" size="6" name="setting[login_time]" value="<?php echo $login_time;?>"/> 秒 <?php tips('0代表关闭浏览器自动退出登录（安全），大于0的数字代表记忆登录的时间，如果设置，最小值为86400（24小时）');?></td>
</tr>
<tr>
<td class="tl">手机验证码登录</td>
<td>
<input type="radio" name="setting[login_sms]" value="1" <?php if($login_sms) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[login_sms]" value="0" <?php if(!$login_sms) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">微信扫码登录</td>
<td>
<input type="radio" name="setting[login_scan]" value="1" <?php if($login_scan) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[login_scan]" value="0" <?php if(!$login_scan) echo 'checked';?>/> 关闭 <?php tips('请确保微信登录已经开启，否则请勿开启');?>
</td>
</tr>
<tr>
<td class="tl">站内短信同时最多发送至</td>
<td>
<input type="text" size="3" name="setting[maxtouser]" value="<?php echo $maxtouser;?>"/> 位会员<?php tips('最小填1，例如填5则表示，同一信件一次最多可以同时发送给5位会员');?>
</td>
</tr>
<tr>
<td class="tl">发送站内短信启用验证码</td>
<td>
<input type="radio" name="setting[captcha_sendmessage]" value="2" <?php if($captcha_sendmessage == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_sendmessage]" value="1" <?php if($captcha_sendmessage == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_sendmessage]" value="0" <?php if($captcha_sendmessage == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">修改资料启用验证码</td>
<td>
<input type="radio" name="setting[captcha_edit]" value="2" <?php if($captcha_edit == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_edit]" value="1" <?php if($captcha_edit == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_edit]" value="0" <?php if($captcha_edit == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">修改商铺设置启用验证码</td>
<td>
<input type="radio" name="setting[captcha_home]" value="2" <?php if($captcha_home == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_home]" value="1" <?php if($captcha_home == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_home]" value="0" <?php if($captcha_home == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">验证邮件/短信有效期</td>
<td>
<input type="text" size="3" name="setting[auth_days]" value="<?php echo $auth_days;?>"/> 天<?php tips('验证邮件链接超过有效期天数将失效 填0为不限制<br/>如果是短信，则为对应的乘10分钟，例如设置3，代表30分钟内有效');?>
</td>
</tr>

<tr>
<td class="tl">贸易提醒模块ID</td>
<td>
<input type="text" size="20" name="setting[alertid]" value="<?php echo $alertid;?>"/> <?php tips('例如5|6代表 供应|求购，模块ID至少为5');?>
</td>
</tr>
<tr>
<td class="tl">贸易提醒需审核</td>
<td>
<input type="radio" name="setting[alert_check]" value="2" <?php if($alert_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[alert_check]" value="1" <?php if($alert_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[alert_check]" value="0" <?php if($alert_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">在线交谈内容长度限制</td>
<td><input type="text" size="5" name="setting[chat_maxlen]" value="<?php echo $chat_maxlen;?>"/> 字符</td>
</tr>
<tr>
<td class="tl">在线交谈超时限制</td>
<td><input type="text" size="5" name="setting[chat_timeout]" value="<?php echo $chat_timeout;?>"/> 秒<?php tips('当交谈双方超过此时间没有发言时，系统自动断开以减轻服务器压力，填0表示不自动断开');?></td>
</tr>
<tr>
<td class="tl">在线交谈轮询时间</td>
<td><input type="text" size="5" name="setting[chat_poll]" value="<?php echo $chat_poll;?>"/> 秒<?php tips('交谈双方客户端需要定时请求服务器端数据，时间设置越短，信息发送的延迟越小，但是服务器压力越大，至少需要设置为1秒，一般建议设置为2秒-5秒之间的数值，推荐设置为3秒');?></td>
</tr>
<tr>
<td class="tl">两次发言间隔时间</td>
<td><input type="text" size="5" name="setting[chat_mintime]" value="<?php echo $chat_mintime;?>"/> 秒<?php tips('防止发言过快');?></td>
</tr>
<tr>
<td class="tl">在线交谈发送文件</td>
<td>
<input type="radio" name="setting[chat_file]" value="1" <?php if($chat_file) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[chat_file]" value="0" <?php if(!$chat_file) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">在线交谈自动解析网址</td>
<td>
<input type="radio" name="setting[chat_url]" value="1" <?php if($chat_url) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[chat_url]" value="0" <?php if(!$chat_url) echo 'checked';?>/> 关闭<?php tips('当内容含有网址时，自动解析为超链接');?>
</td>
</tr>
<tr>
<td class="tl">在线交谈解析图片地址</td>
<td>
<input type="radio" name="setting[chat_img]" value="1" <?php if($chat_img) echo 'checked';?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[chat_img]" value="0" <?php if(!$chat_img) echo 'checked';?>/> 关闭<?php tips('当内容含有图片地址时，自动显示图片');?>
</td>
</tr>
<tr>
<td class="tl">会员资料认证</td>
<td>
<input type="radio" name="setting[vmember]" value="1" <?php if($vmember){ ?>checked <?php } ?> onclick="Ds('dvm');"/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[vmember]" value="0" <?php if(!$vmember){ ?>checked <?php } ?> onclick="Dh('dvm');"/> 关闭
</td>
</tr>
<tbody id="dvm" style="display:<?php if(!$vmember) echo 'none';?>">
<tr>
<td class="tl">邮件认证</td>
<td>
<input type="radio" name="setting[vemail]" value="1" <?php if($vemail){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[vemail]" value="0" <?php if(!$vemail){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">手机认证</td>
<td>
<input type="radio" name="setting[vmobile]" value="1" <?php if($vmobile){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[vmobile]" value="0" <?php if(!$vmobile){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">姓名认证</td>
<td>
<input type="radio" name="setting[vtruename]" value="1" <?php if($vtruename){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[vtruename]" value="0" <?php if(!$vtruename){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">银行帐号认证</td>
<td>
<input type="radio" name="setting[vbank]" value="1" <?php if($vbank){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[vbank]" value="0" <?php if(!$vbank){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">公司认证</td>
<td>
<input type="radio" name="setting[vcompany]" value="1" <?php if($vcompany){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[vcompany]" value="0" <?php if(!$vcompany){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">认证专用传真号码</td>
<td>
<input type="text" size="30" name="setting[vfax]" value="<?php echo $vfax;?>"/> <?php tips('如果设置传真，将提示用户可以选择传真证件进行认证');?>
</td>
</tr>
</tbody>
<tr>
<td class="tl">编辑器工具按钮</td>
<td>
<select name="setting[editor]">
<option value="Default"<?php if($editor == 'Default') echo ' selected';?>>全部</option>
<option value="Destoon"<?php if($editor == 'Destoon') echo ' selected';?>>精简</option>
<option value="Simple"<?php if($editor == 'Simple') echo ' selected';?>>简洁</option>
<option value="Basic"<?php if($editor == 'Basic') echo ' selected';?>>基础</option>
</select>
</td>
</tr>
</table>
</div>

<div id="Tabs1" style="display:none;">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">公司类型</td>
<td><input type="text" name="setting[com_type]" style="width:98%;" value="<?php echo $com_type;?>"/></td>
</tr>
<tr>
<td class="tl">公司规模</td>
<td><input type="text" name="setting[com_size]" style="width:98%;" value="<?php echo $com_size;?>"/></td>
</tr>
<tr>
<td class="tl">经营模式</td>
<td><input type="text" name="setting[com_mode]" style="width:98%;" value="<?php echo $com_mode;?>"/></td>
</tr>
<tr>
<td class="tl">公司注册资本货币类型</td>
<td><input type="text" name="setting[money_unit]" style="width:98%;" value="<?php echo $money_unit;?>"/></td>
</tr>
<tr>
<td class="tl"></td>
<td class="f_red">以上设置请用 | 分隔类型，结尾不需要 |</td>
</tr>
<tr>
<td class="tl">经营模式最多可选</td>
<td>
<input type="text" size="3" name="setting[mode_max]" value="<?php echo $mode_max;?>"/>
</td>
</tr>
<tr>
<td class="tl">主营行业最多可选</td>
<td>
<input type="text" size="3" name="setting[cate_max]" value="<?php echo $cate_max;?>"/>
</td>
</tr>
<tr>
<td class="tl">默认形象图[宽X高]</td>
<td>
<input type="text" size="3" name="setting[thumb_width]" value="<?php echo $thumb_width;?>"/>
X
<input type="text" size="3" name="setting[thumb_height]" value="<?php echo $thumb_height;?>"/> px
</td>
</tr>
<tr>
<td class="tl">截取公司介绍至简介</td>
<td>默认截取 <input type="text" size="3" name="setting[introduce_length]" value="<?php echo $introduce_length;?>"/> 字符
</td>
</tr>
<tr>
<td class="tl">下载公司介绍远程图片</td>
<td>
<input type="radio" name="setting[introduce_save]" value="1" <?php if($introduce_save) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[introduce_save]" value="0" <?php if(!$introduce_save) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">清除公司介绍内容链接</td>
<td>
<input type="radio" name="setting[introduce_clear]" value="1" <?php if($introduce_clear) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[introduce_clear]" value="0" <?php if(!$introduce_clear) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">公司新闻需审核</td>
<td>
<input type="radio" name="setting[news_check]" value="2" <?php if($news_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[news_check]" value="1" <?php if($news_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[news_check]" value="0" <?php if($news_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>

<tr>
<td class="tl">默认缩略图[宽X高]</td>
<td>
<input type="text" size="3" name="setting[news_thumb_width]" value="<?php echo $news_thumb_width;?>"/>
X
<input type="text" size="3" name="setting[news_thumb_height]" value="<?php echo $news_thumb_height;?>"/> px
</td>
</tr>

<tr>
<td class="tl">下载新闻内容远程图片</td>
<td>
<input type="radio" name="setting[news_save]" value="1" <?php if($news_save) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[news_save]" value="0" <?php if(!$news_save) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">清除新闻内容内容链接</td>
<td>
<input type="radio" name="setting[news_clear]" value="1" <?php if($news_clear) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[news_clear]" value="0" <?php if(!$news_clear) echo 'checked';?>/> 关闭
</td>
</tr>


<tr>
<td class="tl">公司单页需审核</td>
<td>
<input type="radio" name="setting[page_check]" value="2" <?php if($page_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_check]" value="1" <?php if($page_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_check]" value="0" <?php if($page_check == 0) echo 'checked';?>> 全部关闭

</td>
</tr>

<tr>
<td class="tl">下载单页内容远程图片</td>
<td>
<input type="radio" name="setting[page_save]" value="1" <?php if($page_save) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_save]" value="0" <?php if(!$page_save) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">清除单页内容内容链接</td>
<td>
<input type="radio" name="setting[page_clear]" value="1" <?php if($page_clear) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_clear]" value="0" <?php if(!$page_clear) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">荣誉资质需审核</td>
<td>
<input type="radio" name="setting[credit_check]" value="2" <?php if($credit_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[credit_check]" value="1" <?php if($credit_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[credit_check]" value="0" <?php if($credit_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>

<tr>
<td class="tl">下载证书介绍远程图片</td>
<td>
<input type="radio" name="setting[credit_save]" value="1" <?php if($credit_save) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[credit_save]" value="0" <?php if(!$credit_save) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">清除证书介绍链接</td>
<td>
<input type="radio" name="setting[credit_clear]" value="1" <?php if($credit_clear) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[credit_clear]" value="0" <?php if(!$credit_clear) echo 'checked';?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">友情链接需审核</td>
<td>
<input type="radio" name="setting[link_check]" value="2" <?php if($link_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_check]" value="1" <?php if($link_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_check]" value="0" <?php if($link_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
</table>
</div>
<div id="Tabs2" style="display:none">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">会员在线充值</td>
<td>
<input type="radio" name="setting[pay_online]" value="1" <?php if($pay_online) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[pay_online]" value="0" <?php if(!$pay_online) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">充值卡充值</td>
<td>
<input type="radio" name="setting[pay_card]" value="1" <?php if($pay_card) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[pay_card]" value="0" <?php if(!$pay_card) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">最小充值额度</td>
<td><input type="text" size="5" name="setting[mincharge]" value="<?php echo $mincharge;?>"/> 0表示不限，填数字表示最小额度</td>
</tr>
<tr>
<td class="tl">打赏快捷额度</td>
<td>
<input type="text" size="50" name="setting[awards]" value="<?php echo $awards;?>"/><?php tips('多个金额用|分割');?>
</td>
</tr>
<tr>
<td class="tl">线下付款网址</td>
<td><input type="text" size="60" name="setting[pay_url]" value="<?php echo $pay_url;?>"/><?php tips('如果未启用会员在线充值，则系统自动调转至此地址查看普通付款方式。建议用扩展功能的单网页建立');?></td>
</tr>
<tr>
<td class="tl">会员提现</td>
<td>
<input type="radio" name="setting[cash_enable]" value="1" <?php if($cash_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[cash_enable]" value="0" <?php if(!$cash_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">提现方式</td>
<td><input type="text" name="setting[cash_banks]" style="width:95%;" value="<?php echo $cash_banks;?>"/><?php tips('不同方式请用 | 分隔');?></td>
</tr>
<tr>
<td class="tl">24小时提现次数</td>
<td><input type="text" size="5" name="setting[cash_times]" value="<?php echo $cash_times;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">单次提现最小金额</td>
<td><input type="text" size="5" name="setting[cash_min]" value="<?php echo $cash_min;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">单次提现最大金额</td>
<td><input type="text" size="5" name="setting[cash_max]" value="<?php echo $cash_max;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">提现费率</td>
<td><input type="text" size="2" name="setting[cash_fee]" value="<?php echo $cash_fee;?>"/> %</td>
</tr>
<tr>
<td class="tl">费率最小值</td>
<td><input type="text" size="5" name="setting[cash_fee_min]" value="<?php echo $cash_fee_min;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">费率封顶值</td>
<td><input type="text" size="5" name="setting[cash_fee_max]" value="<?php echo $cash_fee_max;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">保证金基数</td>
<td><input type="text" size="5" name="setting[deposit]" value="<?php echo $deposit;?>"/><?php tips('例如设置为1000，会员每次增加保证金最少为1000且必须是1000的倍数，例如2000、5000、10000，最小值为100');?></td>
</tr>
<tr>
<td class="tl">买家确认收货时间限制</td>
<td><input type="text" size="2" name="setting[trade_day]" value="<?php echo $trade_day;?>"/> 天<?php tips('买家在此时间内未确认收货或申请仲裁，则系统自动付款给卖家，交易成功');?></td>
</tr>
<tr>
<td class="tl">常用支付方式</td>
<td><input type="text" name="setting[pay_banks]" style="width:95%;" value="<?php echo $pay_banks;?>"/><?php tips('手动添加'.$DT['money_name'].'流水时需选择');?></td>
</tr>
<tr>
<td class="tl">常用快递方式</td>
<td><input type="text" name="setting[send_types]" style="width:95%;" value="<?php echo $send_types;?>"/></td>
</tr>
</table>
</div>
<div id="Tabs3" style="display:none">
<?php include DT_ROOT.'/api/pay/setting.inc.php';?>
</div>
<div id="Tabs4" style="display:none;">
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><?php echo $DT['credit_name'];?>为负禁发信息</td>
<td>
<input type="radio" name="setting[credit_less]" value="0" <?php if(!$credit_less) echo 'checked';?>/> 关闭&nbsp;&nbsp;
<input type="radio" name="setting[credit_less]" value="1" <?php if($credit_less) echo 'checked';?>/> 开启<?php tips($DT['credit_name'].'为负时禁止新发布信息，此项对游客无效');?>
</td>
</tr>
<tr>
<td class="tl">完善个人资料奖励</td>
<td>
<input type="text" size="5" name="setting[credit_edit]" value="<?php echo $credit_edit;?>"/>
</td>
</tr>
<tr>
<td class="tl">24小时登录一次奖励</td>
<td>
<input type="text" size="5" name="setting[credit_login]" value="<?php echo $credit_login;?>"/>
</td>
</tr>
<tr>
<td class="tl">引导一位会员注册奖励</td>
<td>
<input type="text" size="5" name="setting[credit_user]" value="<?php echo $credit_user;?>"/>
</td>
</tr>
<tr>
<td class="tl">引导一个IP访问奖励</td>
<td>
<input type="text" size="5" name="setting[credit_ip]" value="<?php echo $credit_ip;?>"/>
</td>
</tr>
<tr>
<td class="tl">24小时引导<?php echo $DT['credit_name'];?>上限</td>
<td>
<input type="text" size="5" name="setting[credit_maxip]" value="<?php echo $credit_maxip;?>"/>
<?php tips('为了防止作弊，超过'.$DT['credit_name'].'上限将不再计算');?>
</td>
</tr>
<tr>
<td class="tl">在线充值1<?php echo $DT['money_unit'];?>奖励</td>
<td>
<input type="text" size="5" name="setting[credit_charge]" value="<?php echo $credit_charge;?>"/> <?php tips('每充值1'.$DT['money_unit'].' 奖励对应倍数的'.$DT['credit_name']);?>
</td>
</tr>
<tr>
<td class="tl">上传资质证书奖励</td>
<td>
<input type="text" size="5" name="setting[credit_add_credit]" value="<?php echo $credit_add_credit;?>"/>
</td>
</tr>
<tr>
<td class="tl">资质证书被删除扣除</td>
<td>
<input type="text" size="5" name="setting[credit_del_credit]" value="<?php echo $credit_del_credit;?>"/>
</td>
</tr>
<tr>
<td class="tl">发布企业新闻奖励</td>
<td>
<input type="text" size="5" name="setting[credit_add_news]" value="<?php echo $credit_add_news;?>"/>
</td>
</tr>
<tr>
<td class="tl">企业新闻被删除扣除</td>
<td>
<input type="text" size="5" name="setting[credit_del_news]" value="<?php echo $credit_del_news;?>"/>
</td>
</tr>

<tr>
<td class="tl">发布企业单页奖励</td>
<td>
<input type="text" size="5" name="setting[credit_add_page]" value="<?php echo $credit_add_page;?>"/>
</td>
</tr>
<tr>
<td class="tl">企业单页被删除扣除</td>
<td>
<input type="text" size="5" name="setting[credit_del_page]" value="<?php echo $credit_del_page;?>"/>
</td>
</tr>
</table>
<div class="tt"><?php echo $DT['credit_name'];?>购买</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><?php echo $DT['credit_name'];?>购买额度</td>
<td>
<input type="text" size="50" name="setting[credit_buy]" value="<?php echo $credit_buy;?>"/>
</td>
</tr>
<tr>
<td class="tl"><?php echo $DT['credit_name'];?>对应价格</td>
<td>
<input type="text" size="50" name="setting[credit_price]" value="<?php echo $credit_price;?>"/><br/>
<span class="f_gray"><?php echo $DT['credit_name'];?>和价格用|分隔，二者必须一一对应</span>
</td>
</tr>
</table>
<div class="tt"><?php echo $DT['credit_name'];?>兑换</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">会员积分兑换</td>
<td>
<input type="radio" name="setting[credit_exchange]" value="0" <?php if(!$credit_exchange) echo 'checked';?> onclick="Dh('e_x');"/> 关闭&nbsp;&nbsp;
<input type="radio" name="setting[credit_exchange]" value="1" <?php if($credit_exchange) echo 'checked';?> onclick="Ds('e_x');"/> 开启
</td>
</tr>
<tbody id="e_x" style="display:<?php echo $credit_exchange ? '' : 'none';?>">
<tr>
<td class="tl">论坛类型</td>
<td>
<select name="setting[ex_type]">
<option value="DZX"<?php if($ex_type == 'DZX') echo ' selected';?>>Discuz!X</option>
<option value="DZ"<?php if($ex_type == 'DZ') echo ' selected';?>>Discuz!</option>
<option value="PW"<?php if($ex_type == 'PW') echo ' selected';?>>PHPWind</option>
</select>
</td>
</tr>
<tr>
<td class="tl">数据库务器</td>
<td><input name="setting[ex_host]" type="text" size="30" value="<?php echo $ex_host;?>"/></td>
</tr>
<tr>
<td class="tl">数据库户名</td>
<td><input name="setting[ex_user]" type="text" size="15" value="<?php echo $ex_user;?>"/></td>
</tr>
<tr>
<td class="tl">数据库密码</td>
<td><input name="setting[ex_pass]" type="text" size="15" value="<?php echo $ex_pass;?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">数据库名称</td>
<td><input name="setting[ex_data]" type="text" size="15" value="<?php echo $ex_data;?>"/></td>
</tr>
<tr>
<td class="tl">数据表前缀</td>
<td><input name="setting[ex_prex]" type="text" size="15" value="<?php echo $ex_prex;?>"/></td>
</tr>
<tr>
<td class="tl">数据表字段</td>
<td><input name="setting[ex_fdnm]" type="text" size="15" value="<?php echo $ex_fdnm;?>"/><?php tips('DZ论坛一般为extcredits1、extcredits2...<br/>PW论坛一般为credit');?></td>
</tr>
<tr>
<td class="tl">兑换比率</td>
<td><input name="setting[ex_rate]" type="text" size="15" value="<?php echo $ex_rate;?>"/><?php tips('例如填5表示1个论坛积分可兑换5个'.$DT['credit_name']);?></td>
</tr>
<tr>
<td class="tl">论坛积分名称</td>
<td><input name="setting[ex_name]" type="text" size="15" value="<?php echo $ex_name;?>"/></td>
</tr>
</tbody>
</table>
</div>
<div id="Tabs5" style="display:none">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">启用会员整合</td>
<td>
<input type="radio" name="setting[passport]" value="0" <?php if(!$passport) echo 'checked';?> onclick="Dh('p_s');Dh('u_c');"/> 关闭&nbsp;&nbsp;
<input type="radio" name="setting[passport]" value="phpwind" <?php if($passport == 'phpwind') echo 'checked';?> onclick="Ds('p_s');Dh('u_c');"/> PHPWind&nbsp;&nbsp;
<input type="radio" name="setting[passport]" value="discuz" <?php if($passport == 'discuz') echo 'checked';?> onclick="Ds('p_s');Dh('u_c');"/> Discuz!(5.x,6.x)&nbsp;&nbsp;
<input type="radio" name="setting[passport]" value="uc" <?php if($passport == 'uc') echo 'checked';?> onclick="Dh('p_s');Ds('u_c');"/> Ucenter(Discuz!7.x,Discuz! X)
</td>
</tr>
<tbody id="p_s" style="display:<?php echo $passport && $passport != 'uc' ? '' : 'none';?>">
<tr>
<td class="tl">整合程序字符编码</td>
<td>
<select name="setting[passport_charset]">
<option value="gbk"<?php if($passport_charset == 'gbk') echo ' selected';?>>GBK/GB2312</option>
<option value="utf-8"<?php if($passport_charset == 'utf-8') echo ' selected';?>>UTF-8</option>
</select>
</td>
</tr>
<tr>
<td class="tl">整合程序地址</td>
<td><input name="setting[passport_url]" type="text" size="50" value="<?php echo $passport_url;?>"/><?php tips('整合程序接口地址 例如:http://bbs.destoon.com 结尾不要带斜线');?></td>
</tr>
<tr>
<td class="tl">整合密钥</td>
<td><input name="setting[passport_key]" id="passport_key" type="text" size="30" value="<?php echo $passport_key;?>"/> <a href="javascript:Dd('passport_key').value=RandStr();void(0);" class="t">[随机]</a> </td>
</tr>
</tbody>
<tbody id="u_c" style="display:<?php echo $passport && $passport == 'uc' ? '' : 'none';?>">
<tr>
<td class="tl">UCenter配置信息</td>
<td>
<textarea name="ucconfig" id="ucconfig" style="width:450px;height:50px;overflow:visible;"></textarea><br/>
<input type="button" class="btn" value="自动填表" onclick="AutoUC();"/> <span class="f_gray">请将应用的UCenter配置信息粘贴在上面的输入框，然后点击自动填表</span>
</td>
</tr>
<tr>
<td class="tl">API地址</td>
<td><input name="setting[uc_api]" type="text" size="50" value="<?php echo $uc_api;?>" id="uc_api"/><?php tips('整合程序接口地址 例如:http://bbs.destoon.com 结尾不要带斜线');?></td>
</tr>
<tr>
<td class="tl">主机IP</td>
<td><input name="setting[uc_ip]" type="text" size="50" value="<?php echo $uc_ip;?>" id="uc_ip"/><?php tips('一般不用填写,遇到无法同步时,请填写Ucenter主机的IP地址');?></td>
</tr>
<tr>
<td class="tl">整合方式</td>
<td>
<input type="radio" name="setting[uc_mysql]" value="1" <?php if($uc_mysql) echo 'checked';?> onclick="Ds('u_c_m');" id="uc_connect_mysql"/> MySQL
<input type="radio" name="setting[uc_mysql]" value="0" <?php if(!$uc_mysql) echo 'checked';?> onclick="Dh('u_c_m');" id="uc_connect_fopen"/> 远程连接 <?php tips('当UC数据库不在当前服务器且无法直接连接时，请选择远程连接');?>
</td>
</tr>
<tr id="u_c_m" style="display:<?php echo $uc_mysql ? '' : 'none';?>">
<td colspan="2" style="padding:10px;">
	<table cellspacing="0" class="tb">
	<tr>
	<td class="tl">数据库主机名</td>
	<td><input name="setting[uc_dbhost]" type="text" size="30" value="<?php echo $uc_dbhost;?>" id="uc_dbhost"/></td>
	</tr>
	<tr>
	<td class="tl">数据库用户名</td>
	<td><input name="setting[uc_dbuser]" type="text" size="30" value="<?php echo $uc_dbuser;?>" id="uc_dbuser"/></td>
	</tr>
	<tr>
	<td class="tl">数据库密码</td>
	<td><input name="setting[uc_dbpwd]" type="text" size="30" value="<?php echo $uc_dbpwd;?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';" id="uc_dbpw"/></td>
	</tr>
	<tr>
	<td class="tl">数据库名</td>
	<td><input name="setting[uc_dbname]" type="text" size="30" value="<?php echo $uc_dbname;?>" id="uc_dbname"/></td>
	</tr>
	<tr>
	<td class="tl">数据表前缀</td>
	<td><input name="setting[uc_dbpre]" type="text" size="30" value="<?php echo $uc_dbpre;?>" id="uc_dbtablepre"/></td>
	</tr>
	<tr>
	<td class="tl">数据库字符集</td>
	<td>	
	<input type="radio" name="setting[uc_charset]" value="utf8"<?php if($uc_charset == 'utf8') echo ' checked';?> id="uc_charset_utf8"/> UTF-8
	<input type="radio" name="setting[uc_charset]" value="gbk"<?php if($uc_charset == 'gbk') echo ' checked';?> id="uc_charset_gbk"/> GBK/GB2312
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td class="tl">应用ID(APP ID)</td>
<td><input name="setting[uc_appid]" type="text" size="30" value="<?php echo $uc_appid;?>" id="uc_appid"/></td>
</tr>
<tr>
<td class="tl">通信密钥</td>
<td><input name="setting[uc_key]" id="uc_key" type="text" size="30" value="<?php echo $uc_key;?>" id="uc_key"/> <a href="javascript:Dd('uc_key').value=RandStr();void(0);" class="t">[随机]</a></td>
</tr>
<tr>
<td class="tl">论坛会员自动激活</td>
<td>
<input type="radio" name="setting[uc_bbs]" value="0" <?php if(!$uc_bbs) echo 'checked';?>/> 关闭&nbsp;&nbsp;
<input type="radio" name="setting[uc_bbs]" value="1" <?php if($uc_bbs) echo 'checked';?>/> 开启 <?php tips('此项可以在会员注册后自动激活论坛帐号，但仅适用于使用DZX2以上版本的论坛，且论坛与UC安装在同一数据库，且整合方式为MySQL连接，请确认你的整合符合上述条件，否则请勿开启');?>
</td>
</tr>
<tr>
<td class="tl">论坛表前缀</td>
<td><input name="setting[uc_bbspre]" type="text" size="10" value="<?php echo $uc_bbspre;?>" id="uc_bbspre"/> <?php tips('如果开启自动激活，此项必须填写。注意：填写错误可能导致会员无法注册！<br/>默认的表前缀为pre_，具体请参考论坛数据库配置文件');?></td>
</tr>
</tbody>
</table>
</div>
<div id="Tabs6" style="display:none">
<?php include DT_ROOT.'/api/oauth/setting.inc.php';?>
</div>
<div class="sbt">
<input type="submit" name="submit" value="保 存" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="展 开" id="ShowAll" class="btn" onclick="TabAll();" title="展开/合并所有选项"/>
</div>
</form>
<script type="text/javascript">
function AutoUC() {
	if(Dd('ucconfig').value.length < 300) {
		Dalert('请先粘贴应用的UCenter配置信息');
		Dd('ucconfig').focus();
		return false;
	}
	var r,c;
	var cfg = Dd('ucconfig').value;
	cfg = cfg.replace(/define\(\'/g, '');
	cfg = cfg.replace(/\'\)\;/g, '');
	cfg = cfg.replace(/\r/g, '');
	r = cfg.split("\n");
	for(var i=0; i<r.length; i++) {
		if(!r[i]) continue;
		c = r[i].split("', '");
		c[0] = c[0].toLowerCase();
		if(c[0] == 'uc_connect') {
			if(c[1] == 'mysql'){Dd('uc_connect_mysql').checked=true;}else{Dd('uc_connect_fopen').checked=true;}
		} else if(c[0] == 'uc_dbcharset') {
			if(c[1] == 'gbk'){Dd('uc_charset_gbk').checked=true;}else{Dd('uc_charset_utf8').checked=true;}
		} else if(c[0] == 'uc_dbtablepre') {
			Dd(c[0]).value=ext(c[1]);
		} else {
			try{Dd(c[0]).value=c[1];}catch(e){}
		}
	}
}
var tab = <?php echo $tab;?>;
var all = <?php echo $all;?>;
function TabAll() {
	var i = 0;
	while(1) {
		if(Dd('Tabs'+i) == null) break;
		Dd('Tabs'+i).style.display = all ? (i == tab ? '' : 'none') : '';
		i++;
	}
	Dd('ShowAll').value = all ? '展 开' : '合 并';
	all = all ? 0 : 1;
}
$(function(){
	if(tab) Tab(tab);
	if(all) {all = 0; TabAll();}
});
</script>
<?php include tpl('footer');?>