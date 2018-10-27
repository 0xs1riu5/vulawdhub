<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>文件</th>
<th>文件大小(Kb)</th>
<th>记录数</th>
<th width="150">获取时间</th>
<th width="50">操作</th>
</tr>
<?php foreach($mails as $k=>$v) {?>
<tr align="center">
<td align="left">&nbsp;&nbsp;<a href="<?php DT_PATH;?>file/mobile/<?php echo $v['filename'];?>" title="点鼠标右键另存为保存此文件" target="_blank"><?php echo $v['filename'];?></a></td>
<td><?php echo $v['filesize'];?></td>
<td><?php echo $v['count'];?></td>
<td><?php echo $v['mtime'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=download&filename=<?php echo $v['filename'];?>"><img src="admin/image/save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&filenames=<?php echo $v['filename'];?>" onclick="return _delete();"><img src="admin/image/delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="sbox">
<form method="post" action="?" enctype="multipart/form-data">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="upload"/>
<input name="uploadfile" type="file"/>
<input type="submit" name="submit" value="上 传" class="btn-g"/>&nbsp;
</form>
</div>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>