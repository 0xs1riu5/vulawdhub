<?php

/**
 * ECSHOP 管理员信息以及权限管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: privilege.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'login';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}
/* 初始化 $exc 对象 */
$exc = new exchange($ecs->table("admin_user"), $db, 'user_id', 'user_name');

/*------------------------------------------------------ */
//-- 退出登录
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'logout')
{
    if($_SESSION['yunqi_login']==true) yunqi_logout();
    /* 清除cookie */
    setcookie('ECSCP[admin_id]',   '', 1);
    setcookie('ECSCP[admin_pass]', '', 1);

    $sess->destroy_session();
    $url = $GLOBALS['ecs']->url()."admin/privilege.php?act=login";
    echo "<script>window.top.location.replace('".$url."');</script>"; 
}

//获取3.0授权信息
if ($_REQUEST['act'] == 'login_extend'){
    $callback = $GLOBALS['ecs']->url()."admin/privilege.php?act=login&type=yunqi";
    $iframe_url = $cert->get_authorize_url($callback);
    $smarty->assign('iframe_url',$iframe_url);
    $smarty->display('login_extend.html');
}

/*------------------------------------------------------ */
//-- 登陆界面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'login')
{
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    
    if($_REQUEST['type']=='yunqi'){
        $ecs = $GLOBALS['ecs'];
        $db = $GLOBALS['db'];
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");        
        if(isset($_GET['code']) && $_GET['code']){
            $code = $_GET['code'];
            $res = $cert->get_token($code);
            if($res['token'] and $res['params']){
                if (isset($res['params']['data']) && $res['params']['data']) {
                    foreach ($res['params']['data'] as $d_key => $d_value) {
                        $res['params'][$d_key] = $d_value;
                    }
                    unset($res['params']['data']);
                }
                $sql = "SELECT user_id, user_name, password, last_login, action_list, last_login,suppliers_id,ec_salt,passport_uid".
                    " FROM " . $ecs->table('admin_user') .
                    " WHERE passport_uid = '" . $res['params']['passport_uid']."'";
                $admin_row = $db->getRow($sql);
                if($certificate['passport_uid']!=$res['params']['passport_uid'] || $admin_row['passport_uid']!=$res['params']['passport_uid']){
                    $_SESSION['login_err'] = '您好，您的账号尚未激活,请使用本地账号登录!'; 
                    yunqi_logout();
                }
                if ($admin_row){
                    // 检查是否为供货商的管理员 所属供货商是否有效
                    if (!empty($admin_row['suppliers_id'])){
                        $supplier_is_check = suppliers_list_info(' is_check = 1 AND suppliers_id = ' . $admin_row['suppliers_id']);
                        if (empty($supplier_is_check))
                        {
                            sys_msg($_LANG['login_disable'], 1);
                        }
                    }
                    // 登录成功
                    set_admin_session($admin_row['user_id'], $admin_row['user_name'], $admin_row['action_list'], $admin_row['last_login']);
                    $_SESSION['suppliers_id'] = $admin_row['suppliers_id'];
                    $_SESSION['yunqi_login'] = true;
                    $_SESSION['TOKEN'] = $res['token'];
                    if(empty($row['ec_salt']))
                    {
                        $ec_salt=rand(1,9999);
                        $new_possword=md5(md5($user['password']).$ec_salt);
                         $db->query("UPDATE " .$ecs->table('admin_user').
                             " SET ec_salt='" . $ec_salt . "', password='" .$new_possword . "'".
                             " WHERE user_id='".$row['user_id']."'");
                    }

                    if($admin_row['action_list'] == 'all' && empty($admin_row['last_login']))
                    {
                        $_SESSION['shop_guide'] = true;
                    }

                    // 更新最后登录时间和IP
                    $db->query("UPDATE " .$ecs->table('admin_user').
                             " SET last_login='" . gmtime() . "', last_ip='" . real_ip() . "'".
                             " WHERE user_id in ('$_SESSION[admin_id]','".$row['user_id']."')");

                    //检查证书，短信物流token
                    $cert->check_certi($res);
                    //验证授权
                    if($certificate['certificate_id']&&$certificate['token']&&$certificate['node_id']){
                        $info = $cert->check_oauth_certificate($res['token']);

                        //3.6key获取授权失败，改用3.0key获取
                        if($info['status'] == 'success' && !isset($info['data']['service']['authorize_code']) && !isset($_COOKIE['use_oldkey'])){
                            setcookie('use_oldkey','true',time()+7200);
                            $url = $GLOBALS['ecs']->url()."admin/privilege.php?act=login_extend";
                            echo "<script>window.top.location.replace('".$url."');</script>";exit;
                        }
                        setcookie('use_oldkey','true',time()-1);

                        if($info['status']=='success' && ($info['data']['service']['authorize_code'] == 'NCH' || $info['data']['service']['authorize_code'] == 'NDE')){
                            $_SESSION['authorization'] = true;
                            $_SESSION['authorize_name'] = $info['data']['service']['authorize_name'];
                        }

                        $auth_sql = 'SELECT code FROM ' . $ecs->table('shop_config') . ' WHERE code = "authorize"';
                        if ($info['status']=='success' && ($info['data']['service']['authorize_code'] == 'NCH' || $info['data']['service']['authorize_code'] == 'NDE' )) {
                            $auth_conf = array(
                                'authorization' => 'true',
                                'authorize_code' => $info['data']['service']['authorize_code'],
                                'authorize_name' => $info['data']['service']['authorize_name'],
                            );
                            if(!$db->getRow($auth_sql)){
                                $db->query("INSERT " . $ecs->table('shop_config') . " set parent_id=2, code='authorize', type='hidden', value='" . serialize($auth_conf) . "'");
                            }else{
                                $db->query("UPDATE " . $ecs->table('shop_config') . " SET value='" . serialize($auth_conf) . "' WHERE code = 'authorize'");
                            }
                        }
                    }
                    // 清除购物车中过期的数据
                    clear_cart();

                    ecs_header("Location: ./index.php\n");

                    exit;
                }
            }
        }else{
            
        }
    }else{
        if ((intval($_CFG['captcha']) & CAPTCHA_ADMIN) && gd_version() > 0){
            $smarty->assign('gd_version', gd_version());
            $smarty->assign('random',     mt_rand());
        }
        if (isset($_SESSION['login_err']) && $_SESSION['login_err']) {
            $smarty->assign('login_err',$_SESSION['login_err']);
            unset($_SESSION['login_err']);
        }
        $smarty->assign('certi',$certificate);
        
        $activate_callback = $GLOBALS['ecs']->url()."admin/certificate.php?act=get_certificate&type=index";
        $activate_iframe_url = $cert->get_authorize_url($activate_callback);
        $smarty->assign('activate_iframe_url',$activate_iframe_url);
        
        $callback = $GLOBALS['ecs']->url()."admin/privilege.php?act=login&type=yunqi";
        $iframe_url = $cert->get_authorize_url($callback);
        $smarty->assign('iframe_url',$iframe_url);
        $smarty->assign('now_year',date('Y'));

        $yunqi_bg = getYunqiAd('ecshop_login_bg'); //ekaidian_login_bg
        if( isset($yunqi_bg[0]['picpath']) && !empty($yunqi_bg[0]['picpath']) ){
            $smarty->assign('yunqi_bg',$yunqi_bg[0]['picpath']);
        }
        $yunqi_ad = getYunqiAd('ecshop_login_link'); //ekaidian_login_link
        if( $yunqi_ad[0]['picpath'] && $yunqi_ad[0]['link'] ){
            $smarty->assign('yunqi_ad_link',$yunqi_ad[0]['link']);
            $smarty->assign('yunqi_ad_url',$yunqi_ad[0]['picpath']);
        }

        $smarty->display('login.htm');
    }
}

