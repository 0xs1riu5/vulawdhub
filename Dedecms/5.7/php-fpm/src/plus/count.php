<?php
/**
 *
 * 文档统计
 *
 * 如果想显示点击次数,请增加view参数,即把下面ＪＳ调用放到文档模板适当位置
 * <script src="{dede:field name='phpurl'/}/count.php?view=yes&aid={dede:field name='id'/}&mid={dede:field name='mid'/}" language="javascript"></script>
 * 普通计数器为
 * <script src="{dede:field name='phpurl'/}/count.php?aid={dede:field name='id'/}&mid={dede:field name='mid'/}" language="javascript"></script>
 *
 * @version        $Id: count.php 1 20:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
if(isset($aid)) $arcID = $aid;

$cid = empty($cid)? 1 : intval(preg_replace("/[^-\d]+[^\d]/",'', $cid));
$arcID = $aid = empty($arcID)? 0 : intval(preg_replace("/[^\d]/",'', $arcID));

$maintable = '#@__archives';$idtype='id';
if($aid==0) exit();

//获得频道模型ID
if($cid < 0)
{
    $row = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$cid' AND issystem='-1';");
    $maintable = empty($row['addtable'])? '' : $row['addtable'];
    $idtype='aid';
}
$mid = (isset($mid) && is_numeric($mid)) ? $mid : 0;

//UpdateStat();
if(!empty($maintable))
{
    $dsql->ExecuteNoneQuery(" UPDATE `{$maintable}` SET click=click+1 WHERE {$idtype}='$aid' ");
}
if(!empty($mid))
{
    $dsql->ExecuteNoneQuery(" UPDATE `#@__member_tj` SET pagecount=pagecount+1 WHERE mid='$mid' ");
}
if(!empty($view))
{
    $row = $dsql->GetOne(" SELECT click FROM `{$maintable}` WHERE {$idtype}='$aid' ");
    if(is_array($row))
    {
        echo "document.write('".$row['click']."');\r\n";
    }
}
exit();