<?php   if(!defined('DEDEINC')) exit('dedecms');
/**
 * 采集小助手
 *
 * @version        $Id: charset.helper.php 1 2010-07-05 11:43:09Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
require_once(DEDEINC."/dedehttpdown.class.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/charset.func.php");

/**
 *  下载图片
 *
 * @access    public
 * @param     string  $gurl  地址
 * @param     string  $rfurl  来源地址
 * @param     string  $filename  文件名
 * @param     string  $gcookie  调整cookie
 * @param     string  $JumpCount  跳转计数
 * @param     string  $maxtime  最大次数
 * @return    string
 */
function DownImageKeep($gurl, $rfurl, $filename, $gcookie="", $JumpCount=0, $maxtime=30)
{
    $urlinfos = GetHostInfo($gurl);
    $ghost = trim($urlinfos['host']);
    if($ghost=='')
    {
        return FALSE;
    }
    $gquery = $urlinfos['query'];
    if($gcookie=="" && !empty($rfurl))
    {
        $gcookie = RefurlCookie($rfurl);
    }
    $sessionQuery = "GET $gquery HTTP/1.1\r\n";
    $sessionQuery .= "Host: $ghost\r\n";
    $sessionQuery .= "Referer: $rfurl\r\n";
    $sessionQuery .= "Accept: */*\r\n";
    $sessionQuery .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";
    if($gcookie!="" && !preg_match("/[\r\n]/", $gcookie))
    {
        $sessionQuery .= $gcookie."\r\n";
    }
    $sessionQuery .= "Connection: Keep-Alive\r\n\r\n";
    $errno = "";
    $errstr = "";
    $m_fp = fsockopen($ghost, 80, $errno, $errstr,10);
    fwrite($m_fp,$sessionQuery);
    $lnum = 0;

    //获取详细应答头
    $m_httphead = Array();
    $httpstas = explode(" ",fgets($m_fp,256));
    $m_httphead["http-edition"] = trim($httpstas[0]);
    $m_httphead["http-state"] = trim($httpstas[1]);
    while(!feof($m_fp))
    {
        $line = trim(fgets($m_fp,256));
        if($line == "" || $lnum>100)
        {
            break;
        }
        $hkey = "";
        $hvalue = "";
        $v = 0;
        for($i=0; $i<strlen($line); $i++)
        {
            if($v==1)
            {
                $hvalue .= $line[$i];
            }
            if($line[$i]==":")
            {
                $v = 1;
            }
            if($v==0)
            {
                $hkey .= $line[$i];
            }
        }
        $hkey = trim($hkey);
        if($hkey!="")
        {
            $m_httphead[strtolower($hkey)] = trim($hvalue);
        }
    }

    //分析返回记录
    if(preg_match("/^3/", $m_httphead["http-state"]))
    {
        if(isset($m_httphead["location"]) && $JumpCount<3)
        {
            $JumpCount++;
            DownImageKeep($gurl,$rfurl,$filename,$gcookie,$JumpCount);
        }
        else
        {
            return FALSE;
        }
    }
    if(!preg_match("/^2/", $m_httphead["http-state"]))
    {
        return FALSE;
    }
    if(!isset($m_httphead))
    {
        return FALSE;
    }
    $contentLength = $m_httphead['content-length'];

    //保存文件
    $fp = fopen($filename,"w") or die("写入文件：{$filename} 失败！");
    $i=0;
    $okdata = "";
    $starttime = time();
    while(!feof($m_fp))
    {
        $okdata .= fgetc($m_fp);
        $i++;

        //超时结束
        if(time()-$starttime>$maxtime)
        {
            break;
        }

        //到达指定大小结束
        if($i >= $contentLength)
        {
            break;
        }
    }
    if($okdata!="")
    {
        fwrite($fp,$okdata);
    }
    fclose($fp);
    if($okdata=="")
    {
        @unlink($filename);
        fclose($m_fp);
        return FALSE;
    }
    fclose($m_fp);
    return TRUE;
}

/**
 *  获得某页面返回的Cookie信息
 *
 * @access    public
 * @param     string  $gurl  调整地址
 * @return    string
 */
