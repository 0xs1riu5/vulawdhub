<?php
/**
 * 多媒体选择框
 *
 * @version        $Id: select_media.php 1 9:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
require_once(dirname(__FILE__)."/config.php");
if(empty($activepath))
{
    $activepath = '';
}
$activepath = str_replace('.', '', $activepath);
$activepath = preg_replace("#\/{1,}#", '/', $activepath);
if(strlen($activepath) < strlen($cfg_other_medias))
{
    $activepath = $cfg_other_medias;
}
$inpath = $cfg_basedir.$activepath;
$activeurl = '..'.$activepath;
if(empty($f))
{
    $f = 'form1.enclosure';
}

if(empty($comeback))
{
    $comeback = '';
}
$addparm = '';
if (!empty($CKEditor))
{
    $addparm = '&CKEditor='.$CKEditor;
}
if (!empty($CKEditorFuncNum))
{
    $addparm .= '&CKEditorFuncNum='.$CKEditorFuncNum;
}
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
<title>媒体文件管理器</title>
<link href='../../plus/img/base.css' rel='stylesheet' type='text/css'>
<style>
.linerow {border-bottom: 1px solid #CBD8AC;}
</style>
</head>
<body background='img/allbg.gif' leftmargin='0' topmargin='0'>
<SCRIPT language='JavaScript'>
function nullLink()
{
	return;
}
<?php
if ($GLOBALS['cfg_html_editor']=='ckeditor')
{
?>
function ReturnValue(reimg)
{
    var funcNum = <?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>;
    window.opener.CKEDITOR.tools.callFunction(funcNum, reimg);
    window.close();
}
<?php
} else {
?>
function ReturnValue(reimg)
{
	window.opener.document.<?php echo $f?>.value=reimg;
	if(document.all) window.opener=true;
	window.close();
}
<?php
}
?>
</SCRIPT>
<table width='100%' border='0' cellpadding='0' cellspacing='1' bgcolor='#CBD8AC' align="center">
<tr bgcolor='#FFFFFF'>
<td colspan='3'>
<!-- 开始文件列表  -->
<table width='100%' border='0' cellspacing='0' cellpadding='2'>
<tr bgcolor="#CCCCCC">
<td width="55%" align="center" background="img/wbg.gif" class='linerow'><strong>点击名称选择文件</strong></td>
<td width="15%" align="center" bgcolor='#EEF4EA' class='linerow'><strong>文件大小</strong></td>
<td width="30%" align="center" background="img/wbg.gif" class='linerow'><strong>最后修改时间</strong></td>
</tr>
<?php
$dh = dir($inpath);
$ty1="";
$ty2="";
while($file = $dh->read()) {
    //-----计算文件大小和创建时间
    if($file!="." && $file!=".." && !is_dir("$inpath/$file")){
        $filesize = filesize("$inpath/$file");
        $filesize = $filesize/1024;
        if($filesize!="")
        if($filesize<0.1)
        {
            @list($ty1,$ty2) = split("\.", $filesize);
            $filesize = $ty1.".".substr($ty2, 0, 2);
        }
        else{
            @list($ty1,$ty2) = split("\.", $filesize);
            $filesize=$ty1.".".substr($ty2, 0, 1);
        }
        $filetime = filemtime("$inpath/$file");
        $filetime = MyDate("Y-m-d H:i:s", $filetime);
    }

    //------判断文件类型并作处理
    if($file == ".") continue;
    else if($file == "..")
    {
        if($activepath == "") continue;
        $tmp = preg_replace("#[\/][^\/]*$#i", "", $activepath);
        $line = "\n<tr>
    <td class='linerow'> <a href=select_media.php?f=$f&activepath=".urlencode($tmp).$addparm."><img src=img/dir2.gif border=0 width=16 height=16 align=absmiddle>上级目录</a></td>
    <td colspan='2' class='linerow'> 当前目录:$activepath</td>
    </tr>\r\n";
        echo $line;
    }
    else if(is_dir("$inpath/$file"))
    {
        if(preg_match("#^_(.*)$#i", $file)) continue; #屏蔽FrontPage扩展目录和linux隐蔽目录
        if(preg_match("#^\.(.*)$#i", $file)) continue;
        $line = "\n<tr>
   <td bgcolor='#F9FBF0' class='linerow'>
    <a href=select_media.php?f=$f&activepath=".urlencode("$activepath/$file").$addparm."><img src=img/dir.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>-</td>
   <td bgcolor='#F9FBF0' class='linerow'>-</td>
   </tr>";
        echo "$line";
    }
    else if(preg_match("#\.(swf|fly|fla|flv)#i", $file)){
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
   <td class='linerow' bgcolor='#F9FBF0'>
     <a href=\"javascript:ReturnValue('$reurl');\"><img src=img/flash.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
        echo "$line";
    }
    else if(preg_match("#\.(wmv|avi)#i", $file)){

        $reurl = "$activeurl/$file";
        $reurl = preg_replace("#^\.\.#", "", $reurl);
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
        {
            $reurl  = $remoteupUrl.$reurl;
        } else {
            $reurl = $reurl;
        }

        if($file==$comeback) $lstyle = " style='color:red' ";
        else  $lstyle = "";

        $line = "\n<tr>
   <td class='linerow' bgcolor='#F9FBF0'>
     <a href=\"javascript:ReturnValue('$reurl');\"><img src=img/wmv.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
        echo "$line";
    }
    else if(preg_match("#\.(rm|rmvb)#i", $file))
    {
        $reurl = "$activeurl/$file";
        $reurl = preg_replace("#^\.\.#", "", $reurl);
        if($cfg_remote_site=='Y' && $remoteuploads == 1)
        {
           $reurl  = $remoteupUrl.$reurl;
        } else {
            $reurl = $reurl;
        }

        if($file==$comeback) $lstyle = " style='color:red' ";
        else  $lstyle = "";

        $line = "\n<tr>
   <td class='linerow' bgcolor='#F9FBF0'>
     <a href=\"javascript:ReturnValue('$reurl');\"><img src=img/rm.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
        echo "$line";
    }
    else if(preg_match("#\.(mp3|wma)#", $file)){

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
   <td class='linerow' bgcolor='#F9FBF0'>
     <a href=\"javascript:ReturnValue('$reurl');\"><img src=img/mp3.gif border=0 width=16 height=16 align=absmiddle>$file</a>
   </td>
   <td class='linerow'>$filesize KB</td>
   <td align='center' class='linerow' bgcolor='#F9FBF0'>$filetime</td>
   </tr>";
        echo "$line";
    }
}//End Loop
$dh->close();
?>
<!-- 文件列表完 -->
<tr>
<td colspan='3' bgcolor='#E8F1DE'>

<table width='100%'>
<form action='select_media_post.php' method='POST' enctype="multipart/form-data" name='myform'>
<input type='hidden' name='activepath' value='<?php echo $activepath?>'>
<input type='hidden' name='f' value='<?php echo $f?>'>
<input type='hidden' name='job' value='upload'>
<input type='hidden' name='CKEditorFuncNum' value='<?php echo isset($CKEditorFuncNum)? $CKEditorFuncNum : 1;?>'>
<tr>
<td background="img/tbg.gif" bgcolor="#99CC00">
  &nbsp;上　传： <input type='file' name='uploadfile' style='width:320px'>&nbsp;<input type='submit' name='sb1' value='确定'>
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

</body>
</html>