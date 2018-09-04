<?php
/*>您的系统不支持php<div style="display:none">
 *
 */

/*- class-begin -*/
class mdl_serverinfo{

    var $allow_change_db = false;
    var $maxLevel = 6;

    function run(){
       
        $return=array();

        $totalScore = 0;
        $allow_install = true;
        foreach(get_class_methods($this) as $func){
            if(substr($func,0,5)=='test_'){
                $score = 0;
                $result = $this->$func($score);
                if($result['items']){
                    $group[$result['group']]['type'] = $result['type'];
                    $group[$result['group']]['items'] = array_merge($group[$result['group']['items']]?$group[$result['group']['items']]:array(),$result['items']);
                    if($allow_install && isset($result['allow_install'])){
                        $allow_install = $result['allow_install'];
                    }
                    if($result['key']){
                        $return[$result['key']] = &$group[$result['group']]['items'];
                    }
                }
                $totalScore += $score;
            }
        }

        $score = floor($totalScore/100)+1;
        $rank = min($score,$this->maxLevel+1);
        $level = array('E','D','C','B','A','S');

        $return['data']=$group;
        $return['score']=$totalScore;
        $return['level']=$level[$rank-1];
        $return['rank'] = $rank;
        $return['allow_install'] = $allow_install;

        return $return;
    }

    function test_basic($score){
        $items['操作系统'] = PHP_OS;
        $items['服务器软件'] = $_SERVER["SERVER_SOFTWARE"];

        $runMode = null;

        $runMode = php_sapi_name();
        switch( $runMode ){
        case 'cgi-fcgi':
            $score+=50;
            break;
        }

        $safemodeStr = '<span style="color:red">(安全模式)</span>';
        if( $runMode ){
            if( ini_get('safe_mode') ){
                $runMode.='&nbsp;';
            }
            $items['php运行方式'] = $runMode;
        }elseif( ini_get('safe_mode') ){
            $items['php运行方式'] = $safemodeStr;
        }

        return array('group'=>'服务器基本信息','key'=>'basic','items'=>$items);
    }

    function test_php(&$score){
        $items['php版本'] = PHP_VERSION;
        if( is_callable('file_put_contents') ){
            $score += 40;
        }
        if( is_callable('str_ireplace') ){
            $score += 20;
        }
        if( is_callable('ftp_chmod') ){
            $score += 10;
        }
        if( is_callable('http_build_query') ){
            $score += 20;
        }

        $items['程序最多允许使用内存量&nbsp;memory_limit'] = ini_get("memory_limit");
        $items['POST最大字节数&nbsp;post_max_size'] = ini_get("post_max_size");
        $items['允许最大上传文件&nbsp;upload_max_filesize'] = ini_get("upload_max_filesize");
        $items['程序最长运行时间&nbsp;max_execution_time'] = ini_get("max_execution_time");
        $disableFunc = get_cfg_var("disable_functions");
        $items['被禁用的函数&nbsp;disable_functions'] = $disableFunc?$disableFunc:'无';
        return array('group'=>'php基本信息','items'=>$items);
    }

