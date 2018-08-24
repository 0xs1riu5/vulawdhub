<?php
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
<?php
checkadminisdo("pp");

$page = isset($_POST['page'])?$_POST['page']:1;//只从修改页传来的值
checkid($page);
$cpid = isset($_POST['cpid'])?$_POST['cpid']:0;
checkid($cpid,1);
$passed = isset($_POST['passed'])?$_POST['passed']:0;
checkid($passed,1);

$bigclassid = isset($_POST['bigclassid'])?$_POST['bigclassid']:0;
$smallclassid = isset($_POST['smallclassid'])?$_POST['smallclassid']:0;
checkid($bigclassid,1);checkid($smallclassid,1);

query("update zzcms_pp set bigclassid='$bigclassid',smallclassid='$smallclassid',ppname='$cpname',sm='$sm',img='$img',sendtime='$sendtime',passed='$passed' where id='$cpid'");

if ($editor<>$oldeditor) {
$rs=query("select comane,id from zzcms_user where username='".$editor."'");
$row = num_rows($rs);
	if ($row){
	$row = fetch_array($rs);
	$userid=$row["id"];
	$comane=$row["comane"];
	}else{
	$userid=0;
	$comane="";
	}
query("update zzcms_pp set editor='$editor',userid='$userid',comane='$comane',passed='$passed' where id='$cpid'");
}
echo "<script>location.href='pp_manage.php?page=".$page."'</script>";
?>
</body>
</html>