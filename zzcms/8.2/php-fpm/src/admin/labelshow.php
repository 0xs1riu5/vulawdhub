<?php 
include("admin.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<title></title>
<?php
$action = isset($_REQUEST['action'])?$_REQUEST['action']:"";
$channel = isset($_GET['channel'])?$_GET['channel']:"";

if ($action=="add") {
checkadminisdo("label");
$pic = isset($_POST['pic'])?$_POST['pic'][0]:0;
$flv = isset($_POST['flv'])?$_POST['flv'][0]:0;
$elite = isset($_POST['elite'])?$_POST['elite'][0]:0;
$saver = isset($_POST['saver'])?$_POST['saver'][0]:0;//代理用
if ($channel!='aboutshow'){
checkstr($numbers,'num','调用记录数');
}
checkstr($titlenum,'num','标题长度');
checkstr($column,'num','列数');
$start=stripfxg($_POST["start"],true);
$mids=stripfxg($_POST["mids"],true);
$ends=stripfxg($_POST["ends"],true);

if (!file_exists("../template/".siteskin."/label/".$channel)) {mkdir("../template/".siteskin."/label/".$channel,0777,true);}
$f="../template/".siteskin."/label/".$channel."/".$title.".txt";
$fp=fopen($f,"w+");//fopen()的其它开关请参看相关函数
if ($channel=='zsshow'){
$str=$title ."|||".$bigclassid ."|||".$smallclassid ."|||" .$groupid."|||".$pic."|||".$flv ."|||".$elite ."|||" . $numbers ."|||".$orderby ."|||" .$titlenum ."|||" .$column ."|||" .$start ."|||" .$mids. "|||".$ends;
}elseif($channel=='askshow'){
$str=$title ."|||" .$bigclassid ."|||".$smallclassid ."|||".$pic ."|||".$elite."|||".$typeid ."|||" . $numbers ."|||" .$orderby ."|||" .$titlenum ."|||" .$cnum ."|||" .$column . "|||" .$start ."|||" . $mids ."|||" . $ends;
}elseif($channel=='ppshow'){
$str=$title."|||".$bigclassid."|||".$smallclassid."|||".$pic."|||".$numbers."|||".$orderby."|||".$titlenum."|||" .$column."|||" .$start."|||" .$mids."|||" . $ends;
}elseif($channel=='zxshow'){
$str=$title."|||".$bigclassid."|||".$smallclassid ."|||".$pic."|||".$elite."|||".$numbers."|||" . $orderby ."|||" . $titlenum ."|||" . $cnum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='dlshow'){
$str=$title . "|||" .$bigclassid . "|||" .$saver."|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='baojiashow'){
$str=$title . "|||" .$bigclassid . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='jobshow'){
$str=$title . "|||" .$bigclassid . "|||".$smallclassid ."|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='zhshow'){
$str=$title . "|||" .$bigclassid . "|||".$elite . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='specialshow'){
$str=$title . "|||" .$bigclassid . "|||".$smallclassid . "|||".$pic ."|||".$elite . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $cnum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='wangkanshow'){
$str=$title . "|||" .$bigclassid . "|||".$elite . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='linkshow'){
$str=$title . "|||" .$bigclassid . "|||".$pic ."|||".$elite . "|||" . $numbers . "|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='helpshow'){
$str=$title . "|||".$elite . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $cnum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='guestbookshow'){
$str=$title . "|||" . $numbers . "|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='companyshow'){
$str=$title . "|||" .$bigclassid . "|||".$groupid . "|||".$pic . "|||".$flv ."|||".$elite . "|||" . $numbers . "|||" . $orderby ."|||" . $titlenum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}elseif($channel=='aboutshow'){
$str=$title . "|||" .$id . "|||" . $titlenum . "|||" . $cnum ."|||" . $column . "|||" . $start . "|||" . $mids . "|||" . $ends;
}

fputs($fp,$str);
fclose($fp);
$title==$title_old ?$msg='修改成功':$msg='添加成功';
echo "<script>alert('".$msg."');location.href='?channel=".$channel."&labelname=".$title.".txt'</script>";
}

if ($action=="del") {
checkadminisdo("label");
$f="../template/".siteskin."/label/".$channel."/".nostr($_POST["title"]).".txt";
	if (file_exists($f)){
	unlink($f);
	}else{
	echo "<script>alert('请选择要删除的标签');history.back()</script>";
	}	
}
?>
<script language = "JavaScript">
<?php
if ($channel=='zsshow' || $channel=='ppshow' || $channel=='askshow'|| $channel=='zxshow'||$channel=='jobshow'||$channel=='specialshow'){
	if ($channel=='askshow'){
	$sql = "select parentid,classname,classid from zzcms_askclass order by classid asc";
	}elseif($channel=='zxshow'){
	$sql = "select parentid,classname,classid from zzcms_zxclass order by classid asc";
	}elseif($channel=='specialshow'){
	$sql = "select parentid,classname,classid from zzcms_specialclass order by classid asc";
	}elseif($channel=='jobshow'){
	$sql = "select parentid,classname,classid from zzcms_jobclass order by classid asc";
	}else{
	$sql = "select parentid,classname,classid from zzcms_zsclass order by classid asc";
	}
	$rs=query($sql);
	?>
	
	var onecount;
	subcat = new Array();
	<?php
	$count = 0;
	while ($r=fetch_array($rs)){
	?>
	subcat[<?php echo $count?>] = new Array("<?php echo $r['classname']?>","<?php echo $r['parentid']?>","<?php echo $r['classid']?>");
	<?php 
	$count = $count + 1;
	}
	?>
	onecount=<?php echo $count?>;
	function changelocation(locationid){
    document.myform.smallclassid.length = 1; 
	var i;
    for (i=0;i < onecount; i++){
    	if (subcat[i][1] == locationid){ 
			document.myform.smallclassid.options[document.myform.smallclassid.length] = new Option(subcat[i][0], subcat[i][2]);
		}        
	}
	}	
<?php 
}
?>

function CheckForm(){
var re=/^[0-9a-zA-Z_]{1,20}$/; //只输入数字和字母的正则
if (document.myform.title.value==""){
    alert("标签名称不能为空！");
	document.myform.title.focus();
	return false;
  }
if(document.myform.title.value.search(re)==-1)  {
    alert("标签名称只能用字母，数字，_ 。且长度小于20个字符！");
	document.myform.title.focus();
	return false;
  }  
if (document.myform.bigclassid.value=="") {
    alert("请选择大类别！");
	document.myform.bigclassid.focus();
	return false;
  } 
}  
</script>
</head>
<body>

<div class="admintitle"><?php echo $channel?>内容标签</div>
<form action="" method="post" name="myform" id="myform" onSubmit="return CheckForm();">        
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td width="150" align="right" class="border" >现有标签：</td>
      <td class="border" >
	  <div class="boxlink"> 
        <?php
$labelname="";
if (isset($_GET['labelname'])){
$labelname=$_GET['labelname'];
if (substr($labelname,-3)!='txt'){
showmsg('只能是txt这种格式');//防止直接输入php 文件地址显示PHP代码
}
}
if (file_exists("../template/".siteskin."/label/".$channel."")==false){
echo '文件不存在';
}else{			
$dir = opendir("../template/".siteskin."/label/".$channel."");
while(($file = readdir($dir))!=false){
	if ($file!="." && $file!="..") { //不读取. ..
    //$f = explode('.', $file);//用$f[0]可只取文件名不取后缀。
		if ($labelname==$file){
  		echo "<li><a href='?channel=".$channel."&labelname=".$file."' style='color:#000000;background-color:#FFFFFF'>".$file."</a></li>";
		}else{
		echo "<li><a href='?channel=".$channel."&labelname=".$file."'>".$file."</a></li>";
		}
	} 
}
closedir($dir);	  
}	
$title='';$id='';$bigclassid='';$smallclassid='';$groupid='';$pic='';$flv='';$elite='';$typeid='';$saver='';$numbers='';$orderby='';$titlenum='';$cnum='';$column='';$start='';$mids='';$ends='';	
//读取现有标签中的内容
if (isset($_REQUEST["labelname"])){
$fp="../template/".siteskin."/label/".$channel."/".$labelname;
$f=fopen($fp,"r");
$fcontent=fread($f,filesize($fp));
fclose($f);

$fcontent=removeBOM($fcontent);//去除BOM信息，使修改时不用再重写标签名
$f=explode("|||",$fcontent) ;
if ($channel=='zsshow'){
$title=$f[0];$bigclassid=$f[1];$smallclassid=$f[2];$groupid=$f[3];$pic=$f[4];$flv=$f[5];$elite=$f[6];$numbers=$f[7];$orderby=$f[8];$titlenum=$f[9];$column=$f[10];$start=$f[11];$mids=$f[12];$ends=$f[13];	
}elseif($channel=='askshow'){
$title=$f[0];$bigclassid=$f[1];$smallclassid=$f[2];$pic=$f[3];$elite=$f[4];$typeid=$f[5];$numbers=$f[6];$orderby=$f[7];$titlenum=$f[8];$cnum=$f[9];$column=$f[10];$start=$f[11];$mids=$f[12];$ends=$f[13];
}elseif($channel=='ppshow'){
$title=$f[0];$bigclassid=$f[1];$smallclassid=$f[2];$pic=$f[3];$numbers=$f[4];$orderby=$f[5];$titlenum=$f[6];$column=$f[7];$start=$f[8];$mids=$f[9];$ends=$f[10];
}elseif($channel=='zxshow'){
$title=$f[0];$bigclassid=$f[1];$smallclassid=$f[2];$pic=$f[3];$elite=$f[4];$numbers=$f[5];$orderby=$f[6];$titlenum=$f[7];$cnum=$f[8];$column=$f[9];$start=$f[10];$mids=$f[11];$ends=$f[12];
}elseif($channel=='dlshow'){
$title=$f[0];$bigclassid=$f[1];$saver=$f[2];$numbers=$f[3];$orderby=$f[4];$titlenum=$f[5];$column=$f[6];$start=$f[7];$mids=$f[8];$ends=$f[9];
}elseif($channel=='baojiashow'){
$title=$f[0];$bigclassid=$f[1];$numbers=$f[2];$orderby=$f[3];$titlenum=$f[4];$column=$f[5];$start=$f[6];$mids=$f[7];$ends=$f[8];
}elseif($channel=='jobshow'){
$title=$f[0];$bigclassid=$f[1];$smallclassid=$f[2];$numbers=$f[3];$orderby=$f[4];$titlenum=$f[5];$column=$f[6];$start=$f[7];$mids=$f[8];$ends=$f[9];
}elseif($channel=='zhshow'){
$title=$f[0];$bigclassid=$f[1];$elite=$f[2];$numbers=$f[3];$orderby=$f[4];$titlenum=$f[5];$column=$f[6];$start=$f[7];$mids=$f[8];$ends=$f[9];
}elseif($channel=='specialshow'){
$title=$f[0];$bigclassid=$f[1];$smallclassname=$f[2];$pic=$f[3];$elite=$f[4];$numbers=$f[5];$orderby=$f[6];$titlenum=$f[7];$cnum=$f[8];$column=$f[9];$start=$f[10];$mids=$f[11];$ends=$f[12];	
}elseif($channel=='wangkanshow'){
$title=$f[0];$bigclassid=$f[1];$elite=$f[2];$numbers=$f[3];$orderby=$f[4];$titlenum=$f[5];$column=$f[6];$start=$f[7];$mids=$f[8];$ends=$f[9];	
}elseif($channel=='linkshow'){
$title=$f[0];$bigclassid=$f[1];$pic=$f[2];$elite=$f[3];$numbers=$f[4];$titlenum=$f[5];$column=$f[6];$start=$f[7];$mids=$f[8];$ends=$f[9];	
}elseif($channel=='helpshow'){
$title=$f[0];$elite=$f[1];$numbers=$f[2];$orderby=$f[3];$titlenum=$f[4];$cnum=$f[5];$column=$f[6];$start=$f[7];$mids=$f[8];$ends=$f[9];	
}elseif($channel=='guestbookshow'){
$title=$f[0];$numbers=$f[1];$titlenum=$f[2];$column=$f[3];$start=$f[4];$mids=$f[5];$ends=$f[6];	
}elseif($channel=='companyshow'){
$title=$f[0];$bigclassid=$f[1];$groupid=$f[2];$pic=$f[3];$flv=$f[4];$elite=$f[5];$numbers=$f[6];$orderby=$f[7];$titlenum=$f[8];$column=$f[9];$start=$f[10];$mids=$f[11];$ends=$f[12];	
}elseif($channel=='aboutshow'){
$title=$f[0];$id=$f[1];$titlenum=$f[2];$cnum=$f[3];$column=$f[4];$start=$f[5];$mids=$f[6];$ends=$f[7];
}

} 
	   ?>
	   </div>
      </td>
    </tr>
    <tr> 
      <td align="right" class="border" >标签名称：</td>
      <td class="border" >
<input name="title" type="text" id="title" value="<?php echo $title?>" size="50" maxlength="255">
<input name="title_old" type="hidden" id="title_old" value="<?php echo $title?>" size="50" maxlength="255">      </td>
    </tr>
    <tr> 
      <td align="right" class="border" >调用内容：</td>
      <td class="border" > 
	  <?php 
if ($channel=='zsshow' ||$channel=='companyshow'|| $channel=='ppshow' || $channel=='askshow'||$channel=='zxshow'||$channel=='dlshow'||$channel=='baojiashow'||$channel=='jobshow'||$channel=='zhshow'||$channel=='specialshow'||$channel=='wangkanshow'||$channel=='linkshow'){ 
	  ?>
<select name="bigclassid" onChange="changelocation(document.myform.bigclassid.options[document.myform.bigclassid.selectedIndex].value)" size="1">
<option value="0" selected>不指定大类</option>
<?php
	if ($channel=='askshow'){
	$sql = "select classname,classid from zzcms_askclass parentid=0 order by xuhao asc";
	}elseif ($channel=='companyshow'){
	$sql = "select classid,classname from zzcms_userclass where parentid=0 order by xuhao asc";
	}elseif ($channel=='zxshow'){
	$sql = "select classid,classname from zzcms_zxclass where parentid=0 order by xuhao asc";
	}elseif ($channel=='specialshow'){
	$sql = "select classid,classname from zzcms_specialclass where parentid=0 order by xuhao asc";
	}elseif ($channel=='jobshow'){
	$sql = "select classid,classname from zzcms_jobclass where parentid=0 order by xuhao asc";
	}elseif ($channel=='zhshow'){
	$sql = "select classid,classname from zzcms_zhclass  order by xuhao asc";
	}elseif ($channel=='wangkanshow'){
	$sql = "select classid,classname from zzcms_wangkanclass  order by xuhao asc";
	}elseif ($channel=='linkshow'){
	$sql = "select classid,classname from zzcms_linkclass  order by xuhao asc";
	}else{
	$sql = "select classname,classid from zzcms_zsclass where parentid=0 order by xuhao asc";
	}	 
    $rs=query($sql);
	while($r=fetch_array($rs)){
			?>
          <option value="<?php echo $r["classid"]?>" <?php if ($r["classid"]==$bigclassid) { echo "selected";}?>><?php echo trim($r["classname"])?></option>
          <?php   
    	     }	
		 ?>
        </select> 
		<?php
		if ($channel=='zsshow' || $channel=='ppshow' || $channel=='askshow'||$channel=='zxshow'||$channel=='jobshow'||$channel=='specialshow'){
		?>
		<select name="smallclassid">
          <option value="0" selected>不指定小类</option>
        <?php 
	if ($bigclassid<>0 && $bigclassid<>"empty" && $bigclassid<>""){
		if ($channel=='askshow'){
		$sql="select classid,classname from zzcms_askclass where parentid=" . $bigclassid ." order by classid asc";
		}elseif ($channel=='zxshow'){
		$sql="select classid,classname from zzcms_zxclass where parentid='" . $bigclassid ."' order by classid asc";
		}elseif ($channel=='specialshow'){
		$sql="select classid,classname from zzcms_specialclass where parentid='" . $bigclassid ."' order by classid asc";
		}elseif ($channel=='jobshow'){
		$sql="select classid,classname from zzcms_jobclass where parentid='" . $bigclassid ."' order by classid asc";
		}else{
		$sql="select classid,classname from zzcms_zsclass where parentid='" . $bigclassid ."' order by classid asc";
		}
			$rs=query($sql);
			while($r=fetch_array($rs)){
			?>
          <option value="<?php echo $r["classid"]?>" <?php if ($r["classid"]==$smallclassid) { echo "selected";}?>><?php echo $r["classname"]?></option>
          <?php   
			}
	}
			?>
        </select> 
		<?php
		}
		}
		if ($channel=='zsshow'||$channel=='companyshow'){
		?>
		<select name="groupid">
		 <option value="0" >所的会员</option>
          <?php
			$rsn=query("select groupid,groupname from zzcms_usergroup order by groupid asc");
			$r=num_rows($rsn);
			if ($r){
			while ($r=fetch_array($rsn)){
				if ($r["groupid"]==$groupid){
			 	echo "<option value='".$r["groupid"]."' selected>".$r["groupname"]."</option>";
				}else{
				echo "<option value='".$r["groupid"]."' >".$r["groupname"]."</option>";
				}
			}
			}
			?>
        </select>
		<?php
		}
		if ($channel=='zsshow' ||$channel=='companyshow'||$channel=='ppshow'||$channel=='zxshow'||$channel=='specialshow'||$channel=='linkshow'){
		?>
        <label><input name="pic[]" type="checkbox" id="pic" value="1" <?php if ($pic==1){ echo " checked";}?>>
        有图片的 </label>
		<?php
		}
		if ($channel=='zsshow'||$channel=='companyshow'){
		?>
        <label><input name="flv[]" type="checkbox" id="flv[]" value="1" <?php if ($flv==1){ echo " checked";}?>>
        有视频的 </label> 
		<?php
		}
		if ($channel=='zsshow'||$channel=='companyshow'||$channel=='zxshow'||$channel=='zhshow'||$channel=='specialshow'||$channel=='wangkanshow'||$channel=='linkshow'||$channel=='helpshow'){
		?>
        <label><input name="elite[]" type="checkbox" id="elite" value="1" <?php if ($elite==1) { echo " checked";}?>>
        推荐的 </label> 
		<?php
		}
		if ($channel=='askshow'){
		?>
		
		  <select name="typeid" id="typeid">
		 <option value="999">问题类型</option>
		  <option value="999" <?php if ($typeid==999) { echo "selected";}?>>全部</option>
          <option value="1" <?php if ($typeid==1) { echo "selected";}?>>已解决</option>
          <option value="0" <?php if ($typeid==0) { echo "selected";} ?>>待解决</option>
        </select>
		<?php
		}
		if ($channel=='dlshow'){
		?>
		   <label><input name="saver[]" type="checkbox" id="saver" value="1" <?php if ($saver==1){ echo " checked";}?>>
只调用<?php channeldl?>留言 </label>
		<?php
		}
		if ($channel=='aboutshow'){
		?>
		<select name="id">
          <option value="0" selected>调用全部</option>
          <?php
       $sql = "select id,title from zzcms_about order by id desc";
       $rs=query($sql);
		   while($r=fetch_array($rs)){
			?>
          <option value="<?php echo $r["id"]?>" <?php if ($r["id"]==$id) { echo "selected";}?>> 
          <?php echo $r["title"]?></option>
          <?php   
    	     }	
		 ?>
        </select>
		<?php
		}
		?>
		</td>
    </tr>
	 <?php
		if ($channel!='aboutshow'){
		?>
    <tr> 
      <td align="right" class="border" >调用记录条数：</td>
      <td class="border" >
	  <input name="numbers" type="text"  value="<?php echo $numbers?>" size="10" maxlength="255"> 
      </td>
    </tr>
	  <?php
		}
		?>
    <tr> 
      <td align="right" class="border" >排序方式设置：</td>
      <td class="border" > <select name="orderby" id="orderby">
          <option value="id" <?php if ($orderby=="id") { echo "selected";}?>>最新发布</option>
		   <option value="sendtime" <?php if ($orderby=="sendtime") { echo "selected";} ?>>最近更新</option>
          <option value="hit" <?php if ($orderby=="hit") { echo "selected";}?>>最多点击</option>
        <option value="rand" <?php if ($orderby=="rand") { echo "selected";}?>>随机显示</option>
		</select></td>
    </tr>
    <tr > 
      <td align="right" class="border" >标题长度：</td>
      <td class="border" > <input name="titlenum" type="text" id="titlenum" value="<?php echo $titlenum?>" size="20" maxlength="255"></td>
    </tr>
	<?php
		if ($channel=='aboutshow' || $channel=='helpshow' || $channel=='zxshow'|| $channel=='specialshow'|| $channel=='askshow'){
		?>
	 <tr>
      <td align="right" class="border" >内容长度：</td>
      <td class="border" ><input name="cnum" type="text"  value="<?php echo $cnum?>" size="10" maxlength="255">
      </td>
    </tr>
	<?php
		}
		?>
    <tr> 
      <td align="right" class="border" >列数：</td>
      <td class="border" > <input name="column" type="text" id="column" value="<?php echo $column?>" size="20" maxlength="255">
        （分几列显示）</td>
    </tr>
    <tr> 
      <td align="right" class="border" >解释模板（开始）：</td>
      <td class="border" ><textarea name="start" cols="100" rows="6" id="start" style="width:100%"><?php echo $start?></textarea></td>
    </tr>
    <tr> 
      <td align="right" class="border" >解释模板（循环）：</td>
      <td class="border" ><textarea name="mids" cols="100" rows="6" id="mids" style="width:100%"><?php echo $mids ?></textarea> 
      </td>
    </tr>
    <tr> 
      <td align="right" class="border" >解释模板（结束）：</td>
      <td class="border" ><textarea name="ends" cols="100" rows="6" id="ends" style="width:100%"><?php echo $ends ?></textarea></td>
    </tr>
    <tr> 
      <td align="right" class="border" >&nbsp;</td>
      <td class="border" > <input type="submit" name="Submit" value="添加/修改" onClick="myform.action='?action=add&channel=<?php echo $channel?>'"> 
        <input type="submit" name="Submit2" value="删除选中的标签" onClick="myform.action='?action=del&channel=<?php echo $channel?>'"></td>
    </tr>
  </table>
      </form>		  
</body>
</html>