/*------------------------------------------------------ */
//-- 验证登陆信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'signin')
{
    if (intval($_CFG['captcha']) & CAPTCHA_ADMIN)
    {
        include_once(ROOT_PATH . 'includes/cls_captcha.php');

        /* 检查验证码是否正确 */
        $validator = new captcha();
        if (!empty($_POST['captcha']) && !$validator->check_word($_POST['captcha']))
        {
            sys_msg($_LANG['captcha_error'], 1);
        }
    }

    $_POST['username'] = isset($_POST['username']) ? trim($_POST['username']) : '';
    $_POST['password'] = isset($_POST['password']) ? trim($_POST['password']) : '';

    $sql="SELECT `ec_salt` FROM ". $ecs->table('admin_user') ."WHERE user_name = '" . $_POST['username']."'";
    $ec_salt =$db->getOne($sql);
    if(!empty($ec_salt))
    {
         /* 检查密码是否正确 */
         $sql = "SELECT user_id, user_name, password, last_login, action_list, last_login,suppliers_id,ec_salt,passport_uid".
            " FROM " . $ecs->table('admin_user') .
            " WHERE user_name = '" . $_POST['username']. "' AND password = '" . md5(md5($_POST['password']).$ec_salt) . "'";
    }
    else
    {
         /* 检查密码是否正确 */
         $sql = "SELECT user_id, user_name, password, last_login, action_list, last_login,suppliers_id,ec_salt,passport_uid".
            " FROM " . $ecs->table('admin_user') .
            " WHERE user_name = '" . $_POST['username']. "' AND password = '" . md5($_POST['password']) . "'";
    }
    $row = $db->getRow($sql);

    if ($row)
    {
        // 检查是否为供货商的管理员 所属供货商是否有效
        if (!empty($row['suppliers_id']))
        {
            $supplier_is_check = suppliers_list_info(' is_check = 1 AND suppliers_id = ' . $row['suppliers_id']);
            if (empty($supplier_is_check))
            {
                sys_msg($_LANG['login_disable'], 1);
            }
        }
        //如果是云起认证，则使用云起账号登录
        if($row['passport_uid']){
            $yunqi_login = $ecs->url."privilege.php?act=login&type=yunqi";
            header("Location: ".$yunqi_login);exit;
        }
        //end
        // 登录成功
        set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['last_login']);
        $_SESSION['suppliers_id'] = $row['suppliers_id'];
		if(empty($row['ec_salt']))
	    {
			$ec_salt=rand(1,9999);
			$new_possword=md5(md5($_POST['password']).$ec_salt);
             $db->query("UPDATE " .$ecs->table('admin_user').
                 " SET ec_salt='" . $ec_salt . "', password='" .$new_possword . "'".
                 " WHERE user_id='$_SESSION[admin_id]'");
		}

        if($row['action_list'] == 'all' && empty($row['last_login']))
        {
            $_SESSION['shop_guide'] = true;
        }

        // 更新最后登录时间和IP
        $db->query("UPDATE " .$ecs->table('admin_user').
                 " SET last_login='" . gmtime() . "', last_ip='" . real_ip() . "'".
                 " WHERE user_id='$_SESSION[admin_id]'");

        if (isset($_POST['remember']))
        {
            $time = gmtime() + 3600 * 24 * 365;
            setcookie('ECSCP[admin_id]',   $row['user_id'],                            $time);
            setcookie('ECSCP[admin_pass]', md5($row['password'] . $_CFG['hash_code']), $time);
        }

        // 清除购物车中过期的数据
        clear_cart();

        ecs_header("Location: ./index.php\n");

        exit;
    }
    else
    {
        sys_msg($_LANG['login_faild'], 1);
    }
}

