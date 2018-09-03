<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/filter.inc.php");
require_once(DEDEINC."/channelunit.func.php");
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


function GetOnebook($aid)
{
	global $dsql,$title;
	$aid = trim(preg_replace('#[^0-9]#i','',$aid));
	$reArr = array();

	$nquery = "Select * From `#@__story_books` where bid='$aid' ";
	
	$arcRow = $dsql->GetOne($nquery);
	if(!is_array($arcRow)) {
		return $reArr;
	}
	$reArr = $arcRow;
	$reArr['bid']    = $aid;
	$reArr['arctitle'] = $arcRow['bookname'];
	$title = $arcRow['bookname'];
	//$reArr['arcurl'] = GetFileUrl($aid,$arcRow['typeid'],$arcRow['senddate'],$reArr['bookname '],$arcRow['ismake'],$arcRow['arcrank'],$arcRow['namerule'],
	//$arcRow['typedir'],$arcRow['money'],$arcRow['filename'],$arcRow['moresite'],$arcRow['siteurl'],$arcRow['sitepath']);
	return $reArr;

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
	$dsql->ExecuteNoneQuery("Update `#@__bookfeedback` set good = good+1 where id='$fid' ");
	$row = $dsql->GetOne("Select good From `#@__bookfeedback` where id='$fid' ");
	echo "<a onclick=\"postBadGood('goodfb',{$aid})\">支持</a>[{$row['good']}]";
	exit();
}
else if($action=='badfb')
{
	AjaxHead();
	$fid = intval($fid);
	$dsql->ExecuteNoneQuery("Update `#@__bookfeedback` set bad = bad+1 where id='$fid' ");
	$row = $dsql->GetOne("Select bad From `#@__bookfeedback` where id='$fid' ");
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
	$arcRow = GetOnebook($aid);
	if(empty($arcRow['bid']))
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
	$querystring = "select fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores from `#@__bookfeedback` fb
                 left join `#@__member` mb on mb.mid = fb.mid
                 where fb.aid='$aid' and fb.ischeck='1' $wquery order by fb.id desc";
	$dlist->SetParameter('aid',$aid);
	$dlist->SetParameter('action','show');
	$dlist->SetTemplate($cfg_basedir.$cfg_templets_dir.'/plus/bookfeedback_templet.htm');
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
	$row = $dsql->GetOne("Select * from `#@__bookfeedback` where id ='$fid'");
	require_once(DEDEINC.'/dedetemplate.class.php');
	$dtp = new DedeTemplate();
	$dtp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/bookfeedback_quote.htm');
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
	$arcRow = GetOnebook($aid);
	if((empty($arcRow['bid']) || $arcRow['notpost']=='1')&&empty($fid))
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
		$dtp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/bookfeedback_confirm.htm');
		$dtp->Display();
		exit();
	}
	//检查验证码
	if($cfg_feedback_ck=='Y')
	{
		$validate = isset($validate) ? strtolower(trim($validate)) : '';
		$svali = strtolower(trim(GetCkVdValue()));
		if($validate != $svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg('验证码错误！','-1');
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
				$dsql->ExecuteNoneQuery("Update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='{$cfg_ml->M_ID}'; ");
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
		$row = $dsql->GetOne("SELECT dtime FROM `#@__bookfeedback` $where ORDER BY `id` DESC ");
		if($dtime - $row['dtime'] < $cfg_feedback_time)
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
	$msg = cn_substrR(TrimMsg($msg),1000);
	$username = cn_substrR(HtmlReplace($username,2),20);
	if($feedbacktype!='good' && $feedbacktype!='bad')
	{
		$feedbacktype = 'feedback';
	}
	//保存评论内容
	if($comtype == 'comments')
	{
		$arctitle = addslashes($arcRow['arctitle']);
		$arctitle = $arcRow['arctitle'];
		if($msg!='')
		{
			$inquery = "INSERT INTO `#@__bookfeedback`(`aid`,`catid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`, `mid`,`bad`,`good`,`ftype`,`face`,`msg`)
	               VALUES ('$aid','$catid','$username','$bookname','$ip','$ischeck','$dtime', '{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg'); ";
			$rs = $dsql->ExecuteNoneQuery($inquery);
			if(!$rs)
			{
				echo $dsql->GetError();
				exit();
			}
		}
	}
	//引用回复
	elseif ($comtype == 'reply')
	{
		$row = $dsql->GetOne("Select * from `#@__bookfeedback` where id ='$fid'");
		$arctitle = $row['arctitle'];
		$aid =$row['aid'];
		$msg = $quotemsg.$msg;
		$msg = HtmlReplace($msg,2);
		$inquery = "INSERT INTO `#@__bookfeedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`,`mid`,`bad`,`good`,`ftype`,`face`,`msg`)
				VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime','{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg')";
		$dsql->ExecuteNoneQuery($inquery);
	}


	if($cfg_ml->M_ID > 0)
	{
		#api{{
		if(defined('UC_APPID'))
		{
			include_once DEDEROOT.'/api/uc.func.php';
			$row = $dsql->GetOne("SELECT `scores`,`userid` FROM `#@__member` WHERE `mid`='".$cfg_ml->M_ID."'");
			uc_credit_note($row['userid'],$cfg_sendfb_scores);
		}
		#/aip}}
		$dsql->ExecuteNoneQuery("Update `#@__member` set scores=scores+{$cfg_sendfb_scores} where mid='{$cfg_ml->M_ID}' ");
	}
	//统计用户发出的评论
	if($cfg_ml->M_ID > 0)
	{
		#api{{
		if(defined('UC_APPID'))
		{
			include_once DEDEROOT.'/api/uc.func.php';
			//推送事件
			$arcRow = GetOnebook($aid);
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
		$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__bookfeedback` WHERE `mid`='".$cfg_ml->M_ID."'");
		$dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET `feedback`='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");
	}
	$_SESSION['sedtime'] = time();
	if(empty($uid) && isset($cmtuser)) $uid = $cmtuser;
	$backurl = $cfg_formmember ? "index.php?uid={$uid}&action=viewarchives&aid={$aid}" : "bookfeedback.php?aid=$aid";
	if($ischeck==0)
	{
		ShowMsg("成功发表评论，但需审核后才会显示你的评论!",$backurl);
	}elseif($ischeck==1)
	{
		ShowMsg("成功发表评论，现在转到评论页面!",$backurl);
	}
	exit();
}
?>