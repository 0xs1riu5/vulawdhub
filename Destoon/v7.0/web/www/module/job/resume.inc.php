<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$itemid or dheader($DT_PC ? $MOD['linkurl'] : $MOD['mobile']);
$item = $db->get_one("SELECT * FROM {$table_resume} WHERE itemid=$itemid AND status=3");
if($item) {
	if($item['open'] != 3) include load('404.inc');
	extract($item);
} else {
	include load('404.inc');
}
$content = $db->get_one("SELECT content FROM {$table_resume_data} WHERE itemid=$itemid");
$content = $content['content'];
$print = isset($print) ? 1 : 0;
$CAT = get_cat($catid);
if(!check_group($_groupid, $MOD['group_show_resume']) || !check_group($_groupid, $CAT['group_show'])) include load('403.inc');
$parentid = $CATEGORY[$catid]['parentid'] ? $CATEGORY[$catid]['parentid'] : $catid;
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$linkurl = $MOD['linkurl'].$linkurl;
$user_status = 4;
$fee = get_fee($item['fee'], $MOD['fee_view']);
$currency = $MOD['fee_currency'];
$unit = $currency == 'money' ? $DT['money_unit'] : $DT['credit_unit'];
$name = $currency == 'money' ? $DT['money_name'] : $DT['credit_name'];
if(check_group($_groupid, $MOD['group_contact_resume'])) {
	if($MG['fee_mode'] && $MOD['fee_mode']) {
		$user_status = 3;
	} else {
		if($fee) {
			$mid = $moduleid;
			if($_userid) {
				if(check_pay($mid, $itemid)) {
					$user_status = 3;
				} else {
					$user_status = 2;
					$pay_url = ($DT_PC ? $MODULE[2]['linkurl'] : $MODULE[2]['mobile']).'pay.php?mid='.$mid.'&itemid='.$itemid;
				}
			} else {
				$user_status = 0;
			}
		} else {
			$user_status = 3;
		}
	}
} else {
	$user_status = $_userid ? 1 : 0;
}
if($_username && $_username == $item['username']) $user_status = 3;
$description = '';
if($print && $DT_PC) {
	if($user_status != 3) dheader($linkurl);
	include template('print', $module);
	exit;
}
if(!$DT_BOT) $db->query("UPDATE LOW_PRIORITY {$table_resume} SET hits=hits+1 WHERE itemid=$itemid", 'UNBUFFERED');
include DT_ROOT.'/include/seo.inc.php';
$seo_title = lang($L['resume_title'], array($truename)).$seo_delimiter.$seo_catname.$seo_modulename.$seo_delimiter.$seo_sitename;
$head_keywords = $keyword;
$head_description = $introduce ? $introduce : $title;
$template = $item['template'] ? $item['template'] : ($MOD['template_resume'] ? $MOD['template_resume'] : 'resume');
if($DT_PC) {
	if($EXT['mobile_enable']) $head_mobile = str_replace($MOD['linkurl'], $MOD['mobile'], $DT_URL);
} else {
	$back_link = $linkurl;
}
include template($template, $module);
?>