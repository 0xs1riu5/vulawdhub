<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
$menus = array (
    array('基本设置'),
    array('SEO优化'),
    array('权限收费'),
    array('定义字段', 'javascript:Dwidget(\'?file=fields&tb='.$table.'\', \'['.$MOD['name'].']定义字段\');'),
);
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
<div id="Tabs0" style="display:">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">首页默认模板</td>
<td><?php echo tpl_select('index', $module, 'setting[template_index]', '默认模板', $template_index);?></td>
</tr>
<tr>
<td class="tl">列表默认模板</td>
<td><?php echo tpl_select('list', $module, 'setting[template_list]', '默认模板', $template_list);?></td>
</tr>
<tr>
<td class="tl">内容默认模板</td>
<td><?php echo tpl_select('show', $module, 'setting[template_show]', '默认模板', $template_show);?></td>
</tr>
<tr>
<td class="tl">搜索默认模板</td>
<td><?php echo tpl_select('search', $module, 'setting[template_search]', '默认模板', $template_search);?></td>
</tr>
<tr>
<td class="tl">类别默认模板</td>
<td><?php echo tpl_select('type', $module, 'setting[template_type]', '默认模板', $template_type);?></td>
</tr>
<tr>
<td class="tl">默认缩略图[宽X高]</td>
<td>
<input type="text" size="3" name="setting[thumb_width]" value="<?php echo $thumb_width;?>"/>
X
<input type="text" size="3" name="setting[thumb_height]" value="<?php echo $thumb_height;?>"/> px
</td>
</tr>
<tr>
<td class="tl">默认横幅[宽X高]</td>
<td>
<input type="text" size="3" name="setting[banner_width]" value="<?php echo $banner_width;?>"/>
X
<input type="text" size="3" name="setting[banner_height]" value="<?php echo $banner_height;?>"/> px
</td>
</tr>

