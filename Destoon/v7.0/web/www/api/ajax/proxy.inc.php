<?php
defined('IN_DESTOON') or exit('Access Denied');
$id = '';
if($itemid == 1) {
	$id = 'destoon_comment';
} else if($itemid == 2) {
	$id = 'destoon_answer';
} else if($itemid == 3) {
	$id = 'destoon_poll';
}
$id or exit;
?>
<script type="text/javascript">
var H1;
<?php if($itemid == 1) { ?>
function H() {
	if(!window.location.hash) return;
	var T1 = window.location.hash.split('#')[1];
	var T2 = T1.split('|');
	var H2 = T2[0]+'px';
	var I = parent.parent.document.getElementById('<?php echo $id;?>');
	if(H1 != H2) {
		I.style.height = H2;
		H1 = H2;
		parent.parent.document.getElementById('comment_count').innerHTML = T2[1];
	}
}
<?php } else if($itemid == 2) { ?>
function H() {
	if(!window.location.hash) return;
	var T1 = window.location.hash.split('#')[1];	
	var T2 = T1.split('|');
	var H2 = T2[0]+'px';
	var I = parent.parent.document.getElementById('<?php echo $id;?>');
	if(H1 != H2) {
		I.style.height = H2;
		H1 = H2;
		parent.parent.document.getElementById('answer_btn').style.display = T2[1] == 1 ? '' : 'none';
	}
}
<?php } else if($itemid == 3) { ?>
function H() {
	if(!window.location.hash) return;
	var T1 = window.location.hash.split('#')[1];	
	var T2= T1.split('|');
	var H2 = T2[0]+'px';
	var I = parent.parent.document.getElementById('<?php echo $id;?>_'+T2[1]);
	if(H1 != H2) {
		I.style.height = H2;
		H1 = H2;
	}
}
<?php } else { ?>
	//alert(window.location.hash);
function H() {
	if(!window.location.hash) return;
	var H2 = window.location.hash.split('#')[1]+'px';
	var I = parent.parent.document.getElementById('<?php echo $id;?>');
	if(H1 != H2) {
		I.style.height = H2;
		H1 = H2;
	}
}
<?php } ?>
H();
window.setInterval('H()',1000);
</script> 