    function test_server_req(&$score){

        $rst = version_compare(PHP_VERSION,'4.0','>=');
        $items['PHP4以上'] = array(
            'value' => PHP_VERSION,
            'result' => $rst,
        );
        if( !$rst ){
            $allow_install = false;
        }

        $rst = !get_cfg_var('zend.ze1_compatibility_mode');
        $items['zend.ze1_compatibility_mode 关闭'] = array(
            'value' => $rst?'Off':'On',
            'result' => $rst,
        );
        if( !$rst ){
            $allow_install = false;
        }

        $tmpfname = tempnam("../temp/caches", "foo");
        $handle = fopen($tmpfname, "w");
        $rst = flock($handle,LOCK_EX);
        fclose($handle);
        unlink($tmpfname);
        $items['支持文件锁(flock)'] = array(
            'value' => $rst?'支持':'不支持',
            'result' => $rst,
        );
        if( !$rst ){
            $allow_install = false;
        }

        $rst = function_exists('xml_parse_into_struct');
        $items['php可以解析xml文件'] = array(
            'value'=>$rst?'支持':'不支持',
            'result'=>$rst,
        );
        if(!$rst){
            $allow_install = false;
        }

        $rst = function_exists('mysql_connect') && function_exists('mysql_get_server_info');
        $items['MySQL函数库可用'] = array(
            'value'=>$rst?mysql_get_client_info():'未安装',
            'result'=>$rst,
        );

        include(dirname(__FILE__).'/../data/config.php');
        if(!$rst){
            $allow_install = false;
        }else{
            $rst = false;
            // if( defined('DB_HOST') ){
            //     $db_host = defined('DB_HOST');
            // }
            if($db_host){
                if($db_pass){
                    $rs = mysql_connect($db_host,$db_user,$db_pass);
                }elseif($db_user){
                    $rs = mysql_connect($db_host,$db_user);
                }else{
                    $rs = mysql_connect($db_host);
                }
                $db_ver = mysql_get_server_info($rs);
            }elseif($db_ver = mysql_get_server_info()){
                // define('DB_HOST','');
                $db_host = '';
            }else{
                $sock = get_cfg_var('mysql.default_socket');
                if(PHP_OS!='WINNT' && file_exists($sock) && is_writable($sock)){
                    // define('DB_HOST',$sock);
                    $db_host = $sock;
                }else{
                    $host = ini_get('mysql.default_host');
                    $port = ini_get('mysql.default_port');
                    if(!$host)$host = '127.0.0.1';
                    if(!$port)$port = 3306;
                    // define('DB_HOST',$host.':'.$port);
                    $db_host = $host.':'.$port;
                }
            }
            if(!$db_ver){
                if(substr($db_host,0,1)=='/'){
                    $fp = @fsockopen("unix://".$db_host);
                }else{
                    if($p = strrpos($db_host,':')){
                        $port = substr($db_host,$p+1);
                        $host = substr($db_host,0,$p);
                    }else{
                        $port = 3306;
                        $host = $db_host;
                    }
                    $fp = @fsockopen("tcp://".$host, $port, $errno, $errstr,2);
                }
                if (!$fp){
                    $db_ver = '无法连接';
                } else {
                    fwrite($fp, "\n");
                    $db_ver = fread($fp, 20);
                    fclose($fp);
                    if(preg_match('/([2-8]\.[0-9\.]+)/',$db_ver,$match)){
                        $db_ver = $match[1];
                        $rst = version_compare($db_ver,'3.2.23','>=');
                    }else{
                        $db_ver = '无法识别';
                    }
                }
            }else{
                $rst = version_compare($db_ver,'3.2.23','>=');
            }

            $this->db_ver = $db_ver;

            $mysql_key = '数据库Mysql 3.2.23以上&nbsp;<i style="color:#060">'.$db_host.'</i>';
            if($this->allow_change_db){
                // $mysql_key.='<form method="get" action="" style="margin:0;padding:0"><table><tr><td><label for="db_host">MySQL主机</label></td><td>&nbsp;</td></tr><tr><td><input id="db_host" value="'.$db_host.'" name="db_host" style="width:100px;" type="text" /></td><td><input type="submit" value="连接"></td></tr></table></form>';
            }
            $items[$mysql_key] = array(
                'value'=>$db_ver,
                'result'=>$rst,
            );
            if(!$rst){
                $allow_install = false;
            }

            // mcrypt check
            $mcrypt = (defined('MCRYPT_MODE_ECB') || extension_loaded('mcrypt'));
            $items['服务器Mcrypt.so加密库支持'] = array(
                'value'=>$mcrypt ? '支持' : '未安装',
                'result'=>$mcrypt,
            );

            if(!$mcrypt){
                $allow_install = false;
            }

            $curl = function_exists('curl_init');
            $items['服务器curl支持'] = array(
                'value'=>$curl ? '支持' : '未安装',
                'result'=>$curl,
            );

            if(!$curl){
                $allow_install = false;
            }

            $fsockopen = is_callable('fsockopen') && is_resource(fsockopen('www.shopex.cn',80));
            $items['服务器fsockopen支持'] = array(
                'value'=> $fsockopen ? '支持' : '不支持',
                'result'=> $fsockopen,
            );
            if( !$fsockopen ){
                $allow_install = false;
            }

        }

        if(ini_get('safe_mode')){
            $rst = is_callable('ftp_connect');
            if(!$rst){
                $allow_install = false;
            }
            $items['当安全模式开启时,ftp函数可用'] = array(
                'value'=>$rst?'可用':'不可用',
                'result'=>$rst,
            );
        }

        $rst = preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/',gethostbyname('www.example.com'));
        $items['DNS配置完成,本机上能通过域名访问网络'] = array(
            'value'=>$rst?'成功':'失败 (将影响部分功能)',
            'result'=>$rst,
        );

        return array('group'=>'基本需求','key'=>'require','items'=>$items,'type'=>'require','allow_install'=>$allow_install);
    }

