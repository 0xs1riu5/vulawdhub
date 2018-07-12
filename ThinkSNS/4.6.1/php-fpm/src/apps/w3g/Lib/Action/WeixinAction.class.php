<?php

class WeixinAction extends BaseAction
{
    public $data;
    public function index()
    {
        if (IS_GET) {
            $url = GetCurUrl();
            weixin_log($url, 'auth');

            if ($this->auth(TOKEN)) {
                echo $_GET ['echostr'];
            }
            die();
        }

        $content = file_get_contents('php://input');
        $content = new \SimpleXMLElement($content);
        foreach ($content as $key => $value) {
            $data [$key] = strval($value);
        }
        $this->data = $data;
        weixin_log($data, $GLOBALS ['HTTP_RAW_POST_DATA']);

        if (!empty($data ['FromUserName'])) {
            session('openid', $data ['FromUserName']);
        }

        $key = $data ['Content'];
        $keywordArr = array();

        $list = D('addons')->where('is_weixin=1')->findAll();
        foreach ($list as $vo) {
            if ($vo ['status']) {
                $addon_list [] = $vo;
            } else {
                $forbit_list [] = $vo;
            }
        }

        if ($data ['MsgType'] == 'event') {
            $event = strtolower($data ['Event']);
            foreach ($addon_list as $vo) {
                $name = $vo ['name'];
                $this->plugin_deal($name, $event, $data);
            }
            if (!($event == 'click' && !empty($data ['EventKey']))) {
                return true;
            }

            $key = $data['Content'] = $data ['EventKey'];
        }

        $uid = intval($this->mid);
        $user_status = S('user_status_'.$uid);
        if (!isset($plugins [$key]) && $user_status) {
            $plugins [$key] = $user_status ['module'];
            $keywordArr = $user_status ['keywordArr'];
            S('user_status_'.$uid, null);
        }

        if (!isset($plugins [$key])) {
            foreach ($addon_list as $k => $vo) {
                $plugins [$vo ['name']] = $k;
                $plugins [$vo ['pluginName']] = $k;
            }
        }

        if (!isset($plugins [$key])) {
            $like ['keyword'] = array(
                    'like',
                    "%$key%",
            );
            if (!empty($forbit_list)) {
                $like ['module'] = array(
                        'not in',
                        $forbit_list,
                );
            }

            $keywordArr = M('keyword')->where($like)->order('id desc')->find();
            $plugins [$key] = $keywordArr ['module'];
        }

        // 回答不上
        if (!isset($plugins [$key])) {
            $plugins [$key] = 'Base';
        }
        $this->plugin_deal($plugins [$key], 'response', $data, $keywordArr);
    }
    public function plugin_deal($plugin, $action, $data, $keywordArr = array())
    {
        $class = $plugin.'Addons';
        if (!class_exists($class)) {
            return false;
        }

        $model = new $class();
        if (!method_exists($model, $action)) {
            return false;
        }

        list($content, $type) = $model->$action ($data, $keywordArr);
        empty($type) && $type = 'text';

        $this->$type ($content);
    }

    /* 回复文本消息 */
    public function text($content)
    {
        $msg ['Content'] = $content;
        $this->_sendData($msg, 'text');
    }
    public function news($articles)
    {
        $msg ['ArticleCount'] = count($articles);
        $msg ['Articles'] = $articles;

        $this->_sendData($msg, 'news');
    }
    /* 发送回复消息到微信平台 */
    private function _sendData($msg, $msgType)
    {
        $msg ['ToUserName'] = $this->data ['FromUserName'];
        $msg ['FromUserName'] = $this->data ['ToUserName'];
        $msg ['CreateTime'] = time();
        $msg ['MsgType'] = $msgType;

        $xml = new \SimpleXMLElement('<xml></xml>');
        $this->_data2xml($xml, $msg);
        $str = $xml->asXML();

        weixin_log($str, '_sendData');
        echo $str;
    }
    /* 组装xml数据 */
    public function _data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {
            is_numeric($key) && ($key = $item);
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                $this->_data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }
    private function auth($token)
    {
        $data = array(
                $_GET ['timestamp'],
                $_GET ['nonce'],
                $token,
        );
        $sign = $_GET ['signature'];
        sort($data, SORT_STRING);
        $signature = sha1(implode($data));

        weixin_log($signature, $sign.'___'.$token);

        return $signature === $sign;
    }
}
