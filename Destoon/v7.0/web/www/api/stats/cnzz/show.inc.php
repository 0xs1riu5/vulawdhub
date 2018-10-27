<?php
defined('IN_DESTOON') or exit('Access Denied');
$stats = str_replace('&amp;', '&', $stats);
if(preg_match("/^http:\/\/[a-z0-9]{1,5}\.cnzz\.com\/stat\.php\?id=[0-9]{5,20}&web_id=[0-9]{5,11}$/", $stats)) {
?>
&nbsp;|&nbsp;<script type="text/javascript" src="<?php echo $stats;?>"></script>
<?php
}
?>