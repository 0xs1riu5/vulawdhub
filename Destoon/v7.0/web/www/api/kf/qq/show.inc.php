<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[0-9a-z]{32,}$/i", $kf)) {
?>
<script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=<?php echo $kf;?>"></script>
<?php
}
?>