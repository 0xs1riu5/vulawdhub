<?php
if(!isset($_SESSION)){session_start();} 
include("../inc/conn.php");
include("check.php");
$fpath="text/specialsave.txt";
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
if (str_is_inarr(usergr_power,'special')=="no" && $usersf=='个人'){
echo $f_array[0];
exit;
}

$page = isset($_POST['page'])?$_POST['page']:1;//返回列表页用
checkid($page);
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id);
$bigclassid = isset($_POST['bigclassid'])?$_POST['bigclassid']:0;
$smallclassid = isset($_POST['smallclassid'])?$_POST['smallclassid']:0;

$bigclassname="";
$smallclassname="";
if ($bigclassid!=0){
$rs = query("select classname from zzcms_specialclass where classid='$bigclassid'"); 
$row= fetch_array($rs);
$bigclassname=$row["classname"];
}

if ($smallclassid!=0){
$rs = query("select classname from zzcms_specialclass where classid='$smallclassid'"); 
$row= fetch_array($rs);
$smallclassname=$row["classname"];
}
$link=addhttp($_POST["link"]);
$img=getimgincontent(stripfxg($content,true));
if ($keywords=="" ){
$keywords=$title;
}

if ($_POST["action"]=="add"){
//判断是不是重复信息,为了修改信息时不提示这段代码要放到添加信息的地方
$sql="select title,editor from zzcms_special where title='".$title."'";
$rs = query($sql); 
$row= num_rows($rs); 
if ($row){
echo $f_array[1];
}

query("Insert into zzcms_special(bigclassid,bigclassname,smallclassid,smallclassname,title,link,laiyuan,keywords,description,groupid,jifen,content,img,editor,sendtime) values('$bigclassid','$bigclassname','$smallclassid','$smallclassname','$title','$link','$laiyuan','$keywords','$description','$groupid','$jifen','$content','$img','$editor','".date('Y-m-d H:i:s')."')");  
$id=insert_id();		
}elseif ($_POST["action"]=="modify"){
query("update zzcms_special set bigclassid='$bigclassid',bigclassname='$bigclassname',smallclassid='$smallclassid',smallclassname='$smallclassname',title='$title',link='$link',laiyuan='$laiyuan',
keywords='$keywords',description='$description',groupid='$groupid',jifen='$jifen',content='$content',img='$img',editor='$editor',
sendtime='".date('Y-m-d H:i:s')."',passed=0 where id='$id'");	
}
$_SESSION['bigclassid']=$bigclassid;
$_SESSION['smallclassid']=$smallclassid;
passed("zzcms_special");	
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
    <td class="tstitle"> 
	 <?php
	if ($_REQUEST["action"]=="add") {
      echo $f_array[2];
	  }elseif ($_REQUEST["action"]=="modify"){
	  echo $f_array[3];
	  }else{
	  echo $f_array[4];
	  }
     ?>      </td>
  </tr>
  <tr> 
    <td class="border3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr bgcolor="#FFFFFF">
        <td width="25%" align="right" bgcolor="#FFFFFF"><strong><?php echo $f_array[5]?></strong></td>
        <td width="75%"><?php echo $title?></td>
      </tr>
    </table>
      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="bgcolor">
        <tr> 
          <td width="120" align="center" class="bgcolor1"><a href="specialadd.php"><?php echo $f_array[6]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="specialmodify.php?id=<?php echo $id?>"><?php echo $f_array[7]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="specialmanage.php?bigclassid=<?php echo $bigclassid?>&page=<?php echo $page?>"><?php echo $f_array[8]?></a></td>
                <td width="120" align="center" class="bgcolor1"><a href="<?php echo getpageurl("special",$id)?>" target="_blank"><?php echo $f_array[9]?></a></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php
session_write_close();
unset ($f_array);
?>
</div>
</div>
</div>
</div>
</body>
</html>