<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-13
 * Time: 下午5:28
 * @author 郑钟良<zzl@ourstu.com>
 */


/**
 * 获取当前用户登录的角色的标识
 * @return int 角色id
 * @author 郑钟良<zzl@ourstu.com>
 */
function get_login_role()
{
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['role_id'] : 0;
    }
}

/**
 * 获取当前用户登录的角色是否审核通过
 * @return status 用户角色审核状态  1：通过，2：待审核，0：审核失败
 * @author 郑钟良<zzl@ourstu.com>
 */
function get_login_role_audit()
{
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['audit'] : 0;
    }
}

/**
 * 根据用户uid获取角色id
 * @param int $uid
 * @return int
 * @author 郑钟良<zzl@ourstu.com>
 */
function get_role_id($uid = 0)
{
    !$uid && $uid = is_login();
    if ($uid == is_login()) {//自己
        $role_id = get_login_role();
    } else {//不是自己
        $role_id = query_user(array('show_role'), $uid);
        $role_id = $role_id['show_role'];
    }
    return $role_id;
}

/**
 * 获取角色配置表 D('RoleConfig')查询条件
 * @param $type 类型
 * @param int $role_id 角色id
 * @return mixed 查询条件 $map
 * @author 郑钟良<zzl@ourstu.com>
 */
function getRoleConfigMap($type, $role_id = 0)
{
    $map['role_id'] = $role_id;
    $map['category'] = '';
    $map['name'] = $type;
    switch ($type) {
        case 'score'://积分
        case 'avatar'://默认头像
        case 'rank'://默认头衔
        case 'user_tag'://用户可拥有标签
            break;
        case 'expend_field'://角色拥有的扩展字段
        case 'register_expend_field'://注册时角色要填写的扩展字段
            $map['category'] = 'expend_field';
            break;
        default:
            ;
    }
    return $map;
}

/**
 * 清除角色缓存
 * @param int $role_id 角色id
 * @param $type 要清除的缓存，空：清除所有；字符串（Role_Expend_Info_）：清除一个缓存；数组array('Role_Expend_Info_','Role_Avatar_Id_','Role_Register_Expend_Info_')：清除多个缓存
 * @return bool
 * @author 郑钟良<zzl@ourstu.com>
 */
function clear_role_cache($role_id = 0, $type)
{
    if (isset($type)) {
        if (is_array($type)) {
            foreach ($type as $val) {
                S($val . $role_id, null);
            }
            unset($val);
        } else {
            S($type . $role_id, null);
        }
    } else {
        S('Role_Expend_Info_' . $role_id, null);
        S('Role_Avatar_Id_' . $role_id, null);
        S('Role_Register_Expend_Info_' . $role_id, null);
    }
    return true;
}

/**
 * check_login_role_authorized 检测模块是否允许当前登录角色前台访问
 * @param $module_name
 * @param bool $exit
 * @return int 返回1，表示有访问权限，没被禁用
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
function check_login_role_authorized($module_name, $exit = false)
{
    $modules = D('Common/Module')->getAll();

    foreach ($modules as $m) {
        if ($m['name'] == ucfirst($module_name)) {
            $module = $m;
            break;
        }
    }
    if (isset($module['is_setup']) && $module['is_setup'] == 0) {
        $res['status'] = 0;
        $res['info'] = '您所访问的模块未安装，禁止访问，请管理员到后台云市场-本地-模块中安装。';
    } elseif ($module['auth_role'][0] !== '' && !is_administrator()) {
        if (is_login()) {
            if (get_login_role_audit() != 1) {
                $res['status'] = -1;
                $res['info'] = '当前身份还未审核通过，不能访问该模块。';
            } elseif (!in_array(get_login_role(), $module['auth_role'])) {
                $res['status'] = -2;
                $res['info'] = '没有访问该模块的权限，如有疑问请联系管理员。（可切换身份后尝试）';
            }
        } else {
            $res['status'] = -3;
            $res['info'] = '该模块未对非登录用户开放。';
        }
    }
    if ($res) {
        if ($exit) {
            header("Content-Type: text/html; charset=utf-8");
            exit($res['info']);
        } else {
            return $res['status'];
        }
    }
    return 1;
}

/**
 * check_all_role_authorized检测模块是否禁用当前登录用户所有角色前台访问
 * @param $module_name
 * @param bool $exit
 * @return int 返回1，表示有访问权限，没全被禁用
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
function check_all_role_authorized($module_name, $exit = false)
{
    $modules = D('Common/Module')->getAll();

    foreach ($modules as $m) {
        if ($m['name'] == ucfirst($module_name)) {
            $module = $m;
            break;
        }
    }
    if (isset($module['is_setup']) && $module['is_setup'] == 0) {
        $res['status'] = 0;
        $res['info'] = '您所访问的模块未安装，禁止访问，请管理员到后台云市场-本地-模块中安装。';
    } elseif ($module['auth_role'][0] !== '' && !is_administrator()) {
        if (is_login()) {
            $role_ids = get_user_all_role();
            $auth_roles = array_intersect($role_ids, $module['auth_role']);
            if (!count($auth_roles)) {//用户全部的身份都没有被该模块允许访问
                $res['status'] = -1;
                $res['info'] = '没有访问该模块的权限，如有疑问请联系管理员。';
            }
        } else {
            $res['status'] = -2;
            $res['info'] = '该模块未对非登录用户开放。';
        }
    }
    if ($res) {
        if ($exit) {
            header("Content-Type: text/html; charset=utf-8");
            exit($res['info']);
        } else {
            return $res['status'];
        }
    }
    return 1;
}

/**
 * 获取用户所有审核通过对额角色
 * @param $uid
 * @return array|bool
 * @author:zzl(郑钟良) zzl@ourstu.com
 */
function get_user_all_role($uid)
{
    !$uid && $uid = is_login();
    if (!$uid) {
        return false;
    }
    $map['uid'] = $uid;
    $map['status'] = 1;
    $role_list = M('UserRole')->where($map)->field('role_id')->select();
    return array_column($role_list, 'role_id');
}