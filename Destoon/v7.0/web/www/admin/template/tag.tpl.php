<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 所属模块</td>
<td><input type="text" name="setting[moduleid]" size="20" id="moduleid" value="<?php echo $mid;?>"/>
<select onchange="mod(this.value);">
<option value="">请选择</option>
<?php foreach($MODULE as $k=>$v) {
	if($k > 4 && !$v['islink']) echo '<option value="'.$k.'"'.($k == $mid ? ' selected' : '').'>'.$v['name'].'</option>';
}
?>
<option value="$moduleid" style="background:blue;">变量</option>
</select>
</td>
<td width="100">moduleid</td>
</tr>
<tr id="tr_table" style="display:<?php echo $mid ? 'none' : '';?>">
<td class="tl"><span class="f_hid">*</span> 数据表</td>
<td><input type="text" name="setting[table]" size="20" id="table"/>
<span id="stable"><select onchange="Dd('table').value=this.value;">
<option value="">选择表</option>
<?php echo $table_select;?>
</select></span>
<a href="###" onclick="Dict();" class="t">[数据字典]</a>&nbsp;
<a href="###" onclick="Dd('stable').innerHTML=Dd('alltable').value;void(0);" class="t">[显示所有]</a>
<?php tips('数据表是调用数据的来源<br>系统允许调用同数据库其他表的数据');?>
<textarea style="display:none;" id="alltable">
<select onchange="Dd('table').value=this.value">
<option value="">选择表</option>
<?php echo $all_select;?>
</select>
</textarea>
</td>
<td>table</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 调用条件</td>
<td><input type="text" name="setting[condition]" size="50" value="1" id="condition"/>
<select onchange="Dd('condition').value=this.value">
<option value="">常用调用条件</option>
<option value="1">不限条件</option>
<option value="status=3">已发布的信息</option>
<option value="status=3 and thumb<>''">有图的信息</option>
<option value="status=3 and vip>0"><?php echo VIP;?>信息</option>
</select>
<?php tips('SQL语句的WHERE之后的条件语句，1表示不限条件<br>此项需要对MySQL语法有一定了解');?>
</td>
<td>condition</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 调用数量</td>
<td><input type="text" name="setting[pagesize]" size="10" value="10" id="pagesize"/></td>
<td>pagesize</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 排序方式</td>
<td><input type="text" name="setting[order]" size="30" id="order"/>
<select onchange="Dd('order').value=this.value">
<option value="">常用排序方式</option>
<option value="itemid desc">按信息ID排序</option>
<option value="edittime desc">按修改时间排序</option>
<option value="addtime desc">按添加时间排序</option>
<option value="vip desc">按VIP排序</option>
<option value="hits desc">按浏览次数排序</option>
<option value="rand() desc">按随机排序</option>
</select>
</td>
<td>order</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属分类</td>
<td><input type="text" name="setting[catid]" size="30" id="catid"/>
<?php if($mid) { ?>
<?php echo ajax_category_select('catids', '不限分类', 0, $mid);?>
<a href="javascript:cat();" class="t">&larr;添加</a>
<?php } else { ?>
<span id="scatid"><select onchange="Dd('catid').value=this.value;">
<option value="">不限分类</option>
<option value="$catid">变量</option>
</select></span>
<?php } ?>
</td>
<td>catid</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 包含子分类</td>
<td>
<input type="radio" name="setting[child]" value="1" checked/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[child]" value="0" id="child"/> 否
</td>
<td>child</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属地区</td>
<td><input type="text" name="setting[areaid]" size="30" id="areaid"/>
<?php echo ajax_area_select('areaids', '不限地区');?>
<a href="javascript:are();" class="t">&larr;添加</a>
</td>
<td>areaid</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 包含子地区</td>
<td>
<input type="radio" name="setting[areachild]" value="1" checked/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[areachild]" value="0" id="areachild"/> 否
</td>
<td>areachild</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 缓存时间</td>
<td><input type="text" name="setting[expires]" size="10" id="expires"/>
<select onchange="Dd('expires').value=this.value">
<option value="">默认缓存</option>
<option value="0">不缓存</option>
<option value="600">自定义时间(秒)</option>
</select>
</td>
<td>expires</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 标签模板</td>
<td>
<?php echo tpl_select('', 'tag', 'setting[template]', '请选择', '0', 'id="template"');?>
</td>
<td>template</td>
</tr>
<tr>
<td class="tl" height="40">

