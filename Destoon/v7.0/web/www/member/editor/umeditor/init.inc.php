<?php
defined('IN_DESTOON') or exit('Access Denied');
$_isadmin = defined('DT_ADMIN') ? 1 : 0;
$moddir = $_isadmin ? $MODULE[2]['moduledir'].'/editor/' : 'editor/';
$draft = $textareaid == 'content' && $_userid && $DT['save_draft'];
if($DT['save_draft'] == 2 && !$_isadmin) $draft = false;
$_width = is_numeric($width) ? $width.'px' : $width;
$_height = is_numeric($height) ? $height.'px' : $height;
if($_width == '100%') $_width = '99%';
$editor .= '<link type="text/css" rel="stylesheet" href="'.$moddir.'umeditor/themes/default/css/umeditor.css"/>';
$editor .= '<script type="text/javascript" src="'.$moddir.'umeditor/third-party/jquery.min.js"></script>';
$editor .= '<script type="text/javascript" src="'.$moddir.'umeditor/third-party/template.min.js"></script>';
$editor .= '<script type="text/javascript" src="'.$moddir.'umeditor/umeditor.config.js"></script>';
$editor .= '<script type="text/javascript" src="'.$moddir.'umeditor/umeditor.min.js"></script>';
$editor .= '<script type="text/javascript" src="'.$moddir.'umeditor/lang/zh-cn/zh-cn.js"></script>';
$editor .= '<script type="text/javascript">';
$editor .= 'var EDW = "'.$_width.'";';
$editor .= 'var EDH = "'.$_height.'";';
$editor .= 'var EDD = "'.($draft ? 1 : 0).'";';
$editor .= 'var EID = "'.$textareaid.'";';
$editor .= '$(function(){$(\'.edui-container\').css({width:\''.$_width.'\'});});';
$editor .= 'var um = UM.getEditor(\''.$textareaid.'\',';
$editor .= '$opt={';
$editor .= 'autoFloatEnabled:false,';
$editor .= 'initialFrameWidth:"'.$_width.'",';
$editor .= 'imageUrl:UPPath+"?from=editor&moduleid='.$moduleid.'",';
$editor .= 'toolbar:';
if($toolbarset == 'Destoon') {
	$editor .= "['source | bold italic underline strikethrough | forecolor backcolor | paragraph fontfamily fontsize | justifyleft justifycenter justifyright | link unlink | image video | drafts removeformat fullscreen']";
} elseif($toolbarset == 'Simple') {
	$editor .= $editor .= "['source | bold italic underline strikethrough | forecolor | fontfamily fontsize | justifyleft justifycenter justifyright | link unlink | image video | drafts removeformat fullscreen']";
} elseif($toolbarset == 'Basic') {
	$editor .= $editor .= "['source | bold italic | forecolor | justifyleft justifycenter justifyright | link unlink | image video | drafts removeformat fullscreen']";
} elseif($toolbarset == 'Message') {
	$editor .= "['source | bold italic | forecolor | justifyleft justifycenter justifyright | link unlink | emotion image video | removeformat fullscreen']";
} else {
	$editor .= "['source | undo redo | bold italic underline strikethrough | superscript subscript | forecolor backcolor | insertorderedlist insertunorderedlist paragraph | fontfamily fontsize | justifyleft justifycenter justifyright justifyjustify | link unlink | emotion image video map horizontal formula | selectall cleardoc print preview drafts removeformat fullscreen']";
}
if(!$_isadmin) $editor = str_replace("source | ", '', $editor);
$editor .="}";
$editor .= ');';
$editor .= '</script>';
$editor .= '<script type="text/javascript" src="'.$moddir.'umeditor/init.api.js"></script>';
$editor .= '<script type="text/javascript" src="'.DT_STATIC.'file/script/editor.js"></script>';
?>