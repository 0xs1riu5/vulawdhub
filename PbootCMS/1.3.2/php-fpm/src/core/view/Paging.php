<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月6日
 *  分页控制类
 */
namespace core\view;

use core\basic\Config;

class Paging
{

    // 每页数量
    public $pageSize;

    // 当前页码
    public $page;

    // 数字条数量
    public $num = 5;

    // 调整数量
    public $start = 1;

    // 总记录
    private $rowTotal = 0;

    // 页面数量
    private $pageCount;

    // 存储前置URL
    private $preUrl;

    // 存储地址后缀
    private $suffix;

    // 分页实例
    private static $paging;

    private function __construct()
    {
        // 禁用类new实例化
    }

    // 获取单一实例
    public static function getInstance()
    {
        if (! self::$paging) {
            self::$paging = new self();
        }
        return self::$paging;
    }

    // 限制语句
    public function limit($total = null, $morePageStr = false)
    {
        // 起始数据调整
        if (! is_numeric($this->start) || $this->start < 1) {
            $this->start = 1;
        }
        if ($this->start > $total) {
            $this->start = $total + 1;
        }
        
        // 设置总数
        if ($total) {
            $this->rowTotal = $total - ($this->start - 1);
        }
        
        // 设置分页大小
        if (! isset($this->pageSize)) {
            $this->pageSize = config::get('pagesize') ?: 15;
        }
        
        // 计算页数
        $this->pageCount = @ceil($this->rowTotal / $this->pageSize);
        
        // 地址后缀
        if (is_rewrite()) {
            $this->suffix = Config::get('url_suffix');
        } else {
            $this->suffix = '';
        }
        
        // 获取当前页面
        $this->page = $this->page();
        
        // 定义相关常量，用于方便模板引擎解析序号等计算和调用
        define('ROWTOTAL', $this->rowTotal);
        define('PAGECOUNT', $this->pageCount);
        define('PAGE', $this->page);
        define('PAGESIZE', $this->pageSize);
        
        // 注入分页模板变量
        $this->assign($morePageStr);
        
        // 返回限制语句
        return ($this->page - 1) * $this->pageSize + ($this->start - 1) . ",$this->pageSize";
    }

    // 注入页面相关信息,用于模板调用，如：{$pagebar}调用分页条
    private function assign($morePageStr = false)
    {
        assign('pagebar', $this->pageBar());
        if ($morePageStr) {
            assign('pagecurrent', $this->page()); // 注入当前页
            assign('pagecount', $this->pageCount); // 注入总页数
            assign('pagerows', $this->rowTotal); // 注入总数据
            assign('pageindex', $this->pageIndex()); // 注入首页链接
            assign('pagepre', $this->pagePre()); // 注入前一页链接
            assign('pagenext', $this->pageNext()); // 注入后一页链接
            assign('pagelast', $this->pageLast()); // 注入最后一页链接
            assign('pagestatus', $this->pageStatus()); // 注入分页状态
            assign('pagenumbar', $this->pageNumBar()); // 注入数字
            assign('pageselectbar', $this->pageSelectBar()); // 注入选择栏
        }
    }

    // 当前页码容错处理
    private function page()
    {
        $page = get('page', 'int') ?: $this->page;
        if (is_numeric($page) && $page > 1) {
            if ($page > $this->pageCount && $this->pageCount) {
                return $this->pageCount;
            } else {
                return $page;
            }
        } else {
            return 1;
        }
    }

    // 过滤分页，避免异常翻页URL
    private function getPreUrl()
    {
        if (! isset($this->preUrl) && URL) {
            $url = parse_url(URL);
            $path = preg_replace('/\/page\/[0-9]+/i', '', $url['path']);
            $url_html_suffix = Config::get('url_suffix');
            if (substr($path, - strlen($url_html_suffix)) == $url_html_suffix) {
                $path = substr($path, 0, - strlen($url_html_suffix));
            }
            if ($path == '/') {
                $this->preUrl = url('home/index/index', false);
            } else {
                $this->preUrl = $path;
            }
        }
        return $this->preUrl;
    }

    // 分页条
    private function pageBar()
    {
        if (! $this->pageCount)
            return "<span class='page-none' style='color:#999'>未查询到任何数据!</span>";
        $string = "<span class='page-status'>{$this->pageStatus()}</span>";
        $string .= "<span class='page-index'><a href='" . $this->pageIndex() . "'>首页</a></span>";
        $string .= "<span class='page-pre'><a href='" . $this->pagePre() . "'>前一页</a></span>";
        $string .= "<span class='page-numbar'>{$this->pageNumBar()}</span>";
        $string .= "<span class='page-next'><a href='" . $this->pageNext() . "'>后一页</a></span>";
        $string .= "<span class='page-last'><a href='" . $this->pageLast() . "'>尾页</a></span>";
        // $string .= "<span class='page-select'>{$this->pageSelectBar()}</span>";
        return $string;
    }

