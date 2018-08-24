<?php
include ("admin.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
checkadminisdo("baojia");
$page = isset($_POST['page'])?$_POST['page']:1;
checkid($page);
$id = isset($_GET['dlid'])?$_GET['dlid']:0;
checkid($id,1);
checkid($classid,1);
$passed = isset($_POST['passed'])?$_POST['passed']:0;

if ($_POST["action"]=="add"){
	if ($cp<>'' && $truename<>'' && $tel<>''){
	$addok=query("Insert into zzcms_baojia(classid,cp,province,city,xiancheng,price,danwei,companyname,truename,tel,address,email,sendtime) 		values('$classid','$cp','$province','$city','$xiancheng','$price','$danwei','$companyname','$truename','$tel','$address','$email','".date('Y-m-d H:i:s')."')") ; 
	}
}elseif ($_POST["action"]=="modify"){
$oldprovince=trim($_POST["oldprovince"]);
if ($province=='请选择省份'){
$province=$oldprovince;
}
$addok=query("update zzcms_baojia set classid='$classid',cp='$cp',province='$province',city='$city',xiancheng='$xiancheng',price='$price',danwei='$danwei',companyname='$companyname',truename='$truename',tel='$tel',address='$address',email='$email',sendtime='".date('Y-m-d H:i:s')."',passed='$passed' where id='$id'");
}
if ($addok){
echo "<script>location.href='baojia_manage.php?page=".$page."'</script>";
}		
?>
</body>
</html>