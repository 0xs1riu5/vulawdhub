<?php
/**
 * 我的收藏夹
 * 
 * @version        $Id: mystow.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
$type = empty($type)? "sys" : trim($type);
$tpl = '';
$menutype = 'mydede';
$rank = empty($rank)? "" : $rank;
if($rank == 'top'){
    $sql = "SELECT s.*,COUNT(s.aid) AS num,t.*  from #@__member_stow AS s LEFT JOIN `#@__member_stowtype` AS t on t.stowname=s.type where s.type='$type' group by s.aid order by num desc";
    $tpl = 'stowtop';
}else{
    $sql = "SELECT s.*,t.* FROM `#@__member_stow` AS s left join `#@__member_stowtype` AS t on t.stowname=s.type  where s.mid='".$cfg_ml->M_ID."' AND s.type='$type' order by s.id desc";
    $tpl = 'mystow';
}

$dsql->Execute('nn','SELECT indexname,stowname FROM `#@__member_stowtype`');
while($row = $dsql->GetArray('nn'))
{
    $rows[]=$row;
}

$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetTemplate(DEDEMEMBER."/templets/$tpl.htm");
$dlist->SetSource($sql);
$dlist->Display();