<?php
/**
 * @version        $Id: flink_main.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if($cfg_mb_lit=='Y')
{
    ShowMsg("由于系统开启了精简版会员空间，你访问的功能不可用！","-1");
    exit();
}
if(empty($dopost)) $dopost = '';

if($dopost=="addnew")
{
    AjaxHead();
    $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__member_flink` WHERE mid='".$cfg_ml->M_ID."' ");
    if($row['dd']>=50)
    {
        echo "<font color='red'>增加网址失败，因为已经达到五十个网址的上限！</font>";
        GetLinkList($dsql);
        exit();
    }
    if(!preg_match("#^http:\/\/#",$url)) $url = "http://".HtmlReplace($url, 2);

    $title = HtmlReplace($title);
    $inquery = "INSERT INTO `#@__member_flink`(mid,title,url) VALUES(".$cfg_ml->M_ID.",'$title','$url'); ";
    $dsql->ExecuteNoneQuery($inquery);
    echo "<font color='red'>成功增加一链接！</font>";
    GetLinkList($dsql);
    exit();
}
else if($dopost=="del")
{
    AjaxHead();
    $aid = intval($aid);
    if(empty($aid)) exit("<font color='red'>参数错误！</font>");

    $dsql->ExecuteNoneQuery("DELETE FROM  `#@__member_flink` WHERE aid='$aid' AND mid='".$cfg_ml->M_ID."';");
    echo "<font color='red'>成功删除链接：{$aid}</font>";
    GetLinkList($dsql);
}
else if($dopost=="update")
{
    AjaxHead();
    $aid = intval($aid);
    if(!preg_match("#^http:\/\/#", $url)) $url = "http://".HtmlReplace($url,2);

    $title = HtmlReplace($title);
    $upquery = "UPDATE `#@__member_flink` SET title='$title',url='$url' WHERE aid='$aid' AND mid='".$cfg_ml->M_ID."'; ";
    $rs = $dsql->ExecuteNoneQuery($upquery);
    if($rs)
    {
        echo "<font color='red'>成功更新链接：{$title}</font>";
        GetLinkList($dsql);
        exit();
    }
    else
    {
        echo "<font color='red'>更新链接：{$title} 失败！</font>";
        GetLinkList($dsql);
        exit();
    }
}
else if($dopost=="reload")
{
    AjaxHead();
    GetLinkList($dsql);
    exit();
}
//默认界面
else
{
    require_once(dirname(__FILE__)."/templets/flink_main.htm");
    exit();
}

/**
 *  获取链接列表
 *
 * @access    public
 * @param     object  $dsql  数据库操作类
 * @return    string
 */
function GetLinkList(&$dsql)
{
    global $cfg_ml;
    $dsql->SetQuery("SELECT * FROM `#@__member_flink` WHERE mid='".$cfg_ml->M_ID."' ORDER BY aid DESC");
    $dsql->Execute();
    $j=0;
    while($row = $dsql->GetArray())
    {
        $j++;
        $line = "
<div class='item flink'>
  <div class='itemHead' >
    <div class='fRight'>
      <span class='itemDigg'><a href='#' onclick='UpdateType({$row['aid']})'>[更新]</a></span>
      <span class='itemManage'><a href='#' onclick='DelType({$row['aid']})'>[删除]</a></span>
    </div>
    <span class='itemTitle'>名称：<input name='title{$row['aid']}' type='text' id='title{$row['aid']}' value='{$row['title']}' class='intxt' /></span>
    <div class='mt5'>网址：<input name='url{$row['aid']}' type='text' id='url{$row['aid']}' value='{$row['url']}' class='intxt' /></div>
  </div>
</div>
<hr class='dotted' />";
        echo $line;
    }
    if($j==0)
    {
        echo "尚无任何链接";
    }
}