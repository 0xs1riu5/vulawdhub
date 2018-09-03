<?php
/**
 * 用户动态ajax显示页
 *
 * @version        $Id: feed.php 1 17:55 2010年7月6日Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';

//选择数据库
$feeds = array();
$type=(empty($type))? "" : $type;
if($type=="allfeed")
{
    $sql="SELECT * FROM `#@__member_feed` ORDER BY dtime DESC LIMIT 8";
    $dsql->SetQuery($sql);
    $dsql->Execute();
    while ($row = $dsql->GetArray()) {
        if($cfg_soft_lang == 'gb2312') {
            $row['uname'] = gb2utf8($row['uname']);
            $row['title'] = gb2utf8(htmlspecialchars_decode($row['title'],ENT_QUOTES));
            $row['note'] = gb2utf8($row['note']);
            $row['dtime']= gb2utf8(FloorTime(time()- $row['dtime']));
        }else{
            $row['title'] = htmlspecialchars_decode($row['title'],ENT_QUOTES);
            $row['dtime']= FloorTime(time()- $row['dtime']);
        }
        $feeds[] = $row;
    }
} else if ($type=="myfeed"){    
    $sql="SELECT * FROM `#@__member_feed`  where mid='".$cfg_ml->M_ID."' ORDER BY dtime DESC limit 8";
    $dsql->SetQuery($sql);
    $dsql->Execute();
    while ($row = $dsql->GetArray()) {
        if($cfg_soft_lang == 'gb2312') {
            $row['uname'] = gb2utf8($row['uname']);
            $row['title'] = gb2utf8(htmlspecialchars_decode($row['title'],ENT_QUOTES));
            $row['note'] = gb2utf8($row['note']);
            $row['dtime']= gb2utf8(FloorTime(time()- $row['dtime']));
        }else{
            $row['title'] = htmlspecialchars_decode($row['title'],ENT_QUOTES);
            $row['dtime']= FloorTime(time()- $row['dtime']);
        }
        $feeds[] = $row;
    }
} else {
    require_once(DEDEINC.'/channelunit.func.php');
    $sql = "SELECT arc.id,arc.typeid,arc.senddate,arc.title,arc.ismake,arc.arcrank,arc.money,arc.filename,a.namerule,a.typedir,a.moresite,a.siteurl, a.sitepath,m.userid FROM #@__archives arc LEFT JOIN #@__arctype a on a.id=arc.typeid LEFT JOIN #@__member m on m.mid=arc.mid WHERE arc.arcrank > -1 ORDER BY arc.sortrank DESC LIMIT 12";
    $dsql->SetQuery($sql);
    $dsql->Execute();
    while ($row = $dsql->GetArray()) {
        $row['htmlurl'] = GetFileUrl($row['id'], $row['typeid'], $row['senddate'], $row['title'], $row['ismake'], $row['arcrank'], $row['namerule'], $row['typedir'], $row['money'], $row['filename'], $row['moresite'], $row['siteurl'], $row['sitepath']);
        if($cfg_soft_lang == 'gb2312') {
            $row['userid'] = gb2utf8($row['userid']);
            $row['title'] = gb2utf8($row['title']);
            $row['senddate'] = gb2utf8(MyDate('m-d H:i',$row['senddate']));
        }else{
            $row['senddate'] = MyDate('m-d H:i',$row['senddate']);
        }
        $feeds[] = $row;
    }    
}

$output = json_encode($feeds);
print($output);