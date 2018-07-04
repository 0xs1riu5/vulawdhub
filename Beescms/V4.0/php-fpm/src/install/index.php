<?php
/**
 * $Author: BEESCMS $
 * ============================================================================
 * 网站地址: http://www.beescms.com
 * 您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/

define('CMS','true');
include('init.php');
$dir=str_replace("install",'',dirname(__FILE__));
if(file_exists($dir.'data/install.lock')){die("系统已经安装完成,需要重新安装请先删除data目录下的install.lock文件");}
$action=empty($action)?'wel':$action;
go_url($action);

function wel(){
include('template/wel.html');
}

function check(){
$check_dir=array('data','data/cache','data/cache_arr','data/cache_channel','data/cache_form','data/cache_tpl','install','upload','upload/fck','upload/file','upload/img','template');

include('template/check.html');
}

function cmsdata(){
include('template/confing.html');
}
function install(){
global $localhost,$db_name,$db_user,$db_password,$db_pre,$admin,$password,$password2,$mail,$is_data;
$loclahost=empty($localhost)?'':trim($localhost);
$db_name=empty($db_name)?'':trim($db_name);
$db_user=empty($db_user)?'':trim($db_user);
$db_password=empty($db_password)?'':trim($db_password);
$db_pre=empty($db_pre)?'lps_':trim($db_pre);
$cms_self=isset($_POST['cmsself'])?trim($_POST['cmsself']):'';
$is_data=isset($_POST['is_data'])?intval($_POST['is_data']):0;
if(empty($db_name)){die("数据库名称不能为空<a href=\"javascript:history.go(-1);\">返回</a>");};
if(empty($cms_self)){die("安装地址错误<a href=\"javascript:history.go(-1);\">返回</a>");}
$str="<?php\n";
$str.="define('DB_HOST', '{$localhost}');\n";
$str.="define('DB_USER', '{$db_user}');\n";
$str.="define('DB_PASSWORD','{$db_password}');\n";
$str.="define('DB_NAME', '{$db_name}');\n";
$str.="define('DB_PRE', '{$db_pre}');\n";
$str.="define('DB_PCONNECT', 0);\n";
$str.="define('DB_CHARSET', 'utf8');\n";
$str.="define('CMS_ADDTIME', ".time().");\n";
$str.="define('CMS_SELF','".$cms_self."');\n";
$str.="?>";
if(!$conn=@mysql_connect($localhost,$db_user,$db_password)){die("数据库连接失败<a href=\"javascript:history.go(-1);\">返回</a>");}
$dbs=@mysql_query("show DATABASES",$conn);
while(($rel=mysql_fetch_assoc($dbs))!=false){
	if($rel['Database']==$db_name){$is_db=1;}
}
$is_db=isset($is_db)?$is_db:'';
if(!$is_db){@mysql_query("create database {$db_name}",$conn);}
@mysql_select_db($db_name,$conn);
cache_write(DATA_PATH.'confing.php',$str);
include(DATA_PATH."confing.php");
mysql_unbuffered_query("set names ".DB_CHARSET,$conn);
require_once('data/data.php');
include("template/install.htm");
//安装演示
if($is_data){
	$data=@file_get_contents('data/data.sql');
	$data=explode(";\n",trim($data));
	if(!empty($data)){
		foreach($data as $k=>$v){
			$v=str_replace('bees_',DB_PRE,$v);
			@mysql_query($v,$conn);
		}
	}
}else{
	@unlink('../data/cache_cate/cache_category_all.php');
	@unlink('../data/cache_cate/cate_list_cn.php');
}
$password=md5($password);
@mysql_query("insert into ".DB_PRE."admin (admin_name,admin_password,admin_nich,admin_purview,admin_mail) values ('{$admin}','{$password}','{$admin}',1,'{$mail}')",$conn);
}
?>
