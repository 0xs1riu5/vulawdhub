<?php
class leancloud_client {

    const VERSION = '0.2.5';

    private static $api = "https://api.leancloud.cn";

    private static $apiVersion = "1.1";

    private static $apiTimeout = 15;

    private static $appId;

    private static $appKey;

    private static $useProduction = false;

    private static $defaultHeaders;

    public static function initialize($appId, $appKey) {
        self::$appId        = $appId;
        self::$appKey       = $appKey;

        self::$defaultHeaders = array(
            'X-LC-Id' => self::$appId,
            'Content-Type' => 'application/json;charset=utf-8',
            'User-Agent'   => self::getVersionString()
        );
    }

    private static function assertInitialized() {
        if (!isset(self::$appId) &&
            !isset(self::$appKey)) {
            throw new \RuntimeException("Client is not initialized, " .
                                        "please specify application key " .
                                        "with Client::initialize.");
        }
    }

    private static function getVersionString() {
        return "LeanCloud PHP SDK " . self::VERSION;
    }

    public static function useProduction($flag) {
        self::$useProduction = $flag ? true : false;
    }

    public static function getAPIEndPoint() {
        return self::$api . "/"  . self::$apiVersion;
    }

    public static function buildHeaders() {

        $h = self::$defaultHeaders;

        $h['X-LC-Prod'] = self::$useProduction ? 1 : 0;

        $timestamp = time();
        $key       = self::$appKey;
        $sign      = md5($timestamp . $key);
        $h['X-LC-Sign'] = $sign . "," . $timestamp;
        
        return $h;
    }

    public static function verifySign($appId, $sign) {
        if (!$appId || ($appId != self::$appId)) {
            return false;
        }
        $parts = explode(",", $sign);
        $key   = self::$appKey;

        return $parts[0] === md5(trim($parts[1]) . $key);
    }

    public static function verifyKey($appId, $key) {
        if (!$appId || ($appId != self::$appId)) {
            return false;
        }
        $parts = explode(",", $key);

        return self::$appKey === $parts[0];
    }

    public static function request($method, $path, $data, $headers=array()) {
        self::assertInitialized();
        $url  = self::getAPIEndPoint();
        $url .= $path;

        $defaultHeaders = self::buildHeaders();
        if (empty($headers)) {
            $headers = $defaultHeaders;
        } else {
            $headers = array_merge($defaultHeaders, $headers);
        }
        if (strpos($headers["Content-Type"], "/json") !== false) {
            $json = json_encode($data);
        }

        // Build headers list in HTTP format
        $headersList = array_map(function($key, $val) { return "$key: $val";},
                                 array_keys($headers),
                                 $headers);

        $req = curl_init($url);
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($req, CURLOPT_HTTPHEADER, $headersList);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_TIMEOUT, self::$apiTimeout);
        // curl_setopt($req, CURLINFO_HEADER_OUT, true);
        switch($method) {
            case "GET":
                if ($data) {
                    // append GET data as query string
                    curl_setopt($req, CURLOPT_URL,
                                $url ."?". http_build_query($data));
                }
                break;
            case "POST":
                curl_setopt($req, CURLOPT_POST, 1);
                curl_setopt($req, CURLOPT_POSTFIELDS, $json);
                break;
            case "PUT":
                curl_setopt($req, CURLOPT_POSTFIELDS, $json);
                curl_setopt($req, CURLOPT_CUSTOMREQUEST, $method);
            case "DELETE":
                curl_setopt($req, CURLOPT_CUSTOMREQUEST, $method);
                break;
            default:
                break;
        }
        $resp     = curl_exec($req);
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $respType = curl_getinfo($req, CURLINFO_CONTENT_TYPE);
        $error    = curl_error($req);
        $errno    = curl_errno($req);
        curl_close($req);
        /** type of error:
          *  - curl connection error
          *  - http status error 4xx, 5xx
          *  - rest api error
          */
        if ($errno > 0) {
            throw new \RuntimeException("CURL connection ($url) error: " .
                                        "$errno $error",
                                        $errno);
        }
        if (strpos($respType, "text/html") !== false) {
            self::error("Bad request", 400);
        }

