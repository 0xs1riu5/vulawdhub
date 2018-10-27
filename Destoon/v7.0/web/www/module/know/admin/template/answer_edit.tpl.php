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
<td class="tl"><span class="f_hid">*</span> 回答者</td>
<td><a href="javascript:_user('<?php echo $username;?>');" class="t"><?php echo $username ? $passport : 'Guest';?></a></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> IP</td>
<td><?php echo $ip;?> 来自 <?php echo ip2area($ip);?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 答案内容</td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', 'Destoon', '100%', 350);?><br/><span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 参考资料</td>
<td><input type="text" name="post[url]" value="<?php echo $url;?>" size="60"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 匿名设定</td>
<td>
<input type="radio" name="post[hidden]" value="1" <?php if($hidden == 1) echo 'checked';?> id="hidden_1"/><label for="hidden_1">  是</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[hidden]" value="0" <?php if($hidden == 0) echo 'checked';?> id="hidden_0"/><label for="hidden_0"> 否</label>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 答案状态</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?>/> 通过&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?>/> 待审
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">Menuon(<?php echo $status == 3 ? 0 : 1;?>);</script>
<?php include tpl('footer');?>