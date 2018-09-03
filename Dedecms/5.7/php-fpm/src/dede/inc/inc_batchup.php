<?php
/**
 * 文档操作相关函数
 *
 * @version        $Id: inc_batchup.php 1 10:32 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/**
 *  删除文档信息
 *
 * @access    public
 * @param     string  $aid  文档ID
 * @param     string  $type  类型
 * @param     string  $onlyfile  删除数据库记录
 * @return    string
 */
function DelArc($aid, $type='ON', $onlyfile=FALSE,$recycle=0)
{
    global $dsql,$cfg_cookie_encode,$cfg_multi_site,$cfg_medias_dir;
    global $cuserLogin,$cfg_upload_switch,$cfg_delete,$cfg_basedir;
    global $admin_catalogs, $cfg_admin_channel;
    
    if($cfg_delete == 'N') $type = 'OK';
    if(empty($aid)) return ;
    $aid = preg_replace("#[^0-9]#i", '', $aid);
    $arctitle = $arcurl = '';
    if($recycle == 1) $whererecycle = "AND arcrank = '-2'";
	else $whererecycle = "";

    //查询表信息
    $query = "SELECT ch.maintable,ch.addtable,ch.nid,ch.issystem FROM `#@__arctiny` arc
                LEFT JOIN `#@__arctype` tp ON tp.id=arc.typeid
              LEFT JOIN `#@__channeltype` ch ON ch.id=arc.channel WHERE arc.id='$aid' ";
    $row = $dsql->GetOne($query);
    $nid = $row['nid'];
    $maintable = (trim($row['maintable'])=='' ? '#@__archives' : trim($row['maintable']));
    $addtable = trim($row['addtable']);
    $issystem = $row['issystem'];

    //查询档案信息
    if($issystem==-1)
    {
        $arcQuery = "SELECT arc.*,tp.* from `$addtable` arc LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id WHERE arc.aid='$aid' ";
    }
    else
    {
        $arcQuery = "SELECT arc.*,tp.*,arc.id AS aid FROM `$maintable` arc LEFT JOIN `#@__arctype` tp ON arc.typeid=tp.id WHERE arc.id='$aid' ";
    }

    $arcRow = $dsql->GetOne($arcQuery);

    //检测权限
    if(!TestPurview('a_Del,sys_ArcBatch'))
    {
        if(TestPurview('a_AccDel'))
        {
            if( !in_array($arcRow['typeid'], $admin_catalogs) && (count($admin_catalogs) != 0 || $cfg_admin_channel != 'all') )
            {
                return FALSE;
            }
        }
        else if(TestPurview('a_MyDel'))
        {
            if($arcRow['mid'] != $cuserLogin->getUserID())
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    //$issystem==-1 是单表模型，不使用回收站
    if($issystem == -1) $type = 'OK';
    if(!is_array($arcRow)) return FALSE;
    
    /** 删除到回收站 **/
    if($cfg_delete == 'Y' && $type == 'ON')
    {
        $dsql->ExecuteNoneQuery("UPDATE `$maintable` SET arcrank='-2' WHERE id='$aid' ");
        $dsql->ExecuteNoneQuery("UPDATE `#@__arctiny` SET `arcrank` = '-2' WHERE id = '$aid'; ");
    }
    else
    {
        //删除数据库记录
        if(!$onlyfile)
        {
            $query = "Delete From `#@__arctiny` where id='$aid' $whererecycle";
            if($dsql->ExecuteNoneQuery($query))
            {
                $dsql->ExecuteNoneQuery("Delete From `#@__feedback` where aid='$aid' ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_stow` where aid='$aid' ");
                $dsql->ExecuteNoneQuery("Delete From `#@__taglist` where aid='$aid' ");
                $dsql->ExecuteNoneQuery("Delete From `#@__erradd` where aid='$aid' ");
                if($addtable != '')
                {
                    $dsql->ExecuteNoneQuery("Delete From `$addtable` where aid='$aid' $whererecycle");
                }
                if($issystem != -1)
                {
                    $dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$aid' $whererecycle");
                }
                //删除相关附件
                if($cfg_upload_switch == 'Y')
                {
                    $dsql->Execute("me", "SELECT * FROM `#@__uploads` WHERE arcid = '$aid'");
                    while($row = $dsql->GetArray('me'))
                    {
                        $addfile = $row['url'];
                        $aid = $row['aid'];
                        $dsql->ExecuteNoneQuery("Delete From `#@__uploads` where aid = '$aid' ");
                        $upfile = $cfg_basedir.$addfile;
                        if(@file_exists($upfile)) @unlink($upfile);
                    }
                }
            }
        }
        //删除文本数据
        $filenameh = DEDEDATA."/textdata/".(ceil($aid/5000))."/{$aid}-".substr(md5($cfg_cookie_encode),0,16).".txt";
        if(@is_file($filenameh)) @unlink($filenameh);
        
    }
    
    if(empty($arcRow['money'])) $arcRow['money'] = 0;
    if(empty($arcRow['ismake'])) $arcRow['ismake'] = 1;
    if(empty($arcRow['arcrank'])) $arcRow['arcrank'] = 0;
    if(empty($arcRow['filename'])) $arcRow['filename'] = '';

    //删除HTML
    if($arcRow['ismake']==-1 || $arcRow['arcrank']!=0 || $arcRow['typeid']==0 || $arcRow['money']>0)
    {
        return TRUE;
    }

    //强制转换非多站点模式，以便统一方式获得实际HTML文件
    $GLOBALS['cfg_multi_site'] = 'N';
    $arcurl = GetFileUrl($arcRow['aid'],$arcRow['typeid'],$arcRow['senddate'],$arcRow['title'],$arcRow['ismake'],
                       $arcRow['arcrank'],$arcRow['namerule'],$arcRow['typedir'],$arcRow['money'],$arcRow['filename']);
    if(!preg_match("#\?#", $arcurl))
    {
        $htmlfile = GetTruePath().str_replace($GLOBALS['cfg_basehost'],'',$arcurl);
        if(file_exists($htmlfile) && !is_dir($htmlfile))
        {
            @unlink($htmlfile);
            $arcurls = explode(".", $htmlfile);
            $sname = $arcurls[count($arcurls)-1];
            $fname = preg_replace("#(\.$sname)$#", "", $htmlfile);
            for($i=2; $i<=100; $i++)
            {
                $htmlfile = $fname."_{$i}.".$sname;
                if( @file_exists($htmlfile) ) @unlink($htmlfile);
                else break;
            }
        }
    }

    return true;
}

//获取真实路径
function GetTruePath($siterefer='', $sitepath='')
{
    $truepath = $GLOBALS['cfg_basedir'];
    return $truepath;
}