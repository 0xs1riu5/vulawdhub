<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<script type="text/javascript">var errimg = '<?php echo DT_SKIN;?>image/nopic50.gif';</script>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
卖家：<input type="text" name="seller" value="<?php echo $seller;?>" size="10"/>&nbsp;
买家：<input type="text" name="buyer" value="<?php echo $buyer;?>" size="10"/>&nbsp;
商品ID：<input type="text" name="itemid" value="<?php echo $itemid;?>" size="10"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>');"/>
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="60">缩略图</th>
<th>商品</th>
<th>卖家</th>
<th>买家</th>
<th width="130">访问时间</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="uids[]" value="<?php echo $v['uid'];?>"/></td>
<td><a href="<?php echo $v['linkurl'];?>" target="_blank"><img src="<?php if($v['thumb']) { ?><?php echo $v['thumb'];?><?php } else { ?><?php echo DT_SKIN;?>image/nopic50.gif<?php } ?>" width="50" height="50" onerror="this.src=errimg;" style="padding:5px;"/></a></td>
<td align="left" class="f_gray">&nbsp;
<a href="<?php echo $v['linkurl'];?>" target="_blank" class="t px14"><?php echo $v['title'];?></a>
</td>
<td class="px12"><a href="javascript:_user('<?php echo $v['username'];?>');"><?php echo $v['username'];?></a></td>
<td class="px12"><a href="javascript:_user('<?php echo $v['buyer'];?>');"><?php echo $v['buyer'];?></a></td>
<td class="px12"><?php echo timetodate($v['lasttime'], 5);?></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="删除记录" class="btn-r" onclick="if(confirm('确定要删除选中记录吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>