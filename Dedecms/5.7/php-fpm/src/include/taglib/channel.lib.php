<?php
/**
 * 获取栏目列表标签
 *
 * @version        $Id: channel.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/*>>dede>>
<name>频道标签</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>用于获取栏目列表</description>
<demo>
{dede:channel type='top' row='8' currentstyle="<li><a href='~typelink~' class='thisclass'>~typename~</a> </li>"} 
 <li><a href='[field:typelink/]'>[field:typename/]</a> </li> 
{/dede:channel}
</demo>
<attributes>
    <iterm>typeid:栏目ID</iterm> 
    <iterm>reid:上级栏目ID</iterm>
    <iterm>row:调用栏目数</iterm>
    <iterm>col:分多少列显示（默认为单列）</iterm>
    <iterm>type:son表示下级栏目,self表示同级栏目,top顶级栏目</iterm>
    <iterm>currentstyle:应用样式</iterm>
</attributes> 
>>dede>>*/
 
function lib_channel(&$ctag,&$refObj)
{
    global $dsql;

    $attlist = "typeid|0,reid|0,row|100,col|1,type|son,currentstyle|,cacheid|";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);
    $innertext = $ctag->GetInnerText();
    $line = empty($row) ? 100 : $row;
    
    $likeType = '';
    //读取固定的缓存块
    $cacheid = trim($cacheid);
    if($cacheid !='') {
        $likeType = GetCacheBlock($cacheid);
        if($likeType != '') return $likeType;
    }

    $reid = 0;
    $topid = 0;
    //如果属性里没指定栏目id，从引用类里获取栏目信息
    if(empty($typeid))
    {
        if( isset($refObj->TypeLink->TypeInfos['id']) )
        {
            $typeid = $refObj->TypeLink->TypeInfos['id'];
            $reid = $refObj->TypeLink->TypeInfos['reid'];
            $topid = $refObj->TypeLink->TypeInfos['topid'];
        }
        else {
          $typeid = 0;
      }
    }
    //如果指定了栏目id，从数据库获取栏目信息
    else
    {
        $row2 = $dsql->GetOne("SELECT * FROM `#@__arctype` WHERE id='$typeid' ");
        $typeid = $row2['id'];
        $reid = $row2['reid'];
        $topid = $row2['topid'];
        $issetInfos = true;
    }
    
    if($type=='' || $type=='sun') $type='son';
    if($innertext=='') $innertext = GetSysTemplets("channel_list.htm");

    if($type=='top')
    {
        $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
          From `#@__arctype` WHERE reid=0 And ishidden<>1 order by sortrank asc limit 0, $line ";
    }
    else if($type=='son')
    {
        if($typeid==0) return '';
        $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
          From `#@__arctype` WHERE reid='$typeid' And ishidden<>1 order by sortrank asc limit 0, $line ";
    }
    else if($type=='self')
    {
        if($reid==0) return '';
        $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
            FROM `#@__arctype` WHERE reid='$reid' And ishidden<>1 order by sortrank asc limit 0, $line ";
    }
    //And id<>'$typeid'
    $needRel = false;
    $dtp2 = new DedeTagParse();
    $dtp2->SetNameSpace('field','[',']');
    $dtp2->LoadSource($innertext);
    //检查是否有子栏目，并返回rel提示（用于二级菜单）
    if(preg_match('#:rel#', $innertext)) $needRel = true;
    
    if(empty($sql)) return '';
    $dsql->SetQuery($sql);
    $dsql->Execute();
    
    $totalRow = $dsql->GetTotalRow();
    //如果用子栏目模式，当没有子栏目时显示同级栏目
    if($type=='son' && $reid!=0 && $totalRow==0)
    {
        $sql = "SELECT id,typename,typedir,isdefault,ispart,defaultname,namerule2,moresite,siteurl,sitepath
            FROM `#@__arctype` WHERE reid='$reid' And ishidden<>1 order by sortrank asc limit 0, $line ";
        $dsql->SetQuery($sql);
      $dsql->Execute();
    }
    $GLOBALS['autoindex'] = 0;
    for($i=0;$i < $line;$i++)
    {
        if($col>1) $likeType .= "<dl>\r\n";
        for($j=0; $j<$col; $j++)
        {
            if($col>1) $likeType .= "<dd>\r\n";
            if($row=$dsql->GetArray())
            {
                $row['sonids'] = $row['rel'] = '';
                if($needRel)
                {
                    $row['sonids'] = GetSonIds($row['id'], 0, false);
                    if($row['sonids']=='') $row['rel'] = '';
                    else $row['rel'] = " rel='dropmenu{$row['id']}'";
                }
                //处理同级栏目中，当前栏目的样式
                if( ($row['id']==$typeid || ($topid==$row['id'] && $type=='top') ) && $currentstyle!='' )
                {
                    $linkOkstr = $currentstyle;
                    $row['typelink'] = GetOneTypeUrlA($row);
                    $linkOkstr = str_replace("~rel~",$row['rel'],$linkOkstr);
                    $linkOkstr = str_replace("~id~",$row['id'],$linkOkstr);
                    $linkOkstr = str_replace("~typelink~",$row['typelink'],$linkOkstr);
                    $linkOkstr = str_replace("~typename~",$row['typename'],$linkOkstr);
                    $likeType .= $linkOkstr;
                }
                else
                {
                    $row['typelink'] = $row['typeurl'] = GetOneTypeUrlA($row);
                    if(is_array($dtp2->CTags))
                    {
                        foreach($dtp2->CTags as $tagid=>$ctag)
                        {
                            if(isset($row[$ctag->GetName()])) $dtp2->Assign($tagid,$row[$ctag->GetName()]);
                        }
                    }
                    $likeType .= $dtp2->GetResult();
                }
            }
            if($col>1) $likeType .= "</dd>\r\n";
            $GLOBALS['autoindex']++;
        }
        //Loop Col
        if($col>1)
        {
            $i += $col - 1;
            $likeType .= "    </dl>\r\n";
        }
    }
    //Loop for $i
    $dsql->FreeResult();
    if($cacheid !='') {
        WriteCacheBlock($cacheid, $likeType);
    }
    return $likeType;
}