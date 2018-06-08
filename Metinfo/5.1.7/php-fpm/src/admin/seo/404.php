<?php
require_once $depth.'../login/login_check.php';
$metHOST=$_SERVER['HTTP_HOST'];
$met404="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Page Not Found!</title>
<meta http-equiv=\"refresh\" content=\"3; url='$met_weburl' \"> 
<style type=\"text/css\">
<!--
body, td, th {  font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; margin: 0; padding: 0;}
a:link,
a:visited {color: #0240a3;}
.top {height: 50px;	background-image:  url({$met_weburl}upload/image/top.gif); background-position: top right; background-repeat: no-repeat;	margin-bottom:40px;	padding-top: 5px;padding-left: 10px; color:#FFFFFF;}
.top a{color:#FFFFFF; text-decoration:none;}
.logo{ float:left; width:auto; height:auto; margin:5px 0px 0px 5px; overflow:hidden;}
.copyright{ float:right; width:auto; margin:5px 5px 0px 0px; text-align:right;}
.content {width: 652px;	margin: auto;	border: 1px solid #D1CBD0;	background: #F9F9F9 url({$met_weburl}upload/image/top1.gif) no-repeat right top;}
.content_TOP {width: 600px; margin: auto;}
.message {width: 98%; margin: 15px auto; padding-top:10px;}
.banner {height:100px; text-align:center; background: #F9F9F9 url({$met_weburl}upload/image/foot.gif) no-repeat center; overflow:auto;}
.bannertext{ width:95%; height:20px; margin-top:70px; line-height:20px; color:#FFFFFF; text-align:right;}
.bannertext a{ color:#FFFFFF; text-decoration:none;}
-->
</style>
</head>
<body>
<div class=\"top\">
<div class=\"logo\"></div>
<div class=\"copyright\">&copy;&nbsp;2008-$m_now_year $met_webname<br /> <a href=\"$met_weburl\" >$metHOST</a></div>
</div>

<div class=\"content_TOP\"></div>
<div class=\"content\">
  <div class=\"message\">
  <table width=\"586\" height=\"220\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
      <td width=\"134\" height=\"116\" valign=\"middle\"><img src=\"{$met_weburl}upload/image/notice.gif\" /></td>
      <td width=\"452\" valign=\"middle\" >
	  <br /><br />
<p><big><b>Page Not Found!</b></big></p>
<p>The requested URL was not found, please contact with your administrator. </p>
<p><big><b>3 seconds, automatically jump to the home page.</b></big></p>
<p>&raquo;&nbsp;<a href=\"$met_weburl\">Goto Home</a>
</td>
    </tr>
  </table>
<div class=\"banner\">
<div class=\"bannertext\">
<p style=\"font-family:arial;\">Powered by&nbsp;<a href=\"http://www.MetInfo.cn\" target=\"_blank\" ><b>MetInfo</b></a> $metcms_v &copy;&nbsp;2008-$m_now_year <a href=\"http://www.MetInfo.cn\" target=\"_blank\">www.MetInfo.cn</a></p></div></div>
  </div>
  
</div>
</body>
</html>

";


$fp = fopen($depth."../../404.html",w);
      fputs($fp, $met404);
      fclose($fp);
?>