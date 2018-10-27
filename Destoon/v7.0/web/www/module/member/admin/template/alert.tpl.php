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
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<select name="mid">
<option value="0">类型</option>
<?php foreach($mids as $v) { ?>
<option value="<?php echo $v;?>"<?php echo $mid == $v ? ' selected' : '';?>><?php echo $MODULE[$v]['name'];?></option>
<?php } ?>
</select>&nbsp;
<?php echo ajax_area_select('areaid', '所在地区', $areaid);?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>类别</th>
<th>关键词</th>
<th>行业</th>
<th>地区</th>
<th>添加时间</th>
<th>上次发送</th>
<th>频率</th>
<th>会员</th>
<th>邮件</th>
<th width="50">管理</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><a href="<?php echo $MODULE[$v['mid']]['linkurl'];?>" class="t" target="_blank"><?php echo $MODULE[$v['mid']]['name'];?></a></td>
<td><?php if($v['word']) { ?><a href="<?php echo $MODULE[$v['mid']]['linkurl'];?>search.php?kw=<?php echo urlencode($v['word']);?>" class="t" target="_blank"><?php echo $v['word'];?></a><?php } else { ?>不限<?php } ?></td>
<td><?php if($v['catid']) { ?><?php echo $v['cate'];?><?php } else { ?>不限<?php } ?></td>
<td><?php if($v['areaid']) { ?><a href="<?php echo $MODULE[$v['mid']]['linkurl'];?>search.php?areaid=<?php echo $v['areaid'];?>" target="_blank"><?php echo area_pos($v['areaid'], '-');?></a><?php } else { ?>不限<?php } ?></td>
<td class="px12 f_gray"><?php echo timetodate($v['addtime'], 5);?></td>
<?php if($v['sendtime']) { ?>
<td class="px12 f_gray"><?php echo timetodate($v['sendtime'], 5);?></td>
<?php } else { ?>
<td class="f_gray">从未</td>
<?php } ?>
<td class="f_red"><?php if($v['rate']) { ?><?php echo $v['rate'];?>天<?php } else { ?>不限<?php } ?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>

<td class="px12 f_gray"><?php echo $v['email'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<?php if($action == 'check') { ?>
<input type="submit" value=" 通过审核 " class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=check';"/>&nbsp;
<?php } else { ?>
<input type="submit" value=" 撤销审核 " class="btn-r" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=reject';"/>&nbsp;
<?php } ?>
<input type="submit" value=" 删 除 " class="btn-r" onclick="if(confirm('确定要删除选中贸易提醒吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>