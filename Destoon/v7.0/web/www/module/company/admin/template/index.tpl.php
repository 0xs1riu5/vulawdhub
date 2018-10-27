<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="25" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $level_select;?>&nbsp;
<select name="vip">
<option value=""><?php echo VIP;?>级别</option>
<?php 
for($i = 0; $i < 11; $i++) {
	echo '<option value="'.$i.'"'.($i == $vip ? ' selected' : '').'>'.$i.' 级</option>';
}
?>
</select>&nbsp;
<?php echo $valid_select;?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
<tr>
<td>&nbsp;
<?php echo category_select('catid', '所属行业', $catid, $moduleid);?>&nbsp;
<?php echo ajax_area_select('areaid', '所在地区', $areaid);?>&nbsp;
<?php echo $mode_select;?>&nbsp;
<?php echo $type_select;?>&nbsp;
<?php echo $size_select;?>&nbsp;
<input type="checkbox" name="thumb" value="1"<?php echo $thumb ? ' checked' : '';?>/> 图片&nbsp;
</td>
</tr>
<tr>
<td>&nbsp;
<select name="timetype">
<option value="totime" <?php if($timetype == 'totime') echo 'selected';?>>服务到期</option>
<option value="fromtime" <?php if($timetype == 'fromtime') echo 'selected';?>>服务开始</option>
<option value="validtime" <?php if($timetype == 'validtime') echo 'selected';?>>认证时间</option>
<option value="styletime" <?php if($timetype == 'styletime') echo 'selected';?>>模板到期</option>
</select>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
注册资本：<input type="text" size="5" name="mincapital" value="<?php echo $mincapital;?>"/> ~ <input type="text" size="5" name="maxcapital" value="<?php echo $maxcapital;?>"/> 万&nbsp;
会员名：<input type="text" name="username" value="<?php echo $username;?>" size="10"/>&nbsp;
会员ID：<input type="text" name="uid" value="<?php echo $uid;?>" size="10"/>&nbsp;
</td>
</tr>
</table>
</form>
<form method="post">
<?php if($action == 'domain') { ?>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="14"> </th>
<th><?php echo $MOD['name'];?>名称</th>
<th>会员名</th>
<th>绑定域名</th>
<th>备案号</th>
<th><?php echo VIP;?>指数</th>
<th>人气</th>
<th>评论</th>
<th width="60">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="userid[]" value="<?php echo $v['userid'];?>"/></td>
<td><?php if($v['level']) {?><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&level=<?php echo $v['level'];?>"><img src="admin/image/level_<?php echo $v['level'];?>.gif" title="<?php echo $v['level'];?>级" alt=""/></a><?php } ?></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['company'];?></a><?php if($v['vip']) {?> <img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif" title="<?php echo VIP;?>:<?php echo $v['vip'];?>" align="absmiddle"/><?php } ?><?php if($v['thumb']) {?> <a href="javascript:_preview('<?php echo $v['thumb'];?>');"><img src="admin/image/img.gif" width="10" height="10" title="标题图,点击预览" alt=""/></a><?php } ?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['domain'];?></a></td>
<td><?php echo $v['icp'];?></td>
<td><img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif"/></td>
<td><?php echo $v['hits'];?></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=3&file=comment&mid=<?php echo $moduleid;?>&itemid=<?php echo $v['userid'];?>', '[<?php echo $v['company'];?>] 评论列表');"><?php echo $v['comments'];?></a></td>
<td><a href="?moduleid=2&action=edit&userid=<?php echo $v['userid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改会员[<?php echo $v['username'];?>]资料" alt=""/></a>&nbsp;
<a href="?moduleid=2&action=delete&userid=<?php echo $v['userid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php } else { ?>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="14"> </th>
<th><?php echo $MOD['name'];?>名称</th>
<th>会员名</th>
<th>所在地</th>
<th>注册年份</th>
<th>注册资本</th>
<th><?php echo VIP;?>指数</th>
<th>人气</th>
<th>评论</th>
<th width="60">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center" title="<?php echo $MOD['name'];?>类型:<?php echo $v['type'];?>&#10;<?php echo $MOD['name'];?>规模:<?php echo $v['size'];?>">
<td><input type="checkbox" name="userid[]" value="<?php echo $v['userid'];?>"/></td>
<td><?php if($v['level']) {?><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&level=<?php echo $v['level'];?>"><img src="admin/image/level_<?php echo $v['level'];?>.gif" title="<?php echo $v['level'];?>级" alt=""/></a><?php } ?></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['company'];?></a><?php if($v['vip']) {?> <img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif" title="<?php echo VIP;?>:<?php echo $v['vip'];?>" align="absmiddle"/><?php } ?><?php if($v['thumb']) {?> <a href="javascript:_preview('<?php echo $v['thumb'];?>');"><img src="admin/image/img.gif" width="10" height="10" title="标题图,点击预览" alt=""/></a><?php } ?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><?php echo area_pos($v['areaid'], '/');?></td>
<td><?php echo $v['regyear'];?></td>
<td><?php echo $v['capital'] ? $v['capital'].'万'.$v['regunit'] : '未填';?></td>
<td><img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif"/></td>
<td><?php echo $v['hits'];?></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=3&file=comment&mid=<?php echo $moduleid;?>&itemid=<?php echo $v['userid'];?>', '[<?php echo $v['company'];?>] 评论列表');"><?php echo $v['comments'];?></a></td>
<td><a href="?moduleid=2&action=edit&userid=<?php echo $v['userid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改会员[<?php echo $v['username'];?>]资料" alt=""/></a>&nbsp;
<a href="?moduleid=2&action=delete&userid=<?php echo $v['userid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php } ?>
<div class="btns">
<input type="submit" value="设置<?php echo VIP;?>" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=vip&action=add';"/>&nbsp;
<input type="submit" value="删除公司" class="btn-r" onclick="if(confirm('确定要删除选中会员吗？系统将删除选中用户所有信息，此操作将不可撤销')){this.form.action='?moduleid=2&action=delete'}else{return false;}"/>&nbsp;
<input type="submit" value="移动地区" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=move';"/>&nbsp;
<?php echo level_select('level', '设置级别为</option><option value="0">取消', 0, 'onchange="this.form.action=\'?moduleid='.$moduleid.'&file='.$file.'&action=level\';this.form.submit();"');?>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>