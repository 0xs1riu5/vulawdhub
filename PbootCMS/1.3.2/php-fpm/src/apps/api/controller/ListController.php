<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年4月20日
 *  内容列表接口控制器
 */
namespace app\api\controller;

use core\basic\Controller;
use app\api\model\CmsModel;

class ListController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = new CmsModel();
    }

    public function index()
    {
        // 获取参数
        $acode = request('acode', 'var') ?: $this->config('lgs.0.acode');
        $scode = request('scode', 'var') ?: '';
        $num = request('num', 'int') ?: $this->config('pagesize');
        $order = get('order');
        if (! preg_match('/^[\w-,\s]+$/', $order)) {
            $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
        } else {
            switch ($order) {
                case 'id':
                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.id DESC,a.date DESC,a.sorting ASC';
                    break;
                case 'date':
                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.date DESC,a.sorting ASC,a.id DESC';
                    break;
                case 'sorting':
                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'istop':
                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'isrecommend':
                    $order = 'a.isrecommend DESC,a.istop DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'isheadline':
                    $order = 'a.isheadline DESC,a.istop DESC,a.isrecommend DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                case 'visits':
                case 'likes':
                case 'oppose':
                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,' . $order . ' DESC,a.sorting ASC,a.date DESC,a.id DESC';
                    break;
                default:
                    $order = $order . ',a.sorting ASC,a.date DESC,a.id DESC';
            }
        }
        
        // 读取数据
        $data = $this->model->getLists($acode, $scode, $num, $order);
        
        foreach ($data as $key => $value) {
            if ($value->outlink) {
                $data[$key]->link = $data->outlink;
            } else {
                $data[$key]->link = url('/api/list/index/scode/' . $data[$key]->id, false);
            }
            $data[$key]->likeslink = url('/home/Do/likes/id/' . $data[$key]->id, false);
            $data[$key]->opposelink = url('/home/Do/oppose/id/' . $data[$key]->id, false);
            $data[$key]->content = str_replace(STATIC_DIR . '/upload/', get_http_url() . STATIC_DIR . '/upload/', $data[$key]->content);
        }
        
        // 输出数据
        if (get('page') <= PAGECOUNT) {
            json(1, $data);
        } else {
            return json(0, '已经到底了！');
        }
    }
}