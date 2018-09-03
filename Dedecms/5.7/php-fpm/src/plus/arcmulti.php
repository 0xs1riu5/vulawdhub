<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC.'/channelunit.class.php');
require_once(DEDEINC.'/taglib/arcpagelist.lib.php');

$mtype = empty($mtype)? 0 : intval(preg_replace("/[^\d]/",'', $mtype));
$pnum = empty($pnum)? 0 : intval(preg_replace("/[^\d]/",'', $pnum));
$tagid = empty($tagid)? '' : (preg_replace("/[^a-z0-9]/",'', $tagid));

if($tagid=='' || $pnum==0) die(" Request Error! ");

if($tagid !='')
{
    $row = $dsql->GetOne("SELECT * FROM #@__arcmulti WHERE tagid='$tagid'");
    $ids = explode(',', $row['arcids']);
    $totalnum = $line = count($ids);
    //取出属性并解析为变量
    $attarray = unserialize($row['attstr']);
    extract($attarray, EXTR_SKIP);

    $artlist = '';
    //通过页面及总数解析当前页面数据范围
    $strnum = ($pnum-1) * $row['pagesize'];
    $limitsql = " LIMIT $strnum,{$row['pagesize']} ";
    
    if($mtype == 0)
    {
      //处理列表内容项
        $query = "SELECT arc.*,tp.typedir,tp.typename,tp.corank,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,
            tp.moresite,tp.siteurl,tp.sitepath
            {$row['addfieldsSql']}
             FROM `#@__archives` arc LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
             {$row['addfieldsSqlJoin']}
          WHERE arc.id IN({$row['arcids']}) {$row['ordersql']} $limitsql";
        $dsql->SetQuery($query);
        $dsql->Execute('al');
        $dtp2 = new DedeTagParse();
        $dtp2->SetNameSpace('field', '[', ']');
        $dtp2->LoadString($row['innertext']);
        $GLOBALS['autoindex'] = 0;
        $ids = array();
    
        for($i=0; $i<$line; $i++)
        {
            if($col>1) $artlist .= "<tr>\r\n";
            for($j=0; $j<$col; $j++)
            {
                if($col>1) $artlist .= "    <td width='$colWidth'>\r\n";
                if($row = $dsql->GetArray("al"))
                {
                    $ids[] = $row['id'];
                    //处理一些特殊字段
                    $row['info'] = $row['infos'] = cn_substr($row['description'],$infolen);
                    $row['id'] =  $row['id'];

                    if($row['corank'] > 0 && $row['arcrank']==0)
                    {
                        $row['arcrank'] = $row['corank'];
                    }

                    $row['filename'] = $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
                    $row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);

                    $row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],$row['ispart'],
                    $row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);

                    if($row['litpic'] == '-' || $row['litpic'] == '')
                    {
                        $row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
                    }
                    if(!preg_match("#^http:\/\/#", $row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
                    {
                        $row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
                    }
                    $row['picname'] = $row['litpic'];
                    $row['stime'] = GetDateMK($row['pubdate']);
                    $row['typelink'] = "<a href='".$row['typeurl']."'>".$row['typename']."</a>";
                    $row['image'] = "<img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".preg_replace("#['><]#", "", $row['title'])."'>";
                    $row['imglink'] = "<a href='".$row['filename']."'>".$row['image']."</a>";
                    $row['fulltitle'] = $row['title'];
                    $row['title'] = cn_substr($row['title'],$titlelen);
                    if($row['color']!='') $row['title'] = "<font color='".$row['color']."'>".$row['title']."</font>";
                    if(preg_match('#b#', $row['flag'])) $row['title'] = "<strong>".$row['title']."</strong>";
                    //$row['title'] = "<b>".$row['title']."</b>";

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
                            } else {
                                if(isset($row[$ctag->GetName()])) $dtp2->Assign($k,$row[$ctag->GetName()]);
                                else $dtp2->Assign($k,'');
                           }
                        }
                        $GLOBALS['autoindex']++;
                    }
                    $artlist .= $dtp2->GetResult()."\r\n";
                }//if hasRow
                else {
                    $artlist .= '';
                }
                if($col>1) $artlist .= "    </td>\r\n";
            }//Loop Col
            if($col>1) $i += $col - 1;
            if($col>1) $artlist .= "    </tr>\r\n";
        }//loop line
        if($col>1) $artlist .= "    </table>\r\n";
        $dsql->FreeResult("al");    
    } else 
    {
        //处理分页字段
        $artlist .= '<div id="page_'.$tagid.'">';
        $artlist .= multipage($totalnum, $pnum, $row['pagesize'], $tagid);
        $artlist .= '</div>';
    }
}

AjaxHead();
echo $artlist;
exit();