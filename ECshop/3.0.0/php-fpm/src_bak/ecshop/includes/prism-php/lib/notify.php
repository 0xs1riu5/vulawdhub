<?php
class prism_notify {

    private $client;
    private $connected = false;
    private $sock;
    private $_frames = array();
    private $messages = array();
    private $last_buf = '';
    private $consuming = false;
    const TextFrame = 0x01;
    const BinaryFrame = 0x02;
    const CloseFrame = 0x08;
    const PingFrame = 0x09;
    const PongFrame = 0x09;

    const action_publish = 1;
    const action_ack     = 3;
    const action_consume = 2;

    function __construct(&$client){
        $this->client = $client;
        $this->client->register_handler(101, array($this, 'handle_upgrade'));
        $this->connect();
    }

    public function pub($routing_key, $message, $ctype="text/plain"){
        $size_routing_key = strlen($routing_key);
        $size_message = strlen($message);
        $size_ctype = strlen($ctype);
        $this->send(self::action_publish, pack("na*Na*na*", $size_routing_key, 
            $routing_key, $size_message, $message, $size_ctype, $ctype));
    }

    public function close(){
        if($this->connected){
            fwrite($this->sock, $this->encode(self::CloseFrame));
            fclose($this->sock);
            $this->connected = false;
        }
    }

    private function consume(){
        if(!$this->consuming){
            $this->send(self::action_consume);
            $this->consuming = true;
        }
    }

    private function send($type, $message=""){
        if(!$this->connected || !@fwrite($this->sock, 
                $this->encode(self::BinaryFrame, pack("ca*", $type , $message)))){
            $this->connected = false;
            throw new prism_exception("websocket is not connected");
        }
    }

    public function ack($tid){
        return $this->send(prism_notify::action_ack, $tid);
    }

    public function get(){
        if(!$this->consuming){
            $this->consume();
        }
        while (!isset($this->messages[0])){
            $this->recv_message();
        }

        return new prism_message($this, array_shift($this->messages));
    }

    public function handle_upgrade($c, $sock){
        $this->client->log("connected");
        $this->connected = true;
        $this->consuming = false;
        $this->sock = $sock;
        register_shutdown_function(array($this, 'close'));
    }

    private function connect(){
        $headers = array('Upgrade'=>'websocket',
                      'Sec-Websocket-Key' => $this->wskey(),
                      'Sec-WebSocket-Version' => 13,
                      'Sec-WebSocket-Protocol' => 'chat',
                      'Origin' => $this->client->base_url.'/platform/notify',
                      'Connection'=>'Upgrade');
        $error = $this->client->get('platform/notify', array(), $headers);
        if($error){
            if (is_object($error)){
                $error = $error->message;
            }
            $this->client->log("websocket handshake error: ".$error);
        }
    }
    
    private function recv_message(){
        $raw = fread($this->sock, 8192);
        $raw = $this->last_buf . $raw;
        $this->last_buf = '';
        $i = 0;

        while($raw){
            $i ++;
            $len = ord($raw[1]) & ~128;
            $data = substr($raw, 2);

            if ($len == 126) {
                $arr = unpack("n", $data);
                $len = array_pop($arr);
                $data = substr($data, 2);
            } elseif ($len == 127) {
                list(, $h, $l) = unpack('N2', $data);
                $len = ($l + ($h * 0x0100000000));
                $data = substr($data, 8);
            }
            if(strlen($data)>=$len){
                array_push($this->messages, substr($data, 0, $len));
                $raw = substr($data, $len);
            }else{
                $this->last_buf = $raw;
                return $i;
            }
        }
        return $i;
    }

    private function encode($type, $data='') {
        $b1 = 0x80 | ($type & 0x0f);
        $length = strlen($data);
        
        if($length <= 125)
            $header = pack('CC', $b1, 128 + $length);
        elseif($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 128 + 126, $length);
        elseif($length >= 65536)
            $header = pack('CCN', $b1, 128 + 127, $length);

        $key = 0;
        $key = pack("N", rand(0, pow(255, 4) - 1));
        $header .= $key;
        
        return $header.$this->rotMask($data, $key);
    }

    private function wskey(){
        return base64_encode(time());
    }

    private function rotMask($data, $key, $offset = 0) {
        $res = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $j = ($i + $offset) % 4;
            $res .= chr(ord($data[$i]) ^ ord($key[$j]));
        }

        return $res;
    }

}

class prism_exception extends Exception{

}

class prism_command{
    var $type;
    var $data;

    function __construct($type, $data=""){
        $this->type = $type;
        $this->data = $data;
    }

    function __toString(){
        return json_encode(array(
                'type'=> &$this->type,
                'data'=> &$this->data,
            ));
    }
}

class prism_message{

    public $body;
    public $content_type;
    private $conn;
    private $raw;
    private $tid;

    function __construct($conn, $raw){
        $this->raw = $raw;
        $this->data = json_decode($raw);
        $this->conn = $conn;
        if($this->data){
            $this->body = &$this->data->body;
            $this->tid = &$this->data->tag;
            $this->content_type = $this->data->type;
        }else{
            $this->body = &$this->raw;
        }
    }

    function ack(){
        return $this->conn->ack($this->tid);
    }

    function __toString(){
        return (string)$this->body;
    }
}