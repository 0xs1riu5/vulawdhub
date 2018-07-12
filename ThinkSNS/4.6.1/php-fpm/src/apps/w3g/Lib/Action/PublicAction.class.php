<?php

class PublicAction extends Action
{
    private $_config;                    // 注册配置信息字段
    private $_register_model;            // 注册模型字段
    private $_user_model;                // 用户模型字段
    private $_invite;                    // 是否是邀请注册
    private $_invite_code;                // 邀请码

    /**
     * 模块初始化，获取注册配置信息、用户模型对象、注册模型对象、邀请注册与站点头部信息设置.
     */
    protected function _initialize()
    {

        /* # 验证是否开启 */
        json_decode(json_encode(model('Xdata')->get('admin_Mobile:setting')), false)->switch or $this->redirect(U('public/Index/index'));

        $this->_invite = false;
        // 未激活与未审核用户
        if ($this->mid > 0 && !in_array(ACTION_NAME, array('changeActivationEmail', 'activate', 'isEmailAvailable', 'isValidVerify'))) {
            $GLOBALS['ts']['user']['is_audit'] == 0 && ACTION_NAME != 'waitForAudit' && U('public/Register/waitForAudit', array('uid' => $this->mid), true);
            $GLOBALS['ts']['user']['is_audit'] == 1 && $GLOBALS['ts']['user']['is_active'] == 0 && ACTION_NAME != 'waitForActivation' && U('public/Register/waitForActivation', array('uid' => $this->mid), true);
        }
        // 登录后，将不显示注册页面
        // $this->mid > 0 && $GLOBALS['ts']['user']['is_init'] == 1 && redirect($GLOBALS['ts']['site']['home_url']);

        $this->_config = model('Xdata')->get('admin_Config:register');
        $this->_user_model = model('User');
        $this->_register_model = model('Register');
        $this->setTitle(L('PUBLIC_REGISTER'));

        $logo = '';
        if (($logo = model('Xdata')->get('admin_Mobile:w3gLogo'))) {
            $logo = getAttachUrlByAttachId($logo['logo']);
        }
        $this->assign('logo', $logo);
    }

    //刷新操作
    public function jump()
    {
        $url = $_GET['url'];
        $this->redirect($url);
    }

    public function isRegisterOpen()
    {
        return strtolower(model('Xdata')->get('register:register_type')) == 'open';
    }

    public function home()
    {
        // 登录验证
        $passport = model('Passport');
        //载入站点配置全局变量
        // if($GLOBALS['ts']['site']['site_logo_w3g']==''){
        //     $w3gLogoUrl='img/logo.png';
        // }else{
        //     $attach = model('Attach')->getAttachById($GLOBALS['ts']['site']['site_logo_w3g']);
        //     $w3gLogoUrl = getImageUrl($attach['save_path'].$attach['save_name']);
        // }

        $logo = '';
        if (($logo = model('Xdata')->get('admin_Mobile:w3gLogo'))) {
            $logo = getAttachUrlByAttachId($logo['logo']);
        }

        $this->assign('logo', $logo);
        $this->assign('is_register_open', $this->isRegisterOpen() ? '1' : '0');

        // # 幻灯
        $list = D('w3g_slide_show')->field('`image`, `url`')->select();
        $this->assign('slide', $list);

        $this->display();
    }

    public function login()
    {
        // 		dump(session('openid'));dump(session('__BACK_URL__'));exit();
        // 登录验证
        $passport = model('Passport');
        if ($passport->isLogged()) {
            $this->redirect(U('w3g/Public/home'));
        }

        $this->assign('is_register_open', $this->isRegisterOpen() ? '1' : '0');
        $this->display();
    }

