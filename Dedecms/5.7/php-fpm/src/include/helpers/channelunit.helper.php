<?php  if(!defined('DEDEINC')) exit('dedecms');
/**
 * 文档小助手
 *
 * @version        $Id: channelunit.helper.php 1 16:49 2010年7月6日Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  用星表示软件或Flash的等级
 *
 * @param     string  $rank  星星数
 * @return    string
 */
if ( ! function_exists('GetRankStar'))
{
    function GetRankStar($rank)
    {
        $nstar = "";
        for($i=1;$i<=$rank;$i++)
        {
            $nstar .= "★";
        }
        for($i;$i<=5;$i++)
        {
            $nstar .= "☆";
        }
        return $nstar;
    }
}

/**
 *  获得文章网址
 *  如果要获得文件的路径，直接用
 *  GetFileUrl($aid,$typeid,$timetag,$title,$ismake,$rank,$namerule,$typedir,$money)
 *  即是不指定站点参数则返回相当对根目录的真实路径
 *
 * @param     int  $aid  文档ID
 * @param     int  $typeid  栏目ID
 * @param     int  $timetag  时间戳
 * @param     string  $title  标题
 * @param     int  $ismake  是否生成
 * @param     int  $rank  阅读权限
 * @param     string  $namerule  名称规则
 * @param     string  $typedir  栏目dir
 * @param     string  $money  需要金币
 * @param     string  $filename  文件名称
 * @param     string  $moresite  多站点
 * @param     string  $siteurl  站点地址
 * @param     string  $sitepath  站点路径
 * @return    string
 */
if ( ! function_exists('GetFileUrl'))
{
    function GetFileUrl($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$typedir='',
    $money=0, $filename='',$moresite=0,$siteurl='',$sitepath='')
    {
        $articleUrl = GetFileName($aid,$typeid,$timetag,$title,$ismake,$rank,$namerule,$typedir,$money,$filename);
        $sitepath = MfTypedir($sitepath);

        //是否强制使用绝对网址
        if($GLOBALS['cfg_multi_site']=='Y')
        {
            if($siteurl=='')
            {
                $siteurl = $GLOBALS['cfg_basehost'];
            }
            if($moresite==1)
            {
                $articleUrl = preg_replace("#^".$sitepath.'#', '', $articleUrl);
            }
            if(!preg_match("/http:/", $articleUrl))
            {
                $articleUrl = $siteurl.$articleUrl;
            }
        }

        return $articleUrl;
    }
}

/**
 *  获得新文件名(本函数会自动创建目录)
 *
 * @param     int  $aid  文档ID
 * @param     int  $typeid  栏目ID
 * @param     int  $timetag  时间戳
 * @param     string  $title  标题
 * @param     int  $ismake  是否生成
 * @param     int  $rank  阅读权限
 * @param     string  $namerule  名称规则
 * @param     string  $typedir  栏目dir
 * @param     string  $money  需要金币
 * @param     string  $filename  文件名称
 * @return    string
 */
if ( ! function_exists('GetFileNewName'))
{
     function GetFileNewName($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$typedir='',$money=0,$filename='')
    {
        global $cfg_arc_dirname;
        $articlename = GetFileName($aid,$typeid,$timetag,$title,$ismake,$rank,$namerule,$typedir,$money,$filename);
        
        if(preg_match("/\?/", $articlename))
        {
            return $articlename;
        }
        
        if($cfg_arc_dirname=='Y' && preg_match("/\/$/", $articlename))
        {
            $articlename = $articlename."index.html";
        }
        
        $slen = strlen($articlename)-1;
        for($i=$slen;$i>=0;$i--)
        {
            if($articlename[$i]=='/')
            {
                $subpos = $i;
                break;
            }
        }
        $okdir = substr($articlename,0,$subpos);
        CreateDir($okdir);
        return $articlename;
    }
}



