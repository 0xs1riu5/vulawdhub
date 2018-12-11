<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年2月14日
 *  标签解析引擎控制器
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\ParserModel;

class ParserController extends Controller
{

    protected $model;

    protected $pre = array();

    public function __construct()
    {
        $this->model = new ParserModel();
    }

    public function _empty()
    {
        error('您访问的地址有误，请核对后重试！');
    }

    // 解析全局前置公共标签
    public function parserBefore($content)
    {
        // 处理模板中不需要解析的标签
        $content = $this->savePreLabel($content);
        
        // 实现自动页面关键字及描述
        if (C == 'List') {
            $content = str_replace('{pboot:pagetitle}', '{sort:name}-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = str_replace('{pboot:pagekeywords}', '{sort:keywords}', $content);
            $content = str_replace('{pboot:pagedescription}', '{sort:description}', $content);
        } elseif (C == 'Content') {
            $content = str_replace('{pboot:pagetitle}', '{content:title}-{sort:name}-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = str_replace('{pboot:pagekeywords}', '{content:keywords}', $content);
            $content = str_replace('{pboot:pagedescription}', '{content:description}', $content);
        } elseif (C == 'About') {
            $content = str_replace('{pboot:pagetitle}', '{content:title}-{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = str_replace('{pboot:pagekeywords}', '{content:keywords}', $content);
            $content = str_replace('{pboot:pagedescription}', '{content:description}', $content);
        } else {
            $content = str_replace('{pboot:pagetitle}', '{pboot:sitetitle}-{pboot:sitesubtitle}', $content);
            $content = str_replace('{pboot:pagekeywords}', '{pboot:sitekeywords}', $content);
            $content = str_replace('{pboot:pagedescription}', '{pboot:sitedescription}', $content);
        }
        return $content;
    }

    // 解析全局后置公共标签
    public function parserAfter($content)
    {
        $content = $this->parserSingleLabel($content); // 单标签解析
        $content = $this->parserSiteLabel($content); // 站点标签
        $content = $this->parserCompanyLabel($content); // 公司标签
        $content = $this->parserUserLabel($content); // 自定义标签
        $content = $this->parserNavLabel($content); // 分类列表
        $content = $this->parserSelectAllLabel($content); // CMS筛选全部标签解析
        $content = $this->parserSelectLabel($content); // CMS筛选标签解析
        $content = $this->parserSpecifySortLabel($content); // 指定分类
        $content = $this->parserListLabel($content); // 指定列表
        $content = $this->parserSpecifyContentLabel($content); // 指定内容
        $content = $this->parserContentPicsLabel($content); // 内容多图
        $content = $this->parserContentCheckboxLabel($content); // 内容多选调取
        $content = $this->parserContentTagsLabel($content); // 内容tags调取
        $content = $this->parserSlideLabel($content); // 幻灯片
        $content = $this->parserLinkLabel($content); // 友情链接
        $content = $this->parserMessageLabel($content); // 留言板
        $content = $this->parserFormLabel($content); // 自定义表单
        $content = $this->parserSubmitFormLabel($content); // 自定义表单提交
        $content = $this->parserQrcodeLabel($content); // 二维码生成
        $content = $this->parserPageLabel($content); // CMS分页标签解析(需置后)
        $content = $this->parserLoopLabel($content); // LOOP语句(需置后)
        $content = $this->parserIfLabel($content); // IF语句(需置最后)
        $content = $this->restorePreLabel($content); // 还原不需要解析的内容
        return $content;
    }

    // 保存保留内容
    public function savePreLabel($content)
    {
        $pattern = '/\{pboot:pre}([\s\S]*?)\{\/pboot:pre\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $this->pre[] = $matches[1][$i];
                end($this->pre);
                $content = str_replace($matches[0][$i], '{pre:' . key($this->pre) . '}', $content);
            }
        }
        return $content;
    }

    // 还原保留内容
    public function restorePreLabel($content)
    {
        $pattern = '/\{pre:([0-9]+)\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $content = str_replace($matches[0][$i], $this->pre[$matches[1][$i]], $content);
            }
        }
        return $content;
    }

    // 解析单标签
    public function parserSingleLabel($content)
    {
        $content = str_replace('{pboot:msgaction}', url('/home/Message/add'), $content); // 留言提交路径
        $content = str_replace('{pboot:checkcode}', CORE_DIR . '/code.php', $content); // 验证码路径
        $content = str_replace('{pboot:lgpath}', url('/home/Do/area'), $content); // 多语言切换前置路径,如{pboot:lgpath}?lg=cn
        $content = str_replace('{pboot:scaction}', url('/home/Search/index'), $content); // 搜索提交路径
        $content = str_replace('{pboot:appid}', $this->config('api_appid'), $content); // API认证用户
        $content = str_replace('{pboot:timestamp}', time(), $content); // 认证时间戳
        $content = str_replace('{pboot:signature}', md5(md5($this->config('api_appid') . $this->config('api_secret') . time())), $content); // API认证密钥
        $content = str_replace('{pboot:httpurl}', get_http_url(), $content); // 当前访问的域名地址
        $content = str_replace('{pboot:pageurl}', get_current_url(), $content); // 当前页面的地址
        $content = str_replace('{pboot:keyword}', get('keyword'), $content); // 当前搜索的关键字
        $content = str_replace('{pboot:checkcodestatus}', $this->config('message_check_code'), $content); // 是否开启验证码
        return $content;
    }

    // 解析站点标签
    public function parserSiteLabel($content)
    {
        $pattern = '/\{pboot:site([\w]+)(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $data = $this->model->getSite();
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[2][$i]);
                switch ($matches[1][$i]) {
                    case 'index':
                        $content = str_replace($matches[0][$i], url('/', false), $content);
                        break;
                    case 'path':
                        $content = str_replace($matches[0][$i], SITE_DIR, $content);
                        break;
                    case 'logo':
                        if (isset($data->logo) && $data->logo) {
                            $content = str_replace($matches[0][$i], SITE_DIR . $data->logo, $content);
                        } else {
                            $content = str_replace($matches[0][$i], STATIC_DIR . '/images/logo.png', $content);
                        }
                        break;
                    case 'tplpath':
                        $content = str_replace($matches[0][$i], APP_THEME_DIR, $content);
                        break;
                    case 'language':
                        $content = str_replace($matches[0][$i], get_lg(), $content);
                        break;
                    case 'statistical':
                        if (isset($data->statistical)) {
                            $content = str_replace($matches[0][$i], decode_string($data->statistical), $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                    case 'copyright':
                        if (isset($data->copyright)) {
                            $content = str_replace($matches[0][$i], decode_string($data->copyright), $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                    default:
                        if (isset($data->{$matches[1][$i]})) {
                            $content = str_replace($matches[0][$i], $this->adjustLabelData($params, $data->{$matches[1][$i]}), $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                }
            }
        }
        return $content;
    }

    // 解析公司标签
    public function parserCompanyLabel($content)
    {
        $pattern = '/\{pboot:company([\w]+)(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $data = $this->model->getCompany();
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                if (! $data) { // 无数据时直接替换为空
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                $params = $this->parserParam($matches[2][$i]);
                switch ($matches[1][$i]) {
                    case 'weixin':
                        if (isset($data->weixin) && $data->weixin) {
                            $content = str_replace($matches[0][$i], SITE_DIR . $data->weixin, $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                        break;
                    default:
                        if (isset($data->{$matches[1][$i]})) {
                            $content = str_replace($matches[0][$i], $this->adjustLabelData($params, $data->{$matches[1][$i]}), $content);
                        }
                }
            }
        }
        return $content;
    }

    // 解析自定义标签
    public function parserUserLabel($content)
    {
        $pattern = '/\{label:([\w]+)(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $data = $this->model->getLabel();
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                if (! $data) { // 无数据时直接替换为空
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                $params = $this->parserParam($matches[2][$i]);
                switch ($matches[1][$i]) {
                    default:
                        if (isset($data[$matches[1][$i]])) {
                            if ($data[$matches[1][$i]]['type'] == 3 && $data[$matches[1][$i]]['value']) {
                                $data[$matches[1][$i]]['value'] = SITE_DIR . $data[$matches[1][$i]]['value'];
                            }
                            $content = str_replace($matches[0][$i], $this->adjustLabelData($params, $data[$matches[1][$i]]['value']), $content);
                        }
                }
            }
        }
        return $content;
    }

    // 解析栏目列表标签
    public function parserNavLabel($content)
    {
        $pattern = '/\{pboot:nav(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:nav\}/';
        $pattern2 = '/\[nav:([\w]+)(\s+[^]]+)?\]/';
        $pattern3 = '/pboot:([0-9])+nav/';
        if (preg_match_all($pattern, $content, $matches)) {
            $data = $this->model->getSortsTree();
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                
                // 无数据时直接替换整体标签为空
                if (! $data['tree']) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $parent = 0;
                $num = 0;
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'parent':
                            $parent = $value;
                            break;
                        case 'num':
                            $num = $value;
                            break;
                    }
                }
                
                if ($parent) { // 非顶级栏目起始
                    if (isset($data['tree'][$parent]['son'])) {
                        $out_data = $data['tree'][$parent]['son'];
                    } else {
                        $out_data = array();
                    }
                } else { // 顶级栏目起始
                    $out_data = $data['top'];
                }
                
                // 读取指定数量
                if ($num) {
                    $out_data = array_slice($out_data, 0, $num);
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($out_data as $value) { // 按查询的数据条数循环
                    $one_html = $matches[2][$i];
                    if ($count2) {
                        for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                            $params = $this->parserParam($matches2[2][$j]);
                            switch ($matches2[1][$j]) {
                                case 'n':
                                    $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                    break;
                                case 'i':
                                    $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                    break;
                                case 'link':
                                    if ($value['outlink']) {
                                        $one_html = str_replace($matches2[0][$j], $value['outlink'], $one_html);
                                    } elseif ($value['type'] == 1) {
                                        if ($value['filename']) {
                                            $one_html = str_replace($matches2[0][$j], url('/home/about/index/scode/' . $value['filename']), $one_html);
                                        } else {
                                            $one_html = str_replace($matches2[0][$j], url('/home/about/index/scode/' . $value['scode']), $one_html);
                                        }
                                    } else {
                                        if ($value['filename']) {
                                            $one_html = str_replace($matches2[0][$j], url('/home/list/index/scode/' . $value['filename']), $one_html);
                                        } else {
                                            $one_html = str_replace($matches2[0][$j], url('/home/list/index/scode/' . $value['scode']), $one_html);
                                        }
                                    }
                                    break;
                                case 'soncount':
                                    if (isset($data['tree'][$value['scode']]['son'])) {
                                        $one_html = str_replace($matches2[0][$j], count($data['tree'][$value['scode']]['son']), $one_html);
                                    } else {
                                        $one_html = str_replace($matches2[0][$j], 0, $one_html);
                                    }
                                    break;
                                case 'rows':
                                    $one_html = str_replace($matches2[0][$j], $this->model->getSortRows($value['scode']), $one_html);
                                    break;
                                case 'ico':
                                    if ($value['ico']) {
                                        $one_html = str_replace($matches2[0][$j], SITE_DIR . $value['ico'], $one_html);
                                    } else {
                                        $one_html = str_replace($matches2[0][$j], '', $one_html);
                                    }
                                    break;
                                case 'pic':
                                    if ($value['pic']) {
                                        $one_html = str_replace($matches2[0][$j], SITE_DIR . $value['pic'], $one_html);
                                    } else {
                                        $one_html = str_replace($matches2[0][$j], '', $one_html);
                                    }
                                    break;
                                default:
                                    if (isset($value[$matches2[1][$j]])) {
                                        $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value[$matches2[1][$j]]), $one_html);
                                    }
                            }
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                
                // 无限极嵌套解析
                if (preg_match($pattern3, $out_html, $matches3)) {
                    $out_html = str_replace('pboot:' . $matches3[1] . 'nav', 'pboot:nav', $out_html);
                    $out_html = str_replace('[' . $matches3[1] . 'nav:', '[nav:', $out_html);
                    $out_html = $this->parserNavLabel($out_html);
                }
                
                // 执行内容替换
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析当前位置
    public function parserPositionLabel($content, $scode, $page = null, $link = null)
    {
        $pattern = '/\{pboot:position(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            $data = $this->model->getPosition($scode);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[1][$i]);
                
                $separator = '';
                $separatoricon = '';
                $indextext = '';
                $indexicon = '';
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'separator':
                            $separator = $value;
                            break;
                        case 'separatoricon':
                            $separatoricon = $value;
                            break;
                        case 'indextext':
                            $indextext = $value;
                            break;
                        case 'indexicon':
                            $indexicon = $value;
                            break;
                    }
                }
                
                // 已经设置图标，则图标优先，如果没有，则判断是否已经设置文字
                if ($separatoricon) {
                    $separator = ' <i class="' . $separatoricon . '"></i> ';
                } elseif (! $separator) {
                    $separator = ' >> ';
                }
                
                if ($indexicon) {
                    $indextext = '<i class="' . $indexicon . '"></i>';
                } elseif (! $indextext) {
                    $indextext = '首页';
                }
                
                $out_html = '<a href="' . SITE_DIR . '/">' . $indextext . '</a>';
                if ($page && $scode == 0) {
                    $out_html .= $separator . '<a href="' . $link . '">' . $page . '</a>';
                } else {
                    foreach ($data as $key => $value) {
                        if ($value['outlink']) {
                            $out_html .= $separator . '<a href="' . $value['outlink'] . '">' . $value['name'] . '</a>';
                        } elseif ($value['type'] == 1) {
                            if ($value['filename']) {
                                $out_html .= $separator . '<a href="' . url('/home/about/index/scode/' . $value['filename']) . '">' . $value['name'] . '</a>';
                            } else {
                                $out_html .= $separator . '<a href="' . url('/home/about/index/scode/' . $value['scode']) . '">' . $value['name'] . '</a>';
                            }
                        } elseif ($value['type'] == 2) {
                            if ($value['filename']) {
                                $out_html .= $separator . '<a href="' . url('/home/list/index/scode/' . $value['filename']) . '">' . $value['name'] . '</a>';
                            } else {
                                $out_html .= $separator . '<a href="' . url('/home/list/index/scode/' . $value['scode']) . '">' . $value['name'] . '</a>';
                            }
                        }
                    }
                }
                // 执行内容替换
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析当前分类标签
    public function parserSortLabel($content, $sort)
    {
        $pattern = '/\{sort:([\w]+)(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[2][$i]);
                switch ($matches[1][$i]) {
                    case 'link':
                        if ($sort->outlink) {
                            $content = str_replace($matches[0][$i], $sort->outlink, $content);
                        } elseif ($sort->type == 1) {
                            if ($sort->filename) {
                                $content = str_replace($matches[0][$i], url('/home/about/index/scode/' . $sort->filename), $content);
                            } else {
                                $content = str_replace($matches[0][$i], url('/home/about/index/scode/' . $sort->scode), $content);
                            }
                        } else {
                            if ($sort->filename) {
                                $content = str_replace($matches[0][$i], url('/home/list/index/scode/' . $sort->filename), $content);
                            } else {
                                $content = str_replace($matches[0][$i], url('/home/list/index/scode/' . $sort->scode), $content);
                            }
                        }
                        break;
                    case 'tcode': // 顶级栏目ID
                        if (! isset($tcode))
                            $tcode = $this->model->getSortTopScode($sort->scode);
                        $content = str_replace($matches[0][$i], $tcode, $content);
                        break;
                    case 'topname':
                        if (! isset($tcode))
                            $tcode = $this->model->getSortTopScode($sort->scode);
                        $content = str_replace($matches[0][$i], $this->model->getSortName($tcode), $content);
                        break;
                    case 'toplink':
                        if (! isset($tcode)) {
                            $tcode = $this->model->getSortTopScode($sort->scode);
                        }
                        $top_sort = $this->model->getSort($tcode);
                        if ($top_sort->outlink) {
                            $toplink = $top_sort->outlink;
                        } elseif ($top_sort->type == 1) {
                            if ($top_sort->filename) {
                                $toplink = url('/home/about/index/scode/' . $top_sort->filename);
                            } else {
                                $toplink = url('/home/about/index/scode/' . $top_sort->scode);
                            }
                        } else {
                            if ($top_sort->filename) {
                                $toplink = url('/home/list/index/scode/' . $top_sort->filename);
                            } else {
                                $toplink = url('/home/list/index/scode/' . $top_sort->scode);
                            }
                        }
                        $content = str_replace($matches[0][$i], $toplink, $content);
                        break;
                    case 'parentname':
                        if ($sort->pcode == 0) {
                            $content = str_replace($matches[0][$i], $sort->name, $content);
                        } else {
                            $content = str_replace($matches[0][$i], $sort->parentname, $content);
                        }
                        break;
                    case 'parentlink':
                        if ($sort->pcode == 0) {
                            $parent_sort = $sort;
                        } else {
                            $parent_sort = $this->model->getSort($sort->pcode);
                        }
                        if ($parent_sort->outlink) {
                            $parentlink = $top_sort->outlink;
                        } elseif ($parent_sort->type == 1) {
                            if ($parent_sort->filename) {
                                $parentlink = url('/home/about/index/scode/' . $parent_sort->filename);
                            } else {
                                $parentlink = url('/home/about/index/scode/' . $parent_sort->scode);
                            }
                        } else {
                            if ($parent_sort->filename) {
                                $parentlink = url('/home/list/index/scode/' . $parent_sort->filename);
                            } else {
                                $parentlink = url('/home/list/index/scode/' . $parent_sort->scode);
                            }
                        }
                        $content = str_replace($matches[0][$i], $parentlink, $content);
                        break;
                    case 'toprows':
                        if (! isset($tcode))
                            $tcode = $this->model->getSortTopScode($sort->scode);
                        $content = str_replace($matches[0][$i], $this->model->getSortRows($tcode), $content);
                        break;
                    case 'parentrows':
                        if ($sort->pcode == 0) {
                            $content = str_replace($matches[0][$i], $this->model->getSortRows($sort->scode), $content);
                        } else {
                            $content = str_replace($matches[0][$i], $this->model->getSortRows($sort->pcode), $content);
                        }
                        break;
                    case 'rows':
                        $content = str_replace($matches[0][$i], $this->model->getSortRows($sort->scode), $content);
                        break;
                    case 'ico':
                        if ($sort->ico) {
                            $content = str_replace($matches[0][$i], SITE_DIR . $sort->ico, $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                        break;
                    case 'pic':
                        if ($sort->pic) {
                            $content = str_replace($matches[0][$i], SITE_DIR . $sort->pic, $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                        break;
                    case 'keywords': // 如果栏目关键字为空，则自动使用全局关键字
                        if ($sort->keywords) {
                            $content = str_replace($matches[0][$i], $this->adjustLabelData($params, $sort->keywords), $content);
                        } else {
                            $content = str_replace($matches[0][$i], '{pboot:sitekeywords}', $content);
                        }
                        break;
                    case 'description': // 如果栏目描述为空，则自动使用全局描述
                        if ($sort->description) {
                            $content = str_replace($matches[0][$i], $this->adjustLabelData($params, $sort->description), $content);
                        } else {
                            $content = str_replace($matches[0][$i], '{pboot:sitedescription}', $content);
                        }
                        break;
                    default:
                        if (isset($sort->{$matches[1][$i]})) {
                            $content = str_replace($matches[0][$i], $this->adjustLabelData($params, $sort->{$matches[1][$i]}), $content);
                        } else {
                            $content = str_replace($matches[0][$i], '', $content);
                        }
                }
            }
        }
        return $content;
    }

    // 解析非列表页分类标签
    public function parserSpecialPageSortLabel($content, $id, $page, $link)
    {
        $pattern = '/\{sort:([\w]+)(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[2][$i]);
                switch ($matches[1][$i]) {
                    case 'tcode': // 顶级栏目ID
                        $content = str_replace($matches[0][$i], $id, $content);
                        break;
                    case 'topname':
                        $content = str_replace($matches[0][$i], $page, $content);
                        break;
                    case 'toplink':
                        $content = str_replace($matches[0][$i], $link, $content);
                        break;
                    case 'pcode': // 父栏目ID
                        $content = str_replace($matches[0][$i], $id, $content);
                        break;
                    case 'parentname':
                        $content = str_replace($matches[0][$i], $page, $content);
                        break;
                    case 'parentlink':
                        $content = str_replace($matches[0][$i], $link, $content);
                        break;
                    case 'scode': // 当前栏目ID
                        $content = str_replace($matches[0][$i], $id, $content);
                        break;
                    case 'link':
                        $content = str_replace($matches[0][$i], $link, $content);
                        break;
                    case 'name': // 当前分类名称
                        $content = str_replace($matches[0][$i], $page, $content);
                        break;
                    case 'keywords': // 当前分类关键字,使用全局
                        $content = str_replace($matches[0][$i], '{pboot:sitekeywords}', $content);
                        break;
                    case 'description': // 当前分类描述,使用全局
                        $content = str_replace($matches[0][$i], '{pboot:sitedescription}', $content);
                        break;
                    default:
                        $content = str_replace($matches[0][$i], '', $content);
                }
            }
        }
        return $content;
    }

    // 解析指定分类标签
    public function parserSpecifySortLabel($content)
    {
        $pattern = '/\{pboot:sort(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:sort\}/';
        $pattern2 = '/\[sort:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $scode = - 1;
                
                // 跳过未指定scode的列表
                if (! array_key_exists('scode', $params)) {
                    continue;
                }
                
                // 分离分类编码
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'scode':
                            $scode = $value;
                            break;
                    }
                }
                
                if (! $scode) {
                    $scode = - 1;
                }
                
                // 读取一个或多个栏目数据
                $data = $this->model->getMultSort(escape_string($scode));
                
                // 无数据直接跳过
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        $params = $this->parserParam($matches2[2][$j]);
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'link':
                                if ($value->outlink) {
                                    $one_html = str_replace($matches2[0][$j], $value->outlink, $one_html);
                                } elseif ($value->type == 1) {
                                    if ($value->filename) {
                                        $one_html = str_replace($matches2[0][$j], url('/home/about/index/scode/' . $value->filename), $one_html);
                                    } else {
                                        $one_html = str_replace($matches2[0][$j], url('/home/about/index/scode/' . $value->scode), $one_html);
                                    }
                                } else {
                                    if ($value->filename) {
                                        $one_html = str_replace($matches2[0][$j], url('/home/list/index/scode/' . $value->filename), $one_html);
                                    } else {
                                        $one_html = str_replace($matches2[0][$j], url('/home/list/index/scode/' . $value->scode), $one_html);
                                    }
                                }
                                break;
                            case 'ico':
                                if ($value->ico) {
                                    $one_html = str_replace($matches2[0][$j], SITE_DIR . $value->ico, $one_html);
                                } else {
                                    $one_html = str_replace($matches2[0][$j], '', $one_html);
                                }
                                break;
                            case 'pic':
                                if ($value->pic) {
                                    $one_html = str_replace($matches2[0][$j], SITE_DIR . $value->pic, $one_html);
                                } else {
                                    $one_html = str_replace($matches2[0][$j], '', $one_html);
                                }
                                break;
                            case 'rows':
                                $one_html = str_replace($matches2[0][$j], $this->model->getSortRows($value->scode), $one_html); // 获取分类包含子类的内容数量
                                break;
                            default:
                                if (isset($value->{$matches2[1][$j]})) {
                                    $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->{$matches2[1][$j]}), $one_html);
                                }
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                // 执行替换
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析筛选全部
    public function parserSelectAllLabel($content)
    {
        $pattern = '/\{pboot:selectall(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[1][$i]);
                $text = '全部';
                $field = '';
                $class = '';
                $active = '';
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'field':
                            $field = $value;
                            break;
                        case 'text':
                            $text = $value;
                            break;
                        case 'class':
                            $class = $value;
                            break;
                        case 'active':
                            $active = $value;
                            break;
                    }
                }
                
                // 跳过不带field的标签
                if (! $field) {
                    continue;
                }
                
                // 获取地址路径
                $url = parse_url(URL);
                $path = preg_replace('/\/page\/[0-9]+/', '', $url['path']); // 去除路径方式分页，回到第一页
                                                                            
                // 分离字符串参数
                $output = array();
                if (isset($_SERVER["QUERY_STRING"]) && ! ! $qs = $_SERVER["QUERY_STRING"]) {
                    parse_str($qs, $output);
                    unset($output['page']); // 去除字符串方式分页，回到第一页
                    unset($output[$field]); // 不筛选该字段
                }
                if ($output) {
                    $qs = '?' . http_build_query($output);
                } else {
                    $qs = '';
                }
                // 如果有对本字段进行筛选，则不高亮
                if (get($field)) {
                    $out_html = '<a href="' . $path . $qs . '" class="' . $class . '">' . $text . '</a>';
                } else {
                    $out_html = '<a href="' . $path . $qs . '" class="' . $active . '">' . $text . '</a>';
                }
                
                // 执行内容替换
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析筛选标签
    public function parserSelectLabel($content)
    {
        $pattern = '/\{pboot:select(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:select\}/';
        $pattern2 = '/\[select:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            
            // 获取地址路径
            $url = parse_url(URL);
            $path = preg_replace('/\/page\/[0-9]+/', '', $url['path']); // 去除路径方式分页，回到第一页
                                                                        
            // 分离字符串参数
            if (isset($_SERVER["QUERY_STRING"]) && ! ! $qs = $_SERVER["QUERY_STRING"]) {
                parse_str($qs, $output);
                unset($output['page']); // 去除字符串方式分页，回到第一页
            }
            
            for ($i = 0; $i < $count; $i ++) {
                
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $field = '';
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'field':
                            $field = $value;
                            break;
                    }
                }
                
                // 跳过不带field的标签
                if (! $field) {
                    continue;
                }
                
                // 读取数据
                if (! ! $data = $this->model->getSelect(escape_string($field))) {
                    $data = explode(',', $data);
                } else {
                    $data = array();
                }
                
                // 无数据直接替换为空并跳过
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        $params = $this->parserParam($matches2[2][$j]);
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'value':
                                $one_html = str_replace($matches2[0][$j], $value, $one_html);
                                break;
                            case 'current':
                                $one_html = str_replace($matches2[0][$j], get($field), $one_html);
                                break;
                            case 'link':
                                $qs = $output;
                                $qs[$field] = $value;
                                $qs = http_build_query($qs);
                                $one_html = str_replace($matches2[0][$j], $path . '?' . $qs, $one_html);
                                break;
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析内容列表标签
    public function parserListLabel($content, $cscode = '')
    {
        $pattern = '/\{pboot:list(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:list\}/';
        $pattern2 = '/\[list:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $num = $this->config('pagesize'); // 未设置条数时使用默认15
                $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC'; // 默认排序
                $filter = ''; // 过滤
                $tags = ''; // tag标签
                $fuzzy = true; // 设置过滤、tag、筛选是否模糊匹配
                $ispics = ''; // 是否多图
                $isico = ''; // 是否缩略图
                $istop = ''; // 是否置顶
                $isrecommend = ''; // 是否推荐
                $isheadline = ''; // 是否头条
                $start = 1; // 起始条数，默认第一条开始
                            
                // 判断当前栏目和指定栏目
                if ($cscode && ! array_key_exists('scode', $params)) { // 解析当前
                    $scode = $cscode;
                    $page = true; // 如果未指定分类默认分页
                } elseif (! $cscode && array_key_exists('scode', $params)) { // 解析指定
                    $scode = $params['scode'];
                    $page = false; // 如果指定分类默认不分页
                } else {
                    continue;
                }
                
                if ($scode == '*') {
                    $scode = '';
                }
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'num':
                            $num = $value;
                            break;
                        case 'order':
                            switch ($value) {
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
                                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,' . $value . ' DESC,a.sorting ASC,a.date DESC,a.id DESC';
                                    break;
                                default:
                                    $order = $value . ',a.sorting ASC,a.date DESC,a.id DESC';
                            }
                            break;
                        case 'filter':
                            $filter = $value;
                            break;
                        case 'fuzzy':
                            $fuzzy = $value;
                            break;
                        case 'tags':
                            $tags = $value;
                            break;
                        case 'ispics':
                            $ispics = $value;
                            break;
                        case 'isico':
                            $isico = $value;
                            break;
                        case 'istop':
                            $istop = $value;
                            break;
                        case 'isrecommend':
                            $isrecommend = $value;
                            break;
                        case 'isheadline':
                            $isheadline = $value;
                            break;
                        case 'page':
                            $page = $value;
                            break;
                        case 'start':
                            $start = $value;
                            break;
                    }
                }
                
                // filter数据筛选
                $where1 = array();
                if ($filter) {
                    $filter = explode('|', $filter);
                    if (count($filter) == 2) {
                        $filter_arr = explode(',', $filter[1]);
                        if ($filter[0] == 'title') {
                            $filter[0] = 'a.title';
                        }
                        foreach ($filter_arr as $value) {
                            if ($value) {
                                if ($fuzzy) {
                                    $where1[] = $filter[0] . " like '%" . escape_string($value) . "%'";
                                } else {
                                    $where1[] = $filter[0] . "='" . escape_string($value) . "'";
                                }
                            }
                        }
                    }
                }
                
                // tags数据参数筛选
                $where2 = array();
                if ($tags) {
                    $tags_arr = explode(',', $tags);
                    foreach ($tags_arr as $value) {
                        if ($value) {
                            if ($fuzzy) {
                                $where2[] = "a.tags like '%" . escape_string($value) . "%'";
                            } else {
                                $where2[] = "a.tags='" . escape_string($value) . "'";
                            }
                        }
                    }
                }
                
                // tags数据传值筛选
                if (! ! $get_tags = get('tags', 'vars')) {
                    if ($fuzzy) {
                        $where2[] = "a.tags like '%" . $get_tags . "%'";
                    } else {
                        $where2[] = "a.tags='" . $get_tags . "'";
                    }
                }
                
                // 扩展字段数据筛选
                $where3 = array();
                foreach ($_GET as $key => $value) {
                    if (preg_match('/^ext_[\w-]+$/', $key)) { // 其他字段不加入
                        $where3[$key] = get($key, 'vars');
                    }
                }
                
                // 判断多图调节参数
                if ($ispics !== '') {
                    if ($ispics) {
                        $where3[] = "a.pics<>''";
                    } else {
                        $where3[] = "a.pics=''";
                    }
                }
                
                // 判断缩略图调节参数
                if ($isico !== '') {
                    if ($isico) {
                        $where3[] = "a.ico<>''";
                    } else {
                        $where3[] = "a.ico=''";
                    }
                }
                
                // 判断置顶调节参数
                if ($istop !== '') {
                    if ($istop) {
                        $where3[] = "a.istop=1";
                    } else {
                        $where3[] = "a.istop=0";
                    }
                }
                
                // 判断推荐调节参数
                if ($isrecommend !== '') {
                    if ($isrecommend) {
                        $where3[] = "a.isrecommend=1";
                    } else {
                        $where3[] = "a.isrecommend=0";
                    }
                }
                
                // 判断头条调节参数
                if ($isheadline !== '') {
                    if ($isheadline) {
                        $where3[] = "a.isheadline=1";
                    } else {
                        $where3[] = "a.isheadline=0";
                    }
                }
                
                // 起始数校验
                if (! is_numeric($start) || $start < 1) {
                    $start = 1;
                }
                
                if ($page) {
                    if (isset($paging)) {
                        error('请不要在一个页面使用多个具有分页的列表，您可将多余的使用page=0关闭分页！');
                    } else {
                        $paging = true;
                        $data = $this->model->getLists($scode, $num, $order, $where1, $where2, $where3, $fuzzy, $start);
                    }
                } else {
                    $data = $this->model->getList($scode, $num, $order, $where1, $where2, $where3, $fuzzy, $start);
                }
                
                // 无数据直接替换
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        $params = $this->parserParam($matches2[2][$j]);
                        $one_html = $this->parserList($matches2[1][$j], $matches2[0][$j], $one_html, $value, $params, $key);
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析当前内容标签
    public function parserCurrentContentLabel($content, $sort, $data)
    {
        $pattern = '/\{content:([\w]+)(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 无数据直接替换并跳过
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                $params = $this->parserParam($matches[2][$i]);
                $content = $this->ParserContent($matches[1][$i], $matches[0][$i], $content, $data, $params, $sort);
            }
        }
        return $content;
    }

    // 解析指定内容标签,单页支持使用scode调用
    public function parserSpecifyContentLabel($content)
    {
        $pattern = '/\{pboot:content(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:content\}/';
        $pattern2 = '/\[content:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $id = - 1;
                $scode = - 1;
                
                // 跳过未指定id和scode的列表
                if (array_key_exists('id', $params)) {
                    $id = $params['id'];
                    $data = $this->model->getContent(escape_string($id));
                } elseif (array_key_exists('scode', $params)) {
                    $scode = $params['scode'];
                    $data = $this->model->getAbout(escape_string($scode));
                } else {
                    continue;
                }
                
                // 读取数据
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = $matches[2][$i];
                for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                    $params = $this->parserParam($matches2[2][$j]);
                    $out_html = $this->parserList($matches2[1][$j], $matches2[0][$j], $out_html, $data, $params, 1);
                }
                // 执行替换
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析指定内容多图
    public function parserContentPicsLabel($content)
    {
        $pattern = '/\{pboot:pics(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:pics\}/';
        $pattern2 = '/\[pics:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $id = - 1;
                
                // 跳过未指定id的列表
                if (! array_key_exists('id', $params)) {
                    continue;
                }
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'id':
                            $id = $value;
                            break;
                        case 'num':
                            $num = $value;
                            break;
                    }
                }
                
                // 读取内容多图
                if (! ! $pics = $this->model->getContentPics(escape_string($id))) {
                    $pics = explode(',', $pics);
                } else {
                    $pics = array();
                }
                
                // 无图直接替换为空并跳过
                if (! $pics) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($pics as $value) { // 按查询图片条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'src':
                                if ($value) {
                                    $one_html = str_replace($matches2[0][$j], SITE_DIR . $value, $one_html);
                                } else {
                                    $one_html = str_replace($matches2[0][$j], '', $one_html);
                                }
                                break;
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                    if (isset($num) && $key > $num) {
                        unset($num);
                        break;
                    }
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析指定内容多选
    public function parserContentCheckboxLabel($content)
    {
        $pattern = '/\{pboot:checkbox(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:checkbox\}/';
        $pattern2 = '/\[checkbox:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $id = - 1;
                
                // 跳过未指定id的调用
                if (! array_key_exists('id', $params)) {
                    continue;
                }
                
                // 跳过未指定field的调用
                if (! array_key_exists('field', $params)) {
                    continue;
                }
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'id':
                            $id = $value;
                            break;
                        case 'field':
                            $field = $value;
                            break;
                    }
                }
                
                // 读取内容多图
                if (! ! $checkboxs = $this->model->getContentCheckbox(escape_string($id), escape_string($field))) {
                    $data = explode(',', $checkboxs);
                } else {
                    $data = array();
                }
                
                // 无内容直接替换为空并跳过
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'text':
                                $one_html = str_replace($matches2[0][$j], $value, $one_html);
                                break;
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析内容tags
    public function parserContentTagsLabel($content)
    {
        $pattern = '/\{pboot:tags(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:tags\}/';
        $pattern2 = '/\[tags:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $id = ''; // 调取指定内容的tags
                $scode = ''; // 调取指定分类的tags
                             
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'id':
                            $id = $value;
                            break;
                        case 'scode':
                            $scode = $value;
                            break;
                        case 'num':
                            $num = $value;
                            break;
                    }
                }
                
                // 获取数据
                $data = array();
                if ($id) { // 获取单个内容的tags
                    if (strpos($scode, ',') !== false) {
                        error('模板中指定id输出tags时不允许scode指定多个栏目！');
                    }
                    $rs = $this->model->getContentTags(escape_string($id));
                    $tags = explode(',', $rs->tags);
                    $scode = $scode ?: $rs->scode;
                    foreach ($tags as $key => $value) {
                        $data[] = array(
                            'scode' => $scode,
                            'tags' => $value
                        );
                    }
                } elseif ($scode) { // 获取指定栏目的tags
                    $scodes = explode(',', $scode); // 多个栏目是分别获取
                    foreach ($scodes as $key => $value) {
                        $tags = implode(',', $this->model->getAllTags($value)); // 先把所有列串起来
                        $tags = array_unique(explode(',', $tags)); // 再把所有tags组成数组
                        foreach ($tags as $key2 => $value2) {
                            $data[] = array(
                                'scode' => $value,
                                'tags' => $value2
                            );
                        }
                    }
                } else {
                    continue; // 未指定任何时不解析
                }
                
                // 无内容直接替换为空并跳过
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'text':
                                $one_html = str_replace($matches2[0][$j], $value['tags'], $one_html);
                                break;
                            case 'link':
                                $one_html = str_replace($matches2[0][$j], url('/home/list/index/scode/' . $value['scode']) . '?tags=' . $value['tags'], $one_html);
                                break;
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                    if (isset($num) && $key > $num) {
                        unset($num);
                        break;
                    }
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析幻灯片标签
    public function parserSlideLabel($content)
    {
        $pattern = '/\{pboot:slide(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:slide\}/';
        $pattern2 = '/\[slide:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $gid = 1;
                $num = 5;
                $start = 1;
                
                // 跳过未指定gid的标签
                if (! array_key_exists('gid', $params)) {
                    continue;
                }
                
                // 分离参数
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'gid':
                            $gid = $value;
                            break;
                        case 'num':
                            $num = $value;
                            break;
                        case 'start':
                            $start = $value;
                            break;
                    }
                }
                
                // 起始数校验
                if (! is_numeric($start) || $start < 1) {
                    $start = 1;
                }
                
                // 读取数据
                if (! $data = $this->model->getSlides(escape_string($gid), escape_string($num), $start)) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'src':
                                if ($value->pic) {
                                    $one_html = str_replace($matches2[0][$j], SITE_DIR . $value->pic, $one_html);
                                } else {
                                    $one_html = str_replace($matches2[0][$j], '', $one_html);
                                }
                                break;
                            default:
                                if (isset($value->{$matches2[1][$j]})) {
                                    $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->{$matches2[1][$j]}), $one_html);
                                }
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析友情链接标签
    public function parserLinkLabel($content)
    {
        $pattern = '/\{pboot:link(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:link\}/';
        $pattern2 = '/\[link:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $gid = 1;
                $num = 10;
                $start = 1;
                
                // 跳过未指定gid的标签
                if (! array_key_exists('gid', $params)) {
                    continue;
                }
                
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'gid':
                            $gid = $value;
                            break;
                        case 'num':
                            $num = $value;
                            break;
                        case 'start':
                            $start = $value;
                            break;
                    }
                }
                
                // 起始数校验
                if (! is_numeric($start) || $start < 1) {
                    $start = 1;
                }
                
                // 读取数据
                if (! $data = $this->model->getLinks(escape_string($gid), escape_string($num), $start)) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'logo':
                                if ($value->logo) {
                                    $one_html = str_replace($matches2[0][$j], SITE_DIR . $value->logo, $one_html);
                                } else {
                                    $one_html = str_replace($matches2[0][$j], '', $one_html);
                                }
                                break;
                            default:
                                if (isset($value->{$matches2[1][$j]})) {
                                    $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->{$matches2[1][$j]}), $one_html);
                                }
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析留言板标签
    public function parserMessageLabel($content)
    {
        $pattern = '/\{pboot:message(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:message\}/';
        $pattern2 = '/\[message:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $num = $this->config('pagesize');
                $page = true;
                $start = 1;
                
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'num':
                            $num = $value;
                            break;
                        case 'page':
                            $page = $value;
                            break;
                        case 'start':
                            $start = $value;
                            break;
                    }
                }
                
                // 起始数校验
                if (! is_numeric($start) || $start < 1) {
                    $start = 1;
                }
                
                // 读取数据
                if (! $data = $this->model->getMessage(escape_string($num), $page, $start)) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        $params = $this->parserParam($matches2[2][$j]);
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'ip':
                                $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, long2ip($value->user_ip)), $one_html);
                                break;
                            case 'os':
                                $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->user_os), $one_html);
                                break;
                            case 'bs':
                                $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->user_bs), $one_html);
                                break;
                            case 'askdate':
                                $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->create_time), $one_html);
                                break;
                            case 'replydate':
                                $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->update_time), $one_html);
                                break;
                            default:
                                if (isset($value->{$matches2[1][$j]})) {
                                    $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->{$matches2[1][$j]}), $one_html);
                                }
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析表单数据标签
    public function parserFormLabel($content)
    {
        $pattern = '/\{pboot:form(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:form\}/';
        $pattern2 = '/\[form:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $num = $this->config('pagesize');
                $fcode = - 1;
                $page = true;
                $start = 1;
                
                // 跳过未指定fcode的标签
                if (! array_key_exists('fcode', $params)) {
                    continue;
                }
                
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'num':
                            $num = $value;
                            break;
                        case 'fcode':
                            $fcode = $value;
                            break;
                        case 'page':
                            $page = $value;
                            break;
                        case 'start':
                            $start = $value;
                            break;
                    }
                }
                
                // 起始数校验
                if (! is_numeric($start) || $start < 1) {
                    $start = 1;
                }
                
                // 获取表名称
                if (! $table = $this->model->getFormTable(escape_string($fcode))) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 读取数据
                if (! $data = $this->model->getForm($table, escape_string($num), $page, $start)) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        $params = $this->parserParam($matches2[2][$j]);
                        switch ($matches2[1][$j]) {
                            case 'n':
                                $one_html = str_replace($matches2[0][$j], $key - 1, $one_html);
                                break;
                            case 'i':
                                $one_html = str_replace($matches2[0][$j], $key, $one_html);
                                break;
                            case 'date':
                                $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->create_time), $one_html);
                                break;
                            default:
                                if (isset($value->{$matches2[1][$j]})) {
                                    $one_html = str_replace($matches2[0][$j], $this->adjustLabelData($params, $value->{$matches2[1][$j]}), $one_html);
                                }
                        }
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析表单提交标签
    public function parserSubmitFormLabel($content)
    {
        $pattern = '/\{pboot:form(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[1][$i]);
                $fcode = '';
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'fcode':
                            $fcode = $value;
                            break;
                    }
                }
                if (! $fcode) { // 无表单编码不解析
                    continue;
                }
                $content = str_replace($matches[0][$i], url('/home/Form/add/fcode/' . $fcode), $content);
            }
        }
        return $content;
    }

    // 解析二维码生成标签
    public function parserQrcodeLabel($content)
    {
        $pattern = '/\{pboot:qrcode(\s+[^}]+)?\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $params = $this->parserParam($matches[1][$i]);
                $string = '';
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'string':
                            $string = $value;
                            break;
                    }
                }
                if (! $string) { // 无内容不解析
                    continue;
                }
                $content = str_replace($matches[0][$i], '<img src="' . CORE_DIR . '/qrcode.php?string=' . $string . '" class="qrcode" alt="二维码">', $content);
            }
        }
        return $content;
    }

    // 解析内容搜索结果标签
    public function parserSearchLabel($content)
    {
        $pattern = '/\{pboot:search(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:search\}/';
        $pattern2 = '/\[search:([\w]+)(\s+[^]]+)?\]/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            $field = request('field', 'var');
            $keyword = request('keyword', 'vars');
            $scode = request('scode');
            $start = 1;
            if (! preg_match('/^[\w,\s]+$/', $scode)) {
                $scode = '';
            }
            
            for ($i = 0; $i < $count; $i ++) {
                
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $num = $this->config('pagesize'); // 未设置条数时使用默认15
                $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,a.sorting ASC,a.date DESC,a.id DESC'; // 默认排序
                $filter = ''; // 过滤
                $tags = ''; // tag标签
                $fuzzy = true; // 设置过滤、tag、筛选是否模糊匹配
                $ispics = ''; // 是否多图
                $isico = ''; // 是否缩略图
                $istop = ''; // 是否置顶
                $isrecommend = ''; // 是否推荐
                $isheadline = ''; // 是否头条
                $page = true; // 搜索默认分页
                
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'field':
                            $field = $value;
                            break;
                        case 'scode':
                            $scode = $value;
                            break;
                        case 'num':
                            $num = $value;
                            break;
                        case 'order':
                            switch ($value) {
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
                                    $order = 'a.istop DESC,a.isrecommend DESC,a.isheadline DESC,' . $value . ' DESC,a.sorting ASC,a.date DESC,a.id DESC';
                                    break;
                                default:
                                    $order = $value . ',a.sorting ASC,a.date DESC,a.id DESC';
                            }
                            break;
                        case 'filter':
                            $filter = $value;
                        case 'fuzzy':
                            $fuzzy = $value;
                            break;
                        case 'tags':
                            $tags = $value;
                            break;
                        case 'ispics':
                            $ispics = $value;
                            break;
                        case 'isico':
                            $isico = $value;
                            break;
                        case 'istop':
                            $istop = $value;
                            break;
                        case 'isrecommend':
                            $isrecommend = $value;
                            break;
                        case 'isheadline':
                            $isheadline = $value;
                            break;
                        case 'page':
                            $page = $value;
                            break;
                        case 'start':
                            $start = $value;
                            break;
                    }
                }
                
                if ($scode == '*') {
                    $scode = '';
                }
                
                // filter数据筛选
                $where1 = array();
                if ($filter) {
                    $filter = explode('|', $filter);
                    if (count($filter) == 2) {
                        $filter_arr = explode(',', $filter[1]);
                        if ($filter[0] == 'title') {
                            $filter[0] = 'a.title';
                        }
                        foreach ($filter_arr as $value) {
                            if ($value) {
                                if ($fuzzy) {
                                    $where1[] = $filter[0] . " like '%" . escape_string($value) . "%'";
                                } else {
                                    $where1[] = $filter[0] . "='" . escape_string($value) . "'";
                                }
                            }
                        }
                    }
                }
                
                // tags数据筛选
                $where2 = array();
                if ($tags) {
                    $tags_arr = explode(',', $tags);
                    foreach ($tags_arr as $value) {
                        if ($value) {
                            if ($fuzzy) {
                                $where2[] = "a.tags like '%" . escape_string($value) . "%'";
                            } else {
                                $where2[] = "a.tags='" . escape_string($value) . "'";
                            }
                        }
                    }
                }
                
                // 存储搜索条件，条件为“并列”关系，由于为模糊匹配，条件为空时意味着“任意”
                $where3 = array();
                
                // 采取keyword方式
                if ($keyword) {
                    if (strpos($field, '|')) { // 匹配多字段的关键字搜索
                        $field = explode('|', $field);
                        foreach ($field as $value) {
                            if ($value == 'title') {
                                $value = 'a.title';
                            }
                            if ($fuzzy) {
                                $like = " like '%" . $keyword . "%'"; // 前面已经转义过
                            } else {
                                $like = " like '" . $keyword . "'"; // 前面已经转义过
                            }
                            if (isset($where3[0])) {
                                $where3[0] .= ' OR ' . $value . $like;
                            } else {
                                $where3[0] = $value . $like;
                            }
                        }
                        if (count($field) > 1) {
                            $where3[0] = '(' . $where3[0] . ')';
                        }
                    } else { // 匹配单一字段的关键字搜索
                        if ($field) {
                            if ($field == 'title') {
                                $field = 'a.title';
                            }
                            $where3[$field] = $keyword;
                        } else {
                            $where3['a.title'] = $keyword;
                        }
                    }
                }
                
                // 数据接收
                if ($_POST) {
                    $receive = $_POST;
                } else {
                    $receive = $_GET;
                }
                
                foreach ($receive as $key => $value) {
                    if (! ! $value = request($key, 'vars')) {
                        if ($key == 'title') {
                            $key = 'a.title';
                        }
                        if (preg_match('/^[\w-\.]+$/', $key)) { // 带有违规字符时不带入查询
                            $where3[$key] = $value;
                        }
                    }
                }
                
                // 去除特殊键值
                unset($where3['keyword']);
                unset($where3['field']);
                unset($where3['scode']);
                unset($where3['page']);
                unset($where3['from']);
                unset($where3['isappinstalled']);
                unset($where3['x']);
                unset($where3['y']);
                
                // 无任何条件不显示内容
                if (! $where3) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 判断多图调节参数
                if ($ispics !== '') {
                    if ($ispics) {
                        $where3[] = "a.pics<>''";
                    } else {
                        $where3[] = "a.pics=''";
                    }
                }
                
                // 判断缩略图调节参数
                if ($isico !== '') {
                    if ($isico) {
                        $where3[] = "a.ico<>''";
                    } else {
                        $where3[] = "a.ico=''";
                    }
                }
                
                // 判断置顶调节参数
                if ($istop !== '') {
                    if ($istop) {
                        $where3[] = "a.istop=1";
                    } else {
                        $where3[] = "a.istop=0";
                    }
                }
                
                // 判断推荐调节参数
                if ($isrecommend !== '') {
                    if ($isrecommend) {
                        $where3[] = "a.isrecommend=1";
                    } else {
                        $where3[] = "a.isrecommend=0";
                    }
                }
                
                // 判断头条调节参数
                if ($isheadline !== '') {
                    if ($isheadline) {
                        $where3[] = "a.isheadline=1";
                    } else {
                        $where3[] = "a.isheadline=0";
                    }
                }
                
                // 起始数校验
                if (! is_numeric($start) || $start < 1) {
                    $start = 1;
                }
                
                // 读取数据
                if ($page) {
                    if (isset($paging)) {
                        error('请不要在一个页面使用多个具有分页的列表，您可将多余的使用page=0关闭分页！');
                    } else {
                        $paging = true;
                        $data = $this->model->getLists($scode, $num, $order, $where1, $where2, $where3, $fuzzy, $start);
                    }
                } else {
                    $data = $this->model->getList($scode, $num, $order, $where1, $where2, $where3, $fuzzy, $start);
                }
                
                // 无数据直接替换
                if (! $data) {
                    $content = str_replace($matches[0][$i], '', $content);
                    continue;
                }
                
                // 匹配到内部标签
                if (preg_match_all($pattern2, $matches[2][$i], $matches2)) {
                    $count2 = count($matches2[0]); // 循环内的内容标签数量
                } else {
                    $count2 = 0;
                }
                
                $out_html = '';
                $key = 1;
                foreach ($data as $value) { // 按查询数据条数循环
                    $one_html = $matches[2][$i];
                    for ($j = 0; $j < $count2; $j ++) { // 循环替换数据
                        $params = $this->parserParam($matches2[2][$j]);
                        $one_html = $this->parserList($matches2[1][$j], $matches2[0][$j], $one_html, $value, $params, $key);
                    }
                    $key ++;
                    $out_html .= $one_html;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析列表分页标签
    public function parserPageLabel($content)
    {
        $pattern = '/\{page:([\w]+)\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                switch ($matches[1][$i]) {
                    case 'bar':
                        $content = str_replace($matches[0][$i], $this->getVar('pagebar'), $content);
                        break;
                    case 'current':
                        $content = str_replace($matches[0][$i], $this->getVar('pagecurrent') ?: 0, $content);
                        break;
                    case 'count':
                        $content = str_replace($matches[0][$i], $this->getVar('pagecount') ?: 0, $content);
                        break;
                    case 'rows':
                        $content = str_replace($matches[0][$i], $this->getVar('pagerows') ?: 0, $content);
                        break;
                    case 'index':
                        $content = str_replace($matches[0][$i], $this->getVar('pageindex'), $content);
                        break;
                    case 'pre':
                        $content = str_replace($matches[0][$i], $this->getVar('pagepre'), $content);
                        break;
                    case 'next':
                        $content = str_replace($matches[0][$i], $this->getVar('pagenext'), $content);
                        break;
                    case 'last':
                        $content = str_replace($matches[0][$i], $this->getVar('pagelast'), $content);
                        break;
                    case 'status':
                        $content = str_replace($matches[0][$i], $this->getVar('pagestatus'), $content);
                        break;
                    case 'numbar':
                        $content = str_replace($matches[0][$i], $this->getVar('pagenumbar'), $content);
                        break;
                    case 'selectbar':
                        $content = str_replace($matches[0][$i], $this->getVar('pageselectbar'), $content);
                        break;
                }
            }
        }
        return $content;
    }

    // 解析循环标签
    public function parserLoopLabel($content)
    {
        $pattern = '/\{pboot:loop(\s+[^}]+)?\}([\s\S]*?)\{\/pboot:loop\}/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                // 获取调节参数
                $params = $this->parserParam($matches[1][$i]);
                $start = 1;
                $end = $this->config('pagesize');
                
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case 'start':
                            $start = $value;
                            break;
                        case 'end':
                            $end = $value;
                            break;
                    }
                }
                
                $out_html = '';
                $key = 1;
                for ($n = $start; $n <= $end; $n ++) {
                    $one_html = str_replace('[loop:n]', $key - 1, $matches[2][$i]);
                    $one_html = str_replace('[loop:i]', $key, $one_html);
                    $one_html = str_replace('[loop:index]', $n, $one_html);
                    $out_html .= $one_html;
                    $key ++;
                }
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 解析IF条件标签
    public function parserIfLabel($content)
    {
        $pattern = '/\{pboot:if\(([^}]+)\)\}([\s\S]*?)\{\/pboot:if\}/';
        $pattern2 = '/pboot:([0-9])+if/';
        if (preg_match_all($pattern, $content, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $flag = '';
                $out_html = '';
                $danger = false;
                
                $white_fun = array(
                    'date',
                    'in_array',
                    'explode',
                    'implode',
                    'get',
                    'post'
                );
                
                // 带有函数的条件语句进行安全校验
                if (preg_match_all('/([\w]+)([\s]+)?\(/i', $matches[1][$i], $matches2)) {
                    foreach ($matches2[1] as $value) {
                        if ((function_exists($value) || preg_match('/^eval$/i', $value)) && ! in_array($value, $white_fun)) {
                            $danger = true;
                            break;
                        }
                    }
                }
                
                // 如果有危险函数，则不解析该IF
                if ($danger) {
                    continue;
                } else {
                    $matches[1][$i] = decode_string($matches[1][$i]); // 解码条件字符串
                }
                
                eval('if(' . $matches[1][$i] . '){$flag="if";}else{$flag="else";}');
                if (preg_match('/([\s\S]*)?\{else\}([\s\S]*)?/', $matches[2][$i], $matches2)) { // 判断是否存在else
                    switch ($flag) {
                        case 'if': // 条件为真
                            if (isset($matches2[1])) {
                                $out_html = $matches2[1];
                            }
                            break;
                        case 'else': // 条件为假
                            if (isset($matches2[2])) {
                                $out_html = $matches2[2];
                            }
                            break;
                    }
                } elseif ($flag == 'if') {
                    $out_html = $matches[2][$i];
                }
                
                // 无限极嵌套解析
                if (preg_match($pattern2, $out_html, $matches3)) {
                    $out_html = str_replace('pboot:' . $matches3[1] . 'if', 'pboot:if', $out_html);
                    $out_html = str_replace('{' . $matches3[1] . 'else}', '{else}', $out_html);
                    $out_html = $this->parserIfLabel($out_html);
                }
                
                // 执行替换
                $content = str_replace($matches[0][$i], $out_html, $content);
            }
        }
        return $content;
    }

    // 调整标签数据
    protected function adjustLabelData($params, $data)
    {
        if (! $params || ! $data)
            return $data;
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'style': // 时间样式
                    if ($params['style'] && $date = strtotime($data)) {
                        $data = date($params['style'], $date);
                    }
                    break;
                case 'len': // 长度截取
                    if ($params['len'] && is_string($data)) {
                        if (mb_strlen($data, 'utf-8') > $params['len']) {
                            $data = mb_substr($data, 0, $params['len'], 'utf-8') . '···';
                        }
                    }
                    break;
                case 'lencn': // 以中文占位长度方式截取，英文算半个
                    if ($params['lencn'] && is_string($data)) {
                        if (strlen_both($data) > $params['lencn']) {
                            $data = substr_both($data, 0, $params['lencn']) . '···';
                        }
                    }
                    break;
                case 'drophtml': // 去除html标签
                    if ($params['drophtml']) {
                        $data = strip_tags($data);
                    }
                    break;
                case 'dropblank': // 清理特殊空白
                    if ($params['dropblank']) {
                        $data = clear_html_blank($data);
                    }
                    break;
                case 'decode': // 解码或转义字符
                    if ($params['decode']) {
                        $data = decode_string($data);
                    } else {
                        $data = escape_string($data);
                    }
                    break;
                case 'substr': // 截取字符串
                    if ($params['substr'] && is_string($data)) {
                        $arr = explode(',', $params['substr']);
                        if (count($arr) == 2 && $arr[1]) {
                            $data = mb_substr($data, $arr[0] - 1, $arr[1], 'utf-8');
                        } else {
                            $data = mb_substr($data, $arr[0] - 1);
                        }
                    }
                    break;
                case 'unit': // bytes转换未其它单位
                    switch ($params['unit']) {
                        case 'KB':
                        case 'kb':
                            $data = $data / 1024;
                            break;
                        case 'MB':
                        case 'mb':
                            $data = $data / (1024 * 1024);
                            break;
                        case 'GB':
                        case 'gb':
                            $data = $data / (1024 * 1024 * 1024);
                            break;
                        case 'TB':
                        case 'tb':
                            $data = $data / (1024 * 1024 * 1024 * 1024);
                            break;
                        case 'PB':
                        case 'pb':
                            $data = $data / (1024 * 1024 * 1024 * 1024 * 1024);
                            break;
                        case 'EB':
                        case 'eb':
                            $data = $data / (1024 * 1024 * 1024 * 1024 * 1024 * 1024);
                            break;
                    }
                    break;
                case 'decimal':
                    if ($params['decimal']) {
                        $data = number_format($data, $params['decimal']);
                    }
                    break;
            }
        }
        return $data;
    }

    // 解析调节参数
    protected function parserParam($string)
    {
        if (! $string = trim($string))
            return array();
        $string = preg_replace('/\s+/', ' ', $string); // 多空格处理
        $param = array();
        if (preg_match_all('/([\w]+)[\s]?=[\s]?(\'([^\']+)\'|([^\s]+))/', $string, $matches)) {
            foreach ($matches[1] as $key => $value) {
                if ($matches[3][$key]) {
                    $param[$value] = $matches[3][$key];
                } else {
                    $param[$value] = $matches[4][$key];
                }
            }
        }
        return $param;
    }

    // 解析列表标签
    protected function parserList($label, $search, $content, $data, $params, $key)
    {
        switch ($label) {
            case 'n':
                $content = str_replace($search, $key - 1, $content);
                break;
            case 'i':
                $content = str_replace($search, $key, $content);
                break;
            case 'link':
                if ($data->outlink) { // 外链
                    $content = str_replace($search, $data->outlink, $content);
                } elseif ($data->filename) { // 自定义名称
                    $content = str_replace($search, url('/home/content/index/id/' . $data->filename), $content);
                } else { // 编码
                    $content = str_replace($search, url('/home/content/index/id/' . $data->id), $content);
                }
                break;
            case 'sortlink':
                if ($data->sortfilename) {
                    $content = str_replace($search, url('/home/list/index/scode/' . $data->sortfilename), $content);
                } else {
                    $content = str_replace($search, url('/home/list/index/scode/' . $data->scode), $content);
                }
                break;
            case 'subsortlink':
                if ($data->subscode) {
                    if ($data->subfilename) {
                        $content = str_replace($search, url('/home/list/index/scode/' . $data->subfilename), $content);
                    } else {
                        $content = str_replace($search, url('/home/list/index/scode/' . $data->subscode), $content);
                    }
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'sortname':
                if ($data->sortname) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->sortname), $content);
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'subsortname':
                if ($data->subsortname) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->subsortname), $content);
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'ico':
                if ($data->ico) {
                    $content = str_replace($search, SITE_DIR . $data->ico, $content);
                } else {
                    $content = str_replace($search, STATIC_DIR . '/images/nopic.png', $content);
                }
                break;
            case 'isico':
                if ($data->ico) {
                    $content = str_replace($search, 1, $content);
                } else {
                    $content = str_replace($search, 0, $content);
                }
                break;
            case 'enclosure':
                if ($data->enclosure) {
                    $content = str_replace($search, SITE_DIR . $data->enclosure, $content);
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'enclosuresize':
                if ($data->enclosure && file_exists(ROOT_PATH . $data->enclosure)) {
                    $content = str_replace($search, $this->adjustLabelData($params, filesize(ROOT_PATH . $data->enclosure)), $content);
                } else {
                    $content = str_replace($search, 0, $content);
                }
            case 'likeslink':
                $content = str_replace($search, url('/home/Do/likes/id/' . $data->id), $content);
                break;
            case 'opposelink':
                $content = str_replace($search, url('/home/Do/oppose/id/' . $data->id), $content);
                break;
            case 'content':
                $this->pre[] = $this->adjustLabelData($params, $data->content); // 保存内容避免解析
                end($this->pre); // 指向最后一个元素
                $content = str_replace($search, '{pre:' . key($this->pre) . '}', $content); // 占位替换
                break;
            default:
                if (isset($data->$label)) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->$label), $content);
                } elseif (strpos($label, 'ext_') === 0) {
                    $content = str_replace($search, '', $content);
                }
        }
        return $content;
    }

    // 解析内容标签
    protected function ParserContent($label, $search, $content, $data, $params, $sort)
    {
        switch ($label) {
            case 'link':
                if ($data->outlink) {
                    $content = str_replace($search, $data->outlink, $content);
                } elseif ($data->filename) {
                    $content = str_replace($search, url('/home/content/index/id/' . $data->filename), $content);
                } else {
                    $content = str_replace($search, url('/home/content/index/id/' . $data->id), $content);
                }
                break;
            case 'sortlink':
                if ($data->sortfilename) {
                    $content = str_replace($search, url('/home/list/index/scode/' . $data->sortfilename), $content);
                } else {
                    $content = str_replace($search, url('/home/list/index/scode/' . $data->scode), $content);
                }
                break;
            case 'subsortlink':
                if ($data->subscode) {
                    if ($data->subfilename) {
                        $content = str_replace($search, url('/home/list/index/scode/' . $data->subfilename), $content);
                    } else {
                        $content = str_replace($search, url('/home/list/index/scode/' . $data->subscode), $content);
                    }
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'sortname':
                if ($data->sortname) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->sortname), $content);
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'subsortname':
                if ($data->subsortname) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->subsortname), $content);
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'ico':
                if ($data->ico) {
                    $content = str_replace($search, SITE_DIR . $data->ico, $content);
                } else {
                    $content = str_replace($search, STATIC_DIR . '/images/nopic.png', $content);
                }
                break;
            case 'isico':
                if ($data->ico) {
                    $content = str_replace($search, 1, $content);
                } else {
                    $content = str_replace($search, 0, $content);
                }
                break;
            case 'enclosure':
                if ($data->enclosure) {
                    $content = str_replace($search, SITE_DIR . $data->enclosure, $content);
                } else {
                    $content = str_replace($search, '', $content);
                }
                break;
            case 'enclosuresize':
                if ($data->enclosure && file_exists(ROOT_PATH . $data->enclosure)) {
                    $content = str_replace($search, $this->adjustLabelData($params, filesize(ROOT_PATH . $data->enclosure)), $content);
                } else {
                    $content = str_replace($search, 0, $content);
                }
                break;
            case 'likeslink':
                $content = str_replace($search, url('/home/Do/likes/id/' . $data->id), $content);
                break;
            case 'opposelink':
                $content = str_replace($search, url('/home/Do/oppose/id/' . $data->id), $content);
                break;
            case 'precontent':
                if ($data->type != 2) // 非列表内容页不解析
                    break;
                if (! ! $pre = $this->model->getContentPre($sort->scode, $data->id)) {
                    if ($pre->filename) {
                        $content = str_replace($search, '<a href="' . url('/home/content/index/id/' . $pre->filename) . '">' . $this->adjustLabelData($params, $pre->title) . '</a>', $content);
                    } else {
                        $content = str_replace($search, '<a href="' . url('/home/content/index/id/' . $pre->id) . '">' . $this->adjustLabelData($params, $pre->title) . '</a>', $content);
                    }
                } else {
                    if (isset($params['notext'])) {
                        $content = str_replace($search, $params['notext'], $content);
                    } else {
                        $content = str_replace($search, '没有了！', $content);
                    }
                }
                break;
            case 'prelink':
                if ($data->type != 2) // 非列表内容页不解析
                    break;
                if (! ! $pre = $this->model->getContentPre($sort->scode, $data->id)) {
                    if ($pre->filename) {
                        $content = str_replace($search, url('/home/content/index/id/' . $pre->filename), $content);
                    } else {
                        $content = str_replace($search, url('/home/content/index/id/' . $pre->id), $content);
                    }
                } else {
                    $content = str_replace($search, '#', $content);
                }
                break;
            case 'pretitle':
                if ($data->type != 2) // 非列表内容页不解析
                    break;
                if (! ! $pre = $this->model->getContentPre($sort->scode, $data->id)) {
                    $content = str_replace($search, $this->adjustLabelData($params, $pre->title), $content);
                } else {
                    if (isset($params['notext'])) {
                        $content = str_replace($search, $params['notext'], $content);
                    } else {
                        $content = str_replace($search, '没有了！', $content);
                    }
                }
                break;
            case 'nextcontent':
                if ($data->type != 2) // 非列表内容页不解析
                    break;
                if (! ! $next = $this->model->getContentNext($sort->scode, $data->id)) {
                    if ($next->filename) {
                        $content = str_replace($search, '<a href="' . url('/home/content/index/id/' . $next->filename) . '">' . $this->adjustLabelData($params, $next->title) . '</a>', $content);
                    } else {
                        $content = str_replace($search, '<a href="' . url('/home/content/index/id/' . $next->id) . '">' . $this->adjustLabelData($params, $next->title) . '</a>', $content);
                    }
                } else {
                    if (isset($params['notext'])) {
                        $content = str_replace($search, $params['notext'], $content);
                    } else {
                        $content = str_replace($search, '没有了！', $content);
                    }
                }
                break;
            case 'nextlink':
                if ($data->type != 2) // 非列表内容页不解析
                    break;
                if (! ! $next = $this->model->getContentNext($sort->scode, $data->id)) {
                    if ($next->filename) {
                        $content = str_replace($search, url('/home/content/index/id/' . $next->filename), $content);
                    } else {
                        $content = str_replace($search, url('/home/content/index/id/' . $next->id), $content);
                    }
                } else {
                    $content = str_replace($search, '#', $content);
                }
                break;
            case 'nexttitle':
                if ($data->type != 2) // 非列表内容页不解析
                    break;
                if (! ! $next = $this->model->getContentNext($sort->scode, $data->id)) {
                    $content = str_replace($search, $this->adjustLabelData($params, $next->title), $content);
                } else {
                    if (isset($params['notext'])) {
                        $content = str_replace($search, $params['notext'], $content);
                    } else {
                        $content = str_replace($search, '没有了！', $content);
                    }
                }
                break;
            case 'content':
                if (! isset($addvisits)) {
                    $visits = "<script src='" . url('/home/Do/visits/id/' . $data->id) . "' async='async'></script>";
                    $content = preg_replace('/(<\/body>)/i', $visits . "\n$1", $content);
                    $addvisits = true;
                }
                $this->pre[] = $this->adjustLabelData($params, $data->content); // 保存内容避免解析
                end($this->pre); // 指向最后一个元素
                $content = str_replace($search, '{pre:' . key($this->pre) . '}', $content); // 占位替换
                break;
            case 'keywords': // 如果内容关键字为空，则自动使用全局关键字
                if ($data->keywords) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->keywords), $content);
                } else {
                    $content = str_replace($search, '{pboot:sitekeywords}', $content);
                }
                break;
            case 'description': // 如果内容描述为空，则自动使用全局描述
                if ($data->description) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->description), $content);
                } else {
                    $content = str_replace($search, '{pboot:sitedescription}', $content);
                }
                break;
            default:
                if (isset($data->$label)) {
                    $content = str_replace($search, $this->adjustLabelData($params, $data->$label), $content);
                } elseif (strpos($label, 'ext_') === 0) {
                    $content = str_replace($search, '', $content);
                }
        }
        return $content;
    }
}