<?php
/**
 * 任务操作
 *
 * @version        $Id: task_do.php 1 23:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__).'/config.php');
$dopost = (!isset($dopost) ? '' : $dopost);
/******************************
返回到下一任务的URL
特殊变量，除非知道作用，否则不能在任务传递中占用
f 临时，仅为了方便网址结构
dopost 当前任务(指向下一个任务)， 由用户自行处理或在 nextdo 中自动获得
del 上一次任务删除的变量
morejob ，设定后，表示当前任务需请求多次，会把 dopost 和 nextdo 处理后转为 doposttmp, nextdotmp，然后由用户自行处理
******************************/
function GetNextUrl($notallowArr = array('dopost', 'f', 'del'))
{
    $reurl = "task_do.php?f=0";
    foreach($_GET as $k=>$v)
    {
        if($k=='nextdo')
        {
            $nextdo = '';
            $nextdos = explode(',', $GLOBALS[$k]);
            if(isset($nextdos[1]))
            {
                for($i=1; $i < count($nextdos); $i++)
                {
                    if( trim($nextdos[$i]) == '' ) continue;
                    $nextdo .= ($nextdo=='' ? $nextdos[$i] : ','.$nextdos[$i]);
                }
            }
            //如果系统有多重任务， 把下一任务和任务列表参数提交给程序处理
            if( in_array('morejob', $notallowArr) )
            {
                $reurl .= "&doposttmp=".$nextdos[0];
                if($nextdo != '') $reurl .= "&nextdotmp=$nextdo";
            }
            else
            {
                $reurl .= "&dopost=".$nextdos[0];
                if($nextdo != '') $reurl .= "&nextdo=$nextdo";
            }
        }
        else if( in_array($k, $notallowArr) )
        {
            continue;
        }
        else
        {
            $reurl .= "&{$k}=".urlencode($GLOBALS[$k]);
        }
    }
    return $reurl;
}
/******************************
//更新上一篇和下一篇
function makeprenext() {  }
******************************/
if($dopost=='makeprenext')
{
    require_once(DEDEINC.'/arc.archives.class.php');
    $aid = intval($aid);
    $preRow =  $dsql->GetOne("SELECT id FROM `#@__arctiny` WHERE id<$aid AND arcrank>-1 AND typeid='$typeid' ORDER BY id DESC");
    $nextRow = $dsql->GetOne("SELECT id FROM `#@__arctiny` WHERE id>$aid AND arcrank>-1 AND typeid='$typeid' ORDER BY id ASC");
    if(is_array($preRow))
    {
        $envs['aid'] = $preRow['id'];
        $arc = new Archives($preRow['id']);
        $arc->MakeHtml();
    }
    if(is_array($nextRow))
    {
        $envs['aid'] = $nextRow['id'];
        $arc = new Archives($nextRow['id']);
        $arc->MakeHtml();
    }
    if( empty($nextdo) )
    {
        ShowMsg("<b>完成上下篇文档更新任务！完成所有更新任务！</b>", "close::tgtable");
        exit();
    }
    else
    {
        $jumpurl = GetNextUrl();
        ShowMsg("完成下篇文档更新任务！ 继续执行其它任务...", $jumpurl,0,500);
        exit();
    }
}
/******************************
//更新主页的任务
function makeindex() {  }
******************************/
if($dopost=='makeindex')
{
    require_once(DEDEINC.'/arc.partview.class.php');
    $envs = $_sys_globals = array();
    $envs['aid'] = 0;
    $pv = new PartView();
    $row = $pv->dsql->GetOne('SELECT * FROM `#@__homepageset`');
    $templet = str_replace("{style}", $cfg_df_style, $row['templet']);
    $homeFile = dirname(__FILE__).'/'.$row['position'];
    $homeFile = str_replace("//", "/", str_replace("\\", "/", $homeFile));
    $fp = fopen($homeFile, 'w') or die("无法更新网站主页到：$homeFile 位置");
    fclose($fp);
    $tpl = $cfg_basedir.$cfg_templets_dir.'/'.$templet;
    if(!file_exists($tpl))
    {
        $tpl = $cfg_basedir.$cfg_templets_dir.'/default/index.htm';
        if(!file_exists($tpl)) exit("无法找到主页模板：$tpl ");
    }
    $GLOBALS['_arclistEnv'] = 'index';
    $pv->SetTemplet($tpl);
    $pv->SaveToHtml($homeFile);
    $pv->Close();
    if( empty($nextdo) )
    {
        ShowMsg("<b>完成主页更新任务！完成所有更新任务！</b>", "close::tgtable");
        exit();
    }
    else
    {
        $jumpurl = GetNextUrl();
        ShowMsg("完成主页更新！ 现在跳转到其它更新任务...", $jumpurl,0,500);
        exit();
    }
}
/******************************
//更新所有关连的栏目
function makeparenttype() {  }
******************************/
else if($dopost=='makeparenttype')
{
    require_once(DEDEDATA."/cache/inc_catalog_base.inc");
    require_once(DEDEINC.'/arc.listview.class.php');
    $notallowArr = array('dopost', 'f', 'del', 'curpage', 'morejob');

    $jumpurl = GetNextUrl($notallowArr);
    
    if( empty($typeid) )
    {
        ShowMsg("<b>完成栏目更新任务！完成所有更新任务！</b>", "close::tgtable");
        exit();
    }
    $topids = explode(',', GetTopids($typeid));
    if(empty($curpage)) $curpage = 0;
    $tid = $topids[$curpage];
    
    if(isset($cfg_Cs[$tid]) && $cfg_Cs[$tid][1]>0)
    {
        require_once(DEDEINC."/arc.listview.class.php");
        $lv = new ListView($tid);
        $lv->MakeHtml();
        $lv->Close();
    }
    else
    {
        require_once(DEDEINC."/arc.sglistview.class.php");
        $lv = new SgListView($tid);
        $lv->MakeHtml();
        $lv->Close();
    }
    
    if($curpage >= count($topids)-1)
    {
        if( !empty($doposttmp) )
        {
            $jumpurl = preg_replace("#doposttmp|nextdotmp#", 'del', $jumpurl);
            $jumpurl .= "&dopost={$doposttmp}&nextdo={$nextdotmp}";
            ShowMsg("完成栏目:{$tid}  更新！<br /><b>完成栏目更新任务，继续执行后续任务...</b>", $jumpurl,0,500);
            exit();
        }
        else
        {
            ShowMsg("完成栏目:{$tid}  更新！<br /><b>完成栏目更新任务，完成所有更新任务！</b>", "close::tgtable");
            exit();
        }
    }
    else
    {
        $curpage++;
        $jumpurl .= "&curpage={$curpage}&dopost=makeparenttype";
        ShowMsg("完成栏目:{$tid}  更新，继续更新其它栏目...", $jumpurl,0,500);
        exit();
    }
}