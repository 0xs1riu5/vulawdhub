<?php
/**
 * 生成Rss操作
 *
 * @version        $Id: makehtml_rss.php 1 11:17 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.rssview.class.php");
$isremote = empty($isremote)? 0 : $isremote;
$serviterm = empty($serviterm)? "" : $serviterm;

if(empty($tid)) $tid = 0;
if(empty($maxrecord)) $maxrecord = 50;

if($cfg_remote_site=='Y' && $isremote=="1")
{    
    if($serviterm!="")
    {
        list($servurl, $servuser, $servpwd) = explode(',', $serviterm);
        $config = array( 'hostname' => $servurl, 'username' => $servuser, 
                         'password' => $servpwd,'debug' => 'TRUE');
    } else {
        $config=array();
    }
    if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
}
$row = $dsql->GetOne("SELECT id FROM `#@__arctype` WHERE id>'$tid' AND ispart<>2 ORDER BY id ASC LIMIT 0,1;");
if(!is_array($row))
{
    echo "完成所有文件更新！";
} else {
    $rv = new RssView($row['id'],$maxrecord);
    $rssurl = $rv->MakeRss($isremote);
    $tid = $row['id'];
    ShowMsg("成功更新".$rssurl."，继续进行操作！","makehtml_rss_action.php?tid=$tid&maxrecord=$maxrecord&isremote=$isremote&serviterm={$serviterm}",0,100);
}