</td>
<td>
<input type="button" value="生成标签" class="btn-b" onclick="mk_tag();"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="调用手册" class="btn" onclick="window.open('https://www.destoon.com/doc/develop/22.html');"/>
</td>
<td> </td>
</table>
<form method="post" action="?" target="destoon_tag" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="preview"/>
<input type="hidden" id="tag_expires" name="tag_expires"/>
<div class="tt">标签代码</div>
<table cellspacing="0" class="tb">
<tr class="dsn">
<td class="tl"><span class="f_hid">*</span> 自定义CSS</td>
<td><textarea name="tag_css" id="tag_css"  style="width:98%;height:40px;font-family:Fixedsys,verdana;overflow:visible;color:green;"></textarea> 
</td>
</tr>
<tr class="dsn">
<td class="tl"><span class="f_hid">*</span> HTML开始标签</td>
<td><input type="text" name="tag_html_s" id="tag_html_s" size="30" value="" style="font-family:Fixedsys,verdana;"/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 标签代码</td>
<td><textarea name="tag_code" id="tag_code"  style="width:98%;height:40px;font-family:Fixedsys,verdana;overflow:visible;color:blue;"></textarea> 
</td>
</tr>
<tr class="dsn">
<td class="tl"><span class="f_hid">*</span> HTML结束标签</td>
<td><input type="text" name="tag_html_e" id="tag_html_e" size="10" value="" style="font-family:Fixedsys,verdana;"/></td>
</tr>
<tr>
<td class="tl"></td>
<td>
<input type="submit" name="submit" value="预览标签" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="重新生成" class="btn" onclick="Go('?file=<?php echo $file;?>');"/>&nbsp;&nbsp;
</td>
</tr>
</table>
</form>
<script type="text/javascript">
function mk_tag() {
	if(Dd('moduleid').value == '' && Dd('table').value == '') {
		alert('所属模块 或 数据表 必须指定一项');
		return false;
	}
	var tag = '';
	if(Dd('moduleid').value != '') tag += '&moduleid='+Dd('moduleid').value;
	if(Dd('table').value != '') tag += '&table='+Dd('table').value;
	if(Dd('catid').value != '') tag += '&catid='+Dd('catid').value;
	if(Dd('catid').value != '' && Dd('child').checked) tag += '&child=0';
	if(Dd('areaid').value != '') tag += '&areaid='+Dd('areaid').value;
	if(Dd('areaid').value != '' && Dd('areachild').checked) tag += '&areachild=0';
	if(Dd('condition').value != '' && Dd('condition').value != '1') tag += '&condition='+Dd('condition').value;
	if(Dd('pagesize').value == '') {
		alert('请填写调用数量');
		Dd('pagesize').focus();
		return;
	} else {
		tag += '&pagesize='+Dd('pagesize').value;
	}
	if(Dd('order').value != '') tag += '&order='+Dd('order').value;
	if(Dd('template').value != 0) tag += '&template='+Dd('template').value;
	tag = tag.substr(1);
	tag = '<!--{tag("'+tag+'"';
	if(Dd('expires').value != '') {
		tag += ', '+Dd('expires').value;
	}
	tag = tag+')}-->';
	Dd('tag_code').value = tag;
}
function copy_tag() {
	if(!Dd('tag_code').value) return;
	Dd('tag_code').select();
	if(isIE) {
		clipboardData.setData('text', Dd('tag_code').value);
	} else {
		prompt('Press Ctrl+C Copy to Clipboard', Dd('tag_code').value);
	}
}
function check() {
	if(Dd('expires').value != '') Dd('tag_expires').value = Dd('expires').value
	if(Dd('tag_code').value == '') {
		if(confirm('标签代码尚未生成，现在生成吗？')) mk_tag();
		return false;
	}
}
function mod(m) {
	if(m == '$moduleid') {
		Dd('moduleid').value = m;
		Dh('tr_table');
		return false;
	}
	if(m == '') {
		Dd('moduleid').value = m;
		Ds('tr_table');
		return false;
	}
	Go('?file=<?php echo $file;?>&mid='+m);
}
function stoinp(s, i, p) {
	if(Dd(i).value) {
		var p = p ? p : ',';
		var a = Dd(i).value.split(p);
		for (var j=0; j<a.length; j++) {if(s == a[j]) return;}
		Dd(i).value += p+s;
	} else {
		Dd(i).value = s;
	}
}
function cat() {
	if(Dd('catid_1').value > 0) {
		stoinp(Dd('catid_1').value, 'catid');
	} else {
		Dd('catid').value = '';
	}
}
function are() {
	if(Dd('areaid_1').value > 0) {
		stoinp(Dd('areaid_1').value, 'areaid');
	} else {
		Dd('areaid').value = '';
	}
}
function Dict() {
	if(Dd('moduleid').value == '' && Dd('table').value == '') {
		alert('所属模块 或 数据表 必须指定一项');
		return false;
	}
	Dwidget('?file=tag&action=find&mid='+Dd('moduleid').value+'&tb='+Dd('table').value, '数据字典');
}
</script>
<script type="text/javascript">Menuon(3);</script>
<?php include tpl('footer');?>