<?php
/**
 * 获得字段创建信息
 *
 * @access    public
 * @param     string  $dtype  字段类型
 * @param     string  $fieldname  字段名称
 * @param     string  $dfvalue  默认值
 * @param     string  $mxlen  最大字符长度
 * @return    array
 */
function GetFieldMake($dtype, $fieldname, $dfvalue, $mxlen)
{
    $fields = array();
    if($dtype == "int" || $dtype == "datetime")
    {
        if($dfvalue == "" || preg_match("#[^0-9-]#", $dfvalue))
        {
            $dfvalue = 0;
        }
        $fields[0] = " `$fieldname` int(11) NOT NULL default '$dfvalue';";
        $fields[1] = "int(11)";
    }
    else if($dtype == "stepselect")
    {
        if($dfvalue == "" || preg_match("#[^0-9\.-]#", $dfvalue))
        {
            $dfvalue = 0;
        }
        $fields[0] = " `$fieldname` char(20) NOT NULL default '$dfvalue';";
        $fields[1] = "char(20)";
    }
    else if($dtype == "float")
    {
        if($dfvalue == "" || preg_match("#[^0-9\.-]#", $dfvalue))
        {
            $dfvalue = 0;
        }
        $fields[0] = " `$fieldname` float NOT NULL default '$dfvalue';";
        $fields[1] = "float";
    }
    else if($dtype == "img" || $dtype == "media" || $dtype == "addon" || $dtype == "imgfile")
    {
        if(empty($dfvalue)) $dfvalue = '';
        if($mxlen=="") $mxlen = 200;
        if($mxlen > 255) $mxlen = 100;

        $fields[0] = " `$fieldname` varchar($mxlen) NOT NULL default '$dfvalue';";
        $fields[1] = "varchar($mxlen)";
    }
    else if($dtype == "multitext" || $dtype == "htmltext")
    {
        $fields[0] = " `$fieldname` mediumtext;";
        $fields[1] = "mediumtext";
    }
    else if($dtype=="textdata")
    {
        if(empty($dfvalue)) $dfvalue = '';

        $fields[0] = " `$fieldname` varchar(100) NOT NULL default '';";
        $fields[1] = "varchar(100)";
    }
    else if($dtype=="textchar")
    {
        if(empty($dfvalue)) $dfvalue = '';
        
        $fields[0] = " `$fieldname` char(100) NOT NULL default '$dfvalue';";
        $fields[1] = "char(100)";
    }
    else if($dtype=="checkbox")
    {
        $dfvalue = str_replace(',',"','",$dfvalue);
        $dfvalue = "'".$dfvalue."'";
        $fields[0] = " `$fieldname` SET($dfvalue) NULL;";
        $fields[1] = "SET($dfvalue)";
    }
    else if($dtype=="select" || $dtype=="radio")
    {
        $dfvalue = str_replace(',', "','", $dfvalue);
        $dfvalue = "'".$dfvalue."'";
        $fields[0] = " `$fieldname` enum($dfvalue) NULL;";
        $fields[1] = "enum($dfvalue)";
    }
    else
    {
        if(empty($dfvalue))
        {
            $dfvalue = '';
        }
        if(empty($mxlen))
        {
            $mxlen = 100;
        }
        if($mxlen > 255)
        {
            $mxlen = 250;
        }
        $fields[0] = " `$fieldname` varchar($mxlen) NOT NULL default '$dfvalue';";
        $fields[1] = "varchar($mxlen)";
    }
    return $fields;
}

/**
 * 获取模型列表字段
 *
 * @access    public
 * @param     object  $dtp  模板引擎
 * @param     string  $oksetting  设置
 * @return    array
 */
function GetAddFieldList(&$dtp,&$oksetting)
{
    $oklist = '';
    $dtp->SetNameSpace("field","<",">");
    $dtp->LoadSource($oksetting);
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tagid=>$ctag)
        {
            if($ctag->GetAtt('islist')==1)
            {
                $oklist .= ($oklist=='' ? strtolower($ctag->GetName()) : ','.strtolower($ctag->GetName()) );
            }
        }
    }
    return $oklist;
}