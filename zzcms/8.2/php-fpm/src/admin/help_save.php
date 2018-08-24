<?php
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
checkadminisdo("helps");
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id,1);
$page=isset($_POST["page"])?$_POST["page"]:1;//只从修改页传来的值
checkid($page);
$elite=isset($_POST["elite"])?$_POST["elite"]:0;
checkid($elite,1);
$b=isset($_POST["b"])?$_POST["b"]:0;
checkid($b,1);

$img=getimgincontent($content);

if ($_REQUEST["action"]=="add"){
	query("INSERT INTO zzcms_help (classid,title,content,img,elite,sendtime)VALUES('$b','$title','$content','$img','$elite','".date('Y-m-d H:i:s')."')");
	}elseif ($_REQUEST["action"]=="modify"){
	query("update zzcms_help set classid='$b',title='$title',content='$content',img='$img',elite='$elite',sendtime='".date('Y-m-d H:i:s')."' where id='$id' ");
}
echo "<script>location.href='help_manage.php?b=".$b."&page=".$page."'</script>";
?>
</body>
</html>	