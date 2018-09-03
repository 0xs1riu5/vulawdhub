<?php   if(!defined('DEDEINC')) exit('Request Error!');

/**
 * 动态模板spacenewart标签
 *
 * @version        $Id: plus_spacenewart.php 1 13:58 2010年7月5日Z tianya $
 * @package        DedeCMS.Tpllib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
function plus_spacenewart(&$atts,&$refObj,&$fields)
{
    global $dsql,$_vars;

    $attlist = "channel=1,titlelen=30,infolen=200,row=8,imgwidth=120,imgheight=90";
    FillAtts($atts,$attlist);
    FillFields($atts,$fields,$refObj);
    extract($atts, EXTR_OVERWRITE);

    $query = "Select arc.*,mt.mtypename,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,
        tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
        from `#@__archives` arc
        left join `#@__arctype` tp on arc.typeid=tp.id
        left join `#@__mtypes` mt on mt.mtypeid=arc.mtype
        where arc.mid='{$_vars['mid']}' and arc.channel=$channel
        order by id desc limit 0,$row";

    $dsql->SetQuery($query);
    $dsql->Execute("al");
    $artlist = '';
    $rearr = array();
    while($row = $dsql->GetArray("al"))
    {
        //处理一些特殊字段
        $row['infos'] = cn_substr($row['description'],$infolen);
        $row['id'] =  $row['id'];

        $row['arcurl'] = GetFileUrl($row['id'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],
        $row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);

        $row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],$row['ispart'],
        $row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);

        if($row['litpic']=='')
        {
            $row['litpic'] = '/images/defaultpic.gif';
        }
        if(!preg_match("#^http:\/\/#i", $row['litpic']))
        {
            $row['picname'] = $row['litpic'] = $GLOBALS['cfg_cmsurl'].$row['litpic'];
        }
        else
        {
            $row['picname'] = $row['litpic'] = $row['litpic'];
        }
        $row['stime'] = GetDateMK($row['pubdate']);
        $row['typelink'] = "<a href='".$row['typeurl']."'>".$row['typename']."</a>";
        $row['image'] = "<img src='".$row['picname']."' border='0' width='$imgwidth' height='$imgheight' alt='".preg_replace("#['><]#", "", $row['title'])."'>";
        $row['imglink'] = "<a href='".$row['filename']."'>".$row['image']."</a>";
        $row['fulltitle'] = $row['title'];
        $row['title'] = cn_substr($row['title'],$titlelen);
        if($row['color']!='') {
            $row['title'] = "<font color='".$row['color']."'>".$row['title']."</font>";
        }
        if(preg_match('#b#', $row['flag']))
        {
            $row['title'] = "<strong>".$row['title']."</strong>";
        }
        //$row['title'] = "<b>".$row['title']."</b>";

        $row['textlink'] = "<a href='".$row['filename']."'>".$row['title']."</a>";

        $row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
        $row['memberurl'] = $GLOBALS['cfg_memberurl'];
        $row['templeturl'] = $GLOBALS['cfg_templeturl'];

        $rearr[] = $row;
    }//loop line
    $dsql->FreeResult("al");
    return $rearr;
}