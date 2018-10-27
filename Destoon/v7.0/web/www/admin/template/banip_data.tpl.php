<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb ls">
<tr>
<th>库文件</th>
<th>更新时间</th>
<th>最新版本</th>
<?php if($get) { ?>
<th>在线更新</th>
<?php } else { ?>
<th>手动下载<?php echo tips('提示：无法在线更新，请手动下载wry.rar，解压其中的wry.dat，覆盖上传至file/ipdata/目录');?></th>
<?php } ?>
</tr>
<tr align="center">
<td>file/ipdata/wry.dat</td>
<td><?php echo $now?></td>
<td><?php echo $new;?></td>
<?php if($get) { ?>
<?php if($update) { ?>
<td><a href="?file=<?php echo $file;?>&action=update" class="t" title="文件较大，更新可能用时稍长，请耐心等待">立即更新</a></td>
<?php } else { ?>
<td class="f_gray">暂无更新</td>
<?php } ?>
<?php } else { ?>
<td><a href="?file=<?php echo $file;?>&action=down" class="t">立即下载</a></td>
<?php } ?>
</tr>
</table>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>