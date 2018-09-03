<?php
/**
 * 栏目管理
 *
 * @version        $Id: catalog_main.php 1 14:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typeunit.class.admin.php");
$userChannel = $cuserLogin->getUserChannel();
include DedeInclude('templets/catalog_main.htm');