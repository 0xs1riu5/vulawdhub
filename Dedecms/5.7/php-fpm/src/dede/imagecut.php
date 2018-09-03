<?php
/**
 * 截取图片
 *
 * @version        $Id: imagecut.php 1 11:06 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__).'/config.php');
$action = isset($action) ? trim($action) : '';
if(empty($action))
{
    if(!@is_file($cfg_basedir.$file))
    {
        ShowMsg("对不起，必须选择站内的图片才能进行裁剪！<br />点击'<a href='/include/dialog/select_images.php?f=form1.picname&imgstick=small'>站内选择</a>', 上传或选择一个图片，然后才能进行裁剪！", "../include/dialog/select_images.php?f=form1.picname&imgstick=small", 0 , 10000);
        exit();
    }
    include DEDEADMIN.'/templets/imagecut.htm';
    exit();
}
elseif($action == 'cut')
{
    require_once(DEDEINC.'/image.func.php');

    if(!@is_file($cfg_basedir.$file))
    {
        ShowMsg('对不起，请重新选择裁剪图片！', '-1');
        exit();
    }
    if(empty($width))
    {
        ShowMsg('对不起，请选择裁剪图片的尺寸！', '-1');
        exit();
    }
    if(empty($height))
    {
        ShowMsg('对不起，请选择裁剪图片的尺寸！', '-1');
        exit();
    }
    $imginfo = getimagesize($cfg_basedir.$file);
    $imgw = $imginfo[0];
    $imgh = $imginfo[1];
    $temp = 400/$imgw;
    $newwidth = 400;
    $newheight = $imgh * $temp;
    $srcFile = $cfg_basedir.$file;
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $thumba = imagecreatetruecolor($width, $height);

    switch($imginfo['mime'])
    {
        case 'image/jpeg':
            $source = imagecreatefromjpeg($srcFile);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($srcFile);
            break;
        case 'image/png':
            $source = imagecreatefrompng($srcFile);
            break;
        default:
            ShowMsg('对不起，裁剪图片类型不支持请选择其他类型图片！', '-1');
            break;
    }

    imagecopyresized($thumb, $source, 0, 0, 0, 0 , $newwidth, $newheight, $imgw, $imgh);
    imagecopy($thumba, $thumb, 0, 0, $left, $top, $newwidth, $newheight);

    $ddn = substr($srcFile, -3);
    
    $ddpicok = $reObjJs = '';
    if( empty($isupload) )
    {
        $ddpicok = preg_replace("#\.".$ddn."$#", '-lp.'.$ddn, $file);
        $reObjJs = "        var backObj = window.opener.document.form1.picname;
        var prvObj = window.opener.document.getElementById('divpicview');\r\n";
    }
    else
    {
        $ddpicok = $file;
        $reObjJs = "        var backObj = window.opener.parent.document.form1.picname;
        var prvObj = window.opener.parent.document.getElementById('divpicview');\r\n";
    }
    
    $ddpicokurl = $cfg_basedir.$ddpicok;

    switch($imginfo['mime'])
    {
        case 'image/jpeg':
            imagejpeg($thumba, $ddpicokurl, 85);
            break;
        case 'image/gif':
            imagegif($thumba, $ddpicokurl);
            break;
        case 'image/png':
            imagepng($thumba, $ddpicokurl);
            break;
        default:
            ShowMsg("对不起，裁剪图片类型不支持请选择其他类型图片！", "-1");
            break;
    }
    
    //对任意裁剪方式再次缩小图片至限定大小
    if($newwidth > $cfg_ddimg_width || $newheight > $cfg_ddimg_height)
    {
        ImageResize($ddpicokurl, $cfg_ddimg_width, $cfg_ddimg_height);
    }
    
    //如果从其它图中剪出， 保存附件信息
    if( empty($isupload) )
    {
         $inquery = "INSERT INTO `#@__uploads`(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
        VALUES ('$ddpicok','$ddpicok','1','0','0','0','".filesize($ddpicokurl)."','".time()."','".$cuserLogin->getUserID()."'); ";
         $dsql->ExecuteNoneQuery($inquery);
         $fid = $dsql->GetLastID();
         AddMyAddon($fid, $ddpicok);
    }
    
?>
<SCRIPT language=JavaScript>
function ReturnImg(reimg)
{
    <?php echo $reObjJs; ?>
    backObj.value = reimg;
    if(prvObj)
    {
        prvObj.style.width = '150px';
        prvObj.innerHTML = "<img src='"+reimg+"?n' width='150' />";
    }
    if(document.all) {
        window.opener=true;
    }
    window.close();
}
ReturnImg("<?php echo $ddpicok; ?>");
</SCRIPT>
<?php
}
?>