    public function doLogin()
    {
        $email = safe($_POST['email']);
        $password = safe($_POST['password']);
        $remember = 1;
        $r = array();
        if (empty($email) || empty($password)) {
            // $this->redirect(U('w3g/Public/login'), 3, '用户名和密码不能为空');
            $r['success'] = 0;
            $r['des'] = '用户名或密码不能为空';
        }
        if (!isValidEmail($email)) {
            // $this->redirect(U('w3g/Public/login'), 3, 'Email格式错误，请重新输入');
            $r['success'] = 0;
            $r['des'] = 'Email格式错误，请重新输入';
        }
        if ($user = model('Passport')->getLocalUser($email, $password)) {
            // dump($user);
            if ($user['is_active'] == 0) {
                // $this->redirect(U('w3g/Public/login'), 3, '帐号尚未激活，请激活后重新登录');
                $r['success'] = 0;
                $r['des'] = '帐号尚未激活，请激活后重新登录';
            }
            model('Passport')->loginLocal($email, $password, $remember);
            $this->setSessionAndCookie($user['uid'], $user['uname'], $user['email'], intval($_POST['remember']) === 1);

            $openid = session('openid');
            if (empty($user ['openid']) && !empty($openid)) {
                M('user')->where('uid='.$user ['uid'])->setField('openid', $openid);
            }
            // $this->recordLogin($user['uid']);
            // model('Passport')->registerLogin($user, intval($_POST['remember']) === 1);
            $r['success'] = 1;
            $r['back_url'] = session('__BACK_URL__');
            $r['des'] = 'success';
        } else {
            // $this->redirect(U('w3g/Public/login'), 3, '帐号或密码错误，请重新输入');
            $r['success'] = 0;
            $r['des'] = '帐号或密码错误，请重新输入';
        }
        echo json_encode($r);
        exit;
    }

    //退出
    public function log_out()
    {
        model('Passport')->logoutLocal('');
        $this->redirect(U('w3g/Public/login'));
    }

    public function setSessionAndCookie($uid, $uname, $email, $remember = false)
    {
        $_SESSION['mid'] = $uid;
        $_SESSION['uname'] = $uname;
        $remember ?
            cookie('TSV4_LOGGED_USER', jiami('thinksns.'.$uid), (3600 * 24 * 365)) :
            cookie('TSV4_LOGGED_USER', jiami('thinksns.'.$uid), (3600 * 2));
    }

    //登录记录
    public function recordLogin($uid)
    {
        $data['uid'] = $uid;
        $data['ip'] = get_client_ip();
        $data['place'] = convert_ip($data['ip']);
        $data['ctime'] = time();
        M('login_record')->add($data);
    }