/**
 *  获得文件相对于主站点根目录的物理文件名(动态网址返回url)
 *
 * @param     int  $aid  文档ID
 * @param     int  $typeid  栏目ID
 * @param     int  $timetag  时间戳
 * @param     string  $title  标题
 * @param     int  $ismake  是否生成
 * @param     int  $rank  阅读权限
 * @param     string  $namerule  名称规则
 * @param     string  $typedir  栏目dir
 * @param     string  $money  需要金币
 * @param     string  $filename  文件名称
 * @return    string
 */
if ( ! function_exists('GetFileName'))
{
    function GetFileName($aid,$typeid,$timetag,$title,$ismake=0,$rank=0,$namerule='',$typedir='',$money=0,$filename='')
    {
        global $cfg_rewrite, $cfg_cmspath, $cfg_arcdir, $cfg_special, $cfg_arc_dirname;
        //没指定栏目时用固定规则（专题）
        if(empty($namerule)) {
            $namerule = $cfg_special.'/arc-{aid}.html';
            $typeid = -1;
        }
        if($rank!=0 || $ismake==-1 || $typeid==0 || $money>0)
        {
            //动态文章
            if($cfg_rewrite == 'Y')
            {
                return $GLOBALS["cfg_plus_dir"]."/view-".$aid.'-1.html';
            }
            else
            {
                return $GLOBALS['cfg_phpurl']."/view.php?aid=$aid";
            }
        }
        else
        {
            $articleDir = MfTypedir($typedir);
            $articleRule = strtolower($namerule);
            if($articleRule=='')
            {
                $articleRule = strtolower($GLOBALS['cfg_df_namerule']);
            }
            if($typedir=='')
            {
                $articleDir  = $GLOBALS['cfg_cmspath'].$GLOBALS['cfg_arcdir'];
            }
            $dtime = GetDateMk($timetag);
            list($y, $m, $d) = explode('-', $dtime);
            $arr_rpsource = array('{typedir}','{y}','{m}','{d}','{timestamp}','{aid}','{cc}');
            $arr_rpvalues = array($articleDir,$y, $m, $d, $timetag, $aid, dd2char($m.$d.$aid.$y));
            if($filename != '')
            {
                $articleRule = dirname($articleRule).'/'.$filename.$GLOBALS['cfg_df_ext'];
            }
            $articleRule = str_replace($arr_rpsource,$arr_rpvalues,$articleRule);
            if(preg_match("/\{p/", $articleRule))
            {
                $articleRule = str_replace('{pinyin}',GetPinyin($title).'_'.$aid,$articleRule);
                $articleRule = str_replace('{py}',GetPinyin($title,1).'_'.$aid,$articleRule);
            }
            $articleUrl = '/'.preg_replace("/^\//", '', $articleRule);
            if(preg_match("/index\.html/", $articleUrl) && $cfg_arc_dirname=='Y')
            {
                $articleUrl = str_replace('index.html', '', $articleUrl);
            }
            return $articleUrl;
        }
    }
}


/**
 *  获得指定类目的URL链接
 *  对于使用封面文件和单独页面的情况，强制使用默认页名称
 *
 * @param     int  $typeid  栏目ID
 * @param     string  $typedir  栏目目录
 * @param     int  $isdefault  是否默认
 * @param     string  $defaultname  默认名称
 * @param     int  $ispart  栏目属性
 * @param     string  $namerule2  名称规则
 * @param     string  $moresite  多站点
 * @param     string  $siteurl  站点地址
 * @param     string  $sitepath  站点目录
 * @return    string
 */
