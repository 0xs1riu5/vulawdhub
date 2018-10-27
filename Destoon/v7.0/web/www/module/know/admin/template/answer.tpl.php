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
<?php echo $order_select;?>&nbsp;
提问ID <input type="text" size="5" name="qid" value="<?php echo $qid;?>"/>&nbsp;
<input type="checkbox" name="expert" value="1"<?php echo $expert ? ' checked' : '';?>/> 专家&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&qid=<?php echo $qid;?>');"/>
</form>
</div>
<form method="post">
<div id="content">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>答案内容</th>
<th width="60">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td align="left" style="padding:10px;">
<div>
<span class="f_r f_gray">
<?php if($v['expert']) {?><span class="f_red">专家</span>&nbsp;|&nbsp;<?php } ?>票数 (<?php echo $v['vote'];?>)
</span>
<span class="px12 f_blue">
<?php echo $v['adddate'];?>
</span>
&nbsp;
<?php if($v['username']) { ?>
<a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['passport'];?></a> 
<?php } else { ?>
Guest
<?php } ?>
<?php if($v['hidden']) { ?>
(匿名)
<?php } ?>
</div>
<div class="b5 c_b"> </div>
<div>
<?php echo $v['content'];?>
</div>

<div class="b5 c_b"> </div>
<div><a href="<?php echo DT_PATH;?>api/redirect.php?mid=<?php echo $moduleid;?>&itemid=<?php echo $v['qid'];?>" target="_blank"><img src="admin/image/link.gif" width="16" height="16" title="点击打开原问题" alt="" align="absmiddle"/></a> IP:<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&ip=<?php echo $v['ip'];?>"><?php echo $v['ip'];?></a> - <?php echo ip2area($v['ip']);?></div>

<?php if($v['url']) { ?>
<div class="b5 c_b"> </div>
<div>参考资料：<a href="<?php echo DT_PATH;?>api/redirect.php?url=<?php echo urlencode($v['url']);?>" target="_blank"><?php echo $v['url'];?></a></div>
<?php } ?>
</td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
</div>
<div class="btns">
<?php if($action == 'check') { ?>
<input type="submit" value="通过审核" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check&status=3';"/>&nbsp;
<?php } else { ?>
<input type="submit" value="取消审核" class="btn-r" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check&status=2';"/>&nbsp;
<?php } ?>
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中答案吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">
var content_id = 'content';
var img_max_width = <?php echo $MOD['max_width'];?>;
</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/content.js"></script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>