<?php
/**
 * 会员类型
 *
 * @version        $Id: member_type.php 1 14:14 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Type');
if(empty($dopost)) $dopost = "";

//保存更改
if($dopost=="save")
{
    $startID = 1;
    $endID = $idend;
    for( ;$startID <= $endID; $startID++)
    {
        $query = '';
        $aid = ${'ID_'.$startID};
        $pname =   ${'pname_'.$startID};
        $rank =    ${'rank_'.$startID};
        $money =   ${'money_'.$startID};
        $exptime = ${'exptime_'.$startID};
        if(isset(${'check_'.$startID}))
        {
            if($pname!='')
            {
                $query = "UPDATE #@__member_type SET pname='$pname',money='$money',rank='$rank',exptime='$exptime' WHERE aid='$aid'";
            }
        }
        else
        {
            $query = "DELETE FROM #@__member_type WHERE aid='$aid' ";
        }
        if($query!='')
        {
            $dsql->ExecuteNoneQuery($query);
        }
    }

    //增加新记录
    if(isset($check_new) && $pname_new!='')
    {
        $query = "INSERT INTO #@__member_type(rank,pname,money,exptime) VALUES('{$rank_new}','{$pname_new}','{$money_new}','{$exptime_new}');";
        $dsql->ExecuteNoneQuery($query);
    }
    header("Content-Type: text/html; charset={$cfg_soft_lang}");
    echo "<script> alert('成功更新会员产品分类表！'); </script>";
}
$arcranks = array();
$dsql->SetQuery("SELECT * FROM #@__arcrank WHERE rank>10 ");
$dsql->Execute();
while($row=$dsql->GetArray())
{
    $arcranks[$row['rank']] = $row['membername'];
}

$times = array();
$times[7] = '一周';
$times[30] = '一个月';
$times[90] = '三个月';
$times[183] = '半年';
$times[366] = '一年';
$times[32767] = '终身';

require_once(DEDEADMIN."/templets/member_type.htm");