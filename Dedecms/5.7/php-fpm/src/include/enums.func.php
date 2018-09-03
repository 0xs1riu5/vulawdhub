<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 联动菜单类
 *
 * @version        $Id: enums.func.php 2 13:19 2011-3-24 tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

// 弱不存在缓存文件则写入缓存
if(!file_exists(DEDEDATA.'/enums/system.php')) WriteEnumsCache();

/**
 *  更新枚举缓存
 *
 * @access    public
 * @param     string  $egroup  联动组
 * @return    string
 */
function WriteEnumsCache($egroup='')
{
    global $dsql;
    $egroups = array();
    if($egroup=='') {
        $dsql->SetQuery("SELECT egroup FROM `#@__sys_enum` GROUP BY egroup ");
    }
    else {
        $dsql->SetQuery("SELECT egroup FROM `#@__sys_enum` WHERE egroup='$egroup' GROUP BY egroup ");
    }
    $dsql->Execute('enum');
    while($nrow = $dsql->GetArray('enum')) {
        $egroups[] = $nrow['egroup'];
    }
    foreach($egroups as $egroup)
    {
        $cachefile = DEDEDATA.'/enums/'.$egroup.'.php';
        $fp = fopen($cachefile,'w');
        fwrite($fp,'<'."?php\r\nglobal \$em_{$egroup}s;\r\n\$em_{$egroup}s = array();\r\n");
        $dsql->SetQuery("SELECT ename,evalue,issign FROM `#@__sys_enum` WHERE egroup='$egroup' ORDER BY disorder ASC, evalue ASC ");
        $dsql->Execute('enum');
        $issign = -1;
        $tenum = false; //三级联动标识
        while($nrow = $dsql->GetArray('enum'))
        {
            fwrite($fp,"\$em_{$egroup}s['{$nrow['evalue']}'] = '{$nrow['ename']}';\r\n");
            if($issign==-1) $issign = $nrow['issign'];
            if($nrow['issign']==2) $tenum = true;
        }
        if ($tenum) $dsql->ExecuteNoneQuery("UPDATE `#@__stepselect` SET `issign`=2 WHERE egroup='$egroup'; ");
        fwrite($fp,'?'.'>');
        fclose($fp);
        if(empty($issign)) WriteEnumsJs($egroup);
    }
    return '成功更新所有枚举缓存！';
}

/**
 *  获取联动表单两级数据的父类与子类
 *
 * @access    public
 * @param     string  $v
 * @return    array
 */
function GetEnumsTypes($v)
{
    $rearr['top'] = $rearr['son'] = 0;
    if($v==0) return $rearr;
    if($v%500==0) {
        $rearr['top'] = $v;
    }
    else {
        $rearr['son'] = $v;
        $rearr['top'] = $v - ($v%500);
    }
    return $rearr;
}

/**
 *  获取枚举的select表单
 *
 * @access    public
 * @param     string  $egroup  联动组
 * @param     string  $evalue  联动值
 * @param     string  $formid  表单ID
 * @param     string  $seltitle  选择标题
 * @return    string  成功后返回一个枚举表单
 */
function GetEnumsForm($egroup, $evalue=0, $formid='', $seltitle='')
{
    $cachefile = DEDEDATA.'/enums/'.$egroup.'.php';
    include($cachefile);
    if($formid=='')
    {
        $formid = $egroup;
    }
    $forms = "<select name='$formid' id='$formid' class='enumselect'>\r\n";
    $forms .= "\t<option value='0' selected='selected'>--请选择--{$seltitle}</option>\r\n";
    foreach(${'em_'.$egroup.'s'} as $v=>$n)
    {
        $prefix = ($v > 500 && $v%500 != 0) ? '└─ ' : '';
        if (preg_match("#\.#", $v)) $prefix = ' &nbsp;&nbsp;└── ';

        if($v==$evalue)
        {
            $forms .= "\t<option value='$v' selected='selected'>$prefix$n</option>\r\n";
        }
        else
        {
            $forms .= "\t<option value='$v'>$prefix$n</option>\r\n";
        }
    }
    $forms .= "</select>";
    return $forms;
}

/**
 *  获取一级数据
 *
 * @access    public
 * @param     string    $egroup   联动组
 * @return    array
 */
function getTopData($egroup)
{
    $data = array();
    $cachefile = DEDEDATA.'/enums/'.$egroup.'.php';
    include($cachefile);
    foreach(${'em_'.$egroup.'s'} as $k=>$v)
    {
        if($k >= 500 && $k%500 == 0) {
            $data[$k] = $v;
        }
    }
    return $data;
}


/**
 *  获取数据的JS代码(二级联动)
 *
 * @access    public
 * @param     string    $egroup   联动组
 * @return    string
 */
function GetEnumsJs($egroup)
{
    global ${'em_'.$egroup.'s'};
    include_once(DEDEDATA.'/enums/'.$egroup.'.php');
    $jsCode = "<!--\r\n";
    $jsCode .= "em_{$egroup}s=new Array();\r\n";
    foreach(${'em_'.$egroup.'s'} as $k => $v)
    {
        // JS中将3级类目存放到第二个key中去
        if (preg_match("#([0-9]{1,})\.([0-9]{1,})#", $k, $matchs))
        {
            $valKey = $matchs[1] + $matchs[2] / 1000;
            $jsCode .= "em_{$egroup}s[{$valKey}]='$v';\r\n";
        } else { 
            $jsCode .= "em_{$egroup}s[$k]='$v';\r\n";
        }
    }
    $jsCode .= "-->";
    return $jsCode;
}

/**
 *  写入联动JS代码
 *
 * @access    public
 * @param     string    $egroup   联动组
 * @return    string
 */
function WriteEnumsJs($egroup)
{
    $jsfile = DEDEDATA.'/enums/'.$egroup.'.js';
    $fp = fopen($jsfile, 'w');
    fwrite($fp, GetEnumsJs($egroup));
    fclose($fp);
}


/**
 *  获取枚举的值
 *
 * @access    public
 * @param     string    $egroup   联动组
 * @param     string    $evalue   联动值
 * @return    string
 */
function GetEnumsValue($egroup, $evalue=0)
{
    include_once(DEDEDATA.'/enums/'.$egroup.'.php');
    if(isset(${'em_'.$egroup.'s'}[$evalue])) {
        return ${'em_'.$egroup.'s'}[$evalue];
    }
    else {
        return "保密";
    }
}