/*------------------------------------------------------ */
//-- 管理员列表页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'list')
{
    /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['admin_list']);
    $smarty->assign('action_link', array('href'=>'privilege.php?act=add', 'text' => $_LANG['admin_add']));
    $smarty->assign('full_page',   1);
    $smarty->assign('admin_list',  get_admin_userlist());

    /* 显示页面 */
    assign_query_info();
    $smarty->display('privilege_list.htm');
}

/*------------------------------------------------------ */
//-- 查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $smarty->assign('admin_list',  get_admin_userlist());

    make_json_result($smarty->fetch('privilege_list.htm'));
}

/*------------------------------------------------------ */
//-- 添加管理员页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('admin_manage');

     /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['admin_add']);
    $smarty->assign('action_link', array('href'=>'privilege.php?act=list', 'text' => $_LANG['admin_list']));
    $smarty->assign('form_act',    'insert');
    $smarty->assign('action',      'add');
    $smarty->assign('select_role',  get_role_list());

    /* 显示页面 */
    assign_query_info();
    $smarty->display('privilege_info.htm');
}

/*------------------------------------------------------ */
//-- 添加管理员的处理
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    admin_priv('admin_manage');
    if($_POST['token']!=$_CFG['token'])
    {
         sys_msg('add_error', 1);
    }
    /* 判断管理员是否已经存在 */
    if (!empty($_POST['user_name']))
    {
        $is_only = $exc->is_only('user_name', stripslashes($_POST['user_name']));

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['user_name_exist'], stripslashes($_POST['user_name'])), 1);
        }
    }

    /* Email地址是否有重复 */
    if (!empty($_POST['email']))
    {
        $is_only = $exc->is_only('email', stripslashes($_POST['email']));

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['email_exist'], stripslashes($_POST['email'])), 1);
        }
    }

    /* 获取添加日期及密码 */
    $add_time = gmtime();
    
    $password  = md5($_POST['password']);
    $role_id = '';
    $action_list = '';
    if (!empty($_POST['select_role']))
    {
        $sql = "SELECT action_list FROM " . $ecs->table('role') . " WHERE role_id = '".$_POST['select_role']."'";
        $row = $db->getRow($sql);
        $action_list = $row['action_list'];
        $role_id = $_POST['select_role'];
    }

        $sql = "SELECT nav_list FROM " . $ecs->table('admin_user') . " WHERE action_list = 'all'";
        $row = $db->getRow($sql);


    $sql = "INSERT INTO ".$ecs->table('admin_user')." (user_name, email, password, add_time, nav_list, action_list, role_id) ".
           "VALUES ('".trim($_POST['user_name'])."', '".trim($_POST['email'])."', '$password', '$add_time', '$row[nav_list]', '$action_list', '$role_id')";

    $db->query($sql);
    /* 转入权限分配列表 */
    $new_id = $db->Insert_ID();

    /*添加链接*/
    $link[0]['text'] = $_LANG['go_allot_priv'];
    $link[0]['href'] = 'privilege.php?act=allot&id='.$new_id.'&user='.$_POST['user_name'].'';

    $link[1]['text'] = $_LANG['continue_add'];
    $link[1]['href'] = 'privilege.php?act=add';

    sys_msg($_LANG['add'] . "&nbsp;" .$_POST['user_name'] . "&nbsp;" . $_LANG['action_succeed'],0, $link);

    /* 记录管理员操作 */
    admin_log($_POST['user_name'], 'add', 'privilege');
 }

