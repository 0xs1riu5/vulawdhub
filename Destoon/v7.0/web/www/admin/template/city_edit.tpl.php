<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 所在地区</td>
<td class="tr"><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?> <span id="dareaid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 分站名称</td>
<td><input name="post[name]" type="text" id="name" size="20" value="<?php echo $name;?>"/> <?php echo dstyle('post[style]', $style);?> <span id="dname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 绑定域名</td>
<td><input name="post[domain]" type="text" size="40" value="<?php echo $domain;?>"/><?php tips('例如http://xian.destoon.com/,以 / 结尾<br/>同时在服务器端绑定此域名至网站根目录，如果不绑定请勿填写');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> IP地址名称</td>
<td><input name="post[iparea]" type="text" size="60" value="<?php echo $iparea;?>"/><?php tips('一般为常见城市名称，多个地名用|分隔。例如开通的是广东分站，可以填写广州|深圳|佛山等，系统将根据这些名称按IP地址自动跳转分站');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 字母索引</td>
<td><input name="post[letter]" type="text" id="letter" size="4" value="<?php echo $letter;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 排序</td>
<td><input name="post[listorder]" type="text" id="listorder" size="4" value="<?php echo $listorder;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 分站首页模板</td>
<td><?php echo tpl_select('index', 'city', 'post[template]', '默认模板', $template);?><?php tips('请在模板目录city目录里建立index-xxx.htm规则的模板，然后在此选择。模板内容请参考网站首页模板。如果不选择，系统默认使用网站首页模板。');?></td>
</tr>

<tr>
<td class="tl"><span class="f_hid">*</span> Title(SEO标题)</td>
<td><input name="post[seo_title]" type="text" id="seo_title" value="<?php echo $seo_title;?>" size="61"></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> Meta Keywords<br/>&nbsp;&nbsp;(网页关键词)</td>
<td><textarea name="post[seo_keywords]" cols="60" rows="3" id="seo_keywords"><?php echo $seo_keywords;?></textarea></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> Meta Description<br/>&nbsp;&nbsp;(网页描述)</td>
<td><textarea name="post[seo_description]" cols="60" rows="3" id="seo_description"><?php echo $seo_description;?></textarea></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $areaid ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $areaid ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<script type="text/javascript">
function check() {
	if(Dd('areaid_1').value == 0) {
		Dmsg('请选择所在地区', 'areaid', 1);
		return false;
	}
	if(Dd('name').value == '') {
		Dmsg('请填写分站名称', 'name');
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $areaid ? 1 : 0;?>);</script>
<?php include tpl('footer');?>