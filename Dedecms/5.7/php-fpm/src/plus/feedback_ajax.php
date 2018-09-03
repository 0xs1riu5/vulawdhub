<?php
/**
 *
 * Ajax评论
 *
 * @version        $Id: feedback_ajax.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(DEDEINC.'/channelunit.func.php');
AjaxHead();

if($cfg_feedback_forbid=='Y') exit('系统已经禁止评论功能！');

$aid = intval($aid);
if(empty($aid)) exit('没指定评论文档的ID，不能进行操作！');

include_once(DEDEINC.'/memberlogin.class.php');
$cfg_ml = new MemberLogin();

if(empty($dopost)) $dopost = '';
$page = empty($page) ? 1 : intval($page);
$pagesize = 10;

/*----------------------
获得指定页的评论内容
function getlist(){ }
----------------------*/
if($dopost=='getlist')
{
    $totalcount = GetList($page);
    GetPageList($pagesize, $totalcount);
    exit();
}
/*----------------------
发送评论
function send(){ }
----------------------*/
else if($dopost=='send')
{
    require_once(DEDEINC.'/charset.func.php');
    
    //检查验证码
    if($cfg_feedback_ck=='Y')
    {
        $svali = strtolower(trim(GetCkVdValue()));
        if(strtolower($validate) != $svali || $svali=='')
        {
            ResetVdValue();
            echo '<font color="red">验证码错误，请点击验证码图片更新验证码！</font>';
            exit();
        }
    }
    
    $arcRow = GetOneArchive($aid);
    if(empty($arcRow['aid']))
    {
        echo '<font color="red">无法查看未知文档的评论!</font>';
        exit();
    }
    if(isset($arcRow['notpost']) && $arcRow['notpost']==1)
    {
        echo '<font color="red">这篇文档禁止评论!</font>';
        exit();
    }
    
    if( $cfg_soft_lang != 'utf8' )
    {
        $msg = UnicodeUrl2Gbk($msg);
        if(!empty($username)) $username = UnicodeUrl2Gbk($username);
    }
    //词汇过滤检查
    if( $cfg_notallowstr != '' )
    {
        if(preg_match("#".$cfg_notallowstr."#i", $msg))
        {
            echo "<font color='red'>评论内容含有禁用词汇！</font>";
            exit();
        }
    }
    if( $cfg_replacestr != '' )
    {
        $msg = preg_replace("#".$cfg_replacestr."#i", '***', $msg);
    }
    if( empty($msg) )
    {
        echo "<font color='red'>评论内容可能不合法或为空！</font>";
        exit();
    }
    //检查用户
    $username = empty($username) ? '游客' : $username;
    if(empty($notuser)) $notuser = 0;
    if($notuser==1)
    {
        $username = $cfg_ml->M_ID > 0 ? '匿名' : '游客';
    }
    else if($cfg_ml->M_ID > 0)
    {
        $username = $cfg_ml->M_UserName;
    }
    else if($username!='' && $pwd!='')
    {
        $rs = $cfg_ml->CheckUser($username, $pwd);
        if($rs==1)
        {
            $dsql->ExecuteNoneQuery("Update `#@__member` set logintime='".time()."',loginip='".GetIP()."' where mid='{$cfg_ml->M_ID}'; ");
        }
        $cfg_ml = new MemberLogin();
    }
    
    //检查评论间隔时间
    $ip = GetIP();
    $dtime = time();
    if(!empty($cfg_feedback_time))
    {
        //检查最后发表评论时间，如果未登陆判断当前IP最后评论时间
        $where = ($cfg_ml->M_ID > 0 ? "WHERE `mid` = '$cfg_ml->M_ID' " : "WHERE `ip` = '$ip' ");
        $row = $dsql->GetOne("SELECT dtime FROM `#@__feedback` $where ORDER BY `id` DESC ");
        if(is_array($row) && $dtime - $row['dtime'] < $cfg_feedback_time)
        {
            ResetVdValue();
            echo '<font color="red">管理员设置了评论间隔时间，请稍等休息一下！</font>';
            exit();
        }
    }
    $face = intval($face);
    extract($arcRow, EXTR_SKIP);
    $msg = cn_substrR(TrimMsg($msg), 500);
    $username = cn_substrR(HtmlReplace($username,2), 20);
    if(empty($feedbacktype) || ($feedbacktype!='good' && $feedbacktype!='bad'))
    {
        $feedbacktype = 'feedback';
    }
    //保存评论内容
    if(!empty($fid))
    {
        $row = $dsql->GetOne("SELECT username,msg from `#@__feedback` WHERE id ='$fid' ");
        $qmsg = '{quote}{title}'.$row['username'].' 的原帖：{/title}{content}'.$row['msg'].'{/content}{/quote}';
        $msg = addslashes($qmsg).$msg;
    }
    $ischeck = ($cfg_feedbackcheck=='Y' ? 0 : 1);
    $arctitle = addslashes($title);
    $inquery = "INSERT INTO `#@__feedback`(`aid`,`typeid`,`username`,`arctitle`,`ip`,`ischeck`,`dtime`, `mid`,`bad`,`good`,`ftype`,`face`,`msg`)
                   VALUES ('$aid','$typeid','$username','$arctitle','$ip','$ischeck','$dtime', '{$cfg_ml->M_ID}','0','0','$feedbacktype','$face','$msg'); ";
    $rs = $dsql->ExecuteNoneQuery($inquery);
    if( !$rs )
    {
            echo "<font color='red'>发表评论出错了！</font>";
            //echo $dslq->GetError();
            exit();
    }
    $newid = $dsql->GetLastID();
  //给文章评分
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
    //给用户增加积分
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
        $dsql->ExecuteNoneQuery("UPDATE `#@__member` set scores=scores+{$cfg_sendfb_scores} WHERE mid='{$cfg_ml->M_ID}' ");
        $row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__feedback` WHERE `mid`='".$cfg_ml->M_ID."'");
        $dsql->ExecuteNoneQuery("UPDATE `#@__member_tj` SET `feedback`='$row[nums]' WHERE `mid`='".$cfg_ml->M_ID."'");
    }
    $_SESSION['sedtime'] = time();
    if($ischeck==0)
    {
        echo '<font color="red">成功发表评论，但需审核后才会显示你的评论!</font>';
        exit();
    }
    else
    {
        $spaceurl = '#';
        if($cfg_ml->M_ID > 0) $spaceurl = "{$cfg_memberurl}/index.php?uid=".urlencode($cfg_ml->M_LoginID);
        $id = $newid;
        $msg = stripslashes($msg);
        $msg = str_replace('<', '&lt;', $msg);
        $msg = str_replace('>', '&gt;', $msg);
        $msg = Quote_replace($msg);
        if($feedbacktype=='bad') $bgimg = 'cmt-bad.gif';
        else if($feedbacktype=='good') $bgimg = 'cmt-good.gif';
        else $bgimg = 'cmt-neu.gif';
        global $dsql, $aid, $pagesize, $cfg_templeturl;
        if($cfg_ml->M_ID==""){
             $mface=$cfg_cmspath."/member/templets/images/dfboy.png";
        } else {
          $row = $dsql->GetOne("SELECT face,sex FROM `#@__member` WHERE mid={$cfg_ml->M_ID} ");
            if(empty($row['face']))
            {
              if($row['sex']=="女") $mface=$cfg_cmspath."/member/templets/images/dfgirl.png";
              else $mface=$cfg_cmspath."/member/templets/images/dfboy.png";
            }
        }
?>
	<div class='decmt-box2'> <ul>
     <li>
      <a href='<?php echo $spaceurl; ?>' class='plpic'><img src='<?php echo $mface;?>'  height='40' width='40'/></a>
      <span class="title"><a href="<?php echo $spaceurl; ?>"><?php echo $username; ?></a></span>
      <div class="comment_act"><span class="fr"><span id='goodfb<?php echo $id; ?>'>
				<a href='#goodfb<?php echo $id; ?>' onclick="postBadGood('goodfb',<?php echo $id; ?>);">支持</a>[0]
			</span>
			<span id='badfb<?php echo $id; ?>'>
				<a href='#badfb<?php echo $id; ?>' onclick="postBadGood('badfb',<?php echo $id; ?>);">反对</a>[0]
			</span>
			<span class='quote'>
				<a href='/plus/feedback.php?aid=<?php echo $id; ?>&fid=<?php echo $id; ?>&action=quote'">[引用]</a>
			</span></span><?php echo GetDateMk($dtime); ?>发表</div>			
     <p><?php echo $msg; ?><img src='<?php echo $cfg_templeturl; ?>/default/images/mood/ico-mood-<?php echo $face; ?>.gif'/></p>
  </li>
 </ul>
</div>
	<br style='clear:both' />
<?php
    }
    exit();
}

