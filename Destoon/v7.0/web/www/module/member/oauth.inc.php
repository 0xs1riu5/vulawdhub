<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
switch($action) {
	case 'delete':
		login();
		$MOD['oauth'] or dheader('index.php');
		$itemid or message();
		$U = $db->get_one("SELECT * FROM {$DT_PRE}oauth WHERE itemid=$itemid");
		if(!$U || $U['username'] != $_username) message();
		$db->query("DELETE FROM {$DT_PRE}oauth WHERE itemid=$itemid");
		dmsg($L['oauth_quit'], '?action=index');
	break;
	case 'bind':
		$avatar = '';
		if(!$_userid) {
			$auth = decrypt(get_cookie('bind'), DT_KEY.'BIND');
			$openid = decrypt(get_cookie('weixin_openid'), DT_KEY.'WXID');
			if(is_openid($openid) && $DT_MOB['browser'] == 'weixin') {
				$U = $db->get_one("SELECT * FROM {$DT_PRE}weixin_user WHERE openid='$openid'");
				if($U) {
					$OAUTH = cache_read('oauth.php');
					$nohead = DT_PATH.'api/weixin/image/headimg.jpg';
					$avatar = $U['headimgurl'] ? $U['headimgurl'] : $nohead;
					$nickname = $U['nickname'] ? $U['nickname'] : 'USER';
					$site = $OAUTH['wechat']['name'];
					$connect = DT_MOB.'api/weixin.php?action=connect';
				}
			} else if(strpos($auth, '|') !== false) {
				$t = explode('|', $auth);
				$itemid = intval($t[0]);
				$U = $db->get_one("SELECT * FROM {$DT_PRE}oauth WHERE itemid=$itemid");
				if($U && $U['site'] = $t[1]) {
					$OAUTH = cache_read('oauth.php');
					$nohead = DT_PATH.'api/oauth/avatar.png';
					$avatar = $U['avatar'] ? $U['avatar'] : $nohead;
					$nickname = $U['nickname'] ? $U['nickname'] : 'USER';
					$site = $OAUTH[$U['site']]['name'];
					$connect = DT_PATH.'api/oauth/'.$U['site'].'/connect.php';
				}
			}
		}
		$avatar or dheader($DT_PC ? 'index.php' : DT_MOB.'my.php');
		$head_title = $L['oauth_bind'];
	break;
	default:
		login();
		$MOD['oauth'] or dheader('index.php');
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}oauth WHERE username='$_username'");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['logindate'] = timetodate($r['logintime'], 5);
			$r['nickname'] or $r['nickname'] = '-';
			$lists[$r['site']] = $r;
		}
		$OAUTH = cache_read('oauth.php');
		$head_title = $L['oauth_title'];	
	break;
}
if($DT_PC) {
	//
} else {
	$foot = '';
	if($action == 'bind') {
		$back_link = DT_MOB.'my.php';
	} else {
		$back_link = 'index.php';
	}
}
include template('oauth', $module);
?>