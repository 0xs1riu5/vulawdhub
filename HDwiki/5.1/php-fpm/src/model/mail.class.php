<?php 
!defined('IN_HDWIKI') && exit('Access Denied');

define('MAIL_RETRY', 5);

class mailmodel {

	var $db;
	var $base;

	function mailmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function get_total_num() {
		return $this->db->fetch_total('mailqueue');
	}
	
	function add($to_uids=array(), $to_emails=array(), $subject, $message, $frommail = '', $html = 1, $priority = 1) {
		if($priority) {
			$sql = "INSERT INTO ".DB_TABLEPRE."mailqueue (touid, tomail, subject, message, frommail, html, priority, time, failures) VALUES ";
			$values_arr = array();
			foreach($to_uids as $uid) {
				if(empty($uid)) continue;
				$values_arr[] = "('{$uid}', '', '{$subject}', '{$message}', '{$frommail}', '{$html}', '{$priority}', '{$this->base->time}', '0')";
			}
			foreach($to_emails as $email) {
				if(empty($email)) continue;
				$values_arr[] = "('', '{$email}', '{$subject}', '{$message}', '{$frommail}', '{$html}', '{$priority}', '{$this->base->time}', '0')";
			}
			$sql .= implode(',', $values_arr);
			$this->db->query($sql);
			$insert_id = $this->db->insert_id();
			$insert_id && @touch(HDWIKI_ROOT.'/data/mail.exists');
			return $insert_id;
		} else {
			$mail['email_to'] = array();
			if(!empty($to_uids)) {
				$uids = 0;
				foreach($to_uids as $uid) {
					if(empty($uid)) continue;
					$uids .= ','.$uid;
				}
				$query = $this->db->query("SELECT uid, username, email FROM ".DB_TABLEPRE."user WHERE uid IN ($uids)");
				while($v = $this->db->fetch_array($query)) {
					$mail['email_to'][] = $v['username'].'<'.$v['email'].'>';
				}
			}
			foreach($to_emails as $email) {
				if(empty($email)) continue;
				$mail['email_to'][] = $email;
			}
			$mail['subject']	= $subject;
			$mail['frommail']	= $frommail;
			$mail['html']		= $html;
			$mail['priority'] 	= 0;
			$mail['message'] 	= str_replace('\"', '"', $message);
			$mail['email_to'] 	= implode(',', $mail['email_to']);
			return $this->send_one_mail($mail);
		}
	}

	function send() {
		register_shutdown_function(array($this, '_send'));
	}

	function _send() {

		$mail = $this->_get_mail();
		if(empty($mail)) {
			@unlink(HDWIKI_ROOT.'/data/mail.exists');
			return NULL;
		} else {
			$mail['email_to'] = $mail['tomail'] ? $mail['tomail'] : $mail['username'].'<'.$mail['email'].'>';
			if($this->send_one_mail($mail)) {
				$this->_delete_one_mail($mail['id']);
				return true;
			} else {
				$this->_update_failures($mail['id']);
				return false;
			}
		}

	}

	function send_by_id($id) {
		if ($this->send_one_mail($this->_get_mail_by_id($id))) {
			$this->_delete_one_mail($id);
			return true;
		}
	}
	
	function send_one_mail($mail) {
		if(empty($mail)) return;
		$mail['email_to'] = $mail['email_to'] ? $mail['email_to'] : $mail['username'].'<'.$mail['email'].'>';
		$mail_setting = unserialize($this->base->setting['mail_config']);
		return include HDWIKI_ROOT.'/lib/sendmail.inc.php';
	}
	
	function reset_failures() {
		return $this->db->query("UPDATE ".DB_TABLEPRE."mailqueue SET failures=0");
	}

	function _get_mail() {
		return $this->db->fetch_first("SELECT m.*, u.username, u.email FROM ".DB_TABLEPRE."mailqueue m LEFT JOIN ".DB_TABLEPRE."user u ON m.touid=u.uid WHERE failures<'".MAIL_RETRY."' ORDER BY m.priority DESC, m.id ASC LIMIT 1");
	}

	function _get_mail_by_id($id) {
		return $this->db->fetch_first("SELECT m.*, u.username, u.email FROM ".DB_TABLEPRE."mailqueue m LEFT JOIN ".DB_TABLEPRE."user u ON m.touid=u.uid WHERE m.id='$id'");;
	}

	function _delete_one_mail($id) {
		$id = intval($id);
		return $this->db->query("DELETE FROM ".DB_TABLEPRE."mailqueue WHERE id='$id'");
	}

	function _update_failures($id) {
		$id = intval($id);
		return $this->db->query("UPDATE ".DB_TABLEPRE."mailqueue SET failures=failures+1 WHERE id='$id'");
	}
}

?>