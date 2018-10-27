<?php
defined('IN_DESTOON') or exit('Access Denied');
if(!$_userid) exit;
isset($MODULE[$mid]) or exit;
if($job == 'get') {
	$file = DT_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/editor.data.'.$mid.'.php';
	$content = file_get($file);
	if($content) {
		echo substr($content, 13);
	} else {
		echo '';
	}
} else {
	if(!$content) exit;
	$content = stripslashes($content);
	$content = '<?php exit;?>'.timetodate($DT_TIME).$content;
	file_put(DT_ROOT.'/file/user/'.dalloc($_userid).'/'.$_userid.'/editor.data.'.$mid.'.php', $content);
}
?>