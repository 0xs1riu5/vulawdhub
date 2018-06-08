<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once 'global.func.php';
if($foldyanzheng){
	$metcms='';
	$folder_m=$db->get_one("SELECT * FROM $met_column WHERE foldername='$foldername' and lang='$lang'");
	if($folder_m)$metcms=$lang_columnerr4;
	if(!preg_match('/^[a-z0-9_-]+$/i',metdetrim($foldername)))$metcms=$lang_columnerr1;
	echo $metcms;
	die;
}
$movenow=$db->get_one("select * from $met_column where id='$id'");
if($action=='move'){
$classto=$class2?$class2:$class1;
$moveto=$db->get_one("select * from $met_column where id='$classto'");
if(($movenow['module']<6&&$movenow['module']!=0&&$moveto['module']<6&&$moveto['module']!=0&&$movenow['module']==$moveto['module']&&!$removeurl)||(!$moveto['id']&&$movenow['releclass']==0)){
/*非关联*/
	switch($movenow['module']){
		case 2:
			$table=$met_news;
		break;
		case 3:
			$table=$met_product;		
		break;
		case 4:
			$table=$met_download;		
		break;	
		case 5:
			$table=$met_img;		
		break;				
	}
	$classold1=$classold2="class$movenow[classtype]=$movenow[id]";		
	$classtypenext=$movenow['classtype']+1;
	if($classtypenext<4){
			$classold1.=" and class$classtypenext=0";
	}
	if($classto==0){/*移动为顶级栏目*/
		$filedir="../../".metdetrim($foldername);  
		if(!file_exists($filedir)){ @mkdir ($filedir, 0777); } 
		if(!file_exists($filedir)){ metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_modFiledir);}
		if($movenow['module']!=8&&$movenow['module']!=7){
			column_copyconfig($foldername,$movenow['module'],$movenow['id']);
		}
		$query="update $met_column set classtype=1,bigclass=0,foldername='$foldername',releclass=0 where id='$id'";
		$db->query($query);
		if($metinfo_admin_pop!="metinfo"){
			$metinfo_admin_pop.=$id.'-';
			$query = "update $met_admin_table SET admin_type = '$metinfo_admin_pop' where id='$admin_list[id]'";
			$db->query($query);
		}
		$query="update $table set class1=$movenow[id],class2=0,class3=0 where $classold1";
		$db->query($query);
		$query="select * from $met_column where bigclass='$movenow[id]'";
		$moveclass3=$db->get_all($query);
		foreach($moveclass3 as $key=>$val){
			$classtypenext=$movenow['classtype']+1;
			$classold2.=" and class$classtypenext=$val[id]";
			$query="update $table set class1=$movenow[id],class2=$val[id],class3=0 where $classold2";
			$db->query($query);	
		}
		$query="update $met_column set classtype=2,foldername='$foldername',releclass=0 where bigclass='$id'";
		$db->query($query);
		file_unlink("../../cache/column_$lang.inc.php");
		metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);
	}
	else{
		$moveto['classtype']+=1;
		$query="update $met_column set classtype=$moveto[classtype],bigclass=$moveto[id],foldername='$moveto[foldername]' where id='$id'";
		$db->query($query);
		if($moveto['classtype']==2){
			$query="update $table set class1=$moveto[id],class2=$movenow[id],class3=0 where $classold1";
		}
		else{
			$query="update $table set class1=$moveto[bigclass],class2=$moveto[id],class3=$movenow[id] where $classold1";	
		}
		$db->query($query);
		$moveto['classtype']+=1;
		if($moveto['classtype']==3){
			$query="select * from $met_column where bigclass='$movenow[id]'";
			$moveclass3=$db->get_all($query);
			foreach($moveclass3 as $key=>$val){
				$classtypenext=$movenow['classtype']+1;
				$classold2.=" and class$classtypenext=$val[id]";
				$query="update $table set class1=$moveto[id],class2=$movenow[id],class3=$val[id] where $classold2";
				$db->query($query);	
			}
		}
		$query="update $met_column set classtype=$moveto[classtype],foldername='$moveto[foldername]' where bigclass='$id'";
		$db->query($query);		
		/*delete foldername*/
		$admin_lists = $db->get_one("SELECT * FROM $met_column WHERE foldername='$movenow[foldername]'");
		if(!$admin_lists['id'] && ($movenow['classtype'] == 1 || $movenow['releclass'])){
			if($movenow['foldername']!='' && ($movenow['module']<6 || $movenow['module']==8)){
				if(!unkmodule($movenow['foldername'])){
					$foldername="../../".$movenow['foldername'];
					if(!deldir($foldername))metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_columntip9);
				}
			}
		}
		file_unlink("../../cache/column_$lang.inc.php");
		metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);
	}
}
else{/*关联*/
	if($classto==0){
		$query="update $met_column set classtype=1,bigclass=0,releclass=0 where id='$id'";
		$db->query($query);
		$query="update $met_column set classtype=2 where bigclass=$movenow[id]";
		$db->query($query);
	}
	else{
		if($moveto['classtype']==1){
			$query="update $met_column set classtype=2,bigclass=$moveto[id],releclass=$moveto[id] where id='$id'";
			$db->query($query);
			$query="update $met_column set classtype=3 where bigclass=$movenow[id]";
			$db->query($query);
		}
		else{
			$query="update $met_column set classtype=3,bigclass=$moveto[id],releclass=$moveto[id] where id='$id'";
			$db->query($query);
		}
	}
	file_unlink("../../cache/column_$lang.inc.php");
	metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang);	
}
}else{
	$query = "SELECT * FROM $met_column where lang='$lang'";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
		if($list['classtype']==1){
		$purview='admin_pop'.$list['id'];
		$purview=$$purview;
		if($metinfo_admin_pop=="metinfo" || $purview=='metinfo')$clist1[]=$list;
		}
		if($list['classtype']==2){$clist2[]=$list;}
		if($list['classtype']==3){$clist3[]=$list;}
	}
	if($movenow['module']>5||$movenow['releclass']!=0){
		foreach($clist1 as $key=>$val){
			if($val['id']!=$movenow['id']&&$val['id']!=$movenow['bigclass']){
				$clist[]=$val;
				$clist1now[]=$val;
			}
		} 
	}else{
		$havenext=0;
		if($movenow['classtype']==1){
			foreach($clist2 as $key=>$val){
				if($val['bigclass']==$movenow['id']){
					if($val['foldername']!=$movenow['foldername']&&$val['module']!=0){$havenext=2;break;}
					$havenext=1;
					foreach($clist3 as $key1=>$val1){
						if($val1['bigclass']==$val['id']){
							$havenext=2;
							$jump=1;
							break;
						}
					}
					if($jump==1)break;
				}
			}		
		}
		if($movenow['classtype']==2){
			foreach($clist3 as $key=>$val){
				if($val['bigclass']==$movenow['id']){
					$havenext=1;
				}
			}		
		}	
		if($havenext==1){/*一级下级栏目*/
			if($movenow['classtype']==1){
				foreach($clist1 as $key=>$val){
					if($val['id']!=$movenow['id']&&$val['id']!=$movenow['bigclass']){
						$clist[]=$val;
						$clist1now[]=$val;
					}
				} 
			}
			else{
				foreach($clist1 as $key=>$val){
					if($val['module']==$movenow['module']&&$val['id']!=$movenow['id']&&$val['id']!=$movenow['bigclass']){
						$clist[]=$val;
						$clist1now[]=$val;
					}
				} 
			}		
		}
		else if($havenext==0){/*无下级栏目*/
			if(($movenow['module']<6&&$movenow['module']!=0&&$movenow['releclass']==0)&&$movenow['classtype']!=1){/*列表模块且且不为1级栏目*/
				foreach($clist1 as $key=>$val){
					if($val['module']==$movenow['module']&&$val['releclass']==0&&$val['id']!=$movenow['id']){
						$clist[]=$val;
						$clist1now[]=$val;
					}
				} 
				foreach($clist2 as $key=>$val){
					if($val['module']==$movenow['module']&&$val['releclass']==0&&$val['id']!=$movenow['id']){
						$clist[]=$val;
					}
				} 			
			}
			else{		
				foreach($clist1 as $key=>$val){
					if($val['id']!=$movenow['id']&&$val['id']!=$movenow['bigclass']){
						$clist[]=$val;
						$clist1now[]=$val;
					}
				}
				foreach($clist2 as $key=>$val){
					if($val['module']==$movenow['module']&&$val['releclass']==0&&$val['id']!=$movenow['id']&&$val['id']!=$movenow['bigclass']){
						$clist[]=$val;
					}
				} 
			}
		}
		else if($havenext==2){/*二级下级栏目*/
			$clist=NULL;
		}
	}
	if($action=='b1'){
		foreach($clist as $key=>$val){
			$clistb[$val['bigclass']][]=$val;
		}
		$metinfo='';
		if($movenow['classtype']!=1){
			$rejs = 'return new1column($(this));';
			if($movenow['releclass']!=0)$rejs = 'return linkSmit($(this),1,"'.$lang_columnerr5.'");';
			$metinfo .= "<div class='b1list'><a href='move.php?anyid={$anyid}&lang={$lang}&id={$id}&action=move&class1=0' onclick='{$rejs}'>{$lang_columnerr7}</a></div>";
		}
		foreach($clist1now as $key=>$val){
			if($val['module']<6 && $val['if_in']==0){
				$rejs = ($movenow['classtype']==1||$movenow['releclass']!=0)?'return new2column($(this));':'return linkSmit($(this),1,"'.$lang_columnerr6.'");';
				if($val['module']!=$movenow['module'])$rejs='return linkSmit($(this),1,"'.$lang_columnerr5.'");';
				$coun2 = count($clistb[$val['id']]);
				$vclass = $coun2>0?'b1list b2box':'b1list';
				$vlink  = "move.php?anyid={$anyid}&lang={$lang}&id={$id}&action=move&class1={$val[id]}";
				$metinfo .= "<div class='{$vclass}'><a href='{$vlink}' onclick='{$rejs}'>{$val[name]}</a>";
				if($coun2>0){
					$metinfo .= "<div class='moveb2'>";
					foreach($clistb[$val['id']] as $key=>$vallist){
						$rejs = 'return linkSmit($(this),1,"'.$lang_columnerr6.'");';
						if($val['module']!=$movenow['module'])$rejs='return linkSmit($(this),1,"'.$lang_columnerr5.'");';
						$metinfo .= "<div><a href='{$vlink}&class2={$vallist[id]}' onclick='{$rejs}'>{$vallist[name]}</a></div>";
					}
					$metinfo .= "</div>";
				}
				$metinfo .= "</div>";
			}
		}
	}
	echo $metinfo;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>