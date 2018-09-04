<?php

/**
 * ECSHOP 后台标签管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: tag_manage.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* act操作项的初始化 */
$_REQUEST['act'] = trim($_REQUEST['act']);
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}

/*------------------------------------------------------ */
//-- 获取标签数据列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 权限判断 */
    admin_priv('tag_manage');

    /* 模板赋值 */
    $smarty->assign('ur_here',      $_LANG['tag_list']);
    $smarty->assign('action_link', array('href' => 'tag_manage.php?act=add', 'text' => $_LANG['add_tag']));
    $smarty->assign('full_page',    1);

    $tag_list = get_tag_list();
    $smarty->assign('tag_list',     $tag_list['tags']);
    $smarty->assign('filter',       $tag_list['filter']);
    $smarty->assign('record_count', $tag_list['record_count']);
    $smarty->assign('page_count',   $tag_list['page_count']);

    $sort_flag  = sort_flag($tag_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 页面显示 */
    assign_query_info();
    $smarty->display('tag_manage.htm');
}

/*------------------------------------------------------ */
//-- 添加 ,编辑
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    admin_priv('tag_manage');

    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('insert_or_update', $is_add ? 'insert' : 'update');

    if($is_add)
    {
        $tag = array(
            'tag_id' => 0,
            'tag_words' => '',
            'goods_id' => 0,
            'goods_name' => $_LANG['pls_select_goods']
        );
        $smarty->assign('ur_here',      $_LANG['add_tag']);
    }
    else
    {
        $tag_id = $_GET['id'];
        $tag = get_tag_info($tag_id);
        $tag['tag_words']=htmlspecialchars($tag['tag_words']);
        $smarty->assign('ur_here',      $_LANG['tag_edit']);
    }
    $smarty->assign('tag', $tag);
    $smarty->assign('action_link', array('href' => 'tag_manage.php?act=list', 'text' => $_LANG['tag_list']));

    assign_query_info();
    $smarty->display('tag_edit.htm');
}

