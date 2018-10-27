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
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
会员ID:<input type="text" name="userid" value="<?php echo $userid;?>" size="5"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>姓名</th>
<th>会员</th>
<th>公司</th>
<th colspan="8">联系方式</th>
<th>会员ID</th>
<th width="130">添加时间</th>
<th width="50">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center" title="备注:<?php echo $v['note'];?>">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td align="left">&nbsp;<?php echo $v['truename'];?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td align="left">&nbsp;<?php echo $v['company'];?></td>

<td width="20"><?php if($v['homepage']) { ?><a href="<?php echo $v['homepage'];?>" target="_blank"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/homepage.gif" title="公司主页" alt=""/></a><?php } else { ?>&nbsp;<?php } ?></td>

<td width="20"><?php if($v['mobile']) { ?><a href="javascript:Dwidget('?moduleid=2&file=sendsms&mobile=<?php echo $v['mobile'];?>', '发送短信');"><img src="<?php echo DT_SKIN;?>image/mobile.gif" title="发送短信" alt=""/></a><?php } else { ?>&nbsp;<?php } ?></td>

<td width="20"><?php if($v['username']) { ?><a href="javascript:Dwidget('?moduleid=2&file=message&action=send&touser=<?php echo $v['username'];?>', '发送消息');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/msg.gif" title="发送消息" alt=""/></a><?php } else { ?>&nbsp;<?php } ?></td> 

<td width="20"><?php if($v['email']) { ?><a href="javascript:Dwidget('?moduleid=2&file=sendmail&email=<?php echo $v['email'];?>', '发送邮件');"><img width="16" height="16" src="<?php echo DT_SKIN;?>image/email.gif" title="发送邮件" alt=""/></a><?php } else { ?>&nbsp;<?php } ?></td>

<td width="20"><?php if($v['qq']) { echo im_qq($v['qq']); } else { echo '&nbsp;'; } ?></td>
<td width="20"><?php if($v['wx']) { echo im_wx($v['wx'], $v['username']); } else { echo '&nbsp;'; } ?></td>

<td width="20"><?php if($v['ali']) { echo im_ali($v['ali']); } else { echo '&nbsp;'; } ?></td>
<td width="20"><?php if($v['skype']) { echo im_skype($v['skype']); } else { echo '&nbsp;'; } ?></td>

<td><a href="javascript:_user('<?php echo $v['userid'];?>', 'userid');"><?php echo $v['userid'];?></a></td>
<td class="px12"><?php echo $v['adddate'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删 除 " class="btn-r" onclick="if(confirm('确定要删除选中商友吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;&nbsp;&nbsp;备注：会员ID代表商友的添加人(拥有者)
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>