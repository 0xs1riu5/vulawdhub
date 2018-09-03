<?php
/**
 * 软件编辑
 * 
 * @version        $Id: soft_edit.php 2 14:16 2010-11-11 tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0, 0);
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 3;
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
$menutype = 'content';

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
    //读取归档信息
    $arcQuery = "SELECT
    #@__channeltype.typename as channelname,
    #@__arcrank.membername as rankname,
    #@__channeltype.arcsta,
    #@__archives.*
    FROM #@__archives
    LEFT JOIN #@__channeltype ON #@__channeltype.id=#@__archives.channel
    LEFT JOIN #@__arcrank ON #@__arcrank.rank=#@__archives.arcrank
    WHERE #@__archives.id='$aid'";
    $dsql->SetQuery($arcQuery);
    $row = $dsql->GetOne($arcQuery);
    if(!is_array($row))
    {
        ShowMsg("读取档案基本信息出错!","-1");
        exit();
    }
    else if($row['arcrank']>=0)
    {
        $dtime = time();
        $maxtime = $cfg_mb_editday * 24 *3600;
        if($dtime - $row['senddate'] > $maxtime)
        {
            ShowMsg("这篇文档已经锁定，你不能再修改它！","-1");
            exit();
        }
    }
    $query = "SELECT * FROM `#@__channeltype` WHERE id='".$row['channel']."'";
    $cInfos = $dsql->GetOne($query);
    if(!is_array($cInfos))
    {
        ShowMsg("读取频道配置信息出错!","javascript:;");
        exit();
    }
    $addtable = $cInfos['addtable'];
    $addQuery = "SELECT * FROM `$addtable` WHERE aid='$aid'";
    $addRow = $dsql->GetOne($addQuery);
    $newRowStart = 1;
    $nForm = '';
    if(isset($addRow['softlinks']) && $addRow['softlinks']!='')
    {
        $dtp = new DedeTagParse();
        $dtp->LoadSource($addRow['softlinks']);
        if(is_array($dtp->CTags))
        {
            foreach($dtp->CTags as $ctag)
            {
                if($ctag->GetName()=='link')
                {
                    $nForm .= "软件地址".$newRowStart."：<input class='text' type='text' name='softurl".$newRowStart."'  value='".trim($ctag->GetInnerText())."' />
            服务器名称：<input class='text' type='text' name='servermsg".$newRowStart."' value='".$ctag->GetAtt("text")."'  />
            <br />";
                    $newRowStart++;
                }
            }
        }
        $dtp->Clear();
    }
    $channelid = $row['channel'];
    $tags = GetTags($aid);
    include(DEDEMEMBER."/templets/soft_edit.htm");
    exit();
}
/*------------------------------
function _SaveArticle(){  }
------------------------------*/
else if($dopost=='save')
{
    $description = '';
    include(DEDEMEMBER.'/inc/archives_check_edit.php');

    //分析处理附加表数据
    $inadd_f = '';
    if(!empty($dede_addonfields))
    {
        $addonfields = explode(';', $dede_addonfields);
        if(is_array($addonfields))
        {
            foreach($addonfields as $v)
            {
                if($v=='')
                {
                    continue;
                }
                $vs = explode(',',$v);
                if(!isset(${$vs[0]}))
                {
                    ${$vs[0]} = '';
                }
                ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$aid);
                $inadd_f .= ','.$vs[0]." ='".${$vs[0]}."' ";
            }
        }
    }
    $body = AnalyseHtmlBody($body, $description);
    $body = HtmlReplace($body, -1);

    //处理图片文档的自定义属性
    if($litpic!='') $flag = 'p';

    //分析处理附加表数据
    $inadd_f = '';
    $inadd_v = '';
    if(!empty($dede_addonfields))
    {
        $addonfields = explode(';',$dede_addonfields);
        $inadd_f = '';
        $inadd_v = '';
        if(is_array($addonfields))
        {
            foreach($addonfields as $v)
            {
                if($v=='')
                {
                    continue;
                }
                $vs = explode(',', $v);

                //HTML文本特殊处理
                if($vs[1]=='htmltext'||$vs[1]=='textdata')
                {
                    ${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
                }
                else
                {
                    if(!isset(${$vs[0]}))
                    {
                        ${$vs[0]} = '';
                    }
                    ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$arcID);
                }
                $inadd_f .= ",`{$vs[0]}` = '".${$vs[0]}."'";
            }
        }
        
        if (empty($dede_fieldshash) || $dede_fieldshash != md5($dede_addonfields.$cfg_cookie_encode))
        {
            showMsg('数据校验不对，程序返回', '-1');
            exit();
        }
        
        // 这里对前台提交的附加数据进行一次校验
        $fontiterm = PrintAutoFieldsAdd($cInfos['fieldset'],'autofield', FALSE);
        if ($fontiterm != $inadd_f)
        {
            ShowMsg("提交表单同系统配置不相符,请重新提交！", "-1");
            exit();
        }
    }
    
    //更改主档案表
    $upQuery = "UPDATE `#@__archives` set
             ismake='$ismake',
             arcrank='$arcrank',
             typeid='$typeid',
             title='$title',
             litpic='$litpic',
             description='$description',
             keywords='$keywords',            
             flag='$flag'
      WHERE id='$aid' AND mid='$mid'; ";
    if(!$dsql->ExecuteNoneQuery($upQuery))
    {
        ShowMsg("更新数据库archives表时出错，请检查！", "-1");
        exit();
    }

    //软件链接列表
    $urls = '';
    for($i=1; $i<=9; $i++)
    {
        if(!empty(${'softurl'.$i}))
        {
            $servermsg = str_replace("'",'',stripslashes(${'servermsg'.$i}));
            $softurl = stripslashes(${'softurl'.$i});
            if(!preg_match("#(http:\/\/)?([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?#i",$softurl)){
                ShowMsg("请使用标准的url格式", "-1");
                exit();
            }
            if($servermsg=='')
            {
                $servermsg = '下载地址'.$i;
            }
            if($softurl!='' && $softurl!='http://')
            {
                $urls .= "{dede:link text='$servermsg'} $softurl {/dede:link}\r\n";
            }
        }
    }
    $urls = addslashes($urls);

    //更新附加表
    $needmoney = @intval($needmoney);
    if($needmoney > 100) $needmoney = 100;
    $cts = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$channelid' ");
    $addtable = trim($cts['addtable']);
    if($addtable!='')
    {
        $inQuery = "update `$addtable`
            set typeid ='$typeid',
            filetype ='$filetype',
            language ='$language',
            softtype ='$softtype',
            accredit ='$accredit',
            os ='$os',
            softrank ='$softrank',
            officialUrl ='$officialUrl',
            officialDemo ='$officialDemo',
            softsize ='$softsize',
            softlinks ='$urls',
            userip='$userip',
            needmoney='$needmoney',
            introduce='$body'{$inadd_f}
            where aid='$aid'; ";
        if(!$dsql->ExecuteNoneQuery($inQuery))
        {
            ShowMsg("更新数据库附加表 addonsoft 时出错，请检查原因！","-1");
            exit();
        }
    }
    UpIndexKey($aid,$arcrank,$typeid,$sortrank,$tags);
    $artUrl = MakeArt($aid,TRUE);
    if($artUrl=='')
    {
        $artUrl = $cfg_phpurl."/view.php?aid=$aid";
    }

    //返回成功信息
    $msg = "　　请选择你的后续操作：
        <a href='soft_add.php?cid=$typeid'><u>发布新软件</u></a>
        &nbsp;&nbsp;
        <a href='soft_edit.php?channelid=$channelid&aid=".$aid."'><u>查看更改</u></a>
        &nbsp;&nbsp;
        <a href='$artUrl' target='_blank'><u>查看软件</u></a>
        &nbsp;&nbsp;
        <a href='content_list.php?channelid=$channelid'><u>管理软件</u></a>
        ";
    $wintitle = "成功更改软件！";
    $wecome_info = "软件管理::更改软件";
    $win = new OxWindow();
    $win->AddTitle("成功更改软件：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand","&nbsp;",FALSE);
    $win->Display();
}