<?php
/**
 * kl_album_setting.php
 *design by KLLER
 */
!defined('EMLOG_ROOT') && exit('access deined!');
function plugin_setting_view()
{
	$DB = MySql::getInstance();
	$is_exist_album_query = $DB->query('show tables like "'.DB_PREFIX.'kl_album"');
	if($DB->num_rows($is_exist_album_query) == 0){
?>
<script type="text/javascript">
$("#kl_album").addClass('sidebarsubmenu1');
jQuery(function($){
	$('#create').click(function(){if(confirm('确定要创建？')){$.get('../content/plugins/kl_album/kl_album_ajax_do.php?action=init&sid='+Math.random(),{create:'Y'},function(result){if($.trim(result).indexOf('kl_album_successed')!=-1){window.location.reload()}else{alert('发生错误:'+result)}})}})
})
setTimeout(hideActived,2600);
</script>
<div class=containertitle><b>相册配置</b></div>
<div class=line></div>
<div style="float:left;padding-right:5px;width:300px;">
<p>注：使用本相册首先需要点击下面按钮在数据库中手动创建一张存储相片信息的表！</p><br />
<p><input id="create" type="submit" value="现在就创建" /></p>
</div>
<?php
return;
	}
	isset($_GET['kl_album_action']) ? $kl_album_action = $_GET['kl_album_action'] : $kl_album_action = '';
	switch ($kl_album_action){
		case 'upload':
			require('kl_album_upload.php');
			break;
		case 'display':
			if(isset($_GET['album'])){
				require('kl_album_photo_list.php');
			}else{
				require('kl_album_list.php');
			}
			break;
		case 'config':
			require('kl_album_config.php');
			break;
		default:
			require('kl_album_list.php');
			break;
	}
}
?>