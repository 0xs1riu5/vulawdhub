<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-14
 * Time: AM9:28
 */

/**
 * 友好的时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 */
function friendlyDate($sTime,$type = 'normal',$alt = 'false') {
    if (!$sTime)
        return '';
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime      =   time();
    $dTime      =   $cTime - $sTime;
    $dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
    //$dDay     =   intval($dTime/3600/24);
    $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if($type=='normal'){
        if( $dTime < 60 ){
            if($dTime < 10){
                return L('_JUST_');    //by yangjs
            }else{
                return intval(floor($dTime / 10) * 10).L('_SECONDS_AGO_');
            }
        }elseif( $dTime < 3600 ){
            return intval($dTime/60).L('_MINUTES_AGO_');
            //今天的数据.年份相同.日期相同.
        }elseif( $dYear==0 && $dDay == 0  ){
            //return intval($dTime/3600).L('_HOURS_AGO_');
            return L('_TODAY_').date('H:i',$sTime);
        }elseif($dYear==0){
            return date("m月d日 H:i",$sTime);
        }else{
            return date("Y-m-d H:i",$sTime);
        }
    }elseif($type=='mohu'){
        if( $dTime < 60 ){
            return $dTime.L('_SECONDS_AGO_');
        }elseif( $dTime < 3600 ){
            return intval($dTime/60).L('_MINUTES_AGO_');
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600).L('_HOURS_AGO_');
        }elseif( $dDay > 0 && $dDay<=7 ){
            return intval($dDay).L('_DAYS_AGO_');
        }elseif( $dDay > 7 &&  $dDay <= 30 ){
            return intval($dDay/7) . L('_WEEK_AGO_');
        }elseif( $dDay > 30 ){
            return intval($dDay/30) . L('_A_MONTH_AGO_');
        }
        //full: Y-m-d , H:i:s
    }elseif($type=='full'){
        return date("Y-m-d , H:i:s",$sTime);
    }elseif($type=='ymd'){
        return date("Y-m-d",$sTime);
    }else{
        if( $dTime < 60 ){
            return $dTime.L('_SECONDS_AGO_');
        }elseif( $dTime < 3600 ){
            return intval($dTime/60).L('_MINUTES_AGO_');
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600).L('_HOURS_AGO_');
        }elseif($dYear==0){
            return date("Y-m-d H:i:s",$sTime);
        }else{
            return date("Y-m-d H:i:s",$sTime);
        }
    }
}