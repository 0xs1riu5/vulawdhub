<?php 
defined('IN_DESTOON') or exit('Access Denied');
if($DT_BOT) dhttp(403);
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$itemid or exit;
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid");
$item['status'] > 2 or exit;
if($action == 'best') {
	if(!$item) exit('0');
	$op = $op ? 1 : 0;
	$f = $op ? 'agree' : 'against';
	if(get_cookie('best_answer_'.$itemid)) exit('-1');
	$db->query("UPDATE {$table} SET `{$f}`=`{$f}`+1 WHERE itemid=$itemid");
	set_cookie('best_answer_'.$itemid, 1, $DT_TIME + 86400);
	exit('1');
}
$item or exit;
include load('misc.lang');
$linkurl = $MOD['linkurl'].$item['linkurl'];
$aid = isset($aid) ? intval($aid) : 0;
$aser = $aid ? $db->get_one("SELECT * FROM {$table_answer} WHERE itemid=$aid AND status=3") : array();
if($aser && $aser['qid'] != $itemid) exit;
$could_admin = $could_addition = $could_close = $_username && $_username == $item['username'];
if($item['process'] > 1) $could_addition = $could_close = false;
$could_answer = false;
switch($action) {
	case 'addition':
		if($could_addition) {
			$content = dhtmlspecialchars($content);
			$db->query("UPDATE {$table} SET addition='$content' WHERE itemid=$itemid");
			if($MOD['show_html']) tohtml('show', $module);
		}
		dalert('', $linkurl);
	break;
	case 'vote':
		$could_vote = $could_admin;
		if($item['process'] != 1) $could_vote = false;
		if($could_vote) {
			$items = $db->count($table_answer, "qid=$itemid AND status=3");
			if($items < 2) $could_vote = false;
		}
		if($could_vote) {
			$totime = $DT_TIME + $MOD['votedays']*86400;
			$db->query("UPDATE {$table} SET process=2,totime=$totime WHERE itemid=$itemid");
			if($MOD['show_html']) tohtml('show', $module);
		}
		dalert('', $linkurl);
	break;
	case 'vote_del':
		if($item['process'] != 2) dalert($L['vote_end']);
		$items = $db->count($table_answer, "qid=$itemid AND status=3");
		if($items < 3) dalert($L['min_answer']);
		if($aser['qid'] == $itemid) $db->query("DELETE FROM {$table_answer} WHERE itemid=$aid");
		dalert('', '', 'parent.window.location=parent.window.location;');
	break;
	case 'vote_add':
		$could_vote = check_group($_groupid, $MOD['group_vote']);
		if(get_cookie('answer_vote_'.$itemid)) $could_vote = false;
		if($could_vote) {
			if($_userid) {
				$v = $db->get_one("SELECT itemid FROM {$table_vote} WHERE qid=$itemid AND username='$_username'");
			} else {
				$v = $db->get_one("SELECT itemid FROM {$table_vote} WHERE qid=$itemid AND ip='$DT_IP' AND addtime>$DT_TIME-86400");
			}
		}
		if($v) $could_vote = false;
		set_cookie('answer_vote_'.$itemid, 1, $DT_TIME + 86400);
		if($could_vote) {
			$db->query("INSERT INTO {$table_vote} (qid,aid,username,passport,addtime,ip) VALUES ('$itemid','$aid','$_username','$_passport','$DT_TIME','$DT_IP')");
			$db->query("UPDATE {$table_answer} SET vote=vote+1 WHERE itemid=$aid");
			if($MOD['credit_vote'] && $_username) {
				$could_credit = true;
				if($MOD['credit_maxvote'] > 0) {					
					$r = $db->get_one("SELECT SUM(amount) AS total FROM {$DT_PRE}finance_credit WHERE username='$_username' AND addtime>$DT_TIME-86400  AND reason='".$L['vote_answer']."'");
					if($r['total'] > $MOD['credit_maxvote']) $could_credit = false;
				}
				if($could_credit) {
					credit_add($_username, $MOD['credit_vote']);
					credit_record($_username, $MOD['credit_vote'], 'system', $L['vote_answer'], 'ID:'.$itemid);
				}
			}
			dalert('', '', 'parent.window.location=parent.window.location;');
		} else {
			dalert($L['vote_reject'], '', 'parent.window.location=parent.window.location;');
		}
	break;
	case 'vote_show':
		if($item['process'] != 2) dalert($L['vote_end'], 'goback');
		$votes = array();
		$result = $db->query("SELECT * FROM {$table_answer} WHERE qid=$itemid AND status=3 ORDER BY itemid ASC");
		$total = 0;
		while($r = $db->fetch_array($result)) {
			$total += $r['vote'];
			$votes[] = $r;
		}
		foreach($votes as $k=>$v) {
			$votes[$k]['precent'] = $total ? dround($v['vote']*100/$total, 2, true).'%' : '1%';
		}
	break;
	case 'close':
		if($could_close) {
			$db->query("UPDATE {$table} SET process=0 WHERE itemid=$itemid");
			if($MOD['show_html']) tohtml('show', $module);
		}
		dalert('', $linkurl);
	break;
	case 'choose':
		$could_choose = $could_admin;
		if($item['process'] != 1) $could_choose = false;
		$aid = intval($aid);
		if(!$aid) $could_choose = false;
		if($could_choose) {
			$a = $db->get_one("SELECT * FROM {$table_answer} WHERE itemid=$aid AND qid=$itemid");
			if($a) {
				$content = dhtmlspecialchars($thx);
				$expert = $a['expert'] ? $a['username'] : '';
				if($expert) $db->query("UPDATE {$table_expert} SET best=best+1 WHERE username='$expert'");
				$db->query("UPDATE {$table} SET process=3,aid=$aid,expert='$expert',comment='$content',updatetime='$DT_TIME' WHERE itemid=$itemid");
				if($a['username']) {
					if($item['credit']) {
						credit_add($a['username'], $item['credit']);
						credit_record($a['username'], $item['credit'], 'system', lang($L['record_reward'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
					}
					if($MOD['credit_best']) {
						credit_add($a['username'], $MOD['credit_best']);
						credit_record($a['username'], $MOD['credit_best'], 'system', lang($L['record_best'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
					}
					$credit = intval($credit);
					if(in_array($credit, $CREDITS) && $credit > 1 && $credit <= $_credit) {
						credit_add($_username, -$credit);
						credit_record($_username, -$credit, 'system', lang($L['record_thank'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
						credit_add($a['username'], $credit);
						credit_record($a['username'], $credit, 'system', lang($L['record_thank'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
					}
				}
				if($MOD['show_html']) tohtml('show', $module);
			}
		}
		dalert('', $linkurl);
	break;
	case 'raise':
		$credit = intval($credit);
		if($credit < 1 || !in_array($credit, $CREDITS)) dalert($L['select_credit'], 'goback');
		if($credit > $_credit) dalert($L['lack_credit'], 'goback');
		$could_raise = $could_admin;
		if($item['process'] != 1) $could_raise = false;
		if($item['raise'] >= $MOD['maxraise'])  $could_raise = false;
		if($could_raise) {
			if($credit >= $MOD['raisecredit']) {
				$addtime = $DT_TIME;
				$totime = $DT_TIME + $MOD['overdays']*86400 + $MOD['raisedays']*86400;
			} else {
				$addtime = $item['addtime'];
				$totime = $item['totime'] + $MOD['raisedays']*86400;
			}
			$db->query("UPDATE {$table} SET credit=credit+$credit,raise=raise+1,addtime=$addtime,totime=$totime WHERE itemid=$itemid");
			credit_add($_username, -$credit);
			credit_record($_username, -$credit, 'system', lang($L['record_addto'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
			if($MOD['show_html']) tohtml('show', $module);
		}
		dalert('', $linkurl);
	break;
	default:
		$could_answer = check_group($_groupid, $MOD['group_answer']);
		if($item['process'] != 1 || $could_admin) $could_answer = false;
		if($MOD['answer_pagesize']) {
			$pagesize = $MOD['answer_pagesize'];
			$offset = ($page-1)*$pagesize;
		}
		$need_captcha = $MOD['captcha_answer'] == 2 ? $MG['captcha'] : $MOD['captcha_answer'];
		$need_question = $MOD['question_answer'] == 2 ? $MG['question'] : $MOD['question_answer'];
		if($could_answer && !$MOD['answer_repeat']) {
			if($_username) {
				$r = $db->get_one("SELECT itemid FROM {$table_answer} WHERE username='$_username' AND qid=$itemid");
			} else {
				$r = $db->get_one("SELECT itemid FROM {$table_answer} WHERE ip='$DT_IP' AND qid=$itemid AND addtime>$DT_TIME-86400");
			}
			if($r) $could_answer = false;
		}

		if($submit && $could_answer) {
			$msg = captcha($captcha, $need_captcha, true);
			if($msg) dalert($msg);
			$msg = question($answer, $need_question, true);
			if($msg) dalert($msg);
			$content = dhtmlspecialchars(strip_tags(trim($content)));
			if(!$content) dalert($L['type_answer']);
			$content = nl2br($content);
			is_url($url) or $url = '';
			$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_answer'];
			$status = get_status(3, $need_check);
			$hidden = isset($hidden) ? 1 : 0;
			$expert = 0;
			if($_username) {
				$t = $db->get_one("SELECT itemid FROM {$table_expert} WHERE username='$_username'");
				if($t) {
					$expert = 1;
					$db->query("UPDATE {$table_expert} SET answer=answer+1 WHERE username='$_username'");
				}
			}
			$db->query("INSERT INTO {$table_answer} (qid,url,content,username,passport,expert,addtime,ip,status,hidden) VALUES ('$itemid','$url','$content','$_username','$_passport','$expert','$DT_TIME','$DT_IP','$status','$hidden')");
			if($MOD['credit_answer'] && $_username && $status == 3) {
				$could_credit = true;
				if($MOD['credit_maxanswer'] > 0) {					
					$r = $db->get_one("SELECT SUM(amount) AS total FROM {$DT_PRE}finance_credit WHERE username='$_username' AND addtime>$DT_TIME-86400  AND reason='".$L['answer_question']."'");
					if($r['total'] >= $MOD['credit_maxanswer']) $could_credit = false;
				}
				if($could_credit) {
					credit_add($_username, $MOD['credit_answer']);
					credit_record($_username, $MOD['credit_answer'], 'system', $L['answer_question'], 'ID:'.$itemid);
				}
			}
			if($MOD['answer_message'] && $item['username']) {
				send_message($item['username'], lang($L['answer_msg_title'], array(dsubstr($item['title'], 20, '...'))), lang($L['answer_msg_content'], array($item['title'], stripslashes($content), $linkurl)));
			}
			if($status == 3) {
				$items = isset($items) ? intval($items)+1 : 1;
				$page = ceil($items/$pagesize);
				$forward = 'answer.php?itemid='.$itemid.'&page='.$page.'&rand='.mt_rand(10, 99).'#last';
				dalert('', '', 'parent.window.location="'.$forward.'";');
			} else {
				dalert($L['answer_check'], '', 'parent.window.location=parent.window.location;');
			}
		} else {
			$could_vote = check_group($_groupid, $MOD['group_vote']);
			if(get_cookie('answer_vote_'.$itemid)) $could_vote = false;
			$pages = '';
			$answers = array();
			$items = $db->count($table_answer, "qid=$itemid AND status=3 AND itemid!=$item[aid]");
			$a = $items;
			if($item['aid']) $a += 1;
			if($item['answer'] != $a) {
				$item['answer'] = $a;
				$db->query("UPDATE {$table} SET answer=$a WHERE itemid=$itemid");
			}
			if($item['process'] == 1 && $item['username'] && !$item['message'] && $MOD['messagedays']) {
				if($item['totime'] - $DT_TIME < $MOD['messagedays']*86400) {
					send_message($item['username'], lang($L['expired_msg_title'], array(dsubstr($item['title'], 20, '...'))), lang($L['expired_msg_content'], array($linkurl)));
					$db->query("UPDATE {$table} SET message=1 WHERE itemid=$itemid");
				}
			}
			if($DT_TIME > $item['totime']) {
				$reload = false;
				if($item['process'] == 1) {
					if($item['username'] && $MOD['credit_deal'] > 0) {
						credit_add($item['username'], -$MOD['credit_deal']);
						credit_record($item['username'], -$MOD['credit_deal'], 'system', lang($L['record_expired'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
					}
					if($item['answer'] > 1) {
						$totime = $DT_TIME + $MOD['votedays']*86400;
						$db->query("UPDATE {$table} SET process=2,totime=$totime,updatetime='$DT_TIME' WHERE itemid=$itemid");
					} else {
						$db->query("UPDATE {$table} SET process=0,updatetime='$DT_TIME' WHERE itemid=$itemid");
					}
					$reload = true;
				} else if($item['process'] == 2) {
					$a = $db->get_one("SELECT * FROM {$table_answer} WHERE qid=$itemid ORDER BY vote DESC");
					if($a && $a['vote'] > $MOD['minvote']) {
						$aid = intval($a['itemid']);
						$expert = $a['expert'] ? $a['username'] : '';
						if($expert) $db->query("UPDATE {$table_expert} SET best=best+1 WHERE username='$expert'");
						$db->query("UPDATE {$table} SET process=3,aid=$aid,expert='$expert',updatetime='$DT_TIME' WHERE itemid=$itemid");
						if($a['username']) {
							if($item['credit']) {
								credit_add($a['username'], $item['credit']);
								credit_record($a['username'], $item['credit'], 'system', lang($L['record_reward'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
							}
							if($MOD['credit_best']) {
								credit_add($a['username'], $MOD['credit_best']);
								credit_record($a['username'], $MOD['credit_best'], 'system', lang($L['record_best'], array($MODULE[$moduleid]['name'])), 'ID:'.$itemid);
							}
						}
					} else {
						$db->query("UPDATE {$table} SET process=0,updatetime='$DT_TIME' WHERE itemid=$itemid");
					}
					$reload = true;
				}
				if($reload) {
					if($MOD['show_html']) tohtml('show', $module);
					dalert('', '', 'top.window.location.reload();');
				}
			}
			$pages = pages($items, $page, $pagesize);
			$result = $db->query("SELECT * FROM {$table_answer} WHERE qid=$itemid AND status=3 ORDER BY itemid ASC LIMIT $offset,$pagesize");
			while($r = $db->fetch_array($result)) {
				if($r['itemid'] == $aid) continue;
				$answers[] = $r;
			}
			$head_title = $L['answer_question'].$DT['seo_delimiter'].$item['title'].$DT['seo_delimiter'].$MOD['name'];
		}
	break;
}
include template($MOD['template_answer'] ? $MOD['template_answer'] : 'answer', $module);
?>