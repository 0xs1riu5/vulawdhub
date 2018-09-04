<?php

namespace App\Models\v2;
use App\Models\BaseModel;
use App\Helper\Token;
use \DB;
use Log;
use Cache;
use Illuminate\Support\Facades\Mail;

use App\Services\Shopex\Sms;
use App\Services\Oauth\Wechat;

class Member extends BaseModel {

    const VENDOR_WEIXIN = 1;
    const VENDOR_WEIBO  = 2;
    const VENDOR_QQ     = 3;
    const VENDOR_TAOBAO = 4;
    const VENDOR_WXA    = 5;    //微信小程序

    const GENDER_SECRET = 0;
    const GENDER_MALE   = 1;
    const GENDER_FEMALE = 2;


    /* 帐号变动类型 */
    const ACT_SAVING               =  0;     // 帐户冲值
    const ACT_DRAWING              =  1;     // 帐户提款
    const ACT_ADJUSTING            =  2;     // 调节帐户
    const ACT_OTHER                = 99;     // 其他类型

    protected $connection = 'shop';
    protected $table      = 'users';
    protected $primaryKey = 'user_id';
    public    $timestamps = false;

    protected $guarded = [];
    protected $appends = ['id','age','rank','gender','username','nickname','mobile','avatar','mobile_binded','joined_at','is_auth', 'is_completed'];
    protected $visible = ['id','age','rank','gender','username','nickname','mobile','avatar','mobile_binded','joined_at','is_auth', 'is_completed'];

    public static function login(array $attributes)
    {
        extract($attributes);

        if ($model = self::validatePassword($username, $password)) {
            $token = Token::encode(['uid' => $model->user_id]);

            UserRegStatus::toUpdate($model->user_id, 1);

            return self::formatBody(['token' => $token, 'user' => $model->toArray()]);
        }

        return self::formatError(self::BAD_REQUEST, trans('message.member.failed'));
    }

    public static function logAccountChange($user_id, $user_money = 0, $frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type = self::ACT_OTHER)
    {
        /* 插入帐户变动记录 */
        $account_log = array(
            'user_id'       => $user_id,
            'user_money'    => $user_money,
            'frozen_money'  => $frozen_money,
            'rank_points'   => $rank_points,
            'pay_points'    => $pay_points,
            'change_time'   => time(),
            'change_desc'   => $change_desc,
            'change_type'   => $change_type
        );
        AccountLog::insert($account_log);

        /* 更新用户信息 */
        $user = self::find($user_id);
        $user->user_money   += $user_money;
        $user->frozen_money += $frozen_money;
        $user->rank_points  += $rank_points;
        $user->pay_points   += $pay_points;
        $user->save();
    }

    public static function createMember(array $attributes)
    {
        extract($attributes);

        if (!Member::where('user_name', $username)->orWhere('email', $email)->first())
        {
            $data = [
                'user_name' => $username,
                'email' => $email,
                'password' => self::setPassword($password),
                'reg_time' => time(),
                'user_rank' => 1,
                'sex' => 0,
                'alias' => $username,
                'mobile_phone' => '',
                'rank_points' => 0
            ];

            if ($model = self::create($data))
            {
                // 邀请注册
                if (isset($invite_code)) {
                    $up_uid = $invite_code;

                    if (AffiliateLog::checkOpen() == 1) {

                        $affiliate = AffiliateLog::getAffiliateConfig();
                        $affiliate['config']['level_register_all'] = intval($affiliate['config']['level_register_all']);
                        $affiliate['config']['level_register_up'] = intval($affiliate['config']['level_register_up']);
                        $_LANG = trans('message.member.account');
                        if ($up_uid) {
                            if (!empty($affiliate['config']['level_register_all']))
                            {
                                if (!empty($affiliate['config']['level_register_up']))
                                {
                                    if ($rank_points = self::find($up_uid)) {
                                        $rank_points = $rank_points->rank_points;
                                    
                                        if ($rank_points + $affiliate['config']['level_register_all'] <= $affiliate['config']['level_register_up'])
                                        {
                                            self::logAccountChange($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, sprintf($_LANG, $model->user_id, $model->user_name));
                                        }
                                    }
                                    
                                }
                                else
                                {
                                    self::logAccountChange($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, $_LANG);
                                }
                            }

                            //设置推荐人
                            $model->parent_id = $up_uid;
                            $model->save();
                        }
                    }
                }
		
                if (isset($device_id) && $device_id) {
                    Device::toUpdateOrCreate($model->user_id, $attributes);
                }

                UserRegStatus::toUpdate($model->user_id, 0);

                $token = Token::encode(['uid' => $model->user_id]);
                return self::formatBody(['token' => $token, 'user' => $model->toArray()]);

            }   else {

                return self::formatError(self::UNKNOWN_ERROR);

            }

        } else {

            return self::formatError(self::BAD_REQUEST, trans('message.member.exists'));

        }
    }

