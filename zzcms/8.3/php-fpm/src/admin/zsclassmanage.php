<?php
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<title></title>
<script language="JavaScript" src="/js/gg.js"></script>
<script language="JavaScript" type="text/JavaScript">
function ConfirmDelBig(){
   if(confirm("确定要删除此大类吗？删除此大类同时将删除所包含的小类，并且不能恢复！"))
     return true;
   else
     return false;
}
function ConfirmDelSmall(){
   if(confirm("确定要删除此小类吗？一旦删除将不能恢复！"))
     return true;
   else
     return false;	 
}
</script></head>
<body>
<?php
$action=isset($_REQUEST["action"])?$_REQUEST["action"]:'';
if ($action=="px"){
checkadminisdo("zsclass");
$sqlb="Select * From zzcms_zsclass where parentid=0";
$rsb=query($sqlb);
while($rowb= fetch_array($rsb)){

$xuhao=$_POST["xuhao".$rowb["classid"]];//表单名称是动态显示的，并于FORM里的名称相同。
	   if (trim($xuhao) == "" ||  is_numeric($xuhao) == false){ 
	       $xuhao = 0;
		   }elseif ($xuhao < 0){
	       $xuhao = 0;
		   }else{
	       $xuhao = $xuhao;
	  		}
query("update zzcms_zsclass set xuhao='$xuhao' where classid='".$rowb['classid']."'");
$sqls="Select * From zzcms_zsclass where parentid='".$rowb['classid']."'";
$rss=query($sqls);
while($rows= fetch_array($rss)){

$xuhaos=$_POST["xuhaos".$rows["classid"]];//表单名称是动态显示的，并于FORM里的名称相同。
	   if (trim($xuhaos) == "" ||  is_numeric($xuhaos) == false){ 
	       $xuhaos = 0;
		   }elseif ($xuhaos < 0){
	       $xuhaos = 0;
		   }else{
	       $xuhaos = $xuhaos;
	   }
query("update zzcms_zsclass set xuhao='$xuhaos' where classid='".$rows['classid']."'");
}
}
}
if ($action=="delbig"){
checkadminisdo("zsclass");
$bigclassid=trim($_REQUEST["bigclassid"]);
checkid($bigclassid);
if ($bigclassid<>""){
	query("delete from zzcms_zsclass where parentid='$bigclassid'");//删大类下的小类
	query("delete from zzcms_zsclass where classid='$bigclassid'");
}
    
echo "<script>location.href='?'</script>";
}
if ($action=="delsmall"){
checkadminisdo("zsclass");
$smallclassid=trim($_REQUEST["smallclassid"]);
checkid($smallclassid);
$bigclassid=trim($_REQUEST["bigclassid"]);//返回列表定位用
if ($smallclassid<>""){
	query("delete from zzcms_zsclass where classid='$smallclassid'");
}
echo "<script>location.href='?#B".$bigclassid."'</script>";
}
?>
<div class="admintitle"><?php echo channelzs?>信息类别设置</div>
<div class="border center"><input name="submit3" type="submit" class="buttons" onClick="javascript:location.href='zsclassaddbig.php?dowhat=addbigclass'" value="添加大类"></div>
<?php
$sql="Select * From zzcms_zsclass where parentid=0 order by xuhao";
$rs=query($sql);
?>
<form name="form1" method="post" action="?action=px">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr> 
      <td width="9%" class="border" >classid</td>
      <td width="9%" class="border" >类别名称</td>
      <td width="18%" class="border" >拼音</td>
      <td width="18%" class="border" >排序</td>
      <td width="19%" class="border" >大类属性</td>
      <td width="27%" class="border" >操作</td>
    </tr>
      <?php
	while($row= fetch_array($rs)){
?>
    <tr bgcolor="#F1F1F1"> 
      <td style="font-weight:bold"><?php echo $row["classid"]?></td>
      <td style="font-weight:bold"><a name="B<?php echo $row["classid"]?>"></a><img src="image/icobig.gif" width="9" height="9"> <?php echo $row["classname"]?></td>
      <td style="font-weight:bold"><?php echo $row["classzm"]?></td>
      <td width="18%" > <input name="<?php echo"xuhao".$row["classid"]?>" type="text"  value="<?php echo $row["xuhao"]?>" size="4"> 
        <input type="submit" name="Submit" value="更新序号"></td>
      <td width="19%" ><?php if ($row["isshow"]==1) { echo "首页显示";} else{echo "<font color=red>首页不显示</font>";}?></td>
      <td width="27%" >[ <a href="zsclassmodifybig.php?classid=<?php echo $row["classid"]?>">修改</a> 
        | <a href="?action=delbig&bigclassid=<?php echo $row["classid"]?>" onClick="return ConfirmDelBig();">删除</a> 
        | <a href="zsclassaddsmall.php?bigclassid=<?php echo $row["classid"]?>">添加子类</a> 
        ] </td>
    </tr>
    <?php
	$n=0;
	$sqln="Select * From zzcms_zsclass Where parentid='" .$row["classid"]. "' order by xuhao";
	$rsn=query($sqln);	
	while($rown= fetch_array($rsn)){
?>
    <tr class="bgcolor1" onMouseOver="fSetBg(this)" onMouseOut="fReBg(this)"> 
      <td ><?php echo $rown["classid"]?></td>
      <td ><a name="S<?php echo $rown["classid"]?>"></a><img src="image/icosmall.gif" width="23" height="11"> <?php echo $rown["classname"]?></td>
      <td ><?php echo $rown["classzm"]?></td>
      <td colspan="2"><input name="<?php echo "xuhaos".$rown["classid"]?>" type="text"  value="<?php echo $rown["xuhao"]?>" size="4"> 
        <input name="checked" type="submit" id="checked" value="更新序号"></td>
      <td>[ <a href="zsclassmodifysmall.php?classid=<?php echo $rown["classid"]?>">修改</a> 
        | <a href="?action=delsmall&smallclassid=<?php echo $rown["classid"]?>&bigclassid=<?php echo $row["classid"]?>" onClick="return ConfirmDelSmall();">删除</a> 
        ] </td>
    </tr>
    <?php
		$n=$n+1;
	}
	}
	
	?>
  </table>
