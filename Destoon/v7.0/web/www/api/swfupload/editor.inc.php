<?php
defined('IN_DESTOON') or exit('Access Denied');
$swf_is_admin = defined('DT_ADMIN');
?>
<tr>
<td class="tl"><?php echo $swf_is_admin ? '<span class="f_hid">*</span> ' : '';?>批量传图</td> 
<td<?php echo $swf_is_admin ? '' : ' class="tr"';?>>
<link href="<?php echo DT_PATH;?>api/swfupload/style.css" rel="stylesheet" type="text/css"/>
	<div class="swfuploadbtn">
		<span id="spanButtonPlaceholder"></span>
	</div>
<div id="divFileProgressContainer"></div>
<div id="thumbnails"></div>
<script type="text/javascript" src="<?php echo $swf_is_admin ? DT_PATH : $MODULE[1]['linkurl'];?>api/swfupload/swfupload.js"></script>
<script type="text/javascript">var swfu_max = <?php echo $swf_is_admin ? 0 : 20;?>;</script>
<script type="text/javascript" src="<?php echo $swf_is_admin ? DT_PATH : $MODULE[1]['linkurl'];?>api/swfupload/handlers_editor.js"></script>
<script type="text/javascript">
	var swfu;
	//window.onload = function () {
		swfu = new SWFUpload({
			// Backend Settings
			upload_url: UPPath,
			post_params: {"moduleid": "<?php echo $moduleid;?>", "from": "photo", "width": "100", "height": "100", "swf_userid": "<?php echo $_userid;?>", "swf_username": "<?php echo $_username;?>", "swf_groupid": "<?php echo $_groupid;?>", "swf_company": "<?php echo $_company;?>", "swf_auth": "<?php echo md5($_userid.$_username.$_groupid.$_company.DT_KEY.$DT_IP);?>", "swfupload": "1"},

			// File Upload Settings
			file_size_limit : "32 MB",	// 32MB
			file_types : "*.jpg;*.jpeg;*.gif;*.png",
			file_types_description : "Images",
			file_upload_limit : swfu_max,

			// Event Handler Settings - these functions as defined in Handlers.js
			//  The handlers are not part of SWFUpload but are part of my website and control how
			//  my website reacts to the SWFUpload events.
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,

			// Button Settings
			button_image_url : "<?php echo $swf_is_admin ? DT_PATH : $MODULE[1]['linkurl'];?>api/swfupload/upload3.png",
			button_placeholder_id : "spanButtonPlaceholder",
			button_width: 195,
			button_height: 25,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			
			// Flash Settings
			flash_url : "<?php echo $swf_is_admin ? DT_PATH : $MODULE[1]['linkurl'];?>api/swfupload/swfupload.swf",

			custom_settings : {
				upload_target : "divFileProgressContainer"
			},
			
			// Debug Settings
			debug: false
		});
	//};
</script>
</td>
</tr>