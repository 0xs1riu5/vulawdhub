<?php
if(!isset($_SESSION)){session_start();} 
include("../inc/conn.php");
include("check.php");
$fpath="text/zssave.txt";
$fcontent=file_get_contents($fpath);
$f_array=explode("|||",$fcontent) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<link href="style/<?php echo siteskin_usercenter?>/style.css" rel="stylesheet" type="text/css">
<?php
if (str_is_inarr(usergr_power,'zs')=="no" && $usersf=='个人'){
echo $f_array[0];
exit;
}
$page = isset($_POST['page'])?$_POST['page']:1;//返回列表页用
checkid($page);
$cpid = isset($_POST['cpid'])?$_POST['cpid']:'0';
checkid($cpid,1);//允许为0

$bigclassid=$_POST["bigclassid"];
$smallclassid=isset($_POST['smallclassid'])?$_POST["smallclassid"][0]:0;//加[]可同多选共用同一个JS判断函数uncheckall
$smallclassids="";
if(!empty($_POST['smallclassid'])){
    for($i=0; $i<count($_POST['smallclassid']);$i++){
    //$smallclassids=$smallclassids.('"'.$_POST['smallclassid'][$i].'"'.',');//为字符串时的写法
	$smallclassids=$smallclassids.($_POST['smallclassid'][$i].',');
    }
	$smallclassids=substr($smallclassids,0,strlen($smallclassids)-1);//去除最后面的","
}

$shuxing = isset($_POST['shuxing'])?$_POST['shuxing']:'0'; 
$shuxing_value="";
	if(!empty($_POST['sx'])){
    for($i=0; $i<count($_POST['sx']);$i++){
	$shuxing_value=$shuxing_value.($_POST['sx'][$i].'|||');
    }
	$shuxing_value=substr($shuxing_value,0,strlen($shuxing_value)-3);//去除最后面的"|||"
	}
$szm = isset($_POST['szm'])?$_POST['szm']:''; 
$flv=isset($_POST["flv"])?$_POST["flv"]:'';
$city=$_POST["city"];
if ($city=='请选择城区'){
$city='';
}
$xiancheng=$_POST["cityforadd"];
if ($xiancheng=='请选择县城'){
$xiancheng='';
}
$title=isset($_POST["title"])?$_POST["title"]:$proname;
$keyword=isset($_POST["keyword"])?$_POST["keyword"]:$proname;
$discription=isset($_POST["discription"])?$_POST["discription"]:$proname;
$skin=isset($_POST["skin"])?$_POST["skin"]:'';
$rs=query("select groupid,qq,comane,id,renzheng from zzcms_user where username='".$username."'");
$row=fetch_array($rs);
$groupid=$row["groupid"];
$qq=$row["qq"];
$comane=$row["comane"];
$renzheng=$row["renzheng"];
$userid=$row["id"];

//判断是不是重复信息
if ($_REQUEST["action"]=="add" ){
$sql="select id from zzcms_main where proname='".$proname."' and editor='".$username."' ";
$rs=query($sql);
$row=num_rows($rs);
if ($row){
echo $f_array[1];
exit;
}
}elseif($_REQUEST["action"]=="modify"){
$sql="select id from zzcms_main where proname='".$proname."' and editor='".$username."' and id<>".$cpid." ";
$rs=query($sql);
$row=num_rows($rs);
if ($row){
echo $f_array[1];
exit;
}
}

$ranNum=rand(100000,99999);
if ($groupid>1){
$TimeNum=date('Y')+1;
}else{
$TimeNum=date('Y');
}	
$TimeNum=$TimeNum.date("mdHis").$ranNum;
  
if ($_POST["action"]=="add"){
$isok=query("Insert into zzcms_main(proname,bigclassid,smallclassid,smallclassids,shuxing,szm,prouse,sm,img,flv,province,city,xiancheng,zc,yq,shuxing_value,title,keywords,description,sendtime,timefororder,editor,userid,groupid,qq,comane,renzheng,skin) values('$proname','$bigclassid','$smallclassid','$smallclassids','$shuxing','$szm','$gnzz','$sm','$img','$flv','$province','$city','$xiancheng','$zc','$yq','$shuxing_value','$title','$keyword','$discription','".date('Y-m-d H:i:s')."','$TimeNum','$username','$userid','$groupid','$qq','$comane','$renzheng','$skin')") ;  
$cpid=insert_id();		
}elseif ($_POST["action"]=="modify"){
$oldimg=trim($_POST["oldimg"]);
$oldflv=trim($_POST["oldflv"]);

$isok=query("update zzcms_main set proname='$proname',bigclassid='$bigclassid',smallclassid='$smallclassid',smallclassids='$smallclassids',shuxing='$shuxing',szm='$szm',prouse='$gnzz',sm='$sm',img='$img',flv='$flv',province='$province',city='$city',xiancheng='$xiancheng',zc='$zc',yq='$yq',shuxing_value='$shuxing_value',title='$title',keywords='$keyword',description='$discription',sendtime='".date('Y-m-d H:i:s')."',timefororder='$TimeNum',editor='$username',userid='$userid',groupid='$groupid',qq='$qq',comane='$comane',renzheng='$renzheng',skin='$skin',passed=0 where id='$cpid'");

	if ($oldimg<>$img && $oldimg<>"image/nopic.gif") {
	//deloldimg
		$f=$oldimg;
		if (file_exists($f)){
		unlink($f);		
		}
		$fs=str_replace(".","_small.",$oldimg);
		if (file_exists($fs)){
		unlink($fs);		
		}
	}
	if ($oldflv<>$flv && $oldflv<>""){
	//deloldflv
		$f="../".$oldflv;
		if (file_exists($f)){
		unlink($f);		
		}
	}			
}
$_SESSION['bigclassid']=$bigclassid;
$_SESSION['province']=$province;
$_SESSION['city']=$city;
$_SESSION['xiancheng']=$xiancheng;
$_SESSION['zc']=$zc;
$_SESSION['yq']=$yq;
passed("zzcms_main");		
?>
</head>

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
	if ($isok) {
      echo $f_array[2];
	  }else{
	  echo $f_array[3];}
     ?>      </td>
  </tr>
  <tr> 
    <td class="border3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr bgcolor="#FFFFFF">
        <td width="25%" align="right" bgcolor="#FFFFFF"><strong><?php echo $f_array[4]?></strong></td>
        <td width="75%"><?php echo $proname?></td>
      </tr>
      
      <tr bgcolor="#FFFFFF">
        <td align="right" bgcolor="#FFFFFF"><strong><?php echo $f_array[6]?></strong></td>
        <td><?php echo $province.$city?></td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="bgcolor">
        <tr> 
          <td width="120" align="center" class="bgcolor1"><a href="zsadd.php"><?php echo $f_array[7]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="zsmodify.php?id=<?php echo $cpid?>"><?php echo $f_array[8]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="zsmanage.php?page=<?php echo $page?>"><?php echo $f_array[9]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="<?php echo getpageurl("zs",$cpid)?>" target="_blank"><?php echo $f_array[10]?></a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
session_write_close();
?>
</div>
</div>
</div>
</div>
</body>
</html>