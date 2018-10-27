<?php
defined('IN_DESTOON') or exit('Access Denied');
if(preg_match("/^[a-z0-9]{32}$/", $stats)) {
?>
&nbsp;|&nbsp;
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?<?php echo $stats;?>";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<?php
}
?>