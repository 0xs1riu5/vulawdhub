<?php
$query="select * from {$tablepre}config where name='met_tablename' and lang='metinfo'";
$mettable=$db->get_one($query);
$mettables=explode('|',$mettable[value]);
foreach($mettables as $key=>$val){
	$tablename='met_'.$val;	
	$$tablename=$tablepre.$val;
}
?>