function RefurlCookie($gurl)
{
    global $gcookie,$lastRfurl;
    $gurl = trim($gurl);
    if(!empty($gcookie) && $lastRfurl==$gurl)
    {
        return $gcookie;
    }
    else
    {
        $lastRfurl=$gurl;
    }
    if(trim($gurl)=='')
    {
        return '';
    }
    $urlinfos = GetHostInfo($gurl);
    $ghost = $urlinfos['host'];
    $gquery = $urlinfos['query'];
    $sessionQuery = "GET $gquery HTTP/1.1\r\n";
    $sessionQuery .= "Host: $ghost\r\n";
    $sessionQuery .= "Accept: */*\r\n";
    $sessionQuery .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";
    $sessionQuery .= "Connection: Close\r\n\r\n";
    $errno = "";
    $errstr = "";
    $m_fp = fsockopen($ghost, 80, $errno, $errstr,10) or die($ghost.'<br />');
    fwrite($m_fp,$sessionQuery);
    $lnum = 0;

    //获取详细应答头
    $gcookie = "";
    while(!feof($m_fp))
    {
        $line = trim(fgets($m_fp,256));
        if($line == "" || $lnum>100)
        {
            break;
        }
        else
        {
            if(preg_match("/^cookie/i", $line))
            {
                $gcookie = $line;
                break;
            }
        }
    }
    fclose($m_fp);
    return $gcookie;
}

/**
 *  获得网址的host和query部份
 *
 * @access    public
 * @param     string  $gurl  调整地址
 * @return    string
 */
function GetHostInfo($gurl)
{
    $gurl = preg_replace("/^http:\/\//i", "", trim($gurl));
    $garr['host'] = preg_replace("/\/(.*)$/i", "", $gurl);
    $garr['query'] = "/".preg_replace("/^([^\/]*)\//i", "", $gurl);
    return $garr;
}

/**
 *  HTML里的图片转DEDE格式
 *
 * @access    public
 * @param     string  $body  文章内容
 * @return    string
 */
function TurnImageTag(&$body)
{
    global $cfg_album_width,$cfg_ddimg_width;
    if(empty($cfg_album_width))
    {
        $cfg_album_width = 800;
    }
    if(empty($cfg_ddimg_width))
    {
        $cfg_ddimg_width = 150;
    }
    $patten = "/<\\s*img\\s.*?src\\s*=\\s*([\"\\'])?(?(1)(.*?)\\1|([^\\s\\>\"\\']+))/isx";
    preg_match_all($patten,$body,$images);
    $returnArray1 = $images[2];
    $returnArray2 = $images[3];
    foreach ( $returnArray1 as $key => $value )
    {
        if ($value)
        {
          $ttx .= "{dede:img ddimg='$litpicname' text='图 ".($key+1)."'}".$value."{/dede:img}"."\r\n";
        }
        else
        {
          $ttx .= "{dede:img ddimg='$litpicname' text='图 ".($key+1)."'}".$returnArray2[$key]."{/dede:img}"."\r\n";
        }
    }
    $ttx = "\r\n{dede:pagestyle maxwidth='{$cfg_album_width}' ddmaxwidth='{$cfg_ddimg_width}' row='3' col='3' value='2'/}\r\n{dede:comments}图集类型会采集时生成此配置是正常的，不过如果后面没有跟着img标记则表示规则无效{/dede:comments}\r\n".$ttx;
    return $ttx;
}

/**
 *  HTML里的网址格式转换
 *
 * @access    public
 * @param     string  $body  文章内容
 * @return    string
 */
function TurnLinkTag(&$body)
{
    $ttx = '';
    $handid = '服务器';
    preg_match_all("/<a href=['\"](.+?)['\"]([^>]+?)>(.+?)<\/a>/is",$body,$match);
    if(is_array($match[1]) && count($match[1])>0)
    {
        for($i=0;isset($match[1][$i]);$i++)
        {
            $servername = (isset($match[3][$i]) ? str_replace("'","`",$match[3][$i]) : $handid.($i+1));
            if(preg_match("/[<>]/", $servername) || strlen($servername)>40)
            {
                $servername = $handid.($i+1);
            }
            $ttx .= "{dede:link text='$servername'} {$match[1][$i]} {/dede:link}\r\n";
        }
    }
    return $ttx;
}

/**
 *  替换XML的CDATA
 *
 * @access    public
 * @param     string  $str  字符串
 * @return    string
 */
function RpCdata($str)
{
    $str = str_replace('<![CDATA[', '', $str);
    $str = str_replace(']]>', '', $str);
    return  $str;
}

/**
 *  分析RSS里的链接
 *
 * @access    public
 * @param     string  $rssurl  rss地址
 * @return    string
 */
