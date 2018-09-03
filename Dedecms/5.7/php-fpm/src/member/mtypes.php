<?php
/**
 * @version        $Id: mtypes.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckRank(0, 0);
$dopost = isset($dopost) ? trim($dopost) : '';
$menutype = 'config';
if($dopost == '')
{
    if(empty($channelid)) $channelid = 0;
    $channelid = intval($channelid);
    $mtypearr = array();
    $addquery = '';
    if(!empty($channelid)) $addquery = " AND channelid='$channelid' ";
    $query = "SELECT * FROM `#@__mtypes` WHERE mid='{$cfg_ml->M_ID}' $addquery ";
    $dsql->SetQuery($query);
    $dsql->Execute();
    while($row = $dsql->GetArray())
    {
        $mtypearr[] = $row;
    }
    $tpl = new DedeTemplate();
    $tpl->LoadTemplate(DEDEMEMBER.'/templets/mtypes.htm');
    $tpl->Display();
    exit();
}
elseif ($dopost == 'add')
{
    $mtypename = HtmlReplace(trim($mtypename));
    $channelid = intval($channelid);
    if(empty($channelid)) $channelid = 1;
    if(strlen($mtypename) > 40 || strlen($mtypename) < 2)
    {
        ShowMsg('分类名称必须大于两个字节少于40个字节', '-1');
        exit();
    }
    $query = "INSERT INTO `#@__mtypes`(mtypename, channelid, mid) VALUES ('$mtypename', '$channelid', '$cfg_ml->M_ID'); ";
    if($dsql->ExecuteNoneQuery($query))
    {
        ShowMsg('增加分类成功', 'mtypes.php');
    }
    else
    {
        ShowMsg('增加分类失败', '-1');
    }
    exit();
}
elseif ($dopost == 'save')
{
    if(isset($mtypeidarr) && is_array($mtypeidarr))
    {
        $delids = '0';
        $mtypeidarr = array_filter($mtypeidarr, 'is_numeric');
        foreach($mtypeidarr as $delid)
        {
            $delids .= ','.$delid;
            unset($mtypename[$delid]);
        }
        $query = "DELETE FROM `#@__mtypes` WHERE mtypeid IN ($delids) AND mid='$cfg_ml->M_ID';";
        $dsql->ExecNoneQuery($query);
    }
    foreach ($mtypename as $id => $name)
    {
        $name = HtmlReplace($name);
        $query = "UPDATE `#@__mtypes` SET mtypename='$name' WHERE mtypeid='$id' AND mid='$cfg_ml->M_ID'";
        $dsql->ExecuteNoneQuery($query);
    }
    ShowMsg('分类修改完成','mtypes.php');
}