<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<table cellspacing="0" class="tb">
<tr>
<td height="30">&nbsp;
<input type="submit" value="一键更新" class="btn-g" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=all';" title="生成该模块所有网页"/>&nbsp;&nbsp;
<input type="submit" value="生成首页" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=index';" title="生成该模块首页"/>&nbsp;&nbsp;
<input type="submit" value="生成列表" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list';" title="生成该模块所有分类"/>&nbsp;&nbsp;
<input type="submit" value="生成内容" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show';" title="生成该模块所有内容页"/>&nbsp;&nbsp;
<input type="submit" value="更新信息" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&update=1';" title="更新该模块所有信息地址等项目"/>
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th>起始ID</th>
<th>结束ID</th>
<th width="200">每轮生成数量</th>
<th width="200">操作</th>
</tr>
<tr align="center">
<td><input type="text" size="6" name="fid" value="<?php echo $fid;?>"/></td>
<td><input type="text" size="6" name="tid" value="<?php echo $tid;?>"/></td>
<td><input type="text" size="5" name="num" value="100"/></td>
<td><input type="submit" value="生成内容" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show';"/>&nbsp;
<input type="submit" value="更新信息" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&update=1';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<table cellspacing="0" class="tb ls">
<tr>
<th>选择分类</th>
<th width="200">每轮生成数量</th>
<th width="200">操作</th>
</tr>
<tr align="center">
<td>
<?php echo category_select('catid', '选择分类', 0, $moduleid);?>
&nbsp;&nbsp;&nbsp;&nbsp;
第 <input type="text" size="3" name="fpage" value="1"/> 页 至 <input type="text" size="3" name="tpage" value=""/> 页
</td>
<td><input type="text" size="5" name="num" value="100"/></td>
<td>
<input type="submit" value="生成列表" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=cate';"/>&nbsp;
<input type="submit" value="生成内容" class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=item';"/>
</td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>