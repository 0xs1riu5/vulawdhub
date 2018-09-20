<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * Class SsoModel  单点登录模型
 * @package Admin\Model
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class SsoModel extends Model{
    /**
     * 自动完成
     * @var array
     */

    private $appModel =null;
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    protected function _initialize()
    {
        parent::_initialize();
        $this->appModel =  M('sso_app');
    }

    public function getApp($map){
        $app = $this->appModel->where($map)->find();
        return $app;
    }

    public function addApp($data){
        $res = $this->appModel->add($data);
        return $res;
    }

    public function delApp($ids){
        $res = $this->appModel->where(array('id'=>array(array('in',$ids))))->delete();
        return $res;
    }

    public function editApp($data){
        $res = $this->appModel->save($data);
        return $res;
    }


}