/**
 *  读取列表内容
 *
 * @param     int  $page  页码
 * @return    string
 */
function GetList($page=1)
{
    global $dsql, $aid, $pagesize, $cfg_templeturl,$cfg_cmspath;
    $querystring = "SELECT fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores,mb.sex FROM `#@__feedback` fb
                 LEFT JOIN `#@__member` mb on mb.mid = fb.mid WHERE fb.aid='$aid' AND fb.ischeck='1' ORDER BY fb.id DESC";
    $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__feedback` WHERE aid='$aid' AND ischeck='1' ");
    $totalcount = (empty($row['dd']) ? 0 : $row['dd']);
    $startNum = $pagesize * ($page-1);
    if($startNum > $totalcount)
    {
        echo "参数错误！";
        return $totalcount;
    }
    $dsql->Execute('fb', $querystring." LIMIT $startNum, $pagesize ");
    while($fields = $dsql->GetArray('fb'))
    {
        if($fields['userid']!='') $spaceurl = $GLOBALS['cfg_memberurl'].'/index.php?uid='.$fields['userid'];
        else $spaceurl = '#';
        if($fields['username']=='匿名') $spaceurl = '#';
        $fields['bgimg'] = 'cmt-neu.gif';
        $fields['ftypetitle'] = '该用户表示中立';
        if($fields['ftype']=='bad')
        {
            $fields['bgimg'] = 'cmt-bad.gif';
            $fields['ftypetitle'] = '该用户表示差评';
        }
        else if($fields['ftype']=='good')
        {
            $fields['bgimg'] = 'cmt-good.gif';
            $fields['ftypetitle'] = '该用户表示好评';
        }
        if(empty($fields['mface']))
        {
            if($fields['sex']=="女") $fields['mface']=$cfg_cmspath."/member/templets/images/dfgirl.png";
            else $fields['mface']=$cfg_cmspath."/member/templets/images/dfboy.png";
        }
        $fields['face'] = empty($fields['face']) ? 6 : $fields['face'];
        $fields['msg'] = str_replace('<', '&lt;', $fields['msg']);
        $fields['msg'] = str_replace('>', '&gt;', $fields['msg']);
        $fields['msg'] = Quote_replace($fields['msg']);
        extract($fields, EXTR_OVERWRITE);
?>
<div class="decmt-box2">
   <ul>
     <li>
      <a href='<?php echo $spaceurl; ?>' class='plpic'><img src='<?php echo $mface;?>'  height='40' width='40'/></a>
      <span class="title"><a href="<?php echo $spaceurl; ?>"><?php echo $username; ?></a></span>
      <div class="comment_act"><span class="fr"><span id='goodfb<?php echo $id; ?>'>
				<a href='#goodfb<?php echo $id; ?>' onclick="postBadGood('goodfb',<?php echo $id; ?>);">支持</a>[<?php echo $good; ?>]
			</span>
			<span id='badfb<?php echo $id; ?>'>
				<a href='#badfb<?php echo $id; ?>' onclick="postBadGood('badfb',<?php echo $id; ?>);">反对</a>[<?php echo $bad; ?>]
			</span>
			<span class='quote'>
				<a href='/plus/feedback.php?aid=<?php echo $id; ?>&fid=<?php echo $id; ?>&action=quote'">[引用]</a>
			</span></span><?php echo GetDateMk($dtime); ?>发表</div>			
     <p><?php echo $msg; ?><img src='<?php echo $cfg_templeturl; ?>/default/images/mood/ico-mood-<?php echo $face; ?>.gif'/></p>
  </li>
 </ul>
</div>
<?php
    }
    return $totalcount;            
}

/**
 *  获取分页列表
 *
 * @param     int  $pagesize  显示条数
 * @param     int  $totalcount  总数
 * @return    string
 */
function GetPageList($pagesize, $totalcount)
{
    global $page;
    $curpage = empty($page) ? 1 : intval($page);
    $allpage = ceil($totalcount / $pagesize);
    if($allpage < 2) 
    {
        echo '';
        return ;
    }
    echo "<div id='commetpages'>";
    echo "<span>总: {$allpage} 页/{$totalcount} 条评论</span> ";
    $listsize = 5;
    $total_list = $listsize * 2 + 1;
    $totalpage = $allpage;
    $listdd = '';
    if($curpage-1 > 0 )
    {
        echo "<a href='#commettop' onclick='LoadCommets(".($curpage-1).");'>上一页</a> ";
    }
    if($curpage >= $total_list)
    {
        $j = $curpage - $listsize;
        $total_list = $curpage + $listsize;
        if($total_list > $totalpage)
        {
            $total_list = $totalpage;
        }
    }
    else
    {
        $j = 1;
        if($total_list > $totalpage) $total_list = $totalpage;
    }
    for($j; $j <= $total_list; $j++)
    {
        echo ($j==$curpage ? "<strong>$j</strong> " : "<a href='#commettop' onclick='LoadCommets($j);'>{$j}</a> ");
    }
    if($curpage+1 <= $totalpage )
    {
        echo "<a href='#commettop' onclick='LoadCommets(".($curpage+1).");'>下一页</a> ";
    }
    echo "</div>";
}