/*------------------------------------------------------ */
//-- 编辑管理员信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    /* 不能编辑demo这个管理员 */
    if ($_SESSION['admin_name'] == 'demo')
    {
       $link[] = array('text' => $_LANG['back_list'], 'href'=>'privilege.php?act=list');
       sys_msg($_LANG['edit_admininfo_cannot'], 0, $link);
    }

    $_REQUEST['id'] = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

    /* 查看是否有权限编辑其他管理员的信息 */
    if ($_SESSION['admin_id'] != $_REQUEST['id'])
    {
        admin_priv('admin_manage');
    }

    /* 获取管理员信息 */
    $sql = "SELECT user_id, user_name, email, password, agency_id, role_id FROM " .$ecs->table('admin_user').
           " WHERE user_id = '".$_REQUEST['id']."'";
    $user_info = $db->getRow($sql);


    /* 取得该管理员负责的办事处名称 */
    if ($user_info['agency_id'] > 0)
    {
        $sql = "SELECT agency_name FROM " . $ecs->table('agency') . " WHERE agency_id = '$user_info[agency_id]'";
        $user_info['agency_name'] = $db->getOne($sql);
    }

    /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['admin_edit']);
    $smarty->assign('action_link', array('text' => $_LANG['admin_list'], 'href'=>'privilege.php?act=list'));
    $smarty->assign('user',        $user_info);

    /* 获得该管理员的权限 */
    $priv_str = $db->getOne("SELECT action_list FROM " .$ecs->table('admin_user'). " WHERE user_id = '$_GET[id]'");

    /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
    if ($priv_str != 'all')
    {
       $smarty->assign('select_role',  get_role_list());
    }
    $smarty->assign('form_act',    'update');
    $smarty->assign('action',      'edit');

    assign_query_info();
    $smarty->display('privilege_info.htm');
}

