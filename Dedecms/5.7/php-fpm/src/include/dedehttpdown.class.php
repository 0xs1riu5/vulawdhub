<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 织梦HTTP下载类
 *
 * @version        $Id: dedehttpdown.class.php 1 11:42 2010年7月6日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
@set_time_limit(0);

class DedeHttpDown
{
    var $m_url = '';
    var $m_urlpath = '';
    var $m_scheme = 'http';
    var $m_host = '';
    var $m_port = '80';
    var $m_user = '';
    var $m_pass = '';
    var $m_path = '/';
    var $m_query = '';
    var $m_fp = '';
    var $m_error = '';
    var $m_httphead = '';
    var $m_html = '';
    var $m_puthead = '';
    var $BaseUrlPath = '';
    var $HomeUrl = '';
    var $reTry = 0;
    var $JumpCount = 0;

    /**
     *  初始化系统
     *
     * @access    public
     * @param     string    $url   需要下载的地址
     * @return    string
     */
    function PrivateInit($url)
    {
        if($url=='') {
            return ;
        }
        $urls = '';
        $urls = @parse_url($url);
        $this->m_url = $url;
        if(is_array($urls))
        {
            $this->m_host = $urls["host"];
            if(!empty($urls["scheme"]))
            {
                $this->m_scheme = $urls["scheme"];
            }
            if(!empty($urls["user"]))
            {
                $this->m_user = $urls["user"];
            }
            if(!empty($urls["pass"]))
            {
                $this->m_pass = $urls["pass"];
            }
            if(!empty($urls["port"]))
            {
                $this->m_port = $urls["port"];
            }
            if(!empty($urls["path"]))
            {
                $this->m_path = $urls["path"];
            }
            $this->m_urlpath = $this->m_path;
            if(!empty($urls["query"]))
            {
                $this->m_query = $urls["query"];
                $this->m_urlpath .= "?".$this->m_query;
            }
            $this->HomeUrl = $urls["host"];
            $this->BaseUrlPath = $this->HomeUrl.$urls["path"];
            $this->BaseUrlPath = preg_replace("/\/([^\/]*)\.(.*)$/","/",$this->BaseUrlPath);
            $this->BaseUrlPath = preg_replace("/\/$/","",$this->BaseUrlPath);
        }
    }

    /**
     *  重设各参数
     *
     * @access    public
     * @return    void
     */
    function ResetAny()
    {
        $this->m_url = "";
        $this->m_urlpath = "";
        $this->m_scheme = "http";
        $this->m_host = "";
        $this->m_port = "80";
        $this->m_user = "";
        $this->m_pass = "";
        $this->m_path = "/";
        $this->m_query = "";
        $this->m_error = "";
    }

    /**
     *  打开指定网址
     *
     * @access    public
     * @param     string    $url   地址
     * @param     string    $requestType   请求类型
     * @return    string
     */
    function OpenUrl($url,$requestType="GET")
    {
        $this->ResetAny();
        $this->JumpCount = 0;
        $this->m_httphead = Array() ;
        $this->m_html = '';
        $this->reTry = 0;
        $this->Close();

        //初始化系统
        $this->PrivateInit($url);
        $this->PrivateStartSession($requestType);
    }

    /**
     *  转到303重定向网址
     *
     * @access    public
     * @param     string   $url   地址
     * @return    string
     */
    function JumpOpenUrl($url)
    {
        $this->ResetAny();
        $this->JumpCount++;
        $this->m_httphead = Array() ;
        $this->m_html = "";
        $this->Close();

        //初始化系统
        $this->PrivateInit($url);
        $this->PrivateStartSession('GET');
    }

    /**
     *  获得某操作错误的原因
     *
     * @access    public
     * @return    void
     */
    function printError()
    {
        echo "错误信息：".$this->m_error;
        echo "<br/>具体返回头：<br/>";
        foreach($this->m_httphead as $k=>$v){ echo "$k => $v <br/>\r\n"; }
    }

    /**
     *  判别用Get方法发送的头的应答结果是否正确
     *
     * @access    public
     * @return    bool
     */
    function IsGetOK()
    {
        if( preg_match("/^2/",$this->GetHead("http-state")) )
        {
            return TRUE;
        }
        else
        {
            $this->m_error .= $this->GetHead("http-state")." - ".$this->GetHead("http-describe")."<br/>";
            return FALSE;
        }
    }

    /**
     *  看看返回的网页是否是text类型
     *
     * @access    public
     * @return    bool
     */
    function IsText()
    {
        if( preg_match("/^2/",$this->GetHead("http-state")) && preg_match("/text|xml/i",$this->GetHead("content-type")) )
        {
            return TRUE;
        }
        else
        {
            $this->m_error .= "内容为非文本类型或网址重定向<br/>";
            return FALSE;
        }
    }

