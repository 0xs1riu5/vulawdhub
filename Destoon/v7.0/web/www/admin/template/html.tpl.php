<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="btns">
<input type="button" value="生成首页" class="btn-g" onclick="Go('?file=<?php echo $file;?>&action=homepage');"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="更新缓存" class="btn" onclick="Go('?file=<?php echo $file;?>&action=caches');"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="更新模板" class="btn" onclick="Go('?file=<?php echo $file;?>&action=template');"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="更新扩展" class="btn" onclick="Go('?moduleid=3&file=<?php echo $file;?>');"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="更新全站" class="btn" onclick="Go('?file=<?php echo $file;?>&action=start');"/>
</div>
<table cellspacing="0" class="tb ls">
<tr>
<th width="120">模块</th>
<th width="100">生成首页</th>
<th width="100">生成列表</th>
<th width="100">生成内容</th>
<th width="100">更新信息</th>
<th width="100">一键更新</th>
<th width="100">更新数据</th>
<th></th>
</tr>
<?php
foreach($MODULE as $k=>$v) {
	if($v['islink'] || $v['moduleid'] < 4) continue;
?>
<tr align="center">
<td><a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['name'];?></a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=html&action=index', '更新数据 - <?php echo $v['name'];?>');">生成首页</a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=html&action=list', '更新数据 - <?php echo $v['name'];?>');">生成列表</a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=html&action=show', '更新数据 - <?php echo $v['name'];?>');">生成内容</a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=html&action=show&update=1', '更新数据 - <?php echo $v['name'];?>');">更新信息</a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=html&action=all', '更新数据 - <?php echo $v['name'];?>');">一键更新</a></td>
<td><a href="javascript:Dwidget('?moduleid=<?php echo $v['moduleid'];?>&file=html', '更新数据 - <?php echo $v['name'];?>');" class="t">更新数据</a></td>
<td></td>
</tr>
<?php } ?>
</table>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>