<?php
/**
 * 栏目操作
 *
 * @version        $Id: catalog_do.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
if(empty($dopost))
{
    ShowMsg("对不起，请指定栏目参数！","catalog_main.php");
    exit();
}
$cid = empty($cid) ? 0 : intval($cid);
$channelid = empty($channelid) ? 0 : intval($channelid);

/*--------------------------
//增加文档
function addArchives();
---------------------------*/
if($dopost=="addArchives")
{
    //默认文章调用发布表单
    if(empty($cid) && empty($channelid))
    {
        header("location:article_add.php");
        exit();
    }
    if(!empty($channelid))
    {
        //根据模型调用发布表单
        $row = $dsql->GetOne("SELECT addcon FROM #@__channeltype WHERE id='$channelid'");
    }
    else
    {
        //根据栏目调用发布表单
        $row = $dsql->GetOne("SELECT ch.addcon FROM `#@__arctype` tp LEFT JOIN `#@__channeltype` ch ON ch.id=tp.channeltype WHERE tp.id='$cid' ");
    }
    $gurl = $row["addcon"];
    if($gurl=="")
    {
        ShowMsg("对不起，你指的栏目可能有误！","catalog_main.php");
        exit();
    }

    //跳转并传递参数
    header("location:{$gurl}?channelid={$channelid}&cid={$cid}");
    exit();
}
/*--------------------------
//管理文档
function listArchives();
---------------------------*/
else if($dopost=="listArchives")
{
    if(!empty($gurl))
    {
        if(empty($arcrank))
        {
            $arcrank = '';
        }
        $gurl = str_replace('..','',$gurl);
        header("location:{$gurl}?arcrank={$arcrank}&cid={$cid}");
        exit();
    }
    if($cid>0)
    {
        $row = $dsql->GetOne("SELECT #@__arctype.typename,#@__channeltype.typename AS channelname,#@__channeltype.id,#@__channeltype.mancon FROM #@__arctype LEFT JOIN #@__channeltype on #@__channeltype.id=#@__arctype.channeltype WHERE #@__arctype.id='$cid'");
        $gurl = $row["mancon"];
        $channelid = $row["id"];
        $typename = $row["typename"];
        $channelname = $row["channelname"];
        if($gurl=="")
        {
            ShowMsg("对不起，你指的栏目可能有误！","catalog_main.php");
            exit();
        }
    }
    else if($channelid>0)
    {
        $row = $dsql->GetOne("SELECT typename,id,mancon FROM #@__channeltype WHERE id='$channelid'");
        $gurl = $row["mancon"];
        $channelid = $row["id"];
        $typename = "";
        $channelname = $row["typename"];
    }
    
    if(empty($gurl)) $gurl = 'content_list.php';
    header("location:{$gurl}?channelid={$channelid}&cid={$cid}");
    exit();
}
/*--------------------------
//浏览通用模板目录
function viewTempletDir();
---------------------------*/
else if($dopost=="viewTemplet")
{
    header("location:tpl.php?path=/".$cfg_df_style);
    exit();
}

