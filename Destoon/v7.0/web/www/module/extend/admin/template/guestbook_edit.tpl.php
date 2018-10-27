<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 留言人</td>
<td><a href="javascript:_user('<?php echo $username;?>');" class="t"><?php echo $username ? $username : 'Guest';?></a>&nbsp; IP:<?php echo $ip;?> 来自 <?php echo ip2area($ip);?> <input type="checkbox" name="post[hidden]" value="1" <?php if($hidden) echo 'checked';?>/> 匿名留言</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 留言时间</td>
<td><?php echo $addtime;?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 留言内容</td>
<td><textarea name="post[content]" id="content"  rows="8" cols="70"><?php echo $content;?></textarea></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 联系人</td>
<td><?php echo $truename;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 联系电话</td>
<td><?php echo $telephone;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 电子邮件</td>
<td><?php echo $email;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> QQ</td>
<td><?php echo $qq ? im_qq($qq).' '.$qq : '';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 微信</td>
<td><?php echo $wx ? im_wx($wx, $username).' '.$wx : '';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 阿里旺旺</td>
<td><?php echo $ali ? im_ali($ali).' '.$ali : '';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> Skype</td>
<td><?php echo $skype ? im_skype($skype).' '.$skype : '';?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 回复留言</td>
<td><textarea name="post[reply]" id="reply" rows="8" cols="70"><?php echo $reply;?></textarea></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 前台显示</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?>/> 否
</td>
</tr>
<?php if($DT['city']) { ?>
<tr style="display:<?php echo $_areaids ? 'none' : '';?>;">
<td class="tl"><span class="f_hid">*</span> 地区(分站)</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?></td>
</tr>
<?php } ?>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>