/*------------------------------------------------------ */
//-- 更新管理员信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update' || $_REQUEST['act'] == 'update_self')
{

    /* 变量初始化 */
    $admin_id    = !empty($_REQUEST['id'])        ? intval($_REQUEST['id'])      : 0;
    $admin_name  = !empty($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : '';
    $admin_email = !empty($_REQUEST['email'])     ? trim($_REQUEST['email'])     : '';
    $ec_salt=rand(1,9999);
    $password = !empty($_POST['new_password']) ? ", password = '".md5(md5($_POST['new_password']).$ec_salt)."'"    : '';
    if($_POST['token']!=$_CFG['token'])
    {
         sys_msg('update_error', 1);
    }
    if ($_REQUEST['act'] == 'update')
    {
        /* 查看是否有权限编辑其他管理员的信息 */
        if ($_SESSION['admin_id'] != $_REQUEST['id'])
        {
            admin_priv('admin_manage');
        }
        $g_link = 'privilege.php?act=list';
        $nav_list = '';
    }
    else
    {
        $nav_list = !empty($_POST['nav_list'])     ? ", nav_list = '".@join(",", $_POST['nav_list'])."'" : '';
        $admin_id = $_SESSION['admin_id'];
        $g_link = 'privilege.php?act=modif';
    }
    /* 判断管理员是否已经存在 */
    if (!empty($admin_name))
    {
        $is_only = $exc->num('user_name', $admin_name, $admin_id);
        if ($is_only == 1)
        {
            sys_msg(sprintf($_LANG['user_name_exist'], stripslashes($admin_name)), 1);
        }
    }

    /* Email地址是否有重复 */
    if (!empty($admin_email))
    {
        $is_only = $exc->num('email', $admin_email, $admin_id);

        if ($is_only == 1)
        {
            sys_msg(sprintf($_LANG['email_exist'], stripslashes($admin_email)), 1);
        }
    }

    //如果要修改密码
    $pwd_modified = false;

    if (!empty($_POST['new_password']))
    {
        /* 查询旧密码并与输入的旧密码比较是否相同 */
        $sql = "SELECT password FROM ".$ecs->table('admin_user')." WHERE user_id = '$admin_id'";
        $old_password = $db->getOne($sql);
		$sql ="SELECT ec_salt FROM ".$ecs->table('admin_user')." WHERE user_id = '$admin_id'";
        $old_ec_salt= $db->getOne($sql);
		if(empty($old_ec_salt))
	    {
			$old_ec_password=md5($_POST['old_password']);
		}
		else
	    {
			$old_ec_password=md5(md5($_POST['old_password']).$old_ec_salt);
		}
        if ($old_password <> $old_ec_password)
        {
           $link[] = array('text' => $_LANG['go_back'], 'href'=>'javascript:history.back(-1)');
           sys_msg($_LANG['pwd_error'], 0, $link);
        }

        /* 比较新密码和确认密码是否相同 */
        if ($_POST['new_password'] <> $_POST['pwd_confirm'])
        {
           $link[] = array('text' => $_LANG['go_back'], 'href'=>'javascript:history.back(-1)');
           sys_msg($_LANG['js_languages']['password_error'], 0, $link);
        }
        else
        {
            $pwd_modified = true;
        }
    }

    $role_id = '';
    $action_list = '';
    if (!empty($_POST['select_role']))
    {
        $sql = "SELECT action_list FROM " . $ecs->table('role') . " WHERE role_id = '".$_POST['select_role']."'";
        $row = $db->getRow($sql);
        $action_list = ', action_list = \''.$row['action_list'].'\'';
        $role_id = ', role_id = '.$_POST['select_role'].' ';
    }
    //更新管理员信息
    if($pwd_modified)
    {
        $sql = "UPDATE " .$ecs->table('admin_user'). " SET ".
               "user_name = '$admin_name', ".
               "email = '$admin_email', ".
               "ec_salt = '$ec_salt' ".
               $action_list.
               $role_id.
               $password.
               $nav_list.
               "WHERE user_id = '$admin_id'";
    }
    else
    {
        $sql = "UPDATE " .$ecs->table('admin_user'). " SET ".
               "user_name = '$admin_name', ".
               "email = '$admin_email' ".
               $action_list.
               $role_id.
               $nav_list.
               "WHERE user_id = '$admin_id'";
    }

   $db->query($sql);
   /* 记录管理员操作 */
   admin_log($_POST['user_name'], 'edit', 'privilege');

   /* 如果修改了密码，则需要将session中该管理员的数据清空 */
   if ($pwd_modified && $_REQUEST['act'] == 'update_self')
   {
       $sess->delete_spec_admin_session($_SESSION['admin_id']);
       $msg = $_LANG['edit_password_succeed'];
   }
   else
   {
       $msg = $_LANG['edit_profile_succeed'];
   }

   /* 提示信息 */
   $link[] = array('text' => strpos($g_link, 'list') ? $_LANG['back_admin_list'] : $_LANG['modif_info'], 'href'=>$g_link);
   sys_msg("$msg<script>parent.document.getElementById('header-frame').contentWindow.document.location.reload();</script>", 0, $link);

}

/*------------------------------------------------------ */
//-- 编辑个人资料
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'modif')
{
    /* 不能编辑demo这个管理员 */
    if ($_SESSION['admin_name'] == 'demo')
    {
       $link[] = array('text' => $_LANG['back_admin_list'], 'href'=>'privilege.php?act=list');
       sys_msg($_LANG['edit_admininfo_cannot'], 0, $link);
    }

    include_once('includes/inc_menu.php');
    include_once('includes/inc_priv.php');

    /* 包含插件菜单语言项 */
    $sql = "SELECT code FROM ".$ecs->table('plugins');
    $rs = $db->query($sql);
    while ($row = $db->FetchRow($rs))
    {
        /* 取得语言项 */
        if (file_exists(ROOT_PATH.'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php'))
        {
            include_once(ROOT_PATH.'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php');
        }

        /* 插件的菜单项 */
        if (file_exists(ROOT_PATH.'plugins/'.$row['code'].'/languages/inc_menu.php'))
        {
            include_once(ROOT_PATH.'plugins/'.$row['code'].'/languages/inc_menu.php');
        }
    }

    foreach ($modules AS $key => $value)
    {
        ksort($modules[$key]);
    }
    ksort($modules);

    foreach ($modules AS $key => $val)
    {
        if (is_array($val))
        {
            foreach ($val AS $k => $v)
            {
                if (is_array($purview[$k]))
                {
                    $boole = false;
                    foreach ($purview[$k] as $action)
                    {
                         $boole = $boole || admin_priv($action, '', false);
                    }
                    if (!$boole)
                    {
                        unset($modules[$key][$k]);
                    }
                }
                elseif (! admin_priv($purview[$k], '', false))
                {
                    unset($modules[$key][$k]);
                }
            }
        }
    }

    /* 获得当前管理员数据信息 */
    $sql = "SELECT user_id, user_name, email, nav_list ".
           "FROM " .$ecs->table('admin_user'). " WHERE user_id = '".$_SESSION['admin_id']."'";
    $user_info = $db->getRow($sql);

    /* 获取导航条 */
    $nav_arr = (trim($user_info['nav_list']) == '') ? array() : explode(",", $user_info['nav_list']);
    $nav_lst = array();
    foreach ($nav_arr AS $val)
    {
        $arr              = explode('|', $val);
        $nav_lst[$arr[1]] = $arr[0];
    }

    /* 模板赋值 */
    $smarty->assign('lang',        $_LANG);
    $smarty->assign('ur_here',     $_LANG['modif_info']);
    $smarty->assign('action_link', array('text' => $_LANG['admin_list'], 'href'=>'privilege.php?act=list'));
    $smarty->assign('user',        $user_info);
    $smarty->assign('menus',       $modules);
    $smarty->assign('nav_arr',     $nav_lst);

    $smarty->assign('form_act',    'update_self');
    $smarty->assign('action',      'modif');

    /* 显示页面 */
    assign_query_info();
    $smarty->display('privilege_info.htm');
}