    // URL重定向
    public function redirect($url, $time = 0, $msg = '')
    {
        //多行URL地址支持
        $url = str_replace(array("\n", "\r"), '', $url);
        if (empty($msg)) {
            $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: '.$url);
            } else {
                header("refresh:{$time};url={$url}");
                // 防止手机浏览器下的乱码
                $str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
                $str .= $msg;
            }
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0) {
                $str .= $msg;
            }
        }
        $this->assign('msg', $str);

        $this->display('redirect');
    }

    // 访问正常版
    public function w3gToNormal()
    {
        $_SESSION['wap_to_normal'] = '1';
        cookie('wap_to_normal', '1', 3600 * 24 * 365);
        redirect(U('public'));
    }

    public function register()
    {
        // if (!$this->isRegisterOpen())
            // redirect(U('/Public/login'), 3, '站点未开放注册');

        $this->assign($_GET);
        $this->display();
    }
    //找回密码页面
    public function forgot()
    {
        // if (!$this->isRegisterOpen())
            // redirect(U('/Public/login'), 3, '站点未开放注册');
        $this->assign($_GET);
        $this->display('forgot');
    }

    //用来传递头像地址
    public function ava()
    {
        if (!isset($_GET['uid'])) {
            exit;
        }
        $data['user_id'] = intval($_GET['uid']);
        // 用户资料
        $profile = api('User')->data($data)->show();
        $this->assign('profile', $profile['avatar_small']);
        $this->display();
    }

    public function doRegister()
    {
        $service = model('Register');

        $invite = t($_POST['invate']);
        $inviteCode = t($_POST['invate_key']);

        $email = t($_POST['email']);
        $verify = t($_POST['verify']);

        $phone = t($_POST['phone']);
        $regCode = t($_POST['regCode']);

        $uname = t($_POST['uname']);
        $sex = 1 == $_POST['sex'] ? 1 : 2;
        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);

        if (!$email && !$phone) {
            $re['flag'] = 0;
            $re['msg'] = '非法提交';
            echo json_encode($re);
            exit;
        }

        $email && $email_correct = $service->isValidEmail($email);
        // $verify && $verify_correct = (md5(strtoupper($verify)) == $_SESSION['verify']);
        /* # 验证邮箱验证码是否不正确 */
        $verify and $verify_correct = model('Sms')->checkEmailCaptcha($email, $verify);

        $phone && $phone_correct = $service->isValidPhone($phone);
        // $regCode && $regCode_correct = $service->isValidRegCode($regCode);
        /* # 验证手机验证码是否不正确 */
        $regCode and $regCode_correct = model('Sms')->CheckCaptcha($phone, $regCode);

        $uname_correct = $service->isValidName($uname);
        $password_correct = $service->isValidPassword($_POST['password'], $_POST['repassword']);
        if (
            ($email && (!$email_correct || !$verify_correct)) ||
            ($phone && (!$phone_correct && !$regCode_correct)) ||
            !$uname_correct ||
            !$password_correct
        ) {
            $re['flag'] = 0;
            $re['msg'] = $service->getLastError();
            $verify_correct or $re['msg'] = '验证码错误';
            echo json_encode($re);
            exit;
        }
        /*if ($user = model('Passport')->getLocalUser($email, $password)) {
            if ($user['is_active'] == 0) {
                //redirect(U('w3g/Public/login'), 3, '帐号尚未激活，请激活后重新登录');
                $re['flag'] = 0;
                $re['msg'] = '帐号尚未激活，请激活后重新登录';
                echo json_encode($re);
                exit;
            }
        }*/
        $login_salt = rand(11111, 99999);
        $map['uname'] = $uname;
        $map['sex'] = $sex;
        $map['login_salt'] = $login_salt;
        $map['password'] = md5(md5($password).$login_salt);
        $email and $map['email'] = $email;
        $phone and $map['phone'] = $phone;
        $map['reg_ip'] = get_client_ip();
        $map['ctime'] = time();
        $map['first_letter'] = getFirstLetter($uname);
        $map['is_init'] = 1;
        $openid = session('openid');
        empty($openid) || $map['openid'] = $openid;

        $map['city'] = 0;
        $map['area'] = 0;
        $map['is_del'] = 0;

        // 审核状态： 0-需要审核；1-通过审核
        $map['is_audit'] = $this->_config['register_audit'] ? 0 : 1;
        // 需求添加 - 若后台没有填写邮件配置，将直接过滤掉激活操作
        $isActive = $this->_config['need_active'] ? 0 : 1;
        if ($isActive == 0) {
            $emailConf = model('Xdata')->get('admin_Config:email');
            if (empty($emailConf['email_host']) || empty($emailConf['email_account']) || empty($emailConf['email_password'])) {
                $isActive = 1;
            }
        }
        //当手机注册时，直接过滤掉激活操作
        //$phone && $isActive = 1;

        //直接激活
        $isActive = 1;

        $map['is_active'] = $isActive;
        //$map['first_letter'] = getFirstLetter($uname);
        //如果包含中文将中文翻译成拼音
        if (preg_match('/[\x7f-\xff]+/', $map['uname'])) {
            //昵称和呢称拼音保存到搜索字段
            $map['search_key'] = $map['uname'].' '.model('PinYin')->Pinyin($map['uname']);
        } else {
            $map['search_key'] = $map['uname'];
        }
        $uid = $this->_user_model->add($map);
        // dump($uid);
        if ($uid) {
            // 添加积分
            model('Credit')->setUserCredit($uid, 'init_default');
            // 如果是邀请注册，则邀请码失效
            if ($invite) {
                // 验证码使用
                $receiverInfo = model('User')->getUserInfo($uid);
                // 添加用户邀请码字段
                model('Invite')->setInviteCodeUsed($inviteCode, $receiverInfo);
                //给邀请人奖励
                model('User')->where('uid='.$uid)->setField('invite_code', $inviteCode);
            }

            // 添加至默认的用户组
            $userGroup = model('Xdata')->get('admin_Config:register');
            $userGroup = empty($userGroup['default_user_group']) ? C('DEFAULT_GROUP_ID') : $userGroup['default_user_group'];
            model('UserGroupLink')->domoveUsergroup($uid, implode(',', $userGroup));

            //注册来源-第三方帐号绑定
            if (isset($_POST['other_type'])) {
                $other['type'] = t($_POST['other_type']);
                $other['type_uid'] = t($_POST['other_uid']);
                $other['oauth_token'] = t($_POST['oauth_token']);
                $other['oauth_token_secret'] = t($_POST['oauth_token_secret']);
                $other['uid'] = $uid;
                $other['is_sync'] = 0;
                D('login')->add($other);
            }

            //判断是否需要审核
            if ($this->_config['register_audit']) {
                //$this->redirect('w3g/Register/waitForAudit', array('uid' => $uid));
                $re['flag'] = 0;
                $re['msg'] = '注册成功,等待审核';
                echo json_encode($re);
                exit;
            } else {
                if (!$isActive) {
                    $this->_register_model->sendActivationEmail($uid);
                    //$this->redirect('w3g/Register/waitForActivation', array('uid' => $uid));
                    $re['flag'] = 0;
                    $re['msg'] = '注册成功，等待激活';
                    echo json_encode($re);
                    exit;
                } else {
                    $email = $email ? $email : $phone;
                    //自动登录帐号
                    D('Passport')->loginLocal($email, $password);
                    $re['flag'] = 1;
                    $re['msg'] = '注册成功';
                    echo json_encode($re);
                    exit;
                }
            }
        } else {
            // $this->error(L('PUBLIC_REGISTER_FAIL'));			// 注册失败
            // echo '0';
            $re['flag'] = 0;
            $re['msg'] = '注册失败';
            echo json_encode($re);
            exit;
        }
    }

    /**
     * 通过邮箱找回密码
     *
     * @return json
     */
    public function doFindPassByEmail()
    {
        $email = t($_POST['email']);
        $re['flag'] = 0;
        if (!$this->_isEmailString($email)) {
            $re['msg'] = L('PUBLIC_EMAIL_TYPE_WRONG');
            echo json_encode($re);
            exit;
        }

        $user = model('User')->where('`email`="'.$email.'"')->find();
        if (!$user) {
            $re['msg'] = '找不到该邮箱注册信息';
            echo json_encode($re);
            exit;
        }

        $result = $this->_sendPasswordEmail($user);
        if ($result) {
            $re['flag'] = 1;
            $re['msg'] = '邮件发送成功';
            echo json_encode($re);
            exit;
        }
        $re['msg'] = '操作失败，请重试';
        echo json_encode($re);
        exit;
    }

    /**
     * 手机号是否有效.
     *
     * @return json
     */
    public function isPhoneAvailable()
    {
        $mobile = t($_POST['phone']);
        $res = preg_match("/^[1][358]\d{9}$/", $mobile, $matches) !== 0;
        if (!$res) {
            $this->ajaxReturn(null, '无效的手机号', 0);
        }
        $count = model('User')->where('`phone`="'.mysql_escape_string($mobile).'"')->count();
        if ($res && $count == 0) {
            $this->ajaxReturn(null, '此手机号没有注册该站点', 0);
        }

        $this->ajaxReturn(null, '验证通过', 1);
    }

    /**
     * 验证码是否有效.
     *
     * @return json
     */
    /*public function isRegCodeAvailable() {
        $mobile = t($_POST['phone']);
        $code = t($_POST['regCode']);
        $result = model('Captcha')->checkPasswordCode($mobile, $code);
        if ($result) {
            $this->ajaxReturn(null, '验证通过', 1);
        } else {
            $this->ajaxReturn(null, '验证码错误，请检查验证码', 0);
        }
    }*/

    /**
     * 验证验证码是否有效.
     *
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function isRegCodeAvailable()
    {
        $phone = floatval($_POST['phone']);
        $code = intval($_POST['regCode']);
        $sms = model('Sms');
        $sms->CheckCaptcha($phone, $code) or $this->ajaxReturn(null, $sms->getMessage(), 0);
        $this->ajaxReturn(null, '验证通过', 1);
    }

    /**
     * 发送手机验证码
     *
     * @return json
     */
    /*public function sendPasswordCode() {
        $mobile = t($_POST['mobile']);
        $res = preg_match("/^[1][358]\d{9}$/", $mobile, $matches) !== 0;
        if (!$res) {
            $this->ajaxReturn(null, '无效的手机号', 0);
        }
        $count = model('User')->where('`login`="'.mysql_escape_string($mobile).'"')->count();
        if ($res && $count == 0) {
            $this->ajaxReturn(null, '此手机号没有注册该站点', 0);
        }
        $res = model('Captcha')->sendPasswordCode($mobile);
        if ($res) {
            $this->ajaxReturn(null, '发送成功', 1);
        } else {
            $this->ajaxReturn(null, model('Captcha')->getLastError(), 0);
        }
    }*/

    /**
     * 发送手机验证码
     *
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function sendPasswordCode()
    {
        $phone = floatval($_POST['mobile']);

        /* # 检查手机号码格式是否正确 */
        if (!preg_match('/^\+?[0\s]*[\d]{0,4}[\-\s]?\d{4,12}$/', $phone)) {
            $this->ajaxReturn(null, '无效的手机号码', 0);

        /* # 检查手机是否没有被注册 */
        } elseif (model('User')->isChangePhone($phone)) {
            $this->ajaxReturn(null, '该手机号码未注册用户', 0);

        /* # 发送验证码 */
        } elseif (($sms = model('Sms')) and $sms->CheckCaptcha($phone, true)) {
            $this->ajaxReturn(null, '发送成功', 1);
        }

        $this->ajaxReturn(null, $sms->getMessage(), 0);
    }

    /**
     * 通过手机找回密码
     *
     * @return json
     */
    /*public function doFindPasswordByMobile() {
        $mobile = t($_POST['phone']);
        $code = t($_POST['regCode']);
        $result = model('Captcha')->checkPasswordCode($mobile, $code);
        if ($result) {
            $map['login'] = $mobile;
            $user = model('User')->where($map)->find();
            $code = md5($user["uid"].'+'.$user["password"].'+'.rand(1111, 9999));
            //设置旧的code过期
            D('FindPassword')->where('uid='.$user["uid"])->setField('is_used', 1);
            //添加新的修改密码code
            $add['uid'] = $user['uid'];
            $add['email'] = $user['login'];
            $add['code'] = $code;
            $add['is_used'] = 0;
            $result = D('FindPassword')->add($add);
            $data['url'] = U('w3g/Public/resetPassword', array('code'=>$code));
            $this->ajaxReturn($data, '发送成功', 1);
        } else {
            $this->ajaxReturn(null, '发送失败', 0);
        }
    }*/

    /**
     * 通过手机找回密码
     *
     * @author Medz Seven <lovevipdsw@vip.qq.com>
     **/
    public function doFindPasswordByMobile()
    {
        $phone = floatval($_POST['phone']);
        $code = intval($_POST['regCode']);
        $sms = model('Sms');

        /* # 检查验证码 */
        $sms->CheckCaptcha($phone, $code) or $this->ajaxReturn(null, $sms->getMessage(), 0);

        unset($sms);

        /* # 生成找回密码代码 */
        $user = model('User')->where('`phone` = '.$phone)->field('`uid`, `phone`, `password`')->find();
        $code = md5($user['uid'].'+'.$user['password'].'+'.rand(1111, 9999));

        /* # 设置旧的code过期 */
        D('find_password')->where('`uid` = '.$user['uid'])->setField('is_used', 1);

        /* # 添加新的代码 */
        D('find_password')->add(array(
            'uid' => $user['uid'],
            'email' => $user['phone'],
            'code' => $code,
            'is_used' => 0,
        ));
        $this->ajaxReturn(array(
            'url' => U('w3g/Public/resetPassword', array('code' => $code)),
        ), '发送成功', 1);
    }

    /**
     * 重置密码页面.
     */
    public function resetPassword()
    {
        $code = t($_GET['code']);
        $this->_checkResetPasswordCode($code);
        $this->assign('code', $code);
        $this->display();
    }

    /**
     * 签到.
     */
    public function sign_in()
    {
        if (!$this->mid) {
            header('Location:'.U('w3g/Public/login'));
        }
        $this->display();
    }

    /**
     * 执行重置密码操作.
     */
    public function doResetPassword()
    {
        $code = t($_POST['code']);
        $user_info = $this->_checkResetPasswordCode($code);

        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);

        $re['flag'] = 0;
        if (!model('Register')->isValidPassword($password, $repassword)) {
            $re['msg'] = model('Register')->getLastError();
            echo json_encode($re);
            exit;
        }

        $map['uid'] = $user_info['uid'];
        $data['login_salt'] = rand(10000, 99999);
        $data['password'] = md5(md5($password).$data['login_salt']);
        $res = model('User')->where($map)->save($data);
        if ($res) {
            D('find_password')->where('uid='.$user_info['uid'])->setField('is_used', 1);
            model('User')->cleanCache($user_info['uid']);
            //$this->assign('jumpUrl', U('public/Passport/login'));
            //邮件中会包含明文密码，很不安全，改为密文的
            $config['newpass'] = $this->_markPassword($password); //密码加星号处理
            model('Notify')->sendNotify($user_info['uid'], 'password_setok', $config);

            //$this->success(L('PUBLIC_PASSWORD_RESET_SUCCESS'));
            $re['flag'] = 1;
            $re['msg'] = L('PUBLIC_PASSWORD_RESET_SUCCESS');
            $re['jump'] = U('w3g/Public/login');
            echo json_encode($re);
            exit;
        }
            // $this->error(L('PUBLIC_PASSWORD_RESET_FAIL'));
        $re['msg'] = L('PUBLIC_PASSWORD_RESET_FAIL');
        echo json_encode($re);
    }

    /**
     * 检查重置密码的验证码操作.
     */
    private function _checkResetPasswordCode($code)
    {
        $map['code'] = $code;
        $map['is_used'] = 0;
        $uid = D('find_password')->where($map)->getField('uid');
        if (!$uid) {
            $this->assign('jumpUrl', U('public/Passport/findPassword'));
            $this->error('重置密码链接已失效，请重新找回');
        }
        $user_info = model('User')->where("`uid`={$uid}")->find();

        if (!$user_info) {
            $this->redirect = U('w3g/Public/login');
        }

        return $user_info;
    }

    /**
     * 发送找回密码邮件.
     */
    private function _sendPasswordEmail($user)
    {
        if ($user['uid']) {
            //$this->appCssList[] = 'login.css';		// 添加样式
            $code = md5($user['uid'].'+'.$user['password'].'+'.rand(1111, 9999));
            $config['reseturl'] = U('w3g/Public/resetPassword', array('code' => $code));
            //设置旧的code过期
            D('FindPassword')->where('uid='.$user['uid'])->setField('is_used', 1);
            //添加新的修改密码code
            $add['uid'] = $user['uid'];
            $add['email'] = $user['email'];
            $add['code'] = $code;
            $add['is_used'] = 0;
            $result = D('FindPassword')->add($add);
            if ($result) {
                model('Notify')->sendNotify($user['uid'], 'password_reset', $config);

                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * 验证安全邮箱
     * @return void
     */
    public function doCheckEmail()
    {
        $email = t($_POST['email']);
        if ($this->_isEmailString($email)) {
            die(1);
        } else {
            die(0);
        }
    }

    /*
     * 正则匹配，验证邮箱格式
     * @return integer 1=成功 ""=失败
     */
    private function _isEmailString($email)
    {
        return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
    }

    /*
     * 替换密码为星号
     * @return integer 1=成功 ""=失败
     */
    private function _markPassword($str)
    {
        $c = strlen($str) / 2;

        return preg_replace('|(?<=.{'.(ceil($c / 2)).'})(.{'.floor($c).'}).*?|', str_pad('', floor($c), '*'), $str, 1);
    }
}
