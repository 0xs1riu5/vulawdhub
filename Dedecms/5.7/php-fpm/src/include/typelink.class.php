<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 栏目连接
 *
 * @version        $Id: typelink.class.php 1 15:21 2010年7月5日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEINC."/channelunit.func.php");

/**
 * 栏目连接类
 *
 * @package          TypeLink
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class TypeLink
{
    var $typeDir;
    var $dsql;
    var $TypeID;
    var $baseDir;
    var $modDir;
    var $indexUrl;
    var $indexName;
    var $TypeInfos;
    var $SplitSymbol;
    var $valuePosition;
    var $valuePositionName;
    var $OptionArrayList;

    //构造函数///////
    //php5构造函数
    function __construct($typeid)
    {
        $this->indexUrl = $GLOBALS['cfg_basehost'].$GLOBALS['cfg_indexurl'];
        $this->indexName = $GLOBALS['cfg_indexname'];
        $this->baseDir = $GLOBALS['cfg_basedir'];
        $this->modDir = $GLOBALS['cfg_templets_dir'];
        $this->SplitSymbol = $GLOBALS['cfg_list_symbol'];
        $this->dsql = $GLOBALS['dsql'];
        $this->TypeID = $typeid;
        $this->valuePosition = '';
        $this->valuePositionName = '';
        $this->typeDir = '';
        $this->OptionArrayList = '';

        //载入类目信息
        $query = "SELECT tp.*,ch.typename as ctypename,ch.addtable,ch.issystem FROM `#@__arctype` tp left join `#@__channeltype` ch
        on ch.id=tp.channeltype  WHERE tp.id='$typeid' ";
        if($typeid > 0)
        {
            $this->TypeInfos = $this->dsql->GetOne($query);
            if(is_array($this->TypeInfos))
            {
                $this->TypeInfos['tempindex'] = MfTemplet($this->TypeInfos['tempindex']);
                $this->TypeInfos['templist'] = MfTemplet($this->TypeInfos['templist']);
                $this->TypeInfos['temparticle'] = MfTemplet($this->TypeInfos['temparticle']);
            }
        }
    }

    //对于使用默认构造函数的情况
    //GetPositionLink()将不可用
    function TypeLink($typeid)
    {
        $this->__construct($typeid);
    }

    //关闭数据库连接，析放资源
    function Close()
    {
    }

    //重设类目ID
    function SetTypeID($typeid)
    {
        $this->TypeID = $typeid;
        $this->valuePosition = "";
        $this->valuePositionName = "";
        $this->typeDir = "";
        $this->OptionArrayList = "";

        //载入类目信息
        $query = "
        SELECT #@__arctype.*,#@__channeltype.typename as ctypename
        FROM #@__arctype left join #@__channeltype
        on #@__channeltype.id=#@__arctype.channeltype WHERE #@__arctype.id='$typeid' ";
        $this->dsql->SetQuery($query);
        $this->TypeInfos = $this->dsql->GetOne();
    }

    //获得这个类目的路径
    function GetTypeDir()
    {
        if(empty($this->TypeInfos['typedir']))
        {
            return $GLOBALS['cfg_cmspath'].$GLOBALS['cfg_arcdir'];
        }
        else
        {
            return $this->TypeInfos['typedir'];
        }
    }

    //获得某类目的链接列表 如：类目一>>类目二>> 这样的形式
    //islink 表示返回的列表是否带连接
    function GetPositionLink($islink=true)
    {
        $indexpage = "<a href='".$this->indexUrl."'>".$this->indexName."</a>";
        if($this->valuePosition!="" && $islink)
        {
            return $this->valuePosition;
        }
        else if($this->valuePositionName!="" && !$islink)
        {
            return $this->valuePositionName;
        }
        else if($this->TypeID==0)
        {
            if($islink)
            {
                return $indexpage;
            }
            else
            {
                return "没指定分类！";
            }
        }
        else
        {
            if($islink)
            {
                $this->valuePosition = $this->GetOneTypeLink($this->TypeInfos);
                if($this->TypeInfos['reid']!=0)
                {
                    //调用递归逻辑
                    $this->LogicGetPosition($this->TypeInfos['reid'],true);
                }
                $this->valuePosition = $indexpage.$this->SplitSymbol.$this->valuePosition;
                return $this->valuePosition.$this->SplitSymbol;
            }
            else
            {
                $this->valuePositionName = $this->TypeInfos['typename'];
                if($this->TypeInfos['reid']!=0)
                {
                    //调用递归逻辑
                    $this->LogicGetPosition($this->TypeInfos['reid'],false);
                }
                return $this->valuePositionName;
            }
        }
    }

    //获得名字列表
    function GetPositionName()
    {
        return $this->GetPositionLink(false);
    }

    //获得某类目的链接列表，递归逻辑部分
    function LogicGetPosition($id,$islink)
    {
        $this->dsql->SetQuery("SELECT id,reid,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath FROM #@__arctype WHERE id='".$id."'");
        $tinfos = $this->dsql->GetOne();
        if($islink)
        {
            $this->valuePosition = $this->GetOneTypeLink($tinfos).$this->SplitSymbol.$this->valuePosition;
        }
        else
        {
            $this->valuePositionName = $tinfos['typename'].$this->SplitSymbol.$this->valuePositionName;
        }
        if($tinfos['reid']>0)
        {
            $this->LogicGetPosition($tinfos['reid'],$islink);
        }
        else
        {
            return 0;
        }

    }

    //获得某个类目的超链接信息
    function GetOneTypeLink($typeinfos)
    {
        $typepage = $this->GetOneTypeUrl($typeinfos);
        $typelink = "<a href='".$typepage."'>".$typeinfos['typename']."</a>";
        return $typelink;
    }

    //获得某分类连接的URL
    function GetOneTypeUrl($typeinfos)
    {
        return GetTypeUrl($typeinfos['id'],MfTypedir($typeinfos['typedir']),$typeinfos['isdefault'],$typeinfos['defaultname'],
        $typeinfos['ispart'],$typeinfos['namerule2'],$typeinfos['moresite'],$typeinfos['siteurl'],$typeinfos['sitepath']);
    }

    //获得类别列表
    //hid 是指默认选中类目，0 表示“请选择类目”或“不限类目”
    //oper 是用户允许管理的类目，0 表示所有类目
    //channeltype 是指类目的内容类型，0 表示不限频道
    function GetOptionArray($hid=0,$oper=0,$channeltype=0,$usersg=0)
    {
        return $this->GetOptionList($hid,$oper,$channeltype,$usersg);
    }

    function GetOptionList($hid=0,$oper=0,$channeltype=0,$usersg=0)
    {
        global $cfg_admin_channel;
        if(empty($cfg_admin_channel)) $cfg_admin_channel = 'all';
        
        if(!$this->dsql) $this->dsql = $GLOBALS['dsql'];
        $this->OptionArrayList = '';
        
        if($hid>0)
        {
            $row = $this->dsql->GetOne("SELECT id,typename,ispart,channeltype FROM #@__arctype WHERE id='$hid'");
            $channeltype = $row['channeltype'];
            if($row['ispart']==1) {
                $this->OptionArrayList .= "<option value='".$row['id']."' style='background-color:#DFDFDB;color:#888888' selected>".$row['typename']."</option>\r\n";
            }
            else {
                $this->OptionArrayList .= "<option value='".$row['id']."' selected>".$row['typename']."</option>\r\n";
            }
        }
        
        if($channeltype==0) $ctsql = '';
        else $ctsql=" AND channeltype='$channeltype' ";
        
        
        if(is_array($oper) && $cfg_admin_channel != 'all')
        {
            if( count($oper) == 0 )
            {
                $query = "SELECT id,typename,ispart FROM `#@__arctype` WHERE 1=2 ";
            }
            else
            {
                $admin_catalog_tmp = $admin_catalog = join(',', $oper);
                $this->dsql->SetQuery("SELECT reid FROM `#@__arctype` WHERE id in($admin_catalog) GROUP BY reid ");
                $this->dsql->Execute();
                $topidstr = '';
                while($row = $this->dsql->GetObject())
                {
                    if($row->reid==0) continue;
                    $topidstr .= ($topidstr=='' ? $row->reid : ','.$row->reid);
                }
                $admin_catalog .= ','.$topidstr;
                $admin_catalogs = explode(',', $admin_catalog);
                $admin_catalogs = array_unique($admin_catalogs);
                $admin_catalog = join(',', $admin_catalogs);
                $admin_catalog = preg_replace("/,$/", '', $admin_catalog);
                $query = "SELECT id,typename,ispart FROM `#@__arctype` WHERE ispart<>2 AND id in({$admin_catalog}) AND reid=0 $ctsql";
            }
        }
        else
        {
            $query = "SELECT id,typename,ispart FROM `#@__arctype` WHERE ispart<>2 AND reid=0 $ctsql ORDER BY sortrank ASC";
        }

        $this->dsql->SetQuery($query);
        $this->dsql->Execute();
        while($row=$this->dsql->GetObject())
        {
            if($row->id!=$hid)
            {
                if($row->ispart==1) {
                    $this->OptionArrayList .= "<option value='".$row->id."' style='background-color:#EFEFEF;color:#666666'>".$row->typename."</option>\r\n";
                }
                else {
                    $this->OptionArrayList .= "<option value='".$row->id."'>".$row->typename."</option>\r\n";
                }
            }
            $this->LogicGetOptionArray($row->id, "─", $oper);
        }
        return $this->OptionArrayList;
    }

    /**
     *  逻辑递归
     *
     * @access    public
     * @param     int   $id   栏目ID
     * @param     int   $step   步进标志
     * @param     int   $oper   操作权限
     * @return    string
     */
    function LogicGetOptionArray($id, $step, $oper=0)
    {
        global $cfg_admin_channel;
        if(empty($cfg_admin_channel)) $cfg_admin_channel = 'all';
        
        $this->dsql->SetQuery("SELECT id,typename,ispart FROM #@__arctype WHERE reid='".$id."' AND ispart<>2 ORDER BY sortrank ASC");
        $this->dsql->Execute($id);
        while($row=$this->dsql->GetObject($id))
        {
            if(is_array($oper) && $cfg_admin_channel != 'all')
            {
                if(!in_array($row->id, $oper)) continue;
            }
            if($row->ispart==1) {
                $this->OptionArrayList .= "<option value='".$row->id."' style='background-color:#EFEFEF;color:#666666'>$step".$row->typename."</option>\r\n";
            }
            else {
                $this->OptionArrayList .= "<option value='".$row->id."'>$step".$row->typename."</option>\r\n";
            }
            $this->LogicGetOptionArray($row->id, $step."─", $oper);
        }
    }

    /**
     *  获得与该类相关的类目，本函数应用于模板标记{dede:channel}{/dede:channel}中
     *  $typetype 的值为： sun 下级分类 self 同级分类 top 顶级分类
     *
     * @access    public
     * @param     int   $typeid   栏目ID
     * @param     int   $reid   所属ID
     * @param     int   $row   栏目行数
     * @param     string   $typetype   栏目类型
     * @param     string   $innertext   底层模板
     * @param     int   $col   显示列数
     * @param     int   $tablewidth   表格宽度
     * @param     int   $myinnertext   自定义底层模板
     * @return    string
     */
    function GetChannelList($typeid=0, $reid=0, $row=8, $typetype='sun', $innertext='',
    $col=1, $tablewidth=100, $myinnertext='')
    {
        if($typeid==0) $typeid = $this->TypeID;
        if($row=="") $row = 8;
        if($reid=="") $reid = 0;
        if($col=="") $col = 1;

        $tablewidth = str_replace("%","",$tablewidth);
        if($tablewidth=="") $tablewidth=100;
        if($col=="") $col = 1;

        $colWidth = ceil(100/$col);
        $tablewidth = $tablewidth."%";
        $colWidth = $colWidth."%";
        if($typetype=="") $typetype="sun";

        if($innertext=="") $innertext = GetSysTemplets("channel_list.htm");

        if($reid==0 && $typeid>0)
        {
            $dbrow = $this->dsql->GetOne("SELECT reid FROM #@__arctype WHERE id='$typeid' ");
            if(is_array($dbrow))
            {
                $reid = $dbrow['reid'];
            }
        }
        $likeType = "";
        if($typetype=="top")
        {
            $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl
          FROM #@__arctype WHERE reid=0 AND ishidden<>1 ORDER BY sortrank ASC limit 0,$row";
        }
        else if($typetype=="sun"||$typetype=="son")
        {
            $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl
          FROM #@__arctype WHERE reid='$typeid' AND ishidden<>1 ORDER BY sortrank ASC limit 0,$row";
        }
        else if($typetype=="self")
        {
            $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl
            FROM #@__arctype WHERE reid='$reid' AND ishidden<>1 ORDER BY sortrank ASC limit 0,$row";
        }

        //AND ID<>'$typeid'
        $dtp2 = new DedeTagParse();
        $dtp2->SetNameSpace("field","[","]");
        $dtp2->LoadSource($innertext);
        $this->dsql->SetQuery($sql);
        $this->dsql->Execute();
        $line = $row;
        $GLOBALS['autoindex'] = 0;
        if($col>1)
        {
            $likeType = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
        }
        for($i=0;$i<$line;$i++)
        {
            if($col>1)
            {
                $likeType .= "<tr>\r\n";
            }
            for($j=0;$j<$col;$j++)
            {
                if($col>1) $likeType .= "    <td width='$colWidth'>\r\n";
                if($row=$this->dsql->GetArray())
                {
                    //处理当前栏目的样式
                    if($row['id']=="$typeid" && $myinnertext != '')
                    {
                        $linkOkstr = $myinnertext;
                        $row['typelink'] = $this->GetOneTypeUrl($row);
                        $linkOkstr = str_replace("~typelink~", $row['typelink'], $linkOkstr);
                        $linkOkstr = str_replace("~typename~", $row['typename'], $linkOkstr);
                        $likeType .= $linkOkstr;
                    }
                    else
                    {
                        //非当前栏目
                        $row['typelink'] = $this->GetOneTypeUrl($row);
                        if(is_array($dtp2->CTags))
                        {
                            foreach($dtp2->CTags as $tagid=>$ctag)
                            {
                                if(isset($row[$ctag->GetName()]))
                                {
                                    $dtp2->Assign($tagid, $row[$ctag->GetName()]);
                                }
                            }
                        }
                        $likeType .= $dtp2->GetResult();
                    }
                }
                if($col>1)
                {
                    $likeType .= "    </td>\r\n";
                }
                $GLOBALS['autoindex']++;
            }//Loop Col

            if($col>1)
            {
                $i += $col - 1;
            }
            if($col>1)
            {
                $likeType .= "    </tr>\r\n";
            }
        }//Loop for $i

        if($col>1)
        {
            $likeType .= "    </table>\r\n";
        }
        $this->dsql->FreeResult();
        return $likeType;
    }//GetChannel

}//End Class