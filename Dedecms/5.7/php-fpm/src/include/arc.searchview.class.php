<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 搜索视图类
 *
 * @version        $Id: arc.searchview.class.php 1 15:26 2010年7月7日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/splitword.class.php");
require_once(DEDEINC."/taglib/hotwords.lib.php");
require_once(DEDEINC."/taglib/channel.lib.php");

@set_time_limit(0);

/**
 * 搜索视图类
 *
 * @package          SearchView
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class SearchView
{
    var $dsql;
    var $dtp;
    var $dtp2;
    var $TypeID;
    var $TypeLink;
    var $PageNo;
    var $TotalPage;
    var $TotalResult;
    var $PageSize;
    var $ChannelType;
    var $TempInfos;
    var $Fields;
    var $PartView;
    var $StartTime;
    var $Keywords;
    var $OrderBy;
    var $SearchType;
    var $mid;
    var $KType;
    var $Keyword;
    var $SearchMax;
    var $SearchMaxRc;
    var $SearchTime;
    var $AddSql;
    var $RsFields;
    var $Sphinx;

    /**
     *  php5构造函数
     *
     * @access    public
     * @param     int  $typeid  栏目ID
     * @param     string  $keyword  关键词
     * @param     string  $orderby  排序
     * @param     string  $achanneltype  频道类型
     * @param     string  $searchtype  搜索类型
     * @param     string  $starttime  开始时间
     * @param     string  $upagesize  页数
     * @param     string  $kwtype  关键词类型
     * @param     string  $mid  会员ID
     * @return    string
     */
    function __construct($typeid,$keyword,$orderby,$achanneltype="all",
    $searchtype='',$starttime=0,$upagesize=20,$kwtype=1,$mid=0)
    {
        global $cfg_search_max,$cfg_search_maxrc,$cfg_search_time,$cfg_sphinx_article;
        if(empty($upagesize))
        {
            $upagesize = 10;
        }
        $this->TypeID = $typeid;
        $this->Keyword = $keyword;
        $this->OrderBy = $orderby;
        $this->KType = $kwtype;
        $this->PageSize = $upagesize;
        $this->StartTime = $starttime;
        $this->ChannelType = $achanneltype;
        $this->SearchMax = $cfg_search_max;
        $this->SearchMaxRc = $cfg_search_maxrc;
        $this->SearchTime = $cfg_search_time;
        $this->mid = $mid;
        $this->RsFields = '';
        $this->SearchType = $searchtype=='' ? 'titlekeyword' : $searchtype;
        $this->dsql = $GLOBALS['dsql'];
        $this->dtp = new DedeTagParse();
        $this->dtp->SetRefObj($this);
        $this->dtp->SetNameSpace("dede","{","}");
        $this->dtp2 = new DedeTagParse();
        $this->dtp2->SetNameSpace("field","[","]");
        $this->TypeLink = new TypeLink($typeid);
        // 通过分词获取关键词
        $this->Keywords = $this->GetKeywords($keyword);

        //设置一些全局参数的值
        if($this->TypeID=="0"){
            $this->ChannelTypeid=1;
        }else{
            $row =$this->dsql->GetOne("SELECT channeltype FROM `#@__arctype` WHERE id={$this->TypeID}");
            $this->ChannelTypeid=$row['channeltype'];
        }
        foreach($GLOBALS['PubFields'] as $k=>$v)
        {
            $this->Fields[$k] = $v;
        }
        if ($cfg_sphinx_article == 'Y')
        {
            // 初始化sphinx
            $this->sphinx = new SphinxClient;
            
            $mode = SPH_MATCH_EXTENDED2;            //匹配模式
            $ranker = SPH_RANK_PROXIMITY_BM25; //统计相关度计算模式，仅使用BM25评分计算
            $this->sphinx->SetServer($GLOBALS['cfg_sphinx_host'], $GLOBALS['cfg_sphinx_port']);
            $this->sphinx->SetArrayResult(true);
            $this->sphinx->SetMatchMode($mode);
            $this->sphinx->SetRankingMode($ranker);
            
            $this->CountRecordSphinx();
        } else {
            $this->CountRecord();
        }
        
        
        $tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/search.htm";
        if(!file_exists($tempfile)||!is_file($tempfile))
        {
            echo "模板文件不存在，无法解析！";
            exit();
        }
        $this->dtp->LoadTemplate($tempfile);
        $this->TempInfos['tags'] = $this->dtp->CTags;
        $this->TempInfos['source'] = $this->dtp->SourceString;
        if($this->PageSize=="")
        {
            $this->PageSize = 20;
        }
        $this->TotalPage = ceil($this->TotalResult/$this->PageSize);
        if($this->PageNo==1)
        {
            $this->dsql->ExecuteNoneQuery("UPDATE `#@__search_keywords` SET result='".$this->TotalResult."' WHERE keyword='".addslashes($keyword)."'; ");
        }
    
    }

    //php4构造函数
    function SearchView($typeid,$keyword,$orderby,$achanneltype="all",
    $searchtype="",$starttime=0,$upagesize=20,$kwtype=1,$mid=0)
    {
        $this->__construct($typeid,$keyword,$orderby,$achanneltype,$searchtype,$starttime,$upagesize,$kwtype,$mid);
    }

    //关闭相关资源
    function Close()
    {
    }

    /**
     *  获得关键字的分词结果，并保存到数据库
     *
     * @access    public
     * @param     string  $keyword  关键词
     * @return    string
     */
    function GetKeywords($keyword)
    {
        global $cfg_soft_lang;
        $keyword = cn_substr($keyword, 50);
        $row = $this->dsql->GetOne("SELECT spwords FROM `#@__search_keywords` WHERE keyword='".addslashes($keyword)."'; ");
        if(!is_array($row))
        {
            if(strlen($keyword)>7)
            {
                //echo $keyword;
                $sp = new SplitWord($cfg_soft_lang, $cfg_soft_lang);
                $sp->SetSource($keyword, $cfg_soft_lang, $cfg_soft_lang);
                $sp->SetResultType(2);
                $sp->StartAnalysis(TRUE);
                $keywords = $sp->GetFinallyResult(' ');
                
                $keywords = preg_replace("/[ ]{1,}/", " ", trim($keywords));
            }
            else
            {
                $keywords = $keyword;
            }
            $inquery = "INSERT INTO `#@__search_keywords`(`keyword`,`spwords`,`count`,`result`,`lasttime`)
          VALUES ('".addslashes($keyword)."', '".addslashes($keywords)."', '1', '0', '".time()."'); ";
            $this->dsql->ExecuteNoneQuery($inquery);
        }
        else
        {
            $this->dsql->ExecuteNoneQuery("UPDATE `#@__search_keywords` SET count=count+1,lasttime='".time()."' WHERE keyword='".addslashes($keyword)."'; ");
            $keywords = $row['spwords'];
        }
        return $keywords;
    }

    /**
     *  获得关键字SQL
     *
     * @access    private
     * @return    string
     */
    function GetKeywordSql()
    {
        $ks = explode(' ',$this->Keywords);
        $kwsql = '';
        $kwsqls = array();
        foreach($ks as $k)
        {
            $k = trim($k);
            if(strlen($k)<1)
            {
                continue;
            }
            if(ord($k[0])>0x80 && strlen($k)<2)
            {
                continue;
            }
            $k = addslashes($k);
            if($this->ChannelType < 0 || $this->ChannelTypeid < 0){
                $kwsqls[] = " arc.title LIKE '%$k%' ";
            }else{
                if($this->SearchType=="title"){
                    $kwsqls[] = " arc.title LIKE '%$k%' ";
                }else{
                    $kwsqls[] = " CONCAT(arc.title,' ',arc.writer,' ',arc.keywords) LIKE '%$k%' ";
                }
            }
        }
        if(!isset($kwsqls[0]))
        {
            return '';
        }
        else
        {
            if($this->KType==1)
            {
                $kwsql = join(' OR ',$kwsqls);
            }
            else
            {
                $kwsql = join(' And ',$kwsqls);
            }
            return $kwsql;
        }
    }

    /**
     *  获得相关的关键字
     *
     * @access    public
     * @param     string  $num  关键词数目
     * @return    string
     */
    function GetLikeWords($num=8)
    {
        $ks = explode(' ',$this->Keywords);
        $lsql = '';
        foreach($ks as $k)
        {
            $k = trim($k);
            if(strlen($k)<2)
            {
                continue;
            }
            if(ord($k[0])>0x80 && strlen($k)<2)
            {
                continue;
            }
            $k = addslashes($k);
            if($lsql=='')
            {
                $lsql = $lsql." CONCAT(spwords,' ') LIKE '%$k %' ";    
            }else{
                $lsql = $lsql." OR CONCAT(spwords,' ') LIKE '%$k %' ";
            }
        }
        if($lsql=='')
        {
            return '';
        }
        else
        {
            $likeword = '';
            $lsql = "(".$lsql.") AND NOT(keyword like '".addslashes($this->Keyword)."') ";
            $this->dsql->SetQuery("SELECT keyword,count FROM `#@__search_keywords` WHERE $lsql ORDER BY lasttime DESC LIMIT 0,$num; ");
            $this->dsql->Execute('l');
            while($row=$this->dsql->GetArray('l'))
            {
                if($row['count']>1000)
                {
                    $fstyle=" style='font-size:11pt;color:red'";
                }
                else if($row['count']>300)
                {
                    $fstyle=" style='font-size:10pt;color:green'";
                }
                else
                {
                    $style = "";
                }
                $likeword .= "　<a href='search.php?keyword=".urlencode($row['keyword'])."&searchtype=titlekeyword'".$style."><u>".$row['keyword']."</u></a> ";
            }
            return $likeword;
        }
    }

    /**
     *  加粗关键字
     *
     * @access    private
     * @param     string  $fstr  关键词字符
     * @return    string
     */
    function GetRedKeyWord($fstr)
    {
        //echo $fstr;
        $ks = explode(' ',$this->Keywords);
        foreach($ks as $k)
        {
            $k = trim($k);
            if($k=='')
            {
                continue;
            }
            if(ord($k[0])>0x80 && strlen($k)<2)
            {
                continue;
            }
            // 这里不区分大小写进行关键词替换
            // $fstr = str_ireplace($k, "<font color='red'>$k</font>", $fstr);
            // 速度更快,效率更高
            $fstr = str_replace($k, "<font color='red'>$k</font>", $fstr);
        }
        return $fstr;
    }
    
    // Sphinx记录统计
    function CountRecordSphinx()
    {
        $this->TotalResult = -1;
        if(isset($GLOBALS['TotalResult']))
        {
            $this->TotalResult = $GLOBALS['TotalResult'];
            $this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
        }
        if(isset($GLOBALS['PageNo']))
        {
            $this->PageNo = $GLOBALS['PageNo'];
        }
        else
        {
            $this->PageNo = 1;
        }
        
        if($this->StartTime > 0)
        {
            $this->sphinx->SetFilterRange('senddate', $this->StartTime, time(), false);
        }
        if($this->TypeID > 0)
        {
            $this->sphinx->SetFilter('typeid', GetSonIds($this->TypeID));
        }
        $this->sphinx->SetFilter('channel', array(1));
        if($this->mid > 0)
        {
            $this->sphinx->SetFilter('mid', $this->mid);
        }
        //$this->sphinx->SetFilterRange('arcrank', -1, 100, false);
        // var_dump($this->sphinx);exit;
        $res = array();
        $res = AutoCharset($this->sphinx->Query($this->Keywords, 'mysql, delta'), 'utf-8', 'gbk');
        
        $this->TotalResult = $res['total'];
    }

    /**
     *  统计列表里的记录
     *
     * @access    public
     * @return    string
     */
    function CountRecord()
    {
        $this->TotalResult = -1;
        if(isset($GLOBALS['TotalResult']))
        {
            $this->TotalResult = $GLOBALS['TotalResult'];
            $this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
        }
        if(isset($GLOBALS['PageNo']))
        {
            $this->PageNo = $GLOBALS['PageNo'];
        }
        else
        {
            $this->PageNo = 1;
        }
        $ksql = $this->GetKeywordSql();
        $ksqls = array();
        if($this->StartTime > 0)
        {
            $ksqls[] = " arc.senddate>'".$this->StartTime."' ";
        }
        if($this->TypeID > 0)
        {
            $ksqls[] = " typeid IN (".GetSonIds($this->TypeID).") ";
        }
        if($this->ChannelType > 0)
        {
            $ksqls[] = " arc.channel='".$this->ChannelType."'";
        }
        if($this->mid > 0)
        {
            $ksqls[] = " arc.mid = '".$this->mid."'";
        }
        $ksqls[] = " arc.arcrank > -1 ";
        $this->AddSql = ($ksql=='' ? join(' AND ',$ksqls) : join(' AND ',$ksqls)." AND ($ksql)" );
        if($this->ChannelType < 0 || $this->ChannelTypeid< 0){
            if($this->ChannelType=="0") $id=$this->ChannelTypeid;
            else $id=$this->ChannelType;
            $row = $this->dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id=$id");
            $addtable = trim($row['addtable']);
            $this->AddTable=$addtable;
        }else{
            $this->AddTable="#@__archives";
        }
        $cquery = "SELECT * FROM `{$this->AddTable}` arc WHERE ".$this->AddSql;
        $hascode = md5($cquery);
        $row = $this->dsql->GetOne("SELECT * FROM `#@__arccache` WHERE `md5hash`='".$hascode."' ");
        $uptime = time();
        if(is_array($row) && time()-$row['uptime'] < 3600 * 24)
        {
            $aids = explode(',', $row['cachedata']);
            $this->TotalResult = count($aids)-1;
            $this->RsFields = $row['cachedata'];
        }
        else
        {
            if($this->TotalResult==-1)
            {
                $this->dsql->SetQuery($cquery);
                $this->dsql->execute();
                $aidarr = array();
                $aidarr[] = 0;
                while($row = $this->dsql->getarray())
                {
                    if($this->ChannelType< 0 ||$this->ChannelTypeid< 0) $aidarr[] = $row['aid'];
                    else $aidarr[] = $row['id'];
                }
                $nums = count($aidarr)-1;
                $aids = implode(',', $aidarr);
                $delete = "DELETE FROM `#@__arccache` WHERE uptime<".(time() - 3600 * 24);
                $this->dsql->SetQuery($delete);
                $this->dsql->executenonequery();
                $insert = "INSERT INTO `#@__arccache` (`md5hash`, `uptime`, `cachedata`)
                 VALUES('$hascode', '$uptime', '$aids')";
                $this->dsql->SetQuery($insert);
                $this->dsql->executenonequery();
                $this->TotalResult = $nums;
            }
        }
    }

    /**
     *  显示列表
     *
     * @access    public
     * @param     string
     * @return    string
     */
    function Display()
    {
        foreach($this->dtp->CTags as $tagid=>$ctag)
        {
            $tagname = $ctag->GetName();
            if($tagname=="list")
            {
                $limitstart = ($this->PageNo-1) * $this->PageSize;
                $row = $this->PageSize;
                if(trim($ctag->GetInnerText())=="")
                {
                    $InnerText = GetSysTemplets("list_fulllist.htm");
                }
                else
                {
                    $InnerText = trim($ctag->GetInnerText());
                }
                $this->dtp->Assign($tagid,
                $this->GetArcList($limitstart,
                $row,
                $ctag->GetAtt("col"),
                $ctag->GetAtt("titlelen"),
                $ctag->GetAtt("infolen"),
                $ctag->GetAtt("imgwidth"),
                $ctag->GetAtt("imgheight"),
                $this->ChannelType,
                $this->OrderBy,
                $InnerText,
                $ctag->GetAtt("tablewidth"))
                );
            }
            else if($tagname=="pagelist")
            {
                $list_len = trim($ctag->GetAtt("listsize"));
                if($list_len=="")
                {
                    $list_len = 3;
                }
                $this->dtp->Assign($tagid,$this->GetPageListDM($list_len));
            }
            else if($tagname=="likewords")
            {
                $this->dtp->Assign($tagid,$this->GetLikeWords($ctag->GetAtt('num')));
            }
            else if($tagname=="hotwords")
            {
                $this->dtp->Assign($tagid,lib_hotwords($ctag,$this));
            }
            else if($tagname=="field")
            {
                //类别的指定字段
                if(isset($this->Fields[$ctag->GetAtt('name')]))
                {
                    $this->dtp->Assign($tagid,$this->Fields[$ctag->GetAtt('name')]);
                }
                else
                {
                    $this->dtp->Assign($tagid,"");
                }
            }
            else if($tagname=="channel")
            {
                //下级频道列表
                if($this->TypeID>0)
                {
                    $typeid = $this->TypeID; $reid = $this->TypeLink->TypeInfos['reid'];
                }
                else
                {
                    $typeid = 0; $reid=0;
                }
                $GLOBALS['envs']['typeid'] = $typeid;
                $GLOBALS['envs']['reid'] = $typeid;
                $this->dtp->Assign($tagid,lib_channel($ctag,$this));
            }//End if

        }
        global $keyword,  $oldkeyword;
        if(!empty($oldkeyword)) $keyword = $oldkeyword;
        $this->dtp->Display();
    }

    /**
     *  获得文档列表
     *
     * @access    public
     * @param     int  $limitstart  限制开始  
     * @param     int  $row  行数 
     * @param     int  $col  列数
     * @param     int  $titlelen  标题长度
     * @param     int  $infolen  描述长度
     * @param     int  $imgwidth  图片宽度
     * @param     int  $imgheight  图片高度
     * @param     string  $achanneltype  列表类型
     * @param     string  $orderby  排列顺序
     * @param     string  $innertext  底层模板
     * @param     string  $tablewidth  表格宽度
     * @return    string
     */
    function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$infolen=250,
    $imgwidth=120,$imgheight=90,$achanneltype="all",$orderby="default",$innertext="",$tablewidth="100")
    {
        global $cfg_sphinx_article;
        $typeid=$this->TypeID;
        if($row=='') $row = 10;
        if($limitstart=='') $limitstart = 0;
        if($titlelen=='') $titlelen = 30;
        if($infolen=='') $infolen = 250;
        if($imgwidth=='') $imgwidth = 120;
        if($imgheight='') $imgheight = 120;
        if($achanneltype=='') $achanneltype = '0';
        $orderby = $orderby=='' ? 'default' : strtolower($orderby);
        $tablewidth = str_replace("%","",$tablewidth);
        if($tablewidth=='') $tablewidth=100;
        if($col=='') $col=1;
        $colWidth = ceil(100/$col);
        $tablewidth = $tablewidth."%";
        $colWidth = $colWidth."%";
        $innertext = trim($innertext);
        if($innertext=='')
        {
            $innertext = GetSysTemplets("search_list.htm");
        }
        
        if ($cfg_sphinx_article == 'Y')
        {
            $ordersql = '';
            if($this->ChannelType< 0 ||$this->ChannelTypeid< 0)
            {
                if($orderby=="id"){
                    $ordersql="@id desc";
                }else{
                    $ordersql="@senddate desc";
                }
            } else {
                if($orderby=="senddate")
                {
                    $ordersql="@senddate desc";
                }
                else if($orderby=="pubdate")
                {
                    $ordersql="@pubdate desc";
                }
                else if($orderby=="id")
                {
                    $ordersql="@id desc";
                }
                else
                {
                    $ordersql="@sortrank desc";
                }
            }
            
            $this->sphinx->SetLimits($limitstart, (int)$row, ($row>1000) ? $row : 1000);
            $res = array();
            $res = AutoCharset($this->sphinx->Query($this->Keywords, 'mysql, delta'), 'utf-8', 'gbk');
            
            foreach ($res['words'] as $k => $v) {
                $this->Keywords .= " $k";
            }
            foreach($res['matches'] as $_v) {
                $aids[] = $_v['id'];
            }
            
            $aids = @implode(',', $aids);
            
            //搜索
            $query = "SELECT arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
            act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
            FROM `#@__archives` arc LEFT JOIN `#@__arctype` act ON arc.typeid=act.id
            WHERE arc.id IN ($aids)";
            
        } else {
            //排序方式
            $ordersql = '';
            if($this->ChannelType< 0 ||$this->ChannelTypeid< 0)
            {
                if($orderby=="id"){
                    $ordersql="ORDER BY arc.aid desc";
                }else{
                    $ordersql="ORDER BY arc.senddate desc";
                }
            } else {
                if($orderby=="senddate")
                {
                    $ordersql=" ORDER BY arc.senddate desc";
                }
                else if($orderby=="pubdate")
                {
                    $ordersql=" ORDER BY arc.pubdate desc";
                }
                else if($orderby=="id")
                {
                    $ordersql="  ORDER BY arc.id desc";
                }
                else
                {
                    $ordersql=" ORDER BY arc.sortrank desc";
                }
            }

            //搜索
            $query = "SELECT arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
            act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
            FROM `{$this->AddTable}` arc LEFT JOIN `#@__arctype` act ON arc.typeid=act.id
            WHERE {$this->AddSql} $ordersql LIMIT $limitstart,$row";
        }
        
        $this->dsql->SetQuery($query);
        $this->dsql->Execute("al");
        $artlist = "";
        if($col>1)
        {
            $artlist = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
        }
        $this->dtp2->LoadSource($innertext);
        for($i=0;$i<$row;$i++)
        {
            if($col>1)
            {
                $artlist .= "<tr>\r\n";
            }
            for($j=0;$j<$col;$j++)
            {
                if($col>1)
                {
                    $artlist .= "<td width='$colWidth'>\r\n";
                }
                if($row = $this->dsql->GetArray("al"))
                {
                    if($this->ChannelType< 0 || $this->ChannelTypeid< 0) {
                        $row["id"]=$row["aid"];
                        $row["ismake"]=empty($row["ismake"])? "" : $row["ismake"];
                        $row["filename"]=empty($row["filename"])? "" : $row["filename"];
                        $row["money"]=empty($row["money"])? "" : $row["money"];
                        $row["description"]=empty($row["description "])? "" : $row["description"];
                        $row["pubdate"]=empty($row["pubdate  "])? $row["senddate"] : $row["pubdate"];
                    }
                    //处理一些特殊字段
                    $row["arcurl"] = GetFileUrl($row["id"],$row["typeid"],$row["senddate"],$row["title"],
                    $row["ismake"],$row["arcrank"],$row["namerule"],$row["typedir"],$row["money"],$row['filename'],$row["moresite"],$row["siteurl"],$row["sitepath"]);
                    $row["description"] = $this->GetRedKeyWord(cn_substr($row["description"],$infolen));
                    $row["title"] = $this->GetRedKeyWord(cn_substr($row["title"],$titlelen));
                    $row["id"] =  $row["id"];
                    if($row['litpic'] == '-' || $row['litpic'] == '')
                    {
                        $row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
                    }
                    if(!preg_match("/^http:\/\//", $row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
                    {
                        $row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
                    }
                    $row['picname'] = $row['litpic'];
                    $row["typeurl"] = GetTypeUrl($row["typeid"],$row["typedir"],$row["isdefault"],$row["defaultname"],$row["ispart"],$row["namerule2"],$row["moresite"],$row["siteurl"],$row["sitepath"]);
                    $row["info"] = $row["description"];
                    $row["filename"] = $row["arcurl"];
                    $row["stime"] = GetDateMK($row["pubdate"]);
                    $row["textlink"] = "<a href='".$row["filename"]."'>".$row["title"]."</a>";
                    $row["typelink"] = "[<a href='".$row["typeurl"]."'>".$row["typename"]."</a>]";
                    $row["imglink"] = "<a href='".$row["filename"]."'><img src='".$row["picname"]."' border='0' width='$imgwidth' height='$imgheight'></a>";
                    $row["image"] = "<img src='".$row["picname"]."' border='0' width='$imgwidth' height='$imgheight'>";
                    $row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
                    $row['memberurl'] = $GLOBALS['cfg_memberurl'];
                    $row['templeturl'] = $GLOBALS['cfg_templeturl'];
                    if(is_array($this->dtp2->CTags))
                    {
                        foreach($this->dtp2->CTags as $k=>$ctag)
                        {
                            if($ctag->GetName()=='array')
                            {
                                //传递整个数组，在runphp模式中有特殊作用
                                $this->dtp2->Assign($k,$row);
                            }
                            else
                            {
                                if(isset($row[$ctag->GetName()]))
                                {
                                    $this->dtp2->Assign($k,$row[$ctag->GetName()]);
                                }
                                else
                                {
                                    $this->dtp2->Assign($k,'');
                                }
                            }
                        }
                    }
                    $artlist .= $this->dtp2->GetResult();
                }//if hasRow

                else
                {
                    $artlist .= "";
                }
                if($col>1) $artlist .= "</td>\r\n";
            }//Loop Col

            if($col>1)
            {
                $artlist .= "</tr>\r\n";
            }
        }//Loop Line

        if($col>1)
        {
            $artlist .= "</table>\r\n";
        }
        $this->dsql->FreeResult("al");

        return $artlist;
    }

    /**
     *  获取动态的分页列表
     *
     * @access    public
     * @param     string  $list_len  列表宽度
     * @return    string
     */
    function GetPageListDM($list_len)
    {
        global $oldkeyword;
        $prepage="";
        $nextpage="";
        $prepagenum = $this->PageNo - 1;
        $nextpagenum = $this->PageNo + 1;
        if($list_len=="" || preg_match("/[^0-9]/", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult / $this->PageSize);
        if($totalpage<=1 && $this->TotalResult>0)
        {
            return "共1页/".$this->TotalResult."条记录";
        }
        if($this->TotalResult == 0)
        {
            return "共0页/".$this->TotalResult."条记录";
        }
        $purl = $this->GetCurUrl();
        
        $oldkeyword = (empty($oldkeyword) ? $this->Keyword : $oldkeyword);

        //当结果超过限制时，重设结果页数
        if($this->TotalResult > $this->SearchMaxRc)
        {
            $totalpage = ceil($this->SearchMaxRc/$this->PageSize);
        }
        $infos = "<td>共找到<b>".$this->TotalResult."</b>条记录/最大显示<b>{$totalpage}</b>页 </td>\r\n";
        $geturl = "keyword=".urlencode($oldkeyword)."&searchtype=".$this->SearchType;
        $hidenform = "<input type='hidden' name='keyword' value='".rawurldecode($oldkeyword)."'>\r\n";
        $geturl .= "&channeltype=".$this->ChannelType."&orderby=".$this->OrderBy;
        $hidenform .= "<input type='hidden' name='channeltype' value='".$this->ChannelType."'>\r\n";
        $hidenform .= "<input type='hidden' name='orderby' value='".$this->OrderBy."'>\r\n";
        $geturl .= "&kwtype=".$this->KType."&pagesize=".$this->PageSize;
        $hidenform .= "<input type='hidden' name='kwtype' value='".$this->KType."'>\r\n";
        $hidenform .= "<input type='hidden' name='pagesize' value='".$this->PageSize."'>\r\n";
        $geturl .= "&typeid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
        $hidenform .= "<input type='hidden' name='typeid' value='".$this->TypeID."'>\r\n";
        $hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
        $purl .= "?".$geturl;

        //获得上一页和下一页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<td width='50'><a href='".$purl."PageNo=$prepagenum'>上一页</a></td>\r\n";
            $indexpage="<td width='30'><a href='".$purl."PageNo=1'>首页</a></td>\r\n";
        }
        else
        {
            $indexpage="<td width='30'>首页</td>\r\n";
        }
        if($this->PageNo!=$totalpage && $totalpage>1)
        {
            $nextpage.="<td width='50'><a href='".$purl."PageNo=$nextpagenum'>下一页</a></td>\r\n";
            $endpage="<td width='30'><a href='".$purl."PageNo=$totalpage'>末页</a></td>\r\n";
        }
        else
        {
            $endpage="<td width='30'>末页</td>\r\n";
        }

        //获得数字链接
        $listdd="";
        $total_list = $list_len * 2 + 1;
        if($this->PageNo >= $total_list)
        {
            $j = $this->PageNo - $list_len;
            $total_list = $this->PageNo + $list_len;
            if($total_list > $totalpage)
            {
                $total_list = $totalpage;
            }
        }
        else
        {
            $j=1;
            if($total_list > $totalpage)
            {
                $total_list = $totalpage;
            }
        }
        for($j; $j<=$total_list; $j++)
        {
            if($j == $this->PageNo)
            {
                $listdd.= "<td>$j&nbsp;</td>\r\n";
            }
            else
            {
                $listdd.="<td><a href='".$purl."PageNo=$j'>[".$j."]</a>&nbsp;</td>\r\n";
            }
        }
        $plist  =  "<table border='0' cellpadding='0' cellspacing='0'>\r\n";
        $plist .= "<tr align='center' style='font-size:10pt'>\r\n";
        $plist .= "<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
        $plist .= $infos;
        $plist .= $indexpage;
        $plist .= $prepage;
        $plist .= $listdd;
        $plist .= $nextpage;
        $plist .= $endpage;
        if($totalpage>$total_list)
        {
            $plist.="<td width='38'><input type='text' name='PageNo' style='width:28px;height:14px' value='".$this->PageNo."' /></td>\r\n";
            $plist.="<td width='30'><input type='submit' name='plistgo' value='GO' style='width:30px;height:22px;font-size:9pt' /></td>\r\n";
        }
        $plist .= "</form>\r\n</tr>\r\n</table>\r\n";
        return $plist;
    }

    /**
     *  获得当前的页面文件的url
     *
     * @access    public
     * @return    string
     */
    function GetCurUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $nowurl = $_SERVER["REQUEST_URI"];
            $nowurls = explode("?",$nowurl);
            $nowurl = $nowurls[0];
        }
        else
        {
            $nowurl = $_SERVER["PHP_SELF"];
        }
        return $nowurl;
    }
}//End Class