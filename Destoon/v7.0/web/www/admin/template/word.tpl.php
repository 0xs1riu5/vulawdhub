<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<?php if($submit) { ?>
<div class="tt">保存成功</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> HTML代码</td>
<td><textarea name="content" id="content" style="width:600px;height:150px;margin:3px;font-family:Fixedsys,verdana;"><?php echo $content;?></textarea>
</td>
</tr>
<tr>
<td></td>
<td>
<input type="button" value="复 制" class="btn" onclick="CopyCode();"/>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="返 回" class="btn" onclick="Go('?file=<?php echo $file;?>&rand=<?php echo $DT_TIME;?>');"/>&nbsp;&nbsp;
提示：复制代码之后，切换编辑器到源代码模式后粘贴即可
</td>
</tr>
</table>
<div class="tt">效果预览</div>
<div style="padding:10px;background:#FFFFFF;font-size:14px;"><?php echo $content;?></div>
<script type="text/javascript">
function CopyCode() {
	Dd('content').select();
	if(isIE) {
		clipboardData.setData('text', Dd('content').value);
	} else {
		confirm('您的浏览器不支持JS复制，请按Ctrl+C复制');
	}
}
</script>
<?php } else { ?>
<div class="tt">什么是编辑助手？</div>
<table cellspacing="0" class="tb">
<tr>
<td style="line-height:32px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当您使用word制作了一篇图文并茂的文档，通过后台发布时，发现文档内容里的图片并不能直接粘贴到编辑器里。由于word的加密方式不公开，在不安装插件的情况下，常规的方法目前无法有效的解决此问题。于是一张一张的上传占用了您大量的宝贵时间……<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;编辑助手可以通过以下三步帮您快速解决此问题：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、复制一份您的word文件，然后修改新的文件名为英文和数字格式(注意：中文的文件名可能无法被助手识别)，例如“word.doc”；<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、双击打开新的word文件，点击文件，选择另存为，保存类型选择“网页 (*.htm，*.html)”；<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、假如您的word文件名为word.doc，通过另存后，会生成一个word.htm文件和一个word.files目录，选择这个文件和目录压缩为.zip格式文件，在下面上传；<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;小提示：本工具同样可以处理您制作的或从网上另存的htm静态页面，原理是相同的。<br/>
</td>
</tr>
</table>
<iframe src="" name="send" id="send" style="display:none;"></iframe>
<div class="tt">上传zip压缩文件</div>
<form method="post" action="?" enctype="multipart/form-data" target="send" onsubmit="return Upcheck();" id="up">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="upload"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 选择文件</td>
<td>
&nbsp;<input name="uploadfile" id="uploadfile" type="file" size="25" onchange="Upcheck();Dd('up').submit();"/>&nbsp;&nbsp;
<input type="submit" value=" 上 传 " class="btn-g" id="upbtn"/>
</td>
</tr>
</table>
</form>
<div style="display:none;" id="maindiv">
<div class="tt">上传成功</div>
<form method="post" action="?" onsubmit="return WdCheck();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="word" id="word" value=""/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 读取文件</td>
<td>
&nbsp;
<select name="wd_charset" id="wd_charset">
<option value="utf-8">UTF-8编码</option>
<option value="gbk">GBK编码</option>
</select>&nbsp;
<input name="wd_nr" id="wd_nr" type="checkbox" value="1" checked/> <label for="wd_nr">过滤空行</label>&nbsp;
<input name="wd_note" id="wd_note" type="checkbox" value="1" checked/> <label for="wd_note">过滤注释</label>&nbsp;
<input name="wd_span" id="wd_span" type="checkbox" value="1" checked/> <label for="wd_span">过滤span</label>&nbsp;
<input name="wd_style" id="wd_style" type="checkbox" value="1" checked/> <label for="wd_style">过滤style</label>&nbsp;
<input name="wd_class" id="wd_class" type="checkbox" value="1" checked/> <label for="wd_class">过滤class</label>&nbsp;
<input type="button" value=" 读 取 " class="btn" onclick="ReadWord();"/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> HTML代码</td>
<td><textarea name="content" id="content" style="width:600px;height:150px;margin:8px;font-family:Fixedsys,verdana;"></textarea>
</td>
</tr>
<tr>
<td></td>
<td><input type="checkbox" name="water" value="1" checked/> 图片添加水印&nbsp;&nbsp;<input type="submit" name="submit" value="保 存" class="btn-g" id="save"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="预 览" class="btn" onclick="RunCode();"/></td>
</tr>
</table>
</form>
<form method="post" action="?" id="runcode_form" target="_blank">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="run"/>
<input type="hidden" name="code" id="code" value=""/>
<input type="hidden" name="temp" id="temp" value=""/>
</form>
</div>
<script type="text/javascript">
function Upcheck() {
	Dh('maindiv');
	if(Dd('uploadfile').value=='') {
		alert('请选择zip文件');
		return false;
	}
	Dd('upbtn').value = '上传中..';
}
function Upsuccess(s) {
	Ds('maindiv');
	Dd('word').value = s;
	Dd('up').reset();
	Dd('upbtn').value = '上 传';
	ReadWord();
}
function ReadWord() {
	var p = '?file=<?php echo $file;?>&action=read&word='+Dd('word').value+'&charset='+Dd('wd_charset').value;
	p += '&wd_nr='+(Dd('wd_nr').checked ? 1 : 0);
	p += '&wd_note='+(Dd('wd_note').checked ? 1 : 0);
	p += '&wd_span='+(Dd('wd_span').checked ? 1 : 0);
	p += '&wd_style='+(Dd('wd_style').checked ? 1 : 0);
	p += '&wd_class='+(Dd('wd_class').checked ? 1 : 0);
	$.get(p, function(data) {
		if(data) {
			Dd('content').value = data;
		} else {
			alert('读取失败，请检查压缩包内的htm文件');
		}
	});
}
function RunCode() {
	if(Dd('content').value == '') {
		if(confirm('您还没有读取文件，是否现在读取？')) ReadWord();
		return false;
	}
	Dd('code').value = Dd('content').value;
	Dd('temp').value = Dd('word').value;
	Dd('runcode_form').submit();
}
function WdCheck() {
	if(Dd('content').value == '') {
		if(confirm('您还没有读取文件，是否现在读取？')) ReadWord();
		return false;
	}
	Dd('save').value = '处理中..';
}
</script>
<?php } ?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>