<?php
defined('IN_DESTOON') or exit('Access Denied');
if($filepath && strpos($filepath, '/') !== false) {
	$total = 1;
	$file_dir = DT_ROOT.'/api/flashpageflip';
	$book_dir = dirname(DT_ROOT.'/'.$MOD['moduledir'].'/'.$filepath);
	$book_xml = '<content width="400" height="500" bgcolor="cccccc" loadercolor="ffffff" bgimage="0" panelcolor="5d5d61" buttoncolor="5d5d61" textcolor="ffffff" fullscreen="ture" tellafriendmode="false" leftbeginning="false">'."\n";
	foreach($T as $_T) {
		$book_xml .= '<page src="'.$_T['big'].'"/>'."\n";
	}
	$book_xml .= '</content>';
	file_put($book_dir.'/xml/Pages.xml', $book_xml);
	file_copy($file_dir.'/swf/Magazine.swf', $book_dir.'/swf/Magazine.swf');
	file_copy($file_dir.'/swf/Pages.swf', $book_dir.'/swf/Pages.swf');
	file_copy($file_dir.'/txt/Lang.txt', $book_dir.'/txt/Lang.txt');
} else {
	$template = 'show';
}
?>