    public static function createMemberByMobile(array $attributes)
    {
        extract($attributes);

        if (!Member::where('user_name', $mobile)->first())
        {
            if (!self::verifyCode($mobile, $code)) {
                return self::formatError(self::BAD_REQUEST, trans('message.member.mobile.code.error'));
            }

            $data = [
                'user_name' => $mobile,
                'email' => "{$mobile}@mobile.user",
                'password' => self::setPassword($password),
                'reg_time' => time(),
                'user_rank' => 1,
                'sex' => 0,
                'alias' => $mobile,
                'mobile_phone' => '',
                'rank_points' => 0
            ];

            if ($model = self::create($data))
            {
                // 邀请注册
                if (isset($invite_code)) {
                    $up_uid = $invite_code;

                    if (AffiliateLog::checkOpen() == 1) {

                        $affiliate = AffiliateLog::getAffiliateConfig();
                        $affiliate['config']['level_register_all'] = intval($affiliate['config']['level_register_all']);
                        $affiliate['config']['level_register_up'] = intval($affiliate['config']['level_register_up']);
                        $_LANG = trans('message.member.account');
                        if ($up_uid) {
                            if (!empty($affiliate['config']['level_register_all']))
                            {
                                if (!empty($affiliate['config']['level_register_up']))
                                {
                                    if ($rank_points = self::find($up_uid)) {
                                        $rank_points = $rank_points->rank_points;
                                    
                                        if ($rank_points + $affiliate['config']['level_register_all'] <= $affiliate['config']['level_register_up'])
                                        {
                                            self::logAccountChange($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, sprintf($_LANG, $model->user_id, $model->user_name));
                                        }
                                    }
                                    
                                }
                                else
                                {
                                    self::logAccountChange($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, $_LANG);
                                }
                            }

                            //设置推荐人
                            $model->parent_id = $up_uid;
                            $model->save();
                        }
                    }
                }
		
                if (isset($device_id) && $device_id) {
                    Device::toUpdateOrCreate($model->user_id, $attributes);
                }

                UserRegStatus::toUpdate($model->user_id, 0);

                $token = Token::encode(['uid' => $model->user_id]);
                return self::formatBody(['token' => $token, 'user' => $model->toArray()]);

            }   else {

                return self::formatError(self::UNKNOWN_ERROR);

            }

        } else {

            return self::formatError(self::BAD_REQUEST, trans('message.member.exists'));

        }
    }

    public static function verifyMobile(array $attributes)
    {
        extract($attributes);
        if ($model = Member::where('user_name', $mobile)->first())
        {
            return self::formatError(self::BAD_REQUEST, trans('message.member.mobile.exists'));
        }

        return self::formatBody();
    }

    public static function sendCode(array $attributes)
    {
        extract($attributes);

        $res = Sms::requestSmsCode($mobile);

        if ($res === true) { // !isset($res['error'])
            return self::formatBody();
        }
        
        return self::formatError(self::BAD_REQUEST, trans('message.member.mobile.send.error'));
    }

