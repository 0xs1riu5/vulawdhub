<?php
/**
 * 多媒体发送
 *
 * @version        $Id: select_media_post.php 1 9:43 2010年7月8日Z tianya $
 * @package        DedeCMS.Dialog
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
include_once(dirname(__FILE__).'/config.php');
$cfg_softtype = $cfg_mediatype;
$cfg_soft_dir = $cfg_other_medias;
$bkurl = 'select_media.php';
$uploadmbtype = "多媒体文件类型";
require_once(dirname(__FILE__)."/select_soft_post.php");