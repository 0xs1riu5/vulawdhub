<?php
/**
 *
 * 文档digg处理ajax文件
 *
 * @version        $Id: digg_ajax.php 1 21:17 2010年7月8日Z tianya $
 * @package        DedeCMS.Plus
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");

$action = isset($action) ? trim($action) : '';
$id = empty($id)? 0 : intval(preg_replace("/[^\d]/",'', $id));

if($id < 1)
{
    exit();
}
$maintable = '#@__archives';
if($action == 'good')
{
    $dsql->ExecuteNoneQuery("UPDATE `$maintable` SET scores = scores + {$cfg_caicai_add},goodpost=goodpost+1,lastpost=".time()." WHERE id='$id'");
}
else if($action=='bad')
{
    $dsql->ExecuteNoneQuery("UPDATE `$maintable` SET scores = scores - {$cfg_caicai_sub},badpost=badpost+1,lastpost=".time()." WHERE id='$id'");
}
$digg = '';
$row = $dsql->GetOne("SELECT goodpost,badpost,scores FROM `$maintable` WHERE id='$id' ");
if(!is_array($row)) exit();

if($row['goodpost'] + $row['badpost'] == 0)
{
    $row['goodper'] = $row['badper'] = 0;
}
else
{
    $row['goodper'] = number_format($row['goodpost'] / ($row['goodpost'] + $row['badpost']), 3) * 100;
    $row['badper'] = 100 - $row['goodper'];
}

if(empty($formurl)) $formurl = '';
if($formurl=='caicai')
{
    if($action == 'good') $digg = $row['goodpost'];
    if($action == 'bad') $digg  = $row['badpost'];
}
else
{
    $row['goodper'] = trim(sprintf("%4.2f", $row['goodper']));
    $row['badper'] = trim(sprintf("%4.2f", $row['badper']));
    $digg = '<div class="diggbox digg_good" onmousemove="this.style.backgroundPosition=\'left bottom\';" onmouseout="this.style.backgroundPosition=\'left top\';" onclick="postDigg(\'good\','.$id.')">
            <div class="digg_act">顶一下</div>
            <div class="digg_num">('.$row['goodpost'].')</div>
            <div class="digg_percent">
                <div class="digg_percent_bar"><span style="width:'.$row['goodper'].'%"></span></div>
                <div class="digg_percent_num">'.$row['goodper'].'%</div>
            </div>
        </div>
        <div class="diggbox digg_bad" onmousemove="this.style.backgroundPosition=\'right bottom\';" onmouseout="this.style.backgroundPosition=\'right top\';" onclick="postDigg(\'bad\','.$id.')">
            <div class="digg_act">踩一下</div>
            <div class="digg_num">('.$row['badpost'].')</div>
            <div class="digg_percent">
                <div class="digg_percent_bar"><span style="width:'.$row['badper'].'%"></span></div>
                <div class="digg_percent_num">'.$row['badper'].'%</div>
            </div>
        </div>';
}
AjaxHead();
echo $digg;
exit();
