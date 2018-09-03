<?php
/**
 *
 * 广告JS调用方式
 *
 * @version        $Id: ad_js.php 1 20:30 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");

if(isset($arcID)) $aid = $arcID;
$arcID = $aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
if($aid==0) die(' Request Error! ');

$cacheFile = DEDEDATA.'/cache/myad-'.$aid.'.htm';
if( isset($nocache) || !file_exists($cacheFile) || time() - filemtime($cacheFile) > $cfg_puccache_time )
{
    $row = $dsql->GetOne("SELECT * FROM `#@__myad` WHERE aid='$aid' ");
    $adbody = '';
    if($row['timeset']==0)
    {
        $adbody = $row['normbody'];
    }
    else
    {
        $ntime = time();
        if($ntime > $row['endtime'] || $ntime < $row['starttime']) {
            $adbody = $row['expbody'];
        } else {
            $adbody = $row['normbody'];
        }
    }
    $adbody = str_replace('"', '\"',$adbody);
    $adbody = str_replace("\r", "\\r",$adbody);
    $adbody = str_replace("\n", "\\n",$adbody);
    $adbody = "<!--\r\ndocument.write(\"{$adbody}\");\r\n-->\r\n";
    $fp = fopen($cacheFile, 'w');
    fwrite($fp, $adbody);
    fclose($fp);
}
include $cacheFile;