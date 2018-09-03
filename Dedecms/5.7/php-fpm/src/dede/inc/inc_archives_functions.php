<?php
/**
 * 文档操作相关函数
 *
 * @version        $Id: inc_archives_functions.php 1 9:56 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEINC.'/dedehttpdown.class.php');
require_once(DEDEINC.'/image.func.php');
require_once(DEDEINC.'/archives.func.php');
require_once(DEDEINC.'/arc.partview.class.php');
$backurl = !empty($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : '';
$backurl = preg_match("#content_#", $backurl) ? "<a href='$backurl'>[<u>记忆的列表页</u>]</a> &nbsp;" : '';
if(!isset($_NOT_ARCHIVES))
{
    require_once(DEDEINC.'/customfields.func.php');
}

/**
 * 获得HTML里的外部资源，针对图集
 *
 * @access    public
 * @param     string  $body  文档内容
 * @param     string  $rfurl  来源地址
 * @param     string  $firstdd  开始标记
 * @return    string
 */
function GetCurContentAlbum($body, $rfurl, &$firstdd)
{
    global $dsql,$cfg_multi_site,$cfg_basehost,$cfg_ddimg_width;
    global $cfg_basedir,$pagestyle,$cuserLogin,$cfg_addon_savetype;
    require_once(DEDEINC.'/dedecollection.func.php');
    if(empty($cfg_ddimg_width))    $cfg_ddimg_width = 320;
    $rsimg = '';
    $cfg_uploaddir = $GLOBALS['cfg_image_dir'];
    $cfg_basedir = $GLOBALS['cfg_basedir'];
    $basehost = 'http://'.$_SERVER['HTTP_HOST'];
    $img_array = array();
    preg_match_all("/(src)=[\"|'| ]{0,}(http:\/\/([^>]*)\.(gif|jpg|png))/isU",$body,$img_array);
    $img_array = array_unique($img_array[2]);
    $imgUrl = $cfg_uploaddir.'/'.MyDate($cfg_addon_savetype, time());
    $imgPath = $cfg_basedir.$imgUrl;
    if(!is_dir($imgPath.'/'))
    {
        MkdirAll($imgPath,$GLOBALS['cfg_dir_purview']);
        CloseFtp();
    }
    $milliSecond = 'co'.dd2char( MyDate('ymdHis',time())) ;
    foreach($img_array as $key=>$value)
    {
        $value = trim($value);
        if(preg_match("#".$basehost."#i", $value) || !preg_match("#^http:\/\/#i", $value) 
        || ($cfg_basehost != $basehost && preg_match("#".$cfg_basehost."#i", $value)))
        {
            continue;
        }
        $itype =  substr($value, -4, 4);
        if( !preg_match("#\.(gif|jpg|png)#", $itype) ) $itype = ".jpg";
        
        $rndFileName = $imgPath.'/'.$milliSecond.'-'.$key.$itype;
        $iurl = $imgUrl.'/'.$milliSecond.'-'.$key.$itype;
        
        //下载并保存文件
        $rs = DownImageKeep($value, $rfurl, $rndFileName, '', 0, 30);
        if($rs)
        {
            $info = '';
            $imginfos = GetImageSize($rndFileName, $info);
            $fsize = filesize($rndFileName);
            $filename = $milliSecond.'-'.$key.$itype;
            //保存图片附件信息
            $inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
            VALUES ('0','$filename','$iurl','1','{$imginfos[0]}','$imginfos[1]','0','$fsize','".time()."','".$cuserLogin->getUserID()."'); ";
            $dsql->ExecuteNoneQuery($inquery);
            $fid = $dsql->GetLastID();
            AddMyAddon($fid, $iurl);
            if($pagestyle > 2)
            {
                $litpicname = GetImageMapDD($iurl, $cfg_ddimg_width);
            }
            else
            {
                $litpicname = $iurl;
            }
            if(empty($firstdd) && !empty($litpicname))
            {
                $firstdd = $litpicname;
                if(!file_exists($cfg_basedir.$firstdd))
                {
                    $firstdd = $iurl;
                }
            }
            @WaterImg($rndFileName, 'down');
            $rsimg .= "{dede:img ddimg='$litpicname' text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
        }
    }
    return $rsimg;
}

/**
 * 获得文章body里的外部资源
 *
 * @access    public
 * @param     string  $body  文档内容
 * @return    string
 */
