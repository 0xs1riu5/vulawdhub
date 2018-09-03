<?php
/**
 * 站内新闻管理
 *
 * @version        $Id: mynews_main.php 1 15:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$sql = "SELECT
 #@__mynews.aid,#@__mynews.title,#@__mynews.writer,
 #@__mynews.senddate,#@__mynews.typeid,
 #@__arctype.typename
 FROM #@__mynews
 LEFT JOIN #@__arctype ON #@__arctype.id=#@__mynews.typeid
 ORDER BY aid DESC";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/mynews_main.htm");
$dlist->SetSource($sql);
$dlist->display();