<?php
/**
 * HomePage: https://github.com/KamiOrz
 * Fixed by XiaoGai.
 * Date: 2016/3/17
 * Time: 17:00
 */

namespace KamiOrz\Cors;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsService {

    /**
     * 允许源
     * @var array
     */
    private $allowOrigins = [];

    /**
     * 允许请求方法
     * @var array
     */
    private $allowMethods = [];

    /**
     * 允许头类型
     * @var array
     */
    private $allowHeaders = [];

    /**
     * 允许传递认证信息
     * @var bool
     */
    private $allowCredentials = false;

    /**
     * 输出头类型
     * @var array
     */
    private $exposeHeaders = [];

    /**
     * 此次预请求处理有效时间
     * @var int
     */
    private $maxAge = 0;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    /**
     * 处理预请求 OPTIONS
     * @param Request $request
     * @return Response
     */
    public function handlePreflightRequest(Request $request)
    {
        $origin = $request->headers->get('Origin');
        if(!$this->isOriginAllowed($origin)){
            return $this->createErrorResponse('Origin not allowed.', 403);
        }

        $method = strtoupper($request->headers->get('Access-Control-Request-Method'));

        if(!$this->isMethodAllowed($method)){
            return $this->createErrorResponse('Method not allowed.', 405);
        }

        if(!$this->allowHeaders && $request->headers->has('Access-Control-Request-Headers')){
            $headers = explode(',', $request->headers->get('Access-Control-Request-Headers'));

            foreach($headers as $header){
                if(!$this->isHeaderAllowed($header)){
                    return $this->createErrorResponse('Header not allowed.', 403);
                }
            }
        }

        return $this->createPreflightResponse($request);
    }

    /**
     * 处理跨域请求
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleRequest(Request $request, $response)
    {

        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));

        $vary = $request->headers->has('Vary')?$request->headers->get('Vary').', Origin' : 'Origin';

        $response->headers->set('Vary', $vary);

        if($this->allowCredentials){
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        if($this->exposeHeaders){
            $response->headers->set('Access-Control-Expose-Headers', implode(', ', $this->exposeHeaders));
        }

        return $response;
    }

    /**
     * 判断是否是跨域请求
     * @param Request $request
     * @return bool
     */
    public function isCorsRequest(Request $request)
    {
        return $request->headers->has('Origin');
    }

    /**
     * 判断是否是预请求
     * @param Request $request
     * @return bool
     */
    public function isPreflightRequest(Request $request)
    {
//        header('X-Request-options: isPreflight');
        return $this->isCorsRequest($request) && $request->isMethod('OPTIONS') && $request->headers->has('Access-Control-Request-Method');

    }

    /**
     * 判断是否是允许请求的来源
     * @param Request $request
     * @return bool
     */
    public function isRequestAllowed(Request $request)
    {
        return $this->isOriginAllowed($request->headers->get('Origin'));
    }

    /**
     * 设置各选项
     * @param array $config
     */
    protected function configure(array $config)
    {
        if(isset($config['allowOrigins'])){
            if(in_array('*', $config['allowOrigins'])){
                $this->allowOrigins = true;
            }else{
                foreach($config['allowOrigins'] as $origin){
                    $this->allowOrigin($origin);
                }
            }
        }

        if(isset($config['allowHeaders'])){
            if(in_array('*', $config['allowHeaders'])){
                $this->allowHeaders = true;
            }else{
                foreach($config['allowHeaders'] as $header){
                    $this->allowHeader($header);
                }
            }
        }


        if(isset($config['allowMethods'])){
            if(in_array('*', $config['allowMethods'])){
                $this->allowMethods = true;
            }else{
                foreach($config['allowMethods'] as $method){
                    $this->allowMethod($method);
                }
            }
        }

        if(isset($config['allowCredentials'])){
            $this->allowCredentials = $config['allowCredentials'];
        }

        if(isset($config['exposeHeaders'])){
            foreach($config['exposeHeaders'] as $header){
                $this->exposeHeader($header);
            }
        }

        if(isset($config['maxAge'])){
            $this->maxAge = $config['maxAge'];
        }
    }

    /**
     * 创建预请求的响应头
     *
     * @param Request $request
     * @return Response
     */
    protected function createPreflightResponse(Request $request)
    {
        $response = new Response();

        //设置输出 allowOrigin
        $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));

        if($this->allowCredentials){
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        if($this->maxAge){
            $response->headers->set('Access-Control-Max-Age', $this->maxAge);
        }

        $allowMethods = $this->allowMethods === true
            ? strtoupper($request->headers->get('Access-Control-Request-Method'))
            : implode(', ', $this->allowMethods);

        //设置输出 allowMethods
        $response->headers->set('Access-Control-Allow-Methods', $allowMethods);

        $allowHeaders = $this->allowHeaders === true
            ? strtoupper($request->headers->get('Access-Control-Request-Headers'))
            : implode(', ', $this->allowHeaders);

        //设置输出 allowHeaders
        $response->headers->set('Access-Control-Allow-Headers', $allowHeaders);

        return $response;
    }

    /**
     * 创建错误响应头
     *
     * @param string $content
     * @param int $status
     * @return Response
     */
    protected function createErrorResponse($content = '', $status = 400)
    {
        return new Response($content, $status);
    }

    /**
     * 获取允许 Origin
     * @param string $origin
     */
    protected function allowOrigin($origin)
    {
        $this->allowOrigins[] = $origin;
    }

    /**
     * 获取允许 Method
     * @param string $method
     */
    protected function allowMethod($method)
    {
        $this->allowMethods[] = strtoupper($method);
    }

    /**
     * 获取允许 Header
     * @param string $header
     */
    protected function allowHeader($header)
    {
        $this->exposeHeaders[] = strtoupper($header);
    }

    /**
     * 获取允许 expose header
     * @param string $header
     */
    protected function exposeHeader($header)
    {
        $this->exposeHeaders[] = strtolower($header);
    }

    /**
     * 判断跨站来源是否允许
     * @param string $origin
     * @return bool
     */
    protected function isOriginAllowed($origin)
    {
        return $this->allowOrigins === true ?:in_array($origin, $this->allowOrigins);
    }

    /**
     * 判断 Mehod 方法是否允许
     * @param string $method
     * @return bool
     */
    protected function isMethodAllowed($method)
    {
        return $this->allowMethods === true?:in_array(strtoupper($method), $this->allowMethods);
    }

    /**
     * 判断 header 头是否允许
     * @param string $header
     * @return bool
     */
    protected function isHeaderAllowed($header)
    {
        return $this->allowHeaders === true ?:in_array(strtoupper($header), $this->allowHeaders);
    }

}