<?php
/**
 * Created by PhpStorm.
 * User: hn
 */

require_once('WebSocketAPI.class.php');


class SocketIO {

    private $type;
    /**
     * 主机地址(有效 IP 或 域名）
     * @var
     */
    private $address;
    /**
     * 主机端口（socket 监听 - 通信端口）
     * @var
     */
    private $port;
    /**
     * 主 socket
     * @var
     */
    private $master;
    /**
     * socket 集合（哈希）
     * @var
     */
    private $sockets;
    /**
     * WebSocket 集合（哈希）
     * @var
     */
    private $clients;
    /**
     * @var int
     */
    private $read_size = 1024;
    /**
     * @var int
     */
    private $write_size = 1024;
    /**
     * 客户端 允许失连最大时间间隔，单位：秒
     * @var int
     */
    private $waite_time = 0;
    /**
     * 最大连接数
     * @var int
     */
    private $max_connect_count = 300;
    /**
     * 当前连接数
     * @var int
     */
    private $cur_connect_count = 0;
    /**
     * API
     * @var WebSocket
     */
    private $wsAPI;
    /**
     * 事件函数
     * @var array
     */
    private $events = array('connect'=>null,'close'=>null);
    /**
     * 回掉函数
     * @var
     */
    private $callbacks;

    /**
     * 构造函数
     * @param $address
     * @param $port
     */
    public function __construct($address, $port, $type='ws'){
        $this->type = in_array($type,array('ws','tcp')) ? $type : 'ws';
        $this->address = $address;
        $this->port = $port;
        $this->wsAPI = new WebSocketAPI();
    }

    /**
     * 绑定事件
     * @param $event
     * @param $callback
     */
    function on($event,$callback){
        $this->events[$event] = $callback;
    }

    /**
     * 单向发送（请求/推送）
     * @param $uid
     * @param $msg
     */
    function emit($event, $uid, $msg, $callback = null){
        if($callback!=null)$this->callbacks[$event] = $callback;
        $data = array(
            'event'=> $event,
            'etype'=> $callback!=null ? 'callback' : 'event',
            'msg'=> $msg
        );
        $msg = json_encode($data);
        $this->write($this->clients[$uid]['socket'], $msg);
    }

    /**
     * 单向发送(响应)
     * @param $uid
     * @param $msg
     */
    function response($event, $uid, $msg){
        $data = array(
            'event'=> $event,
            'etype'=> 'callback',
            'msg'=> $msg
        );
        $msg = json_encode($data);
        $this->write($this->clients[$uid]['socket'], $msg);
    }

    /**
     * 广播消息
     * @param $msg
     */
    function broadcast($event, $msg, $callback = null){
        if($callback !== null) $this->callbacks[$event] = $callback;
        $data = array(
            'event'=>$event,
            'msg'=>$msg,
            'etype'=> $callback !== null ? 'callback' : '',
        );
        $msg = json_encode($data);
        foreach($this->clients as $client){
            $this->write($client['socket'],$msg);
        }
    }

    /**
     * 设置最大连接限制
     * @param $num
     */
    function setMaxConnectCount($num){
        $this->max_connect_count = $num;
    }

    /**
     * 获取当前连接数
     * @return int
     */
    function getCurConnectCount(){
        return $this->cur_connect_count;
    }

    /**
     * 获取最大连接限制
     * @return int
     */
    function getMaxConnectCount(){
        return $this->max_connect_count;
    }

    /**
     * 设置最大等待时间
     * @param int $long
     */
    function setWaiteTime($long=0){
        $this->waite_time = $long;
    }

    /**
     * 主进程，完成 子 socket 的调度管理
     */
    function run(){
        $this->master=$this->createMaterSocket($this->address, $this->port);
        $this->sockets=array('master'=>$this->master);
        $this->console('Listening on   : '.$this->address.' port '.$this->port);
        $this->console('Server Started : '.date('Y-m-d H:i:s'));
        while(file_exists('./Conf/ws.lock')){
            $reads = $this->sockets;
            $writes = array();
            $excepts = array();
            if(@socket_select($reads, $writes, $excepts,null) > 0){
                foreach($reads as $key=>$socket){
                    if($socket == $this->master){
                        $this->addClient();
                    }else{
                        $this->conduct($key,$socket);
                    }
                }
            }
        }
    }

    /**
     * 关闭客户端连接
     * @param $uid
     */
    function close($uid){
        if(is_resource($this->sockets[$uid])){
            socket_shutdown($this->sockets[$uid], 2);
            socket_close($this->sockets[$uid]);
        }
        $this->sockets[$uid] = null;
        unset($this->sockets[$uid]);

        if(is_resource($this->clients[$uid]['socket'])){
            socket_shutdown($this->clients[$uid], 2);
            socket_close($this->clients[$uid]['socket']);
        }
        $this->clients[$uid]['socket'] = null;
        unset($this->clients[$uid]);
    }

