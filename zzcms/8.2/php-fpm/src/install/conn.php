<?php 
//--以下FUNCTION只在安装文件中有调用
function select_db($db_name) {
global $conn;
return mysqli_select_db($conn,$db_name);//conn必选参数，与PHP5中位置不同
}

function connect($db_host,$db_user,$db_pass,$db_name,$db_port){
	if ($db_name != ''){
	return mysqli_connect($db_host,$db_user,$db_pass,$db_name,$db_port) ;
	}else{
	return mysqli_connect($db_host,$db_user,$db_pass,'',$db_port);
	}
}

function query($sql){ 
global $conn;  
return mysqli_query($conn,$sql);//conn必选参数     
} 
?>