/*------------------------------------------------------ */
//-- 为管理员分配权限
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'allot')
{
    include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/priv_action.php');

    admin_priv('allot_priv');
    if ($_SESSION['admin_id'] == $_GET['id'])
    {
        admin_priv('all');
    }

    /* 获得该管理员的权限 */
    $priv_str = $db->getOne("SELECT action_list FROM " .$ecs->table('admin_user'). " WHERE user_id = '$_GET[id]'");

    /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
    if ($priv_str == 'all')
    {
       $link[] = array('text' => $_LANG['back_admin_list'], 'href'=>'privilege.php?act=list');
       sys_msg($_LANG['edit_admininfo_cannot'], 0, $link);
    }

    /* 获取权限的分组数据 */
    $sql_query = "SELECT action_id, parent_id, action_code,relevance FROM " .$ecs->table('admin_action').
                 " WHERE parent_id = 0";
    $res = $db->query($sql_query);
    while ($rows = $db->FetchRow($res))
    {
        $priv_arr[$rows['action_id']] = $rows;
    }

    /* 按权限组查询底级的权限名称 */
    $sql = "SELECT action_id, parent_id, action_code,relevance FROM " .$ecs->table('admin_action').
           " WHERE parent_id " .db_create_in(array_keys($priv_arr));
    $result = $db->query($sql);
    while ($priv = $db->FetchRow($result))
    {
        $priv_arr[$priv["parent_id"]]["priv"][$priv["action_code"]] = $priv;
    }

    // 将同一组的权限使用 "," 连接起来，供JS全选
    foreach ($priv_arr AS $action_id => $action_group)
    {
        $priv_arr[$action_id]['priv_list'] = join(',', @array_keys($action_group['priv']));

        foreach ($action_group['priv'] AS $key => $val)
        {
            $priv_arr[$action_id]['priv'][$key]['cando'] = (strpos($priv_str, $val['action_code']) !== false || $priv_str == 'all') ? 1 : 0;
        }
    }

    /* 赋值 */
    $smarty->assign('lang',        $_LANG);
    $smarty->assign('ur_here',     $_LANG['allot_priv'] . ' [ '. $_GET['user'] . ' ] ');
    $smarty->assign('action_link', array('href'=>'privilege.php?act=list', 'text' => $_LANG['admin_list']));
    $smarty->assign('priv_arr',    $priv_arr);
    $smarty->assign('form_act',    'update_allot');
    $smarty->assign('user_id',     $_GET['id']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('privilege_allot.htm');
}

/*------------------------------------------------------ */
//-- 更新管理员的权限
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update_allot')
{
    admin_priv('admin_manage');
    if($_POST['token']!=$_CFG['token'])
    {
         sys_msg('update_allot_error', 1);
    }
    /* 取得当前管理员用户名 */
    $admin_name = $db->getOne("SELECT user_name FROM " .$ecs->table('admin_user'). " WHERE user_id = '$_POST[id]'");

    /* 更新管理员的权限 */
    $act_list = @join(",", $_POST['action_code']);
    $sql = "UPDATE " .$ecs->table('admin_user'). " SET action_list = '$act_list', role_id = '' ".
           "WHERE user_id = '$_POST[id]'";

    $db->query($sql);
    /* 动态更新管理员的SESSION */
    if ($_SESSION["admin_id"] == $_POST['id'])
    {
        $_SESSION["action_list"] = $act_list;
    }

    /* 记录管理员操作 */
    admin_log(addslashes($admin_name), 'edit', 'privilege');

    /* 提示信息 */
    $link[] = array('text' => $_LANG['back_admin_list'], 'href'=>'privilege.php?act=list');
    sys_msg($_LANG['edit'] . "&nbsp;" . $admin_name . "&nbsp;" . $_LANG['action_succeed'], 0, $link);

}

/*------------------------------------------------------ */
//-- 删除一个管理员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('admin_drop');

    $id = intval($_GET['id']);

    /* 获得管理员用户名 */
    $admin_user_info = $db->getRow('SELECT user_name,passport_uid FROM '.$ecs->table('admin_user')." WHERE user_id='$id'");

    $admin_name = $admin_user_info['user_name'];

    /* demo这个管理员不允许删除 */
    if ($admin_name == 'demo')
    {
        make_json_error($_LANG['edit_remove_cannot']);
    }

    /* ID为1的不允许删除 */
    if ($id == 1)
    {
        make_json_error($_LANG['remove_cannot']);
    }

    if($admin_user_info['passport_uid']){
        make_json_error($_LANG['remove_cannot']);
    }

    /* 管理员不能删除自己 */
    if ($id == $_SESSION['admin_id'])
    {
        make_json_error($_LANG['remove_self_cannot']);
    }

    if ($exc->drop($id))
    {
        $sess->delete_spec_admin_session($id); // 删除session中该管理员的记录

        admin_log(addslashes($admin_name), 'remove', 'privilege');
        clear_cache_files();
    }

    $url = 'privilege.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}


/*------------------------------------------------------ */
//-- 根据用户名检测用户是否是云起管理员
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'is_yunqi_admin')
{
    $result = 'local';
    $_POST['username'] = isset($_POST['username']) ? trim($_POST['username']) : '';
    $sql="SELECT `passport_uid` FROM ". $ecs->table('admin_user') ."WHERE user_name = '" . $_POST['username']."'";
    $rs =$db->getOne($sql);
    !empty($rs) and $result = 'yunqi';
    make_json_result($result);
}

