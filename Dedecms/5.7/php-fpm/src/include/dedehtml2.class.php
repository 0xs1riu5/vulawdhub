<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 织梦HTML解析类V1.6 PHP版
 * function c____DedeHtml2();
 * 这个类针对于采集程序，主要是获取某区域内的图片、超链接等信息
 * 
 *
 * @version        $Id: dedehtml2.class.php 1 14:44 2010年7月6日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
// ------------------------------------------------------------------------
/**
 * 织梦HTML解析类V1.6 PHP版
 *
 * @package          DedeHtml2
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class DedeHtml2
{
    var $CAtt;
    var $SourceHtml;
    var $Title;
    var $Medias;
    var $MediaInfos;
    var $Links;
    var $CharSet;
    var $BaseUrl;
    var $BaseUrlPath;
    var $HomeUrl;
    var $IsHead;
    var $ImgHeight;
    var $ImgWidth;
    var $GetLinkType;

    //构造函数
    function __construct()
    {
        $this->CAtt = '';
        $this->SourceHtml = '';
        $this->Title = '';
        $this->Medias = Array();
        $this->MediaInfos = Array();
        $this->Links = Array();
        $this->BaseUrl = '';
        $this->BaseUrlPath = '';
        $this->HomeUrl = '';
        $this->IsHead = false;
        $this->ImgHeight = 30;
        $this->ImgWidth = 50;
        $this->GetLinkType = 'link';
    }

    function DedeHtml2()
    {
        $this->__construct();
    }

    /**
     *  设置HTML的内容和来源网址
     *
     * @access    public
     * @param     string    $html  html资源
     * @param     string    $url  地址
     * @param     string    $linktype  连接类型
     * @return    void
     */
    function SetSource(&$html, $url = '', $linktype='')
    {
        $this->__construct();
        $this->CAtt = new DedeAttribute2();
        $url = trim($url);
        $this->SourceHtml = $html;
        $this->BaseUrl = $url;
        //判断文档相对于当前的路径
        $urls = @parse_url($url);
        $this->HomeUrl = $urls['host'];
        $this->BaseUrlPath = $this->HomeUrl.$urls['path'];
        $this->BaseUrlPath = preg_replace("/\/([^\/]*)\.(.*)$/","/",$this->BaseUrlPath);
        $this->BaseUrlPath = preg_replace("/\/$/",'',$this->BaseUrlPath);
        if($linktype!='')
        {
            $this->GetLinkType = $linktype;
        }
        if($html != '')
        {
            $this->Analyser();
        }
    }

    /**
     *  解析HTML
     *
     * @access    private
     * @return    void
     */
    function Analyser()
    {
        $cAtt = new DedeAttribute2();
        $cAtt->IsTagName = false;
        $c = '';
        $i = 0;
        $startPos = 0;
        $endPos = 0;
        $wt = 0;
        $ht = 0;
        $scriptdd = 0;
        $attStr = '';
        $tmpValue = '';
        $tmpValue2 = '';
        $tagName = '';
        $hashead = 0;
        $slen = strlen($this->SourceHtml);
        if($this->GetLinkType=='link' || $this->GetLinkType=='')
        {
            $needTags = array('a');
        }
        if($this->GetLinkType=='media')
        {
            $needTags = array('img','embed','a');
            $this->IsHead = true;
        }
        $tagbreaks = array(' ','<','>',"\r","\n","\t");
        for(;isset($this->SourceHtml[$i]);$i++)
        {
            if($this->SourceHtml[$i]=='<')
            {
                $tagName = '';
                $j = 0;
                for($i=$i+1; isset($this->SourceHtml[$i]); $i++)
                {
                    if($j>10)
                    {
                        break;
                    }
                    $j++;
                    if( in_array($this->SourceHtml[$i],$tagbreaks) )
                    {
                        break;
                    }
                    else
                    {
                        $tagName .= $this->SourceHtml[$i];
                    }
                }
                $tagName = strtolower($tagName);

                //标记为注解
                if($tagName=='!--')
                {
                    $endPos = strpos($this->SourceHtml,'-->',$i);
                    if($endPos !== false)
                    {
                        $i=$endPos+3;
                    }
                    continue;
                }

                //标记在指定集合内
                else if( in_array($tagName,$needTags) )
                {
                    $startPos = $i;
                    $endPos = strpos($this->SourceHtml,'>',$i+1);
                    if($endPos===false)
                    {
                        break;
                    }
                    $attStr = substr($this->SourceHtml,$i+1,$endPos-$startPos-1);
                    $cAtt->SetSource($attStr);
                    if($tagName=='img')
                    {
                        $this->InsertMedia($cAtt->GetAtt('src'),'img');
                    }
                    else if($tagName=='embed')
                    {
                        $rurl = $this->InsertMedia($cAtt->GetAtt('src'),'embed');
                        if($rurl != '')
                        {
                            $this->MediaInfos[$rurl][0] = $cAtt->GetAtt('width');
                            $this->MediaInfos[$rurl][1] = $cAtt->GetAtt('height');
                        }
                    }
                    else if($tagName=='a')
                    {
                        $this->InsertLink($this->FillUrl($cAtt->GetAtt('href')),$this->GetInnerText($i,'a'));
                    }
                }
                else
                {
                    continue;
                }
            }//End if char

        }//End for

        if($this->Title == '')
        {
            $this->Title = $this->BaseUrl;
        }
    }

    /**
     *  重置资源
     *
     * @access    private
     * @return    void
     */
    function Clear()
    {
        $this->CAtt = '';
        $this->SourceHtml = '';
        $this->Title = '';
        $this->Links = '';
        $this->Medias = '';
        $this->BaseUrl = '';
        $this->BaseUrlPath = '';
    }

    /**
     *  分析链接
     *
     * @access    public
     * @param     string  $url  地址
     * @param     string  $mtype  媒体类型
     * @return    string
     */
    function InsertMedia($url, $mtype)
    {
        if( preg_match("/^(javascript:|#|'|\")/", $url) )
        {
            return '';
        }
        if($url == '')
        {
            return '';
        }
        $this->Medias[$url]=$mtype;
        return $url;
    }

    /**
     *  分析链接
     *
     * @access    public
     * @param     string  $url  地址
     * @param     string  $atitle  文档
     * @return    string
     */
    function InsertLink($url, $atitle)
    {
        if( preg_match("/^(javascript:|#|'|\")/", $url) )
        {
            return '';
        }
        if($url == '')
        {
            return '';
        }
        if( preg_match('/^img:/', $atitle) )
        {
            list($aimg, $atitle) = explode(':txt:', $atitle);
            if(!isset($this->Links[$url]))
            {
                if($atitle != '')
                {
                    $this->Links[$url]['title'] = cn_substr($atitle,50);
                }
                else
                {
                    $this->Links[$url]['title'] = preg_replace('/img:/', '', $aimg);
                }
                $this->Links[$url]['link']  = $url;
            }
            $this->Links[$url]['image'] = preg_replace('/img:/', '', $aimg);
            $this->InsertMedia($this->Links[$url]['image'], 'img');
        }
        else
        {
            if(!isset($this->Links[$url]))
            {
                $this->Links[$url]['image'] = '';
                $this->Links[$url]['title'] = $atitle;
                $this->Links[$url]['link']  = $url;
            }
            else
            {
                if(strlen($this->Links[$url]['title']) < strlen($atitle)) $this->Links[$url]['title'] = $atitle;
            }
        }
        return $url;
    }

    /**
     *  分析content-type中的字符类型
     *
     * @access    public
     * @param     string  $att  属性字符串
     * @return    string
     */
    function ParCharSet($att)
    {
        $startdd=0;
        $taglen=0;
        $startdd = strpos($att,'=');
        if($startdd===false)
        {
            return '';
        }
        else
        {
            $taglen = strlen($att)-$startdd-1;
            if($taglen<=0)
            {
                return '';
            }
            return trim(substr($att, $startdd+1, $taglen));
        }
    }

    /**
     *  补全相对网址
     *
     * @access    public
     * @param     string  $surl  地址
     * @return    string
     */
    function FillUrl($surl)
    {
        $i = $pathStep = 0;
        $dstr = $pstr = $okurl = '';

        $surl = trim($surl);
        if($surl == '')
        {
            return '';
        }
        $pos = strpos($surl,'#');
        if($pos>0)
        {
            $surl = substr($surl,0,$pos);
        }
        if($surl[0]=='/')
        {
            $okurl = $this->HomeUrl.'/'.$surl;
        }
        else if($surl[0]=='.')
        {
            if(!isset($surl[2]))
            {
                return '';
            }
            else if($surl[0]=='/')
            {
                $okurl = $this->BaseUrlPath."/".substr($surl,2,strlen($surl)-2);
            }
            else
            {
                $urls = explode('/',$surl);
                foreach($urls as $u)
                {
                    if($u=='..')
                    {
                        $pathStep++;
                    }
                    else if($i<count($urls)-1)
                    {
                        $dstr .= $urls[$i].'/';
                    }
                    else
                    {
                        $dstr .= $urls[$i];
                    }
                    $i++;
                }
                $urls = explode('/',$this->BaseUrlPath);
                if(count($urls) <= $pathStep)
                {
                    return '';
                }
                else
                {
                    $pstr = '';
                    for($i=0;$i<count($urls)-$pathStep;$i++){ $pstr .= $urls[$i].'/'; }
                    $okurl = $pstr.$dstr;
                }
            }
        }
        else
        {
            if( strlen($surl) < 7 )
            {
                $okurl = $this->BaseUrlPath.'/'.$surl;
            }
            else if( strtolower(substr($surl,0,7))=='http://' )
            {
                $okurl = preg_replace('/^http:\/\//i', '', $surl);
            }
            else
            {
                $okurl = $this->BaseUrlPath.'/'.$surl;
            }
        }
        $okurl = preg_replace('/\/{1,}/i', '/', $okurl);
        return 'http://'.$okurl;
    }

    /**
     *  获得和下一个标记之间的文本内容
     *
     * @access    public
     * @param     string  $pos  位置地址
     * @param     string  $tagname  标签名称
     * @return    string
     */
    function GetInnerText(&$pos,$tagname)
    {
        $startPos=0;
        $endPos=0;
        $textLen=0;
        $str = '';
        $startPos = strpos($this->SourceHtml,'>',$pos);

        if($tagname=='title')
        {
            $endPos = strpos($this->SourceHtml,'<',$startPos);
        }
        else
        {
            $endPos1 = strpos($this->SourceHtml,'</a',$startPos);
            $endPos2 = strpos($this->SourceHtml,'</A',$startPos);
            if($endPos1===false)
            {
                $endPos = $endPos2;
            }
            else if($endPos2===false)
            {
                $endPos = $endPos1;
            }
            else
            {
                $endPos = ($endPos1 < $endPos2 ? $endPos1 : $endPos2 );
            }
        }
        if($endPos > $startPos)
        {
            $textLen = $endPos-$startPos;
            $str = substr($this->SourceHtml,$startPos+1,$textLen-1);
        }
        $pos = $startPos + $textLen + strlen("</".$tagname) + 1;
        if($tagname=='title')
        {
            return trim($str);
        }
        else
        {
            preg_match_all("/<img(.*)src=[\"']{0,1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU",$str,$imgs);
            if(isset($imgs[2][0]))
            {
                $txt = trim(Html2Text($str));
                $imgs[2][0] = preg_replace("/[\"']/",'',$imgs[2][0]);
                return "img:".$this->FillUrl($imgs[2][0]).':txt:'.$txt;
            }
            else
            {
            	$str = strip_tags($str);
                //$str = preg_replace('/<\/(.*)$/i', '', $str);
                //$str = trim(preg_replace('/^(.*)>/i','',$str));
                return $str;
            }
        }
    }
}//End class

