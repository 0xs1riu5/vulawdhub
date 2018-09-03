<?php
/**
 * 会员查看
 *
 * @version        $Id: member_view.php 1 14:15 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('member_Edit');
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? "member_main.php" : '';
$id = preg_replace("#[^0-9]#", "", $id);
$row = $dsql->GetOne("select  * from #@__member where mid='$id'");

$staArr = array(
    -10=>'等待验证邮件',
    -2=>'限制用户(禁言)',
    -1=>'未通过审核',
     0=>'审核通过，提示填写完整信息',
     1=>'没填写详细资料',
     2=>'正常使用状态'
);

//如果这个用户是管理员帐号，必须有足够权限的用户才能操作
if($row['matt']==10) CheckPurview('sys_User');

if($row['uptime']>0 && $row['exptime']>0)
{
    $mhasDay = $row['exptime'] - ceil((time() - $row['uptime'])/3600/24)+1;
} else {
    $mhasDay = 0;
}
include DedeInclude('templets/member_view.htm');