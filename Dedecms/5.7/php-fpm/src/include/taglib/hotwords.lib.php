<?php
/**
 * 获取网站搜索的热门关键字
 *
 * @version        $Id: hotwords.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>热门关键词</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>获取网站搜索的热门关键字</description>
<demo>
{dede:hotwords /}
</demo>
<attributes>
    <iterm>num:关键词数目</iterm> 
    <iterm>subday:天数</iterm>
    <iterm>maxlength:关键词最大长度</iterm>
</attributes> 
>>dede>>*/
 
function lib_hotwords(&$ctag,&$refObj)
{
    global $cfg_phpurl,$dsql;

    $attlist="num|6,subday|365,maxlength|16";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    $nowtime = time();
    if(empty($subday)) $subday = 365;
    if(empty($num)) $num = 6;
    if(empty($maxlength)) $maxlength = 20;
    $maxlength = $maxlength+1;
    $mintime = $nowtime - ($subday * 24 * 3600);
	// 2011-6-28 根据论坛反馈(http://bbs.dedecms.com/371416.html)，修正SQL大小写问题(by:织梦的鱼)
    $dsql->SetQuery("SELECT keyword FROM `#@__search_keywords` WHERE lasttime>$mintime AND length(keyword)<$maxlength ORDER BY count DESC LIMIT 0,$num");
    $dsql->Execute('hw');
    $hotword = '';
    while($row=$dsql->GetArray('hw')){
        $hotword .= "　<a href='".$cfg_phpurl."/search.php?keyword=".urlencode($row['keyword'])."'>".$row['keyword']."</a> ";
    }
    return $hotword;
}