/*******************************
//属性解析器
function c____DedeAttribute2();
********************************/
class DedeAttribute2
{
    var $SourceString = '';
    var $SourceMaxSize = 1024;
    var $CharToLow = FALSE;  //属性值是否不分大小写(属性名统一为小写)
    var $IsTagName = TRUE; //是否解析标记名称
    var $Count = -1;
    var $Items = ''; //属性元素的集合

    //设置属性解析器源字符串
    function SetSource($str = '')
    {
        $this->Count = -1;
        $this->Items = '';
        $strLen = 0;
        $this->SourceString = trim(preg_replace("/[ \t\r\n]{1,}/"," ",$str));
        $strLen = strlen($this->SourceString);
        $this->SourceString .= " "; //增加一个空格结尾,以方便处理没有属性的标记
        if($strLen>0&&$strLen<=$this->SourceMaxSize)
        {
            $this->PrivateAttParse();
        }
    }

    //获得某个属性
    function GetAtt($str)
    {
        if($str == '')
        {
            return '';
        }
        $str = strtolower($str);
        if(isset($this->Items[$str]))
        {
            return $this->Items[$str];
        }
        else
        {
            return '';
        }
    }

    //判断属性是否存在
    function IsAtt($str)
    {
        if($str == '')
        {
            return false;
        }
        $str = strtolower($str);
        if(isset($this->Items[$str]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //获得标记名称
    function GetTagName()
    {
        return $this->GetAtt("tagname");
    }

    // 获得属性个数
    function GetCount()
    {
        return $this->Count+1;
    }

    //解析属性(仅给SetSource调用)
    function PrivateAttParse()
    {
        $d = '';
        $tmpatt = '';
        $tmpvalue = '';
        $startdd = -1;
        $ddtag = '';
        $strLen = strlen($this->SourceString);
        $j = 0;

        //这里是获得标记的名称
        if($this->IsTagName)
        {
            //如果属性是注解，不再解析里面的内容，直接返回
            if(isset($this->SourceString[2]))
            {
                if($this->SourceString[0].$this->SourceString[1].$this->SourceString[2]=='!--')
                {
                    $this->Items['tagname'] = '!--';
                    return ;
                }
            }
            for($i=0;$i<$strLen;$i++)
            {
                $d = $this->SourceString[$i];
                $j++;
                if(preg_match("/[ '\"\r\n\t]/i", $d))
                {
                    $this->Count++;
                    $this->Items["tagname"]=strtolower(trim($tmpvalue));
                    $tmpvalue = ''; break;
                }
                else
                {
                    $tmpvalue .= $d;
                }
            }
            if($j>0)
            {
                $j = $j-1;
            }
        }

        //遍历源字符串，获得各属性
        for($i=$j;$i<$strLen;$i++)
        {
            $d = $this->SourceString[$i];
            //获得属性的键
            if($startdd==-1)
            {
                if($d!='=')
                {
                    $tmpatt .= $d;
                }
                else
                {
                    $tmpatt = strtolower(trim($tmpatt));
                    $startdd=0;
                }
            }

            //检测属性值是用什么包围的，允许使用 '' '' 或空白
            else if($startdd==0)
            {
                switch($d)
                {
                    case ' ':
                        continue;
                        break;
                    case '\'':
                        $ddtag='\'';
                        $startdd=1;
                        break;
                    case '"':
                        $ddtag='"';
                        $startdd=1;
                        break;
                    default:
                        $tmpvalue.=$d;
                        $ddtag=' ';
                        $startdd=1;
                        break;
                }
            }

            //获得属性的值
            else if($startdd==1)
            {
                if($d==$ddtag)
                {
                    $this->Count++;
                    if($this->CharToLow)
                    {
                        $this->Items[$tmpatt] = strtolower(trim($tmpvalue));
                    }
                    else
                    {
                        $this->Items[$tmpatt] = trim($tmpvalue);
                    }
                    $tmpatt = '';
                    $tmpvalue = '';
                    $startdd=-1;
                }
                else
                {
                    $tmpvalue.=$d;
                }
            }
        }//End for

        //处理没有值的属性(必须放在结尾才有效)如："input type=radio name=t1 value=aaa checked"
        if($tmpatt != '')
        {
            $this->Items[$tmpatt] = '';
        }
    }//End Function PrivateAttParse

}//End Class DedeAttribute2

?>