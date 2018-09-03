<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 *
 *  本类用于指定一个 MySQL 的数据表
 *  自动生成列出、修改、删除、增加等操作
 *
 *   2010-11-17 [修复]启用request类默认数据处理问题
 *   2010-11-17 [修复]如果存在UNIQUE表字段分析错误的问题
 *   2010-11-17 [增加]增加检索条件匹配方法
 *   2010-11-16 [增加]如果表字段存在注释,则列表项的title中则直接显示注释信息
 *   2010-11-14 [修复]数据表存在前缀,模板名称解析问题,同时可以对Lurd单独设定模板
 *
 *
 * @version        $Id: lurd.class.php 6 11:19 2010-11-26 IT柏拉图,tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(DEDEINC.'/datalistcp.class.php');

/***********************************
 @ class lurd

***********************************/
class Lurd extends DataListCP
{
    // 固定属性
    var $dateTypes = array('DATE', 'DATETIME', 'TIMESTAMP', 'TIME', 'YEAR');
    var $floatTypes = array('FLOAT', 'DOUBLE', 'DECIMAL');
    var $intTypes = array('TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT');
    var $charTypes = array('VARCHAR', 'CHAR', 'TINYTEXT');
    var $textTypes = array('TEXT', 'MEDIUMTEXT', 'LONGTEXT');
    var $binTypes = array('TINYBLOB', 'BLOB', 'MEDIUMBLOB', 'LONGBLOB', 'BINARY', 'VARBINARY');
    var $emTypes = array('ENUM', 'SET');
    // 普通属性
    var $tableName = '';
    var $templateDir = '';
    var $lurdTempdir = '';
    var $tplName = '';
    var $appName = '';
    var $primaryKey = '';
    var $autoField = '';
    var $orderQuery = '';
    // 所的字段及属性数组
    var $fields = array(); 
    // 当这个值为TRUE，每次都会生成新模板，用于调试模式
    var $isDebug = FALSE;
    // 文本内容安全级别 
    // 0 为不处理， 1 为禁止不安全HTML内容(javascript等)，2完全禁止HTML内容，并替换部份不安全字符串（如：eval(、union、CONCAT(、--、等）
    var $stringSafe = 1;
    // 这个变量用于存放连结表的参数
    // 可以通过 $lurd->AddLinkTable($tablelurd, linkfields, mylinkid, linkid); 联结一个表
    var $linkTables = array();
    // 联结表的指定字段(如果后一个表的字段和前一个重名, 前者会被替换)
    var $addFields = array();
    // 查询条件
    var $searchParameters = array();

    //构造函数，指定要操作的表名
    function __construct($tablename, $templatedir='', $lurdtempdir='')
    {
        global $dsql;
        $prefix = "#@__";
        $this->tplName = str_replace($prefix, '', $tablename);
        $this->tableName = str_replace($prefix, $dsql->dbPrefix, $tablename);
        $this->templateDir = $templatedir;
        $this->lurdTempdir = empty($lurdtempdir) ? $this->templateDir.'/lurd' : $lurdtempdir;
        parent::__construct();
        $this->AnalyseTable();
        $ct = isset($GLOBALS['ct'])? $GLOBALS['ct'] : request('ct', '');
        if(!empty($ct))
        {
            $this->SetParameter('ct', $ct);
        }
        $this->SetParameter('ac', 'list');
    }
    
    //监听器，根据指定的ac(ac)调用相应处理方法
    function ListenAll($listfield = '', $wherequery = '', $orderquery ='')
    {
        global $action;
        $action = !empty($action)? $action : request('action');
        switch($action)
        {
            case 'add' :
                $this->AddData();
                break;
            case 'edit':
                $this->EditData();
                break;
            case 'del':
                $this->DelData();
                break;
            case 'saveadd':
                $this->SaveAddData();
                break;
            case 'saveedit':
                $this->SaveEditData();
                break;
            default:
                $this->ListData($listfield, $wherequery, $orderquery);
                break;
        }
    }

    // 指定主键
    // 在数据表没有主键的情况下，必须指定一个字段为主键字段，否则系统用所有数据的md5值作为主键
    function AddPriKey($fieldname)
    {
        $this->primaryKey = $fieldname;
    }
    
    // 指定应用的名称
    function AddAppName($appname)
    {
        $this->appName = $appname;
    }
    
    //设置用于排序的SQL，如 order by id desc
    function SetOrderQuery($query)
    {
        $this->orderQuery = $query;
    }
    
    //强制指定字段为其它类型
    function BindType($fieldname, $ftype, $format='')
    {
        //'type' =>'','length' =>'0','unsigned'=>FALSE,'autokey'=>FALSE,
        //'null'=>FALSE,default'=>'','em'=>'','format'=>'','listtemplate'=>'','edittemplate'=>''
        $typesArr = array_merge($this->dateTypes, $this->floatTypes, $this->intTypes, $this->charTypes, $this->textTypes, $this->binTypes, $this->emTypes);
        if( isset($this->fields[$fieldname]) && in_array(strtoupper($ftype), $typesArr) )
        {
            $this->fields[$fieldname]['type'] = $ftype;
            $this->fields[$fieldname]['format'] = $format;
        }
    }
    
