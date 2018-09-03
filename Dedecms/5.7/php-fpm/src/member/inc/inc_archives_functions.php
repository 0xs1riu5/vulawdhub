<?php
/**
 * 文档处理函数
 * 
 * @version        $Id: inc_archives_functions.php 1 13:52 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
if(!defined('DEDEMEMBER')) exit('dedecms');
require_once(DEDEINC.'/image.func.php');
require_once(DEDEINC.'/archives.func.php');
require_once(DEDEINC."/userlogin.class.php");

//检查用户是否被禁言
CheckNotAllow();

/**
 *  获得HTML里的外部资源，针对图集
 *
 * @param     string  $body  内容
 * @param     string  $rfurl  地址
 * @param     string  $firstdd  第一个缩略图
 * @return    string 
 */
function GetCurContentAlbum($body,$rfurl,&$firstdd)
{
    global $cfg_multi_site,$cfg_basehost,$ddmaxwidth,$cfg_basedir,$pagestyle,$cfg_mb_rmdown,$title,$cfg_ml,$cfg_user_dir;
    include_once(DEDEINC."/dedecollection.func.php");
    if(empty($ddmaxwidth)) $ddmaxwidth = 240;
    $rsimg = '';
    $basehost = "http://".$_SERVER["HTTP_HOST"];
    $img_array = array();
    preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/([^>]*)\.(gif|jpg|png))/isU", $body, $img_array);
    $img_array = array_unique($img_array[2]);
    $imgUrl = $cfg_user_dir."/".$cfg_ml->M_ID;
    $imgPath = $cfg_basedir.$imgUrl;
    if(!is_dir($imgPath."/"))
    {
        MkdirAll($imgPath,$GLOBALS['cfg_dir_purview']);
        CloseFtp();
    }
    $milliSecond = MyDate("ymdHis",time());
    foreach($img_array as $key => $value)
    {
        if(preg_match("#".$basehost."#i", $value))
        {
            continue;
        }
        if($cfg_basehost!=$basehost && preg_match("#".$cfg_basehost."#i", $value))
        {
            continue;
        }
        if(!preg_match("#^http:\/\/#i", $value))
        {
            continue;
        }
        if($cfg_mb_rmdown=='Y')
        {
            $value = trim($value);
            $itype =  substr($value,-4,4);
            if(!preg_match("#\.(gif|jpg|png)#i", $itype)) $itype = ".jpg";
            $rndFileName = $imgPath."/".$milliSecond.$key.$itype;
            $iurl = $imgUrl."/".$milliSecond.$key.$itype;

            //下载并保存文件
            //$rs = $htd->SaveToBin($rndFileName);
            $rs = DownImageKeep($value, $rfurl, $rndFileName, '', 0, 30);
            if($rs)
            {
                if($pagestyle > 2)
                {
                    $litpicname = GetImageMapDD($iurl,$ddmaxwidth);
                    if($litpicname!='') SaveUploadInfo($title,$litpicname,1,$addinfos);
                }
                else
                {
                    $litpicname = '';
                }
                if(empty($firstdd))
                {
                    $firstdd = $litpicname;
                    if(!file_exists($cfg_basedir.$firstdd)) $firstdd = $iurl;
                }
                @WaterImg($rndFileName,'down');
                $info = '';
                $imginfos = GetImageSize($rndFileName,$info);
                SaveUploadInfo($title,$iurl,1,$imginfos);
                $rsimg .= "{dede:img ddimg='$litpicname' text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
            }
        }
        else
        {
            $rsimg .= "{dede:img ddimg='$value' text='' width='0' height='0'} $value {/dede:img}\r\n";
        }
    }
    return $rsimg;
}

/**
 *  图集里大图的小图
 *
 * @param     string  $filename  文件名
 * @param     string  $ddm  缩略图
 * @param     string   $oldname  旧的名称
 * @return    string
 */
function GetImageMapDD($filename, $ddm, $oldname='')
{
    if($oldname!='' && !preg_match("#^http:\/\/#i", $oldname))
    {
        $ddpicok = $oldname;
    }
    else
    {
        $ddn = substr($filename,-3);
        $ddpicok = preg_replace("#\.".$ddn."$#", "-lp.".$ddn, $filename);
    }
    $toFile = $GLOBALS['cfg_basedir'].$ddpicok;
    ImageResize($GLOBALS['cfg_basedir'].$filename, $ddm, 300, $toFile);
    return $ddpicok;
}

