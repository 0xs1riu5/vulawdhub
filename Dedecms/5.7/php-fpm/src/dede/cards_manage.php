<?php
/**
 * 点卡管理
 *
 * @version        $Id: cards_manage.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC.'/datalistcp.class.php');
$dopost=empty($dopost)? "" : $dopost;
if($dopost=="delete"){
    $ids = explode('`',$aids);
    $dquery = "";
    foreach($ids as $id){
        if($dquery=="") $dquery .= "aid='$id' ";
        else $dquery .= " OR aid='$id' ";
    }
    if($dquery!="") $dquery = " WHERE ".$dquery;
    $dsql->ExecuteNoneQuery("DELETE FROM #@__moneycard_record $dquery");
    ShowMsg("成功删除指定的记录！","cards_manage.php");
    exit();    
}else{
    $addsql = '';
    if(isset($isexp)) $addsql = " WHERE isexp='$isexp' ";
    
    $sql = "SELECT * FROM #@__moneycard_record $addsql ORDER BY aid DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 25; //设定每页显示记录数（默认25条）
    if(isset($isexp)) $dlist->SetParameter("isexp",$isexp);

    $dlist->dsql->SetQuery("SELECT * FROM #@__moneycard_type ");
    $dlist->dsql->Execute('ts');
    while($rw = $dlist->dsql->GetArray('ts'))
    {
        $TypeNames[$rw['tid']] = $rw['pname'];
    }
    $tplfile = DEDEADMIN."/templets/cards_manmage.htm";
    
    //这两句的顺序不能更换
    $dlist->SetTemplate($tplfile);      //载入模板
    $dlist->SetSource($sql);            //设定查询SQL
    $dlist->Display();                  //显示
}

function GetMemberID($mid)
{
    global $dsql;
    if($mid==0) return '0';
    $row = $dsql->GetOne("SELECT userid FROM #@__member WHERE mid='$mid' ");
    if(is_array($row)) return "<a href='member_view.php?mid={$mid}'>".$row['userid']."</a>";
    else return '0';
}

function GetUseDate($time=0)
{
    if(!empty($time)) return GetDateMk($time);
    else return '未使用';
}
function GetSta($sta)
{
    if($sta==1) return '已售出';
    else if($sta==-1) return '已使用';
    else return '未使用';
}