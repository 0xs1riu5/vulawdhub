<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="backup" value="1"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);Dcac();"/></th>
<th>表 名</th>
<th>表注释</th>
<th>大小(M)</th>
<th>记录数</th>
<th width="150">操 作</th>
</tr>
<?php foreach($dtables as $k=>$v) {?>
<tr align="center">
<td>
<input type="checkbox" name="tables[]" value="<?php echo $v['name'];?>" onclick="Dcac();" checked/>
<input type="hidden" name="sizes[<?php echo $v['name'];?>]" value="<?php echo $v['tsize'];?>"/>
</td>
<td align="left">&nbsp;<a href="###" onclick="Dict('<?php echo $v['name'];?>','<?php echo $v['note'];?>');"><?php echo $v['name'];?></a></td>
<td><a href="javascript:Dcomment('<?php echo $v['name'];?>', '<?php echo urlencode($v['note']);?>');" title="点击修改表注释"><?php echo $v['note'] ? $v['note'] : '--';?></a></td>
<td title="数据:<?php echo $v['size'];?> 索引:<?php echo $v['index'];?> 碎片:<?php echo $v['chip'];?>"><?php echo $v['tsize'];?></td>
<td><?php echo $v['rows'];?></td>
<td><a href="javascript:Dict('<?php echo $v['name'];?>','<?php echo $v['note'];?>');">字典</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=repair&table=<?php echo $v['name'];?>">修复</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=optimize&table=<?php echo $v['name'];?>">优化</a></td>
</tr>
<?php }?>
<?php if($tables) {?>
<?php foreach($tables as $k=>$v) {?>
<tr align="center">
<td>
<input type="checkbox" name="tables[]" value="<?php echo $v['name'];?>" onclick="Dcac();"/>
<input type="hidden" name="sizes[<?php echo $v['name'];?>]" value="<?php echo $v['tsize'];?>"/>
</td>
<td align="left">&nbsp;<a href="###" onclick="Dict('<?php echo $v['name'];?>','<?php echo $v['note'];?>');"><?php echo $v['name'];?></a></td>
<td><a href="javascript:Dcomment('<?php echo $v['name'];?>', '<?php echo urlencode($v['note']);?>');" title="点击修改表注释"><?php echo $v['note'] ? $v['note'] : '--';?></a></td>
<td title="数据:<?php echo $v['size'];?> 索引:<?php echo $v['index'];?> 碎片:<?php echo $v['chip'];?>"><?php echo $v['tsize'];?></td>
<td><?php echo $v['rows'];?></td>
<td><a href="###" onclick="Dict('<?php echo $v['name'];?>','<?php echo $v['note'];?>');">字典</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=repair&table=<?php echo $v['name'];?>">修复</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=optimize&table=<?php echo $v['name'];?>">优化</a></td>
</tr>
<?php }?>
<?php } ?>
</table>
<div class="tt"><span class="f_r px12">共<span id="dtotal"><?php echo count($dtables);?></span>个表 / <span id="dsize"><?php echo $dtotalsize;?></span>M</span>备份选中</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">分卷文件大小</td>
<td>
<span class="f_r">
<a href="javascript:" onclick="checkall(Dd('dform'), 1);Dcac();" class="t">反选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 2);Dcac();" class="t">全选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 3);Dcac();" class="t">全不选</a>&nbsp;&nbsp;
</span>
<input type="text" name="sizelimit" value="2048" size="5"/> K</td>
</tr>
<tr>
<td class="tl">建表语句格式</td>
<td><input type="radio" name="sqlcompat" value="" checked="checked"/> 默认 &nbsp; <input type="radio" name="sqlcompat" value="MYSQL40"/> MySQL 3.23/4.0.x &nbsp; <input type="radio" name="sqlcompat" value="MYSQL41"/> MySQL 4.1.x/5.x &nbsp;</td>
</tr>
<tr>
<td class="tl">强制字符集</td>
<td><input type="radio" name="sqlcharset" value="" checked/> 默认 &nbsp; <input type="radio" name="sqlcharset" value="utf8"/> UTF-8 &nbsp; <input type="radio" name="sqlcharset" value="gbk"/> GBK &nbsp; <input type="radio" name="sqlcharset" value="latin1"/> LATIN1</td>
</tr>
</table>
<div class="btns">
<input type="submit" name="submit" value="开始备份" class="btn-g"/>&nbsp;
<input type="submit" value="删除表" class="btn-r" onclick="if(confirm('警告！确定要删除中表吗？此操作将不可恢复\n\n为了系统安全，系统仅删除非Destoon系统表')){this.form.action='?file=<?php echo $file;?>&action=drop';}else{return false;}"/>&nbsp;
<input type="submit" value="重建注释" class="btn" onclick="if(confirm('确定要重建表注释吗？')){this.form.action='?file=<?php echo $file;?>&action=comments';}else{return false;}"/>&nbsp;
</div>
</form>
<script type="text/javascript">
function Dict(t, n) {
	Dwidget('?file=tag&action=dict&table='+t+'&note='+n, '数据字典 - '+t+' - '+n);
}
function Dcomment(t, n) {
	Dwidget('?file=<?php echo $file;?>&action=comment&table='+t+'&note='+n, '修改注释');
}
function Dcac() {
	var s = 0;
	var t = 0;
	$(':checkbox').each(function() {
		if($(this).attr('checked') && $(this).attr('name')) {
			s += parseFloat($(this).parent().siblings('td:eq(2)').html());
			t++;
		}
	});
	$('#dtotal').html(t);
	$('#dsize').html(s.toFixed(2));
}
Menuon(0);
</script>
<?php include tpl('footer');?>