function GetRssLinks($rssurl)
{
    global $cfg_soft_lang;
    $dhd = new DedeHttpDown();
    $dhd->OpenUrl($rssurl);
    $rsshtml = $dhd->GetHtml();

    //分析编码
    preg_match("/encoding=[\"']([^\"']*)[\"']/is",$rsshtml,$infos);
    if(isset($infos[1]))
    {
        $pcode = strtolower(trim($infos[1]));
    }
    else
    {
        $pcode = strtolower($cfg_soft_lang);
    }
    if($cfg_soft_lang=='gb2312')
    {
        if($pcode=='utf-8')
        {
            $rsshtml = utf82gb($rsshtml);
        }
        else if($pcode=='big5')
        {
            $rsshtml = big52gb($rsshtml);
        }
    }
    else if($cfg_soft_lang=='utf-8')
    {
        if($pcode=='gbk'||$pcode=='gb2312')
        {
            $rsshtml = gb2utf8($rsshtml);
        }
        else if($pcode=='big5')
        {
            $rsshtml = gb2utf8(big52gb($rsshtml));
        }
    }
    $rsarr = array();
    preg_match_all("/<item(.*)<title>(.*)<\/title>/isU",$rsshtml,$titles);
    preg_match_all("/<item(.*)<link>(.*)<\/link>/isU",$rsshtml,$links);
    preg_match_all("/<item(.*)<description>(.*)<\/description>/isU",$rsshtml,$descriptions);
    if(!isset($links[2]))
    {
        return '';
    }
    foreach($links[2] as $k=>$v)
    {
        $rsarr[$k]['link'] = RpCdata($v);

        if(isset($titles[2][$k]))
        {
            $rsarr[$k]['title'] = RpCdata($titles[2][$k]);
        }
        else
        {
            $rsarr[$k]['title'] = preg_replace("/^(.*)\//i", "", RpCdata($titles[2][$k]));
        }
        if(isset($descriptions[2][$k]))
        {
            $rsarr[$k]['image'] = GetddImgFromRss($descriptions[2][$k],$rssurl);
        }
        else
        {
            $rsarr[$k]['image'] = '';
        }
    }
    return $rsarr;
}

/**
 *  从RSS摘要获取图片信息
 *
 * @access    public
 * @param     string  $descriptions  描述
 * @param     string  $refurl  来源地址
 * @return    string
 */
function GetddImgFromRss($descriptions,$refurl)
{
    if($descriptions=='')
    {
        return '';
    }
    preg_match_all("/<img(.*)src=[\"']{0,1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU",$descriptions,$imgs);
    if(isset($imgs[2][0]))
    {
        $imgs[2][0] = preg_replace("/[\"']/", '', $imgs[2][0]);
        $imgs[2][0] = preg_replace("/\/{1,}/", '/', $imgs[2][0]);
        return FillUrl($refurl,$imgs[2][0]);
    }
    else
    {
        return '';
    }
}

/**
 *  补全网址
 *
 * @access    public
 * @param     string  $refurl  来源地址
 * @param     string  $surl  站点地址
 * @return    string
 */
function FillUrl($refurl,$surl)
{
    $i = $pathStep = 0;
    $dstr = $pstr = $okurl = '';
    $refurl = trim($refurl);
    $surl = trim($surl);
    $urls = @parse_url($refurl);
    $basehost = ( (!isset($urls['port']) || $urls['port']=='80') ? $urls['host'] : $urls['host'].':'.$urls['port']);

    //$basepath = $basehost.(!isset($urls['path']) ? '' : '/'.$urls['path']);
    //由于直接获得的path在处理 http://xxxx/nnn/aaa?fdsafd 这种情况时会有错误，因此用其它方式处理
    $basepath = $basehost;
    $paths = explode('/',preg_replace("/^http:\/\//i", "", $refurl));
    $n = count($paths);
    for($i=1;$i < ($n-1);$i++)
    {
        if(!preg_match("/[\?]/", $paths[$i])) $basepath .= '/'.$paths[$i];
    }
    if(!preg_match("/[\?\.]/", $paths[$n-1]))
    {
        $basepath .= '/'.$paths[$n-1];
    }
    if($surl=='')
    {
        return $basepath;
    }
    $pos = strpos($surl, "#");
    if($pos>0)
    {
        $surl = substr($surl, 0, $pos);
    }

    //用 '/' 表示网站根的网址
    if($surl[0]=='/')
    {
        $okurl = $basehost.$surl;
    }
    else if($surl[0]=='.')
    {
        if(strlen($surl)<=2)
        {
            return '';
        }
        else if($surl[1]=='/')
        {
            $okurl = $basepath.preg_replace('/^./', '', $surl);
        }
        else
        {
            $okurl = $basepath.'/'.$surl;
        }
    }
    else
    {
        if( strlen($surl) < 7 )
        {
            $okurl = $basepath.'/'.$surl;
        }
        else if( preg_match("/^http:\/\//i",$surl) )
        {
            $okurl = $surl;
        }
        else
        {
            $okurl = $basepath.'/'.$surl;
        }
    }
    $okurl = preg_replace("/^http:\/\//i", '', $okurl);
    $okurl = 'http://'.preg_replace("/\/{1,}/", '/', $okurl);
    return $okurl;
}

/**
 *  从匹配规则中获取列表网址
 *
 * @access    public
 * @param     string  $regxurl  正则地址
 * @param     string  $handurl  操作地址
 * @param     string  $startid  开始ID
 * @param     string  $endid  结束ID
 * @param     string  $addv  增值
 * @param     string  $usemore  使用更多
 * @param     string  $batchrule  列表规则
 * @return    string
 */
