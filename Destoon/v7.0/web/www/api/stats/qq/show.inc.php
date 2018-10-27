<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[0-9]{5,11}$/", $stats)) {
?>
&nbsp;|&nbsp;<script type="text/javascript" src="http://tajs.qq.com/stats?sId=<?php echo $stats;?>" charset="UTF-8"></script>
<?php
}
?>