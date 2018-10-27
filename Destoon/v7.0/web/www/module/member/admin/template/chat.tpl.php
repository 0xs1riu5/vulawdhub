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
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="60">头像</th>
<th>发起人</th>
<th>未读消息</th>
<th>最后会话</th>
<th width="60">头像</th>
<th>接收人</th>
<th>未读消息</th>
<th>最后会话</th>
<th width="40"></th>
<th width="40">查看</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="chatid[]" value="<?php echo $v['chatid'];?>"/></td>
<td><img src="<?php echo useravatar($v['fromuser']);?>" style="padding:5px;" width="48" height="48"/></td>
<td>
<?php if(check_name($v['fromuser'])) { ?>
<a href="javascript:_user('<?php echo $v['fromuser'];?>')"><?php echo $v['fromuser'];?></a>
<?php } else { ?>
<a href="javascript:_ip('<?php echo $v['fromuser'];?>')" title="IP:<?php echo $v['fromuser'];?> - <?php echo ip2area($v['fromuser']);?>"><span class="f_gray">游客</span></a>
<?php } ?>
</td>
<td class="px12"><?php echo $v['fnew'];?></td>
<td class="px12"><?php echo timetodate($v['freadtime'], 6);?></td>
<td><img src="<?php echo useravatar($v['touser']);?>" style="padding:5px;" width="48" height="48"/></td>
<td><a href="javascript:_user('<?php echo $v['touser'];?>')"><?php echo $v['touser'];?></a></td>
<td class="px12"><?php echo $v['tnew'];?></td>
<td class="px12"><?php echo timetodate($v['treadtime'], 6);?></td>
<td>
<?php if($v['forward']) { ?>
<a href="<?php echo $v['forward'];?>" target="_blank"><img src="admin/image/link.gif" width="16" height="16" title="点击打开来源网址" alt=""/></a>
<?php } else { ?>
&nbsp;
<?php } ?>
</td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=view&chatid=<?php echo $v['chatid'];?>', '聊天记录');"><img src="admin/image/view.png" width="16" height="16" title="点击查看" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="删除交谈" class="btn-r" onclick="if(confirm('确定要删除选中交谈吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>