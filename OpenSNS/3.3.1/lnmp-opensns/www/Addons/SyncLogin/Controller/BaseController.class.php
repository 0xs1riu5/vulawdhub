<?php

namespace Addons\SyncLogin\Controller;

use Think\Hook;
use User\Api\UserApi;
use Home\Controller\AddonsController;

require_once(dirname(dirname(__FILE__)) . "/ThinkSDK/ThinkOauth.class.php");


class BaseController extends AddonsController
{

    private $access_token = '';
    private $openid = '';
    private $type = '';
    private $token = array();


    public function _initialize()
    {
        $this->getSession();
    }


    private function getSession()
    {
        $session = session('SYNCLOGIN');
        /*        if(empty($session) && (ACTION_NAME != 'callback' && ACTION_NAME != 'login')){
                    $this->error('参数错误');
                }*/
        $this->token = $session['TOKEN'];
        $this->type = $session['TYPE'];
        $this->openid = $session['OPENID'];
        $this->access_token = $session['ACCESS_TOKEN'];
    }

    //登陆地址
    public function login()
    {
        $type = I('get.type');
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns = \ThinkOauth::getInstance($type);
        //跳转到授权页面
        if(is_mobile() && ($type == 'weixin')) {
            $config = D('Weixin/WeixinConfig')->getWeixinConfig();
            $redirect =urlencode(U('Weixin/Index/callback','',true,true));
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$config['APP_ID']}&redirect_uri={$redirect}&response_type=code&scope=snsapi_userinfo&state=opensns#wechat_redirect";
            redirect($url);
            exit;
        } else {
            redirect($sns->getRequestCodeURL());
        }
    }


    /**
     * callback  登陆后回调地址
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function callback()
    {
        $code = I('get.code');
        $type = I('get.type');
        $is_login = is_login();
        $sns = \ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if ($type == 'tencent') {
            $extend = array('openid' => I('get.openid'), 'openkey' => I('get.openkey'));
        }

        $token = $sns->getAccessToken($code, $extend);

        $openid = !empty($token['unionid']) ? $token['unionid'] : $token['openid'];

        if (empty($token)) {
            $this->error('参数错误');
        }
        $session = array('TOKEN' => $token, 'TYPE' => $type, 'OPENID' => $openid, 'ACCESS_TOKEN' => $token['access_token']);

        session('SYNCLOGIN', $session);
        $this->getSession(); // 重新获取session
        if ($is_login) {
            $this->dealIsLogin($is_login);
        } else {
            $addon_config = get_addon_config('SyncLogin');
            $check = $this->checkIsSync(array('type_uid' => $openid, 'type' => $type));
            if ($addon_config['bind'] && !$check) {
                redirect(addons_url('SyncLogin://Base/bind'));
            } else {
                $this->unBind();
            }
        }
    }


    public function unBind()
    {
        $openid = $this->openid;
        $type = $this->type;
        $token = $this->token;
        $access_token = $this->access_token;
        $map = array('type_uid' => $openid, 'type' => $type);
        $user_info = D('Addons://SyncLogin/Info')->$type($token);

        if ($uid = M('sync_login')->field('uid')->where($map)->getField('uid')) {
            $user = UCenterMember()->where(array('id' => $uid))->count();
            if (!($user > 0)) {
                M('sync_login')->where($map)->delete();
                $uid = $this->addData($user_info);
            } else {
                $syncdata ['oauth_token'] = $access_token;
                $syncdata ['oauth_token_secret'] = $openid;
                M('sync_login')->where($map)->save($syncdata);
            }
        } else {
            $uid = $this->addData($user_info);
        }

        $this->loginWithoutpwd($uid);
    }


    private function addData($user_info)
    {
        $ucenterModer = UCenterMember();
        $uid = $ucenterModer->addSyncData();
        D('Member')->addSyncData($uid, $user_info);

        $config =  D('addons')->where(array('name'=>'SyncLogin'))->find();
        $config   =   json_decode($config['config'], true);
        $this->initRoleUser($config['role'], $uid); //初始化角色用户

        // 记录数据到sync_login表中
        $this->addSyncLoginData($uid);
        $this->saveAvatar($user_info['head'], $uid);
        return $uid;
    }


    /**
     * addSyncLoginData  增加sync_login表中数据
     * @param $uid
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function addSyncLoginData($uid)
    {
        $data['uid'] = $uid;
        $data['type_uid'] = $this->openid;
        $data['oauth_token'] = $this->access_token;
        $data['oauth_token_secret'] = $this->openid;
        $data['type'] = $this->type;
        $syncModel = M('sync_login');
        if (!$syncModel->where($data)->count()) {
            $syncModel->add($data);
        }
        return true;
    }


    /**
     * saveAvatar  保存头像到本地
     * @param $url
     * @param $oid
     * @param $uid
     * @param $type
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function saveAvatar($url, $uid)
    {
        $driver = modC('PICTURE_UPLOAD_DRIVER', 'local', 'config');

        if ($driver == 'local') {
            mkdir('./Uploads/Avatar/' . $uid, 0777, true);
            $img = file_get_contents($url);
            $filename = './Uploads/Avatar/' . $uid . '/crop.jpg';
            file_put_contents($filename, $img);
            $data['path'] = '/' . $uid . '/crop.jpg';
        } else {
            $name = get_addon_class($driver);
            $class = new $name();
            $res = $class->uploadRemote($url, 'Uploads/Avatar/' . $uid . '/crop.jpg');
            if ($res !== false) {
                $data['path'] = $res;
            }
        }
        $data['uid'] = $uid;
        $data['create_time'] = time();
        $data['status'] = 1;
        $data['is_temp'] = 0;
        $data['driver'] = $driver;
        M('avatar')->add($data);
    }


    /**
     * loginWithoutpwd  使用uid直接登陆，不使用帐号密码
     * @param $uid
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function loginWithoutpwd($uid)
    {
        if (0 < $uid) { //UC登陆成功
            /* 登陆用户 */
            $Member = D('Member');
            if ($this->doLogin($uid)) { //登陆用户
                $this->success('登陆成功！', session('login_http_referer'));
            } else {
                $this->error($Member->getError());
            }
        }
    }

    public function bind()
    {
        if (!$this->token) {
            $this->error('无效的token');
        }

        $tip = I('get.tip');
        $tip == '' && $tip = 'new';
        $this->assign('tip', $tip);
        if (is_mobile()) {
            redirect(U('Mob/member/bind'));
        } else {
            $this->display(T('Addons://SyncLogin@Base/bind'));
        }

    }


    public function existLogin()
    {

        $aUsername = I('post.username');
        $aPassword = I('post.password');
        $aRemember = I('post.remember');

        if( preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#',$aUsername)){
            $uid = UCenterMember()->login($aUsername, $aPassword,3);//绑定手机登录
        }elseif(preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i',$aUsername)){
             $uid = UCenterMember()->login($aUsername, $aPassword,2);//绑定邮箱登录
        }else{
            $uid = UCenterMember()->login($aUsername, $aPassword,1);//绑定用户名登录
        }

        if (0 < $uid) { //UC登陆成功
            /* 登陆用户 */
            $Member = D('Member');

            if ($this->doLogin($uid, $aRemember == 1)) { //登陆用户
                $this->addSyncLoginData($uid);
                $this->success('登陆成功！', session('login_http_referer'));
            } else {
                $this->error($Member->getError());
            }

        } else { //登陆失败
            switch ($uid) {
                case -1:
                    $error = '用户不存在或被禁用！';
                    break; //系统级别禁用
                case -2:
                    $error = '密码错误！';
                    break;
                default:
                    $error = '未知错误27！';
                    break; // 0-接口参数错误（调试阶段使用）
            }
            $this->error($error);
        }
    }


    public function newAccount()
    {


        $aUsername = I('post.username');
        $aNickname = I('post.nickname');
        $aPassword = I('post.password');

        // 行为限制
        $return = check_action_limit('reg', 'ucenter_member', 1, 1, true);
        if ($return && !$return['state']) {
            $this->error($return['info'], $return['url']);
        }


        $ucenterModel = UCenterMember();
        $uid = $ucenterModel->register($aUsername, $aNickname, $aPassword);
        if (0 < $uid) { //注册成功
            $this->addSyncLoginData($uid);

            $config = M('addons')->where(array('name' => 'SyncLogin'))->find();
            $config = json_decode($config['config'], true);

            $this->initRoleUser($config['role'], $uid); //初始化角色用户

            $uid = $ucenterModel->login($aUsername, $aPassword, 1); //通过账号密码取到uid
            $this->doLogin($uid);
            $this->success('绑定成功！', session('login_http_referer'));
        } else { //注册失败，显示错误信息
            $this->error(A('Ucenter/Member')->showRegError($uid));
        }

    }


    /**
     * 初始化角色用户信息
     * @param $role_id
     * @param $uid
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function initRoleUser($role_id = 0, $uid)
    {
        $role_id = $role_id ? $role_id : 1;
        $memberModel = D('Member');
        $role = M('Role')->where(array('id' => $role_id))->find();
        $user_role = array('uid' => $uid, 'role_id' => $role_id, 'step' => "start");
        if ($role['audit']) { //该角色需要审核
            $user_role['status'] = 2; //未审核
        } else {
            $user_role['status'] = 1;
        }
        $result = M('UserRole')->add($user_role);
        if (!$role['audit']) { //该角色不需要审核
            $memberModel->initUserRoleInfo($role_id, $uid);
        }
        $memberModel->initDefaultShowRole($role_id, $uid);

        return $result;
    }

    protected function dealIsLogin($uid = 0)
    {
        $session = session('SYNCLOGIN');
        $openid = $session['OPENID'];
        $type = $session['TYPE'];
        if ($this->checkIsSync(array('type_uid' => $openid, 'type' => $type))) {
            $this->error('该帐号已经被绑定！');
        }
        $this->addSyncLoginData($uid);
        $this->success('绑定成功！', U('ucenter/config/other'));
    }


    private function checkIsSync($map = array())
    {
        if (M('sync_login')->where($map)->count()) {
            return true;
        } else {
            return false;
        }
    }


    private function doLogin($uid, $remember = false)
    {
        if (is_mobile()) {
            $rs = D('Mob/Member')->mobileLogin($uid, $remember); //登陆
        } else {
            $rs = D('Member')->login($uid, $remember); //登陆
        }
        if ($rs) {
            session('SYNCLOGIN', null);
        }
        return $rs;
    }

}