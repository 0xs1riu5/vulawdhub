<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="table" value="<?php echo $table;?>"/>
<input type="hidden" name="submit" value="1"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 表名称</td>
<td class="f_b">&nbsp;<?php echo $table;?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 新注释</td>
<td>&nbsp;<input type="text" name="name" value="<?php echo $note;?>" size="10"/></td>
</tr>
</table>
<div class="sbt"><input type="submit" value="修 改" class="btn-g"/></div>
</form>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>