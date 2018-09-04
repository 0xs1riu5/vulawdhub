<?php

/**
 * ECSHOP 升级程序 之 模型
 * ============================================================================
 * 版权所有 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Date: 2009-05-14 $
 * $Id: lib_updater.php 2009-05-14Z liuhui $
 */

/**
 * 获得需要升级的版本号列表。
 * @param   string      $old_version    旧版本号
 * @param   string      $new_version    新版本号
 * @return  array
 */
function get_needup_version_list($old_version, $new_version)
{
    /* 需要升级的版本号列表 */
    $old_version = explode(' ',$old_version);
    $old_version = $old_version[0];
    $need_list = array();
    $need = false;
    $version_history = read_version_history();

    foreach ($version_history as $version)
    {
        if ($need)
        {
            $need_list[] = $version;
            if ($version == $new_version)
            {
                $need = false;;
            }
        }
        else
        {

            if ($version == $old_version)
            {

                $need = true;
            }
        }
    }

    return $need_list;
}

/**
 * 读取版本历史记录，并按字典序排序。
 * @return  array
 */
function read_version_history()
{
    $ver_history = array('v2.0.5');
    $pkg_root = ROOT_PATH . 'upgrade/packages/';
    $ver_handle = @opendir($pkg_root);
    while (($filename = @readdir($ver_handle)) !== false)
    {
        $filepath = $pkg_root . $filename;
        if(is_dir($filepath) && strpos($filename, '.') !== 0)
        {
            $ver_history[] = $filename;
        }
    }
    asort($ver_history);

    return $ver_history;
}

/**
 * 获得原有系统的语言。
 * @return  mixed       成功返回具体的语言，失败返回false。
 */
function  get_current_lang()
{
    global $db, $ecs;

    $lang = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . " WHERE code = 'lang'");
    $lang = $lang ? $lang : false;

    return $lang;
}

/**
 * 获得最新的版本号。
 * @return  string
 */
function get_new_version()
{
    return  preg_replace('/(?:\.|\s+)[a-z]*$/i', '', VERSION);
}

/**
 * 获得原有系统的版本号。
 * @return  string
 */
function  get_current_version()
{
    global $db, $ecs;

    $ver = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . " WHERE code = 'ecs_version'");
    $ver = $ver ? $ver : 'v2.0.5';
    $ver = preg_replace('/\.[a-z]*$/i', '', $ver);

    return $ver;
}

/**
 * 获得某个SQL文件的记录数(SQL语句数量)。
 * @return  int
 */
function get_record_number($next_ver, $type)
{
    global $db, $prefix;

    $file_path = ROOT_PATH . 'upgrade/packages/' . $next_ver . '/' . $type . '.sql';
    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix);

    $query_items = $se->parse_sql_file($file_path);

    if(empty($query_items))
    {
        return 0;
    }

    return count($query_items);
}

/**
 * 获得配置信息。
 * @return  array
 */
function get_config_info()
{
    global $_LANG;
    $config = array();

    $config['config_path'] = array($_LANG['config_path'], '/data/config.php');
    $config['db_host'] = array($_LANG['db_host'], $GLOBALS['db_host']);
    $config['db_name'] = array($_LANG['db_name'], $GLOBALS['db_name']);
    $config['db_user'] = array($_LANG['db_user'], $GLOBALS['db_user']);
    $config['db_pass'] = array($_LANG['db_pass'], '*******');
    $config['prefix'] = array($_LANG['db_prefix'], $GLOBALS['prefix']);
    if (isset($GLOBALS['timezone']))
    {
        $config['timezone'] = array($_LANG['timezone'], $GLOBALS['timezone']);
    }
    if (isset($GLOBALS['cookie_path']))
    {
        $config['cookie_path'] = array($_LANG['cookie_path'], $GLOBALS['cookie_path']);
    }
    if (isset($GLOBALS['admin_dir']))
    {
        $config['admin_dir'] = array($_LANG['admin_dir'], $GLOBALS['admin_dir']);
    }

    return $config;
}

