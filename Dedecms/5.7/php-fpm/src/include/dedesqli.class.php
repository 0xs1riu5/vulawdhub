<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 数据库类
 * 说明:系统底层数据库核心类
 *      调用这个类前,请先设定这些外部变量
 *      $GLOBALS['cfg_dbhost'];
 *      $GLOBALS['cfg_dbuser'];
 *      $GLOBALS['cfg_dbpwd'];
 *      $GLOBALS['cfg_dbname'];
 *      $GLOBALS['cfg_dbprefix'];
 *
 * @version        $Id: dedesqli.class.php 1 15:00 2011-1-21 tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
@set_time_limit(0);
// 在工程所有文件中均不需要单独初始化这个类，可直接用 $dsql 或 $db 进行操作
// 为了防止错误，操作完后不必关闭数据库
$dsql = $dsqli = $db = new DedeSqli(FALSE);
/**
 * Dede MySQLi数据库类
 *
 * @package        DedeSqli
 * @subpackage     DedeCMS.Libraries
 * @link           http://www.dedecms.com
 */
class DedeSqli
{
    var $linkID;
    var $dbHost;
    var $dbUser;
    var $dbPwd;
    var $dbName;
    var $dbPrefix;
    var $result;
    var $queryString;
    var $parameters;
    var $isClose;
    var $safeCheck;

    //用外部定义的变量初始类，并连接数据库
    function __construct($pconnect=FALSE,$nconnect=TRUE)
    {
        $this->isClose = FALSE;
        $this->safeCheck = TRUE;
        if($nconnect)
        {
            $this->Init($pconnect);
        }
    }

    function DedeSql($pconnect=FALSE,$nconnect=TRUE)
    {
        $this->__construct($pconnect,$nconnect);
    }

    function Init($pconnect=FALSE)
    {
        $this->linkID = 0;
        $this->queryString = '';
        $this->parameters = Array();
        $this->dbHost   =  $GLOBALS['cfg_dbhost'];
        $this->dbUser   =  $GLOBALS['cfg_dbuser'];
        $this->dbPwd    =  $GLOBALS['cfg_dbpwd'];
        $this->dbName   =  $GLOBALS['cfg_dbname'];
        $this->dbPrefix =  $GLOBALS['cfg_dbprefix'];
        $this->result["me"] = 0;
        $this->Open($pconnect);
    }

    //用指定参数初始数据库信息
    function SetSource($host,$username,$pwd,$dbname,$dbprefix="dede_")
    {
        $this->dbHost = $host;
        $this->dbUser = $username;
        $this->dbPwd = $pwd;
        $this->dbName = $dbname;
        $this->dbPrefix = $dbprefix;
        $this->result["me"] = 0;
    }
    function SelectDB($dbname)
    {
        mysql_select_db($dbname);
    }

    //设置SQL里的参数
    function SetParameter($key,$value)
    {
        $this->parameters[$key]=$value;
    }

    //连接数据库
    function Open($pconnect=FALSE)
    {
        global $dsqli;
        //连接数据库
        if($dsqli && !$dsqli->isClose)
        {
            $this->linkID = $dsqli->linkID;
        }
        else
        {
            $i = 0;
            @list($dbhost, $dbport) = explode(':', $this->dbHost);
            !$dbport && $dbport = 3306;
            
            $this->linkID = mysqli_init();
            mysqli_real_connect($this->linkID, $dbhost, $this->dbUser, $this->dbPwd, false, $dbport);
            mysqli_errno($this->linkID) != 0 && $this->DisplayError('DedeCms错误警告： 链接('.$this->pconnect.') 到MySQL发生错误');


            //复制一个对象副本
            CopySQLiPoint($this);
        }

        //处理错误，成功连接则选择数据库
        if(!$this->linkID)
        {
            $this->DisplayError("DedeCms错误警告：<font color='red'>连接数据库失败，可能数据库密码不对或数据库服务器出错！</font>");
            exit();
        }
        $serverinfo = mysqli_get_server_info($this->linkID);
        if ($serverinfo > '4.1' && $GLOBALS['cfg_db_language']) 
        {
            mysqli_query($this->linkID, "SET character_set_connection=" . $GLOBALS['cfg_db_language'] . ",character_set_results=" . $GLOBALS['cfg_db_language'] . ",character_set_client=binary");
        }
        if ($serverinfo > '5.0') {
            mysqli_query($this->linkID, "SET sql_mode=''");
        }
        if ($this->dbName && !@mysqli_select_db($this->linkID, $this->dbName)) {
            $this->DisplayError('无法使用数据库');
        }
        return TRUE;
    }
    