/**
 *  把上传的信息保存到数据库
 *
 * @param     string  $title  标题
 * @param     string  $filename  文件名称
 * @param     string  $medaitype  附件类型
 * @param     string  $addinfos  附加信息
 * @return    string
 */
function SaveUploadInfo($title,$filename,$medaitype=1,$addinfos='')
{
    global $dsql,$cfg_ml,$cfg_basedir;
    if($filename=='')
    {
        return FALSE;
    }
    if(!is_array($addinfos))
    {
        $addinfos[0] = $addinfos[1] = $addinfos[2] = 0;
    }
    if($medaitype==1)
    {
        $info = '';
        $addinfos = GetImageSize($cfg_basedir.$filename,$info);
    }
    $addinfos[2] = @filesize($cfg_basedir.$filename);
    $row = $dsql->GetOne("SELECT aid,title,url FROM `#@__uploads` WHERE url LIKE '$filename' AND mid='".$cfg_ml->M_ID."'; ");
    $uptime = time();
    if(is_array($row))
    {
        $query = "UPDATE `#@__uploads` SET title='$title',mediatype='$medaitype',
                     width='{$addinfos[0]}',height='{$addinfos[1]}',filesize='{$addinfos[2]}',uptime='$uptime'
                     WHERE aid='{$row['aid']}'; ";
        $dsql->ExecuteNoneQuery($query);
    }
    else
    {
        $inquery = "INSERT INTO `#@__uploads`(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
           VALUES ('$title','$filename','$medaitype','".$addinfos[0]."','".$addinfos[1]."','0','".$addinfos[2]."','$uptime','".$cfg_ml->M_ID."'); ";
        $dsql->ExecuteNoneQuery($inquery);
    }
    $fid = $dsql->GetLastID();
    AddMyAddon($fid, $filename);
    return TRUE;
}

/**
 *  获得一个附加表单
 *
 * @param     object  $ctag
 * @return    string
 */
function GetFormItemA($ctag)
{
    return GetFormItem($ctag,'member');
}

/**
 *  处理不同类型的数据
 *
 * @param     string  $dvalue
 * @param     string  $dtype
 * @param     int  $aid
 * @param     string  $job
 * @param     string  $addvar
 * @return    string
 */
function GetFieldValueA($dvalue,$dtype,$aid=0,$job='add',$addvar='')
{
    return GetFieldValue($dvalue,$dtype,$aid,$job,$addvar,'member');
}

/**
 *  获得带值的表单(编辑时用)
 *
 * @param     object  $ctag
 * @param     string  $fvalue  默认值
 * @return    string
 */
function GetFormItemValueA($ctag,$fvalue)
{
    return GetFormItemValue($ctag,$fvalue,'member');
}

/**
 *  载入自定义表单(用于发布)
 *
 * @access    public
 * @param     string  $fieldset
 * @param     string  $loadtype
 * @param     bool    $isprint   是否打印
 * @return    string
 */
function PrintAutoFieldsAdd(&$fieldset, $loadtype='all', $isprint=TRUE)
{
    global $cfg_cookie_encode;
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace('field','<','>');
    $dtp->LoadSource($fieldset);
    $dede_addonfields = '';
    $addonfieldsname = '';
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tid=>$ctag)
        {
            if($loadtype!='autofield' ||  $ctag->GetAtt('autofield')==1 )
            {
                $dede_addonfields .= ( $dede_addonfields=="" ? $ctag->GetName().",".$ctag->GetAtt('type') : ";".$ctag->GetName().",".$ctag->GetAtt('type') );
                $addonfieldsname .= ",".$ctag->GetName();
                if ($isprint) echo  GetFormItemA($ctag);
            }
        }
    }
    if ($isprint) echo "<input type='hidden' name='dede_addonfields' value=\"".$dede_addonfields."\">\r\n";
    echo "<input type=\"hidden\" name=\"dede_fieldshash\" value=\"".md5($dede_addonfields.$cfg_cookie_encode)."\" />";
    // 增加一个返回
    return $addonfieldsname;
}

/**
 *  载入自定义表单(用于编辑)
 *
 * @param     string  $fieldset
 * @param     string  $fieldValues
 * @param     string  $loadtype
 * @return    string
 */
