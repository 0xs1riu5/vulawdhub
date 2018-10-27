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
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $type_select;?>&nbsp;
<?php echo $level_select;?>&nbsp;
<?php echo $DT['city'] ? ajax_area_select('areaid', '地区(分站)', $areaid).'&nbsp;' : '';?>
<span data-hide="1200"><?php echo $order_select;?>&nbsp;</span>
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th data-hide="1200">ID</th>
<th data-hide="1200">分类</th>
<th width="14"> </th>
<th>标题</th>
<th>状态</th>
<th>选项</th>
<th>回复</th>
<th>浏览</th>
<th>添加时间</th>
<th width="100">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center" title="编辑:<?php echo $v['editor'];?>&#10;更新时间:<?php echo $v['editdate'];?>">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td data-hide="1200"><?php echo $v['itemid'];?></td>
<td data-hide="1200"><a href="<?php echo $v['typeurl'];?>" target="_blank"><?php echo $v['typename'];?></td>
<td><?php if($v['level']) {?><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&level=<?php echo $v['level'];?>"><img src="admin/image/level_<?php echo $v['level'];?>.gif" title="<?php echo $v['level'];?>级" alt=""/></a><?php } ?></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['title'];?></td>
<td><img src="<?php echo DT_PATH;?>file/image/process_<?php echo get_process($v['fromtime'], $v['totime']);?>.gif"/></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=question&fid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 表单选项');"><?php echo $v['question'];?></a></td>
<td class="px12"><a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=answer&fid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 回复记录');"><?php echo $v['answer'];?></a></td>
<td class="px12"><?php echo $v['hits'];?></td>
<td class="px12"><?php echo $v['adddate'];?></td>
<td>
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=question&fid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 表单选项');"><img src="admin/image/child.png" width="16" height="16" title="管理选项" alt=""/></a>&nbsp;
<a href="javascript:Dwidget('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=answer&fid=<?php echo $v['itemid'];?>', '[<?php echo $v['alt'];?>] 回复记录');"><img src="admin/image/poll.png" width="16" height="16" title="回复记录" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&itemid=<?php echo $v['itemid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中表单吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
<?php echo level_select('level', '设置级别为</option><option value="0">取消', 0, 'onchange="this.form.action=\'?moduleid='.$moduleid.'&file='.$file.'&action=level\';this.form.submit();"');?>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<div class="tt">链接说明</div>
<table cellspacing="0" class="tb">
<tr>
<td class="lh20 f_gray">
&nbsp;&nbsp;&nbsp;一般情况直接链接到表单网址，如果需要自定义参数来区分不同场景，可使用如下地址：<br/>
&nbsp;&nbsp;&nbsp;<?php echo $EXT['form_url'];?>index.php?itemid=表单ID&item=参数<br/>
&nbsp;&nbsp;&nbsp;参数限制为a-z、A-Z、0-9、中划线(-)、下划线(_)的组合<br/>
</td>
</tr>
</table>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>