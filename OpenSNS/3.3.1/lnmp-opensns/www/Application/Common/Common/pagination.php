<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-10
 * Time: PM7:40
 */

function getPagination($totalCount, $countPerPage = 10,$rollPage=0)
{
    //计算总页数
    $pageCount = ceil($totalCount / $countPerPage);

    //如果只有1页，就没必要翻页了
    if ($pageCount <= 1) {
        return '';
    }
    $Page       = new \Think\Page($totalCount,$countPerPage);// 实例化分页类 传入总记录数和每页显示的记录数
    if($rollPage){
        $Page->setRollPage($rollPage);
    }
    return   $Page->show();
}


function getPageHtml($f_name, $totalpage, $data, $nowpage)
{
    if ($totalpage > 1 && $totalpage != null) {
        $str = '';
        foreach ($data as $k => $v) {
            $str = $str . '"' . $v . '"' . ',';
        }
        $pages = '<ul id="navigation"><li onmouseover="displaySubMenu(this)" onmouseout="hideSubMenu(this)"><a style="height:40px;line-height:40px;border: none">'.$nowpage.'</a><ul>';
        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $nowpage) {
                $pages = $pages . "<li class=\"active\"><a href=\"javascript:\"  class='page active' onclick='" . $f_name . "(" . $str . $i . ")'>" . $i . "</a></li>";
            } else {
                $pages = $pages . "<li><a href=\"javascript:\"  class='page' onclick='" . $f_name . "(" . $str . $i . ")'>" . $i . "</a></li>";
            }
        }
        $pages.='</ul></li></ul>';
        if ($nowpage == 1) {
            $a = $nowpage;
            $pre = "<li class=\"disabled\"><a style=\"border-radius: 100%!important;padding: 0 5px;\" href=\"javascript:\" class='page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'> < </a></li>";
        } else {
            $a = $nowpage - 1;
            $pre = "<li><a style=\"border-radius: 100%!important;padding: 0 5px;\" href=\"javascript:\" class='page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'> < </a></li>";
        }
        /*    $pre = "<li class=\"disabled\"><a class='a page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'>" . L('_LAST_PAGE_') . "</a></li>";*/

        if ($nowpage == $totalpage) {
            $b = $totalpage;
            $next = "<li class=\"disabled\"><a style=\"border-radius: 100%!important;padding: 0 5px;\" href=\"javascript:\" class='a page_next'  onclick = '" . $f_name . "( " . $str . $b . ")'> > </a></li>";
        } else {
            $b = $nowpage + 1;
            $next = "<li><a style=\"border-radius: 100%!important;padding: 0 5px;\" href=\"javascript:\" class='a page_next'  onclick = '" . $f_name . "( " . $str . $b . ")'> > </a></li>";
        }

        return $pre . $pages . $next;
    }
}


function getPage($data, $limit, $page)
{
    $offset = ($page - 1) * $limit;
    return array_slice($data, $offset, $limit);
}


function addUrlParam($url, $params)
{
    $app = MODULE_NAME;
    $controller = CONTROLLER_NAME;
    $action = ACTION_NAME;
    $get = array_merge($_GET, $params);
    return U("$app/$controller/$action", $get);
}

function getCurrentUrl()
{
    return $_SERVER['REQUEST_URI'];
}