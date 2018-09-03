<?php
/**
 * 生成首页
 *
 * @version        $Id: makehtml_homepage.php 2 9:30 2010-11-11 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");
if(empty($dopost)) $dopost = '';

if($dopost=="view")
{
    $pv = new PartView();
    $templet = str_replace("{style}",$cfg_df_style,$templet);
    $pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
    $pv->Display();
    exit();
}
else if($dopost=="make")
{
    $remotepos = empty($remotepos)? '/index.html' : $remotepos;
    $isremote = empty($isremote)? 0 : $isremote;
    $serviterm = empty($serviterm)? "" : $serviterm;
    $homeFile = DEDEADMIN."/".$position;
    $homeFile = str_replace("\\","/",$homeFile);
    $homeFile = str_replace("//","/",$homeFile);
    $fp = fopen($homeFile,"w") or die("你指定的文件名有问题，无法创建文件");
    fclose($fp);
    if($saveset==1)
    {
        $iquery = "UPDATE `#@__homepageset` SET templet='$templet',position='$position' ";
        $dsql->ExecuteNoneQuery($iquery);
    }
    // 判断首页生成模式
    if ($showmod == 1)
    {
        // 需要生成静态
        $templet = str_replace("{style}", $cfg_df_style, $templet);
        $pv = new PartView();
        $GLOBALS['_arclistEnv'] = 'index';
        $pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$templet);
        $pv->SaveToHtml($homeFile);
        echo "成功更新主页HTML：".$homeFile."<br /><a href='{$position}' target='_blank'>浏览...</a><br />";
    } else { 
        // 动态浏览
        if (file_exists($homeFile)) @unlink($homeFile);
        echo "采用动态浏览模式：<a href='../index.php' target='_blank'>浏览...</a><br />";
    }
    
    $iquery = "UPDATE `#@__homepageset` SET showmod='$showmod'";
    $dsql->ExecuteNoneQuery($iquery);

    if($serviterm =="")
    {
        $config=array();
    } else {
        list($servurl, $servuser, $servpwd) = explode(',',$serviterm);
        $config=array( 'hostname' => $servurl, 'username' => $servuser, 
                       'password' => $servpwd,'debug' => 'TRUE');
    }
    //如果启用远程站点则上传
    if($cfg_remote_site=='Y' && $showmod==1)
    {
        if($ftp->connect($config) && $isremote == 1)
        {
            if($ftp->upload($position, $remotepos, 'ascii')) echo "远程发布成功!"."<br />";
        }
    }
    exit();
}
$row  = $dsql->GetOne("SELECT * FROM #@__homepageset");
include DedeInclude('templets/makehtml_homepage.htm');