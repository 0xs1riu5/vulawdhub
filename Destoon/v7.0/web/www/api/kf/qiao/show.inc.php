<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[0-9a-z]{32}$/", $kf)) {
?>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F<?php echo $kf;?>' type='text/javascript'%3E%3C/script%3E"));
</script>
<?php
}
?>