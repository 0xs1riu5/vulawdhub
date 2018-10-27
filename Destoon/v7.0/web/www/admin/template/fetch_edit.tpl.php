<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 采编域名</td>
<td><input name="domain" type="text" id="domain" size="50" value="<?php echo $domain;?>"/>
<span id="ddomain" class="f_red"></span><br/>
不带http及目录，例如 bbs.destoon.com
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 网站名称</td>
<td><input name="sitename" type="text" id="sitename" size="50" value="<?php echo $sitename;?>"/><span id="dsitename" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 标题过滤</td>
<td><input name="title" type="text" id="title" size="50" value="<?php echo $title;?>"/><span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 内容规则</td>
<td><textarea name="content" rows="6" cols="50" id="content"><?php echo $content;?></textarea>
<br/><span id="dcontent" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 内容编码</td>
<td>
<input type="radio" name="encode" value="utf-8"<?php echo $encode == 'utf-8' ? ' checked' : '';?>/> UTF-8&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="encode" value="gbk"<?php echo $encode == 'gbk' ? ' checked' : '';?>/> GBK
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $itemid ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $itemid ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	if(Dd('domain').value.length < 5) {
		Dmsg('请填写域名', 'domain');
		return false;
	}
	if(Dd('content').value.length < 10) {
		Dmsg('请填写内容规则', 'content');
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $itemid ? 1 : 0;?>);</script>
<?php include tpl('footer');?>