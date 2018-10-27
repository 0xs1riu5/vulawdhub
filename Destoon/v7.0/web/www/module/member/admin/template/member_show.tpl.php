<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
if(!isset($dialog)) show_menu($menus);
?>
<div class="tt">会员资料</div>
<table cellspacing="0" class="tb">
<tr>
<td rowspan="9" align="center" width="160" class="f_gray">
<img src="<?php echo useravatar($username, 'large');?>" width="128" height="128"/>
<div style="padding:5px 0 0 0;">
<a href="?moduleid=<?php echo $moduleid;?>&action=login&userid=<?php echo $userid;?>" class="t" target="_blank" title="点击登入会员商务中心">会员前台</a> | 
<a href="?moduleid=<?php echo $moduleid;?>&action=edit&userid=<?php echo $userid;?>" class="t"<?php if(isset($dialog)) {?> target="_blank"<?php } ?>>修改资料</a>
</div>
<div style="padding:2px 0 2px 0;">
<a href="?moduleid=<?php echo $moduleid;?>&action=move&groupids=2&userid=<?php echo $userid;?>" class="t"<?php if(isset($dialog)) {?> target="_blank"<?php } ?> onclick="return confirm('确定要禁止此会员访问吗？');">禁止访问</a> | 
<a href="?moduleid=<?php echo $moduleid;?>&action=delete&userid=<?php echo $userid;?>&forward=<?php echo urlencode('?moduleid='.$moduleid);?>" class="t"<?php if(isset($dialog)) {?> target="_blank"<?php } ?> onclick="return confirm('确定要删除此会员吗？系统将删除选中用户所有信息，此操作将不可撤销');">删除会员</a><br/>
</div>
<?php if($DT['im_web']) { ?><?php echo im_web($username);?> <?php } ?>
<a href="javascript:Dwidget('?moduleid=2&file=sendmail&email=<?php echo $email;?>', '发送邮件');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/email.gif" title="发送邮件 <?php echo $email;?>" align="absmiddle"/></a> 
<?php if($mobile) { ?><a href="javascript:Dwidget('?moduleid=2&file=sendsms&mobile=<?php echo $mobile;?>', '发送短信');"><img src="<?php echo DT_SKIN;?>image/mobile.gif" title="发送短信" align="absmiddle"/></a> <?php } ?>
<a href="javascript:Dwidget('?moduleid=2&file=message&action=send&touser=<?php echo $username;?>', '发送消息');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/msg.gif" title="发送消息" align="absmiddle"/></a>
<?php echo im_qq($qq);?>
<?php echo im_wx($wx, $username);?>
<?php echo im_ali($ali);?>
<?php echo im_skype($skype);?>
</td>
<td class="tl">会员名</td>
<td>&nbsp;<a href="<?php echo $linkurl;?>" target="_blank"><?php echo $username;?></a>
[<?php $ol = online($userid);if($ol == 1) { ?><span class="f_red">在线</span><?php } else if($ol == -1) { ?><span class="f_blue">隐身</span><?php } else { ?><span class="f_gray">离线</span><?php } ?>]
</td>
<td class="tl">会员ID</td>
<td>&nbsp;<?php echo $userid;?>&nbsp;&nbsp;

</tr>
<tr>
<td class="tl">昵称</td>
<td>&nbsp;<?php echo $passport;?></td>
<td class="tl">会员组</td>
<td class="f_red">&nbsp;<?php echo $GROUP[$groupid]['groupname'];?></td>
</tr>

