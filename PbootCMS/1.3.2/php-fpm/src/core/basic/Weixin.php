<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年12月28日
 *  微信公众号对接类
 */
namespace core\basic;

class Weixin
{

    protected $appid;

    protected $secret;

    protected $noncestr;

    protected $redirect;

    protected $data = array();

    public function __construct()
    {
        $this->appid = Config::get('weixin.appid');
        $this->secret = Config::get('weixin.secret');
        $this->noncestr = get_uniqid();
        $this->redirect = Config::get('weixin.redirect');
    }

    // 检查客户端访问Token是否过期
    public function checkAccessToken()
    {
        if (Config::get('weixin.access_token') && time() - Config::get('weixin.access_token_timestamp') < Config::get('weixin.access_token_expires')) {
            return true;
        } else {
            return false;
        }
    }

    // 获取客户端访问Token
    public function getAccessToken()
    {
        if (! $this->checkAccessToken()) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
            $result = json_decode(get_url($url));
            if (isset($result->errcode) && $result->errcode) {
                error('获取微信AccessToken发生错误：' . $result->errmsg . '(' . $result->errcode . ')');
                return false;
            } else {
                $this->data['access_token'] = $result->access_token;
                $this->data['access_token_expires'] = $result->expires_in;
                $this->data['access_token_timestamp'] = time();
                Config::set('weixin', $this->data); // 缓存数据
                return $result->access_token;
            }
        } else {
            return Config::get('weixin.access_token');
        }
    }

    // 获取签名信息
    public function getJsapiSignature()
    {
        // 签名数据数组
        $data['jsapi_ticket'] = $this->getJsapiTicket();
        $data['noncestr'] = $this->noncestr;
        $data['timestamp'] = time();
        $data['url'] = get_current_url();
        // 返回数据数组
        $result['appid'] = $this->appid;
        $result['timestamp'] = $data['timestamp'];
        $result['noncestr'] = $this->noncestr;
        $result['signature'] = sha1(urldecode(http_build_query($data)));
        return $result;
    }

    // 检查Ticket是否过期
    public function checkJsapiTicket()
    {
        if (Config::get('weixin.jsaspi_ticket') && time() - Config::get('weixin.jsaspi_ticket_timestamp') < Config::get('weixin.jsaspi_ticket_expires')) {
            return true;
        } else {
            return false;
        }
    }

    // 获取访问Ticket
    public function getJsapiTicket()
    {
        if (! $this->checkJsapiTicket()) {
            $access_token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
            $result = json_decode(get_url($url));
            if (isset($result->errcode) && $result->errcode) {
                error('获取微信JsapiTicket发生错误：' . $result->errmsg . '(' . $result->errcode . ')');
                return false;
            } else {
                $this->data['jsaspi_ticket'] = $result->ticket;
                $this->data['jsaspi_ticket_expires'] = $result->expires_in;
                $this->data['jsaspi_ticket_timestamp'] = time();
                Config::set('weixin', $this->data); // 缓存数据
                return $result->ticket;
            }
        } else {
            return Config::get('weixin.jsaspi_ticket');
        }
    }

    // 获取卡券签名
    public function getCardSignature()
    {
        // 签名数据数组
        $data['jsapi_ticket'] = $this->getCardTicket();
        $data['noncestr'] = $this->noncestr;
        $data['timestamp'] = time();
        $data['url'] = get_current_url();
        // 返回数据数组
        $result['appid'] = $this->appid;
        $result['timestamp'] = $data['timestamp'];
        $result['noncestr'] = $this->noncestr;
        $result['signature'] = sha1(urldecode(http_build_query($data)));
        return $result;
    }

    // 检查卡券Ticket是否过期
    public function checkCardTicket()
    {
        if (Config::get('weixin.card_ticket') && time() - Config::get('weixin.card_ticket_timestamp') < Config::get('weixin.card_ticket_expires')) {
            return true;
        } else {
            return false;
        }
    }

    // 获取卡券Ticket
    public function getCardTicket()
    {
        if (! $this->checkCardTicket()) {
            $access_token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=wx_card";
            $result = json_decode(get_url($url));
            if (isset($result->errcode) && $result->errcode) {
                error('获取微信CardTicket发生错误：' . $result->errmsg . '(' . $result->errcode . ')');
                return false;
            } else {
                $this->data['card_ticket'] = $result->ticket;
                $this->data['card_ticket_expires'] = $result->expires_in;
                $this->data['card_ticket_timestamp'] = time();
                Config::set('weixin', $this->data); // 缓存数据
                return $result->ticket;
            }
        } else {
            return Config::get('weixin.card_ticket');
        }
    }

    // 自动刷新授权获取用户信息
    public function getAuthUser()
    {
        if (! ! $code = get('code')) { // 重新授权方式获取数据
            $result = $this->getAuthToken($code);
            $wx_user = $this->getAuthUserInfo($result->access_token, $result->openid);
        } else {
            // 三种方式：1、直接获取，2、刷新后获取，3、重新授权
            if (($token = session('auth_token')) && ($openid = session('auth_openid')) && $this->checkAuthToken($token, $openid)) { // 未过期,直接获取
                $wx_user = $this->getAuthUserInfo($token, $openid);
            } elseif (! ! $refresh_token = session('auth_refresh_token') && $result = $this->refreshAuthToken($refresh_token)) { // 刷新后获取
                $wx_user = $this->getAuthUserInfo($result->access_token, $result->openid);
            } else { // 重新授权
                $this->redirectAuth(get_current_url());
                exit();
            }
        }
        return $wx_user;
    }

    // 执行网页授权登陆，返回指定地址,$type模式为snsapi_userinfo或snsapi_base(静默方式)
    public function redirectAuth($redirectUrl, $type = 'snsapi_userinfo')
    {
        if (strpos($redirectUrl, 'http') === FALSE) {
            $http_type = is_https() ? 'https://' : 'http://';
            $redirectUrl = $http_type . $_SERVER['HTTP_HOST'] . $redirectUrl;
        }
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri=$redirectUrl&response_type=code&scope=$type&state=weixin#wechat_redirect";
        header('Location:' . $url);
    }

    // 获取微信网页授权token,$code为引导用户访问微信授权页面后返回的参数值
    public function getAuthToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->secret}&code=$code&grant_type=authorization_code";
        $result = json_decode(get_url($url));
        if (isset($result->errcode) && $result->errcode) {
            error('获取用户登陆授权令牌发生错误，请关闭后重新进入,错误：' . $result->errmsg);
        }
        session('auth_token', $result->access_token);
        session('auth_refresh_token', $result->refresh_token);
        session('auth_openid', $result->openid);
        return $result;
    }

    // 检验微信网页授权token是否有效
    public function checkAuthToken($token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/auth?access_token=$token&openid=$openid";
        $result = json_decode(get_url($url));
        if (isset($result->errcode) && $result->errcode) {
            return false;
        } else {
            return true;
        }
    }

    // 刷新微信网页授权token，传递用户上一次获取的刷新令牌
    public function refreshAuthToken($refreshToken)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->appid}&grant_type=refresh_token&refresh_token=$refreshToken";
        $result = json_decode(get_url($url));
        if (isset($result->errcode) && $result->errcode) {
            return false;
        }
        session('auth_token', $result->access_token);
        session('auth_refresh_token', $result->refresh_token);
        session('auth_openid', $result->openid);
        return $result;
    }

    // 获取网页授权后用户微信信息，传递用户令牌及用户识别码
    public function getAuthUserInfo($token, $openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
        $result = json_decode(get_url($url));
        if (isset($result->errcode) && $result->errcode) {
            error('获取用户基础信息发生错误，请关闭后重新进入,错误：' . $result->errmsg);
        }
        return $result;
    }
}
