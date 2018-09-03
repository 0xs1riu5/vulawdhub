<?php
/**
 * 模型解析相关函数
 *
 * @version        $Id: inc_archives_all.php 1 9:56 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
/**
 * 获得一个附加表单
 *
 * @access    public
 * @param     object  $ctag  ctag
 * @return    string
 */
function GetFormItem($ctag)
{
    $fieldname = $ctag->GetName();
    $formitem = "
        <table width=\"800\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
       <tr>
        <td width=\"80\">~name~</td>
        <td width=\"720\">~form~</td>
       </tr>
    </table>\r\n";
    $innertext = trim($ctag->GetInnerText());
    if($innertext != "")
    {
        if($ctag->GetAtt("type") == 'select')
        {
            $myformItem = '';
            $items = explode(',', $innertext);
            $myformItem = "<select name='$fieldname' style='width:150px'>";
            foreach($items as $v)
            {
                $v = trim($v);
                if($v!='')
                {
                    $myformItem.= "<option value='$v'>$v</option>\r\n";
                }
            }
            $myformItem .= "</select>\r\n";
            $formitem = str_replace("~name~", $ctag->GetAtt('itemname'), $formitem);
            $formitem = str_replace("~form~", $myformItem,$formitem);
            return $formitem;
        }
        else if($ctag->GetAtt("type") == 'radio')
        {
            $myformItem = '';
            $items = explode(',', $innertext);
            foreach($items as $v)
            {
                $v = trim($v);
                $i = 0;
                if($v!='')
                {
                    if($i==0)
                    {
                        $myformItem .= "<input type='radio' name='$fieldname' class='np' value='$v' checked>$v\r\n";
                    }
                    else
                    {
                        $myformItem .= "<input type='radio' name='$fieldname' class='np' value='$v'>$v\r\n";
                    }
                }
            }
            $formitem = str_replace("~name~", $ctag->GetAtt('itemname'), $formitem);
            $formitem = str_replace("~form~", $myformItem,$formitem);
            return $formitem;
        }
        else
        {
            $formitem = str_replace('~name~', $ctag->GetAtt('itemname'), $formitem);
            $formitem = str_replace('~form~', $innertext,$formitem);
            $formitem = str_replace('@value', '', $formitem);
            return $formitem;
        }
    }
    if($ctag->GetAtt("type")=="htmltext"||$ctag->GetAtt("type")=="textdata")
    {
        $formitem = "";
        $formitem .= "<table width=\"800\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"80\">".$ctag->GetAtt('itemname')."</td><td>";
        $formitem .= GetEditor($fieldname,'',350,'Basic','string');
        $formitem .= "</td></tr></table>\r\n";
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="multitext")
    {
        $innertext = "<textarea name='$fieldname' id='$fieldname' style='width:100%;height:80'></textarea>\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="datetime")
    {
        $nowtime = GetDateTimeMk(time());
        $innertext = "<input name=\"$fieldname\" value=\"$nowtime\" type=\"text\" id=\"$fieldname\" style=\"width:200\">";
        $innertext .= "<input name=\"selPubtime\" type=\"button\" id=\"selkeyword\" value=\"选择\" onClick=\"showCalendar('$fieldname', 'Y-m-d H:i:00', '24');\">";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="img")
    {
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectImage('form1.$fieldname','big')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="media")
    {
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectMedia('form1.$fieldname')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="addon")
    {
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectSoft('form1.$fieldname')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="media")
    {
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectMedia('form1.$fieldname')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else
    {
        if($ctag->GetAtt('default')!="") $dfvalue = $ctag->GetAtt('default');
        else $dfvalue = "";
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:200' value='$dfvalue'>\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
}

/**
 * 处理不同类型的数据
 *
 * @access    public
 * @param     string  $dvalue  值
 * @param     string  $dtype  类型
 * @param     int  $aid  文档ID
 * @param     string  $job  操作类型
 * @param     string  $addvar  增加值
 * @return    string
 */
function GetFieldValue($dvalue,$dtype,$aid=0,$job='add',$addvar='')
{
    global $cfg_cookie_encode,$cfg_dir_purview;
    if($dtype=="int")
    {
        $dvalue = trim(preg_replace("#[^0-9]#", "", $dvalue));
        if($dvalue=="") $dvalue = 0;
        return $dvalue;
    }
    else if($dtype=="float")
    {
        $dvalue = trim(preg_replace("#[^0-9\.]#", "", $dvalue));
        if($dvalue=="") $dvalue = 0;
        return $dvalue;
    }
    else if($dtype=="datetime")
    {
        return GetMkTime($dvalue);
    }
    else if($dtype=="textdata")
    {
        if($job=='edit')
        {
            $addvarDirs = explode('/', $addvar);
            $addvarDir = preg_replace("#\/".$addvarDirs[count($addvarDirs)-1]."$#", "", $addvar);
            $mdir = $GLOBALS['cfg_basedir'].$addvarDir;
            if(!is_dir($mdir))
            {
                MkdirAll($mdir, $GLOBALS['cfg_dir_purview']);
            }
            $fp = fopen($GLOBALS['cfg_basedir'].$addvar, "w");
            fwrite($fp, stripslashes($dvalue));
            fclose($fp);
            CloseFtp();
            return $addvar;
        }
        else
        {
            $ipath = $GLOBALS['cfg_cmspath']."/data/textdata";
            $tpath = ceil($aid/5000);
            if(!is_dir($GLOBALS['cfg_basedir'].$ipath))
            {
                MkdirAll($GLOBALS['cfg_basedir'].$ipath,$cfg_dir_purview);
            }
            if(!is_dir($GLOBALS['cfg_basedir'].$ipath.'/'.$tpath))
            {
                MkdirAll($GLOBALS['cfg_basedir'].$ipath.'/'.$tpath,$cfg_dir_purview);
            }
            $ipath = $ipath.'/'.$tpath;
            $filename = "{$ipath}/{$aid}-".cn_substr(md5($cfg_cookie_encode), 0, 16).".txt";
            $fp = fopen($GLOBALS['cfg_basedir'].$filename,"w");
            fwrite($fp, stripslashes($dvalue));
            fclose($fp);
            CloseFtp();
            return $filename;
        }
    }
    else if($dtype=="img")
    {
        $iurl = stripslashes($dvalue);
        if(trim($iurl)=="")
        {
            return "";
        }
        $iurl = trim(str_replace($GLOBALS['cfg_basehost'],"",$iurl));
        $imgurl = "{dede:img text='' width='' height=''} ".$iurl." {/dede:img}";
        if(preg_match("#^http:\/\/#i", $iurl) && $GLOBALS['isUrlOpen'])
        {
            //远程图片
            $reimgs = "";
            if($isUrlOpen)
            {
                $reimgs = GetRemoteImage($iurl,$GLOBALS['adminid']);
                if(is_array($reimgs))
                {
                    $imgurl = "{dede:img text='' width='".$reimgs[1]."' height='".$reimgs[2]."'} ".$reimgs[0]." {/dede:img}";
                }
            }
            else
            {
                $imgurl = "{dede:img text='' width='' height=''} ".$iurl." {/dede:img}";
            }
        }
        else if($iurl!="")
        {
            //站内图片
            $imgfile = $GLOBALS['cfg_basedir'].$iurl;
            if(is_file($imgfile))
            {
                $imginfos = GetImageSize($imgfile,&$info);
                $imgurl = "{dede:img text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}";
            }
        }
        return addslashes($imgurl);
    }
    else
    {
        return $dvalue;
    }
}

/**
 * 获得带值的表单(编辑时用)
 *
 * @access    public
 * @param     object  $ctag  ctag
 * @param     string  $fvalue  表单值
 * @return    string
 */

function GetFormItemValue($ctag, $fvalue)
{
    $fieldname = $ctag->GetName();
    $formitem = "
        <table width=\"800\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
       <tr>
        <td width=\"80\">~name~</td>
        <td width=\"720\">~form~</td>
       </tr>
    </table>\r\n";
    $innertext = trim($ctag->GetInnerText());
    if($innertext != "")
    {
        if($ctag->GetAtt("type") == 'select')
        {
            $myformItem = '';
            $items = explode(',', $innertext);
            $myformItem = "<select name='$fieldname' style='width:150px'>";
            foreach($items as $v)
            {
                $v = trim($v);
                if($v!='')
                {
                    if($fvalue==$v)
                    {
                        $myformItem.= "<option value='$v' selected>$v</option>\r\n";
                    }
                    else
                    {
                        $myformItem.= "<option value='$v'>$v</option>\r\n";
                    }
                }
            }
            $myformItem .= "</select>\r\n";
            $formitem = str_replace("~name~", $ctag->GetAtt('itemname'), $formitem);
            $formitem = str_replace("~form~", $myformItem,$formitem);
            return $formitem;
        }
        else if($ctag->GetAtt("type")=='radio')
        {
            $myformItem = '';
            $items = explode(',', $innertext);
            foreach($items as $v)
            {
                $v = trim($v);
                if($v!='')
                {
                    if($fvalue==$v)
                    {
                        $myformItem.= "<input type='radio' name='$fieldname' class='np' value='$v' checked>$v\r\n";
                    }
                    else
                    {
                        $myformItem.= "<input type='radio' name='$fieldname' class='np' value='$v'>$v\r\n";
                    }
                }
            }
            $formitem = str_replace("~name~", $ctag->GetAtt('itemname'), $formitem);
            $formitem = str_replace("~form~", $myformItem,$formitem);
            return $formitem;
        }
        else
        {
            $formitem = str_replace('~name~', $ctag->GetAtt('itemname'), $formitem);
            $formitem = str_replace('~form~', $innertext, $formitem);
            $formitem = str_replace('@value', $fvalue, $formitem);
            return $formitem;
        }
    }

    //文本数据的特殊处理
    if($ctag->GetAtt("type")=="textdata")
    {
        if(is_file($GLOBALS['cfg_basedir'].$fvalue))
        {
            $fp = fopen($GLOBALS['cfg_basedir'].$fvalue, 'r');
            $okfvalue = "";
            while(!feof($fp))
            {
                $okfvalue .= fgets($fp,1024);
            }
            fclose($fp);
        }
        else
        {
            $okfvalue="";
        }
        $formitem  = "<table width=\"800\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"80\">".$ctag->GetAtt('itemname')."</td>\r\n";
        $formitem .= "<td>\r\n".GetEditor($fieldname,$okfvalue,350,'Basic','string')."</td>\r\n";
        $formitem .= "</tr></table>\r\n";
        $formitem .= "<input type='hidden' name='{$fieldname}_file' value='{$fvalue}'>\r\n";
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="htmltext")
    {
        $formitem  = "<table width=\"800\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"80\">".$ctag->GetAtt('itemname')."</td>\r\n";
        $formitem .= "<td>\r\n".GetEditor($fieldname,$fvalue,350,'Basic','string')."</td>\r\n";
        $formitem .= "</tr></table>\r\n";
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="multitext")
    {
        $innertext = "<textarea name='$fieldname' id='$fieldname' style='width:100%;height:80'>$fvalue</textarea>\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="datetime")
    {
        $nowtime = GetDateTimeMk($fvalue);
        $innertext = "<input name=\"$fieldname\" value=\"$nowtime\" type=\"text\" id=\"$fieldname\" style=\"width:200\">";
        $innertext .= "<input name=\"selPubtime\" type=\"button\" id=\"selkeyword\" value=\"选择\" onClick=\"showCalendar('$fieldname', 'Y-m-d H:i:00', '24');\">";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="img")
    {
        $ndtp = new DedeTagParse();
        $ndtp->LoadSource($fvalue);
        if(!is_array($ndtp->CTags))
        {
            $ndtp->Clear();
            $fvalue =  "";
        }
        $ntag = $ndtp->GetTag("img");
        $fvalue = trim($ntag->GetInnerText());
        $innertext = "<input type='text' name='$fieldname' value='$fvalue' id='$fieldname' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectImage('form1.$fieldname','big')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="media")
    {
        $innertext = "<input type='text' name='$fieldname' value='$fvalue' id='$fieldname' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectMedia('form1.$fieldname')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else if($ctag->GetAtt("type")=="addon")
    {
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' value='$fvalue' style='width:300'><input name='".$fieldname."_bt' type='button' value='浏览...' onClick=\"SelectSoft('form1.$fieldname')\">\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
    else
    {
        $innertext = "<input type='text' name='$fieldname' id='$fieldname' style='width:200' value='$fvalue'>\r\n";
        $formitem = str_replace("~name~",$ctag->GetAtt('itemname'),$formitem);
        $formitem = str_replace("~form~",$innertext,$formitem);
        return $formitem;
    }
}