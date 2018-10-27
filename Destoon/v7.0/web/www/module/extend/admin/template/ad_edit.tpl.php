<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menusad);
?>
<form method="post" action="?" id="runcode_form" target="_blank">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="runcode"/>
<input type="hidden" name="codes" value=""/>
</form>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="aid" value="<?php echo $aid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="pid" value="<?php echo $p['pid'];?>"/>
<input type="hidden" name="ad[typeid]" value="<?php echo $p['typeid'];?>"/>
<input type="hidden" name="ad[key_moduleid]" value="<?php echo $p['moduleid'];?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 广告位</td>
<td class="f_b">&nbsp;<?php echo $p['name'];?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告类型</td>
<td class="f_gray">&nbsp;<?php echo $TYPE[$p['typeid']];?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告位ID</td>
<td><input name="ad[pid]" type="text" size="5" value="<?php echo $p['pid'];?>"/> <span class="f_gray">[注意]修改广告位ID可以移动此广告至其他广告位，必须在同类广告位之间移动。</span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 广告名称</td>
<td><input name="ad[title]" id="title" type="text" size="30" value="<?php echo $title;?>"/> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告介绍</td>
<td><input name="ad[introduce]" type="text" size="50" value="<?php echo $introduce;?>"/></td>
</tr>
<?php if($p['typeid'] == 1) { ?>
<tr>
<td class="tl"><span class="f_red">*</span> 广告代码</td>
<td><textarea name="ad[code]" id="code" style="width:98%;height:150px;overflow:visible;font-family:Fixedsys,verdana;"><?php echo $code;?></textarea><br/>
<input type="button" value=" 运行代码 " class="btn" onclick="runcode();"/> <span id="dcode" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 上传文件</td>
<td class="f_gray"><input type="text" size="50" id="upload"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, Dd('upload').value, 'upload');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if(Dd('upload').value) window.open(Dd('upload').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('upload').value='';" class="jt">[删除]</span><?php tips('从这里上传文件后，把地址复制到代码里即可使用');?></td>
</tr>
<?php } ?>
<?php if($p['typeid'] == 2) { ?>
<tr>
<td class="tl"><span class="f_red">*</span> 链接文字</td>
<td class="f_gray"><input type="text" size="50" name="ad[text_name]" id="text_name" value="<?php echo $text_name;?>"/> <?php echo dstyle('ad[text_style]', $text_style);?> [支持HTML语法] <span id="dtext_name" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 链接地址</td>
<td><input type="text" size="50" name="ad[text_url]" id="text_url" value="<?php echo $text_url;?>"/> <span id="dtext_url" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> Title提示</td>
<td><input type="text" size="50" name="ad[text_title]" value="<?php echo $text_title;?>"/></td>
</tr>
<?php } ?>
<?php if($p['typeid'] == 3 || $p['typeid'] == 5) { ?>
<tr>
<td class="tl"><span class="f_red">*</span> 图片地址</td>
<td class="f_gray"><input type="text" size="50" name="ad[image_src]" id="thumb" value="<?php echo $image_src;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $p['width'];?>,<?php echo $p['height'];?>, Dd('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview(Dd('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('thumb').value='';" class="jt">[删除]</span> <span id="dthumb" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 链接地址</td>
<td><input type="text" size="50" name="ad[image_url]" value="<?php echo $image_url;?>" id="image_url"/> <span id="dimage_url" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 标题ALT</td>
<td><input type="text" size="50" name="ad[image_alt]" value="<?php echo $image_alt;?>"/></td>
</tr>
<?php } ?>
<?php if($p['typeid'] == 4) { ?>
<tr>
<td class="tl"><span class="f_red">*</span> Flash地址</td>
<td class="f_gray"><input type="text" size="50" name="ad[flash_src]" id="flash" value="<?php echo $flash_src;?>"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, Dd('flash').value, 'flash');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if(Dd('flash').value) window.open(Dd('flash').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('flash').value='';" class="jt">[删除]</span> <span id="dflash" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 循环播放</td>
<td>
<input type="radio" name="ad[flash_loop]" value="1" <?php if($flash_loop == 1) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="ad[flash_loop]" value="0" <?php if($flash_loop == 0) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 链接地址</td>
<td><input type="text" size="50" name="ad[flash_url]" value="<?php echo $flash_url;?>"/></td>
</tr>
<?php } ?>
<?php if($p['typeid'] == 6) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属模块</td>
<td class="f_gray">&nbsp;<?php echo $MODULE[$p['moduleid']]['name'];?><?php tips('如果行业与关键字未设置，则参与'.$MODULE[$p['moduleid']]['name'].'首页列表排名');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属行业</td>
<td><?php echo ajax_category_select('ad[key_catid]', '请选择', $key_catid, $p['moduleid']);?><?php tips('如果选择，则参与行业列表排名');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 关键字</td>
<td><input type="text" size="30" name="ad[key_word]" value="<?php echo $key_word;?>"/><?php tips('如果填写，则参与搜索结果排名<br/>请勿过长，建议控制10个汉字内');?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 信息ID</td>
<td><input type="text" size="10" name="ad[key_id]" id="key_id" value="<?php echo $key_id;?>"/> <img src="<?php echo $MODULE[2]['linkurl'];?>image/img_select.gif" width="12" height="12" title="选择信息ID" class="c_p" onclick="select_item('<?php echo $p['moduleid'];?>&itemid='+Dd('key_id').value);"/> <span id="dkey_id" class="f_red"></span></td>
</tr>
<?php } ?>
<?php if($p['typeid'] == 7) { ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属模块</td>
<td class="f_gray">&nbsp;<?php echo $MODULE[$p['moduleid']]['name'];?><?php tips('如果行业与关键词未设置，则显示在'.$MODULE[$p['moduleid']]['name'].'首页');?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 所属分类</td>
<td><?php echo ajax_category_select('ad[key_catid]', '请选择', $key_catid, $p['moduleid']);?><?php tips('如果选择，则显示在列表页');?></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 关键词</td>
<td><input type="text" size="30" name="ad[key_word]" value="<?php echo $key_word;?>"/><?php tips('如果填写，则显示在搜索结果<br/>请勿过长，建议控制10个汉字内');?></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 广告代码</td>
<td><textarea name="ad[code]" id="code" style="width:98%;height:150px;overflow:visible;font-family:Fixedsys,verdana;"><?php echo $code;?></textarea><br/>
<input type="button" value=" 运行代码 " class="btn" onclick="runcode();"/> <span id="dcode" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 上传文件</td>
<td class="f_gray"><input type="text" size="50" id="upload"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, Dd('upload').value, 'upload');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if(Dd('upload').value) window.open(Dd('upload').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="Dd('upload').value='';" class="jt">[删除]</span><?php tips('从这里上传文件后，把地址复制到代码里即可使用');?></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_red">*</span> 投放时段</td>
<td><?php echo dcalendar('ad[fromtime]', $fromtime);?> 至 <?php echo dcalendar('ad[totime]', $totime);?>&nbsp;
<select onchange="Dd('adtotime').value=this.value;">
<option value="">快捷选择</option>
<?php $FTIME = strtotime($fromtime);?>
<option value="<?php echo timetodate($FTIME+86400*7, 3);?>">一周</option>
<option value="<?php echo timetodate($FTIME+86400*15, 3);?>">半月</option>
<option value="<?php echo timetodate($FTIME+86400*30, 3);?>">一月</option>
<option value="<?php echo timetodate($FTIME+86400*90, 3);?>">三月</option>
<option value="<?php echo timetodate($FTIME+86400*182, 3);?>">半年</option>
<option value="<?php echo timetodate($FTIME+86400*365, 3);?>">一年</option>
<option value="<?php echo timetodate($FTIME+86400*365*2, 3);?>">二年</option>
<option value="<?php echo timetodate($FTIME+86400*365*3, 3);?>">三年</option>
</select>&nbsp;<span id="dtime" class="f_red"></span></td>
</tr>
<?php if($DT['city']) { ?>
<tr style="display:<?php echo $_areaids ? 'none' : '';?>;">
<td class="tl"><span class="f_hid">*</span> 地区(分站)</td>
<td><?php echo ajax_area_select('ad[areaid]', '请选择', $areaid);?></td>
</tr>
<?php } ?>
<tr>
<td class="tl"><span class="f_hid">*</span> 会员名</td>
<td><input name="ad[username]" type="text" size="20" value="<?php echo $username;?>" id="ad_username"/> <a href="javascript:_user(Dd('ad_username').value);" class="t">[资料]</a></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 备注</td>
<td><input name="ad[note]" type="text" size="50" value="<?php echo $note;?>"/></td>
</tr>
<tr style="display:<?php if($p['typeid'] < 2 || $p['typeid'] > 6) echo 'none';?>">
<td class="tl"><span class="f_hid">*</span> 点击统计</td>
<td>
<input type="radio" name="ad[stat]" value="1" <?php if($stat) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="ad[stat]" value="0" <?php if(!$stat) echo 'checked';?>/> 关闭
</td>
</tr>
<tr style="display:<?php if(!$stat) echo 'none';?>">
<td class="tl"><span class="f_hid">*</span> 点击次数</td>
<td><input type="text" size="5" name="ad[hits]" value="<?php echo $hits;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 广告状态</td>
<td>
<input type="radio" name="ad[status]" value="3" <?php if($status==3) echo 'checked';?>/> 已通过&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="ad[status]" value="2" <?php if($status==2) echo 'checked';?>/> 审核中
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="修 改" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="返 回" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $pid;?>');"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	var t = <?php echo $p['typeid'];?>;
	f = 'title';
	l = Dd(f).value.length;
	if(l < 1) {
		Dmsg('请填写广告名称', f);
		return false;
	}
	if(Dd('adfromtime').value.length != 10 || Dd('adtotime').value.length != 10) {
		Dmsg('请填写投放时段', 'time');
		return false;
	}
	if(t == 1 || t == 7) {
		f = 'code';
		l = Dd(f).value.length;
		if(l < 5) {
			Dmsg('请填写广告代码', f);
			return false;
		}
	} else if(t == 2) {
		f = 'text_name';
		l = Dd(f).value.length;
		if(l < 2) {
			Dmsg('请填写链接文字', f);
			return false;
		}
		f = 'text_url';
		l = Dd(f).value.length;
		if(l < 12) {
			Dmsg('请填写链接地址', f);
			return false;
		}
	} else if(t == 3 || t == 5) {
		f = 'thumb';
		l = Dd(f).value.length;
		if(l < 2) {
			Dmsg('请填写图片地址', f);
			return false;
		}
	} else if(t == 4) {
		f = 'flash';
		l = Dd(f).value.length;
		if(l < 5) {
			Dmsg('请填写Flash地址', f);
			return false;
		}
	} else if(t == 6) {
		f = 'key_id';
		l = Dd(f).value.length;
		if(l < 1) {
			Dmsg('请填写信息ID', f);
			return false;
		}
	}
	return true;
}
function runcode() {
	if(Dd('code').value.length < 3) {
		Dmsg('请填写代码', 'code');
		return false;
	}
	Dd('codes').value = Dd('code').value;
	Dd('runcode_form').submit();
}
</script>
<script type="text/javascript">Menuon(<?php echo $status == 2 ? 2 : 1;?>);</script>
<?php include tpl('footer');?>