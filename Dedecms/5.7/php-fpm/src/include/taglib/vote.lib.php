<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 投票标签
 *
 * @version        $Id: vote.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>投票标签</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>用于获取一组投票表单</description>
<demo>
{dede:vote id='' lineheight='22' tablewidth='100%' titlebgcolor='#EDEDE2' titlebackground='' tablebgcolor='#FFFFFF'/}
{/dede}
</demo>
<attributes>
    <iterm>id:数字，当前投票ID</iterm>
    <iterm>lineheight:表格高度</iterm>
    <iterm>tablewidth:表格宽度</iterm>
    <iterm>titlebgcolor:投票标题背景色</iterm>
    <iterm>titlebackground:标题背景图</iterm>
    <iterm>tablebg:投票表格背景色</iterm>
</attributes>
>>dede>>*/
 
require_once(DEDEINC.'/dedevote.class.php');
function lib_vote(&$ctag,&$refObj)
{
    global $dsql;
    $attlist="id|0,lineheight|24,tablewidth|100%,titlebgcolor|#EDEDE2,titlebackgroup|,tablebg|#FFFFFF";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    if(empty($id)) $id=0;
    if($id==0)
    {
        $row = $dsql->GetOne("SELECT aid FROM `#@__vote` ORDER BY aid DESC LIMIT 0,1");
        if(!isset($row['aid'])) return '';
        else $id=$row['aid'];
    }
    $vt = new DedeVote($id);
    return $vt->GetVoteForm($lineheight,$tablewidth,$titlebgcolor,$titlebackgroup,$tablebg);
}