function GetCurContent($body)
{
    global $cfg_multi_site,$cfg_basehost,$cfg_basedir,$cfg_image_dir;
    $cfg_uploaddir = $cfg_image_dir;
    $htd = new DedeHttpDown();
    $basehost = "http://".$_SERVER["HTTP_HOST"];
    $img_array = array();
    preg_match_all("/src=[\"|'|\s]{0,}(http:\/\/([^>]*)\.(gif|jpg|png))/isU",$body,$img_array);
    $img_array = array_unique($img_array[1]);
    $imgUrl = $cfg_uploaddir.'/'.MyDate("ymd", time());
    $imgPath = $cfg_basedir.$imgUrl;
    if(!is_dir($imgPath.'/'))
    {
        MkdirAll($imgPath, $GLOBALS['cfg_dir_purview']);
        CloseFtp();
    }
    $milliSecond = MyDate('His',time());
    foreach($img_array as $key=>$value)
    {
        if(preg_match("#".$basehost."#i", $value))
        {
            continue;
        }
        if($cfg_basehost != $basehost && preg_match("#".$cfg_basehost."#i", $value))
        {
            continue;
        }
        if(!preg_match("#^http:\/\/#i", $value))
        {
            continue;
        }
        $htd->OpenUrl($value);
        $itype = $htd->GetHead("content-type");
        $itype = substr($value, -4, 4);
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
        $milliSecondN = dd2char($milliSecond.mt_rand(1000, 8000));
        $value = trim($value);
        $rndFileName = $imgPath.'/'.$milliSecondN.'-'.$key.$itype;
        $fileurl = $imgUrl.'/'.$milliSecondN.'-'.$key.$itype;
        $rs = $htd->SaveToBin($rndFileName);
        if($rs)
        {
            if($cfg_multi_site == 'Y')
            {
                $fileurl = $cfg_basehost.$fileurl;
            }
            $body = str_replace($value, $fileurl, $body);
            @WaterImg($rndFileName, 'down');
        }
    }
    $htd->Close();
    return $body;
}

/**
 * 获取一个远程图片
 *
 * @access    public
 * @param     string  $url  地址
 * @param     int  $uid  用户id
 * @return    array
 */
function GetRemoteImage($url, $uid=0)
{
    global $cfg_basedir, $cfg_image_dir, $cfg_addon_savetype;
    $cfg_uploaddir = $cfg_image_dir;
    $revalues = Array();
    $ok = false;
    $htd = new DedeHttpDown();
    $htd->OpenUrl($url);
    $sparr = Array("image/pjpeg", "image/jpeg", "image/gif", "image/png", "image/xpng", "image/wbmp");
    if(!in_array($htd->GetHead("content-type"),$sparr))
    {
        return '';
    }
    else
    {
        $imgUrl = $cfg_uploaddir.'/'.MyDate($cfg_addon_savetype, time());
        $imgPath = $cfg_basedir.$imgUrl;
        CreateDir($imgUrl);
        $itype = $htd->GetHead("content-type");
        if($itype=="image/gif")
        {
            $itype = '.gif';
        }
        else if($itype=="image/png")
        {
            $itype = '.png';
        }
        else if($itype=="image/wbmp")
        {
            $itype = '.bmp';
        }
        else
        {
            $itype = '.jpg';
        }
        $rndname = dd2char($uid.'_'.MyDate('mdHis',time()).mt_rand(1000,9999));
        $rndtrueName = $imgPath.'/'.$rndname.$itype;
        $fileurl = $imgUrl.'/'.$rndname.$itype;
        $ok = $htd->SaveToBin($rndtrueName);
        @WaterImg($rndtrueName, 'down');
        if($ok)
        {
            $data = GetImageSize($rndtrueName);
            $revalues[0] = $fileurl;
            $revalues[1] = $data[0];
            $revalues[2] = $data[1];
        }
    }
    $htd->Close();
    return ($ok ? $revalues : '');
}

/**
 *  获取远程flash
 *
 * @access    public
 * @param     string  $url  地址
 * @param     int  $uid  用户id
 * @return    string
 */
