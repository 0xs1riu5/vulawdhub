<?php   if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 连载图书调用
 *
 * @version        $Id: booklist.lib.php 1 9:29 2010年7月6日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  图书列表调用
 *
 * @access    public
 * @param     object  $ctag  解析标签
 * @param     object  $refObj  引用对象
 * @param     int  $getcontent  是否调用内容
 * @return    string
 */
 
/*>>dede>>
<name>连载图书</name>
<type>全局标记</type>
<for>V55,V56,V57</for>
<description>连载图书调用</description>
<demo>
{dede:booklist row='12' booktype='-1' orderby='lastpost' author='' keyword=''}
<a href='[field:bookurl /]'>[field:bookname /]</a><br />
{/dede:booklist}
</demo>
<attributes>
    <iterm>row:调用记录条数</iterm> 
    <iterm>booktype:图书类型，0 图书、1 漫画，默认全部</iterm>
    <iterm>orderby:排序类型，当按排序类型为 commend 表示推荐图书</iterm>
    <iterm>author:作者</iterm>
    <iterm>keyword:关键字</iterm>
</attributes> 
>>dede>>*/

function lib_booklist(&$ctag, &$refObj, $getcontent=0)
{
    global $dsql, $envs, $cfg_dbprefix, $cfg_cmsurl;
    
    //属性处理
    $attlist="row|12,booktype|-1,titlelen|30,orderby|lastpost,author|,keyword|";
    FillAttsDefault($ctag->CAttribute->Items,$attlist);
    extract($ctag->CAttribute->Items, EXTR_SKIP);

    if( !$dsql->IsTable("{$cfg_dbprefix}story_books") ) return '没安装连载模块';
    $addquery = '';
    
    if(empty($innertext))
    {
        if($getcontent==0) $innertext = GetSysTemplets('booklist.htm');
        else $innertext = GetSysTemplets('bookcontentlist.htm');
    }
    
    //图书类型
    if($booktype!=-1) {
        $addquery .= " AND b.booktype='{$booktype}' ";
    }
    
    //推荐
    if($orderby=='commend')
    {
        $addquery .= " AND b.iscommend=1 ";
        $orderby = 'lastpost';
    }

    //作者条件
    if(!empty($author))
    {
        $addquery .= " AND b.author LIKE '$author' ";
    }
    
    //关键字条件
    if(!empty($keyword))
    {
        $keywords = explode(',', $keyword);
        $keywords = array_unique($keywords);
        if(count($keywords) > 0) {
            $addquery .= " AND (";
        }
        foreach($keywords as $v) {
            $addquery .= " CONCAT(b.author,b.bookname,b.keywords) LIKE '%$v%' OR";
        }
        $addquery = preg_replace("# OR$#", "", $addquery);
        $addquery .= ")";
    }
    
    $clist = '';
    $query = "SELECT b.id,b.catid,b.ischeck,b.booktype,b.iscommend,b.click,b.bookname,b.lastpost,
         b.author,b.mid,b.litpic,b.pubdate,b.weekcc,b.monthcc,b.description,c.classname,c.classname as typename,c.booktype as catalogtype
         FROM `#@__story_books` b LEFT JOIN `#@__story_catalog` c ON c.id = b.catid
         WHERE b.postnum>0 AND b.ischeck>0 $addquery ORDER BY b.{$orderby} DESC LIMIT 0, $row";
    $dsql->SetQuery($query);
    $dsql->Execute();

    $ndtp = new DedeTagParse();
    $ndtp->SetNameSpace('field', '[', ']');
    $GLOBALS['autoindex'] = 0;
    while($row = $dsql->GetArray())
    {
        $GLOBALS['autoindex']++;
        $row['title'] = $row['bookname'];
        $ndtp->LoadString($innertext);

        //获得图书最新的一个更新章节
        $row['contenttitle'] = '';
        $row['contentid'] = '';
        if($getcontent==1) {
            $nrow = $dsql->GetOne("SELECT id,title,chapterid FROM `#@__story_content` WHERE bookid='{$row['id']}' order by id desc ");
            $row['contenttitle'] = $nrow['title'];
            $row['contentid'] = $nrow['id'];
        }
        if($row['booktype']==1) {
            $row['contenturl'] = $cfg_cmspath.'/book/show-photo.php?id='.$row['contentid'];
        }
        else {
            $row['contenturl'] = $cfg_cmspath.'/book/story.php?id='.$row['contentid'];
        }

        //动态网址
        $row['dmbookurl'] = $cfg_cmspath.'/book/book.php?id='.$row['id'];

        //静态网址
        $row['bookurl'] = $row['url'] = GetBookUrl($row['id'],$row['bookname']);
        $row['catalogurl'] = $cfg_cmspath.'/book/list.php?id='.$row['catid'];
        $row['cataloglink'] = "<a href='{$row['catalogurl']}'>{$row['classname']}</a>";
        $row['booklink'] = "<a href='{$row['bookurl']}'>{$row['bookname']}</a>";
        $row['contentlink'] = "<a href='{$row['contenturl']}'>{$row['contenttitle']}</a>";
        $row['imglink'] = "<a href='{$row['bookurl']}'><img src='{$row['litpic']}' width='$imgwidth' height='$imgheight' border='0' /></a>";
        
        if($row['ischeck']==2) $row['ischeck']='已完成连载';
        else $row['ischeck']='连载中...';

        if($row['booktype']==0) $row['booktypename']='小说';
        else $row['booktypename']='漫画';

        if(is_array($ndtp->CTags))
        {
            foreach($ndtp->CTags as $tagid=>$ctag)
            {
                $tagname = $ctag->GetTagName();
                if(isset($row[$tagname])) $ndtp->Assign($tagid,$row[$tagname]);
                else $ndtp->Assign($tagid,'');
            }
        }
        $clist .= $ndtp->GetResult();
    }
    
    return $clist;
}