<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 自定义宏标记调用标签
 *
 * @version        $Id: mytag.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>自定义宏标记</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>用于获取自定义宏标记的内容</description>
<demo>
{dede:mytag typeid='0' name=''/}
</demo>
<attributes>
    <iterm>name:标记名称，该项是必须的属性，以下 2、3是可选属性</iterm> 
    <iterm>ismake:默认是 no 表示设定的纯HTML代码， yes 表示含板块标记的代码</iterm>
    <iterm>typeid:表示所属栏目的ID，默认为 0 ，表示所有栏目通用的显示内容，在列表和文档模板中，typeid默认是这个列表或文档本身的栏目ＩＤ</iterm>
</attributes> 
>>dede>>*/
 
function lib_mytag(&$ctag, &$refObj)
{
    $attlist = "typeid|0,name|,ismake|no";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    if(trim($ismake)=='') $ismake = 'no';
    $body = lib_GetMyTagT($refObj, $typeid, $name, '#@__mytag');
    //编译
    if($ismake=='yes')
    {
        require_once(DEDEINC.'/arc.partview.class.php');
        $pvCopy = new PartView($typeid);
        $pvCopy->SetTemplet($body,"string");
        $body = $pvCopy->GetResult();
    }
    return $body;
}

function lib_GetMyTagT(&$refObj, $typeid,$tagname,$tablename)
{
    global $dsql;
    if($tagname=='') return '';
    if(trim($typeid)=='') $typeid=0;
    if( !empty($refObj->Fields['typeid']) && $typeid==0) $typeid = $refObj->Fields['typeid'];
    
    $typesql = $row = '';
    if($typeid > 0) $typesql = " And typeid IN(0,".GetTopids($typeid).") ";
    
    $row = $dsql->GetOne(" SELECT * FROM $tablename WHERE tagname LIKE '$tagname' $typesql ORDER BY typeid DESC ");
    if(!is_array($row)) return '';

    $nowtime = time();
    if($row['timeset']==1 
      && ($nowtime<$row['starttime'] || $nowtime>$row['endtime']) )
    {
        $body = $row['expbody'];
    }
    else
    {
        $body = $row['normbody'];
    }
    
    return $body;
}