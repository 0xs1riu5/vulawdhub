<?php

class SettingAction extends BaseAction
{
    // 个人设置首页
    public function index()
    {
        $this->display();
    }

    //基本信息设置
    public function base()
    {
        $this->need_login();
        if ($_POST['ajaxSubmit']) {
            D('user')->where($map)->setField('sex', intval($_POST['sex']));
            //清空用户数据缓存
            model('User')->cleanCache($this->mid);
            $re['status'] = 1;
            $re['info'] = '保存成功';
            echo json_encode($re);
            exit;
        }
        //获取我的个人信息
        $user = getUserInfo($this->mid);
        $this->assign('user', $user);
        $this->display();
    }

    //设置用户昵称
    public function setName()
    {
        $this->need_login();
        if ($_POST['uname']) {
            $map['uid'] = $this->mid;
            D('user')->where($map)->setField('uname', t($_POST['uname']));
            //清空用户数据缓存
            model('User')->cleanCache($this->mid);
            $re['status'] = 1;
            $re['info'] = '保存成功';
            echo json_encode($re);
            exit;
        }
        $user = getUserInfo($this->mid);
        $this->assign('user_info', $user);
        $this->display();
    }

    //设置用户地区
    public function setArea()
    {
        $this->need_login();
        if ($_POST['ajaxSubmit']) {
            $map['uid'] = $this->mid;
            $pid = intval($_POST['province']);
            $cid = intval($_POST['city']);
            $aid = intval($_POST['area']);
            D('user')->where($map)->setField('province', $pid);
            D('user')->where($map)->setField('city', $cid);
            D('user')->where($map)->setField('area', $aid);
            $province = model('Area')->getAreaById($pid);
            $city = model('Area')->getAreaById($cid);
            $area = model('Area')->getAreaById($aid);
            $current_name = $province['title'].'  '.$city['title'].'  '.$area['title'];
            D('user')->where($map)->setField('location', $current_name);
            //清空用户数据缓存
            model('User')->cleanCache($this->mid);
            $re['status'] = 1;
            $re['info'] = '保存成功';
            echo json_encode($re);
            exit;
        }
        $user = getUserInfo($this->mid);
        $this->assign('user_info', $user);
        $this->display();
    }

    //设置个人简介
    public function setIntro()
    {
        $this->need_login();
        if ($_POST['ajaxSubmit']) {
            $map['uid'] = $this->mid;
            $_POST['intro'] = $_POST['intro'] ? formatEmoji(true, $_POST['intro']) : '';
            D('user')->where($map)->setField('intro', t($_POST['intro']));
            //清空用户数据缓存
            model('User')->cleanCache($this->mid);
            $re['status'] = 1;
            $re['info'] = '保存成功';
            echo json_encode($re);
            exit;
        }
        $user = getUserInfo($this->mid);
        $this->assign('user_info', $user);
        $this->display();
    }

    /**
     * 隐私设置页面.
     */
    public function privacy()
    {
        $user_privacy = D('UserPrivacy')->getUserSet($this->mid);
        $this->assign('user_privacy', $user_privacy);
        //dump($user_privacy);exit;
        $user = model('User')->getUserInfo($this->mid);
        $this->setTitle(L('PUBLIC_PRIVACY'));
        $this->setKeywords(L('PUBLIC_PRIVACY'));
        // 获取用户职业信息
        // $userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
        // $userCateArray = array();
        // if(!empty($userCategory)) {
        // 	foreach($userCategory as $value) {
        // 		$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
        // 	}
        // }
        //$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
        //$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
        $this->display();
    }

    /**
     * 保存登录用户隐私设置操作.
     *
     * @return json 返回操作后的JSON信息数据
     */
    public function doSavePrivacy()
    {
        //dump($_POST);exit;
        $res = model('UserPrivacy')->dosave($this->mid, $_POST);
        $this->ajaxReturn(null, model('UserPrivacy')->getError(), $res);
    }

