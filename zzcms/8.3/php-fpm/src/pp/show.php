<?php
if(!isset($_SESSION)){session_start();} 
include("../inc/conn.php");
include("subpp.php");
include("../inc/top.php");
include("../inc/bottom.php");
include("../label.php");
if (isset($_GET["id"])){
$cpid=trim($_GET["id"]);
checkid($cpid);
}else{
$cpid=0;
}

$sql="select * from zzcms_pp where id='$cpid'";
$rs=query($sql);
$row=fetch_array($rs);
if (!$row){
echo showmsg("不存在相关信息！");
}else{
query("update zzcms_pp set hit=hit+1 where id='$cpid'");
$editor=$row["editor"];
$ppname=$row["ppname"];
$img=$row["img"];
$imgs="<img src='".getsmallimg($row["img"])."' onload='resizeimg(70,70,this)'>";

$bigclassid=$row["bigclassid"];
$smallclassid=$row["smallclassid"];
$sendtime=$row["sendtime"];
$hit=$row["hit"];
$sm=$row["sm"];
$comane=$row["comane"];

$bigclassname="大类已删除";
$bigclasszm='###';
$smallclassname="小类已删除";
$smallclasszm='###';
$rs=query("select classname,classzm from zzcms_zsclass where classid='".$bigclassid."'");
$row=fetch_array($rs);
if ($row){
$bigclassname=$row["classname"];
$bigclasszm=$row["classzm"];
}

if ($smallclassid<>0){
$rs=query("select classname,classzm from zzcms_zsclass where classid='".$smallclassid."'");
$row=fetch_array($rs);
if ($row){
$smallclassname=$row["classname"];
$smallclasszm=$row["classzm"];
}
}

$sql="select * from zzcms_user where username='".$editor."'";
$rs=query($sql);
$row=fetch_array($rs);
$startdate=$row["startdate"];
$comane=$row["comane"];
$kind=$row["bigclassid"];
$somane2=$row["somane"];
$userid=$row["id"];
$groupid=$row["groupid"];
$sex=$row["sex"];
$phone2=$row["phone"];
$fox2=$row["fox"];
$mobile2=$row["mobile"];
$qq2=$row["qq"];
$email2=$row["email"];

$contact=showcontact("pp",$cpid,$startdate,$comane,$kind,$editor,$userid,$groupid,$somane2,$sex,$phone2,$qq2,$email2,$mobile2,$fox2);
$fp="../template/".$siteskin."/ppshow.htm";
$f = fopen($fp,'r');
$strout = fread($f,filesize($fp));
fclose($f);
//dlform
if (isset($_COOKIE["UserName"])) {
$rsn=query("select * from zzcms_user where username='".trim($_COOKIE["UserName"])."'");
session_write_close();
$rown=fetch_array($rsn);
$companyname=$rown["comane"];
$somane=$rown["somane"];
$phone=$rown["phone"];
$email=$rown["email"];
}else{
$companyname="";
$somane=$rown="";
$phone=$rown="";
$email=$rown="";
}

$strout=str_replace("{textarea}","<textarea id='contents' rows=6 cols=30 name='contents' onfocus='check_contents()' onblur='check_contents()'>愿加盟“".$ppname."”这个品牌，请与我联系。</textarea>",$strout) ;
$strout=str_replace("{#companyname}",$companyname,$strout) ;
$strout=str_replace("{#somane}",$somane,$strout) ;
$strout=str_replace("{#phone}",$phone,$strout);
$strout=str_replace("{#email}",$email,$strout);
$strout=str_replace("{#editor}",$editor,$strout);

$strout=str_replace("{#siteskin}",$siteskin,$strout) ;
$strout=str_replace("{#sitename}",sitename,$strout) ;
$strout=str_replace("{#station}",getstation($bigclasszm,$bigclassname,$smallclasszm,$smallclassname,"","","pp"),$strout) ;

$strout=str_replace("{#pagetitle}",$ppname,$strout);
$strout=str_replace("{#pagekeywords}",$ppname.ppshowkeyword,$strout);
$strout=str_replace("{#pagedescription}",$ppname.ppshowdescription,$strout);
$strout=str_replace("{#img}",$img,$strout);
$strout=str_replace("{#imgs}",$imgs,$strout);

$strout=str_replace("{#cpid}",$cpid,$strout);
$strout=str_replace("{#title}",$ppname,$strout);
$strout=str_replace("{#comane}",$comane,$strout);
$strout=str_replace("{#sendtime}",$sendtime,$strout);
$strout=str_replace("{#hit}",$hit,$strout);

$strout=str_replace("{#sm}",nl2br($sm),$strout);
$strout=str_replace("{#contact}",$contact,$strout);

$strout=str_replace("{#sitebottom}",sitebottom(),$strout);
$strout=str_replace("{#sitetop}",sitetop(),$strout);
//if (strpos($strout,"{@")!==false) $strout=showlabel($strout);//先查一下，如是要没有的就不用再调用showlabel
$strout=showlabel($strout);
echo  $strout;
}
?>