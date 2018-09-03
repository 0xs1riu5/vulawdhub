<?php
/**
 *
 * 计划任务
 *
 * 计划任务程序使用说明：
 * 本程序本身并不会执行任务任务，职责是跳转到要执行的任务网址（JS调用形式），或直接返回要执行的任务网址（客户端形式）
 * ·为了确保任务能执行完全，建议使用Dede的客户端工具，否则只能通过JS触发，但JS触发有很多不确定因素会导致任务不能完成；
 * ·JS触发方式：在所有文档页面中用JS调用/plus/task.php?client=js（必须禁用计划任务的密码，系统配置参数->其它选项）；
 * ·自行定制客户端：直接访问“http://网址/plus/task.php?clientpwd=管理密码”，会返回其中一个可执行任务的网址（没有可用任务则返回串：notask)，然后客户端运行这个网址即可。 
 *
 * @version        $Id: task.php 1 21:40 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(DEDEINC.'/dedetag.class.php');

if(empty($client)) $client = 'dede';
if(empty($clientpwd)) $clientpwd = '';
$cfg_task_pwd = trim($cfg_task_pwd);

//验证客户端工具密码
if(!empty($cfg_task_pwd) && $clientpwd != $cfg_task_pwd)
{
    echo ($client=='js' ? '' : 'notask');
    exit();
}

//取得当时间的各个值
$ntime = time();
$nformattime = GetDateTimeMk($ntime);
list($ndate, $ntime) = explode(' ', $nformattime);
list($y, $m, $d) = explode('-', $ndate);
list($hh, $mm, $ss) = explode(':', $ntime);

$daylimit = 24 * 3600;

$dsql->Execute('me', 'SELECT * FROM `#@__sys_task` WHERE islock=0 ORDER BY id ASC ');
while($arr = $dsql->GetArray())
{
    $starttime = $arr['starttime'];
    $endtime = $arr['endtime'];
	$ntime = strtotime("now");//(计划任务时间比较修正，2011.6.24 by：织梦的鱼)
    //跳过一次性运行，并且已经运行的任务
    if($arr['lastrun'] > $starttime && $arr['runtype']==1) continue;
    //超过了设定的任务结束时间
    if($endtime!=0 && $endtime < $ntime) continue;
    //未达到任务开始时间的任务
    if($starttime!=0 && $ntime < $starttime) continue;
    
    $dotime = GetMkTime($ndate.' '.$arr['runtime'].':00');
    $limittime = $daylimit * $arr['freq'];
    
    $isplay = false;
    //判断符合执行条件的任务
    if($arr['freq'] > 1 && $ntime - $arr['lastrun'] > $limittime )
    {
        $isplay = true;
    }
    else
    {
        $ndateInt = intval( str_replace('-', '', $ndate) );
        $rdateInt = intval( str_replace('-', '', GetDateMk($arr['lastrun'])) );
        list($th, $tm) = explode(':', $arr['runtime']);
        if($ndateInt > $rdateInt 
        && ($hh > $th || ($hh==$th && $mm >= $tm) ) )
        {
            $isplay = true;
        }
    }
    //符合需执行条件的任务
    if($isplay)
    {
        $dourl = trim($arr['dourl']);
        if(!file_exists("task/$dourl"))
        {
            echo ($client=='js' ? '' : 'notask');
            exit();
        }
        else
        {
            $getConfigStr = trim($arr['parameter']);
            $getString = '';
            if(preg_match('#t:#', $getConfigStr))
            {
                $getStrings = array();
                $dtp = new DedeTagParse();
                $dtp->SetNameSpace('t', '<', '>');
                $dtp->LoadString($getConfigStr);
                if(is_array($dtp->CTags))
                {
                    foreach($dtp->CTags as $k=>$ctag)
                    {
                        $getString .= ($getString=='' ? '' : '&').$ctag->GetAtt('key').'='.urlencode($ctag->GetAtt('value'));
                    }
                }
            }
            $dsql->ExecuteNoneQuery("Update `#@__sys_task` set lastrun='".time()."', sta='运行' where id='{$arr['id']}' ");
            if($getString !='' ) $dourl .= '?'.$getString; 
            if($client=='js') header("location:{$cfg_phpurl}/task/{$dourl}");
            else echo "{$cfg_phpurl}/task/{$dourl}";
            exit();
        }
    }
}
echo ($client=='js' ? '' : 'notask');
exit();