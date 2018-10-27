<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
($MG['biz'] && $MG['homepage'] && $MG['home']) or dalert(lang('message->without_permission_and_upgrade'), 'goback');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
include load('homepage.lang');
$tab = isset($tab) ? intval($tab) : 0;
$menu_id = 2;
switch($action) {
	case 'reset':
		isset($item) or $item = '';
		if(in_array($item, array('menu', 'side', 'main'))) {
			foreach(array('show', 'order', 'num', 'file', 'name') as $v) {
				$v = $item.'_'.$v;
				$db->query("DELETE FROM {$DT_PRE}company_setting WHERE userid=$_userid AND item_key='$v'");
			}
			$tabs = array('menu'=>1, 'side'=>2, 'main'=>3);
			dmsg($L['home_msg_reset'], '?tab='.$tabs[$item]);
		} else {
			delete_upload($setting['background'], $_userid);
			delete_upload($setting['logo'], $_userid);
			delete_upload($setting['video'], $_userid);
			delete_upload($setting['banner'], $_userid);
			delete_upload($setting['bannerf'], $_userid);
			delete_upload($setting['banner1'], $_userid);
			delete_upload($setting['banner2'], $_userid);
			delete_upload($setting['banner3'], $_userid);
			delete_upload($setting['banner4'], $_userid);
			delete_upload($setting['banner5'], $_userid);
			$db->query("DELETE FROM {$DT_PRE}company_setting WHERE userid=$_userid");
			dmsg($L['home_msg_reset'], '?tab='.$tab);
		}
	break;
	default:
		$need_captcha = $MOD['captcha_home'] == 2 ? $MG['captcha'] : $MOD['captcha_home'];
		if($submit) {
			captcha($captcha, $need_captcha);
			foreach(array('background', 'logo', 'video', 'banner', 'bannerf', 'banner1', 'banner2', 'banner3', 'banner4', 'banner5', 'bannerlink1', 'bannerlink2', 'bannerlink3', 'bannerlink4', 'bannerlink5') as $v) {
				is_url($setting[$v]) or $setting[$v] = '';
			}
			$HOME = get_company_setting($_userid);
			if($HOME['background'] != $setting['background']) delete_upload($HOME['background'], $_userid);
			if($HOME['logo'] != $setting['logo']) delete_upload($HOME['logo'], $_userid);
			if($HOME['video'] != $setting['video']) delete_upload($HOME['video'], $_userid);
			if($HOME['banner'] != $setting['banner']) delete_upload($HOME['banner'], $_userid);
			if($HOME['bannerf'] != $setting['bannerf']) delete_upload($HOME['bannerf'], $_userid);
			if($HOME['banner1'] != $setting['banner1']) delete_upload($HOME['banner1'], $_userid);
			if($HOME['banner2'] != $setting['banner2']) delete_upload($HOME['banner2'], $_userid);
			if($HOME['banner3'] != $setting['banner3']) delete_upload($HOME['banner3'], $_userid);
			if($HOME['banner4'] != $setting['banner4']) delete_upload($HOME['banner4'], $_userid);
			if($HOME['banner5'] != $setting['banner5']) delete_upload($HOME['banner5'], $_userid);
			clear_upload($setting['background'].$setting['logo'].$setting['video'].$setting['banner'].$setting['bannerf'].$setting['banner1'].$setting['banner2'].$setting['banner3'].$setting['banner4'].$setting['banner5'], $_userid, 'company_setting');
			$announce = $setting['announce'];
			unset($setting['announce']);
			$setting['stats'] = $setting['stats_type'] ? $stats[$setting['stats_type']] : '';
			$setting['kf'] = $setting['kf_type'] ? $kf[$setting['kf_type']] : '';
			$setting = dhtmlspecialchars($setting);
			$setting['announce'] = dsafe($announce);
			update_company_setting($_userid, $setting);
			dmsg($L['home_msg_save'], '?tab='.$tab);
		} else {
			$CS = cache_read('module-4.php');
			$api_map = $CS['map'];
			$api_stats = $CS['stats'] ? explode(',', $CS['stats']) : array();
			$api_kf = $CS['kf'] ? explode(',', $CS['kf']) : array();
			$menu_f = ',';
			foreach(explode(',' , $MG['menu_c']) as $v) {
				$menu_f .= $MFILE[$v].',';
			}
			if($MG['menu_d']) {
				$_menu_show = array();
				foreach($HMENU as $k=>$v) {
					$_menu_show[$k] = strpos(','.$MG['menu_d'].',', ','.$k.',') !== false ? 1 : 0;
				}
				$_menu_show = implode(',', $_menu_show);
			} else {
				$_menu_show = '1,1,1,1,1,1,1,1,0,0,0,0,0,0';
			}
			$_menu_order = '0,10,20,30,40,50,60,70,80,90,100,110,120,130';
			$_menu_num = '1,16,30,30,10,30,1,12,12,12,12,30,12,1';
			$_menu_file = implode(',' , $MFILE);
			$_menu_name = implode(',' , $HMENU);

			$side_f = ',';
			foreach(explode(',' , $MG['side_c']) as $v) {
				$side_f .= $SFILE[$v].',';
			}
			if($MG['side_d']) {
				$_side_show = array();
				foreach($HSIDE as $k=>$v) {
					$_side_show[$k] = strpos(','.$MG['side_d'].',', ','.$k.',') !== false ? 1 : 0;
				}
				$_side_show = implode(',', $_side_show);
			} else {
				$_side_show = '1,1,1,0,1,0,1';
			}
			$_side_order = '0,10,20,30,40,50,60';
			$_side_num = '1,5,10,1,1,5,5';
			$_side_file = implode(',' , $SFILE);
			$_side_name = implode(',' , $HSIDE);

			$main_f = ',';
			foreach(explode(',' , $MG['main_c']) as $v) {
				$main_f .= $IFILE[$v].',';
			}
			if($MG['main_d']) {
				$_main_show = array();
				foreach($HMAIN as $k=>$v) {
					$_main_show[$k] = strpos(','.$MG['main_d'].',', ','.$k.',') !== false ? 1 : 0;
				}
				$_main_show = implode(',', $_main_show);
			} else {
				$_main_show = '1,1,1,0,0,0,0';
			}
			$_main_order = '0,10,20,30,40,50,60,70';
			$_main_num = '10,1,10,5,3,4,4,5';
			$_main_file= implode(',' , $IFILE);
			$_main_name = implode(',' , $HMAIN);

			$HOME = get_company_setting($_userid);
			extract($HOME);

			$menu_show = explode(',', isset($HOME['menu_show']) ? $HOME['menu_show'] : $_menu_show);
			$menu_order = explode(',', isset($HOME['menu_order']) ? $HOME['menu_order'] : $_menu_order);
			$menu_num = explode(',', isset($HOME['menu_num']) ? $HOME['menu_num'] : $_menu_num);
			$menu_file = explode(',', isset($HOME['menu_file']) ? $HOME['menu_file'] : $_menu_file);
			$menu_name = explode(',', isset($HOME['menu_name']) ? $HOME['menu_name'] : $_menu_name);

			$_HMENU = array();
			asort($menu_order);
			foreach($menu_order as $k=>$v) {
				$_HMENU[$k] = $HMENU[$k];
				if($menu_num[$k] < 1 || $menu_num[$k] > 50) $menu_num[$k] = 10;
			}
			$HMENU = $_HMENU;

			$pageid = array_search('page', $menu_file);

			$main_show = explode(',', isset($HOME['main_show']) ? $HOME['main_show'] : $_main_show);
			$main_order = explode(',', isset($HOME['main_order']) ? $HOME['main_order'] : $_main_order);
			$main_num = explode(',', isset($HOME['main_num']) ? $HOME['main_num'] : $_main_num);
			$main_file = explode(',', isset($HOME['main_file']) ? $HOME['main_file'] : $_main_file);
			$main_name = explode(',', isset($HOME['main_name']) ? $HOME['main_name'] : $_main_name);
			$_HMAIN = array();
			asort($main_order);
			foreach($main_order as $k=>$v) {
				$_HMAIN[$k] = $HMAIN[$k];
				if($main_num[$k] < 1 || $main_num[$k] > 50) $main_num[$k] = 10;
			}
			$HMAIN = $_HMAIN;

			$side_show = explode(',', isset($HOME['side_show']) ? $HOME['side_show'] : $_side_show);
			$side_order = explode(',', isset($HOME['side_order']) ? $HOME['side_order'] : $_side_order);
			$side_num = explode(',', isset($HOME['side_num']) ? $HOME['side_num'] : $_side_num);
			$side_file = explode(',', isset($HOME['side_file']) ? $HOME['side_file'] : $_side_file);
			$side_name = explode(',', isset($HOME['side_name']) ? $HOME['side_name'] : $_side_name);
			$_HSIDE = array();
			asort($side_order);
			foreach($side_order as $k=>$v) {
				$_HSIDE[$k] = $HSIDE[$k];
				if($side_num[$k] < 1 || $side_num[$k] > 50) $side_num[$k] = 10;
			}
			$HSIDE = $_HSIDE;

			isset($HOME['side_pos']) or $side_pos = 0;
			isset($HOME['side_width']) or $side_width = 200;
			isset($HOME['show_stats']) or $show_stats = 1;
			isset($HOME['intro_length']) or $intro_length = 1000;
			isset($HOME['stats']) or $stats = '';
			isset($HOME['stats_type']) or $stats_type = '';
			isset($HOME['kf']) or $kf = '';
			isset($HOME['kf_type']) or $kf_type = '';
			isset($HOME['map']) or $map = '';
			isset($HOME['background']) or $background = '';
			isset($HOME['bgcolor']) or $bgcolor = '';
			isset($HOME['bannert']) or $bannert = 0;
			isset($HOME['banner']) or $banner = '';
			isset($HOME['bannerf']) or $bannerf = '';
			isset($HOME['bannerw']) or $bannerw = 960;
			isset($HOME['bannerh']) or $bannerh = 200;
			isset($HOME['banner1']) or $banner1 = '';
			isset($HOME['banner2']) or $banner2 = '';
			isset($HOME['banner3']) or $banner3 = '';
			isset($HOME['banner4']) or $banner4 = '';
			isset($HOME['banner5']) or $banner5 = '';
			isset($HOME['bannerlink1']) or $bannerlink1 = '';
			isset($HOME['bannerlink2']) or $bannerlink2 = '';
			isset($HOME['bannerlink3']) or $bannerlink3 = '';
			isset($HOME['bannerlink4']) or $bannerlink4 = '';
			isset($HOME['bannerlink5']) or $bannerlink5 = '';
			isset($HOME['logo']) or $logo = '';
			isset($HOME['video']) or $video = '';
			isset($HOME['css']) or $css = '';
			isset($HOME['announce']) or $announce = '';
			isset($HOME['seo_title']) or $seo_title = '';
			isset($HOME['seo_keywords']) or $seo_keywords = '';
			isset($HOME['seo_description']) or $seo_description = '';
			$head_title = $L['home_title'];
		}
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	$back_link = 'biz.php';
}
include template('home', $module);
?>