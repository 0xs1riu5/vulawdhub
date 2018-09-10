<?php $kl_album_config = unserialize(Option::get('kl_album_config'));?>
<style type="text/css">
.lanniu {-moz-background-clip:border;-moz-background-inline-policy:continuous;-moz-background-origin:padding;background:transparent url(../content/plugins/kl_album/images/lanniu.jpg) no-repeat scroll 0 0;border:medium none;display:inline;height:21px;line-height:21px;margin-right:10px;text-align:center;width:61px;}
</style>
<script type="text/javascript">
jQuery.fn.onlyPressNum = function(){$(this).css('ime-mode','disabled');$(this).css('-moz-user-select','none');$(this).bind('keydown',function(event){var k=event.keyCode;if(!((k==13)||(k==9)||(k==35)||(k == 36)||(k==8)||(k==46)||(k>=48&&k<=57)||(k>=96&&k<=105)||(k>=37&&k<=40))){event.preventDefault();}})}
jQuery(function($){
	$('input[name=kl_album_num_rows],input[name=kl_album_compression_length],input[name=kl_album_compression_width]').onlyPressNum();
	$("#kl_album").addClass('sidebarsubmenu1');
	$('#xiangceliebiao').click(function(){location.href='./plugin.php?plugin=kl_album&kl_album_action=display'});
	$('#set_key').click(function(){$.getJSON('../content/plugins/kl_album/kl_album_ajax_do.php?action=set_key&sid='+Math.random(),{kl_album_key:$('input[name=kl_album_key]').val()},function(result){if(result[0]=='Y'){alert('修改成功');$('input[name^=kl_album_key]').val(result[1]);}else{alert('更改失败：'+result)};})})
	$('#set_description').click(function(){$.getJSON('../content/plugins/kl_album/kl_album_ajax_do.php?action=set_description&sid='+Math.random(),{kl_album_description:$('input[name=kl_album_description]').val()},function(result){if(result[0]=='Y'){alert('修改成功');$('input[name^=kl_album_descritpion]').val(result[1]);}else{alert('更改失败：'+result)};})})
	$('#set_num_rows').click(function(){if($.trim($('input[name=kl_album_num_rows]').val())==''){alert('每页的显示相片数不能为空哦~')}else{$.getJSON('../content/plugins/kl_album/kl_album_ajax_do.php?action=set_num_rows&sid='+Math.random(),{kl_album_num_rows:$('input[name=kl_album_num_rows]').val()},function(result){if(result[0]=='Y'){alert('修改成功');$('input[name^=kl_album_num_rows]').val(result[1]);}else{alert('更改失败：'+result)};})}})
	$('#set_compression_size').click(function(){if($.trim($('input[name=kl_album_compression_length]').val())=='' || $.trim($('input[name=kl_album_compression_width]').val())==''){alert('最大长和最大宽都不能为空哦~')}else{$.getJSON('../content/plugins/kl_album/kl_album_ajax_do.php?action=set_compression_size&sid='+Math.random(),{kl_album_compression_length:$('input[name=kl_album_compression_length]').val(),kl_album_compression_width:$('input[name=kl_album_compression_width]').val()},function(result){if(result[0]=='Y'){alert('修改成功');$('input[name^=kl_album_compression_length]').val(result[1]);$('input[name^=kl_album_compression_width]').val(result[2]);}else{alert('更改失败：'+result)};})}})
	$('#set_log_photo_size').click(function(){if($.trim($('input[name=kl_album_log_photo_length]').val())=='' || $.trim($('input[name=kl_album_log_photo_width]').val())==''){alert('最大长和最大宽都不能为空哦~')}else{$.getJSON('../content/plugins/kl_album/kl_album_ajax_do.php?action=set_log_photo_size&sid='+Math.random(),{kl_album_log_photo_length:$('input[name=kl_album_log_photo_length]').val(),kl_album_log_photo_width:$('input[name=kl_album_log_photo_width]').val()},function(result){if(result[0]=='Y'){alert('修改成功');$('input[name^=kl_album_log_photo_length]').val(result[1]);$('input[name^=kl_album_log_photo_width]').val(result[2]);}else{alert('更改失败：'+result)};})}})
	$('#is_disabled').click(function(){$.get('../content/plugins/kl_album/kl_album_ajax_do.php?action=is_disabled&sid='+Math.random(),{is_disabled:$('input[name=kl_album_disabled]:checked').val()},function(result){if($.trim(result).indexOf('kl_album_successed')!=-1){alert('设置成功')}else{alert('设置失败:'+result)}})})
	$('#remove').click(function(){if(confirm('确定要恢复插件至初始状态？')){$.get('../content/plugins/kl_album/kl_album_ajax_do.php?action=remove&sid='+Math.random(),{remove:'Y'},function(result){if($.trim(result).indexOf('kl_album_successed')!=-1){window.location.reload()}else{alert('发生错误:'+result)}})}})
})
</script>
<div class=containertitle><b>相册配置</b></div>
<div class=line></div>
<div id="content">
<div style="height:20px;">
<span style="float:left;"><input id="xiangceliebiao" type="button" value="相册列表" class="lanniu" /></span>
</div>
<div style="margin-top:20px; padding:5px; width:720px; height:480px; border:1px dashed #CCC">
<div style="float:left;padding-right:5px;width:450px;">
<p><b>* 您可以在这里设置，当相册导航为隐藏时，相册是否可以访问。</b></p>
<p>
<?php
if(isset($kl_album_config['disabled']) && $kl_album_config['disabled'] == 'y'){
	echo '<input type="radio" name="kl_album_disabled" value="n" />可以　　　<input type="radio" name="kl_album_disabled" value="y" checked />不可以';
}else{
	echo '<input type="radio" name="kl_album_disabled" value="n" checked />可以　　　<input type="radio" name="kl_album_disabled" value="y" />不可以';
}
?>
　　　　<input id="is_disabled" type="button" class="lanniu" value="确　定" /></p>
<p><b>* 您可以在这里设置相册每页显示的相片数。</b></p>
<p><input type="text" name="kl_album_num_rows" value="<?php echo isset($kl_album_config['num_rows']) ? $kl_album_config['num_rows'] : 20; ?>" onpaste="return false" />　 <input id="set_num_rows" type="button" class="lanniu" value="确　定" /></p>
<p><b>* 您可以在这里设置相片的压缩尺寸，任一值为0则不进行压缩。(等比压缩)</b></p>
<p>最大宽：<input type="text" size="5" name="kl_album_compression_length" value="<?php echo isset($kl_album_config['compression_length']) ? $kl_album_config['compression_length'] : 1024; ?>" onpaste="return false" /> 最大高：<input type="text" size="5" name="kl_album_compression_width" value="<?php echo isset($kl_album_config['compression_width']) ? $kl_album_config['compression_width'] : 768; ?>" onpaste="return false" />　 <input id="set_compression_size" type="button" class="lanniu" value="确　定" /></p>
<p>注：所有的上传到本相册的相片都保存在本插件目录的upload文件夹下，以月份归档，其中以thum-为前缀的是缩略图，其他为原图。</p>
<p><b>* 您可以在这里设置插入到日志中的相片压缩尺寸，任一值为0则不进行压缩。(等比压缩)</b></p>
<p>最大宽：<input type="text" size="5" name="kl_album_log_photo_length" value="<?php echo isset($kl_album_config['log_photo_length']) ? $kl_album_config['log_photo_length'] : 480; ?>" onpaste="return false" /> 最大高：<input type="text" size="5" name="kl_album_log_photo_width" value="<?php echo isset($kl_album_config['log_photo_width']) ? $kl_album_config['log_photo_width'] : 360; ?>" onpaste="return false" />　 <input id="set_log_photo_size" type="button" class="lanniu" value="确　定" /></p>
<p><b>* 您可以在这里设置页面的关键字</b></p>
<p><input type="text" name="kl_album_key" style="width:350px;" value="<?php echo isset($kl_album_config['key']) ? $kl_album_config['key'] : ''?>" />　 <input id="set_key" type="button" class="lanniu" value="确　定" /></p>
<p><b>* 您可以在这里设置页面的描述</b></p>
<p><input type="text" name="kl_album_description" style="width:350px;" value="<?php echo isset($kl_album_config['description']) ? $kl_album_config['description'] : ''?>" />　 <input id="set_description" type="button" class="lanniu" value="确　定" /></p>
</div>
<div style="float:right; width:180px; background:#EAEAEA; padding:15px;">
<p><input id="remove" type="button" value="恢复插件至初始状态" /></p><br />
<p>注：执行此操作，只会删除数据库中由本插件产生的数据。您上传的相片不会被删除。</p>
</div>
</div>
</div>