<?php
/**
 * @version        $Id: login.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
if($cfg_ml->IsLogin())
{
    ShowMsg('你已经登陆系统，无需重新注册！', 'index.php');
    exit();
}
require_once(dirname(__FILE__)."/templets/login.htm");