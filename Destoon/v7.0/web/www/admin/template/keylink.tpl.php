<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="item" value="<?php echo $item;?>"/>
<input type="text" size="50" name="kw" placeholder="请输入关键词" value="<?php echo $kw;?>" title="关键词或链接地址"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>&item=<?php echo $item;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="item" value="<?php echo $item;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="60">删除</th>
<th>关键词</th>
<th>链接</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr align="center">
<td><input name="post[<?php echo $v['itemid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][title]" type="text" size="30" value="<?php echo $v['title'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][url]" type="text" size="50" value="<?php echo $v['url'];?>"/></td>
</tr>
<?php } ?>
<tr align="center">
<td class="f_green">新增</td>
<td><input name="post[0][title]" type="text" size="30" value=""/></td>
<td><input name="post[0][url]" type="text" size="50" value="http://"/></td>
</tr>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td height="30" colspan="2"><input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个关联链接，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="导 出" class="btn" onclick="Go('?file=<?php echo $file;?>&item=<?php echo $item;?>&action=export');"/>&nbsp;
&nbsp;&nbsp;&nbsp;
提示：过多的关联链接会影响页面打开或生成速度</td>
</tr>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="item" value="<?php echo $item;?>"/>
<input type="hidden" name="action" value="add"/>
<div class="tt">批量添加</div>
<table cellspacing="0" class="tb">
<tr>
<td width="60" align="center"><span class="f_red">*</span> 内容</td>
<td><textarea name="content" style="width:500px;height:100px;"><?php echo $content;?></textarea><br/>
一行一个，关键词和链接用|分隔，例如：DESTOON|https://www.destoon.com
</td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value="添 加" class="btn"/></td>
</tr>
</table>
</form>
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="item" value="<?php echo $item;?>"/>
<div class="tt">链接导入</div>
<table cellspacing="0" class="tb">
<tr>
<td width="60" align="center"><span class="f_red">*</span> 模块</td>
<td><select name="fid" id="fid">
<option value="">请选择</option>
<?php 
foreach($MODULE as $v) {
	if($v['moduleid'] > 4 && $v['moduleid'] != $item && !$v['islink']) echo '<option value="'.$v['moduleid'].'"'.($fid == $v['moduleid'] ? ' selected' : '').'>'.$v['name'].'</option>';
}
?>
</select>&nbsp;&nbsp;
<a href="javascript:if(Dd('fid').value){Dwidget('?file=<?php echo $file;?>&item='+Dd('fid').value, '查看链接');}else{Dalert('请选择模块');}" class="t">[查看]</a>
</td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value="导 入" class="btn"/></td>
</tr>
</table>
</form>
<br/>
<?php include tpl('footer');?>