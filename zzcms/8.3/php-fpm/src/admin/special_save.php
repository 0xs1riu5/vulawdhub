<?php
include("admin.php");
checkadminisdo("special");

$page = isset($_POST['page'])?$_POST['page']:1;//只从修改页传来的值
checkid($page);
$id = isset($_POST['id'])?$_POST['id']:0;
checkid($id,1);
$passed = isset($_POST['passed'])?$_POST['passed']:0;
checkid($passed,1);

$bigclassid = isset($_POST['bigclassid'])?$_POST['bigclassid']:0;
$smallclassid = isset($_POST['smallclassid'])?$_POST['smallclassid']:0;
checkid($bigclassid,1);checkid($smallclassid,1);

$bigclassname="";$smallclassname="";
$rs = query("select * from zzcms_specialclass where classid='".$bigclassid."'"); 
$row= fetch_array($rs);
$bigclassname=$row["classname"];

if ($smallclassid!=0){
$rs = query("select * from zzcms_specialclass where classid='".$smallclassid."'"); 
$row= fetch_array($rs);
$smallclassname=$row["classname"];
}
$link=addhttp($link);
$img=getimgincontent(stripfxg($content,true));
$img=getimg2($img);
if ($keywords=="" ){$keywords=$title;}

if (isset($_POST["elite"])){
$elite=$_POST["elite"];
	if ($elite>255){
	$elite=255;
	}elseif ($elite<0){
	$elite=0;
	}
}else{
$elite=0;
}
checkid($elite,1);
$jifen_info=trim($_POST["jifen"]);

if ($_REQUEST["action"]=="add"){
//判断是不是重复信息,为了修改信息时不提示这段代码要放到添加信息的地方
//$sql="select title,editor from zzcms_special where title='".$title."'";
//$rs = query($sql); 
//$row= fetch_array($rs);
//if ($row){
//showmsg('此信息已存在，请不要发布重复的信息！','special_add.php');
//}

$isok=query("Insert into zzcms_special(bigclassid,bigclassname,smallclassid,smallclassname,title,link,laiyuan,keywords,description,content,img,groupid,jifen,elite,passed,sendtime) values('$bigclassid','$bigclassname','$smallclassid','$smallclassname','$title','$link','$laiyuan','$keywords','$description','$content','$img','$groupid','$jifen_info','$elite','$passed','".date('Y-m-d H:i:s')."')");  
$id=insert_id();	
}elseif ($_REQUEST["action"]=="modify"){
$isok=query("update zzcms_special set bigclassid='$bigclassid',bigclassname='$bigclassname',smallclassid='$smallclassid',smallclassname='$smallclassname',title='$title',link='$link',laiyuan='$laiyuan',keywords='$keywords',description='$description',content='$content',img='$img',groupid='$groupid',jifen='$jifen_info',sendtime='".date('Y-m-d H:i:s')."',elite='$elite',passed='$passed' where id='$id'");	
}

setcookie("ztbigclassid",$bigclassid);
setcookie("ztsmallclassid",$smallclassid);
?>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" class="left-title"><?php
	if ($_REQUEST["action"]=="add") {echo "添加 ";}else{echo"修改";}
	if ($isok){echo"成功";}else{echo "失败";}
     ?></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="1" cellpadding="5">
        <tr bgcolor="#FFFFFF"> 
          <td width="20%" align="right" bgcolor="#FFFFFF">名称：</td>
          <td width="80%"><?php echo $title?></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td align="right" bgcolor="#FFFFFF">是否推荐：</td>
          <td> 
            <?php if ($elite<>0){echo "是" ;}else{ echo "否" ;}?>
          </td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td align="right" bgcolor="#FFFFFF">类别：</td>
          <td><?php echo $bigclassname.">".$smallclassname?></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%" align="center" class="border"><a href="special_add.php">继续添加</a></td>
          <td width="25%" align="center" class="border"><a href="special_manage.php?b=<?php echo $_REQUEST["bigclassid"]?>&page=<?php echo $page?>">返回</a><a href="special_manage.php?b=<?php echo $_REQUEST["bigclassid"]?>&page=<?php echo $page?>"></a></td>
          <td width="25%" align="center" class="border"><a href="special_modify.php?id=<?php echo $id?>">修改</a></td>
          <td width="25%" align="center" class="border"><a href="<?php echo getpageurl("zt",$id)?>" target="_blank">预览</a></td>
        </tr>
      </table></td>
  </tr>
</table>

</body>
</html>