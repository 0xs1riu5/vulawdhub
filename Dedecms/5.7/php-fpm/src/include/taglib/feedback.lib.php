<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 调用最新评论
 *
 * @version        $Id: feedback.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>会员评论内容</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>用于调用最新评论</description>
<demo>
{dede:feedback}
 <ul>
  <li class='fbtitle'>[field:username function="(@me=='guest' ? '游客' : @me)"/] 对 [field:title/] 的评论：</li>
  <li class='fbmsg'> <a href="plus/feedback.php?aid=[field:aid/]" class='fbmsg'>[field:msg /]</a></li>
 </ul>
{/dede:feedback}
</demo>
<attributes>
    <iterm>row:调用评论条数</iterm> 
    <iterm>titlelen:标题长度</iterm>
    <iterm>infolen:评论长度</iterm>
</attributes> 
>>dede>>*/
 
function lib_feedback(&$ctag,&$refObj)
{
    global $dsql;
    $attlist="row|12,titlelen|24,infolen|100";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    $innertext = trim($ctag->GetInnerText());
    $totalrow = $row;
    $revalue = '';
    if(empty($innertext))
    {
        $innertext = GetSysTemplets('tag_feedback.htm');
    }
    $wsql = " where ischeck=1 ";
    $equery = "SELECT * FROM `#@__feedback` $wsql ORDER BY id DESC LIMIT 0 , $totalrow";
    $ctp = new DedeTagParse();
    $ctp->SetNameSpace('field','[',']');
    $ctp->LoadSource($innertext);

    $dsql->Execute('fb',$equery);
    while($arr=$dsql->GetArray('fb'))
    {
        $arr['title'] = cn_substr($arr['arctitle'],$titlelen);
        $arr['msg'] = jsTrim(Html2Text($arr['msg']),$infolen);
        foreach($ctp->CTags as $tagid=>$ctag)
        {
            if(!empty($arr[$ctag->GetName()]))
            {
                $ctp->Assign($tagid,$arr[$ctag->GetName()]);
            }
        }
        $revalue .= $ctp->GetResult();
    }
    return $revalue;
}

function jsTrim($str,$len)
{
    $str = preg_replace("/{quote}(.*){\/quote}/is",'',$str);
    $str = str_replace('&lt;br/&gt;',' ',$str);
    $str = cn_substr($str,$len);
    $str = preg_replace("#['\"\r\n]#", "", $str);
    return $str;
}