        $data = json_decode($resp, true);
        if (isset($data["error"])) {
            $code = isset($data["code"]) ? $data["code"] : 400;
            self::error("{$data['error']}", $code);
        }
        return $data;
    }

    public static function get($path, $data=null, $headers=array()) {
        return self::request("GET", $path, $data,
                             $headers);
    }

    public static function post($path, $data, $headers=array()) {
        return self::request("POST", $path, $data,
                             $headers);
    }

    public static function put($path, $data, $headers=array()) {
        return self::request("PUT", $path, $data,
                             $headers);
    }

    public static function delete($path, $headers=array()) {
        return self::request("DELETE", $path, null,
                             $headers);
    }

    public static function batch($requests, $headers=array()) {
        $response = self::post("/batch", array("requests" => $requests), $headers);
        if (count($requests) != count($response)) {
            self::error("Number of resquest and response " .
                                    "mismatch in batch operation!");
        }
        return $response;
    }

    /**
     * Encode file with params in multipart format
     *
     * @param array  $file     File data and attributes
     * @param array  $params   Key-value params
     * @param string $boundary Boundary string used for frontier
     * @return string          Multipart encoded string
     */
    public static function multipartEncode($file, $params, $boundary=null) {
        if (!$boundary) {
            $boundary = md5(microtime());
        }

        $body = "";
        forEach($params as $key => $val) {
            $body .= <<<EOT
--{$boundary}
Content-Disposition: form-data; name="{$key}"

{$val}

EOT;
        }

        if (!empty($file)) {
            $mimeType = "application/octet-stream";
            if (isset($file["mimeType"])) {
                $mimeType = $file["mimeType"];
            }
            // escape quotes in file name
            $filename = filter_var($file["name"],
                                   FILTER_SANITIZE_MAGIC_QUOTES);

            $body .= <<<EOT
--{$boundary}
Content-Disposition: form-data; name="file"; filename="{$filename}"
Content-Type: {$mimeType}

{$file['content']}

EOT;
        }

        // append end frontier
        $body .=<<<EOT
--{$boundary}

EOT;

        return $body;
    }

    /**
     * Upload file content to Qiniu storage
     *
     * @param string $token    Qiniu token
     * @param string $content  File content
     * @param string $name     File name
     * @param string $mimeType MIME type of file
     * @return array           JSON response from qiniu
     * @throws CloudException, RuntimeException
     */
    public static function uploadToQiniu($token, $content, $name, $mimeType=null) {
        $boundary = md5(microtime());
        $file     = array("name"     => $name,
                          "content"  => $content,
                          "mimeType" => $mimeType);
        $params   = array("token" => $token, "key" => $name);
        $body     = static::multipartEncode($file, $params, $boundary);

        $headers[] = "User-Agent: " . self::getVersionString();
        $headers[] = "Content-Type: multipart/form-data;" .
                     " boundary={$boundary}";
        $headers[] = "Content-Length: " . strlen($body);

        $url = "http://upload.qiniu.com";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $resp     = curl_exec($ch);
        $respCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $respType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $error    = curl_errno($ch);
        $errno    = curl_errno($ch);
        curl_close($ch);

        /** type of error:
         *  - curl error
         *  - http status error 4xx, 5xx
         *  - rest api error
         */
        if ($errno > 0) {
            throw new \RuntimeException("CURL connection ($url) error: " .
                                        "$errno $error",
                                        $errno);
        }

        $data = json_decode($resp, true);
        if (isset($data["error"])) {
            $code = isset($data["code"]) ? $data["code"] : 400;
            self::error("{$data['error']}", $code);
        }
        return $data;
    }

    public static function error($message, $code = 400)
    {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(Helper::formatResponse([
                    'error'=>$code, 'data' => $message
                ]));
        exit;
    }
}

