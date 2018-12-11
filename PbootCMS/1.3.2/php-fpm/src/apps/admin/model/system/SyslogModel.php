<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年3月29日
 *  日志模型类
 */
namespace app\admin\model\system;

use core\basic\Model;

class SyslogModel extends Model
{

    // 获取日志列表
    public function getList()
    {
        return parent::table('ay_syslog')->order('id DESC')
            ->page()
            ->select();
    }
}