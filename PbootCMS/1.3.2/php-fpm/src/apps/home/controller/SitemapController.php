<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年7月15日
 *  生成sitemap文件
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\SitemapModel;

class SitemapController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = new SitemapModel();
    }

    public function index()
    {
        header("Content-type:text/xml;charset=utf-8");
        $str = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<urlset>';
        $str .= $this->makeNode('', date('Y-m-d'), 1); // 根目录
        $sorts = $this->model->getSorts();
        foreach ($sorts as $value) {
            if ($value->outlink) {
                $link = $value->outlink;
            } elseif ($value->type == 1) {
                if ($value->filename) {
                    $link = url('/home/about/index/scode/' . $value->filename);
                } else {
                    $link = url('/home/about/index/scode/' . $value->scode);
                }
                $str .= $this->makeNode($link, date('Y-m-d'), 0.8);
            } else {
                if ($value->filename) {
                    $link = url('/home/list/index/scode/' . $value->filename);
                } else {
                    $link = url('/home/list/index/scode/' . $value->scode);
                }
                $str .= $this->makeNode($link, date('Y-m-d'), 0.8);
                $contents = $this->model->getList($value->scode);
                foreach ($contents as $value2) {
                    if ($value2->outlink) { // 外链
                        $link = $value2->outlink;
                    } elseif ($value2->filename) { // 自定义名称
                        $link = url('/home/content/index/id/' . $value2->filename);
                    } else {
                        $link = url('/home/content/index/id/' . $value2->id);
                    }
                    $str .= $this->makeNode($link, date('Y-m-d'), 0.6);
                }
            }
        }
        echo $str . "\n</urlset>";
    }

    // 生成结点信息
    private function makeNode($link, $date, $priority = 0.6)
    {
        $node = '
<url>
    <loc>' . get_http_url() . $link . '</loc>
    <lastmod>' . $date . '</lastmod>
    <changefreq>daily</changefreq>
    <priority>' . $priority . '</priority>
</url>';
        return $node;
    }
}