    public static function getMemberByToken()
    {
        $uid = Token::authorization();

        if ($model = Member::where('user_id', $uid)->first())
        {
            return self::formatBody(['user' => $model->toArray()]);

        } else {

            return self::formatError(self::NOT_FOUND);

        }

    }

    public static function updateMember(array $attributes)
    {
        extract($attributes);

        $uid = Token::authorization();

        if ($model = Member::where('user_id', $uid)->first())
        {
            if (isset($gender)) {
                $model->sex = $gender;
            }

            if (isset($nickname)) {
                $model->alias = strip_tags($nickname);
            }

            if(isset($avatar_url)){
                if($avatar = Avatar::where('user_id', $uid)->first()){
                    $avatar->avatar = $avatar_url;
                    $avatar->save();
                }else{
                    $avatar = new Avatar;
                    $avatar->user_id = $uid;
                    $avatar->avatar = $avatar_url;
                    $avatar->save();
                }
            }

            if (isset($values)) {
                $values = json_decode($values, true);
                if ($values && is_array($values)) {
                    foreach ($values as $key => $value) {
                        if (isset($value['id']) && isset($value['value'])) {
                            RegExtendInfo::toUpdate($value['id'], $uid, $value['value']);
                        }
                    }
                }
            }

            if ($model->save())
            {
                return self::formatBody(['user' => $model->toArray()]);

            }   else {

                return self::formatError(self::UNKNOWN_ERROR);
            }

        } else {

            return self::formatError(self::NOT_FOUND);

        }
    }

    public static function updatePassword(array $attributes)
    {
        extract($attributes);

        $uid = Token::authorization();

        if ($model = Member::where('user_id', $uid)->first()) {

            if (self::setPassword($old_password, $model->ec_salt) == $model->password) {
                // update password
                $model->password = self::setPassword($password);
                $model->ec_salt = 0;
                $model->salt = 0;

                if ($model->save()) {

                    return self::formatBody();

                } else {

                    return self::formatError(self::UNKNOWN_ERROR);
                }

            } else {
                //old password error
                return self::formatError(self::BAD_REQUEST, trans('message.member.password.old_password'));
            }
        } else {

            return self::formatError(self::NOT_FOUND);

        }
    }


    public static function updatePasswordByMobile(array $attributes)
    {
        extract($attributes);

        if ($model = Member::where('user_name', $mobile)->first()) {

                if (self::verifyCode($mobile, $code)) {
                    // update password
                    $model->password = self::setPassword($password);
                    $model->ec_salt = 0;
                    $model->salt = 0;

                    if ($model->save()) {

                        return self::formatBody();

                    } else {
                        return self::formatError(self::UNKNOWN_ERROR);
                    }
                } else {
                    return self::formatError(self::BAD_REQUEST, trans('message.member.mobile.code.error'));
                }

        } else {

            return self::formatError(self::BAD_REQUEST, trans('message.member.mobile.404'));

        }
    }

    public static function resetPassword(array $attributes)
    {
        extract($attributes);

        if ($model = Member::where('email', $email)->first()){
	
            $hash_code = ShopConfig::findByCode('hash_code');
	    
            $activation = md5($model->user_id . $hash_code . $model->reg_time);

            //Send mail
            Mail::send('emails.reset',
            [
                'username' => $model->user_name,
                'sitename' => env('MAIL_FROM_NAME'),
                'link'     => config('app.shop_url').'/user.php?act=get_password&uid='.$model->user_id.'&code='.$activation
            ],
            function($message) use ($model)
            {
              $message->to($model->email)
                  ->subject(trans('message.email.reset.subject'));
            });

            return self::formatBody();
        }

        return self::formatError(self::BAD_REQUEST, trans('message.email.error'));
    }

