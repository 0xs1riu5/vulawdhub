<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('IN_HDWIKI', TRUE);
define('HDWIKI_ROOT', '../');

$lang_name = 'zh';

require HDWIKI_ROOT."/lang/$lang_name/install.php";
require HDWIKI_ROOT.'/version.php';
require HDWIKI_ROOT.'/model/base.class.php';
$step = (isset ($_GET['step'])) ? $_GET['step'] : $_POST['step'];

if (file_exists(HDWIKI_ROOT.'/data/install.lock') && $step != '8') {
	echo "<font color='red'>{$lang['tipAlreadyInstall']}</font>";
	exit();
}


$dbcharset = $lang['commonDBCharset'];
header("Content-Type: text/html; charset={$lang['commonCharset']}");
$installfile = basename(__FILE__);
$configfile = HDWIKI_ROOT.'/config.php';
$logofile = HDWIKI_ROOT.'/style/default/logo.gif';

$sqlfile = HDWIKI_ROOT.'/install/hdwiki.sql';
if (!is_readable($sqlfile)) {
	exit ($strDBNoExists);
}

require HDWIKI_ROOT.'/install/install_func.php';
if (''==$step)
	$step = 1;
$arrTitle = array (
	"",
	$lang['commonLicenseInfo'],
	$lang['commonSystemCheck'],
	$lang['commonDatabaseSetup'],
	$lang['commonAdministratorSetup'],
	'创建数据表',
	$lang['commonInstallComplete']
);
$arrStep = range(0, 5);

$nextStep = $step +1;
$prevStep = $step -1;
if($step==3){
	$nextStep=$step;
	$prevStep=$step;
}
$nextAccess = 1;

$uploadsDir = HDWIKI_ROOT.'/uploads';
$dataDir = HDWIKI_ROOT.'/data';
$pluginDir =HDWIKI_ROOT.'/plugins';
$site_url="http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-20);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $lang['commonInstallTitle']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['commonCharset']?>">
<meta content="noindex, nofollow" name="robots">
<link rel="stylesheet" href="images/install.css" type="text/css" media="screen,projection" />
<script language="JavaScript" type="text/javascript">
	function selectlang(lang){
		var selectlang = document.getElementById(lang);
		var curstep = <?php echo $step?>;
		var langvalue = selectlang.options[selectlang.selectedIndex].value;
		window.location = "install.php?step="+curstep+"&lang="+langvalue;
	}
	
	function checkConfig(E){
		if(E.value) E.value = E.value.replace(/[^0-9a-z_]/gi, '');
	}
</script>
</head>
<body>
<div id="container">
<div id="header">
<div id="logo"></div>
<div id="topheader">
<p><strong>HDWiki V<?php echo HDWIKI_VERSION?> Release <?php echo HDWIKI_RELEASE?></strong></p>
<p><?php echo $lang['commonSetupLanguage'] ?>
<select id="lang" name="lang" onchange="selectlang('lang');">
	<option value="zh"<?php  if('zh' == $lang_name) { ?> selected="selected"<?php } ?>> <?php echo $lang['zh']?></option>
</select>
</p>
</div>
</div>
<div id="content-wrap">
<div id="menu">
<ul class="sidemenu">
<li class="navtitle"><?php echo $lang['commonSetupNavigate']?></li>
<?php
$steptotal = count($arrTitle);
for ($i = 1; $i < $steptotal; $i++) {
	if ($step >= $arrStep[$i]) {
		if($step==$i) {
			$href1 = "<li class=\"sidemenubg\">";
			$href2 = "</li>";
		}else{
			$href1 = "<li><a href='$installfile?step=" . $arrStep[$i] . "'>";
			$href2 = "</a></li>";
		}
	} else {
		$href1 = "<li><a>";
		$href2 = "</a></li>";
	}
?>
                    <?php echo $href1.$i.". ".$arrTitle[$i].$href2?>
					<?php } ?>
					</ul>
					<p class="lbox"> <?php echo $lang['tipLeftHelp']?></p>
