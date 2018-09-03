<?php
/**
 * 交易支付
 * 
 * @version        $Id: mypay.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'op';
require_once(DEDEINC.'/datalistcp.class.php');
setcookie('ENV_GOBACK_URL', GetCurUrl(), time()+3600, '/');
if(!isset($dopost)) $dopost = '';

if($dopost=='')
{
    $query = "Select * From `#@__member_operation` where mid='".$cfg_ml->M_ID."' And product='archive' order by aid desc";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER.'/templets/mypay.htm');
    $dlist->SetSource($query);
    $dlist->Display();
}
elseif($dopost=='del')
{
    $ids = preg_replace("#[^0-9,]#", "", $ids);
    $query = "Delete From `#@__member_operation` where aid in($ids) And mid='{$cfg_ml->M_ID}' And product='archive'";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg("成功删除指定的交易记录!","mypay.php");
    exit();
}