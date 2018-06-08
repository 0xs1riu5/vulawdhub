<?php
$depth='../';
require_once $depth.'../login/login_check.php';
setlocale(LC_ALL,array('zh_CN.gbk','zh_CN.gb2312','zh_CN.gb18030'));
session_start();
$codeold='gbk';
$codenew='utf-8';
$classflie=explode('_',$fileField);
$classflie=explode('-',$classflie[count($classflie)-1]);
$class1=$classflie[0];
$class2=$classflie[1];
$class3=$classflie[2];
$class=$class3?$class3:($class2?$class2:$class1); 
$classcsv=$db->get_one("select * from $met_column where id=$class");
if(!$classcsv){
metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang",$lang_csvnocolumn,$depth);
}
$table=moduledb($classcsv['module']);
$file = fopen($flienamecsv,'r');
$fdata=fgetcsv($file);
foreach($fdata as $key=>$val){
	if(iconv($codeold,$codenew,$val)==$lang_columnhtmlname){$staticnum=$key;}
}
$numcsv=0;
while ($data = fgetcsv($file)){
	$staticone=iconv($codeold,$codenew,$data[$staticnum]);
	if($staticone!=NULL){
		$static[$numcsv+1]=$staticone;
		$static_copy[]=$staticone;
	}
	$dataqrray[]=$data;
	$numcsv++;
}  
fclose($file); 
@file_unlink($flienamecsv); 
if($static){
	foreach($static_copy as $key=>$val){
		$shift=array_shift($static_copy);
		if(array_search($shift,$static_copy)==NULL){
			if($shift==$static_copy[0]){
					$num=$key+2;
					metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang","{$lang_csverror1}{$num}",$depth);
			}
		}
		else{
			$num=$key+2;
			metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang","{$lang_csverror1}{$num}",$depth);
		}
	}
	$query="select id from $met_column where (classtype=1 or releclass!=0) and foldername='$classcsv[foldername]'";
	$sameflie=$db->get_all($query);
	foreach($sameflie as $key=>$val){
		$classquery.=$key==0?"class1='$val[id]'":" or class1='$val[id]'";
	}
	$query="select filename from $table where ($classquery) and filename !=''";
	$static_temp=$db->get_all($query);
	$i=1;
	foreach($static_temp as $key=>$val){
		$static_file[$i++]=$val['filename'];
	}
	foreach($static_file as $key=>$val){
		$num=array_search($val,$static)+1;
		if($num!=1){
			metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang","{$lang_csverror2}{$num}",$depth);
		}
	}
}
$numcsvcopy=0;
foreach($dataqrray as $key=>$val){
$numcsvcopy++;
$title=iconv($codeold,$codenew,$val[0]);
$items=1;
$querycsvpara=array();
if($classcsv['module']!=2){
	$querypara = "select * from $met_parameter where lang='$lang' and module='$classcsv[module]' and (class1='$classcsv[id]' or class1=0) order by no_order";
	$csvpara=$db->get_all($querypara);
	foreach($csvpara as $key1=>$val1){
	if($val1['type']!=5){
		$querycsvpara[]="paraid   ='".iconv($codeold,$codenew,$val1[id])."',
						 info     ='".iconv($codeold,$codenew,$val[$items])."',
						 imgname  ='',
						 module   ='$classcsv[module]',
						 lang     ='$lang'";
		$items+=1;
		}
	}
}
$filename=iconv($codeold,$codenew,$val[$items]);
$items+=1;
$keywords=iconv($codeold,$codenew,$val[$items]);
$items+=1;
$description=iconv($codeold,$codenew,$val[$items]);
$items+=1;

$content=iconv($codeold,$codenew,$val[$items]);;
$items+=1;
if($metadmin['productother']&&$classcsv['module']==3){
$metadminnum=$metadmin['productother'];
}
if($metadmin['imgother']&&$classcsv['module']==5){
$metadminnum=$metadmin['imgother'];
}
if($metadminnum!=0){
	$cvsother=1;
	while($cvsother<=$metadminnum){
		$contenttemp='content'.$cvsother;
		$$contenttemp=iconv($codeold,$codenew,$val[$items]);;
		$items+=1;
		$cvsother++;
	}
}

$addtime=$updatetime=date('Y-m-d H:i:s');
$query = "INSERT INTO $table SET
					  title              = '$title',
					  description        = '$description',
					  keywords           = '$keywords',
					  content            = '$content',
					  class1             = '$class1',
					  class2             = '$class2',
					  class3             = '$class3',
					  issue              = '$_SESSION[metinfo_admin_name]',
					  addtime            = '$addtime', 
					  updatetime         = '$updatetime',
					  filename           = '$filename',
					  recycle            = '-1',
					  lang          	 = '$lang'";
if(($metadmin[productother]||$metadmin[imgother])&&($classcsv['module']==3||$classcsv['module']==5))$query .=",
                      content1            = '$content1',
					  content2            = '$content2',
					  content3            = '$content3',
					  content4            = '$content4'
					  ";

$db->query($query);
$id=mysql_insert_id();
if($numcsvcopy==1){$fid=$id;}
if($numcsv==$numcsvcopy){$lid=$id;}
foreach($querycsvpara as $key2=>$val2){
    $query = "INSERT INTO $met_plist SET listid ='$id',$val2";
    $db->query($query);
}
}
if($numcsv){
	if($classcsv['module']==2){
		metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang",$lang_jsok,$depth);
	}
	metsave("../app/batch/fileup.php?anyid=$anyid&lang=$lang&class1=$class1&class2=$class2&class3=$class3&fileup=1&numcsv=$numcsv&fid=$fid&lid=$lid&action=do&page=1",$lang_jsok,$depth);
}
else{
	metsave("../app/batch/contentup.php?anyid=$anyid&lang=$lang",$lang_csvnodata,$depth);
}
?>