<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 获得责任编辑名称
 *
 * @version        $Id: adminname.lib.php 2 8:48 2010年7月8日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

 /**
 *  获得责任编辑名称
 *
 * @access    public
 * @param     object  $ctag  解析标签
 * @param     object  $refObj  引用对象
 * @return    string  成功后返回解析后的标签内容
 */
 
 /*>>dede>>
<name>责任编辑</name> 
<type>仅内容模板</type> 
<for>V55,V56,V57</for>
<description>获得责任编辑名称</description>
<demo>
{dede:adminname /}	
</demo>
<attributes>
</attributes> 
>>dede>>*/

function lib_adminname(&$ctag, &$refObj)
{
    global $dsql;
    if(empty($refObj->Fields['dutyadmin']))
    {
        $dutyadmin = $GLOBALS['cfg_df_dutyadmin'];
    }
    else
    {
        $row = $dsql->GetOne("SELECT uname FROM `#@__admin` WHERE id='{$refObj->Fields['dutyadmin']}' ");
        $dutyadmin = isset($row['uname']) ? $row['uname'] : $GLOBALS['cfg_df_dutyadmin'];
    }
    return $dutyadmin;
}