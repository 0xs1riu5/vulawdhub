<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/10/31
 * Time: 16:15
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
function cloudU($url, $p = array())
{
    $url = adminU($url, $p);
    return str_replace(__ROOT__, '', $url);
}

function appstoreU($url, $p = array())
{
    return cloud_url() . cloudU($url, $p);
}


function show_cloud_cover($path){
    //不存在http://
    $not_http_remote=(strpos($path, 'http://') === false);
    //不存在https://
    $not_https_remote=(strpos($path, 'https://') === false);
    if ($not_http_remote && $not_https_remote) {
        //本地url
        return str_replace('//', '/', cloud_url() . $path); //防止双斜杠的出现
    } else {
        //远端url
        return $path;
    }
}

/**云市场url
 * @return string
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
function cloud_url()
{
    return 'http://os.opensns.cn';
}