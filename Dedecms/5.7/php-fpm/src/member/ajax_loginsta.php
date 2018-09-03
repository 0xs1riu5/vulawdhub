<?php
/**
 * @version        $Id: ajax_loginsta.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
if($myurl == '') exit('');

$uid  = $cfg_ml->M_LoginID;

!$cfg_ml->fields['face'] && $face = ($cfg_ml->fields['sex'] == '女')? 'dfgirl' : 'dfboy';
$facepic = empty($face)? $cfg_ml->fields['face'] : $GLOBALS['cfg_memberurl'].'/templets/images/'.$face.'.png';
?>
<div class="userinfo">
    <div class="welcome">你好：<strong><?php echo $cfg_ml->M_UserName; ?></strong>，欢迎登录 </div>
    <div class="userface">
        <a href="<?php echo $cfg_memberurl; ?>/index.php"><img src="<?php echo $facepic;?>" width="52" height="52" /></a>
    </div>
    <div class="mylink">
        <ul>
            <li><a href="<?php echo $cfg_memberurl; ?>/guestbook_admin.php">我的留言</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/mystow.php">我的收藏</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/article_add.php">发表文章</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/myfriend.php">好友管理</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/visit-history.php">访客记录</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/search.php">查找好友</a></li>
        </ul>
    </div>
    <div class="uclink">
        <a href="<?php echo $cfg_memberurl; ?>/index.php">会员中心</a> | 
        <a href="<?php echo $cfg_memberurl; ?>/edit_fullinfo.php">资料</a> | 
        <a href="<?php echo $myurl;?>">空间</a> | 
        <a href="<?php echo $cfg_memberurl; ?>/index_do.php?fmdo=login&dopost=exit">退出登录</a> 
    </div>
</div><!-- /userinfo -->