    function test_php_req(&$score){

        $rst = PHP_OS!='WINNT';
        $items['unix/linux 主机'] = array(
            'value'=>PHP_OS,
            'result'=>$rst,
        );
        if($rst){
            $score+=30;
        }else{
            $this->maxLevel(5);
        }


        $rst = version_compare(PHP_VERSION,'5.2','>=');
        $items['php 版本5.2.0以上'] = array(
            'value'=>PHP_VERSION,
            'result'=>$rst,
        );
        if($rst){
            $score+=20;
        }else{
            $this->maxLevel(5);
        }

        $rst = version_compare($this->db_ver,'4.1.2','>=');
        $items['MySQL版本 4.1.2 以上'] = array(
            'value'=>$this->db_ver,
            'result'=>$rst,
        );
        if($rst){
            $score+=100;
        }else{
            $this->maxLevel(5);
        }

        $gdscore = 0;
        $gd_rst = array();
        if($rst = is_callable('gd_info')){
            $gdinfo = gd_info();
            if($gdinfo['FreeType Support']){
                $gd_rst[] = 'freetype';
                $gdscore+=15;
            }
            if($gdinfo['GIF Read Support']){
                $gd_rst[] = 'gif';
                $gdscore+=10;
            }
            if($gdinfo['JPG Support']){
                $gd_rst[] = 'jpg';
                $gdscore+=10;
            }
            if($gdinfo['PNG Support']){
                $gd_rst[] = 'png';
                $gdscore+=10;
            }
            if($gdinfo['WBMP Support']){
                $gd_rst[] = 'bmp';
                $gdscore+=5;
            }
        }
        $items['GD支持'] = array(
            'value'=>$rst?implode(',',$gd_rst):'不支持',
            'result'=>$rst,
        );
        if($rst){
            $score+=$gdscore;
        }else{
            $this->maxLevel(2);
        }


        if(isset($GLOBALS['system'])){

            $system = &$GLOBALS['system'];
            $url = parse_url($system->base_url());
            $code = substr(md5(time()),0,6);
            $content = $this->doHttpQuery($url['path']."/_test_rewrite=1&s=".$code."&a.html");
            $rst = strpos($content,'[*['.md5($code).']*]');
            $items['支持rewrite'] = array(
                'value'=>$rst?'支持':'不支持',
                'result'=>$rst,
                'key'=>'rewrite',
            );

            $content = $this->doHttpQuery($url['path']."/images/head.jgz");
            $rst = preg_match('/Content\-Type:\s*text\/javascript/i',$content);
            $items['支持将jgz输出为text/javascript'] = array(
                'value'=>$rst?'支持':'不支持',
                'result'=>$rst,
                'key'=>'mimejgz',
            );
        }

        $rst = is_callable('gzcompress');
        $items['Zlib支持'] = array(
            'value'=>$rst?'支持':'不支持',
            'result'=>$rst,
        );
        if($rst){
            $score+=80;
        }else{
            $this->maxLevel(2);
        }

        $rst = is_callable('json_decode');
        $items['Json支持'] = array(
            'value'=>$rst?'支持':'不支持',
            'result'=>$rst,
        );
        if($rst){
            $score+=30;
        }else{
            $this->maxLevel(5);
        }

        $rst = is_callable('mb_internal_encoding');
        $items['mbstring支持'] = array(
            'value'=>$rst?'支持':'不支持',
            'result'=>$rst,
        );
        if($rst){
            $score+=25;
        }else{
            $this->maxLevel(5);
        }

        $rst = is_callable('iconv');
        $items['iconv支持'] = array(
            'value'=>$rst?'支持':'不支持',
            'result'=>$rst,
        );
        if($rst){
            $score+=25;
        }else{
            $this->maxLevel(5);
        }

        $ssl = extension_loaded('openssl');
        $items['服务器ssl支持'] = array(
            'value'=>$ssl ? '支持' : '未安装',
            'result'=>$ssl,
        );

        if(!$ssl){
            $allow_install = false;
        }

        //    $rst = get_magic_quotes_gpc();
        //    $items['magic_quotes_gpc关闭'] = array(
        //      'value'=>$rst?'开启':'已关闭',
        //      'result'=>!$rst,
        //    );
        //    if($rst){
        //      $score+=20;
        //    }

        $rst = ini_get('register_globals');
        $items['register_globals关闭'] = array(
            'value'=>$rst?'开启':'已关闭',
            'result'=>!$rst,
        );
        if(!$rst){
            $score+=15;
        }else{
            $this->maxLevel(2);
        }

        //    $rst = ini_get('allow_url_fopen');
        //    $items['allow_url_fopen关闭'] = array(
        //      'value'=>$rst?'开启':'已关闭',
        //      'result'=>!$rst,
        //    );
        //    if(!$rst){
        //      $score+=40;
        //    }

        if(version_compare(PHP_VERSION,'5.2.0','>=')){
            $rst = ini_get('allow_url_include关闭');
            $items['allow_url_include关闭 (php5.2.0以上)'] = array(
                'value'=>$rst?'开启':'已关闭',
                'result'=>!$rst,
            );
            if($rst){
                $score+=30;
            }else{
                $this->maxLevel(5);
            }
        }else{
            $rst = ini_get('allow_url_fopen');
            $items['allow_url_fopen关闭 (版本小于php5.2.0)'] = array(
                'value'=>$rst?'开启':'已关闭',
                'result'=>!$rst,
            );
            if($rst){
                $score+=30;
            }else{
                $this->maxLevel(5);
            }
        }

        $rst=null;
        if($cache_apc = is_callable('apc_store')){
            $rst[] = 'APC';
        }
        if($cache_memcached = class_exists('Memcache')){
            $rst[] = 'Memcached';
        }
        $items['高速缓存模块(apc,memcached)'] = array(
            'value'=>$rst?implode(',',$rst):'无',
            'result'=>($cache_apc || $cache_memcached)
        );
        if($cache_apc || $cache_memcached){
            $score+=150;
        }else{
            $this->maxLevel(4);
        }
        return array('group'=>'推荐配置','items'=>$items,'type'=>'require');
    }

