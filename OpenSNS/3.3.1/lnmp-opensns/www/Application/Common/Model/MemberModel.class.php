<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Common\Model;

use Think\Model;
use User\Api\UserApi;


/**
 * 文档基础模型
 */
class MemberModel extends Model
{
    /* 用户模型自动完成 */
    protected $_auto = array(
        array('login', 0, self::MODEL_INSERT),
        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('reg_time', NOW_TIME, self::MODEL_INSERT),
        array('last_login_ip', 0, self::MODEL_INSERT),
        array('last_login_time', 0, self::MODEL_INSERT),
        array('update_time', NOW_TIME),
        array('status', 1, self::MODEL_INSERT),
        array('score1', 0, self::MODEL_INSERT),
        array('score2', 0, self::MODEL_INSERT),
        array('score3', 0, self::MODEL_INSERT),
        array('score4', 0, self::MODEL_INSERT),
        array('pos_province', 0, self::MODEL_INSERT),
        array('pos_city', 0, self::MODEL_INSERT),
        array('pos_district', 0, self::MODEL_INSERT),
        array('pos_community', 0, self::MODEL_INSERT),
    );

    protected $_validate = array(
        array('signature', '0,100', -1, self::EXISTS_VALIDATE, 'length'),
        /* 验证昵称 */
        array('nickname', 'checkNickname', -33, self::EXISTS_VALIDATE, 'callback'), //昵称长度不合法
        array('nickname', 'checkDenyNickname', -31, self::EXISTS_VALIDATE, 'callback'), //昵称禁止注册
        array('nickname', 'checkNickname', -32, self::EXISTS_VALIDATE, 'callback'),
        array('nickname', '', -30, self::EXISTS_VALIDATE, 'unique'), //昵称被占用

    );

    protected $insertField = 'nickname,sex,birthday,qq,signature'; //新增数据时允许操作的字段
    protected $updateField = 'nickname,sex,birthday,qq,signature,last_login_ip,login,update_time,last_login_role,show_role,status,tox_money,score,pos_province,pos_city,pos_district,pos_community'; //编辑数据时允许操作的字段