    /**
     *  判断返回的网页是否是特定的类型
     *
     * @access    public
     * @param     string   $ctype   内容类型
     * @return    string
     */
    function IsContentType($ctype)
    {
        if(preg_match("/^2/",$this->GetHead("http-state"))
        && $this->GetHead("content-type")==strtolower($ctype))
        {    return TRUE; }
        else
        {
            $this->m_error .= "类型不对 ".$this->GetHead("content-type")."<br/>";
            return FALSE;
        }
    }

    /**
     *  用Http协议下载文件
     *
     * @access    public
     * @param     string    $savefilename  保存文件名称
     * @return    string
     */
    function SaveToBin($savefilename)
    {
        if(!$this->IsGetOK())
        {
            return FALSE;
        }
        if(@feof($this->m_fp))
        {
            $this->m_error = "连接已经关闭！"; return FALSE;
        }
        $fp = fopen($savefilename,"w");
        while(!feof($this->m_fp))
        {
            fwrite($fp, fread($this->m_fp, 1024));
        }
        fclose($this->m_fp);
        fclose($fp);
        return TRUE;
    }

    /**
     *  保存网页内容为Text文件
     *
     * @access    public
     * @param     string    $savefilename  保存文件名称
     * @return    string
     */
    function SaveToText($savefilename)
    {
        if($this->IsText())
        {
            $this->SaveBinFile($savefilename);
        }
        else
        {
            return "";
        }
    }

    /**
     *  用Http协议获得一个网页的内容
     *
     * @access    public
     * @return    string
     */
    function GetHtml()
    {
        if(!$this->IsText())
        {
            return '';
        }
        if($this->m_html!='')
        {
            return $this->m_html;
        }
        if(!$this->m_fp||@feof($this->m_fp))
        {
            return '';
        }
        while(!feof($this->m_fp))
        {
            $this->m_html .= fgets($this->m_fp,256);
        }
        @fclose($this->m_fp);
        return $this->m_html;
    }

    /**
     *  开始HTTP会话
     *
     * @access    public
     * @param     string    $requestType    请求类型
     * @return    string
     */
    function PrivateStartSession($requestType="GET")
    {
        if(!$this->PrivateOpenHost())
        {
            $this->m_error .= "打开远程主机出错!";
            return FALSE;
        }
        $this->reTry++;
        if($this->GetHead("http-edition")=="HTTP/1.1")
        {
            $httpv = "HTTP/1.1";
        }
        else
        {
            $httpv = "HTTP/1.0";
        }
        $ps = explode('?',$this->m_urlpath);

        $headString = '';

        //发送固定的起始请求头GET、Host信息
        if($requestType=="GET")
        {
            $headString .= "GET ".$this->m_urlpath." $httpv\r\n";
        }
        else
        {
            $headString .= "POST ".$ps[0]." $httpv\r\n";
        }
        $this->m_puthead["Host"] = $this->m_host;

        //发送用户自定义的请求头
        if(!isset($this->m_puthead["Accept"]))
        {
            $this->m_puthead["Accept"] = "*/*";
        }
        if(!isset($this->m_puthead["User-Agent"]))
        {
            $this->m_puthead["User-Agent"] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2)";
        }
        if(!isset($this->m_puthead["Refer"]))
        {
            $this->m_puthead["Refer"] = "http://".$this->m_puthead["Host"];
        }

        foreach($this->m_puthead as $k=>$v)
        {
            $k = trim($k);
            $v = trim($v);
            if($k!=""&&$v!="")
            {
                $headString .= "$k: $v\r\n";
            }
        }
        fputs($this->m_fp, $headString);
        if($requestType=="POST")
        {
            $postdata = "";
            if(count($ps)>1)
            {
                for($i=1;$i<count($ps);$i++)
                {
                    $postdata .= $ps[$i];
                }
            }
            else
            {
                $postdata = "OK";
            }
            $plen = strlen($postdata);
            fputs($this->m_fp,"Content-Type: application/x-www-form-urlencoded\r\n");
            fputs($this->m_fp,"Content-Length: $plen\r\n");
        }

        //发送固定的结束请求头
        //HTTP1.1协议必须指定文档结束后关闭链接,否则读取文档时无法使用feof判断结束
        if($httpv=="HTTP/1.1")
        {
            fputs($this->m_fp,"Connection: Close\r\n\r\n");
        }
        else
        {
            fputs($this->m_fp,"\r\n");
        }
        if($requestType=="POST")
        {
            fputs($this->m_fp,$postdata);
        }

