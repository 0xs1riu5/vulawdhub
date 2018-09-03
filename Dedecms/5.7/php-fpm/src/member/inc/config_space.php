<?php
/**
 * 空间配置
 * 
 * @version        $Id: config_space.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
if(!defined('DEDEMEMBER')) exit('dedecms');

//检查是否开放会员功能
if($cfg_mb_open=='N')
{
    ShowMsg("系统关闭了会员功能，因此你无法访问此页面！","javascript:;");
    exit();
}

//对uid进行过滤
if(preg_match("/'/", $uid)){
   ShowMsg("您的用户名中含有非法字符！", "-1");
   exit();
}else{
   $uid=RemoveXSS($uid);
}


$_vars = GetUserSpaceInfos();
$_vars['bloglinks'] = $_vars['curtitle'] = '';

//---------------------------
//用户权限检查
//被禁言用户
if($_vars['spacesta'] == -2)
{
    ShowMsg("用户：{$_vars['userid']} 被禁言，因此个人空间禁止访问！", "-1");
    exit();
}
//未审核用户
if($_vars['spacesta'] < 0)
{
    ShowMsg("用户：{$_vars['userid']} 的资料尚未通过审核，因此空间禁止访问！", "-1");
    exit();
}
//是否禁止了管理员空间的访问
if( !isset($_vars['matt']) ) $_vars['matt'] = 0;
if($_vars['matt'] == 10 && $cfg_mb_adminlock=='Y' 
&& !(isset($cfg_ml->fields) && $cfg_ml->fields['matt']==10))
{
    ShowMsg('系统设置了禁止访问管理员的个人空间！', '-1');
    exit();
}

//---------------------------
//默认风格
if($_vars['spacestyle']=='')
{
    if($_vars['mtype']=='个人') {
        $_vars['spacestyle'] = 'person';
    }
    else if($_vars['mtype']=='企业') {
        $_vars['spacestyle'] = 'company';
    }
    else {
        $_vars['spacestyle'] = 'person';
    }
}
//找不到指定样式文件夹的时候使用person为默认
if(!is_dir(DEDEMEMBER.'/space/'.$_vars['spacestyle']))
{
    $_vars['spacestyle'] = 'person';
}

//获取分类数据
$mtypearr = array();
$dsql->Execute('mty', "select * from `#@__mtypes` where mid='".$_vars['mid']."'");
while($row = $dsql->GetArray('mty'))
{
    $mtypearr[] = $row;
}

//获取栏目导航数据
$_vars['bloglinks'] = array();
$query = "SELECT tp.channeltype,ch.typename FROM `#@__arctype` tp 
      LEFT JOIN `#@__channeltype` ch on ch.id=tp.channeltype 
      WHERE (ch.usertype='' OR ch.usertype LIKE '{$_vars['mtype']}') And tp.channeltype<>1 And tp.issend=1 And tp.ishidden=0 GROUP BY tp.channeltype ORDER BY ABS(tp.channeltype) asc";
$dsql->Execute('ctc', $query);
while( $row = $dsql->GetArray('ctc') )
{
    $_vars['bloglinks'][$row['channeltype']] = $row['typename'];
}


//获取企业用户私有数据
if($_vars['mtype']=='企业')
{
    require_once(DEDEINC.'/enums.func.php');
    $query = "SELECT * FROM `#@__member_company` WHERE mid='".$_vars['mid']."'";
    $company = $db->GetOne($query);
    $company['vocation'] = GetEnumsValue('vocation', $company['vocation']);
    $company['cosize'] = GetEnumsValue('cosize', $company['cosize']);
    $tmpplace = GetEnumsTypes($company['place']);
    $provinceid = $tmpplace['top'];
    $provincename = (isset($em_nativeplaces[$provinceid]) ?  $em_nativeplaces[$provinceid] : '');
    $cityname = (isset($em_nativeplaces[$tmpplace['son']]) ? $em_nativeplaces[$tmpplace['son']] : '');
    $company['place'] = $provincename.' - '.$cityname;
    $_vars = array_merge($company, $_vars);
    if($action == 'infos') $action = 'introduce';
    $_vars['comface'] = empty($_vars['comface']) ? 'images/comface.png' : $_vars['comface'];
}

/**
 * 获取空间基本信息
 *
 * @return unknown
 */
function GetUserSpaceInfos()
{
    global $dsql,$uid,$cfg_memberurl;
    $_vars = array();
    $userid = preg_replace("#[\r\n\t \*%]#", '', $uid);
    $query = "SELECT m.mid,m.mtype,m.userid,m.uname,m.sex,m.rank,m.email,m.scores,
                            m.spacesta,m.face,m.logintime,
                            s.*,t.*,m.matt,r.membername,g.msg
                  From `#@__member` m
                  LEFT JOIN `#@__member_space` s on s.mid=m.mid
                  LEFT JOIN `#@__member_tj` t on t.mid=m.mid
                  LEFT JOIN `#@__arcrank` r on r.rank=m.rank
                  LEFT JOIN `#@__member_msg` g on g.mid=m.mid
                  where m.userid like '$uid' ORDER BY g.dtime DESC ";
    $_vars = $dsql->GetOne($query);
    if(!is_array($_vars))
    {
        ShowMsg("你访问的用户可能已经被删除！","javascript:;");
        exit();
    }
    if($_vars['face']=='')
    {
        $_vars['face']=($_vars['sex']=='女')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
    }
    $_vars['userid_e'] = urlencode($_vars['userid']);
    $_vars['userurl'] = $cfg_memberurl."/index.php?uid=".$_vars['userid_e'];
    if($_vars['membername']=='开放浏览') $_vars['membername'] = '限制会员';
    return $_vars;
}