<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
switch($action) {
	case 'ftp':
		require DT_ROOT.'/include/ftp.class.php';
		if(strpos($ftp_pass, '***') !== false) $ftp_pass = $DT['ftp_pass'];
		$ftp = new dftp($ftp_host, $ftp_user, $ftp_pass, $ftp_port, $ftp_path, $ftp_pasv, $ftp_ssl);
		if(!$ftp->connected) dialog('FTP无法连接，请检查设置');
		if(!$ftp->dftp_chdir()) dialog('FTP无法进入远程存储目录，请检查远程存储目录');
		dialog('FTP设置正常,可以使用');
	break;
	case 'mail':
		define('TESTMAIL', true);
		if(strpos($smtp_pass, '***') !== false) $smtp_pass = $DT['smtp_pass'];
		$DT['mail_type'] = $mail_type;
		$DT['smtp_host'] = $smtp_host;
		$DT['smtp_port'] = $smtp_port;
		$DT['smtp_auth'] = $smtp_auth;
		$DT['smtp_user'] = $smtp_user;
		$DT['smtp_pass'] = $smtp_pass;
		$DT['mail_sender'] = $mail_sender;
		$DT['mail_name'] = $mail_name;
		$DT['mail_delimiter'] = $mail_delimiter;
		$DT['mail_sign'] = '';
		log_write($DT, 'mail', 1);
		if($mail_type == 'sc') {
			$subject = '来自SendCloud的第一封邮件！';
			$body = '你太棒了！你已成功的从SendCloud发送了一封测试邮件，接下来快登录前台去完善账户信息吧！';
		} else {
			$subject = $DT['sitename'].'邮件发送测试';
			$body = '<b>恭喜！您的站点['.$DT['sitename'].']邮件发送设置成功！</b><br/>------------------------------------<br><a href="https://www.destoon.com/" target="_blank">Send By DESTOON B2B Mail Tester</a>';
		}	
		if(send_mail($testemail, $subject, $body)) dialog('邮件已发送至'.$testemail.'，请注意查收', $mail_sender);
		dialog('邮件发送失败，请检查设置');
	break;
	case 'static':
		if($itemid) {
			foreach(array(DT_ROOT.'/file/flash/', DT_ROOT.'/file/image/', DT_ROOT.'/file/script/', DT_ROOT.'/skin/'.$CFG['skin'].'/', DT_ROOT.'/'.$MODULE[2]['moduledir'].'/image/', DT_ROOT.'/'.$MODULE[4]['moduledir'].'/skin/', DT_ROOT.'/'.$MODULE[4]['moduledir'].'/image/') as $d) {
				$s = str_replace(DT_ROOT, DT_ROOT.'/file/static', $d);
				dir_copy($d, $s);
			}
			foreach(array(DT_ROOT.'/favicon.ico', DT_ROOT.'/lang/'.DT_LANG.'/lang.js') as $d) {
				$s = str_replace(DT_ROOT, DT_ROOT.'/file/static', $d);
				file_copy($d, $s);
			}
		}
		include tpl('static');
	break;
	case 'cache':
		$dc->set('destoon', 'b2b', 3600);
		$pass = $dc->get('destoon') == 'b2b' ? 1 : 0;
		dialog('<div style="padding:16px 16px 0 16px;">测试结果：'.($pass ? '<span class="f_green">成功</span>' : '<span class="f_red">失败</span>').'&nbsp; &nbsp;缓存类型：'.$CFG['cache'].'<div style="padding:10px 0;">如果类型不正确，请先保存设置再测试</div></div>');
	break;
	case 'html':
		tohtml('index');
		$dc->set('destoon', 'b2b', 3600);
		$dc->get('destoon') == 'b2b' or dalert('缓存类型'.$CFG['cache'].'测试失败，'.($CFG['cache'] == 'file' ? '请检查file目录是否可写' : '请立即更换'), '?moduleid='.$moduleid.'&file='.$file.'&tab=2');
		dmsg('设置保存成功', '?moduleid='.$moduleid.'&file='.$file.'&tab='.$tab);
	break;
	default:
		$tab = isset($tab) ? intval($tab) : 0;
		$all = isset($all) ? intval($all) : 0;
		if($submit) {
			foreach($setting as $k=>$v) {
				if(strpos($k, 'seo_') === false) continue;
				seo_check($v) or msg('SEO信息包含非法字符');
			}
			if(strpos($setting['remote_url'], 'file/upload') !== false) msg('FTP远程访问URL不能包含file/upload');
			if($setting['safe_domain']) {
				$setting['safe_domain'] = str_replace('http://', '', $setting['safe_domain']);
				if(substr($setting['safe_domain'], 0, 4) == 'www.') $setting['safe_domain'] = substr($setting['safe_domain'], 4);
			}
			if(substr($config['url'], -1) != '/') $config['url'] = $config['url'].'/';
			if($config['cookie_domain'] && substr($config['cookie_domain'], 0, 1) != '.') $config['cookie_domain'] = '.'.$config['cookie_domain'];
			if($config['cookie_domain'] != $CFG['cookie_domain']) $config['cookie_pre'] = 'D'.random(2).'_';
			in_array($setting['file_ext'], array('html', 'htm', 'shtml', 'shtm')) or $setting['file_ext'] = 'html';
			if(!is_numeric($config['cloud_uid']) || strlen($config['cloud_key']) != 16) $setting['sms'] = $setting['cloud_express'] = 0;
			$config['cloud_key'] = pass_decode($config['cloud_key'], DT_CLOUD_KEY);
			$setting['smtp_pass'] = pass_decode($setting['smtp_pass'], $DT['smtp_pass']);
			$setting['ftp_pass'] = pass_decode($setting['ftp_pass'], $DT['ftp_pass']);
			$setting['trade_pw'] = pass_decode($setting['trade_pw'], $DT['trade_pw']);
			$setting['admin_week'] = implode(',', $setting['admin_week']);
			$setting['check_week'] = implode(',', $setting['check_week']);
			if($setting['logo'] != $DT['logo']) clear_upload($setting['logo'], $_userid, 'setting');
			if(!is_write(DT_ROOT.'/config.inc.php')) msg('根目录config.inc.php无法写入，请设置可写权限');
			$tmp = file_get(DT_ROOT.'/config.inc.php');
			foreach($config as $k=>$v) {
				$tmp = preg_replace("/[$]CFG\['$k'\]\s*\=\s*[\"'].*?[\"']/is", "\$CFG['$k'] = '$v'", $tmp);
			}
			file_put(DT_ROOT.'/config.inc.php', $tmp);
			update_setting($moduleid, $setting);
			cache_module(1);
			cache_module();
			file_put(DT_ROOT.'/file/avatar/remote.html', $setting['ftp_remote'] && $setting['remote_url'] ? $setting['remote_url'] : 'URL');
			$filename = DT_ROOT.'/'.$setting['index'].'.'.$setting['file_ext'];
			if(!$setting['index_html'] && $setting['file_ext'] != 'php') file_del($filename);
			$pdir = DT_ROOT.'/'.$MODULE[2]['moduledir'].'/';
			$mdir = DT_ROOT.'/mobile/'.$MODULE[2]['moduledir'].'/';
			if($setting['file_register'] != $old_file_register) {
				@rename($pdir.$old_file_register, $pdir.$setting['file_register']);
				@rename($mdir.$old_file_register, $mdir.$setting['file_register']);
			}
			if($setting['file_login'] != $old_file_login) {
				@rename($pdir.$old_file_login, $pdir.$setting['file_login']);
				@rename($mdir.$old_file_login, $mdir.$setting['file_login']);
			}
			if($setting['file_my'] != $old_file_my) {
				@rename($pdir.$old_file_my, $pdir.$setting['file_my']);
				@rename($mdir.$old_file_my, $mdir.$setting['file_my']);
			}
			dheader('?moduleid='.$moduleid.'&file='.$file.'&action=html&tab='.$tab);
		} else {
			include DT_ROOT.'/config.inc.php';
			extract(dhtmlspecialchars($CFG));
			extract(dhtmlspecialchars($DT));
			$W = array('天', '一', '二', '三', '四', '五', '六');
			$smtp_pass = pass_encode($smtp_pass);
			$ftp_pass = pass_encode($ftp_pass);
			$trade_pw = pass_encode($trade_pw);
			$cloud_key = pass_encode($cloud_key);
			if($kw) {
				$all = 1;
				ob_start();
			}
			include tpl('setting', $module);
			if($kw) {
				$data = $content = ob_get_contents();
				ob_clean();
				$data = preg_replace('\'(?!((<.*?)|(<a.*?)|(<strong.*?)))('.$kw.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</strong>))\'si', '<span class=highlight>'.$kw.'</span>', $data);
				$data = preg_replace('/<span class=highlight>/', '<a name=high></a><span class=highlight>', $data, 1);
				echo $data ? $data : $content;
			}
		}
	break;
}
?>