<?php
defined('DT_ADMIN') or exit('Access Denied');
$one = (isset($one) && $one) ? 1 : 0;
if(isset($all)) {
	if($one) dheader('?file=html&action=back&mid='.$moduleid);
	msg('扩展功能更新成功', '?moduleid=3&file=setting');
} else {
	#spread->ad->announce->webpage->gift->vote->poll->form
	msg('正在开始更新扩展', '?moduleid=3&file=spread&action=html&all=1&one='.$one);
}
?>