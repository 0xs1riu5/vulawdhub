<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-2
 * Time: 上午10:54
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */


error_reporting(0);
set_magic_quotes_runtime(0);
$db_config = require('./oc_config.php');

require_once('./ocenter/Lib/Think.php');

if ($db_config['SSO_SWITCH'] == 0) {
    exit('该应用未开启单点登录');
}
$think = new think($db_config['SSO_DATA_AUTH_KEY']);
$code = @$_GET['code'];
parse_str($think->thinkDecrypt($code), $get);

$timestamp = time();
if($timestamp - $get['time'] > 3600) {
    exit('timeout');
}
if(empty($get)) {
    exit('parameter error');
}

if (in_array($get['action'], array('test', 'synLogin', 'synLogout'))) {
    $node = new ocNode();
    exit($node->$get['action']($get));
} else {
    exit('error');
}

class ocNode
{
    var $db;
    var $tablePre;
    var $dirpath;
    var $thisConfig;

    function ocNode()
    {
        $this->dirpath = substr(dirname(__FILE__), 0, -5);
        require_once($this->dirpath . 'OcApi/OCenter/Lib/Mysql.php');

        $this->thisConfig = require_once $this->dirpath . '/Conf/common.php';
        $this->db = new Mysql($this->thisConfig ['DB_NAME'], $this->thisConfig ['DB_HOST'], $this->thisConfig ['DB_USER'], $this->thisConfig ['DB_PWD']);
        $this->tablePre = $this->thisConfig ['DB_PREFIX'];
    }

    function test()
    {
        return 'success';
    }

    /**
     * synLogin  同步登陆
     * @param $get
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    function synLogin($get)
    {
        $uid = $get['uid'];
        $username = $get['username'];
        $password = $get['password'];
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        session_start();
        $check_user = $this->db->getOne("SELECT * FROM `" . $this->tablePre . "member` WHERE uid=" . $uid);
        if ($check_user) {
            require_once($this->dirpath . 'OcApi/OCenter/OCenter.php');
            $OCApi = new OCApi();
            $user = $OCApi->ocGetUserInfo("id=" . $uid . " AND password='" . $password . "'");
            //验证用户
            if ($user) {

                $audit = $this->db->getOne("SELECT * FROM `" . $this->tablePre . "user_role` WHERE uid=" . $uid.' and role_id='.$user['last_login_role']);

                $auth = array(
                    'uid' => $user['uid'],
                    'username' => $user['username'],
                    'last_login_time' => $user['last_login_time'],
                    'role_id' => $user['last_login_role'],
                    'audit' => $audit,
                );
/*                $auth = array(
                    'uid' => $user['uid'],
                    'username' => $user['username'],
                    'last_login_time' => $user['last_login_time'],
                );*/
                if($this->thisConfig['SESSION_PREFIX']){
                    $_SESSION[$this->thisConfig['SESSION_PREFIX']]['user_auth'] = $auth;
                    $_SESSION[$this->thisConfig['SESSION_PREFIX']]['user_auth_sign'] = data_auth_sign($auth);
                }else{
                    $_SESSION['user_auth'] = $auth;
                    $_SESSION['user_auth_sign'] = data_auth_sign($auth);
                }

            }
        }
    }

    /**
     * synLogout  同步登出
     * @param $get
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    function synLogout($get)
    {
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        session_start();

        if($this->thisConfig['SESSION_PREFIX']){
            $_SESSION[$this->thisConfig['SESSION_PREFIX']]['user_auth'] = null;
            $_SESSION[$this->thisConfig['SESSION_PREFIX']]['user_auth_sign'] = null;
        }else{
            $_SESSION['user_auth'] = null;
            $_SESSION['user_auth_sign'] = null;
        }

    }
}
/**
 * 数据签名认证
 * @param  array $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data)
{
    //数据类型检测
    if (!is_array($data)) {
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}