    /**
     * 帐号安全设置页面.
     */
    public function security()
    {
        $this->need_login();
        $user = model('User')->getUserInfo($this->mid);
        $mobile = $user['login'];
        $email = $user['email'];
        $matchMobile = preg_match('/^[1][358]\d{9}$/', $mobile);
        $bindingMobile = ($matchMobile === 1) ? true : false;
        $this->assign('bindingMobile', $bindingMobile);
        $this->assign('mobile', $mobile);

        $matchEmail = preg_match('/[_a-zA-Z\d\-\.]+(@[_a-zA-Z\d\-\.]+\.[_a-zA-Z\d\-]+)+$/i', $email);
        $bindingEmail = ($matchEmail === 1) ? true : false;
        $this->assign('bindingEmail', $bindingEmail);
        $this->assign('email', $email);

        $this->setTitle(L('PUBLIC_ACCOUNT_SECURITY'));
        $this->setKeywords(L('PUBLIC_ACCOUNT_SECURITY'));
        // 获取用户职业信息
        // $userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
        // $userCateArray = array();
        // if(!empty($userCategory)) {
        // 	foreach($userCategory as $value) {
        // 		$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
        // 	}
        // }
        // $user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
        //$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
        $this->display();
    }

    /**
     * 修改登录用户帐号密码操作.
     *
     * @return json 返回操作后的JSON信息数据
     */
    public function doModifyPassword()
    {
        $_POST['oldpassword'] = t($_POST['oldpassword']);
        $_POST['password'] = t($_POST['password']);
        $_POST['repassword'] = t($_POST['repassword']);
        // 验证信息
        if ($_POST['oldpassword'] === '') {
            // $this->error('请填写原始密码');
            $this->ajaxReturn(null, '请填写原始密码', 0);
        }
        if ($_POST['password'] === '') {
            // $this->error('请填写新密码');
            $this->ajaxReturn(null, '请填写新密码', 0);
        }
        if ($_POST['repassword'] === '') {
            // $this->error('请填写确认密码');
            $this->ajaxReturn(null, '请填写确认密码', 0);
        }
        if ($_POST['password'] != $_POST['repassword']) {
            // $this->error(L('PUBLIC_PASSWORD_UNSIMILAR'));			// 新密码与确认密码不一致
            $this->ajaxReturn(null, L('PUBLIC_PASSWORD_UNSIMILAR'), 0);
        }
        if (strlen($_POST['password']) < 6) {
            //$this->error('密码太短了，最少6位');
            $this->ajaxReturn(null, '密码太短了，最少6位', 0);
        }
        if (strlen($_POST['password']) > 15) {
            // $this->error('密码太长了，最多15位');
            $this->ajaxReturn(null, '密码太长了，最多15位', 0);
        }
        if ($_POST['password'] == $_POST['oldpassword']) {
            //$this->error();				// 新密码与旧密码相同
            $this->ajaxReturn(null, L('PUBLIC_PASSWORD_SAME'), 0);
        }

        $user_model = model('User');
        $map['uid'] = $this->mid;
        $user_info = $user_model->where($map)->find();

        if ($user_info['password'] == $user_model->encryptPassword($_POST['oldpassword'], $user_info['login_salt'])) {
            $data['login_salt'] = rand(11111, 99999);
            $data['password'] = $user_model->encryptPassword($_POST['password'], $data['login_salt']);
            $res = $user_model->where("`uid`={$this->mid}")->save($data);
            $info = $res ? L('PUBLIC_PASSWORD_MODIFY_SUCCESS') : L('PUBLIC_PASSWORD_MODIFY_FAIL');            // 密码修改成功，密码修改失败
        } else {
            $info = L('PUBLIC_ORIGINAL_PASSWORD_ERROR');            // 原始密码错误
        }

        return $this->ajaxReturn(null, $info, $res);
    }

    //绑定手机
    public function doBindingMobile()
    {
        $mobile = t($_POST['mobile']);
        if (!model('Register')->isValidPhone($mobile)) {
            $this->ajaxReturn(null, model('Register')->getLastError(), 0);
        }
        $code = t($_POST['mobile_code']);
        if (!model('Captcha')->checkLoginCode($mobile, $code)) {
            $this->ajaxReturn(null, '验证码错误，请检查验证码', 0);
        }
        $map['uid'] = $this->mid;
        $result = model('User')->where($map)->setField('login', $mobile);
        if ($result) {
            model('User')->cleanCache($this->mid);
            $data['mobile'] = hideContactInformation($mobile, 'mobile');
            $data['type'] = 'mobile';
            $this->ajaxReturn($data, '设置成功', 1);
        } else {
            $this->ajaxReturn(null, '设置失败', 0);
        }
    }

