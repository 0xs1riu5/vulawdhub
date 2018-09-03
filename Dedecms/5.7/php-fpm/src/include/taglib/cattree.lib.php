<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 
 *
 * @version        $Id: cattree.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
 /*>>dede>>
<name>树形类目标签</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>调用树形类目</description>
<demo>
{dede:cattree typeid='' catid='' showall=''/}
</demo>
<attributes>
    <iterm>typeid:顶级树id</iterm> 
    <iterm>catid:上级栏目id</iterm>
    <iterm>showall:在空或不存在时，强制用产品模型id；如果是 yes 刚显示整个语言区栏目树；为其它数字则是这个数字的模型的id</iterm>
</attributes> 
>>dede>>*/
 
function lib_cattree(&$ctag, &$refObj)
{
    global $dsql;
    //属性处理
    //属性 showall 在空或不存在时，强制用产品模型id；如果是 yes 刚显示整个语言区栏目树；为其它数字则是这个数字的模型的id
    //typeid 指定顶级树 id ，指定后，前一个属性将无效
    $attlist="showall|,catid|0";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    $revalue = '';

    if(empty($typeid))
    {
        if( isset($refObj->TypeLink->TypeInfos['id']) ) {
            $typeid = $refObj->TypeLink->TypeInfos['id'];
            $reid = $refObj->TypeLink->TypeInfos['reid'];
            $topid = $refObj->TypeLink->TypeInfos['topid'];
            $channeltype = $refObj->TypeLink->TypeInfos['channeltype'];
            $ispart = $refObj->TypeLink->TypeInfos['ispart'];
            if($reid==0) $topid = $typeid;
        } else {
          $typeid = $reid = $topid = $channeltype = $ispart = 0;
        }
    }
    else
    {
        $row = $dsql->GetOne("SELECT reid,topid,channeltype,ispart FROM `#@__arctype` WHERE id='$typeid' ");
        if(!is_array($row))
        {
            $typeid = $reid = $topid = $channeltype = $ispart = 0;
        } else {
            $reid = $row['reid'];
            $topid = $row['topid'];
            $channeltype = $row['channeltype'];
            $ispart = $row['ispart'];
        }
    }
    if( !empty($catid) )
    {
        $topQuery = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath FROM `#@__arctype` WHERE reid='$catid' And ishidden<>1 ";
    }
    else
    {
        if($showall == "yes" )
        {
            $topQuery = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath FROM `#@__arctype` WHERE reid='$topid' ";
        }
        else
        {
           if($showall=='')
           {
                   if( $ispart < 2 && !empty($channeltype) ) $showall = $channeltype;
                   else $showall = 6;
           }
           $topQuery = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath FROM `#@__arctype` WHERE reid='{$topid}' And channeltype='{$showall}' And ispart<2 And ishidden<>1 ";
        }
    }
  $dsql->Execute('t', $topQuery);
  while($row = $dsql->GetArray('t'))
  {
      $row['typelink'] = GetOneTypeUrlA($row);
    $revalue .= "<dl class='cattree'>\n";
    $revalue .= "<dt><a href='{$row['typelink']}'>{$row['typename']}</a></dt>\n";
    cattreeListSon($row['id'], $revalue);
    $revalue .= "</dl>\n";
  }
    return $revalue;
}

function cattreeListSon($id, &$revalue)
{
    global $dsql;
    $query = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath FROM `#@__arctype` WHERE reid='{$id}' And ishidden<>1 ";
    $dsql->Execute($id, $query);
    $thisv = '';
  while($row = $dsql->GetArray($id))
  {
      $row['typelink'] = GetOneTypeUrlA($row);
    $thisv .= "    <dl class='cattree'>\n";
    $thisv .= "    <dt><a href='{$row['typelink']}'>{$row['typename']}</a></dt>\n";
    cattreeListSon($row['id'], $thisv);
    $thisv .= "    </dl>\n";
  }
  if($thisv!='') $revalue .= "    <dd>\n$thisv    </dd>\n";
}