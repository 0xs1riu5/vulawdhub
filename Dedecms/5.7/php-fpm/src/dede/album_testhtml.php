<?php
/**
 * 图集测试
 *
 * @version        $Id: album_testhtml.php 1 8:26 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

require_once(dirname(__FILE__)."/config.php");
AjaxHead();
$myhtml = UnicodeUrl2Gbk(stripslashes($myhtml));
echo "<div class='coolbg61'>[<a href='#' onclick='javascript:HideObj(\"_myhtml\")'>关闭</a>]</div>\r\n";
preg_match_all("/(src|SRC)=[\"|'| ]{0,}(http:\/\/(.*)\.(gif|jpg|jpeg|png))/isU", $myhtml, $img_array);
$img_array = array_unique($img_array[2]);
echo "<div class='coolbg62'><xmp>";
echo "捕获的图片：\r\n";
print_r($img_array);
echo "</xmp></div>\r\n";