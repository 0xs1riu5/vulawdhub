<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 所属分类</td>
<td><?php echo $_admin == 1 ? category_select('post[catid]', '选择分类', $catid, $moduleid) : ajax_category_select('post[catid]', '选择分类', $catid, $moduleid);?> <span id="dcatid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> <?php echo $MOD['name'];?>标题</td>
<td><input name="post[title]" type="text" id="title" size="60" value="<?php echo $title;?>"/> <?php echo level_select('post[level]', '级别', $level);?> <?php echo dstyle('post[style]', $style);?> <br/><span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 标题图片</td>
<td><input name="post[thumb]" id="thumb" type="text" size="60" value="<?php echo $thumb;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, Dd('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt">[删除]</span><span id="dthumb" class="f_red"></span></td>
</tr>
<?php if($MOD['swfu']) { ?>
<tr>
<td class="tl"><span class="f_red">*</span> 视频地址</td>
<td>
<div style="float:left;"><input name="post[video]" id="video" type="text" size="60" value="<?php echo $video;?>" onblur="UpdateURL();"/>&nbsp;&nbsp;</div><div style="width:34px;height:20px;float:left;"><span id="spanButtonPlaceHolder"></span></div><table cellspacing="0" style="display:none;">
<tr>
	<td>Files Queued:</td>
	<td id="tdFilesQueued"></td>
</tr>			
<tr>
	<td>Files Uploaded:</td>
	<td id="tdFilesUploaded"></td>
</tr>			
<tr>
	<td>Errors:</td>
	<td id="tdErrors"></td>
</tr>
<tr>
	<td>Current Speed:</td>
	<td id="tdCurrentSpeed"></td>
</tr>			
<tr>
	<td>Average Speed:</td>
	<td id="tdAverageSpeed"></td>
</tr>			
<tr>
	<td>Moving Average Speed:</td>
	<td id="tdMovingAverageSpeed"></td>
</tr>			
<tr>
	<td>Time Remaining</td>
	<td id="tdTimeRemaining"></td>
</tr>			
<tr>
	<td>Time Elapsed</td>
	<td id="tdTimeElapsed"></td>
</tr>
<tr>
	<td>Size Uploaded</td>
	<td id="tdSizeUploaded"></td>
</tr>			
<tr>
	<td>Progress Event Count</td>
	<td id="tdProgressEventCount"></td>
</tr>	
</table><div style="float:left;">&nbsp;&nbsp;<span onclick="vs();" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="vh();Dd('video').value='';" class="jt">[删除]</span>&nbsp;&nbsp; 
<span class="f_gray">进度：<span id="tdPercentUploaded">0%</span></span> <span id="dvideo" class="f_red"></span></div>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/swfupload/swfupload.speed.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>api/swfupload/handlers_video.js"></script>
<script type="text/javascript">
	var swfu;
	var settings = {
		flash_url : "<?php echo DT_PATH;?>api/swfupload/swfupload.swf",
		upload_url: UPPath,
		post_params: {"moduleid": "<?php echo $moduleid;?>", "from": "file", "width": "100", "height": "100", "swf_userid": "<?php echo $_userid;?>", "swf_username": "<?php echo $_username;?>", "swf_groupid": "<?php echo $_groupid;?>", "swf_company": "<?php echo $_company;?>", "swf_auth": "<?php echo md5($_userid.$_username.$_groupid.$_company.DT_KEY.$DT_IP);?>", "swfupload": "1"},
		file_size_limit : "1000 MB",
		//file_types : "*.*",
		file_types : "*.<?php echo str_replace('|', ';*.', $MOD['upload']);?>",
		file_types_description : "视频文件",
		//file_upload_limit : 100,
		file_upload_limit : 10,
		file_queue_limit : 0,

		debug: false,

		// Button settings
		button_image_url: "<?php echo DT_PATH;?>api/swfupload/upload1.png",
		button_width: "34",
		button_height: "20",
		button_placeholder_id: "spanButtonPlaceHolder",
		
		moving_average_history_size: 40,
		
		// The event handler functions are defined in handlers.js
		file_queued_handler : fileQueued,
		file_dialog_complete_handler: fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		
		custom_settings : {
			tdFilesQueued : document.getElementById("tdFilesQueued"),
			tdFilesUploaded : document.getElementById("tdFilesUploaded"),
			tdErrors : document.getElementById("tdErrors"),
			tdCurrentSpeed : document.getElementById("tdCurrentSpeed"),
			tdAverageSpeed : document.getElementById("tdAverageSpeed"),
			tdMovingAverageSpeed : document.getElementById("tdMovingAverageSpeed"),
			tdTimeRemaining : document.getElementById("tdTimeRemaining"),
			tdTimeElapsed : document.getElementById("tdTimeElapsed"),
			tdPercentUploaded : document.getElementById("tdPercentUploaded"),
			tdSizeUploaded : document.getElementById("tdSizeUploaded"),
			tdProgressEventCount : document.getElementById("tdProgressEventCount")
		}
	};
	swfu = new SWFUpload(settings);
</script>
</td>
</tr>
<?php } else { ?>
<tr>
<td class="tl"><span class="f_red">*</span> 视频地址</td>
<td><input name="post[video]" id="video" type="text" size="60" value="<?php echo $video;?>" onblur="UpdateURL();"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, Dd('video').value, 'video', '<?php echo $MOD['upload'];?>');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="vs();" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('video').value='';" class="jt">[删除]</span> <span id="dvideo" class="f_red"></span>
</td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_red">*</span> 视频宽度</td>
<td><input name="post[width]" id="width" type="text" size="5" value="<?php echo $width;?>"/> px&nbsp;&nbsp;&nbsp;高度 <input name="post[height]" id="height" type="text" size="5" value="<?php echo $height;?>"/> px <span id="dsize" class="f_red"></span></td>
</tr>
<tr id="v_player" style="display:none;">
<td class="tl"><span class="f_hid">*</span> 视频预览</td>
<td><div id="v_play"></div><div style="padding-top:10px;"><a href="javascript:" onclick="vh();" class="t">[关闭预览]</a></div></td>
</tr>
<?php if($CP) { ?>
<script type="text/javascript">
var property_catid = <?php echo $catid;?>;
var property_itemid = <?php echo $itemid;?>;
var property_admin = 1;
</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>file/script/property.js"></script>
<tbody id="load_property" style="display:none;">
<tr><td></td><td></td></tr>
</tbody>
<?php } ?>
<?php echo $FD ? fields_html('<td class="tl">', '<td>', $item) : '';?>
<tr>
<td class="tl"><span class="f_hid">*</span> 视频说明</td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', $MOD['editor'], '100%', 350);?><br/><span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 视频系列</td>
<td><input name="post[album]" type="text" size="30" value="<?php echo $album;?>"/> <?php tips('填写一个视频的关键词或者系列名称，以便关联同系列的视频');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 关键词(Tag)</td>
<td><input name="post[tag]" type="text" size="60" value="<?php echo $tag;?>"/><?php tips('多个关键词请用空格隔开');?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 会员名</td>
<td><input name="post[username]" type="text"  size="20" value="<?php echo $username;?>" id="username"/> <a href="javascript:_user(Dd('username').value);" class="t">[资料]</a> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> <?php echo $MOD['name'];?>状态</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?>/> 通过
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?>/> 待审
<input type="radio" name="post[status]" value="1" <?php if($status == 1) echo 'checked';?> onclick="if(this.checked) Dd('note').style.display='';"/> 拒绝
<input type="radio" name="post[status]" value="0" <?php if($status == 0) echo 'checked';?>/> 删除
</td>
</tr>
<tr id="note" style="display:<?php echo $status==1 ? '' : 'none';?>">
<td class="tl"><span class="f_red">*</span> 拒绝理由</td>
<td><input name="post[note]" type="text"  size="40" value="<?php echo $note;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 添加时间</td>
<td><?php echo dcalendar('post[addtime]', $addtime, '-', 1);?></td>
</tr>
<?php if($DT['city']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 地区(分站)</td>
<td><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 浏览次数</td>
<td><input name="post[hits]" type="text" size="10" value="<?php echo $hits;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 内容收费</td>
<td><input name="post[fee]" type="text" size="5" value="<?php echo $fee;?>"/><?php tips('不填或填0表示继承模块设置价格，-1表示不收费<br/>大于0的数字表示具体收费价格');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 内容模板</td>
<td><?php echo tpl_select('show', $module, 'post[template]', '默认模板', $template, 'id="template"');?><?php tips('如果没有特殊需要，一般不需要选择<br/>系统会自动继承分类或模块设置');?></td>
</tr>
<?php if($MOD['show_html']) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 自定义文件路径</td>
<td><input type="text" size="50" name="post[filepath]" value="<?php echo $filepath;?>" id="filepath"/>&nbsp;<input type="button" value="重名检测" onclick="ckpath(<?php echo $moduleid;?>, <?php echo $itemid;?>);" class="btn"/>&nbsp;<?php tips('可以包含目录和文件 例如 destoon/b2b.html<br/>请确保目录和文件名合法且可写入，否则可能生成失败');?>&nbsp; <span id="dfilepath" class="f_red"></span></td>
</tr>
<?php } ?>
</table>
<div class="sbt"><input type="submit" name="submit" value="<?php echo $action == 'edit' ? '修 改' : '添 加';?>" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo $action == 'edit' ? '返 回' : '取 消';?>" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<?php if($action == 'add') { ?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<div class="tt">单页采编</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 目标网址</td>
<td><input name="url" type="text" size="80" value="<?php echo $url;?>"/>&nbsp;&nbsp;<input type="submit" value=" 获 取 " class="btn"/>&nbsp;&nbsp;<input type="button" value=" 管理规则 " class="btn" onclick="Dwidget('?file=fetch', '管理规则');"/></td>
</tr>
</table>
</form>
<?php } ?>
<?php
load('player.js');
load('url2video.js');
?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'catid_1';
	if(Dd(f).value == 0) {
		Dmsg('请选择所属分类', 'catid', 1);
		return false;
	}
	f = 'title';
	l = Dd(f).value.length;
	if(l < 2) {
		Dmsg('请填写视频名称', f);
		return false;
	}
	f = 'thumb';
	l = Dd(f).value.length;
	if(l < 10) {
		Dmsg('请上传标题图片', f);
		return false;
	}
	UpdateURL();
	f = 'video';
	l = Dd(f).value.length;
	if(l < 10) {
		Dmsg('请填写视频地址', f);
		return false;
	}
	if(!Dd('width').value) {
		Dmsg('请填写视频宽度', 'size');
		return false;
	}
	if(!Dd('height').value) {
		Dmsg('请填写视频高度', 'size');
		return false;
	}
	<?php echo $FD ? fields_js() : '';?>
	<?php echo $CP ? property_js() : '';?>
	return true;
}
function vs() {
	UpdateURL();
	if(Dd('video').value.length > 5) {
		Ds('v_player');
		Inner('v_play', player(Dd('video').value,Dd('width').value,Dd('height').value,1));
	} else {
		Dmsg('请填写视频地址', 'video');
		vh();
	}
}
function vh() {Inner('v_play', '');Dh('v_player');}
function UpdateURL() {
	var str = url2video(Dd('video').value);
	if(str) Dd('video').value = str;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
<?php include tpl('footer');?>