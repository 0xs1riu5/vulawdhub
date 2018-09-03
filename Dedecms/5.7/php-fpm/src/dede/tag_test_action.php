<?php
/**
 * 标签测试操作
 *
 * @version        $Id: tag_test_action.php 1 23:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('temp_Test');
require_once(DEDEINC."/arc.partview.class.php");
if(empty($partcode))
{
    ShowMsg('错误请求','javascript:;');
    exit;
}
$partcode = stripslashes($partcode);

if(empty($typeid)) $typeid = 0;
if(empty($showsource)) $showsource = "";

if($typeid>0) $pv = new PartView($typeid);
else $pv = new PartView();

$pv->SetTemplet($partcode, "string");
if( $showsource == "" || $showsource == "yes" )
{
    echo "模板代码:";
    echo "<span style='color:red;'><pre>".htmlspecialchars($partcode)."</pre></span>";
    echo "结果:<hr size='1' width='100%'>";
}
$pv->Display();