function GetRemoteFlash($url, $uid=0)
{
    global $cfg_addon_savetype, $cfg_media_dir, $cfg_basedir;
    $cfg_uploaddir = $cfg_media_dir;
    $revalues = '';
    $sparr = 'application/x-shockwave-flash';
    $htd = new DedeHttpDown();
    $htd->OpenUrl($url);
    if($htd->GetHead("content-type")!=$sparr)
    {
        return '';
    }
    else
    {
        $imgUrl = $cfg_uploaddir.'/'.MyDate($cfg_addon_savetype, time());
        $imgPath = $cfg_basedir.$imgUrl;
        CreateDir($imgUrl);
        $itype = '.swf';
        $milliSecond = $uid.'_'.MyDate('mdHis', time());
        $rndFileName = $imgPath.'/'.$milliSecond.$itype;
        $fileurl = $imgUrl.'/'.$milliSecond.$itype;
        $ok = $htd->SaveToBin($rndFileName);
        if($ok)
        {
            $revalues = $fileurl;
        }
    }
    $htd->Close();
    return $revalues;
}

/**
 *  检测频道ID
 *
 * @access    public
 * @param     int  $typeid  栏目ID
 * @param     int  $channelid  频道ID
 * @return    bool
 */
function CheckChannel($typeid, $channelid)
{
    global $dsql;
    if($typeid==0) return TRUE;

    $row = $dsql->GetOne("SELECT ispart,channeltype FROM `#@__arctype` WHERE id='$typeid' ");
    if($row['ispart']!=0 || $row['channeltype'] != $channelid) return FALSE;
    else return TRUE;
}

/**
 *  检测档案权限
 *
 * @access    public
 * @param     int  $aid  文档AID
 * @param     int  $adminid  管理员ID
 * @return    bool
 */
function CheckArcAdmin($aid, $adminid)
{
    global $dsql;
    $row = $dsql->GetOne("SELECT mid FROM `#@__archives` WHERE id='$aid' ");
    if($row['mid']!=$adminid) return FALSE;
    else return TRUE;
}

/**
 *  文档自动分页
 *
 * @access    public
 * @param     string  $mybody  内容
 * @param     string  $spsize  分页大小
 * @param     string  $sptag  分页标记
 * @return    string
 */
function SpLongBody($mybody, $spsize, $sptag)
{
    if(strlen($mybody) < $spsize)
    {
        return $mybody;
    }
    $mybody = stripslashes($mybody);
    $bds = explode('<', $mybody);
    $npageBody = '';
    $istable = 0;
    $mybody = '';
    foreach($bds as $i=>$k)
    {
        if($i==0)
        {
            $npageBody .= $bds[$i]; continue;
        }
        $bds[$i] = "<".$bds[$i];
        if(strlen($bds[$i])>6)
        {
            $tname = substr($bds[$i],1,5);
            if(strtolower($tname)=='table')
            {
                $istable++;
            }
            else if(strtolower($tname)=='/tabl')
            {
                $istable--;
            }
            if($istable>0)
            {
                $npageBody .= $bds[$i]; continue;
            }
            else
            {
                $npageBody .= $bds[$i];
            }
        }
        else
        {
            $npageBody .= $bds[$i];
        }
        if(strlen($npageBody)>$spsize)
        {
            $mybody .= $npageBody.$sptag;
            $npageBody = '';
        }
    }
    if($npageBody!='')
    {
        $mybody .= $npageBody;
    }
    return addslashes($mybody);
}

/**
 *  创建指定ID的文档
 *
 * @access    public
 * @param     string  $aid  文档ID
 * @param     string  $ismakesign  生成标志
 * @param     int  $isremote  是否远程
 * @return    string
 */
function MakeArt($aid, $mkindex=FALSE, $ismakesign=FALSE, $isremote=0)
{
    global $envs, $typeid;
    require_once(DEDEINC.'/arc.archives.class.php');
    if($ismakesign) $envs['makesign'] = 'yes';
    $arc = new Archives($aid);
    $reurl = $arc->MakeHtml($isremote);
    return $reurl;
}

/**
 *  取第一个图片为缩略图
 *
 * @access    public
 * @param     string  $body  文档内容
 * @return    string
 */
function GetDDImgFromBody(&$body)
{
    $litpic = '';
    preg_match_all("/(src)=[\"|'| ]{0,}([^>]*\.(gif|jpg|bmp|png))/isU",$body,$img_array);
    $img_array = array_unique($img_array[2]);
    if(count($img_array)>0)
    {
        $picname = preg_replace("/[\"|'| ]{1,}/", '', $img_array[0]);
        if(preg_match("#_lit\.#", $picname)) $litpic = $picname;
        else $litpic = GetDDImage('ddfirst', $picname,1);
    }
    return $litpic;
}

