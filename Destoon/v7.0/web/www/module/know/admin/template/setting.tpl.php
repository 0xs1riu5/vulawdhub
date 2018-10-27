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
<td class="tl">回答默认模板</td>
<td><?php echo tpl_select('answer', $module, 'setting[template_answer]', '默认模板', $template_answer);?></td>
</tr>
<tr>
<td class="tl">专家默认模板</td>
<td><?php echo tpl_select('expert', $module, 'setting[template_expert]', '默认模板', $template_expert);?></td>
</tr>
<tr>
<td class="tl">常见问题模板</td>
<td><?php echo tpl_select('faq', $module, 'setting[template_faq]', '默认模板', $template_faq);?></td>
</tr>
<tr>
<td class="tl">信息发布模板</td>
<td><?php echo tpl_select('my_'.$module, 'member', 'setting[template_my]', '默认模板', $template_my);?></td>
</tr>
<tr>
<td class="tl">回答管理模板</td>
<td><?php echo tpl_select('my_know_answer', 'member', 'setting[template_my_answer]', '默认模板', $template_my_answer);?></td>
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
<td class="tl">清除答案链接</td>
<td>
<input type="radio" name="setting[clear_alink]" value="1"  <?php if($clear_alink) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[clear_alink]" value="0"  <?php if(!$clear_alink) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">内容关联链接</td>
<td>
<input type="radio" name="setting[keylink]" value="1"  <?php if($keylink) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[keylink]" value="0"  <?php if(!$keylink) echo 'checked';?>/> 关闭
&nbsp;&nbsp;
<a href="javascript:Dwidget('?file=keylink&item=<?php echo $moduleid;?>', '[<?php echo $MOD['name'];?>]关联链接管理');" class="t">[管理链接]</a>
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
<td class="tl">悬赏<?php echo $DT['credit_name'];?>备选</td>
<td><input type="text" size="60" name="setting[credits]" value="<?php echo $credits;?>"/></td>
</tr>
<tr>
<td class="tl">允许多次回答</td>
<td>
<input type="radio" name="setting[answer_repeat]" value="1"  <?php if($answer_repeat) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[answer_repeat]" value="0"  <?php if(!$answer_repeat) echo 'checked';?>/> 关闭 <?php tips('如果关闭，同一个会员或者IP只能回答一次');?>
</td>
</tr>
<tr>
<td class="tl">有回复时发消息给提问者</td>
<td>
<input type="radio" name="setting[answer_message]" value="1"  <?php if($answer_message) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[answer_message]" value="0"  <?php if(!$answer_message) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">问题默认过期时间</td>
<td><input type="text" size="3" name="setting[overdays]" value="<?php echo $overdays;?>"/> 天</td>
</tr>
<tr>
<td class="tl">投票有效期</td>
<td><input type="text" size="3" name="setting[votedays]" value="<?php echo $votedays;?>"/> 天</td>
</tr>
<tr>
<td class="tl">投票问题最佳答案至少</td>
<td><input type="text" size="3" name="setting[minvote]" value="<?php echo $minvote;?>"/> 票</td>
</tr>
<tr>
<td class="tl">追加悬赏次数限制</td>
<td><input type="text" size="3" name="setting[maxraise]" value="<?php echo $maxraise;?>"/> 次</td>
</tr>
<tr>
<td class="tl">追加一次悬赏延长</td>
<td><input type="text" size="3" name="setting[raisedays]" value="<?php echo $raisedays;?>"/> 天</td>
</tr>
<tr>
<td class="tl">追加悬赏</td>
<td><input type="text" size="3" name="setting[raisecredit]" value="<?php echo $raisecredit;?>"/> 分 以上，问题将等同于新提出的问题</td>
</tr>
<tr>
<td class="tl">高分问题最少</td>
<td><input type="text" size="3" name="setting[highcredit]" value="<?php echo $highcredit;?>"/> 分</td>
</tr>
<tr>
<td class="tl">问题过期前</td>
<td><input type="text" size="3" name="setting[messagedays]" value="<?php echo $messagedays;?>"/> 天 发通知给提问人</td>
</tr>

<tr>
<td class="tl">首页精彩推荐数量</td>
<td><input type="text" size="3" name="setting[page_irec]" value="<?php echo $page_irec;?>"/></td>
</tr>

<tr>
<td class="tl">首页待解决的问题</td>
<td><input type="text" size="3" name="setting[page_isolve]" value="<?php echo $page_isolve;?>"/></td>
</tr>

