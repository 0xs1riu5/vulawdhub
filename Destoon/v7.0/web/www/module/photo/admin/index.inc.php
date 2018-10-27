<?php
defined('DT_ADMIN') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/'.$module.'.class.php';
$do = new $module($moduleid);
$menus = array (
    array('添加'.$MOD['name'], '?moduleid='.$moduleid.'&action=add'),
    array($MOD['name'].'列表', '?moduleid='.$moduleid),
    array('审核'.$MOD['name'], '?moduleid='.$moduleid.'&action=check'),
    array('未通过', '?moduleid='.$moduleid.'&action=reject'),
    array('回收站', '?moduleid='.$moduleid.'&action=recycle'),
    array('移动分类', '?moduleid='.$moduleid.'&action=move'),
);

if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
	$CP = $MOD['cat_property'];
	if($CP) require DT_ROOT.'/include/property.func.php';
	isset($post_ppt) or $post_ppt = array();
}

if($_catids || $_areaids) require DT_ROOT.'/admin/admin_check.inc.php';

if(in_array($action, array('', 'check', 'reject', 'recycle'))) {
	$sfields = array('模糊', '标题', '简介', '会员名', '编辑', 'IP', '文件路径', '内容模板');
	$dfields = array('keyword', 'title', 'introduce', 'username', 'editor', 'ip', 'filepath', 'template');
	$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '更新时间降序', '更新时间升序', '浏览次数降序', '浏览次数升序', '评论数量降序', '评论数量升序', '信息ID降序', '信息ID升序');
	$dorder  = array($MOD['order'], 'addtime DESC', 'addtime ASC', 'edittime DESC', 'edittime ASC', 'hits DESC', 'hits ASC', 'comments DESC', 'comments ASC', 'itemid DESC', 'itemid ASC');

	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($order) && isset($dorder[$order]) or $order = 0;
	$level = isset($level) ? intval($level) : 0;

	isset($datetype) && in_array($datetype, array('edittime', 'addtime', 'totime')) or $datetype = 'addtime';
	(isset($fromdate) && is_date($fromdate)) or $fromdate = '';
	$fromtime = $fromdate ? strtotime($fromdate.' 0:0:0') : 0;
	(isset($todate) && is_date($todate)) or $todate = '';
	$totime = $todate ? strtotime($todate.' 23:59:59') : 0;

	$thumb = isset($thumb) ? intval($thumb) : 0;
	$open = isset($open) ? intval($open) : 0;
	$guest = isset($guest) ? intval($guest) : 0;
	$itemid or $itemid = '';

	$fields_select = dselect($sfields, 'fields', '', $fields);
	$level_select = level_select('level', '级别', $level, 'all');
	$order_select  = dselect($sorder, 'order', '', $order);

	$condition = '';
	if($_childs) $condition .= " AND catid IN (".$_childs.")";//CATE
	if($_areaids) $condition .= " AND areaid IN (".$_areaids.")";//CITY
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($catid) $condition .= ($CAT['child']) ? " AND catid IN (".$CAT['arrchildid'].")" : " AND catid=$catid";
	if($areaid) $condition .= ($ARE['child']) ? " AND areaid IN (".$ARE['arrchildid'].")" : " AND areaid=$areaid";
	if($level) $condition .= $level > 9 ? " AND level>0" : " AND level=$level";
	if($open) $condition .= " AND open=$open";
	if($fromtime) $condition .= " AND `$datetype`>=$fromtime";
	if($totime) $condition .= " AND `$datetype`<=$totime";
	if($thumb) $condition .= " AND thumb<>''";
	if($guest) $condition .= " AND username=''";
	if($itemid) $condition .= " AND itemid=$itemid";

	$timetype = strpos($dorder[$order], 'edit') === false ? 'add' : '';
}
switch($action) {
	case 'add':
		if($submit) {
			if($do->pass($post)) {
				if($FD) fields_check($post_fields);
				if($CP) property_check($post_ppt);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				if($MOD['show_html'] && $post['status'] > 2) $do->tohtml($do->itemid);
				dmsg('添加成功', '?moduleid='.$moduleid.'&id='.$do->itemid.'&tm='.($DT_TIME+5));
			} else {
				msg($do->errmsg);
			}
		} else {
			foreach($do->fields as $v) {
				isset($$v) or $$v = '';
			}
			$content = '';
			$status = $open = 3;
			$addtime = timetodate($DT_TIME);
			$username = $_username;
			$item = array();
			$menuid = 0;
			isset($url) or $url = '';
			if($url) {
				$tmp = fetch_url($url);
				if($tmp) extract($tmp);
			}
			include tpl('edit', $module);
		}
	break;
	case 'edit':
		$itemid or msg();
		$do->itemid = $itemid;
		if($submit) {
			if($do->pass($post)) {
				if($FD) fields_check($post_fields);
				if($CP) property_check($post_ppt);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($CP) property_update($post_ppt, $moduleid, $post['catid'], $do->itemid);
				$do->edit($post);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			$item = $do->get_one();
			extract($item);
			$addtime = timetodate($addtime);
			$menuon = array('4', '3', '2', '1');
			$menuid = $menuon[$status];
			include tpl($action, $module);
		}
	break;
	case 'move':
		if($submit) {
			$fromids or msg('请填写来源ID');
			if($tocatid) {
				$db->query("UPDATE {$table} SET catid=$tocatid WHERE `{$fromtype}` IN ($fromids)");
				dmsg('移动成功', $forward);
			} else {
				msg('请选择目标分类');
			}
		} else {
			$itemid = $itemid ? implode(',', $itemid) : '';
			$menuid = 5;
			include tpl($action);
		}
	break;
	case 'update':
		is_array($itemid) or msg('请选择'.$MOD['name']);
		foreach($itemid as $v) {
			$do->update($v);
		}
		dmsg('更新成功', $forward);
	break;
	case 'tohtml':
		is_array($itemid) or msg('请选择'.$MOD['name']);
		$html_itemids = $itemid;
		foreach($html_itemids as $itemid) {
			tohtml('show', $module);
		}
		dmsg('生成成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择'.$MOD['name']);
		isset($recycle) ? $do->recycle($itemid) : $do->delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'restore':
		$itemid or msg('请选择'.$MOD['name']);
		$do->restore($itemid);
		dmsg('还原成功', $forward);
	break;
	case 'clear':
		$do->clear();
		dmsg('清空成功', $forward);
	break;
	case 'level':
		$itemid or msg('请选择'.$MOD['name']);
		$level = intval($level);
		$do->level($itemid, $level);
		dmsg('级别设置成功', $forward);
	break;
	case 'recycle':
		$lists = $do->get_list('status=0'.$condition, $dorder[$order]);
		$menuid = 4;
		include tpl('index', $module);
	break;
	case 'reject':
		if($itemid && !$psize) {
			$do->reject($itemid);
			dmsg('拒绝成功', $forward);
		} else {
			$lists = $do->get_list('status=1'.$condition, $dorder[$order]);
			$menuid = 3;
			include tpl('index', $module);
		}
	break;
	case 'check':
		if($itemid && !$psize) {
			$do->check($itemid);
			dmsg('审核成功', $forward);
		} else {
			$lists = $do->get_list('status=2'.$condition, $dorder[$order]);
			$menuid = 2;
			include tpl('index', $module);
		}
	break;
	case 'item':
		$itemid or msg();
		$do->itemid = $itemid;
		if(isset($update)) {
			$thumb = '';
			if($swf_upload) {
				foreach(explode('|', $swf_upload) as $v) {
					if($v) {
						$thumb .= $v;
						$db->query("INSERT INTO {$table_item} (item,thumb) VALUES ('$itemid', '$v')");
					}
				}
			}
			$do->item_update($post);
			if($MOD['show_html']) tohtml('show', $module);
			dmsg('更新成功', '?moduleid='.$moduleid.'&action='.$action.'&itemid='.$itemid);
		} else {
			$lists = $do->item_list("item=$itemid");
			$item = $do->get_one();
			if($items != $item['items']) $db->query("UPDATE {$table} SET items=$items WHERE itemid=$itemid");
			$menuid = 1;
			include tpl($action, $module);
		}
	break;
	case 'item_delete':
		$itemid or msg();
		$do->item_delete($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'dir':
		$itemid or msg();
		if(file_ext($name) == 'zip' && is_file(DT_ROOT.'/file/temp/'.$name)) {
			$name = substr($name, 0, -4);
			dir_create(DT_ROOT.'/file/temp/'.$name);
			require DT_ROOT.'/admin/unzip.class.php';
			$zip = new unzip;
			$zip->extract_zip(DT_ROOT.'/file/temp/'.$name.'.zip', DT_ROOT.'/file/temp/'.$name);
			file_del(DT_ROOT.'/file/temp/'.$name.'.zip');
		}
		preg_match("/[a-z0-9_\-]/i", $name) or msg('目录名应为数字、字母、下划线、中划线的组合');
		$dir = DT_ROOT.'/file/temp/'.$name;
		is_dir($dir) or msg('目录不存在');
		require DT_ROOT.'/include/image.class.php';
		require DT_ROOT.'/include/bmp.func.php';
		if($DT['ftp_remote'] && $DT['remote_url']) {
			require DT_ROOT.'/include/ftp.class.php';
			$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
		}
		$i = 0;
		foreach(get_file($dir) as $f) {
			if(is_image($f)) {
				$info = getimagesize($f);
				if(!$info) continue;
				$img_w = $info[0];
				$img_h = $info[1];
				if($img_w < $DT['middle_w']) continue;
				$ext = file_ext($f);
				$size = filesize($f);
				if($DT['bmp_jpg'] && $ext == 'bmp') {
					$bmp = imagecreatefrombmp($f);
					if($bmp) {
						$f = str_replace('.bmp', '.jpg', $f);
						$ext = 'jpg';
						imagejpeg($bmp, $f);
					}
				}
				if($DT['max_image'] && $img_w > $DT['max_image']) {
					$img_h = intval($DT['max_image']*$img_h/$img_w);
					$img_w = $DT['max_image'];
					$image = new image($f);
					$image->thumb($img_w, $img_h);
				}
				$file = 'file/upload/'.timetodate($DT_TIME, $DT['uploaddir']).'/'.date('H-i-s', $DT_TIME).'-'.rand(10, 99).'-'.$_userid.'.'.$ext;
				$L = $file;
				$M = $file.'.middle.'.$ext;
				$T = $file.'.thumb.'.$ext;
				if(file_copy($f, DT_ROOT.'/'.$L)) {
					file_copy($f, DT_ROOT.'/'.$M);
					file_copy($f, DT_ROOT.'/'.$T);
					if($DT['water_type'] == 2) {
						$image = new image(DT_ROOT.'/'.$L);
						$image->waterimage();
					} else if($DT['water_type'] == 1) {
						$image = new image(DT_ROOT.'/'.$L);
						$image->watertext();
					}
					$image = new image(DT_ROOT.'/'.$M);
					$image->thumb($DT['middle_w'], $DT['middle_h']);
					$image = new image(DT_ROOT.'/'.$T);
					$image->thumb(100, 100);
					$saveto = linkurl($T);
					if($DT['ftp_remote'] && $DT['remote_url']) {
						if($ftp->connected) {
							$exp = explode("file/upload/", $saveto);
							$_saveto = 'file/upload/'.$exp[1];
							$remote = $exp[1];
							if($ftp->dftp_put($_saveto, $remote)) {
								$saveto = $DT['remote_url'].$remote;
								$DT['ftp_save'] or file_del(DT_ROOT.'/'.$_saveto);
								if(strpos($_saveto, '.thumb.') !== false) {
									$local = str_replace('.thumb.'.$ext, '', $_saveto);
									$remote = str_replace('.thumb.'.$ext, '', $exp[1]);
									$ftp->dftp_put($local, $remote);
									$DT['ftp_save'] or file_del(DT_ROOT.'/'.$local);
									$local = str_replace('.thumb.'.$ext, '.middle.'.$ext, $_saveto);
									$remote = str_replace('.thumb.'.$ext, '.middle.'.$ext, $exp[1]);
									$ftp->dftp_put($local, $remote);
									$DT['ftp_save'] or file_del(DT_ROOT.'/'.$local);
								}
							}
						}
					}
					if($DT['uploadlog']) $db->query("INSERT INTO {$DT_PRE}upload_".($_userid%10)." (item,fileurl,filesize,fileext,upfrom,width,height,moduleid,username,ip,addtime,itemid) VALUES ('".md5($saveto)."','$saveto','$size','$ext','photo','$img_w','$img_h','$moduleid','$_username','$DT_IP','$DT_TIME','$itemid')");
					$db->query("INSERT INTO {$table_item} (item, thumb) VALUES ('$itemid', '$saveto')");
					$i++;
				}
			}
		}
		dir_delete($dir);
		msg('成功添加<strong> '.$i.' </strong>张图片', '?moduleid='.$moduleid.'&action=item&itemid='.$itemid);
	break;
	case 'zip':
		$itemid or msg();
		$_FILES['uploadfile']['size'] or msg('请选择zip文件');
		require DT_ROOT.'/include/upload.class.php';
		$name = date('YmdHis').mt_rand(10, 99).$_userid;
		$upload = new upload($_FILES, 'file/temp/', $name.'.zip', 'zip');
		$upload->adduserid = false;
		if($upload->save()) {
			dir_create(DT_ROOT.'/file/temp/'.$name);
			require DT_ROOT.'/admin/unzip.class.php';
			$zip = new unzip;
			$zip->extract_zip(DT_ROOT.'/file/temp/'.$name.'.zip', DT_ROOT.'/file/temp/'.$name);
			file_del(DT_ROOT.'/file/temp/'.$name.'.zip');
			if(glob(DT_ROOT.'/file/temp/'.$name.'/*.*')) {
				msg('上传并解压缩成功，正在读取..', '?moduleid='.$moduleid.'&action=dir&name='.$name.'&itemid='.$itemid);
			} else {
				msg('解压缩失败，请检查目录权限');
			}
		} else {
			msg($upload->errmsg);
		}
	break;
	default:
		$lists = $do->get_list('status=3'.$condition, $dorder[$order]);
		$menuid = 1;
		include tpl('index', $module);
	break;
}
?>