<tr>
<td class="tl">姓 名</td>
<td>&nbsp;<?php echo $truename;?></td>
<td class="tl">性 别</td>
<td>&nbsp;<?php echo $gender == 1 ? '先生' : '女士';?></td>
</tr>
<tr>
<td class="tl"><?php echo VIP;?>指数</td>
<td>&nbsp;<img src="<?php echo DT_SKIN;?>image/vip_<?php echo $vip;?>.gif"/></td>
<td class="tl">登录次数</td>
<td>&nbsp;<?php echo $logintimes;?></td>
</tr>
<?php if($totime) { ?>
<tr>
<td class="tl">服务开始日期</td>
<td>&nbsp;<?php echo timetodate($fromtime, 3);?></td>
<td class="tl">服务结束日期</td>
<td>&nbsp;<?php echo timetodate($totime, 3);?><?php echo $totime < $DT_TIME ? ' <span class="f_red">[已过期]</span>' : '';?></td>
</tr>
<?php } ?>
<tr>
<td class="tl">上次登录</td>
<td>&nbsp;<?php echo timetodate($logintime, 6);?></td>
<td class="tl">登录IP</td>
<td>&nbsp;<?php echo $loginip;?> - <?php echo ip2area($loginip);?></td>
</tr>
<tr>
<td class="tl">注册时间</td>
<td>&nbsp;<?php echo timetodate($regtime, 6);?></td>
<td class="tl">注册IP</td>
<td>&nbsp;<?php echo $regip;?> - <?php echo ip2area($regip);?></td>
</tr>
<tr>
<td class="tl"><?php echo $DT['money_name'];?>余额</td>
<td>&nbsp;<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=record&username=<?php echo $username;?>', '<?php echo $DT['money_name'];?>流水');"><strong class="f_red"><?php echo $money;?></strong></a> <?php echo $DT['money_unit'];?></td>
<td class="tl">保证金</td>
<td>&nbsp;<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=deposit&username=<?php echo $username;?>', '保证金流水');"><strong class="f_blue"><?php echo $deposit;?></strong></a> <?php echo $DT['money_unit'];?></td>
</tr>
<tr>
<td class="tl">短信余额</td>
<td>&nbsp;<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=sms&action=record&username=<?php echo $username;?>', '短信记录');"><strong class="f_red"><?php echo $sms;?></strong></a> 条</td>
<td class="tl">会员<?php echo $DT['credit_name'];?></td>
<td>&nbsp;<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=credit&username=<?php echo $username;?>', '<?php echo $DT['credit_name'];?>流水');"><strong class="f_blue"><?php echo $credit;?></strong></a> <?php echo $DT['credit_unit'];?></td>
</tr>
</table>
<div class="tt">备注信息</div>
<table cellspacing="0" class="tb">
<?php
	if($note) {
		echo '<tr><th>时间</th><th>内容</th><th width="150">管理员</th></tr>';
		$N = explode('--------------------', $note);
		foreach($N as $n) {
			if(strpos($n, '|') === false) continue;
			list($_time, $_name, $_note) = explode('|', $n);
			if(strlen(trim($_time)) == 16 && check_name($_name) && $_note) echo '<tr><td align="center">'.trim($_time).'</td><td style="padding:6px 10px;line-height:24px;">'.nl2br(trim($_note)).'</td><td align="center"><a href="javascript:_user(\''.$_name.'\')">'.$_name.'</a></td></tr>';
		}
	}
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="note_add"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<tr>
<td class="tl">追加备注</td>
<td align="center">
<textarea name="note" style="width:99%;height:20px;overflow:visible;padding:0;"></textarea></td>
<td align="center" width="130"><input type="submit" name="submit" value="追加" class="btn-g"/><?php if($_admin == 1) {?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:$('#edit_note').toggle();" class="t">修改</a><?php } ?></td>
</tr>
</form>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="note_edit"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<tr id="edit_note" style="display:none;">
<td class="tl">修改备注</td>
<td align="center" class="f_gray">
<textarea name="note" style="width:99%;height:100px;overflow:visible;padding:0;"><?php echo $note;?></textarea><br/>请只修改备注文字，不要改动 | 和 - 符号以及时间和管理员</td>
<td align="center"><input type="submit" name="submit" value="修改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&action=note_edit&userid=<?php echo $userid;?>&note=" class="t" onclick="return confirm('确定要清空此会员的备注信息吗？此操作将不可撤销');">清空</a></td>
</tr>
</form>
</table>
<div class="tt">公司资料</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">公司名称</td>
<td colspan="3">&nbsp;<?php echo $company;?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/company.php?wd=<?php echo urlencode($company);?>" target="_blank" class="t">查询</a></td>
</tr>
<tr>
<td class="tl">公司主页</td>
<td colspan="3">&nbsp;<a href="<?php echo $linkurl;?>" target="_blank" class="t"><?php echo $linkurl;?></a></td>
</tr>
<tr>
<td class="tl">公司类型</td>
<td>&nbsp;<?php echo $type;?></td>
<td class="tl">经营模式</td>
<td>&nbsp;<?php echo $mode;?></td>
</tr>
<tr>
<td class="tl">注册资本</td>
<td>&nbsp;<?php echo $capital;?>万<?php echo $regunit;?></td>
<td class="tl">公司规模</td>
<td>&nbsp;<?php echo $size;?></td>
</tr>
<tr>
<td class="tl">成立年份</td>
<td>&nbsp;<?php echo $regyear;?></td>
<td class="tl">公司所在地</td>
<td>&nbsp;<?php echo area_pos($areaid, '/');?></td>
</tr>
<tr>
<td class="tl">销售的产品 (提供的服务)</td>
<td>&nbsp;<?php echo $sell;?></td>
<td class="tl">采购的产品 (需要的服务)</td>
<td>&nbsp;<?php echo $buy;?></td>
</tr>
<tr>
<td class="tl">主营范围：</td>
<td colspan="3">&nbsp;<?php echo $business;?></td>
</tr>
<?php if($catid) { ?>
<?php $MOD['linkurl'] = $MODULE[4]['linkurl'];?>
<tr>
<td class="tl">主营行业：</td>
<td colspan="3">
	<?php $catids = explode(',', substr($catid, 1, -1));?>
	<?php foreach($catids as $i=>$c) { ?>
	<?php echo cat_pos(get_cat($c), ' / ', '_blank');?>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php } ?>
</td>
</tr>
<?php } ?>
</table>

<div class="tt">联系方式</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">姓 名</td>
<td>&nbsp;<?php echo $truename;?></td>
<td class="tl">手 机</td>
<td>&nbsp;<?php if($mobile) { ?><a href="javascript:Dwidget('?moduleid=2&file=sendsms&mobile=<?php echo $mobile;?>', '发送短信');"><img src="<?php echo DT_SKIN;?>image/mobile.gif" title="发送短信" align="absmiddle"/></a> <?php } ?><a href="javascript:_mobile('<?php echo $mobile;?>');" title="归属地查询"><?php echo $mobile;?></a></td>
</tr>
<tr>
<td class="tl">部 门</td>
<td>&nbsp;<?php echo $department;?></td>
<td class="tl">职 位</td>
<td>&nbsp;<?php echo $career;?></td>
</tr>
<tr>
<td class="tl">电 话</td>
<td>&nbsp;<?php echo $telephone;?></td>
<td class="tl">传 真</td>
<td>&nbsp;<?php echo $fax;?></td>
</tr>
<tr>
<td class="tl">Email (不公开)</td>
<td>&nbsp;<a href="javascript:Dwidget('?moduleid=2&file=sendmail&email=<?php echo $email;?>', '发送邮件');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/email.gif" title="发送Email <?php echo $email;?>" alt="" align="absmiddle"/></a> <?php echo $email;?></td>
<td class="tl">Email (公开)</td>
<td>&nbsp;<?php if($mail) { ?><a href="javascript:Dwidget('?moduleid=2&file=sendmail&email=<?php echo $mail;?>', '发送邮件');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/email.gif" title="发送Email <?php echo $mail;?>" alt="" align="absmiddle"/></a> <?php } ?><?php echo $mail;?></td>
</tr>
<tr>
<td class="tl">QQ</td>
<td>&nbsp;<?php echo im_qq($qq);?> <?php echo $qq;?></td>
<td class="tl">阿里旺旺</td>
<td>&nbsp;<?php echo im_ali($ali);?> <?php echo $ali;?></td>
</tr>
<tr>
<td class="tl">微信</td>
<td>&nbsp;<?php echo im_wx($wx, $username);?> <?php echo $wx;?></td>
<td class="tl">Skype</td>
<td>&nbsp;<?php echo im_skype($skype);?> <?php echo $skype;?></td>
</tr>
<tr>
<td class="tl">网 址</td>
<td>&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=<?php echo $homepage;?>" target="_blank"><?php echo $homepage;?></a></td>
<td class="tl">邮 编</td>
<td>&nbsp;<?php echo $postcode;?></td>
</tr>
<tr>
<td class="tl">公司经营地址</td>
<td colspan="3">&nbsp;<?php echo $address;?></td>
</tr>
</table>

<div class="tt">财务信息</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">开户银行</td>
<td>&nbsp;<?php echo $bank;?></td>
</tr>
<tr>
<td class="tl">开户网点</td>
<td>&nbsp;<?php echo $branch;?></td>
</tr>
<tr>
<td class="tl">账户性质</td>
<td>&nbsp;<?php echo $banktype ? '对公' : '对私';?></td>
</tr>
<tr>
<td class="tl">收款户名</td>
<td>&nbsp;<?php echo $banktype ? $company : $truename;?></td>
</tr>
<tr>
<td class="tl">收款帐号</td>
<td>&nbsp;<?php echo $account;?></td>
</tr>
<tr>
<td class="tl"><?php echo $DT['trade_nm'];?></td>
<td>&nbsp;<?php echo $trade;?></td>
</tr>
</table>

<div class="tt">其他信息</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">推荐注册人</td>
<td>&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&action=show&username=<?php echo $inviter;?>" target="_blank"><?php echo $inviter;?></a></td>
</tr>
<tr>
<td class="tl">企业资料是否通过认证</td>
<td>&nbsp;<?php echo $validated ? '是' : '否';?></td>
</tr>
<tr>
<td class="tl">认证名称或机构</td>
<td>&nbsp;<?php echo $validator;?></td>
</tr>
<tr>
<td class="tl">认证日期</td>
<td>&nbsp;<?php echo $validtime ? timetodate($validtime, 3) : '';?></td>
</tr>
<tr>
<td class="tl">主页风格目录 </td>
<td>&nbsp;<?php echo $skin;?></td>
</tr>
<tr>
<td class="tl">主页模板目录 </td>
<td>&nbsp;<?php echo $template;?></td>
</tr>
<tr>
<td class="tl">顶级域名</td>
<td>&nbsp;<?php echo $domain;?></td>
</tr>
<tr>
<td class="tl">ICP备案号</td>
<td>&nbsp;<?php echo $icp;?></td>
</tr>
<tr>
<td class="tl">黑名单</td>
<td>&nbsp;<?php echo $black;?></td>
</tr>
<tr>
<td class="tl">资料更新时间</td>
<td>&nbsp;<?php echo $edittime ? timetodate($edittime, 6) : '';?></td>
</tr>
<?php if(!isset($dialog)) { ?>
<tr>
<td class="tl"> </td>
<td colspan="3" height="30"><input type="button" value=" 修 改 " class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&action=edit&userid=<?php echo $userid;?>&forward=<?php echo urlencode($DT_URL);?>');"/>&nbsp;&nbsp;<input type="button" value=" 前 台 " class="btn" onclick="window.open('?moduleid=<?php echo $moduleid;?>&action=login&userid=<?php echo $userid;?>');"/>&nbsp;&nbsp;<input type="button" value=" 返 回 " class="btn" onclick="history.back(-1);"/></td>
</tr>
<?php } ?>
</table>
<br/>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>