    //为了防止采集等需要较长运行时间的程序超时，在运行这类程序时设置系统等待和交互时间
    function SetLongLink()
    {
        @mysqli_query("SET interactive_timeout=3600, wait_timeout=3600 ;", $this->linkID);
    }

    //获得错误描述
    function GetError()
    {
        $str = mysql_error();
        return $str;
    }

    //关闭数据库
    //mysql能自动管理非持久连接的连接池
    //实际上关闭并无意义并且容易出错，所以取消这函数
    function Close($isok=FALSE)
    {
        $this->FreeResultAll();
        if($isok)
        {
            @mysqli_close($this->linkID);
            $this->isClose = TRUE;
            $GLOBALS['dsql'] = NULL;
        }
    }

    //定期清理死连接
    function ClearErrLink()
    {
    }

    //关闭指定的数据库连接
    function CloseLink($dblink)
    {
        @mysqli_close($dblink);
    }
    
    function Esc( $_str ) 
    {
        if ( version_compare( phpversion(), '4.3.0', '>=' ) ) 
        {
            return @mysqli_real_escape_string($this->linkID, $_str );
        } else {
            return @mysqli_escape_string ($this->linkID, $_str );
        }
    }

    //执行一个不返回结果的SQL语句，如update,delete,insert等
    function ExecuteNoneQuery($sql='')
    {
        global $dsqli;
        if($dsqli->isClose)
        {
            $this->Open(FALSE);
            $dsqli->isClose = FALSE;
        }
        if(!empty($sql))
        {
            $this->SetQuery($sql);
        }else{
            return FALSE;
        }
        if(is_array($this->parameters))
        {
            foreach($this->parameters as $key=>$value)
            {
                $this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
            }
        }
        //SQL语句安全检查
        if($this->safeCheck) CheckSql($this->queryString,'update');
        return mysqli_query($this->linkID, $this->queryString);
    }


    //执行一个返回影响记录条数的SQL语句，如update,delete,insert等
    function ExecuteNoneQuery2($sql='')
    {
        global $dsqli;
        if($dsqli->isClose)
        {
            $this->Open(FALSE);
            $dsqli->isClose = FALSE;
        }

        if(!empty($sql))
        {
            $this->SetQuery($sql);
        }
        if(is_array($this->parameters))
        {
            foreach($this->parameters as $key=>$value)
            {
                $this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
            }
        }
        mysqli_query($this->linkID, $this->queryString);
        return mysqli_affected_rows($this->linkID);
    }

    function ExecNoneQuery($sql='')
    {
        return $this->ExecuteNoneQuery($sql);
    }
    
    function GetFetchRow($id='me')
    {
        return @mysqli_fetch_row($this->result[$id]);
    }
    
    function GetAffectedRows()
    {
        return mysqli_affected_rows($this->linkID);
    }

    //执行一个带返回结果的SQL语句，如SELECT，SHOW等
    function Execute($id="me", $sql='')
    {
        global $dsqli;
        if($dsqli->isClose)
        {
            $this->Open(FALSE);
            $dsqli->isClose = FALSE;
        }
        if(!empty($sql))
        {
            $this->SetQuery($sql);
        }

        //SQL语句安全检查
        if($this->safeCheck)
        {
            CheckSql($this->queryString);
        }
    
        $t1 = ExecTime();
        
        $this->result[$id] = mysqli_query($this->linkID, $this->queryString);
        
        //$queryTime = ExecTime() - $t1;
        //查询性能测试
        //if($queryTime > 0.05) {
            //echo $this->queryString."--{$queryTime}<hr />\r\n"; 
        //}
        
        if($this->result[$id]===FALSE)
        {
            $this->DisplayError(mysqli_error($this->linkID)." <br />Error sql: <font color='red'>".$this->queryString."</font>");
        }
    }

    function Query($id="me",$sql='')
    {
        $this->Execute($id,$sql);
    }

