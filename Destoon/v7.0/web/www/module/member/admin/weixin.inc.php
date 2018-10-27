<?php
defined('IN_DESTOON') or exit('Access Denied');
isset($username) or $username = '';
isset($openid) or $openid = '';
if(check_name($username)) {
	$U = $db->get_one("SELECT * FROM {$DT_PRE}weixin_user WHERE username='$username'");
	$U or msg('用户未绑定微信帐号');
	$openid = $U['openid'];
} else if($openid) {
	$U = $db->get_one("SELECT * FROM {$DT_PRE}weixin_user WHERE openid='$openid'");
	$U or msg('微信帐号不存在');
}
if($openid) {
	$U['headimgurl'] or $U['headimgurl'] = 'api/weixin/image/headimg.jpg';
	$menus = array (
		array('消息记录', '?moduleid='.$moduleid.'&file='.$file.'&openid='.$openid),
		array('事件记录', '?moduleid='.$moduleid.'&file='.$file.'&openid='.$openid.'&action=event'),
		array('微信交谈', '?moduleid='.$moduleid.'&file='.$file.'&openid='.$openid.'&action=chat'),
	);
} else {
	$menus = array (
		array('消息记录', '?moduleid='.$moduleid.'&file='.$file),
		array('事件记录', '?moduleid='.$moduleid.'&file='.$file.'&action=event'),
		array('用户管理', '?moduleid='.$moduleid.'&file='.$file.'&action=user'),
		array('自动回复', '?moduleid='.$moduleid.'&file='.$file.'&action=auto'),
		array('菜单管理', '?moduleid='.$moduleid.'&file='.$file.'&action=menu'),
		array('帐号设置', '?moduleid='.$moduleid.'&file='.$file.'&action=setting'),
		array('公众平台', DT_PATH.'api/redirect.php?url=http://mp.weixin.qq.com/', 'target="_blank"'),
	);
}
switch($action) {
	case 'unbind':
		$itemid or msg('请选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("UPDATE {$DT_PRE}weixin_user SET username='' WHERE itemid IN ($itemids)");
		dmsg('解除成功', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择记录');
		$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
		$db->query("DELETE FROM {$DT_PRE}weixin_chat WHERE itemid IN ($itemids)");
		dmsg('删除成功', $forward);
	break;
	case 'setting':
		if($submit) {		
			$setting = array_map('trim', $setting);
			$open = $setting['appid'] && $setting['appsecret'] && $setting['apptoken'] && $setting['weixin'] ? 1 : 0;
			$db->query("UPDATE {$DT_PRE}setting SET item_value='$open' WHERE item_key='weixin' AND item='3'");
			cache_module(3);
			update_setting('weixin', $setting);
			cache_weixin();
			dmsg($open ? '微信已开启' : '微信已关闭', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
		} else {
			cache_weixin();
			extract(dhtmlspecialchars(cache_read('weixin.php')));
			include tpl('weixin_setting', $module);
		}
	break;
	case 'sync':
		require DT_ROOT.'/api/weixin/init.inc.php';
		isset($next_openid) or $next_openid = '';
		$num = isset($num) ? intval($num) : 0;
		$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'&next_openid='.$next_openid;
		$arr = $wx->http_get($url);
		isset($arr['total']) or msg('连接失败，请检查配置');
		if($arr['total'] == 0) dmsg('同步成功', '?moduleid='.$moduleid.'&file='.$file.'&action=user');
		foreach($arr['data']['openid'] as $v) {
			$num++;
			$user = weixin_user($v);
			if(!$user) $db->query("INSERT INTO {$DT_PRE}weixin_user (openid) VALUES ('$v')");
		}
		if($arr['next_openid'] == '' || $arr['next_openid'] == $next_openid) {
			msg('会员同步成功，开始同步会员资料...', '?moduleid='.$moduleid.'&file='.$file.'&action=sync_user');
		} else {
			msg('已同步 '.$num.' 位会员'.progress(1, $num, $arr['total']), '?moduleid='.$moduleid.'&file='.$file.'&action='.$action.'&num='.$num.'&next_openid='.$arr['next_openid']);
		}		
	break;
	case 'sync_user':
		require DT_ROOT.'/api/weixin/init.inc.php';
		if(!isset($fid)) {
			$r = $db->get_one("SELECT min(itemid) AS fid FROM {$DT_PRE}weixin_user");
			$fid = $r['fid'] ? $r['fid'] : 0;
		}
		isset($sid) or $sid = $fid;
		if(!isset($tid)) {
			$r = $db->get_one("SELECT max(itemid) AS tid FROM {$DT_PRE}weixin_user");
			$tid = $r['tid'] ? $r['tid'] : 0;
		}
		isset($num) or $num = 50;
		if($fid <= $tid) {
			$result = $db->query("SELECT * FROM {$DT_PRE}weixin_user WHERE itemid>=$fid ORDER BY itemid LIMIT 0,$num ");
			if($db->affected_rows($result)) {
				while($user = $db->fetch_array($result)) {
					$itemid = $user['itemid'];
					$info = $wx->get_user($user['openid']);
					if($info) {
						if($info['subscribe'] == 0) {
							$sql = "subscribe=0,username='',edittime=$DT_TIME";						
						} else {
							$sql = "subscribe=1,addtime=".$info['subscribe_time'].",edittime=$DT_TIME";
							foreach(array('nickname', 'sex', 'city', 'province', 'country', 'language', 'headimgurl') as $v) {
								if(isset($info[$v])) $sql .= ",".$v."='".addslashes($info[$v])."'";
							}
						}
						$db->query("UPDATE {$DT_PRE}weixin_user SET $sql WHERE itemid=$itemid");
					}
				}
				$itemid += 1;
			} else {
				$itemid = $fid + $num;
			}
		} else {
			dmsg('同步成功', '?moduleid='.$moduleid.'&file='.$file.'&action=user');
		}
		msg('ID从'.$fid.'至'.($itemid-1).'资料同步成功'.progress($sid, $fid, $tid), "?moduleid=$moduleid&file=$file&action=$action&sid=$sid&fid=$itemid&tid=$tid&num=$num");
	break;
	case 'chat':
		include tpl('weixin_chat', $module);
	break;
	case 'send':
		$openid or exit;
		$word = trim(strip_tags($word));
		$word or dalert('发送内容不能为空', '', 'window.parent.chat_show();');
		require DT_ROOT.'/api/weixin/init.inc.php';
		$str = substr($word, 0, 4);
		$ext = substr($word, -3);
		$file = '';
		$type = 'text';
		if($str == 'http' && in_array($ext, array('jpg', 'amr', 'mp3', 'mp4'))) {
			if(strpos($word, DT_PATH) === 0) {
				$file = str_replace(DT_PATH, DT_ROOT.'/', $word);
			} else {
				if($DT['remote_url'] && strpos($word, $DT['remote_url']) === 0) {
					$file = DT_ROOT.'/file/temp/'.date('YmdHis', $DT_TIME).mt_rand(10, 99).$_userid.'.'.$ext;
					file_copy($word, $file);
				}
			}
			if(strpos($file, '/file/') !== false && strpos($file, '..') === false && is_file($file)) {
				$arr = $wx->http_upload($file);
				if($arr[0]) {
					file_del($file);
					$word = $arr[0];//Media_ID
					$type = $arr[1];
				} else {
					dalert('上传失败 - '.$arr[1], '', 'window.parent.chat_show();');
				}
			}
		}
		$arr = $wx->send($openid, $type, $word);
		if($arr['errcode'] != 0) {
			if($arr['errcode'] == 45015) dalert('回复时间超过限制[须48小时内回复]', '', 'window.parent.chat_hide(1);');
			dalert('发送失败 - '.$arr['errmsg'].'(errcode:'.$arr['errcode'].')', '', 'window.parent.chat_show();');
		}
		$post = array();
		$post['content'] = $word;
		$post['type'] = 'reply';
		$post['openid'] = $openid;
		$post['editor'] = $_username;
		$post['addtime'] = $DT_TIME;
		$post['misc']['type'] = $type;
		$post['misc'] = $post['misc'] ? serialize($post['misc']) : '';
		$post = daddslashes($post);
		$sql = '';
		foreach($post as $k=>$v) {
			$sql .= ",$k='$v'";
		}
		$db->query("INSERT INTO {$DT_PRE}weixin_chat SET ".substr($sql, 1));
		dalert('', '', 'window.parent.chat_show(2);');
	break;
	case 'load':
		$openid or exit;
		$chatlast = $_chatlast = intval($chatlast);
		$josn = $debug = '';
		$i = $j = 0;
		if($chatlast) {
			$sql = "SELECT * FROM {$DT_PRE}weixin_chat WHERE openid='$openid' AND event=0 AND addtime>$chatlast ORDER BY addtime DESC";
		} else {
			$sql = "SELECT * FROM {$DT_PRE}weixin_chat WHERE openid='$openid' AND event=0 ORDER BY addtime DESC LIMIT 20";
		}
		$lists = array();
		$result = $db->query($sql);
		while($r = $db->fetch_array($result)) {
			if($r['type'] == 'reply' && $r['editor'] != $_username) continue;
			$lists[] = $r;
		}
		$num = count($lists);
		if($num) {
			for($k = $num - 1; $k >= 0; $k--) {
				$r = $lists[$k];
				$time = timetodate($r['addtime'], 'H:i:s');
				$date2 = timetodate($r['addtime'], 'Y-m-d');
				if($date2 == $date1) {
					$date = '';
				} else {
					$date = $date1 = $date2;
				}
				if($i == 0 && $chatlast) $date = '';
				$word = weixin_msg($r['type'], $r['content'], $r['misc']);
				$word = str_replace('"', '\"', $word);
				if($r['editor']) {
					$name = '我';
					$self = 1;
				} else {
					$name = $U['nickname'];
					$self = 0;
					if($_chatlast) $j++;
				}
				$chatlast = $r['addtime'];
				$josn .= ($i ? ',' : '').'{time:"'.$time.'",date:"'.$date.'",name:"'.$name.'",word:"'.$word.'",self:"'.$self.'"}';
				$i = 1;
			}
		}
		$debug = timetodate($chatlast, 6).'-'.$j;
		$josn = '{chat_msg:['.$josn.'],chat_new:"'.$j.'",chat_last:"'.$chatlast.'",chat_bug:"'.$debug.'"}';
		exit($josn);
	break;
	case 'user':
		$SEX = array('未知', '男', '女');
		$SUBSCRIBE = array('<span style="color:red;">已取消</span>', '<span style="color:green;">关注中</span>', '<span style="color:#666666;">未关注</span>');
		$sfields = array('按条件', '会员名', '昵称', '城市', '省份', '国籍', '语言');
		$dfields = array('username', 'username', 'nickname', 'city', 'province', 'country', 'language');
		$sorder  = array('结果排序方式', '关注时间降序', '关注时间升序', '访问时间降序', '访问时间升序');
		$dorder  = array('itemid DESC', 'addtime DESC', 'addtime ASC', 'visittime DESC', 'visittime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($site) or $site = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$thumb = isset($thumb) ? intval($thumb) : 0;
		$sex = isset($sex) ? intval($sex) : -1;
		$subscribe = isset($subscribe) ? intval($subscribe) : -1;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '1';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($thumb) $condition .= " AND headimgurl<>''";
		if($sex > -1) $condition .= " AND sex='$sex'";
		if($subscribe > -1) $condition .= " AND subscribe='$subscribe'";
		$order = $dorder[$order];
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}weixin_user WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}weixin_user WHERE $condition ORDER BY $order LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['visitdate'] = timetodate($r['visittime'], 5);
			$r['gender'] = $SEX[$r['sex']];
			$r['status'] = $SUBSCRIBE[$r['subscribe']];
			$r['headimgurl'] or $r['headimgurl'] = 'api/weixin/image/headimg.jpg';
			$lists[] = $r;
		}
		include tpl('weixin_user', $module);
	break;
	case 'auto':
		if($submit) {		
			foreach($post as $k=>$v) {
				$k = intval($k);
				if($k == 0) {
					if($v['keyword'] && $v['reply']) {
						$K = explode("\n", trim(strip_tags($v['keyword'])));
						$R = explode("\n", trim(strip_tags($v['reply'])));
						foreach($K as $i=>$W) {
							$keyword = trim($W);
							$reply = trim($R[$i]);							
							if($keyword && $reply) $db->query("INSERT INTO {$DT_PRE}weixin_auto SET keyword='$keyword',reply='$reply'");
						}
					}
				} else {
					if(isset($v['delete'])) {
						$db->query("DELETE FROM {$DT_PRE}weixin_auto WHERE itemid=$k");
					} else {
						$keyword = trim(strip_tags($v['keyword']));
						$reply = trim(strip_tags($v['reply']));
						if($keyword && $reply) $db->query("UPDATE {$DT_PRE}weixin_auto SET keyword='$keyword',reply='$reply' WHERE itemid=$k");
					}
				}
			}
			dmsg('更新成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
		} else {
			$condition = "1";
			if($kw) $condition .= " AND (keyword LIKE '%$keyword%' OR reply LIKE '%$keyword%')";
			if($page > 1 && $sum) {
				$items = $sum;
			} else {
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}weixin_auto WHERE $condition");
				$items = $r['num'];
			}
			$pages = pages($items, $page, $pagesize);
			$lists = array();
			$result = $db->query("SELECT * FROM {$DT_PRE}weixin_auto WHERE $condition ORDER BY itemid LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				$lists[] = $r;
			}
			include tpl('weixin_auto', $module);
		}
	break;
	case 'menu':
		if($submit) {
			require DT_ROOT.'/api/weixin/init.inc.php';
			update_setting('weixin-menu', array('menu' => serialize($post)));
			cache_weixin();
			$menu = $sub = $btn = array();
			for($i = 0; $i < 3; $i++) {
				$sub[$i] = 0;
				if($post[$i][1]['name'] && $post[$i][1]['key']) $sub[$i] = 1;
			}
			for($i = 0; $i < 3; $i++) {
				if($post[$i][0]['name']) {
					$menu[$i]['name'] = urlencode($post[$i][0]['name']);
					if($sub[$i]) {
						for($j = 1; $j < 6; $j++) {
							if($post[$i][$j]['name'] && $post[$i][$j]['key']) {
								$menu[$i]['sub_button'][$j-1]['name'] = urlencode($post[$i][$j]['name']);
								if(substr($post[$i][$j]['key'], 0, 4) == 'http') {
									$menu[$i]['sub_button'][$j-1]['type'] = 'view';
									$menu[$i]['sub_button'][$j-1]['url'] = $post[$i][$j]['key'];
								} else if(strpos($post[$i][$j]['key'], '|') !== false) {
									$tmp = explode('|', $post[$i][$j]['key']);
									$menu[$i]['sub_button'][$j-1]['type'] = 'miniprogram';
									$menu[$i]['sub_button'][$j-1]['url'] = isset($tmp[2]) ? $tmp[2] : $EXT['mobile_url'];
									$menu[$i]['sub_button'][$j-1]['appid'] = $tmp[0];
									$menu[$i]['sub_button'][$j-1]['pagepath'] = $tmp[1];
								} else {
									$menu[$i]['sub_button'][$j-1]['type'] = 'click';
									$menu[$i]['sub_button'][$j-1]['key'] = $post[$i][$j]['key'];
								}
							} else {
								break;
							}
						}
					} else {
						if($post[$i][0]['key']) {
							if(substr($post[$i][0]['key'], 0, 4) == 'http') {
								$menu[$i]['type'] = 'view';
								$menu[$i]['url'] = $post[$i][0]['key'];
							} else if(strpos($post[$i][0]['key'], '|') !== false) {
								$tmp = explode('|', $post[$i][0]['key']);
								$menu[$i]['type'] = 'miniprogram';
								$menu[$i]['url'] = isset($tmp[2]) ? $tmp[2] : $EXT['mobile_url'];
								$menu[$i]['appid'] = $tmp[0];
								$menu[$i]['pagepath'] = $tmp[1];
							} else {
								$menu[$i]['type'] = 'click';
								$menu[$i]['key'] = $post[$i][0]['key'];
							}
						} else {
							msg('菜单'.($i+1).' 地址/事件 不能为空');
						}
					}
				}
			}
			$btn['button'] = $menu;
			$par = stripslashes(urldecode(json_encode($btn)));
			$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
			$arr = $wx->http_post($url, $par);
			if($arr['errcode'] == 0) {
				dmsg('同步成功', '?moduleid='.$moduleid.'&file='.$file.'&action='.$action);
			} else {
				msg('同步失败 - '.$arr['errcode'].':'.$arr['errmsg']);
			}
		} else {
			#print_r($wx->http_get('https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$access_token));
			cache_weixin();
			$menu = cache_read('weixin-menu.php');
			if(!is_array($menu) || count($menu) < 1 || count($menu) > 3) {
				$menu = array();
				for($i = 0; $i < 3; $i++) {
					for($j = 0; $j < 6; $j++) {
						$menu[$i][$j]['name'] = '';
						$menu[$i][$j]['key'] = '';
					}
				}
			}
			include tpl('weixin_menu', $module);
		}
	break;
	default:
		$sfields = array('按条件', '消息内容', '微信昵称', '会员名', '网站编辑');
		$dfields = array('c.content', 'c.content', 'u.nickname', 'u.username', 'c.editor');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($type) or $type = '';
		$event = isset($event) ? intval($event) : -1;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$condition = 'u.openid=c.openid';
		if($action == 'event') {
			$condition .= " AND c.event=1";
			$TYPE = array(
				'subscribe' => '用户订阅',
				'unsubscribe' => '取消订阅',
				'SCAN' => '扫描二维码',
				'LOCATION' => '地理位置',
				'CLICK' => '点击菜单',
				'VIEW' => '点击链接',
			);
		} else {
			$condition .= " AND c.event=0";
			$TYPE = array(
				'text' => '文本消息',
				'image' => '图片消息',
				'voice' => '语音消息',
				'video' => '视频消息',
				'location' => '地理位置',
				'link' => '链接消息',
				'reply' => '网站回复',
				'push' => '网站推送',
			);
		}
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";		
		if($type) $condition .= " AND c.type='$type'";
		if($openid) $condition .= " AND c.openid='$openid'";
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}weixin_chat c,{$DT_PRE}weixin_user u WHERE $condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = $db->query("SELECT u.username,u.nickname,u.sex,u.city,u.province,u.country,u.language,u.headimgurl,c.* FROM {$DT_PRE}weixin_chat c,{$DT_PRE}weixin_user u WHERE $condition ORDER BY c.itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['adddate'] = timetodate($r['addtime'], 5);
			$r['headimgurl'] or $r['headimgurl'] = 'api/weixin/image/headimg.jpg';
			$r['msg'] = weixin_msg($r['type'], $r['content'], $r['misc']);
			$lists[] = $r;
		}
		include tpl('weixin', $module);
	break;
}

