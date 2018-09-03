<?php
/**
 * Bshare合作插件
 *
 * @version        $Id: plus_bshare.php 5 13:23 2011-5-19 tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/json.class.php");

// 引入BShare配置文件
@include_once DEDEDATA.'/cache/bshare.inc';
$bscodeFile = DEDEDATA.'/cache/bshare.code.inc';

$do = isset($do)? $do : '';
$starttime = empty($starttime)? MyDate('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-10, date("Y"))) : $starttime;
$endtime = empty($endtime)? MyDate('Y-m-d', time()) : $endtime;

$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

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

/**
 *   获取远程接口数据
 *
 * @access    public
 * @param     string
 * @return    string
 */
function GetAnalyticsDate($url)
{
    global $cfg_bshare,$json,$cfg_soft_lang;
    $user = $cfg_bshare['user'];
    $pass = $cfg_bshare['pwd'];
    $results = DownHost($url, '', 'GET', NULL, NULL, $user, $pass);

    try
    {
        $result = $json->decode($results['results']);
    }
    catch ( Exception $e )
    {
        trigger_error("Server Error:".$result);
    }

    //if ($cfg_soft_lang == 'gb2312')
    //{
        $result = AutoCharset($result, 'utf-8', 'gbk');
    //}
    return $result;
}

function CeilTen($num) 
{ 
    return (ceil($num / 10) * 10); 
}

function GetAnalyticsPicUrl($type='click', $starttime='', $endtime='')
{
    global $cfg_bshare;
    $url = "http://api.bshare.cn/analytics/{$cfg_bshare['uuid']}/{$type}.json?dateStart={$starttime}&dateEnd={$endtime}&locale=zh";
    
    $result = GetAnalyticsDate($url);
    $chxl = $ch1 = $t = array();
    if (isset($result['analytics']) && count($result['analytics']) > 1)
    {
        $total = count($result['analytics']);
        
        
        foreach ($result['analytics'] as $key => $value) {
            
            if ($key % (CeilTen($total)/10) == 0)
            {
                $chxl = array_merge($chxl, array(substr($value['metric'],5,5)));
            } else {
                $chxl = array_merge($chxl, array(''));
            }
            $ch1 = array_merge($ch1, array($value['count']));
            $t = array_merge($t, array($value['count']));
        }
        $tStr = implode(',', $t);
        $chxlStr = implode('|', $chxl);
        $ch1Str = implode('|', $ch1);
        $min = min($t);
        $max = max($t);
        // echo $max;exit;
        
        $googleurl[] = "https://chart.googleapis.com/chart?";
        $googleurl[] = "chxl=0:|{$chxlStr}|2:|{$ch1Str}&";
        // $googleurl[] = "&chds=0,{$max}&";
        $googleurl[] = "chxt=x,y,t&";
        $googleurl[] = "chma=0,0,10,0&";
        $googleurl[] = "chds={$min},{$max},{$min},{$max}&";
        $googleurl[] = "chls=1,2.5&";
        $googleurl[] = "chxr=1,{$min},{$max}&";
        $googleurl[] = "chbh=a,1&";
        $googleurl[] = "chs=540x200&";
        $googleurl[] = "cht=bvs&";
        $googleurl[] = "chd=t:{$tStr}";
        $reval = implode('', $googleurl);
    } else {
        $reval = 'images/dfpic.gif';
    }
    
    return $reval;
}

function GetAnalyticsPlatform($starttime='', $endtime='')
{
    global $cfg_bshare;
    $url = "http://api.bshare.cn/analytics/{$cfg_bshare['uuid']}/platform.json?dateStart={$starttime}&dateEnd={$endtime}&locale=zh";

    $result = GetAnalyticsDate($url);
    
    if (isset($result['analytics']) && count($result['analytics']) > 1)
    {
        $total = 0;
        foreach ($result['analytics'] as $key => $value) {
            $total += $value['count'];
        }
        $colors = array('FFC6A5', 'FFFF42', 'DEF3BD', '00A5C6', 'DEBDDE',
            '109618','990099','0F4B93', 'FF6600','C0DCC0'
        );
        $ch1 = $t = $chco = array();

        if ($total > 0)
        {
            foreach ($result['analytics'] as $key => $value) 
            {
                $varPercent = sprintf("%d",($value['count']/$total) * 100);
                $t = array_merge($t, array($varPercent));
                $chco = array_merge($chco, array($colors[$key % 10]));
                $metric = urlencode(gb2utf8($value['metric']));
                $ch1 = array_merge($ch1, array("{$metric}.($varPercent%)-{$value['count']}"));
            }
        }
        $tStr = implode(',', $t);
        $chcoStr = implode('|', $chco);
        $ch1Str = implode('|', $ch1);

        
        $googleurl[] = "https://chart.googleapis.com/chart?";
        $googleurl[] = "cht=p3&";
        $googleurl[] = "chs=540x200&";
        $googleurl[] = "chd=t:{$tStr}&chl={$ch1Str}&chco={$chcoStr}";
        $reval = implode('', $googleurl);
        unset($googleurl);
        unset($result);
    } else {
        $reval = 'images/dfpic.gif';
    }
    return $reval;
}

