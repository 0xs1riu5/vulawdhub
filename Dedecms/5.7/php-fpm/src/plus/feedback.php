<?php
/**
 *
 * 评论
 *
 * @version        $Id: feedback.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
if($cfg_feedback_forbid=='Y') exit('系统已经禁止评论功能！');
require_once(DEDEINC."/filter.inc.php");
if(!isset($action))
{
    $action = '';
}
//兼容旧的JS代码
if($action == 'good' || $action == 'bad')
{
    if(!empty($aid)) $id = $aid;
    require_once(dirname(__FILE__).'/digg_ajax.php');
    exit();
}

$cfg_formmember = isset($cfg_formmember) ? true : false;
$ischeck = $cfg_feedbackcheck=='Y' ? 0 : 1;
$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
$fid = (isset($fid) && is_numeric($fid)) ? $fid : 0;
if(empty($aid) && empty($fid))
{
    ShowMsg('文档id不能为空!','-1');
    exit();
}

include_once(DEDEINC."/memberlogin.class.php");
$cfg_ml = new MemberLogin();

if($action=='goodfb')
{
    AjaxHead();
    $fid = intval($fid);
    $dsql->ExecuteNoneQuery("UPDATE `#@__feedback` SET good = good+1 WHERE id='$fid' ");
    $row = $dsql->GetOne("SELECT good FROM `#@__feedback` WHERE id='$fid' ");
    echo "<a onclick=\"postBadGood('goodfb',{$aid})\">支持</a>[{$row['good']}]";
    exit();
}
else if($action=='badfb')
{
    AjaxHead();
    $fid = intval($fid);
    $dsql->ExecuteNoneQuery("UPDATE `#@__feedback` SET bad = bad+1 WHERE id='$fid' ");
    $row = $dsql->GetOne("SELECT bad FROM `#@__feedback` WHERE id='$fid' ");
    echo "<a onclick=\"postBadGood('badfb',{$aid})\">反对</a>[{$row['bad']}]";
    exit();
}
//查看评论
/*
function __ViewFeedback(){ }
*/
//-----------------------------------
else if($action=='' || $action=='show')
{
    //读取文档信息
    $arcRow = GetOneArchive($aid);
    if(empty($arcRow['aid']))
    {
        ShowMsg('无法查看未知文档的评论!','-1');
        exit();
    }
    extract($arcRow, EXTR_SKIP);
    include_once(DEDEINC.'/datalistcp.class.php');
    $dlist = new DataListCP();
    $dlist->pageSize = 20;

    if(empty($ftype) || ($ftype!='good' && $ftype!='bad' && $ftype!='feedback'))
    {
        $ftype = '';
    }
    $wquery = $ftype!='' ? " And ftype like '$ftype' " : '';

    //评论内容列表
    $querystring = "SELECT fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores,mb.sex FROM `#@__feedback` fb
                 LEFT JOIN `#@__member` mb on mb.mid = fb.mid
                 WHERE fb.aid='$aid' AND fb.ischeck='1' $wquery ORDER BY fb.id desc";
    $dlist->SetParameter('aid',$aid);
    $dlist->SetParameter('action','show');
    $dlist->SetTemplate(DEDETEMPLATE.'/plus/feedback_templet.htm');
    $dlist->SetSource($querystring);
    $dlist->Display();
    exit();
}

