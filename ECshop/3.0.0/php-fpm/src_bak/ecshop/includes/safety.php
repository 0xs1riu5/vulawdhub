<?php
$url_arr=array(
'xss'=>"\\=\\+\\/v(?:8|9|\\+|\\/)|\\%0acontent\\-(?:id|location|type|transfer\\-encoding)",
);
$args_arr=array(
'xss'=>"[\\'\\\"\\;\\*\\<\\>].*\\bon[a-zA-Z]{3,15}[\\s\\r\\n\\v\\f]*\\=|\\b(?:expression)\\(|\\<script[\\s\\\\\\/]|\\b(?:eval|alert|prompt|msgbox)\\s*\\(|url\\((?:\\#|data|javascript)",
'sql'=>"[^\\{\\s]{1}(\\s|\\b)+(?:select\\b|update\\b|insert(?:(\\/\\*.*?\\*\\/)|(\\s)|(\\+))+into\\b).+?(?:from\\b|set\\b)|[^\\{\\s]{1}(\\s|\\b)+(?:create|delete|drop|truncate|rename|desc)(?:(\\/\\*.*?\\*\\/)|(\\s)|(\\+))+(?:table\\b|from\\b|database\\b)|into(?:(\\/\\*.*?\\*\\/)|\\s|\\+)+(?:dump|out)file\\b|\\bsleep\\([\\s]*[\\d]+[\\s]*\\)|benchmark\\(([^\\,]*)\\,([^\\,]*)\\)|(?:declare|set|select)\\b.*@|union\\b.*(?:select|all)\\b|(?:select|update|insert|create|delete|drop|grant|truncate|rename|exec|desc|from|table|database|set|where)\\b.*(charset|ascii|bin|char|uncompress|concat|concat_ws|conv|export_set|hex|instr|left|load_file|locate|mid|sub|substring|oct|reverse|right|unhex)\\(|(?:master\\.\\.sysdatabases|msysaccessobjects|msysqueries|sysmodules|mysql\\.db|sys\\.database_name|information_schema\\.|sysobjects|sp_makewebtask|xp_cmdshell|sp_oamethod|sp_addextendedproc|sp_oacreate|xp_regread|sys\\.dbms_export_extension)",
'other'=>"\\.\\.[\\\\\\/].*\\%00([^0-9a-fA-F]|$)|%00[\\'\\\"\\.]");
if( !function_exists('filterData') ){
function filterData(&$data,$type){
    $data and filterArray($data,$type);
}
}
if( !function_exists('filterArray') ){
function filterArray(&$data,$filterarr){
    foreach ($data as $key => $value) {
        if( is_array($value) ){
            filterArray($data[$key],$filterarr);
        }else{
            if( $key and in_array(strtolower($key), array('goods_id','product_id','cat_id','gid','pid','uid','site_id'))){
                $value and $data[$key] = intval($value);
            }elseif ($key and in_array(strtolower($key),array('order_num','advance','advance_freeze','point_freeze','point_history','point','score_rate','state','role_type','advance_total','advance_consume'))) {
                unset($data[$key]);
            }
            // elseif( $key and in_array($key, array('tax_company','comment','contact','forward')) ){
            //     $value and $data[$key] = htmltotxt($value);
            // }
            elseif( $value ){
                $data[$key] = filter($value,$filterarr);
            }
        }
    }
}
}
if( !function_exists('filter') ){
function filter($str,$filterarr){
    foreach($filterarr as $value)
    {
        if (preg_match("/".$value."/is",$str)==1||preg_match("/".$value."/is",urlencode($str))==1)
        {
            header("Content-type: text/html; charset=utf-8"); 
            print "您的提交带有不合法参数,谢谢合作";
            exit();
        }
    }
    return $str;
}
}
$referer=empty($_SERVER['HTTP_REFERER']) ? array() : array($_SERVER['HTTP_REFERER']);
$query_string=empty($_SERVER["QUERY_STRING"]) ? array() : array($_SERVER["QUERY_STRING"]);

filterData($query_string,$url_arr);
filterData($_GET,$args_arr);
filterData($_POST,$args_arr);
filterData($_COOKIE,$args_arr);
filterData($referer,$args_arr);
filterData($_SERVER,$args_arr);
