<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[0-9a-zA-z_\-]{4,20}$/", $kf)) {
?>
<script type="text/javascript" src="http://tb.53kf.com/kf.php?arg=<?php echo $kf;?>&style=0"></script>
<?php
}
?>