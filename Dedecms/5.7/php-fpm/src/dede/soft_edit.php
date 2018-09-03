<?php
/**
 * 软件编辑
 *
 * @version        $Id: soft_edit.php 1 16:09 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_Edit,a_AccEdit,a_MyEdit');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");
if(empty($dopost)) $dopost = '';

if($dopost!='save')
{
    require_once(DEDEADMIN."/inc/inc_catalog_options.php");
    require_once(DEDEINC."/dedetag.class.php");
    ClearMyAddon();
    $aid = preg_replace("#[^0-9]#", '', $aid);
    $channelid="3";

    //读取归档信息
    $arcQuery = "SELECT
    #@__channeltype.typename as channelname,
    #@__arcrank.membername as rankname,
    #@__archives.*
    FROM #@__archives
    LEFT JOIN #@__channeltype ON #@__channeltype.id=#@__archives.channel
    LEFT JOIN #@__arcrank ON #@__arcrank.rank=#@__archives.arcrank
    WHERE #@__archives.id='$aid'";
    $dsql->SetQuery($arcQuery);
    $arcRow = $dsql->GetOne($arcQuery);
    if(!is_array($arcRow))
    {
        ShowMsg("读取档案基本信息出错!","-1");
        exit();
    }
    $query = "SELECT * FROM `#@__channeltype` WHERE id='".$arcRow['channel']."'";
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
    $daccess = $addRow['daccess'];
    $needmoney = $addRow['needmoney'];
    if($addRow['softlinks'] != '')
    {
        $dtp = new DedeTagParse();
        $dtp->LoadSource($addRow['softlinks']);
        if(is_array($dtp->CTags))
        {
            foreach($dtp->CTags as $ctag)
            {
                if($ctag->GetName()=='link')
                {
                    $islocal = $ctag->GetAtt('islocal');
                    if($islocal != 1) $needmsg = "<input type='checkbox' name='del{$newRowStart}' value='1' />删除";
                    else $needmsg = '<input name="sel1" type="button" id="sel1" value="选取" onClick="SelectSoft(\'form1.softurl'.$newRowStart.'\')" />';
                    $nForm .= "<div style='line-height:36px'>软件地址{$newRowStart}：<input type='text' name='softurl{$newRowStart}' style='width:280px' value='".trim($ctag->GetInnerText())."' />
            服务器名称：<input type='text' name='servermsg{$newRowStart}' value='".$ctag->GetAtt("text")."' style='width:150px' />
            <input type='hidden' name='islocal{$newRowStart}' value='{$islocal}' />
            $needmsg
            </div>\r\n";
                    $newRowStart++;
                }
            }
        }
        $dtp->Clear();
    }
    $channelid = $arcRow['channel'];
    $tags = GetTags($aid);
    include DedeInclude("templets/soft_edit.htm");
    exit();
}
/*--------------------------------
function __save(){  }
-------------------------------*/
else if($dopost=='save')
{
    require_once(DEDEINC.'/image.func.php');
    require_once(DEDEINC.'/oxwindow.class.php');
    
    $flag = isset($flags) ? join(',',$flags) : '';
    $notpost = isset($notpost) && $notpost == 1 ? 1: 0;
    
    if(empty($typeid2)) $typeid2 = 0;
    if(!isset($autokey)) $autokey = 0;
    if(!isset($remote)) $remote = 0;
    if(!isset($dellink)) $dellink = 0;
    if(!isset($autolitpic)) $autolitpic = 0;

    if($typeid==0)
    {
        ShowMsg("请指定文档的栏目！","-1");
        exit();
    }
    if(empty($channelid))
    {
        ShowMsg("文档为非指定的类型，请检查你发布内容的表单是否合法！","-1");
        exit();
    }
    if(!CheckChannel($typeid,$channelid))
    {
        ShowMsg("你所选择的栏目与当前模型不相符，请选择白色的选项！","-1");
        exit();
    }
    if(!TestPurview('a_Edit'))
    {
        if(TestPurview('a_AccEdit'))
        {
            CheckCatalog($typeid,"对不起，你没有操作栏目 {$typeid} 的文档权限！");
        }
        else
        {
            CheckArcAdmin($id,$cuserLogin->getUserID());
        }
    }

    //对保存的内容进行处理
    $pubdate = GetMkTime($pubdate);
    $senddate = time();
    $sortrank = AddDay($pubdate,$sortup);
    if($ishtml==0)
    {
        $ismake = -1;
    }
    else
    {
        $ismake = 0;
    }
    $title = cn_substrR($title,$cfg_title_maxlen);
    $shorttitle = cn_substrR($shorttitle,36);
    $color =  cn_substrR($color,7);
    $writer =  cn_substrR($writer,20);
    $source = cn_substrR($source,30);
    $description = cn_substrR($description,$cfg_auot_description);
    $keywords = cn_substrR($keywords,60);
    $filename = trim(cn_substrR($filename,40));
    $isremote  = (empty($isremote)? 0  : $isremote);
    $serviterm=empty($serviterm)? "" : $serviterm;
    if(!TestPurview('a_Check,a_AccCheck,a_MyCheck'))
    {
        $arcrank = -1;
    }
    $adminid = $cuserLogin->getUserID();

    //处理上传的缩略图
    if(empty($ddisremote))
    {
        $ddisremote = 0;
    }
    $litpic = GetDDImage('litpic',$picname,$ddisremote);

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
                $vs = explode(',',$v);
                if($vs[1]=='htmltext'||$vs[1]=='textdata') //HTML文本特殊处理
                {
                    ${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
                }
                else
                {
                    if(!isset(${$vs[0]}))
                    {
                        ${$vs[0]} = '';
                    }
                    ${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$id);
                }
                $inadd_f .= ",`{$vs[0]}` = '".${$vs[0]}."'";
            }
        }
    }

    //处理图片文档的自定义属性
    if($litpic!='' && !preg_match('#p#',$flag))
    {
        $flag = ($flag=='' ? 'p' : $flag.',p');
    }
    if($redirecturl!='' && !preg_match('#j#', $flag))
    {
        $flag = ($flag=='' ? 'j' : $flag.',j');
    }

    //跳转网址的文档强制为动态
    if(preg_match('#j#', $flag)) $ismake = -1;
    //更改主档案表
    $inQuery = "UPDATE `#@__archives` SET
        typeid='$typeid',
        typeid2='$typeid2',
        sortrank='$sortrank',
        flag='$flag',
        click='$click',
        ismake='$ismake',
        arcrank='$arcrank',
        money='$money',
        title='$title',
        color='$color',
        source='$source',
        writer='$writer',
        litpic='$litpic',
        pubdate='$pubdate',
        notpost='$notpost',
        description='$description',
        keywords='$keywords',
        shorttitle='$shorttitle',
        filename='$filename',
        dutyadmin='$adminid',
        weight='$weight'
        WHERE id='$id'; ";
    if(!$dsql->ExecuteNoneQuery($inQuery))
    {
        ShowMsg("更新数据库archives表时出错，请检查！","-1");
        exit();
    }

    //软件链接列表
    $urls = '';
    
    for($i=1; $i<=30; $i++)
    {
        if(!empty(${'softurl'.$i}))
        {
            $islocal = empty(${'islocal'.$i}) ? '' : 1;
            $isneed = empty(${'del'.$i}) ? true : false;
            $servermsg = str_replace("'",'',stripslashes(${'servermsg'.$i}));
            $softurl = stripslashes(${'softurl'.$i});
            
            if($servermsg=='')
            {
                $servermsg = '下载地址'.$i;
            }
            if($softurl != 'http://')
            {
                if($islocal==1) $urls .= "{dede:link islocal='$islocal' text='{$servermsg}'} $softurl {/dede:link}\r\n" ;
                else if($isneed) $urls .= "{dede:link text='$servermsg'} $softurl {/dede:link}\r\n";
                else continue;
            }
        }
    }
    $urls = addslashes($urls);

    //更新附加表
    $cts = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$channelid' ");
    $addtable = trim($cts['addtable']);
    if($addtable!='')
    {
        $useip = GetIP();
        $inQuery = "UPDATE `$addtable`
          SET typeid ='$typeid',
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
          redirecturl='$redirecturl',
          userip = '$useip',
          daccess = '$daccess',
          needmoney = '$needmoney',
          introduce='$body'
          {$inadd_f}
          WHERE aid='$id';";
        if(!$dsql->ExecuteNoneQuery($inQuery))
        {
            ShowMsg("更新数据库附加表 addonsoft 时出错，请检查原因！","-1");
            exit();
        }
    }

    //生成HTML
    UpIndexKey($id, $arcrank, $typeid, $sortrank, $tags);
    if($cfg_remote_site=='Y' && $isremote=="1")
    {    
        if($serviterm!="")
        {
            list($servurl, $servuser, $servpwd) = explode(',', $serviterm);
            $config = array( 'hostname' => $servurl, 'username' => $servuser, 
                             'password' => $servpwd, 'debug' => 'TRUE');
        } else {
            $config = array();
        }
        if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
    }
    $arcUrl = MakeArt($id, TRUE, TRUE, $isremote);
    if($arcUrl=="")
    {
        $arcUrl = $cfg_phpurl."/view.php?aid=$id";
    }
    ClearMyAddon($id, $title);
    //返回成功信息
    $msg = "
    　　请选择你的后续操作：
    <a href='soft_add.php?cid=$typeid'><u>发布新软件</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$id."&dopost=editArchives'><u>继续修改</u></a>
    &nbsp;&nbsp;
    <a href='$arcUrl' target='_blank'><u>查看软件</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>已发布软件管理</u></a>
    &nbsp;&nbsp;
    <a href='catalog_main.php'><u>网站栏目管理</u></a>
    ";
    $wintitle = "成功修改一个软件！";
    $wecome_info = "文章管理::修改软件";
    $win = new OxWindow();
    $win->AddTitle("成功修改软件：");
    $win->AddMsgItem($msg);
    $winform = $win->GetWindow("hand", "&nbsp;", FALSE);
    $win->Display();
}