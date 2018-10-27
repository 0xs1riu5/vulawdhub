<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="tb" value="<?php echo $tb;?>"/>
<input type="hidden" name="post[tb]" value="<?php echo $tb;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 字段</td>
<td><input name="post[name]" type="text" id="name" size="20"/>
<span id="dname" class="f_red"></span>
小写字母(a-z),数字(0-9) 推荐使用字母,不能以数字开头
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 字段名称</td>
<td><input name="post[title]" type="text" id="title" size="20" /> <?php tips('建议使用中文');?> 
<span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 提示信息</td>
<td><input name="post[note]" type="text" id="note" size="50" /></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 字段属性</td>
<td>
<select name="post[type]" onchange="dtype(this.value)">
<option value="varchar">字符(Varchar)</option>
<option value="int">整数(Int)</option>
<option value="float">小数(Float)</option>
<option value="text">文本(Text)</option>
</select>
</td>
</tr>
<tr id="tr_length" style="display:">
<td class="tl"><span class="f_hid">*</span> 字段长度</td>
<td><input name="post[length]" type="text" id="length" size="20" value="255"/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 表单类型</td>
<td>
<select name="post[html]" onchange="dhtml(this.value)">
<option value="text" selected>单行文本(text)</option>
<option value="textarea">多行文本(textarea)</option>
<option value="select">下拉框(select)</option>
<option value="radio">单选框(radio)</option>
<option value="checkbox">多选框(checkbox)</option>
<option value="hidden">隐藏域(hidden)</option>
<option value="date">日期选择</option>
<option value="time">时间选择</option>
<option value="thumb">缩略图上传</option>
<option value="file">文件上传</option>
<option value="editor">网页编辑器</option>
<option value="area">地区选择</option>
</select>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 默认值</td>
<td>
<textarea name="post[default_value]" style="width:250px;height:40px;overflow:visible;"></textarea>
<br/>支持PHP变量，例如 $_username
</td>
</tr>
<tr id="tr_option" style="display:none;">
<td class="tl"><span class="f_hid">*</span> 选项值</td>
<td>
<textarea name="post[option_value]" style="width:250px;height:80px;overflow:visible;">
选项值A|选项名A*
选项值B|选项名B*
选项值C|选项名C*
</textarea><br/>
一行一个 选项值|选项名称* 注意*为结尾标志符
</td>
</tr>
<tr id="tr_wh" style="display:none;">
<td class="tl"><span class="f_hid">*</span> 默认宽高</td>
<td><input name="post[width]" type="text" id="width" size="5" value="120"/> X <input name="post[height]" type="text" id="height" size="5" value="90"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 输入限制</td>
<td><input name="post[input_limit]" type="text" id="input_limit" size="40" />
<select onchange="dlimit(this.value)">
<option value="">无限制</option>
<option value="notnull">不能为空</option>
<option value="numeric">限数字</option>
<option value="letter">限字母</option>
<option value="nl">限数字和字母</option>
<option value="email">限E-mail地址</option>
<option value="date">限日期格式</option>
<option value="time">限时间格式</option>
</select><br/>
直接填数字表示限制最小长度,如果要限制长度范围例如6到20之间,则填写 6-20<br/>
可以直接书写兼容js和php的正则表达式<br/>
对于单选框(radio),填非0数字表示必选<br/>
对于多选框(checkbox),填非0数字表示必选个数 填长度范围表示必选个数范围<br/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 附加内容</td>
<td>
<input name="post[addition]" type="text" size="60" id="addition" value=""/> <?php tips('可以添加表单属性、JS事件或CSS样式 如果有单引号请加 \\');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 直接显示</td>
<td>
<input type="radio" name="post[display]" value="1" checked/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[display]" value="0"/> 否 <?php tips('如果选择否，可以手动将本字段加入到对应的模板文件里，系统将不直接显示');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 前台显示</td>
<td>
<input type="radio" name="post[front]" value="1" checked/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="post[front]" value="0"/> 否 <?php tips('如果选择是，则会在前台显示，会员可以修改');?>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="添 加" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&tb=<?php echo $tb;?>');"/></div>
</form>
<script type="text/javascript">
function dhtml(id) {
	if(id == 'select' || id == 'radio' || id == 'checkbox') {
		Dd('tr_option').style.display = '';
		Dd('tr_wh').style.display = 'none';		
	} else if(id == 'thumb' || id == 'editor') {
		Dd('tr_option').style.display = 'none';
		Dd('tr_wh').style.display = '';
		if(id == 'editor') {
			Dd('width').value = '600';
			Dd('height').value = '300';
		}
	} else {
		Dd('tr_option').style.display = 'none';
		Dd('tr_wh').style.display = 'none';
	}

	if(id == 'text') {
		Dd('addition').value = 'size="30"';
	} else if(id == 'textarea') {
		Dd('addition').value = 'rows="5" cols="80"';
	} else {
		Dd('addition').value = '';
	}
}
Dd('addition').value = 'size="30"';
function dtype(id) {
	if(id == 'varchar') {
		Dd('tr_length').style.display = '';
		Dd('length').value = '255';
	} else if(id == 'int') {
		Dd('tr_length').style.display = '';
		Dd('length').value = '10';
	} else if(id == 'float') {
		Dd('tr_length').style.display = 'none';
		Dd('length').value = '';
	} else if(id == 'text') {
		Dd('tr_length').style.display = 'none';
		Dd('length').value = '';
	}
}
function dlimit(id) {
	if(id == 'notnull') {
		Dd('input_limit').value = '1';
	} else if(id == 'numeric') {
		Dd('input_limit').value = '[0-9]{1,}';
	} else if(id == 'letter') {
		Dd('input_limit').value = '[a-z]{1,}';
	} else if(id == 'nl') {
		Dd('input_limit').value = '[a-z0-9]{1,}';
	} else if(id == 'email') {
		Dd('input_limit').value = 'is_email';
	} else if(id == 'date') {
		Dd('input_limit').value = 'is_date';
	} else if(id == 'time') {
		Dd('input_limit').value = 'is_time';
	} else {
		Dd('input_limit').value = '';
	}
}
function check() {
	if(Dd('name').value == '') {
		Dmsg('请填写字段', 'name');
		return false;
	}
	if(Dd('title').value == '') {
		Dmsg('请填写字段名称', 'title');
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>