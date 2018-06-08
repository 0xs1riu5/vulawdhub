<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
	ob_start();
	$depth='../';
	require_once $depth.'../include/common.inc.php';
	ob_clean();
	ob_start();
	require('php_xls.php');
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$class1){
			$tingname    =$val['name'].'_'.$val['columnid'];
			$$val['name']=$$tingname;
		}
	}
	$query = "SELECT * FROM $met_parameter where module=8 and lang='$lang' order by no_order";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		$feedbackpara[$list['id']]=$list;
		$feedback_para[]=$list;
	}	
	$query = "SELECT * FROM $met_flist where module=8 and lang='$lang'";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		if($feedbackpara[$list['paraid']]['type']==5 and $list[info]<>"")$list[info]=$met_weburl."upload/file/".$list[info];
		$paravalue[$list[listid]][$list[paraid]]=$list;
	}
	if($met_fd_export==-1){
		$where=" ";
	}else{
		$where=" and exists(select info from $met_flist where listid=$met_feedback.id and paraid=$met_fd_class and info='$met_fd_export')";
	}
	$query = "SELECT * FROM $met_feedback where class1='$class1' and lang='$lang' ".$where;
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	$list['customerid']=$list['customerid']=='0'?$lang_feedbackAccess0:$list['customerid'];
	  foreach($feedback_para as $key=>$val){
	   $para='para'.$val[id];
	   $list[$para]=$paravalue[$list[id]][$val[id]][info];
	  }
	$feedback_list[]=$list;

	}


	/*set xls*/
	

	$column=array("",$lang_fdeditorInterest);
	$param=array('fdtitle');
	foreach($feedback_para as $key=>$val){
		$column[]=$val['name'];
		$param[]="para".$val[id];
	}
	$column[]=$lang_fdeditorTime;
	$column[]=$lang_fdeditorFrom;
	$column[]=$lang_feedbackID;
	$column[]=$lang_fdeditorRecord;
	$param[]='addtime';
	$param[]='fromurl';
	$param[]='customerid';
	$param[]='useinfo';
	$xls=new PHP_XLS();
	$xls->AddSheet($lang_editor);
	$xls->NewStyle('hd_t');

	$xls->StyleSetFont(0, 10, 0, 1, 0, 0);

	$xls->StyleSetAlignment(0, 0);
	$xls->StyleAddBorder("Top", '#000000', 2);
	$xls->StyleAddBorder("Right", '#000000', 1);
	
	$xls->CopyStyle('hd_t','hd_l');
	$xls->StyleAddBorder("Left", '#000000', 2);

	$xls->CopyStyle('hd_t','hd_r');
	$xls->StyleAddBorder("Right", '#000000', 2);
	
	$xls->SetRowHeight(1,30);

	for($i=1;$i<count($column);$i++)
	{
		$xls->SetColWidth($i,80);
	}
	
	$xls->SetActiveStyle('hd_l');
	$xls->SetActiveStyle('hd_t');
	$xls->SetActiveStyle('hd_r');
	for($i=1;$i<count($column);$i++)
	{
		$xls->Textc(1,$i,$column[$i]);
	}

	
	$xls->NewStyle('center');
	$xls->StyleSetAlignment(0, 0);
	$xls->StyleAddBorder("Top", '#000000', 1);
	$xls->StyleAddBorder("Right", '#000000', 1);
	
	$xls->CopyStyle('center','center_l');
	$xls->StyleAddBorder("Left", '#000000', 2);

	$xls->CopyStyle('center','center_r');
	$xls->StyleAddBorder("Right", '#000000', 2);

	
	/*get feedback infomation *export xls */	
		
	for ($i=0; $i<count($feedback_list); $i++) 
	{		
		
		for ($j=0; $j<count($column)-1; $j++) {
			$xls->SetActiveStyle('center');	
			$xls->Textc($i+2,$j+1,$feedback_list[$i][$param[$j]]);
		}
	}	
	$xls->Output($met_module[8][0][name].".xls");
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>