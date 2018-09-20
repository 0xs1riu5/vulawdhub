<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-1-26
 * Time: 下午4:29
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */

namespace Common\Model;

use Think\Model;

class PushingModel extends Model
{
    public function run()
    {
        ignore_user_abort(true);
        set_time_limit(0);

        $address = modC('WEBSOCKET_ADDRESS', gethostbyname($_SERVER['SERVER_NAME']), 'Config');
        $port = modC('WEBSOCKET_PORT', 8000, 'Config');

        require_once('./ThinkPHP/Library/Vendor/socketio/SocketIO.class.php');
        $io = new \SocketIO($address, $port);

        $io->on('connect', function ($ws, $uid) {
        });

        $io->on('login', function ($ws, $uid, $msg) {
            $timestamp = $msg['timestamp'];
            $signature = $msg['signature'];
            $mid = $msg['uid'];
            $key = C('DATA_AUTH_KEY');
            $rs = true;
            $now_time = time();
            $return = array(
                'status' => 1,
                'uid' => $mid,
            );
            if ($timestamp + 60 * 10 < $now_time) {
                $return['info'] = '超时';
                $rs = false;
            }
            if (md5($mid . $timestamp . $key) != $signature) {
                $return['info'] = '数字签名有误~';
                $rs = false;
            }
            if (!$rs) {
                $return['status'] = 0;
                unset($_SESSION['user_link'][$mid]);
                $ws->response('login', $uid, $return);
            } else {

                $array = array(
                    'uid' => $mid,
                    'timestamp' => $timestamp,
                    'signature' => $signature
                );
                $token = think_encrypt(json_encode($array));
                $return['token'] = $token;
                //   $last = $_SESSION['user_link'][$mid];
                // $ws->close($last);
                $_SESSION['user_link'][$mid] = $uid;
                $ws->response('login', $uid, $return);
            }
        });

        $io->on('msg', function ($ws, $uid, $msg) {
            $token = $msg['token'];
            $aToken = json_decode(think_decrypt($token), true);
            $mid = $aToken['uid'];
            $rs = true;
            if ($mid != 'process') {
                if (($_SESSION['user_link'][$mid] != $uid)) {
                    $return['info'] = '没有权限~';
                    $rs = false;
                }
            }
            if (!$rs) {
                $return['status'] = 0;
                $ws->response('msg', $uid, $return);
            } else {
                $data = array('content' => $msg['content'], 'type' => $msg['type']);
                if (isset($msg['to']) && $msg['to'] == 'all') {
                    $ws->broadcast('msg', $data);
                } else {
                    $to = $_SESSION['user_link'][$msg['to']];

                    $ws->emit('msg', $to, $data);
                    $ws->emit('msg', $uid, $data);
                }
            }

        });
        $io->on('exit', function ($ws, $uid, $msg) {
        });
        $io->on('close', function ($ws, $uid, $err) {
        });
        $io->run();
    }

    public function sendMsg($to = 'all', $content = '', $type = '', $event = 'msg')
    {
        $client = $this->_getClient();
        $array = array(
            'uid' => 'process',
            'timestamp' => time(),
        );
        $token = think_encrypt(json_encode($array));
        $payload = json_encode(array(
            'event' => $event,
            'etype' => 'callback',
            'msg' => array('content' => $content, 'to' => $to, 'type' => $type, 'token' => $token)
        ));
        $rs = $client->sendData($payload);
        return $rs;
    }

    private function _getClient()
    {
        require_once('./ThinkPHP/Library/Vendor/socketio/WebSocketClient.php');
        $client = new \WebSocketClient();
        $address = modC('WEBSOCKET_ADDRESS', gethostbyname($_SERVER['SERVER_NAME']), 'Config');
        $port = modC('WEBSOCKET_PORT', 8000, 'Config');
        $check = $client->connect($address, $port, '/');
        if ($check === false) {
            unlink('./Conf/ws.lock');
        }
        return $client;
    }


    public function doRun()
    {
        if (!S('socket_check')) {
            $this->_getClient();
            S('socket_check', true, 60*10);
        }

        if (!file_exists('./Conf/ws.lock')) {
            file_put_contents('./Conf/ws.lock', '');
            $time = time();
            $url = U('Core/Public/pushing', array('time' => $time, 'token' => md5($time . C('DATA_AUTH_KEY'))), true, true);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);  //设置过期时间为1秒，防止进程阻塞
            curl_setopt($ch, CURLOPT_USERAGENT, '');
            curl_setopt($ch, CURLOPT_REFERER, 'b');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($ch);
            curl_close($ch);
        }
    }

}
