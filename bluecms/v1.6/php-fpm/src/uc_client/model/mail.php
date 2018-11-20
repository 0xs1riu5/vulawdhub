<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: mail.php 848 2008-12-08 05:43:39Z zhaoxiongfei $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_MAIL_REPEAT', 5);	//note 邮件发送失败重复次数

class mailmodel {

	var $db;
	var $base;
	var $apps;

	function __construct(&$base) {
		$this->mailmodel($base);
	}

	function mailmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->apps = &$this->base->cache['apps'];
	}

	/**
	 * 统计通知的总条数
	 *
	 */
	function get_total_num() {
		$data = $this->db->result_first("SELECT COUNT(*) FROM ".UC_DBTABLEPRE."mailqueue");
		return $data;
	}

	/**
	 * Enter 得到邮件列表
	 *
	 * @param int	$page
	 * @param int	$ppp
	 * @param int	$totalnum
	 * @return array 结果集
	 */
	function get_list($page, $ppp, $totalnum) {
		$start = $this->base->page_get_start($page, $ppp, $totalnum);
		$data = $this->db->fetch_all("SELECT m.*, u.username, u.email FROM ".UC_DBTABLEPRE."mailqueue m LEFT JOIN ".UC_DBTABLEPRE."members u ON m.touid=u.uid ORDER BY dateline DESC LIMIT $start, $ppp");
		foreach((array)$data as $k => $v) {
			$data[$k]['subject'] = htmlspecialchars($v['subject']);
			$data[$k]['tomail'] = empty($v['tomail']) ? $v['email'] : $v['tomail'];
			$data[$k]['dateline'] = $v['dateline'] ? $this->base->date($data[$k]['dateline']) : '';
			$data[$k]['appname'] = $this->base->cache['apps'][$v['appid']]['name'];
		}
		return $data;
	}

	/**
	 * 删除邮件通过ids
	 *
	 * @param string/array $ids
	 * @return 受影响的行数
	 */
	function delete_mail($ids) {
		$ids = $this->base->implode($ids);
		$this->db->query("DELETE FROM ".UC_DBTABLEPRE."mailqueue WHERE mailid IN ($ids)");
		return $this->db->affected_rows();
	}

	/**
	 * 添加邮件列表
	 *
	 * @param string 操作
	 * @param string getdata
	 * @param string postdata
	 * @param array appids 指定通知的 APPID
	 * @param int pri 优先级，值越大表示越高
	 * @return int 插入的ID
	 */
	function add($mail) {
		//note 先入库
		if($mail['level']) {
			$sql = "INSERT INTO ".UC_DBTABLEPRE."mailqueue (touid, tomail, subject, message, frommail, charset, htmlon, level, dateline, failures, appid) VALUES ";
			$values_arr = array();
			foreach($mail['uids'] as $uid) {
				if(empty($uid)) continue;
				$values_arr[] = "('$uid', '', '$mail[subject]', '$mail[message]', '$mail[frommail]', '$mail[charset]', '$mail[htmlon]', '$mail[level]', '$mail[dateline]', '0', '$mail[appid]')";
			}
			foreach($mail['emails'] as $email) {
				if(empty($email)) continue;
				$values_arr[] = "('', '$email', '$mail[subject]', '$mail[message]', '$mail[frommail]', '$mail[charset]', '$mail[htmlon]', '$mail[level]', '$mail[dateline]', '0', '$mail[appid]')";
			}
			$sql .= implode(',', $values_arr);
			$this->db->query($sql);
			$insert_id = $this->db->insert_id();
			$insert_id && $this->db->query("REPLACE INTO ".UC_DBTABLEPRE."vars SET name='mailexists', value='1'");
			return $insert_id;
		} else {//note 直接发送
			$mail['email_to'] = array();
			$uids = 0;
			foreach($mail['uids'] as $uid) {
				if(empty($uid)) continue;
				$uids .= ','.$uid;
			}
			$users = $this->db->fetch_all("SELECT uid, username, email FROM ".UC_DBTABLEPRE."members WHERE uid IN ($uids)");
			foreach($users as $v) {
				$mail['email_to'][] = $v['username'].'<'.$v['email'].'>';
			}
			foreach($mail['emails'] as $email) {
				if(empty($email)) continue;
				$mail['email_to'][] = $email;
			}
			$mail['message'] = str_replace('\"', '"', $mail['message']);
			$mail['email_to'] = implode(',', $mail['email_to']);
			return $this->send_one_mail($mail);
		}
	}

	function send() {
		register_shutdown_function(array($this, '_send'));
	}

	function _send() {

		//note 查看是否有邮件
		$mail = $this->_get_mail();
		if(empty($mail)) {
			//note 标示为不需要发送邮件
			$this->db->query("REPLACE INTO ".UC_DBTABLEPRE."vars SET name='mailexists', value='0'");
			return NULL;
		} else {
			$mail['email_to'] = $mail['tomail'] ? $mail['tomail'] : $mail['username'].'<'.$mail['email'].'>';
			if($this->send_one_mail($mail)) {
				$this->_delete_one_mail($mail['mailid']);
				return true;
			} else {
				$this->_update_failures($mail['mailid']);
				return false;
			}
		}

	}

	function send_by_id($mailid) {
		if ($this->send_one_mail($this->_get_mail_by_id($mailid))) {
			$this->_delete_one_mail($mailid);
			return true;
		}
	}

	function send_one_mail($mail) {
		if(empty($mail)) return;
		$mail['email_to'] = $mail['email_to'] ? $mail['email_to'] : $mail['username'].'<'.$mail['email'].'>';
		$mail_setting = $this->base->settings;
		return include UC_ROOT.'lib/sendmail.inc.php';
	}

	function _get_mail() {
		$data = $this->db->fetch_first("SELECT m.*, u.username, u.email FROM ".UC_DBTABLEPRE."mailqueue m LEFT JOIN ".UC_DBTABLEPRE."members u ON m.touid=u.uid WHERE failures<'".UC_MAIL_REPEAT."' ORDER BY level DESC, mailid ASC LIMIT 1");
		return $data;
	}

	function _get_mail_by_id($mailid) {
		$data = $this->db->fetch_first("SELECT m.*, u.username, u.email FROM ".UC_DBTABLEPRE."mailqueue m LEFT JOIN ".UC_DBTABLEPRE."members u ON m.touid=u.uid WHERE mailid='$mailid'");
		return $data;
	}

	function _delete_one_mail($mailid) {
		$mailid = intval($mailid);
		return $this->db->query("DELETE FROM ".UC_DBTABLEPRE."mailqueue WHERE mailid='$mailid'");
	}

	function _update_failures($mailid) {
		$mailid = intval($mailid);
		return $this->db->query("UPDATE ".UC_DBTABLEPRE."mailqueue SET failures=failures+1 WHERE mailid='$mailid'");
	}
}

?>