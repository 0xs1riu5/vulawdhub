<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&itemid=<?php echo $itemid;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th width="130">下单时间</th>
<th>礼品</th>
<th data-hide="1200"><?php echo $DT['credit_name'];?></th>
<th>会员名</th>
<th>订单状态</th>
<th data-hide="1200">备注信息</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input name="post[<?php echo $v['oid'];?>][delete]" type="checkbox" value="1"/><input name="post[<?php echo $v['oid'];?>][itemid]" type="hidden" value="<?php echo $v['itemid'];?>"/></td>
<td class="px12"><?php echo $v['adddate'];?></td>
<td align="left">&nbsp;<a href="<?php echo $v['linkurl'];?>" target="_blank" title="<?php echo $v['title'];?>"><?php echo dsubstr($v['title'], 30, '..');?></a></td>
<td data-hide="1200" class="px12"><?php echo $v['credit'];?></td>
<td title="IP:<?php echo $v['ip'];?>(<?php echo ip2area($v['ip']);?>)"><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td><input name="post[<?php echo $v['oid'];?>][status]" type="text" size="10" value="<?php echo $v['status'];?>" id="status_<?php echo $v['oid'];?>"/>
<select onchange="if(this.value)Dd('status_<?php echo $v['oid'];?>').value=this.value;">
<option value="">备选状态</option>
<option value="处理中">处理中</option>
<option value="审核中">审核中</option>
<option value="已取消">已取消</option>
<option value="已发货">已发货</option>
<option value="已完成">已完成</option>
</select>
</td>
<td data-hide="1200"><input name="post[<?php echo $v['oid'];?>][note]" type="text" size="15" value="<?php echo $v['note'];?>"/></td>
</tr>
<?php }?>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td height="30" colspan="6"><input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个订单，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/></td>
</tr>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>