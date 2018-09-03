<?php
/**
 *
 * 栏目列表/频道动态页
 *
 * @version        $Id: list.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");

//$t1 = ExecTime();

$tid = (isset($tid) && is_numeric($tid) ? $tid : 0);

$channelid = (isset($channelid) && is_numeric($channelid) ? $channelid : 0);

if($tid==0 && $channelid==0) die(" Request Error! ");
if(isset($TotalResult)) $TotalResult = intval(preg_replace("/[^\d]/", '', $TotalResult));


//如果指定了内容模型ID但没有指定栏目ID，那么自动获得为这个内容模型的第一个顶级栏目作为频道默认栏目
if(!empty($channelid) && empty($tid))
{
    $tinfos = $dsql->GetOne("SELECT tp.id,ch.issystem FROM `#@__arctype` tp LEFT JOIN `#@__channeltype` ch ON ch.id=tp.channeltype WHERE tp.channeltype='$channelid' And tp.reid=0 order by sortrank asc");
    if(!is_array($tinfos)) die(" No catalogs in the channel! ");
    $tid = $tinfos['id'];
}
else
{
    $tinfos = $dsql->GetOne("SELECT ch.issystem FROM `#@__arctype` tp LEFT JOIN `#@__channeltype` ch ON ch.id=tp.channeltype WHERE tp.id='$tid' ");
}

if($tinfos['issystem']==-1)
{
    $nativeplace = ( (empty($nativeplace) || !is_numeric($nativeplace)) ? 0 : $nativeplace );
    $infotype = ( (empty($infotype) || !is_numeric($infotype)) ? 0 : $infotype );
    if(!empty($keyword)) $keyword = FilterSearch($keyword);
    $cArr = array();
    if(!empty($nativeplace)) $cArr['nativeplace'] = $nativeplace;
    if(!empty($infotype)) $cArr['infotype'] = $infotype;
    if(!empty($keyword)) $cArr['keyword'] = $keyword;
    include(DEDEINC."/arc.sglistview.class.php");
    $lv = new SgListView($tid,$cArr);
} else {
    include(DEDEINC."/arc.listview.class.php");
    $lv = new ListView($tid);
    //对设置了会员级别的栏目进行处理
    if(isset($lv->Fields['corank']) && $lv->Fields['corank'] > 0)
    {
        require_once(DEDEINC.'/memberlogin.class.php');
        $cfg_ml = new MemberLogin();
        if( $cfg_ml->M_Rank < $lv->Fields['corank'] )
        {
            $dsql->Execute('me' , "SELECT * FROM `#@__arcrank` ");
            while($row = $dsql->GetObject('me'))
            {
                $memberTypes[$row->rank] = $row->membername;
            }
            $memberTypes[0] = "游客或没权限会员";
            $msgtitle = "你没有权限浏览栏目：{$lv->Fields['typename']} ！";
            $moremsg = "这个栏目需要 <font color='red'>".$memberTypes[$lv->Fields['corank']]."</font> 才能访问，你目前是：<font color='red'>".$memberTypes[$cfg_ml->M_Rank]."</font> ！";
            include_once(DEDETEMPLATE.'/plus/view_msg_catalog.htm');
            exit();
        }
    }
}

if($lv->IsError) ParamError();

$lv->Display();
