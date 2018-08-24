<?php
function bigclass($b){
$str="";
$n=1;
$sql="select classname,classid from zzcms_wangkanclass  order by xuhao";
$rs=query($sql);
$row=num_rows($rs);
if (!$row){
$str="暂无分类";
}else{

while ($row=fetch_array($rs)){
$str=$str."<li>";
	if($row['bigclassid']==$b){
	$str=$str."<a href='".getpageurl2("wangkan",$row["classid"],"")."' class='current'>".$row["classname"]."</a>";
	}else{
	$str=$str."<a href='".getpageurl2("wangkan",$row["classid"],"")."'>".$row["classname"]."</a>";
	}
	$str=$str."</li>";
$n=$n+1;		
}
}
return $str;
}		
?>