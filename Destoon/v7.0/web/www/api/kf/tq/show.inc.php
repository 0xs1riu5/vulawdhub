<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[0-9]{5,11}$/", $kf)) {
?>
<script type="text/javascript" src="http://float2006.tq.cn/floatcard?adminid=<?php echo $kf;?>&sort=0"></script>
<?php
}
?>