    public function getCaptcha()
    {
        $type = t($_POST['type']);
        if (!in_array($type, array('mobile', 'email'))) {
            $this->ajaxReturn(null, '参数错误', 0);
        }
        $msg = '';
        $result = false;
        $model = model('Captcha');
        switch ($type) {
            case 'mobile':
                $mobile = t($_POST['mobile']);
                $result = $model->sendLoginCode($mobile);
                break;
            case 'email':
                $email = t($_POST['email']);
                $result = $model->sendEmailCode($email);
                $msg = $model->getError();
                if ($result) {
                    $map['communication'] = $email;
                    $map['type'] = 5;
                    $rand = $model->where($map)->order('captcha_id DESC')->getField('rand');
                    $config['uname'] = getUserName($this->mid);
                    $config['rand'] = $rand;
                    $config['date'] = date('Y-m-d', time());
//					model('Notify')->sendNotify($this->mid, 'email_verification', $config);
                    model('Notify')->sendNotifyChangeEmail($this->mid, 'email_verification', $config, $email);
                }
                break;
        }

        if ($result) {
            empty($msg) && $msg = '设置成功';
            $this->ajaxReturn(null, $msg, 1);
        } else {
            empty($msg) && $msg = '设置失败';
            $this->ajaxReturn(null, $msg, 0);
        }
    }

    //绑定邮箱
    public function doBindingEmail()
    {
        $email = t($_POST['email']);
        if (!model('Register')->isValidEmail($email)) {
            $this->ajaxReturn(null, model('Register')->getLastError(), 0);
        }
        $code = t($_POST['email_code']);
        if (!model('Captcha')->checkEmailCode($email, $code)) {
            $this->ajaxReturn(null, '验证码错误，请检查验证码', 0);
        }
        $map['uid'] = $this->mid;
        $result = model('User')->where($map)->setField('email', $email);
        if ($result) {
            model('User')->cleanCache($this->mid);
            $data['email'] = hideContactInformation($mobile, 'email');
            $data['type'] = 'email';
            $this->ajaxReturn(null, '设置成功', 1);
        } else {
            $this->ajaxReturn(null, '设置失败', 0);
        }
    }

    public function avatar()
    {
        $this->need_login();
        $user = model('User')->getUserInfo($this->mid);
        $this->assign('step', $_GET['step']);
        $this->assign('status', intval($_GET['status']));
        $this->assign('msg', $_GET['msg']);
        $this->assign('picwidth', intval($_GET['picwidth']));
        $this->assign('picheight', intval($_GET['picheight']));
        $this->assign('picurl', $_GET['picurl']);
        $this->assign('fullpicurl', $_GET['fullpicurl']);
        $this->assign('user', $user);
        $this->display();
    }

    /**
     * 保存登录用户的头像设置操作.
     *
     * @return json 返回操作后的JSON信息数据
     */
    public function doSaveAvatar()
    {
        $dAvatar = model('Avatar');
        $dAvatar->init($this->mid);            // 初始化Model用户id
        // 安全过滤
        $step = t($_GET['step']);
        $result = $dAvatar->upload(true);
        $arr = array();
        $arr = $result['data'];
        $arr['step'] = $step;
        $arr['status'] = $result['status'];
        $arr['msg'] = $result['msg'];
        header('Location:'.U('w3g/Setting/avatar', $arr));
        // $this->ajaxReturn($result['data'], $result['info'], $result['status']);
    }

    public function doCutAvatar()
    {
        $dAvatar = model('Avatar');
        $dAvatar->init($this->mid);            // 初始化Model用户id
        $step = t($_GET['step']);
        if ('save' == $step) {
            $result = $dAvatar->dosave(false, true);
            if ($result['status'] == 0) {
                $arr = array();
                $arr['status'] = 0;
                $arr['msg'] = $result['info'];
                header('Location:'.U('w3g/Setting/avatar', $arr));
            } else {
                model('User')->cleanCache($this->mid);
                $user_feeds = model('Feed')->where('uid='.$this->mid)->field('feed_id')->findAll();
                if ($user_feeds) {
                    $feed_ids = getSubByKey($user_feeds, 'feed_id');
                    model('Feed')->cleanCache($feed_ids, $this->mid);
                }
                header('Location:'.U('w3g/Setting'));
            }
        }
    }
}