    //强制指定字模板（列表list和编辑edit、增加add模板里用）
    function BindTemplate($fieldname, $tmptype='list', $temp='')
    {
        if( isset($this->fields[$fieldname]) )
        {
            $this->fields[$fieldname][$tmptype.'template'] = $temp;
        }
    }
    
    // 列出数据
    /*********************************
     * $listfield = '' 指定要列出的字段(默认为'*') ，只需填写当前表
                                                  联结表出的字段在 AddLinkTable 指定， 因此不需要 表名.字段名 方式识别
     * $wherequery = '' 查询query，如果联结了其它表，需用 表名.字段名 识别字段
     * $orderquery = '' 排序query，如果联结了其它表，需用 表名.字段名 识别字段
     **********************************/
    function ListData($listfield = '*', $wherequery = '', $orderquery ='', $Suff = '_list.htm')
    {
        $listdd = '';
        if(trim($listfield)=='') $listfield = '*';
        
        $template = $this->templateDir.'/'.$this->tplName.$Suff;
        //生成列表模板
        if( !file_exists($template) || $this->isDebug )
        {
            $this->MakeListTemplate($listfield);
        }
        
        // 获取检索条件
        if( $wherequery == '' )
        {
            $wherequery = $this->GetSearchQuery();
        }

        //是否有联结其它的表
        $islink = count($this->linkTables) > 0 ? TRUE : FALSE;
        //主表索引字段(Select * From 部份)
        $listfields = explode(',', $listfield);
        foreach($listfields as $v)
        {
                $v = trim($v);
                if( !isset($this->fields[$v]) && $v != '*' ) continue;
                if($islink) {
                    $listdd .= ($listdd=='' ? "{$this->tableName}.{$v}" : ", {$this->tableName}.{$v} ");
                }
                else {
                    if($v=='*') $listdd .= ' * ';
                    else $listdd .= ($listdd=='' ? "`$v`" : ",`$v`");
                }
        }
        if($listdd=='') $listdd = " * ";
        
        //联结表索引字段
        if($islink)
        {
            $joinQuery = '';
            foreach($this->linkTables as $k=>$linkTable)
            {
                $k++;
                $linkTableName = $linkTable[0]->tableName;
                $joinQuery .= " LEFT JOIN `{$linkTableName}` ON {$linkTableName}.{$linkTable[2]} = {$this->tableName}.{$linkTable[1]} ";
                foreach($this->addFields as $f=>$v)
                {
                    if($v['table'] != $linkTableName) continue;
                    $listdd .= ", {$linkTableName}.{$f} ";
                }
            }
            $query = "SELECT $listdd FROM `{$this->tableName}` $joinQuery $wherequery $orderquery ";
        }
        else
        {
            $query = "SELECT $listdd FROM `{$this->tableName}` $wherequery $orderquery ";
        }
        
        $this->SaveCurUrl();
        $this->SetTemplate($template);
        $this->SetSource($query);
        $this->Display();
    }
    
    //记录列表网址到cookie，方便返回时调用
    function SaveCurUrl()
    {
        setcookie('LURD_GOBACK_URL', $this->GetCurFullUrl(), time()+3600, '/');
    }
    
