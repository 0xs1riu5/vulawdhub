<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="12" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="robot">
<option value="">搜索引擎</option>
<?php
foreach($ROBOT as $k=>$v) {
?>
<option value="<?php echo $k;?>" <?php echo $k == $robot ? ' selected' : '';?>><?php echo $v;?></option>
<?php
}
?>
</select>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="25"></th>
<th>网址</th>
<th data-hide="1200">来源</th>
<th>IP</th>
<th data-hide="1200">地区</th>
<th>会员名</th>
<th width="150">时间</th>
<th width="30">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input name="itemid[]" type="checkbox" value="<?php echo $v['itemid'];?>"/></td>
<td>
<?php if($v['robot']) { ?>
<img src="<?php echo DT_PATH;?>file/image/robot_ico_<?php echo $v['robot'];?>.gif" title="<?php echo $ROBOT[$v['robot']];?>"/>
<?php } else { ?>
&nbsp;
<?php } ?>
</td>
<td title="<?php echo $v['url'];?>"><input type="text" size="30" value="<?php echo $v['url'];?>"/> <a href="<?php echo $v['url'];?>" target="_blank"><img src="admin/image/link.gif" width="16" height="16" title="点击打开网址" alt="" align="absmiddle"/></a></td>
<td title="<?php echo $v['refer'];?>" data-hide="1200"><input type="text" size="30" value="<?php echo $v['refer'];?>"/> <a href="<?php echo $v['refer'] ? $v['refer'] : '###';?>"<?php echo $v['refer'] ? ' target="_blank"' : '';?>><img src="admin/image/link.gif" width="16" height="16" title="点击打开网址" alt="" align="absmiddle"/></a></td>
<td><a href="javascript:_ip('<?php echo $v['ip'];?>');"><?php echo $v['ip'];?></a></td>
<td data-hide="1200"><?php echo ip2area($v['ip']);?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td class="px12"><?php echo $v['addtime'];?></td>
<td><a href="?file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="批量删除" class="btn-r" onclick="if(confirm('确定要删除选中日志吗？此操作将不可撤销')){this.form.action='?file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>