function weixin_msg($type, $content, $misc) {
	$misc = $misc ? unserialize($misc) : array();
	switch($type) {
		case 'image':
			return '<a href="'.$content.'" target="_blank"><img src="'.$content.'" onload="if(this.width>200) this.width=200;" onerror="this.src=\'api/weixin/image/media_image_error.gif\';" style="border:#CCCCCC 1px solid;padding:2px;"/></a>';
		break;
		case 'voice':
			return '<a href="javascript:Dwidget(\'api/weixin/media.php?action='.$type.'&mediaid='.$misc['MediaId'].'\', \'播放语音 - QuickTime Player\', 300, 16, \'no\');"><img src="api/weixin/image/media_voice.gif" align="absmiddle"/></a>&nbsp;&nbsp;<a href="api/weixin/down.php?mediaid='.$misc['MediaId'].'" class="t">下载</a>';
		break;
		case 'video':
			return '<a href="javascript:Dwidget(\'api/weixin/media.php?action='.$type.'&mediaid='.$misc['MediaId'].'\', \'播放视频 - QuickTime Player\', 300, 400, \'no\');"><img src="api/weixin/image/media_video.gif" align="absmiddle"/></a>&nbsp;&nbsp;<a href="api/weixin/down.php?mediaid='.$misc['MediaId'].'" class="t">下载</a>';
		break;
		case 'location':
			return '<img src="api/weixin/image/media_map_marker.gif" align="absmiddle"/> <a href="javascript:Dwidget(\'api/weixin/media.php?action='.$type.'&latitude='.$misc['Location_X'].'&longitude='.$misc['Location_Y'].'&zoom='.$misc['Scale'].'\', \'查看地图 - '.$content.'\', 450, 400, \'no\');" class="t">'.$content.'</a>';
		break;
		case 'link':
			return '<a href="'.$content.'" target="_blank" class="t">'.$misc['Title'].'</a>'.($misc['Description'] ? '<br/>'.$misc['Description'] : '');
		break;
		case 'reply':
			if($misc['type'] == 'image') {
				return weixin_msg($misc['type'], 'api/weixin/down.php?mediaid='.$content, '');
			} else if($misc['type'] == 'voice' || $misc['type'] == 'video') {
				return weixin_msg($misc['type'], '', serialize(array('MediaId'=>$content, '')));
			}
			if(preg_match_all("/([http|https]+)\:\/\/([a-z0-9\/\-\_\.\,\?\&\#\=\%\+\;]{4,})/i", $content, $m)) {
				foreach($m[0] as $u) {
					if(preg_match("/^(jpg|jpeg|gif|png|bmp)$/i", file_ext($u)) && !preg_match("/([\?\&\=]{1,})/i", $u)) {
						$content = str_replace($u, '<a href="'.$u.'" target="_blank"><img src="'.$u.'" onload="if(this.width>200) this.width=200;" onerror="this.src=\'api/weixin/image/media_image_error.gif\';" style="border:#CCCCCC 1px solid;padding:2px;"/></a>', $content);
					} else {
						$content = str_replace($u, '<a href="'.$u.'" target="_blank" class="t">'.$u.'</a>', $content);
					}
				}
			}
			return $content;
		break;
		case 'CLICK':
			$E = array(
				'V_member' => '绑定会员',
			);
			return isset($E[$content]) ? $E[$content] : $content;
		break;
		case 'VIEW':
			return '<a href="'.$content.'" target="_blank" class="t">打开链接</a>';
		break;
		case 'LOCATION':
			return '<a href="javascript:Dwidget(\'api/weixin/media.php?action=location&latitude='.$misc['Latitude'].'&longitude='.$misc['Longitude'].'\', \'查看地图\', 450, 400, \'no\');"><img src="api/weixin/image/media_map.gif" align="absmiddle"/></a>';
		break;
		default:
			return $content;
		break;
	}
}
?>