<?php
/**
 * 会员留言管理
 *
 * @version        $Id: member_guestbook.php 1 14:08 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_memberguestbook');
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/common.func.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$dopost = empty($dopost)? "" : $dopost;
$uname = empty($uname)? "" : $uname;
$ways = empty($ways)? "" : $ways;

$sql = $where = "";
$mid = empty($mid) ? 0 : intval($mid);
if($mid>0) $where .= "AND g.mid='$mid' ";
if(!$uname=='') $where .= "AND g.uname='$uname' ";
if(!$ways=='' && !$body=='')
{
    $body = preg_replace ("#^(　| )+#i", '', $body);
    $body = preg_replace ("#(　| )+$#i", '', $body);
    switch ($ways) {
        case "uname": 
        $where .="AND g.uname='$body'";
    break;
    case "userid": 
        $row=$dsql->GetOne("SELECT mid FROM #@__member WHERE userid='$body' LIMIT 1");
        $mid=$row['mid'];
        $where .="AND g.mid='$mid'";
    break;
    case "msg": 
        $where .="AND g.msg LIKE '%$body%'";
    break;
  }
}

//删除留言
if($dopost=="del")
{
    $bkurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "member_guestbook.php";
    $ids = explode('`',$ids);
    $dquery = "";
    foreach($ids as $id)
    {
        if($dquery=="")
        {
            $dquery .= " aid='$id' ";
        }
        else
        {
            $dquery .= " OR aid='$id' ";
        }
    }
    if($dquery!="") $dquery = " WHERE ".$dquery;
    $dsql->ExecuteNoneQuery("DELETE FROM #@__member_guestbook $dquery");
    ShowMsg("成功删除指定的记录！",$bkurl);
    exit();
}

//删除相同留言者的所有留言
else if( $dopost=="deluname" )
{
        $ids = preg_replace("#[^0-9,]#i", ',', $ids);
        $dsql->SetQuery("SELECT uname FROM `#@__member_guestbook` WHERE aid IN ($ids) ");
        $dsql->Execute();
        $unames = '';
        while($row = $dsql->GetArray())
        {
            $unames .= ($unames=='' ? " uname = '{$row['uname']}' " : " OR uname = '{$row['uname']}' ");
        }
        if($unames!='')
        {
            $query = "DELETE FROM `#@__member_guestbook` WHERE $unames ";
            $dsql->ExecuteNoneQuery($query);
        }
        ShowMsg("成功删除指定相同留言者的所有留言!",$_COOKIE['ENV_GOBACK_URL'],0,500);
        exit();
}

//删除相同IP的所有留言
else if( $dopost=="delall" )
{
        $ids = preg_replace("#[^0-9,]#i", ',', $ids);
        $dsql->SetQuery("SELECT ip FROM `#@__member_guestbook` WHERE aid IN ($ids) ");
        $dsql->Execute();
        $ips = '';
        while($row = $dsql->GetArray())
        {
            $ips .= ($ips=='' ? " ip = '{$row['ip']}' " : " OR ip = '{$row['ip']}' ");
        }
        if($ips!='')
        {
            $query = "DELETE FROM `#@__member_guestbook` WHERE $ips ";
            $dsql->ExecuteNoneQuery($query);
        }
        ShowMsg("成功删除指定相同IP的所有留言!",$_COOKIE['ENV_GOBACK_URL'],0,500);
        exit();
}
$sql = "SELECT g.*,m.userid FROM #@__member_guestbook AS g LEFT JOIN #@__member AS m ON g.mid=m.mid WHERE 1=1 $where ORDER BY aid DESC";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetTemplate(DEDEADMIN."/templets/member_guestbook.htm");
$dlist->SetSource($sql);
$dlist->Display();