</form>
<?php
//没有父类的小类记录
$classid='';
$rs=query("Select classid From zzcms_zsclass where parentid =0 order by xuhao");
while($row= fetch_array($rs)){
$classid=$classid.$row['classid'].',';
}
$classid=substr($classid,0,strlen($classid)-1);//去除最后面的","
if ($classid<>''){
$rs=query("Select * From zzcms_zsclass where parentid not in ($classid) and parentid<>0");
$row= num_rows($rs);
if ($row){
?> 
<div class="admintitle2">没有父类的小类（可以修改为现有大类下的子类 或 直接删除）</div> 
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr>
      <td width="9%" class="border" >classid</td>
      <td width="9%" class="border" >类别名称</td>
      <td width="18%" class="border" >拼音</td>
      <td class="border" >所属父类ID</td>
      <td width="27%" class="border" >操作</td>
    </tr>
    <?php while($row= fetch_array($rs)){?>
    <tr bgcolor="#F1F1F1">
      <td><?php echo $row["classid"]?></td>
      <td><?php echo $row["classname"]?></td>
      <td><?php echo $row["classzm"]?></td>
      <td ><?php echo $row["parentid"]?></td>
      <td width="27%" >[ <a href="zsclassmodifysmall.php?classid=<?php echo $row["classid"]?>">修改</a> | <a href="?action=delsmall&smallclassid=<?php echo $row["classid"]?>" >删除</a> ] </td>
    </tr>
    <?php
	}
	?>
  </table>
<?php
}
}
?> 
</body>
</html>