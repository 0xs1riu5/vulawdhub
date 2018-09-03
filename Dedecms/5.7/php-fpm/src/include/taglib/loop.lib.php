<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 调用任意表的数据标签
 *
 * @version        $Id: loop.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>万能循环</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>调用任意表的数据标签</description>
<demo>
{dede:loop table='dede_archives' sort='' row='4' if=''}
<a href='[field:arcurl/]'>[field:title/]</a>
{/dede:loop}
</demo>
<attributes>
    <iterm>table:查询表名</iterm> 
    <iterm>sort:用于排序的字段</iterm>
    <iterm>row:返回结果的条数</iterm>
    <iterm>if:查询的条件</iterm>
</attributes> 
>>dede>>*/
 
require_once(DEDEINC.'/dedevote.class.php');
function lib_loop(&$ctag,&$refObj)
{
    global $dsql;
    $attlist="table|,tablename|,row|8,sort|,if|,ifcase|";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    $innertext = trim($ctag->GetInnertext());
    $revalue = '';
    if(!empty($table)) $tablename = $table;

    if($tablename==''||$innertext=='') return '';
    if($if!='') $ifcase = $if;

    if($sort!='') $sort = " ORDER BY $sort DESC ";
    if($ifcase!='') $ifcase=" WHERE $ifcase ";
    $dsql->SetQuery("SELECT * FROM $tablename $ifcase $sort LIMIT 0,$row");
    $dsql->Execute();
    $ctp = new DedeTagParse();
    $ctp->SetNameSpace("field","[","]");
    $ctp->LoadSource($innertext);
    $GLOBALS['autoindex'] = 0;
    while($row = $dsql->GetArray())
    {
        $GLOBALS['autoindex']++;
        foreach($ctp->CTags as $tagid=>$ctag)
        {
                if($ctag->GetName()=='array')
                {
                        $ctp->Assign($tagid, $row);
                }
                else
                {
                    if( !empty($row[$ctag->GetName()])) $ctp->Assign($tagid,$row[$ctag->GetName()]); 
                }
        }
        $revalue .= $ctp->GetResult();
    }
    return $revalue;
}