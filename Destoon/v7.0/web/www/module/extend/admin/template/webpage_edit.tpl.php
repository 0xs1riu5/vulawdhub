<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 单页标题</td>
<td><input name="post[title]" type="text" id="title" size="50" value="<?php echo $title;?>"/> <?php echo dstyle('post[style]', $style);?>&nbsp; <?php echo level_select('post[level]', '级别', $level);?> &nbsp;<input type="checkbox" name="post[islink]" value="1" id="islink" onclick="_islink();"  <?php if($islink) echo 'checked';?>/> 外部链接 <br/><span id="dtitle" class="f_red"></span></td>
</tr>
<tr id="link" style="display:<?php echo $islink ? '' : 'none';?>;">
<td class="tl"><span class="f_red">*</span> 链接地址</td>
<td><input name="post[linkurl]" type="text" id="linkurl" size="50" value="<?php echo $linkurl;?>"/> <span id="dlinkurl" class="f_red"></span></td>
</tr>
<tbody id="basic" style="display:<?php echo $islink ? 'none' : '';?>;">
<tr>
<td class="tl"><span class="f_hid">*</span> 单页内容</td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', 'Destoon', '100%', 350);?><br/><span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 保存路径</td>
<td><input name="post[filepath]" type="text" size="20" value="<?php echo $filepath;?>"/> <span class="f_gray">如不填写则生成在网站根目录，否则请以‘/’结尾，例如‘about/’</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 文件名称</td>
<td><input name="post[filename]" type="text" size="20" value="<?php echo $filename;?>"/> <span class="f_gray">如不填写则自动按ID生成文件名，例如‘page-1.html’</span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 绑定域名</td>
<td><input name="post[domain]" type="text" size="60" value="<?php echo $domain;?>"/><?php tips('例如设置的生成路径为machine/index.html<br/>那么可以绑定machine.xxx.com至machine目录<br/>此处填写http://machine.xxx.com/');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> SEO标题</td>
<td><input name="post[seo_title]" type="text" size="60" value="<?php echo $seo_title;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> SEO关键词</td>
<td><input name="post[seo_keywords]" type="text" size="60" value="<?php echo $seo_keywords;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> SEO描述</td>
<td><input name="post[seo_description]" type="text" size="60" value="<?php echo $seo_description;?>"/></td>
</tr>
</tbody>
<?php if($DT['city']) { ?>
<tr style="display:<?php echo $_areaids ? 'none' : '';?>;">
<td class="tl"><span class="f_hid">*</span> 地区(分站)</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 分组标识</td>
<td><input name="post[item]" type="text" size="10" value="<?php echo $item;?>"/><?php tips('单页的分组标识，如果不理解含义，请勿修改');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 内容模板</td>
<td><?php echo tpl_select('webpage', $module, 'post[template]', '默认模板', $template);?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'title';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('标题最少2字，当前已输入'+l+'字', f);
		return false;
	}
	if(Dd('islink').checked) {
		f = 'linkurl';
		l = Dd(f).value.length;
		if(l < 12) {
			Dmsg('请输入正确的链接地址', f);
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>