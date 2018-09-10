<?php
/**
 * kl_album_ajax_do.php
 * design by KLLER
 */
require_once('../../../init.php');
$DB = MySql::getInstance();
$kl_album_config = unserialize(Option::get('kl_album_config'));
if(isset($_POST['album']) && isset($_FILES['Filedata'])){
	if(function_exists('ini_get')){
		$kl_album_memory_limit = ini_get('memory_limit');
		$kl_album_memory_limit = substr($kl_album_memory_limit, 0, strlen($kl_album_memory_limit)-1);
		$kl_album_memory_limit = ($kl_album_memory_limit+20).'M';
		ini_set('memory_limit', $kl_album_memory_limit);
	}
	define('KL_UPLOADFILE_MAXSIZE', kl_album_get_upload_max_filesize());
	define('KL_UPLOADFILE_PATH', '../../../content/plugins/kl_album/upload/');
	define('KL_IMG_ATT_MAX_W',	100);//图片附件缩略图最大宽
	define('KL_IMG_ATT_MAX_H',	100);//图片附件缩略图最大高
	$att_type = array('jpg', 'jpeg', 'png', 'gif');//允许上传的文件类型
	$album = isset($_POST['album']) ? intval($_POST['album']) : '';
	if($_FILES['Filedata']['error'] != 4){
		$upfname = klUploadFile($_FILES['Filedata']['name'], $_FILES['Filedata']['error'], $_FILES['Filedata']['tmp_name'], $_FILES['Filedata']['size'], $_FILES['Filedata']['type'], $att_type);
		$result = $DB->query("INSERT INTO ".DB_PREFIX."kl_album(truename, filename, description, album, addtime) VALUES('".$_FILES['Filedata']['name']."', '".$upfname."', '".date('Y-m-d', time())."', $album, ".time().")");
		if($result){
			$new_id = $DB->insert_id();
			$the_option_value = Option::get('kl_album_'.$album);
			if($the_option_value !== null){
				$the_option_value = trim($new_id.','.$the_option_value, ',');
				Option::updateOption('kl_album_'.$album, $the_option_value);
				$CACHE->updateCache('options');
			}
		}
	}
	exit;
}
if(ROLE != 'admin') exit('access deined!');
if(isset($_GET['action']) && $_GET['action']!=''){
	foreach($_GET as $k => $v){
		$v_b = iconv('utf-8', 'utf-8', $v);
		$_GET[$k] = strlen($v_b) != strlen($v) ? iconv('GBK', 'UTF-8', $v) : $v;
	}
	switch (trim($_GET['action'])){
		case 'edit':
			$id = intval($_GET['id']);
			$tn = addslashes(trim($_GET['tn']));
			$d = addslashes(trim($_GET['d']));
			$result = $DB->query('update '.DB_PREFIX."kl_album set truename='{$tn}', description='{$d}' where id={$id}");
			if($result) echo 'kl_album_successed';
			break;
		case 'del':
			$ids = addslashes(trim(trim($_POST['ids']), ','));
			$album = intval($_POST['album']);
			if($album < 1) exit('未获取到相册信息。');
			$id_arr = explode(',', $ids);
			//搜集要删除的图片名
			$query = $DB->query('select * from '.DB_PREFIX."kl_album where id in({$ids})");
			$photo_arr = array();
			while($row = $DB->fetch_array($query)){
				$photo_arr[] = $row['filename'];
			}
			//删除数据表中的记录
			$result = $DB->query('delete from '.DB_PREFIX."kl_album where id in({$ids})");
			if($result){
				//删除对应的图片文件
				foreach($photo_arr as $photo){
					@unlink('../../'.str_replace('thum-', '', $photo));
					@unlink('../../'.$photo);
				}
				//如果删除的相片是某相册的封面则取消其封面
				$kl_album_info = Option::get('kl_album_info');
				$kl_album_info = unserialize($kl_album_info);
				foreach($kl_album_info as $k => $v){
					if(isset($v['head']) && in_array($v['head'], $id_arr)) unset($kl_album_info[$k]['head']);
				}
				$kl_album_info = mysql_escape_string(serialize($kl_album_info));
				Option::updateOption('kl_album_info', $kl_album_info);
				//更新排序中id
				$kl_album = Option::get('kl_album_'.$album);
				$id_arr_o = array();
				if(!empty($kl_album)){
					$id_arr_o = explode(',', $kl_album);
					$kl_album = array_diff($id_arr_o, $id_arr);
					$kl_album = implode(',', $kl_album);
					Option::updateOption('kl_album_'.$album, $kl_album);
				}
				$CACHE->updateCache('options');
			}
			echo 'kl_album_successed';
			break;
		case 'move':
			$ids = addslashes(trim(trim($_POST['ids']), ','));
			$id_arr = explode(',', $ids);
			$newalbum = intval($_POST['newalbum']);
			if($newalbum < 1) exit('未获取到相册信息。');
			//搜集要删除的图片名
			$query = $DB->query('select * from '.DB_PREFIX."kl_album where id in({$ids})");
			$photo_arr = array();
			$album = '';
			while($row = $DB->fetch_array($query)){
				$photo_arr[] = $row['filename'];
				if(empty($album)) $album = $row['album'];
			}
			//更改图片所属相册
			$result = $DB->query("update ".DB_PREFIX."kl_album set album={$newalbum} where id in({$ids})");
			if($result){
				//如果删除的相片是原相册的封面则取消其封面
				$kl_album_info = Option::get('kl_album_info');
				$kl_album_info = unserialize($kl_album_info);
				foreach($kl_album_info as $k => $v){
					if($v['addtime'] == $album && isset($v['head']) && in_array($v['head'], $id_arr)) unset($kl_album_info[$k]['head']);
				}
				$kl_album_info = mysql_escape_string(serialize($kl_album_info));
				Option::updateOption('kl_album_info', $kl_album_info);
				//更新原相册排序中id
				$kl_album = Option::get('kl_album_'.$album);
				if(!is_null($kl_album)){
					$id_arr_o = explode(',', $kl_album);
					$kl_album = array_diff($id_arr_o, $id_arr);
					$kl_album = implode(',', $kl_album);
					Option::updateOption('kl_album_'.$album, $kl_album);
				}
				//更新新相册排序中id
				$kl_album = Option::get('kl_album_'.$newalbum);
				if(!is_null($kl_album)){
					$kl_album = trim($ids.','.$kl_album, ',');
					Option::updateOption('kl_album_'.$newalbum, $kl_album);
				}
			}
			$CACHE->updateCache('options');
			echo 'kl_album_successed';
			break;
		case 'album_sort':
			$ids = addslashes(trim(trim($_POST['ids']), ','));
			$id_arr = explode(',', $ids);
			if(empty($id_arr)) exit('没有获取到相册排序需要的信息');
			$kl_album_info = Option::get('kl_album_info');
			$kl_album_info = unserialize($kl_album_info);
			$new_kl_album_info = array();
			krsort($id_arr);
			foreach($id_arr as $id_v){
				foreach ($kl_album_info as $v){
					if($v['addtime'] == $id_v) $new_kl_album_info[] = $v;
				}
			}
			$new_kl_album_info = mysql_escape_string(serialize($new_kl_album_info));
			Option::updateOption('kl_album_info', $new_kl_album_info);
			$CACHE->updateCache('options');
			echo 'kl_album_successed';
			break;
		case 'photo_sort':
			$ids = addslashes(trim(trim($_POST['ids']), ','));
			$album = intval($_POST['album']);
			if($album < 1) exit('未获取到相册名称');
			$kl_album = Option::get('kl_album_'.$album);
			if(is_null($kl_album)){
				$DB->query("INSERT INTO ".DB_PREFIX."options(option_name, option_value) VALUES('kl_album_{$album}', '{$ids}')");
			}else{
				Option::updateOption('kl_album_'.$album, $ids);
			}
			$CACHE->updateCache('options');
			echo 'kl_album_successed';
			break;
		case 'photo_sort_reset':
			$album = intval($_GET['album']);
			if($album < 1) exit('未获取到相册名称');
			$kl_album = Option::get('kl_album_'.$album);
			if(!is_null($kl_album)){
				$DB->query("DELETE FROM ".DB_PREFIX."options where option_name='kl_album_{$album}'");
			}
			$CACHE->updateCache('options');
			echo 'kl_album_successed';
			break;
		case 'setHead':
			$id = intval($_GET['id']);
			$album = intval($_GET['album']);
			if($album < 1) exit('未获取到相册名称');
			$kl_album_info = Option::get('kl_album_info');
			$kl_album_info = unserialize($kl_album_info);
			foreach ($kl_album_info as $k => $v){
				if($v['addtime'] == $album) $kl_album_info[$k]['head'] = $id;
			}
			$kl_album_info = mysql_escape_string(serialize($kl_album_info));
			Option::updateOption('kl_album_info', $kl_album_info);
			$CACHE->updateCache('options');
			echo 'kl_album_successed';
			break;
		case 'album_edit':
			$key = intval($_GET['key']);
			$n = trim($_GET['n']);
			$d = trim($_GET['d']);
			$r = trim($_GET['r']);
			$p = isset($_GET['p']) ? trim($_GET['p']) : '';
			$kl_album_info = Option::get('kl_album_info');
			$kl_album_info = unserialize($kl_album_info);
			$kl_album_info[$key]['name'] = $n;
			$kl_album_info[$key]['description'] = $d;
			$kl_album_info[$key]['restrict'] = $r;
			$kl_album_info[$key]['pwd'] = $p;
			$kl_album_info = mysql_escape_string(serialize($kl_album_info));
			Option::updateOption('kl_album_info', $kl_album_info);
			$CACHE->updateCache('options');
			echo json_encode(array('Y', $r));
			break;
		case 'album_del':
			$album = intval($_GET['album']);
			if($album < 1) exit('未获取到相册名称');
			$kl_album_info = Option::get('kl_album_info');
			$kl_album_info = unserialize($kl_album_info);
			foreach ($kl_album_info as $k => $v){
				if($v['addtime'] == $album) unset($kl_album_info[$k]);
			}
			$kl_album_info = array_values($kl_album_info);
			$kl_album_info = mysql_escape_string(serialize($kl_album_info));
			Option::updateOption('kl_album_info', $kl_album_info);
			$CACHE->updateCache('options');
			$query = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE album={$album}");
			while($row = $DB->fetch_array($query)){
				@unlink('../../'.$row['filename']);
				@unlink('../../'.str_replace('thum-', '', $row['filename']));
			}
			$DB->query("DELETE FROM ".DB_PREFIX."kl_album WHERE album={$album}");
			$DB->query("DELETE FROM ".DB_PREFIX."options WHERE option_name='kl_album_{$album}'");
			echo 'kl_album_successed';
			break;
		case 'album_create':
			if($_GET['is_create'] == 'Y'){
				$kl_album_arr['name'] = '新相册';
				$kl_album_arr['description'] = date('Y-m-d', time());
				$kl_album_arr['restrict'] = 'public';
				$kl_album_arr['addtime'] = time();
				$kl_album_info = Option::get('kl_album_info');
				if($kl_album_info === null){
					$kl_album_info = array();
					array_push($kl_album_info, $kl_album_arr);
					$kl_album_info = mysql_escape_string(serialize($kl_album_info));
					$DB->query("INSERT INTO ".DB_PREFIX."options(option_name, option_value) VALUES('kl_album_info', '$kl_album_info')");
				}else{
					$kl_album_info = unserialize($kl_album_info);
					array_push($kl_album_info, $kl_album_arr);
					$kl_album_info = mysql_escape_string(serialize($kl_album_info));
					Option::updateOption('kl_album_info', $kl_album_info);
				}
				$CACHE->updateCache('options');
				echo 'kl_album_successed';
			}
			break;
		case 'set_key':
			$kl_album_key = trim($_GET['kl_album_key']) != '' ? trim($_GET['kl_album_key']) : '';
			$kl_album_config['key'] = $kl_album_key;
			$kl_album_config = mysql_escape_string(serialize($kl_album_config));
			Option::updateOption('kl_album_config', $kl_album_config);
			$CACHE->updateCache('options');
			echo json_encode(array('Y', $kl_album_key));
			break;
		case 'set_description':
			$kl_album_description = trim($_GET['kl_album_description']) != '' ? trim($_GET['kl_album_description']) : '';
			$kl_album_config['description'] = $kl_album_description;
			$kl_album_config = mysql_escape_string(serialize($kl_album_config));
			Option::updateOption('kl_album_config', $kl_album_config);
			$CACHE->updateCache('options');
			echo json_encode(array('Y', $kl_album_description));
			break;
		case 'is_disabled':
			$is_disabled = addslashes(trim($_GET['is_disabled']));
			$kl_album_config['disabled'] = $is_disabled;
			Option::updateOption('kl_album_config', serialize($kl_album_config));
			$CACHE->updateCache('options');
			echo 'kl_album_successed';
			break;
		case 'set_num_rows':
			$kl_album_num_rows = trim($_GET['kl_album_num_rows']) != '' ? intval(trim($_GET['kl_album_num_rows'])) : 20;
			$kl_album_config['num_rows'] = $kl_album_num_rows;
			Option::updateOption('kl_album_config', serialize($kl_album_config));
			$CACHE->updateCache('options');
			echo json_encode(array('Y', $kl_album_num_rows));
			break;
		case 'set_compression_size':
			$kl_album_compression_length = trim($_GET['kl_album_compression_length']) != '' ? intval(trim($_GET['kl_album_compression_length'])) : 1024;
			$kl_album_compression_width = trim($_GET['kl_album_compression_width']) != '' ? intval(trim($_GET['kl_album_compression_width'])) : 768;
			$kl_album_config['compression_length'] = $kl_album_compression_length;
			$kl_album_config['compression_width'] = $kl_album_compression_width;
			Option::updateOption('kl_album_config', serialize($kl_album_config));
			$CACHE->updateCache('options');
			echo json_encode(array('Y', $kl_album_compression_length, $kl_album_compression_width));
			break;
		case 'set_log_photo_size':
			$kl_album_log_photo_length = trim($_GET['kl_album_log_photo_length']) != '' ? intval(trim($_GET['kl_album_log_photo_length'])) : 480;
			$kl_album_log_photo_width = trim($_GET['kl_album_log_photo_width']) != '' ? intval(trim($_GET['kl_album_log_photo_width'])) : 360;
			$kl_album_config['log_photo_length'] = $kl_album_log_photo_length;
			$kl_album_config['log_photo_width'] = $kl_album_log_photo_width;
			Option::updateOption('kl_album_config', serialize($kl_album_config));
			$CACHE->updateCache('options');
			echo json_encode(array('Y', $kl_album_log_photo_length, $kl_album_log_photo_width));
			break;
		case 'remove':
			$remove = $_GET['remove'];
			if($remove == 'Y'){
				$DB->query("DROP TABLE IF EXISTS `".DB_PREFIX."kl_album`");
				$DB->query("DELETE FROM ".DB_PREFIX."options WHERE option_name like 'kl_album_%'");
				$CACHE->updateCache('options');
				$Navi_Model = new Navi_Model();
				$navis = $Navi_Model->getNavis();
				foreach($navis as $navi){
					if($navi['url'] == '?plugin=kl_album' && $navi['isdefault'] == 'y'){
						$Navi_Model->deleteNavi($navi['id']);
						$CACHE->updateCache('navi');
					}
				}
				echo 'kl_album_successed';
			}
			break;
		default:
			break;
	}
}
?>