/**
 * 创建版本对象。
 * @return  mixed   成功返回版本对象，失败返回false。
 */
function create_ver_obj($version)
{
    global $err, $_LANG;

    $file_path = ROOT_PATH . 'upgrade/packages/' . $version . '/' . $version . '.php';
    if (file_exists($file_path))
    {
        include_once($file_path);

        // 把 . 替换成 _，把空格去掉，前面加 up_
        $classname = 'up_' . str_replace('.', '_', str_replace(' ', '', $version));
        $ver_obj = new $classname();

        return $ver_obj;
    }
    else
    {
        $err->add($_LANG['create_ver_failed']);

        return false;
    }
}

/**
 * 机械化地升级数据库结构。
 * @return  boolean
 */
function update_structure_automatically($next_ver, $cur_pos)
{
    global $db, $prefix, $err;

    $ver_obj = create_ver_obj($next_ver);
    if (!is_object($ver_obj) || empty($ver_obj->sql_files['structure']))
    {
        return true;
    }

    $structure_path = ROOT_PATH . 'upgrade/packages/' . $next_ver . '/' . $ver_obj->sql_files['structure'];
    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix,
            ROOT_PATH . 'data/upgrade_'.$next_ver.'.log',
            $ver_obj->auto_match, array(1062, 1146));

    $query_item = $se->get_spec_query_item($structure_path, $cur_pos);
    $se->query($query_item);

    if (!empty($se->error))
    {
        $err->add($se->error);
        return false;
    }

    return true;
}

/**
 * 机械化地升级数据库数据。
 * @return  boolean
 */
function update_data_automatically($next_ver)
{
    global $db, $ecs, $prefix, $err;

    $ver_obj = create_ver_obj($next_ver);
    if (!is_object($ver_obj) || empty($ver_obj->sql_files['data']))
    {
        return true;
    }

    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix,
            ROOT_PATH . 'data/upgrade_'.$next_ver.'.log',
            $ver_obj->auto_match, array(1062, 1146));

    $data_path = '';
    $ver_root = ROOT_PATH . 'upgrade/packages/' . $next_ver . '/';
    if (is_array($ver_obj->sql_files['data']))
    {
        $lang = EC_LANGUAGE . '_' . EC_CHARSET;
        if (!isset($ver_obj->sql_files['data'][$lang]))
        {
           $lang = 'zh_cn_utf-8';
        }
        $data_path = $ver_root . $ver_obj->sql_files['data'][$lang];
    }
    else
    {
        $data_path =  $ver_root . $ver_obj->sql_files['data'];
    }
    $se->run_all(array($data_path));

    if (!empty($se->error))
    {
        $err->add($se->error);
        return false;
    }

    return true;
}

/**
 * 随心所欲地升级数据库。
 * @return  boolean
 */
function update_database_optionally($next_ver)
{
    $ver_obj = create_ver_obj($next_ver);
    if ($ver_obj === false)
    {
        return false;
    }

    $ver_obj->update_database_optionally();

    return true;
}

/**
 * 升级文件。
 * @return  array
 */
function update_files($next_ver)
{
    global $err;

    $ver_obj = create_ver_obj($next_ver);
    if ($ver_obj === false)
    {
        return array('msg'=>'OK');
    }

    $result = $ver_obj->update_files();
    if ($result === false)
    {
        $msg = $err->last_message();
        if (is_array($msg)
                && isset($msg['type'])
                && $msg['type'] === 'NOTICE')
        {
            return array('type'=>'NOTICE', 'msg'=>$msg);
        }
    }

    return array('msg'=>'OK');
}

/**
 * 升级版本。
 * @return  void
 */
function update_version($next_ver)
{
    global $db, $ecs;

    $db->query('UPDATE ' . $ecs->table('shop_config') . "  SET value='$next_ver' WHERE code='ecs_version'");
}

