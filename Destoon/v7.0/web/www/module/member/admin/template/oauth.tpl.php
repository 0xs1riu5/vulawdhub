<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<select name="site">
<option value="">平台接口</option>
<?php
foreach($OAUTH as $k=>$v) {
	echo '<option value="'.$k.'" '.($site == $k ? 'selected' : '').'>'.$v['name'].'</option>';
}
?>
</select>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="checkbox" name="thumb" value="1"<?php echo $thumb ? ' checked' : '';?>/> 头像&nbsp;
<input type="checkbox" name="link" value="1"<?php echo $link ? ' checked' : '';?>/> 网址&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn" onclick="Dd('export').value=0;"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th width="20"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="70">头像</th>
<th>昵称</th>
<th>会员名</th>
<th>平台</th>
<th>绑定时间</th>
<th>上次登录</th>
<th>登录次数</th>
</tr>
<?php foreach($members as $k=>$v) {?>
<tr align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><?php if($v['url']) { ?><a href="<?php echo $v['url'];?>" target="_blank" class="t"><?php } ?><img src="<?php echo $v['avatar'];?>" width="50" style="margin:10px 0 10px 0;"/><?php if($v['url']) { ?></a><?php } ?></td>
<td><?php if($v['url']) { ?><a href="<?php echo $v['url'];?>" target="_blank" class="t"><?php } ?><?php echo $v['nickname'];?><?php if($v['url']) { ?></a><?php } ?></td>
<td><a href="javascript:_user('<?php echo $v['username'];?>')"><?php echo $v['username'];?></a></td>
<td title="<?php echo $OAUTH[$v['site']]['name'];?>"><img src="api/oauth/<?php echo $v['site'];?>/ico.png"/></td>
<td class="px12"><?php echo $v['adddate'];?></td>
<td class="px12"><?php echo $v['logindate'];?></td>
<td><?php echo $v['logintimes'];?></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value="解除绑定" class="btn-r" onclick="if(confirm('确定要解除会员绑定吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>&nbsp;
</div>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>