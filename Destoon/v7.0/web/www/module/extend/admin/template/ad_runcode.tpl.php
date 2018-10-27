<?php
defined('DT_ADMIN') or exit('Access Denied');
include template('header');
?>
<div class="m">
<br/>
<table width="800" cellpadding="5" cellspacing="3" align="center">
<tr>
<td bgcolor="#E3EEF5" class="px13 f_b">代码预览</td>
</tr>
<tr>
<td><?php echo $codes;?></td>
</tr>
<tr>
<td bgcolor="#E3EEF5" class="px13"><span class="f_r"><a href="javascript:window.close();">[关闭窗口]</a>&nbsp;</span><strong>源代码</strong></td>
</tr>
<tr>
<td><textarea  style="width:100%;height:200px;font-family:Fixedsys,verdana;overflow:visible;"><?php echo $codes;?></textarea></td>
</tr>
</table>
<br/>
</div>
<?php
include template('footer');
?>