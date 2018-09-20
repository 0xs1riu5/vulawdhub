<?php


/**
 * 支持的字段有
 * member表中的所有字段，ucenter_member表中的所有字段
 * 等级：title
 * 头像：avatar32 avatar64 avatar128 avatar256 avatar512
 * 个人中心地址：space_url
 *
 * @param      $fields array|string 如果是数组，则返回数组。如果不是数组，则返回对应的值
 * @param null $uid
 * @return array|null
 */
function query_user($fields = null, $uid = null)
{
    $uid = $uid == null ? is_login():$uid;
    $info = D('Common/User')->query_user($fields, $uid);

/*    if(!in_array($uid,$_SESSION['assign_user_ids'])){
        $query = D('Common/User')->query_user(null, $uid);
        echo "<script> sessionStorage['user_info_'+".$uid."] = JSON.stringify(".json_encode($query).")</script>";
        array_push( $_SESSION['assign_user_ids'] ,$uid);
        $_SESSION['assign_user_ids'][] = $uid;
    }*/

    return $info;
}

function read_query_user_cache($uid, $field)
{

    return D('Common/User')->read_query_user_cache($uid, $field);
}

function write_query_user_cache($uid, $field, $value)
{
    return D('Common/User')->write_query_user_cache($uid, $field, $value);
}

/**清理用户数据缓存，即时更新query_user返回结果。
 * @param $uid
 * @param $field
 * @auth 陈一枭
 */
function clean_query_user_cache($uid, $field)
{
    D('Common/User')->clean_query_user_cache($uid, $field);
}