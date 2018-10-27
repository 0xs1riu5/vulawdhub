<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $type_select;?>&nbsp;
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<table cellspacing="0" class="tb ls">
<tr>
<th>&nbsp;ID&nbsp;</th>
<th>分 类</th>
<th>标 题</th>
<th>添加时间</th>
<th>发送时间</th>
<th>订阅人数</th>
<th width="80">操作</th>
</tr>
<?php foreach($mails as $k=>$v) {?>
<tr align="center">
<td><?php echo $v['itemid'];?></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&typeid=<?php echo $v['typeid'];?>"><?php echo $v['type'];?></a></td>
<td align="left"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><?php echo $v['title'];?></a></td>
<td><?php echo $v['addtime'];?></td>
<td><?php echo $v['sendtime'];?></td>
<td title="点击查看订阅会员列表"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&typeid=<?php echo $v['typeid'];?>"><?php echo $v['num'];?></a></td>
<td title="编辑:<?php echo $v['editor'];?>,上次修改:<?php echo $v['edittime'];?>">
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=send&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/child.png" width="16" height="16" title="发送邮件" alt=""/></a>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<?php if(!$TYPE) { ?>
<script type="text/javascript">Dwidget('?file=type&item=<?php echo $file;?>', '启用邮件订阅，请先添加订阅分类');</script>
<?php } ?>
<script type="text/javascript">Menuon(1);</script>
<br/>
<?php include tpl('footer');?>