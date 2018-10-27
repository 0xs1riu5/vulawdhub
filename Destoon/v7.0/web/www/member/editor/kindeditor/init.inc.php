<?php
defined('IN_DESTOON') or exit('Access Denied');
$moddir = defined('DT_ADMIN') ? $MODULE[2]['moduledir'].'/editor/' : 'editor/';
$draft = $textareaid == 'content' && $_userid && $DT['save_draft'];
if($DT['save_draft'] == 2 && !defined('DT_ADMIN')) $draft = false;
$_width = is_numeric($width) ? $width.'px' : $width;
$_height = is_numeric($height) ? $height.'px' : $height;
$editor .= '<script type="text/javascript" charset="utf-8" src="'.$moddir.'kindeditor/kindeditor-min.js"></script>';
$editor .= '<script type="text/javascript" charset="utf-8" src="'.$moddir.'kindeditor/lang/zh_CN.js"></script>';
$editor .= '<script type="text/javascript">';
$editor .= 'var ModuleID = '.$moduleid.';';
$editor .= 'var DTAdmin = '.(defined('DT_ADMIN') ? 1 : 0).';';
$editor .= 'var EDPath = "'.$moddir.'kindeditor/";';
$editor .= 'var ABPath = "'.$MODULE[2]['linkurl'].'editor/kindeditor/";';
$editor .= 'var EDW = "'.$_width.'";';
$editor .= 'var EDH = "'.$_height.'";';
$editor .= 'var EDD = "'.($draft ? 1 : 0).'";';
$editor .= 'var EID = "'.$textareaid.'";';
$editor .= '$(\'#'.$textareaid.'\').css({width:\''.$_width.'\',height:\''.$_height.'\',display:\'\'});';
$editor .= 'KindEditor.ready(function(K) { ';
$editor .= 'window.editor = K.create(\'#'.$textareaid.'\', {';
$editor .= 'urlType:\'domain\',';
if($toolbarset == 'Destoon') {
	$editor .= "items : [".(defined('DT_ADMIN') ? "'source', '|', " : "")."'wordpaste', 'plainpaste', '|', 'bold', 'forecolor', 'fontsize', 'link', 'unlink', 'image', 'multiimage', 'media', 'hr', 'justifyleft', 'justifycenter', 'justifyright', 'insertfile', ".($MODULE[$moduleid]['module'] == 'club' ? "'emoticons', " : "")."'fullscreen'],";
} else if($toolbarset == 'Simple') {
	$editor .= "items : [".(defined('DT_ADMIN') ? "'source', '|', " : "")."'wordpaste', 'plainpaste', '|', 'bold', 'forecolor', 'fontsize', 'link', 'unlink', 'image', 'justifyleft', 'justifycenter', 'justifyright', 'insertfile', ".($MODULE[$moduleid]['module'] == 'club' ? "'emoticons', " : "")."'fullscreen'],";
} else if($toolbarset == 'Basic') {
	$editor .= "items : [".(defined('DT_ADMIN') ? "'source', '|', " : "")."'bold', 'forecolor', 'fontsize', 'link', 'unlink', 'image', 'justifyleft', 'justifycenter', 'justifyright', ".($MODULE[$moduleid]['module'] == 'club' ? "'emoticons', " : "")."'fullscreen'],";
} else if($toolbarset == 'Message') {
	$editor .= "items : [".(defined('DT_ADMIN') ? "'source', '|', " : "")."'wordpaste', 'plainpaste', '|', 'bold', 'forecolor', 'fontsize', 'link', 'unlink', 'image', 'emoticons', 'justifyleft', 'justifycenter', 'justifyright', 'insertfile', ".($MODULE[$moduleid]['module'] == 'club' ? "'emoticons', " : "")."'fullscreen'],";
} else {
	$editor .= "items : [".(defined('DT_ADMIN') ? "'source', '|', " : "")."'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',	'justifyfull', 'insertorderedlist', 'insertunorderedlist', '|', 'removeformat', 'clearhtml', 'quickformat', '|', 'fullscreen', '/', 'link', 'unlink', 'anchor','formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',	'italic', 'underline', 'strikethrough', 'lineheight', 'table', 'hr', 'emoticons', '|', 'image', 'multiimage', 'media', 'insertfile'],";
}
$editor .= 'uploadJson:UPPath+\'?action=kindeditor&from=editor&moduleid='.$moduleid.'\'';
$editor .= '}); });';
$editor .= '</script>';
$editor .= '<script type="text/javascript" src="'.$moddir.'kindeditor/init.api.js"></script>';
$editor .= '<script type="text/javascript" src="'.DT_STATIC.'file/script/editor.js"></script>';
?>