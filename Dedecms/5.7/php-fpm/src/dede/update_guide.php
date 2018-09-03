<?php
/**
 * 本文件用于从镜像服务器获取升级信息与文件
 * 并由用户自行控制升级
 *
 * @version        $Id: vote_main.php 1 23:54 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__).'/config.php');
CheckPurview('sys_Edit');
@set_time_limit(0);
require(DEDEINC.'/inc/inc_fun_funAdmin.php');
require(DEDEINC.'/dedehttpdown.class.php');
 
function TestWriteAble($d)
{
    $tfile = '_dedet.txt';
    $fp = @fopen($d.$tfile,'w');
    if(!$fp) {
        return false;
    }
    else {
        fclose($fp);
        $rs = @unlink($d.'/'.$tfile);
        return true;
    }
}

function GetDirName($filename)
{
    $dirname = '../'.preg_replace("#[\\\\\/]{1,}#", '/', $filename);
    $dirname = preg_replace("#([^\/]*)$#", '', $dirname);
    return $dirname;
}

function TestIsFileDir($dirname)
{
    $dirs = array('name'=>'', 'isdir'=>FALSE, 'writeable'=>FALSE);
    $dirs['name'] =  $dirname;
    if(is_dir($dirname))
    {
        $dirs['isdir'] = TRUE;
        $dirs['writeable'] = TestWriteAble($dirname);
    }
    return $dirs;
}

function MkTmpDir($tmpdir,$filename)
{
    $basedir = DEDEDATA.'/'.$tmpdir;
    $dirname = trim(preg_replace("#[\\\\\/]{1,}#", '/', $filename));
    $dirname = preg_replace("#([^\/]*)$#","",$dirname);
    if(!is_dir($basedir)) 
    {
        mkdir($basedir,0777);
    }
    if($dirname=='') 
    {
        return TRUE;
    }
    $dirs = explode('/', $dirname);
    $curdir = $basedir;
    foreach($dirs as $d)
    {
        $d = trim($d);
        if(empty($d)) continue;
        $curdir = $curdir.'/'.$d;
        if(!is_dir($curdir)) 
        {
            mkdir($curdir, 0777) or die($curdir);
        }
    }
    return TRUE;
}

if(empty($dopost)) $dopost = 'test';

//当前软件版本锁定文件
$verLockFile = DEDEDATA.'/admin/ver.txt';

$fp = fopen($verLockFile,'r');
$upTime = trim(fread($fp,64));
fclose($fp);
$oktime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);

/**
用AJAX获取最新版本信息
function _Test() {  }
*/
if($dopost=='test')
{
    AjaxHead();
    //下载远程数据
    $dhd = new DedeHttpDown();
    $dhd->OpenUrl($updateHost.'/verinfo.txt');
    $verlist = trim($dhd->GetHtml());
    $dhd->Close();
    if($cfg_soft_lang=='utf-8') 
    {
        $verlist = gb2utf8($verlist);
    }
    $verlist = preg_replace("#[\r\n]{1,}#", "\n", $verlist);
    $verlists = explode("\n", $verlist);
    
    //分析数据
    $updateVers = array();
    $upitems = $lastTime = '';
    $n = 0;
    foreach($verlists as $verstr)
    {
        if( empty($verstr) || preg_match("#^\/\/#", $verstr) ) 
        {
            continue ;
        }
        list($vtime, $vlang, $issafe, $vmsg) = explode(',', $verstr);
        $vtime = trim($vtime);
        $vlang = trim($vlang);
        $issafe = trim($issafe);
        $vmsg = trim($vmsg);
        if($vtime > $upTime && $vlang==$cfg_soft_lang)
        {
            $updateVers[$n]['issafe'] = $issafe;
            $updateVers[$n]['vmsg'] = $vmsg;
            $upitems .= ($upitems=='' ? $vtime : ','.$vtime);
            $lastTime = $vtime;
            $updateVers[$n]['vtime'] = substr($vtime,0,4).'-'.substr($vtime,4,2).'-'.substr($vtime,6,2);
            $n++;
        }
    }
        
    //echo "<xmp>";
    //判断是否需要更新，并返回适合的结果
    if($n==0)
    {
        $offUrl = SpGetNewInfo();
        echo "<div class='updatedvt'><b>你系统版本最后更新时间为：{$oktime}，当前没有可用的更新</b></div>\r\n";
        echo "<iframe name='stafrm' src='{$offUrl}&uptime={$oktime}' frameborder='0' id='stafrm' width='100%' height='50'></iframe>";
    }
    else
    {
        echo "<div style='width:98%'><form name='fup' action='update_guide.php' method='post' onsubmit='ShowWaitDiv()'>\r\n";
        echo "<input type='hidden' name='dopost' value='getlist' />\r\n";
        echo "<input type='hidden' name='vtime' value='$lastTime' />\r\n";
        echo "<input type='hidden' name='upitems' value='$upitems' />\r\n";
        echo "<div class='upinfotitle'>你系统版本最后更新时间为：{$oktime}，当前可用的更新有：</div>\r\n";
        foreach($updateVers as $vers)
        {
            $style = '';
            if($vers['issafe']==1) 
            {
                $style = "color:red;";
            }
            echo "<div style='{$style}' class='verline'>【".($vers['issafe']==1 ? "安全更新" : "普通更新")."】";
            echo $vers['vtime']."，更新说明：{$vers['vmsg']}</div>\r\n";
        }
        echo "<div style='line-height:32px'><input type='submit' name='sb1' value=' 点击此获取所有更新文件，然后选择安装 ' class='np coolbg' style='cursor:pointer' />\r\n";
        echo " &nbsp; <input type='button' name='sb2' value=' 忽略这些更新 ' onclick='SkipReload({$lastTime})' class='np coolbg'  style='cursor:pointer' /></div>\r\n";
        echo "</form></div>";
    }
    //echo "</xmp>";
    exit();
}
/**
忽略某个日期前的升级
function _Skip() {  }
*/
else if($dopost=='skip')
{
    AjaxHead();
    $fp = fopen($verLockFile, 'w');
    fwrite($fp, $vtime);
    fclose($fp);
    $offUrl = SpGetNewInfo();
    echo "<div class='updatedvt'><b>你系统版本最后更新时间为：{$oktime}，当前没有可用的更新。</b></div>\r\n";
    echo "<iframe name='stafrm' src='{$offUrl}&uptime={$oktime}' frameborder='0' id='stafrm' width='100%' height='60'></iframe>";
    exit();
}
else if($dopost=='skipback')
{
    $fp = fopen($verLockFile, 'w');
    fwrite($fp, $vtime);
    fclose($fp);
    ShowMsg("成功跳过这些更新！", "index_body.php");
    exit();
}
/**
获取升级文件列表
function _GetList() {  }
*/
else if($dopost=='getlist')
{
    $upitemsArr = explode(',', $upitems);
    rsort($upitemsArr);
    
    $tmpdir = substr(md5($cfg_cookie_encode), 0, 16);
    
    $dhd = new DedeHttpDown();
    $fileArr = array();
    $f = 0;
    foreach($upitemsArr as $upitem)
    {
        $durl = $updateHost.$cfg_soft_lang.'/'.$upitem.'.file.txt';
        $dhd->OpenUrl($durl);
        $filelist = $dhd->GetHtml();
        $filelist = trim( preg_replace("#[\r\n]{1,}#", "\n", $filelist) );
        if(!empty($filelist))
        {
            $filelists = explode("\n", $filelist);
            foreach($filelists as $filelist)
            {
                $filelist = trim($filelist);
                if(empty($filelist)) continue;
                $fs = explode(',', $filelist);
                if( empty($fs[1]) ) 
                {
                    $fs[1] = $upitem." 常规功能更新文件";
                }
                if(!isset($fileArr[$fs[0]])) 
                {
                    $fileArr[$fs[0]] = $upitem." ".trim($fs[1]);
                    $f++;
                }
            }
        }
    }
    $dhd->Close();
    
    $allFileList = '';
    if($f==0)
    {
        $allFileList = "<font color='green'><b>没发现可用的文件列表信息，可能是官方服务器存在问题，请稍后再尝试！</b></font>";
    }
    else
    {
        $allFileList .= "<div style='width:98%'><form name='fup' action='update_guide.php' method='post'>\r\n";
        $allFileList .= "<input type='hidden' name='vtime' value='$vtime' />\r\n";
        $allFileList .= "<input type='hidden' name='dopost' value='getfiles' />\r\n";
        $allFileList .= "<input type='hidden' name='upitems' value='$upitems' />\r\n";
        $allFileList .= "<div class='upinfotitle'>以下是需要下载的更新文件（路径相对于DedeCMS的根目录）：</div>\r\n";
        $filelists = explode("\n",$filelist);
        foreach($fileArr as $k=>$v) 
        {
            $allFileList .= "<div class='verline'><input type='checkbox' name='files[]' value='{$k}'  checked='checked' /> $k({$v})</div>\r\n";
        }
        $allFileList .= "<div class='verline'>";
        $allFileList .= "文件临时存放目录：../data/<input type='text' name='tmpdir' style='width:200px' value='$tmpdir' /><br />\r\n";
        $allFileList .= "<input type='checkbox' name='skipnodir' value='1'  checked='checked' /> 跳过系统中没有的文件夹(通常是可选模块的补丁)</div>\r\n";
        $allFileList .= "<div style='line-height:36px;background:#F8FEDA'>&nbsp;\r\n";
        $allFileList .= "<input type='submit' name='sb1' value=' 下载并应用这些补丁 ' class='np coolbg' style='cursor:pointer' />\r\n";
        $allFileList .="</form></div>";
    }
    
    include DedeInclude('templets/update_guide_getlist.htm');
    exit();
}
/**
下载文件（保存需下载内容列表）
function _GetFiles() {  }
*/
else if($dopost=='getfilesstart')
{
    //update_guide.php?dopost=down&curfile=0
    $msg = "如果检测时发现你没安装模块的文件夹有错误，可不必理会<br />";
    $msg .= "<a href=update_guide.php?dopost=down&curfile=0>确认目录状态都正常后，请点击开始下载文件&gt;&gt;</a><br />";
    ShowMsg($msg,"javascript:;");
    exit();
}
else if($dopost=='getfiles')
{
    $cacheFiles = DEDEDATA.'/cache/updatetmp.inc';
    $skipnodir = (isset($skipnodir) ? 1 : 0);
    $adminDir = preg_replace("#(.*)[\/\\\\]#", "", dirname(__FILE__));
    
    if(!isset($files))
    {
        $doneStr = "<p align='center' style='color:red'><br />你没有指定任何需要下载更新的文件，是否跳过这些更新？<br /><br />";
        $doneStr .= "<a href='update_guide.php?dopost=skipback&vtime=$vtime' class='np coolbg'>[跳过这些更新]</a> &nbsp; ";
        $doneStr .= "<a href='index_body.php'  class='np coolbg'>[保留提示以后再进行操作]</a></p>";
    }
    else
    {
        $fp = fopen($cacheFiles, 'w');
        fwrite($fp, '<'.'?php'."\r\n");
        fwrite($fp, '$tmpdir = "'.$tmpdir.'";'."\r\n");
        fwrite($fp, '$vtime = '.$vtime.';'."\r\n");
        $dirs = array();
        $i = -1;
        foreach($files as $filename)
        {
            $tfilename = $filename;
            if( preg_match("#^dede\/#i", $filename) ) 
            {
                $tfilename = preg_replace("#^dede\/#", $adminDir.'/', $filename);
            }
            $curdir = GetDirName($tfilename);
            if( !isset($dirs[$curdir]) ) 
            {
                $dirs[$curdir] = TestIsFileDir($curdir);
            }
            if($skipnodir==1 && $dirs[$curdir]['isdir'] == FALSE) 
            {
                continue;
            }
            else {
                @mkdir($curdir, 0777);
                $dirs[$curdir] = TestIsFileDir($curdir);
            }
            $i++;
            fwrite($fp, '$files['.$i.'] = "'.$filename.'";'."\r\n");
        }
        fwrite($fp, '$fileConut = '.$i.';'."\r\n");
        
        $items = explode(',', $upitems);
        foreach($items as $sqlfile)
        {
            fwrite($fp,'$sqls[] = "'.$sqlfile.'.sql";'."\r\n");
        }
        fwrite($fp, '?'.'>');
        fclose($fp);
        
        $dirinfos = '';
        if($i > -1)
        {
            $dirinfos = '<tr bgcolor="#ffffff"><td colspan="2">';
            $dirinfos .= "本次升级需要在下面文件夹写入更新文件，请注意文件夹是否有写入权限：<br />\r\n";
            foreach($dirs as $curdir)
            {
                $dirinfos .= $curdir['name']." 状态：".($curdir['writeable'] ? "[√正常]" : "<font color='red'>[×不可写]</font>")."<br />\r\n";
            }
            $dirinfos .= "</td></tr>\r\n";
        }
        
        $doneStr = "<iframe name='stafrm' src='update_guide.php?dopost=getfilesstart' frameborder='0' id='stafrm' width='100%' height='100%'></iframe>\r\n";
    }
    include DedeInclude('templets/update_guide_getfiles.htm');
    exit();
}
/**
下载文件，具体操作步骤
function _Down() {  }
*/
else if($dopost=='down')
{
    $cacheFiles = DEDEDATA.'/cache/updatetmp.inc';
    require_once($cacheFiles);
    
    if(empty($startup))
    {
        if($fileConut==-1 || $curfile > $fileConut)
        {
            ShowMsg("已下载所有文件，开始下载数据库升级文件...","update_guide.php?dopost=down&startup=1");
            exit();
        }
        
        //检查临时文件保存目录是否可用
        MkTmpDir($tmpdir, $files[$curfile]);
        
        $downfile = $updateHost.$cfg_soft_lang.'/source/'.$files[$curfile];
        
        $dhd = new DedeHttpDown();
        $dhd->OpenUrl($downfile);
        $dhd->SaveToBin(DEDEDATA.'/'.$tmpdir.'/'.$files[$curfile]);
        $dhd->Close();
        
        ShowMsg("成功下载并保存文件：{$files[$curfile]}； 继续下载下一个文件。","update_guide.php?dopost=down&curfile=".($curfile+1));
        exit();
    }
    else
    {
        MkTmpDir($tmpdir, 'sql.txt');
        $dhd = new DedeHttpDown();
        $ct = '';
        foreach($sqls as $sql)
        {
            $downfile = $updateHost.$cfg_soft_lang.'/'.$sql;
            $dhd->OpenUrl($downfile);
            $ct .= $dhd->GetHtml();
        }
        $dhd->Close();
        $truefile = DEDEDATA.'/'.$tmpdir.'/sql.txt';
        $fp = fopen($truefile, 'w');
        fwrite($fp, $ct);
        fclose($fp);

        ShowMsg("完成所有远程文件获取操作：<a href='update_guide.php?dopost=apply'>&lt;&lt;点击此开始直接升级&gt;&gt;</a><br />你也可以直接使用[../data/{$tmpdir}]目录的文件手动升级。","javascript:;");

        exit();
    }
    exit();
}
/**
应用升级
function _ApplyUpdate() {  }
*/
else if($dopost=='apply')
{
    $cacheFiles = DEDEDATA.'/cache/updatetmp.inc';
    require_once($cacheFiles);
    
    if(empty($step))
    {
        $truefile = DEDEDATA.'/'.$tmpdir.'/sql.txt';
        $fp = fopen($truefile, 'r');
        $sql = @fread($fp, filesize($truefile));
        fclose($fp);
        if(!empty($sql))
        {
            $mysql_version = $dsql->GetVersion(true);
            
            $sql = preg_replace('#ENGINE=MyISAM#i', 'TYPE=MyISAM', $sql);
            $sql41tmp = 'ENGINE=MyISAM DEFAULT CHARSET='.$cfg_db_language;
            if($mysql_version >= 4.1) 
            {
                $sql = preg_replace('#TYPE=MyISAM#i', $sql41tmp, $sql);
            }
            
            $sqls = explode(";\r\n", $sql);
            foreach($sqls as $sql)
            {
                if(trim($sql)!='') 
                {
                    $dsql->ExecuteNoneQuery(trim($sql));
                }
            }
        }
        ShowMsg("完成数据库更新，现在开始复制文件。","update_guide.php?dopost=apply&step=1");
        exit();
    }
    else
    {
        $sDir = DEDEDATA."/$tmpdir";
        $tDir = DEDEROOT;
        
        $badcp = 0;
        $adminDir = preg_replace("#(.*)[\/\\\\]#", "", dirname(__FILE__));
        
        if(isset($files) && is_array($files))
        {
            foreach($files as $f)
            {
                if(preg_match('#^dede#', $f)) 
                {
                    $tf = preg_replace('#^dede#', $adminDir, $f);
                }
                else {
                    $tf = $f;
                }
                if(file_exists($sDir.'/'.$f))
                {
                    $rs = @copy($sDir.'/'.$f, $tDir.'/'.$tf);
                    if($rs) {
                        unlink($sDir.'/'.$f);
                    }
                    else {
                        $badcp++;
                    }
                }
            }
        }
        
        $fp = fopen($verLockFile,'w');
        fwrite($fp,$vtime);
        fclose($fp);
        
        $badmsg = '！';
        if($badcp > 0)
        {
            $badmsg = "，其中失败 {$badcp} 个文件，<br />请从临时目录[../data/{$tmpdir}]中取出这几个文件手动升级。";
        }
        
        ShowMsg("成功完成升级{$badmsg}","javascript:;");
        exit();
    }
}