    public static function auth(array $attributes)
    {
        extract($attributes);
        $userinfo = null;
        switch ($vendor) {
            case self::VENDOR_WEIXIN:
                $userinfo = self::getUserByWeixin($access_token, $open_id);
                break;

            case self::VENDOR_WEIBO:
                $userinfo = self::getUserByWeibo($access_token, $open_id);
                break;

            case self::VENDOR_QQ:
                $userinfo = self::getUserByQQ($access_token, $open_id);
                break;

            case self::VENDOR_TAOBAO:
                return false;
                break;
            case self::VENDOR_WXA:
                $wxainfo = self::getUserByWXA($js_code);                
                if($wxainfo)
                {
                    $open_id = $wxainfo['openid'];
                    $session_key = $wxainfo['session_key'];
                    $userinfo['prefix'] = 'wxa';
                    $userinfo['avatar'] = '';
                    $userinfo['gender'] = 0;
                    $userinfo['nickname'] = 'wxa_'+$wxainfo['openid'];    
                }
                
                break;
            default:
                return false;
                break;
        }

        if (!$userinfo) {
            return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
        }

        $is_new_user = false;
        if (!$user_id = self::checkBind($open_id)) {
            // create user
            $model = self::createAuthUser($vendor, $open_id, $userinfo['nickname'], $userinfo['gender'], $userinfo['prefix'], $userinfo['avatar']);

            if (!$model) {
                return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
            }

            $user_id = $model->user_id;
            $is_new_user = true;

        } else {
            UserRegStatus::toUpdate($user_id, 1);
        }

        if (isset($device_id) && $device_id) {
            Device::toUpdateOrCreate($user_id, $attributes);
        }
        if(!isset($open_id)){
            $open_id = '';
        }
        // login
        return self::formatBody(['token' => Token::encode(['uid' => $user_id]), 'user' => Member::where('user_id', $user_id)->first(),'openid'=>$open_id,'is_new_user' => $is_new_user]
            );

    }


    public static function webOauth(array $attributes)
    {
        extract($attributes);

        switch ($vendor) {
            case self::VENDOR_WEIXIN:

                $oauth = Configs::where(['type' => 'oauth', 'status' => 1, 'code' => 'wechat.web'])->first();
                $config = Configs::verifyConfig(['app_id', 'app_secret'], $oauth);

                if (!$oauth || !$config) {
                    return self::formatError(self::BAD_REQUEST, trans('message.config.oauth.wechat'));
                }

                $wechat = new Wechat($config['app_id'], $config['app_secret']);
                return $wechat->getWeChatAuthorizeURL(url('/v2/ecapi.auth.web.callback/'.self::VENDOR_WEIXIN.'/?referer='.$referer.'&scope='.$scope),$scope);
                break;

            case self::VENDOR_WEIBO:
                return false;
                break;

            case self::VENDOR_QQ:
                
                $oauth = Configs::where(['type' => 'oauth', 'status' => 1, 'code' => 'qq.wap'])->first();
                $config = Configs::verifyConfig(['app_id', 'app_secret'], $oauth);

                Log::info('QQ登录：' . json_encode($oauth));

                if (!$oauth || !$config) {
                    return self::formatError(self::BAD_REQUEST, trans('message.config.oauth.qq'));
                }

                $qc = new Qc($config['app_id'], $config['app_secret']);

                $res = $qc->login(url('/v2/ecapi.auth.web.callback/'.self::VENDOR_QQ.'/?referer='.$referer), 'get_user_info');               

                return $res;
                
                break;

            case self::VENDOR_TAOBAO:
                return false;
                break;

            default:
                return false;
                break;
        }
    }