// 写入BShare的缓存文件
function WriteBshareCache($open='false', $user='', $pwd='', $uuid='')
{
    $cacheFile = DEDEDATA.'/cache/bshare.inc';
    $cacheStr = <<<EOT
<?php if(!defined('DEDEINC')) exit("Request Error!");
global \$cfg_bshare;
\$cfg_bshare = array();
\$cfg_bshare['open'] = $open;
\$cfg_bshare['user'] = '$user';
\$cfg_bshare['pwd'] = '$pwd';
\$cfg_bshare['uuid'] = '$uuid';
?>
EOT;
    return file_put_contents($cacheFile, $cacheStr);
}


if(empty($do))
{
    if (!isset($cfg_bshare['open']) || !$cfg_bshare['open'])
    {
        include DEDEADMIN.'/templets/plus_bshare.htm';
    } else {
        $bshareCode = stripslashes(file_get_contents($bscodeFile));
        // 分析统计信息
        //{{{
        $platformUrl = GetAnalyticsPlatform($starttime, $endtime);

        // ------------------------------------------------------------------------
        // 获取点击和统计表信息
        $shareUrl = GetAnalyticsPicUrl('share', $starttime, $endtime);
        $clickUrl = GetAnalyticsPicUrl('click', $starttime, $endtime);
    
        include DEDEADMIN.'/templets/plus_bshare_state.htm';
    }
} 
// 开通BShare服务
else if($do == 'open') 
{
    $email = isset($user)? $user : '';
    $password = isset($pwd)? $pwd : '';
    $submode = isset($submode)? $submode : 0;
    if (empty($email) || empty($password))
    {
        ShowMsg('请输入邮箱地址和用户密码！', 'plus_bshare.php');
        exit;
    }
    $cfg_bshare['open'] = isset($cfg_bshare['open'])? $cfg_bshare['open'] : false;
    
    if (!$cfg_bshare['open'] && empty($cfg_bshare['uuid']))
    {
        $domain = $_SERVER['HTTP_HOST'];
        $openUrl = "http://api.bshare.cn/analytics/reguuid.json?email={$email}&password={$password}&domain={$domain}&source=dedecms";
        $results = DownHost($openUrl, '', 'GET', NULL, NULL);
        
        $result = $json->decode($results['results']);
        $uuid = $result['uuid'];
        
        if (is_null($result))
        {
            // 错误处理
            if ($results['status'] == 400 && $submode==0) 
            {
                ShowMsg('输入的用户名已经存在，请重新输入', 'plus_bshare.php');
                exit;
            } else if ($results['status'] == 401)
            {
                ShowMsg('您输入的BShare密码错误，请重新输入确保其正确', 'plus_bshare.php');
                exit;
            }
        }

        if (!isset($uuid))
        {
            ShowMsg('远程获取BShare的uuid错误，您需要重新尝试。', 'plus_bshare.php');
            exit;
        }
        // 保存缓存文件
        WriteBshareCache('TRUE', $email, $password, $uuid);
        // 写入默认代码
        $codeOrder = 'qqmb%2csinaminiblog%2csohubai%2cbaiduhi%2crenren%2cbgoogle';
        $remoteUrl = 'http://updatenew.dedecms.com/base-v57/dedecms/plus_bshare.txt';
        $result = DownHost($remoteUrl);
        $codeOrder = isset($result['results'])? $result['results'] : $codeOrder;
        $tplCode = <<<EOT
<a class="bshareDiv" href="http://www.bshare.cn/share">分享按钮</a><script language="javascript" type="text/javascript" src="http://www.bshare.cn/button.js#uuid=$uuid&style=2&textcolor=#000&bgcolor=none&bp={$codeOrder}&ssc=false&sn=true&text=分享到"></script>   
EOT;
        $putfileFunc = function_exists('PutFile')? 'PutFile' : 'file_put_contents';
        $putfileFunc($bscodeFile, $tplCode);
        ShowMsg('已经开通BShare服务，下面我们来进行体验吧。', 'plus_bshare.php');
        exit;
    } else {
        ShowMsg('是否已经开启BShare服务，如果已经开启无需重复开启服务。', '-1');
        exit;
    }
    
}
// 设定样式
else if ($do == 'setcode')
{
    $bscode = isset($bscode)? $bscode : '';
    
    PutFile($bscodeFile, $bscode);
    ShowMsg('成功设定BShare代码', 'plus_bshare.php');
    exit;
}

