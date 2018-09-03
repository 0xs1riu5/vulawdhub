<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 自由列表类
 *
 * @version        $Id: arc.freelist.class.php 3 15:15 2010年7月7日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
require_once DEDEINC.'/arc.partview.class.php';
@set_time_limit(0);

/**
 * 自由列表类
 *
 * @package          FreeList
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class FreeList
{
    var $dsql;
    var $dtp;
    var $TypeID;
    var $TypeLink;
    var $PageNo;
    var $TotalPage;
    var $TotalResult;
    var $PageSize;
    var $ChannelUnit;
    var $Fields;
    var $PartView;
    var $FLInfos;
    var $ListObj;
    var $TempletsFile;
    var $maintable;

    //php5构造函数
    function __construct($fid)
    {
        global $dsql;
        $this->FreeID = $fid;
        $this->TypeLink = new TypeLink(0);
        $this->dsql = $dsql;
        $this->maintable = '#@__archives';
        $this->TempletsFile = '';
        $this->FLInfos = $this->dsql->GetOne("SELECT * FROM `#@__freelist` WHERE aid='$fid' ");
        $liststr = $this->FLInfos['listtag'];
        $this->FLInfos['maxpage'] = (empty($this->FLInfos['maxpage']) ? 100 : $this->FLInfos['maxpage']);

        //载入数据里保存的列表属性信息
        $ndtp = new DedeTagParse();
        $ndtp->SetNameSpace("dede","{","}");
        $ndtp->LoadString($liststr);
        $this->ListObj = $ndtp->GetTag('list');
        $this->PageSize = $this->ListObj->GetAtt('pagesize');
        if(empty($this->PageSize))
        {
            $this->PageSize = 30;
        }
        $channelid = $this->ListObj->GetAtt('channel');
        
        /*
        if(empty($channelid))
        {
            showmsg('必须指定频道','-1');exit();
        }
        else
        {
            $channelid = intval($channelid);
            $channelinfo = $this->dsql->getone("select maintable from #@__channeltype where id='$channelid'");
            $this->maintable = $channelinfo['maintable'];
        }
        */
        $channelid = intval($channelid);
        $this->maintable = '#@__archives';
        
        //全局模板解析器
        $this->dtp = new DedeTagParse();
        $this->dtp->SetNameSpace("dede","{","}");
        $this->dtp->SetRefObj($this);

        //设置一些全局参数的值
        $this->Fields['aid'] = $this->FLInfos['aid'];
        $this->Fields['title'] = $this->FLInfos['title'];
        $this->Fields['position'] = $this->FLInfos['title'];
        $this->Fields['keywords'] = $this->FLInfos['keywords'];
        $this->Fields['description'] = $this->FLInfos['description'];
        $channelid = $this->ListObj->GetAtt('channel');
        if(!empty($channelid))
        {
            $this->Fields['channeltype'] = $channelid;
            $this->ChannelUnit = new ChannelUnit($channelid);
        }
        else
        {
            $this->Fields['channeltype'] = 0;
        }
        foreach($GLOBALS['PubFields'] as $k=>$v)
        {
            $this->Fields[$k] = $v;
        }
        $this->PartView = new PartView();
        $this->CountRecord();
    }

    //php4构造函数
    function FreeList($fid)
    {
        $this->__construct($fid);
    }

    //关闭相关资源
    function Close()
    {
    }

    /**
     *  统计列表里的记录
     *
     * @access    private
     * @return    void
     */
    function CountRecord()
    {
        global $cfg_list_son,$cfg_needsontype;

        //统计数据库记录
        $this->TotalResult = -1;
        if(isset($GLOBALS['TotalResult']))
        {
            $this->TotalResult = $GLOBALS['TotalResult'];
        }
        if(isset($GLOBALS['PageNo']))
        {
            $this->PageNo = $GLOBALS['PageNo'];
        }
        else
        {
            $this->PageNo = 1;
        }

        //已经有总记录的值
        if($this->TotalResult==-1)
        {
            $addSql  = " arcrank > -1 AND channel>-1 ";
            $typeid = $this->ListObj->GetAtt('typeid');
            $subday = $this->ListObj->GetAtt('subday');
            $listtype = $this->ListObj->GetAtt('type');
            $att = $this->ListObj->GetAtt('att');
            $channelid = $this->ListObj->GetAtt('channel');
            if(empty($channelid))
            {
                $channelid = 0;
            }

            //是否指定栏目条件
            if(!empty($typeid))
            {
                if($cfg_list_son=='N')
                {
                    $addSql .= " AND (typeid='$typeid') ";
                }
                else
                {
                    $addSql .= " AND typeid in (".GetSonIds($typeid,0,TRUE).") ";
                }
            }

            //自定义属性条件
            if($att!='') {
                $flags = explode(',',$att);
                for($i=0;isset($flags[$i]);$i++) $addSql .= " AND FIND_IN_SET('{$flags[$i]}',flag)>0 ";
            }

            //文档的频道模型
            if($channelid>0 && !preg_match("#spec#i", $listtype))
            {
                $addSql .= " AND channel = '$channelid' ";
            }

            //推荐文档 带缩略图  专题文档
            if(preg_match("#commend#i",$listtype))
            {
                $addSql .= " AND FIND_IN_SET('c',flag) > 0  ";
            }
            if(preg_match("#image#i",$listtype))
            {
                $addSql .= " AND litpic <> ''  ";
            }
            if(preg_match("#spec#i",$listtype) || $channelid==-1)
            {
                $addSql .= " AND channel = -1  ";
            }
            if(!empty($subday))
            {
                $starttime = time() - $subday * 86400;
                $addSql .= " AND senddate > $starttime  ";
            }
            $keyword = $this->ListObj->GetAtt('keyword');
            if(!empty($keyword))
            {
                $addSql .= " AND CONCAT(title,keywords) REGEXP '$keyword' ";
            }
            $cquery = "SELECT COUNT(*) AS dd FROM `{$this->maintable}` WHERE $addSql";
            $row = $this->dsql->GetOne($cquery);
            if(is_array($row))
            {
                $this->TotalResult = $row['dd'];
            }
            else
            {
                $this->TotalResult = 0;
            }
        }
        $this->TotalPage = ceil($this->TotalResult/$this->PageSize);
        if($this->TotalPage > $this->FLInfos['maxpage'])
        {
            $this->TotalPage = $this->FLInfos['maxpage'];
            $this->TotalResult = $this->TotalPage * $this->PageSize;
        }
    }

    /**
     *  载入模板
     *
     * @access    public
     * @return    void
     */
    function LoadTemplet()
    {
        $tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
        $tempfile = str_replace("{style}",$GLOBALS['cfg_df_style'],$this->FLInfos['templet']);
        $tempfile = $tmpdir."/".$tempfile;
        if(!file_exists($tempfile))
        {
            $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/list_free.htm";
        }
        $this->dtp->LoadTemplate($tempfile);
        $this->TempletsFile = preg_replace("#^".$GLOBALS['cfg_basedir']."#", '', $tempfile);
    }

    /**
     *  列表创建HTML
     *
     * @access    public
     * @param     string  $startpage  开始页面
     * @param     string  $makepagesize  生成的页码数
     * @return    string
     */
    function MakeHtml($startpage=1, $makepagesize=0)
    {
        $this->LoadTemplet();
        $murl = "";
        if(empty($startpage))
        {
            $startpage = 1;
        }
        $this->ParseTempletsFirst();
        $totalpage = ceil($this->TotalResult/$this->PageSize);
        if($totalpage==0)
        {
            $totalpage = 1;
        }
        if($makepagesize>0)
        {
            $endpage = $startpage+$makepagesize;
        }
        else
        {
            $endpage = ($totalpage+1);
        }
        if($endpage>($totalpage+1))
        {
            $endpage = $totalpage;
        }
        $firstFile = '';
        for($this->PageNo=$startpage;$this->PageNo<$endpage;$this->PageNo++)
        {
            $this->ParseDMFields($this->PageNo,1);

            //文件名
            $makeFile = $this->GetMakeFileRule();
            if(!preg_match("#^\/#", $makeFile))
            {
                $makeFile = "/".$makeFile;
            }
            $makeFile = str_replace('{page}',$this->PageNo,$makeFile);
            $murl = $makeFile;
            $makeFile = $GLOBALS['cfg_basedir'].$makeFile;
            $makeFile = preg_replace("#\/{1,}#", "/", $makeFile);
            if($this->PageNo==1)
            {
                $firstFile = $makeFile;
            }

            //保存文件
            $this->dtp->SaveTo($makeFile);
            echo "成功创建：<a href='".preg_replace("#\/{1,}#", "/", $murl)."' target='_blank'>".preg_replace("#\/{1,}#", "/", $murl)."</a><br/>";
        }
        if($this->FLInfos['nodefault']==0)
        {
            $murl = '/'.str_replace('{cmspath}',$GLOBALS['cfg_cmspath'],$this->FLInfos['listdir']);
            $murl .= '/'.$this->FLInfos['defaultpage'];
            $indexfile = $GLOBALS['cfg_basedir'].$murl;
            $murl = preg_replace("#\/{1,}#", "/", $murl);
            echo "复制：$firstFile 为 ".$this->FLInfos['defaultpage']." <br/>";
            copy($firstFile,$indexfile);
        }
        $this->Close();
        return $murl;
    }

    /**
     *  显示列表
     *
     * @access    public
     * @return    void
     */
    function Display()
    {
        $this->LoadTemplet();
        $this->ParseTempletsFirst();
        $this->ParseDMFields($this->PageNo,0);
        $this->dtp->Display();
    }

    /**
     *  显示单独模板页面
     *
     * @access    public
     * @return    void
     */
    function DisplayPartTemplets()
    {
        $nmfa = 0;
        $tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
        if($this->Fields['ispart']==1)
        {
            $tempfile = str_replace("{tid}",$this->FreeID,$this->Fields['tempindex']);
            $tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
            $tempfile = $tmpdir."/".$tempfile;
            if(!file_exists($tempfile))
            {
                $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default.htm";
            }
            $this->PartView->SetTemplet($tempfile);
        }
        else if($this->Fields['ispart']==2)
        {
            $tempfile = str_replace("{tid}",$this->FreeID,$this->Fields['tempone']);
            $tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
            if(is_file($tmpdir."/".$tempfile))
            {
                $this->PartView->SetTemplet($tmpdir."/".$tempfile);
            }
            else
            {
                $this->PartView->SetTemplet("这是没有使用模板的单独页！","string"); $nmfa = 1;
            }
        }
        CreateDir($this->Fields['typedir']);
        $makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",$this->Fields['typedir'],$this->Fields['defaultname'],$this->Fields['namerule2']);
        $makeFile = $this->GetTruePath().$makeUrl;
        if($nmfa==0)
        {
            $this->PartView->Display();
        }
        else{
            if(!file_exists($makeFile))
            {
                $this->PartView->Display();
            }
            else
            {
                include($makeFile);
            }
        }
    }

    /**
     *  解析模板，对固定的标记进行初始给值
     *
     * @access    public
     * @return    void
     */
    function ParseTempletsFirst()
    {
        MakeOneTag($this->dtp,$this);
    }

    /**
     *  解析模板，对内容里的变动进行赋值
     *
     * @access    public
     * @param     string  $PageNo  页码
     * @param     string  $ismake  是否编译
     * @return    string
     */
    function ParseDMFields($PageNo,$ismake=1)
    {
        foreach($this->dtp->CTags as $tagid=>$ctag)
        {
            if($ctag->GetName()=="freelist")
            {
                $limitstart = ($this->PageNo-1) * $this->PageSize;
                if($this->PageNo > $this->FLInfos['maxpage']) $this->dtp->Assign($tagid, '已经超过了最大允许列出的页面！');
                else $this->dtp->Assign($tagid,$this->GetList($limitstart,$ismake));
            }
            else if($ctag->GetName()=="pagelist")
            {
                $list_len = trim($ctag->GetAtt("listsize"));
                $ctag->GetAtt("listitem")=="" ? $listitem="info,index,pre,pageno,next,end,option" : $listitem=$ctag->GetAtt("listitem");
                if($list_len=="")
                {
                    $list_len = 3;
                }
                if($ismake==0)
                {
                    $this->dtp->Assign($tagid,$this->GetPageListDM($list_len,$listitem));
                }
                else
                {
                    $this->dtp->Assign($tagid,$this->GetPageListST($list_len,$listitem));
                }
            }
            else if($ctag->GetName()=="pageno")
            {
                $this->dtp->Assign($tagid,$PageNo);
            }
        }
    }

    /**
     *  获得要创建的文件名称规则
     *
     * @access    public
     * @return    string
     */
    function GetMakeFileRule()
    {
        $okfile = '';
        $namerule = $this->FLInfos['namerule'];
        $listdir = $this->FLInfos['listdir'];
        $listdir = str_replace('{cmspath}',$GLOBALS['cfg_cmspath'],$listdir);
        $okfile = str_replace('{listid}',$this->FLInfos['aid'],$namerule);
        $okfile = str_replace('{listdir}',$listdir,$okfile);
        $okfile = str_replace("\\","/",$okfile);
        $mdir = preg_replace("#/([^/]*)$#", "", $okfile);
        if(!preg_match("#\/#", $mdir) && preg_match("#\.#", $mdir))
        {
            return $okfile;
        }
        else
        {
            CreateDir($mdir,'','');
            return $okfile;
        }
    }

    /**
     *  获得一个单列的文档列表
     *
     * @access    public
     * @param     string  $limitstart  开始限制
     * @param     string  $ismake  是否编译
     * @return    string
     */
    function GetList($limitstart, $ismake=1)
    {
        global $cfg_list_son,$cfg_needsontype;
        $col = $this->ListObj->GetAtt('col');
        if(empty($col))
        {
            $col = 1;
        }
        $titlelen = $this->ListObj->GetAtt('titlelen');
        $infolen = $this->ListObj->GetAtt('infolen');
        $imgwidth = $this->ListObj->GetAtt('imgwidth');
        $imgheight = $this->ListObj->GetAtt('imgheight');
        $titlelen = AttDef($titlelen,60);
        $infolen = AttDef($infolen,250);
        $imgwidth = AttDef($imgwidth,80);
        $imgheight = AttDef($imgheight,80);
        $innertext = trim($this->ListObj->GetInnerText());
        if(empty($innertext)) $innertext = GetSysTemplets("list_fulllist.htm");

        $tablewidth = 100;
        if($col=="") $col=1;
        $colWidth = ceil(100 / $col);
        $tablewidth = $tablewidth."%";
        $colWidth = $colWidth."%";

        //按不同情况设定SQL条件
        $orwhere = " arc.arcrank > -1 AND channel>-1 ";
        $typeid = $this->ListObj->GetAtt('typeid');
        $subday = $this->ListObj->GetAtt('subday');
        $listtype = $this->ListObj->GetAtt('type');
        $att = $this->ListObj->GetAtt('att');
        $channelid = $this->ListObj->GetAtt('channel');
        if(empty($channelid)) $channelid = 0;

        //是否指定栏目条件
        if(!empty($typeid))
        {
            if($cfg_list_son=='N')
            {
                $orwhere .= " AND (arc.typeid='$typeid') ";
            }
            else
            {
                $orwhere .= " AND arc.typeid IN (".GetSonIds($typeid, 0, TRUE).") ";
            }
        }

        //自定义属性条件
        if($att!='') {
            $flags = explode(',', $att);
            for($i=0; isset($flags[$i]); $i++) $orwhere .= " AND FIND_IN_SET('{$flags[$i]}',flag)>0 ";
        }
        //文档的频道模型
        if($channelid>0 && !preg_match("#spec#i", $listtype))
        {
            $orwhere .= " AND arc.channel = '$channelid' ";
        }

        //推荐文档 带缩略图  专题文档
        if(preg_match("#commend#i",$listtype))
        {
            $orwhere .= " AND FIND_IN_SET('c',flag) > 0  ";
        }
        if(preg_match("#image#i",$listtype))
        {
            $orwhere .= " AND arc.litpic <> ''  ";
        }
        if(preg_match("#spec#i",$listtype) || $channelid==-1)
        {
            $orwhere .= " AND arc.channel = -1  ";
        }
        if(!empty($subday))
        {
            $starttime = time() - $subday*86400;
            $orwhere .= " AND arc.senddate > $starttime  ";
        }
        $keyword = $this->ListObj->GetAtt('keyword');
        if(!empty($keyword))
        {
            $orwhere .= " AND CONCAT(arc.title,arc.keywords) REGEXP '$keyword' ";
        }
        $orderby = $this->ListObj->GetAtt('orderby');
        $orderWay = $this->ListObj->GetAtt('orderway');

        //排序方式
        $ordersql = "";
        if($orderby=="senddate")
        {
            $ordersql=" ORDER BY arc.senddate $orderWay";
        }
        else if($orderby=="pubdate")
        {
            $ordersql=" ORDER BY arc.pubdate $orderWay";
        }
        else if($orderby=="id")
        {
            $ordersql="  ORDER BY arc.id $orderWay";
        }
        else if($orderby=="hot"||$orderby=="click")
        {
            $ordersql = " ORDER BY arc.click $orderWay";
        }
        else if($orderby=="lastpost")
        {
            $ordersql = "  ORDER BY arc.lastpost $orderWay";
        }
        else if($orderby=="scores")
        {
            $ordersql = "  ORDER BY arc.scores $orderWay";
        }
        else if($orderby=="rand")
        {
            $ordersql = "  ORDER BY rand()";
        }
        else
        {
            $ordersql=" ORDER BY arc.sortrank $orderWay";
        }

        //获得附加表的相关信息
        $addField = "";
        $addJoin = "";
        if(is_object($this->ChannelUnit))
        {
            $addtable  = $this->ChannelUnit->ChannelInfos['addtable'];
            if($addtable!="")
            {
                $addJoin = " LEFT JOIN $addtable ON arc.id = ".$addtable.".aid ";
                $addField = "";
                $fields = explode(",",$this->ChannelUnit->ChannelInfos['listfields']);
                foreach($fields as $k=>$v)
                {
                    $nfields[$v] = $k;
                }
                foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
                {
                    if(isset($nfields[$k]))
                    {
                        if(!empty($arr['rename']))
                        {
                            $addField .= ",".$addtable.".".$k." as ".$arr['rename'];
                        }
                        else
                        {
                            $addField .= ",".$addtable.".".$k;
                        }
                    }
                }
            }
        }

        $query = "SELECT arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,
        tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
        $addField
        FROM {$this->maintable} arc
        LEFT JOIN #@__arctype tp ON arc.typeid=tp.id
        $addJoin
        WHERE $orwhere $ordersql LIMIT $limitstart,".$this->PageSize;
        $this->dsql->SetQuery($query);
        $this->dsql->Execute("al");
        $artlist = "";
        if($col>1)
        {
            $artlist = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
        }
        $indtp = new DedeTagParse();
        $indtp->SetNameSpace("field","[","]");
        $indtp->LoadSource($innertext);
        $GLOBALS['autoindex'] = 0;
        for($i=0;$i<$this->PageSize;$i++)
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
                    $GLOBALS['autoindex']++;

                    //处理一些特殊字段
                    $row['id'] =  $row['id'];
                    $row['arcurl'] = $this->GetArcUrl($row['id'],$row['typeid'],$row['senddate'],
                    $row['title'],$row['ismake'],$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],
                    $row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);
                    $row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],
                    $row['ispart'],$row['namerule2'],$row['siteurl'],$row['sitepath']);
                    if($ismake==0 && $GLOBALS['cfg_multi_site']=='Y')
                    {
                        if($row["siteurl"]=="")
                        {
                            $row["siteurl"] = $GLOBALS['cfg_mainsite'];
                        }
                    }

                    $row['description'] = cn_substr($row['description'],$infolen);

                    if($row['litpic'] == '-' || $row['litpic'] == '')
                    {
                        $row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
                    }
                    if(!preg_match("#^http:\/\/#i", $row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
                    {
                        $row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
                    }
                    $row['picname'] = $row['litpic'];
                    $row['info'] = $row['description'];
                    $row['filename'] = $row['arcurl'];
                    $row['stime'] = GetDateMK($row['pubdate']);
                    $row['textlink'] = "<a href='".$row['filename']."' title='".str_replace("'","",$row['title'])."'>".$row['title']."</a>";
                    $row['typelink'] = "<a href='".$row['typeurl']."'>[".$row['typename']."]</a>";
                    $row['imglink'] = "<a href='".$row['filename']."'><img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".str_replace("'","",$row['title'])."'></a>";
                    $row['image'] = "<img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".str_replace("'","",$row['title'])."'>";
                    $row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
                    $row['memberurl'] = $GLOBALS['cfg_memberurl'];
                    $row['templeturl'] = $GLOBALS['cfg_templeturl'];
                    $row['title'] = cn_substr($row['title'],$titlelen);
                    if($row['color']!="")
                    {
                        $row['title'] = "<font color='".$row['color']."'>".$row['title']."</font>";
                    }
                    if(preg_match("#c#", $row['flag']))
                    {
                        $row['title'] = "<b>".$row['title']."</b>";
                    }

                    //编译附加表里的数据
                    if(is_object($this->ChannelUnit))
                    {
                        foreach($row as $k=>$v)
                        {
                            if(preg_match("#[A-Z]#", $k))
                            {
                                $row[strtolower($k)] = $v;
                            }
                        }
                        foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
                        {
                            if(isset($row[$k]))
                            {
                                $row[$k] = $this->ChannelUnit->MakeField($k,$row[$k]);
                            }
                        }
                    }

                    //解析单条记录
                    if(is_array($indtp->CTags))
                    {
                        foreach($indtp->CTags as $k=>$ctag)
                        {
                            $_f = $ctag->GetName();
                            if($_f=='array')
                            {
                                //传递整个数组，在runphp模式中有特殊作用
                                $indtp->Assign($k,$row);
                            }
                            else
                            {
                                if(isset($row[$_f]))
                                {
                                    $indtp->Assign($k,$row[$_f]);
                                }
                                else
                                {
                                    $indtp->Assign($k,"");
                                }
                            }
                        }
                    }
                    $artlist .= $indtp->GetResult();
                }//if hasRow

                else
                {
                    $artlist .= "";
                }
                if($col>1)
                {
                    $artlist .= "    </td>\r\n";
                }
            }//Loop Col

            if($col>1){
                $i += $col - 1;
            }
            if($col>1)
            {
                $artlist .= "    </tr>\r\n";
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
     *  获取静态的分页列表
     *
     * @access    public
     * @param     string  $list_len  列表尺寸
     * @param     string  $listitem  列表项目
     * @return    string
     */
    function GetPageListST($list_len, $listitem="info,index,end,pre,next,pageno")
    {
        $prepage="";
        $nextpage="";
        $prepagenum = $this->PageNo-1;
        $nextpagenum = $this->PageNo+1;
        if($list_len=="" || preg_match("#[^0-9]#", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult/$this->PageSize);
        if($totalpage <= 1 && $this->TotalResult > 0)
        {
            return "共1页/".$this->TotalResult."条记录";
        }
        if($this->TotalResult == 0)
        {
            return "共0页/".$this->TotalResult."条记录";
        }
        $maininfo = " 共{$totalpage}页/".$this->TotalResult."条记录 ";
        $purl = $this->GetCurUrl();
        $tnamerule = $this->GetMakeFileRule();
        $tnamerule = preg_replace("#^(.*)\/#", '', $tnamerule);
        

        //获得上一页和主页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<a href='".str_replace("{page}", $prepagenum, $tnamerule)."'>上一页</a>\r\n";
            $indexpage="<a href='".str_replace("{page}", 1, $tnamerule)."'>首页</a>\r\n";
        }
        else
        {
            $indexpage="<a href='#'>首页</a>\r\n";
        }

        //下一页,未页的链接
        if($this->PageNo!=$totalpage && $totalpage>1)
        {
            $nextpage.="<a href='".str_replace("{page}",$nextpagenum,$tnamerule)."'>下一页</a>\r\n";
            $endpage="<a href='".str_replace("{page}",$totalpage,$tnamerule)."'>末页</a>\r\n";
        }
        else
        {
            $endpage="<a href='#'>末页</a>\r\n";
        }

        //option链接
        $optionlen = strlen($totalpage);
        $optionlen = $optionlen*12 + 18;
        if($optionlen < 36) $optionlen = 36;
        if($optionlen > 100) $optionlen = 100;
        $optionlist = "<select name='sldd' style='width:$optionlen' onchange='location.href=this.options[this.selectedIndex].value;'>\r\n";
        for($fl=1; $fl<=$totalpage; $fl++)
        {
            if($fl==$this->PageNo)
            {
                $optionlist .= "<option value='" . str_replace("{page}",$fl,$tnamerule) . "' selected>$fl</option>\r\n";
            } else {
                $optionlist .= "<option value='" . str_replace("{page}",$fl,$tnamerule)."'>$fl</option>\r\n";
            }
        }
        $optionlist .= "</select>";

        //获得数字链接
        $listdd="";
        $total_list = $list_len * 2 + 1;
        if($this->PageNo >= $total_list)
        {
            $j = $this->PageNo-$list_len;
            $total_list = $this->PageNo+$list_len;
            if($total_list > $totalpage)
            {
                $total_list = $totalpage;
            }
        }
        else
        {
            $j = 1;
            if($total_list > $totalpage)
            {
                $total_list = $totalpage;
            }
        }
        
        for($j; $j<=$total_list; $j++)
        {
            if($j==$this->PageNo)
            {
                $listdd.= "<strong>{$j}</strong>\r\n";
            }
            else
            {
                $listdd.="<a href='".str_replace("{page}", $j, $tnamerule)."'>".$j."</a>\r\n";
            }
        }
        $plist = "";
        if(preg_match('#info#i', $listitem))
        {
            $plist .= $maininfo.' ';
        }
        if(preg_match('#index#i',$listitem))
        {
            $plist .= $indexpage.' ';
        }
        if(preg_match('#pre#i', $listitem))
        {
            $plist .= $prepage.' ';
        }
        if(preg_match('#pageno#i', $listitem))
        {
            $plist .= $listdd.' ';
        }
        if(preg_match('#next#i', $listitem))
        {
            $plist .= $nextpage.' ';
        }
        if(preg_match('#end#i', $listitem))
        {
            $plist .= $endpage.' ';
        }
        if(preg_match('#option#i', $listitem))
        {
            $plist .= $optionlist;
        }
        return $plist;
    }

    /**
     *  获取动态的分页列表
     *
     * @access    public
     * @param     string  $list_len  列表尺寸
     * @param     string  $listitem  列表项目
     * @return    string
     */
    function GetPageListDM($list_len,$listitem="index,end,pre,next,pageno")
    {
        $prepage="";
        $nextpage="";
        $prepagenum = $this->PageNo-1;
        $nextpagenum = $this->PageNo+1;
        if($list_len==""||preg_match("/[^0-9]/", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult/$this->PageSize);
        if($totalpage<=1 && $this->TotalResult>0)
        {
            return "共1页/".$this->TotalResult."条记录";
        }
        if($this->TotalResult == 0)
        {
            return "共0页/".$this->TotalResult."条记录";
        }
        $maininfo = "共{$totalpage}页/".$this->TotalResult."条记录";
        $purl = $this->GetCurUrl();
        $geturl = "lid=".$this->FreeID."&TotalResult=".$this->TotalResult."&";
        $hidenform = "<input type='hidden' name='lid' value='".$this->FreeID."' />\r\n";
        $hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."' />\r\n";
        $purl .= "?".$geturl;

        //获得上一页和下一页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<a href='".$purl."PageNo=$prepagenum'>上一页</a>\r\n";
            $indexpage="<a href='".$purl."PageNo=1'>首页</a>\r\n";
        }
        else
        {
            $indexpage="<a href='#'>首页</a>\r\n";
        }
        if($this->PageNo!=$totalpage && $totalpage>1)
        {
            $nextpage.="<a href='".$purl."PageNo=$nextpagenum'>下一页</a>\r\n";
            $endpage="<a href='".$purl."PageNo=$totalpage'>末页</a>\r\n";
        }
        else
        {
            $endpage="<a href='#'>末页</a>\r\n";
        }

        //获得数字链接
        $listdd="";
        $total_list = $list_len * 2 + 1;
        if($this->PageNo >= $total_list)
        {
            $j = $this->PageNo-$list_len;
            $total_list = $this->PageNo+$list_len;
            if($total_list>$totalpage) $total_list=$totalpage;
        }
        else
        {
            $j=1;
            if($total_list>$totalpage) $total_list=$totalpage;
        }
        for($j;$j<=$total_list;$j++)
        {
            if($j==$this->PageNo)
            {
                $listdd.= "<a href='#'>.$j.</a>\r\n";
            }
            else
            {
                $listdd.="<a href='".$purl."PageNo=$j'>".$j."</a>\r\n";
            }
        }
        $plist  = "<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
        $plist .= $maininfo.$indexpage.$prepage.$listdd.$nextpage.$endpage;
        if($totalpage>$total_list)
        {
            $plist.="<input type='text' name='PageNo'  value='".$this->PageNo."' style='width:30px' />\r\n";
            $plist.="<input type='submit' name='plistgo' value='GO' />\r\n";
        }
        $plist .= "</form>\r\n";
        return $plist;
    }

    /**
     *  获得一个指定档案的链接
     *
     * @access    public
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
    function GetArcUrl($aid, $typeid, $timetag, $title, $ismake=0, $rank=0, $namerule='', $artdir='',
    $money=0, $filename='', $moresite='', $siteurl='', $sitepath='')
    {
        return GetFileUrl($aid, $typeid, $timetag, $title, $ismake, $rank, $namerule, $artdir,
        $money, $filename, $moresite, $siteurl, $sitepath);
    }

    /**
     *  获得当前的页面文件的url
     *
     * @access    public
     * @return    void
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