/* 获取管理员列表 */
function get_admin_userlist()
{
    $list = array();
    $sql  = 'SELECT user_id, user_name, email, add_time, last_login '.
            'FROM ' .$GLOBALS['ecs']->table('admin_user').' ORDER BY user_id DESC';
    $list = $GLOBALS['db']->getAll($sql);

    foreach ($list AS $key=>$val)
    {
        $list[$key]['add_time']     = local_date($GLOBALS['_CFG']['time_format'], $val['add_time']);
        $list[$key]['last_login']   = local_date($GLOBALS['_CFG']['time_format'], $val['last_login']);
    }

    return $list;
}

/* 清除购物车中过期的数据 */
function clear_cart()
{
    /* 取得有效的session */
    $sql = "SELECT DISTINCT session_id " .
            "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " .
                $GLOBALS['ecs']->table('sessions') . " AS s " .
            "WHERE c.session_id = s.sesskey ";
    $valid_sess = $GLOBALS['db']->getCol($sql);

    // 删除cart中无效的数据
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
            " WHERE session_id NOT " . db_create_in($valid_sess);
    $GLOBALS['db']->query($sql);
}

/* 获取角色列表 */
function get_role_list()
{
    $list = array();
    $sql  = 'SELECT role_id, role_name, action_list '.
            'FROM ' .$GLOBALS['ecs']->table('role');
    $list = $GLOBALS['db']->getAll($sql);
    return $list;
}

function yunqi_logout(){
    include_once(ROOT_PATH . 'includes/cls_certificate.php');
    $cert = new certificate();
    $url = $cert->logout_url();
    header("location: $url");
    
}

function getYunqiAd($ident){
    if( !$ident ) return false;
    $config['key'] = 'eucgr5fe';
    $config['secret'] = '6rwvbx7gnokqflwa4vfg';
    $config['site'] = 'https://openapi.ishopex.cn';
    $config['oauth'] = 'https://openapi.shopex.cn/oauth';
    $params['ad_ident'] = $ident;
    include_once(ROOT_PATH."admin/includes/oauth/oauth2.php");
    $prism = new oauth2($config);
    $type = 'api/yunqiaccount/csm/adgetapi';
    $r = $prism->request()->get('api/platform/timestamp');
    $time = $r->parsed();
    $prism->request()->timeout = 1;
    $rall = $prism->request()->post($type, $params,$time);
    $results = $rall->parsed();
    if($results['status'] != 'succ'){
        return false;
    }
    return $results['data'];
}

?>