function dump_database($next_ver)
{
    global $db, $err, $prefix;

    include_once(ROOT_PATH . 'admin/includes/cls_sql_dump.php');
    require_once(ROOT_PATH . 'upgrade/packages/' . $next_ver . '/dump_table.php');

    /* 备份表为空时不作备份，返回真 */
    if (empty($temp))
    {
        return true;
    }
    @set_time_limit(300);

    $dump = new cls_sql_dump($db);
    $run_log = ROOT_PATH . 'data/sqldata/run.log';
    $sql_file_name = $next_ver;
    $max_size = '2048';
    $vol = 1;

    /* 变量验证 */
    $allow_max_size = intval(@ini_get('upload_max_filesize')); //单位M
    if ($allow_max_size > 0 && $max_size > ($allow_max_size * 1024))
    {
        $max_size = $allow_max_size * 1024; //单位K
    }

    if ($max_size > 0)
    {
        $dump->max_size = $max_size * 1024;
    }

    $tables = array();
    foreach ($temp AS $table)
    {
        $tables[$prefix . $table] = -1;
    }

    $dump->put_tables_list($run_log, $tables);

    /* 开始备份 */
    $tables = $dump->dump_table($run_log, $vol);

    if ($tables === false)
    {
        $err->add($dump->errorMsg());
        return false;
    }

    if(@file_put_contents(ROOT_PATH . 'data/sqldata/' . $sql_file_name . '.sql', $dump->dump_sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function rollback($next_ver)
{
    global $db, $prefix, $err;

    $structure_path[] = ROOT_PATH . 'data/sqldata/' . $next_ver . '.sql';

    if(!file_exists($structure_path[0]))
    {
        return false;
    }

    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix);
    $result = $se->run_all($structure_path);
    if ($result === false)
    {
        $err->add($se->error);
        return false;
    }

    return true;
}


/**
 * 获得 ECSHOP 当前环境的 HTTP 协议方式
 *
 * @access  public
 *
 * @return  void
 */
function http()
{
    return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}

/**
 * 取得当前的域名
 *
 * @access  public
 *
 * @return  string      当前的域名
 */
function get_domain()
{
    /* 协议 */
    $protocol = http();

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}

/**
 * 获得 ECSHOP 当前环境的 URL 地址
 *
 * @access  public
 *
 * @return  void
 */
function url()
{
    define(PHP_SELF, $_SERVER['PHP_SELF']);
    $curr = strpos(PHP_SELF, 'upgrade/') !== false ?
            preg_replace('/(.*)(upgrade)(\/?)(.)*/i', '\1', dirname(PHP_SELF)) :
            dirname(PHP_SELF);

    $root = str_replace('\\', '/', $curr);

    if (substr($root, -1) != '/')
    {
        $root .= '/';
    }

    return get_domain() . $root;
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
        $return = '';
        $matches = parse_url($url);
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'].'?'.$matches['query'].'#'.$matches['fragment'] : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;

        if($post) {
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: '.strlen($post)."\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if(!$fp) {
            return '';//note $errstr : $errno \r\n
        } else {
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            @fwrite($fp, $out);
            $status = stream_get_meta_data($fp);
            if(!$status['timed_out']) {
                while (!feof($fp)) {
                    if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
                        break;
                    }
                }

                $stop = false;
                while(!feof($fp) && !$stop) {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $return .= $data;
                    if($limit) {
                        $limit -= strlen($data);
                        $stop = $limit <= 0;
                    }
                }
            }
            @fclose($fp);
            return $return;
        }
    }

function write_charset_config($lang, $charset)
{
    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/","");
    $s = insertconfig($s, "/define\('EC_LANGUAGE',\s*'.*?'\);/i", "define('EC_LANGUAGE', '" . $lang . "');");
    $s = insertconfig($s, "/define\('EC_CHARSET',\s*'.*?'\);/i", "define('EC_CHARSET', '" . $charset . "');");
    $s = insertconfig($s, "/\?\>/","?>");
    return file_put_contents($config_file, $s);
}

function remove_lang_config()
{
    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/", "");
    $s = insertconfig($s, "/define\('EC_LANGUAGE',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/\?\>/", "?>");
    return file_put_contents($config_file, $s);
}

function change_ucenter_config()
{
    global $db, $ecs;
    $config_file = ROOT_PATH . 'data/config.php';
    @include ($config_file);
    if (defined('UC_CONNECT'))
    {
        $cfg = array(
            'uc_id' => UC_APPID,
            'uc_key' => UC_KEY,
            'uc_url' => UC_API,
            'uc_ip' => UC_IP,
            'uc_connect' => UC_CONNECT,
            'uc_charset' => UC_CHARSET,
            'db_host' => UC_DBHOST,
            'db_user' => UC_DBUSER,
            'db_name' => UC_DBNAME,
            'db_pass' => UC_DBPW,
            'db_pre' => UC_DBTABLEPRE,
            'db_charset' => UC_DBCHARSET,
        );
        $db->query('UPDATE ' . $ecs->table('shop_config') . "  SET value='ucenter' WHERE code='integrate_code'");
        $db->query('UPDATE ' . $ecs->table('shop_config') . "  SET value='". serialize($cfg) ."' WHERE code='integrate_config'");
    }
    return true;
}

function remove_ucenter_config()
{
    global $db, $ecs;
    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/", "");
    $s = insertconfig($s, "/\/\*\=*UCenter\=*\*\//i", "");
    $s = insertconfig($s, "/define\('UC_CONNECT',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBHOST',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBUSER',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBPW',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBNAME',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_KEY',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_API',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_CHARSET',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_IP',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_APPID',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_PPP',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/\?\>/", "?>");
    return file_put_contents($config_file, $s);
}

function save_uc_config($config)
{
    global $db, $ecs;
    $success = false;

    list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);

    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/","");

    $link = mysql_connect($ucdbhost, $ucdbuser, $ucdbpw, 1);
    $uc_connnect = $link && mysql_select_db($ucdbname, $link) ? 'mysql' : 'post';
    $s = insertconfig($s, "/define\('EC_CHARSET',\s*'.*?'\);/i", "define('EC_CHARSET', '" . EC_CHARSET . "');");

    $s = insertconfig($s, "/\/\*\=*UCenter\=*\*\//","/*=================UCenter=======================*/");
    $s = insertconfig($s, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$uc_connnect');");
    $s = insertconfig($s, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '$ucdbhost');");
    $s = insertconfig($s, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '$ucdbuser');");
    $s = insertconfig($s, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '$ucdbpw');");
    $s = insertconfig($s, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '$ucdbname');");
    $s = insertconfig($s, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '$ucdbcharset');");
    $s = insertconfig($s, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');");
    $s = insertconfig($s, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "define('UC_DBCONNECT', '0');");
    $s = insertconfig($s, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '$appauthkey');");
    $s = insertconfig($s, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$ucapi');");
    $s = insertconfig($s, "/define\('UC_CHARSET',\s*'.*?'\);/i", "define('UC_CHARSET', '$uccharset');");
    $s = insertconfig($s, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '$ucip');");
    $s = insertconfig($s, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '$appid');");
    $s = insertconfig($s, "/define\('UC_PPP',\s*'?.*?'?\);/i", "define('UC_PPP', '20');");
    $s = insertconfig($s, "/\?\>/","?>");

    return file_put_contents($config_file, $s);
}

function insertconfig($s, $find, $replace)
{
    if(preg_match($find, $s))
    {
        $s = preg_replace($find, $replace, $s);
    }
    else
    {
        // 插入到最后一行
        $s .= "\r\n".$replace;
    }
    return $s;
}

?>
