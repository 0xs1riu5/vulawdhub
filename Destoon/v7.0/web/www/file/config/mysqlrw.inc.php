<?php
/*
说明:MySQL只读服务器(slave)配置
注意:MySQL读写必须使用相同的MySQL版本和数据库名
示例:
$MYSQLRW = array(
	array('host'=>'192.168.1.10', 'user'=>'root', 'pass'=>'123456'),
	array('host'=>'192.168.1.11', 'user'=>'root', 'pass'=>'123456'),
	array('host'=>'192.168.1.12', 'user'=>'root', 'pass'=>'123456'),
);
*/
$MYSQLRW = array(
	array('host'=>'192.168.1.35', 'user'=>'root', 'pass'=>'123456'),
);
?>