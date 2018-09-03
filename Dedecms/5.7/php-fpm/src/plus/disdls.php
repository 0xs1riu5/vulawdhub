<?php
/**
 *
 * 下载次数统计
 *
 * 如果想显示下载次数,即把下面ＪＳ调用放到文档模板适当位置
 * <script src="{dede:global name='cfg_phpurl'/}/disdls.php?aid={dede:field name='id'/}" language="javascript"></script>
 *
 * @version        $Id: disdls.php 1 20:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
$row = $dsql->GetOne("SELECT SUM(downloads) AS totals FROM `#@__downloads` WHERE id='$aid' ");
if(empty($row['totals'])) $row['totals'] = 0;
echo "document.write('{$row['totals']}');";
exit();