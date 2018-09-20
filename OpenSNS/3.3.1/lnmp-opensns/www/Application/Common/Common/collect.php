<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-5
 * Time: 上午10:20
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */





//todo 扩展多种采集方式，以备一种方式采集不到页面

function get_content_by_url($url){
    $md5 = md5($url);
    $content = S('file_content_'.$md5);
    if(is_bool($content)){
        $content = curl_file_get_contents($url);
        S('file_content_'.$md5,$content,60*60);
    }
    return $content;
}


function curl_file_get_contents($durl){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, '');
    curl_setopt($ch, CURLOPT_REFERER,'b');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}