<tr>
<td class="tl">自动截取内容至简介</td>
<td><input type="text" size="3" name="setting[introduce_length]" value="<?php echo $introduce_length;?>"/> 字符</td>
</tr>
<tr>
<td class="tl">编辑器工具按钮</td>
<td>
<select name="setting[editor]">
<option value="Default"<?php if($editor == 'Default') echo ' selected';?>>全部</option>
<option value="Destoon"<?php if($editor == 'Destoon') echo ' selected';?>>精简</option>
<option value="Simple"<?php if($editor == 'Simple') echo ' selected';?>>简洁</option>
<option value="Basic"<?php if($editor == 'Basic') echo ' selected';?>>基础</option>
</select>
</td>
</tr>
<tr>
<td class="tl">信息排序方式</td>
<td>
<input type="text" size="50" name="setting[order]" value="<?php echo $order;?>" id="order"/>
<select onchange="if(this.value) Dd('order').value=this.value;">
<option value="">请选择</option>
<option value="addtime desc"<?php if($order == 'addtime desc') echo ' selected';?>>添加时间</option>
<option value="edittime desc"<?php if($order == 'edittime desc') echo ' selected';?>>更新时间</option>
<option value="itemid desc"<?php if($order == 'itemid desc') echo ' selected';?>>信息ID</option>
</select>
</td>
</tr>
<tr>
<td class="tl">列表或搜索主字段</td>
<td><input type="text" size="80" name="setting[fields]" value="<?php echo $fields;?>"/><?php tips('此项可在一定程度上提高列表或搜索效率，请勿随意修改以免导致SQL错误');?></td>
</tr>
<tr>
<td class="tl">分类属性参数</td>
<td>
<input type="radio" name="setting[cat_property]" value="1"  <?php if($cat_property) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[cat_property]" value="0"  <?php if(!$cat_property) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">下载内容远程图片</td>
<td>
<input type="radio" name="setting[save_remotepic]" value="1"  <?php if($save_remotepic) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[save_remotepic]" value="0"  <?php if(!$save_remotepic) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">清除内容链接</td>
<td>
<input type="radio" name="setting[clear_link]" value="1"  <?php if($clear_link) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[clear_link]" value="0"  <?php if(!$clear_link) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">内容分表</td>
<td>
<input type="radio" name="setting[split]" value="1"  <?php if($split) echo 'checked';?> onclick="Ds('split_b');Dh('split_a');confirm('提示:开启之前必须先拆分内容\n\n此设置比较关键，开启后建议不要再关闭');"/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[split]" value="0"  <?php if(!$split) echo 'checked';?> onclick="Ds('split_a');Dh('split_b');confirm('提示:关闭之前必须先合并内容');"/> 关闭
&nbsp;&nbsp;
<span style="display:none;" id="split_a">
<a href="?file=split&mid=<?php echo $moduleid;?>&action=merge" target="_blank" class="t" onclick="return confirm('确定要合并内容吗？合并成功之后请立即关闭内容分表\n\n建议在合并之前备份一次数据库');">[合并内容]</a>
</span>
<span style="display:none;" id="split_b">
<a href="?file=split&mid=<?php echo $moduleid;?>" target="_blank" class="t" onclick="return confirm('确定要拆分内容吗？拆分成功之后请立即开启内容分表\n\n建议在拆分之前备份一次数据库');">[拆分内容]</a>
</span>
&nbsp;<?php tips('如果开启内容分表，内容表将根据id号10万数据创建一个分区<br/>如果你的数据少于10万，则不需要开启，当前最大id为'.$maxid.'，'.($maxid > 100000 ? '建议开启' : '无需开启').'<br/>如果需要开启，请先点拆分内容，然后保存设置<br/>如果需要关闭，请先点合并内容，然后保存设置<br/>此项一旦开启，请不要随意关闭，以免出现未知错误，同时全文搜索将关闭');?>
<input type="hidden" name="maxid" value="<?php echo $maxid;?>"/>
</td>
</tr>
<tr>
<td class="tl">全文搜索</td>
<td>
<input type="radio" name="setting[fulltext]" value="1" <?php if($fulltext==1){ ?>checked <?php } ?>/> LIKE&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[fulltext]" value="2" <?php if($fulltext==2){ ?>checked <?php } ?>/> MATCH&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[fulltext]" value="0" <?php if($fulltext==0){ ?>checked <?php } ?>/> 关闭
<?php tips('此项会增加服务器负担，请根据需要和服务器配置决定是否开启。MATCH模式需要MySQL 4以上版本，且需要在MySQL的my.ini添加ft_min_word_len=1才能支持2个汉字的中文搜索。如果不能设置可以使用LIKE模式，但是效率会低于MATCH模式。<br/>开启MATCH模式请在数据库维护里执行以下SQL添加全文索引<br/>ALTER TABLE `'.$table_data.'` ADD FULLTEXT (`content`);<br/>全文索引占用一定数据空间，如果不开启MATCH模式可以执行以下语句删除索引<br/>ALTER TABLE `'.$table_data.'` DROP INDEX `content`;');?></td>
</tr>
<tr>
<td class="tl">级别中文别名</td>
<td>
<input type="text" name="setting[level]" style="width:98%;" value="<?php echo $level;?>"/>
<br/>用 | 分隔不同别名 依次对应 1|2|3|4|5|6|7|8|9 级 <?php echo level_select('post[level]', '提交后点此预览效果');?>
</td>
</tr>
<tr>
<td class="tl">信息级别中文别名</td>
<td><input type="text" name="setting[level_item]" style="width:98%;" value="<?php echo $level_item;?>"/></td>
</tr>

<tr>
<td class="tl">首页推荐信息数量</td>
<td><input type="text" size="3" name="setting[page_irec]" value="<?php echo $page_irec;?>"/></td>
</tr>

<tr>
<td class="tl">首页分类信息数量</td>
<td><input type="text" size="3" name="setting[page_icat]" value="<?php echo $page_icat;?>"/></td>
</tr>

<tr>
<td class="tl">列表信息分页数量</td>
<td><input type="text" size="3" name="setting[pagesize]" value="<?php echo $pagesize;?>"/></td>
</tr>

<tr>
<td class="tl">内容图片最大宽度</td>
<td><input type="text" size="3" name="setting[max_width]" value="<?php echo $max_width;?>"/> px</td>
</tr>


<tr>
<td class="tl">内容点击次数</td>
<td>
<input type="radio" name="setting[hits]" value="1"  <?php if($hits) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[hits]" value="0"  <?php if(!$hits) echo 'checked';?>/> 关闭
<?php tips('关闭后，有助于缓解频繁更新点击次数对数据表造成的压力');?>
</td>
</tr>

<tr>
<td class="tl">内容页评论列表</td>
<td>
<input type="radio" name="setting[page_comment]" value="1"  <?php if($page_comment==1) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_comment]" value="0"  <?php if($page_comment==0) echo 'checked';?>/> 关闭
</td>
</tr>

</table>
</div>

