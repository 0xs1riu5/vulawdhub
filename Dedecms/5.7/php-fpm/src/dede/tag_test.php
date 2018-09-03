<?php
/**
 * 标签测试
 *
 * @version        $Id: tag_test.php 1 23:07 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('temp_Other');
require_once(DEDEINC."/typelink.class.php");
include DedeInclude('templets/tag_test.htm');