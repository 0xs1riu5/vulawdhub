<?php
/**
 * 单表模型的文章列表调用标记
 *
 * @version        $Id: arclistsg.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

 /**
 *  arclistsg解析标签
 *
 * @access    public
 * @param     object  $ctag  解析标签
 * @param     object  $refObj  引用对象
 * @return    string  成功后返回解析后的标签内容
 */
 
/*>>dede>>
<name>独立单表模型列表</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>单表独立模型的文档列表调用标记</description>
<demo>
{dede:arclistsg  flag='h' typeid='' row='' col='' titlelen='' orderway='' keyword='' limit='0,1'} 
<a href='[field:arcurl/]'>[field:title/]</a> 
{/dede:arclistsg}
</demo>
<attributes>
    <iterm>row:返回文档列表总数</iterm> 
    <iterm>typeid:栏目ID,在列表模板和档案模板中一般不需要指定，在封面模板中允许用","分开表示多个栏目</iterm>
    <iterm>titlelen:标题长度 等同于titlelength</iterm>
    <iterm>orderwey:排序方向</iterm>
    <iterm>keyword:含有指定关键字的文档列表，多个关键字用","分</iterm>
    <iterm>innertext:单条记录样式(innertext是放在标签之间的代码)</iterm>
    <iterm>arcid:指定文档ID</iterm>
    <iterm>idlist:提取特定文档（文档ID）</iterm>
    <iterm>channelid: 频道ID</iterm>
    <iterm>limit:表示限定的记录范围</iterm>
    <iterm>flag:自定义属性值：头条[h]推荐[c]图片[p]幻灯[f]滚动[s]跳转[j]图文[a]加粗[b]</iterm>
    <iterm>subday:表示在多少天以内的文档</iterm>
</attributes> 
>>dede>>*/

