<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

$dopost = isset($dopost)? $dopost : '';

/**
 *  远程获取数据函数
 *  例如：DownHost($url, '', 'GET', NULL, NULL, $user, $pass);
 *
 * @access    public
 * @param     string   站点地址
 * @param     string   附加数据
 * @param     string   请求方法，GET POST方式
 * @param     string   显示agent
 * @param     int      端口号
 * @param     string   用户名，便于Authorization: Basic请求
 * @param     string   密码
 * @param     int      超限时间
 * @return    array
 */
function DownHost($host,$data='',$method='GET',$showagent=null,$port=null,$user='',$pwd='',$timeout=30)
{
    $reval = array();
    $parse = @parse_url($host);
    if (empty($parse)) return false;
    if ((int)$port>0) {
        $parse['port'] = $port;
    } elseif (!isset($parse['port'])) {
        $parse['port'] = '80';
    }
    if(!empty($user)) $parse['user'] = $user;
    if(!empty($pwd)) $parse['pass'] = $pwd;
    
    $parse['host'] = str_replace(array('http://','https://'),array('','ssl://'),"$parse[scheme]://").$parse['host'];
    if (!$fp=@fsockopen($parse['host'],$parse['port'],$errnum,$errstr,$timeout)) {
        return false;
    }
    $method = strtoupper($method);
    $wlength = $wdata = $responseText = '';
    $parse['path'] = str_replace(array('\\','//'),'/',$parse['path'])."?$parse[query]";
    
    $headers = '';
    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2)';
    $headers .= "User-Agent: " . $agent . "\r\n";
    
    $accept = 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*';
    $headers .= "Accept: " . $accept . "\r\n";
    

    
    if (! empty($parse['user']) || ! empty($parse['pass']))
    $headers .= "Authorization: Basic " . base64_encode($parse['user'] . ":" . $parse['pass']) . "\r\n";
    
    $content_type = '';
    if(!empty($content_type))
    {
        $headers .= "Content-type: $content_type";
        if ($content_type == "multipart/form-data")
            $headers .= "; boundary=dede" . md5(uniqid(microtime()));
        $headers .= "\r\n";
    }
    
    if ($method=='GET') {
        $separator = $parse['query'] ? '&' : '';
        substr($data,0,1)=='&' && $data = substr($data,1);
        $parse['path'] .= $separator.$data;
    } elseif ($method=='POST') {
        $wlength = "Content-length: ".strlen($data)."\r\n";
        $wdata = $data;
    }
    
    $write = "$method $parse[path] HTTP/1.0\r\nHost: $parse[host]\r\n{$wlength}{$headers}\r\n$wdata";
    // dump($write);
    
    @fwrite($fp,$write,strlen($write));
    
    while ($currentHeader = fgets($fp, 4096)) {
        if ($currentHeader == "\r\n")
            break;
        // 根据返回信息判断是否跳转
        if (preg_match("/^(Location:|URI:)/i", $currentHeader)) {
            preg_match("/^(Location:|URI:)[ ]+(.*)/i", chop($currentHeader), $matches);
            if (! preg_match("|\:\/\/|", $matches[2])) {
                $_redirectaddr = $parse["scheme"] . "://" . $parse['host'] . ":" . $parse['port'];
                if (! preg_match("|^/|", $matches[2]))
                    $_redirectaddr .= "/" . $matches[2];
                else
                    $_redirectaddr .= $matches[2];
            } else {
                $_redirectaddr = $matches[2];
            }
            return DownHost($_redirectaddr,$data,$method,$showagent,$port,$user,$pwd,$timeout);
        }
        $reval['status'] = '';
        if (preg_match("|^HTTP/|", $currentHeader)) {
            if (preg_match("|^HTTP/[^\s]*\s(.*?)\s|", $currentHeader, $status)) 
            {
                $reval['status'] = $status[1];
            }
        }
    }
    
    $reval['results'] = '';
    do {
        $_data = fread($fp, 500000);
        if (strlen($_data) == 0) {
            break;
        }
        $reval['results'] .= $_data;
    } while (true);
    @fclose($fp);
    return $reval;
}

if ($dopost == 'getcode')
{
    $uuid = isset($uuid)? $uuid : '';
    $codeOrder = 'qqmb%2csinaminiblog%2csohubai%2cbaiduhi%2crenren%2cbgoogle';
    $remoteUrl = 'http://updatenew.dedecms.com/base-v57/dedecms/plus_bshare.txt';
    $result = DownHost($remoteUrl);
    $codeOrder = isset($result['results'])? $result['results'] : $codeOrder;
    echo <<<EOT
<style type="text/css">
iframe { border-style: none; }
body { margin: 0px;padding: 0px; }
</style>
<iframe src="http://www.bshare.cn/moreStylesEmbed?uuid=$uuid&bp=$codeOrder" name="bshare" width="780px" height="300px" scrolling="yes">
 </iframe>
EOT;
    exit;
}

// BShare前台动态JS调用
$bscodeFile = DEDEDATA.'/cache/bshare.code.inc';
$reval = file_get_contents($bscodeFile);

echo "document.write('".$reval."');\r\n";