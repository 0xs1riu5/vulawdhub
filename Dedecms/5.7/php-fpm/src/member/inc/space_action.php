<?php   if(!defined('DEDEMEMBER')) exit('dedecms');
/**
 * 空间操作
 * 
 * @version        $Id: space_action.php 1 15:18 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

//是否允许用户空间显示未审核文档
$addqSql = '';
if($cfg_mb_allowncarc=='N') $addqSql .= " And arc.arcrank > -1 ";
if(isset($mtype)) $mtype = intval($mtype);
if(!empty($mtype)) $addqSql .= " And arc.mtype = '$mtype' ";

/*---------------------------------
文章列表
function list_article(){ }
-------------------------------------*/
if($action=='article')
{
    if(empty($mtype)) $mtype = 0;

    include_once(DEDEINC.'/arc.memberlistview.class.php');
    include_once(DEDEINC.'/channelunit.func.php');
    $query = "SELECT arc.*,mt.mtypename,addt.body,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
          FROM `#@__archives` arc 
          LEFT JOIN `#@__addonarticle` addt ON addt.aid=arc.id
          LEFT JOIN `#@__arctype` tp ON tp.id=arc.typeid 
          LEFT JOIN `#@__mtypes` mt ON mt.mtypeid=arc.mtype
          WHERE arc.mid='{$_vars['mid']}' $addqSql AND arc.channel=1 ORDER BY arc.id DESC";
    $dlist = new MemberListview();
    $dlist->pageSize = $_vars['pagesize'];
    $dlist->SetParameter("mtype",$mtype);
    $dlist->SetParameter("uid",$_vars['userid']);
    $dlist->SetParameter("action",$action);
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/listarticle.htm");
    $dlist->SetSource($query);
    $dlist->Display();
    exit();
}
/*---------------------------------
单篇文章显示
function view_archives(){ }
-------------------------------------*/
else if($action=='viewarchives' && !empty($aid) && is_numeric($aid))
{
    if(empty($mtype)) $mtype = 0;

    include_once(DEDEINC.'/arc.memberlistview.class.php');
    include_once(DEDEINC.'/channelunit.func.php');
    
    //读取文章的评论
    $sql = "SELECT fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores FROM `#@__feedback` fb
        LEFT JOIN `#@__member` mb ON mb.mid = fb.mid
        WHERE fb.aid='$aid' and fb.ischeck='1' ORDER BY fb.id DESC LIMIT 0, 50";
    $msgs = array();
    $dsql->Execute('fb', $sql);
    while ($row = $dsql->GetArray('fb'))
    {
        $msgs[] = $row;
    }
    
    //读取文章内容
    $query = "SELECT arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,
            tp.ispart,tp.moresite,tp.siteurl,tp.sitepath,ar.body FROM `#@__archives` arc
            LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
            LEFT JOIN `#@__addonarticle` ar ON ar.aid=arc.id 
            WHERE arc.mid='{$_vars['mid']}' AND arc.channel=1 AND ar.typeid=tp.id AND ar.aid='$aid' ";
    $arcrow = $dsql->GetOne($query);
    if( !is_array($arcrow) )
    {
        ShowMsg(' 读取文档时发生未知错误! ', '-1');
        exit();
    }
    
    //解析模板
    $dlist = new MemberListview();
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/blog.htm");
    $dlist->Display();
    exit();
}
/*---------------------------------
所有文档列表
function list_archives(){ }
-------------------------------------*/
else if($action=='archives')
{
    if(empty($mtype)) $mtype = 0;
    include_once(DEDEINC.'/arc.memberlistview.class.php');
    include_once(DEDEINC.'/channelunit.func.php');
    
    //如果没指定频道ID的情况下，列出所有非单表模型文档
    if($cfg_mb_spaceallarc > 0 && empty($channelid)) $channelid = intval($cfg_mb_spaceallarc);

    if(empty($channelid))
    {
        $channelid = 0;
        $query = "SELECT arc.*,mt.mtypename,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
                 FROM `#@__archives` arc 
                 LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
                 LEFT JOIN `#@__mtypes` mt ON mt.mtypeid=arc.mtype
                 WHERE arc.mid='{$_vars['mid']}' $addqSql ORDER BY arc.id DESC";
    }
    else
    {
        $channelid = intval($channelid);
        $chRow = $dsql->GetOne("SELECT issystem,addtable,listfields From `#@__channeltype` WHERE id='$channelid' ");
        if(!is_array($chRow)) die(' Channel Error! ');
        if($chRow['issystem']==-1)
        {
            $addtable = trim($chRow['addtable']);
            $listfields = explode(',', $chRow['listfields']);
            $listfields_str = 'arc.'.join(',arc.', $listfields);
            if($listfields_str!='arc.') 
            {
                $listfields_str = $listfields_str.',';
            }
            else {
                $listfields_str = '';
            }
            $query = "SELECT arc.aid,arc.aid as id,arc.typeid,'' as mtypename,1 as ismake,0 as money,'' as filename,{$listfields_str}
                   tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
                   FROM `{$addtable}` arc 
                   LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id
                 WHERE arc.mid='{$_vars['mid']}' And arc.channel='$channelid' $addqSql ORDER BY arc.aid DESC";
        }
        else
        {
            $query = "SELECT arc.*,mt.mtypename,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
                    from `#@__archives` arc
                    LEFT JOIN `#@__arctype` tp on arc.typeid=tp.id
                    LEFT JOIN `#@__mtypes` mt on mt.mtypeid=arc.mtype
                 WHERE arc.mid='{$_vars['mid']}' And arc.channel='$channelid' $addqSql order by arc.id desc";
        }
    }
    $dlist = new MemberListview();
    $dlist->pageSize = $_vars['pagesize'];
    $dlist->SetParameter("mtype", $mtype);
    $dlist->SetParameter("uid", $_vars['userid']);
    $dlist->SetParameter("channelid", $channelid);
    $dlist->SetParameter("action", $action);
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/listarchives.htm");
    $dlist->SetSource($query);
    $dlist->Display();
    exit();
}

