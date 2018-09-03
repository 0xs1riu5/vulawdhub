<?php
/**
 * 图片选择框
 *
 * @version        $Id: select_images.php 1 9:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
include(DEDEDATA.'/mark/inc_photowatermark_config.php');
if(empty($activepath))
{
    $activepath = '';
}
if(empty($imgstick))
{
    $imgstick = '';
}
$noeditor = isset($noeditor)? $noeditor : '';
$activepath = str_replace('.', '', $activepath);
$activepath = preg_replace("#\/{1,}#", '/', $activepath);
if(strlen($activepath) < strlen($cfg_medias_dir))
{
    $activepath = $cfg_medias_dir;
}
$inpath = $cfg_basedir.$activepath;
$activeurl = '..'.$activepath;


if(empty($f))
{
    $f = 'form1.picname';
}
if(empty($v))
{
    $v = 'picview';
}
if(empty($comeback))
{
    $comeback = '';
}
$addparm = '';
if (!empty($CKEditor))
{
    $addparm = '&CKEditor='.$CKEditor;
    $f = $CKEditor;
}
if (!empty($CKEditorFuncNum))
{
    $addparm .= '&CKEditorFuncNum='.$CKEditorFuncNum;
}

if (!empty($noeditor))
{
    $addparm .= '&noeditor=yes';
}
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
<title>图片浏览器</title>
<link href='../../plus/img/base.css' rel='stylesheet' type='text/css'>
<style>
.linerow {border-bottom: 1px solid #CBD8AC;}
.napisdiv {left:40;top:3;width:150px;height:100px;position:absolute;z-index:3;display:none;}
</style>
<script>
function nullLink(){ return; }
function ChangeImage(surl){ document.getElementById('picview').src = surl; }
</script>
</head>
<body background='img/allbg.gif' leftmargin='0' topmargin='0'>
<div id="floater" class="napisdiv">
<a href="javascript:nullLink();" onClick="document.getElementById('floater').style.display='none';"><img src='img/picviewnone.gif' id='picview' border='0' alt='单击关闭预览'></a>
</div>
<SCRIPT language=JavaScript src="js/float.js"></SCRIPT>
<SCRIPT language=JavaScript>
function nullLink(){ return; }
function ChangeImage(surl){ document.getElementById('floater').style.display='block';document.getElementById('picview').src = surl; }
function TNav()
{
	if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
  else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
  else return "OT";
}
<?php
if ($GLOBALS['cfg_html_editor']=='ckeditor' && $noeditor == '')
{
?>
// 获取地址参数
function getUrlParam(paramName)
{
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
  return (match && match.length > 1) ? match[1] : '' ;
}

function ReturnImg(reimg)
{
    var funcNum = getUrlParam('CKEditorFuncNum');
    var fileUrl = reimg;
    window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
    window.close();
}
<?php
} else {
?>
function ReturnImg(reimg)
{
	window.opener.document.<?php echo $f?>.value=reimg;
	if(window.opener.document.getElementById('div<?php echo $v?>'))
  {
  	 if(TNav()=='IE'){
  	 	 window.opener.document.getElementById('div<?php echo $v?>').filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = reimg;
  	 	 window.opener.document.getElementById('div<?php echo $v?>').style.width = '150px';
  	 	 window.opener.document.getElementById('div<?php echo $v?>').style.height = '100px';
  	 }
  	 else
  	 	 window.opener.document.getElementById('div<?php echo $v?>').style.backgroundImage = "url("+reimg+")";
  }
	else if(window.opener.document.getElementById('<?php echo $v?>')){
		window.opener.document.getElementById('<?php echo $v?>').src = reimg;
	}
	if(document.all) window.opener=true;
  window.close();
}
<?php
}
?>
</SCRIPT>
<table width='100%' border='0' cellspacing='0' cellpadding='0' align="center">
<tr>
<td colspan='4' align='right'>
<table width='100%' border='0' cellpadding='0' cellspacing='1' bgcolor='#CBD8AC'>
<tr bgcolor='#FFFFFF'>
<td colspan='4'>
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
<tr bgcolor="#CCCCCC">
<td width="8%" align="center" class='linerow' bgcolor='#EEF4EA'><strong>预览</strong></td>
<td width="47%" align="center" background="img/wbg.gif" class='linerow'><strong>点击名称选择图片</strong></td>
<td width="15%" align="center" bgcolor='#EEF4EA' class='linerow'><strong>文件大小</strong></td>
<td width="30%" align="center" background="img/wbg.gif" class='linerow'><strong>最后修改时间</strong></td>
</tr>
<tr>
<td class='linerow' colspan='4' bgcolor='#F9FBF0'>
点击“V”预览图片，点击图片名选择图片，显示图片后点击该图片关闭预览。
</td>
</tr>
<?php
$dh = dir($inpath);
$ty1="";
$ty2="";
while($file = $dh->read()) {

    //-----计算文件大小和创建时间
    if($file!="." && $file!=".." && !is_dir("$inpath/$file")){
        $filesize = filesize("$inpath/$file");
        $filesize = $filesize / 1024;
        if($filesize != "")
        if($filesize < 0.1){
            @list($ty1, $ty2) = split("\.", $filesize);
            $filesize = $ty1.".".substr($ty2, 0, 2);
        }
        else{
            @list($ty1, $ty2) = split("\.", $filesize);
            $filesize = $ty1.".".substr($ty2, 0, 1);
        }
        $filetime = filemtime("$inpath/$file");
        $filetime = MyDate("Y-m-d H:i:s", $filetime);
    }

    if($file == ".") continue;
    else if($file == "..")
    {
        if($activepath == "") continue;
        $tmp = preg_replace("#[\/][^\/]*$#i", "", $activepath);
        $line = "\n<tr>
   <td class='linerow' colspan='2'>
   <a href='select_images.php?imgstick=$imgstick&v=$v&f=$f&activepath=".urlencode($tmp).$addparm."'><img src=img/dir2.gif border=0 width=16 height=16 align=absmiddle>上级目录</a></td>
   <td colspan='2' class='linerow'> 当前目录:$activepath</td>
   </tr>
   ";
        echo $line;
    }
    else if(is_dir("$inpath/$file"))
    {
        if(preg_match("#^_(.*)$#i", $file)) continue; #屏蔽FrontPage扩展目录和linux隐蔽目录
        if(preg_match("#^\.(.*)$#i", $file)) continue;
        $line = "\n<tr>
   <td bgcolor='#F9FBF0' class='linerow' colspan='2'>
   <a href='select_images.php?imgstick=$imgstick&v=$v&f=$f&activepath=".urlencode("$activepath/$file").$addparm."'><img src=img/dir.gif border=0 width=16 height=16 align=absmiddle>$file</a></td>
   <td class='linerow'>　</td>
   <td bgcolor='#F9FBF0' class='linerow'>　</td>
   </tr>";
        echo "$line";
    }
    else if(preg_match("#\.(gif|png)#i", $file))
    {
        $reurl = "$activeurl/$file";
        $reurl = preg_replace("#^\.\.#", "", $reurl);
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
         {
           $reurl  = $remoteupUrl.$reurl;
        }else{
            $reurl = $reurl;
        }

        if($file==$comeback) $lstyle = " style='color:red' ";
        else  $lstyle = "";

        $line = "\n<tr>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>
   <a href=\"#\" onClick=\"ChangeImage('$reurl');\"><img src='img/picviewnone.gif' width='16' height='16' border='0' align=absmiddle></a>
   </td>
   <td class='linerow' bgcolor='#F9FBF0'>
   <a href=# onclick=\"ReturnImg('$reurl');\" $lstyle><img src=img/gif.gif border=0 width=16 height=16 align=absmiddle>$file</a></td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
        echo "$line";
    }
    else if(preg_match("#\.(jpg)#i", $file))
    {
        $reurl = "$activeurl/$file";
        $reurl = preg_replace("#^\.\.#", "", $reurl);
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
         {
           $reurl  = $remoteupUrl.$reurl;
        }else{
            $reurl = $reurl;
        }

        if($file==$comeback) $lstyle = " style='color:red' ";
        else  $lstyle = "";

        $line = "\n<tr>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>
   <a href=\"#\" onClick=\"ChangeImage('$reurl');\"><img src='img/picviewnone.gif' width='16' height='16' border='0' align=absmiddle></a>
   </td>
   <td class='linerow' bgcolor='#F9FBF0'>
   <a href=# onclick=\"ReturnImg('$reurl');\" $lstyle><img src=img/jpg.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
        echo "$line";
    }
}//End Loop
$dh->close();
?>
<tr>
<td colspan='4' bgcolor='#E8F1DE'>

<table width='100%'>
<form action='select_images_post.php' method='POST' enctype="multipart/form-data" name='myform'>
<input type='hidden' name='activepath' value='<?php echo $activepath?>'>
<input type='hidden' name='f' value='<?php echo $f?>'>
<input type='hidden' name='v' value='<?php echo $v?>'>
<input type='hidden' name='imgstick' value='<?php echo $imgstick?>'>
<input type='hidden' name='CKEditorFuncNum' value='<?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>'>
<input type='hidden' name='job' value='upload'>
<tr>
<td background="img/tbg.gif" bgcolor="#99CC00">
  &nbsp;上　传： <input type='file' name='imgfile' style='width:250px'/>
  <input type='checkbox' name='needwatermark' value='1' class='np' <?php if($photo_markup=='1') echo "checked"; ?> />水印
  <input type='checkbox' name='resize' value='1' class='np' />缩小
  宽：<input type='text' style='width:30' name='iwidth' value='<?php echo $cfg_ddimg_width?>' />
  高：<input type='text' style='width:30' name='iheight' value='<?php echo $cfg_ddimg_height?>' />
  <input type='submit' name='sb1' value='确定' />
</td>
</tr>
</form>
</table>

</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>