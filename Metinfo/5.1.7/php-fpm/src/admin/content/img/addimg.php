<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='add'){
	$num = $lp+1;
    $newlist = "
			<tr class='newlist'>		
				<td class='text'>
					{$lang_displayimg}{$num}<br/>
					<a href='javascript:;' onclick='imgnumfu();delettr($(this));' style='font-weight:normal; margin-right:5px;'>{$lang_delete}</a>
				</td>
				<td class='input upload'>
					<div style='height:30px;'>
						<input name='displayname{$lp}' type='text' class='text med' value='' />
						<span class='tips'>{$lang_imagename}</span>
					</div>
					<input name='displayimg{$lp}' type='text' class='text' value='' />
					<input name='met_upsql{$lp}' type='file' id='displayimg_upload{$lp}' />
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#displayimg_upload{$lp}','big_wate_img','displayimg{$lp}','','5');
					});
					</script>
				</td>
			</tr>
			";
	echo $newlist;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>