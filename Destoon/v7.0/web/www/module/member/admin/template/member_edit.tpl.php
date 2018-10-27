<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return Dcheck();" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<input type="hidden" name="username" value="<?php echo $username;?>"/>
<input type="hidden" name="gid" value="<?php echo $groupid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="post[regid]" value="<?php echo $regid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 会员头像</td>
<td><img src="<?php echo useravatar($username, 'large');?>" style="margin:6px;" width="128" height="128"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 会员登录名</td>
<td><strong><?php echo $username;?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&action=edit_username&userid=<?php echo $userid;?>', '修改会员名', 500, 300);" class="t">[修改]</a></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> 昵称</td>
<td><strong><?php echo $passport;?></strong>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&action=edit_passport&userid=<?php echo $userid;?>', '修改昵称', 500, 300);" class="t">[修改]</a></td>
</tr>

<tr>
<td class="tl"><span class="f_red">*</span> 会员组</td>
<td><?php echo group_select('post[groupid]', '会员组', $groupid, 'id="groupid"');?>&nbsp;<span id="dgroupid" class="f_red"></span></td>
</tr>
<?php if($userid != $_userid || $_founder) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 登录密码</td>
<td><input type="password" size="20" name="post[password]" id="password" onblur="validator('password');" autocomplete="off"/>&nbsp;<span id="dpassword" class="f_red"></span> <span class="f_gray">如不更改,请留空</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 重复输入密码</td>
<td><input type="password" size="20" name="post[cpassword]" id="cpassword" autocomplete="off"/>&nbsp;<span id="dcpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 支付密码</td>
<td><input type="password" size="20" name="post[payword]" id="payword" onblur="validator('payword');" autocomplete="off"/>&nbsp;<span id="dpayword" class="f_red"></span> <span class="f_gray">如不更改,请留空</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 重复支付密码</td>
<td><input type="password" size="20" name="post[cpayword]" id="cpayword" autocomplete="off"/>&nbsp;<span id="dcpayword" class="f_red"></span></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_red">*</span> 电子邮件</td>
<td><input type="text" size="30" name="post[email]" id="email" value="<?php echo $email;?>" onblur="validator('email');"/>&nbsp;<a href="#vv"><img src="<?php echo $MOD['linkurl'];?>image/<?php echo $vemail ? 'v' : 'u';?>_email.gif" width="16" height="16" title="<?php echo $vemail ? '已通过' : '未通过';?>邮件认证" align="absmiddle"/></a>&nbsp;<span id="demail" class="f_red"></span> <span class="f_gray">[不公开]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 真实姓名</td>
<td><input type="text" size="10" name="post[truename]" id="truename" value="<?php echo $truename;?>"/>&nbsp;<a href="#vv"><img src="<?php echo $MOD['linkurl'];?>image/<?php echo $vtruename ? 'v' : 'u';?>_truename.gif" width="16" height="16" title="<?php echo $vtruename ? '已通过' : '未通过';?>实名认证" align="absmiddle"/></a>&nbsp;<span id="dtruename" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 性别</td>
<td>
<input type="radio" name="post[gender]" value="1" <?php if($gender == 1) echo 'checked="checked"';?>/> 先生&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[gender]" value="2" <?php if($gender == 2) echo 'checked="checked"';?>/> 女士
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 所在地区</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?>&nbsp;<span id="dareaid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 部门</td>
<td><input type="text" size="20" name="post[department]" id="department" value="<?php echo $department;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 职位</td>
<td><input type="text" size="20" name="post[career]" id="career" value="<?php echo $career;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 手机号码</td>
<td><input type="text" size="20" name="post[mobile]" id="mobile" value="<?php echo $mobile;?>"/>&nbsp;<a href="#vv"><img src="<?php echo $MOD['linkurl'];?>image/<?php echo $vmobile ? 'v' : 'u';?>_mobile.gif" width="16" height="16" title="<?php echo $vmobile ? '已通过' : '未通过';?>手机认证" align="absmiddle"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:_mobile('<?php echo $mobile;?>');" class="t">查询</a></td>
</tr>
<?php if($DT['im_qq']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> QQ</td>
<td><input type="text" size="20" name="post[qq]" id="qq" value="<?php echo $qq;?>"/></td>
</tr>
<?php } ?>
<?php if($DT['im_wx']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信</td>
<td><input type="text" size="20" name="post[wx]" id="wx" value="<?php echo $wx;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信二维码</td>
<td><input name="post[wxqr]" type="text" size="60" id="wxqr" value="<?php echo $wxqr;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,128, 128, Dd('wxqr').value, true, 'wxqr');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('wxqr').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('wxqr').value='';" class="jt">[删除]</span></td>
</tr>
<?php } ?>
<?php if($DT['im_ali']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 阿里旺旺</td>
<td><input type="text" size="20" name="post[ali]" id="ali" value="<?php echo $ali;?>"/></td>
</tr>
<?php } ?>
<?php if($DT['im_skype']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> Skype</td>
<td><input type="text" size="20" name="post[skype]" id="skype" value="<?php echo $skype;?>"/></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 站内信提示音</td>
<td class="tr">
<div id="audition"></div>
<input type="radio" name="post[sound]" value="0" <?php if($sound==0) { ?>checked="checked"<?php } ?> id="sound_0"/><label for="sound_0"> 无</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[sound]" value="1" <?php if($sound==1) { ?>checked="checked"<?php } ?> id="sound_1"/> <a href="javascript:Inner('audition', sound('message_1'));Dd('sound_1').checked=true;void(0);" title="点击试听">提示音1</a>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[sound]" value="2" <?php if($sound==2) { ?>checked="checked"<?php } ?> id="sound_2"/> <a href="javascript:Inner('audition', sound('message_2'));Dd('sound_2').checked=true;void(0);" title="点击试听">提示音2</a>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[sound]" value="3" <?php if($sound==3) { ?>checked="checked"<?php } ?> id="sound_3"/> <a href="javascript:Inner('audition', sound('message_3'));Dd('sound_3').checked=true;void(0);" title="点击试听">提示音3</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 开户银行</td>
<td>
<select name="post[bank]">
<option value="">开户银行</option>
<?php foreach($BANKS as $v) { ?>
<option value="<?php echo $v;?>" <?php if($bank == $v) { ?>selected<?php } ?>><?php echo $v;?></option>;
<?php } ?>
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 帐号类型</td>
<td>
<input type="radio" name="post[banktype]" value="0"<?php if($banktype == 0) { ?> checked<?php } ?>/> 对私
<input type="radio" name="post[banktype]" value="1"<?php if($banktype == 1) { ?> checked<?php } ?>/> 对公
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 开户网点</td>
<td><input type="text" size="50" name="post[branch]" id="branch" value="<?php echo $branch;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 收款帐号</td>
<td><input type="text" size="30" name="post[account]" id="account" value="<?php echo $account;?>"/>&nbsp;<a href="#vv"><img src="<?php echo $MOD['linkurl'];?>image/<?php echo $vbank ? 'v' : 'u';?>_bank.gif" width="16" height="16" title="<?php echo $vbank ? '已通过' : '未通过';?>银行帐号认证" align="absmiddle"/></a></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> <?php echo $DT['trade_nm'];?>帐号</td>
<td><input type="text" size="30" name="post[trade]" id="trade" value="<?php echo $trade;?>"/>&nbsp;<a href="#vv"><img src="<?php echo $MOD['linkurl'];?>image/<?php echo $vbank ? 'v' : 'u';?>_trade.gif" width="16" height="16" title="<?php echo $vbank ? '已通过' : '未通过';?><?php echo $DT['trade_nm'];?>帐号认证" align="absmiddle"/></a></td>
</tr>
<?php echo $MFD ? fields_html('<td class="tl">', '<td>', $user, $MFD) : '';?>
</table>
<div class="tt"><span class="f_hid">*</span> 公司资料</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 公司名称</td>
<td><input type="text" size="60" name="post[company]" id="company" value="<?php echo $company;?>" onblur="validator('company');"/>&nbsp;<a href="#vv"><img src="<?php echo $MOD['linkurl'];?>image/<?php echo $vcompany ? 'v' : 'u';?>_company.gif" width="16" height="16" title="<?php echo $vcompany ? '已通过' : '未通过';?>工商认证" align="absmiddle"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/company.php?wd=<?php echo urlencode($company);?>" target="_blank" class="t">查询</a></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司类型</td>
<td><?php echo dselect($COM_TYPE, 'post[type]', '请选择', $type, 'id="type"', 0);?>&nbsp;<span id="dtype" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 形象图片</td>
<td><input name="post[thumb]" type="text" size="60" id="thumb" value="<?php echo $thumb;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, Dd('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt">[删除]</span><br/>
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
<span id="cate"><?php echo ajax_category_select('', '请选择行业', 0, 4);?></span> &nbsp; <a href="javascript:cate_add(<?php echo $MOD['cate_max'];?>);" class="t">添加</a><?php if($DT['schcate_limit']) { ?> &nbsp; <a href="javascript:schcate(4);" class="t">搜索</a><?php } ?>
<div id="cate-box">
<div id="cate-tip"<?php if($cates) { ?>style="display:none;"<?php } ?>
>请添加主营行业，最多可添加 <strong class="f_red"><?php echo $MOD['cate_max'];?></strong> 个</div>
<?php if(is_array($cates)) { foreach($cates as $c) { ?>
<div id="cate-<?php echo $c;?>"><span><a href="javascript:cate_del(<?php echo $c;?>);" class="b">删除</a></span><?php echo strip_tags(cat_pos(get_cat($c), '/'));?></div>
<?php } } ?>
</div>
<input type="hidden" name="post[catid]" value="<?php echo $catid;?>" id="catid"/>
<span id="dcatid" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 主要经营范围</td>
<td><input type="text" size="80" name="post[business]" id="business" value="<?php echo $business;?>"/>&nbsp;<span id="dbusiness" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 经营模式</td>
<td>
<span id="com_mode"><?php echo dcheckbox($COM_MODE, 'post[mode][]', $mode, 'onclick="check_mode(this,'.$MOD['mode_max'].');"', 0);?></span> <span class="f_gray">(最多可选<?php echo $MOD['mode_max'];?>种)</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司规模</td>
<td><?php echo dselect($COM_SIZE, 'post[size]', '请选择规模', $size, '', 0);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 注册资本</td>
<td><?php echo dselect($MONEY_UNIT, 'post[regunit]', '', $regunit, '', 0);?> <input type="text" size="6" name="post[capital]" id="capital" value="<?php echo $capital;?>"/> 万</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司成立年份</td>
<td><input type="text" size="15" name="post[regyear]" id="regyear" value="<?php echo $regyear;?>"/>&nbsp;<span id="dregyear" class="f_red"></span> <span class="f_gray">(年份，如：2004)</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司地址</td>
<td><input type="text" size="60" name="post[address]" id="address" value="<?php echo $address;?>"/>&nbsp;<span id="daddress" class="f_red"></span></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> 邮政编码</td>
<td><input type="text" size="8" name="post[postcode]" id="postcode" value="<?php echo $postcode;?>"/></td>
</tr>

<tr>
<td class="tl"><span class="f_red">*</span> 公司电话</td>
<td><input type="text" size="20" name="post[telephone]" id="telephone" value="<?php echo $telephone;?>"/>&nbsp;<span id="dtelephone" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司传真</td>
<td><input type="text" size="20" name="post[fax]" id="fax" value="<?php echo $fax;?>"/></td>
</tr><tr>
<td class="tl"><span class="f_hid">*</span> 公司Email</td>
<td><input type="text" size="30" name="post[mail]" id="mail" value="<?php echo $mail;?>"/> <span class="f_gray">[公开]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司网址</td>
<td><input type="text" size="30" name="post[homepage]" id="homepage" value="<?php echo $homepage;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信公众号</td>
<td><input type="text" size="20" name="post[gzh]" id="gzh" value="<?php echo $gzh;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公众号二维码</td>
<td><input name="post[gzhqr]" type="text" size="60" id="gzhqr" value="<?php echo $gzhqr;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>, 128, 128, Dd('gzhqr').value,true,'gzhqr');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('gzhqr').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('gzhqr').value='';" class="jt">[删除]</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 销售的产品(服务)</td>
<td><input type="text" size="50" name="post[sell]" id="sell" value="<?php echo $sell;?>"/> <span class="f_gray">多个产品或服务请用'|'号隔开</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 采购的产品(服务)</td>
<td><input type="text" size="50" name="post[buy]" id="buy" value="<?php echo $buy;?>"/> <span class="f_gray">多个产品或服务请用'|'号隔开</span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 公司介绍</td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', $MOD['editor'], '100%', 300);?><br/><span id="dcontent" class="f_red"></span></td>
</tr>
<?php echo $CFD ? fields_html('<td class="tl">', '<td>', $user, $CFD) : '';?>
</table>
<div class="tt">资料认证</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 企业资料是否通过认证</td>
<td>
<input type="radio" name="post[validated]" value="1" <?php if($validated) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[validated]" value="0" <?php if(!$validated) echo 'checked';?>/> 否
<?php tips('一般由第三方认证机构或网站对会员总体资料的认证');?><a name="vv"></a>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证名称或机构</td>
<td><input type="text" name="post[validator]" size="30" value="<?php echo $validator;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 认证日期</td>
<td><?php echo dcalendar('post[validtime]', $validtime);?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 邮件认证</td>
<td>
<input type="radio" name="post[vemail]" value="1" <?php if($vemail) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[vemail]" value="0" <?php if(!$vemail) echo 'checked';?>/> 否
<?php tips('开启邮件发送后，此项由会员自行认证');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 手机认证</td>
<td>
<input type="radio" name="post[vmobile]" value="1" <?php if($vmobile) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[vmobile]" value="0" <?php if(!$vmobile) echo 'checked';?>/> 否
<?php tips('开启短信发送后，此项由会员自行认证');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 银行认证</td>
<td>
<input type="radio" name="post[vbank]" value="1" <?php if($vbank) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[vbank]" value="0" <?php if(!$vbank) echo 'checked';?>/> 否
<?php tips('一般在会员提现之后，由网站进行付款认证，认证之后会员的收款信息将不可修改');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> <?php echo $DT['trade_nm'];?>帐号认证</td>
<td>
<input type="radio" name="post[vtrade]" value="1" <?php if($vtrade) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[vtrade]" value="0" <?php if(!$vtrade) echo 'checked';?>/> 否
<?php tips('会员通过支付宝担保交易之后，系统自动认证');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 实名认证</td>
<td>
<input type="radio" name="post[vtruename]" value="1" <?php if($vtruename) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[vtruename]" value="0" <?php if(!$vtruename) echo 'checked';?>/> 否
<?php tips('一般由会员在线提交身份证或其他证件电子文档或传真件，由网站进行认证');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 公司认证</td>
<td>
<input type="radio" name="post[vcompany]" value="1" <?php if($vcompany) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[vcompany]" value="0" <?php if(!$vcompany) echo 'checked';?>/> 否
<?php tips('一般由会员在线提交营业执照、组织机构代码证等电子文档或传真件，由网站进行认证');?>
</td>
</tr>
</table>
<div class="tt">高级设置</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 主页风格目录</td>
<td><input type="text" size="20" name="post[skin]" value="<?php echo $skin;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 主页模板目录</td>
<td><input type="text" size="20" name="post[template]" value="<?php echo $template;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 绑定域名</td>
<td><input type="text" size="30" name="post[domain]" id="domain" value="<?php echo $domain;?>"/><?php tips('例如 www.destoon.com 不带http<br/>同时需要会员将此域名IP指向本站服务器');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 域名ICP备案号</td>
<td><input type="text" size="30" name="post[icp]" id="icp" value="<?php echo $icp;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 站内信黑名单</td>
<td><input type="text" size="60" name="post[black]" id="black" value="<?php echo $black;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 客服专员</td>
<td><input type="text" size="20" name="post[support]" id="support" value="<?php echo $support;?>"/> <a href="javascript:_user(Dd('support').value);" class="t">[资料]</a> <?php tips('填写客服会员名，填写后会员可以看到此客服的联系方式，以便提供一对一服务');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 推荐注册人 </td>
<td><input type="text" size="20" name="post[inviter]" id="inviter" value="<?php echo $inviter;?>"/> <a href="javascript:_user(Dd('inviter').value);" class="t">[资料]</a> <?php tips('推荐此会员注册的会员名，系统自动记录，一般无须填写');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 会员资料是否完整</td>
<td>
<input type="radio" name="post[edittime]" value="1"<?php if($edittime) echo ' checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[edittime]" value="0"<?php if(!$edittime) echo ' checked';?>/> 否&nbsp;&nbsp;
<span class="f_gray">如果选择是，系统将不再提示会员完善资料</span>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
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
	$.post(AJPath, 'moduleid=<?php echo $moduleid;?>&action=member&job='+id+'&value='+Dd(id).value+'&userid=<?php echo $userid;?>', function(data) {
		Dd('d'+vid).innerHTML = data ? '<img src="'+DTPath+'file/image/check-ko.png" width="16" height="16" align="absmiddle"/> '+data : '';
	});
}
function Dcheck() {
	if(Dd('groupid').value == 0) {
		Dmsg('请选择会员组', 'groupid');
		return false;
	}
	<?php if($userid != $_userid) { ?>
	if(Dd('password').value != '') {
		if(Dd('cpassword').value == '') {
			Dmsg('请重复输入密码', 'cpassword');
			return false;
		}
		if(Dd('password').value != Dd('cpassword').value) {
			Dmsg('两次输入的密码不一致', 'password');
			return false;
		}
	}
	if(Dd('payword').value != '') {
		if(Dd('cpayword').value == '') {
			Dmsg('请重复输入支付密码', 'cpayword');
			return false;
		}
		if(Dd('payword').value != Dd('cpayword').value) {
			Dmsg('两次输入的支付密码不一致', 'payword');
			return false;
		}
	}
	<?php } ?>
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
	<?php if($is_company) { ?>
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
	<?php } ?>
	return true;
}
Menuon(1);
</script>
<?php include tpl('footer');?>