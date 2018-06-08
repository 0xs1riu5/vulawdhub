<?php !defined('IN_HDWIKI') && exit('Access Denied');

class noticemailmodel {
	var $base;
	var $db;
	var $mailtpl;

	function noticemailmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		if(empty($this->base->setting['noticemail'])) {
			$this->base->setting['noticemail'] = '';
		}
		$this->mailtpl = isset($this->base->setting['noticemailtpl']) ? unserialize($this->base->setting['noticemailtpl']) : array();
	}
	
	function doc_create(&$doc) {
		$noticemail_config = unserialize($this->base->setting['noticemail']);
		$mail_uids = array();
		if(!empty($noticemail_config['doc-create'])) {
			$doc_firstimg = $this->getfirstimg($doc['content']);
			$mail_subject = str_replace(
				array('_USERNAME_', '_DOCTITLE_'), 
				array($this->base->user['username'], $doc['title']), 
				addslashes($this->mailtpl['doc_create']['subject'])
			);
			$mail_message = str_replace(
				array('_DOCTITLE_', '_TIME_', '_SUMMARY_', '_URL_', '_FIRSTIMG_', '_SITENAME_'),
				array($doc['title'], $this->base->date($doc['time']), $doc['summary'], WIKI_URL.'/'.$this->base->setting['seo_prefix']."doc-view-".$doc['did'].$this->base->setting['seo_suffix'], $doc_firstimg, $this->base->setting['site_name']),
				addslashes($this->mailtpl['doc_create']['body'])
			);
			$to_users = $_ENV['user']->get_group_users($noticemail_config['doc-create'], 'uid');
			while($user = array_pop($to_users)) {
				$mail_uids[] = $user['uid'];
			}
		}
		if(count($mail_uids) > 0) {
			$this->base->load('mail');
			$_ENV['mail']->add($mail_uids, array(), $mail_subject, $mail_message);
		}
	}
	
	function doc_edit(&$doc) {
		$noticemail_config = unserialize($this->base->setting['noticemail']);
		$mail_uids = array();
		if(!empty($noticemail_config['doc-edit'])) {
			$doc_firstimg = $this->getfirstimg($doc['content']);
			$mail_subject = str_replace(
				array('_USERNAME_', '_DOCTITLE_'), 
				array($this->base->user['username'], addslashes($doc['title'])),
				addslashes($this->mailtpl['doc_edit']['subject'])
			);
			$mail_message = str_replace(
				array('_DOCTITLE_', '_TIME_', '_REASON_', '_URL_', '_FIRSTIMG_','_SITENAME_'),
				array(addslashes($doc['title']), $this->base->date($doc['time']), $doc['reason'], WIKI_URL.'/'.$this->base->setting['seo_prefix']."doc-view-".$doc['did'].$this->base->setting['seo_suffix'], $doc_firstimg, $this->base->setting['site_name']),
				addslashes($this->mailtpl['doc_edit']['body'])
			); 
			
			$to_groups = str_replace(array('CREATOR,', 'EDITORS,'), array(), $noticemail_config['doc-edit'].','); //去除两种“特殊”接收者，只保留Group ids
			$to_groups = substr($to_groups, 0, strlen($to_groups)-1);
			if($to_groups) {
				$to_groups = $_ENV['user']->get_group_users($to_groups, 'uid'); 		
				while($user = array_pop($to_groups)) {
					$mail_uids[] = $user['uid'];
				}
			}
			if(false !== strpos($noticemail_config['doc-edit'], 'CREATOR')) {
				$mail_uids[] = $doc['authorid'];
			}
			if(false !== strpos($noticemail_config['doc-edit'], 'EDITORS') && $doc['editions'] > 0) {
				$editors = $_ENV['doc']->get_recenteditor($doc['did'], 100);
				while($editor = array_pop($editors)) {
					$mail_uids[] = $editor['authorid'];
				}
			}
			$mail_uids = array_unique($mail_uids);
		}
		if(count($mail_uids) > 0) {
			$this->base->load('mail');
			$_ENV['mail']->add($mail_uids, array(), $mail_subject, $mail_message);
		}
	}
	
	function comment_add($did,$comment,$reply='',$anonymity=1) {
		$noticemail_config = unserialize($this->base->setting['noticemail']);
		$mail_uids = array();
		if(!empty($noticemail_config['comment_add'])) {
			$this->base->load('doc');
			$doc = $_ENV['doc']->get_doc($did);
			$doc_firstimg = $this->getfirstimg($doc['content']);			
			$mail_subject = str_replace(
				array('_USERNAME_', '_DOCTITLE_'), 
				array(($anonymity ? $this->base->view->lang['commentAnonymity'] : $this->base->user['username']), addslashes($doc['title'])),
				addslashes($this->mailtpl['comment_add']['subject'])
			);
			$mail_message = str_replace(
				array('_DOCTITLE_', '_TIME_', '_COMMENT_', '_REPLY_', '_URL_', '_FIRSTIMG_', '_SITENAME_'),
				array(addslashes($doc['title']), $this->base->date($doc['time']), $comment, $reply, WIKI_URL.'/'.$this->base->setting['seo_prefix']."comment-view-".$doc['did'].$this->base->setting['seo_suffix'], $doc_firstimg, $this->base->setting['site_name']),
				addslashes($this->mailtpl['comment_add']['body'])
			);
			$to_groups = str_replace(array('CREATOR,', 'EDITORS,', 'REVIEWERS,'), array(), $noticemail_config['comment_add'].',');
			$to_groups = substr($to_groups, 0, strlen($to_groups)-1);
			if($to_groups) {
				$to_groups = $_ENV['user']->get_group_users($to_groups, 'uid'); 		
				while($user = array_pop($to_groups)) {
					$mail_uids[] = $user['uid'];
				}
			}
			if(false !== strpos($noticemail_config['comment_add'], 'CREATOR')) {
				$mail_uids[] = $doc['authorid'];
			}
			if(false !== strpos($noticemail_config['comment_add'], 'EDITORS') && $doc['editions'] > 0) {
				$editors = $_ENV['doc']->get_recenteditor($doc['did'], 100);
				while($editor = array_pop($editors)) {
					$mail_uids[] = $editor['authorid'];
				}
			}
			if(false !== strpos($noticemail_config['comment_add'], 'REVIEWERS')) {
				$comments = $_ENV['comment']->search_comment(0, 100, '', $doc['did']);
				while($comment = array_pop($comments)) {
					if(0 != $comment['authorid']) { //匿名评论authorid = 0，不发送邮件
						$mail_uids[] = $comment['authorid'];
					}
				}
				$comments = null;
				unset($comments);
			}
			unset($doc);
			$mail_uids = array_unique($mail_uids);
		}
		if(count($mail_uids) > 0) {
			$this->base->load('mail');
			$_ENV['mail']->add($mail_uids, array(), $mail_subject, $mail_message);
		}
	}
	
	function getfirstimg(&$content) {
		$doc_firstimg = util::getfirstimg($content);
		if(!empty($doc_firstimg)) {
			if(stripos($doc_firstimg, "http") !== false) {
				$doc_firstimg = '<img src="'.$doc_firstimg.'" class="firstimg" alt="" />';
			} else {
				$doc_firstimg = '<img src="'.WIKI_URL.'/'.$doc_firstimg.'" class="firstimg" alt="" />';
			}
		}
		return $doc_firstimg;
	}
	
}