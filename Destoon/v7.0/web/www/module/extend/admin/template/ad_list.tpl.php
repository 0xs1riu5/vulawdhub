<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menusad);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="job" value="<?php echo $job;?>"/>
<input type="hidden" name="pid" value="<?php echo $pid;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="15" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo $order_select;?>&nbsp;
<?php echo ajax_area_select('areaid', '地区(分站)', $areaid);?>&nbsp;
<span data-hide="1200">广告位ID： <input type="text" name="pid" value="<?php echo $pid;?>" size="2" class="t_c"/>&nbsp;</span>
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&job=<?php echo $job;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="40">排序</th>
<th data-hide="1200">ID</th>
<?php if($pid == 0) { ?>
<th>广告类型</th>
<?php } ?>
<th>广告名称</th>
<th data-hide="1200">出价</th>
<th data-hide="1200">单位</th>
<th>点击</th>
<th data-hide="1200">开始时间</th>
<th data-hide="1200">结束时间</th>
<th data-hide="1200">剩余(天)</th>
<th>状态</th>
<th data-hide="1200">审核</th>
<th data-hide="1200">会员</th>
<th width="80">操作</th>
</tr>
<?php foreach($ads as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="aids[]" value="<?php echo $v['aid'];?>"/></td>
<td><input type="text" size="2" name="listorder[<?php echo $v['aid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<td data-hide="1200"><?php echo $v['aid'];?></td>
<?php if($pid == 0) { ?>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&job=<?php echo $job;?>&typeid=<?php echo $v['typeid'];?>"><?php echo $TYPE[$v['typeid']];?></a></td>
<?php } ?>
<td align="left" title="编辑:<?php echo $v['editor'];?>&#10;添加时间:<?php echo $v['adddate'];?>&#10;更新时间:<?php echo $v['editdate'];?>">&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&aid=<?php echo $v['aid'];?>&pid=<?php echo $v['pid'];?>"><?php echo $v['title'];?></a></td>
<td data-hide="1200" class="f_red f_b"><?php echo $v['amount'];?></td>
<td data-hide="1200"><?php echo $v['currency'] == 'money' ? $DT['money_unit'] : $DT['credit_unit'];?></td>
<td><?php echo $v['hits'];?></td>
<td data-hide="1200"><?php echo $v['fromdate'];?></td>
<td data-hide="1200"><?php echo $v['todate'];?></td>
<td data-hide="1200"<?php if($v['days']<5) echo ' class="f_red"';?>><?php echo $v['days'];?></td>
<td><?php echo $v['process'];?></td>
<td data-hide="1200"><?php echo $v['status']==3 ? '已通过' : '<span class="f_red">待审核</span>';?></td>
<td data-hide="1200"><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=view&aid=<?php echo $v['aid'];?>" target="_blank"/><img src="admin/image/view.png" width="16" height="16" title="预览此广告" alt=""></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&aid=<?php echo $v['aid'];?>&pid=<?php echo $v['pid'];?>"><img src="admin/image/edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&aids=<?php echo $v['aid'];?>&pid=<?php echo $v['pid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="更新排序" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=order_ad&pid=<?php echo $pid;?>';"/>&nbsp;
<input type="submit" value="删 除" class="btn-r" onclick="if(confirm('确定要删除选中广告吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&pid=<?php echo $pid;?>'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<br/>
<script type="text/javascript">Menuon(<?php echo $job == 'check' ? 2 : 1;?>);</script>
<?php include tpl('footer');?>