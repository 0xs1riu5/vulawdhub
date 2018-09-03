<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 分类信息地区与类型快捷链接
 *
 * @version        $Id: infolink.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>分类信息地区与类型快捷链接</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>调用分类信息地区与类型快捷链接</description>
<demo>
{dede:infolink /}
</demo>
<attributes>
</attributes> 
>>dede>>*/
 
require_once(DEDEINC.'/enums.func.php');
require_once(DEDEDATA.'/enums/nativeplace.php');
require_once(DEDEDATA.'/enums/infotype.php');

function lib_infolink(&$ctag,&$refObj)
{
    global $dsql,$nativeplace,$infotype,$hasSetEnumJs,$cfg_cmspath,$cfg_mainsite;
    global $em_nativeplaces,$em_infotypes;
    
    //属性处理
    //$attlist="row|12,titlelen|24";
    //FillAttsDefault($ctag->CAttribute->Items,$attlist);
    //extract($ctag->CAttribute->Items, EXTR_SKIP);
    
    $cmspath = ( (empty($cfg_cmspath) || !preg_match("#\/$#", $cfg_cmspath)) ? $cfg_cmspath.'/' : $cfg_cmspath );
    $baseurl = preg_replace("#\/$#", '', $cfg_mainsite).$cmspath;
    
    $smalltypes = '';
    if( !empty($refObj->TypeLink->TypeInfos['smalltypes']) ) {
        $smalltypes = explode(',', $refObj->TypeLink->TypeInfos['smalltypes']);
    }
    
    if(empty($refObj->Fields['typeid'])) {
        $row = $dsql->GetOne("SELECT id FROM `#@__arctype` WHERE channeltype='-8' And reid = '0' ");
        $typeid = (is_array($row) ? $row['id'] : 0);
    }
    else {
        $typeid = $refObj->Fields['typeid'];
    }
    
    $innerText = trim($ctag->GetInnerText());
    if(empty($innerText)) $innerText = GetSysTemplets("info_link.htm");
    $ctp = new DedeTagParse();
    $ctp->SetNameSpace('field','[',']');
    $ctp->LoadSource($innerText);

    $revalue = $seli = '';
    $channelid = ( empty($refObj->TypeLink->TypeInfos['channeltype']) ? -8 : $refObj->TypeLink->TypeInfos['channeltype'] );
    
    $fields = array('nativeplace'=>'','infotype'=>'','typeid'=>$typeid,
                    'channelid'=>$channelid,'linkallplace'=>'','linkalltype'=>'');
    
    $fields['nativeplace'] = $fields['infotype'] = '';
    
    $fields['linkallplace'] = "<a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&infotype={$infotype}'>不限</a>";
    $fields['linkalltype'] = "<a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&nativeplace={$nativeplace}'>不限</a>";
    
    //地区链接
    if(empty($nativeplace))
    {
        foreach($em_nativeplaces as $eid=>$em)
        {
            if($eid % 500 != 0) continue;
            $fields['nativeplace'] .= " <a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&nativeplace={$eid}&infotype={$infotype}'>{$em}</a>\r\n";
        }
    }
    else
    {
        $sontype = ( ($nativeplace % 500 != 0) ? $nativeplace : 0 );
        $toptype = ( ($nativeplace % 500 == 0) ? $nativeplace : ( $nativeplace-($nativeplace%500) ) );
		//2011-6-21 修改地区列表的一个小空格 论坛http://bbs.dedecms.com/371492.html(by：织梦的鱼)
        $fields['nativeplace'] = "<a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&nativeplace={$toptype}&infotype={$infotype}'> <b>{$em_nativeplaces[$toptype]}</b></a> &gt;&gt; ";
        foreach($em_nativeplaces as $eid=>$em)
        {
            if($eid < $toptype+1 || $eid > $toptype+499) continue;
            if($eid == $nativeplace) {
                $fields['nativeplace'] .= " <b>{$em}</b>\r\n";
            }
            else {
                $fields['nativeplace'] .= " <a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&nativeplace={$eid}&infotype={$infotype}'>{$em}</a>\r\n";
          }
      }
    }
    //小分类链接
    if(empty($infotype) || is_array($smalltypes))
    {
        
        foreach($em_infotypes as $eid=>$em)
        {
            if(!is_array($smalltypes) && $eid % 500 != 0) continue;
            if(is_array($smalltypes) && !in_array($eid, $smalltypes)) continue;
            if($eid == $infotype) 
            {
                $fields['infotype'] .= " <b>{$em}</b>\r\n";
            }
            else {
                $fields['infotype'] .= " <a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&infotype={$eid}&nativeplace={$nativeplace}'>{$em}</a>\r\n";
            }
        }
    }
    else
    {
        $sontype = ( ($infotype % 500 != 0) ? $infotype : 0 );
        $toptype = ( ($infotype % 500 == 0) ? $infotype : ( $infotype-($infotype%500) ) );
        $fields['infotype'] .= "<a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&infotype={$toptype}&nativeplace={$nativeplace}'><b>{$em_infotypes[$toptype]}</b></a> &gt;&gt; ";
        foreach($em_infotypes as $eid=>$em)
        {
            if($eid < $toptype+1 || $eid > $toptype+499) continue;
            if($eid == $infotype) {
                $fields['infotype'] .= " <b>{$em}</b>\r\n";
            }
            else {
                $fields['infotype'] .= " <a href='{$baseurl}plus/list.php?channelid={$channelid}&tid={$typeid}&infotype={$eid}&nativeplace={$nativeplace}'>{$em}</a>\r\n";
          }
      }
    }
    
    
    if(is_array($ctp->CTags))
    {
        foreach($ctp->CTags as $tagid=>$ctag)
        {
            if(isset($fields[$ctag->GetName()])) {
                $ctp->Assign($tagid,$fields[$ctag->GetName()]);
            }
        }
        $revalue .= $ctp->GetResult();
    }
    
    return $revalue;
}