function PrintAutoFieldsEdit(&$fieldset, &$fieldValues, $loadtype='all')
{
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace("field","<",">");
    $dtp->LoadSource($fieldset);
    $dede_addonfields = "";
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tid=>$ctag)
        {
            if($loadtype!='autofield'
            || ($loadtype=='autofield' && $ctag->GetAtt('autofield')==1) )
            {
                $dede_addonfields .= ( $dede_addonfields=='' ? $ctag->GetName().",".$ctag->GetAtt('type') : ";".$ctag->GetName().",".$ctag->GetAtt('type') );
                echo GetFormItemValueA($ctag,$fieldValues[$ctag->GetName()]);
            }
        }
    }
    echo "<input type='hidden' name='dede_addonfields' value=\"".$dede_addonfields."\">\r\n";
}

/**
 *  创建指定ID的文档
 *
 * @param     int  $aid
 * @param     bool  $ismakesign
 * @return    string
 */
function MakeArt($aid, $ismakesign=FALSE)
{
    global $cfg_makeindex,$cfg_basedir,$cfg_templets_dir,$cfg_df_style;
    include_once(DEDEINC.'/arc.archives.class.php');
    if($ismakesign)
    {
        $envs['makesign'] = 'yes';
    }
    $arc = new Archives($aid);
    $reurl = $arc->MakeHtml();
    if(isset($typeid))
    {
        $preRow =  $arc->dsql->GetOne("SELECT id FROM `#@__arctiny` WHERE id<$aid AND arcrank>-1 AND typeid='$typeid' order by id desc");
        $nextRow = $arc->dsql->GetOne("SELECT id FROM `#@__arctiny` WHERE id>$aid AND arcrank>-1 AND typeid='$typeid' order by id asc");
        if(is_array($preRow))
        {
            $arc = new Archives($preRow['id']);
            $arc->MakeHtml();
        }
        if(is_array($nextRow))
        {
            $arc = new Archives($nextRow['id']);
            $arc->MakeHtml();
        }
    }
    return $reurl;
}

/**
 *  处理HTML文本、自动摘要、自动获取缩略图等
 *
 * @access    public
 * @param     string  $body  文档内容
 * @param     string  $description  描述
 * @param     string  $dtype  类型
 * @return    string
 */
function AnalyseHtmlBody($body, &$description, $dtype='')
{
    global $cfg_mb_rmdown,$cfg_basehost,$cfg_auot_description,$arcID;
    $autolitpic = (empty($autolitpic) ? '' : $autolitpic);
    $body = stripslashes($body);

    //远程图片本地化
    if($cfg_mb_rmdown=='Y')
    {
        $body = GetCurContent($body);
    }

    //自动摘要
    if($description=='' && $cfg_auot_description>0)
    {
        $description = cn_substr(html2text($body),$cfg_auot_description);
        $description = trim(preg_replace('/#p#|#e#/','',$description));
        $description = addslashes($description);
    }
    $body = addslashes($body);
    return $body;
}

/**
 *  获得文章body里的外部资源
 *
 * @access    public
 * @param     string  $body  内容
 * @return    string
 */
function GetCurContent(&$body)
{
    global $cfg_multi_site,$cfg_basehost,$cfg_basedir,$cfg_user_dir,$title,$cfg_ml;
    include_once(DEDEINC."/dedecollection.func.php");
    $htd = new DedeHttpDown();
    $basehost = "http://".$_SERVER["HTTP_HOST"];
    $img_array = array();
    preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/([^>]*)\.(gif|jpg|png))/isU",$body,$img_array);
    $img_array = array_unique($img_array[2]);
    $imgUrl = $cfg_user_dir."/".$cfg_ml->M_ID;
    $imgPath = $cfg_basedir.$imgUrl;
    if(!is_dir($imgPath."/"))
    {
        MkdirAll($imgPath,$GLOBALS['cfg_dir_purview']);
        CloseFtp();
    }
    $milliSecond = MyDate("ymdHis",time());
    foreach($img_array as $key=>$value)
    {
        if(preg_match("#".$basehost."#i", $value))
        {
            continue;
        }
        if($cfg_basehost!=$basehost && preg_match("#".$cfg_basehost."#i", $value))
        {
            continue;
        }
        if(!preg_match("#^http:\/\/#i", $value))
        {
            continue;
        }
        $htd->OpenUrl($value);
        $itype = $htd->GetHead("content-type");
        $itype = substr($value,-4,4);
        if(!preg_match("#\.(jpg|gif|png)#i", $itype))
        {
            if($itype=='image/gif')
            {
                $itype = ".gif";
            }
            else if($itype=='image/png')
            {
                $itype = ".png";
            }
            else
            {
                $itype = '.jpg';
            }
        }
        $milliSecondN = dd2char($milliSecond.'-'.mt_rand(1000,8000));
        $value = trim($value);
        $rndFileName = $imgPath."/".$milliSecondN.'-'.$key.$itype;
        $fileurl = $imgUrl."/".$milliSecondN.'-'.$key.$itype;
        $rs = $htd->SaveToBin($rndFileName);
        if($rs)
        {
            $body = str_replace($value,$fileurl,$body);
            @WaterImg($rndFileName,'down');
        }
        $info = '';
        $imginfos = GetImageSize($rndFileName,$info);
        SaveUploadInfo($title,$fileurl,1,$imginfos);
    }
    $htd->Close();
    return $body;
}

