<?php
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/gg.js"></script>
</head>
<body>
<?php
checkadminisdo("adv");
$page=isset($_POST["page"])?$_POST["page"]:1;//只从修改页传来的值,返回列表页用
checkid($page);
$newsid=isset($_POST["newsid"])?$_POST["newsid"]:0;
checkid($newsid,1);

$title=str_replace("{","",$title);//过滤{ 如果这里填写调用标签如{#showad:4,0,yes,yes,首页第一行}就会在label中反复替换出现布局上的错乱
$link=str_replace("{","",$link);

if (isset($_POST["noimg"])){$img='';}
$imgwidth=isset($_POST["imgwidth"])?$_POST["imgwidth"]:0;
checkid($imgwidth,1);
$imgheight=isset($_POST["imgheight"])?$_POST["imgheight"]:0;
checkid($imgheight,1);

$bigclassname=$_POST["bigclassid"];
$smallclassname=$_POST["smallclassid"];

if ($starttime=="") {$starttime=date('Y-m-d');}
if ($endtime==""){$endtime=date('Y-m-d',time()+60*60*24*365);}
$elite=$_POST["elite"];
checkid($elite,1);

if ($_REQUEST["action"]=="add"){
query("INSERT INTO zzcms_ad (bigclassname,smallclassname,title,titlecolor,link,img,imgwidth,imgheight,username,starttime,endtime,elite,sendtime)VALUES('$bigclassname','$smallclassname','$title','$titlecolor','$link','$img','$imgwidth','$imgheight','$username','$starttime','$endtime','$elite','".date('Y-m-d H:i:s',time()-(showadvdate+1)*60*60*24)."')");
$newsid=insert_id();
}elseif ($_REQUEST["action"]=="modify") {
query("update zzcms_ad set bigclassname='$bigclassname',smallclassname='$smallclassname',title='$title',titlecolor='$titlecolor',link='$link',img='$img',imgwidth='$imgwidth',imgheight='$imgheight',username='$username',nextuser='$nextuser',starttime='$starttime',endtime='$endtime',elite='$elite' where id='$newsid'");	
	if ($oldimg<>$img || $oldimg<>"/image/nopic.gif") {
	//deloldimg();
	}
}
$_SESSION["bigclassid"]=$bigclassname;
$_SESSION["smallclassid"]=$smallclassname;
$_SESSION["link"]=$link;
$_SESSION["imgwidth"]=$imgwidth;
$_SESSION["imgheight"]=$imgheight;

?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center">
<table width="400" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td class="left-title"> <?php if ($_REQUEST["action"]=="add") { echo "添加";}else{ echo "修改";}?>成功</td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="1" cellpadding="5">
              <tr> 
                <td width="23%" align="right" bgcolor="#FFFFFF">信息标题：</td>
                <td width="77%" bgcolor="#FFFFFF"><span style="color:<?php echo $titlecolor?>"><?php echo $title?></span></td>
              </tr>
              <tr> 
                <td align="right" bgcolor="#FFFFFF">链接地址：</td>
                <td bgcolor="#FFFFFF"><?php echo $link?></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr> 
                <td width="33%" align="center" class="border"><a href="ad_add.php">继续添加</a></td>
				<?php if (isset($newsid)){?>
                <td width="33%" align="center" class="border"><a href="ad_modify.php?id=<?php echo $newsid?>">修改</a></td>
				<?php }?>
                <td width="33%" align="center" class="border"><a href="ad_manage.php?b=<?php echo $bigclassname?>&s=<?php echo $smallclassname?>&page=<?php echo $page?>">返回</a></td>
              </tr>
            </table></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>