<?php
include_once(dirname(__FILE__)."/response.php");
include_once(dirname(__FILE__)."/request.php");

class oauth2{
    protected $token_type = 'Bearer';

    private $__request;
    private $__response;
    private $__config;
    private $_token='';



    function __construct($config){
        $this->key = $config['key'];
        $this->secret = $config['secret'];
        $this->site = $config['site'];
        $this->oauth = $config['oauth'];
        $this->__config = $config;
    }

    public function get_token($code)
    {
        $url = "{$this->oauth}/token";
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => "",
            'client_id'=>$this->key,
            'client_secret'=>$this->secret,
        );
        $this->request()->get_info($url, $data);
        $params = $this->request()->parsed();

        $token = $params['access_token'];
        unset($params['expires_in'], $params['token_type'], $params['access_token']);

        $this->token = $token;
        return array('token'=>$token, 'params'=>$params);
    }

    public function authorize_url($callback,$view=null){
        $data = array(
            'response_type' => 'code',
            'client_id' => $this->key,
            'redirect_uri'=>$callback,
            'view' => $view,
        );
        $query = http_build_query($data);
        $url = "{$this->oauth}/authorize?{$query}";
        return $url;
    }

    public function logout_url($callback)
    {
        $data = array(
            'redirect_uri'=>$callback,
        );
        $query = http_build_query($data);
        $url = "{$this->oauth}/logout?{$query}";
        return $url;

    }

    public function request($token=null)
    {
        if (!$this->__request || $this->_token !== $token){
            $this->_token = $token;
            $this->__request = new oauth2_request($this->__config, $token);
        }
        return $this->__request;
    }

    public function response()
    {
        if (!$this->__response)
            $this->__response = new oauth2_response($this->__config);
        return $this->__response;
    }

    public function verify_api_params()
    {
        $arr_get = $_GET;
        $arr_post = $_POST;
        $sign = $arr_get['sign'];
        $arr_get = $arr_get['sign'];
        return true;
    }

    public function sign($action, $url, $header, $get, $post, $signtime=null)
    {
        $parse_url = parse_url($url);
        $path = $parse_url['path'];
        $get_data['sign_method'] = 'md5';
        $get_data['sign_time'] = $signtime ? $signtime : time();
        $get_data['client_id'] = $this->key;
        $get_data = array_merge($get, $get_data);
        $get_data = $this->ksort($get_data);
        $post_data = $this->ksort($post);
        $header_data = $this->ksort($header);

        $get_params = rawurlencode(urldecode(http_build_query($get_data)));
        $post_params = rawurlencode(urldecode(http_build_query($post_data)));
        $header_params = $header_data?rawurlencode(urldecode(http_build_query($header_data))):'';


        $path = rawurlencode('/'.ltrim($path, '/'));

        $orgsign = "{$this->secret}&".strtoupper($action)."&{$path}&{$header_params}&{$get_params}&{$post_params}&{$this->secret}";
        
        $sign = strtoupper(md5($orgsign));

        $get_data['sign'] = $sign;
        return $get_data;
    }

    
    private function build_query($row, $pre=null)
    {
        $r = null;
        foreach($row as $k => $v) {
            if(is_array($v)){
                $r .= '&'. $this->build_query($v, ($pre ? "{$pre}[$k]" : $k));
            } else {
                $r .= '&'. ($pre ? "{$pre}[{$k}]" : $k) . "=" .$v;
            }
        }
        return $r;
    }

    private function ksort($data)
    {
        if(empty($data)) return $data;
        ksort($data);
        foreach($data as $key => &$val){
            if (is_array($val)) {
                $val = $this->ksort($val);
            }
        }
        return $data;
    }




}