    /**
     * 新增客户端连接
     */
    private function addClient(){
        $this->cur_connect_count += 1 ;
        $newClient = socket_accept($this->master);
        $uid = uniqid();
        $this->sockets[$uid] = $newClient;
        $this->clients[$uid] = array(
            'uid'=> $uid,
            'status'=> array(
                'handshake'=> false,
                'online'=> false,
                'updateTime'=> null
            ),
            'socket'=>$newClient,
        );
    }

    /**
     * 处理客户端 socket
     * @param $uid
     * @param $socket
     */
    private function conduct($uid,$socket){
        $data = $this->read($socket);
        if(!$this->clients[$uid]['status']['handshake']){
            $this->wsAPI->handshake($socket,$data);
            $this->clients[$uid]['status']['handshake'] = true;
            $this->clients[$uid]['status']['online'] = true;
            $this->clients[$uid]['status']['updateTime'] = time();
            $this->doConnect($uid);
        }else{
            if(strlen($data)>0 && !is_bool($data)){
                $data = $this->wsAPI->uncode($data,$uid);
                if(strlen($data)>0) $this->doMessage($uid, $data);
            }else{
//                if( $this->clients[$uid]['status']['online'] === true){
//                    $this->clients[$uid]['status']['online'] = false;
//                    $this->clients[$uid]['status']['updateTime'] = time();
//                }
//                $lastTime = $this->clients[$uid]['status']['updateTime'];
//                // 失连超时则 服务端 回收资源
//                if(($lastTime + $this->waite_time) < time()){
                    $this->close($uid);
                    $this->doClose($uid);
//                }else{
//                    $this->console('exception err : '.date('Y-m-d H:i:s').'--'.$data);
//                }
            }
        }
    }

    /**
     * 连接事件
     * @param $uid
     */
    private function doConnect($uid){
        $function = $this->events['connect'];
        if(is_string($function) && function_exists($function)){
            call_user_func($function,$this,$uid);
        }elseif(is_object($function)){
            $function($this,$uid);
        }else{
            $this->console('connect function is undefined: '.$function);
        }
    }

    /**
     * 事件
     * @param $uid
     * @param $msg
     * @return bool
     */
    private function doMessage($uid,$msg){
        $data = json_decode($msg,true);  // 关键
        $event = $data['event'];
        $msg = $data['msg'];
        $etype = $data['etype'];

        if($etype == 'callback'){
            $callback = isset($this->events[$event]) ? $this->events[$event] : false; // 这里必须用数组索引取值
        }else{
            $callback = isset( $this->callbacks[$event]) ?  $this->callbacks[$event] : false;
        }

        if(is_string($callback) && function_exists($callback)) {
            call_user_func($callback,$this,$uid,$msg);
        }elseif(is_object($callback)){
            $callback($this,$uid,$msg);
        }else{
            $this->console("{$event}  is undefined");
        }
    }

    /**
     * 关闭事件
     * @param $uid
     */
    private function doClose($uid){
        $err = socket_strerror(socket_last_error());
        $function = $this->events['close'];
        if(is_string($function) && function_exists($function)){
            call_user_func($function,$this,$uid,$err);
        }elseif(is_object($function)){
            $function($this,$uid,$err);
        }else{
            $this->console('close function is undefined: '.$function);
            $this->console("uid:$uid close");
        }
    }

    /**
     * 创建主 socket 通道
     * @param $address
     * @param $port
     * @return resource
     */
    private function createMaterSocket($address,$port){
        if(!is_resource($this->master)){
            $server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            //阻塞模式
//            socket_set_block($server);
            socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
            socket_bind($server, $address, $port);
            socket_listen($server);
            $this->master = $server;
        }
        return $this->master;
    }

    /**
     * @param $socket
     * @param null $length
     * @return bool|string
     */
    private function read($socket, $length = null) {
        if($length==null || $length <=0) $length = $this->read_size;
        $read ='';
        $flag = $length;
        try{
            while($flag === $length){
                $flag=@socket_recv($socket, $buf, $length, null);
                $read.=$buf;
            }
            if ($flag<0){
                //error
                return false;
            }elseif ($flag==0){
                //Client disconnected
                return  false;
            }else{
                return $read;
            }
        }catch (ErrorException $e){
            return false;
        }
    }

    /**
     * 单向发送消息
     * @param $socket
     * @param $msg
     */
    private function write($socket,$msg){
        $msg = $this->wsAPI->code($msg);
        $size= $this->write_size;
        $len = strlen($msg);
        if($len > $size){
            $count = ceil($len/$size);
            for($i=0; $i<$count; $i++){
                socket_write($socket, substr($msg, $i*$size, $size), $size);
            }
        }else{
            socket_write($socket, $msg, $size);
        }
    }

    /**
     * 日志和回显
     * @param $str
     */
    private function console($str){
        //todo 駿濤。 取消日志。
/*        $path=dirname(__FILE__).'/log/'.date('Y-m-d').'.txt';
        $dir = dirname($path);
        if(!is_dir($dir)) mkdir($dir,0755,true);
        $str=$str."\n";
        error_log($str,3,$path);
        echo $str;*/
    }
}