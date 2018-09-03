<?php
/**
 * 生成点卡
 *
 * @version        $Id: cards_make.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Card');
if(empty($dopost)) $dopost = '';
if($dopost == '') include(DEDEADMIN."/templets/cards_make.htm");

//生成点卡
elseif($dopost == 'make')
{
    $row = $dsql->GetOne("SELECT * FROM #@__moneycard_record ORDER BY aid DESC");
    !is_array($row) ? $startid=100000 : $startid=$row['aid']+100000;
    $row = $dsql->GetOne("SELECT * FROM #@__moneycard_type WHERE tid='$cardtype'");
    $money = $row['money'];
    $num = $row['num'];
    $mtime = time();
    $utime = 0;
    $ctid = $cardtype;
    $startid++;
    $endid = $startid + $mnum;

    header("Content-Type: text/html; charset={$cfg_soft_lang}");

    for(;$startid<$endid;$startid++)
    {
        $cardid = $snprefix.$startid.'-';
        for($p=0;$p<$pwdgr;$p++)
        {
            for($i=0; $i < $pwdlen; $i++)
            {
                if($ctype==1)
                {
                    $c = mt_rand(49,57); $c = chr($c);
                }
                else
                {
                    $c = mt_rand(65,90);
                    if($c==79)
                    {
                        $c = 'M';
                    }
                    else
                    {
                        $c = chr($c);
                    }
                }
                $cardid .= $c;
            }
            if($p<$pwdgr-1)
            {
                $cardid .= '-';
            }
        }
        $inquery = "INSERT INTO #@__moneycard_record(ctid,cardid,uid,isexp,mtime,utime,money,num)
              VALUES('$ctid','$cardid','0','0','$mtime','$utime','$money','$num'); ";
        $dsql->ExecuteNoneQuery($inquery);
        echo "成功生成点卡：{$cardid}<br/>";
    }
    echo "成功生成 {$mnum} 个点卡！";
}