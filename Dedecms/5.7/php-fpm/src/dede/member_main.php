<?php
/**
 * 会员管理
 *
 * @version        $Id: member_main.php 1 10:49 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_List');
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(!isset($sex)) $sex = '';
if(!isset($mtype)) $mtype = '';
if(!isset($spacesta)) $spacesta = -10;
if(!isset($matt)) $matt = 10;

if(!isset($keyword)) $keyword = '';
else $keyword = trim(FilterSearch($keyword));

$mtypeform = empty($mtype) ? "<option value=''>类型</option>\r\n" : "<option value='$mtype'>$mtype</option>\r\n";
$sexform = empty($sex) ? "<option value=''>性别</option>\r\n" : "<option value='$sex'>$sex</option>\r\n";
$sortkey = empty($sortkey) ? 'mid' : preg_replace("#[^a-z]#i",'',$sortkey);

$staArr = array(-2=>'限制用户(禁言)', -1=>'未通过审核', 0=>'审核通过，提示填写完整信息', 1=>'没填写详细资料', 2=>'正常使用状态');
$staArrmatt = array(1=>'被推荐', 0=>'非普通 ' );
$MemberTypes = array();
$dsql->SetQuery("Select rank,membername From `#@__arcrank` where rank>0 ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
    $MemberTypes[$row->rank] = $row->membername;
}

if($sortkey=='mid')
{
    $sortform = "<option value='mid'>mid/注册时间</option>\r\n";
}
else if($sortkey=='rank')
{
    $sortform = "<option value='rank'>会员等级</option>\r\n";
}
else if($sortkey=='money')
{
    $sortform = "<option value='money'>会员金币</option>\r\n";
}
else if($sortkey=='scores')
{
    $sortform = "<option value='scores'>会员积分</option>\r\n";
}
else
{
    $sortform = "<option value='logintime'>登录时间</option>\r\n";
}

$wheres[] = " (userid LIKE '%$keyword%' OR uname LIKE '%$keyword%' OR email LIKE '%$keyword%') ";

if($sex   != '')
{
    $wheres[] = " sex LIKE '$sex' ";
}

if($mtype != '')
{
    $wheres[] = " mtype LIKE '$mtype' ";
}

if($spacesta != -10)
{
    $wheres[] = " spacesta = '$spacesta' ";
}

if($matt != 10)
{
    $wheres[] = " matt= '$matt' ";
}

$whereSql = join(' AND ',$wheres);
if($whereSql!='')
{
    $whereSql = ' WHERE '.$whereSql;
}
$dsql->SetQuery("SELECT name FROM `#@__member_model`");
$dsql->Execute();
while($row = $dsql->GetArray())
{
    $MemberModels[] = $row;
}
$sql  = "SELECT * FROM `#@__member` $whereSql ORDER BY $sortkey DESC ";
$dlist = new DataListCP();
$dlist->SetParameter('sex',$sex);
$dlist->SetParameter('spacesta',$spacesta);
$dlist->SetParameter('matt',$matt);
$dlist->SetParameter('mtype',$mtype);
$dlist->SetParameter('sortkey',$sortkey);
$dlist->SetParameter('keyword',$keyword);
$dlist->SetTemplet(DEDEADMIN."/templets/member_main.htm");
$dlist->SetSource($sql);
$dlist->display();

function GetMemberName($rank,$mt)
{
    global $MemberTypes;
    if(isset($MemberTypes[$rank]))
    {
        if($mt=='ut') return " <font color='red'>待升级：".$MemberTypes[$rank]."</font>";
        else return $MemberTypes[$rank];
    } else {
        if($mt=='ut') return '';
        else return $mt;
    }
}

function GetMAtt($m)
{
    if($m<1) return '';
    else if($m==10) return "&nbsp;<font color='red'>[管理员]</font>";
    else return "&nbsp;<img src='images/adminuserico.gif' wmidth='16' height='15'><font color='red'>[荐]</font>";
}