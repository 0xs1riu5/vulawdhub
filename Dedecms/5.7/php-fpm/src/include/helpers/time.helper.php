<?php  if(!defined('DEDEINC')) exit('dedecms');
/**
 * 时间戳小助手
 *
 * @version        $Id: time.helper.php 1 2010-07-05 11:43:09Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  返回格林威治标准时间
 *
 * @param     string  $format  字符串格式
 * @param     string  $timest  时间基准
 * @return    string
 */
if ( ! function_exists('MyDate'))
{
    function MyDate($format='Y-m-d H:i:s', $timest=0)
    {
        global $cfg_cli_time;
        $addtime = $cfg_cli_time * 3600;
        if(empty($format))
        {
            $format = 'Y-m-d H:i:s';
        }
        return gmdate ($format, $timest+$addtime);
    }
}


/**
 * 从普通时间转换为Linux时间截
 *
 * @param     string   $dtime  普通时间
 * @return    string
 */
if ( ! function_exists('GetMkTime'))
{
    function GetMkTime($dtime)
    {
        if(!preg_match("/[^0-9]/", $dtime))
        {
            return $dtime;
        }
        $dtime = trim($dtime);
        $dt = Array(1970, 1, 1, 0, 0, 0);
        $dtime = preg_replace("/[\r\n\t]|日|秒/", " ", $dtime);
        $dtime = str_replace("年", "-", $dtime);
        $dtime = str_replace("月", "-", $dtime);
        $dtime = str_replace("时", ":", $dtime);
        $dtime = str_replace("分", ":", $dtime);
        $dtime = trim(preg_replace("/[ ]{1,}/", " ", $dtime));
        $ds = explode(" ", $dtime);
        $ymd = explode("-", $ds[0]);
        if(!isset($ymd[1]))
        {
            $ymd = explode(".", $ds[0]);
        }
        if(isset($ymd[0]))
        {
            $dt[0] = $ymd[0];
        }
        if(isset($ymd[1])) $dt[1] = $ymd[1];
        if(isset($ymd[2])) $dt[2] = $ymd[2];
        if(strlen($dt[0])==2) $dt[0] = '20'.$dt[0];
        if(isset($ds[1]))
        {
            $hms = explode(":", $ds[1]);
            if(isset($hms[0])) $dt[3] = $hms[0];
            if(isset($hms[1])) $dt[4] = $hms[1];
            if(isset($hms[2])) $dt[5] = $hms[2];
        }
        foreach($dt as $k=>$v)
        {
            $v = preg_replace("/^0{1,}/", '', trim($v));
            if($v=='')
            {
                $dt[$k] = 0;
            }
        }
        $mt = mktime($dt[3], $dt[4], $dt[5], $dt[1], $dt[2], $dt[0]);
        if(!empty($mt))
        {
              return $mt;
        }
        else
        {
              return time();
        }
    }
}


/**
 *  减去时间
 *
 * @param     int  $ntime  当前时间
 * @param     int  $ctime  减少的时间
 * @return    int
 */
if ( ! function_exists('SubDay'))
{
    function SubDay($ntime, $ctime)
    {
        $dayst = 3600 * 24;
        $cday = ceil(($ntime-$ctime)/$dayst);
        return $cday;
    }
}


/**
 *  增加天数
 *
 * @param     int  $ntime  当前时间
 * @param     int  $aday   增加天数
 * @return    int
 */
if ( ! function_exists('AddDay'))
{
    function AddDay($ntime, $aday)
    {
        $dayst = 3600 * 24;
        $oktime = $ntime + ($aday * $dayst);
        return $oktime;
    }
}


/**
 *  返回格式化(Y-m-d H:i:s)的是时间
 *
 * @param     int    $mktime  时间戳
 * @return    string
 */
if ( ! function_exists('GetDateTimeMk'))
{
    function GetDateTimeMk($mktime)
    {
        return MyDate('Y-m-d H:i:s',$mktime);
    }
}

/**
 *  返回格式化(Y-m-d)的日期
 *
 * @param     int    $mktime  时间戳
 * @return    string
 */
if ( ! function_exists('GetDateMk'))
{
    function GetDateMk($mktime)
    {
        if($mktime=="0") return "暂无";
        else return MyDate("Y-m-d", $mktime);
    }
}


/**
 *  将时间转换为距离现在的精确时间
 *
 * @param     int   $seconds  秒数
 * @return    string
 */
if ( ! function_exists('FloorTime'))
{
    function FloorTime($seconds)
    {
        $times = '';
        $days = floor(($seconds/86400)%30);
        $hours = floor(($seconds/3600)%24);
        $minutes = floor(($seconds/60)%60);
        $seconds = floor($seconds%60);
        if($seconds >= 1) $times .= $seconds.'秒';
        if($minutes >= 1) $times = $minutes.'分钟 '.$times;
        if($hours >= 1) $times = $hours.'小时 '.$times;
        if($days >= 1)  $times = $days.'天';
        if($days > 30) return false;
        $times .= '前';
        return str_replace(" ", '', $times);
    }
}
