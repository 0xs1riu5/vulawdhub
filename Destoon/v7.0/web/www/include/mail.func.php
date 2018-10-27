<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function dmail($mail_to, $mail_subject, $mail_body, $mail_from = '', $mail_sign = true) {
	global $DT;
	if($DT['mail_type'] == 'close') return 'close';
	if($DT['mail_sign'] && $mail_sign) $mail_body .= $DT['mail_sign'];
	if($DT['mail_type'] == 'sc') {
		$url = 'http://sendcloud.sohu.com/webapi/mail.send.json';
		$par = 'api_user='.$DT['smtp_user'].'&api_key='.$DT['smtp_pass'].'&from='.$DT['mail_sender'].'&fromname='.$DT['mail_name'].'&to='.$mail_to.'&subject='.$mail_subject.'&html='.$mail_body;
		$rec = dcurl($url, $par);
		$arr = json_decode($rec, true);
		if($arr['message'] == 'success') return 'SUCCESS';		
		$errmsg = '';
		foreach($arr['errors'] as $v) {
			$errmsg .= $v."\n";
		}
		$errmsg = trim($errmsg);
		if(defined('TESTMAIL')) dalert('Error:'.$errmsg);
		log_write($errmsg, 'sendcloud');
		return $errmsg;
	} else {
		$sendmail_from = $mail_from ? $mail_from : $DT['mail_sender'];
		$mail_from = "=?".strtolower(DT_CHARSET)."?B?".base64_encode($DT['mail_name'] ? $DT['mail_name'] : $DT['sitename'])."?= <".$sendmail_from.">";
		$mail_subject = stripslashes($mail_subject);
		$mail_subject = str_replace("\r", '', str_replace("\n", '', $mail_subject));
		$mail_subject = "=?".strtolower(DT_CHARSET)."?B?".base64_encode($mail_subject)."?=";
		$mail_body = stripslashes($mail_body);
		$mail_body = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $mail_body)))))));
		$mail_dlmt = $DT['mail_delimiter'] == 1 ? "\r\n" : ($DT['mail_delimiter'] == 2 ? "\n" : "\r");
		$headers = '';
		$headers .= "From: $mail_from".$mail_dlmt;
		$headers .= "X-Priority: 3".$mail_dlmt;
		$headers .= "X-Mailer: Destoon".$mail_dlmt;
		$headers .= "MIME-Version: 1.0".$mail_dlmt;
		$headers .= "Content-type: text/html; charset=".DT_CHARSET.$mail_dlmt;
		$headers .= "Content-Transfer-Encoding: base64".$mail_dlmt;
	}
	if($DT['mail_type'] == 'smtp') {
		$host = $DT['smtp_host'].':'.$DT['smtp_port'].' ';
		if(!$fp = fsockopen($DT['smtp_host'], $DT['smtp_port'], $errno, $errstr, 30)) {
			$errmsg = $host.'can not connect to the SMTP server';
			if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
			log_write($errmsg, 'smtp');
			return $errmsg;
		}
		stream_set_blocking($fp, true);
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != '220') {
			$errmsg = $host.$RE;
			if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
			log_write($errmsg, 'smtp');
			return $errmsg;
		}
		fputs($fp, ($DT['smtp_auth'] ? 'EHLO' : 'HELO')." Destoon\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 220 && substr($RE, 0, 3) != 250) {
			$errmsg = $host.'HELO/EHLO - '.$RE;
			if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
			log_write($errmsg, 'smtp');
			return $errmsg;
		}
		while(1) {
			if(substr($RE, 3, 1) != '-' || empty($RE)) break;
			$RE = fgets($fp, 512);
		}
		if($DT['smtp_auth']) {
			fputs($fp, "AUTH LOGIN\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 334) {
				$errmsg = $host.'AUTH LOGIN - '.$RE;
				if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
				log_write($errmsg, 'smtp');
				return $errmsg;
			}
			fputs($fp, base64_encode($DT['smtp_user'])."\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 334) {
				$errmsg = $host.'USERNAME - '.$RE;
				if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
				log_write($errmsg, 'smtp');
				return $errmsg;
			}
			fputs($fp, base64_encode($DT['smtp_pass'])."\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 235) {
				$errmsg = $host.'PASSWORD - '.$RE;
				if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
				log_write($errmsg, 'smtp');
				return $errmsg;
			}
			$mail_from = strpos($DT['smtp_user'], '@') !== false ? $DT['smtp_user'] : $DT['mail_sender'];
		} else {
			$mail_from = $DT['mail_sender'];
		}
		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $mail_from).">\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 250) {
			fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $mail_from).">\r\n");
			$RE = fgets($fp, 512);
			if(substr($RE, 0, 3) != 250) {
				$errmsg = $host.'MAIL FROM - '.$RE;
				if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
				log_write($errmsg, 'smtp');
				return $errmsg;
			}
		}
		foreach(explode(',', $mail_to) as $touser) {
			$touser = trim($touser);
			if($touser) {
				fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
				$RE = fgets($fp, 512);
				if(substr($RE, 0, 3) != 250) {
					fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
					$RE = fgets($fp, 512);
					$errmsg = $host.'RCPT TO - '.$RE;
					if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
					log_write($errmsg, 'smtp');
					return $errmsg;
				}
			}
		}
		fputs($fp, "DATA\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 354) {
			$errmsg = $host.'DATA - '.$RE;
			if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
			log_write($errmsg, 'smtp');
			return $errmsg;
		}
		list($msec, $sec) = explode(' ', microtime());
		$headers .= "Message-ID: <".date('YmdHis', $sec).".".($msec*1000000).".".substr($mail_from, strpos($mail_from,'@')).">".$mail_dlmt;
		fputs($fp, "Date: ".date('r')."\r\n");
		fputs($fp, "To: ".$mail_to."\r\n");
		fputs($fp, "Subject: ".$mail_subject."\r\n");
		fputs($fp, $headers."\r\n");
		fputs($fp, "\r\n\r\n");
		fputs($fp, "$mail_body\r\n.\r\n");
		$RE = fgets($fp, 512);
		if(substr($RE, 0, 3) != 250) {
			$errmsg = $host.'END - '.$RE;
			if(defined('TESTMAIL')) dalert('Error:'.trim($errmsg));
			log_write($errmsg, 'smtp');
			return $errmsg;
		}
		fputs($fp, "QUIT\r\n");
		return 'SUCCESS';
	} else {
		if($DT['mail_type'] != 'mail') {
			ini_set('SMTP', $DT['smtp_host']);
			ini_set('smtp_port', $DT['smtp_port']);
			ini_set('sendmail_from', $sendmail_from);
		}
		return @mail($mail_to, $mail_subject, $mail_body, $headers) ? 'SUCCESS' : '';
	}
}
?>