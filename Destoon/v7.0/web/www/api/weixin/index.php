<?php
require '../../common.inc.php';
require DT_ROOT.'/api/weixin/init.inc.php';
if($wx->signature()) {
	isset($HTTP_RAW_POST_DATA) or $HTTP_RAW_POST_DATA = file_get_contents("php://input");
	if($HTTP_RAW_POST_DATA) {
		if(function_exists('libxml_disable_entity_loader')) libxml_disable_entity_loader(true);
		$x = simplexml_load_string($HTTP_RAW_POST_DATA, 'SimpleXMLElement', LIBXML_NOCDATA);
		$ToUserName = $x->ToUserName;
		$FromUserName = $x->FromUserName;
		$CreateTime = $x->CreateTime;
		$MsgType = $x->MsgType;
		$credit_add = 0;
		$post = array();
		$post['openid'] = $FromUserName;
		$post['addtime'] = $DT_TIME;//$CreateTime;
		$post['type'] = $MsgType;
		$post['misc'] = array();
		if($MsgType == 'event') {//事件
			$Event = $x->Event;
			$post['type'] = $Event;
			$post['event'] = 1;
			$EventKey = $x->EventKey;			
			switch($Event) {
				case 'CLICK'://点击菜单拉取消息时的事件推送
					switch($EventKey) {
						case 'V_member'://绑定会员
							$post['content'] = $EventKey;
							//回复图文消息
							$misc = $tags = array();
							$tags['title'] = '会员中心';
							$tags['description'] = $WX['bind'];
							$tags['picurl'] = DT_PATH.'api/weixin/image/top_bind.jpg';
							$tags['url'] = DT_MOB.'api/weixin.php?action=member&auth='.encrypt("$FromUserName", DT_KEY.'WXID');
							$misc[] = $tags;						
							$wx->response($FromUserName, $ToUserName, 'news', '', $misc);
						break;
						default:
							if(substr($EventKey, 0, 5) == 'V_mid') {
								$mid = intval(substr($EventKey, 5));
								$mod = $MODULE[$mid]['module'];
								if(in_array($mod, array('article', 'brand', 'club', 'down', 'exhibit', 'group', 'job', 'know', 'photo', 'quote', 'special', 'video'))) {
									$post['content'] = $EventKey;
									$misc = array();
									$result = $db->query("SELECT itemid,title,thumb,linkurl,level FROM ".get_table($mid)." WHERE status=3 AND thumb<>'' AND level=2 ORDER BY addtime DESC LIMIT 1");
									while($r = $db->fetch_array($result)) {
										$tags = array();
										$tags['title'] = $r['title'];
										$tags['description'] = '';
										$tags['picurl'] = $r['thumb'];
										$tags['url'] = strpos($r['linkurl'], '://') === false ? $MODULE[$mid]['mobile'].$r['linkurl'] : $r['linkurl'];
										$misc[] = $tags;
									}
									if($misc) {
										$result = $db->query("SELECT itemid,title,thumb,linkurl,level FROM ".get_table($mid)." WHERE status=3 AND thumb<>'' AND level=1 ORDER BY addtime DESC LIMIT 3");
										while($r = $db->fetch_array($result)) {
											$tags = array();
											$tags['title'] = $r['title'];
											$tags['description'] = '';
											$tags['picurl'] = $r['thumb'];
											$tags['url'] = strpos($r['linkurl'], '://') === false ? $MODULE[$mid]['mobile'].$r['linkurl'] : $r['linkurl'];
											$misc[] = $tags;
										}					
										$wx->response($FromUserName, $ToUserName, 'news', '', $misc);
									}
								} else if(in_array($mod, array('sell', 'buy', 'info', 'mall'))) {
									$post['content'] = $EventKey;
									$misc = array();
									$result = $db->query("SELECT itemid,title,thumb,linkurl,level FROM ".get_table($mid)." WHERE status=3 AND thumb<>'' AND level>0 ORDER BY addtime DESC LIMIT 4");
									while($r = $db->fetch_array($result)) {
										$tags = array();
										$tags['title'] = $r['title'];
										$tags['description'] = '';
										$tags['picurl'] = str_replace('.thumb.', '.middle.', $r['thumb']);
										$tags['url'] = strpos($r['linkurl'], '://') === false ? $MODULE[$mid]['mobile'].$r['linkurl'] : $r['linkurl'];
										$misc[] = $tags;
									}					
									$wx->response($FromUserName, $ToUserName, 'news', '', $misc);
								}
							}
						break;
					}
				break;
				case 'subscribe'://订阅
					if(strlen($EventKey) > 0) {//扫描二维码关注
						$post['content'] = $EventKey;
						$post['misc']['Ticket'] = "$x->Ticket";
					} else {//普通关注
						$post['content'] = '';
					}
					$user = weixin_user($FromUserName);
					$info = $wx->get_user($FromUserName);
					$stime = intval($info['subscribe_time']);
					$stime > 0 or $stime = $DT_TIME;
					$sql = "subscribe=1,addtime=$stime,edittime=$DT_TIME,visittime=$DT_TIME";
					foreach(array('nickname', 'sex', 'city', 'province', 'country', 'language', 'headimgurl') as $v) {
						if(isset($info[$v])) $sql .= ",".$v."='".addslashes($info[$v])."'";
					}
					if($user) {
						$db->query("UPDATE {$DT_PRE}weixin_user SET $sql WHERE openid='$FromUserName'");
					} else {
						$sql .= ",openid='$FromUserName'";
						$db->query("INSERT INTO {$DT_PRE}weixin_user SET $sql");
					}
					if(strpos($post['content'], 'qrscene_') !== false) {
						$sid = intval(substr($post['content'], 8));
						$B = $db->get_one("SELECT * FROM {$DT_PRE}weixin_bind WHERE sid='$sid'");
						if($B) {
							if($DT_TIME - $B['addtime'] < 1800 && check_name($B['username'])) weixin_bind($FromUserName, $B['username']);
							$db->query("DELETE FROM {$DT_PRE}weixin_bind WHERE sid='$sid'");
						}
					}
					//回复欢迎消息
					$wx->response($FromUserName, $ToUserName, 'text', $WX['welcome']);
				break;
				case 'unsubscribe'://取消订阅
					$post['content'] = '';
					$db->query("UPDATE {$DT_PRE}weixin_user SET subscribe=0,username='',edittime=$DT_TIME WHERE openid='$FromUserName'");
				break;
				case 'SCAN'://扫描二维码[已关注]
					$post['content'] = $EventKey;
					$post['misc']['Ticket'] = "$x->Ticket";
					if($EventKey == '99999') $credit_add = 1;
					if(preg_match("/^[1-9]{9}$/", $EventKey)) {//已关注未绑定
						$sid = intval($EventKey);
						$B = $db->get_one("SELECT * FROM {$DT_PRE}weixin_bind WHERE sid='$sid'");
						if($B) {
							if($DT_TIME - $B['addtime'] < 1800 && check_name($B['username'])) weixin_bind($FromUserName, $B['username']);
							$db->query("DELETE FROM {$DT_PRE}weixin_bind WHERE sid='$sid'");
							//回复欢迎消息
							$wx->response($FromUserName, $ToUserName, 'text', '恭喜！您的会员名['.$B['username'].']已经成功与微信绑定');
						}
					}
				break;
				case 'LOCATION'://上报地理位置事件
					$post['content'] = '';
					$post['misc']['Latitude'] = "$x->Latitude";
					$post['misc']['Longitude'] = "$x->Longitude";
					$post['misc']['Precision'] = "$x->Precision";
				break;
				case 'VIEW'://点击菜单跳转链接时的事件推送
					$post['content'] = $EventKey;
				break;
				default:
				break;
			}
		} else {//消息
			switch($MsgType) {
				case 'text'://文本消息
					$Content = "$x->Content";
					$post['content'] = $Content;
					if($Content == '签到') {
						$credit_add = 1;
					} else {
						//自动回复
						$t = $db->get_one("SELECT * FROM {$DT_PRE}weixin_auto WHERE keyword='$Content'");
						if($t) {
							$wx->response($FromUserName, $ToUserName, 'text', $t['reply']);
						} else if($WX['auto']) {
							$wx->response($FromUserName, $ToUserName, 'text', $WX['auto']);
						}
					}
				break;
				case 'image'://图片消息
					$post['content'] = $x->PicUrl;
					$post['misc']['MediaId'] = "$x->MediaId";
				break;
				case 'voice'://语音消息
					$post['content'] = '';
					$post['misc']['Format'] = "$x->Format";
					$post['misc']['MediaId'] = "$x->MediaId";
				break;
				case 'video'://视频消息
					$post['content'] = '';
					$post['misc']['ThumbMediaId'] = "$x->ThumbMediaId";
					$post['misc']['MediaId'] = "$x->MediaId";
				break;
				case 'location'://地理位置消息
					$post['content'] = "$x->Label";
					$post['misc']['Location_X'] = "$x->Location_X";
					$post['misc']['Location_Y'] = "$x->Location_Y";
					$post['misc']['Scale'] = "$x->Scale";
				break;
				case 'link'://链接消息
					$post['content'] = $x->Url;
					$post['misc']['Title'] = "$x->Title";
					$post['misc']['Description'] = "$x->Description";
				break;
				default:
				break;
			}
		}
		if(isset($post['content'])) {
			$post['misc'] = $post['misc'] ? serialize($post['misc']) : '';
			$post = daddslashes($post);
			$sql = '';
			foreach($post as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$db->query("INSERT INTO {$DT_PRE}weixin_chat SET ".substr($sql, 1));
		}
		if($credit_add && $WX['credit']) {//签到送积分
			$credit = intval($WX['credit']);
			$user = weixin_user($FromUserName);
			if($user['credittime'] < 1) $user['credittime'] = 1;
			$msg = '欢迎回来，今日已签到，请继续使用其他服务';
			if($credit && $user && $user['username'] && timetodate($DT_TIME, 3) != timetodate($user['credittime'], 3)) {
				require_once DT_ROOT.'/include/module.func.php';
				credit_add($user['username'], $credit);
				credit_record($user['username'], $credit, 'system', '微信签到');
				$db->query("UPDATE {$DT_PRE}weixin_user SET credittime=$DT_TIME WHERE itemid=$user[itemid]");
				$msg = '签到成功，已赠送您'.$credit.$DT['credit_name'];
			}
			$wx->response($FromUserName, $ToUserName, 'text', $msg);
		}
		$db->query("UPDATE {$DT_PRE}weixin_user SET visittime=$DT_TIME WHERE openid='$FromUserName'");
	} else {
		echo $_GET["echostr"];
	}
} else {
	echo DT_DEBUG ? 'Working...' : '<meta http-equiv="refresh" content="0;url=../">';
}
?>