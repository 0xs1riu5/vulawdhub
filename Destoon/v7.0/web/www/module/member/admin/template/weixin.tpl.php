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
<input type="hidden" name="openid" value="<?php echo $openid;?>"/>
<?php echo $openid ? '' : $fields_select.'&nbsp;';?>

<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<select name="type">
<option value="">消息类型</option>
<?php
foreach($TYPE as $k=>$v) {
	echo '<option value="'.$k.'" '.($type == $k ? 'selected' : '').'>'.$v.'</option>';
}
?>
</select>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&openid=<?php echo $openid;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb">
<tr>
<?php if(!$openid) { ?>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="70">头像</th>
<th width="150">昵称</th>
<th width="100">会员名</th>
<?php } ?>
<th width="100">消息类型</th>
<th width="130">发送时间</th>
<th>消息内容</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<?php if(!$openid) { ?>
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><a href="javascript:_preview('<?php echo $v['headimgurl'];?>');"><img src="<?php echo $v['headimgurl'];?>" width="46" style="margin:5px 0 5px 0;"/></a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&openid=<?php echo $v['openid'];?>&action=chat', '与[<?php echo $v['nickname'];?>]交谈中...', 550, 490);"><?php echo $v['nickname'];?></a></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>')"><?php echo $v['username'];?></a></td>
<?php } ?>
<td><?php echo $TYPE[$v['type']];?></td>
<td class="px12"><?php echo $v['adddate'];?></td>
<td align="left"><div style="padding:5px;"><?php echo $v['msg'];?></div></td>
</tr>
<?php }?>
</table>
<?php if(!$openid) { ?>
<div class="btns">
<input type="submit" value="删除记录" class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&openid=<?php echo $openid;?>&action=delete'}else{return false;}"/>&nbsp;
</div>
<?php } ?>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(<?php echo $action == 'event' ? 1 : 0;?>);</script>
<?php include tpl('footer');?>