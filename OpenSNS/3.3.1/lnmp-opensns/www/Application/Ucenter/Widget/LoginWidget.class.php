<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-4
 * Time: 下午6:57
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Widget;

use Think\Action;

class LoginWidget extends Action
{
    public function login($type = "quickLogin")
    {
        if ($type != "quickLogin") {
            if (is_login()) {
                redirect(U('Home/Index/index'));
            }
        }
        $this->assign('login_type', $type);
        $ph = array();
        check_login_type('username') && $ph[] = L('_USERNAME_');
        check_login_type('email') && $ph[] = L('_EMAIL_');
        check_login_type('mobile') && $ph[] = L('_PHONE_');
        $this->assign('ph', implode('/', $ph));
        $this->display('Widget/Login/login');
    }

    public function doLogin()
    {
        $aUsername = $username = I('post.username', '', 'op_t');
        $aPassword = I('post.password', '', 'op_t');
        $aVerify = I('post.verify', '', 'op_t');
        $aRemember = I('post.remember', 0, 'intval');


        /* 检测验证码 */
        if (check_verify_open('login')) {
            if (!check_verify($aVerify)) {
                $res['info']=L('_INFO_VERIFY_CODE_INPUT_ERROR_').L('_PERIOD_');
                return $res;
            }
        }

        /* 调用UC登录接口登录 */
        check_username($aUsername, $email, $mobile, $aUnType);

        if (!check_reg_type($aUnType)) {
            $res['info']=L('_INFO_TYPE_NOT_OPENED_').L('_PERIOD_');
        }

        $uid = UCenterMember()->login($username, $aPassword, $aUnType);
        if (0 < $uid) { //UC登录成功
            /* 登录用户 */
            $Member = D('Member');
            $args['uid'] = $uid;
            $args = array('uid'=>$uid,'nickname'=>$username);
            check_and_add($args);

            if ($Member->login($uid, $aRemember == 1)) { //登录用户
                //TODO:跳转到登录前页面

                $html_uc = '';
                if (UC_SYNC && $uid != 1) {
                    include_once './api/uc_client/client.php';
                    //同步登录到UC
                    $ref = M('ucenter_user_link')->where(array('uid' => $uid))->find();
                    $html_uc = uc_user_synlogin($ref['uc_uid']);
                }

                $oc_config =  include_once './OcApi/oc_config.php';
                if ($oc_config['SSO_SWITCH']) {
                    include_once  './OcApi/OCenter/OCenter.php';
                    $OCApi = new \OCApi();
                    $html_oc = $OCApi->ocSynLogin($uid);
                }

                $html =  empty($html_oc) ? $html_uc : $html_oc;
                $res['status']=1;
                $res['info']=$html;
                //$this->success($html, get_nav_url(C('AFTER_LOGIN_JUMP_URL')));
            } else {
                $res['info']=$Member->getError();
            }

        } else { //登录失败
            switch ($uid) {
                case -1:
                    $res['info']= L('_INFO_USER_FORBIDDEN_');
                    break; //系统级别禁用
                case -2:
                    $res['info']= L('_INFO_PW_ERROR_').L('_EXCLAMATION_');
                    break;
                default:
                    $res['info']= $uid;
                    break; // 0-接口参数错误（调试阶段使用）
            }
        }
        return $res;
    }
} 