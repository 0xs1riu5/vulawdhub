<?php
/**
 * @version        $Id: install.inc.php 1 13:41 2010年7月26日Z tianya $
 * @package        DedeCMS.Install
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
function RunMagicQuotes(&$str)
{
    if(!get_magic_quotes_gpc()) {
        if( is_array($str) )
            foreach($str as $key => $val) $str[$key] = RunMagicQuotes($val);
        else
            $str = addslashes($str);
    }
    return $str;
}

function gdversion()
{
  //没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
  if(!function_exists('phpinfo'))
  {
      if(function_exists('imagecreate')) return '2.0';
      else return 0;
  }
  else
  {
    ob_start();
    phpinfo(8);
    $module_info = ob_get_contents();
    ob_end_clean();
    if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info,$matches)) {   $gdversion_h = $matches[1];  }
    else {  $gdversion_h = 0; }
    return $gdversion_h;
  }
}

function GetBackAlert($msg,$isstop=0)
{
    global $s_lang;
    $msg = str_replace('"','`',$msg);
    if($isstop==1) $msg = "<script>\r\n<!--\r\n alert(\"{$msg}\");\r\n-->\r\n</script>\r\n";
    else $msg = "<script>\r\n<!--\r\n alert(\"{$msg}\");history.go(-1);\r\n-->\r\n</script>\r\n";
    $msg = "<meta http-equiv=content-type content='text/html; charset={$s_lang}'>\r\n".$msg;
    return $msg;
}


function TestWrite($d)
{
    $tfile = '_dedet.txt';
    $d = preg_replace("#\/$#", '', $d);
    $fp = @fopen($d.'/'.$tfile,'w');
    if(!$fp) return false;
    else
    {
        fclose($fp);
        $rs = @unlink($d.'/'.$tfile);
        if($rs) return true;
        else return false;
    }
}

function ReWriteConfigAuto()
{
    global $dsql;
    $configfile = DEDEDATA.'/config.cache.inc.php';
    if(!is_writeable($configfile))
    {
        echo "配置文件'{$configfile}'不支持写入，无法修改系统配置参数！";
        //ClearAllLink();
        exit();
    }
    $fp = fopen($configfile,'w');
    flock($fp,3);
    fwrite($fp,"<"."?php\r\n");
    $dsql->SetQuery("Select `varname`,`type`,`value`,`groupid` From `#@__sysconfig` order by aid asc ");
    $dsql->Execute();
    while($row = $dsql->GetArray())
    {
        if($row['type']=='number') fwrite($fp,"\${$row['varname']} = ".$row['value'].";\r\n");
        else fwrite($fp,"\${$row['varname']} = '".str_replace("'",'',$row['value'])."';\r\n");
    }
    fwrite($fp,"?".">");
    fclose($fp);
}

//更新栏目缓存
function UpDateCatCache()
{
    global $dsql,$cfg_multi_site;
    $cache1 = DEDEDATA."/cache/inc_catalog_base.inc";
    $dsql->SetQuery("Select id,reid,channeltype,issend From `#@__arctype`");
    $dsql->Execute();
    $fp1 = fopen($cache1,'w');
    $phph = '?';
    $fp1Header = "<{$phph}php\r\nglobal \$cfg_Cs;\r\n\$cfg_Cs=array();\r\n";
    fwrite($fp1,$fp1Header);
    while($row=$dsql->GetObject())
    {
        fwrite($fp1,"\$cfg_Cs[{$row->id}]=array({$row->reid},{$row->channeltype},{$row->issend});\r\n");
    }
    fwrite($fp1,"{$phph}>");
    fclose($fp1);
}

function IsDownLoad($url) 
{
	if(file_exists($url.'.xml')){
		return true;
	}else{
		return false;
	}
}