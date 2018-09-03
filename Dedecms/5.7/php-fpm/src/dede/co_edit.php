<?php
/**
 * 采集规则编辑
 *
 * @version        $Id: co_edit.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
CheckPurview('co_EditNote');
require_once(DEDEINC.'/dedetag.class.php');
$nid = (isset($nid) ? intval($nid) : '');
if($nid=='')
{
    ShowMsg('参数无效!','-1');
    exit();
}
if(empty($dopost)) $dopost = '';

/*----------------------
function _Save()
----------------------*/
if($dopost=='save' || $dopost=='saveandtest')
{
    $usemore = (!isset($usemore) ? 0 : 1);
    $listconfig = "{dede:noteinfo notename=\\\"$notename\\\" channelid=\\\"$channelid\\\" macthtype=\\\"$macthtype\\\"
    refurl=\\\"$refurl\\\" sourcelang=\\\"$sourcelang\\\" cosort=\\\"$cosort\\\"
  isref=\\\"$isref\\\" exptime=\\\"$exptime\\\" usemore=\\\"$usemore\\\" /}
{dede:listrule sourcetype=\\\"$sourcetype\\\" rssurl=\\\"$rssurl\\\" regxurl=\\\"$regxurl\\\"
startid=\\\"$startid\\\" endid=\\\"$endid\\\" addv=\\\"$addv\\\" urlrule=\\\"$urlrule\\\" musthas=\\\"$musthas\\\"
 nothas=\\\"$nothas\\\" listpic=\\\"$listpic\\\" usemore=\\\"$usemore\\\"}
    {dede:addurls}$addurls{/dede:addurls}
    {dede:batchrule}$batchrule{/dede:batchrule}
    {dede:regxrule}$regxrule{/dede:regxrule}
    {dede:areastart}$areastart{/dede:areastart}
    {dede:areaend}$areaend{/dede:areaend}
{/dede:listrule}\r\n";
    $itemconfig = "{dede:sppage sptype=\\'$sptype\\' srul=\\'$srul\\' erul=\\'$erul\\'}$sppage{/dede:sppage}\r\n";
    $itemconfig .= "{dede:previewurl}$previewurl{/dede:previewurl}\r\n";
    $itemconfig .= "{dede:keywordtrim}$keywordtrim{/dede:keywordtrim}\r\n";
    $itemconfig .= "{dede:descriptiontrim}$descriptiontrim{/dede:descriptiontrim}\r\n";
    $fs = explode(',','value,match,isunit,isdown,trim,function');
    foreach($fields as $field)
    {
        foreach($fs as $f)
        {
            $GLOBALS[$f.'_'.$field] = (!isset($GLOBALS[$f.'_'.$field]) ? '' : $GLOBALS[$f.'_'.$field]);
        }
        $matchstr = $GLOBALS["match_".$field];
        $trimstr = $GLOBALS["trim_".$field];
        $trimstr = trim(str_replace('&nbsp;', '#n#', $trimstr));
        $matchstr = trim(str_replace('&nbsp;', '#n#', $matchstr));
        if($trimstr!='' && !preg_match("#{dede:trim#isU", $trimstr))
        {
            $trimstr = "      {dede:trim}$trimstr{/dede:trim}\r\n";
        }
        $itemconfig .= "{dede:item field=\\'".$field."\\' value=\\'".$GLOBALS["value_".$field]."\\' isunit=\\'".$GLOBALS["isunit_".$field]."\\' isdown=\\'".$GLOBALS["isdown_".$field]."\\'}
   {dede:match}".$matchstr."{/dede:match}
   $trimstr
   {dede:function}".$GLOBALS["function_".$field]."{/dede:function}
{/dede:item}";
    }
    
    $uptime = time();
    if(empty($freq)) $freq = 1;
    if(empty($extypeid)) $extypeid = 0;
    if(empty($islisten)) $islisten = 0;

    $query = " UPDATE `#@__co_note` SET
    `channelid`='$channelid',
    `notename`='$notename',
    `sourcelang`='$sourcelang',
    `uptime`='$uptime',
    `isok`='1',
    `usemore`='$usemore',
    `listconfig`='$listconfig',
    `itemconfig`='$itemconfig'
     WHERE nid='$nid'; ";
    $rs = $dsql->ExecuteNoneQuery($query);
    echo $dsql->GetError();
    if($donext=='save')
    {
        ShowMsg("成功保存配置！","co_main.php");
    }
    else
    {
        require_once(dirname(__FILE__)."/co_test_rule.php");
    }
    exit();
}
$arr = $dsql->GetOne("SELECT * FROM `#@__co_note` WHERE nid='$nid'");

//如果内容规则未设置，转到设置内容规则的表单
if(trim($arr['itemconfig'])=='')
{
    $channelid = $arr['channelid'];
    $nid = $arr['nid'];
    if(!isset($previewurl)) $previewurl = '';

    require_once(DEDEINC.'/dedetag.class.php');
    require_once(DEDEADMIN."/templets/co_add_step2.htm");
    exit();
}
$usemore = $arr['usemore'];
$notename = $arr['notename'];
$notes = array();
$dsql->FreeResult();
$dtp = new DedeTagParse();
$dtp2 = new DedeTagParse();
$dtp->LoadString($arr['listconfig'].$arr['itemconfig']);
$channelid = $arr['channelid'];
$notes['keywordtrim'] = '';
$notes['descriptiontrim'] = '';
foreach($dtp->CTags as $tid => $ctag)
{
    if($ctag->GetName()=='item')
    {
        $f = $ctag->GetAtt('field');
        $notes[$f]['item'] = $ctag;
        $dtp2->LoadString($ctag->GetInnerText());
        $notes[$f]['trim'] = '';
        foreach($dtp2->CTags as $ctag2)
        {
            if($ctag2->GetName()=='trim')
            {
                $notes[$f]['trim'] .= "{dede:trim replace=\"".$ctag2->GetAtt('replace')."\"}".$ctag2->GetInnerText()."{/dede:trim}\r\n";
            }
            else if($ctag2->GetName()=='match')
            {
                $notes[$f]['match'] = $ctag2->GetInnerText()."\r\n";
            }
            else if($ctag2->GetName()=='function')
            {
                $notes[$f]['function'] = $ctag2->GetInnerText()."\r\n";
            }
        }
    }
    else if($ctag->GetName()=='keywordtrim')
    {
        $notes['keywordtrim'] = $ctag->GetInnerText();
    }
    else if($ctag->GetName()=='descriptiontrim')
    {
        $notes['descriptiontrim'] = $ctag->GetInnerText();
    }
    else if($ctag->GetName()=='noteinfo')
    {
        $noteinfo = $ctag;
    }
    else if($ctag->GetName()=='listrule')
    {
        $listrule = $ctag;
        $dtp2->LoadString($ctag->GetInnerText());
        $addurls = $dtp2->GetTagByName('addurls');
        $regxrule = $dtp2->GetTagByName('regxrule');
        $areastart = $dtp2->GetTagByName('areastart');
        $areaend = $dtp2->GetTagByName('areaend');
        $batchrule = $dtp2->GetTagByName('batchrule');
    }
    else if($ctag->GetName()=='sppage')
    {
        $sppage = $ctag;
    }
    else if($ctag->GetName()=='previewurl')
    {
        $previewurl = trim($ctag->GetInnerText());
    }
}
if(!isset($previewurl)) $previewurl = '';
require_once(DEDEADMIN.'/templets/co_edit.htm');