/*--------------------------
//留言簿管理
function GoGuestBook();
---------------------------*/
else if($dopost=="guestbook")
{
    ShowMsg("正在跳转到留言本&gt;&gt;", "{$cfg_phpurl}/guestbook.php?gotopagerank=admin");
    exit();
}
/*------------------------
浏览单个页面的栏目
function ViewSgPage()
------------------------*/
else if($dopost=="viewSgPage")
{
    require_once(DEDEINC."/arc.listview.class.php");
    $lv = new ListView($cid);
    $pageurl = $lv->MakeHtml();
    ShowMsg("更新缓冲，请稍后...",$pageurl);
    exit();
}
/*------------------------
更改栏目排列顺序
function upRank()
------------------------*/
else if($dopost=="upRank")
{
    //检查权限许可
    CheckPurview('t_Edit,t_AccEdit');

    //检查栏目操作许可
    CheckCatalog($cid,"你无权更改本栏目！");
    $row = $dsql->GetOne("SELECT reid,sortrank FROM #@__arctype WHERE id='$cid'");
    $reid = $row['reid'];
    $sortrank = $row['sortrank'];
    $row = $dsql->GetOne("SELECT sortrank FROM #@__arctype WHERE sortrank<=$sortrank AND reid=$reid ORDER BY sortrank DESC ");
    if(is_array($row))
    {
        $sortrank = $row['sortrank']-1;
        $dsql->ExecuteNoneQuery("UPDATE #@__arctype SET sortrank='$sortrank' WHERE id='$cid'");
    }
    UpDateCatCache();
    ShowMsg("操作成功，返回目录...","catalog_main.php");
    exit();
}
else if($dopost=="upRankAll")
{
    //检查权限许可
    CheckPurview('t_Edit');
    $row = $dsql->GetOne("SELECT id FROM #@__arctype ORDER BY id DESC");
    if(is_array($row))
    {
        $maxID = $row['id'];
        for($i=1;$i<=$maxID;$i++)
        {
            if(isset(${'sortrank'.$i}))
            {
                $dsql->ExecuteNoneQuery("UPDATE #@__arctype SET sortrank='".(${'sortrank'.$i})."' WHERE id='{$i}';");
            }
        }
    }
    UpDateCatCache();
    ShowMsg("操作成功，正在返回...","catalog_main.php");
    exit();
}
/*--------------------------
//更新栏目缓存
function UpCatlogCache();
---------------------------*/
else if($dopost=="upcatcache")
{
    UpDateCatCache();
    $sql = " TRUNCATE TABLE `#@__arctiny`";
    $dsql->ExecuteNoneQuery($sql);
    
    //导入普通模型微数据
    $sql = "INSERT INTO `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
            SELECT id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid FROM `#@__archives` ";
    $dsql->ExecuteNoneQuery($sql);
    
    //导入单表模型微数据
    $dsql->SetQuery("SELECT id,addtable FROM `#@__channeltype` WHERE id < -1 ");
    $dsql->Execute();
    $doarray = array();
    while($row = $dsql->GetArray())
    {
        $tb = str_replace('#@__', $cfg_dbprefix, $row['addtable']);
        if(empty($tb) || isset($doarray[$tb]) )
        {
            continue;
        }
        else
        {
            $sql = "INSERT INTO `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
                    SELECT aid, typeid, 0, arcrank, channel, senddate, 0, mid FROM `$tb` ";
            $rs = $dsql->executenonequery($sql); 
            $doarray[$tb]  = 1;
        }
    }
    ShowMsg("操作成功，正在返回...","catalog_main.php");
    exit();
}
/*---------------------
获取JS文件
function GetJs
----------------------*/
else if($dopost=="GetJs")
{
    header("location:makehtml_js.php");
    exit();
}
/*-----------
获得子类的内容
function GetSunListsMenu();
-----------*/
else if($dopost=="GetSunListsMenu")
{
    $userChannel = $cuserLogin->getUserChannel();
    require_once(DEDEINC."/typeunit.class.menu.php");
    AjaxHead();
    PutCookie('lastCidMenu',$cid,3600*24,"/");
    $tu = new TypeUnit($userChannel);
    $tu->LogicListAllSunType($cid,"　");
}
/*-----------
获得子类的内容
function GetSunLists();
-----------*/
else if($dopost=="GetSunLists")
{
    require_once(DEDEINC."/typeunit.class.admin.php");
    AjaxHead();
    PutCookie('lastCid', $cid, 3600*24, "/");
    $tu = new TypeUnit();
    $tu->dsql = $dsql;
    echo "    <table width='100%' border='0' cellspacing='0' cellpadding='0'>\r\n";
    $tu->LogicListAllSunType($cid, "　");
    echo "    </table>\r\n";
    $tu->Close();
}
/*----------------
合并栏目
function unitCatalog() { }
-----------------*/
else if($dopost == 'unitCatalog')
{
    CheckPurview('t_Move');
    require_once(DEDEINC.'/oxwindow.class.php');
    require_once(DEDEINC.'/typelink.class.php');
    require_once(DEDEINC.'/channelunit.func.php');
    if(empty($nextjob))
    {
        $typeid = isset($typeid) ? intval($typeid) : 0;
        $row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__arctype` WHERE reid='$typeid' ");
        $tl = new TypeLink($typeid);
        $typename = $tl->TypeInfos['typename'];
        $reid = $tl->TypeInfos['reid'];
        $channelid = $tl->TypeInfos['channeltype'];
        if(!empty($row['dd']))
        {
            ShowMsg("栏目： $typename($typeid) 有子栏目，不能进行合并操作！", '-1');
            exit();
        }
        $typeOptions = $tl->GetOptionArray(0, 0, $channelid);
        $wintitle = '合并栏目';
        $wecome_info = "<a href='catalog_main.php'>栏目管理</a> &gt;&gt; 合并栏目";
        $win = new OxWindow();
        $win->Init('catalog_do.php', 'js/blank.js', 'POST');
        $win->AddHidden('dopost', 'unitCatalog');
        $win->AddHidden('typeid', $typeid);
        $win->AddHidden('channelid', $channelid);
        $win->AddHidden('nextjob', 'unitok');
        $win->AddTitle("合并目录时不会删除原来的栏目目录，合并后需手动更新目标栏目的文档HTML和列表HTML。");
        $win->AddItem('你选择的栏目是：', "<font color='red'>$typename($typeid)</font>");
        $win->AddItem('你希望合并到那个栏目？', "<select name='unittype'>\r\n{$typeOptions}\r\n</select>");
        $win->AddItem('注意事项：', '栏目不能有下级子栏目，只允许子级到更高级或同级或不同父级的情况。');
        $winform = $win->GetWindow('ok');
        $win->Display();
        exit();
    }
    else
    {
        if($typeid==$unittype)
        {
            ShowMsg("同一栏目无法合并,请后退重试！", '-1');
            exit();
        }
        if(IsParent($unittype, $typeid))
        {
            ShowMsg('不能从父类合并到子类！', 'catalog_main.php');
            exit();
        }
        $row = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$channelid' ");
        $addtable = (empty($row['addtable']) ? '#@__addonarticle' : $row['addtable'] );
        $dsql->ExecuteNoneQuery("UPDATE `#@__arctiny` SET typeid='$unittype' WHERE typeid='$typeid' ");
        $dsql->ExecuteNoneQuery("UPDATE `#@__feedback` SET typeid='$unittype' WHERE typeid='$typeid' ");
        $dsql->ExecuteNoneQuery("UPDATE `#@__archives` SET typeid='$unittype' WHERE typeid='$typeid' ");
        $dsql->ExecuteNoneQuery("UPDATE `#@__archives` SET typeid2='$unittype' WHERE typeid2='$typeid' ");
        $dsql->ExecuteNoneQuery("UPDATE `#@__addonspec` SET typeid='$unittype' WHERE typeid='$typeid' ");
        $dsql->ExecuteNoneQuery("UPDATE `$addtable` SET typeid='$unittype' WHERE typeid='$typeid' ");
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctype` WHERE id='$typeid' ");
        UpDateCatCache();
        ShowMsg('成功合并指定栏目！', 'catalog_main.php');
        exit();
    }
}
/*----------------
移动栏目
function moveCatalog() { }
-----------------*/
else if($dopost == 'moveCatalog')
{
    CheckPurview('t_Move');
    require_once(DEDEINC.'/oxwindow.class.php');
    require_once(DEDEINC.'/typelink.class.php');
    require_once(DEDEINC.'/channelunit.func.php');
    if(empty($nextjob))
    {
        $tl = new TypeLink($typeid);
        $typename = $tl->TypeInfos['typename'];
        $reid = $tl->TypeInfos['reid'];
        $channelid = $tl->TypeInfos['channeltype'];
        $typeOptions = $tl->GetOptionArray(0,0,$channelid);
        $wintitle = "移动栏目";
        $wecome_info = "<a href='catalog_main.php'>栏目管理</a> &gt;&gt; 移动栏目";
        $win = new OxWindow();
        $win->Init('catalog_do.php', 'js/blank.js', 'POST');
        $win->AddHidden('dopost', 'moveCatalog');
        $win->AddHidden('typeid', $typeid);
        $win->AddHidden('channelid', $channelid);
        $win->AddHidden('nextjob', 'unitok');
        $win->AddTitle("移动目录时不会删除原来已创建的列表，移动后需重新对栏目创建HTML。");
        $win->AddItem('你选择的栏目是：',"$typename($typeid)");
        $win->AddItem('你希望移动到那个栏目？',"<select name='movetype'>\r\n<option value='0'>移动为顶级栏目</option>\r\n$typeOptions\r\n</select>");
        $win->AddItem('注意事项：','不允许从父级移动到子级目录，只允许子级到更高级或同级或不同父级的情况。');
        $winform = $win->GetWindow('ok');
        $win->Display();
        exit();
    }
    else
    {
        if($typeid==$movetype)
        {
            ShowMsg('移对对象和目标位置相同！', 'catalog_main.php');
            exit();
        }
        if(IsParent($movetype, $typeid))
        {
            ShowMsg('不能从父类移动到子类！', 'catalog_main.php');
            exit();
        }
        $dsql->ExecuteNoneQuery(" UPDATE `#@__arctype` SET reid='$movetype' WHERE id='$typeid' ");
        UpDateCatCache();
        ShowMsg('成功移动目录！', 'catalog_main.php');
        exit();
    }
}