/**
 *  获得缩略图
 *
 * @access    public
 * @param     string  $litpic  缩略图
 * @param     string  $picname  图片名称
 * @param     string  $isremote  是否远程
 * @return    string
 */
function GetDDImage($litpic, $picname, $isremote)
{
    global $cuserLogin,$cfg_ddimg_width,$cfg_ddimg_height,$cfg_basedir,$ddcfg_image_dir,$cfg_addon_savetype;
    $ntime = time();
    if( ($litpic != 'none' || $litpic != 'ddfirst') && 
     !empty($_FILES[$litpic]['tmp_name']) && is_uploaded_file($_FILES[$litpic]['tmp_name']))
    {
        //如果用户自行上传缩略图
        $istype = 0;
        $sparr = Array("image/pjpeg", "image/jpeg", "image/gif", "image/png");
        $_FILES[$litpic]['type'] = strtolower(trim($_FILES[$litpic]['type']));
        if(!in_array($_FILES[$litpic]['type'], $sparr))
        {
            ShowMsg("上传的图片格式错误，请使用JPEG、GIF、PNG格式的其中一种！","-1");
            exit();
        }
        $savepath = $ddcfg_image_dir.'/'.MyDate($cfg_addon_savetype, $ntime);

        CreateDir($savepath);
        $fullUrl = $savepath.'/'.dd2char(MyDate('mdHis', $ntime).$cuserLogin->getUserID().mt_rand(1000, 9999));
        if(strtolower($_FILES[$litpic]['type']) == "image/gif")
        {
            $fullUrl = $fullUrl.".gif";
        }
        else if(strtolower($_FILES[$litpic]['type']) == "image/png")
        {
            $fullUrl = $fullUrl.".png";
        }
        else
        {
            $fullUrl = $fullUrl.".jpg";
        }

        @move_uploaded_file($_FILES[$litpic]['tmp_name'], $cfg_basedir.$fullUrl);
        $litpic = $fullUrl;

        if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($cfg_basedir.$fullUrl,$cfg_ddimg_width,$cfg_ddimg_height);
        else @ImageResize($cfg_basedir.$fullUrl,$cfg_ddimg_width,$cfg_ddimg_height);
        
        $img = $cfg_basedir.$litpic;

    }
    else
    {

        $picname = trim($picname);
        if($isremote==1 && preg_match("#^http:\/\/#i", $picname))
        {
            $litpic = $picname;
            $ddinfos = GetRemoteImage($litpic, $cuserLogin->getUserID());

            if(!is_array($ddinfos))
            {
                $litpic = '';
            }
            else
            {
                $litpic = $ddinfos[0];
                if($ddinfos[1] > $cfg_ddimg_width || $ddinfos[2] > $cfg_ddimg_height)
                {
                    if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($cfg_basedir.$litpic,$cfg_ddimg_width,$cfg_ddimg_height);
                    else @ImageResize($cfg_basedir.$litpic,$cfg_ddimg_width,$cfg_ddimg_height);
                }
            }
        }
        else
        {
            if($litpic=='ddfirst' && !preg_match("#^http:\/\/#i", $picname))
            {
                $oldpic = $cfg_basedir.$picname;
                $litpic = str_replace('.', '-lp.', $picname);
                if($GLOBALS['cfg_ddimg_full']=='Y') @ImageResizeNew($oldpic,$cfg_ddimg_width,$cfg_ddimg_height,$cfg_basedir.$litpic);
                else @ImageResize($oldpic,$cfg_ddimg_width,$cfg_ddimg_height,$cfg_basedir.$litpic);
                if(!is_file($cfg_basedir.$litpic)) $litpic = '';
            }
            else
            {
                $litpic = $picname;
                return $litpic;
            }
        }
    }
    if($litpic=='litpic' || $litpic=='ddfirst') $litpic = '';
    return $litpic;
}

/**
 *  获得一个附加表单
 *
 * @access    public
 * @param     object  $ctag  ctag
 * @return    string
 */
function GetFormItemA($ctag)
{
    return GetFormItem($ctag, 'admin');
}

/**
 *  处理不同类型的数据
 *
 * @access    public
 * @param     string  $dvalue
 * @param     string  $dtype
 * @param     int  $aid
 * @param     string  $job
 * @param     string  $addvar
 * @return    string
 */
