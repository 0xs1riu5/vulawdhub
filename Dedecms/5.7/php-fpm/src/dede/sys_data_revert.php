<?php
/**
 * @version        $Id: sys_data_revert.php 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Data');
$bkdir = DEDEDATA."/".$cfg_backup_dir;
$filelists = Array();
$dh = dir($bkdir);
$structfile = "没找到数据结构文件";
while(($filename=$dh->read()) !== false)
{
    if(!preg_match("#txt$#", $filename))
    {
        continue;
    }
    if(preg_match("#tables_struct#", $filename))
    {
        $structfile = $filename;
    }
    else if( filesize("$bkdir/$filename") >0 )
    {
        $filelists[] = $filename;
    }
}
$dh->close();
include DedeInclude('templets/sys_data_revert.htm');