    //执行一个SQL语句,返回前一条记录或仅返回一条记录
    function GetOne($sql='',$acctype=MYSQLI_ASSOC)
    {
        global $dsqli;
        if($dsqli->isClose)
        {
            $this->Open(FALSE);
            $dsqli->isClose = FALSE;
        }
        if(!empty($sql))
        {
            if(!preg_match("/LIMIT/i",$sql)) $this->SetQuery(preg_replace("/[,;]$/i", '', trim($sql))." LIMIT 0,1;");
            else $this->SetQuery($sql);
        }
        $this->Execute("one");
        $arr = $this->GetArray("one", $acctype);
        if(!is_array($arr))
        {
            return '';
        }
        else
        {
            @mysqli_free_result($this->result["one"]); return($arr);
        }
    }

    //执行一个不与任何表名有关的SQL语句,Create等
    function ExecuteSafeQuery($sql,$id="me")
    {
        global $dsqli;
        if($dsqli->isClose)
        {
            $this->Open(FALSE);
            $dsqli->isClose = FALSE;
        }
        $this->result[$id] = @mysqli_query($sql,$this->linkID);
    }

    //返回当前的一条记录并把游标移向下一记录
    // MYSQLI_ASSOC、MYSQLI_NUM、MYSQLI_BOTH
    function GetArray($id="me",$acctype=MYSQLI_ASSOC)
    {
        // var_dump($this->result);
        if($this->result[$id]===0)
        {
            return FALSE;
        }
        else
        {
            return @mysqli_fetch_array($this->result[$id], $acctype);
        }
    }

    function GetObject($id="me")
    {
        if($this->result[$id]===0)
        {
            return FALSE;
        }
        else
        {
            return mysqli_fetch_object($this->result[$id]);
        }
    }

    // 检测是否存在某数据表
    function IsTable($tbname)
    {
        $prefix="#@__";
        $tbname = str_replace($prefix, $this->dbPrefix, $tbname);
        if( mysqli_num_rows( @mysqli_query($this->linkID, "SHOW TABLES LIKE '".$tbname."'")))
        {
            return TRUE;
        }
        return FALSE;
    }

    //获得MySql的版本号
    function GetVersion($isformat=TRUE)
    {
        global $dsqli;
        if($dsqli->isClose)
        {
            $this->Open(FALSE);
            $dsqli->isClose = FALSE;
        }
        $rs = mysqli_query($this->linkID, "SELECT VERSION();");
        $row = mysqli_fetch_array($rs);
        $mysql_version = $row[0];
        mysqli_free_result($rs);
        if($isformat)
        {
            $mysql_versions = explode(".",trim($mysql_version));
            $mysql_version = number_format($mysql_versions[0].".".$mysql_versions[1],2);
        }
        return $mysql_version;
    }

    //获取特定表的信息
    function GetTableFields($tbname, $id="me")
    {
        $prefix="#@__";
        $tbname = str_replace($prefix, $this->dbPrefix, $tbname);
        $query = "SELECT * FROM {$tbname} LIMIT 0,1";
        $this->result[$id] = mysqli_query($this->linkID, $query);
    }

    //获取字段详细信息
    function GetFieldObject($id="me")
    {
        return mysqli_fetch_field($this->result[$id]);
    }

    //获得查询的总记录数
    function GetTotalRow($id="me")
    {
        if($this->result[$id]===0)
        {
            return -1;
        }
        else
        {
            return @mysqli_num_rows($this->result[$id]);
        }
    }

    //获取上一步INSERT操作产生的ID
    function GetLastID()
    {
        //如果 AUTO_INCREMENT 的列的类型是 BIGINT，则 mysqli_insert_id() 返回的值将不正确。
        //可以在 SQL 查询中用 MySQL 内部的 SQL 函数 LAST_INSERT_ID() 来替代。
        //$rs = mysqli_query($this->linkID, "Select LAST_INSERT_ID() as lid");
        //$row = mysqli_fetch_array($rs);
        //return $row["lid"];
        return mysqli_insert_id($this->linkID);
    }

    //释放记录集占用的资源
    function FreeResult($id="me")
    {
        @mysqli_free_result($this->result[$id]);
    }
    function FreeResultAll()
    {
        if(!is_array($this->result))
        {
            return '';
        }
        foreach($this->result as $kk => $vv)
        {
            if($vv)
            {
                @mysqli_free_result($vv);
            }
        }
    }

    //设置SQL语句，会自动把SQL语句里的#@__替换为$this->dbPrefix(在配置文件中为$cfg_dbprefix)
    function SetQuery($sql)
    {
        $prefix="#@__";
        $sql = str_replace($prefix,$this->dbPrefix,$sql);
        $this->queryString = $sql;
    }