function lib_arclistsg(&$ctag,&$refObj)
{
    global $dsql,$PubFields,$cfg_keyword_like,$cfg_index_cache,$_arclistEnv,$envs,$_sys_globals;

    //属性处理
    $attlist="typeid|0,row|10,col|1,flag|,titlelen|30,sort|default,keyword|,innertext|,arcid|0,idlist|,channelid|0,limit|,orderway|desc,subday|0";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    $line = $row;
    $orderby=strtolower($sort);
    if($col=='') $col = 1;
	if(empty($imgwidth)) $imgwidth = "";
	if(empty($imgheight)) $imgheight = "";
    $innertext = trim($ctag->GetInnerText());
    if($innertext=='') $innertext = GetSysTemplets("part_arclistsg.htm");

    if(empty($channelid) && isset($GLOBALS['envs']['channelid'])) {
        $channelid = $GLOBALS['envs']['channelid'];
    }
    
    if(empty($typeid) && !empty($envs['typeid'])) {
      $typeid = $envs['typeid'];
    }
    
    if(empty($typeid) && empty($channelid))
    {
        return "No channel info!";
    }

    if(!empty($channelid)) $gquery = "SELECT addtable,listfields FROM `#@__channeltype` WHERE id='$channelid' ";
    else $gquery = "SELECT ch.addtable,listfields FROM `#@__arctype` tp LEFT JOIN `#@__channeltype` ch ON ch.id=tp.channeltype WHERE id='$typeid'";

  $row = $dsql->GetOne($gquery);

    $orwheres = array();
    $maintable = trim($row['addtable']);

    if($maintable=='')
    {
        return "No addtable info!";
    }

    //列表调用字段
    $listarcs = array('aid', 'typeid');
    if(!empty($row['listfields']))
    {
         $listfields = explode(',', $row['listfields']);
         foreach($listfields as $v)
         {
              if(!in_array($v, $listarcs)) $listarcs[] = $v;
         }
    }
    $arclistquery = join(',', $listarcs);
    $arclistquery .= ",arc.aid AS id,arc.senddate AS pubdate";

    //按不同情况设定SQL条件 排序方式
    if($idlist=='')
    {
        if($orderby=='near' && $cfg_keyword_like=='N'){ $keyword=''; }
        //时间限制(用于调用最近热门文章、热门评论之类)
        if($subday>0)
        {
            //这里的时间只能计算到天，否则缓存功能将无效
            $ntime = gmmktime(0, 0, 0, gmdate('m'), gmdate('d'), gmdate('Y'));
            $limitday = $ntime - ($subday * 24 * 3600);
            $orwheres[] = " arc.senddate > $limitday ";
        }
        
        if($flag!='')
        {
            $flags = explode(',',$flag);
            for($i=0;isset($flags[$i]);$i++) $orwheres[] = " FIND_IN_SET('{$flags[$i]}',flag)>0 ";
        }

        if(!empty($typeid))
        {
            //指定了多个栏目时，不再获取子类的id
            if(preg_match('#,#',$typeid)) $orwheres[] = " typeid IN ($typeid) ";
            else
            {
                //处理交叉栏目
                $CrossID = '';
                if((isset($envs['cross']) || $ctag->GetAtt('cross')=='1' ) && $ctag->GetAtt('nocross')!='1')
                {
                    $arr = $dsql->GetOne("SELECT `id`,`topid`,`cross`,`crossid`,`ispart`,`typename` FROM `#@__arctype` WHERE id='$typeid' ");
                    if($arr['cross']==0 || ($arr['cross']==2 && trim($arr['crossid']=='')))
                    $orwheres[] = ' typeid IN ('.GetSonIds($typeid).')';
                    else
                    {
                        $selquery = '';
                        if($arr['cross']==1) {
                            $selquery = "SELECT id,topid FROM `#@__arctype` WHERE typename like '{$arr['typename']}' AND id<>'{$typeid}' AND topid<>'{$typeid}'  ";
                        }
                        else {
                            $arr['crossid'] = preg_replace('#[^0-9,]#', '', trim($arr['crossid']));
                            if($arr['crossid']!='') $selquery = "SELECT id,topid FROM `#@__arctype` WHERE id IN('{$arr['crossid']}') AND id<>'{$typeid}' AND topid<>'{$typeid}'  ";
                        }

                        if($selquery!='')
                        {
                            $dsql->SetQuery($selquery);
                            $dsql->Execute();
                            while($arr = $dsql->GetArray()) {
                                $CrossID .= ($CrossID=='' ? $arr['id'] : ','.$arr['id']);
                            }
                        }
                    }
                }
                if($CrossID=='') $orwheres[] = ' typeid IN ('.GetSonIds($typeid).')';
                else $orwheres[] = ' typeid IN ('.GetSonIds($typeid).','.$CrossID.')';
            }
        }
        //频道ID

        if(!empty($channelid)) $orwheres[] = " AND arc.channel = '$channelid' ";

        //由于这个条件会导致缓存功能失去意义，因此取消
        //if($arcid!=0) $orwheres[] = " arc.id<>'$arcid' ";
    }
    //文档排序的方式
    $ordersql = '';
    if($orderby=='hot'||$orderby=='click') $ordersql = " ORDER BY arc.click $orderway";
    else if($orderby=='id') $ordersql = "  ORDER BY arc.aid $orderway";
    else if($orderby=='near') $ordersql = " ORDER BY ABS(arc.id - ".$arcid.")";
    else if($orderby=='rand') $ordersql = "  ORDER BY rand()";
    else $ordersql=" ORDER BY arc.aid $orderway";
    //limit条件
    $limit = trim(preg_replace('#limit#i', '', $limit));
    if($limit!='') $limitsql = " LIMIT $limit ";
    else $limitsql = " LIMIT 0,$line ";

    $orwhere = '';
    if(isset($orwheres[0])) {
        $orwhere = join(' AND ',$orwheres);
        $orwhere = preg_replace("#^ AND#i", '', $orwhere);
        $orwhere = preg_replace("#AND[ ]{1,}AND#i", 'AND ', $orwhere);
    }
    if($orwhere!='') $orwhere = " WHERE $orwhere ";

    $query = "SELECT $arclistquery,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,
        tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
        FROM `$maintable` arc LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
        $orwhere AND arc.arcrank > -1 $ordersql $limitsql";

    $md5hash = md5($query);
    $needcache = TRUE;
    if($idlist!='') $needcache = FALSE;
    else{
        $idlist = GetArclistSgCache($md5hash);
        if($idlist!='') $needcache = FALSE;
    }
    //指定了id或使用缓存中的id
    if($idlist!='' && $_arclistEnv != 'index')
    {
        $query = "SELECT $arclistquery,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,
            tp.moresite,tp.siteurl,tp.sitepath FROM `$maintable` arc LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
          WHERE arc.aid IN($idlist) $ordersql $limitsql";
    }
    $dsql->SetQuery($query);
    $dsql->Execute("al");
    $artlist = "";
    $dtp2 = new DedeTagParse();
    $dtp2->SetNameSpace("field","[","]");
    $dtp2->LoadString($innertext);
    $GLOBALS['autoindex'] = 0;
    $ids = array();
    for($i=0;$i<$line;$i++)
    {
        for($j=0;$j<$col;$j++)
        {
            if($col>1) $artlist .= "    <div>\r\n";
            if($row = $dsql->GetArray("al"))
            {
                $ids[] = $row['aid'];

                $row['filename'] = $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],1,
                0,$row['namerule'],$row['typedir'],0,'',$row['moresite'],$row['siteurl'],$row['sitepath']);

                $row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],$row['ispart'],
                $row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);

                if($row['litpic'] == '-' || $row['litpic'] == '')
                {
                    $row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
                }
                if(!preg_match("#^http:\/\/#i", $row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
                {
                    $row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
                }
                $row['picname'] = $row['litpic'];
                
                $row['image'] = "<img src='".$row['picname']."' border='0' width='{$imgwidth}' height='{$imgheight}' alt='".preg_replace("#['><]#", "", $row['title'])."' />";

                $row['imglink'] = "<a href='".$row['filename']."'>".$row['image']."</a>";

                $row['stime'] = GetDateMK($row['pubdate']);
                $row['typelink'] = "<a href='".$row['typeurl']."'>".$row['typename']."</a>";
                $row['fulltitle'] = $row['title'];
                $row['title'] = cn_substr($row['title'],$titlelen);
                $row['textlink'] = "<a href='".$row['filename']."'>".$row['title']."</a>";
                $row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
                $row['memberurl'] = $GLOBALS['cfg_memberurl'];
                $row['templeturl'] = $GLOBALS['cfg_templeturl'];

                if(is_array($dtp2->CTags))
                {
                    foreach($dtp2->CTags as $k=>$ctag)
                    {
                        if($ctag->GetName()=='array')
                        {
                            //传递整个数组，在runphp模式中有特殊作用
                            $dtp2->Assign($k,$row);
                        }
                        else
                        {
                            if(isset($row[$ctag->GetName()])) $dtp2->Assign($k,$row[$ctag->GetName()]);
                            else $dtp2->Assign($k,'');
                        }
                    }
                    $GLOBALS['autoindex']++;
                }

                $artlist .= $dtp2->GetResult()."\r\n";
            }//if hasRow
            else{
                $artlist .= '';
            }
            if($col>1) $artlist .= "    </div>\r\n";
        }//Loop Col
        if($col>1) $i += $col - 1;
    }//loop line
    $dsql->FreeResult("al");
    //保存ID缓存
    $idsstr = join(',',$ids);
    if($idsstr!='' && $needcache && $cfg_index_cache>0)
    {
        $mintime = time() - ($cfg_index_cache * 3600);
        $inquery = "INSERT INTO `#@__arccache`(`md5hash`,`uptime`,`cachedata`) VALUES ('".$md5hash."', '".time()."', '$idsstr'); ";
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arccache` WHERE md5hash='".$md5hash."' or uptime < $mintime ");
        $dsql->ExecuteNoneQuery($inquery);
    }
    return $artlist;
}

//查询缓存
function GetArclistSgCache($md5hash)
{
    global $dsql,$envs,$cfg_makesign_cache,$cfg_index_cache;
    //没启用缓存
    if($cfg_index_cache<=0) return '';
    //少量更新禁用缓存
    if(isset($envs['makesign']) && $cfg_makesign_cache=='N') return '';
    //正常情况
    $mintime = time() - ($cfg_index_cache * 3600);
    $arr = $dsql->GetOne("SELECT cachedata,uptime FROM `#@__arccache` WHERE md5hash = '$md5hash' AND uptime > $mintime ");
    //没数据
    if(!is_array($arr)) return '';
    //返回缓存id数据
    else return $arr['cachedata'];
}

function lib_GetAutoChannelID2($sortid,$topid)
{
    global $dsql;
    if(empty($sortid)) $sortid = 1;
    $getstart = $sortid - 1;
    $row = $dsql->GetOne("SELECT id,typename From #@__arctype WHERE reid='{$topid}' AND ispart<2 AND ishidden<>'1' ORDER BY sortrank asc limit $getstart,1");
    if(!is_array($row)) return 0;
    else return $row['id'];
}