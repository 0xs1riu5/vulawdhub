<?php
/**
 * 检测重复文档
 *
 * @version        $Id: article_test_same.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
@set_time_limit(0);
CheckPurview('sys_ArcBatch');
if(empty($dopost)) $dopost = '';
if($dopost=='analyse')
{
    $arr = $dsql->getone("SELECT maintable FROM `#@__channeltype` WHERE id='$channelid' ");
    if(is_array($arr)) {
        $maintable = $arr['maintable'];
    } else {
        showmsg('频道id不正确，无法处理！','javascript:;');
        exit();
    }
    $dsql->SetQuery("SELECT COUNT(title) AS dd,title FROM `$maintable` WHERE channel='$channelid' GROUP BY title ORDER BY dd DESC LIMIT 0, $pagesize");
    $dsql->Execute();
    $allarc = 0;
    include DedeInclude('templets/article_result_same.htm');
    exit();
}
//删除选中的内容（只保留一条）
else if($dopost=='delsel')
{
    require_once(dirname(__FILE__)."/../include/typelink.class.php");
    require_once(dirname(__FILE__)."/inc/inc_batchup.php");
    
    if(empty($titles))
    {
        header("Content-Type: text/html; charset={$cfg_ver_lang}");
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$cfg_ver_lang}\">\r\n";
        echo "没有指定删除的文档！";
        exit();
    }
    
    $titless = split('`',$titles);

    if($channelid < -1) {
        $orderby = ($deltype=='delnew' ? " ORDER BY aid DESC " : " ORDER BY aid ASC ");
    } else {
        $orderby = ($deltype=='delnew' ? " ORDER BY id DESC " : " ORDER BY id ASC ");
    }
    
    $totalarc = 0;
    
    foreach($titless as $title)
    {
         $title = trim($title);
         $title = addslashes( $title=='' ? '' : urldecode($title) );
         if($channelid < -1) {
              $q1 = "SELECT aid as id,title FROM `$maintable` WHERE channel='$channelid' AND title='$title' $orderby ";
         }
         else {
              $q1 = "SELECT id,title FROM `$maintable` WHERE channel='$channelid' AND title='$title' $orderby ";
         }
         $dsql->SetQuery($q1);
         $dsql->Execute();
         $rownum = $dsql->GetTotalRow();
         if($rownum < 2) continue;
         $i = 1;
         while($row = $dsql->GetObject())
         {
               $i++;
               $naid = $row->id;
               $ntitle = $row->title;
               if($i > $rownum) continue;
               $totalarc++;
               DelArc($naid, 'OFF');
         }
    }
    $dsql->ExecuteNoneQuery(" OPTIMIZE TABLE `$maintable`; ");
    ShowMsg("一共删除了[{$totalarc}]篇重复的文档！","javascript:;");
    exit();
}

//向导页
$channelinfos = array();
$dsql->setquery("SELECT id,typename,maintable,addtable FROM `#@__channeltype` ");
$dsql->execute();
while($row = $dsql->getarray()) $channelinfos[] = $row;
include DedeInclude('templets/article_test_same.htm');