    function GetCurFullUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $scriptName = $_SERVER["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $_SERVER["PHP_SELF"];
            $nowurl = empty($_SERVER["QUERY_STRING"]) ? $scriptName : $scriptName."?".$_SERVER["QUERY_STRING"];
        }
        return $nowurl;
    }
    
    //生成全部模板
    function MakeAllTemplate($listfield = '')
    {
        $this->MakeListTemplate($listfield);
        $this->MakeAddEditTemplate('add');
        $this->MakeAddEditTemplate('edit');
    }
    
    //生成列表模板
    function MakeListTemplate($listfield = '')
    {
        $templateTemplate = $this->lurdTempdir.'/lurd-list.htm';
        $template = $this->templateDir.'/'.$this->tplName.'_list.htm';
        $tempstr = '';
        $fp = fopen($templateTemplate, 'r');
        while( !feof($fp) ) $tempstr .= fread($fp, 1024);
        fclose($fp);
        $tempItems = array('appname'=>'', 'totalitem'=>'', 'titleitem'=>'', 'fielditem'=>'');
        $tempItems['appname'] = empty($this->appName) ? "管理数据表： ".$this->tableName : $this->appName;
        //设置选择项
        $tempItems['totalitem'] = 1;
        $titleitem = "    <td class='nowrap'>选择</td>\r\n";
        if( !preg_match("/,/", $this->primaryKey) )
        {
                $fielditem = "    <td class='nowrap'><input type=\"checkbox\" name=\"{$this->primaryKey}[]\" value=\"{dede:field name='{$this->primaryKey}' /}\" /></td>\r\n";
        }
        else
        {
                $prikeys = explode(',', $this->primaryKey);
                $prikeyValue = '<'.'?php echo md5("key"';
                foreach($prikeys as $v)
                {
                    $prikeyValue .= '.$fields["'.$v.'"]';
                }
                $prikeyValue .= '); ?'.'>';
                $fielditem = "    <td class='nowrap'><input type=\"checkbox\" name=\"primarykey[]\" value=\"{$prikeyValue}\" /></td>\r\n";
        }
        //使用手工指定列出字段
        if(!empty($listfield) && $listfield != '*' )
        {
            $listfields = explode(',', $listfield);
            $tempItems['totalitem'] = count($listfields) + 1;
            foreach($listfields as $k)
            {
                $k = trim($k);
                if( !isset($this->fields[$k]) ) continue;
                $v = $this->fields[$k];
                $title = !empty($v['comment'])? $v['comment'] : $k;
                $titleitem .= "    <td class='nowrap'>$title</td>\r\n";
                if( !empty($v['listtemplate']) )
                {
                    $fielditem .= "    <td class='nowrap'>{$v['listtemplate']}</td>\r\n";
                }
                else
                {
                    $dofunc = $dtype = $fformat = '';
                    $dtype = !empty($v['type']) ? $v['type'] : 'check';
                    if(isset($v['format']))
                    {
                        $fformat = $v['format'];
                    }
                    if(isset($v['dofunc']))
                    {
                        $dofunc = $v['dofunc'];
                    }
                    if(isset($v['type']))
                    {
                        $this->fields[$k]['type'] = $v['type'];
                    }
                    if( in_array($v['type'], $this->floatTypes) ) 
                    {
                        $dtype = 'float';
                        $dofunc = ($dofunc=='' ? "function=\"Lurd::FormatFloat(@me, '$fformat')\"" : '');
                    }
                    if( in_array($v['type'], $this->dateTypes) )
                    {
                        $dtype = 'date';
                        $dofunc = ($dofunc=='' ? "function=\"Lurd::FormatDate(@me, '{$this->fields[$k]['type']}', '$fformat')\"" : '');
                    }
                    $fielditem .= "    <td class='nowrap'>{dede:field name='{$k}' $dofunc /}</td>\r\n";
                }
            }//End foreach
        }
        //自动处理
        else
        {
            foreach($this->fields as $k=>$v)
            {
                if(in_array($v['type'], $this->binTypes) )
                {
                    continue;
                }
                $tempItems['totalitem']++;
                $titleitem .= "    <td class='nowrap'>$k</td>\r\n";
                $dofunc = $dtype = $fformat = '';
                if(isset($v['format']))
                {
                        $fformat = $v['format'];
                }
                if( in_array($v['type'], $this->floatTypes) ) 
                {
                    $dtype = 'float';
                    $dofunc = ($dofunc=='' ? "function=\"Lurd::FormatFloat(@me, '$fformat')\"" : '');
                }
                if( in_array($v['type'], $this->dateTypes) )
                {
                    $dtype = 'date';
                    $dofunc = ($dofunc=='' ? "function=\"Lurd::FormatDate(@me, '{$this->fields[$k]['type']}', '$fformat')\"" : '');
                }
                $fielditem .= "    <td class='nowrap'>{dede:field name='{$k}' $dofunc /}</td>\r\n";
            }
        }
        //是否有联结其它的表
        $islink = count($this->linkTables) > 0 ? TRUE : FALSE;
        //附加表的字段
        if($islink)
        {
            foreach($this->addFields as $k=>$v)
            {
                if(in_array($v['type'], $this->binTypes) )
                {
                    continue;
                }
                $tempItems['totalitem']++;
                $titleitem .= "    <td class='nowrap'>$k</td>\r\n";
                $dofunc = $dtype = $fformat = '';
                if( in_array($v['type'], $this->floatTypes) ) 
                {
                    $dtype = 'float';
                    $dofunc = ($dofunc=='' ? "function=\"Lurd::FormatFloat(@me, '$fformat')\"" : '');
                }
                if( in_array($v['type'], $this->dateTypes) )
                {
                    $dtype = 'date';
                    $dofunc = ($dofunc=='' ? "function=\"Lurd::FormatDate(@me, '{$this->fields[$k]['type']}', '$fformat')\"" : '');
                }
                $fielditem .= "    <td class='nowrap'>{dede:field name='{$k}' $dofunc /}</td>\r\n";
            }
        }
        $tempItems['titleitem'] = $titleitem;
        $tempItems['fielditem'] = $fielditem;
        foreach($tempItems as $k => $v)
        {
            $tempstr = str_replace("~$k~", $v, $tempstr);
        }
        $fp = fopen($template, 'w');
        fwrite($fp, $tempstr);
        fclose($fp);
    }
    
    //生成发布或编辑模板
    function MakeAddEditTemplate($getTemplets='add')
    {
        $templateTemplate = $this->lurdTempdir."/lurd-{$getTemplets}.htm";
        $template = $this->templateDir.'/'.$this->tplName."_{$getTemplets}.htm";
        $tempstr = '';
        $fp = fopen($templateTemplate, 'r');
        while( !feof($fp) ) $tempstr .= fread($fp, 1024);
        fclose($fp);
        $tempItems = array('appname'=>'', 'fields'=>'', 'primarykey'=>'');
        $tempItems['appname'] = empty($this->appName) ? "在 {$this->tableName} ".($getTemplets=='add' ? '添加数据' : '编辑数据' ) : $this->appName;
        $tempItems['fields'] = '';
        if( !preg_match("/,/", $this->primaryKey) )
        {
            $tempItems['primarykey'] = "    <input type=\"hidden\" name=\"{$this->primaryKey}\" value=\"{dede:field name='{$this->primaryKey}' /}\" />\r\n";
        }
        else
        {
            $prikeys = explode(',', $this->primaryKey);
            $prikeyValue = '<'.'?php echo md5("key"';
            foreach($prikeys as $v)
            {
                $prikeyValue .= '.$fields["'.$v.'"]';
            }
            $prikeyValue .= '); ?'.'>';
            $tempItems['primarykey'] = "    <input type=\"hidden\" name=\"primarykey[]\" value=\"{$prikeyValue}\" />\r\n";
        }
        $fielditem = '';
        foreach($this->fields as $k=>$v)
        {
            $aeform = $dtype = $defaultvalue = $fformat = '';
            //在指定了字段模板情况下不使用自动生成
            if(isset($this->fields[$k][$getTemplets.'template']))
            {
                $fielditem .= $this->fields[$k][$getTemplets.'template'];
                continue;
            }
            //排除自动递增键
            if($k==$this->autoField)
            {
                continue;
            }
            //编辑时，排除主键
            if($k==$this->primaryKey && $getTemplets=='edit')
            {
                continue;
            }
            //格式化选项(编辑时用)
            if(isset($this->fields[$k]['format']))
            {
                $fformat = $this->fields[$k]['format'];
            }
            //获得字段默认值（编辑时从数据库获取）
            if($getTemplets=='edit')
            {
                if( in_array($this->fields[$k]['type'], $this->binTypes) ) $dfvalue = '';
                else $dfvalue = "{dede:field name='$k' /}";
            }
            else
            {
                $dfvalue = $this->fields[$k]['default'];
            }
            //小数类型
            if( in_array($this->fields[$k]['type'], $this->floatTypes) ) 
            {
                if($getTemplets=='edit')
                {
                    $dfvalue = "{dede:field name='$k' function=\"Lurd::FormatFloat(@me, '$fformat')\" /}";
                }
                else if($this->fields[$k]['default']=='')
                {
                    $dfvalue = 0;
                }
                $aeform  = "<input type='input' name='{$k}' class='txtnumber' value='$dfvalue' />";
            }
            //整数类型
            if( in_array($this->fields[$k]['type'], $this->intTypes) ) 
            {
                $aeform  = "<input type='input' name='{$k}' class='txtnumber' value='$dfvalue' />";
            }
            //时间类型
            else if( in_array($this->fields[$k]['type'], $this->dateTypes))
            {
                if(empty($fformat)) $fformat = 'Y-m-d H:i:s';
                if($getTemplets=='edit')
                {
                    $dfvalue = "{dede:field name='$k' function=\"MyDate(@me, '$fformat')\" /}";
                }
                else if(empty($this->fields[$k]['default']))
                {
                    $dfvalue = "{dede:var.a function=\"MyDate(time(), '$fformat')\" /}";
                }
                $aeform  = "<input type='input' name='{$k}' class='txtdate' value='$dfvalue' />";
            }
            //长文本类型
            else if( in_array($this->fields[$k]['type'], $this->textTypes))
            {
                $aeform  = "<textarea name='$k' class='txtarea'>{$dfvalue}</textarea>";
            }
            //二进制类型
            else if( in_array($this->fields[$k]['type'], $this->textTypes))
            {
                $aeform = "<input type='file' name='$k' size='45' />";
            }
            //SET类型
            else if( $this->fields[$k]['type']=='SET' )
            {
                $ems = explode(',', $this->fields[$k]['em']);
                if($getTemplets=='edit')
                {
                    $aeform .= '<'.'?php $enumItems = explode(\',\', $fields[\''.$k.'\']); ?'.'>';
                }
                foreach($ems as $em)
                {
                    if($getTemplets=='add')
                    {
                        $aeform .= "<input type='checkbox' name='{$k}[]' value='$em' />$em \r\n";
                    }
                    else
                    {
                        $aeform .= "<input type='checkbox' name='{$k}[]' value='$em' {dede:if in_array('$em', \$enumItems)}checked{/dede:if} />$em \r\n";
                    }
                }
            }
            //ENUM类型
            else if( $this->fields[$k]['type']=='ENUM' )
            {
                $ems = explode(',', $this->fields[$k]['em']);
                foreach($ems as $em)
                {
                    if($getTemplets=='edit') {
                        $aeform .= "<input type='radio' name='$k' value='$em' {dede:if \$fields['$k']}=='$em'}checked{/dede:if} />$em \r\n";
                    }
                    else {
                        $aeform .= "<input type='radio' name='$k' value='$em' />$em \r\n";
                    }
                }
            }
            else
            {
                $aeform  = "<input type='input' name='{$k}' class='txt' value='$dfvalue' />";
            }
            $fielditem .= "    <tr>\r\n<td height='28' align='center' bgcolor='#FFFFFF'>{$k}</td>\r\n<td bgcolor='#FFFFFF'>{$aeform}</td>\r\n</tr>\r\n";
        }
        $tempItems['fields'] = $fielditem;
        foreach($tempItems as $k=>$v)
        {
            $tempstr = str_replace("~$k~", $v, $tempstr);
        }
        $fp = fopen($template, 'w');
        fwrite($fp, $tempstr);
        fclose($fp);
    }

    // 读取数据
    function EditData()
    {
        $template = $this->templateDir.'/'.$this->tplName.'_edit.htm';
        //生成列表模板
        if( !file_exists($template) || $this->isDebug )
        {
            $this->MakeAddEditTemplate('edit');
        }
        $whereQuery = '';
        $GLOBALS[$this->primaryKey] = isset($GLOBALS[$this->primaryKey])? $GLOBALS[$this->primaryKey] : request($this->primaryKey);
        if(empty($GLOBALS['primarykey'][0]) && empty($GLOBALS[$this->primaryKey][0]))
        {
            ShowMsg('请选择要修改的记录！', '-1');
            exit();
        }
        if(preg_match("/,/", $this->primaryKey))
        {
            $whereQuery = "WHERE md5(CONCAT('key', `".str_replace(',', '`,`', $this->primaryKey)."`) = '{$GLOBALS['primarykey'][0]}' ";
        }
        else
        {
            $whereQuery = "WHERE `{$this->primaryKey}` = '".$GLOBALS[$this->primaryKey][0]."' ";
        }

        //列出数据
        $query = "SELECT * FROM `{$this->tableName}` $whereQuery ";
        $this->SetTemplate($template);
        $this->SetSource($query);
        $this->Display();
    }

    // 新增数据
    function AddData()
    {
        $template = $this->templateDir.'/'.$this->tplName.'_add.htm';
        //生成列表模板
        if( !file_exists($template) || $this->isDebug )
        {
            $this->MakeAddEditTemplate('add');
        }
        //生成发布表单数据
        $this->SetTemplate($template);
        $this->Display();
        exit();
    }

    // 保存新增数据
    function SaveAddData($isturn=TRUE)
    {
        $allfield = $allvalue = '';
        foreach($this->fields as $k=>$v)
        {
            //自动递增键不处理
            if($k==$this->autoField)
            {
                continue;
            }
            $allfield .= ($allfield=='' ? "`$k`" : ' , '."`$k`");
            $v = $this->GetData($k);
            $allvalue .= ($allvalue=='' ? "'$v'" : ' , '."'$v'");
        }
        $inQuery = "INSERT INTO `$this->tableName`($allfield) VALUES($allvalue); ";
        $rs = $this->dsql->ExecuteNoneQuery($inQuery);
        
        //只返回结果
        if(!$isturn)
        {
            return $rs;
        }
        //完全处理模式
        $gourl = !empty($_COOKIE['LURD_GOBACK_URL']) ? $_COOKIE['LURD_GOBACK_URL'] : '-1';
        if(!$rs)
        {
            $this->dsql->SaveErrorLog($inQuery);
            ShowMsg('保存数据失败，请检查数据库错误日志！', $gourl);
            exit();
        }
        else
        {
            ShowMsg('成功保存一组数据！', $gourl);
            exit();
        }
    }

    // 保存编辑数据
    function SaveEditData($isturn=TRUE)
    {
        $editfield = '';
        foreach($this->fields as $k=>$v)
        {
            //自动递增键不处理
            $GLOBALS[$k] = isset($GLOBALS[$k])? $GLOBALS[$k] : $GLOBALS['request']->forms[$k];
            if($k==$this->autoField || !isset($GLOBALS[$k]))
            {
                continue;
            }
            $v = $this->GetData($k);
            $editfield .= ($editfield=='' ? " `$k`='$v' " : ",\n `$k`='$v' ");
        }
        //获得主键值
        if(preg_match("#,#", $this->primaryKey))
        {
            $keyvalue = (isset($GLOBALS['primarykey']) ? $GLOBALS['primarykey'] : '');
        }
        else
        {
            $keyvalue = $this->GetData($this->primaryKey);
        }
        $keyvalue = preg_replace("#[^0-9a-z]#i", "", $keyvalue);
        if( !preg_match("#,#", $this->primaryKey) )
        {
            $inQuery = " UPDATE `$this->tableName` SET $editfield WHERE `{$this->primaryKey}`='{$keyvalue}' ";
        }
        else
        {
            $inQuery = " UPDATE `$this->tableName` SET $editfield WHERE md5('key', `".str_replace(',','`,`',$this->primaryKey)."`='{$keyvalue}' ";
        }
        $rs = $this->dsql->ExecuteNoneQuery($inQuery);
        //只返回结果
        if(!$isturn)
        {
            return $rs;
        }
        //完全处理模式
        $gourl = !empty($_COOKIE['LURD_GOBACK_URL']) ? $_COOKIE['LURD_GOBACK_URL'] : '-1';
        if(!$rs)
        {
            $this->dsql->SaveErrorLog($inQuery);
            ShowMsg('保存数据失败，请检查数据库错误日志！', $gourl);
            exit();
        }
        else
        {
            ShowMsg('成功保存一组数据！', $gourl);
            exit();
        }
    }

    // 删除数据
    function DelData($isturn=TRUE)
    {
        $GLOBALS[$this->primaryKey] = isset($GLOBALS[$this->primaryKey])? $GLOBALS[$this->primaryKey] : request($this->primaryKey);
        if(preg_match("#,#", $this->primaryKey))
        {
            $keyArr = (isset($GLOBALS['primarykey']) ? $GLOBALS['primarykey'] : '');
        }
        else
        {
            $keyArr = isset($GLOBALS[$this->primaryKey]) ? $GLOBALS[$this->primaryKey] : '';
        }
        if(!is_array($keyArr))
        {
            ShowMsg('没指定要删除的记录！', '-1');
            exit();
        }
        else
        {
            $isid = !preg_match("#,#", $this->primaryKey) ? TRUE : FALSE;
            $i = 0;
            foreach($keyArr as $v)
            {
                $v = preg_replace("#[^0-9a-z]#i", '', $v);
                if(empty($v)) continue;
                $i++;
                if($isid)
                {
                    $this->dsql->ExecuteNoneQuery("DELETE FROM `{$this->tableName}` WHERE `$this->primaryKey`='$v' ");
                }
                else
                {
                    $this->dsql->ExecuteNoneQuery("DELETE FROM `{$this->tableName}` WHERE md5('key', `".str_replace(',','`,`',$this->primaryKey)."`='$v' ");
                }
            }
            if($isturn)
            {
                $gourl = !empty($_COOKIE['LURD_GOBACK_URL']) ? $_COOKIE['LURD_GOBACK_URL'] : '-1';
                ShowMsg('成功删除指定的记录！', $gourl);
                exit();
            }
            else
            {
                return $i;
            }
        }
    }
    
    //联结其它表(用于数据查询的情况)
    //$tablelurd 目标表lurd对象, $linkfields select的字段
    //$mylinkid 当前表用于联结的字段
    //$linkid 目标表用于联结的字段
    function AddLinkTable(&$tablelurd, $mylinkid, $linkid, $linkfields='*')
    {
        if(trim($linkfields)=='') $linkfields = '*';
        $this->linkTables[] = array($tablelurd, $mylinkid, $linkid, $linkfields);
        //记录附加表的字段信息
        if($linkfields != '*')
        {
            $fs = explode(',', $linkfields);
            foreach($fs as $f)
            {
                $f = trim($f);
                if(isset($tablelurd->fields[$f]))
                {
                    $this->addFields[$f] = $tablelurd->fields[$f];
                    $this->addFields[$f]['table'] = $tablelurd->tableName;
                }
            }
        }
        else
        {
            foreach($tablelurd->fields as $k=>$v) 
            {
                $this->addFields[$k] = $v;
                $this->addFields[$k]['table'] = $tablelurd->tableName;
            }
        }
    }

    //分析表结构
    function AnalyseTable()
    {
        if($this->tableName == '')
        {
            exit(" No Input Table! ");
        }
        
        $this->dsql->Execute('ana', " SHOW CREATE TABLE `{$this->tableName}`; ");
        $row = $this->dsql->GetArray('ana', MYSQL_NUM);
        if(!is_array($row))
        {
            exit(" Analyse Table `$tablename` Error! ");
        }

        // 先去掉内容中的注释
        // $row[1] = preg_replace('#COMMENT \'(.*?)\'#i', '', $row[1]);
        // echo $row[1];exit;
        $flines = explode("\n", $row[1]);
        $parArray = array('date', 'float', 'int', 'char', 'text', 'bin', 'em');
        $prikeyTmp = '';
        for($i=1; $i < count($flines)-1; $i++ )
        {
            $line = trim($flines[$i]);
            $lines = explode(' ', str_replace('`', '', $line));

            if( $lines[0] == 'KEY' ) continue;
            if( $lines[0] == 'UNIQUE' ) continue;
            
            if( $lines[0] == 'PRIMARY' )
            {
                $this->primaryKey = preg_replace("/[\(\)]|,$/", '', $lines[count($lines)-1]);
                continue;
            }
            //字段名称、类型
            $this->fields[$lines[0]] = array('type' => '',  'length' => '', 'unsigned' => FALSE, 'autokey' => FALSE, 'null' => TRUE, 'default' => '', 'em' => '', 'comment' => '');
            $this->fields[$lines[0]]['type'] = strtoupper(preg_replace("/\(.*$|,/", '', $lines[1]));
            $this->fields[$lines[0]]['length'] = preg_replace("/^.*\(|\)/", '', $lines[1]);
            if(preg_match("#[^0-9]#", $this->fields[$lines[0]]['length']))
            {
                if($this->fields[$lines[0]]['type'] == 'SET'
                    || $this->fields[$lines[0]]['type'] == 'ENUM')
                {
                    $this->fields[$lines[0]]['em'] = preg_replace("/'/", '', $this->fields[$lines[0]]['length']);
                }
                $this->fields[$lines[0]]['length'] = 0;
            }
            
            //把特定类型的数据加入数组中
            foreach($parArray as $v)
            {
                $tmpstr = "if(in_array(\$this->fields[\$lines[0]]['type'], \$this->{$v}Types))
                {
                    \$this->{$v}Fields[] = \$lines[0];
                }";
                eval($tmpstr);
            }
            if( !in_array($this->fields[$lines[0]]['type'], $this->textTypes) 
                && !in_array($this->fields[$lines[0]]['type'], $this->binTypes) )
            {
                $prikeyTmp .= ($prikeyTmp=='' ? $lines[0] : ','.$lines[0]);
            }
            
            //分析其它属性
            // echo $line;exit;
            if(preg_match("#unsigned#i", $line))
            {
                $this->fields[$lines[0]]['unsigned'] = TRUE;
            }
            if(preg_match("#auto_increment#i", $line))
            {
                $this->fields[$lines[0]]['autokey'] = TRUE;
                $this->autoField = $lines[0];
            }
            if(preg_match("#NOT NULL#i", $line))
            {
                $this->fields[$lines[0]]['null'] = FALSE;
            }
            if(preg_match("#default#i", $line))
            {
                preg_match("#default '(.*?)'#i", $line, $dfmatchs);
                $this->fields[$lines[0]]['default'] = isset($dfmatchs[1])? $dfmatchs[1] : NULL;
            }
            if(preg_match("#comment#i", $line))
            {
                preg_match("#comment '(.*?)'#i", $line, $cmmatchs);
                $this->fields[$lines[0]]['comment'] = isset($cmmatchs[1])? $cmmatchs[1] : NULL;
            }
            
        }//end for
        if( $this->primaryKey=='' )
        {
            $this->primaryKey = $prikeyTmp;
        }
    }
    
    /**
    * 增加搜索条件
    * @parem $fieldname 字段名称
    * @parem $fieldvalue 传入的 value 值必须先经过转义
    * @parem $condition 条件 >、<、=、<> 、like、%like%、%like、like%
    * @parem $linkmode AND 或 OR
    */
    function AddSearchParameter($fieldname, $fieldvalue, $condition, $linkmode='AND')
    {
        $c = count($this->searchParameters);
        //对于指定了多个字段，使用 CONCAT 进行联结（通常是like操作）
        if( preg_match('/,/', $fieldname) )
        {
            $fs = explode(',', $fieldname);
            $fieldname = "CONCAT(";
            $ft = '';
            foreach($fs as $f)
            {
                $f = trim($f);
                $ft .= ($ft=='' ? "`{$f}`" : ",`{$f}`");
            }
            $fieldname .= "{$ft}) ";
        }
        $this->searchParameters[$c]['field'] = $fieldname;
        $this->searchParameters[$c]['value'] = $fieldvalue;
        $this->searchParameters[$c]['condition'] = $condition;
        $this->searchParameters[$c]['mode'] = $linkmode;
    }

    // 获取搜索条件
    function GetSearchQuery()
    {
        $wquery = '';
        if( count( $this->searchParameters ) == 0 )
        {
            return '';
        }
        else
        {
            foreach($this->searchParameters as $k=>$v)
            {
                if( preg_match("/like/i", $v['condition']) )
                {
                    $v['value'] = preg_replace("/like/i", $v['value'], $v['condition']);
                }
                $v['condition'] = preg_replace("/%/", '', $v['condition']);
                if( $wquery=='' )
                {
                    if(!preg_match("/\./", $v['field'])) $v['field'] = "{$v['field']}";
                    $wquery .= "WHERE ".$v['field']." {$v['condition']} '{$v['value']}' ";
                }
                else
                {
                    if(!preg_match("/\./", $v['field'])) $v['field'] = "{$v['field']}";
                    $wquery .= $v['mode']." ".$v['field']." {$v['condition']} '{$v['value']}' ";
                }
            }
        }
        return $wquery ;
    }
    
    //把从表单传递回来的数值进行处理
    function GetData($fname)
    {
        $reValue = '';
        $ftype = $this->fields[$fname]['type'];
        $GLOBALS[$fname] = isset($GLOBALS[$fname])? $GLOBALS[$fname] : @$GLOBALS['request']->forms[$fname];
        //二进制单独处理
        if( in_array($ftype, $this->binTypes) )
        {
            return $this->GetBinData($fname);
        }
        //没有这个变量返回默认值
        else if( !isset($GLOBALS[$fname]) )
        {
            if( isset($this->fields[$fname]['default']) )
            {
                return $this->fields[$fname]['default'];
            }
            else
            {
                if(in_array($ftype, $this->intTypes) || in_array($ftype, $this->floatTypes)) {
                    return 0;
                }
                else if(in_array($ftype, $this->charTypes) || in_array($ftype, $this->textTypes)) {
                    return '';
                }
                else {
                    return 'NULL';
                }
            }
        }
        //处理整数
        else if( preg_match("#YEAR|INT#", $ftype) )
        {
            // $temp = isset($GLOBALS[$fname][0])? $GLOBALS[$fname][0] : 0;
            $negTag = is_int($GLOBALS[$fname]) && $GLOBALS[$fname]< 0 ? '-' : $GLOBALS[$fname];
            $reValue = preg_replace("#[^0-9]#", '', $GLOBALS[$fname]);
            $reValue = empty($reValue) ? 0 : intval($reValue);
            if($negTag=='-' && !$this->fields[$fname]['unsigned']
                 && $reValue != 0 && $ftype != 'YEAR')
            {
                $reValue = intval('-'.$reValue);
            }
        }
        //处理小数类型
        else if(in_array($ftype, $this->floatTypes))
        {
            $negTag = $GLOBALS[$fname][0];
            $reValue = preg_replace("#[^0-9\.]|^\.#", '', $GLOBALS[$fname]);
            $reValue = empty($reValue) ? 0 : doubleval($reValue);
            if($negTag=='-' && !$this->fields[$fname]['unsigned'] && $reValue != 0)
            {
                $reValue = intval('-'.$reValue);
            }
        }
        //字符串类型
        else if(in_array($ftype, $this->charTypes))
        {
            $reValue = cn_substrR($this->StringSafe($GLOBALS[$fname]), $this->fields[$fname]['length']);
        }
        //文本类型
        else if(in_array($ftype, $this->textTypes))
        {
            $reValue = $this->StringSafe($GLOBALS[$fname]);
        }
        //SET类型
        else if($ftype=='SET')
        {
            $sysSetArr = explode(',', $this->fields[$fname]['em']);
            if( !is_array($GLOBALS[$fname]) )
            {
                $setArr[] = $GLOBALS[$fname];
            }
            else
            {
                $setArr = $GLOBALS[$fname];
            }
            $reValues = array();
            foreach($setArr as $a)
            {
                if(in_array($a, $sysSetArr)) $reValues[] = $a;
            }
            $reValue = count($reValues)==0 ? 'NULL' : join(',', $reValues);
        }
        //枚举类型
        else if($ftype=='ENUM')
        {
            $sysEnumArr = explode(',', $this->fields[$fname]['em']);
            if(in_array($GLOBALS[$fname], $sysEnumArr)) $reValue = $GLOBALS[$fname];
            else $reValue = 'NULL';
        }
        //时间日期类型
        else if(in_array($ftype, $this->dateTypes))
        {
            if($ftype=='TIMESTAMP')
            {
                $reValue = GetMkTime($GLOBALS[$fname]);
            }
            else
            {
                $reValue = preg_replace("#[^0-9 :-]#", '', $GLOBALS[$fname]);
            }
        }
        return $reValue;
    }
    
    //对请求的字符串进行安全处理
    function StringSafe($str, $safestep=-1)
    {
        $safestep = ($safestep > -1) ? $safestep : $this->stringSafe;
        //过滤危险的HTML(默认级别)
        if($safestep == 1)
        {
            $str = preg_replace("#script:#i", "ｓｃｒｉｐｔ：", $str);
            $str = preg_replace("#<[\/]{0,1}(link|meta|ifr|fra|scr)[^>]*>#isU", '', $str);
            $str = preg_replace("#[\r\n\t ]{1,}#", ' ', $str);
            return $str;
        }
        //完全禁止HTML
        //并转换一些不安全字符串（如：eval(、union、CONCAT(、--、等）
        else if($this->stringSafe == 2)
        {
            $str = addslashes(htmlspecialchars(stripslashes($str)));
            $str = preg_replace("#eval#i", 'ｅｖａｌ', $str);
            $str = preg_replace("#union#i", 'ｕｎｉｏｎ', $str);
            $str = preg_replace("#concat#i", 'ｃｏｎｃａｔ', $str);
            $str = preg_replace("#--#", '－－', $str);
            $str = preg_replace("#[\r\n\t ]{1,}#", ' ', $str);
            return $str;
        }
        //不作安全处理
        else
        {
            return $str;
        }
    }
    //保存二进制文件数据
    //为了安全起见，对二进制数据保存使用base64编码后存入
    function GetBinData($fname)
    {
        $lurdtmp = DEDEDATA.'/lurdtmp';
        if(!isset($_FILES[$fname]['tmp_name']) || !is_uploaded_file($_FILES[$fname]['tmp_name']))
        {
            return '';
        }
        else
        {
            $tmpfile = $lurdtmp.'/'.md5( time() . Sql::ExecTime() . mt_rand(1000, 5000) ).'.tmp';
            $rs = move_uploaded_file($_FILES[$fname]['tmp_name'], $tmpfile);
            if(!$rs) return '';
            $fp = fopen($tmpfile, 'r');
            $data = base64_encode(fread($fp, filesize($tmpfile)));
            fclose($fp);
            return $data;
        }
    }

    //格式化浮点数字
    function FormatFloat($fvalue, $ftype='')
    {
        if($ftype=='') $ftype='%0.4f';
        return sprintf($ftype, $fvalue);
    }
    
    //获得默认时间格式
    function GetDateTimeDf($ftype)
    {
        if($ftype=='DATE') return 'Y-m-d';
        else if($ftype=='TIME') return 'H:i:s';
        else if($ftype=='YEAR') return 'Y';
        else return 'Y-m-d H:i:s';
    }
    
    //格式化日期
    function FormatDate($fvalue, $ftype, $fformat='')
    {
        if($ftype=='INT' || $ftype='TIMESTAMP' ) return MyDate($fvalue, $fformat);
        else return $fvalue;
    }

}
?>