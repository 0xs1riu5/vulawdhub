<?php 
//--以下FUNCTION只在安装文件中有调用
function select_db($db_name) {
global $conn;
return mysql_select_db($db_name);//conn必选参数，与PHP5中位置不同
}

function connect($db_host,$db_user,$db_pass,$db_name,$db_port){
	if ($db_name != ''){
	return mysql_connect($db_host.':'.$db_port,$db_user,$db_pass,$db_name) ;
	}else{
	return mysql_connect($db_host.':'.$db_port,$db_user,$db_pass);
	}
}

function query($sql){ 
global $conn;  
return mysql_query($sql,$conn); 
} 
?>