/*------------------------------------------------------ */
//-- 更新
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    admin_priv('tag_manage');

    $is_insert = $_REQUEST['act'] == 'insert';

    $tag_words = empty($_POST['tag_name']) ? '' : trim($_POST['tag_name']);
    $id = intval($_POST['id']);
    $goods_id = intval($_POST['goods_id']);
    if ($goods_id <= 0)
    {
        sys_msg($_LANG['pls_select_goods']);
    }

    if (!tag_is_only($tag_words, $id, $goods_id))
    {
        sys_msg(sprintf($_LANG['tagword_exist'], $tag_words));
    }

    if($is_insert)
    {
        $sql = 'INSERT INTO ' . $ecs->table('tag') . '(tag_id, goods_id, tag_words)' .
               " VALUES('$id', '$goods_id', '$tag_words')";
        $db->query($sql);

        admin_log($tag_words, 'add', 'tag');

         /* 清除缓存 */
        clear_cache_files();

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'tag_manage.php?act=list';

        sys_msg($_LANG['tag_add_success'], 0, $link);
    }
    else
    {

        edit_tag($tag_words, $id, $goods_id);

        /* 清除缓存 */
        clear_cache_files();

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'tag_manage.php?act=list';

        sys_msg($_LANG['tag_edit_success'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('tag_manage');

    $tag_list = get_tag_list();
    $smarty->assign('tag_list',     $tag_list['tags']);
    $smarty->assign('filter',       $tag_list['filter']);
    $smarty->assign('record_count', $tag_list['record_count']);
    $smarty->assign('page_count',   $tag_list['page_count']);

    $sort_flag  = sort_flag($tag_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('tag_manage.htm'), '',
        array('filter' => $tag_list['filter'], 'page_count' => $tag_list['page_count']));
}

/*------------------------------------------------------ */
//-- 搜索
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_goods')
{
    check_authz_json('tag_manage');

    include_once(ROOT_PATH . 'includes/cls_json.php');

    $json   = new JSON;
    $filter = $json->decode($_GET['JSON']);
    $arr    = get_goods_list($filter);
    if (empty($arr))
    {
        $arr[0] = array(
            'goods_id'   => 0,
            'goods_name' => ''
        );
    }

    make_json_result($arr);
}

/*------------------------------------------------------ */
//-- 批量删除标签
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_drop')
{
    admin_priv('tag_manage');

    if (isset($_POST['checkboxes']))
    {
        $count = 0;
        foreach ($_POST['checkboxes'] AS $key => $id)
        {
            $sql = "DELETE FROM " .$ecs->table('tag'). " WHERE tag_id='$id'";
            $db->query($sql);

            $count++;
        }

        admin_log($count, 'remove', 'tag_manage');
        clear_cache_files();

        $link[] = array('text' => $_LANG['back_list'], 'href'=>'tag_manage.php?act=list');
        sys_msg(sprintf($_LANG['drop_success'], $count), 0, $link);
    }
    else
    {
        $link[] = array('text' => $_LANG['back_list'], 'href'=>'tag_manage.php?act=list');
        sys_msg($_LANG['no_select_tag'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 删除标签
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('tag_manage');

    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $id = intval($_GET['id']);

    /* 获取删除的标签的名称 */
    $tag_name = $db->getOne("SELECT tag_words FROM " .$ecs->table('tag'). " WHERE tag_id = '$id'");

    $sql = "DELETE FROM " .$ecs->table('tag'). " WHERE tag_id = '$id'";
    $result = $GLOBALS['db']->query($sql);
    if ($result)
    {
        /* 管理员日志 */
        admin_log(addslashes($tag_name), 'remove', 'tag_manage');

        $url = 'tag_manage.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
        ecs_header("Location: $url\n");
        exit;
    }
    else
    {
       make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑标签名称
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == "edit_tag_name")
{
    check_authz_json('tag_manage');

    $name = json_str_iconv(trim($_POST['val']));
    $id = intval($_POST['id']);

    if (!tag_is_only($name, $id))
    {
        make_json_error(sprintf($_LANG['tagword_exist'], $name));
    }
    else
    {
        edit_tag($name, $id);
        make_json_result(stripslashes($name));
    }
}

/**
 * 判断同一商品的标签是否唯一
 *
 * @param $name  标签名
 * @param $id  标签id
 * @return bool
 */
function tag_is_only($name, $tag_id, $goods_id = '')
{
    if(empty($goods_id))
    {
        $db = $GLOBALS['db'];
        $sql = 'SELECT goods_id FROM ' . $GLOBALS['ecs']->table('tag') . " WHERE tag_id = '$tag_id'";
        $row = $GLOBALS['db']->getRow($sql);
        $goods_id = $row['goods_id'];
    }

    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('tag') . " WHERE tag_words = '$name'" .
           " AND goods_id = '$goods_id' AND tag_id != '$tag_id'";

    if($GLOBALS['db']->getOne($sql) > 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * 更新标签
 *
 * @param  $name
 * @param  $id
 * @return void
 */
function edit_tag($name, $id, $goods_id = '')
{
    $db = $GLOBALS['db'];
    $sql = 'UPDATE ' . $GLOBALS['ecs']->table('tag') . " SET tag_words = '$name'";
    if(!empty($goods_id))
    {
        $sql .= ", goods_id = '$goods_id'";
    }
    $sql .= " WHERE tag_id = '$id'";
    $GLOBALS['db']->query($sql);

    admin_log($name, 'edit', 'tag');
}

/**
 * 获取标签数据列表
 * @access  public
 * @return  array
 */
function get_tag_list()
{
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 't.tag_id' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('tag');
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    $sql = "SELECT t.tag_id, u.user_name, t.goods_id, g.goods_name, t.tag_words ".
            "FROM " .$GLOBALS['ecs']->table('tag'). " AS t ".
            "LEFT JOIN " .$GLOBALS['ecs']->table('users'). " AS u ON u.user_id=t.user_id ".
            "LEFT JOIN " .$GLOBALS['ecs']->table('goods'). " AS g ON g.goods_id=t.goods_id ".
            "ORDER by $filter[sort_by] $filter[sort_order] LIMIT ". $filter['start'] .", ". $filter['page_size'];
    $row = $GLOBALS['db']->getAll($sql);
    foreach($row as $k=>$v)
    {
        $row[$k]['tag_words'] = htmlspecialchars($v['tag_words']);
    }

    $arr = array('tags' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 * 取得标签的信息
 * return array
 */

function get_tag_info($tag_id)
{
    $sql = 'SELECT t.tag_id, t.tag_words, t.goods_id, g.goods_name FROM ' . $GLOBALS['ecs']->table('tag') . ' AS t' .
           ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON t.goods_id=g.goods_id' .
           " WHERE tag_id = '$tag_id'";
    $row = $GLOBALS['db']->getRow($sql);

    return $row;
}

?>