    // 当前页面情况
    private function pageStatus()
    {
        if (! $this->pageCount)
            return;
        return "共" . $this->rowTotal . "条 当前" . $this->page . "/" . $this->pageCount . "页";
    }

    // 首页链接
    private function pageIndex()
    {
        if (! $this->pageCount)
            return;
        return $this->getPreUrl() . '/page/1' . $this->suffix . $this->queryString();
    }

    // 上一页链接
    private function pagePre()
    {
        if (! $this->pageCount)
            return;
        if ($this->page > 1) {
            $pre_page = $this->getPreUrl() . '/page/' . ($this->page - 1) . $this->suffix . $this->queryString();
        } else {
            $pre_page = '';
        }
        return $pre_page;
    }

    // 下一页链接
    private function pageNext()
    {
        if (! $this->pageCount)
            return;
        if ($this->page < $this->pageCount) {
            $next_page = $this->getPreUrl() . '/page/' . ($this->page + 1) . $this->suffix . $this->queryString();
        } else {
            $next_page = '';
        }
        return $next_page;
    }

    // 尾页
    private function pageLast()
    {
        if (! $this->pageCount)
            return;
        return $this->getPreUrl() . '/page/' . $this->pageCount . $this->suffix . $this->queryString();
    }

    // 数字分页,要修改数字显示的条数，请修改类头部num属性值
    private function pageNumBar()
    {
        if (! $this->pageCount)
            return;
        $num_html = '';
        $total = $this->num;
        $halfl = intval($total / 2);
        $halfu = ceil($total / 2);
        
        if ($this->page > $halfu) {
            $num_html .= '<span class="page-num">···</span>';
        }
        
        if ($this->page <= $halfl || $this->pageCount < $total) { // 当前页小于一半或页数小于总数
            for ($i = 1; $i <= $total; $i ++) {
                if ($i > $this->pageCount)
                    break;
                if ($this->page == $i) {
                    $num_html .= '<a href="' . $this->getPreUrl() . '/page/' . $i . $this->suffix . $this->queryString() . '" class="page-num page-num-current">' . $i . '</a>';
                } else {
                    $num_html .= '<a href="' . $this->getPreUrl() . '/page/' . $i . $this->suffix . $this->queryString() . '" class="page-num">' . $i . '</a>';
                }
            }
        } elseif ($this->page + $halfl >= $this->pageCount) { // 当前页为倒数页以内
            for ($i = $this->pageCount - $total + 1; $i <= $this->pageCount; $i ++) {
                if ($this->page == $i) {
                    $num_html .= '<a href="' . $this->getPreUrl() . '/page/' . $i . $this->suffix . $this->queryString() . '" class="page-num page-num-current">' . $i . '</a>';
                } else {
                    $num_html .= '<a href="' . $this->getPreUrl() . '/page/' . $i . $this->suffix . $this->queryString() . '" class="page-num">' . $i . '</a>';
                }
            }
        } else { // 正常的前后各5页
            for ($i = $this->page - $halfl; $i <= $this->page + $halfl; $i ++) {
                if ($this->page == $i) {
                    $num_html .= '<a href="' . $this->getPreUrl() . '/page/' . $i . $this->suffix . $this->queryString() . '" class="page-num page-num-current">' . $i . '</a>';
                } else {
                    $num_html .= '<a href="' . $this->getPreUrl() . '/page/' . $i . $this->suffix . $this->queryString() . '" class="page-num">' . $i . '</a>';
                }
            }
        }
        
        if ($this->pageCount > $total && $this->page < $this->pageCount - $halfl) {
            $num_html .= '<span class="page-num">···</span>';
        }
        
        return $num_html;
    }

    // 跳转分页
    private function pageSelectBar()
    {
        if (! $this->pageCount)
            return;
        $select_html = '<select onchange="changepage(this)" lay-ignore>';
        for ($i = 1; $i <= $this->pageCount; $i ++) {
            if ($i == $this->page) {
                $select_html .= '<option value="' . $i . '" selected="selected">跳到' . $i . '页</option>';
            } else {
                $select_html .= '<option value="' . $i . '">跳到' . $i . '页</option>';
            }
        }
        $select_html .= '</select><script>function changepage(tag){window.location.href="' . $this->getPreUrl() . '/page/"+tag.value+"' . $this->suffix . $this->queryString() . '";}</script>';
        return $select_html;
    }

    // URL查询字符
    private function queryString()
    {
        if (isset($_SERVER["QUERY_STRING"]) && ! ! $qs = $_SERVER["QUERY_STRING"]) {
            parse_str($qs, $output);
            if (isset($output['page'])) {
                unset($output['page']);
                $qs = http_build_query($output);
            }
            if ($qs)
                return '?' . $qs;
        }
    }
}