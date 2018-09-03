<?php   if(!defined('DEDEINC'))exit('Request Error!');
/**
 * 单表模型列表视图类
 *
 * @version        $Id: arc.sglistview.class.php 1 15:48 2010年7月7日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
@set_time_limit(0);
require_once(DEDEINC."/arc.partview.class.php");

/**
 * 单表模型列表视图类
 *
 * @package          SgListView
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class SgListView
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
    var $ChannelUnit;
    var $ListType;
    var $Fields;
    var $PartView;
    var $addSql;
    var $IsError;
    var $CrossID;
    var $IsReplace;
    var $AddTable;
    var $ListFields;
    var $searchArr;
    var $sAddTable;
    
    /**
     *  php5构造函数
     *
     * @access    public
     * @param     int  $typeid  栏目ID
     * @param     array  $searchArr  检索数组
     * @return    void
     */
    function __construct($typeid,$searchArr=array())
    {
        global $dsql;
        $this->TypeID = $typeid;
        $this->dsql = $dsql;
        $this->CrossID = '';
        $this->IsReplace = false;
        $this->IsError = false;
        $this->dtp = new DedeTagParse();
        $this->dtp->SetRefObj($this);
        $this->sAddTable = false;
        $this->dtp->SetNameSpace("dede","{","}");
        $this->dtp2 = new DedeTagParse();
        $this->dtp2->SetNameSpace("field","[","]");
        $this->TypeLink = new TypeLink($typeid);
        $this->searchArr = $searchArr;
        if(!is_array($this->TypeLink->TypeInfos))
        {
            $this->IsError = true;
        }
        if(!$this->IsError)
        {
            $this->ChannelUnit = new ChannelUnit($this->TypeLink->TypeInfos['channeltype']);
            $this->Fields = $this->TypeLink->TypeInfos;
            $this->Fields['id'] = $typeid;
            $this->Fields['position'] = $this->TypeLink->GetPositionLink(true);
            $this->Fields['title'] = preg_replace("/[<>]/", " / ", $this->TypeLink->GetPositionLink(false));

            //获得附加表和列表字段信息
            $this->AddTable = $this->ChannelUnit->ChannelInfos['addtable'];
            $listfield = trim($this->ChannelUnit->ChannelInfos['listfields']);

            $this->ListFields = explode(',', $listfield);

            //设置一些全局参数的值
            foreach($GLOBALS['PubFields'] as $k=>$v) $this->Fields[$k] = $v;
            $this->Fields['rsslink'] = $GLOBALS['cfg_cmsurl']."/data/rss/".$this->TypeID.".xml";

            //设置环境变量
            SetSysEnv($this->TypeID,$this->Fields['typename'],0,'','list');
            $this->Fields['typeid'] = $this->TypeID;

            //获得交叉栏目ID
            if($this->TypeLink->TypeInfos['cross']>0 && $this->TypeLink->TypeInfos['ispart']==0)
            {
                $selquery = '';
                if($this->TypeLink->TypeInfos['cross']==1)
                {
                    $selquery = "SELECT id,topid FROM `#@__arctype` WHERE typename LIKE '{$this->Fields['typename']}' AND id<>'{$this->TypeID}' AND topid<>'{$this->TypeID}'  ";
                }
                else
                {
                    $this->Fields['crossid'] = preg_replace("/[^0-9,]/", '', trim($this->Fields['crossid']));
                    if($this->Fields['crossid']!='')
                    {
                        $selquery = "SELECT id,topid FROM `#@__arctype` WHERE id IN({$this->Fields['crossid']}) AND id<>{$this->TypeID} AND topid<>{$this->TypeID}  ";
                    }
                }
                if($selquery!='')
                {
                    $this->dsql->SetQuery($selquery);
                    $this->dsql->Execute();
                    while($arr = $this->dsql->GetArray())
                    {
                        $this->CrossID .= ($this->CrossID=='' ? $arr['id'] : ','.$arr['id']);
                    }
                }
            }

        }//!error

    }

    //php4构造函数
    function SgListView($typeid,$searchArr=array()){
        $this->__construct($typeid,$searchArr);
    }
    //关闭相关资源
    function Close()
    {

    }

    /**
     *  统计列表里的记录
     *
     * @access    public
     * @return    void
     */
    function CountRecord()
    {
        global $cfg_list_son;

        //统计数据库记录
        $this->TotalResult = -1;
        if(isset($GLOBALS['TotalResult'])) $this->TotalResult = $GLOBALS['TotalResult'];
        if(isset($GLOBALS['PageNo'])) $this->PageNo = $GLOBALS['PageNo'];
        else $this->PageNo = 1;
        $this->addSql  = " arc.arcrank > -1 ";

        //栏目id条件
        if(!empty($this->TypeID))
        {
            if($cfg_list_son=='N')
            {
                if($this->CrossID=='') $this->addSql .= " AND (arc.typeid='".$this->TypeID."') ";
                else $this->addSql .= " AND (arc.typeid IN({$this->CrossID},{$this->TypeID})) ";
            }
            else
            {
                if($this->CrossID=='') $this->addSql .= " AND (arc.typeid IN (".GetSonIds($this->TypeID,$this->Fields['channeltype']).") ) ";
                else $this->addSql .= " AND (arc.typeid IN (".GetSonIds($this->TypeID,$this->Fields['channeltype']).",{$this->CrossID}) ) ";
            }
        }

        $naddQuery = '';
        //地区与信息类型条件
        if(count($this->searchArr) > 0)
        {
            if(!empty($this->searchArr['nativeplace']))
            {
                if($this->searchArr['nativeplace'] % 500 ==0 )
                {
                    $naddQuery .= " AND arc.nativeplace >= '{$this->searchArr['nativeplace']}' AND arc.nativeplace < '".($this->searchArr['nativeplace']+500)."'";
                }
                else
                {
                    $naddQuery .= "AND arc.nativeplace = '{$this->searchArr['nativeplace']}'";
                }
            }
            if(!empty($this->searchArr['infotype']))
            {
                if($this->searchArr['infotype'] % 500 ==0 )
                {
                    $naddQuery .= " AND arc.infotype >= '{$this->searchArr['infotype']}' AND arc.infotype < '".($this->searchArr['infotype']+500)."'";
                }
                else
                {
                    $naddQuery .= "AND arc.infotype = '{$this->searchArr['infotype']}'";
                }
            }
            if(!empty($this->searchArr['keyword']))
            {
                $naddQuery .= "AND arc.title like '%{$this->searchArr['keyword']}%' ";
          }
        }

        if($naddQuery!='')
        {
            $this->sAddTable = true;
            $this->addSql .= $naddQuery;
        }

        if($this->TotalResult==-1)
        {
            if($this->sAddTable)
            {
                $cquery = "SELECT COUNT(*) AS dd FROM `{$this->AddTable}` arc WHERE ".$this->addSql;
            }
            else
            {
                $cquery = "SELECT COUNT(*) AS dd FROM `#@__arctiny` arc WHERE ".$this->addSql;
            }
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
        //初始化列表模板，并统计页面总数
        $tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$this->TypeLink->TypeInfos['templist'];
        $tempfile = str_replace("{tid}",$this->TypeID,$tempfile);
        $tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
        if(!file_exists($tempfile))
        {
            $tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/list_default_sg.htm";
        }
        if(!file_exists($tempfile)||!is_file($tempfile))
        {
            echo "模板文件不存在，无法解析文档！";
            exit();
        }
        $this->dtp->LoadTemplate($tempfile);
        $ctag = $this->dtp->GetTag("page");
        if(!is_object($ctag))
        {
            $ctag = $this->dtp->GetTag("list");
        }
        if(!is_object($ctag))
        {
            $this->PageSize = 20;
        }
        else
        {
            if($ctag->GetAtt('pagesize')!='')
            {
                $this->PageSize = $ctag->GetAtt('pagesize');
            }
            else
            {
                $this->PageSize = 20;
            }
        }
        $this->TotalPage = ceil($this->TotalResult/$this->PageSize);
    }

    /**
     *  列表创建HTML
     *
     * @access    public
     * @param     string  $startpage  开始页面
     * @param     string  $makepagesize  生成尺寸
     * @return    string
     */
    function MakeHtml($startpage=1,$makepagesize=0)
    {
        if(empty($startpage))
        {
            $startpage = 1;
        }

        //创建封面模板文件
        if($this->TypeLink->TypeInfos['isdefault']==-1)
        {
            echo '这个类目是动态类目！';
            return '';
        }

        //单独页面
        else if($this->TypeLink->TypeInfos['ispart']>0)
        {
            $reurl = $this->MakePartTemplets();
            return $reurl;
        }

        $this->CountRecord();
        //初步给固定值的标记赋值
        $this->ParseTempletsFirst();
        $totalpage = ceil($this->TotalResult/$this->PageSize);
        if($totalpage==0)
        {
            $totalpage = 1;
        }
        CreateDir(MfTypedir($this->Fields['typedir']));
        $murl = '';
        if($makepagesize > 0)
        {
            $endpage = $startpage+$makepagesize;
        }
        else
        {
            $endpage = ($totalpage+1);
        }
        if( $endpage >= $totalpage+1 )
        {
            $endpage = $totalpage+1;
        }
        if($endpage==1)
        {
            $endpage = 2;
        }
        for($this->PageNo=$startpage; $this->PageNo < $endpage; $this->PageNo++)
        {
            $this->ParseDMFields($this->PageNo,1);
            $makeFile = $this->GetMakeFileRule($this->Fields['id'],'list',$this->Fields['typedir'],'',$this->Fields['namerule2']);
            $makeFile = str_replace("{page}",$this->PageNo,$makeFile);
            $murl = $makeFile;
            if(!preg_match("/^\//",$makeFile))
            {
                $makeFile = "/".$makeFile;
            }
            $makeFile = $this->GetTruePath().$makeFile;
            $makeFile = preg_replace("/\/{1,}/", "/", $makeFile);
            $murl = $this->GetTrueUrl($murl);
            $this->dtp->SaveTo($makeFile);
        }
        if($startpage==1)
        {
            //如果列表启用封面文件，复制这个文件第一页
            if($this->TypeLink->TypeInfos['isdefault']==1
            && $this->TypeLink->TypeInfos['ispart']==0)
            {
                $onlyrule = $this->GetMakeFileRule($this->Fields['id'],"list",$this->Fields['typedir'],'',$this->Fields['namerule2']);
                $onlyrule = str_replace("{page}","1",$onlyrule);
                $list_1 = $this->GetTruePath().$onlyrule;
                $murl = MfTypedir($this->Fields['typedir']).'/'.$this->Fields['defaultname'];
                $indexname = $this->GetTruePath().$murl;
                copy($list_1,$indexname);
            }
        }
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
        if($this->TypeLink->TypeInfos['ispart']>0 && count($this->searchArr)==0 )
        {
            $this->DisplayPartTemplets();
            return ;
        }
        $this->CountRecord();
        /*
        if((empty($this->PageNo) || $this->PageNo==1) && $this->TypeLink->TypeInfos['ispart']==1)
        {
            $tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
            $tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
            $tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
            $tempfile = $tmpdir."/".$tempfile;
            if(!file_exists($tempfile))
            {
                $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default_sg.htm";
            }
            $this->dtp->LoadTemplate($tempfile);
        }
        */
        $this->ParseTempletsFirst();
        $this->ParseDMFields($this->PageNo,0);
        $this->dtp->Display();
    }

    /**
     *  创建单独模板页面
     *
     * @access    public
     * @return    string
     */
    function MakePartTemplets()
    {
        $this->PartView = new PartView($this->TypeID,false);
        $this->PartView->SetTypeLink($this->TypeLink);
        $nmfa = 0;
        $tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
        if($this->Fields['ispart']==1)
        {
            $tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
            $tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
            $tempfile = $tmpdir."/".$tempfile;
            if(!file_exists($tempfile))
            {
                $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default_sg.htm";
            }
            $this->PartView->SetTemplet($tempfile);
        }
        else if($this->Fields['ispart']==2)
        {
            //跳转网址
            return $this->Fields['typedir'];
        }
        CreateDir(MfTypedir($this->Fields['typedir']));
        $makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",MfTypedir($this->Fields['typedir']),$this->Fields['defaultname'],$this->Fields['namerule2']);
        $makeUrl = preg_replace("/\/{1,}/", "/", $makeUrl);
        $makeFile = $this->GetTruePath().$makeUrl;
        if($nmfa==0)
        {
            $this->PartView->SaveToHtml($makeFile);
        }
        else
        {
            if(!file_exists($makeFile))
            {
                $this->PartView->SaveToHtml($makeFile);
            }
        }
        return $this->GetTrueUrl($makeUrl);
    }

    /**
     *  显示单独模板页面
     *
     * @access    public
     * @return    void
     */
    function DisplayPartTemplets()
    {
        $this->PartView = new PartView($this->TypeID,false);
        $this->PartView->SetTypeLink($this->TypeLink);
        $nmfa = 0;
        $tmpdir = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir'];
        if($this->Fields['ispart']==1)
        {
            //封面模板
            $tempfile = str_replace("{tid}",$this->TypeID,$this->Fields['tempindex']);
            $tempfile = str_replace("{cid}",$this->ChannelUnit->ChannelInfos['nid'],$tempfile);
            $tempfile = $tmpdir."/".$tempfile;
            if(!file_exists($tempfile))
            {
                $tempfile = $tmpdir."/".$GLOBALS['cfg_df_style']."/index_default_sg.htm";
            }
            $this->PartView->SetTemplet($tempfile);
        }
        else if($this->Fields['ispart']==2)
        {
            //跳转网址
            $gotourl = $this->Fields['typedir'];
            header("Location:$gotourl");
            exit();
        }
        CreateDir(MfTypedir($this->Fields['typedir']));
        $makeUrl = $this->GetMakeFileRule($this->Fields['id'],"index",MfTypedir($this->Fields['typedir']),$this->Fields['defaultname'],$this->Fields['namerule2']);
        $makeFile = $this->GetTruePath().$makeUrl;
        if($nmfa==0)
        {
            $this->PartView->Display();
        }
        else
        {
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
     *  获得站点的真实根路径
     *
     * @access    public
     * @return    string
     */
    function GetTruePath()
    {
        $truepath = $GLOBALS["cfg_basedir"];
        return $truepath;
    }

    /**
     *  获得真实连接路径
     *
     * @access    public
     * @param     string  $nurl  连接地址
     * @return    string
     */
    function GetTrueUrl($nurl)
    {
        if(preg_match("/^http:\/\//", $nurl)) return $nurl;
        if($this->Fields['moresite']==1)
        {
            if($this->Fields['sitepath']!='')
            {
                $nurl = preg_replace("/^".$this->Fields['sitepath']."/", '', $nurl);
            }
            $nurl = $this->Fields['siteurl'].$nurl;
        }
        return $nurl;
    }

    /**
     *  解析模板，对固定的标记进行初始给值
     *
     * @access    private
     * @return    void
     */
    function ParseTempletsFirst()
    {
        if(isset($this->TypeLink->TypeInfos['reid']))
        {
            $GLOBALS['envs']['reid'] = $this->TypeLink->TypeInfos['reid'];
        }
        $GLOBALS['envs']['channelid'] = $this->TypeLink->TypeInfos['channeltype'];
        $GLOBALS['envs']['typeid'] = $this->TypeID;
        $GLOBALS['envs']['cross'] = 1;
        MakeOneTag($this->dtp,$this);
    }

    /**
     *  解析模板，对内容里的变动进行赋值
     *
     * @access    public
     * @param     int  $PageNo  页码
     * @param     int  $ismake  是否编译
     * @return    void
     */
    function ParseDMFields($PageNo, $ismake=1)
    {
        //替换第二页后的内容
        if(($PageNo>1 || strlen($this->Fields['content'])<10 ) && !$this->IsReplace)
        {
            $this->dtp->SourceString = str_replace('[cmsreplace]','display:none',$this->dtp->SourceString);
            $this->IsReplace = true;
        }
        foreach($this->dtp->CTags as $tagid=>$ctag)
        {
            if($ctag->GetName()=="list")
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
                $this->GetArcList(
                $limitstart,
                $row,
                $ctag->GetAtt("col"),
                $ctag->GetAtt("titlelen"),
                $ctag->GetAtt("listtype"),
                $ctag->GetAtt("orderby"),
                $InnerText,
                $ctag->GetAtt("tablewidth"),
                $ismake,
                $ctag->GetAtt("orderway")
                )
                );
            }
            else if($ctag->GetName()=="pagelist")
            {
                $list_len = trim($ctag->GetAtt("listsize"));
                $ctag->GetAtt("listitem")=="" ? $listitem="index,pre,pageno,next,end,option" : $listitem=$ctag->GetAtt("listitem");
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
            else if($PageNo!=1 && $ctag->GetName()=='field' && $ctag->GetAtt('display')!='')
            {
                $this->dtp->Assign($tagid,'');
            }
        }
    }

    /**
     *  获得要创建的文件名称规则
     *
     * @access    public
     * @param     string  $typeid  栏目ID
     * @param     string  $wname
     * @param     string  $typedir  栏目目录
     * @param     string  $defaultname  默认名称
     * @param     string  $namerule2  名称规则
     * @return    string
     */
    function GetMakeFileRule($typeid,$wname,$typedir,$defaultname,$namerule2)
    {
        $typedir = MfTypedir($typedir);
        if($wname=='index')
        {
            return $typedir.'/'.$defaultname;
        }
        else
        {
            $namerule2 = str_replace('{tid}',$typeid,$namerule2);
            $namerule2 = str_replace('{typedir}',$typedir,$namerule2);
            return $namerule2;
        }
    }

    /**
     *  获得一个单列的文档列表
     *
     * @access    public
     * @param     int  $limitstart  限制开始  
     * @param     int  $row  行数 
     * @param     int  $col  列数
     * @param     int  $titlelen  标题长度
     * @param     int  $infolen  描述长度
     * @param     int  $imgwidth  图片宽度
     * @param     int  $imgheight  图片高度
     * @param     string  $listtype  列表类型
     * @param     string  $orderby  排列顺序
     * @param     string  $innertext  底层模板
     * @param     string  $tablewidth  表格宽度
     * @param     string  $ismake  是否编译
     * @param     string  $orderWay  排序方式
     * @return    string
     */
    function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$listtype="all",$orderby="default",$innertext="",$tablewidth="100",$ismake=1,$orderWay='desc')
    {
        global $cfg_list_son;
        $typeid=$this->TypeID;

        if($row=='') $row = 10;

        if($limitstart=='') $limitstart = 0;

        if($titlelen=='') $titlelen = 100;

        if($listtype=='') $listtype = "all";

        if($orderby=='') $orderby='id';
        else $orderby=strtolower($orderby);

        if($orderWay=='') $orderWay = 'desc';

        $tablewidth = str_replace("%", "", $tablewidth);
        if($tablewidth=='') $tablewidth=100;
        if($col=='') $col=1;
        $colWidth = ceil(100 / $col);
        $tablewidth = $tablewidth."%";
        $colWidth = $colWidth."%";

        $innertext = trim($innertext);
        if($innertext=='') $innertext = GetSysTemplets('list_sglist.htm');

        //排序方式
        $ordersql = '';
        if($orderby=='senddate'||$orderby=='id')
        {
            $ordersql=" ORDER BY arc.aid $orderWay";
        }
        else if($orderby=='hot'||$orderby=='click')
        {
            $ordersql = " ORDER BY arc.click $orderWay";
        }
        else
        {
            $ordersql=" ORDER BY arc.aid $orderWay";
        }

        $addField = 'arc.'.join(',arc.',$this->ListFields);

        //如果不用默认的sortrank或id排序，使用联合查询（数据量大时非常缓慢）
        if(preg_match('/hot|click/', $orderby) || $this->sAddTable)
        {
            $query = "SELECT tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,
            tp.ispart,tp.moresite,tp.siteurl,tp.sitepath,arc.aid,arc.aid AS id,arc.typeid,
            $addField
            FROM `{$this->AddTable}` arc
            LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
            WHERE {$this->addSql} $ordersql LIMIT $limitstart,$row";
        }
        //普通情况先从arctiny表查出ID，然后按ID查询（速度非常快）
        else
        {
            $t1 = ExecTime();
            $ids = array();
            $nordersql = str_replace('.aid','.id',$ordersql);
            $query = "SELECT id From `#@__arctiny` arc WHERE {$this->addSql} $nordersql LIMIT $limitstart,$row ";

            $this->dsql->SetQuery($query);
            $this->dsql->Execute();
            while($arr=$this->dsql->GetArray())
            {
                $ids[] = $arr['id'];
            }
            $idstr = join(',',$ids);
            if($idstr=='')
            {
                return '';
            }
            else
            {
                $query = "SELECT tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,
                tp.ispart,tp.moresite,tp.siteurl,tp.sitepath,arc.aid,arc.aid AS id,arc.typeid,
                       $addField
                       FROM `{$this->AddTable}` arc LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
                       WHERE arc.aid IN($idstr) AND arc.arcrank >-1 $ordersql ";
            }
            $t2 = ExecTime();
            //echo $t2-$t1;
        }

        $this->dsql->SetQuery($query);
        $this->dsql->Execute('al');
        $t2 = ExecTime();

        //echo $t2-$t1;
        $artlist = '';
        $this->dtp2->LoadSource($innertext);
        $GLOBALS['autoindex'] = 0;
        for($i=0;$i<$row;$i++)
        {
            if($col>1)
            {
                $artlist .= "<div>\r\n";
            }
            for($j=0;$j<$col;$j++)
            {
                if($row = $this->dsql->GetArray("al"))
                {
                    $GLOBALS['autoindex']++;
                    $ids[$row['aid']] = $row['id']= $row['aid'];

                    //处理一些特殊字段
                    $row['ismake'] = 1;
                    $row['money'] = 0;
                    $row['arcrank'] = 0;
                    $row['filename'] = '';
                    $row['filename'] = $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
                                                       $row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);

                    $row['typeurl'] = GetTypeUrl($row['typeid'],MfTypedir($row['typedir']),$row['isdefault'],$row['defaultname'],
                                       $row['ispart'],$row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);
                    if($row['litpic'] == '-' || $row['litpic'] == '')
                    {
                        $row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
                    }
                    if(!preg_match("/^http:\/\//", $row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
                    {
                        $row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
                    }
                    $row['picname'] = $row['litpic'];

                    $row['pubdate'] = $row['senddate'];

                    $row['stime'] = GetDateMK($row['pubdate']);

                    $row['typelink'] = "<a href='".$row['typeurl']."'>".$row['typename']."</a>";

                    $row['fulltitle'] = $row['title'];

                    $row['title'] = cn_substr($row['title'],$titlelen);

                    if(preg_match('/b/', $row['flag']))
                    {
                        $row['title'] = "<b>".$row['title']."</b>";
                    }

                    $row['textlink'] = "<a href='".$row['filename']."'>".$row['title']."</a>";

                    $row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];

                    $row['memberurl'] = $GLOBALS['cfg_memberurl'];

                    $row['templeturl'] = $GLOBALS['cfg_templeturl'];

                    //编译附加表里的数据
                    foreach($row as $k=>$v) $row[strtolower($k)] = $v;

                    foreach($this->ChannelUnit->ChannelFields as $k=>$arr)
                    {
                         if(isset($row[$k]))
                         {
                              $row[$k] = $this->ChannelUnit->MakeField($k,$row[$k]);
                         }
                    }

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

            }//Loop Col

            if($col>1)
            {
                $i += $col - 1;
                $artlist .= "    </div>\r\n";
            }
        }//Loop Line

        $t3 = ExecTime();

        //echo ($t3-$t2);
        $this->dsql->FreeResult('al');
        return $artlist;
    }

    /**
     *  获取静态的分页列表
     *
     * @access    public
     * @param     int  $list_len  列表宽度
     * @param     string  $listitem  列表样式
     * @return    string
     */
    function GetPageListST($list_len,$listitem="index,end,pre,next,pageno")
    {
        $prepage="";
        $nextpage="";
        $prepagenum = $this->PageNo-1;
        $nextpagenum = $this->PageNo+1;
        if($list_len=="" || preg_match("/[^0-9]/", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult / $this->PageSize);
        if($totalpage <= 1 && $this->TotalResult > 0)
        {
            return "<span class=\"pageinfo\">共 <strong>1</strong>页<strong>".$this->TotalResult."</strong>条记录</span>";
        }
        if($this->TotalResult == 0)
        {
            return "<span class=\"pageinfo\">共 <strong>0</strong>页<strong>".$this->TotalResult."</strong>条记录</span>";
        }
        $purl = $this->GetCurUrl();
        $maininfo = "<span class=\"pageinfo\">共 <strong>{$totalpage}</strong>页<strong>".$this->TotalResult."</strong>条</span>";
        $tnamerule = $this->GetMakeFileRule($this->Fields['id'], "list", $this->Fields['typedir'], $this->Fields['defaultname'], $this->Fields['namerule2']);
        $tnamerule = preg_replace("/^(.*)\//", '', $tnamerule);

        //获得上一页和主页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<li><a href='".str_replace("{page}", $prepagenum, $tnamerule)."'>上一页</a></li>\r\n";
            $indexpage="<li><a href='".str_replace("{page}", 1, $tnamerule)."'>首页</a></li>\r\n";
        }
        else
        {
            $indexpage="<li>首页</li>\r\n";
        }

        //下一页,未页的链接
        if($this->PageNo != $totalpage && $totalpage>1)
        {
            $nextpage.="<li><a href='".str_replace("{page}", $nextpagenum, $tnamerule)."'>下一页</a></li>\r\n";
            $endpage="<li><a href='".str_replace("{page}", $totalpage, $tnamerule)."'>末页</a></li>\r\n";
        }
        else
        {
            $endpage="<li>末页</li>";
        }

        //option链接
        $optionlist = "";
        /*
        $optionlen = strlen($totalpage);
        $optionlen = $optionlen*10+18;
        $optionlist = "<li><select name='sldd' style='width:{$optionlen}px' onchange='location.href=this.options[this.selectedIndex].value;'>\r\n";
        for($mjj=1;$mjj<=$totalpage;$mjj++)
        {
            if($mjj==$this->PageNo)
            {
                $optionlist .= "<option value='".str_replace("{page}",$mjj,$tnamerule)."' selected>$mjj</option>\r\n";
            }
            else
            {
                $optionlist .= "<option value='".str_replace("{page}",$mjj,$tnamerule)."'>$mjj</option>\r\n";
            }
        }
        $optionlist .= "</select><li>";
        */

        //获得数字链接
        $listdd = "";
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
        for($j; $j <= $total_list; $j++)
        {
            if($j == $this->PageNo)
            {
                $listdd.= "<li class=\"thisclass\">$j</li>\r\n";
            }
            else
            {
                $listdd.="<li><a href='".str_replace("{page}", $j, $tnamerule)."'>".$j."</a></li>\r\n";
            }
        }
        $plist = "";
        if(preg_match('/info/i', $listitem))
        {
            $plist .= $maininfo.' ';
        }
        if(preg_match('/index/i', $listitem))
        {
            $plist .= $indexpage.' ';
        }
        if(preg_match('/pre/i', $listitem))
        {
            $plist .= $prepage.' ';
        }
        if(preg_match('/pageno/i', $listitem))
        {
            $plist .= $listdd.' ';
        }
        if(preg_match('/next/i', $listitem))
        {
            $plist .= $nextpage.' ';
        }
        if(preg_match('/end/i', $listitem))
        {
            $plist .= $endpage.' ';
        }
        if(preg_match('/option/i', $listitem))
        {
            $plist .= $optionlist;
        }
        return $plist;
    }

    /**
     *  获取动态的分页列表
     *
     * @access    public
     * @param     int  $list_len  列表宽度
     * @param     string  $listitem  列表样式
     * @return    string
     */
    function GetPageListDM($list_len,$listitem="index,end,pre,next,pageno")
    {
        global $nativeplace,$infotype,$keyword;
        if(empty($nativeplace)) $nativeplace = 0;
        if(empty($infotype)) $infotype = 0;
        if(empty($keyword)) $keyword = '';
        $prepage = $nextpage = '';
        $prepagenum = $this->PageNo - 1;
        $nextpagenum = $this->PageNo + 1;
        if($list_len=="" || preg_match("/[^0-9]/", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult / $this->PageSize);
        if($totalpage<=1 && $this->TotalResult>0)
        {
            return "<span class=\"pageinfo\">共1页/".$this->TotalResult."条记录</span>";
        }
        if($this->TotalResult == 0)
        {
            return "<span class=\"pageinfo\">共0页/".$this->TotalResult."条记录</span>";
        }
        $purl = $this->GetCurUrl();
        $geturl = "tid=".$this->TypeID."&TotalResult=".$this->TotalResult."&nativeplace=$nativeplace&infotype=$infotype&keyword=".urlencode($keyword)."&";
        $hidenform = "<input type='hidden' name='tid' value='".$this->TypeID."' />\r\n";
        $hidenform = "<input type='hidden' name='nativeplace' value='$nativeplace' />\r\n";
        $hidenform = "<input type='hidden' name='infotype' value='$infotype' />\r\n";
        $hidenform = "<input type='hidden' name='keyword' value='$keyword' />\r\n";
        $hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."' />\r\n";
        $purl .= "?".$geturl;

        //获得上一页和下一页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<li><a href='".$purl."PageNo=$prepagenum'>上一页</a></li>\r\n";
            $indexpage="<li><a href='".$purl."PageNo=1'>首页</a></li>\r\n";
        }
        else
        {
            $indexpage="<li><a>首页</a></li>\r\n";
        }
        if($this->PageNo!=$totalpage && $totalpage>1)
        {
            $nextpage.="<li><a href='".$purl."PageNo=$nextpagenum'>下一页</a></li>\r\n";
            $endpage="<li><a href='".$purl."PageNo=$totalpage'>末页</a></li>\r\n";
        }
        else
        {
            $endpage="<li><a>末页</a></li>";
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
        for($j; $j <= $total_list; $j++)
        {
            if($j == $this->PageNo)
            {
                $listdd.= "<li class=\"thisclass\"><a>$j</a></li>\r\n";
            }
            else
            {
                $listdd.="<li><a href='".$purl."PageNo=$j'>".$j."</a></li>\r\n";
            }
        }

        $plist = $indexpage.$prepage.$listdd.$nextpage.$endpage;
        return $plist;
    }

    /**
     *  获得当前的页面文件的url
     *
     * @access    private
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