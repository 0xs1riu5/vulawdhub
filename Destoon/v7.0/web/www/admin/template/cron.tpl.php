<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="60" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th>名称</th>
<th>时间表</th>
<th width="80">文件名</th>
<th width="130">上次运行</th>
<th width="130">下次运行</th>
<th width="40">状态</th>
<th width="50">管理</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center" title="<?php echo $v['note'];?>">
<td><a href="?file=<?php echo $file;?>&action=run&itemid=<?php echo $v['itemid'];?>"<?php if($v['status']) {?> onclick="return confirm('此任务已禁用，确定要运行吗？');"<?php } ?>><?php echo $v['title'];?></a></td>
<td><?php echo $v['text'];?></td>
<td title="api/cron/<?php echo $v['name'];?>.inc.php"><?php echo $v['name'];?></td>
<td class="px12"><?php echo $v['lasttime'];?></td>
<td class="px12"><?php echo $v['nexttime'];?></td>
<td><?php echo $v['status'] ? '<span class="f_red">禁用</span>' : '<span class="f_green">正常</span>';?></td>
<td><a href="?file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>