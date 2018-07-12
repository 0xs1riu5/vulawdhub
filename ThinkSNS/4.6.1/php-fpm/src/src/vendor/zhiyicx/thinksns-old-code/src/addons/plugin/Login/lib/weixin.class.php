<?php

class weixin
{
    private function getCallback($site = '', $type = 'bind', $callbackurl = '')
    {
        if (!$callbackurl) {
            if ($type == 'bind') {
                $callbackurl = Addons::createAddonShow('Login', 'no_register_display', array('type' => $site, 'do' => 'bind'));
            } else {
                $callbackurl = Addons::createAddonShow('Login', 'no_register_display', array('type' => $site));
            }
        }

        return urlencode($callbackurl);
    }

    public function getUrl($callbackurl)
    {
        $loginUrl = 'https://open.weixin.qq.com/connect/qrconnect?'
            .'&appid='.WEIXIN_KEY
            .'&redirect_uri='.$this->getCallback('weixin', 'bind', $callbackurl)
            .'&response_type=code'
            .'&scope=snsapi_login'
            .'&state=STATE'
            .'#wechat_redirect';

        return $loginUrl;
    }

    //用户资料
    public function userInfo()
    {
        if ($_SESSION['weixin']['openid'] && $_SESSION['weixin']['access_token']['oauth_token']) {
            https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID
            $token_url = 'https://api.weixin.qq.com/sns/userinfo?'
                .'&access_token='.$_SESSION['weixin']['access_token']['oauth_token']
                .'&openid='.$_SESSION['weixin']['openid'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $token_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res = json_decode($result, true);
            if ($res) {
                $data['id'] = $res['unionid'];
                $data['uname'] = $res['nickname'];
                $data['userface'] = $res['headimgurl'];
            } else {
                return false;
            }

            return $data;
        } else {
            return false;
        }
    }

    //验证用户
    public function checkUser($type = 'bind')
    {
        if ($_REQUEST['code']) {
            $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?'
                .'&appid='.WEIXIN_KEY
                .'&secret='.WEIXIN_SECRET
                .'&code='.$_REQUEST['code']
                .'&grant_type=authorization_code';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $token_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res = json_decode($result, true);
            if ($res['openid']) {
                $_SESSION['weixin']['access_token']['oauth_token'] = $res['access_token'];
                $_SESSION['weixin']['access_token']['oauth_token_secret'] = $res['refresh_token'];
                $_SESSION['weixin']['isSync'] = 1;
                $_SESSION['weixin']['openid'] = $res['openid'];
                $_SESSION['open_platform_type'] = 'weixin';

                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /****************************************************************************
    ************TODO 以下还未修改
    ****************************************************************************/

    //发布一条分享
    public function update($text, $opt)
    {
        $refresh_uri = 'https://graph.renren.com/oauth/token?grant_type=refresh_token&refresh_token='.$opt['oauth_token_secret'].'&client_id='.RENREN_KEY.'&client_secret='.RENREN_SECRET;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $refresh_uri);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $res = json_decode($result);
        $access_token = $res->access_token;
        $refresh_token = $res->refresh_token;
        $config = new stdClass();
        $config->APIURL = 'http://api.renren.com/restserver.do';
        $config->APIKey = RENREN_KEY;
        $config->SecretKey = RENREN_SECRET;
        $config->APIVersion = '1.0';
        $config->decodeFormat = 'json';
        $GLOBALS['config'] = &$config;
        $rrObj = new RenrenRestApiService();

        $params = array('name'         => getShort($opt['feed_content'], 30),
            'description'              => $opt['feed_content'],
            'url'                      => $opt['feed_url'],
            'image'                    => $opt['pic_url'],
            'action_name'              => $GLOBALS['ts']['site']['site_name'],
            'action_link'              => $opt['feed_url'],
            'message'                  => '分享',
            'access_token'             => $access_token, );
        $res = $rrObj->rr_post_curl('feed.publishFeed', $params);

        return true;
    }

    //上传一个照片，并发布一条分享
    public function upload($text, $opt, $pic)
    {
        $this->update($text, $opt);

        return true;
    }

    //转发一条分享
    public function transpond($transpondId, $reId, $content = '', $opt = null)
    {
        return true;
    }

    //保存数据
    public function saveData($data)
    {
        return true;
    }
}