/*---------------------------------
所有文档列表
function list_album(){ }
-------------------------------------*/
else if($action=='album')
{
    if(empty($mtype)) {
        $mtype = 0;
    }
    include_once(DEDEINC.'/arc.memberlistview.class.php');
    include_once(DEDEINC.'/channelunit.func.php');
    $query = "SELECT arc.*,mt.mtypename,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
          from `#@__archives` arc 
          left join `#@__arctype` tp on arc.typeid=tp.id
          left join `#@__mtypes` mt on mt.mtypeid=arc.mtype
          where arc.mid='{$_vars['mid']}' And arc.channel=2 $addqSql order by arc.id desc";
    $dlist = new MemberListview();
    $dlist->pageSize = $_vars['pagesize'];
    $dlist->SetParameter("mtype",$mtype);
    $dlist->SetParameter("uid",$_vars['userid']);
    $dlist->SetParameter("action",$action);
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/listalbum.htm");
    $dlist->SetSource($query);
    $dlist->Display();
    exit();
}

/*---------------------------------
留言本
function guestbook(){ }
-------------------------------------*/
else if($action=='guestbook')
{
    if(empty($mtype)) {
        $mtype = 0;
    }
    include_once(DEDEINC.'/datalistcp.class.php');
    $query = "SELECT mg.*,mb.face,mb.userid,mb.sex From `#@__member_guestbook` mg 
    left join `#@__member` mb on mb.userid=mg.gid 
    where mg.mid='{$_vars['mid']}' order by mg.aid desc";
    $dlist = new DataListCP();
    $dlist->pageSize = 10;
    $dlist->SetParameter("uid",$_vars['userid']);
    $dlist->SetParameter("action",$action);
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/guestbook.htm");
    $dlist->SetSource($query);
    $dlist->Display();
    exit();
}

/*---------------------------------
我的好友
function friend(){ }
-------------------------------------*/
else if($action=='friend')
{
    if(empty($mtype)) {
        $mtype = 0;
    }
    include_once(DEDEINC.'/arc.memberlistview.class.php');
    include_once(DEDEINC.'/channelunit.func.php');
    $query = "SELECT arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath
          from `#@__archives` arc
          left join `#@__arctype` tp on arc.typeid=tp.id
          where arc.mid='{$_vars['mid']}' $addqSql order by arc.id desc";
    $dlist = new MemberListview();
    $dlist->pageSize = 8;
    $dlist->SetParameter("mtype",$mtype);
    $dlist->SetParameter("uid",$_vars['userid']);
    $dlist->SetParameter("action",$action);
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/friend.htm");
    $dlist->SetSource($query);
    $dlist->Display();
    exit();
}

/*---------------------------------
个人资料
function infos(){ }
-------------------------------------*/
else if($action=='infos')
{
    include_once(DEDEDATA.'/enums/nativeplace.php');
    include_once(DEDEINC."/enums.func.php");
    $row = $dsql->GetOne("SELECT  * from `#@__member_person` where mid='{$_vars['mid']}' ");
    $dpl = new DedeTemplate();
    $dpl->LoadTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/infos.htm");
    $dpl->display();
}

