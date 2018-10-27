<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="chatid" value="<?php echo $chatid;?>"/>
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<input type="text" size="10" name="username" value="<?php echo $username;?>" placeholder="会员名" title="请输入会员名"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&chatid=<?php echo $chatid;?>');"/>
</form>
</div>
<table cellspacing="0" class="tb ls">
<tr>
<th width="60">头像</th>
<th width="160">会员</th>
<th width="160">时间</th>
<th>内容</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><img src="<?php echo useravatar($v['name']);?>" style="padding:5px;" width="48" height="48"/></td>
<td>
<?php if(check_name($v['name'])) { ?>
<a href="javascript:_user('<?php echo $v['name'];?>')"><?php echo $v['name'];?></a>
<?php } else { ?>
<a href="javascript:_ip('<?php echo $v['name'];?>')" title="IP:<?php echo $v['name'];?> - <?php echo ip2area($v['name']);?>"><span class="f_gray">游客</span></a>
<?php } ?>
</td>
<td class="px12"><?php echo $v['date'];?></td>
<td style="padding:10px;text-align:left;line-height:180%;"><?php echo $v['word'];?></td>
</tr>
<?php }?>
</table>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<?php include tpl('footer');?>