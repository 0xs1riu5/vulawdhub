<?php

/**
 * ECSHOP 云服务接口
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cloud.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
session_start();
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_transport.php');
$t = new transport('-1',5);

$data['api_ver'] = '1.0';
$data['version'] = VERSION;
$data['charset'] = strtoupper(EC_CHARSET);
$ecs_charset = $data['charset'];
$data['patch'] = file_get_contents(ROOT_PATH."admin/patch_num");
$data['ecs_lang'] = !empty($_SESSION['ecs_lang']) ? $_SESSION['ecs_lang'] : 'zh_cn';
$data['release'] = RELEASE;
$step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : '';
$step_arr = array('welcome','check','setting_ui','done','active');

if (!in_array($step,$step_arr))
{
    @header('Location: index.php');
}

$apiget = "step= $step &ecs_lang= $data[ecs_lang] &release= $data[release] &version= $data[version] &patch= $data[patch] &charset= $data[charset] &api_ver= $data[api_ver]";
foreach ($_SESSION[$step] as $k => $v)
{
    $smarty->assign($k, $v);
    $GLOBALS[$k] = $v;
}

$installer_lang_package_path = ROOT_PATH . 'install/languages/' . $data['ecs_lang'] . '.php';
if (file_exists($installer_lang_package_path))
{
    include_once($installer_lang_package_path);
    $lang = $_LANG;
    $smarty->assign('lang', $_LANG);
}

if ($step == 'welcome')
{
    $apiget = str_replace('welcome', 'welcome_yunqi', $apiget);
    $content = api_request($apiget);
    if ($content)
    {
        //$content=str_replace('<'.'?php','<'.'?',$content);
        $content='?'.'>'.trim($content);
        eval($content);
    }
    else
    {
        $smarty->display('welcome_content.php');
    }
}
elseif ($step == 'check')
{
    $content = api_request($apiget);
    if ($content)
    {
        //$content=str_replace('<'.'?php','<'.'?',$content);
        $content='?'.'>'.trim($content);
        eval($content);
    }
    else
    {
        $smarty->display('checking_content.php');
    }
}
elseif ($step == 'setting_ui')
{
    $content = api_request($apiget);
    if ($content)
    {
        //$content=str_replace('<'.'?php','<'.'?',$content);
        $content='?'.'>'.trim($content);
        eval($content);
    }
    else
    {
        $smarty->display('setting_content.php');
    }
}
elseif ($step == 'done')
{
    $content = api_request($apiget);
    if ($content)
    {
        //$content=str_replace('<'.'?php','<'.'?',$content);
        $content='?'.'>'.trim($content);
        eval($content);
    }
    else
    {
        $smarty->display('done_content.php');
    }
}
elseif ($step == 'active')
{
    $content = api_request($apiget);
    if ($content)
    {
        //$content=str_replace('<'.'?php','<'.'?',$content);
        $content='?'.'>'.trim($content);
        eval($content);
    }
    else
    {
        $smarty->display('active_content.php');
    }
}

function api_request($apiget)
{
    global $t,$ecs_charset;
    $api_comment = $t->request('http://cloud.ecshop.com/install_api.php', $apiget);
    $api_str = $api_comment["body"];
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;
    $api_arr = array();
    $api_arr = @$json->decode($api_str,1);
    if (!empty($api_arr) && $api_arr['error'] == 0 && md5($api_arr['content']) == $api_arr['hash'])
    {
        $api_arr['content'] = urldecode($api_arr['content']);
        if ($ecs_charset != 'UTF-8')
        {
            $api_arr['content'] = ecs_iconv('UTF-8',$ecs_charset,$api_arr['content']);
        }
        return $api_arr['content'];
    }
    else
    {
        return false;
    }
}

?>