if ( ! function_exists('GetTypeUrl'))
{
    function GetTypeUrl($typeid,$typedir,$isdefault,$defaultname,$ispart,$namerule2,$moresite=0,$siteurl='',$sitepath='')
    {
        global $cfg_typedir_df;
        $typedir = MfTypedir($typedir);
        $sitepath = MfTypedir($sitepath);
        if($isdefault==-1)
        {
            //动态
            $reurl = $GLOBALS['cfg_phpurl']."/list.php?tid=".$typeid;
        }
        else if($ispart==2)
        {
            //跳转网址
            $reurl = $typedir;
            return $reurl;
        }
        else
        {
            if($isdefault==0 && $ispart==0)
            {
                $reurl = str_replace("{page}","1",$namerule2);
                $reurl = str_replace("{tid}",$typeid,$reurl);
                $reurl = str_replace("{typedir}",$typedir,$reurl);
            }
            else
            {
                if($cfg_typedir_df=='N' || $isdefault==0) $reurl = $typedir.'/'.$defaultname;
                else $reurl = $typedir.'/';
            }
        }

        if( !preg_match("/^http:\/\//",$reurl) ) {
            $reurl = preg_replace("/\/{1,}/i", '/', $reurl);
        }
        
        if($GLOBALS['cfg_multi_site']=='Y')
        {
            if($siteurl=='') {
                $siteurl = $GLOBALS['cfg_basehost'];
            }
            if($moresite==1 ) {
                $reurl = preg_replace("#^".$sitepath."#", '', $reurl);
            }
            if( !preg_match("/^http:\/\//", $reurl) ) {
                $reurl = $siteurl.$reurl;
            }
        }
        return $reurl;
    }
}

/**
 *  魔法变量，用于获取两个可变的值
 *
 * @param     string  $v1  第一个变量
 * @param     string  $v2  第二个变量
 * @return    string
 */
if ( ! function_exists('MagicVar'))
{
    function MagicVar($v1,$v2)
    {
        return $GLOBALS['autoindex']%2==0 ? $v1 : $v2;
    }
}

/**
 *  获取某个类目的所有上级栏目id
 *
 * @param     int  $tid  栏目ID
 * @return    string
 */
if ( ! function_exists('GetTopids'))
{
    function GetTopids($tid)
    {
        $arr = GetParentIds($tid);
        return join(',',$arr);
    }
}



/**
 *  获取上级ID列表
 *
 * @access    public
 * @param     string  $tid  栏目ID
 * @return    string
 */
if ( ! function_exists('GetParentIds'))
{
    function GetParentIds($tid)
    {
        global $cfg_Cs;
        $GLOBALS['pTypeArrays'][] = $tid;
        if(!is_array($cfg_Cs))
        {
            require_once(DEDEDATA."/cache/inc_catalog_base.inc");
        }
        if(!isset($cfg_Cs[$tid]) || $cfg_Cs[$tid][0]==0)
        {
            return $GLOBALS['pTypeArrays'];
        }
        else
        {
            return GetParentIds($cfg_Cs[$tid][0]);
        }
    }
}


/**
 *  检测栏目是否是另一个栏目的父目录
 *
 * @access    public
 * @param     string  $sid  顶级目录id
 * @param     string  $pid  下级目录id
 * @return    bool
 */
if ( ! function_exists('IsParent'))
{
    function IsParent($sid, $pid)
    {
        $pTypeArrays = GetParentIds($sid);
        return in_array($pid, $pTypeArrays);
    }
}


/**
 *  获取一个类目的顶级类目id
 *
 * @param     string  $tid  栏目ID
 * @return    string
 */
if ( ! function_exists('GetTopid'))
{
    function GetTopid($tid)
    {
        global $cfg_Cs;
        if(!is_array($cfg_Cs))
        {
            require_once(DEDEDATA."/cache/inc_catalog_base.inc");
        }
        if(!isset($cfg_Cs[$tid][0]) || $cfg_Cs[$tid][0]==0)
        {
            return $tid;
        }
        else
        {
            return GetTopid($cfg_Cs[$tid][0]);
        }
    }
}


/**
 *  获得某id的所有下级id
 *
 * @param     string  $id  栏目id
 * @param     string  $channel  模型ID
 * @param     string  $addthis  是否包含本身
 * @return    string
 */
