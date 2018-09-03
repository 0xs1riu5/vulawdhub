<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 文档关连的用户信息
 *
 * @version        $Id: memberinfos.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>用户信息</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>文档关连的用户信息</description>
<demo>
{dede:memberinfos mid = '' /}
</demo>
<attributes>
    <iterm>mid:用户ID</iterm> 
</attributes> 
>>dede>>*/
 
function lib_memberinfos(&$ctag,&$refObj)
{
    global $dsql,$sqlCt;
    $attlist="mid|0";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    
    if(empty($mid))
    {
        if(!empty($refObj->Fields['mid'])) $mid =  $refObj->Fields['mid'];
        else $mid = 1;
    }
    else
    {
            $mid = intval($mid);
    }

    $revalue = '';
    $innerText = trim($ctag->GetInnerText());
    if(empty($innerText)) $innerText = GetSysTemplets('memberinfos.htm');

    $sql = "SELECT mb.*,ms.spacename,ms.sign,ar.membername as rankname FROM `#@__member` mb
        LEFT JOIN `#@__member_space` ms ON ms.mid = mb.mid 
        LEFT JOIN `#@__arcrank` ar ON ar.rank = mb.rank
        WHERE mb.mid='{$mid}' LIMIT 0,1 ";

    $ctp = new DedeTagParse();
    $ctp->SetNameSpace('field','[',']');
    $ctp->LoadSource($innerText);

    $dsql->Execute('mb',$sql);
    while($row = $dsql->GetArray('mb'))
    {
        if($row['matt']==10) return '';
        $row['spaceurl'] = $GLOBALS['cfg_basehost'].'/member/index.php?uid='.$row['userid'];
        if(empty($row['face'])) {
            $row['face']=($row['sex']=='女')?  $GLOBALS['cfg_memberurl'].'/templets/images/dfgirl.png' : $GLOBALS['cfg_memberurl'].'/templets/images/dfboy.png';
        }
        foreach($ctp->CTags as $tagid=>$ctag)
        {
            if(isset($row[$ctag->GetName()])){ $ctp->Assign($tagid,$row[$ctag->GetName()]); }
        }
        $revalue .= $ctp->GetResult();
    }
    return $revalue;
}