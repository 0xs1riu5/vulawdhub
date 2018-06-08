<?php
function cache_online(){
    global $db,$met_online,$lang;
	$query="select * from $met_online where lang='$lang' order by no_order";
	$result= $db->query($query);
	while($list = $db->fetch_array($result)){
		$data[]=$list;
	}
	return cache_page('online_'.$lang.'.inc.php',$data);
}
function cache_otherinfo($retype=1){
	global $db,$met_otherinfo,$lang;
    $data = $db->get_one("SELECT * FROM $met_otherinfo where lang='$lang' order by id");
	return cache_page('otherinfo_'.$lang.'.inc.php',$data,$retype);
	
}
function cache_str(){
	global $db,$met_label,$lang;
	$query = "SELECT * FROM $met_label where lang='$lang' order BY char_length(oldwords) DESC";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {
		$str_list_temp[0]=$list['oldwords'];
		$str_list_temp[1]="<a title='$list[newtitle]' target='_blank' href='$list[url]' class='seolabel'>$list[newwords]</a>";
		$str_list_temp[2]=$list['num'];
		$str_list[]=$str_list_temp;
	}
	return cache_page("str_".$lang.".inc.php",$str_list);
}
function cache_column(){
	global $db,$met_column,$lang;
	$query="select * from $met_column where lang='$lang' order by classtype desc,no_order";
	$result= $db->query($query);
	while($list = $db->fetch_array($result)){
		$cache_column[$list['id']]=$list;
	}
	return cache_page("column_".$lang.".inc.php",$cache_column);
}
function cache_page($file,$string,$retype=1){  
	$return = $string;
	if(is_array($string)) $string = "<?php\n return ".var_export($string, true)."; ?>";
	$string=str_replace('\n','',$string);
	if(!is_dir(ROOTPATH.'cache/'))mkdir(ROOTPATH.'cache/','0777');
	$file = ROOTPATH.'cache/'.$file;
	$strlen = file_put_contents($file, $string);
	if($retype==1){
		return $return;
	}else{
		return $strlen;
	}
}
function met_cache($file){
    $file = ROOTPATH.'cache/'.$file;
	if(!file_exists($file))return array();
	return include $file;
}
?>