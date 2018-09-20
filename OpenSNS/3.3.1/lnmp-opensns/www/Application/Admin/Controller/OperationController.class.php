<?php
namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;


/**
 * Class OperationController  运维控制器
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class OperationController extends AdminController
{

    public function index()
    {
        $this->redirect('Message/userList');
    }
}