function GetUrlFromListRule($regxurl='',$handurl='',$startid=0,$endid=0,$addv=1,$usemore=0,$batchrule='')
{
    global $dsql,$islisten;

    $lists = array();
    $n = 0;
    $islisten = (empty($islisten) ? 0 : $islisten);
    if($handurl!='')
    {
        $handurls = explode("\n",$handurl);
        foreach($handurls as $handurl)
        {
            $handurl = trim($handurl);
            if(preg_match("/^http:\/\//i", $handurl))
            {
                $lists[$n][0] = $handurl;
                $lists[$n][1] = 0;
                $n++;
                if($islisten==1)
                {
                    break;
                }
            }
        }
    }
    if($regxurl!='')
    {
        //没指定(#)和(*)
        if(!preg_match("/\(\*\)/i", $regxurl) && !preg_match("/\(#\)/", $regxurl))
        {
            $lists[$n][0] = $regxurl;
            $lists[$n][1] = 0;
            $n++;
        }
        else
        {
            if($addv <= 0)
            {
                $addv = 1;
            }

            //没指定多栏目匹配规则
            if($usemore==0)
            {
                while($startid <= $endid)
                {
                    $lists[$n][0] = str_replace("(*)",sprintf('%0'.strlen($startid).'d',$startid),$regxurl);
                    $lists[$n][1] = 0;
                    $startid = sprintf('%0'.strlen($startid).'d',$startid + $addv);
                    $n++;
                    if($n>2000 || $islisten==1)
                    {
                        break;
                    }
                }
            }

            //匹配多个栏目
            //规则表达式 [(#)=>(#)匹配的网址; (*)=>(*)的范围，如：1-20; typeid=>栏目id; addurl=>附加的网址(用|分开多个)]
            else
            {
                $nrules = explode(']',trim($batchrule));
                foreach($nrules as $nrule)
                {
                    $nrule = trim($nrule);
                    $nrule = preg_replace("/^\[|\]$/", '', $nrule);
                    $nrules  = explode(';',$nrule);
                    if(count($nrules)<3)
                    {
                        continue;
                    }
                    $brtag = '';
                    $startid = 0;
                    $endid = 0;
                    $typeid = 0;
                    $addurls = array();
                    foreach($nrules as $nrule)
                    {
                        $nrule = trim($nrule);
                        list($k,$v) = explode('=>',$nrule);
                        if(trim($k)=='(#)')
                        {
                            $brtag = trim($v);
                        }
                        else if(trim($k)=='typeid')
                        {
                            $typeid = trim($v);
                        }
                        else if(trim($k)=='addurl')
                        {
                            $addurl = trim($v);
                            $addurls = explode('|',$addurl);
                        }
                        else if(trim($k)=='(*)')
                        {
                            $v = preg_replace("/[ \r\n\t]/", '', trim($v));
                            list($startid,$endid) = explode('-',$v);
                        }
                    }

                    //如果栏目用栏目名称
                    if(preg_match('/[^0-9]/', $typeid))
                    {
                        $arr = $dsql->GetOne("SELECT id FROM `#@__arctype` WHERE typename LIKE '$typeid' ");
                        if(is_array($arr))
                        {
                            $typeid = $arr['id'];
                        }
                        else
                        {
                            $typeid = 0;
                        }
                    }

                    //附加网址优先
                    $mjj = 0;
                    if(isset($addurls[0]))
                    {
                        foreach($addurls as $addurl)
                        {
                            $addurl = trim($addurl);
                            if($addurl=='')
                            {
                                continue;
                            }
                            $lists[$n][0] = $addurl;
                            $lists[$n][1] = $typeid;
                            $n++;
                            $mjj++;
                            if($islisten==1)
                            {
                                break;
                            }
                        }
                    }

                    //如果为非监听模式或监听模式没手工指定的附加网址
                    if($islisten!=1 || $mjj==0 )
                    {
                        //匹配规则里的网址，注：(#)的网址是是允许使用(*)的
                        while($startid <= $endid)
                        {
                            $lists[$n][0] = str_replace("(#)",$brtag,$regxurl);
                            $lists[$n][0] = str_replace("(*)",sprintf('%0'.strlen($startid).'d',$startid),$lists[$n][0]);
                            $lists[$n][1] = $typeid;
                            $startid = sprintf('%0'.strlen($startid).'d',$startid + $addv);
                            $n++;
                            if($islisten==1)
                            {
                                break;
                            }
                            if($n>20000)
                            {
                                break;
                            }
                        }
                    }
                }
            } //End 匹配多栏目

        } //End使用规则匹配的情况

    }

    return $lists;
}//End