<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[0-9]{5,11}$/", $stats)) {
?>
&nbsp;|&nbsp;<script type="text/javascript" src="http://js.users.51.la/<?php echo $stats;?>.js"></script>
<?php
}
?>