/*---------------------------------
保存留言
function guestbook_save(){ }
-------------------------------------*/
else if($action=='guestbooksave')
{
    CheckRank(0,0);
    $svali = GetCkVdValue();
    if(strtolower($vdcode)!=$svali || $svali=='')
    {
        ResetVdValue();
        ShowMsg('验证码错误！', '-1');
        exit();
    }
    $uidnum = intval($uidnum);
    if(empty($uidnum))
    {
        ShowMsg('参数错误！', '-1');
        exit();
    }
    if(strlen($msg)<6)
    {
        ShowMsg('你的留言内容太短！', '-1');
        exit();
    }
    $uname = HtmlReplace($uname, 1);
    $msg = cn_substrR(HtmlReplace($msg), 2048);
    if($cfg_ml->M_UserName != '' && $cfg_ml->M_ID != $uidnum)
    {
        $gid = $cfg_ml->M_UserName;
    }
    else
    {
        $gid = '';
    }
    $inquery = "INSERT INTO `#@__member_guestbook`(mid,gid,msg,uname,ip,dtime)
   VALUES ('$uidnum','$gid','$msg','$uname','".GetIP()."',".time()."); ";
    $dsql->ExecuteNoneQuery($inquery);
    ShowMsg('成功提交你的留言！', "index.php?uid={$uid}&action=guestbook");
    exit();
}

/*---------------------------------
删除留言
function guestbook_del(){ }
-------------------------------------*/
else if($action=='guestbookdel')
{
    CheckRank(0,0);
    if($cfg_ml->M_LoginID!=$uid)
    {
        ShowMsg('这条留言不是给你的，你不能删除！', -1);
        exit();
    }
    $inquery = "DELETE FROM `#@__member_guestbook` WHERE aid='$aid' AND mid='$mid'"; 
    $dsql->ExecuteNoneQuery($inquery);
    ShowMsg('成功删除！', "index.php?uid={$uid}&action=guestbook");
    exit();
}

