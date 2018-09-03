<?php
/**
 * @version        $Id: guestbook_admin.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'gu';
$pagesize = isset($pagesize) && is_numeric($pagesize) ? $pagesize : 5;
$pageno = isset($pageno) && is_numeric($pageno) ? max(1,$pageno) : 1;
if(empty($dopost)) $dopost = '';


//重载列表
if($dopost=='getlist')
{
    AjaxHead();
    GetList($dsql,$pageno,$pagesize);
    exit();
}

//删除留言
if($dopost=='del')
{
    if(!empty($aid))
    {
        $aid = intval($aid);
        $dsql->ExecuteNoneQuery("Delete FROM `#@__member_guestbook` WHERE aid='$aid' And mid='".$cfg_ml->M_ID."'; ");
    }
    else if(!empty($ids))
    {
        $ids = preg_replace("#[^0-9,]#", '', $ids);
        if($ids!='')
        {
            $dsql->ExecuteNoneQuery("Delete FROM `#@__member_guestbook` WHERE aid in($ids) And mid='".$cfg_ml->M_ID."'; ");
        }
    }
    AjaxHead();
    GetList($dsql, $pageno, $pagesize);
    exit();
}

//第一次进入这个页面
if($dopost=='')
{
    $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__member_guestbook` WHERE mid='".$cfg_ml->M_ID."'; ");
    $totalRow = $row['dd'];
    include(dirname(__FILE__)."/templets/guestbook_admin.htm");
}

/**
 *  获得特定的关键字列表
 *
 * @access    public
 * @param     object  $dsql  数据库操作类
 * @param     int  $pageno  页面数
 * @param     int  $pagesize  页面尺寸
 * @return    string
 */
function GetList(&$dsql, $pageno, $pagesize)
{
    global $cfg_phpurl,$cfg_ml;
    $pagesize = intval($pagesize);
    $pageno = intval($pageno);
    $start = ($pageno-1) * $pagesize;
    $dsql->SetQuery("SELECT * FROM `#@__member_guestbook` WHERE mid='".$cfg_ml->M_ID."' ORDER BY aid DESC LIMIT $start,$pagesize ");
    $dsql->Execute();
    $line = '';
    while($row = $dsql->GetArray())
    {

        $line .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='list'>
          <thead>
            <tr>
              <th colspan='6'><strong class='fLeft' style='padding-left: 5px;'>留言标题：".$row['title']."</strong><span class='fRight'>
              <input name=\"ids\" type=\"checkbox\" id=\"ids\" value=\"".$row['aid']."\" />
              <a href='#' onclick='DelNote(".$row['aid'].")' style='color:#29A5C9;'>删除</a></span></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td width='18%' style='padding-left: 10px;'>用户称呼：".$row['uname']."</td>
              <td>时间：".MyDate("Y-m-d H:i",$row['dtime'])."&nbsp;IP地址：".$row['ip']."&nbsp;";

        if(!empty($row['gid']))
        {
            $line .= " <a href='index.php?uid={$row['uname']}&action=infos' target='_blank'>资料</a> <a href='index.php?uid={$row['uname']}' target='_blank'>空间</a> <a href='index.php?uid={$row['uname']}&action=guestbook' target='_blank'>回复</a> ";
        }
        $line .= "</td>
            </tr>
            <tr>
              <td colspan='2' style='padding-left: 10px;'>".Text2Html($row['msg'])."</td>
            </tr>
          </tbody>
        </table>";
    }
    $line = $line == '' ? '暂无留言' : $line;
    echo $line;
}