function GetFieldValueA($dvalue, $dtype, $aid=0, $job='add', $addvar='')
{
    return GetFieldValue($dvalue, $dtype, $aid, $job, $addvar, 'admin');
}

/**
 *  获得带值的表单(编辑时用)
 *
 * @access    public
 * @param     object  $ctag  ctag
 * @param     string  $fvalue  fvalue
 * @return    string
 */
function GetFormItemValueA($ctag, $fvalue)
{
    return GetFormItemValue($ctag, $fvalue, 'admin');
}

/**
 *  载入自定义表单(用于发布)
 *
 * @access    public
 * @param     string  $fieldset  字段列表
 * @param     string  $loadtype  载入类型
 * @return    string
 */
function PrintAutoFieldsAdd(&$fieldset, $loadtype='all')
{
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace('field','<','>');
    $dtp->LoadSource($fieldset);
    $dede_addonfields = '';
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tid=>$ctag)
        {
            if($loadtype!='autofield'
            || ($loadtype=='autofield' && $ctag->GetAtt('autofield')==1) )
            {
                $dede_addonfields .= ( $dede_addonfields=="" ? $ctag->GetName().",".$ctag->GetAtt('type') : ";".$ctag->GetName().",".$ctag->GetAtt('type') );
                echo  GetFormItemA($ctag);
            }
        }
    }
    echo "<input type='hidden' name='dede_addonfields' value=\"".$dede_addonfields."\">\r\n";
}

/**
 *  载入自定义表单(用于编辑)
 *
 * @access    public
 * @param     string  $fieldset  字段列表
 * @param     string  $fieldValues  字段值
 * @param     string  $loadtype  载入类型
 * @return    string
 */
function PrintAutoFieldsEdit(&$fieldset, &$fieldValues, $loadtype='all')
{
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace("field", "<", ">");
    $dtp->LoadSource($fieldset);
    $dede_addonfields = "";
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tid=>$ctag)
        {
            if($loadtype != 'autofield'
            || ($loadtype == 'autofield' && $ctag->GetAtt('autofield') == 1) )
            {
                $dede_addonfields .= ( $dede_addonfields=='' ? $ctag->GetName().",".$ctag->GetAtt('type') : ";".$ctag->GetName().",".$ctag->GetAtt('type') );
                echo GetFormItemValueA($ctag, $fieldValues[$ctag->GetName()]);
            }
        }
    }
    echo "<input type='hidden' name='dede_addonfields' value=\"".$dede_addonfields."\">\r\n";
}


/**
 * 处理HTML文本
 * 删除非站外链接、自动摘要、自动获取缩略图
 *
 * @access    public
 * @param     string  $body  内容
 * @param     string  $description  描述
 * @param     string  $litpic  缩略图
 * @param     string  $keywords  关键词
 * @param     string  $dtype  类型
 * @return    string
 */
function AnalyseHtmlBody($body,&$description,&$litpic,&$keywords,$dtype='')
{
    global $autolitpic,$remote,$dellink,$autokey,$cfg_basehost,$cfg_auot_description,$id,$title,$cfg_soft_lang;
    $autolitpic = (empty($autolitpic) ? '' : $autolitpic);
    $body = stripslashes($body);

    //远程图片本地化
    if($remote==1)
    {
        $body = GetCurContent($body);
    }

    //删除非站内链接
    if($dellink==1)
    {
        $allow_urls = array($_SERVER['HTTP_HOST']);
        // 读取允许的超链接设置
        if(file_exists(DEDEDATA."/admin/allowurl.txt"))
        {
            $allow_urls = array_merge($allow_urls, file(DEDEDATA."/admin/allowurl.txt"));
        }
        $body = Replace_Links($body, $allow_urls);
    }

    //自动摘要
    if($description=='' && $cfg_auot_description>0)
    {
        $description = cn_substr(html2text($body),$cfg_auot_description);
        $description = trim(preg_replace('/#p#|#e#/','',$description));
        $description = addslashes($description);
    }

    //自动获取缩略图
    if($autolitpic==1 && $litpic=='')
    {
        $litpic = GetDDImgFromBody($body);
    }

    //自动获取关键字
    if($autokey==1 && $keywords=='')
    {
        $subject = $title;
        $message = $body;
        include_once(DEDEINC.'/splitword.class.php');
        $keywords = '';
        $sp = new SplitWord($cfg_soft_lang, $cfg_soft_lang);
        $sp->SetSource($subject, $cfg_soft_lang, $cfg_soft_lang);
        $sp->StartAnalysis();
        $titleindexs = preg_replace("/#p#|#e#/",'',$sp->GetFinallyIndex());
        $sp->SetSource(Html2Text($message), $cfg_soft_lang, $cfg_soft_lang);
        $sp->StartAnalysis();
        $allindexs = preg_replace("/#p#|#e#/",'',$sp->GetFinallyIndex());
        
        if(is_array($allindexs) && is_array($titleindexs))
        {
            foreach($titleindexs as $k => $v)
            {
                if(strlen($keywords.$k)>=60)
                {
                    break;
                }
                else
                {
                    if(strlen($k) <= 2) continue;
                    $keywords .= $k.',';
                }
            }
            foreach($allindexs as $k => $v)
            {
                if(strlen($keywords.$k)>=60)
                {
                    break;
                }
                else if(!in_array($k,$titleindexs))
                {
                    if(strlen($k) <= 2) continue;
                    $keywords .= $k.',';
                }
            }
        }
        $sp = null;
    }
    $body = GetFieldValueA($body,$dtype,$id);
    $body = addslashes($body);
    return $body;
}

