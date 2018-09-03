<?php
/**
 * 我的好友
 *
 * @version        $Id: myfriend.php 1 17:55 2010年7月6日Z tianya $
 * @package        DedeCMS.Helpers
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0, 0);
$menutype = 'mydede';
$menutype_son = 'mf';
if($cfg_mb_lit=='Y')
{
    ShowMsg("由于系统开启了精简版会员空间，你访问的功能不可用！","-1");
    exit();
}
require_once(DEDEINC."/datalistcp.class.php");

if(!isset($ftype)) $ftype = 0;
if(!isset($dopost)) $dopost = '';

//更改好友状态
if($dopost=='upsta')
{
    $ids = preg_replace("#[^0-9,]#", "", $ids);
    if($sta=='good')
    {
        $upsta = " ftype=1 ";
    }
    else if($sta=='bad')
    {
        $upsta = " ftype=-1 ";
    }
    else
    {
        $upsta = " ftype=0 ";
    }
    $dsql->ExecuteNoneQuery("UPDATE `#@__member_friends` SET $upsta WHERE id IN($ids) AND mid='{$cfg_ml->M_ID}' ");
    
    #api{{
    if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php' && $sta!='bad')
    {
        if($data = uc_get_user($cfg_ml->M_LoginID)) uc_friend_add($uid, $data[0]);
    }
    #/aip}}
    
    if($sta=='good')
    {
        ShowMsg("成功把指定好友设为关注好友！","myfriend.php?ftype=1");
    }
    else if($sta=='bad')
    {
        ShowMsg("成功把指定好友放入黑名单！","myfriend.php?ftype=-1");
    }
    else
    {
        ShowMsg("成功把指定好友转为普通好友！","myfriend.php");
    }
    exit();
}

//删除好友
else if($dopost=='del')
{
    $ids = preg_replace("#[^0-9,]#", "", $ids);
    #api{{
    if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
    {
        if($data = uc_get_user($cfg_ml->M_LoginID))
          {
            list($uid, $username, $email) = $data;          
            $friendids = @explode(",", $ids);
            if(!empty($friendids)) uc_friend_delete($uid , $friendids);
        }
    }
    #/aip}}
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__member_friends` WHERE id IN($ids) AND mid='{$cfg_ml->M_ID}' ");
    ShowMsg("成功删除所选的好友！","myfriend.php?ftype=".$ftype);
    exit();
}
//浏览
else{
    $wsql = '';
    if(empty($ftype))
    {
        $wsql = " F.mid='{$cfg_ml->M_ID}' AND F.ftype <>  '-1' ";
        $tname = "所有好友";
    }
    else if($ftype==1)
    {
        $wsql = " F.mid='{$cfg_ml->M_ID}' AND F.ftype =  '1' ";
        $tname = "特别关注";
    }
    else if($ftype==-1)
    {
        $wsql = " F.mid='{$cfg_ml->M_ID}' AND F.ftype =  '-1' ";
        $tname = "黑名单";
    }
    $query = "SELECT F.*,G.groupname FROM `#@__member_group` AS G  LEFT JOIN #@__member_friends AS F ON F.groupid=G.id WHERE $wsql ORDER BY F.id DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetParameter("ftype",$ftype);
    $dlist->SetTemplate(dirname(__FILE__).'/templets/myfriend.htm');
    $dlist->SetSource($query);
    $dlist->Display();
}

/**
 *  获取用户信息
 *
 * @param     int  $uid  用户UID
 * @param     string  $_field  用户字段
 * @return    string
 */
function getUserInfo($uid,$_field = 'uname')
{
    global $dsql;
    $row = $dsql->GetOne("SELECT M.*,YEAR(CURDATE())-YEAR(P.birthday) as age,DATE_FORMAT(P.birthday,'%e月%d日出生') as birthday,S.spacename,S.sign FROM #@__member AS M 
                           LEFT JOIN #@__member_person AS P ON P.mid=M.mid
                           LEFT JOIN #@__member_space AS S ON M.mid=M.mid WHERE M.mid='$uid'");
    if(isset($row[$_field]))
    {
        if($_field == 'face')
        {
            if(empty($row[$_field])){
                $row[$_field]=($row['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
            }
        }
        return $row[$_field];
    }
    else return '';
}