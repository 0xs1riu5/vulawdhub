<?php
/**
 * 系统文件校验
 *
 * @version        $Id: sys_verifies.php 1 23:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
@set_time_limit(0);
require(DEDEINC.'/dedehttpdown.class.php');

$action = isset($action) ? trim($action) : '';

//当前软件版本锁定文件
$verLockFile = DEDEDATA.'/admin/ver.txt';
//当前软件指纹码锁定文件
$verifiesLockFile = DEDEDATA.'/admin/verifies.txt';

$fp = fopen($verLockFile,'r');
$upTime = trim(fread($fp,64));
fclose($fp);

$updateTime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);
$verifiesTime = "未同步过指纹码";
if(file_exists($verifiesLockFile))
{
    $fp = fopen($verifiesLockFile,'r');
    $upTime = trim(fread($fp,64));
    fclose($fp);
    $verifiesTime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);
}

$tmpdir = substr(md5($cfg_cookie_encode),0,16);


//重定义file_get_contents来兼容不支持此函数的PHP
//因为有个别地方用fgets读文件生成校验码不兼容
if(!function_exists('file_get_contents'))
{
    function file_get_contents($fname)
    {
        if(!file_exists($fname) || is_dir($fname))
        {
            return '';
        }
        else
        {
            $fp = fopen($fname, 'r');
            $ct = fread($fp, filesize($fname));
            fclose($fp);
            return $ct;
        }
    }
}

if($action == '')
{
    include(DEDEADMIN.'/templets/sys_verifies.htm');
    exit();
}
/*----------------
校验文件
function _verify() { }
----------------*/
else if($action == 'verify')
{
    $dsql->SetQuery("SELECT * FROM `#@__verifies` ");
    $dsql->Execute();
    $filelist = array();
    while($row = $dsql->GetArray())
    {
        $turefile = str_replace('../dede', '.', $row['filename']);
        //跳过不存在的文件
        if(!file_exists($turefile)) {
            continue;
        }
        if( filesize($turefile)==0 ) {
            continue;
        }
        $ct = file_get_contents($turefile);
        $ct = preg_replace("/\/\*\*[\r\n]{1,}(.*)[\r\n]{1,} \*\//sU", '', $ct);
        $cthash = md5($ct);
        if($cthash != $row['cthash']) 
        {
            $row['localhash'] = $cthash;
            $row['mtime'] = MyDate('Y-m-d H:i:s', filemtime($turefile));
            $row['turefile'] = $turefile;
            $filelist[] = $row;
        }
    }
    if(!isset($filelist[0]))
    {
        ShowMsg("所有文件都通过效验证，核心文件没有被改动过！","sys_verifies.php");
    }
    else
    {
        include(DEDEADMIN.'/templets/sys_verifies_verify.htm');
    }
    exit();
}
/*--------------------
查看单个本地文件
function _view() { }
----------------------*/
else if ($action == 'view')
{
    require_once(DEDEINC."/oxwindow.class.php");
    
    $filetxt = '';
    if( !preg_match("#data(.*)common.inc.php#i", $filename) )
    {
        $fp = fopen($filename, 'r');
        $filetxt = fread($fp, filesize($filename));
        fclose($fp);
    }
    
    $filetxt = str_replace('textarea', '!textarea', $filetxt);
    
    $wintitle = "文件效验::查看文件";
    $wecome_info = "<a href='sys_verifies.php'><u>文件效验</u></a>::查看文件";
    $win = new OxWindow();
    $win->Init();
    $win->AddTitle("以下为文件 $filename 的内容，请检查是否可疑：");
    $winform = $win->GetWindow("hand","<textarea name='filetxt' style='width:100%;height:450px;word-wrap: break-word;word-break:break-all;'>".$filetxt."</textarea>");
    $win->Display();
    exit();
}
/*-----------------
管理指纹码
function _manage() { }
-------------------*/
else if ($action == 'manage')
{
    $dsql->SetQuery("SELECT * FROM `#@__verifies` ");
    $dsql->Execute();
    $filelist = array();
    while($row = $dsql->GetArray())
    {
        $filelist[] = $row;
    }
    include(DEDEADMIN.'/templets/sys_verifies_manage.htm');
    exit();
}
/*-----------------------
下载文件
function _getfiles()
------------------------*/
else if ($action == 'getfiles')
{
    if(!isset($refiles))
    {
        ShowMsg("你没进行任何操作！","sys_verifies.php");
        exit();
    }
    $cacheFiles = DEDEDATA.'/modifytmp.inc';
    $fp = fopen($cacheFiles, 'w');
    fwrite($fp, '<'.'?php'."\r\n");
    fwrite($fp, '$tmpdir = "'.$tmpdir.'";'."\r\n");
    $dirs = array();
    $i = -1;
    $adminDir = preg_replace("#(.*)[\/\\\\]#", "", dirname(__FILE__));
    foreach($refiles as $filename)
    {
        $filename = substr($filename,3,strlen($filename)-3);
        if(preg_match("#^dede/#i", $filename)) 
        {
            $curdir = GetDirName( preg_replace("#^dede/#i", $adminDir.'/', $filename) );
        } else {
            $curdir = GetDirName($filename);
        }
        if( !isset($dirs[$curdir]) ) 
        {
            $dirs[$curdir] = TestIsFileDir($curdir);
        }
        $i++;
        fwrite($fp, '$files['.$i.'] = "'.$filename.'";'."\r\n");
    }
    fwrite($fp, '$fileConut = '.$i.';'."\r\n");
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
        
    $doneStr = "<iframe name='stafrm' src='sys_verifies.php?action=down&curfile=0' frameborder='0' id='stafrm' width='100%' height='100%'></iframe>\r\n";
    
    include(DEDEADMIN.'/templets/sys_verifies_getfiles.htm');
    
    exit();
}
/*-----------------------
下载文件
function _down()
------------------------*/
else if($action=='down')
{
    $cacheFiles = DEDEDATA.'/modifytmp.inc';
    require_once($cacheFiles);
    
    if($fileConut==-1 || $curfile > $fileConut)
    {
        ShowMsg("已下载所有文件<br /><a href='sys_verifies.php?action=apply'>[直接替换文件]</a> &nbsp; <a href='#'>[我自己手动替换文件]</a>","javascript:;");
        exit();
    }
    
    //检查临时文件保存目录是否可用
    MkTmpDir($tmpdir, $files[$curfile]);
        
    $downfile = $updateHost.$cfg_soft_lang.'/source/'.$files[$curfile];
        
    $dhd = new DedeHttpDown();
    $dhd->OpenUrl($downfile);
    $dhd->SaveToBin(DEDEDATA.'/'.$tmpdir.'/'.$files[$curfile]);
    $dhd->Close();
        
    ShowMsg("成功下载文件：{$files[$curfile]}； 继续下载下一个文件。","sys_verifies.php?action=down&curfile=".($curfile+1));
    exit();
}
/*-----------------------
修改效验方式
function _modify()
------------------------*/
else if($action=='modify')
{
    if(!isset($modifys))
    {
        ShowMsg("没选定要修改的文件！","-1");
        exit();
    }
    else
    {
        foreach($modifys as $fname)
        {
            if($method=='local')
            {
                $tureFilename = str_replace('../dede','./',$fname);
                if(file_exists($tureFilename))
                {
                    $fp = fopen($tureFilename,'r');
                    $ct = fread($fp,filesize($tureFilename));
                    fclose($fp);
                    $cthash = md5($ct);
                    $dsql->ExecuteNoneQuery("UPDATE `#@__verifies` SET `method`='local',cthash='$cthash' WHERE filename='$fname' ");
                }
            }
            else
            {
                $dsql->ExecuteNoneQuery("UPDATE `#@__verifies` SET `method`='offical' WHERE filename='$fname' ");
            }
        }
    }
    if($method=='local')
    {
        ShowMsg("成功修改指定文件的验证方式！","sys_verifies.php?action=manage");
    }
    else
    {
        ShowMsg("成功修改指定文件的验证方式！<br /> 由于你修改了文件为远程验证方式，因此需进行更新操作<br /> <a href='sys_verifies.php?action=update'>[更新]</a> &nbsp; <a href='sys_verifies.php?action=manage'>[返回]</a>","javascript:;");
    }
    exit();
}
/*-----------------------
还原文件
function _applyRecover()
------------------------*/
else if ($action == 'apply')
{
    $cacheFiles = DEDEDATA.'/modifytmp.inc';
    require_once($cacheFiles);
    $sDir = DEDEDATA."/$tmpdir";
    $tDir = DEDEROOT;
        
    $badcp = 0;
    $adminDir = preg_replace("#(.*)[\/\\\\]#", "", dirname(__FILE__));
        
    if(isset($files) && is_array($files))
    {
        foreach($files as $f)
        {
            if(preg_match("#^dede#", $f)) $tf = preg_replace("#^dede#", $adminDir, $f);
            else $tf = $f;

            if(file_exists($sDir.'/'.$f))
            {
                //还原文件前先进行文件效验
                $ct = file_get_contents($sDir.'/'.$f);
                $ct = preg_replace("/\/\*\*[\r\n]{1,}(.*)[\r\n]{1,} \*\//sU", '', $ct);
                $newhash = md5($ct);
                $row = $dsql->GetOne("SELECT * FROM `#@__verifies` WHERE filename='../{$f}' ");
                if(is_array($row) && $row['cthash'] != $newhash)
                {
                    $badcp++;
                } else {
                    $rs = @copy($sDir.'/'.$f, $tDir.'/'.$tf);
                    if($rs) unlink($sDir.'/'.$f);
                    else $badcp++;
                }
            }
        }
    }

    $badmsg = '！';
    if($badcp > 0)
    {
        $badmsg = "，其 {$badcp} 个文件效验码不正确或复制失败，<br />请从临时目录[../data/{$tmpdir}]中取出这几个文件手动还原。";
    }

    ShowMsg("成功完成还原指定文件{$badmsg}", "javascript:;");
    exit();
}
/*---------------
在线更新指纹码
function _update()
-----------------*/
else if($action == 'update')
{
    $rmFile = $updateHost.$cfg_soft_lang.'/verifys.txt';
    $dhd = new DedeHttpDown();
    $dhd->OpenUrl($rmFile);
    $ct = $dhd->GetHtml();
    $dhd->Close();
    $cts = split("[\r\n]{1,}",$ct);
    foreach($cts as $ct)
    {
        $ct = trim($ct);
        if(empty($ct)) continue;
        list($nameid, $cthash, $fname) = explode("\t", $ct);
        $row = $dsql->GetOne("SELECT * FROM `#@__verifies` WHERE nameid='$nameid' ");
        if(!is_array($row) || ($row['method']=='official' && $row['cthash']!=$cthash ))
        {
            $dsql->ExecuteNoneQuery("REPLACE INTO `#@__verifies`(nameid,cthash,method,filename) VALUES ('$nameid','$cthash','official','$fname'); ");
        }
    }
    $fp = fopen($verifiesLockFile,'w');
    fwrite($fp, MyDate('Ymd',time()));
    fclose($fp);
    ShowMsg("完成效验码更新，是否马上进行效验操作？<br /> <a href='sys_verifies.php?action=verify'>[开始效验]</a> &nbsp; <a href='sys_verifies.php?action=manage'>[管理]</a> &nbsp; <a href='sys_verifies.php'>[返回]</a>","javascript:;");
    exit();
}
/*-----------------
生成指纹码
function _make() { }
-------------------*/
else if ($action == 'make')
{
    $fp = fopen(DEDEROOT.'/../verifys.txt','w');
    foreach (preg_ls ('../', TRUE, "/.*\.(php|htm|html|js)$/i", 'CVS,data,html,uploads,templets,special') as $onefile)
    {
        $nameid = md5($onefile);
        $ctbody = file_get_contents(DEDEADMIN.'/'.$onefile);
        $ctbody = preg_replace("/\/\*\*[\r\n]{1,}(.*)[\r\n]{1,} \*\//sU", '', $ctbody);
        $cthash = md5($ctbody);
        fwrite($fp,"{$nameid}\t{$cthash}\t{$onefile}\r\n");
    }
    fclose($fp);
    ShowMsg("操作成功！","sys_verifies.php");
    exit();
}

