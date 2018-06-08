<?php
require_once '../login/login_check.php';
require_once './global.func.php';

$table=moduledb($module);
if($id){
	$contentslist=$db->get_one("select * from $table where id='$id'");
	if($contentslist){
		$query = "select * from $met_plist where module='$module' and listid='$id'";
		$result = $db->query($query);
		while($list = $db->fetch_array($result)){
			$nowpara="para".$list[paraid];
			$contentslist[$nowpara]=$list[info];
			$nowparaname="";
			if($list[imgname]<>"")$nowparaname=$nowpara."name";$contentslist[$nowparaname]=$list[imgname];
		}
	}	
}
$str='';
$para_list=para_list_with($contentslist);
foreach($para_list as $key=>$val){
	if($val['type']==5)$upcs='upload';
	$str.="<tr name='paralist'>
		<td class=\"text\">{$val[name]}{$lang_marks}</td>
		<td colspan=\"2\" class=\"input {$upcs}\">{$val[inputcont]}</td>
	</tr>
	";
}
echo $str;
?>