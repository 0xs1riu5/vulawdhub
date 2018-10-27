<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>文件名称</th>
<th width="150">文件大小(M)</th>
<th width="200">修改时间</th>
<th width="100">分卷</th>
<th width="100">操作</th>
</tr>
<?php
for($i = 1; $i <= $tid; $i++) {
	$v = $sqls[$i];
?>
<tr align="center">
<td align="left">&nbsp;<img src="admin/image/sql.gif" width="16" height="16" alt="" align="absmiddle"/> <a href="<?php DT_PATH;?>file/backup/<?php echo $dir;?>/<?php echo $v['filename'];?>" title="点鼠标右键另存为保存此文件" target="_blank"><?php echo $v['filename'];?></a></td>
<td><?php echo $v['filesize'];?></td>
<td title="备份时间:<?php echo $v['btime'];?>"><?php echo $v['mtime'];?></td>
<td><?php echo $v['number'];?></td>
<td>
<a href="?file=<?php echo $file;?>&action=import&filepre=<?php echo $v['pre'];?>&tid=<?php echo $tid;?>&import=1" onclick="return confirm('确定要导入此系列文件吗？现有数据将被覆盖，此操作将不可恢复');">导入</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=download&dir=<?php echo $dir;?>&filename=<?php echo $v['filename'];?>">下载</a></td>
</tr>
<?php }?>
</table>
<?php include tpl('footer');?>