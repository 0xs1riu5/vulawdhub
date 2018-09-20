<?php
/**
 * 放置用户登陆注册
 */
namespace Ucenter\Controller;


use Common\Model\FollowModel;
use Think\Controller;
use User\Api\UserApi;

require_once APP_PATH . 'User/Conf/config.php';

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class MemberController extends Controller
{

    /**
     * register  注册页面
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function register()
    {

        //获取参数
        $aUsername = $username = I('post.username', '', 'op_t');
        $aNickname = I('post.nickname', '', 'op_t');
        $aPassword = I('post.password', '', 'op_t');
        $aVerify = I('post.verify', '', 'op_t');
        $aRegVerify = I('post.reg_verify', '', 'op_t');
        $aRegType = I('post.reg_type', '', 'op_t');
        $aStep = I('get.step', 'start', 'op_t');
        $aRole = I('post.role', 0, 'intval');


        if (!modC('REG_SWITCH', 'email', 'USERCONFIG')) {
            $this->error(L('_ERROR_REGISTER_CLOSED_'));
        }


        if (IS_POST) {
            if ($aUsername == null) {
                $this->error(L('_PLACEHOLDER_USERNAME_INPUT_'));
            }
            //注册用户
            $return = check_action_limit('reg', 'ucenter_member', 1, 1, true);
            if ($return && !$return['state']) {
                $this->error($return['info'], $return['url']);
            }
            /* 检测验证码 */
            if (check_verify_open('reg') && (!$aRegVerify)) {
                if (!check_verify($aVerify)) {
                    $this->error(L('_ERROR_VERIFY_CODE_') . L('_PERIOD_'));
                }
            }
            if (!$aRole) {
                $this->error(L('_ERROR_ROLE_SELECT_') . L('_PERIOD_'));
            }

            if (($aRegType == 'mobile' && modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG') == 1) || (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 2 && $aRegType == 'email')) {
                if (!D('Verify')->checkVerify($aUsername, $aRegType, $aRegVerify, 0)) {
                    $str = $aRegType == 'mobile' ? L('_PHONE_') : L('_EMAIL_');
                    $this->error($str . L('_FAIL_VERIFY_'));
                }
            }
            $aUnType = 0;
            //获取注册类型
            check_username($aUsername, $email, $mobile, $aUnType);
            if ($aRegType == 'email' && $aUnType != 2) {
                $this->error(L('_ERROR_EMAIL_FORMAT_'));
            }
            if ($aRegType == 'mobile' && $aUnType != 3) {
                $this->error(L('_ERROR_PHONE_FORMAT_'));
            }
            if ($aRegType == 'username') {
                $this->error('该种注册方式已弃用！');
            }
            if (!check_reg_type($aUnType)) {
                $this->error(L('_ERROR_REGISTER_NOT_OPENED_') . L('_PERIOD_'));
            }

            $aCode = I('post.code', '', 'op_t');
            if (!$this->checkInviteCode($aCode)) {
                $this->error(L('_ERROR_INV_ILLEGAL_') . L('_EXCLAMATION_'));
            }

            /* 注册用户 */
            $ucenterMemberModel = UCenterMember();
            $uid = $ucenterMemberModel->register($aUsername, $aNickname, $aPassword, $email, $mobile, $aUnType);
            if (0 < $uid) { //注册成功
                $this->initInviteUser($uid, $aCode, $aRole);
                $ucenterMemberModel->initRoleUser($aRole, $uid); //初始化角色用户
                if (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 1 && $aUnType == 2) {
                    set_user_status($uid, 3);
                    $verify = D('Verify')->addVerify($email, 'email', $uid, 0);
                    $res = $this->sendActivateEmail($email, $verify, $uid); //发送激活邮件
                    // $this->success('注册成功，请登录邮箱进行激活');
                }

                $uid = $ucenterMemberModel->login($username, $aPassword, $aUnType); //通过账号密码取到uid
                send_message($uid, '注册成功提醒', '欢迎你注册本系统', 'Ucenter/Index/index', array(), 1);
                D('Member')->login($uid, false, $aRole); //登陆
                $this->success('', U('Ucenter/member/step', array('step' => get_next_step('start'))));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            //显示注册表单
            if (is_login()) {
                redirect(U('Home/Index/index'));
            }
            $this->checkRegisterType();
            $aType = I('get.type', '', 'op_t');
            $regSwitch = modC('REG_SWITCH', '', 'USERCONFIG');
            $regSwitch = explode(',', $regSwitch);
            $regSwitch = array_diff($regSwitch, array('username'));
            if (!count($regSwitch)) {
                $this->error('系统没有开启注册！');
            }
            $this->assign('regSwitch', $regSwitch);
            $this->assign('step', $aStep);
            $this->assign('type', $aType == '' ? 'email' : $aType);
            $this->display();
        }
    }


    public function step()
    {
        $aStep = I('get.step', '', 'op_t');
        $aUid = session('temp_login_uid');
        $aRoleId = session('temp_login_role_id');
        if (empty($aUid)) {
            $this->error(L('_ERROR_PARAM_'));
        }
        $userRoleModel = D('UserRole');
        $map['uid'] = $aUid;
        $map['role_id'] = $aRoleId;
        $step = $userRoleModel->where($map)->getField('step');
        if (get_next_step($step) != $aStep) {
            $aStep = check_step($step);
            $_GET['step'] = $aStep;
            $userRoleModel->where($map)->setField('step', $aStep);
        }
        $userRoleModel->where($map)->setField('step', $aStep);
        if ($aStep == 'finish') {
            $this->createCode();
            D('Member')->login($aUid, false, $aRoleId);
        }
        $this->assign('step', $aStep);
        $this->display('register');
    }

    public function inCode()
    {
        if (IS_POST) {
            $aType = I('get.type', '', 'op_t');
            $aCode = I('post.code', '', 'op_t');
            $result['status'] = 0;
            if (!mb_strlen($aCode)) {
                $result['info'] = L('_INFO_PLEASE_INPUT_') . L('_EXCLAMATION_');
                $this->ajaxReturn($result);
            }
            $invite = D('Ucenter/Invite')->getByCode($aCode);
            if ($invite) {
                if ($invite['end_time'] > time()) {
                    $result['status'] = 1;
                    $result['url'] = U('Ucenter/Member/register', array('code' => $aCode, 'type' => $aType));
                } else {
                    $result['info'] = L('_INFO_INV_CODE_EXPIRED_');
                }
            } else {
                $result['info'] = L('_INFO_NOT_EXIST_');
            }
            $this->ajaxReturn($result);
        } else {
            $this->display();
        }
    }

    public function upRole()
    {
        $aRoleId = I('role_id', 0, 'intval');
        if (IS_POST) {
            $uid = is_login();
            $result['status'] = 0;
            if ($uid > 0 && $aRoleId != get_login_role()) {
                $aCode = I('post.code', '', 'op_t');
                if (!mb_strlen($aCode)) {
                    $result['info'] = L('_INFO_PLEASE_INPUT_') . L('_EXCLAMATION_');
                    $this->ajaxReturn($result);
                }
                $invite = D('Ucenter/Invite')->getByCode($aCode);
                if ($invite) {
                    if ($invite['end_time'] > time()) {
                        $map['id'] = $invite['invite_type'];
                        $map['roles'] = array('like', '%[' . $aRoleId . ']%');
                        $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                        if ($invite_type) {
                            $roleUser = D('UserRole')->where(array('uid' => $uid, 'role_id' => $aRoleId))->find();
                            if ($roleUser) {
                                $result['info'] = L('_INFO_INV_ROLE_POSSESS_') . L('_EXCLAMATION_');
                            } else {
                                $memberModel = D('Common/Member');
                                $memberModel->logout();
                                $this->initInviteUser($uid, $aCode, $aRoleId);
                                UCenterMember()->initRoleUser($aRoleId, $uid);
                                clean_query_user_cache($uid, 'avatars');
                                clean_query_user_cache($uid, array('rank_link'));
                                $memberModel->login($uid, false, $aRoleId); //登陆
                                $result['status'] = 1;
                                $result['url'] = U('Ucenter/Member/register', array('code' => $aCode));
                            }
                        } else {
                            $result['info'] = L('_INFO_INV_HIGH_LEVEL_NEEDED_') . L('_EXCLAMATION_');
                        }
                    } else {
                        $result['info'] = L('_INFO_INV_CODE_EXPIRED_');
                    }
                } else {
                    $result['info'] = L('_INFO_NOT_EXIST_');
                }
            } else {
                $result['info'] = L('_ERROR_ILLEGAL_OPERATE_') . L('_EXCLAMATION_');
            }
            $this->ajaxReturn($result);
        } else {
            $this->assign('role_id', $aRoleId);
            $this->display();
        }
    }

    /* 登录页面 */
    public function login()
    {
        //dump(11111);exit;
        $this->setTitle(L('_MEMBER_TITLE_LOGIN_'));

        if (IS_POST) {
            $result = A('Ucenter/Login', 'Widget')->doLogin();
            if ($result['status']) {
                $this->success($result['info'], I('post.from', U('Home/index/index'), 'text'));
            } else {
                $this->error($result['info']);
            }
        } else { //显示登录页面
            $this->display();
        }
    }


    /* 快捷登录登录页面 */
    public function quickLogin()
    {
        if (IS_POST) {
            $result = A('Ucenter/Login', 'Widget')->doLogin();
            $this->ajaxReturn($result);
        } else { //显示登录弹出框
            $this->display();
        }
    }

    /* 退出登录 */
    public function logout()
    {
        if (is_login()) {
            D('Member')->logout();
            $this->success(L('_SUCCESS_LOGOUT_') . L('_EXCLAMATION_'), U('User/login'));
        } else {
            $this->redirect('User/login');
        }
    }

    /* 验证码，用于登录和注册 */
    public function verify($id = 1)
    {
        verify($id);
        //  $verify = new \Think\Verify();
        //  $verify->entry(1);
    }

    /* 用户密码找回首页 */
    public function mi($email = '', $verify = '')
    {

        $email = strval($email);

        if (IS_POST) { //登录验证
            //检测验证码

            if (!check_verify($verify)) {
                $this->error(L('_ERROR_VERIFY_CODE_'));
            }

            //根据用户名获取用户UID
            $user = UCenterMember()->where(array('email' => $email, 'status' => 1))->find();
            $uid = $user['id'];
            if (!$uid || $email == null) {
                $this->error(L('_ERROR_USERNAME_EMAIL_'));
            }

            //生成找回密码的验证码
            $verify = $this->getResetPasswordVerifyCode($uid);

            //发送验证邮箱
            $url = 'http://' . $_SERVER['HTTP_HOST'] . U('Ucenter/member/reset?uid=' . $uid . '&verify=' . $verify);
            $content = C('USER_RESPASS') . "<br/>" . $url . "<br/>" . modC('WEB_SITE_NAME', L('_OPENSNS_'), 'Config') . L('_SEND_MAIL_AUTO_') . "<br/>" . date('Y-m-d H:i:s', TIME()) . "</p>";
            send_mail($email, modC('WEB_SITE_NAME', L('_OPENSNS_'), 'Config') . L('_SEND_MAIL_PASSWORD_FOUND_'), $content);
            $this->success(L('_SUCCESS_SEND_MAIL_'), U('Member/login'));
        } else {
            if (is_login()) {
                redirect(U('Home/Index/index'));
            }
            if (!check_reg_type('email')) {
                redirect(U('Ucenter/Member/miMobile'));
            }

            $this->display();
        }
    }

    public function miMobile($email = '', $verify = '')
    {
        if (!check_reg_type('mobile')) {
            $this->error('请开启手机注册');
        }
        $email = strval($email);

        if (IS_POST) { //登录验证
            //检测验证码
            $aMobile = $_POST['mobile'];
            $aMobVerify = $_POST['verify'];

            $isVerify = D('Common/Verify')->checkVerify($aMobile, $type = 'mobile', $aMobVerify, 0);


            if ($isVerify) {
                $user = UCenterMember()->where(array('mobile' => $aMobile, 'status' => 1))->find();
                if (empty($user)) {
                    $this->ajaxReturn(array('status' => 0, 'info' => '该用户不存在！'));
                }
                /*重置密码操作*/
                $ucModel = UCenterMember();
                $res = $ucModel->where(array('id' => $user['id'], 'status' => 1))->save(array('password' => think_ucenter_md5('123456', UC_AUTH_KEY)));
                if ($res) {
                    $this->success('密码重置成功！新密码是“123456”');
                } else {
                    $this->error('密码重置失败！可能密码重置前就是“123456”。');
                }
            } else {
                $this->error('验证码或手机号码错误！');
            }
        } else {
            if (is_login()) {
                redirect(U('Home/Index/index'));
            }

            $this->display();
        }
    }


    /**
     * 重置密码
     */
    public function reset($uid, $verify)
    {
        //检查参数
        $uid = intval($uid);
        $verify = strval($verify);
        if (!$uid || !$verify) {
            //redirect(U('home/index/index'));
            $this->redirect('home/index/index');
            $this->error(L('_ERROR_PARAM_'));
        }

        //确认邮箱验证码正确
        $expectVerify = $this->getResetPasswordVerifyCode($uid);
        if ($expectVerify != $verify) {
            $this->redirect('home/index/index');
            $this->error(L('_ERROR_PARAM_'));
        }

        //将邮箱验证码储存在SESSION
        session('reset_password_uid', $uid);
        session('reset_password_verify', $verify);

        //显示新密码页面
        $this->display();
    }

    public function doReset($password, $repassword)
    {
        //确认两次输入的密码正确
        if ($password != $repassword) {
            $this->error(L('_PW_NOT_SAME_'));
        }

        //读取SESSION中的验证信息
        $uid = session('reset_password_uid');
        $verify = session('reset_password_verify');

        //确认验证信息正确
        $expectVerify = $this->getResetPasswordVerifyCode($uid);
        if ($expectVerify != $verify) {
            $this->error(L('_ERROR_VERIFY_INFO_INVALID_'));
        }

        //将新的密码写入数据库
        $data = array('id' => $uid, 'password' => $password);
        $model = UCenterMember();
        $data = $model->create($data);
        if (!$data) {
            $this->error(L('_ERROR_PASSWORD_FORMAT_'));
        }
        $result = $model->where(array('id' => $uid))->save($data);
        if ($result === false) {
            $this->error(L('_ERROR_DB_WRITE_'));
        }

        //显示成功消息
        $this->success(L('_ERROR_PASSWORD_RESET_'), U('Ucenter/Member/login'));
    }

    private function getResetPasswordVerifyCode($uid)
    {
        $user = UCenterMember()->where(array('id' => $uid))->find();
        $clear = implode('|', array($user['uid'], $user['username'], $user['last_login_time'], $user['password']));
        $verify = thinkox_hash($clear, UC_AUTH_KEY);
        return $verify;
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    public function showRegError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = L('') . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . L('_ERROR_LENGTH_2_') . L('_EXCLAMATION_');
                break;
            case -2:
                $error = L('_ERROR_USERNAME_FORBIDDEN_') . L('_EXCLAMATION_');
                break;
            case -3:
                $error = L('_ERROR_USERNAME_USED_') . L('_EXCLAMATION_');
                break;
            case -4:
                $error = L('_ERROR_LENGTH_PASSWORD_') . L('_EXCLAMATION_');
                break;
            case -5:
                $error = L('_ERROR_EMAIL_FORMAT_2_') . L('_EXCLAMATION_');
                break;
            case -6:
                $error = L('_ERROR_EMAIL_LENGTH_') . L('_EXCLAMATION_');
                break;
            case -7:
                $error = L('_ERROR_EMAIL_FORBIDDEN_') . L('_EXCLAMATION_');
                break;
            case -8:
                $error = L('_ERROR_EMAIL_USED_2_') . L('_EXCLAMATION_');
                break;
            case -9:
                $error = L('_ERROR_PHONE_FORMAT_2_') . L('_EXCLAMATION_');
                break;
            case -10:
                $error = L('_ERROR_FORBIDDEN_') . L('_EXCLAMATION_');
                break;
            case -11:
                $error = L('_ERROR_PHONE_USED_') . L('_EXCLAMATION_');
                break;
            case -20:
                $error = L('_ERROR_USERNAME_FORM_') . L('_EXCLAMATION_');
                break;
            case -30:
                $error = L('_ERROR_NICKNAME_USED_') . L('_EXCLAMATION_');
                break;
            case -31:
                $error = L('_ERROR_NICKNAME_FORBIDDEN_2_') . L('_EXCLAMATION_');
                break;
            case -32:
                $error = L('_ERROR_NICKNAME_FORM_') . L('_EXCLAMATION_');
                break;
            case -33:
                $error = L('_ERROR_LENGTH_NICKNAME_1_') . modC('NICKNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('NICKNAME_MAX_LENGTH', 32, 'USERCONFIG') . L('_ERROR_LENGTH_2_') . L('_EXCLAMATION_');;
                break;
            default:
                $error = L('_ERROR_UNKNOWN_');
        }
        return $error;
    }


    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function profile()
    {
        if (!is_login()) {
            $this->error(L('_ERROR_NOT_LOGIN_'), U('User/login'));
        }
        if (IS_POST) {
            //获取参数
            $uid = is_login();
            $password = I('post.old');
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($password) && $this->error(L('_ERROR_INPUT_ORIGIN_PASSWORD_'));
            empty($data['password']) && $this->error(L('_ERROR_INPUT_NEW_PASSWORD_'));
            empty($repassword) && $this->error(L('_ERROR_CONFIRM_PASSWORD_'));

            if ($data['password'] !== $repassword) {
                $this->error(L('_ERROR_NOT_SAME_PASSWORD_'));
            }

            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if ($res['status']) {
                $this->success(L('_SUCCESS_CHANGE_PASSWORD_') . L('_EXCLAMAITON_'));
            } else {
                $this->error($res['info']);
            }
        } else {
            $this->display();
        }
    }

    /**
     * doSendVerify  发送验证码
     * @param $account
     * @param $verify
     * @param $type
     * @return bool|string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doSendVerify($account, $verify, $type)
    {
        switch ($type) {
            case 'mobile':
                $content = modC('SMS_CONTENT', '{$verify}', 'USERCONFIG');
                $content = str_replace('{$verify}', $verify, $content);
                $content = str_replace('{$account}', $account, $content);
                $res = sendSMS($account, $content);
                return $res;
                break;
            case 'email':
                //发送验证邮箱
                $content = modC('REG_EMAIL_VERIFY', '{$verify}', 'USERCONFIG');
                $content = str_replace('{$verify}', $verify, $content);
                $content = str_replace('{$account}', $account, $content);
                $res = send_mail($account, modC('WEB_SITE_NAME', L('_OPENSNS_'), 'Config') . L('_EMAIL_VERIFY_2_'), $content);
                return $res;
                break;
        }

    }

    /**
     * activate  提示激活页面
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function activate()
    {

        // $aUid = I('get.uid',0,'intval');
        $aUid = session('temp_login_uid');
        $status = UCenterMember()->where(array('id' => $aUid))->getField('status');
        if ($status != 3) {
            redirect(U('ucenter/member/login'));
        }
        $info = query_user(array('uid', 'nickname', 'email'), $aUid);
        $this->assign($info);
        $this->display();
    }

    /**
     * reSend  重发邮件
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function reSend()
    {
        $res = $this->activateVerify();
        if ($res === true) {
            $this->success(L('_SUCCESS_SEND_'), 'refresh');
        } else {
            $this->error(L('_ERROR_SEND_') . $res, 'refresh');
        }

    }

    /**
     * changeEmail  更改邮箱
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function changeEmail()
    {
        $aEmail = I('post.email', '', 'op_t');
        $aUid = session('temp_login_uid');
        $ucenterMemberModel = UCenterMember();
        //$ucenterMemberModel->where(array('id' => $aUid))->getField('status');
        if ($ucenterMemberModel->where(array('id' => $aUid))->getField('status') != 3) {
            $this->error(L('_ERROR_AUTHORITY_LACK_') . L('_EXCLAMATION_'));
        }
        $ucenterMemberModel->where(array('id' => $aUid))->setField('email', $aEmail);
        clean_query_user_cache($aUid, 'email');
        $res = $this->activateVerify();
        $this->success(L('_SUCCESS_CHANGE_'), 'refresh');
    }

    /**
     * activateVerify 添加激活验证
     * @return bool|string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function activateVerify()
    {
        $aUid = session('temp_login_uid');
        $email = UCenterMember()->where(array('id' => $aUid))->getField('email');
        $verify = D('Verify')->addVerify($email, 'email', $aUid, 0);
        $res = $this->sendActivateEmail($email, $verify, $aUid); //发送激活邮件
        return $res;
    }

    /**
     * sendActivateEmail   发送激活邮件
     * @param $account
     * @param $verify
     * @return bool|string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function sendActivateEmail($account, $verify, $uid)
    {

        $url = 'http://' . $_SERVER['HTTP_HOST'] . U('ucenter/member/doActivate?account=' . $account . '&verify=' . $verify . '&type=email&uid=' . $uid);
        $content = modC('REG_EMAIL_ACTIVATE', '{$url}', 'USERCONFIG');
        $content = str_replace('{$url}', $url, $content);
        $content = str_replace('{$title}', modC('WEB_SITE_NAME', L('_OPENSNS_'), 'Config'), $content);
        $res = send_mail($account, modC('WEB_SITE_NAME', L('_OPENSNS_'), 'Config') . L('_VERIFY_LETTER_'), $content);


        return $res;
    }

    /**
     * saveAvatar  保存头像
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function saveAvatar()
    {

        $redirect_url = session('temp_login_uid') ? U('Ucenter/member/step', array('step' => get_next_step('change_avatar'))) : 'refresh';
        $aCrop = I('post.crop', '', 'op_t');
        $aUid = session('temp_login_uid') ? session('temp_login_uid') : is_login();
        $aPath = I('post.path', '', 'op_t');

        if (empty($aCrop)) {
            $this->success(L('_SUCCESS_SAVE_') . L('_EXCLAMATION_'), $redirect_url);
        }

        $returnPath = A('Ucenter/UploadAvatar', 'Widget')->cropPicture($aCrop, $aPath);
        $driver = modC('PICTURE_UPLOAD_DRIVER', 'local', 'config');
        $data = array('uid' => $aUid, 'status' => 1, 'is_temp' => 0, 'path' => $returnPath, 'driver' => $driver, 'create_time' => time());
        $res = M('avatar')->where(array('uid' => $aUid))->save($data);
        if (!$res) {
            M('avatar')->add($data);
        }
        clean_query_user_cache($aUid, array('avatars','avatars_html'));
        $this->success(L('_SUCCESS_AVATAR_CHANGE_') . L('_EXCLAMATION_'), $redirect_url);

    }

    /**
     * doActivate  激活步骤
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doActivate()
    {

        $aAccount = I('get.account', '', 'op_t');
        $aVerify = I('get.verify', '', 'op_t');
        $aType = I('get.type', '', 'op_t');
        $aUid = I('get.uid', 0, 'intval');
        session('temp_login_uid', $aUid);
        $check = D('Common/Verify')->checkVerify($aAccount, $aType, $aVerify, $aUid);
        if ($check) {
            set_user_status($aUid, 1);
            $this->success(L('_SUCCESS_ACTIVE_'), U('Ucenter/member/step', array('step' => get_next_step('start'))));
        } else {
            $this->error(L('_FAIL_ACTIVE_') . L('_EXCLAMATION_'));
        }

    }


    /**
     * checkAccount  ajax验证用户帐号是否符合要求
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function checkAccount()
    {
        $aAccount = I('post.account', '', 'op_t');
        $aType = I('post.type', '', 'op_t');
        if (empty($aAccount)) {
            $this->error(L('_EMPTY_CANNOT_') . L('_EXCLAMATION_'));
        }
        check_username($aAccount, $email, $mobile, $aUnType);
        $mUcenter = UCenterMember();
        switch ($aType) {
            case 'username':
                empty($aAccount) && $this->error(L('_ERROR_USERNAME_FORMAT_') . L('_EXCLAMATION_'));
                $length = mb_strlen($aAccount, 'utf-8'); // 当前数据长度
                if ($length < modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') || $length > modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG')) {
                    $this->error(L('_ERROR_USERNAME_LENGTH_1_') . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . L('_ERROR_USERNAME_LENGTH_2_'));
                }


                $id = $mUcenter->where(array('username' => $aAccount))->getField('id');
                if ($id) {
                    $this->error(L('_ERROR_USERNAME_EXIST_2_'));
                }
                preg_match("/^[a-zA-Z0-9_]{" . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . "," . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . "}$/", $aAccount, $result);
                if (!$result) {
                    $this->error(L('_ERROR_USERNAME_ONLY_PERMISSION_'));
                }
                break;
            case 'email':
                empty($email) && $this->error(L('_ERROR_EMAIL_FORMAT_') . L('_EXCLAMATION_'));
                $length = mb_strlen($email, 'utf-8'); // 当前数据长度
                if ($length < 4 || $length > 32) {
                    $this->error(L('_ERROR_EMAIL_EXIST_'));
                }

                $id = $mUcenter->where(array('email' => $email))->getField('id');
                if ($id) {
//                    $this->error(L('_ERROR_EMAIL_LENGTH_LIMIT_'));
                    $this->error(L('_ERROR_EMAIL_EXIST_'));
                }
                break;
            case 'mobile':
                empty($mobile) && $this->error(L('_ERROR_PHONE_FORMAT_'));
                $id = $mUcenter->where(array('mobile' => $mobile))->getField('id');
                if ($id) {
                    $this->error(L('_ERROR_PHONE_EXIST_'));
                }
                break;
        }
        $this->success(L('_SUCCESS_VERIFY_'));
    }

    /**
     * checkNickname  ajax验证昵称是否符合要求
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function checkNickname()
    {
        $aNickname = I('post.nickname', '', 'op_t');

        if (empty($aNickname)) {
            $this->error(L('_EMPTY_CANNOT_') . L('_EXCLAMATION_'));
        }

        $length = mb_strlen($aNickname, 'utf-8'); // 当前数据长度
        if ($length < modC('NICKNAME_MIN_LENGTH', 2, 'USERCONFIG') || $length > modC('NICKNAME_MAX_LENGTH', 32, 'USERCONFIG')) {
            $this->error(L('_ERROR_NICKNAME_LENGTH_11_') . modC('NICKNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('NICKNAME_MAX_LENGTH', 32, 'USERCONFIG') . L('_ERROR_USERNAME_LENGTH_2_'));
        }

        $memberModel = D('member');
        $uid = $memberModel->where(array('nickname' => $aNickname))->getField('uid');
        if ($uid) {
            $this->error(L('_ERROR_NICKNAME_EXIST_'));
        }
        preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/', $aNickname, $result);
        if (!$result) {
            $this->error(L('_ERROR_NICKNAME_ONLY_PERMISSION_'));
        }

        $this->success(L('_SUCCESS_VERIFY_'));
    }

    /**
     * 切换登录身份
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function changeLoginRole()
    {
        $aRoleId = I('post.role_id', 0, 'intval');
        $uid = is_login();
        $data['status'] = 0;
        if ($uid && $aRoleId != get_login_role()) {
            $roleUser = D('UserRole')->where(array('uid' => $uid, 'role_id' => $aRoleId))->find();
            if ($roleUser) {
                $memberModel = D('Common/Member');
                $memberModel->logout();
                clean_query_user_cache($uid, array('avatars', 'rank_link'));
                $result = $memberModel->login($uid, false, $aRoleId);
                if ($result) {
                    $data['info'] = L('_INFO_ROLE_CHANGE_');
                    $data['status'] = 1;
                }
            }
        }
        $data['info'] = L('_ERROR_ILLEGAL_OPERATE_');
        $this->ajaxReturn($data);
    }

    /**
     * 持有新身份
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function registerRole()
    {
        $aRoleId = I('post.role_id', 0, 'intval');
        $uid = is_login();
        $data['status'] = 0;
        if ($uid > 0 && $aRoleId != get_login_role()) {
            $roleUser = D('UserRole')->where(array('uid' => $uid, 'role_id' => $aRoleId))->find();
            if ($roleUser) {
                $data['info'] = L('_INFO_INV_ROLE_POSSESS_');
                $this->ajaxReturn($data);
            } else {
                $memberModel = D('Common/Member');
                $memberModel->logout();
                UCenterMember()->initRoleUser($aRoleId, $uid);
                clean_query_user_cache($uid, array('avatars', 'rank_link'));
                $memberModel->login($uid, false, $aRoleId); //登陆
            }
        } else {
            $data['info'] = L('_ERROR_ILLEGAL_OPERATE_');
            $this->ajaxReturn($data);
        }
    }


    /**修改用户扩展信息
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function edit_expandinfo()
    {
        $result = A('Ucenter/RegStep', 'Widget')->edit_expandinfo();
        if ($result['status']) {
            $this->success(L('_SUCCESS_SAVE_'), session('temp_login_uid') ? U('Ucenter/member/step', array('step' => get_next_step('expand_info'))) : 'refresh');
        } else {
            !isset($result['info']) && $result['info'] = L('_ERROR_INFO_SAVE_NONE_');
            $this->error($result['info']);
        }
    }

    /**
     * 设置用户标签
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function set_tag()
    {
        $result = A('Ucenter/RegStep', 'Widget')->do_set_tag();
        if ($result['status']) {
            $result['url'] = U('Ucenter/member/step', array('step' => get_next_step('set_tag')));
        } else {
            !isset($result['info']) && $result['info'] = L('_ERROR_INFO_SAVE_NONE_');
        }
        $this->ajaxReturn($result);
    }

    /**
     * 判断注册类型
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function checkRegisterType()
    {
        $aCode = I('get.code', '', 'op_t');
        $register_type = modC('REGISTER_TYPE', 'normal', 'Invite');
        $register_type = explode(',', $register_type);

        if (!in_array('invite', $register_type) && !in_array('normal', $register_type)) {
            $this->error(L('_ERROR_WEBSITE_REG_CLOSED_'));
        }

        if (in_array('invite', $register_type) && $aCode != '') { //邀请注册开启且有邀请码
            $invite = D('Ucenter/Invite')->getByCode($aCode);
            if ($invite) {
                if ($invite['end_time'] <= time()) {
                    $this->error(L('_ERROR_EXPIRED_') . L('_EXCLAMATION_'));
                } else { //获取注册角色
                    $map['id'] = $invite['invite_type'];
                    $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                    if ($invite_type) {
                        if (count($invite_type['roles'])) {
                            //角色
                            $map_role['status'] = 1;
                            $map_role['id'] = array('in', $invite_type['roles']);
                            $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
                            if (!count($roleList)) {
                                $this->error(L('_ERROR_ROLE_') . L('_EXCLAMATION_'));
                            }
                            //角色end
                        } else {
                            //角色
                            $map_role['status'] = 1;
                            $map_role['invite'] = 0;
                            $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
                            //角色end
                        }
                        $this->assign('code', $aCode);
                        $this->assign('invite_user', $invite['user']);
                    } else {
                        $this->error(L('_ERROR_FORBIDDEN_2_') . L('_EXCLAMATION_'));
                    }
                }
            } else {
                $this->error(L('_ERROR_NOT_EXIST_') . L('_EXCLAMATION_'));
            }
        } else {
            //（开启邀请注册且无邀请码）或（只开启了普通注册）
            if (in_array('invite', $register_type)) {
                $this->assign('open_invite_register', 1);
            }

            if (in_array('normal', $register_type)) {
                //角色
                $map_role['status'] = 1;
                $map_role['invite'] = 0;
                $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
                //角色end
            } else {
                //（只开启了邀请注册）
                $this->error(L('_ERROR_NOT_INVITED_') . L('_EXCLAMATION_'));
            }
        }
        $this->assign('role_list', $roleList);
        return true;
    }

    /**
     * 判断邀请码是否可用
     * @param string $code
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function checkInviteCode($code = '')
    {
        if ($code == '') {
            return true;
        }
        $invite = D('Ucenter/Invite')->getByCode($code);
        if ($invite['end_time'] >= time()) {
            $map['id'] = $invite['invite_type'];
            $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
            if ($invite_type) {
                return true;
            }
        }
        return false;
    }

    private function initInviteUser($uid = 0, $code = '', $role = 0)
    {
        if ($code != '') {
            $inviteModel = D('Ucenter/Invite');
            $invite = $inviteModel->getByCode($code);
            $data['inviter_id'] = abs($invite['uid']);
            $data['uid'] = $uid;
            $data['invite_id'] = $invite['id'];
            $result = D('Ucenter/InviteLog')->addData($data, $role);
            if ($result) {
                D('Ucenter/InviteUserInfo')->addSuccessNum($invite['invite_type'], abs($invite['uid']));

                $invite_info['already_num'] = $invite['already_num'] + 1;
                if ($invite_info['already_num'] == $invite['can_num']) {
                    $invite_info['status'] = 0;
                }
                $inviteModel->where(array('id' => $invite['id']))->save($invite_info);

                $map['id'] = $invite['invite_type'];
                $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                if ($invite_type['is_follow']) {
                    $followModel = D('Common/Follow');
                    $followModel->addFollow($uid, abs($invite['uid']), 1);
                    $followModel->addFollow(abs($invite['uid']), $uid, 1);
                    $memberModel = D('Member');
                    $memberModel->where(array('uid' => $uid))->setInc('fans', 1);
                    $memberModel->where(array('uid' => abs($invite['uid'])))->setInc('fans', 1);
                }
                if ($invite['uid'] > 0) {
                    D('Ucenter/Score')->setUserScore(array($invite['uid']), $invite_type['income_score'], $invite_type['income_score_type'], 'inc', '', 0, L('_ERROR_BONUS_'));
                }
            }
        }
        return true;
    }

    /**
     * 自动生成邀请码
     * @author 路飞<lf@ourstu.com>
     */
    public function createCode()
    {
        $invite_type = M('invite_type')->where(array('title' => '系统默认邀请码', 'create_time' => '1466749163'))->find();
        $aTypeId = $invite_type['id'];
        $aCodeNum = 1;
        $aCanNum = 100000;

        D('Ucenter/InviteUserInfo')->decNumber($aTypeId, $aCanNum * $aCodeNum);//修改用户信息
        $data['can_num'] = $aCanNum;
        $data['invite_type'] = $aTypeId;
        $result = D('Ucenter/Invite')->createUserCode($data, $aCodeNum);
        //$this->ajaxReturn($result);
    }

}