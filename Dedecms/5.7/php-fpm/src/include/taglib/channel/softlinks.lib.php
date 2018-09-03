<?php
if(!defined('DEDEINC')) exit('Request Error!');
/**
 * 软件相关标签
 *
 * @version        $Id:softlinks.lib.php 1 9:33 2010年7月8日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

/**
 *  获取软件连接
 *
 * @access    public
 * @param     string  $fvalue  默认值
 * @param     object  $ctag  解析标签
 * @param     object  $refObj  引用对象
 * @param     bool  $downloadpage  下载页面
 * @return    string
 */
function ch_softlinks($fvalue, &$ctag, &$refObj, $fname='', $downloadpage=false)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT * FROM `#@__softconfig` ");
    $phppath = $GLOBALS['cfg_phpurl'];
    $downlinks = '';
    if($row['downtype']!='0' && !$downloadpage)
    {
        $tempStr = GetSysTemplets("channel_downlinkpage.htm");
        $links = $phppath."/download.php?open=0&aid=".$refObj->ArcID."&cid=".$refObj->ChannelID;
        $downlinks = str_replace("~link~", $links, $tempStr);
        return $downlinks;
    }
    else
    {
        return ch_softlinks_all($fvalue, $ctag, $refObj, $row);
    }
}

//读取所有链接地址
function ch_softlinks_all($fvalue, &$ctag, &$refObj, &$row)
{
    global $dsql, $cfg_phpurl;
    $phppath = $cfg_phpurl;
    $islinktype = false;
    //$link_type = trim($ctag->GetAtt('type')); (2011.6.29 修正下载链接列表 by：织梦的鱼)
    if(!empty($link_type)) $islinktype = true;
    $dtp = new DedeTagParse();
    $dtp->LoadSource($fvalue);
    if( !is_array($dtp->CTags) )
    {
        $dtp->Clear();
        return "无链接信息！";
    }
    // 去除链接信息
    if (!empty($row['sites']))
    {
        $sertype_arr = array();
        $row['sites'] = preg_replace("#[\r\n]{1,}#", "\n", $row['sites']);
        $sites = explode("\n", trim($row['sites']));
        foreach($sites as $site)
        {
            if(trim($site)=='') continue;
            list($link,$serverName,$serverType) = explode('|', $site);
            $sertype_arr[trim($serverName)] = trim($serverType);
        }
    }

    $tempStr = GetSysTemplets('channel_downlinks.htm');
    $downlinks = '';
    foreach($dtp->CTags as $ctag)
    {
        if($ctag->GetName()=='link')
        {
            $link = trim($ctag->GetInnerText());
            $serverName = trim($ctag->GetAtt('text'));
            $islocal = trim($ctag->GetAtt('islocal'));
            if (isset($sertype_arr[$serverName]) && $islinktype && $sertype_arr[$serverName] != $link_type) continue;
            
            //分析本地链接
            if(!isset($firstLink) && $islocal==1) $firstLink = $link;
            if($islocal==1 && $row['islocal'] != 1) continue;
    
            //支持http,迅雷下载,ftp,flashget
            if(!preg_match("#^http:\/\/|^thunder:\/\/|^ftp:\/\/|^flashget:\/\/#i", $link))
            {
                $link = $GLOBALS['cfg_mainsite'].$link;
            }
            $downloads = getDownloads($link);
            $uhash = substr(md5($link), 0, 24);
            if($row['gotojump']==1)
            {
                $link = $phppath."/download.php?open=2&id={$refObj->ArcID}&uhash={$uhash}";
            }
            $temp = str_replace("~link~",$link,$tempStr);
            $temp = str_replace("~server~",$serverName,$temp);
            $temp = str_replace("~downloads~",$downloads,$temp);
            $downlinks .= $temp;
        }
    }
    $dtp->Clear();
    //获取镜像功能的地址
    //必须设置为：[根据本地地址和服务器列表自动生成] 的情况
    $linkCount = 1;
    if($row['ismoresite']==1 && $row['moresitedo']==1 && trim($row['sites'])!='' && isset($firstLink))
    {
        $firstLink = preg_replace("#http:\/\/([^\/]*)\/#i", '/', $firstLink);
        
        foreach($sites as $site)
        {
            if(trim($site)=='') continue;
            list($link,$serverName,$serverType) = explode('|', $site);
            if (!empty($link_type) && $link_type != trim($serverType)) continue;
            
            $link = trim( preg_replace("#\/$#", "", $link) ).$firstLink;
            $downloads = getDownloads($link);
            $uhash = substr(md5($link), 0, 24);
            if($row['gotojump']==1)
            {
                $link = $phppath."/download.php?open=2&id={$refObj->ArcID}&uhash={$uhash}";
            }
            $temp = str_replace("~link~", $link, $tempStr);
            $temp = str_replace("~server~", $serverName, $temp);
            $temp = str_replace("~downloads~", $downloads, $temp);
            $downlinks .= $temp;
        }
    }
    return $downlinks;
}

function getDownloads($url)
{
    global $dsql;
    $hash = md5($url);
    $query = "SELECT downloads FROM `#@__downloads` WHERE hash='$hash' ";
    $row = $dsql->GetOne($query);
    if(is_array($row))
    {
        $downloads = $row['downloads'];
    }
    else
    {
        $downloads = 0;
    }
    return $downloads;
}