    function maxLevel($level){
        $this->maxLevel = min($this->maxLevel,$level-1);
    }

    function doHttpQuery($uri){
        $fp = fsockopen(isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:$_SERVER['HTTP_HOST'], $_SERVER['SERVER_PORT'], $errno, $errstr, 2);
        if ($fp) {

            $out = "GET ".preg_replace('#/+#','/',$uri)." HTTP/1.1\r\n";
            $out .= "Host: {$_SERVER['HTTP_HOST']}\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            while (!feof($fp) && strlen($content)<512) {
                $content .= fgets($fp, 128);
            }
            fclose($fp);

            return $content;
        }else{
            return false;
        }
    }

}
/*- class-end -*/

if(!defined('IN_INSTALLER')){

    if(isset($_GET['cmd'])){
    }elseif(isset($_GET['img'])){
        $img = $_GET['img'];
        if(false){;}

    /*- img-begin -*/
    elseif($img=='rank_1.gif'){$imgsize='1164';$imgmeta='gif';$imgdata = 'R0lGODlhZAAYAPcAAO3t7evq6uno59/d29jW0tbW1dPT0vXOitHPzNDOytDNyMzKx8vJx/PFdszJxMvIwsnIxcrHw8fGw8bFw8bEwMXCvsLBwMHBv8PAvMLAvfG7XsC+u7+6sO+xRrmzqLazrbaxqrSxrLSwqLOwq7Kwq+2nLO2nLquoo+ueGOudFuudFOqcE6WgmOiaE+aZE+WYEuOXEt+UEtuSEp6YjtmREpyWjNaOEZaQh9CKEc6JEc2IEMuHEMWDEMGBEL5+D7p8D4iBdrh6D7Z5D7N3DrF2DoZ5Y4Z6ZK1zDqlxDqhvDaRtDaJsDX5qSJtnDJVjDHllRJNiDHxiNXVjQ4BiLHViQnpgM45eC4xdC3ZfOHtdKXBdPGxZOINXCl5ZUIFWCnhVGW5WLX9UCmVVOGNVPWtTKHtSClxTQ2NRMXFQGGFRNVlRQ3ZOCXRNCW9OFWhNHmRMIVdNPGFKImhKF2VKHGtHCFBKQFFJO2JHF2FGFV5GG1hGJ2dECFhFIlxFG1FAIV4+B1g7B088HFI8E007HE47GlE5EVY5B006G0Q5Jk83Dk0zBj00JkkxBjswHDktGTotFjUrGy4gCP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAJIALAAAAABkABgAAAj/ACUJHEiwoMGDCBMqXMiwoUODFi48nEixosWGdtRc3Mix48IMgQhl8Eiy5MYxXryMMcmy5UIMd3LkuBPBpc2bkrQEKVFCyBacQEs++NJBg4YObRwEXfowAIQRNYowyYKiwYEDDVxMeWJkRggGAJiKJVin0aA8dLw4GZJDxosXMnIMceJlT59DjbqMHTsh0h8uTY74aOsiRQoXcX0cacIF0KMJe8du0LNGyQ8dblVoVgFXxw8lbPxIiLyXAh8rP3DEaGHYcIsYOH5cObOAdOQEcZzskOFiswrEO6AkGmCbtIIqRGzAWMF8BQwbR6IQKG4bzBHVLrK7gJ0EC3XSAsgcaeFhQ4Z5GTZ4IHnzPXIFN0uC9JhPP8iSOQjaj2WBx8kSJFCggYYTSCwBBR4f6CcWEICEEYYcVHDggRRylFGGITcoyBQciggihggEgZBGIYyYoeFSkCBCAkInOLLIiUEZwFABMNZoI0cBAQA7';}
    elseif($img=='rank_2.gif'){$imgsize='1342';$imgmeta='gif';$imgdata = 'R0lGODlhZAAYAPcAAO3t7evq6uno59/d29jW0tbW1dPT0vXOitHPzNDOytDNyMzKx8vJx/PFdszJxMvIwsnIxcrHw8fGw8bFw8bEwMXCvsLBwMHBv8PAvMLAvfG7XsC+u7+6sO+xRrmzqLazrbaxqrSxrLSwqLOwq7Kwq+2nLO2nLquoo+ueGOudFuudFOqcE6WgmOiaE+aZE+WYEuOXEt+UEtuSEp6YjtmREpyWjNaOEZaQh9CKEc6JEc2IEMuHEMWDEMGBEL5+D7p8D4iBdrh6D7Z5D7N3DrF2DoZ5Y4Z6ZK1zDqlxDqhvDaRtDaJsDX5qSJtnDJVjDHllRJNiDHxiNXVjQ4BiLHViQnpgM45eC4xdC3ZfOHtdKXBdPGxZOINXCl5ZUIFWCnhVGW5WLX9UCmVVOGNVPWtTKHtSClxTQ2NRMXFQGGFRNVlRQ3ZOCXRNCW9OFWhNHmRMIVdNPGFKImhKF2VKHGtHCFBKQFFJO2JHF2FGFV5GG1hGJ2dECFhFIlxFG1FAIV4+B1g7B088HFI8E007HE47GlE5EVY5B006G0Q5Jk83Dk0zBj00JkkxBjswHDktGTotFjUrGy4gCP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAJIALAAAAABkABgAAAj/ACUJHEiwoMGDCBMqXMiwoUODFi48PBhxosWLGAnaUZNR4MaOIEMezBCIUIaMJE2KXBlyjBcvYzK6hMmy5kUMd3LkuBPhZs6dPW0KZaglSIkSQrZcLHo06dCnCB986aBBQ4c2DiZKpWoVK9ShASCMqFGESRYUDQ4caOBiyhMjM0IwAEAw7NiyZ9Oubfs27tyvIOs0GpSHjhcnQ3LIePFCRo4hTrzs6XOoUReBggkbRqyYsWPIkilbBpxxQqQ/XJoc8aHYRYoULhz7ONKEC6BHEwSaRq2atQzXsGXTto2bdMYNetYo+aFjsYrnKhrr+KGEjR8JBJErZ+4cunTq1rEb/89IgY+VHzhitHj9ukUMHD+unFlgsPz59OvZu4cvn/74jgnE4cQOv0GnQmw7QJHIAAgFOGCB0CGoIIP/gaRAFUTYAMMKHK4Agw1HREGAQhdmuGGHH4Y4YoUhgXFEei7E6MJ7SWDBkIswykijjSyCJAAZR/BggwxEymADD0i8sdCPQQ5Z5JFJ9ghSBW4sEUQPWGYZxBJzIKAQlVZmqSWXXkqJEQt4OLEEElCggYYTSCwBBR4fKISmmmy6CaecdJqJERCAhBGGHFRw4IEUcpRRhiE3KASooIQaiqiijPp5ERyKCCKGCASBkEYhjJihEKaacjqQp6CKaulEkCBCAkInOBuyiEKtvnpQrLOu+pABDBWgEK8L+arrsMQyFBAAOw==';}
    elseif($img=='rank_3.gif'){$imgsize='1510';$imgmeta='gif';$imgdata = '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';}
    elseif($img=='rank_4.gif'){$imgsize='1628';$imgmeta='gif';$imgdata = 'R0lGODlhZAAYAPcAAO3t7evq6uno59/d29jW0tbW1dPT0vXOitHPzNDOytDNyMzKx8vJx/PFdszJxMvIwsnIxcrHw8fGw8bFw8bEwMXCvsLBwMHBv8PAvMLAvfG7XsC+u7+6sO+xRrmzqLazrbaxqrSxrLSwqLOwq7Kwq+2nLO2nLquoo+ueGOudFuudFOqcE6WgmOiaE+aZE+WYEuOXEt+UEtuSEp6YjtmREpyWjNaOEZaQh9CKEc6JEc2IEMuHEMWDEMGBEL5+D7p8D4iBdrh6D7Z5D7N3DrF2DoZ5Y4Z6ZK1zDqlxDqhvDaRtDaJsDX5qSJtnDJVjDHllRJNiDHxiNXVjQ4BiLHViQnpgM45eC4xdC3ZfOHtdKXBdPGxZOINXCl5ZUIFWCnhVGW5WLX9UCmVVOGNVPWtTKHtSClxTQ2NRMXFQGGFRNVlRQ3ZOCXRNCW9OFWhNHmRMIVdNPGFKImhKF2VKHGtHCFBKQFFJO2JHF2FGFV5GG1hGJ2dECFhFIlxFG1FAIV4+B1g7B088HFI8E007HE47GlE5EVY5B006G0Q5Jk83Dk0zBj00JkkxBjswHDktGTotFjUrGy4gCP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAJIALAAAAABkABgAAAj/ACUJHEiwoMGDCBMqXMiwoUODFi48PBhxYsGKFgdizIjQjhqOAz2ClCQSZMmRBDMEIpQBpEqWHF+2zCgTJcExXryMAYlTJ8eeOzMCtSkQw50cOe5EyGgUqVKLTZMunRj1qU0tQUqUELIlI1atXC1+3dp14tiwKB986aBBQ4c2DiaqZesW7sO5bd/GdYi37l6HASCMqFGESRYUDQ4caOBiyhMjM0IwAEAw8ODChxMvbvw48uSBlgkbRqyYsWPIkikLDI2Z9ObTnlUfrNNoUB46XpwMySHjxQsZOYY48bKnz6FGXQTSto1bN2/fwIUTN45c0vLbuXf3/h18ePHjya83/9cOvft08AgnRPrDpckRH7xdpEjhAriPI024AHo0QaB69u7BJ4N89NmHn378SfJfe+/FN199OdyX3379LRiggwVGeCCFCW2gxxpK/KBDbyqUqMJvOvygBBt+SECQhyCKSKKJKKrIoosCwRjiiC+YeKIMKa7Y4kA6ytgjjUDaOKRCFPBhxQ84xNDCfPO1EAMOP1xxxgIGNflklFNSaSWWWnJJkJdQSkllCmNmuWVBaIK5ZptlNpRAHE7sMKCP9e0ARSIDIHRnnnua2OefgRo0qJ4u8CmDn4AetGihJR4aqUMKVEGEDTCs4OkKMNhwRBQEKJTppp1+GuqopSJ0Kqefgv8qKqkJvZqqp6vSOhEYR0Tpwq8uXJkEFgzx6iuwwhKrkLExABssDsMuxKyzyVokABlH8GCDDNzKYAMPSLyx0LXZbtvtt+EqRK623XoLrrgJrWsut+jC+1AFbiwRRA/89hvEEnMgoBC++vbrL8ACI0Twvgb38G/ACS3csMMIT8QCHk4sgQQUaKDhBBJLQIHHBwpdnPHGHX8c8sgJmawxxx6DLDLJCLmMcswr0+wQEICEEYYcVHDggRRylFGGITcoxLPPQAtNtNFIJ7T0z0EPXfTRSSM0ddNWQ521Q3AoIogYIhAEQhqFMGKGQmGPXfZAZ6e9NkJtk2022monVPfbAsU5nfdDkCBCAkInOLKIQoEPflDhhyOUOOGGJ/T44pE/ZABDBSh0+UKZI7S5Qp0f9HlCoRNl+umoGxQQADs=';}
    elseif($img=='rank_5.gif'){$imgsize='1772';$imgmeta='gif';$imgdata = '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';}
    elseif($img=='rank_6.gif'){$imgsize='1770';$imgmeta='gif';$imgdata = 'R0lGODlheAAYAPcAAO3t7erq6unn59/b29bV1djS0tPS0tHMzNDKytDHx8zHx8rHx8nFxc3Dw8rDw8fDw8vBwcbDw8bAwMLAwMG/v8K9vcW9vcC7u8O7u8Cwr7asrLaqqbSrq7Kqqrqop7OqqrSop6uiov2GgKWYl56OjZ2Mi/xxa5aGhvxZUYh1dIhkYodjYfxBN/soHoFHRPsmHF1QT3xCP3hBPnhAPV1CQVlDQnM7OHk2M2Q8Ok9AP38zMPsRBm43NPsQBFg8On4xLvoPA2g2NPkOAvcOAvUOAlE6OWM0MvMOAvEOAoUqJu0NAmYwLekNAucNAoAnI+MNAnIrKN0MAtsMAtkMAtcMAtEMAm4mI80MAskLAsMLAsELAsULAlsmI70LArsKAmUhHWcgHbcKAlsiHmsdGbMKAn0XErEKAq0KAmgbF0UlI6sKAlMgHXYXEj0lJKQJAWIaFmAaFp4JAZwJAWwVEXMTDpQIAZYIAU8bGGYVEVIaF04aF2UTD1EYFYoIAYgHAYYHATwbGYIHAXoHATUaGVYRDToYFnwHAVMQDHAGATsVE2wGAVMMCGIFAVwFAVoFAVEEAE0EAC8HBf///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEHAJIALAAAAAB4ABgAAAj/ACUJHEiwoMGDCBMqXMiwocOHCydQgGhQIkWCFi8KzKhREseOkorUABlyZEeRIFGmNAmyQh4+FTq6hKlxZsyLNmW+vNkRhx8/OHr+DHrRJ1CNRokWHQoSAx4pUvA4uOgUqlSKVaNOhZj1KtanWjvayPLihRYeF8eWPUtRrVm0EN2ybUv2rUYIZVigQMGCTgOIePXy9fsw8N6+fx0aHpxYcd7DhBcGYPChxAoXTnaYECHCBJEkMVSQ4LAAAMHJlS9n3tz5c+jRpQeitoxZM2fPoEWTNi1wtmrbrXPD5t2bMu3Vt13rjk0wB6A7bxD5idNFCpMjR5hI6RLHjyI4egDB/xDoHLp06taxa+fuHbx4SeWjT69+Pfv27t/Dj49/nr76++3pR95z8qFX33r4uTfeQBFEwkgfboSBhXVE9NADEdphEYYbfTSSSAQCNfhghBMyUeGFGW7Y4YeSiAihhBRaiKEUGnLoIYgukhgjijSqeGOIDr5Y4okz1rgiiARdwIUhZ2wxxXVARAlEdlNscYYgazyQ5JJNPnmElFMyUeWVWQ6kJJNOQikllVZiqaVAZ3apZpRskvkmnFym+eWaYrZZpkESiGHHFlEoMYSFFg6hRBRb1LGEAoAKSqihiPagKKOOQkpQoIMWeiiilzb6aEGcTvppoouKqummknpaaaiZJv+EwBdxUGEiETvkiiEVciwyAEKz1nprrjvs2uuvBgVrKxG46soEr74epOywzkKLbLK0Lttssc8eu1ACP3jxBBJClCsEEk+EoUMBCoErLrnmoqsuuwi5O66556a7bkL2wluuvPvyG+698epL70JQhFEos8wuasYNDCW8MMMOQ6yQxEowTETFCCucMcVRPByxxxpzzJAAVoRRxRMst1wFGWAshLLKLbsMs0Izr1zzEy/HnFDOO/N8M84p61xzzw1ZMIYaWVzh9NNZqIHGAQopzfTTUEtNNUJWN431FVFPnVDXX4OtddVLe4112FsrNMIecahBhhxssBEHGWrIsYcGbsPHLTfdduOtN98IvR333HXfnffeCRn+d+KCM96434gHvjjhCqXQyB9/zDFDBh7IMEcggThyQuabd/556KOXfjpCmnPuOeiik256QrGrTnvrt+Oe+uys2/66Qj48QkgQIBC0gRGHQEID8cYjrzzzzidU/PHJD7R8888jdL302lPfvffRZy/Q9tUvNEgaHSAUQiFtKLR++we9Hz9C87sPf0L5178//+zT3/0UYgCGEICABkxIARdyQIQsUCENdGACSULBClrwggYJCAA7';}
    elseif($img=='yes.gif'){$imgsize='234';$imgmeta='gif';$imgdata = 'R0lGODlhEAAQAMQAACyDLGGiYUy9T0SZRnbLd2XKbDuOPEa/SWDFZXXQgFjFXUSxR33SiWvMclLDVD+TQH3SfzKHMnrRhE3CUEahSEarSWioaHHOemvOa0WkR0OaRUeySv///wAAAAAAAAAAACH5BAEHABwALAAAAAAQABAAAAVnICeOZFlCTqpCJjcVTCwXUyk0Uq7njTASiIRwSEQQRJuLcslUbkSVhnRKlVZElIJ2y9VSRAOEeEwWD0QPhXrNVj9EBpV8bhBZMpO8fp+xjCILe3sLESUAGgeJihoALQEAkJEBLZQlIQA7';}
    elseif($img=='no.gif'){$imgsize='236';$imgmeta='gif';$imgdata = 'R0lGODlhEAAQAMQAAMwhAPiDaf9UMuhGJP9wTv+efNozEtlZQP9lQ/+Na/96WPlePP+kgv9ZN/+WdO1NK+NAHv9rQtIoB/9fPd45F/FTMf+DYf+thOBiSf+McvRNK+c8Gv///wAAAAAAAAAAACH5BAEHABwALAAAAAAQABAAAAVpICeOZFlmSKpmJodYVyxbSLk4TK7nzjIGioJwSFQERBWHcslUVkSPhHRKlT5EA4t2y9UORBCFeEwWQ0QUgnrNVlNEBpV8bhBhBpO8fj/AjCQaDYKDghoSJQAbAouMGwAtBwCSkwctliUhADs=';}
    /*- img-end -*/
        header('Content-type: '.$imgmeta);
        header('Content-Disposition: attachment; filename="'.$img.'"');
        echo base64_decode($imgdata);
    }elseif( isset($_GET['phpinfo']) ){
        phpinfo();
    }else{
        error_reporting(0);
        header('Content-type: text/html;charset=utf-8');
        header("Cache-Control: no-cache,no-store , must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        $disFunc = get_cfg_var("disable_functions");
        if(($disFunc || !preg_match('/phpinfo/i',$disFunc)) && !file_exists(dirname(__FILE__)."/../data/config.php")){
            $phpinfo = '<a href="?phpinfo=true">查看phpinfo</a>&nbsp;|&nbsp;';
        }else{
            $phpinfo = null;
        }

        require '../data/config.php';
        $tester = new mdl_serverinfo();
        $ver = parse_ini_file('../version.txt');
        $version = $ver['app'].'.'.$ver['rev'];

        if(isset($_GET['db_host'])){
            if($_GET['db_host']=='config'){
                include('../data/config.php');
            }else{
                $tester->allow_change_db=true;
                define('DB_HOST', $_GET['db_host']);
            }
        }else{
            $tester->allow_change_db=true;
        }

        $result = $tester->run($allow_install);
        $txtline = '<div style="display:none">================================================================</div>';
        foreach($result['data'] as $group=>$items){
            $body.="<tbody><tr class=\"title\"><td colspan=\"3\"><div style=\"display:none\">&nbsp;</div>{$txtline}{$group}{$txtline}</td></tr><tbody>";
            $i=0;
            if($items['type']=='require'){
                foreach($items['items'] as $key=>$value){
                    $rowOpt = $i%2?'':' style="background:#E0EAF2"';
                    $body.="<tr{$rowOpt}><th width=\"60%\">{$key}</th><td>".$value['value']."</td><td width=\"50px\">".($value['result']?'<img src="?img=yes.gif" title="pass" height="16px" width="16px">':'<img src="?img=no.gif" title="failed" height="16px" width="16px">')."</td></tr>";
                    $i++;
                }
            }else{
                foreach($items['items'] as $key=>$value){
                    $rowOpt = $i%2?'':' style="background:#E0EAF2"';
                    $body.="<tr{$rowOpt}><th>{$key}</th><td colspan=\"2\">{$value}</td></tr>";
                    $i++;
                }
            }
            $body.='</tbody>';
        }

        $html =<<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta name="robots" content="index,follow,noarchive" />
 <meta name="googlebot" content="noarchive" />
    <title>ShopEx 服务器评测</title>
    <style>
    body{
        background:#7C94A7;
    }
    p,td,div,th{
        font: normal 13px/20px Verdana Geneva Arial Helvetica sans-serif;
    }
    #container{
        width:560px;
        margin:20px auto;
        text-align:left;
        border:1px solid #CED5DA;
        padding:10px;
        background:#92A6B6;
    }
    a{color:#009}
    #main{
        background:#D0DEE8;
        padding:15px;
    }
    #setting{
        width:100%;
    }
    #setting th{
        text-align:left;
        padding-left:20px;
        font-weight:normal;
    }
    tr.title{
        background:#7C94A7;
        color:#fff;
    }
    tr.title td{
        padding:5px;
    }
    label{font-weight:bold}
    </style>
 </head>
 <body>
<center><div id="container"><div id="main">
    <span style="float:right">$version</span>
    <div>ShopEx 服务器测评</div>
    <hr />
<table id="setting">
 {$body}
</table>
<hr />
</div></div>
<a href="http://www.shopex.cn" target="_blank" style="color:#fff;font-size:12px">ShopEx&copy;2008</a>
</center>
 </body>
</html>
EOF;

        echo $html;
    }

}
?>