function GetSonIds($id,$channel=0,$addthis=true)
{
    global $cfg_Cs;
    $GLOBALS['idArray'] = array();
    if( !is_array($cfg_Cs) )
    {
        require_once(DEDEDATA."/cache/inc_catalog_base.inc");
    }
    GetSonIdsLogic($id,$cfg_Cs,$channel,$addthis);
    $rquery = join(',',$GLOBALS['idArray']);
    $rquery = preg_replace("/,$/", '', $rquery); 
    return $rquery;
}

//递归逻辑
function GetSonIdsLogic($id,$sArr,$channel=0,$addthis=false)
{
    if($id!=0 && $addthis)
    {
        $GLOBALS['idArray'][$id] = $id;
    }
    if(is_array($sArr))
    {
        foreach($sArr as $k=>$v)
        {
            if( $v[0]==$id && ($channel==0 || $v[1]==$channel ))
            {
                GetSonIdsLogic($k,$sArr,$channel,true);
            }
        }
    }
}

/**
 *  栏目目录规则
 *
 * @param     string  $typedir   栏目目录
 * @return    string
 */
function MfTypedir($typedir)
{
    if(preg_match("/^http:|^ftp:/i", $typedir)) return $typedir;
    $typedir = str_replace("{cmspath}",$GLOBALS['cfg_cmspath'],$typedir);
    $typedir = preg_replace("/\/{1,}/", "/", $typedir);
    return $typedir;
}

/**
 *  模板目录规则
 *
 * @param     string  $tmpdir  模板目录
 * @return    string
 */
function MfTemplet($tmpdir)
{
    $tmpdir = str_replace("{style}", $GLOBALS['cfg_df_style'], $tmpdir);
    $tmpdir = preg_replace("/\/{1,}/", "/", $tmpdir);
    return $tmpdir;
}

/**
 *  清除用于js的空白块
 *
 * @param     string  $atme  字符
 * @return    string
 */
function FormatScript($atme)
{
    return $atme=='&nbsp;' ? '' : $atme;
}

/**
 *  给属性默认值
 *
 * @param     array  $atts  属性
 * @param     array  $attlist  属性列表
 * @return    string
 */
function FillAttsDefault(&$atts, $attlist)
{
    $attlists = explode(',', $attlist);
    for($i=0; isset($attlists[$i]); $i++)
    {
        list($k, $v) = explode('|', $attlists[$i]);
        if(!isset($atts[$k]))
        {
            $atts[$k] = $v;
        }
    }
}

/**
 *  给块标记赋值
 *
 * @param     object  $dtp  模板解析引擎
 * @param     object  $refObj  实例化对象
 * @param     object  $parfield
 * @return    string
 */
function MakeOneTag(&$dtp, &$refObj, $parfield='Y')
{
    $alltags = array();
    $dtp->setRefObj($refObj);
    //读取自由调用tag列表
    $dh = dir(DEDEINC.'/taglib');
    while($filename = $dh->read())
    {
        if(preg_match("/\.lib\./", $filename))
        {
            $alltags[] = str_replace('.lib.php','',$filename);
        }
    }
    $dh->Close();

    //遍历tag元素
    if(!is_array($dtp->CTags))
    {
        return '';
    }
    foreach($dtp->CTags as $tagid=>$ctag)
    {
        $tagname = $ctag->GetName();
        if($tagname=='field' && $parfield=='Y')
        {
            $vname = $ctag->GetAtt('name');
            if( $vname=='array' && isset($refObj->Fields) )
            {
                $dtp->Assign($tagid,$refObj->Fields);
            }
            else if(isset($refObj->Fields[$vname]))
            {
                $dtp->Assign($tagid,$refObj->Fields[$vname]);
            }
            else if($ctag->GetAtt('noteid') != '')
            {
                if( isset($refObj->Fields[$vname.'_'.$ctag->GetAtt('noteid')]) )
                {
                    $dtp->Assign($tagid, $refObj->Fields[$vname.'_'.$ctag->GetAtt('noteid')]);
                }
            }
            continue;
        }

        //由于考虑兼容性，原来文章调用使用的标记别名统一保留，这些标记实际调用的解析文件为inc_arclist.php
        if(preg_match("/^(artlist|likeart|hotart|imglist|imginfolist|coolart|specart|autolist)$/", $tagname))
        {
            $tagname='arclist';
        }
        if($tagname=='friendlink')
        {
            $tagname='flink';
        }
        if(in_array($tagname,$alltags))
        {
            $filename = DEDEINC.'/taglib/'.$tagname.'.lib.php';
            include_once($filename);
            $funcname = 'lib_'.$tagname;
            $dtp->Assign($tagid,$funcname($ctag,$refObj));
        }
    }
}

