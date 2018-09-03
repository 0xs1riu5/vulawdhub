<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 会员信息调用标签
 *
 * @version        $Id: memberlist.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>会员信息列表</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>会员信息调用标签</description>
<demo>
{dede:memberlist orderby='scores' row='20'}
<a href="../member/index.php?uid={dede:field.userid /}">{dede:field.userid /}</a>
<span>{dede:field.scores /}</span>
{/dede:memberlist}
</demo>
<attributes>
    <iterm>row:调用数目</iterm> 
    <iterm>iscommend:是否为推荐会员</iterm>
    <iterm>orderby:按登陆时间排序 money 按金钱排序 scores 按积分排序</iterm>
</attributes> 
>>dede>>*/
 
//orderby = logintime(login new) or mid(register new)
function lib_memberlist(&$ctag, &$refObj)
{
    global $dsql,$sqlCt;
    $attlist="row|6,iscommend|0,orderby|logintime,signlen|50";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    $revalue = '';
    $innerText = trim($ctag->GetInnerText());
    if(empty($innerText)) $innerText = GetSysTemplets('memberlist.htm');

    $wheresql = ' WHERE mb.spacesta>-1 AND mb.matt<10 ';

    if($iscommend > 0) $wheresql .= " AND  mb.matt='$iscommend' ";

    $sql = "SELECT mb.*,ms.spacename,ms.sign FROM `#@__member` mb
        LEFT JOIN `#@__member_space` ms ON ms.mid = mb.mid
        $wheresql order by mb.{$orderby} DESC LIMIT 0,$row ";
    
    $ctp = new DedeTagParse();
    $ctp->SetNameSpace('field','[',']');
    $ctp->LoadSource($innerText);

    $dsql->Execute('mb',$sql);
    while($row = $dsql->GetArray('mb'))
    {
        $row['spaceurl'] = $GLOBALS['cfg_basehost'].'/member/index.php?uid='.$row['userid'];
        if(empty($row['face'])){
            $row['face']=($row['sex']=='女')? $GLOBALS['cfg_memberurl'].'/templets/images/dfgirl.png' : $GLOBALS['cfg_memberurl'].'/templets/images/dfboy.png';
        }
        foreach($ctp->CTags as $tagid=>$ctag){
            if(isset($row[$ctag->GetName()])){ $ctp->Assign($tagid,$row[$ctag->GetName()]); }
        }
        $revalue .= $ctp->GetResult();
    }
    
    return $revalue;
}