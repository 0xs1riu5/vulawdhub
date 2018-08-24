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
checkadminisdo("dl");
$page=isset($_POST["page"])?$_POST["page"]:1;//只从修改页传来的值
checkid($page);
$id=isset($_POST["id"])?$_POST["id"]:0;
checkid($id,1);

$passed=isset($_POST["passed"])?$_POST["passed"]:0;
checkid($passed,1);

$classid=$_POST["classid"];
checkid($classid);
$city=$_POST["cityforadd"];

$companyname=isset($_POST["companyname"])?$_POST["companyname"]:"";
if ($dlsf=="个人" ){$companyname="";}

if ($_POST["action"]=="add"){
	if ($cp<>'' && $truename<>'' && $tel<>''){
	$addok=query("Insert into zzcms_dl(classid,cpid,cp,province,city,content,company,companyname,dlsname,tel,address,email,sendtime) values('$classid',0,'$cp','$province','$city','$content','$dlsf','$companyname','$truename','$tel','$address','$email','".date('Y-m-d H:i:s')."')") ; 
	$id=insert_id();  
	}
}elseif ($_POST["action"]=="modify"){
$oldprovince=trim($_POST["oldprovince"]);
if ($province=='请选择省份'){
$province=$oldprovince;
}
$addok=query("update zzcms_dl set classid='$classid',cp='$cp',province='$province',city='$city',content='$content',company='$dlsf',companyname='$companyname',dlsname='$truename',tel='$tel',address='$address',email='$email',sendtime='".date('Y-m-d H:i:s')."',passed='$passed' where id='$id'");
}
if ($addok){
echo "<script>location.href='dl_manage.php?page=".$page."'</script>";
}		
?>
</body>
</html>