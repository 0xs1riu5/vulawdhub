<?php 
include ("admin.php");
?>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<?php
checkadminisdo("friendlink");

$page = isset($_POST['page'])?$_POST['page']:1;//只从修改页传来的值
checkid($page);
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id,1);
$passed = isset($_POST['passed'])?$_POST['passed']:0;
checkid($passed,1);
$elite = isset($_POST['elite'])?$_POST['elite']:0;
checkid($elite,1);

$bigclassid = isset($_POST['bigclassid'])?$_POST['bigclassid']:0;
checkid($bigclassid,1);

$FriendSiteName=trim($_POST["sitename"]);
$url=addhttp($url);
$logo=addhttp($logo);

if ($_REQUEST["action"]=="add"){
query("INSERT INTO zzcms_link (bigclassid,sitename,url,logo,content,passed,elite,sendtime)VALUES('$bigclassid','$FriendSiteName','$url','$logo','$content','$passed','$elite','".date('Y-m-d H:i:s')."')");
}elseif ($_REQUEST["action"]=="modify") {
query("update zzcms_link set bigclassid='$bigclassid',sitename='$FriendSiteName',url='$url',logo='$logo',content='$content',passed='$passed',elite='$elite',sendtime='".date('Y-m-d H:i:s')."' where id='$id'");	
}
$_SESSION["bigclassid"]=$bigclassid;

echo  "<script>location.href='linkmanage.php?b=".$bigclassid."&page=".$page."'</script>";
?>
</body>
</html>