    /**
     * 检测用户名是不是被禁止注册
     * @param  string $nickname 昵称
     * @return boolean          ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyNickname($nickname)
    {
        $denyName = M("Config")->where(array('name' => 'USER_NAME_BAOLIU'))->getField('value');
        if ($denyName != '') {
            $denyName = explode(',', $denyName);
            foreach ($denyName as $val) {
                if (!is_bool(strpos($nickname, $val))) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function checkNickname($nickname)
    {
        //如果用户名中有空格，不允许注册
        if (strpos($nickname, ' ') !== false) {
            return false;
        }
        preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/', $nickname, $result);

        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * 验证昵称长度
     * @param $nickname
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    protected function checkNicknameLength($nickname)
    {
        $length = mb_strlen($nickname, 'utf-8'); // 当前数据长度
        if ($length < modC('NICKNAME_MIN_LENGTH',2,'USERCONFIG') || $length > modC('NICKNAME_MAX_LENGTH',32,'USERCONFIG')) {
            return false;
        }
        return true;
    }

    public function registerMember($nickname = '')
    {
        /* 在当前应用中注册用户 */
        if ($user = $this->create(array('nickname' => $nickname, 'status' => 1))) {
            $uid = $this->add($user);
            if (!$uid) {
                $this->error = L('_THE_FOREGROUND_USER_REGISTRATION_FAILED_PLEASE_TRY_AGAIN_WITH_EXCLAMATION_');
                return false;
            }
            $this->initFollow($uid);
            return $uid;
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }

    }


    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @param bool $remember
     * @param int $role_id 有值代表强制登录这个角色
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid, $remember = false, $role_id = 0)
    {
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($uid);
        if ($role_id != 0) {
            $user['last_login_role'] = $role_id;
        } else {
                if (!intval($user['last_login_role'])) {
                    $user['last_login_role'] = $user['show_role'];
                }
        }
        session('temp_login_uid', $uid);
        session('temp_login_role_id', $user['last_login_role']);

        if ($user['status'] == 3 /*判断是否激活*/) {
            header('Content-Type:application/json; charset=utf-8');
            $data['status'] = 1;
            $data['url'] = U('Ucenter/Member/activate');

            if (IS_AJAX) {
                exit(json_encode($data));
            } else {
                redirect($data['url']);
            }
            return false;
        }

        if (1 != $user['status']) {
            $this->error = L('_USERS_ARE_NOT_ACTIVATED_OR_DISABLED_WITH_EXCLAMATION_'); //应用级别禁用
            return false;
        }

        $step = D('UserRole')->where(array('uid' => $uid, 'role_id' => $user['last_login_role']))->getField('step');
        if (!empty($step) && $step != 'finish') {
            header('Content-Type:application/json; charset=utf-8');
            $data['status'] = 1;
            //执行步骤在start的时候执行下一步，否则执行此步骤
            $go = $step == 'start' ? get_next_step($step) : check_step($step);
            $data['url'] = U('Ucenter/Member/step', array('step' => $go));
            if (IS_AJAX) {
                exit(json_encode($data));
            } else {
                redirect($data['url']);
            }
            return false;
        }
        /* 登录用户 */
        $this->autoLogin($user, $remember);

        session('temp_login_uid', null);
        session('temp_login_role_id', null);
        //记录行为
        action_log('user_login', 'member', $uid, $uid);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout()
    {
        session('_AUTH_LIST_' . get_uid() . '1', null);
        session('_AUTH_LIST_' . get_uid() . '2', null);
        session('_AUTH_LIST_' . get_uid() . 'in,1,2', null);
        session('user_auth', null);
        session('user_auth_sign', null);
        cookie('OX_LOGGED_USER', NULL);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user, $remember = false, $role_id = 0)
    {

        /* 更新登录信息 */
        $data = array(
            'uid' => $user['uid'],
            'login' => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip' => get_client_ip(1),
            'last_login_role' => $user['last_login_role'],
            'session_id'=>session_id(),
        );
        $this->save($data);
        //判断角色用户是否审核
        $map['uid'] = $user['uid'];
        $map['role_id'] = $user['last_login_role'];
        $audit = D('UserRole')->where($map)->getField('status');
        //判断角色用户是否审核 end

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid' => $user['uid'],
            'username' => get_username($user['uid']),
            'last_login_time' => $user['last_login_time'],
            'role_id' => $user['last_login_role'],
            'audit' => $audit,
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
        if ($remember) {
            $user1 = D('user_token')->where('uid=' . $user['uid'])->find();
            $token = $user1['token'];
            if ($user1 == null) {
                $token = build_auth_key();
                $data['token'] = $token;
                $data['time'] = time();
                $data['uid'] = $user['uid'];
                D('user_token')->add($data);
            }
        }

        if (!$this->getCookieUid() && $remember) {
            $expire = 3600 * 24 * 7;
            cookie('OX_LOGGED_USER', $this->jiami($this->change() . ".{$user['uid']}.{$token}"), $expire);
        }
    }

    public function need_login()
    {
        if (!is_login()) {
            if ($uid = $this->getCookieUid()) {
                $this->login($uid);
                return true;
            }
        }

    }

    public function getCookieUid()
    {

        static $cookie_uid = null;
        if (isset($cookie_uid) && $cookie_uid !== null) {
            return $cookie_uid;
        }
        $cookie = cookie('OX_LOGGED_USER');
        $cookie = explode(".", $this->jiemi($cookie));
        $map['uid'] = $cookie[1];
        $user = D('user_token')->where($map)->find();
        $cookie_uid = ($cookie[0] != $this->change()) || ($cookie[2] != $user['token']) ? false : $cookie[1];
        $cookie_uid = $user['time'] - time() >= 3600 * 24 * 7 ? false : $cookie_uid;
        return $cookie_uid;
    }


    /**
     * 加密函数
     * @param string $txt 需加密的字符串
     * @param string $key 加密密钥，默认读取SECURE_CODE配置
     * @return string 加密后的字符串
     */
    private function jiami($txt, $key = null)
    {
        empty($key) && $key = $this->change();

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
        $nh = rand(0, 64);
        $ch = $chars[$nh];
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh + strpos($chars, $txt [$i]) + ord($mdKey[$k++])) % 64;
            $tmp .= $chars[$j];
        }
        return $ch . $tmp;
    }

    /**
     * 解密函数
     * @param string $txt 待解密的字符串
     * @param string $key 解密密钥，默认读取SECURE_CODE配置
     * @return string 解密后的字符串
     */
    private function jiemi($txt, $key = null)
    {
        empty($key) && $key = $this->change();

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
        $ch = $txt[0];
        $nh = strpos($chars, $ch);
        $mdKey = md5($key . $ch);
        $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
        $txt = substr($txt, 1);
        $tmp = '';
        $i = 0;
        $j = 0;
        $k = 0;
        for ($i = 0; $i < strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
            while ($j < 0) {
                $j += 64;
            }
            $tmp .= $chars[$j];
        }

        return base64_decode($tmp);
    }

    private function change()
    {
        preg_match_all('/\w/', C('DATA_AUTH_KEY'), $sss);
        $str1 = '';
        foreach ($sss[0] as $v) {
            $str1 .= $v;
        }
        return $str1;
    }


    /**
     * 设置角色用户默认基本信息
     * @param $role_id
     * @param $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function initUserRoleInfo($role_id, $uid)
    {
        $roleModel = D('Role');
        $roleConfigModel = D('RoleConfig');
        $authGroupAccessModel = D('AuthGroupAccess');
        D('UserRole')->where(array('role_id' => $role_id, 'uid' => $uid))->setField('init', 1);
        //默认权限组设置
        $role = $roleModel->where(array('id' => $role_id))->find();
        if ($role['user_groups'] != '') {
            $role = explode(',', $role['user_groups']);

            //查询已拥有权限组
            $have_user_group_ids = $authGroupAccessModel->where(array('uid' => $uid))->select();
            $have_user_group_ids = array_column($have_user_group_ids, 'group_id');
            //查询已拥有权限组 end

            $authGroupAccess['uid'] = $uid;
            $authGroupAccess_list = array();
            foreach ($role as $val) {
                if ($val != '' && !in_array($val, $have_user_group_ids)) { //去除已拥有权限组
                    $authGroupAccess['group_id'] = $val;
                    $authGroupAccess_list[] = $authGroupAccess;
                }
            }
            unset($val);
            $authGroupAccessModel->addAll($authGroupAccess_list);
        }
        //默认权限组设置 end

        $map['role_id'] = $role_id;
        $map['name'] = array('in', array('score', 'rank'));
        $config = $roleConfigModel->where($map)->select();
        $config = array_combine(array_column($config, 'name'), $config);


        //默认积分设置
        if (isset($config['score']['value'])) {
            $value = json_decode($config['score']['value'], true);
            $data = $this->getUserScore($role_id, $uid, $value);
            $user = $this->where(array('uid' => $uid))->find();
            foreach ($data as $key => $val) {
                if ($val > 0) {
                    if (isset($user[$key])) {
                        $this->where(array('uid' => $uid))->setInc($key, $val);
                    } else {
                        $this->where(array('uid' => $uid))->setField($key, $val);
                    }
                }
            }
            unset($val);
        }
        //默认积分设置 end

        //默认头衔设置
        if (isset($config['rank']['value']) && $config['rank']['value'] != '') {
            $ranks = explode(',', $config['rank']['value']);
            if (count($ranks)) {
                //查询已拥有头衔
                $rankUserModel = D('RankUser');
                $have_rank_ids = $rankUserModel->where(array('uid' => $uid))->select();
                $have_rank_ids = array_column($have_rank_ids, 'rank_id');
                //查询已拥有头衔 end

                $reason = json_decode($config['rank']['data'], true);
                $rank_user['uid'] = $uid;
                $rank_user['create_time'] = time();
                $rank_user['status'] = 1;
                $rank_user['is_show'] = 1;
                $rank_user['reason'] = $reason['reason'];
                $rank_user_list = array();
                foreach ($ranks as $val) {
                    if ($val != '' && !in_array($val, $have_rank_ids)) { //去除已拥有头衔
                        $rank_user['rank_id'] = $val;
                        $rank_user_list[] = $rank_user;
                    }
                }
                unset($val);
                $rankUserModel->addAll($rank_user_list);
            }
        }
        //默认头衔设置 end
    }

    //默认显示哪一个角色的个人主页设置
    public function initDefaultShowRole($role_id, $uid)
    {
        $userRoleModel = D('UserRole');

        $roles = $userRoleModel->where(array('uid' => $uid, 'status' => 1, 'role_id' => array('neq', $role_id)))->select();
        if (!count($roles)) {
            $data['show_role'] = $role_id;
            //执行member表默认值设置
            $this->where(array('uid' => $uid))->save($data);
        }
    }

    //默认显示哪一个角色的个人主页设置 end

    /**
     * 获取用户初始化后积分值
     * @param $role_id 当前初始化角色
     * @param $uid 初始化用户
     * @param $value 初始化角色积分配置值
     * @return array
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function getUserScore($role_id, $uid, $value)
    {
        $roleConfigModel = D('RoleConfig');
        $userRoleModel = D('UserRole');

        $map['role_id'] = array('neq', $role_id);
        $map['uid'] = $uid;
        $map['init'] = 1;
        $role_list = $userRoleModel->where($map)->select();
        $role_ids = array_column($role_list, 'role_id');
        $map_config['role_id'] = array('in', $role_ids);
        $map_config['name'] = 'score';
        $config_list = $roleConfigModel->where($map_config)->field('value')->select();
        $change = array();
        foreach ($config_list as &$val) {
            $val = json_decode($val['value'], true);
        }
        unset($val);
        unset($config_list[0]['score1']);
        foreach ($value as $key => $val) {
            $config_list = list_sort_by($config_list, $key, 'desc');
            if ($val > $config_list[0][$key]) {
                $change[$key] = $val - $config_list[0][$key];
            } else {
                $change[$key] = 0;
            }
        }
        return $change;
    }

    private function initFollow($uid = 0)
    {
        if ($uid != 0) {
            $followModel = D('Common/Follow');
            $follow = modC('NEW_USER_FOLLOW', '', 'USERCONFIG');
            $fans = modC('NEW_USER_FANS', '', 'USERCONFIG');
            $friends = modC('NEW_USER_FRIENDS', '', 'USERCONFIG');
            $allFollow = $follow . "," . $friends;
            $allFans = $fans . "," . $friends;

            if($allFollow != '') {
                $allFollow = explode(',', $allFollow);
                $allFollow = array_unique($allFollow);
                foreach($allFollow as $val) {
                    if(query_user('uid', $val)) {
                        $followModel->addFollow($uid, $val);
                        D('Member')->where(array('uid' => $val))->setInc('fans', 1);
                    }
                }
            }
            if($allFans != '') {
                $allFans = explode(',', $allFans);
                $allFans = array_unique($allFans);
                foreach($allFans as $val) {
                    if(query_user('uid', $val)) {
                        $followModel->addFollow($val, $uid);
                        D('Member')->where(array('uid' => $uid))->setInc('fans', 1);
                    }
                }
            }

        }
        return true;
    }


    /**
     * addSyncData
     * @param $uid
     * @param $info
     * @return mixed
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function addSyncData($uid, $info)
    {
        //去除特殊字符。
        $data['nickname'] = preg_replace('/[^A-Za-z0-9_\x80-\xff\s\']/', '', $info['nick']);
        // 截取字数
        $data['nickname'] = mb_substr($data['nickname'], 0, 32, 'utf-8');
        // 为空则随机生成
        if (empty($data['nickname'])) {
            $data['nickname'] = $this->rand_nickname();
        } else {
            if ($this->where(array('nickname' => $data['nickname']))->select()) {
                $data['nickname'] .= '_' . $uid;
            }
        }
        $data['sex'] = $info['sex'];
        $data = $this->validate(
            array('signature', '0,100', -1, self::EXISTS_VALIDATE, 'length'),
            /* 验证昵称 */
            array('nickname', 'checkDenyNickname', -31, self::EXISTS_VALIDATE, 'callback'), //昵称禁止注册
            array('nickname', 'checkNickname', -32, self::EXISTS_VALIDATE, 'callback'),
            array('nickname', '', -30, self::EXISTS_VALIDATE, 'unique'))->create($data);
        $data['uid'] = $uid;
        $res = $this->add($data);
        return $res;
    }

    private function rand_nickname()
    {
        $nickname = create_rand(4);
        if ($this->where(array('nickname' => $nickname))->select()) {
            $this->rand_nickname();
        } else {
            return $nickname;
        }
    }


}
