<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<select name="vip">
<option value="0"><?php echo VIP;?>级别</option>
<?php 
for($i = 1; $i < 11; $i++) {
	echo '<option value="'.$i.'"'.($i == $vip ? ' selected' : '').'>'.$i.' 级</option>';
}
?>
</select>&nbsp;
<?php echo $group_select;?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>公司名称</th>
<th>会员名</th>
<th>会员组</th>
<th>开通时间</th>
<th>到期时间</th>
<th><?php echo VIP;?>指数</th>
<th>理论值</th>
<th>修正值</th>
<th width="60">管理</th>
</tr>
<?php foreach($companys as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="userid[]" value="<?php echo $v['userid'];?>"/></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['company'];?></a></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><?php echo $GROUP[$v['groupid']]['groupname'];?></td>
<td class="px12"><?php echo timetodate($v['fromtime'], 3);?></td>
<td class="px12"><?php echo timetodate($v['totime'], 3);?></td>
<td><img src="<?php echo DT_SKIN;?>image/vip_<?php echo $v['vip'];?>.gif"/></td>
<td><?php echo $v['vipt'];?></td>
<td><?php echo $v['vipr'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&userid=<?php echo $v['userid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=2&action=login&userid=<?php echo $v['userid'];?>" target="_blank"><img src="admin/image/set.png" width="16" height="16" title="进入会员商务中心" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="撤销<?php echo VIP;?>" class="btn-r" onclick="if(confirm('确定要撤销选中公司<?php echo VIP;?>资格吗吗？')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>