/**
 * 上传一个未经处理的图片
 *
 * 参数一 upname 上传框名称
 * 参数二 handurl 手工填写的网址
 * 参数三 ddisremote 是否下载远程图片 0 不下, 1 下载
 * 参数四 ntitle 注解文字 如果表单有 title 字段可不管
 *
 * @access    public
 * @param     string  $upname  上传名称
 * @param     string  $handurl  操作地址
 * @param     int  $isremote  是否远程
 * @param     string  $ntitle  注释文字
 * @return    string
 */
function UploadOneImage($upname,$handurl='',$isremote=1,$ntitle='')
{
    global $cfg_ml,$cfg_basedir,$cfg_image_dir,$dsql,$title, $dsql;
    if($ntitle!='')
    {
        $title = $ntitle;
    }
    $ntime = time();
    $filename = '';
    $isrm_up = false;
    $handurl = trim($handurl);
    //如果用户自行上传了图片
    if(!empty($_FILES[$upname]['tmp_name']) && is_uploaded_file($_FILES[$upname]['tmp_name']))
    {
        $istype = 0;
        $sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png");
        $_FILES[$upname]['type'] = strtolower(trim($_FILES[$upname]['type']));
        if(!in_array($_FILES[$upname]['type'],$sparr))
        {
            ShowMsg("上传的图片格式错误，请使用JPEG、GIF、PNG格式的其中一种！","-1");
            exit();
        }
        if(!empty($handurl) && !preg_match("#^http:\/\/#", $handurl) && file_exists($cfg_basedir.$handurl) )
        {
            $dsql->ExecuteNoneQuery("Delete From #@__uploads where url like '$handurl' ");
            $fullUrl = preg_replace("#\.([a-z]*)$#i", "", $handurl);
        }
        else
        {
            $savepath = $cfg_image_dir."/".strftime("%Y-%m",$ntime);
            CreateDir($savepath);
            $fullUrl = $savepath."/".strftime("%d",$ntime).dd2char(strftime("%H%M%S",$ntime).'0'.$cfg_ml->M_ID.'0'.mt_rand(1000,9999));
        }
        if(strtolower($_FILES[$upname]['type'])=="image/gif")
        {
            $fullUrl = $fullUrl.".gif";
        }
        else if(strtolower($_FILES[$upname]['type'])=="image/png")
        {
            $fullUrl = $fullUrl.".png";
        }
        else
        {
            $fullUrl = $fullUrl.".jpg";
        }

        //保存
        @move_uploaded_file($_FILES[$upname]['tmp_name'],$cfg_basedir.$fullUrl);
        $filename = $fullUrl;

        //水印
        @WaterImg($imgfile,'up');
        $isrm_up = TRUE;
    }

    //远程或选择本地图片
    else{
        if($handurl=='')
        {
            return '';
        }

        //远程图片并要求本地化
        if($isremote==1 && preg_match("#^http:\/\/#", $handurl))
        {
            $ddinfos = GetRemoteImage($handurl,$cuserLogin->getUserID());
            if(!is_array($ddinfos))
            {
                $litpic = "";
            }
            else
            {
                $filename = $ddinfos[0];
            }
            $isrm_up = TRUE;

            //本地图片或远程不要求本地化
        }
        else
        {
            $filename = $handurl;
        }
    }
    $imgfile = $cfg_basedir.$filename;
    if(is_file($imgfile) && $isrm_up && $filename!='')
    {
        $info = "";
        $imginfos = GetImageSize($imgfile,$info);

        //把新上传的图片信息保存到媒体文档管理档案中
        $inquery = "
        INSERT INTO #@__uploads(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
        VALUES ('$title','$filename','1','".$imginfos[0]."','".$imginfos[1]."','0','".filesize($imgfile)."','".time()."','".$cfg_ml->M_ID."');
    ";
        $dsql->ExecuteNoneQuery($inquery);
    }
    $fid = $dsql->GetLastID();
    AddMyAddon($fid, $filename);
    return $filename;
}