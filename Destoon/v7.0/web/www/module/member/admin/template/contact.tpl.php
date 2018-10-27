<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="export" id="export" value="<?php echo $export;?>"/>
<input type="hidden" name="page" id="page" value="0"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $group_select;?>&nbsp;
<?php echo $gender_select;?>&nbsp;
<?php echo $order_select;?>
&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>
<input type="submit" value="搜 索" class="btn" onclick="Dd('export').value=0;Dd('page').value=0;"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>&nbsp;
<input type="submit" value="导出CSV" class="btn" onclick="Dd('export').value=1;Dd('page').value=<?php echo $page;?>;"/>
</td>
</tr>
<tr>
<td>&nbsp;
<select name="timetype">
<option value="m.regtime" <?php if($timetype == 'm.regtime') echo 'selected';?>>注册时间</option>
<option value="m.logintime" <?php if($timetype == 'm.logintime') echo 'selected';?>>登录时间</option>
<option value="c.totime" <?php if($timetype == 'c.totime') echo 'selected';?>>服务到期</option>
<option value="c.fromtime" <?php if($timetype == 'c.fromtime') echo 'selected';?>>服务开始</option>
<option value="c.validtime" <?php if($timetype == 'c.validtime') echo 'selected';?>>认证时间</option>
<option value="c.styletime" <?php if($timetype == 'c.styletime') echo 'selected';?>>模板到期</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<?php echo $DT['money_name'];?>：<input type="text" size="3" name="minmoney" value="<?php echo $minmoney;?>"/> ~ <input type="text" size="3" name="maxmoney" value="<?php echo $maxmoney;?>"/>&nbsp;
<?php echo $DT['credit_name'];?>：<input type="text" size="3" name="mincredit" value="<?php echo $mincredit;?>"/> ~ <input type="text" size="3" name="maxcredit" value="<?php echo $maxcredit;?>"/>&nbsp;
短信：<input type="text" size="3" name="minsms" value="<?php echo $minsms;?>"/> ~ <input type="text" size="3" name="maxsms" value="<?php echo $maxsms;?>"/>&nbsp;
</td>
</tr>
<tr>
<td>&nbsp;
<?php echo category_select('catid', '所属行业', $catid, 4);?>&nbsp;
<?php echo ajax_area_select('areaid', '所在地区', $areaid);?>&nbsp;
<?php echo $mode_select;?>&nbsp;
<?php echo $type_select;?>&nbsp;
<?php echo $size_select;?>&nbsp;
<select name="vip">
<option value=""><?php echo VIP;?>级别</option>
<?php 
for($i = 0; $i < 11; $i++) {
	echo '<option value="'.$i.'"'.($i == $vip ? ' selected' : '').'>'.$i.' 级</option>';
}
?>
</select>&nbsp;
<input type="checkbox" name="thumb" value="1"<?php echo $thumb ? ' checked' : '';?>/>图片&nbsp;
</td>
</tr>
<tr>
<td>&nbsp;
<?php echo $valid_select;?> 
<?php echo $vprofile_select;?> 
<?php echo $vemail_select;?> 
<?php echo $vmobile_select;?> 
<?php echo $vtruename_select;?> 
<?php echo $vbank_select;?> 
<?php echo $vcompany_select;?> 
<?php echo $avatar_select;?> 
会员名：<input type="text" name="username" value="<?php echo $username;?>" size="8"/>&nbsp;
会员ID：<input type="text" name="uid" value="<?php echo $uid;?>" size="4"/>
</td>
</tr>
</table>
</form>
<table cellspacing="0" class="tb ls">
<tr>
<th>会员名</th>
<th>公司</th>
<th>姓名</th>
<th>职位</th>
<th>性别</th>
<th>电话</th>
<th>手机</th>
<th colspan="8">联系方式</th>
<th width="40">状态</th>
</tr>
<?php foreach($members as $k=>$v) {?>
<tr align="center" title="会员名:<?php echo $v['username'];?>&#10;会员ID:<?php echo $v['userid'];?>&#10;会员组:<?php echo $GROUP[$v['groupid']]['groupname'];?>">
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['company'];?></a><?php if($v['vip']) {?> <img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif" title="<?php echo VIP;?>:<?php echo $v['vip'];?>" align="absmiddle"/><?php } ?></td>
<td><?php echo $v['truename'];?></td>
<td><?php echo $v['career'];?></td>
<td><?php echo gender($v['gender']);?></td>
<td><?php echo $v['telephone'];?></td>
<td><a href="javascript:_mobile('<?php echo $v['mobile'];?>')"><?php echo $v['mobile'];?></a></td>
<td width="20"><?php if($v['mobile']) { ?><a href="javascript:Dwidget('?moduleid=2&file=sendsms&mobile=<?php echo $v['mobile'];?>', '发送短信');"><img src="<?php echo DT_SKIN;?>image/mobile.gif" title="发送短信" alt=""/></a><?php } else { ?>&nbsp;<?php } ?></td>
<td width="20"><a href="javascript:Dwidget('?moduleid=2&file=message&action=send&touser=<?php echo $v['username'];?>', '发送消息');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/msg.gif" title="发送消息" alt=""/></a></td> 
<td width="20"><a href="javascript:Dwidget('?moduleid=2&file=sendmail&email=<?php echo $v['email'];?>', '发送邮件');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/email.gif" title="发送邮件" alt=""/></a></td>
<td width="20"><?php if($DT['im_web']) { echo im_web($v['username']); } else { echo '&nbsp;'; } ?></td>
<td width="20"><?php if($v['qq']) { echo im_qq($v['qq']); } else { echo '&nbsp;'; } ?></td>
<td width="20"><?php if($v['wx']) { echo im_wx($v['wx'], $v['username']); } else { echo '&nbsp;'; } ?></td>
<td width="20"><?php if($v['ali']) { echo im_ali($v['ali']); } else { echo '&nbsp;'; } ?></td>
<td width="20"><?php if($v['skype']) { echo im_skype($v['skype']); } else { echo '&nbsp;'; } ?></td>
<td><?php $ol = online($v['userid']);if($ol == 1) { ?><span class="f_red">在线</span><?php } else if($ol == -1) { ?><span class="f_blue">隐身</span><?php } else { ?><span class="f_gray">离线</span><?php } ?></td>
</tr>
<?php }?>
</table>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<br/>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>