/**
 *  删除非站内链接
 *
 * @access    public
 * @param     string  $body  内容
 * @param     array  $allow_urls  允许的超链接
 * @return    string
 */
function Replace_Links( &$body, $allow_urls=array()  )
{
    $host_rule = join('|', $allow_urls);
    $host_rule = preg_replace("#[\n\r]#", '', $host_rule);
    $host_rule = str_replace('.', "\\.", $host_rule);
    $host_rule = str_replace('/', "\\/", $host_rule);
    $arr = '';
    preg_match_all("#<a([^>]*)>(.*)<\/a>#iU", $body, $arr);
    if( is_array($arr[0]) )
    {
        $rparr = array();
        $tgarr = array();
        foreach($arr[0] as $i=>$v)
        {
            if( $host_rule != '' && preg_match('#'.$host_rule.'#i', $arr[1][$i]) )
            {
                continue;
            } else {
                $rparr[] = $v;
                $tgarr[] = $arr[2][$i];
            }
        }
        if( !empty($rparr) )
        {
            $body = str_replace($rparr, $tgarr, $body);
        }
    }
    $arr = $rparr = $tgarr = '';
    return $body;
}

/**
 *  图集里大图的小图
 *
 * @access    public
 * @param     string  $filename  图片名称
 * @param     string  $maxwidth  最大宽度
 * @return    string
 */
function GetImageMapDD($filename, $maxwidth)
{
    global $cuserLogin, $dsql, $cfg_ddimg_height, $cfg_ddimg_full;
    $ddn = substr($filename, -3);
    $ddpicok = preg_replace("#\.".$ddn."$#", "-lp.".$ddn, $filename);
    $toFile = $GLOBALS['cfg_basedir'].$ddpicok;
    
    if($cfg_ddimg_full=='Y') ImageResizeNew($GLOBALS['cfg_basedir'].$filename, $maxwidth, $cfg_ddimg_height, $toFile);
    else ImageResize($GLOBALS['cfg_basedir'].$filename, $maxwidth, $cfg_ddimg_height, $toFile);
    
    //保存图片附件信息
    $fsize = filesize($toFile);
    $ddpicoks = explode('/', $ddpicok);
    $filename = $ddpicoks[count($ddpicoks)-1];
    $inquery = "INSERT INTO `#@__uploads`(arcid,title,url,mediatype,width,height,playtime,filesize,uptime,mid)
                    VALUES ('0','$filename','$ddpicok','1','0','0','0','$fsize','".time()."','".$cuserLogin->getUserID()."'); ";
    $dsql->ExecuteNoneQuery($inquery);
    $fid = $dsql->GetLastID();
    AddMyAddon($fid, $ddpicok);
    
    return $ddpicok;
}


/**
 *  上传一个未经处理的图片
 *
 * @access    public
 * @param     string  $upname 上传框名称
 * @param     string  $handurl 手工填写的网址
 * @param     string  $ddisremote 是否下载远程图片 0 不下, 1 下载
 * @param     string  $ntitle 注解文字 如果表单有 title 字段可不管
 * @return    mixed
 */
