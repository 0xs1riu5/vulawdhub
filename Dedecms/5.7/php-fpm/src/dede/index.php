<?php
/**
 * 管理后台首页
 *
 * @version        $Id: index.php 1 11:06 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC.'/dedetag.class.php');
$defaultIcoFile = DEDEDATA.'/admin/quickmenu.txt';
$myIcoFile = DEDEDATA.'/admin/quickmenu-'.$cuserLogin->getUserID().'.txt';

if(!file_exists($myIcoFile)) $myIcoFile = $defaultIcoFile;

require(DEDEADMIN.'/inc/inc_menu_map.php');
include(DEDEADMIN.'/templets/index2.htm');
exit();