/*---------------------------------
删除我的动态信息
function feed_del(){ }
-------------------------------------*/
else if($action=='feeddel')
{
    CheckRank(0,0);
    $fid=(empty($fid))? "" : $fid;
    $row = $dsql->GetOne("SELECT mid FROM `#@__member_feed` WHERE fid='$fid'");
    if($cfg_ml->M_ID!=$row['mid'])
    {
        ShowMsg('此动态信息不存在！', -1);
        exit();
    }
    $inquery = "DELETE FROM `#@__member_feed` WHERE fid='$fid' AND mid='".$cfg_ml->M_ID."'"; 
    $dsql->ExecuteNoneQuery($inquery);
    ShowMsg('成功删除一条动态信息！', "index.php");
    exit();
}
/*---------------------------------
删除我的心情信息
function mood_del(){ }
-------------------------------------*/
else if($action=='mooddel')
{
    CheckRank(0,0);
    $id=(empty($id))? "" : $id;
    $row = $dsql->GetOne("SELECT mid FROM `#@__member_msg` WHERE id='$id'");
    if($cfg_ml->M_ID!=$row['mid'])
    {
        ShowMsg('此动态信息不存在！', -1);
        exit();
    }
    $inquery = "DELETE FROM `#@__member_msg` WHERE id='$id' AND mid='".$cfg_ml->M_ID."'"; 
    $dsql->ExecuteNoneQuery($inquery);
    ShowMsg('成功删除一条心情！', "index.php");
    exit();
}
/*---------------------------------
加好友
function newfriend(){ }
-------------------------------------*/
else if($action=='newfriend')
{
    CheckRank(0,0);
    if($_vars['mid']==$cfg_ml->M_ID)
    {
        ShowMsg("你不能加自己为好友！","index.php?uid=".$uid);
        exit();
    }
    $addtime = time();
    $row = $dsql->GetOne("SELECT * FROM `#@__member_friends` where fid='{$_vars['mid']}' And mid='{$cfg_ml->M_ID}' ");
    if(is_array($row))
    {
        ShowMsg("该用户已经是你的好友！","index.php?uid=".$uid);
        exit();
    }
    else
    {
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            if($data = uc_get_user($cfg_ml->M_LoginID)) uc_friend_add($uid,$data[0]);
        }
        #/aip}}
    
        $inquery = "INSERT INTO `#@__member_friends` (`fid` , `floginid` , `funame` , `mid` , `addtime` , `ftype`)
                VALUES ('{$_vars['mid']}' , '{$_vars['userid']}' , '{$_vars['uname']}' , '{$cfg_ml->M_ID}' , '$addtime' , '0'); ";
        $dsql->ExecuteNoneQuery($inquery);
        //统计我的好友数量
        $row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__member_friends` WHERE `mid`='".$cfg_ml->M_ID."'");
        $dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET friend='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");

        //会员动态记录
        $cfg_ml->RecordFeeds('addfriends',"","",$_vars['userid']);
        
        ShowMsg("成功添加好友！","index.php?uid=".$uid);
        exit();
    
    }
}
/*---------------------------------
解除好友关系
function newfriend(){ }
-------------------------------------*/
else if($action=='delfriend')
{
    CheckRank(0,0);
    if($_vars['mid']==$cfg_ml->M_ID)
    {
        ShowMsg("你不能和自己为解除关系！","index.php?uid=".$uid);
        exit();
    }
    $addtime = time();
    $row = $dsql->GetOne("Select * FROM `#@__member_friends` where fid='{$_vars['mid']}' And mid='{$cfg_ml->M_ID}' ");
    if(!is_array($row))
    {
        ShowMsg("该用户已经不是你的好友！","index.php?uid=".$uid);
        exit();
    }
    else
    {
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            if($data = uc_get_user($cfg_ml->M_LoginID)) uc_friend_add($uid,$data[0]);
        }
        #/aip}}
        $inquery = "DELETE FROM `dede_member_friends` where fid='{$_vars['mid']}' And mid='{$cfg_ml->M_ID}' ";
        $dsql->ExecuteNoneQuery($inquery);
        //统计我的好友数量
        $row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__member_friends` WHERE `mid`='".$cfg_ml->M_ID."'");
        $dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET friend='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");
        ShowMsg("成功解除好友关系！","myfriend.php");
        exit();
    }
}
/*---------------------------------
加黑名单
function blackfriend(){ }
-------------------------------------*/
else if($action=='blackfriend')
{
    CheckRank(0,0);
    if($_vars['mid']==$cfg_ml->M_ID)
    {
        ShowMsg("你不能加自己到黑名单！","index.php?uid=".$uid);
        exit();
    }
    $addtime = time();
    $row = $dsql->GetOne("Select * FROM `#@__member_friends` where fid='{$_vars['mid']}' And mid='{$cfg_ml->M_ID}' ");
    if(is_array($row))
    {
        ShowMsg("该用户已经是你的好友！","index.php?uid=".$uid);
        exit();
    }
    else
    {
        $inquery = "INSERT INTO `#@__member_friends` (`fid` , `floginid` , `funame` , `mid` , `addtime` , `ftype`)
                VALUES ('{$cfg_ml->M_ID}' , '{$cfg_ml->M_LoginID}' , '{$cfg_ml->M_UserName}' , '{$_vars['mid']}' , '$addtime' , '-1'); ";
        $dsql->ExecuteNoneQuery($inquery);
        ShowMsg("成功添加好友在黑名单！","index.php?uid=".$uid);
        exit();
    }
}
/*--------------------
function _contact_introduce() {}
公司简介
---------------------*/
elseif($action == 'introduce')
{
    $dpl = new DedeTemplate();
    $dpl->LoadTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/introduce.htm");
    $dpl->display();
}
//联系我们
elseif ($action == 'contact')
{
    $dpl = new DedeTemplate();
    $dpl->LoadTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/contact.htm");
    $dpl->display();
}
/*-------------------------------
function products() { }
公司产品或新闻
--------------------------------*/
elseif($action == 'products')
{
    $mtype = isset($mtype) && is_numeric($mtype) ? $mtype : 0;
    if($action == 'products') {
        $channel = 6;
    }
    include_once(DEDEINC.'/arc.memberlistview.class.php');
    include_once(DEDEINC.'/channelunit.func.php');

    $query = "Select arc.*,tp.typedir,tp.typename,tp.isdefault,tp.defaultname,tp.namerule,
        tp.namerule2,tp.ispart,tp.moresite,tp.siteurl,tp.sitepath FROM `#@__archives` arc
        left join `#@__arctype` tp on arc.typeid=tp.id
        where arc.mid='{$_vars['mid']}' and arc.channel='$channel' $addqSql order by arc.id desc";
    
    $dlist = new MemberListview();
    $dlist->pageSize = 12;
    $dlist->SetParameter('mtype', $mtype);
    $dlist->SetParameter('uid', $_vars['userid']);
    $dlist->SetParameter('action', $action);
    $dlist->SetTemplate(DEDEMEMBER."/space/{$_vars['spacestyle']}/listproducts.htm");
    $dlist->SetSource($query);
    $dlist->Display();
    exit();
}