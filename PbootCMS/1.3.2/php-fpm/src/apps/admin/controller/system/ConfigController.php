<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年01月03日
 *  应用配置控制器
 */
namespace app\admin\controller\system;

use core\basic\Controller;
use app\admin\model\system\ConfigModel;
use core\basic\Config;

class ConfigController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new ConfigModel();
    }

    // 应用配置列表
    public function index()
    {
        // 修改参数配置
        if ($_POST) {
            foreach ($_POST as $key => $value) {
                if (! preg_match('/^[\w-]+$/', $key)) {
                    continue;
                }
                $config = array(
                    'debug',
                    'sn',
                    'url_type',
                    'tpl_html_cache',
                    'tpl_html_cache_time'
                );
                if (in_array($key, $config)) {
                    if ($key == 'tpl_html_cache_time' && ! $value) {
                        $value = 900;
                    } else {
                        $value = post($key);
                    }
                    $this->modConfig($key, $value);
                } else {
                    $this->modDbConfig($key);
                }
            }
            
            $this->log('修改参数配置成功！');
            path_delete(RUN_PATH . '/config'); // 清理缓存的配置文件
            switch (post('submit')) {
                case 'msg':
                    success('修改成功！', url('/admin/Config/index?#tab=t2', false));
                    break;
                case 'baidu':
                    success('修改成功！', url('/admin/Config/index?#tab=t3', false));
                    break;
                case 'api':
                    success('修改成功！', url('/admin/Config/index?#tab=t4', false));
                    break;
                case 'upgrade':
                    success('修改成功！', url('/admin/Upgrade/index?#tab=t2', false));
                    break;
                default:
                    success('修改成功！', url('/admin/Config/index', false));
            }
        }
        $this->assign('basic', true);
        $configs = $this->model->getList();
        $configs['debug']['value'] = $this->config('debug');
        $configs['sn']['value'] = $this->config('sn');
        $configs['url_type']['value'] = $this->config('url_type');
        $configs['tpl_html_cache']['value'] = $this->config('tpl_html_cache');
        $configs['tpl_html_cache_time']['value'] = $this->config('tpl_html_cache_time');
        $this->assign('configs', $configs);
        $this->display('system/config.html');
    }

    // 邮件发送配置
    public function email()
    {
        if (! ! $action = get('action')) {
            switch ($action) {
                case 'sendemail':
                    $rs = sendmail($this->config(), get('to'), '【PbootCMS】测试邮件', '欢迎您使用PbootCMS网站开发管理系统！');
                    if ($rs === true) {
                        alert_back('测试邮件发送成功！');
                    } else {
                        alert('发送失败：' . $rs);
                    }
                    break;
            }
        }
        
        // 修改参数配置
        if ($_POST) {
            foreach ($_POST as $key => $value) {
                if (! preg_match('/^[\w-]+$/', $key)) {
                    continue;
                }
                $this->modDbConfig($key);
            }
            $this->log('修改邮件发送配置成功！');
            path_delete(RUN_PATH . '/config'); // 清理缓存的配置文件
            success('修改成功！', url('/admin/Config/email'));
        }
        $this->assign('email', true);
        $this->assign('configs', $this->model->getList());
        $this->display('system/config.html');
    }

    // 修改配置文件
    private function modConfig($key, $value)
    {
        // 如果开启伪静态时自动拷贝文件
        if ($key == 'url_type' && $value == 2) {
            $soft = get_server_soft();
            if ($soft == 'iis') {
                if (! file_exists(ROOT_PATH . '/web.config')) {
                    copy(ROOT_PATH . '/rewrite/web.config', ROOT_PATH . '/web.config');
                }
            } elseif ($soft == 'apache') {
                if (! file_exists(ROOT_PATH . '/web.config')) {
                    copy(ROOT_PATH . '/rewrite/.htaccess', ROOT_PATH . '/.htaccess');
                }
            }
        }
        $config = file_get_contents(CONF_PATH . '/config.php');
        $value = str_replace(' ', '', $value); // 去除空格
        $value = str_replace('，', ',', $value); // 转换可能输入的中文逗号
        if (is_numeric($value)) {
            $config = preg_replace('/(\'' . $key . '\'([\s]+)?=>([\s]+)?)[\w\'\"\s,]+,/', '${1}' . $value . ',', $config);
        } else {
            $config = preg_replace('/(\'' . $key . '\'([\s]+)?=>([\s]+)?)[\w\'\"\s,]+,/', '${1}\'' . $value . '\',', $config);
        }
        return file_put_contents(CONF_PATH . '/config.php', $config);
    }

    // 修改数据库配置
    private function modDbConfig($key)
    {
        if ($this->model->checkConfig("name='$key'")) {
            $this->model->modValue($key, post($key));
        } elseif ($key != 'submit' && $key != 'formcheck') {
            // 自动新增配置项
            $data = array(
                'name' => $key,
                'value' => post($key),
                'type' => 2,
                'sorting' => 255,
                'description' => ''
            );
            return $this->model->addConfig($data);
        }
    }
}