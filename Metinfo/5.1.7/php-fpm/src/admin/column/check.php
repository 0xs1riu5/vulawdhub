<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

$columntxt=array(
				2=>"$met_news",
				3=>"$met_product",
				4=>"$met_download",
				5=>"$met_img",
				6=>"$met_job",
				7=>"$met_message"
		);

$column_list = $db->get_one("SELECT * FROM $met_column WHERE id='$id'");
$module=$column_list['module'];
$currentAccess= $column_list['access'];
$accesssql=$module==4?" access='$access',downloadaccess='$access' ":" access='$access' ";
if(intval($currentAccess)<intval($access)) $cond="access < $access";
if(intval($currentAccess)>intval($access)) $cond="access <= $currentAccess";
if(intval($currentAccess)!=intval($access))
{
if($column_list[releclass]||$met_class[$column_list[bigclass]][releclass]){
	if($column_list[releclass]){
		$table=$met_column;
		$query ="update $table SET ".
					"access='$access' ".
					" where bigclass=$id".
					" and $cond";
		$db->query($query);		
		if (array_key_exists($module, $columntxt))
		{		
			$table=$columntxt[$module];
			$query ="update $table SET ".
						$accesssql.
						" where $cond";
			if(intval($module)<6) $query = $query." and class1=$id";
			$db->query($query);		
		}
	}else{
		if (array_key_exists($module, $columntxt))
		{		
			$table=$columntxt[$module];
			$query ="update $table SET ".
						$accesssql.
						" where $cond";
			if(intval($module)<6) $query = $query." and class2=$id";
			$db->query($query);		
		}
	}
}else{
	if($classtype==1)
	{
		$table=$met_column;
		$query ="update $table SET ".
					" access='$access' ".
					" where bigclass=$id".
					" and $cond";
		$db->query($query);
		foreach($met_class2[$id] as $key=>$vallist){
			$query ="update $table SET ".
						" access='$access' ".
						" where bigclass=$vallist[id] and $cond";	
			$db->query($query);
			if($vallist[releclass]&&array_key_exists($module, $columntxt)){
				$table=$columntxt[$vallist[module]];
				$query ="update $table SET ".
							$accesssql.
							" where $cond";
				if(intval($module)<6) $query = $query." and class1=$vallist[id]";
				$db->query($query);	
			}
		}
		
		if (array_key_exists($module, $columntxt))
		{		
			$table=$columntxt[$module];
			$query ="update $table SET ".
						$accesssql.
						" where $cond";
			if(intval($module)<6) $query = $query." and class1=$id";
			$db->query($query);		
		}
	}

	if($classtype==2){
		$table=$met_column;
		$query ="update $table SET ".
					" access='$access' ".
					" where bigclass=$id".
					" and $cond";
		$db->query($query);
		if (array_key_exists($module, $columntxt))
		{		
			$table=$columntxt[$module];
			$query ="update $table SET ".
						$accesssql.
						" where $cond";
			if(intval($module)<6) $query = $query." and class2=$id";
			$db->query($query);		
		}
	}

	if($classtype==3)
	{
		if (array_key_exists($module, $columntxt))
		{		
			$table=$columntxt[$module];
			$query ="update $table SET ".
						$accesssql.
						" where $cond";
			if(intval($module)<6) $query = $query." and class3=$id";
			$db->query($query);		
		}
	}
}
}
file_unlink("../../cache/column_$lang.inc.php");
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>