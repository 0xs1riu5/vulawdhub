<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
array('信息统计', '?file='.$file),
array('统计报表', '?file='.$file.'&action=stats'),
);
switch($action) {
	case 'js':
		$db->halt = 0;
		$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');

		$num = $db->count($DT_PRE.'finance_charge', "status=0");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("charge").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'finance_cash', "status=0");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("cash").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'keyword', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("keyword").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'guestbook', "edittime=0");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("guestbook").innerHTML="'.$num.'";}catch(e){}';

		$num = $db->count($DT_PRE.'member_check', "1");//待审核资料修改
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("edit_check").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'ask', "status=0");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("ask").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'alert', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("alert").innerHTML="'.$num.'";}catch(e){}';		
		$num = $db->count($DT_PRE.'gift_order', "status='处理中'");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("gift").innerHTML="'.$num.'";}catch(e){}';

		$num = $db->count($DT_PRE.'news', "status=2");//待审核公司新闻
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("news").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'honor', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("honor").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'page', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("page").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'link', "status=2 AND username<>''");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("comlink").innerHTML="'.$num.'";}catch(e){}';

		foreach(array('company', 'truename', 'mobile', 'email') as $v) {
			$num = $db->count($DT_PRE.'validate', "type='$v' AND status=2");//待审核认证
			$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
			echo 'try{document.getElementById("v'.$v.'").innerHTML="'.$num.'";}catch(e){}';
		}

		$num = $db->count($DT_PRE.'ad', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("ad").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'spread', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("spread").innerHTML="'.$num.'";}catch(e){}'; 
		$num = $db->count($DT_PRE.'comment', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("comment").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'link', "status=2 AND username=''");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("link").innerHTML="'.$num.'";}catch(e){}';

		$num = $db->count($DT_PRE.'member');//会员
		echo 'try{document.getElementById("member").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'upgrade', "status=2");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("member_upgrade").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'member', "groupid=4");
		$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
		echo 'try{document.getElementById("member_check").innerHTML="'.$num.'";}catch(e){}';
		$num = $db->count($DT_PRE.'member', "regtime>$today");
		echo 'try{document.getElementById("member_new").innerHTML="'.$num.'";}catch(e){}';

		foreach($MODULE as $m) {
			if($m['moduleid'] < 5 || $m['islink']) continue;
			$mid = $m['moduleid'];
			$table = get_table($mid);
			$num = $db->count($table, '1');
			echo 'try{Dd("m_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';
			$num = $db->count($table, "status=3");
			echo 'try{Dd("m_'.$mid.'_1").innerHTML="'.$num.'";}catch(e){}';
			$num = $db->count($table, "status=2");
			$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
			echo 'try{Dd("m_'.$mid.'_2").innerHTML="'.$num.'";}catch(e){}';
			$num = $db->count($table, "addtime>$today");
			echo 'try{Dd("m_'.$mid.'_3").innerHTML="'.$num.'";}catch(e){}';

			if($m['module'] == 'mall' || $m['module'] == 'sell') {
				$num = $db->count($DT_PRE.'order', "mid=$mid");
				echo 'try{document.getElementById("order_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'order', "mid=$mid AND status=5");
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("order_'.$mid.'_5").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'order', "mid=$mid AND status=4");
				echo 'try{document.getElementById("order_'.$mid.'_4").innerHTML="'.$num.'";}catch(e){}';
			}

			if($m['module'] == 'group') {
				$num = $db->count($DT_PRE.'group_order_'.$mid, "1");
				echo 'try{document.getElementById("order_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'group_order_'.$mid, "status=4");
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("order_'.$mid.'_4").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'group_order_'.$mid, "status=3");
				echo 'try{document.getElementById("order_'.$mid.'_3").innerHTML="'.$num.'";}catch(e){}';
			}

			if($m['module'] == 'quote') {
				$num = $db->count($DT_PRE.'quote_product_'.$mid, "1");
				echo 'try{document.getElementById("product_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'quote_price_'.$mid, "1");
				echo 'try{document.getElementById("price_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'quote_price_'.$mid, "status=2");
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("price_'.$mid.'_2").innerHTML="'.$num.'";}catch(e){}';
			}

			if($m['module'] == 'exhibit') {
				$num = $db->count($DT_PRE.'exhibit_sign_'.$mid, "1");
				echo 'try{document.getElementById("sign_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'exhibit_sign_'.$mid, "addtime>$today");
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("sign_'.$mid.'_3").innerHTML="'.$num.'";}catch(e){}';
			}

			if($m['module'] == 'know') {
				$num = $db->count($DT_PRE.'know_expert_'.$mid, "1");
				echo 'try{document.getElementById("expert_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'know_answer_'.$mid, "1");
				echo 'try{document.getElementById("answer_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'know_answer_'.$mid, "status=2");
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("answer_'.$mid.'_2").innerHTML="'.$num.'";}catch(e){}';
			}

			if($m['module'] == 'job') {
				$table = $DT_PRE.'job_resume_'.$mid;
				//ALL
				$num = $db->count($table, '1');
				echo 'try{Dd("m_'.$mid.'_resume").innerHTML="'.$num.'";}catch(e){}';
				//PUB
				$num = $db->count($table, "status=3");
				echo 'try{Dd("m_'.$mid.'_resume_1").innerHTML="'.$num.'";}catch(e){}';
				//CHECK
				$num = $db->count($table, "status=2");
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{Dd("m_'.$mid.'_resume_2").innerHTML="'.$num.'";}catch(e){}';
				//NEW
				$num = $db->count($table, "addtime>$today", 30);
				echo 'try{Dd("m_'.$mid.'_resume_3").innerHTML="'.$num.'";}catch(e){}';
			}

			if($m['module'] == 'club') {
				$num = $db->count($DT_PRE.'club_group_'.$mid, "status=2");//商圈
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("club_group_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'club_reply_'.$mid, "status=2");//商圈回复
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("club_reply_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';

				$num = $db->count($DT_PRE.'club_fans_'.$mid, "status=2");//商圈粉丝
				$num = $num ? '<strong class=\"f_red\">'.$num.'</strong>' : 0;
				echo 'try{document.getElementById("club_fans_'.$mid.'").innerHTML="'.$num.'";}catch(e){}';
			}
		}
	break;
	case 'stats':
		$year = isset($year) ? intval($year) : date('Y', $DT_TIME);
		$year or $year = date('Y', $DT_TIME);
		$month = isset($month) ? intval($month) : 0;
		if($mid == 1 || $mid == 3) $mid = 0;
		if($mid == 4) $mid = 2;
		include tpl('count_stats');
	break;
	default:
		$year = isset($year) ? intval($year) : date('Y', $DT_TIME);
		$year or $year = date('Y', $DT_TIME);
		$month = isset($month) ? intval($month) : 0;
		if($mid == 1 || $mid == 3) $mid = 0;
		if($mid == 4) $mid = 2;
		include tpl('count');
	break;
}
?>