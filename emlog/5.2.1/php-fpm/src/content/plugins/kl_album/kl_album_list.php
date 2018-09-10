<?php !defined('EMLOG_ROOT') && exit('access deined!'); ?>
<style type="text/css">
#gallery {padding: 10px;text-align:left; font-size:12px;height:360px;overflow:auto;margin:0px}
#gallery ul { list-style: none; margin:0px}
#gallery ul li { display: inline; width:350px; height:150px; float:left; margin: 5px 5px 5px; border:0px solid #ccc;}
#gallery ul a:hover img {border:1px solid green;margin:0px;}
.notfengmian{padding: 5px 5px;border:0px; margin:0px;}
.pic_back {text-align:left; font-size:12px; padding:0px 20px;}
.lanniu {-moz-background-clip:border;-moz-background-inline-policy:continuous;-moz-background-origin:padding;background:transparent url(../content/plugins/kl_album/images/lanniu.jpg) no-repeat scroll 0 0;border:medium none;display:inline;height:21px;line-height:21px;margin-right:10px;text-align:center;width:61px;}
.o_bg_color{background-color:#EAEAEA}
.no_bg_color{background-color:#FFFFFF}
</style>
<script type="text/javascript" src="../content/plugins/kl_album/js/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="../content/plugins/kl_album/js/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="../content/plugins/kl_album/js/jquery.ui.mouse.min.js"></script>
<script type="text/javascript" src="../content/plugins/kl_album/js/jquery.ui.sortable.min.js"></script>
<script type="text/javascript">
jQuery(function($){
	$("#kl_album").addClass('sidebarsubmenu1');
	$('#xiangcepeizhi').click(function(){location.href='./plugin.php?plugin=kl_album&kl_album_action=config'});
	$('#xinjianxiangce').click(function(){if(confirm('确定要建立一个新相册？')){$.get('../content/plugins/kl_album/kl_album_ajax_do.php?action=album_create&sid='+Math.random(),{is_create:'Y'},function(result){if($.trim(result)=='kl_album_successed'){window.location.reload()}else{alert('发生错误:'+result)}})}});
	$('#baocunpaixu').click(function(){var ids='';$('div#gallery input[name^=sort]').each(function(){ids=ids+$(this).val()+',';});if(ids==''){alert('您貌似还木有创建相册哦')}else{$.post('../content/plugins/kl_album/kl_album_ajax_do.php?action=album_sort&sid='+Math.random(),{ids:ids},function(result){if($.trim(result)=='kl_album_successed'){alert('保存成功')}else{alert('保存失败!'+result)}})}});
	$("#kl_album_ul").sortable({handle:'div',placeholder:'o_bg_color'}).end().disableSelection();
});
function album_getclick(el){$(el).removeClass('o_bg_color').addClass('no_bg_color');};
function album_edit(num){if($('select[name^=album_r_'+num+']').val()=='protect' && $.trim($('input[name^=album_p_'+num+']').val())==''){alert('您选择了密码访问，密码不可以为空哦~')}else{if($.trim($('input[name^=album_n_'+num+']').val())==''){alert('相册名称不可以为空哦~')}else{$.getJSON('../content/plugins/kl_album/kl_album_ajax_do.php?action=album_edit&sid='+Math.random(),{key:num,n:$('input[name^=album_n_'+num+']').val(),d:$('input[name^=album_d_'+num+']').val(),r:$('select[name^=album_r_'+num+']').val(),p:$('input[name^=album_p_'+num+']').val()},function(result){if(result[0]=='Y'){$('input[name^=album_n_'+num+'],input[name^=album_d_'+num+'],input[name^=album_p_'+num+']').removeClass('no_bg_color').addClass('o_bg_color');$('#album_public_img_'+num+',#album_private_img_'+num+',#album_protect_img_'+num).not($('#album_'+result[1]+'_img_'+num)).parent().hide();$('#album_'+result[1]+'_img_'+num).parent().show()}else{alert('保存失败：'+result)};});}}}
function album_del(num){if(confirm('删除相册将一并删除该相册内所有相片，确定要删除？')){$.get('../content/plugins/kl_album/kl_album_ajax_do.php?action=album_del&sid='+Math.random(),{album:num},function(result){if($.trim(result)=='kl_album_successed'){window.location.reload()}else{alert('发生错误:'+result)}})}}
function album_r_change(obj){if($(obj).val()=='protect'){$(obj).next().show()}else{$(obj).next().hide()}}
</script>
<div class=containertitle><b>相册列表</b></div>
<div class=line></div>
<div id="content">
<div style="height:30px;">
<span style="float:left;"><input id="xinjianxiangce" type="button" value="新建相册" class="lanniu" />
<input id="baocunpaixu" type="button" value="保存排序" class="lanniu" />
<input id="xiangcepeizhi" type="button" value="相册配置" class="lanniu" /></span>
</div>
<div id="gallery">
<ul id="kl_album_ul">
<?php
$kl_album_info = Option::get('kl_album_info');
$kl_album_info = unserialize($kl_album_info);
$album_head1 = '../content/plugins/kl_album/images/only_me.jpg';
$album_head2 = '../content/plugins/kl_album/images/no_cover_s.jpg';
if(!is_array($kl_album_info) || empty($kl_album_info)){
	echo '<li>还未创建相册</li>';
}else{
	krsort($kl_album_info);
	foreach ($kl_album_info as $key => $val){
		if(!isset($val['name'])) continue;
		if(isset($val['head']) && $val['head'] != 0){
			$iquery = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE id={$val['head']}");
			if($DB->num_rows($iquery) > 0){
				$irow = $DB->fetch_row($iquery);
				$coverPath = $irow[2];
			}else{
				$coverPath = $album_head2;
			}
		}else{
			$iquery = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE album={$val['addtime']}");
			if($DB->num_rows($iquery) > 0){
				$irow = $DB->fetch_array($iquery);
				$coverPath = $irow['filename'];
			}else{
				$coverPath = $album_head2;
			}
		}
		$pwd = isset($val['pwd']) ? $val['pwd'] : '';
		switch ($val['restrict']){
			case 'public':
				$kl_quanxian_footer_str = '<select class="o_bg_color" name="album_r_'.$key.'" onchange="album_r_change(this);"><option value="public" selected>所有人可见</option><option value="private">仅主人可见</option><option value="protect">密码访问</option></select><input type="text" name="album_p_'.$key.'" value="'.$pwd.'" class="o_bg_color" onclick="album_getclick(this)" onpaste="return false" style="width:55px;display:none;ime-mode:disabled;" />';
				$kl_img_str = '<span><img id="album_public_img_'.$key.'" class="notfengmian" src="'.$coverPath.'" /></span><span style="display:none;"><img id="album_private_img_'.$key.'" class="notfengmian" src="../content/plugins/kl_album/images/only_me.jpg" /></span><span style="display:none;"><img id="album_protect_img_'.$key.'" class="notfengmian" src="'.$coverPath.'" /></span>';
				break;
			case 'private':
				$kl_quanxian_footer_str = '<select class="o_bg_color" name="album_r_'.$key.'" onchange="album_r_change(this);"><option value="public">所有人可见</option><option value="private" selected>仅主人可见</option><option value="protect">密码访问</option></select><input type="text" name="album_p_'.$key.'" value="'.$pwd.'" class="o_bg_color" onclick="album_getclick(this)" onpaste="return false" style="width:55px;display:none;ime-mode:disabled;" />';
				$kl_img_str = '<span style="display:none;"><img id="album_public_img_'.$key.'" class="notfengmian" src="'.$coverPath.'" /></span><span><img id="album_private_img_'.$key.'" class="notfengmian" src="../content/plugins/kl_album/images/only_me.jpg" /></span><span style="display:none;"><img id="album_protect_img_'.$key.'" class="notfengmian" src="'.$coverPath.'" /></span>';
				break;
			case 'protect':
				$kl_quanxian_footer_str = '<select class="o_bg_color" name="album_r_'.$key.'" onchange="album_r_change(this);"><option value="public">所有人可见</option><option value="private">仅主人可见</option><option value="protect" selected>密码访问</option></select><input type="text" name="album_p_'.$key.'" value="'.$pwd.'" class="o_bg_color" onclick="album_getclick(this)" onpaste="return false" style="width:55px;ime-mode:disabled;" />';
				$kl_img_str = '<span style="display:none;"><img id="album_public_img_'.$key.'" class="notfengmian" src="'.$coverPath.'" /></span><span style="display:none;"><img id="album_private_img_'.$key.'" class="notfengmian" src="../content/plugins/kl_album/images/only_me.jpg" /></span><span><img id="album_protect_img_'.$key.'" class="notfengmian" src="'.$coverPath.'" /></span>';
				break;
		}
		echo '
<li>
<table height="100%" width="100%" border="0" style="background:#FFF;border:1px solid #CCC;">
  <tr>
  	<td width="5"><div style="background:#996600;height:140px;width:5px;cursor:move;"></div></td>
	<td width="110" height="140" rowspan="2" align="center"><a href="./plugin.php?plugin=kl_album&kl_album_action=display&album='.$val['addtime'].'">'.$kl_img_str.'</a></td>
	<td vlign="top">
	  <table border="0" width="100%" height="100%" style="border:1px solid #CCC;">
		<tr>
		  <td width="40" height="35"><nobr>相册名称：</nobr><input type="hidden" name="sort[]" value="'.$val['addtime'].'" /></td>
		  <td><input name="album_n_'.$key.'" type="text" value="'.$val['name'].'" class="o_bg_color" onclick="album_getclick(this)" /></td>
		</tr>
		<tr>
		  <td height="35" <nobr>相册描述：</nobr></td>
		  <td><input name="album_d_'.$key.'" type="text" value="'.$val['description'].'" class="o_bg_color" onclick="album_getclick(this)" /></td>
		</tr>
		<tr>
		  <td height="35" <nobr>访问权限：</nobr></td>
		  <td>'.$kl_quanxian_footer_str.'</td>
		</tr>
		<tr>
		  <td height="30"><mobr>操　　作：</td>
		  <td><input id="album_edit_'.$key.'" type="button" value="保存" class="lanniu" onclick="album_edit('.$key.')" /><input id="shanchu_'.$key.'" type="button" value="删除" class="lanniu" onclick="album_del('.$val['addtime'].')" /></td>
		</tr>
	  </table>
	</td>
  </tr>
</table>
</li>';
	}
}
?>
</ul>
</div>
</div>