//获取所有文件列表
function preg_ls($path=".", $rec=FALSE, $pat="/.*/", $ignoredir='')
{
    while (substr ($path,-1,1) =="/")
    {
        $path=substr ($path,0,-1);
    }
    if (!is_dir ($path) )
    {
        $path=dirname ($path);
    }
    if ($rec!==TRUE)
    {
        $rec=FALSE;
    }
    $d=dir ($path);
    $ret=Array ();
    while (FALSE!== ($e=$d->read () ) )
    {
        if ( ($e==".") || ($e=="..") )
        {
            continue;
        }
        if ($rec && is_dir ($path."/".$e) && ($ignoredir == '' || strpos($ignoredir,$e ) === FALSE))
        {
            $ret = array_merge ($ret, preg_ls($path."/".$e, $rec, $pat, $ignoredir));
            continue;
        }
        if (!preg_match ($pat, $e) )
        {
            continue;
        }
        $ret[] = $path."/".$e;
    }
    return (empty ($ret) && preg_match ($pat,basename($path))) ? Array ($path."/") : $ret;
}

function TestWriteAble($d)
{
    $tfile = '_dedet.txt';
    $fp = @fopen($d.$tfile, 'w');
    if(!$fp) 
    {
        return FALSE;
    }
    else {
        fclose($fp);
        $rs = @unlink($d.'/'.$tfile);
        return TRUE;
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
    $dirs = array('name'=>'','isdir'=>FALSE,'writeable'=>FALSE);
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
    $dirname = preg_replace("#([^\/]*)$#", "", $dirname);
    if(!is_dir($basedir)) 
    {
        mkdir($basedir, 0777);
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
            mkdir($curdir,0777) or die($curdir);
        }
    }
    return TRUE;
}