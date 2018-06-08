<?php
include('mail/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
if ( ! function_exists('jmailsend'))
{
	function jmailsend($from,$fromname,$to,$title,$body,$usename,$usepassword,$smtp,$repto,$repname)
	{
		$mail             = new PHPMailer();
		//$mail->SMTPDebug  = 3;
		
		$mail->CharSet    = "UTF-8"; // charset
		$mail->Encoding   = "base64";

		$mail->IsSMTP(); // telling the class to use SMTP
		
		//system
		if(stripos($smtp,'.gmail.com')===false){
			$mail->Port       = 25;
			$mail->Host       = $smtp; // SMTP server
		}
		else{
			$mail->Port       = 465;
			$mail->Host       = $smtp; // SMTP server
			$mail->SMTPSecure = "ssl";
			//$mail->Host       = 'ssl://'.$smtp; // SMTP server
		}

		$mail->SMTPAuth   = true;
		$mail->Username   = $usename; // SMTP account username
		$mail->Password   = $usepassword;        // SMTP account password

		$mail->From       = $from;//send email
		$mail->FromName   = $fromname; //name of send

		//repet
		if($repto!=""){
			$name = isset($repname)?$repname:$repto;
			$mail->AddReplyTo($repto, $name);
		}
		$mail->WordWrap   = 50; // line 
		
		//title
		$mail->Subject		= (isset($title)) ? $title : '';//title

		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //

		//body
		$body             = eregi_replace("[\]",'',$body);
		$mail->MsgHTML($body);
        
		//to
		if($to)
		{
			$address = explode("|",$to);
			foreach($address AS $key => $val)
			{
				$mail->AddAddress($val, "");
			}
		}
		//send attech
		//if(isset($data['attach']))
		//{
			//$attach = explode("|",$data['attach']);
			//foreach($attach AS $key => $val)
			//{
				//$mail->AddAttachment($val,"");             // attech
			//}			
		//}
		if(!$mail->Send()) {
			$mail->SmtpClose();
		  //return "Mailer Error: " . $mail->ErrorInfo;
		  return false;
		} else {
			$mail->SmtpClose();
		  //return "Message sent!";
		  return true;
		}
	}
}
?>
