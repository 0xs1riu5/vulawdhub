<?php
class smtp {
	var $smtp_port;
	var $time_out;
	var $host_name;
	var $log_file;
	var $relay_host;
	var $debug;
	var $auth;
	var $user;
	var $pass;
	var $sock;
	function smtp($relay_host="", $smtp_port=25,$auth=false,$user,$pass) {
		$this->debug = false;
		$this->smtp_port = $smtp_port;
		$this->relay_host = $relay_host;
		$this->time_out = 30; //is used in fsockopen()
		$this->auth = $auth;//auth
		$this->user = $user;
		$this->pass = $pass;
		$this->host_name = "localhost"; //is used in HELO command
		$this->log_file = "";
		$this->sock = false;
	}
	function sendmail($to, $from, $subject="", $body="", $mailtype, $cc="", $bcc="", $additional_headers="") {
		$mail_from = $this->get_address($this->strip_comment($from));
		echo $body = preg_replace("/(^|(\r\n))(\.)/", "\1.\3", $body);
		$header = "";
		$header .= "MIME-Version:1.0\r\n";
		if ($mailtype == "HTML") {
			$header .= "Content-Type:text/html\r\n";
		}
		$header .= "To: ".$to."\r\n";
		if ($cc != "") {
			$header .= "Cc: ".$cc."\r\n";
		}
		$header .= "From: $from<".$from.">\r\n";
		$header .= "Subject: ".$subject."\r\n";
		$header .= $additional_headers;
		$header .= "Date: ".date("r")."\r\n";
		$header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
		list($msec, $sec) = explode(" ", microtime());
		$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
		$TO = explode(",", $this->strip_comment($to));
		if ($cc!="") {
			$TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
		}
		if ($bcc != "") {
			$TO=array_merge($TO, explode(",", $this->strip_comment($bcc)));
		}
		$sent = TRUE;
		foreach ($TO as $rcpt_to) {
			$rcpt_to = $this->get_address($rcpt_to);
			if (!$this->smtp_sockopen($rcpt_to)) {
				$this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
				$sent = FALSE;
				continue;
			}
			if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) {
				$this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
			} else {
				$this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
				$sent = FALSE;
			}
			fclose($this->sock);
			$this->log_write("Disconnected from remote host\n");
		}
		return $sent;
	}
	function smtp_send($helo, $from, $to, $header, $body="") {
		if (!$this->smtp_putcmd("HELO", $helo)) {
			return $this->smtp_error("sending HELO command");
		}
		if($this->auth){
			if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user))) {
				return $this->smtp_error("sending HELO command");
			}
			if (!$this->smtp_putcmd("", base64_encode($this->pass))) {
				return $this->smtp_error("sending HELO command");
			}
		}
		if (!$this->smtp_putcmd("MAIL", "FROM:<".$from.">")) {
			return $this->smtp_error("sending MAIL FROM command");
		}
		if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) {
			return $this->smtp_error("sending RCPT TO command");
		}
		if (!$this->smtp_putcmd("DATA")) {
			return $this->smtp_error("sending DATA command");
		}
		if (!$this->smtp_message($header, $body)) {
			return $this->smtp_error("sending message");
		}
		if (!$this->smtp_eom()) {
			return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
		}
		if (!$this->smtp_putcmd("QUIT")) {
			return $this->smtp_error("sending QUIT command");
		}
		return true;
	}
	function smtp_sockopen($address) {
		if ($this->relay_host == "") {
			return $this->smtp_sockopen_mx($address);
		}
		else {
			return $this->smtp_sockopen_relay();
		}
	}
	function smtp_sockopen_relay() {
		$this->log_write("Trying to ".$this->relay_host.":".$this->smtp_port."\n");
		$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
		if (!($this->sock && $this->smtp_ok())) {
			$this->log_write("Error: Cannot connenct to relay host ".$this->relay_host."\n");
			$this->log_write("Error: ".$errstr." (".$errno.")\n");
			return false;
		}
		$this->log_write("Connected to relay host ".$this->relay_host."\n");
		return true;
	}
	function smtp_sockopen_mx($address) {
		echo $domain = preg_replace("/^.+@([^@]+)$/", "\1", $address);
		if (!@getmxrr($domain, $MXHOSTS)) {
			$this->log_write("Error: Cannot resolve MX \"".$domain."\"\n");
			return false;
		}
		foreach ($MXHOSTS as $host) {
			$this->log_write("Trying to ".$host.":".$this->smtp_port."\n");
			$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
			if (!($this->sock && $this->smtp_ok())) {
				$this->log_write("Warning: Cannot connect to mx host ".$host."\n");
				$this->log_write("Error: ".$errstr." (".$errno.")\n");
				continue;
			}
			$this->log_write("Connected to mx host ".$host."\n");
			return true;
		}
		$this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
		return false;
	}
	function smtp_message($header, $body) {
		fputs($this->sock, $header."\r\n".$body);
		$this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
		return true;
	}
	function smtp_eom() {
		fputs($this->sock, "\r\n.\r\n");
		$this->smtp_debug(". [EOM]\n");
		return $this->smtp_ok();
	}
	function smtp_ok() {
		$response = str_replace("\r\n", "", fgets($this->sock, 512));
		$this->smtp_debug($response."\n");
		if (!preg_match("/^[23]/", $response)) {
			fputs($this->sock, "QUIT\r\n");
			fgets($this->sock, 512);
			$this->log_write("Error: Remote host returned \"".$response."\"\n");
			return false;
		}
		return true;
	}
	function smtp_putcmd($cmd, $arg="") {
		if ($arg != "") {
			if($cmd=="") $cmd=$arg;
			else $cmd=$cmd." ".$arg;
		}
		fputs($this->sock, $cmd."\r\n");
		$this->smtp_debug("> ".$cmd."\n");
		return $this->smtp_ok();
	}
	function smtp_error($string) {
		$this->log_write("Error: Error occurred while ".$string.".\n");
		return false;
	}
	function log_write($message) {
		$this->smtp_debug($message);
		if ($this->log_file == "") {
			return true;
		}
		$message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
		if (!@file_exists($this->log_file) || !($fp=@fopen($this->log_file, "a"))) {
			$this->smtp_debug("Warning: Cannot open log file \"".$this->log_file."\"\n");
			return false;
		}
		flock($fp, LOCK_EX);
		fputs($fp, $message);
		fclose($fp);
		return true;
	}
	function strip_comment($address) {
		$comment = "/\([^()]*\)/";
		while (preg_match($comment, $address)) {
			$address = preg_replace($comment, "", $address);
		}
		return $address;
	}
	function get_address($address) {
		$address = preg_replace("/([ \t\r\n])+/", "", $address);
		$address = preg_replace("/^.*<(.+)>.*$/", "\1", $address);
		return $address;
	}
	function smtp_debug($message) {
		if ($this->debug) {
			echo $message;
		}
	}
}

/*
这是一个测试程序!!!
请按照说明设置好以下的参数,以下是以tom.com的用户为例设置好的.
*/

$smtpserver = 'smtp.163.com';//SMTP服务器
$smtpserverport = 25;//SMTP服务器端口
$smtpuser = 'koyshe@163.com';//SMTP服务器的用户帐号
$smtppass = 'yongyuan511218';//SMTP服务器的用户密码
$smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.

/*
$email_touser = "3110914@qq.com";//发送给谁
$email_user = "koyshe@163.net";//发送人
$email_name = "欢迎注册为 '爱上灵宝网(23lb.com)' 会员";//邮件主题
$email_text = "<p>以下是23lb的注册激活链接，有效期为7天</p><a href='http://23lb.com/index.php?mod=user&act=emailactive&email={$email_touser}&user_name=&user_mark=' target='_blank'>点此激活</a>";//邮件内容
$email_type = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件

//$smtp->debug = TRUE;//是否显示发送的调试信息
$smtp->sendmail($email_touser, $email_user, $email_name, $email_text, $email_type);
*/
?>