function UploadOneImage($upname,$handurl='',$isremote=1,$ntitle='')
{
    global $cuserLogin,$cfg_basedir,$cfg_image_dir,$title, $dsql;
    if($ntitle!='')
    {
        $title = $ntitle;
    }
    $ntime = time();
    $filename = '';
    $isrm_up = FALSE;
    $handurl = trim($handurl);

    //如果用户自行上传了图片
    if(!empty($_FILES[$upname]['tmp_name']) && is_uploaded_file($_FILES[$upname]['tmp_name']))
    {
        $istype = 0;
        $sparr = Array("image/pjpeg", "image/jpeg", "image/gif", "image/png");
        $_FILES[$upname]['type'] = strtolower(trim($_FILES[$upname]['type']));
        if(!in_array($_FILES[$upname]['type'], $sparr))
        {
            ShowMsg("上传的图片格式错误，请使用JPEG、GIF、PNG格式的其中一种！","-1");
            exit();
        }
        if(!empty($handurl) && !preg_match("#^http:\/\/#i", $handurl) && file_exists($cfg_basedir.$handurl) )
        {
            if(!is_object($dsql))
            {
                $dsql = new DedeSql();
            }
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__uploads` WHERE url LIKE '$handurl' ");
            $fullUrl = preg_replace("#\.([a-z]*)$#i", "", $handurl);
        }
        else
        {
            $savepath = $cfg_image_dir.'/'.strftime("%Y-%m",$ntime);
            CreateDir($savepath);
            $fullUrl = $savepath.'/'.strftime("%d",$ntime).dd2char(strftime("%H%M%S", $ntime).'0'.$cuserLogin->getUserID().'0'.mt_rand(1000, 9999));
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
        @move_uploaded_file($_FILES[$upname]['tmp_name'], $cfg_basedir.$fullUrl);
        $filename = $fullUrl;

        //水印
        @WaterImg($imgfile, 'up');
        $isrm_up = TRUE;
    }

    //远程或选择本地图片
    else
    {
        if($handurl=='')
        {
            return '';
        }

        //远程图片并要求本地化
        if($isremote==1 && preg_match("#^http:\/\/#i", $handurl))
        {
            $ddinfos = GetRemoteImage($handurl, $cuserLogin->getUserID());
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
        $imginfos = GetImageSize($imgfile, $info);

        //把新上传的图片信息保存到媒体文档管理档案中
        $inquery = "
        INSERT INTO #@__uploads(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
        VALUES ('$title','$filename','1','".$imginfos[0]."','".$imginfos[1]."','0','".filesize($imgfile)."','".time()."','".$cuserLogin->getUserID()."');
    ";
        $dsql->ExecuteNoneQuery($inquery);
    }
    return $filename;
}

/**
 *  获取更新测试信息
 *
 * @access    public
 * @return    string
 */
function GetUpdateTest()
{
    global $arcID, $typeid, $cfg_make_andcat, $cfg_makeindex, $cfg_make_prenext;
    $revalue = $dolist = '';
    if($cfg_makeindex=='Y' || $cfg_make_andcat=='Y' || $cfg_make_prenext=='Y')
    {
        if($cfg_make_prenext=='Y' && !empty($typeid)) $dolist = 'makeprenext';
        if($cfg_makeindex=='Y') $dolist .= empty($dolist) ? 'makeindex' : ',makeindex';
        if($cfg_make_andcat=='Y') $dolist .= empty($dolist) ? 'makeparenttype' : ',makeparenttype';
        $dolists = explode(',', $dolist);
        $jumpUrl = "task_do.php?typeid={$typeid}&aid={$arcID}&dopost={$dolists[0]}&nextdo=".preg_replace("#".$dolists[0]."[,]{0,1}#", '', $dolist);
        $revalue = "<table width='80%' style='border:1px dashed #cdcdcd;margin-left:20px;margin-bottom:15px' id='tgtable' align='left'><tr><td bgcolor='#EBF5C9'>&nbsp;<strong>正在进行相关内容更新，请完成前不要进行其它操作：</strong>\r\n</td></tr>\r\n";
        $revalue .= "<tr><td>\r\n<iframe name='stafrm' frameborder='0' id='stafrm' width='100%' height='200px' src='$jumpUrl'></iframe>\r\n</td></tr>\r\n";
        $revalue .= "</table>";
    }
    else
    {
        $revalue = '';
    }
    return $revalue;
}