//引用评论
//------------------------------------
/*
function __Quote(){ }
*/
else if($action=='quote')
{
    $row = $dsql->GetOne("SELECT * FROM `#@__feedback` WHERE id ='$fid'");
    require_once(DEDEINC.'/dedetemplate.class.php');
    $dtp = new DedeTemplate();
    $dtp->LoadTemplate(DEDETEMPLATE.'/plus/feedback_quote.htm');
    $dtp->Display();
    exit();
}
//发表评论
//------------------------------------
/*
function __SendFeedback(){ }
*/
else if($action=='send')
{
    //读取文档信息
    $arcRow = GetOneArchive($aid);
    if((empty($arcRow['aid']) || $arcRow['notpost']=='1') && empty($fid))
    {
        ShowMsg('无法对该文档发表评论!','-1');
        exit();
    }

    //是否加验证码重确认
    if(empty($isconfirm))
    {
        $isconfirm = '';
    }
    if($isconfirm!='yes' && $cfg_feedback_ck=='Y')
    {
        extract($arcRow, EXTR_SKIP);
        require_once(DEDEINC.'/dedetemplate.class.php');
        $dtp = new DedeTemplate();
        $dtp->LoadTemplate(DEDETEMPLATE.'/plus/feedback_confirm.htm');
        $dtp->Display();
        exit();
    }
    //检查验证码
    if(preg_match("/4/",$safe_gdopen)){
        $validate = isset($validate) ? strtolower(trim($validate)) : '';
        $svali = GetCkVdValue();
        if(strtolower($validate)!=$svali || $svali=='')
        {
            ResetVdValue();
            ShowMsg('验证码错误！', '-1');
            exit();
        }
        
    }

    //检查用户登录
    if(empty($notuser))
    {
        $notuser=0;
    }

    //匿名发表评论
    if($notuser==1)
    {
        $username = $cfg_ml->M_ID > 0 ? '匿名' : '游客';
    }

    //已登录的用户
    else if($cfg_ml->M_ID > 0)
    {
        $username = $cfg_ml->M_UserName;
    }

    //用户身份验证
    else
    {
        if($username!='' && $pwd!='')
        {
            $rs = $cfg_ml->CheckUser($username,$pwd);
            if($rs==1)
            {
                $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET logintime='".time()."',loginip='".GetIP()."' WHERE mid='{$cfg_ml->M_ID}'; ");
            }
            else
            {
                $username = '游客';
            }
        }
        else
        {
            $username = '游客';
        }
    }
    $ip = GetIP();
    $dtime = time();
    
    //检查评论间隔时间；
    if(!empty($cfg_feedback_time))
    {
        //检查最后发表评论时间，如果未登陆判断当前IP最后评论时间
        if($cfg_ml->M_ID > 0)
        {
            $where = "WHERE `mid` = '$cfg_ml->M_ID'";
        }
        else
        {
            $where = "WHERE `ip` = '$ip'";
        }
        $row = $dsql->GetOne("SELECT dtime FROM `#@__feedback` $where ORDER BY `id` DESC ");
        if(is_array($row) && $dtime - $row['dtime'] < $cfg_feedback_time)
        {
            ResetVdValue();
            ShowMsg('管理员设置了评论间隔时间，请稍等休息一下！','-1');
            exit();
        }
    }

    if(empty($face))
    {
        $face = 0;
    }
    $face = intval($face);
    extract($arcRow, EXTR_SKIP);
    $msg = cn_substrR(TrimMsg($msg), 1000);
    $username = cn_substrR(HtmlReplace($username, 2), 20);
    if(empty($feedbacktype) || ($feedbacktype!='good' && $feedbacktype!='bad'))
    {
        $feedbacktype = 'feedback';
    }
    //保存评论内容
    if($comtype == 'comments')
    {
        $arctitle = addslashes($title);
        if($msg!='')
        {
            $inquery = "INSERT INTO `#@__feedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`, `mid`,`bad`,`good`,`ftype`,`face`,`msg`)
                   VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime', '{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg'); ";
            $rs = $dsql->ExecuteNoneQuery($inquery);
            if(!$rs)
            {
                ShowMsg(' 发表评论错误! ', '-1');
                //echo $dsql->GetError();
                exit();
            }
        }
    }
    //引用回复
    elseif ($comtype == 'reply')
    {
        $row = $dsql->GetOne("SELECT * FROM `#@__feedback` WHERE id ='$fid'");
        $arctitle = $row['arctitle'];
        $aid =$row['aid'];
        $msg = $quotemsg.$msg;
        $msg = HtmlReplace($msg, 2);
        $inquery = "INSERT INTO `#@__feedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`,`mid`,`bad`,`good`,`ftype`,`face`,`msg`)
                VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime','{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg')";
        $dsql->ExecuteNoneQuery($inquery);
    }

    if($feedbacktype=='bad')
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__archives` SET scores=scores-{cfg_feedback_sub},badpost=badpost+1,lastpost='$dtime' WHERE id='$aid' ");
    }
    else if($feedbacktype=='good')
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__archives` SET scores=scores+{$cfg_feedback_add},goodpost=goodpost+1,lastpost='$dtime' WHERE id='$aid' ");
    }
    else
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__archives` SET scores=scores+1,lastpost='$dtime' WHERE id='$aid' ");
    }
    if($cfg_ml->M_ID > 0)
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET scores=scores+{$cfg_sendfb_scores} WHERE mid='{$cfg_ml->M_ID}' ");
    }
    //统计用户发出的评论
    if($cfg_ml->M_ID > 0)
    {
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/api/uc.func.php')
        {
            //同步积分
            uc_credit_note($cfg_ml->M_LoginID, $cfg_sendfb_scores);
            
            //推送事件
            $arcRow = GetOneArchive($aid);
            $feed['icon'] = 'thread';
            $feed['title_template'] = '<b>{username} 在网站发表了评论</b>';
            $feed['title_data'] = array('username' => $cfg_ml->M_UserName);
            $feed['body_template'] = '<b>{subject}</b><br>{message}';
            $url = !strstr($arcRow['arcurl'],'http://') ? ($cfg_basehost.$arcRow['arcurl']) : $arcRow['arcurl'];        
            $feed['body_data'] = array('subject' => "<a href=\"".$url."\">$arcRow[arctitle]</a>", 'message' => cn_substr(strip_tags(preg_replace("/\[.+?\]/is", '', $msg)), 150));
            $feed['images'][] = array('url' => $cfg_basehost.'/images/scores.gif', 'link'=> $cfg_basehost);
            uc_feed_note($cfg_ml->M_LoginID,$feed); unset($arcRow);
        }
        #/aip}}
    
        $row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__feedback` WHERE `mid`='".$cfg_ml->M_ID."'");
        $dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET `feedback`='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");
    }
    
    //会员动态记录
    $cfg_ml->RecordFeeds('feedback', $arctitle, $msg, $aid);
    
    $_SESSION['sedtime'] = time();
    if(empty($uid) && isset($cmtuser)) $uid = $cmtuser;
    $backurl = $cfg_formmember ? "index.php?uid={$uid}&action=viewarchives&aid={$aid}" : "feedback.php?aid=$aid";
    if($ischeck==0)
    {
        ShowMsg('成功发表评论，但需审核后才会显示你的评论!', $backurl);
    }
    else
    {
        ShowMsg('成功发表评论，现在转到评论页面!', $backurl);
    }
    exit();
}