        //获取应答头状态信息
        $httpstas = explode(" ",fgets($this->m_fp,256));
        $this->m_httphead["http-edition"] = trim($httpstas[0]);
        $this->m_httphead["http-state"] = trim($httpstas[1]);
        $this->m_httphead["http-describe"] = "";
        for($i=2;$i<count($httpstas);$i++)
        {
            $this->m_httphead["http-describe"] .= " ".trim($httpstas[$i]);
        }

        //获取详细应答头
        while(!feof($this->m_fp))
        {
            $line = trim(fgets($this->m_fp,256));
            if($line == "")
            {
                break;
            }
            $hkey = "";
            $hvalue = "";
            $v = 0;
            for($i=0;$i<strlen($line);$i++)
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
                $this->m_httphead[strtolower($hkey)] = trim($hvalue);
            }
        }

        //如果连接被不正常关闭，重试
        if(feof($this->m_fp))
        {
            if($this->reTry > 10)
            {
                return FALSE;
            }
            $this->PrivateStartSession($requestType);
        }

        //判断是否是3xx开头的应答
        if(preg_match("/^3/",$this->m_httphead["http-state"]))
        {
            if($this->JumpCount > 3)
            {
                return;
            }
            if(isset($this->m_httphead["location"]))
            {
                $newurl = $this->m_httphead["location"];
                if(preg_match("/^http/i",$newurl))
                {
                    $this->JumpOpenUrl($newurl);
                }
                else
                {
                    $newurl = $this->FillUrl($newurl);
                    $this->JumpOpenUrl($newurl);
                }
            }
            else
            {
                $this->m_error = "无法识别的答复！";
            }
        }
    }

    /**
     *  获得一个Http头的值
     *
     * @access    public
     * @param     string    $headname   头文件名称
     * @return    string
     */
    function GetHead($headname)
    {
        $headname = strtolower($headname);
        return isset($this->m_httphead[$headname]) ? $this->m_httphead[$headname] : '';
    }

    /**
     *  设置Http头的值
     *
     * @access    public
     * @param     string   $skey  键
     * @param     string   $svalue  值
     * @return    string
     */
    function SetHead($skey,$svalue)
    {
        $this->m_puthead[$skey] = $svalue;
    }

    /**
     *  打开连接
     *
     * @access    public
     * @return    bool
     */
    function PrivateOpenHost()
    {
        if($this->m_host=="")
        {
            return FALSE;
        }
        $errno = "";
        $errstr = "";
        $this->m_fp = @fsockopen($this->m_host, $this->m_port, $errno, $errstr,10);
        if(!$this->m_fp)
        {
            $this->m_error = $errstr;
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    /**
     *  关闭连接
     *
     * @access    public
     * @return    void
     */
    function Close()
    {
        @fclose($this->m_fp);
    }

    /**
     *  补全相对网址
     *
     * @access    public
     * @param     string   $surl  需要不全的地址
     * @return    string
     */
    function FillUrl($surl)
    {
        $i = 0;
        $dstr = "";
        $pstr = "";
        $okurl = "";
        $pathStep = 0;
        $surl = trim($surl);
        if($surl=="")
        {
            return "";
        }
        $pos = strpos($surl,"#");
        if($pos>0)
        {
            $surl = substr($surl,0,$pos);
        }
        if($surl[0]=="/")
        {
            $okurl = "http://".$this->HomeUrl.$surl;
        }
        else if($surl[0]==".")
        {
            if(strlen($surl)<=1)
            {
                return "";
            }
            else if($surl[1]=="/")
            {
                $okurl = "http://".$this->BaseUrlPath."/".substr($surl,2,strlen($surl)-2);
            }
            else
            {
                $urls = explode("/",$surl);
                foreach($urls as $u)
                {
                    if($u=="..")
                    {
                        $pathStep++;
                    }
                    else if($i<count($urls)-1)
                    {
                        $dstr .= $urls[$i]."/";
                    }
                    else
                    {
                        $dstr .= $urls[$i];
                    }
                    $i++;
                }
                $urls = explode("/",$this->BaseUrlPath);
                if(count($urls) <= $pathStep)
                {
                    return "";
                }
                else
                {
                    $pstr = "http://";
                    for($i=0;$i<count($urls)-$pathStep;$i++)
                    {
                        $pstr .= $urls[$i]."/";
                    }
                    $okurl = $pstr.$dstr;
                }
            }
        }
        else
        {
            if(strlen($surl)<7)
            {
                $okurl = "http://".$this->BaseUrlPath."/".$surl;
            }
            else if(strtolower(substr($surl,0,7))=="http://")
            {
                $okurl = $surl;
            }
            else
            {
                $okurl = "http://".$this->BaseUrlPath."/".$surl;
            }
        }
        $okurl = preg_replace("/^(http:\/\/)/i","",$okurl);
        $okurl = preg_replace("/\/{1,}/", "/", $okurl);
        return "http://".$okurl;
    }
}//End Class