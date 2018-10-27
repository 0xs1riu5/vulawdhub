<?php
require '../../common.inc.php';
require DT_ROOT.'/include/mobile.inc.php';
$file = DT_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/mobile-search.php';
if($action == 'clear') {
	if($_userid) {
		file_del($file);
		exit('ok');
	}
	exit('ko');
}
if($mid > 3 && $kw && isset($MODULE[$mid]) && !$MODULE[$mid]['islink']) {
	$kw = input_trim($kw);
	if($_userid && $kw && strpos($kw, '|') === false) {
		$data = file_get($file);
		if($data) {
			$data = trim(substr($data, 13));
			$text = '<?php exit;?>'.$kw.'|'.$mid."\n";
			$i = 0;
			foreach(explode("\n", $data) as $v) {
				if($i++ < 50 && strpos($v, $kw.'|') === false) $text .= $v."\n";
			}
			file_put($file, $text);
		} else {
			file_put($file, '<?php exit;?>'.$kw.'|'.$mid."\n");
		}
	}
	dheader($MODULE[$mid]['mobile'].'search.php?kw='.urlencode($kw));
}
$lists = array();
if($_userid) {
	$data = file_get($file);
	if($data) {
		$data = trim(substr($data, 13));		
		foreach(explode("\n", $data) as $v) {
			list($_k, $_m) = explode('|', $v);
			$lists[] = array('kw' => $_k, 'mid' => $_m);
		}
	}
}
$head_title = $head_name = $L['search_title'];
$foot = 'channel';
include template('search', 'mobile');
?>