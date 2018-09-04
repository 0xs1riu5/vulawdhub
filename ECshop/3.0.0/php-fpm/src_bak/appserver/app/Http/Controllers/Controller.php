<?php
//
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Validator;
use Log;
use App\Helper\Token;
use App\Helper\XXTEA;
use App\Models\BaseModel;
use Illuminate\Pagination\Paginator;

class Controller extends BaseController
{
    public $validated;
    public $request;

    public function __construct() {
        $this->request = app('request');
    }

    /**
     * 验证输入信息
     * @param  array $rules
     * @return response
     */
    public function validateInput($rules)
    {
        $requests = $this->request->all();

        $validator = Validator::make($requests, $rules);
        if ($validator->fails()) {
            return self::json(BaseModel::formatError(BaseModel::BAD_REQUEST, $validator->messages()->first()));
        } else {
            $this->validated = array_intersect_key($requests, $rules);
            $this->validated = $requests ;
            return false;
        }
    }

    /**
     * 自定义验证
     */
    public function customValidate($requests, $rules)
    {
        $validator = Validator::make($requests, $rules);
        if ($validator->fails()) {
            return self::json(BaseModel::formatError(BaseModel::BAD_REQUEST, $validator->messages()->first()));
        } else {
            return false;
        }
    }

    /**
     * 返回Json数据
     * @param  array   $data
     * @param  array   $ext
     * @param  array   $paged
     * @return json
     */
    public function json($body = false)
    {
        //过滤null为空字符串(需协调客户端兼容)
        // if ($body) {
        //     $body = format_array($body);
        // }

        // 写入日志
        if (config('app.debug')) {

            $debug_id = uniqid();

            Log::debug($debug_id,[
                'LOG_ID'         => $debug_id,
                'IP_ADDRESS'     => $this->request->ip(),
                'REQUEST_URL'    => $this->request->fullUrl(),
                'AUTHORIZATION'  => $this->request->header('X-'.config('app.name').'-Authorization'),
                'REQUEST_METHOD' => $this->request->method(),
                'PARAMETERS'     => $this->validated,
                'RESPONSES'      => $body
            ]);

            $body['debug_id'] = $debug_id;
        }

        if (isset($body['error']) && $body['error']) {
            unset($body['error']);
            $response = response()->json($body);
            $response->header('X-'.config('app.name').'-ErrorCode', $body['error_code']);
            $response->header('X-'.config('app.name').'-ErrorDesc', urlencode($body['error_desc']));
        } else {
            $response = response()->json($body);
            $response->header('X-'.config('app.name').'-ErrorCode', 0);
        }

        if (config('token.refresh')) {
            if ($new_token = Token::refresh()) {
                // 生成新token
                $response->header('X-'.config('app.name').'-New-Authorization', $new_token);
            }
        }

        return $response;
    }

}