    public static function webOauthCallback($vendor)
    {
        switch ($vendor) {
            case self::VENDOR_WEIXIN:

                $oauth = Configs::where(['type' => 'oauth', 'status' => 1, 'code' => 'wechat.web'])->first();

                $config = Configs::verifyConfig(['app_id', 'app_secret'], $oauth);                                                                

                if (!$oauth || !$config) {
                    return self::formatError(self::BAD_REQUEST, trans('message.config.oauth.wechat'));
                }


        		$scope = isset($_GET['scope'])?$_GET['scope']:"";

                $wechat = new Wechat($config['app_id'], $config['app_secret']);

                if (!$access_token = $wechat->getAccessToken('code', isset($_GET['code']) ? $_GET['code'] : '')) {
                    Log::error('access_token: '.$wechat->error());
                    return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
                }
                $open_id = $wechat->getOpenid();
                if($scope == "snsapi_userinfo"){
                    $oauth_id = $wechat->getUnionid() ?: $open_id;
                    $userinfo = self::getUserByWeixin($access_token, $oauth_id);
                }                

                $platform = 'wechat';

                if($scope == "snsapi_userinfo"){
                    if (!$userinfo) {
                        return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
                    }

                    if (!$user_id = self::checkBind($oauth_id)) {
                        // create user
                        $model = self::createAuthUser($vendor, $oauth_id, $userinfo['nickname'], $userinfo['gender'], $userinfo['prefix']);

                        if (!$model) {
                            return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
                        }

                        $user_id = $model->user_id;
                    }

                    $token = Token::encode(['uid' => $user_id]);

                    $key = "platform:{$user_id}";
                    Cache::put($key, $platform, 0);

                    return ['token' => $token, 'openid' => $open_id];
                }
                else{
                    return ['token' => "", 'openid' => $open_id];        
                }

                break;

            case self::VENDOR_WEIBO:
                return false;
                break;

            case self::VENDOR_QQ:
                $oauth = Configs::where(['type' => 'oauth', 'status' => 1, 'code' => 'qq.wap'])->first();

                $config = Configs::verifyConfig(['app_id', 'app_secret'], $oauth);

                if (!$oauth || !$config) {
                    return self::formatError(self::BAD_REQUEST, trans('message.config.oauth.qq'));
                }
                
                $qc = new Qc($config['app_id'], $config['app_secret']);

                $access_token = $qc->get_access_token(url('/v2/ecapi.auth.web.callback/'.self::VENDOR_QQ)); // 

                $open_id = $qc->get_openid($access_token); // open_id

                $user_info = $qc->get_user_info($access_token, $open_id, $config['app_id']);

                if (!$user_info) {
                    return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
                }

                if (!$user_id = self::checkBind($open_id)) {
                    // create user
                    $model = self::createAuthUser($vendor, $open_id, $user_info['nickname'], $user_info['gender']);

                    if (!$model) {
                        return self::formatError(self::BAD_REQUEST, trans('message.member.auth.error'));
                    }
                }

                return ['token' => $access_token, 'openid' => $open_id];

                break;

            case self::VENDOR_TAOBAO:
                return false;
                break;

            default:
                return false;
                break;
        }

    }


    private static function getUserByWeixin($access_token, $open_id)
    {
        $api = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}";
        $res = curl_request($api);
        if (isset($res['errcode'])) {
            Log::error('weixin_oauth_log: '.json_encode($res));
            return false;
        }

