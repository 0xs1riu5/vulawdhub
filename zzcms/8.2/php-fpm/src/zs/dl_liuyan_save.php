<?php
if(!isset($_SESSION)){session_start();} 
include("../inc/conn.php");
include("../inc/mail_class.php");
include ("../3/mobile_msg/inc.php");
//include ("../inc/top2.php");
//include ("../inc/bottom.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<link href="../template/<?php echo siteskin?>/style.css" rel="stylesheet" type="text/css">
<body>
<div class="main" >
<?php
//echo sitetop();
checkyzm($_POST["yzm"]);
if ($_POST['token'] != $_SESSION['token'] || $_POST['token']=='' ){    
showmsg('非法提交','back');
}else{
unset($_SESSION['token']);
}

checkid($bigclassid);
checkid($cpid);
$province="";
if(!empty($_POST['province'])){
    for($i=0; $i<count($_POST['province']);$i++){
    $province=$province.($_POST['province'][$i].'|');
    }
	$province=substr($province,0,strlen($province)-1);//去除最后面的","
}
if (strpos($province,'全国')!==false) {
$province='全国';
}
$city = isset($_POST['city'])?$_POST['city']:"";
$contents=$_POST["contents"];
$company = isset($_POST['dlsf'])?$_POST['dlsf']:"";
if ($company=="个人"){
$companyname="";
}
$dlsname=$_POST["name"];
$saver=$_POST["fbr"];

checkstr($dlsname,'hanzi','联系人',$_SERVER['HTTP_REFERER']);
checkstr($tel,'tel','',$_SERVER['HTTP_REFERER']);

$rs=query("select groupid from zzcms_user where username='".$saver."' ");
$row=fetch_array($rs);
$savergroupid=$row['groupid'];

if ($cp<>'' && $dlsname<>'' && $tel<>''){
//$rs=query("select * from zzcms_dl where dlsname='$dlsname' and tel='$tel' and saver='$saver' and cpid='$cpid'");
//$row=num_rows($rs);
//if ($row){
//echo "<script>alert('您已留过言了！');history.back(-1)<//script>";
//}else{
$isok=query("insert into zzcms_dl (cp,cpid,classid,province,city,content,company,companyname,dlsname,tel,email,editor,saver,savergroupid,sendtime)values('$cp','$cpid','$bigclassid','$province','$city','$contents','$company','$companyname','$dlsname','$tel','$email','".@$_COOKIE["UserName"]."','$saver','$savergroupid','".date('Y-m-d H:i:s')."')");
$_SESSION["dlliuyan"]=$saver;//供留言后显示联系方式处用
$dlid=insert_id();

$rsn=query("select id,username,sex,email,mobile,somane from zzcms_user where username='".$saver."'");
$rown=fetch_array($rsn);
$id=$rown["id"];//供返回展厅首页用	

if (whendlsave=="Yes"){
	$fbr_email=$rown["email"];
	$dstmobile=$rown["mobile"];
	$somane=$rown["somane"];
	$sex=$rown["sex"];
	if ($sex==1){$sex="先生";}elseif($sex==0){$sex=="女士";}
	$smtp=new smtp(smtpserver,25,true,sender,smtppwd,sender);//25:smtp服务器的端口一般是25
	//$smtp->debug = true; //是否开启调试,只在测试程序时使用，正式使用时请将此行注释
	$to = $fbr_email; //收件人
	$subject = "有人在".sitename."上给您留言想要".channeldl.$cp;
	$body= "<table width='100%'><tr><td style='font-size:14px;line-height:25px'>".$somane.$sex. "：<br>&nbsp;&nbsp;&nbsp;&nbsp;您好！<br>有人在".sitename."上给您留言，想要".channeldl.$cp."；以下是部分信息：<hr>";
	$body=$body . "留&nbsp;言&nbsp;人：".$dlsname."<br>".channeldl."产品：".$cp."<br>代理区域：".$city."<br>留言时间：".date('Y-m-d H:i:s')."<br><a href='".siteurl."/user/login.php' target='_blank'><b>登录网站查看详情</b></a>";
	$body=$body . "</td></tr></table>";
	
	$fp="../template/".siteskin."/email.htm";
	$f= fopen($fp,'r');
	$strout = fread($f,filesize($fp));
	fclose($f);
	$strout=str_replace("{#body}",$body,$strout) ;
	$strout=str_replace("{#siteurl}",siteurl,$strout) ;
	$strout=str_replace("{#logourl}",logourl,$strout) ;
	$body=$strout;
	
	$smtp->sendmail($to,sender,$subject,$body,"HTML");//邮件的类型可选值是 TXT 或 HTML 
	//echo "发送失败原因：".$smtp->logs;
	
	//发手机短信网站编码为GB2312不能在此页中发
	if (sendsms=="Yes"){
	$msg='有人在'.sitename.'留言要'.channeldl.'您发布的产品，请登录网站查看详情，网址：'.siteurl;
	$msg = iconv("UTF-8","GBK",$msg);
	$result = sendSMS(smsusername,smsuserpass,$dstmobile,$msg,apikey_mobile_msg);
	//echo $result."<br>";
	}
}
if ($isok){
showmsg('成功提交',$_SERVER['HTTP_REFERER']);
}
//}
}
session_write_close();
?>
</div>
</body>
</html>