<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-2
 * Time: 下午3:09
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */

namespace Vendor;

class requester {
    /**
     *
     * @var String 请求的完整URL
     */
    public $url;
    /**
     *
     * @var String 请求方式 GET,POST
     */
    public $method = 'GET';
    /**
     *
     * @var String Content-Type eg. 'application/json'
     *      ,'application/xml','application/x-www-form-urlencoded'
     */
    public $content_type = 'application/x-www-form-urlencoded';
    /**
     *
     * @var String 请求过程使用的字符集编码
     */
    public $charset = 'UTF-8';
    /**
     *
     * @var String 请求数据
     */
    public $data;
    /**
     *
     * @var boolean 是否启用cookie
     */
    public $enableCookie;
    /**
     *
     * @var resource 启用cookie发送请求时需要的cookie文件
     */
    public $cookieFile;
    /**
     *
     * @var boolean 启用时会将响应头信息作为数据流输出
     */
    public $enableHeaderOutput;

    /**
     *
     * @param String $charset
     *         请求URL.
     */
    function __construct($url ='') {
        $this->url = $url;
    }

    /**
     * 模拟浏览器发送请求
     *
     * @return array
     *         包含封装的http状态码,响应内容和cookie的数组array(retCode,retContent,retCookieFile).
     */
    public final function request() {
        ini_set ( 'max_execution_time', '0' );

        $ch = curl_init ();

        $header = array (
            'Content-Type: ' . $this->content_type . '; charset=' . strtoupper ( $this->charset ) . '',
            'Content-Length: ' . strlen ( $this->data )
        );

        curl_setopt ( $ch, CURLOPT_URL, $this->url );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $ch, CURLOPT_HEADER, $this->enableHeaderOutput );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );

        //跳过ssl证书检测 --駿濤
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);


        $cookieJar = null;


        if ($this->enableCookie) {
            // 带cookie请求服务器
            curl_setopt ( $ch, CURLOPT_COOKIEFILE, $this->cookieFile );
            // 保存服务器发送的cookie
            $cookieJar = tempnam ( 'tmp', 'cookie' );
            curl_setopt ( $ch, CURLOPT_COOKIEJAR, $cookieJar );
        }

        if (strtoupper ( $this->method ) == 'POST') {
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->data );
        }

        $return_content = curl_exec ( $ch );

        $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );

        curl_close ( $ch );

        return array (
            $return_code,
            $return_content,
            $cookieJar
        );
    }
}
?>