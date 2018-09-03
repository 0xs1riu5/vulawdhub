<?php
/**
 * 模板发送
 *
 * @version        $Id: select_templets_post.php 1 9:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
 
require_once(dirname(__FILE__)."/config.php");
$cfg_txttype = "htm|html|tpl|txt";
if(empty($uploadfile))
{
    $uploadfile = "";
}
if(!is_uploaded_file($uploadfile))
{
    ShowMsg("你没有选择上传的文件!","-1");
    exit();
}
if(!preg_match("#^text#", $uploadfile_type))
{
    ShowMsg("你上传的不是文本类型附件!","-1");
    exit();
}
if(!preg_match("#\.(".$cfg_txttype.")#i", $uploadfile_name))
{
    ShowMsg("你所上传的模板文件类型不能被识别，只允许htm、html、tpl、txt扩展名！","-1");
    exit();
}
if($filename!='')
{
    $filename = trim(preg_replace("#[ \r\n\t\*\%\\\/\?><\|\":]{1,}#", '', $filename));
}
else
{
    $uploadfile_name = trim(preg_replace("#[ \r\n\t\*\%\\\/\?><\|\":]{1,}#", '', $uploadfile_name));
    $filename = $uploadfile_name;
    if($filename=='' || !preg_match("#\.(".$cfg_txttype.")#i", $filename))
    {
        ShowMsg("你所上传的文件存在问题，请检查文件类型是否适合！","-1");
        exit();
    }
}
$fullfilename = $cfg_basedir.$activepath."/".$filename;
move_uploaded_file($uploadfile,$fullfilename) or die("上传文件到 $fullfilename 失败！");
@unlink($uploadfile);
ShowMsg("成功上传文件！","select_templets.php?comeback=".urlencode($filename)."&f=$f&activepath=".urlencode($activepath)."&d=".time());
exit();