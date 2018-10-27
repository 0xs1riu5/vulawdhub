<?php
defined('DT_ADMIN') or exit('Access Denied');
if(DT_DEBUG) {
	echo '<br/><center class="f_gray px12">';
	debug();
	echo '</center><br/>';
}
?>
<div class="back2top"><a href="javascript:void(0);" title="返回顶部">&nbsp;</a></div>
<script type="text/javascript">
<?php if(isset($_message) && $_message) { ?>
Dnotification('new_message', '<?php echo $MODULE[2]['linkurl'];?>message.php', '<?php echo useravatar($_username, 'large');?>', '站内信(<?php echo $_message;?>)', '收到新的站内信件，点击查看');
<?php } ?>
<?php if(isset($_chat) && $_chat) { ?>
Dnotification('new_chat', '<?php echo $MODULE[2]['linkurl'];?>im.php', '<?php echo useravatar($_username, 'large');?>', '新交谈(<?php echo $_chat;?>)', '收到新的对话请求，点击交谈');
<?php } ?>
</script>
</body>
</html>