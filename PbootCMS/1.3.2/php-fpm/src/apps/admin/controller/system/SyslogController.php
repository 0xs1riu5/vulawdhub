<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年3月29日
 *  系统日志控制器
 */
namespace app\admin\controller\system;

use core\basic\Controller;
use app\admin\model\system\SyslogModel;

class SyslogController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new SyslogModel();
    }

    // 日志列表
    public function index()
    {
        $this->assign('syslogs', $this->model->getList());
        $this->display('system/syslog.html');
    }
}