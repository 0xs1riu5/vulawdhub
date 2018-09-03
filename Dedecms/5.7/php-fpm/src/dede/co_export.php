<?php
/**
 * 导出采集规则
 *
 * @version        $Id: co_edit_text.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('co_Export');
if(empty($dopost)) $dopost = '';

if($dopost!='done')
{
    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    $totalcc = $channelid = $usemore = 0;
    if(!empty($nid))
    {
        $mrow = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__co_htmls` WHERE nid='$nid' AND isdown='1' ");
        $totalcc = $mrow['dd'];
        $rrow = $dsql->GetOne("SELECT channelid,usemore FROM `#@__co_note` WHERE nid='$nid' ");
        $channelid = $rrow['channelid'];
        $usemore = $rrow['usemore'];
    }
    else
    {
        $mrow = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__co_htmls` WHERE isdown='1' ");
        $totalcc = $mrow['dd'];
    }
    include DedeInclude("templets/co_export.htm");
    exit();
}
else
{
    require_once(DEDEINC.'/dedecollection.class.php');
    $channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 0;
    $typeid = isset($typeid) && is_numeric($typeid) ? $typeid : 0;
    $pageno = isset($pageno) && is_numeric($pageno) ? $pageno : 1;
    $startid = isset($startid) && is_numeric($startid) ? $startid : 0;
    $endid = isset($endid) && is_numeric($endid) ? $endid : 0;
    
    if(!isset($makehtml)) $makehtml = 0;
    if(!isset($onlytitle)) $onlytitle = 0;
    if(!isset($usetitle)) $usetitle = 0;
    if(!isset($autotype)) $autotype = 0;

    $co = new DedeCollection();
    $co->LoadNote($nid);
    $orderway = (($co->noteInfos['cosort']=='desc' || $co->noteInfos['cosort']=='asc') ? $co->noteInfos['cosort'] : 'desc');
    if($channelid==0 && $typeid==0)
    {
        ShowMsg('请指定默认导出栏目或频道ID！','javascript:;');
        exit();
    }
    if($channelid==0)
    {
        $row = $dsql->GetOne("SELECT ch.* FROM `#@__arctype` tp LEFT JOIN `#@__channeltype` ch ON ch.id=tp.channeltype WHERE tp.id='$typeid'; ");
    }
    else
    {
        $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$channelid'; ");
    }
    if(!is_array($row))
    {
        echo "找不到频道内容模型信息，无法完成操作！";
        exit();
    }

    //分析规则，并生成临时的SQL语句
    $channelid = $row['id'];
    $maintable = $row['maintable'];
    $addtable = $row['addtable'];
    if(empty($maintable)) $maintable = '#@__archives';
    if(empty($addtable))
    {
        echo "找不主表配置信息，无法完成操作！";
        exit();
    }
    $adminid = $cuserLogin->getUserID();

    //微索引表
    $indexSqlTemplate = "INSERT INTO `#@__arctiny`(`arcrank`,`typeid`,`channel`,`senddate`,`sortrank`) VALUES ('$arcrank','@typeid@' ,'$channelid','@senddate@', '@sortrank@'); ";

    //基本信息主表
    $mainSqlTemplate  = "INSERT INTO `$maintable`(id,typeid,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,color,writer,source,litpic,pubdate,senddate,mid,description,keywords)
               VALUES ('@aid@','@typeid@','@sortrank@','@flag@','0','$channelid','$arcrank','0','0','@title@','','','@writer@','@source@','@litpic@','@pubdate@','@senddate@','$adminid','@description@','@keywords@'); ";

    //生成附加表插入的SQL语句
    $inadd_f = $inadd_v = '';
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace('field','<','>');
    $dtp->LoadString($row['fieldset']);
    foreach($dtp->CTags as $ctag)
    {
        $tname = $ctag->GetTagName();
        $inadd_f .= ",`$tname`";
        $notsend = $ctag->GetAtt('notsend');
        $fieldtype = $ctag->GetAtt('type');
        if($notsend==1)
        {
            //对不同类型设置默认值
            if($ctag->GetAtt('default')!='')
            {
                $dfvalue = $ctag->GetAtt('default');
            }
            else if($fieldtype=='int' || $fieldtype=='float' || $fieldtype=='number')
            {
                $dfvalue = '0';
            }
            else if($fieldtype=='dtime')
            {
                $dfvalue = time();
            }
            else
            {
                $dfvalue = '';
            }
            $inadd_v .= ",'$dfvalue'";
        }
        else
        {
            $inadd_v .= ",'@$tname@'";
        }
    }
    $addSqlTemplate = "INSERT INTO `{$addtable}`(`aid`,`typeid`{$inadd_f}) Values('@aid@','@typeid@'{$inadd_v})";

    //导出数据的SQL操作
    $dtp = new DedeTagParse();
    $totalpage = $totalcc / $pagesize;
    $startdd = ($pageno-1) * $pagesize;
    if(!empty($nid))
    {
        $dsql->SetQuery("SELECT * FROM `#@__co_htmls` WHERE nid='$nid' AND isdown='1' ORDER BY aid $orderway LIMIT $startdd,$pagesize");
    }
    else
    {
        $dsql->SetQuery("SELECT * FROM `#@__co_htmls` WHERE isdown='1' ORDER BY aid $orderway LIMIT $startdd,$pagesize");
    }
    $dsql->Execute();
    while($row = $dsql->GetObject())
    {
        if(trim($row->result=='')) continue;

        //$addSqlTemplate,$mainSqlTemplate,$indexSqlTemplate
        $ntypeid = ($autotype==1 && $row->typeid != 0) ? $row->typeid : $typeid;
        $indexSql = str_replace('@typeid@', $ntypeid, $indexSqlTemplate);
        $mainSql = str_replace('@typeid@', $ntypeid, $mainSqlTemplate);
        $addSql = str_replace('@typeid@', $ntypeid, $addSqlTemplate);
        $dtp->LoadString($row->result);
        $exid = $row->aid;
        if(!is_array($dtp->CTags)) continue;

        //获取时间和标题
        $pubdate = $sortrank = time();
        $title = $row->title;
        $litpic = '';
        foreach ($dtp->CTags as $ctag)
        {
            $itemName = $ctag->GetAtt('name');
            if($itemName == 'title' && $usetitle==0)
            {
                $title = trim($ctag->GetInnerText());
                if($title=='')
                {
                    $title = $row->title;
                }
            }
            else if($itemName == 'pubdate')
            {
                $pubdate = trim($ctag->GetInnerText());
                if(preg_match("#[^0-9]#", $pubdate))
                {
                    $pubdate = $sortrank = GetMkTime($pubdate);
                }
                else
                {
                    $pubdate = $sortrank = time();
                }
            }
            else if($itemName == 'litpic')
            {
                $litpic = trim($ctag->GetInnerText());
            }
        }

        //检测重复标题
        $title = addslashes($title);
        if($onlytitle)
        {
            $testrow = $dsql->GetOne("SELECT COUNT(ID) AS dd FROM `$maintable` WHERE title LIKE '$title'");
            if($testrow['dd']>0)
            {
                echo "数据库已存在标题为: {$title} 的文档，程序阻止了此本条内容导入<br />\r\n";
                continue;
            }
        }

        //替换固定的项目
        $senddate = time();
        $flag = '';
        if($litpic!='') $flag = 'p';

        //随机推荐
        if($randcc>0)
        {
            $rflag = mt_rand(1, $randcc);
            if($rflag==$randcc)
            {
                $flag = ($flag=='' ? 'c' : $flag.',c');
            }
        }
        $indexSql = str_replace('@senddate@', $senddate, $indexSql);
        $indexSql = str_replace('@sortrank@', $sortrank, $indexSql);
        $mainSql = str_replace('@flag@', $flag, $mainSql);
        $mainSql = str_replace('@sortrank@', $sortrank, $mainSql);
        $mainSql = str_replace('@pubdate@', $pubdate, $mainSql);
        $mainSql = str_replace('@senddate@', $senddate, $mainSql);
        $mainSql = str_replace('@title@', cn_substr($title, 60), $mainSql);
        $addSql = str_replace('@sortrank@', $sortrank, $addSql);
        $addSql = str_replace('@senddate@', $senddate, $addSql);

        //替换模型里的其它字段
        foreach($dtp->CTags as $ctag)
        {
            if($ctag->GetName()!='field')
            {
                continue;
            }
            $itemname = $ctag->GetAtt('name');
            $itemvalue = addslashes(trim($ctag->GetInnerText()));
            $mainSql = str_replace("@$itemname@", $itemvalue, $mainSql);
            $addSql = str_replace("@$itemname@", $itemvalue, $addSql);
        }

        //插入数据库
        $rs = $dsql->ExecuteNoneQuery($indexSql);
        if($rs)
        {
            $aid = $dsql->GetLastID();
            $mainSql = str_replace('@aid@', $aid, $mainSql);
            $addSql = str_replace('@aid@', $aid, $addSql);
            $mainSql = preg_replace("#@([a-z0-9]{1,})@#", '', $mainSql);
            $addSql = preg_replace("#@([a-z0-9]{1,})@#", '', $addSql);
            $rs = $dsql->ExecuteNoneQuery($mainSql);
            if(!$rs)
            {
                echo "导入 '$title' 时错误：".$dsql->GetError()."<br />";
                $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$aid' ");
            }
            else
            {
                $rs = $dsql->ExecuteNoneQuery($addSql);
                if(!$rs)
                {
                    echo "导入 '$title' 时错误：".$dsql->GetError()."<br />";
                    $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE id='$aid' ");
                    $dsql->ExecuteNoneQuery("DELETE FROM `$maintable` WHERE id='$aid' ");
                }
            }
        }
        $dsql->ExecuteNoneQuery("UPDATE `#@__co_htmls` SET isexport=1 WHERE aid='$exid' ");
    }

    //检测是否完成或后续操作
    if($totalpage <= $pageno)
    {
        if($channelid>0 && $makehtml==1)
        {
            if( $autotype==0 && !empty($nid) )
            {
                $mhtml = "makehtml_archives_action.php?typeid=$typeid&startid=$startid&endid=$endid&pagesize=20";
                ShowMsg("完成数据导入，准备生成文档HTML...",$mhtml);
                exit();
            }
            else
            {
                ShowMsg("完成所有数据导入，请手工更新HTML！","javascript:;");
                exit();
            }
        }
        else
        {
            ShowMsg("完成所有数据导入！","javascript:;");
            exit();
        }
    }
    else
    {
        if($totalpage>0)
        {
            $rs = substr(($pageno / $totalpage * 100), 0, 2);
        }
        else
        {
            $rs = 100;
        }
        $pageno++;
        $gourl = "co_export.php?dopost=done&nid=$nid&totalcc=$totalcc&channelid=$channelid&pageno=$pageno";
        $gourl .= "&nid=$nid&typeid=$typeid&autotype=$autotype&arcrank=$arcrank&pagesize=$pagesize&randcc=$randcc";
        $gourl .= "&startid=$startid&endid=$endid&onlytitle=$onlytitle&usetitle=$usetitle&makehtml=$makehtml";
        ShowMsg("完成 {$rs}% 导入，继续执行操作...",$gourl,'',500);
        exit();
    }
}