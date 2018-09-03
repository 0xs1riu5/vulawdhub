<?php
/**
 * 数据表信息查看
 *
 * @version        $Id: sys_sql_info.php 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('sys_Data');
if(empty($dopost)) $dopost = "";
$dbdoc = new MakeDBDocument;
$dbdoc->show();

class MakeDBDocument
{
    var $dsql;
    function __construct()
    {
        global $dsql;
        $this->dsql = $dsql;
    }
    //分析具体表
    function analyse_table(&$tableinfo, $tablename)
    {
        $flines = explode("\n", $tableinfo);
        $addinfo = $tbinfo = $tb_comment = '';
        $fields = array();
        foreach($flines as $line)
        {
            $line = trim($line);
            if( $line=='' ) continue;
            if( preg_match('/CREATE TABLE/i', $line) ) continue;
            if( !preg_match('/`/', $line) )
            {
                $arr = '';
                preg_match("/ENGINE=([a-z]*)(.*)DEFAULT CHARSET=([a-z0-9]*)/i", $line, $arr);
                $tbinfo = "ENGINE=".$arr[1].'/CHARSET='.$arr[3];
                $arr = '';
                preg_match("/comment='([^']*)'/i", $line, $arr);
                if( isset($arr[1]) )
                {
                    $tb_comment = $arr[1];
                }
                continue;
            }
            if( preg_match('/KEY/', $line) )
            {
                $addinfo .= $line."<br />\n";
            }
            else
            {
                $arr = '';
                $nline = preg_replace("/comment '([^']*)'/i", '', $line);
                preg_match("/`([^`]*)` (.*)[,]{0,1}$/U", $nline, $arr);
                $f = $arr[1];
                $fields[ $f ][0] = $arr[2];
                $fields[ $f ][1] = '';
                $arr = '';
                preg_match("/comment '([^']*)'/i", $line, $arr);
                if( isset($arr[1]) )
                {
                    $fields[ $f ][1] = $arr[1];
                }
            
            }
        }
        $tablehtml = "    <table width=\"960\" align=\"center\" border=\"0\" cellpadding=\"5\" cellspacing=\"1\" bgcolor=\"#C1D1A3\" style=\"font-size:14px;margin-bottom:10px\">
    <tr>
        <td height=\"34\" colspan=\"3\" bgcolor=\"#DDEDA5\">
        <a name=\"{$tablename}\"></a>
        <table width=\"90%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td width=\"29%\"><strong>表名：{$tablename}</strong> <br />($tbinfo)</td>
                <td width=\"71%\">说明：{$tb_comment}</td>
            </tr>
        </table></td>
    </tr>
    <tr>
        <td width=\"20%\" height=\"28\" bgcolor=\"#F7FDEA\">字段名</td>
        <td width=\"28%\" bgcolor=\"#F7FDEA\">说明描述</td>
        <td bgcolor=\"#F7FDEA\">具体参数</td>
    </tr>\n";
        foreach($fields as $k=>$v)
        {
            $tablehtml .= "    <tr height=\"24\" bgcolor=\"#FFFFFF\">
        <td><b>{$k}</b></td>
        <td>{$v[1]}</td>
        <td>{$v[0]}</td>
    </tr>\n";
        }
        $tablehtml .= "    <tr>
        <td height=\"28\" colspan=\"3\" bgcolor=\"#F7FDEA\">
        <b>索引：</b><br />
        {$addinfo}
        </td>
    </tr>
    </table>";
        return $tablehtml;
    }
    //列出数据库的所有表
    function show( $type='' )
    {
        $namehtml = $tablehtml = '';
        $this->dsql->Execute('me', ' SHOW TABLES; ');
        while( $row = $this->dsql->GetArray('me', MYSQL_NUM) )
        {
            // print_r($row);exit;
            $this->dsql->Execute('dd', " Show CREATE TABLE `{$row[0]}` ");
            $row2 = $this->dsql->GetArray('dd', MYSQL_NUM);
            
            if( $type=='' )
            {
                if( preg_match("/^cms_/", $row[0]) ) continue;
            }
            else
            {
                if( !preg_match("/^".$type."_/", $row[0]) ) continue;
            }
            
            $namehtml .= "<a href='#{$row[0]}'>{$row[0]}</a> | ";
            $tablehtml .= $this->analyse_table( $row2[1],  $row[0]);
        }
        $htmlhead = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\" />
<link href=\"css/base.css\" rel=\"stylesheet\" type=\"text/css\">
<style>
* {
    font-size:14px;
    font-family:Arial, \"宋休\", \"Courier New\";
}
a {
  text-decoration:none;
}
</style>
<title>数据库说明文档</title>
</head>
<body  background='images/allbg.gif' leftmargin='8' topmargin='8'>";
        echo $htmlhead;
        echo "<table align='center' width='960' style='margin-bottom:8px' ><tr><td>".$namehtml."</td></tr></table>";
        echo $tablehtml;
        echo "</body>\n</html>";
        exit();
    }//end show
}