<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<div class="sbox">
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="submit" name="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 搜" class="btn" onclick="Go('?file=<?php echo $file;?>');"/>&nbsp;
</div>
</form>
<form method="post">
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<?php if($parentid) {?>
<div class="tt">
<span class="f_r"><a href="?file=<?php echo $file;?>&parentid=<?php echo $AREA[$parentid]['parentid'];?>" class="t" style="font-weight:normal;">返回上级</a></span>
<?php echo $AREA[$parentid]['areaname'];?>
</div>
<?php }?>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="100">排序</th>
<th width="100">ID</th>
<th>上级ID</th>
<th>地区名</th>
<th width="80">子地区</th>
<th width="80">操作</th>
</tr>
<?php foreach($DAREA as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="areaids[]" value="<?php echo $v['areaid'];?>"/></td>
<td><input name="area[<?php echo $v['areaid'];?>][listorder]" type="text" size="5" value="<?php echo $v['listorder'];?>"/></td>
<td>&nbsp;<?php echo $v['areaid'];?></td>
<td><input name="area[<?php echo $v['areaid'];?>][parentid]" type="text" size="10" value="<?php echo $v['parentid'];?>"/></td>
<td><input name="area[<?php echo $v['areaid'];?>][areaname]" type="text" size="20" value="<?php echo $v['areaname'];?>"/></td>
<td>&nbsp;<a href="?file=<?php echo $file;?>&parentid=<?php echo $v['areaid'];?>"><?php echo $v['childs'];?></a></td>
<td>
<a href="?file=<?php echo $file;?>&action=add&parentid=<?php echo $v['areaid'];?>"><img src="admin/image/add.png" width="16" height="16" title="添加子地区" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&parentid=<?php echo $v['areaid'];?>"><img src="admin/image/child.png" width="16" height="16" title="管理子地区，当前有<?php echo $v['childs'];?>个子地区" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&areaid=<?php echo $v['areaid'];?>&parentid=<?php echo $parentid;?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<span class="f_r">
地区总数:<strong class="f_red"><?php echo count($AREA);?></strong>&nbsp;&nbsp;
当前目录:<strong class="f_blue"><?php echo count($DAREA);?></strong>&nbsp;&nbsp;
</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="submit" value="更新地区" class="btn-g" onclick="this.form.action='?file=<?php echo $file;?>&parentid=<?php echo $parentid;?>&action=update'"/>&nbsp;&nbsp;
<input type="submit" value="删除选中" class="btn-r" onclick="if(confirm('确定要删除选中地区吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&parentid=<?php echo $parentid;?>&action=delete'}else{return false;}"/>&nbsp;&nbsp;
</div>
</form>
<form method="post" action="?">
<div class="tt">快捷操作</div>
<table cellspacing="0" class="tb">
<tr align="center">
<td>
<div style="float:left;padding:10px;">
<?php echo ajax_area_select('aid', '地区结构', $parentid, 'size="2" style="width:200px;height:130px;" id="aid"');?></div>
<div style="float:left;padding:10px;">
	<table class="ctb">
	<tr>
	<td><input type="submit" value="管理地区" class="btn" onclick="this.form.action='?file=<?php echo $file;?>&parentid='+Dd('aid').value;"/></td>
	</tr>
	<tr>
	<td><input type="submit" value="添加地区" class="btn" onclick="this.form.action='?file=<?php echo $file;?>&action=add&parentid='+Dd('aid').value;"/></td>
	</tr>
	<tr>
	<td><input type="submit" value="删除地区" class="btn-r" onclick="if(confirm('确定要删除选中地区吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&action=delete&areaid='+Dd('aid').value;}else{return false;}"/></td>
	</tr>
	</table>
</div>
</td>
</tr>
</table>
</div>
</form>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>