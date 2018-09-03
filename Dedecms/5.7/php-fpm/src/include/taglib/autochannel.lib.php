<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 按排序位置的获取单个栏目的链接信息
 *
 * @version        $Id: autochannel.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
 /*>>dede>>
<name>指定栏目</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>指定排序位置的单个栏目的链接</description>
<demo>
{dede:autochannel partsort='' typeid=’’}{/dede:autochannel}
</demo>
<attributes>
    <iterm>partsort:栏目所在的排序位置</iterm> 
    <iterm>typeid:获取单个栏目的顶级栏目</iterm>
</attributes> 
>>dede>>*/
 
function lib_autochannel(&$ctag,&$refObj)
{
    global $dsql;

    $attlist='partsort|0,typeid=-1';
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    $innertext = trim($ctag->GetInnerText());
    $topid = $typeid;
    $sortid = $partsort;

    if($topid=='-1' || $topid=='')
    {        
        $topid = ( isset($refObj->TypeLink->TypeInfos['id']) ? $refObj->TypeLink->TypeInfos['id'] : 0);
    }
    
    if(empty($sortid)) $sortid = 1;
    $getstart = $sortid - 1;

    $row = $dsql->GetOne("SELECT id,typename FROM `#@__arctype` WHERE reid='{$topid}' AND 
                          ispart<2 AND ishidden<>'1' ORDER BY sortrank asc limit $getstart,1");
                          
    if(!is_array($row) ) return '';
    else $typeid = $row['id'];
    
    if(trim($innertext)=='') $innertext = GetSysTemplets('part_autochannel.htm');
    
    $row = $dsql->GetOne("SELECT id,typedir,isdefault,defaultname,ispart,namerule2,typename,moresite,siteurl,sitepath 
                          FROM `#@__arctype` WHERE id='$typeid' ");
    if(!is_array($row)) return '';

    $dtp = new DedeTagParse();
    $dtp->SetNameSpace('field','[',']');
    $dtp->LoadSource($innertext);
    if(!is_array($dtp->CTags))
    {
        unset($dtp);
        return '';
    }
    else
    {
        $row['typelink'] = GetTypeUrl($row['id'],MfTypedir($row['typedir']),$row['isdefault'],
                           $row['defaultname'],$row['ispart'],$row['namerule2'],$row['siteurl'],$row['sitepath']);
        foreach($dtp->CTags as $tagid=>$ctag)
        {
            if(isset($row[$ctag->GetName()])) $dtp->Assign($tagid,$row[$ctag->GetName()]);
        }
        $revalue = $dtp->GetResult();
        unset($dtp);
        return $revalue;
    }
}