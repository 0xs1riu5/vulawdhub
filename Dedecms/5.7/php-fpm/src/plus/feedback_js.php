<?php
/**
 *
 * 评论js调用
 *
 * @version        $Id: freelist.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
if($cfg_feedback_forbid=='Y') exit("document.write('系统已经禁止评论功能！');\r\n");
require_once(DEDEINC."/datalistcp.class.php");
if(isset($arcID)) $aid = $arcID;

$arcID = $aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
if($aid==0) exit(" Request Error! ");

$querystring = "SELECT fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores FROM `#@__feedback` fb
                 LEFT JOIN `#@__member` mb ON mb.mid = fb.mid
                 WHERE fb.aid='$aid' AND fb.ischeck='1' ORDER BY fb.id DESC";
$dlist = new DataListCP();
$dlist->pageSize = 6;
$dlist->SetTemplet(DEDETEMPLATE.'/plus/feedback_templet_js.htm');
$dlist->SetSource($querystring);
$dlist->display();

?>