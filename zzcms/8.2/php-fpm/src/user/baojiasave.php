<?php
if(!isset($_SESSION)){session_start();} 
include("../inc/conn.php");
include("check.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style/<?php echo siteskin_usercenter?>/style.css" rel="stylesheet" type="text/css">
</head>
<?php
$page = isset($_POST['page'])?$_POST['page']:1;//返回列表页用
checkid($page);
$id = isset($_POST['dlid'])?$_POST['dlid']:'0';
checkid($id,1);
if ($city=='请选择城区')$city='';
if ($xiancheng=='请选择县城')$xiancheng='';
$companyname=$_POST["company"];
checkyzm($_POST["yzm"]);

if ($_POST["action"]=="add"){
if ($cp<>'' && $truename<>'' && $tel<>''){
$isok=query("Insert into zzcms_baojia(classid,cp,province,city,price,danwei,companyname,truename,tel,address,email,sendtime,editor) values('$classid','$cp','$province','$city','$price','$danwei','$companyname','$truename','$tel','$address','$email','".date('Y-m-d H:i:s')."','$username')") ;   
$id=insert_id();	
}	
}elseif ($_POST["action"]=="modify"){
checkstr($tel,'tel');
$isok=query("update zzcms_baojia set classid='$classid',cp='$cp',province='$province',city='$city',price='$price',danwei='$danwei',companyname='$companyname',truename='$truename',tel='$tel',address='$address',email='$email',sendtime='".date('Y-m-d H:i:s')."' where id='$id'");
}
$_SESSION['danwei']=$danwei;
$_SESSION['bigclassid']=$classid;
$_SESSION['province']=$province;
$_SESSION['city']=$city;
$_SESSION['xiancheng']=$xiancheng;
passed("zzcms_dl",$classid);		
?>
<body>
<div class="main">
<?php
include("top.php");
?>
<div class="pagebody">
<div class="left">
<?php
include("left.php");
?>
</div>
<div class="right">
<div class="content">
<table width="400" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td class="tstitle"> <?php
	if ($_REQUEST["action"]=="add") {echo "添加"; }else{ echo"修改";}
	if ($isok) {echo "成功"; }else{ echo"失败";};
     ?>      </td>
  </tr>
  <tr> 
    <td class="border3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr bgcolor="#FFFFFF">
        <td width="25%" align="right" bgcolor="#FFFFFF">名称：</td>
        <td width="75%"><?php echo $cp?></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" bgcolor="#FFFFFF">意向区域：</td>
        <td><?php echo $province.$city?></td>
      </tr>
    </table>
      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="bgcolor">
        <tr> 
          <td width="120" align="center" class="bgcolor1"><a href="baojiaadd.php">继续添加</a></td>
                <td width="120" align="center" class="bgcolor1"><a href="baojiamodify.php?id=<?php echo $id?>">修改</a></td>
                <td width="120" align="center" class="bgcolor1"><a href="baojiamanage.php?page=<?php echo $page?>">返回</a></td>
                <td width="120" align="center" class="bgcolor1"><a href="<?php echo getpageurl("baojia",$id)?>" target="_blank">预览</a></td>
        </tr>
      </table></td>
  </tr>
</table>

</div>
</div>
</div>
</div>
</body>
</html>