<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="40" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<select name="online">
<option value="2"<?php if($online==2) echo ' selected';?>>状态</option>
<option value="1"<?php if($online==1) echo ' selected';?>>在线</option>
<option value="0"<?php if($online==0) echo ' selected';?>>隐身</option>
</select>&nbsp;
<?php echo module_select('mid', '模块', $mid);?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<table cellspacing="0" class="tb ls">
<tr>
<th width="60">头像</th>
<th>会员名</th>
<th>状态</th>
<th>所在模块</th>
<th>IP</th>
<th>IP所在地</th>
<th width="130">访问时间</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><img src="<?php echo useravatar($v['username']);?>" style="padding:5px;" width="48" height="48"/></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>')"><?php echo $v['username'];?></a></td>
<td><?php echo $v['online'] ? '<span class="f_green">在线</span>' : '<span class="f_gray">隐身</span>';?></td>
<td><a href="<?php echo $MODULE[$v['moduleid']]['linkurl'];?>" target="_blank"><?php echo $MODULE[$v['moduleid']]['name'];?></a></td>
<td><?php echo $v['ip'];?></td>
<td><?php echo ip2area($v['ip']);?></td>
<td><?php echo $v['lasttime'];?></td>
</tr>
<?php }?>
</table>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>