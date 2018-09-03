<?php
/**
 * 生成js操作
 *
 * @version        $Id: makehtml_js_action.php 1 11:04 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");
if(empty($typeid)) $typeid = 0;

$isremote = empty($isremote)? 0 : $isremote;
$serviterm=empty($serviterm)? "" : $serviterm;
if(empty($templet)) $templet = "plus/js.htm";
if(empty($uptype)) $uptype = "all";

if($cfg_remote_site=='Y' && $isremote=="1")
{    
    if($serviterm!="")
    {
        list($servurl, $servuser, $servpwd) = explode(',',$serviterm);
        $config=array( 'hostname' => $servurl, 'username' => $servuser, 
                       'password' => $servpwd,'debug' => 'TRUE');
    } else {
        $config=array();
    }
    if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
}
if($uptype == "all")
{
    $row = $dsql->GetOne("SELECT id FROM #@__arctype WHERE id>'$typeid' AND ispart<>2 ORDER BY id ASC LIMIT 0,1;");
    if(!is_array($row))
    {
        echo "完成所有文件更新！";
        exit();
    } else {
        $pv = new PartView($row['id']);
        $pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
        $pv->SaveToHtml($cfg_basedir.$cfg_cmspath."/data/js/".$row['id'].".js",$isremote);
        $typeid = $row['id'];;
        ShowMsg("成功更新".$cfg_cmspath."/data/js/".$row['id'].".js，继续进行操作！","makehtml_js_action.php?typeid=$typeid&isremote=$isremote&serviterm=$serviterm",0,100);
        exit();
    }
} else {
    $pv = new PartView($typeid);
    $pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
    $pv->SaveToHtml($cfg_basedir.$cfg_cmspath."/data/js/".$typeid.".js",$isremote);
    echo "成功更新".$cfg_cmspath."/data/js/".$typeid.".js！";
    echo "预览：";
    echo "<hr>";
    echo "<script src='".$cfg_cmspath."/data/js/".$typeid.".js'></script>";
    exit();
}