<tr>
<td class="tl">首页投票中的问题</td>
<td><input type="text" size="3" name="setting[page_ivote]" value="<?php echo $page_ivote;?>"/></td>
</tr>

<tr>
<td class="tl">首页已解决的问题</td>
<td><input type="text" size="3" name="setting[page_iresolve]" value="<?php echo $page_iresolve;?>"/></td>
</tr>


<tr>
<td class="tl">首页知道专家</td>
<td><input type="text" size="3" name="setting[page_iexpert]" value="<?php echo $page_iexpert;?>"/></td>
</tr>

<tr>
<td class="tl">列表信息分页数量</td>
<td><input type="text" size="3" name="setting[pagesize]" value="<?php echo $pagesize;?>"/></td>
</tr>

<tr>
<td class="tl">内容每页显示答案</td>
<td><input type="text" size="3" name="setting[answer_pagesize]" value="<?php echo $answer_pagesize;?>"/></td>
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
<td class="tl">允许浏览模块首页</td>
<td><?php echo group_checkbox('setting[group_index][]', $group_index);?></td>
</tr>
<tr>
<td class="tl">允许浏览分类列表</td>
<td><?php echo group_checkbox('setting[group_list][]', $group_list);?></td>
</tr>
<tr>
<td class="tl">允许查看最佳答案</td>
<td><?php echo group_checkbox('setting[group_show][]', $group_show);?></td>
</tr>
<tr>
<td class="tl">允许搜索信息</td>
<td><?php echo group_checkbox('setting[group_search][]', $group_search);?></td>
</tr>
<tr>
<td class="tl">允许设置标题颜色</td>
<td><?php echo group_checkbox('setting[group_color][]', $group_color);?></td>
</tr>
<tr>
<td class="tl">审核发布问题</td>
<td>
<input type="radio" name="setting[check_add]" value="2"  <?php if($check_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_add]" value="1"  <?php if($check_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_add]" value="0"  <?php if($check_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布问题启用验证码</td>
<td>
<input type="radio" name="setting[captcha_add]" value="2"  <?php if($captcha_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_add]" value="1"  <?php if($captcha_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_add]" value="0"  <?php if($captcha_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布问题验证问题</td>
<td>
<input type="radio" name="setting[question_add]" value="2"  <?php if($question_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_add]" value="1"  <?php if($question_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_add]" value="0"  <?php if($question_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">允许回答</td>
<td><?php echo group_checkbox('setting[group_answer][]', $group_answer);?></td>
</tr>
<tr>
<td class="tl">允许投票</td>
<td><?php echo group_checkbox('setting[group_vote][]', $group_vote);?></td>
</tr>
<tr>
<td class="tl">审核发布答案</td>
<td>
<input type="radio" name="setting[check_answer]" value="2"  <?php if($check_answer == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_answer]" value="1"  <?php if($check_answer == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_answer]" value="0"  <?php if($check_answer == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布答案启用验证码</td>
<td>
<input type="radio" name="setting[captcha_answer]" value="2"  <?php if($captcha_answer == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_answer]" value="1"  <?php if($captcha_answer == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_answer]" value="0"  <?php if($captcha_answer == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布答案验证问题</td>
<td>
<input type="radio" name="setting[question_answer]" value="2"  <?php if($question_answer == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_answer]" value="1"  <?php if($question_answer == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_answer]" value="0"  <?php if($question_answer == 0) echo 'checked';?>> 全部关闭
</td>
</tr>

<tr>
<td class="tl">会员是否收费</td>
<td>
<input type="radio" name="setting[fee_mode]" value="1"  <?php if($fee_mode == 1) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[fee_mode]" value="0"  <?php if($fee_mode == 0) echo 'checked';?>> 全部启用
</td>
</tr>
<tr>
<td class="tl">会员收费使用</td>
<td>
<input type="radio" name="setting[fee_currency]" value="money"  <?php if($fee_currency == 'money') echo 'checked';?>/> <?php echo $DT['money_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[fee_currency]" value="credit"  <?php if($fee_currency == 'credit') echo 'checked';?>/> <?php echo $DT['credit_name'];?>
</td>
</tr>
<tr>
<td class="tl">发布信息收费</td>
<td><input type="text" size="5" name="setting[fee_add]" value="<?php echo $fee_add;?>"/> <?php echo $fee_currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];?>/条</td>
</tr>
<tr>
<td class="tl">查看信息收费</td>
<td><input type="text" size="5" name="setting[fee_view]" value="<?php echo $fee_view;?>"/> <?php echo $fee_currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];?>/条</td>
</tr>
<tr>
<td class="tl">收费有效时间</td>
<td><input type="text" size="5" name="setting[fee_period]" value="<?php echo $fee_period;?>"/> 分钟 <?php tips('如果支付时间超过有效时间，系统将重新收费<br/>填零表示不重复收费');?></td>
</tr>
<tr>
<td class="tl">向发布人返利</td>
<td><input type="text" size="2" name="setting[fee_back]" value="<?php echo $fee_back;?>"/> % <?php tips('请填写1-100之间的数字，用户支付之后，系统将按此比例向最佳答案发布人增加对应的'.$DT['money_name'].'或者'.$DT['credit_name']);?></td>
</tr>
<tr>
<td class="tl">向发布人打赏</td>
<td><input type="text" size="2" name="setting[fee_award]" value="<?php echo $fee_award;?>"/> % <?php tips('请填写1-100之间的数字，用户打赏之后，系统将按此比例向最佳答案发布人增加对应的赏金，填0代表关闭打赏');?></td>
</tr>
<tr>
<td class="tl">未支付内容显示</td>
<td><input type="text" size="5" name="setting[pre_view]" value="<?php echo $pre_view;?>"/> 字符</td>
</tr>
</table>
<div class="tt"><?php echo $DT['credit_name'];?>规则</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">发布问题奖励</td>
<td><input type="text" size="5" name="setting[credit_add]" value="<?php echo $credit_add;?>"/></td>
</tr>
<tr>
<td class="tl">提问被删除扣除</td>
<td><input type="text" size="5" name="setting[credit_del]" value="<?php echo $credit_del;?>"/></td>
</tr>
<tr>
<td class="tl">提问设置颜色扣除</td>
<td>
<input type="text" size="5" name="setting[credit_color]" value="<?php echo $credit_color;?>"/>
</td>
</tr>
<tr>
<td class="tl">设定匿名提问扣除</td>
<td><input type="text" size="5" name="setting[credit_hidden]" value="<?php echo $credit_hidden;?>"/></td>
</tr>
<tr>
<td class="tl">答案被设置为最佳奖励</td>
<td><input type="text" size="5" name="setting[credit_best]" value="<?php echo $credit_best;?>"/></td>
</tr>
<tr>
<td class="tl">回答问题奖励</td>
<td><input type="text" size="5" name="setting[credit_answer]" value="<?php echo $credit_answer;?>"/></td>
</tr>
<tr>
<td class="tl">回答问题24小时奖励上限</td>
<td><input type="text" size="5" name="setting[credit_maxanswer]" value="<?php echo $credit_maxanswer;?>"/></td>
</tr>
<tr>
<td class="tl">参与投票奖励</td>
<td><input type="text" size="5" name="setting[credit_vote]" value="<?php echo $credit_vote;?>"/></td>
</tr>
<tr>
<td class="tl">参与投票24小时奖励上限</td>
<td><input type="text" size="5" name="setting[credit_maxvote]" value="<?php echo $credit_maxvote;?>"/></td>
</tr>
<tr>
<td class="tl">回复被删除扣除</td>
<td><input type="text" size="5" name="setting[credit_del_answer]" value="<?php echo $credit_del_answer;?>"/></td>
</tr>
<tr>
<td class="tl">问题未处理扣除</td>
<td><input type="text" size="5" name="setting[credit_deal]" value="<?php echo $credit_deal;?>"/></td>
</tr>
</table>
<div class="tt">发布数量</div>
<table cellspacing="0" class="tb">
<tr align="center">
<td width="158">会员组</td>
<td width="100">总数限制</td>
<td width="100">免费数量</td>
<td width="100">每日回复</td>
<td align="right"><a href="<?php echo DT_PATH;?>api/redirect.php?url=https://www.destoon.com/doc/skill/94.html" target="_blank" class="t">设置说明</a></td>
</tr>
<?php foreach($GROUP as $v) {?>
<tr align="center">
<td><?php echo $v['groupname'];?></td>
<?php $k = 'limit_'.$v['groupid'];?>
<td><input type="text" name="setting[<?php echo $k;?>]" size="5" value="<?php echo $$k;?>"/></td>
<?php $k = 'free_limit_'.$v['groupid'];?>
<td><input type="text" name="setting[<?php echo $k;?>]" size="5" value="<?php echo $$k;?>"/></td>
<?php $k = 'answer_limit_'.$v['groupid'];?>
<td><input type="text" name="setting[<?php echo $k;?>]" size="5" value="<?php echo $$k;?>"/></td>
<td></td>
</tr>
<?php }?>
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