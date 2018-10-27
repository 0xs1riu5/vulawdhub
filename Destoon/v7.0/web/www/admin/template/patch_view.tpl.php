<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="tt">文件列表</div>
<table cellspacing="0" class="tb ls">
<tr>
<th>文件</th>
<th width="150">大小</th>
<th width="150">修改时间</th>
</tr>
<?php foreach($lists as $v) { ?>
<tr align="center">
<td align="left" class="f_fd">&nbsp;<?php echo str_replace(DT_ROOT.'/file/patch/'.$fid.'/', '', $v);?></td>
<td class="px12"><?php echo dround(filesize($v)/1024);?> Kb</td>
<td class="px12"><?php echo timetodate(filemtime($v), 6);?></td>
</tr>
<?php } ?>
</table>
<?php include tpl('footer');?>