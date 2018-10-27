<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
check_referer() or dheader($MOD['linkurl']);
$itemid or dheader($MOD['linkurl']);
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
($item && $item['status'] > 2) or dheader($MOD['linkurl']);
extract($item);
$CAT = get_cat($catid);
$linkurl = $MOD['linkurl'].$linkurl;
if(!check_group($_groupid, $MOD['group_show']) || !check_group($_groupid, $MOD['group_contact']) || !check_group($_groupid, $CAT['group_show'])) {
	dheader($linkurl );
}
$fee = get_fee($item['fee'], $MOD['fee_view']);
if($MG['fee_mode'] && $MOD['fee_mode']) $fee = 0;
if($item['username'] == $_username) $fee = 0;
if($fee) {
	if($_userid) {
		check_pay($moduleid, $itemid) or dheader($linkurl);
	} else {
		dheader($linkurl);
	}
}
$db->query("UPDATE {$table} SET download=download+1 WHERE itemid=$itemid");
$fileurl = trim($fileurl);
$localfile = str_replace(DT_PATH, '', $fileurl);
if(strpos($localfile, '://') !== false) {
	$local = false;
} else {
	$localfile = DT_ROOT.'/'.$localfile;	
	if($DT['pcharset']) $localfile = convert($localfile, DT_CHARSET, $DT['pcharset']);
	if(is_file($localfile)) {
		$local = true;
		$fileurl = linkurl($fileurl);
	} else {
		dheader($fileurl);
		//dalert($L['not_file'], $linkurl);
	}
}
if(isset($mirror)) {	
	include DT_ROOT.'/file/config/mirror.inc.php';
	if(isset($MIRROR[$mirror])) {
		if($local) {
			dheader(str_replace(DT_ROOT.'/', $MIRROR[$mirror]['url'], $localfile));
		} else {
			if($DT['ftp_remote'] && $DT['remote_url']) $fileurl = str_replace($DT['remote_url'], $MIRROR[$mirror]['url'], $fileurl);
			dheader($fileurl);
		}
	} else {
		dalert($L['not_mirror'], $linkurl);
	}
} else {
	if($local) {
		if($MOD['upload'] && filesize($localfile) < $MOD['readsize']*1024*1024) {
			$ext = file_ext($localfile);
			if(!in_array($ext, explode('|', $MOD['upload'])) || in_array($ext, array('php', 'sql')) || strpos($localfile, './') !== false) dheader($fileurl);//Safe
			$title = file_vname($title);
			$title or dheader($fileurl);
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) $title = convert($title, DT_CHARSET, 'UTF-8');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) $title = str_replace(' ', '_', $title);
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) $title = convert($title, DT_CHARSET, 'GBK');
			$title or dheader($fileurl);
			file_down($localfile, $title.'.'.$ext);
		} else {
			dheader($fileurl);
		}
	} else {
		dheader($fileurl);
	}
}
?>