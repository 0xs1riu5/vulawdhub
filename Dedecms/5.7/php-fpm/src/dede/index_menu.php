<?php
/**
 * 菜单项
 *
 * @version        $Id: index_menu.php 1 11:06 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__).'/config.php');
require(DEDEADMIN.'/inc/inc_menu.php');
require(DEDEADMIN.'/inc/inc_menu_func.php');
$openitem = (empty($openitem) ? 1 : $openitem);
include DedeInclude('templets/index_menu2.htm');
