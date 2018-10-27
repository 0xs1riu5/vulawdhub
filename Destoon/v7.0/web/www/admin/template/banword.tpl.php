<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?file=<?php echo $file;?>');"/>
</form>
</div>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="40">删除</th>
<th>查找词语</th>
<th>替换为</th>
<th width="120">拦截</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr align="center">
<td><input name="post[<?php echo $v['bid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="post[<?php echo $v['bid'];?>][replacefrom]" type="text" size="40" value="<?php echo $v['replacefrom'];?>"/></td>
<td><input name="post[<?php echo $v['bid'];?>][replaceto]" type="text" size="40" value="<?php echo $v['replaceto'];?>"/></td>
<td>
<input name="post[<?php echo $v['bid'];?>][deny]" type="radio" value="1" <?php if($v['deny']) echo 'checked';?>/> 是&nbsp;&nbsp;
<input name="post[<?php echo $v['bid'];?>][deny]" type="radio" value="0" <?php if(!$v['deny']) echo 'checked';?>/> 否
</td>
</tr>
<?php } ?>
<tr align="center">
<td class="f_green">新增</td>
<td><textarea name="post[0][replacefrom]" rows="10" cols="40"></textarea></td>
<td><textarea name="post[0][replaceto]" rows="10" cols="40"></textarea></td>
<td>
<input name="post[0][deny]" type="radio" value="1"/> 是&nbsp;&nbsp;
<input name="post[0][deny]" type="radio" value="0" checked/> 否
</td>
</tr>
<tr>
<td align="center"><input type="checkbox" onclick="checkall(this.form);" title="全选/反选"/></td>
<td height="30" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="更 新" onclick="if($(':checkbox:checked').length && !confirm('提示：您选择删除'+$(':checkbox:checked').length+'个词语，确定要删除吗？此操作将不可撤销')) return false;" class="btn-g"/></td>
</tr>
<?php if($pages) { ?>
<tr>
<td colspan="4"><?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?></td>
</tr>
<?php } ?>
<tr>
<td></td>
<td colspan="3" class="lh20">
&nbsp;&nbsp;1、批量添加时，查找和替换词语一行一个，互相对应<br/>
&nbsp;&nbsp;2、如果选择拦截，则匹配到查找词语时直接提示，拒绝提交<br/>
&nbsp;&nbsp;3、例如“您*好”格式，可替换“您好”之间的干扰字符<br/>
&nbsp;&nbsp;4、为不影响程序效率，请不要设置过多过滤内容<br/>
&nbsp;&nbsp;5、过滤仅对前台会员提交信息生效，后台不受限制<br/>
</td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>