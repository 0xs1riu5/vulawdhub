<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年3月8日
 *  
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\DoModel;

class DoController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new DoModel();
    }

    // 多语言切换
    public function area()
    {
        $lg = request('lg', 'var');
        if ($lg) {
            $lgs = $this->config('lgs');
            foreach ($lgs as $value) {
                if ($value['acode'] == $lg) {
                    cookie('lg', $lg);
                }
            }
            location(SITE_DIR . '/');
        }
    }

    // 文章访问量累计
    public function visits()
    {
        if (! ! $id = get('id', 'int')) {
            $this->model->addVisits($id);
            json(1, 'ok');
        } else {
            json(0, 'error');
        }
    }

    // 点赞
    public function likes()
    {
        if (($id = get('id', 'int')) && ! cookie('likes_' . $id)) {
            $this->model->addLikes($id);
            cookie('likes_' . $id, true, 31536000);
        }
        location('-1');
    }

    // 反对
    public function oppose()
    {
        if (($id = get('id', 'int')) && ! cookie('oppose_' . $id)) {
            $this->model->addOppose($id);
            cookie('oppose_' . $id, true, 31536000);
        }
        location('-1');
    }
}



