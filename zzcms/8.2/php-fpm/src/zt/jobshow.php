<?php
include("../inc/conn.php");
$cpid=isset($_GET['cpid'])?$_GET['cpid']:0;
checkid($cpid,1);

$rs=query("select * from zzcms_job where id='$cpid'");
$row=num_rows($rs);
if(!$row){
showmsg('无记录');
}else{
query("update zzcms_job set hit=hit+1 where id='$cpid'");
$row=fetch_array($rs);
$editorinzsshow=$row["editor"];//供传值到top.php
$jobname=$row['jobname'];
$province=$row['province'];
$city=$row['city'];
$sendtime=$row["sendtime"];
$hit=$row["hit"];
$sm=stripfxg($row["sm"],false,true);

include("top.php");
include("bottom.php");
include("left.php");

$fp="../skin/".$skin."/jobshow.htm";
if (file_exists($fp)==false){
WriteErrMsg($fp.'模板文件不存在');
exit;
}
$f = fopen($fp,'r');
$strout = fread($f,filesize($fp));
fclose($f);

$pagetitle=$comane.jobshowtitle.$jobname;
$pagekeywords=$comane.jobshowkeyword.$jobname;
$pagedescription=$comane.jobshowdescription.$jobname;

$strout=str_replace("{#jobname}",$jobname,$strout) ;
$strout=str_replace("{#comane}",$comane,$strout) ;
$strout=str_replace("{#hit}",$hit,$strout) ;
$strout=str_replace("{#province}",$province,$strout) ;
$strout=str_replace("{#city}",$city,$strout) ;
$strout=str_replace("{#sendtime}",$sendtime,$strout) ;
$strout=str_replace("{#email}",$email,$strout) ;
$strout=str_replace("{#sm}",$sm,$strout) ;

$strout=str_replace("{#siteskin}",siteskin,$strout) ;
$strout=str_replace("{#sitename}",sitename,$strout) ;
$strout=str_replace("{#siteurl}",siteurl,$strout);
$strout=str_replace("{#pagetitle}",$pagetitle,$strout);
$strout=str_replace("{#pagekeywords}",$pagekeywords,$strout);
$strout=str_replace("{#pagedescription}",$pagedescription,$strout);
$strout=str_replace("{#ztleft}",$siteleft,$strout);
$strout=str_replace("{#showdaohang}",$showdaohang,$strout);
$strout=str_replace("{#skin}",$skin,$strout);

$strout=str_replace("{#sitebottom}",$sitebottom,$strout);
$strout=str_replace("{#sitetop}",$sitetop,$strout);
echo  $strout;
}			  
?>