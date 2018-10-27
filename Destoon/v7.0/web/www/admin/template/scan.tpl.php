<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<?php if($submit) { ?>
<table cellspacing="0" class="tb">
<?php if($lists) { ?>
<tr>
<th>文件</th>
<th width="300">特征码</th>
<th width="100">匹配次数</th>
<th width="100">大小</th>
<th width="150">修改时间</th>
</tr>
	<?php foreach($lists as $v) { ?>
	<tr align="center">
	<td align="left" class="f_fd">&nbsp;<?php echo $v['file'];?></td>
	<td><input type="text" size="30" value="<?php echo $v['code'];?>" class="f_fd f_red"/></td>
	<td class="px12<?php echo $v['num'] > 2 ? ' f_red' : '';?>"><?php echo $v['num'];?></td>
	<td class="px12"><?php echo dround(filesize(DT_ROOT.'/'.$v['file'])/1024);?> Kb</td>
	<td class="px12"><?php echo timetodate(filemtime(DT_ROOT.'/'.$v['file']), 6);?></td>
	</tr>
	<?php } ?>
	<tr>
	<td colspan="5" height="30" class="f_blue">&nbsp; - 共发现<strong><?php echo $find;?></strong>个可疑文件，请下载手动检查文件内容是否安全&nbsp;&nbsp;&nbsp;&nbsp;<a href="?file=<?php echo $file;?>" class="t">[重新扫描]</a></td>
	</tr>
<?php } else { ?>
<tr>
<td class="f_green" height="40">&nbsp; - 指定范围没有扫描到可疑文件&nbsp;&nbsp;&nbsp;&nbsp;<a href="?file=<?php echo $file;?>" class="t">[重新扫描]</a></td>
</tr>
<?php } ?>
</table>

<?php } else { ?>
<form method="post" action="?" id="dform">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">选择目录</td>
<td>
<table cellspacing="2" width="600" class="ctb">
<?php foreach($dirs as $k=>$d) { ?>
<?php if($k%4==0) {?><tr><?php } ?>
<td width="150"><input type="checkbox" name="filedir[]" value="<?php echo $d;?>" checked id="dir_<?php echo $d;?>"/><label for="dir_<?php echo $d;?>">&nbsp;<img src="admin/image/folder.gif" width="16" height="14" alt="" align="absmiddle"/> <?php echo $d;?></label></td>
<?php if($k%4==3) {?></tr><?php } ?>
<?php } ?>
</table>
<div>&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 1);" class="t">反选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 2);" class="t">全选</a>&nbsp;&nbsp;
<a href="javascript:" onclick="checkall(Dd('dform'), 3);" class="t">全不选</a>&nbsp;&nbsp;
</div>
</td>
</tr>
<tr>
<td class="tl">文件类型</td>
<td>&nbsp;<input type="text" size="60" name="fileext" value="<?php echo $fileext;?>" class="f_fd"/></td>
</tr>
<tr>
<td class="tl">文件编码</td>
<td>
<input type="radio" name="charset" value="utf-8" checked/> UTF-8&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="charset" value="gbk"/> GBK
</td>
</tr>
<tr>
<td class="tl">特征代码</td>
<td>&nbsp;<textarea name="code" id="code" style="width:600px;height:50px;overflow:visible;font-family:Fixedsys,verdana;"><?php echo $code;?></textarea></td>
</tr>
<tr>
<td class="tl">匹配次数</td>
<td>&nbsp;<input type="text" size="1" name="codenum" value="1" class="f_fd"/> 次以上</td>
</tr>
<tr>
<td></td>
<td height="30">&nbsp;<input type="submit" name="submit" value="开始扫描" class="btn-g" onclick="this.value='扫描中..';this.blur();this.className='btn f_gray';"/>
</td>
</tr>
</table>
</form>
<?php } ?>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>