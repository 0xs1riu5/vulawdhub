<?php
if (!isset($_SESSION['dlliuyan'])){
$_SESSION['dlliuyan']='';
}
$rs=query("select config from zzcms_usergroup where groupid=$groupid");
$row=fetch_array($rs);
$showcontact=str_is_inarr($row["config"],'showcontact');
$siteleft="<div class='titleleft'>联系人</div>";
$siteleft=$siteleft."<div class='contentleft'>";
if ($showcontact=="yes" || $_SESSION["dlliuyan"]==$editor) {
$siteleft=$siteleft. "<ul>";
$siteleft=$siteleft. "<li><b>".$somane."</b>&nbsp;&nbsp; ";
if ($sex==1){ 
$siteleft=$siteleft. "先生";
}elseif ($sex==0){ 
$siteleft=$siteleft. "女士";
}
$siteleft=$siteleft. "</li>";
$siteleft=$siteleft. "<li>电话：".$phone."</li>";
$siteleft=$siteleft. "<li>手机：".$mobile."</li>";
$siteleft=$siteleft. "<li>传真：".$fox."</li>";

if ($qq<>""){
$siteleft=$siteleft. "<li><a target=blank href=http://wpa.qq.com/msgrd?v=1&uin=".$qq."&Site=".sitename."&Menu=yes><img border='0' src=http://wpa.qq.com/pa?p=1:".$qq.":10 alt='QQ交流'></a> </li>";
}
$siteleft=$siteleft. "<li>";
if (whtml=="Yes"){ 
$siteleft=$siteleft. "<a href='contact-".$id.".htm' style='text-decoration: underline;font-weight:bold'>";
}else{ 
$siteleft=$siteleft. "<a href='contact.php?id=".$id."#contact' style='text-decoration: underline;font-weight:bold'>";
}
$siteleft=$siteleft. "详细信息";
$siteleft=$siteleft. "</a></li>";
$siteleft=$siteleft. "</ul>";
}else{
$siteleft=$siteleft. "<ul>";
$siteleft=$siteleft. "<li>联系方式不显示</li>";
$siteleft=$siteleft. "</ul>";
}

$siteleft=$siteleft. "</div>";
//以下显示招商分类
$bigclass=isset($_REQUEST['bigclass'])?$_REQUEST['bigclass']:'';
$smallclass=isset($_REQUEST['smallclass'])?$_REQUEST['smallclass']:'';

$bigclassnames="大类已删除";
$bigclasszms="###";

$siteleft=$siteleft. "<div class='titleleft'>分类".channelzs."</div>";
$siteleft=$siteleft. "<div class='contentleft'>";
$rsleft=query("select bigclassid from zzcms_main where editor='".$editor."'and bigclassid<>0 group by bigclassid");
$rowleft=num_rows($rsleft);
if ($rowleft){
	while ($rowleft=fetch_array($rsleft)){
		$rsb=query("select classname,classzm from zzcms_zsclass where classid='".$rowleft["bigclassid"]."'");
		$rowb=num_rows($rsb);
		if ($rowb){
		$rowb=fetch_array($rsb);
		$bigclassnames=cutstr($rowb["classname"],5);
		$bigclasszms=$rowb["classzm"];
		}
		
		$rsb=query("select count(id) as total from zzcms_main where editor='".$editor."'and bigclassid='".$rowleft["bigclassid"]."'");
		//$numb=mysql_result($rsb,0);//PHP7不支持
		$rowb = fetch_array($rsb);
		$numb = $rowb['total'];
		
		$siteleft=$siteleft."<li style='font-weight:bold'>";
		if ($bigclasszms==$bigclass){
			if (whtml=="Yes"){
			$siteleft=$siteleft."<a href='/sell/zs-".$id."-".$bigclasszms.".htm' style='color:red'>".$bigclassnames."</a>";
			}else{
			$siteleft=$siteleft."<a href='/zt/zs.php?id=".$id."&bigclass=".$bigclasszms."' style='color:red'>".$bigclassnames."</a>";
			}
		}else{
			if (whtml=="Yes"){
			$siteleft=$siteleft."<a href='/sell/zs-".$id."-".$bigclasszms.".htm'>".$bigclassnames."</a>";
			}else{	
			$siteleft=$siteleft."<a href='/zt/zs.php?id=".$id."&bigclass=".$bigclasszms."'>".$bigclassnames ."</a>";
			}
		}
		$siteleft=$siteleft."&nbsp;(<span style='color:#ff6600'>".$numb."</span>)";
		$siteleft=$siteleft."</li>";
		
		if (zsclass_isradio=='Yes'){
		$rsn=query("select smallclassid from zzcms_main where editor='".$editor."'and bigclassid='".$rowleft["bigclassid"]."' group by smallclassid");
		$rown=num_rows($rsn);
		if ($rown){
			while ($rown=fetch_array($rsn)){
				$rss=query("select classname,classzm from zzcms_zsclass where classid='".$rown["smallclassid"]."'");
				$rows=num_rows($rss);
				if ($rows){
				$rows=fetch_array($rss);
				$smallclassnames=$rows["classname"];
				$smallclasszms=$rows["classzm"];
				}else{
				$smallclassnames="小类已删除";
				$smallclasszms="###";
				}
				
				$rss=query("select count(id) as total from zzcms_main where editor='".$editor."'and smallclassid='".$rown["smallclassid"]."'");
				$rows = fetch_array($rss);
				$nums = $rows['total'];
				
				$siteleft=$siteleft."<li style='list-style:none;'>";
				if ($smallclasszms==$smallclass){
					if (whtml=="Yes"){
					$siteleft=$siteleft."<a href='/sell/zs-".$id."-".$bigclasszms."-".$smallclasszms.".htm' style='color:red'>".$smallclassnames."</a>";
					}else{
					$siteleft=$siteleft."<a href='/zt/zs.php?id=".$id."&bigclass=".$bigclasszms."&smallclass=".$smallclasszms."' style='color:red'>".$smallclassnames."</a>";
					}
				}else{
					if (whtml=="Yes"){
					$siteleft=$siteleft.  "<a href='/sell/zs-".$id."-".$bigclasszms."-".$smallclasszms.".htm'>".$smallclassnames."</a>";
					}else{	
					$siteleft=$siteleft. "<a href='/zt/zs.php?id=".$id."&bigclass=".$bigclasszms."&smallclass=".$smallclasszms."'>".$smallclassnames ."</a>";
					}
				}
				$siteleft=$siteleft."&nbsp;(<span style='color:#ff6600'>".$nums."</span>)";
				$siteleft=$siteleft."</li>";
			}
		}else{
		$siteleft=$siteleft. "暂无信息";
		}
		}	
	}
}else{
$siteleft=$siteleft. "暂无信息";
}
$siteleft=$siteleft. "</div>";
?>			