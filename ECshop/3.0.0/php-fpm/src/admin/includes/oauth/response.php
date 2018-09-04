<?php

class oauth2_response extends oauth2 {

    function __construct($config){
        #parent::__construct($config);
    }

    public function success($data)
    {
        $r = array(
            'status' => 'success',
            'data' => $data,
        );
        return json_encode($r);
    }

    public function fail($code, $data)
    {
        $r = array(
            'status' => 'error',
            'code' => $code,
            'data' => $data,
        );
        return json_encode($r);
    }


}
