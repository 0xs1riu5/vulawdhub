<?php
/**
 * 自定义表单列表管理
 *
 * @version        $Id: diy_main.php 1 18:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_List');
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC."/common.func.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$sql = "Select `diyid`,`name`,`table` From #@__diyforms order by diyid asc";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/diy_main.htm");
$dlist->SetSource($sql);
$dlist->display();
$dlist->Close();