/**
 *  获取某栏目的url
 *
 * @param     array  $typeinfos  栏目信息
 * @return    string
 */
function GetOneTypeUrlA($typeinfos)
{
    return GetTypeUrl($typeinfos['id'],MfTypedir($typeinfos['typedir']),$typeinfos['isdefault'],$typeinfos['defaultname'],
    $typeinfos['ispart'],$typeinfos['namerule2'],$typeinfos['moresite'],$typeinfos['siteurl'],$typeinfos['sitepath']);
}

/**
 *  设置全局环境变量
 *
 * @param     int  $typeid  栏目ID
 * @param     string  $typename  栏目名称
 * @param     string  $aid  文档ID
 * @param     string  $title  标题
 * @param     string  $curfile  当前文件
 * @return    string
 */
function SetSysEnv($typeid=0,$typename='',$aid=0,$title='',$curfile='')
{
    global $_sys_globals;
    if(empty($_sys_globals['curfile']))
    {
        $_sys_globals['curfile'] = $curfile;
    }
    if(empty($_sys_globals['typeid']))
    {
        $_sys_globals['typeid'] = $typeid;
    }
    if(empty($_sys_globals['typename']))
    {
        $_sys_globals['typename'] = $typename;
    }
    if(empty($_sys_globals['aid']))
    {
        $_sys_globals['aid'] = $aid;
    }
}

/**
 *  获得图书的URL
 *
 * @param     string  $bid  书籍ID
 * @param     string  $title  标题
 * @param     string  $gdir
 * @return    string
 */
function GetBookUrl($bid,$title,$gdir=0)
{
    global $cfg_cmspath;
    $bookurl = $gdir==1 ?
    "{$cfg_cmspath}/book/".DedeID2Dir($bid) : "{$cfg_cmspath}/book/".DedeID2Dir($bid).'/'.GetPinyin($title).'-'.$bid.'.html';
    return $bookurl;
}

/**
 *  根据ID生成目录
 *
 * @param     string  $aid  内容ID
 * @return    int
 */
function DedeID2Dir($aid)
{
    $n = ceil($aid / 1000);
    return $n;
}

/**
 *  获得自由列表的网址
 *
 * @param     string  $lid  列表id
 * @param     string  $namerule  命名规则
 * @param     string  $listdir  列表目录
 * @param     string  $defaultpage  默认页面
 * @param     string  $nodefault  没有默认页面
 * @return    string
 */
function GetFreeListUrl($lid,$namerule,$listdir,$defaultpage,$nodefault){
    $listdir = str_replace('{cmspath}',$GLOBALS['cfg_cmspath'],$listdir);
    if($nodefault==1)
    {
        $okfile = str_replace('{page}','1',$namerule);
        $okfile = str_replace('{listid}',$lid,$okfile);
        $okfile = str_replace('{listdir}',$listdir,$okfile);
    }
    else
    {
        $okfile = $GLOBALS['cfg_phpurl']."/freelist.php?lid=$lid";
        return $okfile;
    }
    $okfile = str_replace("\\","/",$okfile);
    $okfile = str_replace("//","/",$okfile);
    $trueFile = $GLOBALS['cfg_basedir'].$okfile;
    if(!@file_exists($trueFile))
    {
        $okfile = $GLOBALS['cfg_phpurl']."/freelist.php?lid=$lid";
    }
    return $okfile;
}

