<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserv. 
function daddslashes1($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}
if($_GET[langset]!="" and $met_admin_type_ok==1){
$languser = $_GET[langset];
}
$langset=($languser!="")?$languser:$met_admin_type;
$langset=daddslashes($langset,0,1);
if(!file_exists(ROOTPATH.'cache/langadmin_'.$langset.'.php')){
	$js="var user_msg = new Array();\n";
	$query="select * from $met_language where lang='$langset' and site='1' and array!='0'";
	$result= $db->query($query);
	while($listlang= $db->fetch_array($result)){
		if(substr($listlang['name'],0,2)=='js'){
			$tmp=trim($listlang['value']);
			$js=$js."user_msg['{$listlang['name']}']='$tmp';\n";
		}
		$name = 'lang_'.$listlang['name'];
		$$name= trim($listlang['value']);
		$str.='$'."{$name}='".str_replace(array('\\',"'"),array("\\\\","\\'"),trim($listlang['value']))."';";
	}
	$js1='$'."js='".str_replace("'","\\'",$js).'\';';
	$str="<?php\n".$str.$js1."\n?>";
	file_put_contents(ROOTPATH.'cache/langadmin_'.$langset.'.php',$str);
}else{
	require_once ROOTPATH.'cache/langadmin_'.$langset.'.php';
}

$query = "SELECT * FROM $met_config WHERE lang='{$langset}-metinfo'";
$result = $db->query($query);
while($list_config= $db->fetch_array($result)){
	$setagents[$list_config['name']]=$list_config['value'];
}
@extract($setagents);
if($met_agents_type>1){
	$lang_indexthanks=$met_agents_thanks;
	$lang_metinfo=$met_agents_name;
	$lang_copyright=$met_agents_copyright;
	$lang_loginmetinfo=$met_agents_depict_login;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>