</div>
<div id="main">
       	<?php if($step!=7){?><form name="settingsform" method="post" action="<?php echo $installfile; ?>"><?php }?>
      	<?php switch ($step) {
		case 1 :
			if ($msg) {
				$str = "<p>" . $msg . "</p>";
			}
			if ($nextAccess == 1)
				$str = "<div id=\"tips\"><div class=\"log\">{$lang['step1ReadLicense']}</div><div class=\"mes\"><div align=\"center\"><textarea style=\"width: 94%; height: 300px;\">" . $lang['step1LicenseInfo'] . "</textarea></div><br /><div align=\"center\"><input type=\"submit\" value=\"{$lang['step1Agree']}\" class=\"inbut1\">    <input type=\"button\" value=\"{$lang['step1Disagree']}\" class=\"inbut\" onclick=\"javascript:window.close();\"></div></div>";
			break;
		case 2 :
			
			$fileConfigAccess = file_writeable($configfile);
			$filelogoAccess=file_writeable($logofile);
			
			$dirUploadsAccess = file_writeable($uploadsDir);
			$dirDataAccess = file_writeable($dataDir);
			$dirPluginAccess = file_writeable($pluginDir);

			if(@ini_get("file_uploads")) {
				$max_size = @ini_get(upload_max_filesize);
				$curr_upload_status = "<font class=\"s4_color\">{$lang['step2AttachAllowSize']}: $max_size</font>";
			} else {
				$curr_upload_status = "<font color='red'>{$lang['step2AttachDisabled']}</font>";
				$msg .= "<span class='err'>{$lang['step2AttachDisabledTip']}</span><br>";
				$nextAccess=0;
				$extend .= '{A}'.$lang['step2AttachDisabledTip']."\n";
			}

			$curr_php_version = PHP_VERSION;

			if ($curr_php_version < '4.1.0') {
				$curr_php_version = "$curr_php_version <font color='red'>{$lang['step2PHPVersionTooLowTip']}</font>";
				$nextAccess = 0;
				$extend .= '{B}'.$lang['step2PHPVersionTooLowTip']."\n";
			}
			
			if ($curr_php_version >= '7.0') {
				$curr_php_version = "$curr_php_version <font color='red'>{$lang['step2PHPVersionTooHighTip']}</font>";
				$nextAccess = 0;
				$extend .= '{B}'.$lang['step2PHPVersionTooHighTip']."\n";
			}

			if (!function_exists('mysql_connect')) {
				$MySQLVersion = "<font color='s3_color'>{$lang['commonUnsupport']}</font>";
				$nextAccess = 0;
				$extend .= '{C}'.'mysql'.$lang['commonUnsupport']."\n";
			} else {
				$MySQLVersion = "<font class='s2_color'>{$lang['commonSupport']}</font>";;
			}

			$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

			$os = strtoupper(substr(PHP_OS, 0, 3));
			$curOs = PHP_OS;
			if ($fileConfigAccess) {
				$fileConfigAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$fileConfigAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{D}'.'configfile'.$lang['commonWriteable']."\n";
			}
			if ($filelogoAccess) {
				$filelogoAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$filelogoAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{E}'.'logofile'.$lang['commonWriteable']."\n";
			}
			if ($dirUploadsAccess) {
				$dirUploadsAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$dirUploadsAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{F}'.'logofile'.$lang['commonWriteable']."\n";
			}
			if ($dirDataAccess ) {
				$dirDataAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$dirDataAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{G}'.'dirData'.$lang['commonWriteable']."\n";
			}
			if ($dirPluginAccess ) {
				$dirPluginAccessTip = "<font class='s1_color'>{$lang['commonWriteable']}</font>";
			}else{
				$dirPluginAccessTip = "<font class='s3_color'>{$lang['commonNotWriteable']}</font>";
				$nextAccess = 0;
				$extend .= '{H}'.'dirPlugin'.$lang['commonWriteable']."\n";
			}
			$str = $str."<div id=\"tips\">{$lang['step2Tip']}</div>";

			$str = $str."<div id=\"wrapper\">
  <table class=\"table_nav\">
    <tr class=\"nav_bar\">
      <td></td>
      <td>HDWiki {$lang['commonConfigRequire']}</td>
      <td>HDWiki {$lang['commonConfigOptimized']}</td>
      <td>{$lang['commonConfigCurrent']}</td>
    </tr>
    <tr>
      <td>{$lang['commonOS']}</td>
      <td>{$lang['commonUnlimited']}</td>
      <td class=\"s1_color\">UNIX/Linux/FreeBSD </td>
      <td class=\"s4_color\">$curOs</td>
    </tr>
    <tr>
      <td>PHP {$lang['commonVersion']}</td>
      <td>5.2.x+ </td>
      <td class=\"s1_color\">5.6.x+</td>
      <td class=\"s2_color\">$curr_php_version</td>
    </tr>
    <tr>
      <td>{$lang['commonAttachUpload']}</td>
      <td>{$lang['commonUnlimited']}</td>
      <td class=\"s1_color\">{$lang['commonAllow']}</td>
      <td >$curr_upload_status</td>
    </tr>
    <tr>
      <td>MySQL {$lang['commonSupport']}</td>
      <td>3.23+</td>
      <td class=\"s1_color\">{$lang['commonSupport']}</td>
      <td>$MySQLVersion</td>
    </tr>
    <tr>
      <td>{$lang['commonDiskSpace']}</td>
      <td>10M+</td>
      <td class=\"s1_color\">{$lang['commonUnlimited']}</td>
      <td class=\"s4_color\">$curr_disk_space</td>
    </tr>
  </table>
</div>";

$str = $str."<div id=\"wrapper1\">
						<table class=\"table_nav\">
    <tr class=\"nav_bar\">
      <td>{$lang['commonDirName']}</td>
      <td>{$lang['commonDirDescribe']}</td>
      <td>{$lang['commonStateOptimized']}</td>
      <td>{$lang['commonStateCurrent']}</td>
    </tr>
    <tr>
      <td>./uploads</td>
      <td>{$lang['commonDirAttach']}</td>
      <td class=\"s1_color\">{$lang['commonDirPower']} {$lang['commonWriteable']}</td>
      <td>$dirUploadsAccessTip</td>
    </tr>
    <tr>
      <td>./data</td>
      <td>{$lang['commonDirSysData']}</td>
      <td class=\"s1_color\">{$lang['commonDirPower']} {$lang['commonWriteable']}</td>
      <td>$dirDataAccessTip</td>
    </tr>
    <tr>
      <td>./plugins</td>
      <td>{$lang['commonDirSysPlugin']}</td>
      <td class=\"s1_color\">{$lang['commonDirPower']} {$lang['commonWriteable']}</td>
      <td>$dirPluginAccessTip</td>
    </tr>
    <tr>
      <td>./config.php</td>
      <td>{$lang['commonFileConfig']}</td>
      <td class=\"s1_color\">{$lang['commonFilePower']} {$lang['commonWriteable']}</td>
      <td>$fileConfigAccessTip</td>
    </tr>
    <tr>
      <td>./style/default/logo.gif</td>
      <td>{$lang['commonFileLogo']}</td>
      <td class=\"s1_color\">{$lang['commonFilePower']} {$lang['commonWriteable']}</td>
      <td>$filelogoAccessTip</td>
    </tr>
  </table></div>";
			break;
		case 3 :
			$saveconfig=$_REQUEST['saveconfig'];
			if($saveconfig=='1'){
				//db parameter
				$dbhost = trim($_POST['dbhost']);
				$dbuser = trim($_POST['dbuser']);
				$dbpassword = trim($_POST['dbpassword']);
				$dbname = trim($_POST['dbname']);
				$table_prefix = trim($_POST['table_prefix']);

				// 接受到的内容写入CONFIG 文件，用于回显
				if (is_writeable($configfile) || (!file_exists($configfile))) {
						$configcontent = "<?php
	define('DB_HOST', '".$dbhost."');
	define('DB_USER', '".$dbuser."');
	define('DB_PW', '".$dbpassword."');
	define('DB_NAME', '".$dbname."');
	define('DB_TABLEPRE', '".$table_prefix."');
	define('WIKI_URL', '".$site_url."');
?>";
						$fp1 = fopen($configfile, 'wbt');
						$bytes=fwrite($fp1, $configcontent);
						@ fclose($fp1);
					} else {
						if (!file_exists($configfile)) {
							$msg .= "<SPAN class=err>{$lang['step3DBConfigWriteErrorTip']}</span><br />";
							$nextAccess = 0;
							$extend .= '{I}'.$lang['step3DBConfigWriteErrorTip']."\n";
						}else{
							$msg .= "<SPAN class=err>{$lang['step3DBConfigNotWriteTip']}</span><br />";
							$nextAccess = 0;
							$extend .= '{J}'.$lang['step3DBConfigNotWriteTip']."\n";
						}
					}
					
				if ($dbhost == "" or $dbuser == "" or $dbname == "" or $table_prefix == "") {
					$msg .= "<SPAN class=err>{$lang['step3IsNull']}</span><br />";
					$nextAccess = 0;
					$extend .= '{K}'.$lang['step3IsNull']."\n";
				}

				if (strstr($table_prefix, '.') and $nextAccess == 1) {
					$msg .= "<SPAN class=err>{$lang['step3DBPrefix']}</span><br />";
					$nextAccess = 0;
					$extend .= '{L}'.$lang['step3DBPrefix']."\n";
				}

				if ($nextAccess == 1) {
					if(!@mysql_connect($dbhost, $dbuser, $dbpassword)) {
						$msg .= '<SPAN class=err>'.$lang['step3NoConnDB'].'</span>';
						$nextAccess = 0;
						$extend .= '{M}'.$lang['step3NoConnDB']."\n";
					} else {
						if(mysql_get_server_info() > '4.1') {
							mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET $dbcharset");
						} else {
							mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname`");
						}
						if(mysql_errno()) {
							$msg .= "<SPAN class=err>{$lang['step3DBNoPower']}</span><br />";
							$nextAccess = 0;
							$extend .= '{N}'.$lang['step3DBNoPower']."\n";
						}

						mysql_close();
					}
				}

				if ($nextAccess == 1) {
					
						$configcontent = "<?php
	define('DB_HOST', '".$dbhost."');
	define('DB_USER', '".$dbuser."');
	define('DB_PW', '".$dbpassword."');
	define('DB_NAME', '".$dbname."');
	define('DB_CHARSET', '".$dbcharset."');
	define('DB_TABLEPRE', '".$table_prefix."');
	define('DB_CONNECT', 0);
	define('WIKI_FOUNDER', 1);
	define('WIKI_CHARSET', '".$lang['commonCharset']."');
	define('WIKI_URL', '".$site_url."');
?>";
						$fp1 = fopen($configfile, 'wbt');
						$bytes=fwrite($fp1, $configcontent);
						@ fclose($fp1);
				}

				if ($nextAccess == 0) {
					$msg .= "<br /><SPAN class=err>{$lang['tipGenErrInfo']}</span><br /><br />";
					$msg .= "</p>\n";
				} else {
					echo   "<script>window.location=\"{$_SERVER['PHP_SELF']}?step=4\";</script>";
				}
				$str=$str.$msg;
			}else{
				if (PHP_VERSION < '4.0.6') {
					$msg .= "<SPAN class=err>{$lang['step2PHPVersionTooLowTip']}</span><br /><br />";
					$nextAccess = 0;
					$extend .= '{O}'.$lang['step2PHPVersionTooLowTip']."\n";
				}
				if (!function_exists('mysql_connect')) {
					$msg .= "<SPAN class=err>{$lang['step3MySQLExtErrorTip']}</span><br /><br />";
					$nextAccess = 0;
					$extend .= '{P}'.$lang['step3MySQLExtErrorTip']."\n";
				}
				if ($msg) {
					$str = "<p>" . $msg . "</p>";
				}

				if ($nextAccess == 1) {
					// 自动填充数据库信息
					$db_config = get_db_config();

					$str = "<div id=\"tips\">{$lang['step3Tip']}</div>";

					$str .= "<div id=\"wrapper\">
	<div class=\"col\">
	<h3>{$lang['commonSetupOption']}        {$lang['commonSetupParameterValue']}        {$lang['commonSetupComment']}</h3>
	<p><span class=\"red\">{$lang['step3MySqlHost']}: </span> <input name=\"dbhost\" value=\"".$db_config['dbhost']."\" type=\"text\" size=\"20\"/>    {$lang['step3MySqlHostComment']}</p>
	<p>{$lang['step3MySqlUser']}: <input name=\"dbuser\" value=\"".$db_config['dbuser']."\" type=\"text\" size=\"20\" maxlength=\"16\"/>    {$lang['step3MySqlUserComment']}</p>
	<p>{$lang['step3MySqlPass']}:&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"dbpassword\" value=\"".$db_config['dbpassword']."\" type=\"password\" size=\"20\"/>    {$lang['step3MySqlPassComment']}</p>
	<p>{$lang['step3MySqlDBName']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"dbname\" value=\"".$db_config['dbname']."\" type=\"text\" size=\"20\" onblur=\"checkConfig(this)\" maxlength=\"64\"/>    {$lang['step3MySqlDBNameComment']}</p>
	<p><span class=\"red\">{$lang['step3MySqlDBTablePrefix']}:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"table_prefix\" value=\"".$db_config['table_prefix']."\" type=\"text\" size=\"20\" onblur=\"checkConfig(this)\" maxlength=\"30\"/>    {$lang['step3MySqlDBTablePrefixComment']}</p>
	</div></div><input type='hidden' name='saveconfig' value='1'/>";
				}
				$prevStep=$prevStep-1;
			}

			break;
		case 4 :
				require_once HDWIKI_ROOT.'/config.php';
				if(!@mysql_connect(DB_HOST, DB_USER, DB_PW)) {
					$msg .= '<SPAN class=err>'.$lang['step3NoConnDB'].'</span><br/>';
					$nextAccess = 0;
					$extend .= '{Q}'.$lang['step3NoConnDB']."\n";
				} else {
					$curr_mysql_version = mysql_get_server_info();
					if($curr_mysql_version < '3.23') {
						$msg .= '<SPAN class=err>'.$lang['step3MySqlVersionToLowTip'].'</span><br/>';
						$nextAccess = 0;
						$extend .= '{R}'.$lang['step3MySqlVersionToLowTip']."\n";
					}
					$islink=mysql_select_db(DB_NAME);
					if($islink){
						$result = mysql_query("SELECT COUNT(*) FROM ".DB_TABLEPRE."setting");
						if($result) {
							$msg .= '<SPAN class=err>'.$lang['step3DBAlreadyExist'].'</span><br/>';
							$alert = " onClick=\"return confirm('{$lang['step3DBDropTableConfirm']}');\"";
						}
					}
				}

				// 通过服务器获得管理员用户名和邮箱
				$admin_info = $_SERVER['SERVER_ADMIN'];
				if(!empty($admin_info)) {
					$admin_email = $admin_info;
					$admin_master = explode('@', $admin_email);
					$admin_master = $admin_master[0];
				} else {
					$admin_email = '';
					$admin_master = '';
				}
				$str = "<div id=\"tips\">" .
							"<div class=\"log\">{$lang['commonInfotip']}</div><div class=\"mes\"><p>{$lang['step4Tip']}<br/>$msg</p></div>
							</div>";
				$str .="<div id=\"wrapper\"><div class=\"col\">" .
	"<h3>{$lang['commonSetupOption']}{$lang['commonSetupParameterValue']}{$lang['commonSetupComment']}</h3>
	<p><span class=\"red\">{$lang['step4AdministratorNick']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name=\"admin_master\" value=\"$admin_master\" type=\"text\" size=\"20\" maxlength=\"32\"/>{$lang['step4AdministratorNickComment']}</p>
	<p><span class=\"red\">{$lang['step4AdministratorEmail']}:&nbsp;</span><input name=\"admin_email\" value=\"$admin_email\" type=\"text\" size=\"20\" />{$lang['step4AdministratorEmailComment']}</p>
	<p><span class=\"red\">{$lang['step4AdministratorPass']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name=\"admin_pw\" value=\"\" type=\"password\" size=\"20\" maxlength=\"32\"/>{$lang['step4AdministratorPassComment']}</p>
	<p><span class=\"red\">{$lang['step4AdministratorRePass']}:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input name=\"admin_pw2\" value=\"\" type=\"password\" size=\"20\" maxlength=\"32\"/>{$lang['step4AdministratorRePassComment']}</p>
	</div></div>";
			break;
		case 5 :
			$admin_pw = encode($_POST['admin_pw']);
			$admin_pw2 = encode($_POST['admin_pw2']);
			$admin_email = encode(trim($_POST['admin_email']));
			$admin_master = encode(trim($_POST['admin_master']));
			$site_icp = "";

			if ($admin_pw == "" or $admin_pw2 == "" or $admin_email == "" or $admin_master == "") {
				$str = "<SPAN class=err>{$lang['step3IsNull']}</span>";
				$nextAccess = 0;
				$extend .= '{S}'.$lang['step3IsNull']."\n";
			}elseif (strlen($admin_pw) < 6) {
				$str = "<SPAN class=err>{$lang['step4AdministratorPassTooShortTip']}</span>";
				$nextAccess = 0;
				$extend .= '{T}'.$lang['step4AdministratorPassTooShortTip']."\n";
			}elseif ($admin_pw != $admin_pw2) {
				$str = "<SPAN class=err>{$lang['step4AdministratorPassNotSame']}</span>";
				$nextAccess = 0;
				$extend .= '{U}'.$lang['step4AdministratorPassNotSame']."\n";
			}elseif (check_email($admin_email) == 0) {
				$str = "<SPAN class=err>{$lang['step4AdministratorEmailInvalid']}</span>";
				$nextAccess = 0;
				$extend .= '{V}'.$lang['step4AdministratorEmailInvalid']."\n";
			} else {
				if ($nextAccess == 1) {
					require_once HDWIKI_ROOT.'/config.php';
					require_once HDWIKI_ROOT.'/lib/hddb.class.php';
					$db = new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET);
					$fp = fopen($sqlfile, 'rb');
					$sql = fread($fp, filesize($sqlfile));
					fclose($fp);
					$strcretip=runquery($sql);
 
					if($nextAccess==1) $msg .= "{$lang['step4ImportDefaultData']} <br />";

					$admin_email = strtolower($admin_email);
					$admin_email_len = strlen($admin_email);
					$adminpwd = md5($admin_pw);
					$regtime=time();
					$site_name = $lang['step4DefaultSiteName'];
					$auth_key = generate_key();
$installsql = <<<EOT

INSERT INTO wiki_usergroup (`groupid`, `grouptitle`, `regulars`, `default`, `type`, `creditslower`, `creditshigher`, `stars`, `color`, `groupavatar`) VALUES
(1, '匿名用户', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-getpass|user-code|user-space|user-clearcookies|synonym-view|passport_client-login|passport_client-logout|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|search-agent', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-getpass|user-code|user-space|user-clearcookies|synonym-view|passport_client-login|passport_client-logout|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|search-agent', 1, 0, 0, 0, '', ''),
(3, '词条管理员', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|exchange-default|admin_share-default|admin_share-search|admin_share-share|admin_main-datasize|doc-editletter', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|exchange-default|admin_share-default|admin_share-search|admin_share-share|admin_main-datasize|doc-editletter', 1, 0, 0, 2, '', ''),
(4, '超级管理员', '', '', 1, 0, 0, 3, '', ''),
(5, '白丁', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|doc-edit|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-view|synonym-savesynonym|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|doc-edit|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-view|synonym-savesynonym|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, -999999, 0, 0, '', ''),
(2, '书童', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 0, 100, 1, '', ''),
(6, '秀才', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 100, 300, 4, '', ''),
(7, '举人', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 300, 600, 5, '', ''),
(8, '进士', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 600, 1000, 8, '', ''),
(9, '状元', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 1000, 1500, 16, '', ''),
(10, '翰林', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|comment-add|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 1500, 2100, 18, '', ''),
(11, '太傅', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 2100, 2800, 24, '', ''),
(12, '圣贤', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 2, 2800, 999999999, 33, '', ''),
(13, '荣誉宰相', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 'index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-changename|doc-lock|doc-unlock|doc-audit|comment-remove|comment-add|comment-edit|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|doc-delsave|doc-managesave|exchange-default|doc-editletter', 0, 0, 0, 5, '', ''),
(14, '管理员', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_banned-default|admin_friendlink-default|admin_friendlink-add|admin_friendlink-edit|admin_friendlink-remove|admin_friendlink-updateorder|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_setting-base|admin_setting-code|admin_setting-time|admin_setting-cookie|admin_setting-logo|admin_setting-credit|admin_setting-seo|admin_setting-cache|admin_setting-renewcache|admin_setting-removecache|admin_setting-attachment|admin_setting-mail|admin_setting-noticemail|admin_task-default|admin_task-taskstatus|admin_task-edittask|admin_task-run|admin_log-default|admin_setting-notice|admin_setting-anticopy|admin_setting-listdisplay|admin_setting-sec|admin_setting-index|admin_setting-docset|admin_setting-search|admin_plugin-list|admin_plugin-default|admin_plugin-manage|admin_plugin-will|admin_plugin-find|admin|admin_plugin-install|admin_plugin-uninstall|admin_plugin-start|admin_plugin-stop|admin_plugin-setvar|admin_plugin-hook|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_recycle-default|admin_recycle-search|admin_recycle-remove|admin_recycle-recover|admin_recycle-|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|admin_gift-default|admin_gift-view|admin_gift-search|admin_gift-add|admin_gift-edit|admin_gift-remove|admin_user-default|admin_user-list|admin_user-add|admin_user-edit|admin_usergroup-default|admin_usergroup-list|admin_category-default|admin_category-list|admin_category-add|admin_category-batchedit|admin_category-edit|admin_category-reorder|admin_statistics-stand|admin_statistics-cat_toplist|admin_statistics-doc_toplist|admin_statistics-edit_toplist|admin_statistics-credit_toplist|admin_statistics-admin_team|exchange-default|admin_share-default|admin_share-search|admin_share-share|doc-editletter|admin_sitemap-default|admin_sitemap-setting|admin_sitemap-createdoc|admin_sitemap-updatedoc|admin_sitemap-submit|admin_sitemap-baiduxml|admin_filecheck-docreate|admin_safe-default|admin_safe-setting|admin_safe-scanfile|admin_safe-validate|admin_safe-scanfuns|admin_safe-list|admin_safe-editcode|admin_safe-del', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status|admin_actions-map|index-default|index-settheme|attachment-download|user-removefavorite|user-exchange|user-addfavorite|archiver-default|archiver-list|archiver-view|datacall-js|search-agent|category-default|category-ajax|category-view|category-letter|list-letter|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-login|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-logout|user-profile|user-editprofile|user-editpass|user-editimage|user-editimageifeam|user-cutimage|admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacall-operate|admin_datacall-remove|admin_datacall-addsql|admin_datacall-editsql|user-getpass|user-code|user-space|user-clearcookies|user-cutoutimage|user-invite|pms-default|pms-box|pms-setread|pms-remove|pms-sendmessage|pms-checkrecipient|pms-blacklist|pms-publicmessage|attachment-uploadimg|attachment-remove|doc-create|doc-verify|doc-edit|doc-editsection|doc-refresheditlock|doc-unseteditlock|doc-sandbox|doc-setfocus|doc-getcategroytree|doc-changecategory|doc-changename|doc-lock|doc-unlock|doc-audit|doc-remove|comment-remove|comment-add|comment-edit|edition-remove|edition-excellent|edition-unexcellent|edition-copy|synonym-removesynonym|synonym-view|synonym-savesynonym|doc-immunity|reference-add|reference-remove|attachment-upload|doc-removefocus|doc-autosave|doc-getrelateddoc|doc-addrelatedoc|passport_client-login|passport_client-logout|admin_doc-cancelrecommend|doc-delsave|doc-managesave|admin_banned-default|admin_friendlink-default|admin_friendlink-add|admin_friendlink-edit|admin_friendlink-remove|admin_friendlink-updateorder|admin_main-login|admin_main-default|admin_main-logout|admin_main-mainframe|admin_main-update|admin_setting-base|admin_setting-code|admin_setting-time|admin_setting-cookie|admin_setting-logo|admin_setting-credit|admin_setting-seo|admin_setting-cache|admin_setting-renewcache|admin_setting-removecache|admin_setting-attachment|admin_setting-mail|admin_setting-noticemail|admin_task-default|admin_task-taskstatus|admin_task-edittask|admin_task-run|admin_log-default|admin_setting-notice|admin_setting-anticopy|admin_setting-listdisplay|admin_setting-sec|admin_setting-index|admin_setting-docset|admin_setting-search|admin_plugin-list|admin_plugin-default|admin_plugin-manage|admin_plugin-will|admin_plugin-find|admin|admin_plugin-install|admin_plugin-uninstall|admin_plugin-start|admin_plugin-stop|admin_plugin-setvar|admin_plugin-hook|admin_doc-default|admin_doc-search|admin_doc-audit|admin_doc-recommend|admin_doc-lock|admin_doc-unlock|admin_doc-remove|admin_doc-move|admin_doc-rename|admin_comment-default|admin_comment-search|admin_comment-delete|admin_attachment-default|admin_attachment-search|admin_attachment-remove|admin_attachment-download|admin_focus-focuslist|admin_focus-remove|admin_focus-reorder|admin_focus-edit|admin_focus-updateimg|admin_focus-numset|admin_tag-hottag|admin_word-default|admin_synonym-default|admin_synonym-search|admin_synonym-delete|admin_synonym-save|admin_recycle-default|admin_recycle-search|admin_recycle-remove|admin_recycle-recover|admin_recycle-|admin_cooperate-default|admin_hotsearch-default|admin_image-default|admin_image-editimage|admin_image-remove|admin_relation-default|admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_editi|admin_gift-default|admin_gift-view|admin_gift-search|admin_gift-add|admin_gift-edit|admin_gift-remove|admin_user-default|admin_user-list|admin_user-add|admin_user-edit|admin_usergroup-default|admin_usergroup-list|admin_category-default|admin_category-list|admin_category-add|admin_category-batchedit|admin_category-edit|admin_category-reorder|admin_statistics-stand|admin_statistics-cat_toplist|admin_statistics-doc_toplist|admin_statistics-edit_toplist|admin_statistics-credit_toplist|admin_statistics-admin_team|exchange-default|admin_share-default|admin_share-search|admin_share-share|doc-editletter|admin_sitemap-default|admin_sitemap-setting|admin_sitemap-createdoc|admin_sitemap-updatedoc|admin_sitemap-submit|admin_sitemap-baiduxml|admin_filecheck-docreate|admin_safe-default|admin_safe-setting|admin_safe-scanfile|admin_safe-validate|admin_safe-scanfuns|admin_safe-list|admin_safe-editcode|admin_safe-del', 1, 0, 0, 2, '', ''),
(15, '被冻结', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-profile|user-editpass|user-getpass|user-code|user-space|user-clearcookies|pms-blacklist|synonym-view|doc-editletter', 'index-default|index-settheme|category-default|category-ajax|category-view|category-letter|list-letter|list-default|list-recentchange|list-popularity|list-focus|doc-view|doc-innerlink|doc-summary|doc-editor|comment-view|comment-report|comment-oppose|comment-aegis|edition-list|edition-view|edition-compare|search-default|search-fulltext|search-kw|search-tag|list-weekuserlist|list-allcredit|list-rss|doc-random|doc-vote|doc-cooperate|gift-default|gift-view|gift-search|gift-apply|pic-piclist|pic-view|pic-ajax|pic-search|user-register|user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail|user-profile|user-editpass|user-getpass|user-code|user-space|user-clearcookies|pms-blacklist|synonym-view|doc-editletter', 1, 0, 0, 1, '', '');
				
REPLACE INTO wiki_setting(variable,value) VALUES
	('site_name', '{$site_name}'),
	('site_icp', ''),
	('cookie_domain', ''),
	('cookie_pre', 'hd_'),
	('app_url', 'http://kaiyuan.hudong.com'),
	('auth_key', '{$auth_key}'),

	('verify_doc', '0'),
	('cat_intro_set','0'),
	('time_offset','8'),
	('time_diff','0'),
	('time_format',''),
	('date_format','m-d'),
	('style_user_select','1'),

	('credit_create', '5'),
	('credit_edit', '3'),
	('credit_upload', '2'),
	('credit_register', '20'),
	('credit_login', '1'),
	('credit_pms','1'),
	('credit_comment','2'),
	('list_prepage', '20'),
	
	('list_focus', '10'),
	('list_recentupdate', '10'),
	('list_weekuser', '10'),
	('list_allcredit', '10'),
	('list_popularity', '10'),
	('list_letter', '10'),
	('login_show', '1'),
	('category_view', '10'),
	('category_letter', '10'),
	('index_commend', '15'),
	('index_recentupdate', '6'),
	('index_recentcomment', '5'),
	('index_hotdoc', '3'),
	('index_wonderdoc', '6'),
	('index_picture', '9'),
	('index_cooperate', '20'),

	('seo_prefix', 'index.php?'),
	('seo_separator', '-'),
	('seo_suffix', ''),
	('seo_title', ''),
	('seo_keywords', ''),
	('seo_description', ''),
	('seo_headers', ''),
	('seo_type', '0'),
	('seo_type_doc', '0'),

	('attachment_size', '2048'),
	('attachment_open', '0'),
	('attachment_type', 'jpg|jpeg|bmp|gif|png|gz|bz2|zip|rar|doc|ppt|mp3|xls|txt|swf|flv|php|pdf'),
		
	('index_cache_time', '300'),
	('list_cache_time', '300'),
	('doc_cache_time', '300'),
	('tpl_name', 'default'),
	('theme_name','default'),
	('lang_name','zh'),
	('auto_picture','0'),
	('checkcode','3'),
	('sandbox_id',''),
	('logowidth','220px'),
	('site_notice','本站是由<span style="color:#FF0000">1</span>位网民共同撰写的百科全书，目前已收录词条<span style="color:#FF0000"> 0</span>个'),

	
	('search_time', '1'),
	('search_tip_switch', '1'),
	('search_num', '10000'),
	('close_register_reason', '对不起，网站暂停注册！给您带来的不便还请谅解。'),
	('error_names', '管理员'),
	('register_check', '0'),
	('name_min_length', '3'),
	('name_max_length', '15'),
	('register_least_minute', '30'),
	('reg_status', '3'),
	('inviter_credit', '5'),
	('invitee_credit', '0'),
	('invite_subject', '您收到_USERNAME_的邀请了！'),
	('invite_content', '你好，我是_USERNAME_，在_SITENAME_上注册了会员，里面有很多有用的知识，邀请你也加入进来。\\r\\n\\r\\n邀请附言：\\r\\n\\r\\n_PS_\\r\\n\\r\\n请你点击以下链接，接受好友邀请：_LINK_\\r\\n\\r\\n_SITENAME_ 敬上'),
	('welcome_subject', '_USERNAME_，您好，欢迎您的加入^_^'),
	('welcome_content', '尊敬的_USERNAME_，\\r\\n\\r\\n您好！您已成功注册为_SITENAME_的会员，欢迎您与大家积极分享知识。\\r\\n\\r\\n_SITENAME_ 敬上\\r\\n_TIME_'),
	('send_welcome', '0'),
	('close_website', '0'),
	('forbidden_edit_time', '0'),
	('comments', '1'),
	('base_toplist', '0'),		
	('base_createdoc', '0'),
	('doc_verification_edit_code', '0'),
	('doc_verification_create_code', '0'),
	('relateddoc', ''),
	('isrelate', '0'),
	('close_website_reason', '网站暂时关闭，马上就会恢复，请稍候关注，谢谢。'),
	
	('coin_register', '20'),
	('coin_login', '1'),
	('coin_create', '2'),
	('coin_edit', '1'),
	('coin_upload', '1'),
	('coin_pms', '0'),
	('coin_comment', '1'),
	('credit_exchangeRate', '5'),
	('coin_exchangeRate', '1'),
	('credit_exchange', '0'),
	('coin_exchange', '0'),
	('credit_download', '0'),
	('coin_download', '10'),
	
	('img_width_big', '300'),
	('img_height_big', '300'),
	('img_width_small', '140'),
	('img_height_small', '140'),

	('cloud_search', '1'),
	('cloud_search_close_time', '30'),
	('cloud_search_timeout', '5'),
	('cloud_search_cache', '300'),
	
	('gift_range', 'a:4:{i:0;s:2:"50";i:50;s:3:"100";i:100;s:3:"200";i:200;s:3:"500";}'),
	('watermark', 'a:8:{s:8:"imagelib";s:1:"0";s:11:"imageimpath";s:0:"";s:15:"watermarkstatus";s:1:"0";s:17:"watermarkminwidth";s:3:"180";s:18:"watermarkminheight";s:3:"180";s:13:"watermarktype";s:1:"0";s:14:"watermarktrans";s:2:"60";s:16:"watermarkquality";s:3:"100";}'),
	('coin_unit', ''),
	('mail_config', 'a:10:{s:11:"maildefault";s:{$admin_email_len}:"{$admin_email}";s:8:"mailsend";s:1:"1";s:10:"mailserver";s:0:"";s:8:"mailport";s:2:"25";s:8:"mailauth";s:1:"0";s:8:"mailfrom";s:0:"";s:17:"mailauth_username";s:0:"";s:17:"mailauth_password";s:0:"";s:13:"maildelimiter";s:1:"0";s:12:"mailusername";s:1:"1";}'),
	('sitemap_config', 'a:5:{s:8:"use_gzip";s:1:"1";s:14:"idx_changefreq";s:5:"daily";s:14:"doc_changefreq";s:7:"monthly";s:10:"textcolumn";s:7:"summary";s:10:"updateperi";s:2:"30";}'),
	('auto_baiduxml', '0'),
	('random_open', '0'),
	('random_text', ''),
	('check_useragent', '0'),
	('ua_allow_first', '1'),
	('allow_ua_both', '1'),
	('ua_whitelist', ''),
	('ua_blacklist', ''),
	('check_visitrate', '0'),
	('visitrate', 'a:3:{s:8:"duration";i:60;s:5:"pages";i:30;s:8:"ban_time";i:1;}'),
	('compatible', '1'),
	('hdapi_bklm', '1'),
	('hdapi_sharetosns', '1'),
	('hdapi_autoshare_edit', '0'),
	('hdapi_autoshare_create', '0'),
	('hdapi_autoshare_comment', '0'),
	('hdapi_autoshare_ding', '0'),
	('base_isreferences', '1'),
	('doc_verification_reference_code', '0'),
	('noticemailtpl', 'a:3:{s:10:"doc_create";a:2:{s:7:"subject";s:38:"_USERNAME_ 分享了“ _DOCTITLE_ ”";s:4:"body";s:793:"<style>cite, .build {background:none repeat scroll 0 0 #FFFFCC;border:1px solid #F39700;display:block;margin-bottom:8px;padding:10px;}table { font-size: 13px; }.firstimg { padding: 5px; border: 1px solid #EFEFEF; margin-right: 16px; }h4{ font-size: 16px; margin: 0; padding: 0; }hr { width: 100px; }div.sig { font-size: 12px; font-family: "Arial"  }.time { color: #CCCCCF; }.uns { color: green; }</style><table border="0"><tr><td valign="top">_FIRSTIMG_</td><td valign="top"><strong>_DOCTITLE_</strong><br /><a href="_URL_" target="_blank">_URL_</a><br /><br />创建时间：_TIME_<br /><br />摘要：_SUMMARY_<br /><br /><a href="_URL_" target="_blank">查看详情</a></td></tr></table><br /><br /><div class="sig"><span class="time">_TIME_ </span><hr align="left" />_SITENAME_<br /></div>";}s:8:"doc_edit";a:2:{s:7:"subject";s:38:"_USERNAME_ 编辑了“ _DOCTITLE_ ”";s:4:"body";s:798:"<style>cite, .build {background:none repeat scroll 0 0 #FFFFCC;border:1px solid #F39700;display:block;margin-bottom:8px;padding:10px;}table { font-size: 13px; }.firstimg { padding: 5px; border: 1px solid #EFEFEF; margin-right: 16px; }h4{ font-size: 16px; margin: 0; padding: 0; }hr { width: 100px; }div.sig { font-size: 12px; font-family: "Arial"  }.time { color: #CCCCCF; }.uns { color: green; }</style><table border="0"><tr><td valign="top">_FIRSTIMG_</td><td valign="top"><strong>_DOCTITLE_</strong><br /><a href="_URL_" target="_blank">_URL_</a><br /><br />编辑时间：_TIME_<br /><br />编辑原因：_REASON_<br /><br /><a href="_URL_" target="_blank">查看详情</a></td></tr></table><br /><br /><div class="sig"><span class="time">_TIME_ </span><hr align="left" />_SITENAME_<br /></div>";}s:11:"comment_add";a:2:{s:7:"subject";s:38:"_USERNAME_评论了 “ _DOCTITLE_ ”";s:4:"body";s:782:"<style>cite, .build {background:none repeat scroll 0 0 #FFFFCC;border:1px solid #F39700;display:block;margin-bottom:8px;padding:10px;}table { font-size: 13px; }.firstimg { padding: 5px; border: 1px solid #EFEFEF; margin-right: 16px; }h4{ font-size: 16px; margin: 0; padding: 0; }hr { width: 100px; }div.sig { font-size: 12px; font-family: "Arial"  }.time { color: #CCCCCF; }.uns { color: green; }</style><table border="0"><tr><td valign="top">_FIRSTIMG_</td><td valign="top"><strong>_DOCTITLE_</strong><br /><a href="_URL_" target="_blank">_URL_</a><br /><br /><div >_REPLY_</div><br /><br />_COMMENT_<br /><br /><a href="_URL_" target="_blank">查看详情</a></td></tr></table><br /><br /><div class="sig"><span class="time">_TIME_ </span><hr align="left" />_SITENAME_<br /></div>";}}'),
	('noticemail', 'a:3:{s:10:"doc-create";s:1:"4";s:8:"doc-edit";s:0:"";s:11:"comment_add";s:0:"";}'),
	('visitrate_ip_exception', '127.0.0.*');

INSERT INTO wiki_category(`name`,`navigation`,`docs`) VALUES ('Default','a:1:{i:0;a:2:{s:3:"cid";s:1:"1";s:4:"name";s:7:"Default";}}',0);
INSERT INTO wiki_user(email,username,`password`,`lastip`,groupid,credit1,credit2,creates,regtime) VALUES ('{$admin_email}', '{$admin_master}', '$adminpwd', '{$_SERVER[REMOTE_ADDR]}',4,20,20,32,'$regtime') ;
INSERT INTO wiki_creditdetail(`uid`,`operation`,`credit1`,`credit2`,`time`) VALUES (1,'user-register',20,20,$regtime);

INSERT INTO wiki_regular (`id`, `name`, `regular`, `type`, `regulargroupid`) VALUES
	(1, '首页', 'index-default', 0, 18),
	(2, '更改风格', 'index-settheme', 0, 18),
	(3, '图片上传', 'attachment-uploadimg', 0, 20),
	(4, '附件下载（附件）', 'attachment-download', 0, 18),
	(5, '删除附件（附件）', 'attachment-remove', 0, 20),
	(6, '浏览分类', 'category-default|category-ajax', 0, 18),
	(7, '浏览具体分类', 'category-view', 0, 18),
	(8, '分类下字母顺序浏览', 'category-letter', 0, 18),
	(9, '按字母顺序浏览（排行榜）', 'list-letter', 0, 18),
	(10, '最近更新词条（排行榜）', 'list-default|list-recentchange', 0, 18),
	(13, '用户人气词条列表(排行榜)', 'list-popularity', 0, 18),
	(14, '推荐词条列表(排行榜)', 'list-focus', 0, 18),
	(15, '浏览词条', 'doc-view', 0, 18),
	(16, '创建词条', 'doc-create', 0, 20),
	(17, '验证词条是否存在', 'doc-verify', 0, 20),
	(18, '编辑词条', 'doc-edit', 0, 20),
	(19, '分段编辑词条', 'doc-editsection', 0, 20),
	(20, '刷新编辑锁', 'doc-refresheditlock', 0, 20),
	(21, '取消编辑锁', 'doc-unseteditlock', 0, 20),
	(22, '浏览词条内链', 'doc-innerlink', 0, 18),
	(23, '浏览词条摘要', 'doc-summary', 0, 18),
	(24, '浏览词条贡献者', 'doc-editor', 0, 18),
	(25, '沙盒', 'doc-sandbox', 0, 20),
	(26, '设置推荐词条（前台词条管理）', 'doc-setfocus', 0, 20),
	(27, '移动词条分类（前台词条管理）', 'doc-getcategroytree|doc-changecategory', 0, 20),
	(28, '更改词条名（前台词条管理）', 'doc-changename', 0, 20),
	(29, '锁定词条（前台词条管理）', 'doc-lock', 0, 20),
	(30, '解除词条锁定（前台词条管理）', 'doc-unlock', 0, 20),
	(31, '审核词条（前台词条管理）', 'doc-audit', 0, 20),
	(32, '删除词条（前台词条管理）', 'doc-remove', 0, 20),
	(33, '查看评论', 'comment-view|comment-report|comment-oppose|comment-aegis', 0, 18),
	(34, '删除评论（前台评论管理）', 'comment-remove', 0, 20),
	(35, '添加评论（前台评论管理）', 'comment-add', 0, 20),
	(36, '编辑评论（前台评论管理）', 'comment-edit', 0, 20),
	(37, '版本列表（历史版本）', 'edition-list', 0, 18),
	(38, '浏览版本（历史版本）', 'edition-view', 0, 18),
	(39, '版本对比（历史版本）', 'edition-compare', 0, 18),
	(40, '删除版本（历史版本）', 'edition-remove', 0, 20),
	(41, '优秀版本（历史版本）', 'edition-excellent', 0, 20),
	(42, '取消优秀（历史版本）', 'edition-unexcellent', 0, 20),
	(43, '复制版本（历史版本）', 'edition-copy', 0, 20),
	(44, '进入词条（搜索）', 'search-default', 0, 18),
	(45, '全文搜索（搜索）', 'search-fulltext|search-kw', 0, 18),
	(46, 'TAG搜索（搜索）', 'search-tag', 0, 18),
	(47, '用户注册（用户）', 'user-register', 0, 19),
	(48, '用户登录（用户）', 'user-login', 0, 19),
	(49, '检测用户（用户）', 'user-check|user-checkusername|user-checkcode|user-checkpassword|user-checkoldpass|user-checkemail', 0, 19),
	(50, '用户注销（用户）', 'user-logout', 0, 19),
	(51, '个人信息（用户）', 'user-profile', 0, 19),
	(52, '个人信息设置（用户）', 'user-editprofile', 0, 19),
	(53, '修改密码（用户）', 'user-editpass', 0, 19),
	(54, '修改头像（用户）', 'user-editimage|user-editimageifeam|user-cutimage', 0, 19),
	(55, '找回密码（用户）', 'user-getpass', 0, 19),
	(56, '显示验证码（用户）', 'user-code', 0, 19),
	(57, '个人空间（用户）', 'user-space', 0, 19),
	(58, '清除cookies（用户）', 'user-clearcookies', 0, 19),
	(59, 'IP禁止', 'admin_banned-default', 1, 21),
	(60, '分类管理列表（分类管理）', 'admin_category-default|admin_category-list', 1, 25),
	(61, '添加分类（分类管理）', 'admin_category-add', 1, 25),
	(62, '编辑分类（分类管理）', 'admin_category-edit', 1, 25),
	(63, '删除分类（分类管理）', 'admin_category-remove', 1, 25),
	(64, '分类排序（分类管理）', 'admin_category-reorder', 1, 25),
	(65, '分类合并（分类管理）', 'admin_category-merge', 1, 25),
	(66, '数据库备份（数据库管理）', 'admin_db-backup', 1, 27),
	(67, '数据库还原（数据库管理）', 'admin_db-import', 1, 27),
	(68, '删除数据文件（数据库管理）', 'admin_db-remove', 1, 27),
	(69, '数据库列表（数据库管理）', 'admin_db-tablelist', 1, 27),
	(70, '数据库优化（数据库管理）', 'admin_db-optimize', 1, 27),
	(71, '数据库修复（数据库管理）', 'admin_db-repair', 1, 27),
	(72, '下载数据文件（数据库管理）', 'admin_db-downloadfile', 1, 27),
	(73, '词条列表（管理词条）', 'admin_doc-default', 1, 23),
	(74, '搜索词条（管理词条）', 'admin_doc-search', 1, 23),
	(75, '审核词条（管理词条）', 'admin_doc-audit', 1, 23),
	(76, '推荐词条（管理词条）', 'admin_doc-recommend', 1, 23),
	(77, '锁定词条（管理词条）', 'admin_doc-lock', 1, 23),
	(78, '解锁词条（管理词条）', 'admin_doc-unlock', 1, 23),
	(79, '删除词条（管理词条）', 'admin_doc-remove', 1, 23),
	(80, '移动词条（管理词条）', 'admin_doc-move', 1, 23),
	(81, '重命名词条（管理词条）', 'admin_doc-rename', 1, 23),
	(82, '搜索评论（后台管理评论）', 'admin_comment-default|admin_comment-search', 1, 23),
	(83, '删除评论（后台管理评论）', 'admin_comment-delete', 1, 23),
	(84, '搜索附件（后台管理附件）', 'admin_attachment-default|admin_attachment-search', 1, 23),
	(85, '删除附件（后台管理附件）', 'admin_attachment-remove', 1, 23),
	(86, '下载附件（后台管理附件）', 'admin_attachment-download', 1, 23),
	(87, '推荐词条列表（推荐词条）', 'admin_focus-focuslist', 1, 23),
	(88, '删除推荐词条（推荐词条）', 'admin_focus-remove', 1, 23),
	(89, '更改推荐词条顺序（推荐词条）', 'admin_focus-reorder', 1, 23),
	(90, '编辑推荐词条（推荐词条）', 'admin_focus-edit', 1, 23),
	(91, '更新图片（推荐词条）', 'admin_focus-updateimg', 1, 23),
	(92, '词条显示数量设置（推荐词条）', 'admin_focus-numset', 1, 23),
	(93, '友情链接列表（友情链接）', 'admin_friendlink-default', 1, 21),
	(94, '添加友情链接（友情链接）', 'admin_friendlink-add', 1, 21),
	(95, '编辑友情链接（友情链接）', 'admin_friendlink-edit', 1, 21),
	(96, '删除友情链接（友情链接）', 'admin_friendlink-remove', 1, 21),
	(97, '更新友情链接顺序（友情链接）', 'admin_friendlink-updateorder', 1, 21),
	(98, '语言列表（语言）', 'admin_language-default', 1, 26),
	(99, '添加语言（语言）', 'admin_language-addlang', 1, 26),
	(100, '编辑语言（语言）', 'admin_language-editlang', 1, 26),
	(101, '更新语言（语言）', 'admin_language-updatelanguage', 1, 26),
	(102, '设置默认语言（语言）', 'admin_language-setdefaultlanguage', 1, 26),
	(103, '管理员登录（后台登录）', 'admin_main-login|admin_main-default', 1, 21),
	(104, '管理员退出（后台登录）', 'admin_main-logout', 1, 21),
	(105, '后台框架（后台登录）', 'admin_main-mainframe', 1, 21),
	(106, '后台新版本提示（后台登录）', 'admin_main-update', 1, 21),
	(107, '插件列表（插件管理）', 'admin_plugin-list|admin_plugin-default|admin_plugin-manage|admin_plugin-will|admin_plugin-find|admin_plugin-share', 1, 22),
	(108, '安装插件（插件管理）', 'admin_plugin-install', 1, 22),
	(109, '卸载插件（插件管理）', 'admin_plugin-uninstall', 1, 22),
	(110, '启用插件（插件管理）', 'admin_plugin-start', 1, 22),
	(111, '停用插件（插件管理）', 'admin_plugin-stop', 1, 22),
	(112, '插件变量（插件管理）', 'admin_plugin-setvar', 1, 22),
	(113, '插件钩子（插件管理）', 'admin_plugin-hook', 1, 22),
	(114, '规则列表(管理权限)', 'admin_regular-list|admin_regular-default', 1, 24),
	(115, '添加规则(管理权限)', 'admin_regular-add', 1, 24),
	(116, '编辑规则(管理权限)', 'admin_regular-edit', 1, 24),
	(117, '删除规则(管理权限)', 'admin_regular-remove', 1, 24),
	(118, '基本设置(网站管理)', 'admin_setting-base', 1, 21),
	(119, '上传logo(网站管理)', 'admin_setting-logo', 1, 21),
	(120, '经验设置(网站管理)', 'admin_setting-credit', 1, 21),
	(121, 'seo设置(网站管理)', 'admin_setting-seo', 1, 21),
	(122, '缓存页面(网站管理)', 'admin_setting-cache', 1, 21),
	(123, '更新缓存设置(网站管理)', 'admin_setting-renewcache', 1, 21),
	(124, '清除缓存(网站管理)', 'admin_setting-removecache', 1, 21),
	(125, '附件设置(网站管理)', 'admin_setting-attachment', 1, 21),
	(126, '邮件设置(网站管理)', 'admin_setting-mail', 1, 21),
	(127, '风格列表（风格）', 'admin_style-default', 1, 26),
	(128, '创建模版风格页面（风格）', 'admin_style-create', 1, 26),
	(129, '删除风格（风格）', 'admin_style-removestyle', 1, 26),
	(131, '设置默认风格（风格）', 'admin_style-setdefaultstyle', 1, 26),
	(132, '热门标签设置（热门标签）', 'admin_tag-hottag', 1, 23),
	(133, '列表|添加|删除（定时任务）', 'admin_task-default', 1, 21),
	(134, '启用|停用（定时任务）', 'admin_task-taskstatus', 1, 21),
	(135, '编辑定时任务（定时任务）', 'admin_task-edittask', 1, 21),
	(136, '执行定时任务（定时任务）', 'admin_task-run', 1, 21),
	(137, '用户列表（管理用户）', 'admin_user-default|admin_user-list', 1, 24),
	(138, '添加用户（管理用户）', 'admin_user-add', 1, 24),
	(139, '编辑用户（管理用户）', 'admin_user-edit', 1, 24),
	(140, '删除用户（管理用户）', 'admin_user-remove', 1, 24),
	(141, '用户组列表（管理用户组）', 'admin_usergroup-default|admin_usergroup-list', 1, 24),
	(142, '添加用户组（管理用户组）', 'admin_usergroup-add', 1, 24),
	(143, '编辑用户组（管理用户组）', 'admin_usergroup-edit', 1, 24),
	(144, '删除用户组（管理用户组）', 'admin_usergroup-remove', 1, 24),
	(145, '关键词过滤(词语过滤)', 'admin_word-default', 1, 23),
	(146, '裁剪图片', 'user-cutoutimage', 0, 19),
	(147, '上周贡献榜', 'list-weekuserlist', 0, 18),
	(148, '总贡献榜', 'list-allcredit', 0, 18),
	(149, '修改用户组(管理用户组)', 'admin_usergroup-change', 1, 24),
	(150, 'Rss订阅', 'list-rss', 0, 18),
	(151, '后台操作记录(网站管理)', 'admin_log-default', 1, 21),
	(152, '查收短消息', 'pms-default|pms-box|pms-setread', 0, 19),
	(153, '删除短消息', 'pms-remove', 0, 19),
	(154, '发送短消息', 'pms-sendmessage|pms-checkrecipient', 0, 19),
	(155, '忽略列表', 'pms-blacklist', 0, 19),
	(156, '站内公告(网站管理)', 'admin_setting-notice', 1, 21),
	(157, '删除同义词(前台同义词管理)', 'synonym-removesynonym', 0, 20),
	(158, '查看同义词(前台同义词管理)', 'synonym-view', 0, 20),
	(159, '编辑同义词(前台同义词管理)', 'synonym-savesynonym', 0, 20),
	(160, '同义词列表(后台同义词管理)', 'admin_synonym-default', 1, 23),
	(161, '搜索同义词(后台同义词管理)', 'admin_synonym-search', 1, 23),
	(162, '删除同义词(后台同义词管理)', 'admin_synonym-delete', 1, 23),
	(163, '编辑同义词(后台同义词管理)', 'admin_synonym-save', 1, 23),
	(164, '基本概况统计(后台统计)', 'admin_statistics-stand', 1, 28),
	(165, '分类排行榜(后台统计)', 'admin_statistics-cat_toplist', 1, 28),
	(166, '词条排行榜(后台统计)', 'admin_statistics-doc_toplist', 1, 28),
	(167, '编辑排行榜(后台统计)', 'admin_statistics-edit_toplist', 1, 28),
	(168, '经验排行榜(后台统计)', 'admin_statistics-credit_toplist', 1, 28),
	(169, '管理团队(后台统计)', 'admin_statistics-admin_team', 1, 28),
	(170, 'UC经验兑换', 'exchange-default', 2, 19),
	(174, '词条免检', 'doc-immunity', 0, 20),
	(176, '编辑模版文件（风格）', 'admin_style-editxml', 1, 26),
	(177, '编辑模版描述文件（风格）', 'admin_style-edit', 1, 26),
	(178, '读取模版文件（风格）', 'admin_style-readfile', 1, 26),
	(179, '保存模版文件（风格）', 'admin_style-savefile', 1, 26),
	(181, '卸载模版（风格）', 'admin_style-removestyle', 1, 26),
	(183, '可安装模版列表（风格）', 'admin_style-list', 1, 26),
	(184, '安装模版（风格）', 'admin_style-install', 1, 26),
	(185, '显示广告列表', 'admin_adv-default', 0, 21),
	(186, '设置广告加载方式', 'admin_adv-config', 0, 21),
	(187, '搜索广告(后台)', 'admin_adv-search', 0, 21),
	(188, '添加广告', 'admin_adv-add', 0, 21),
	(189, '编辑广告', 'admin_adv-edit', 0, 21),
	(190, '删除广告', 'admin_adv-remove', 0, 21),
	(191, '审核用户', 'admin_user-checkup', 1, 24),
	(192, '待审核用户列表', 'admin_user-uncheckeduser', 1, 24),
	(193, '注册控制', 'admin_setting-baseregister', 0, 21),
	(201, '随便看看', 'doc-random', 0, 18),
	(202, '此词条对我有用', 'doc-vote', 0, 18),
	(203, '创建新风格页面', 'admin_style-add', 0, 26),
	(204, '创建新风格', 'admin_style-createstyle', 0, 26),
	(206, '频道列表（频道）', 'admin_channel-default', 1, 21),
	(207, '添加频道（频道）', 'admin_channel-add', 1, 21),
	(208, '编辑频道（频道）', 'admin_channel-edit', 1, 21),
	(209, '删除频道（频道）', 'admin_channel-remove', 1, 21),
	(210, '修改频道显示顺序（频道）', 'admin_channel-updateorder', 1, 21),
	(211, '添加修改参考资料', 'reference-add', 0, 20),
	(212, '删除参考资料', 'reference-remove', 0, 20),
	(213, '上传附件', 'attachment-upload', 0, 20),
	(214, '取消焦点词条', 'doc-removefocus', 0, 20),
	(215, '自动保存', 'doc-autosave', 0, 20),
	(216, '删除自动保存的词条', 'doc-delsave', 0, 24),
	(217, '自动保存管理', 'doc-managesave', 0, 24),
	(218, '通行证权限', 'passport_client-login|passport_client-logout', 0, 21),
	(219, '取消推荐词条（管理词条）', 'admin_doc-cancelrecommend', 0, 23),
	(220, '相关词条权限', 'doc-getrelateddoc|doc-addrelatedoc', 0, 20),
	(251, '回收站管理', 'admin_recycle-default|admin_recycle-search|admin_recycle-remove|admin_recycle-recover|admin_recycle-clear', 1, 23),
	(252, 'SQL查询窗口（数据库管理）', 'admin_db-sqlwindow', 1, 27),
	(253, '服务器信息', 'admin_log-phpinfo', 1, 21),
	(254, '模版高级编辑', 'admin_style-advancededit', 1, 26),
	(255, '待完善词条', 'admin_cooperate-default', 1, 23),
	(256, '热门搜索', 'admin_hotsearch-default', 1, 23),
	(257, '图片百科', 'admin_image-default|admin_image-editimage|admin_image-remove', 1, 23),
	(258, '相关词条', 'admin_relation-default', 1, 23),
	(259, '前台相关词条', 'doc-cooperate', 0, 18),
	(260, '版本评审', 'admin_edition-default|admin_edition-search|admin_edition-addcoin|admin_edition-excellent|admin_edition-remove', 1, 23),
	(261, '添加金币(管理用户)', 'admin_user-addcoins', 1, 24),
	(262, '礼品商店', 'admin_gift-default|admin_gift-view|admin_gift-search|admin_gift-add|admin_gift-edit|admin_gift-remove|admin_gift-available|admin_gift-price|admin_gift-notice|admin_gift-log|admin_gift-verify', 1, 23),
	(263, '礼品商店', 'gift-default|gift-view|gift-search|gift-apply', 0, 18),
	(264, '通行证设置', 'admin_setting-passport', 1, 21),
	(265, '图片百科', 'pic-piclist|pic-view|pic-ajax|pic-search', 0, 18),
	(266, '群发短消息', 'pms-publicmessage', 0, 19),
	(267, '防采集设置', 'admin_setting-anticopy', 1, 21),
	(268, '图片水印', 'admin_setting-watermark|admin_setting-preview', 1, 21),
	(269, '后台列表显示', 'admin_setting-listdisplay', 1, 21),
	(270, '防灌水设置', 'admin_setting-sec', 1, 21),
	(271, '验证码设置', 'admin_setting-code', 0, 21),
	(272, '时间设置', 'admin_setting-time', 0, 21),
	(273, 'COOKIE设置', 'admin_setting-cookie', 0, 21),
	(274, '词条设置', 'admin_setting-docset', 0, 21),
	(275, '首页设置', 'admin_setting-index', 0, 21),
	(276, '搜索设置', 'admin_setting-search', 0, 21),
	(277, '数据JS调用', 'datacall-js', 2, 18),
	(278, '收藏词条', 'user-addfavorite', 2, 20),
	(279, '删除词条收藏', 'user-removefavorite|user-exchange', 2, 20),
	(280, '添加编辑分类（分类管理）', 'admin_category-batchedit',1, 25),
	(281, '云搜索-词条列表', 'archiver-default|archiver-list|archiver-view',2, 20),
	(282, '邀请注册', 'user-invite', 0, 19),
	(283, '邮件提醒设置(网站管理)', 'admin_setting-noticemail', 1, 21),
	(284, 'Sitemap管理', 'admin_sitemap-default|admin_sitemap-setting|admin_sitemap-createdoc|admin_sitemap-updatedoc|admin_sitemap-submit|admin_sitemap-baiduxml', 1, 21),
	(285, '云搜索中介页面', 'search-agent', 2, 18),
	(286, '百科联盟', 'admin_hdapi-set|admin_hdapi-nosynset|admin_hdapi-down|admin_hdapi-info|admin_hdapi-default|admin_hdapi-siteuserinfo|admin_hdapi-titles|admin_hdapi-import|admin_hdapi-rolldocs|admin_hdapi-registercheck|admin_hdapi-login|admin_hdapi-privatedoc', 1, 21),
	(287, '数据库大小', 'admin_main-datasize', 2, 27),
	(288, '后台快捷菜单', 'admin_setting-shortcut', 2, 21),
	(289, '数据库存储设置', 'admin_db-storage|admin_db-convert', 2, 27),
	(290, '设置词条首字母', 'doc-editletter', 0, 20),
	(291, '分享词条', 'admin_share-default|admin_share-search|admin_share-share|admin_share-set', 2, 23),
	(292, '模板编辑', 'admin_theme-default|admin_theme-editxml|admin_theme-add|admin_theme-create|admin_theme-createstyle|admin_theme-edit|admin_theme-codeedit|admin_theme-delbak|admin_theme-baseedit|admin_theme-advancededit|admin_theme-readfile|admin_theme-savefile|admin_theme-removestyle|admin_theme-setdefaultstyle|admin_theme-list|admin_theme-install|admin_theme-saveblock|admin_theme-delblock|admin_theme-preview|admin_theme-addblock|admin_theme-getconfig|admin_theme-savetemp', 0, 21),
	(293, '数据调用相关操作', 'admin_datacall-default|admin_datacall-list|admin_datacall-search|admin_datacall-view|admin_datacal', 2, 23),
	(294, '后台菜单搜索', 'admin_actions-default', 1, 20),
	(295, '木马扫描', 'admin_filecheck-docreate|admin_safe-default|admin_safe-setting|admin_safe-scanfile|admin_safe-validate|admin_safe-scanfuns|admin_safe-list|admin_safe-editcode|admin_safe-del', 2, 21),
	(296, '自动升级', 'admin_upgrade-default|admin_upgrade-check|admin_upgrade-initpage|admin_upgrade-install', 1, 21),
	(297, 'Map', 'admin_actions-map', 1, 20),
	(298, '导航模块', 'admin_nav-default|admin_nav-search|admin_nav-add|admin_nav-hotdocs|admin_nav-searchdocs|admin_nav-catedoc|admin_nav-check|admin_nav-del|admin_nav-editdoc|admin_nav-editnav|admin_navmodel-default|admin_navmodel-add|admin_navmodel-getmodel|admin_navmodel-del|admin_navmodel-status', 1, 21);
	
INSERT INTO wiki_language (`name`, `available`, `path`, `copyright`) VALUES 
	('简体中文', 1, 'zh', 'baike.com');

INSERT INTO wiki_theme (`name`, `available`, `path`, `copyright`, `css`) VALUES
	('默认风格', 1, 'default', 'baike.com', 'a:18:{s:8:"bg_color";s:11:"transparent";s:14:"left_framcolor";s:7:"#e6e6e6";s:16:"leftitle_bgcolor";s:7:"#f7f7f8";s:18:"leftitle_framcolor";s:7:"#efefef";s:16:"middle_framcolor";s:7:"#eaf1f6";s:19:"middletitle_bgcolor";s:7:"#eaf6fd";s:21:"middletitle_framcolor";s:7:"#c4d2db";s:15:"right_framcolor";s:7:"#cef2e0";s:17:"rightitle_bgcolor";s:7:"#cef2e0";s:19:"rightitle_framcolor";s:7:"#a3bfb1";s:13:"nav_framcolor";s:7:"#e1e1e1";s:11:"nav_bgcolor";s:7:"#aaaeb1";s:13:"nav_linkcolor";s:4:"#fff";s:13:"nav_overcolor";s:4:"#ff0";s:8:"nav_size";s:4:"14px";s:10:"bg_imgname";s:11:"html_bg.jpg";s:13:"titbg_imgname";s:10:"col_bg.jpg";s:4:"path";s:7:"default";}');


INSERT INTO wiki_regulargroup (`id`, `title`, `size`, `type`) VALUES
	(18, '页面浏览', 0, 0),
	(19, '用户操作', 0, 0),
	(20, '词条管理', 0, 0),
	(21, '网站管理', 0, 1),
	(22, '插件管理', 0, 1),
	(23, '内容管理', 0, 1),
	(24, '用户管理', 0, 1),
	(25, '分类管理', 0, 1),
	(26, '语言/风格', 0, 1),
	(27, '数据库管理', 0, 1),
	(28, '站内统计', 0, 1);

INSERT INTO wiki_block (`id`, `theme`, `file`, `area`, `areaorder`, `block`, `fun`, `tpl`, `params`, `modified`) VALUES
(1, 'default', 'index', 'ctop1', 0, 'doc', 'hotdocs', 'hotdocs.htm', 'a:2:{s:3:"num";s:0:"";s:5:"style";s:0:"";}', NULL),
(2, 'default', 'index', 'ctop2', 0, 'doc', 'wonderdocs', 'wonderdocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(3, 'default', 'index', 'right', 0, 'user', 'login', 'login.htm', '', NULL),
(4, 'default', 'index', 'right', 1, 'news', 'recentnews', 'recentnews.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(5, 'default', 'index', 'right', 2, 'doc', 'cooperatedocs', 'cooperatedocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(6, 'default', 'index', 'dbottomr', 0, 'doc', 'recentdocs', 'recentdocs.htm', 'a:2:{s:3:"num";s:0:"";s:5:"style";s:0:"";}', NULL),
(7, 'default', 'index', 'cbottoml', 0, 'comment', 'recentcomment', 'recentcomment.htm', 'a:1:{s:3:"num";s:0:"";}', NULL),
(8, 'default', 'index', 'cbottomr', 0, 'pic', 'recentpics', 'recentpics.htm', 'a:1:{s:3:"num";s:0:"";}', NULL),
(9, 'default', 'index', 'cbottomr', 1, 'doc', 'commenddocs', 'commenddocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(10, 'default', 'index', 'bottom', 0, 'doc', 'hottags', 'hottags.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(11, 'default', 'giftlist', 'price', 0, 'gift', 'giftpricerange', 'giftpricerange.htm', '', NULL),
(12, 'default', 'giftlist', 'right', 0, 'gift', 'giftnotice', 'giftnotice.htm', '', NULL),
(13, 'default', 'categorylist', 'right', 0, 'doc', 'hottags', 'hottags.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(14, 'default', 'categorylist', 'right', 1, 'doc', 'getletterdocs', 'getletterdocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(15, 'default', 'viewcomment', 'right', 0, 'doc', 'hotcommentdocs', 'hotcommentdocs.htm', 'a:1:{s:3:"num";s:2:"10";}', NULL),
(16, 'default', 'index', 'bottom', 1, 'links', 'friendlinks', 'friendlinks.htm', 'a:1:{s:5:"style";s:0:"";}', NULL),
(17, 'default', 'index', 'right', 3, 'doc', 'getletterdocs', 'getletterdocs.htm', 'a:1:{s:5:"style";s:0:"";}', NULL);

INSERT INTO wiki_datacall (`id`, `name`, `type`, `category`, `classname`, `function`, `desc`, `param`, `cachetime`, `available`) VALUES
(1, 'doc_most_visited', 'sql', 'doc', 'sql', 'sql', '访问最多词条', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:84:"select did,title,summary from {DB_TABLEPRE}doc where 1 order by views desc limit 10;";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(2, 'user_total_num', 'sql', 'user', 'sql', 'sql', '注册会员数', 'a:9:{s:7:"tplcode";s:9:"[usernum]";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:49:"SELECT COUNT(*) AS usernum FROM {DB_TABLEPRE}user";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(3, 'doc_total_num', 'sql', 'doc', 'sql', 'sql', '网站所有词条数', 'a:9:{s:7:"tplcode";s:20:"doc total num: [num]";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:0:"";s:6:"select";s:18:"count(did) as num ";s:4:"from";s:16:"{DB_TABLEPRE}doc";s:5:"where";s:1:"1";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:3:"0,1";}', 1000, 1),
(4, 'doc_most_comment', 'sql', 'doc', 'sql', 'sql', '评论最多词条列表', 'a:9:{s:7:"tplcode";s:181:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]([num])</a></dt><dd>[comment][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd></dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:0:"";s:6:"select";s:44:"d.did,d.title,c.comment, count(c.did) AS num";s:4:"from";s:68:"{DB_TABLEPRE}doc AS d LEFT JOIN {DB_TABLEPRE}comment AS c USING(did)";s:5:"where";s:0:"";s:5:"other";s:14:"GROUP BY c.did";s:7:"orderby";s:8:"num desc";s:5:"limit";s:4:"0,10";}', 1000, 1),
(5, 'doc_recommends', 'sql', 'doc', 'sql', 'sql', '推荐词条信息', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:91:"select did,title,summary from {DB_TABLEPRE}focus where `type`=1 order by did desc limit 10;";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(6, 'doc_wonderful', 'sql', 'doc', 'sql', 'sql', '精彩词条', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:91:"select did,title,summary from {DB_TABLEPRE}focus where `type`=3 order by did desc limit 10;";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(7, 'user_new_register', 'sql', 'user', 'sql', 'sql', '最新注册用户', 'a:9:{s:7:"tplcode";s:104:"<dl class="col-dl "><dt><a href="index.php?user-space-[uid]"> [username]</a></dt><dd>[regtime]</dd></dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:109:"SELECT uid,username,from_unixtime( regtime ) as regtime  FROM {DB_TABLEPRE}user order by regtime desc limit 1";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(8, 'cat_doc', 'sql', 'doc', 'sql', 'sql', '调用某个分类下的词条,不包括子分类；\r\n默认是调用分类ID等于10的词条,\r\n如需调用其它分类下词条，修改“SQL完整表达式”下面cid=10中10的值', 'a:9:{s:7:"tplcode";s:175:"<dl class="col-dl "><dt><a title="[title]" href="index.php?doc-view-[did]"> [title]</a></dt><dd>[summary][<a class="entry" href="index.php?doc-view-[did]">view</a>]</dd>	</dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:116:"select did,title,summary from {DB_TABLEPRE}doc WHERE did IN (select did from {DB_TABLEPRE}categorylink where cid=10)";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1),
(9, 'cat_subcat', 'sql', 'category', 'sql', 'sql', '调用某个分类下子分类,\r\n默认是调用分类ID等于3下子分类,\r\n如需调用其它分类下子分类，修改“SQL完整表达式”下面pid=3的pid值', 'a:9:{s:7:"tplcode";s:101:"<dl class="col-dl "><dd><a title="[title]" href="index.php?category-view-[cid]"> [name]</a></dd></dl>";s:13:"empty_tplcode";s:0:"";s:3:"sql";s:57:"select  cid, name  from {DB_TABLEPRE}category where pid=3";s:6:"select";s:0:"";s:4:"from";s:0:"";s:5:"where";s:0:"";s:5:"other";s:0:"";s:7:"orderby";s:0:"";s:5:"limit";s:4:"0,10";}', 1000, 1);
INSERT INTO wiki_regular_relation (`idleft`, `idright`) VALUES
	(5, 3),
	(5, 18),
	(19, 18),
	(21, 20),
	(30, 29),
	(34, 36),
	(40, 43),
	(40, 41),
	(40, 42),
	(48, 50),
	(52, 51),
	(53, 51),
	(54, 51),
	(62, 60),
	(63, 64),
	(63, 65),
	(63, 60),
	(63, 61),
	(63, 62),
	(65, 64),
	(67, 70),
	(67, 69),
	(67, 71),
	(67, 72),
	(67, 68),
	(67, 66),
	(68, 72),
	(68, 71),
	(68, 70),
	(68, 69),
	(68, 66),
	(71, 69),
	(79, 162),
	(79, 83),
	(79, 80),
	(79, 78),
	(79, 77),
	(79, 75),
	(79, 85),
	(79, 81),
	(79, 73),
	(88, 87),
	(88, 89),
	(88, 90),
	(88, 92),
	(88, 91),
	(96, 95),
	(96, 93),
	(96, 94),
	(96, 97),
	(100, 98),
	(100, 99),
	(100, 101),
	(100, 102),
	(102, 101),
	(109, 108),
	(109, 113),
	(109, 112),
	(109, 111),
	(109, 110),
	(109, 107),
	(117, 114),
	(117, 116),
	(117, 115),
	(129, 127),
	(129, 131),
	(129, 130),
	(129, 128),
	(131, 130),
	(140, 137),
	(140, 138),
	(140, 139),
	(144, 143),
	(144, 142),
	(144, 141),
	(152, 51),
	(153, 51),
	(153, 154),
	(153, 155),
	(153, 152),
	(154, 51),
	(155, 51),
	(157, 159),
	(157, 158),
	(162, 161),
	(162, 160),
	(162, 163);
INSERT INTO wiki_navmodel VALUES (1, '简语+链接集合', '<p class="bor-ccc bg_g">与“物质文化”相对，人类在社会历史实践过程中所创造的各种精神文化。本任务即盘点我国民间的舞蹈。</p>\r\n<table class="table">\r\n	<tr>\r\n		<td>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%9C%9D%E9%98%B3%E6%B0%91%E9%97%B4%E7%A7%A7%E6%AD%8C"  target="_blank" title="朝阳民间秧歌">\r\n		朝阳民间秧歌</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%A4%A7%E6%A0%85%E6%A0%8F%E4%BA%94%E6%96%97%E6%96%8B%E9%AB%98%E8%B7%B7%E7%A7%A7%E6%AD%8C"  target="_blank"title="大栅栏五斗斋高跷秧歌">\r\n		大栅栏五斗斋高跷秧歌</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%BA%AC%E8%A5%BF%E5%A4%AA%E5%B9%B3%E9%BC%93" target="_blank" title="京西太平鼓">\r\n		京西太平鼓</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%89%93%E9%94%A3%E9%95%B2" target="_blank" title="打锣镲">\r\n		打锣镲</a> <br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%9C%AC%E6%BA%AA%E7%A4%BE%E7%81%AB" target="_blank" title="本溪社火">\r\n		本溪社火</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%82%A3%E6%97%8F%E7%99%BD%E8%B1%A1%E8%88%9E" target="_blank" title="傣族白象舞">\r\n		傣族白象舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%AF%86%E4%BA%91%E8%9D%B4%E8%9D%B6%E4%BC%9A" target="_blank" title="密云蝴蝶会">\r\n		密云蝴蝶会</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%BB%B6%E5%BA%86%E6%97%B1%E8%88%B9" target="_blank" title="延庆旱船">\r\n		延庆旱船</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%80%9A%E5%B7%9E%E8%BF%90%E6%B2%B3%E9%BE%99%E7%81%AF" target="_blank" title="通州运河龙灯">\r\n		通州运河龙灯</a> <br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%9D%BF%E5%87%B3%E9%BE%99" target="_blank" title="板凳龙">\r\n		板凳龙</a> </td>\r\n		<td>\r\n		<p>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%87%91%E5%B7%9E%E9%BE%99%E8%88%9E" target="_blank" title="金州龙舞">\r\n		金州龙舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%9D%92%E9%BE%99%E5%9C%AA%E6%A0%8F%E6%A3%92" target="_blank" title="青龙圪栏棒">\r\n		青龙圪栏棒</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%9B%96%E5%B7%9E%E9%AB%98%E8%B7%B7%E7%A7%A7%E6%AD%8C" target="_blank" title="盖州高跷秧歌">\r\n		盖州高跷秧歌</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%8A%E5%8F%A3%E5%AD%90%E9%AB%98%E8%B7%B7%E7%A7%A7%E6%AD%8C" target="_blank" title="上口子高跷秧歌">\r\n		上口子高跷秧歌</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%AE%89%E5%BA%B7%E5%B0%8F%E5%9C%BA%E5%AD%90" target="_blank" title="安康小场子">\r\n		安康小场子</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%80%9A%E5%9F%8E%E6%8B%8D%E6%89%93" target="_blank" title="通城拍打">\r\n		通城拍打</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%99%BD%E7%BA%B8%E5%9D%8A%E5%A4%AA%E7%8B%AE" target="_blank" title="白纸坊太狮">\r\n		白纸坊太狮</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%9C%E5%82%A8%E5%8F%8C%E9%BE%99%E4%BC%9A" target="_blank" title="东储双龙会">\r\n		东储双龙会</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%92%B5%E8%8A%B1" target="_blank" title="撵花">\r\n		撵花</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%98%93%E5%8E%BF%E6%91%86%E5%AD%97%E9%BE%99%E7%81%AF" target="_blank" title="易县摆字龙灯">\r\n		易县摆字龙灯</a></p>\r\n		</td>\r\n		<td>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%9E%91%E5%AD%90%E7%A7%A7%E6%AD%8C" target="_blank" title="鞑子秧歌">\r\n		鞑子秧歌</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E8%B5%AB%E5%93%B2%E6%97%8F%E8%90%A8%E6%BB%A1%E8%88%9E" target="_blank" title="赫哲族萨满舞">\r\n		赫哲族萨满舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%B1%B3%E7%B2%AE%E5%B1%AF%E9%AB%98%E8%B7%B7" target="_blank" title="米粮屯高跷">\r\n		米粮屯高跷</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E7%81%AB%E7%BB%AB%E5%AD%90%E4%BC%9E%E8%88%9E" target="_blank" title="火绫子伞舞">\r\n		火绫子伞舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%85%AB%E9%97%BD%E5%8D%83%E5%A7%BF%E8%88%9E" target="_blank" title="八闽千姿舞">\r\n		八闽千姿舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%BE%B7%E5%AE%89%E6%BD%98%E5%85%AC%E6%88%8F" target="_blank" title="德安潘公戏">\r\n		德安潘公戏</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B9%8C%E6%8B%89%E6%BB%A1%E6%97%8F%E7%A7%A7%E6%AD%8C" target="_blank" title="乌拉满族秧歌">\r\n		乌拉满族秧歌</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%98%8C%E5%B9%B3%E5%90%8E%E7%89%9B%E5%9D%8A%E6%9D%91%E8%8A%B1%E9%92%B9%E5%A4%A7%E9%BC%93" target="_blank" title="昌平后牛坊村花钹大鼓">\r\n		昌平后牛坊村花钹大鼓</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E8%B5%9E%E7%9A%87%E9%93%81%E9%BE%99%E7%81%AF" target="_blank" title="赞皇铁龙灯">\r\n		赞皇铁龙灯</a>\r\n		</td>\r\n		<td>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%86%89%E9%BE%99%E8%88%9E" target="_blank" title="醉龙舞">醉龙舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%9A%86%E5%B0%A7%E6%8B%9B%E5%AD%90%E9%BC%93" target="_blank" title="隆尧招子鼓">\r\n		隆尧招子鼓</a> <br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%99%8B%E5%B7%9E%E5%AE%98%E4%BC%9E" target="_blank" title="晋州官伞">\r\n		晋州官伞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E9%A9%AC%E9%B9%BF%E8%88%9E" target="_blank" title="马鹿舞">\r\n		马鹿舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%B0%E5%AE%81%E8%9D%B4%E8%9D%B6%E8%88%9E" target="_blank" title="丰宁蝴蝶舞">\r\n		丰宁蝴蝶舞</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%B4%87%E4%BB%81%E8%B7%B3%E5%85%AB%E4%BB%99" target="_blank" title="崇仁跳八仙">\r\n		崇仁跳八仙</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E4%B8%89%E8%8A%82%E9%BE%99%C2%B7%E8%B7%B3%E9%BC%93" target="_blank" title="三节龙·跳鼓">\r\n		三节龙·跳鼓</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%B5%B7%E6%B7%80%E6%89%91%E8%9D%B4%E8%9D%B6" target="_blank" title="海淀扑蝴蝶">\r\n		海淀扑蝴蝶</a><br/>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E8%B4%AF%E6%BA%AA%E6%9D%91%E5%9C%B0%E5%9B%B4%E5%AD%90" target="_blank" title="贯溪村地围子">\r\n		贯溪村地围子</a>\r\n		</td>\r\n	</tr>\r\n</table>\r\n', 1),
								   (2, '两列表格', '<table class="table">
	<tr>
		<td class="w-70">网络红人</td>
		<td>红人简介</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E7%AB%A0%E6%B3%BD%E5%A4%A9" target="_blank" title="章泽天">章泽天</a></td>
		<td>女，江苏南京人，南京外国语学校学生。2009年12月在网络走红，被网友称为奶茶美眉。2011年1月4日，清华大学证实，南外高三学生章泽天的确被清华大学认定为文科保送生。</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E5%8D%96%E8%8F%9C%E5%93%A5" target="_blank" title="卖菜哥">卖菜哥</a>
		</td>
		<td>寒冬时节，两名穿着厚棉服的小伙子在乌鲁木齐一个居民小区户外，出售仅比批发价高出几毛钱的各类蔬菜，所到之处往往人头攒动，被老百姓亲切地称为“卖菜哥”。 
		<br />
		</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E8%8B%8F%E7%B4%AB%E7%B4%AB" target="_blank" title="苏紫紫">
		苏紫紫</a></td>
		<td>女，湖北省宜昌市人，中国人民大学艺术系二年级学生，也是一名人体模特。2010年12月，她在学校里办了个人体艺术私拍展，引发学校老师同学的议论，遭受白眼。</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%8A%80%E6%9C%AF%E5%A5%B3" target="_blank" title="技术女">
		技术女</a></td>
		<td>某技术女与一位名叫jack weppler 的男友分手之后，制作了N张男友的PS照片（主要是PS文字），然后上传到网上，并通过SEO，让Google 
		图片搜索jack weppler 之后会出现这些照片。</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%B5%87%E6%B0%B4%E5%93%A5" target="_blank" title="浇水哥">浇水哥</a></td>
		<td>情侣吵架纵火烧房，淡定&quot;浇水哥&quot;楼顶救火走红，上班后不久，“浇水哥”的照片被人发上了网，他淡定的动作和众网友搞笑的跟帖，一下子使他在单位“火”了。</td>
	</tr>
</table>
', 1),
								   (3, '免责声明', '<P class="bor-ccc bg_g">本站声明：该内容为网友协作，并不代表本站观点。由此产生的后果，于本站无关。</P>', 1),
								   (4, '两列表格_有标题', '<table class="table">
	<tr>
		<td class="bold" colspan="2"><strong>2010年年度十大网络红人</strong></td>
	</tr>
	<tr>
		<td class="w-70">网络红人 </td>
		<td>红人简介</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E7%AB%A0%E6%B3%BD%E5%A4%A9" target="_blank" title="章泽天">章泽天</a></td>
		<td>女，江苏南京人，南京外国语学校学生。2009年12月在网络走红，被网友称为奶茶美眉。2011年1月4日，清华大学证实，南外高三学生章泽天的确被清华大学认定为文科保送生。</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E5%8D%96%E8%8F%9C%E5%93%A5" target="_blank" title="卖菜哥">卖菜哥</a>
		</td>
		<td>寒冬时节，两名穿着厚棉服的小伙子在乌鲁木齐一个居民小区户外，出售仅比批发价高出几毛钱的各类蔬菜，所到之处往往人头攒动，被老百姓亲切地称为“卖菜哥”。 
		</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E8%8B%8F%E7%B4%AB%E7%B4%AB" target="_blank" title="苏紫紫">
		苏紫紫</a></td>
		<td>女，湖北省宜昌市人，中国人民大学艺术系二年级学生，也是一名人体模特。2010年12月，她在学校里办了个人体艺术私拍展，引发学校老师同学的议论，遭受白眼。</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%8A%80%E6%9C%AF%E5%A5%B3" target="_blank" title="技术女">
		技术女</a></td>
		<td>某技术女与一位名叫jack weppler 的男友分手之后，制作了N张男友的PS照片（主要是PS文字），然后上传到网上，并通过SEO，让Google 
		图片搜索jack weppler 之后会出现这些照片。</td>
	</tr>
	<tr>
		<td><a class="innerlink" href="index.php?doc-innerlink-%E6%B5%87%E6%B0%B4%E5%93%A5" target="_blank" title="浇水哥">浇水哥</a></td>
		<td>情侣吵架纵火烧房，淡定&quot;浇水哥&quot;楼顶救火走红，上班后不久，“浇水哥”的照片被人发上了网，他淡定的动作和众网友搞笑的跟帖，一下子使他在单位“火”了。</td>
	</tr>
</table>
', 1),
								   (5, '图文混排', '<table class="table" vertical-align="top">\r\n	<tr>\r\n		<td rowspan="2">\r\n		<div class="img img_l" style="WIDTH: 140px">\r\n			<a href="style/default/zt.jpg" target="_blank" title="主题图片">\r\n			<img alt="主题图片" src="style/default/zt.jpg" title="主题图片"/></a><br />\r\n			<strong>主题图片</strong></div>\r\n		</td>\r\n		<td class="bold">模块标题</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="p_a_m">\r\n		<p>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">\r\n		内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E5%86%85%E5%AE%B9%E8%AF%8D%E6%9D%A1" target="_blank" title="内容词条">内容词条</a><a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a>\r\n		<a class="innerlink" href="index.php?doc-innerlink-%E6%A8%A1%E5%9D%97%E6%A0%87%E9%A2%98" target="_blank" title="模块标题">\r\n		模块标题</a> </p>\r\n		</td>\r\n	</tr>\r\n</table>', 1);
INSERT INTO wiki_channel VALUES 	(1, '首页', '{$site_url}', 0, 1, 2),
									(2, '百科分类', '{$site_url}/index.php?category', 1, 1, 2),
									(3, '排行榜', '{$site_url}/index.php?list', 2, 1, 2),
									(4, '图片百科', '{$site_url}/index.php?pic-piclist-2', 3, 1, 2),
									(5, '礼品商店', '{$site_url}/index.php?gift', 4, 1, 2);								   
EOT;

 	$strtip=runquery($installsql);

/* 
	$pluginbase = new pluginbase($db);
	$pluginbase->install('hdapi');
 	$pluginbase->install('ucenter');
*/	
					if (mysql_error()) {
						$str = "<SPAN class=err>" . $strtip . ' ' . mysql_error() . "</span>";
						$nextAccess = 0;
						$extend .= '{W}'.$strtip . ' ' . mysql_error()."\n";
					}
					if($nextAccess==1){
						$str = "<div id=\"tips\">{$lang['stepSetupDelInstallDirTip']}</div>";
						$str .="<div id=\"wrapper_1\"><div class=\"col\"><br />$strcretip $msg<br /></div></div>";
					}
					if ($nextAccess == 1) {
	                    @cleardir(HDWIKI_ROOT.'/data/view');
	                    @cleardir(HDWIKI_ROOT.'/data/cache');
	                    @forceMkdir(HDWIKI_ROOT.'/data/attachment');
	                    @forceMkdir(HDWIKI_ROOT.'/data/backup');
	                    @forceMkdir(HDWIKI_ROOT.'/data/cache');
						@forceMkdir(HDWIKI_ROOT.'/data/db_backup');
	                    @forceMkdir(HDWIKI_ROOT.'/data/logs');
						@forceMkdir(HDWIKI_ROOT.'/data/tmp');
	                    @forceMkdir(HDWIKI_ROOT.'/data/view');
	                    @forceMkdir(HDWIKI_ROOT.'/data/momo');
					}
				}
			}
			break;
			case 6:
				//云搜索
				clode_register_install();
				$str ="<div id=\"wrapper\"><div class=\"col\">" .
	"<h3>添加帮助文档</h3>
	<p><input name='istestdata' type='checkbox' value='1' checked='checked' />添加帮助文档</p>
	</div></div>";
				break;
			case 7:
			//加入测试数据。
			if($_POST['istestdata']){
				require_once HDWIKI_ROOT.'/config.php';
				require_once HDWIKI_ROOT.'/lib/hddb.class.php';
				$db = new hddb(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET);
				$sqltestfile= HDWIKI_ROOT.'/install/testdata/hdwikitest.sql';
				$fp = fopen($sqltestfile, 'rb');
				$sql=fread($fp, filesize($sqltestfile));
				fclose($fp);
				$user=$db->result_first('select username from '.DB_TABLEPRE.'user where uid=1');
				if($user!='admin'){
					$sql=str_replace('admin',$user,$sql);
				}
				runquery($sql);
				
				$sql = "INSERT INTO wiki_setting (`variable`, `value`) VALUES ('hotsearch', '".serialize(array ( 0 => array ( 'name' => 'HDwiki', 'url' => 'index.php?doc-view-51', ), 1 => array ( 'name' => '协作者', 'url' => 'index.php?doc-view-34', ), 2 => array ( 'name' => 'Wiki与BBS', 'url' => 'index.php?doc-view-22', ), 3 => array ( 'name' => 'Wiki', 'url' => 'index.php?doc-view-21', ), 4 => array ( 'name' => 'Wiki与Blog', 'url' => 'index.php?doc-view-23' )))."'),('cooperatedoc', '东北虎;蝙蝠;大耳蝠;维基尼亚鹿;裸鼹鼠;草原犬鼠;指狐猴;树獭;王企鹅'),('hottag', '".serialize(array ( 0 => array ( 'tagname' => '考拉', 'tagcolor' => '', ), 1 => array ( 'tagname' => '鲸', 'tagcolor' => '', ), 2 => array ( 'tagname' => 'HDwiki', 'tagcolor' => '', ), 3 => array ( 'tagname' => '鼬', 'tagcolor' => '', ), 4 => array ( 'tagname' => '小熊猫', 'tagcolor' => '', ), 5 => array ( 'tagname' => '大耳狐', 'tagcolor' => 'red', ), 6 => array ( 'tagname' => '紫玉兰', 'tagcolor' => 'red', ), 7 => array ( 'tagname' => '驯鹿', 'tagcolor' => 'red', ), 8 => array ( 'tagname' => '侏绿鱼狗', 'tagcolor' => 'red', ), 9 => array ( 'tagname' => '七彩文鸟', 'tagcolor' => '', ), 10 => array ( 'tagname' => '钩嘴翠鸟', 'tagcolor' => 'red', ), 11 => array ( 'tagname' => '丹顶鹤', 'tagcolor' => '', ), 12 => array ( 'tagname' => '云雀', 'tagcolor' => 'red', ), 13 => array ( 'tagname' => '仙居鸡', 'tagcolor' => 'red', ), 14 => array ( 'tagname' => '伊力格氏金刚鹦鹉', 'tagcolor' => 'red', ), 15 => array ( 'tagname' => '棕腰歌百灵', 'tagcolor' => '', ), 16 => array ( 'tagname' => '索科罗苇鹪鹩', 'tagcolor' => '', ), 17 => array ( 'tagname' => '八音鸟', 'tagcolor' => '')))."'),('advmode', '1');\n";
				$sql .= "REPLACE INTO wiki_category (`cid`, `pid`, `name`, `displayorder`, `docs`, `image`, `navigation`, `description`) VALUES
(1, 3, 'HDWIKI', 0, 18, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '1', 'name' => 'HDWIKI', )))."', ''),
(2, 3, '互动百科', 2, 1, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '2', 'name' => '互动百科' )))."', ''),
(3, 0, '帮助文档', 2, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), ))."', ''),
(4, 3, 'wik相关', 1, 3, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '4', 'name' => 'wik相关')))."', ''),
(5, 3, '帐号相关', 3, 4, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '5', 'name' => '帐号相关')))."', ''),
(6, 3, '词条相关', 4, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '6', 'name' => '词条相关' )))."', ''),
(7, 3, '分类相关', 5, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '7', 'name' => '分类相关' )))."', ''),
(8, 3, '投诉建议', 6, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '8', 'name' => '投诉建议' )))."', ''),
(9, 3, '用户相关', 7, 0, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '9', 'name' => '用户相关' )))."', ''),
(10, 6, '词条产品相关', 0, 8, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '6', 'name' => '词条相关' ), 2 => array ( 'cid' => '10', 'name' => '词条产品相关' )))."', ''),
(11, 6, '词条命名规范', 1, 2, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '6', 'name' => '词条相关' ), 2 => array ( 'cid' => '11', 'name' => '词条命名规范' )))."', ''),
(12, 6, '词条内容编写规范', 2, 12, '', '".serialize(array ( 0 => array ( 'cid' => 3, 'name' => '帮助文档', ), 1 => array ( 'cid' => '6', 'name' => '词条相关' ), 2 => array ( 'cid' => '12', 'name' => '词条内容编写规范' )))."', '');";
				runquery($sql);
				
				copydir(HDWIKI_ROOT.'/install/testdata/201007',HDWIKI_ROOT.'/uploads/201007');
				copydir(HDWIKI_ROOT.'/install/testdata/201008',HDWIKI_ROOT.'/uploads/201008');
				copydir(HDWIKI_ROOT.'/install/testdata/201612',HDWIKI_ROOT.'/uploads/201612');
			}
			
			@touch(HDWIKI_ROOT.'/data/install.lock');
			
			$str = "<div id=\"wrapper1\"><span style=\"color:red\">{$lang['stepSetupSuccessTip']}</span></div>";
			$str .= '<br><br><a href="../">进入首页</a>';
			break;
	}

	if ($nextAccess == 0) {
		$str .= "<br /><br /><input class=\"inbut\" type=\"button\" value=\"{$lang['commonPrevStep']}\" onclick=\"javascript: window.location=('$installfile?step=$prevStep');\">\n";
	}elseif($step>1&&$nextAccess&&$step<7){
		$str .= "<div id=\"wrapper2\"><input onclick=\"window.location='install.php?step=$prevStep';\" type=\"button\" value=\"{$lang['commonPrevStep']}\" class=\"inbut\"/>  <input type=\"submit\" value=\"{$lang['commonNextStep']}\" class=\"inbut1\"/ $alert></div>";
	}

	echo $str;
?>
<?php if($step!=7){?>
<INPUT type=hidden value=<?php echo $nextStep?> name="step">
</form><?php }?>
</div>
</div>
<div class="clear"></div>
<div id="footer">
<p>Powered by <a class="footlink" href="http://kaiyuan.hudong.com">HDWiki</a> V<?php echo HDWIKI_VERSION?>| &copy;2005-2017 <strong>Hudong</strong></p>
</div>
</div>
</body>
</html>