/**
 *  获取网站搜索的热门关键字
 *
 * @param     string  $dsql
 * @param     string  $num  获取数目
 * @param     string  $nday  天数
 * @param     string  $klen 关键词字数
 * @param     string  $orderby 排列顺序
 * @return    string
 */
function GetHotKeywords(&$dsql,$num=8,$nday=365,$klen=16,$orderby='count')
{
    global $cfg_phpurl,$cfg_cmspath;
    $nowtime = time();
    $num = @intval($num);
    $nday = @intval($nday);
    $klen = @intval($klen);
    if(empty($nday))
    {
        $nday = 365;
    }
    if(empty($num))
    {
        $num = 6;
    }
    if(empty($klen))
    {
        $klen = 16;
    }
    $klen = $klen+1;
    $mintime = $nowtime - ($nday * 24 * 3600);
    if(empty($orderby))
    {
        $orderby = 'count';
    }
    $dsql->SetQuery("SELECT keyword FROM #@__search_keywords WHERE lasttime>$mintime AND length(keyword)<$klen ORDER BY $orderby DESC LIMIT 0,$num");
    $dsql->Execute('hw');
    $hotword = "";
    while($row=$dsql->GetArray('hw'))
    {
        $hotword .= "　<a href='".$cfg_phpurl."/search.php?keyword=".urlencode($row['keyword'])."&searchtype=titlekeyword'>".$row['keyword']."</a> ";
    }
    return $hotword;
}

/**
 *  使用绝对网址
 *
 * @param     string  $gurl  地址
 * @return    string
 */
function Gmapurl($gurl)
{
    return preg_replace("/http:\/\//i", $gurl) ? $gurl : $GLOBALS['cfg_basehost'].$gurl;
}

/**
 *  引用回复标记处理
 *
 * @param     string  $quote
 * @return    string
 */
function Quote_replace($quote)
{
    $quote = str_replace('{quote}','<div class="decmt-box">',$quote);
    $quote = str_replace('{title}','<div class="decmt-title"><span class="username">',$quote);
    $quote = str_replace('{/title}','</span></div>',$quote);
    $quote = str_replace('&lt;br/&gt;','<br>',$quote);
    $quote = str_replace('&lt;', '<', $quote);
    $quote = str_replace('&gt;', '>', $quote);
    $quote = str_replace('{content}','<div class="decmt-content">',$quote);
    $quote = str_replace('{/content}','</div>',$quote);
    $quote = str_replace('{/quote}','</div>',$quote);
    return $quote;
}

/**
 *  获取、写入指定cacheid的块
 *
 * @param     string  $cacheid  缓存ID
 * @return    string
 */
function GetCacheBlock($cacheid)
{
    global $cfg_puccache_time;
    $cachefile = DEDEDATA.'/cache/'.$cacheid.'.inc';
    if(!file_exists($cachefile) || filesize($cachefile)==0 || 
      $cfg_puccache_time==0 || time() - filemtime($cachefile) > $cfg_puccache_time)
    {
        return '';
    }
    $fp = fopen($cachefile, 'r');
    $str = @fread($fp, filesize($cachefile));
    fclose($fp);
    return $str;
}

/**
 *  写入缓存块
 *
 * @param     string  $cacheid  缓存ID
 * @param     string  $str  字符串信息
 * @return    string
 */
function WriteCacheBlock($cacheid, $str)
{
    $cachefile = DEDEDATA.'/cache/'.$cacheid.'.inc';
    $fp = fopen($cachefile, 'w');
    $str = fwrite($fp, $str);
    fclose($fp);
}
