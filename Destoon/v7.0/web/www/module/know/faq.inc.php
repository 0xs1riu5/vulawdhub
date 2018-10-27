<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$head_title = lang($L['faq_title'], array($MOD['name']));
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $MOD['mobile'];
}
include template($MOD['template_faq'] ? $MOD['template_faq'] : 'faq', $module);
?>