        return [
            'nickname' => $res['nickname'],
            'gender' => $res['sex'],
            'prefix' => 'wx',
            'avatar' => $res['headimgurl']
        ];
    }

    private static function getUserByWXA($js_code)
    {
        $oauth = Configs::where(['type' => 'oauth', 'status' => 1, 'code' => 'wechat.wxa'])->first();
        $config = Configs::verifyConfig(['app_id', 'app_secret'], $oauth);
        Log::error('weixin_config: '.var_export($config,true));
        if (!$oauth || !$config) {
            return self::formatError(self::BAD_REQUEST, trans('message.config.oauth.wechat'));

        }

        $app_id = $config['app_id'];
        $app_secret = $config['app_secret'];
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$app_id}&secret={$app_secret}&js_code={$js_code}&grant_type=authorization_code";
        Log::error('weixin_oauth_log: '.$api);
        $res = curl_request($api);
        if (isset($res['errcode'])) {
            Log::error('weixin_oauth_log: '.json_encode($res));
            return false;
        };
        Log::error('weixin_oauth_log: '.json_encode($res));
        return [
            'openid' => $res['openid'],
            'prefix' => 'wxa',
            'session_key' => $res['session_key']            
        ];
        
    }
    

    private static function getUserByWeibo($access_token, $open_id)
    {
        $api = "https://api.weibo.com/2/users/show.json?access_token={$access_token}&uid={$open_id}";
        $res = curl_request($api);
        if (isset($res['error_code'])) {
            Log::error('weibo_oauth_log: '.json_encode($res));
            return false;
        }

        return [
            'nickname' => $res['screen_name'],
            'gender' => ($res['gender'] == 'm') ? self::GENDER_MALE : (($res['gender'] == 'f') ? self::GENDER_MALE : self::GENDER_SECRET),
            'prefix' => 'wb',
            'avatar' => $res['avatar_large']
        ];
    }

    private static function getUserByQQ($access_token, $open_id)
    {
        if ($qq = Configs::where(['type' => 'oauth', 'code' => 'qq.app'])->first()) {
            $config = json_decode($qq->config, true);
            if (isset($config['app_id'])) {
                $api = "https://graph.qq.com/user/get_user_info?oauth_consumer_key={$config['app_id']}&access_token={$access_token}&openid={$open_id}&format=json";
                $res = curl_request($api);

                if (isset($res['ret']) && $res['ret'] != 0) {
                    Log::error('qq_oauth_log: '.json_encode($res));
                    return false;
                }

                return [
                    'nickname' => $res['nickname'],
                    'gender' => ($res['gender'] == '男' ? 1 : ($res['gender'] == '女' ? 2 : 0)),
                    'prefix' => 'qq',
                    'avatar' => $res['figureurl_qq_2']
                ];
            }
        }

        return false;

    }

    private static function checkBind($open_id)
    {
        $user = Sns::where('open_id', $open_id)->first();
        if (empty($user)) {
            return false;
        }
        return $user->user_id;
    }

    private static function createAuthUser($vendor, $open_id, $nickname, $gender, $prefix = 'ec', $avatar = '')
    {
        $username = self::genUsername($prefix);

        if (!Member::where('user_name', $username)->first())
        {
            $data = [
                'user_name' => $username,
                'email' => "{$username}@sns.user",
                'password' => self::setPassword(uniqid()),
                'reg_time' => time(),
                'user_rank' => 0,
                'sex' => $gender,
                'alias' => strip_tags($nickname),
                'mobile_phone' => '',
                'rank_points' => 0
            ];

            if ($model = self::create($data))
            {
                $sns = new Sns;
                $sns->user_id = $model->user_id;
                $sns->open_id = $open_id;
                $sns->vendor  = $vendor;
                $sns->save();

                return $model;
            }

            return false;
        }
    }

    private static function genUsername($type)
    {
        return $type.'_'.time().rand(1000,9999);
    }

    private static function validatePassword($username, $password)
    {
        $type = self::getUsernameType($username);

        if ($type == 'email') {

            $model = self::where('email', $username)->first();

        } else {

            $model = self::where('user_name', $username)->first();

        }

        if ($model && $model->password == self::setPassword($password, $model->ec_salt))
        {
            $model->last_login = time();
            $model->last_ip = app('request')->ip();
            $model->save();

            return $model;
        }

            return false;
    }

    private static function setPassword($password, $salt = false)
    {
        if ($salt) {
            return md5(md5($password).$salt);
        }
        return md5($password);
    }

    public static function getUsernameType($username)
    {
        if (preg_match("/^\d{11}$/",$username)) {

            return 'mobile';

        } elseif (preg_match("/^\w+@\w+\.\w+$/",$username)) {

            return 'email';

        } else {

            return 'username';
        }
    }

    public static function getUserPayPoints()
    {
        $uid = Token::authorization();

        if($member = Member::where('user_id', $uid)->first()){
            $rule = ShopConfig::findByCode('integral_scale');
            if(isset($rule)){
                return self::formatBody(['score' => $member->pay_points, 'rule' => $rule/100]);
           }
        }
    }

    /**
     * 取得用户信息
     * @param   int     $user_id    用户id
     * @return  array   用户信息
     */
    public static function user_info($user_id)
    {

        $user = Member::where('user_id', $user_id)->first();
        unset($user['question']);
        unset($user['answer']);

        /* 格式化帐户余额 */
        if ($user)
        {
    //        if ($user['user_money'] < 0)
    //        {
    //            $user['user_money'] = 0;
    //        }
            $user['formated_user_money'] = Goods::price_format($user['user_money'], false);
            $user['formated_frozen_money'] = Goods::price_format($user['frozen_money'], false);
        }

        return $user;
    }


    /**
     * 获得用户的可用积分
     *
     * @access  private
     * @return  integral
     */
    public static function flow_available_points()
    {
        $val = 0;
        $res = Cart::join('goods','cart.goods_id','=','goods.goods_id')
                ->where('goods.integral','>',0)
                ->where('cart.is_gift','=',0)
                ->where('cart.rec_type','=',Cart::CART_GENERAL_GOODS)
                // ->sum(DB::raw('integral * (cart.goods_number)'));
                // ->select('sum('goods.integral' * 'cart.goods_number') AS total')
                ->first(['goods.integral','cart.goods_number']);
        if ($res) {
            $val = $res->integral * $res->goods_number;
        }
        return Order::integral_of_value($val);
    }


    public static function giveRegisterPoints()
    {
        $score = ShopConfig::findByCode('register_points');

        if( $score > 0 && AccountLog::logAccountChange(0, 0, $score, $score, trans('message.score.register'))){
            return true;
        }
        return false;
    }

    /**
     * 记录帐户变动
     * @param   int     $user_id        用户id
     * @param   float   $user_money     可用余额变动
     * @param   float   $frozen_money   冻结余额变动
     * @param   int     $rank_points    等级积分变动
     * @param   int     $pay_points     消费积分变动
     * @param   string  $change_desc    变动说明
     * @param   int     $change_type    变动类型：参见常量文件
     * @return  void
     */
    public static function log_account_change($user_id, $user_money = 0, $frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = '', $change_type = self::ACT_OTHER)
    {
        /* 插入帐户变动记录 */
        $account_log = array(
            'user_id'       => $user_id,
            'user_money'    => $user_money,
            'frozen_money'  => $frozen_money,
            'rank_points'   => $rank_points,
            'pay_points'    => $pay_points,
            'change_time'   => time(),
            'change_desc'   => $change_desc,
            'change_type'   => $change_type
        );
        AccountLog::insert($account_log);
        // /* 更新用户信息 */
        self::where('user_id',$user_id)
                ->limit(1)
                ->increment('user_money',$user_money)
                ->increment('frozen_money',$frozen_money)
                ->increment('rank_points',$rank_points)
                ->increment('pay_points',$pay_points);
    }

    private static function verifyCode($mobile, $code)
    {
    	$res = Sms::verifySmsCode($mobile, $code);
        if ($res === true) { // !isset($res['error']
            return true;
        }
        return false;
    }

    public function getIdAttribute()
    {
        return $this->attributes['user_id'];
    }

    public function getAgeAttribute()
    {
        return null;
    }

    public function getRankAttribute()
    {
        if ($model = UserRank::findRankByUid($this->user_id)) {
            return $model->toArray();
        }else{
            //如果没有等级　默认返回注册用户
            return null;
        }
    }

    public function getGenderAttribute()
    {
        return $this->attributes['sex'];
    }

    public function getUsernameAttribute()
    {
        return $this->attributes['user_name'];
    }

    public function getNicknameAttribute()
    {
        return $this->attributes['alias'];
    }

    public function getMobileAttribute()
    {
        return $this->attributes['mobile_phone'];
    }

    public function getAvatarAttribute()
    {
        return null;
    }

    public function getMobileBindedAttribute()
    {
        return false;
    }

    public function getJoinedAtAttribute()
    {
        return $this->attributes['reg_time'];
    }

    public function getIsAuthAttribute()
    {
        if(strpos($this->attributes['email'], '@sns.user') === false){
            return false;
        }
        return true;
    }

    public function getIsCompletedAttribute()
    {
        return UserRegStatus::IsCompleted($this->attributes['user_id']);
    }
}