<div id="Tabs1" style="display:none">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">首页是否生成html</td>
<td>
<input type="radio" name="setting[index_html]" value="1"  <?php if($index_html){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[index_html]" value="0"  <?php if(!$index_html){ ?>checked <?php } ?>/> 否
</td>
</tr>
<tr>
<td class="tl">列表页是否生成html</td>
<td>
<input type="radio" name="setting[list_html]" value="1"  <?php if($list_html){ ?>checked <?php } ?> onclick="Dd('list_html').style.display='';Dd('list_php').style.display='none';"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[list_html]" value="0"  <?php if(!$list_html){ ?>checked <?php } ?> onclick="Dd('list_html').style.display='none';Dd('list_php').style.display='';"/> 否
</td>
</tr>
<tbody id="list_html" style="display:<?php echo $list_html ? '' : 'none'; ?>">
<tr>
<td class="tl">HTML列表页文件名前缀</td>
<td><input name="setting[htm_list_prefix]" type="text" id="htm_list_prefix" value="<?php echo $htm_list_prefix;?>" size="10"></td>
</tr>
<tr>
<td class="tl">HTML列表页地址规则</td>
<td><?php echo url_select('setting[htm_list_urlid]', 'htm', 'list', $htm_list_urlid);?><?php tips('提示:规则列表可在./api/url.inc.php文件里自定义');?></td>
</tr>
</tbody>
<tr id="list_php" style="display:<?php echo $list_html ? 'none' : ''; ?>">
<td class="tl">PHP列表页地址规则</td>
<td><?php echo url_select('setting[php_list_urlid]', 'php', 'list', $php_list_urlid);?></td>
</tr>
<tr>
<td class="tl">内容页是否生成html</td>
<td>
<input type="radio" name="setting[show_html]" value="1"  <?php if($show_html){ ?>checked <?php } ?> onclick="Dd('show_html').style.display='';Dd('show_php').style.display='none';"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[show_html]" value="0"  <?php if(!$show_html){ ?>checked <?php } ?> onclick="Dd('show_html').style.display='none';Dd('show_php').style.display='';"/> 否
</td>
</tr>
<tbody id="show_html" style="display:<?php echo $show_html ? '' : 'none'; ?>">
<tr>
<td class="tl">HTML内容页文件名前缀</td>
<td><input name="setting[htm_item_prefix]" type="text" id="htm_item_prefix" value="<?php echo $htm_item_prefix;?>" size="10"></td>
</tr>
<tr>
<td class="tl">HTML内容页地址规则</td>
<td><?php echo url_select('setting[htm_item_urlid]', 'htm', 'item', $htm_item_urlid);?></td>
</tr>
</tbody>
<tr id="show_php" style="display:<?php echo $show_html ? 'none' : ''; ?>">
<td class="tl">PHP内容页地址规则</td>
<td><?php echo url_select('setting[php_item_urlid]', 'php', 'item', $php_item_urlid);?></td>
</tr>

<tr>
<td class="tl">模块首页Title<br/>(网页标题)</td>
<td><input name="setting[seo_title_index]" type="text" id="seo_title_index" value="<?php echo $seo_title_index;?>" style="width:90%;"/><br/> 
常用变量：<?php echo seo_title('seo_title_index', array('modulename', 'sitename', 'sitetitle', 'page', 'delimiter'));?><br/>
支持页面PHP变量，例如{$MOD[name]}表示模块名称
</td>
</tr>
<tr>
<td class="tl">模块首页Keywords<br/>(网页关键词)</td>
<td><input name="setting[seo_keywords_index]" type="text" id="seo_keywords_index" value="<?php echo $seo_keywords_index;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_keywords_index', array('modulename', 'sitename', 'sitetitle'));?>
</td>
</tr>
<tr>
<td class="tl">模块首页Description<br/>(网页描述)</td>
<td><input name="setting[seo_description_index]" type="text" id="seo_description_index" value="<?php echo $seo_description_index;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_description_index', array('modulename', 'sitename', 'sitetitle'));?>
</td>
</tr>

<tr>
<td class="tl">列表页Title<br/>(网页标题)</td>
<td><input name="setting[seo_title_list]" type="text" id="seo_title_list" value="<?php echo $seo_title_list;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_title_list', array('catname', 'cattitle', 'modulename', 'sitename', 'sitetitle', 'page', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">列表页Keywords<br/>(网页关键词)</td>
<td><input name="setting[seo_keywords_list]" type="text" id="seo_keywords_list" value="<?php echo $seo_keywords_list;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_keywords_list', array('catname', 'catkeywords', 'modulename', 'sitename', 'sitekeywords'));?></td>
</tr>
<tr>
<td class="tl">列表页Description<br/>(网页描述)</td>
<td><input name="setting[seo_description_list]" type="text" id="seo_description_list" value="<?php echo $seo_description_list;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_description_list', array('catname', 'catdescription', 'modulename', 'sitename', 'sitedescription'));?></td>
</tr>

<tr>
<td class="tl">内容页Title<br/>(网页标题)</td>
<td><input name="setting[seo_title_show]" type="text" id="seo_title_show" value="<?php echo $seo_title_show;?>" style="width:90%;"/><br/>
<?php echo seo_title('seo_title_show', array('showtitle', 'catname', 'cattitle', 'modulename', 'sitename', 'sitetitle', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">内容页Keywords<br/>(网页关键词)</td>
<td><input name="setting[seo_keywords_show]" type="text" id="seo_keywords_show" value="<?php echo $seo_keywords_show;?>" style="width:90%;"/><br/>
<?php echo seo_title('seo_keywords_show', array('showtitle', 'catname', 'catkeywords', 'modulename', 'sitename', 'sitekeywords'));?>
</td>
</tr>
<tr>
<td class="tl">内容页Description<br/>(网页描述)</td>
<td><input name="setting[seo_description_show]" type="text" id="seo_description_show" value="<?php echo $seo_description_show;?>" style="width:90%;"/><br/>
<?php echo seo_title('seo_description_show', array('showtitle', 'showintroduce', 'catname', 'catdescription', 'modulename', 'sitename', 'sitedescription'));?>
</td>
</tr>
<tr>
<td class="tl">搜索页Title<br/>(网页标题)</td>
<td><input name="setting[seo_title_search]" type="text" id="seo_title_search" value="<?php echo $seo_title_search;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_title_search', array('kw', 'areaname', 'catname', 'cattitle', 'modulename', 'sitename', 'sitetitle', 'page', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">搜索页Keywords<br/>(网页关键词)</td>
<td><input name="setting[seo_keywords_search]" type="text" id="seo_keywords_search" value="<?php echo $seo_keywords_search;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_keywords_search', array('kw', 'areaname', 'catname', 'catkeywords', 'modulename', 'sitename', 'sitekeywords'));?>
</td>
</tr>
<tr>
<td class="tl">搜索页Description<br/>(网页描述)</td>
<td><input name="setting[seo_description_search]" type="text" id="seo_description_search" value="<?php echo $seo_description_search;?>" style="width:90%;"/><br/> 
<?php echo seo_title('seo_description_search', array('kw', 'areaname', 'catname', 'catdescription', 'modulename', 'sitename', 'sitedescription'));?>
</td>
</tr>
</table>
</div>

<div id="Tabs2" style="display:none">
<table cellspacing="0" class="tb">
<tr>
<td class="tl">允许浏览<?php echo $MOD['name'];?>首页</td>
<td><?php echo group_checkbox('setting[group_index][]', $group_index);?></td>
</tr>
<tr>
<td class="tl">允许浏览分类列表</td>
<td><?php echo group_checkbox('setting[group_list][]', $group_list);?></td>
</tr>
<tr>
<td class="tl">允许浏览<?php echo $MOD['name'];?>内容</td>
<td><?php echo group_checkbox('setting[group_show][]', $group_show);?></td>
</tr>

<tr>
<td class="tl">允许搜索<?php echo $MOD['name'];?></td>
<td><?php echo group_checkbox('setting[group_search][]', $group_search);?></td>
</tr>

<tr>
<td class="tl">向发布人打赏</td>
<td><input type="text" size="2" name="setting[fee_award]" value="<?php echo $fee_award;?>"/> % <?php tips('请填写1-100之间的数字，用户打赏之后，系统将按此比例向发布人增加对应的赏金，填0代表关闭打赏');?></td>
</tr>
</table>
</div>

<div class="sbt">
<input type="submit" name="submit" value="保 存" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="展 开" id="ShowAll" class="btn" onclick="TabAll();" title="展开/合并所有选项"/>
</div>
</form>
<script type="text/javascript">
var tab = <?php echo $tab;?>;
var all = <?php echo $all;?>;
function TabAll() {
	var i = 0;
	while(1) {
		if(Dd('Tabs'+i) == null) break;
		Dd('Tabs'+i).style.display = all ? (i == tab ? '' : 'none') : '';
		i++;
	}
	Dd('ShowAll').value = all ? '展 开' : '合 并';
	all = all ? 0 : 1;
}
$(function(){
	if(tab) Tab(tab);
	if(all) {all = 0; TabAll();}
});
</script>
<?php include tpl('footer');?>