    function SetSql($sql)
    {
        $this->SetQuery($sql);
    }

    //显示数据链接错误信息
    function DisplayError($msg)
    {
        $errorTrackFile = dirname(__FILE__).'/../data/mysqli_error_trace.inc';
        if( file_exists(dirname(__FILE__).'/../data/mysqli_error_trace.php') )
        {
            @unlink(dirname(__FILE__).'/../data/mysqli_error_trace.php');
        }
        $emsg = '';
        $emsg .= "<div><h3>DedeCMS Error Warning!</h3>\r\n";
        $emsg .= "<div><a href='http://bbs.dedecms.com' target='_blank' style='color:red'>Technical Support: http://bbs.dedecms.com</a></div>";
        $emsg .= "<div style='line-helght:160%;font-size:14px;color:green'>\r\n";
        $emsg .= "<div style='color:blue'><br />Error page: <font color='red'>".$this->GetCurUrl()."</font></div>\r\n";
        $emsg .= "<div>Error infos: {$msg}</div>\r\n";
        $emsg .= "<br /></div></div>\r\n";
        
        echo $emsg;
        
        $savemsg = 'Page: '.$this->GetCurUrl()."\r\nError: ".$msg;
        //保存MySql错误日志
        $fp = @fopen($errorTrackFile, 'a');
        @fwrite($fp, '<'.'?php  exit();'."\r\n/*\r\n{$savemsg}\r\n*/\r\n?".">\r\n");
        @fclose($fp);
    }
    
    //获得当前的脚本网址
    function GetCurUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $scriptName = $_SERVER["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $_SERVER["PHP_SELF"];
            if(empty($_SERVER["QUERY_STRING"])) {
                $nowurl = $scriptName;
            }
            else {
                $nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
            }
        }
        return $nowurl;
    }
    
}

//复制一个对象副本
function CopySQLiPoint(&$ndsql)
{
    $GLOBALS['dsqli'] = $ndsql;
}

//SQL语句过滤程序，由80sec提供，这里作了适当的修改
if (!function_exists('CheckSql'))
{
    function CheckSql($db_string,$querytype='select')
    {
        global $cfg_cookie_encode;
        $clean = '';
        $error='';
        $old_pos = 0;
        $pos = -1;
        $log_file = DEDEINC.'/../data/'.md5($cfg_cookie_encode).'_safe.txt';
        $userIP = GetIP();
        $getUrl = GetCurUrl();

        //如果是普通查询语句，直接过滤一些特殊语法
        if($querytype=='select')
        {
            $notallow1 = "[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";

            //$notallow2 = "--|/\*";
            if(preg_match("/".$notallow1."/", $db_string))
            {
                fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||SelectBreak\r\n");
                exit("<font size='5' color='red'>Safe Alert: Request Error step 1 !</font>");
            }
        }

        //完整的SQL检查
        while (TRUE)
        {
            $pos = strpos($db_string, '\'', $pos + 1);
            if ($pos === FALSE)
            {
                break;
            }
            $clean .= substr($db_string, $old_pos, $pos - $old_pos);
            while (TRUE)
            {
                $pos1 = strpos($db_string, '\'', $pos + 1);
                $pos2 = strpos($db_string, '\\', $pos + 1);
                if ($pos1 === FALSE)
                {
                    break;
                }
                elseif ($pos2 == FALSE || $pos2 > $pos1)
                {
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }
            $clean .= '$s$';
            $old_pos = $pos + 1;
        }
        $clean .= substr($db_string, $old_pos);
        $clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

        //老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
        if (strpos($clean, 'union') !== FALSE && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="union detect";
        }

        //发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们
        elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== FALSE || strpos($clean, '#') !== FALSE)
        {
            $fail = TRUE;
            $error="comment detect";
        }

        //这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
        elseif (strpos($clean, 'sleep') !== FALSE && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'benchmark') !== FALSE && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'load_file') !== FALSE && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="file fun detect";
        }
        elseif (strpos($clean, 'into outfile') !== FALSE && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="file fun detect";
        }

        //老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息
        elseif (preg_match('~\([^)]*?select~s', $clean) != 0)
        {
            $fail = TRUE;
            $error="sub select detect";
        }
        if (!empty($fail))
        {
            fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||$error\r\n");
            exit("<font size='5' color='red'>Safe Alert: Request Error step 2!</font>");
        }
        else
        {
            return $db_string;
        }
    }
}

