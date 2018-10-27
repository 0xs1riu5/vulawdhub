<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<style type="text/css">
#todo {display:none;width:100%;border-bottom:#E7E7EB 1px solid;padding-bottom:10px;}
#todo ul {margin-top:10px;}
#todo li {float:left;width:180px;line-height:32px;padding:0 16px;}
#todo li b {color:red;padding:0 2px;font-size:12px;}
</style>
<div id="tips_update" style="display:none;">
<div class="tt">更新提示</div>
<table cellspacing="0" class="tb">
<tr>
<td><div style="padding:20px;" title="当前版本V<?php echo DT_VERSION; ?> 更新时间<?php echo DT_RELEASE;?>"><img src="admin/image/tips-update.png" width="32" height="32" align="absmiddle"/>&nbsp;&nbsp; 您的当前软件版本有新的更新，请注意升级&nbsp;&nbsp;&nbsp;&nbsp;最新版本：V<span id="last_v"><?php echo DT_VERSION; ?></span>&nbsp;&nbsp;更新时间：<span id="last_r"><?php echo DT_RELEASE; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="?file=cloud&action=update" class="t">[立即检查]</a></div></td>
</tr>
</table>
</div>
<div class="tt"><span class="f_r px12" style="font-weight:normal;">IP:<?php echo $user['loginip']; ?> <?php echo ip2area($user['loginip']);?></span>欢迎管理员，<?php echo $_username;?></div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">管理级别</td>
<td width="30%">&nbsp;<?php echo $_admin == 1 ? (is_founder($_userid) ? '网站创始人' : '超级管理员') : ($_aid ? '<span class="f_blue">'.$AREA[$_aid]['areaname'].'站</span>管理员' : '普通管理员'); ?></td>
<td class="tl">登录次数</td>
<td width="30%">&nbsp;<?php echo $user['logintimes']; ?> 次</td>
</tr>
<tr>
<td class="tl">站内信件</td>
<td>&nbsp;<a href="<?php echo $MODULE[2]['linkurl'].'message.php';?>" target="_blank">收件箱(<?php echo $_message ? '<b class="f_red">'.$_message.'</b>' : $_message;?>)</a></td>
<td class="tl">登录时间</td>
<td>&nbsp;<?php echo timetodate($user['logintime'], 5); ?> </td>
</tr>
<tr>
<td class="tl">账户余额</td>
<td>&nbsp;<?php echo $_money; ?></td>
<td class="tl">会员<?php echo $DT['credit_name'];?></td>
<td>&nbsp;<?php echo $_credit; ?> </td>
</tr>
<form method="post" action="?">
<tr>
<td class="tl">工作便笺</td>
<td colspan="2">
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<textarea name="note" style="width:98%;height:50px;overflow:visible;color:#444444;"><?php echo $note;?></textarea></td>
<td>&nbsp;<input type="submit" name="submit" value="保 存" class="btn-g"/>
</td>
</tr>
</form>
</table>
<div id="todo"></div>
<?php if($_founder) {?>
<div id="destoon"></div>
<div class="tt">系统信息</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">程序信息</td>
<td>&nbsp;<a href="?file=cloud&action=update" class="t">DESTOON B2B Version <?php echo DT_VERSION;?> Release <?php echo DT_RELEASE;?> <?php echo DT_CHARSET;?> <?php echo strtoupper(DT_LANG);?> [检查更新]</a></td>
</tr>
<tr>
<td class="tl">软件版本</td>
<?php if($edition == '&#20010;&#20154;&#29256;') { ?>
<td id="destoon_edition">&nbsp;<span class="f_blue"><?php echo $edition;?></span> <span class="f_red">(未授权)</span>&nbsp;&nbsp;<a href="?file=cloud&action=buy" target="_blank" class="t">[购买授权]</a></td>
<?php } else { ?>
<td id="destoon_edition">&nbsp;<span class="f_blue">商业<?php echo $edition;?></span>&nbsp;&nbsp;<a href="?file=cloud&action=biz" target="_blank" class="t" title="技术支持">[技术支持]</a></td>
<?php } ?>
</tr>
<tr>
<td class="tl">安装时间</td>
<td>&nbsp;<?php echo $install;?></td>
</tr>
<tr>
<td class="tl">官方网站</td>
<td>&nbsp;<a href="https://www.destoon.com/?tracert=AdminMain" target="_blank">https://www.destoon.com/</a></td>
</tr>
<tr>
<td class="tl">用户论坛</td>
<td>&nbsp;<a href="https://bbs.destoon.com/?tracert=AdminMain" target="_blank">https://bbs.destoon.com/</a></td>
</tr>
<tr>
<td class="tl">帮助文档</td>
<td>&nbsp;<a href="https://www.destoon.com/doc/?tracert=AdminMain" target="_blank">https://www.destoon.com/doc/</a></td>
</tr>
<tr>
<td class="tl">专用主机</td>
<td>&nbsp;<a href="https://www.destoon.com/host/?tracert=AdminMain" target="_blank">https://www.destoon.com/host/</a></td>
</tr>
<tr>
<td class="tl">SSL证书</td>
<td>&nbsp;<a href="https://www.destoon.com/ssl/?tracert=AdminMain" target="_blank">https://www.destoon.com/ssl/</a></td>
</tr>
<tr>
<td class="tl">服务器时间</td>
<td>&nbsp;<?php echo timetodate($DT_TIME, 'Y-m-d H:i:s l');?></td>
</tr>
<tr>
<td class="tl">服务器信息</td>
<td>&nbsp;<?php echo PHP_OS.'&nbsp;'.$_SERVER["SERVER_SOFTWARE"];?> [<?php echo gethostbyname($_SERVER['SERVER_NAME']);?>:<?php echo $_SERVER["SERVER_PORT"];?>] <a href="?file=doctor" class="t">[系统体检]</a></td>
</tr>
<tr>
<td class="tl">数据库版本</td>
<td>&nbsp;MySQL <?php echo $db->version();?></td>
</tr>
<tr>
<td class="tl">站点路径</td>
<td>&nbsp;<?php echo DT_ROOT;?></td>
</tr>
</table>
<div class="tt">使用协议</div>
<table cellspacing="0" class="tb">
<tr>
<td style="padding:10px;"><textarea style="width:100%;height:100px;" onmouseover="this.style.height='600px';" onmouseout="this.style.height='100px';"><?php echo file_get(DT_ROOT.'/license.txt');?></textarea></td>
</tr>
</table>
<script type="text/javascript" src="?action=todo&rand=<?php echo $DT_TIME;?>"></script>
<script type="text/javascript" src="<?php echo $notice_url;?>"></script>
<script type="text/javascript">
$(function(){
	var destoon_release = <?php echo DT_RELEASE;?>;
	var destoon_version = <?php echo DT_VERSION;?>;
	if(typeof destoon_lastrelease != 'undefined') {
		var lastrelease = parseInt(destoon_lastrelease.replace('-', '').replace('-', ''));
		if(destoon_lastversion == destoon_version && destoon_release < lastrelease) {
			$('#last_v').html(destoon_lastversion);
			$('#last_r').html(destoon_lastrelease);
			$('#tips_update').slideDown(600);
		}
	}
});
</script>
<?php } ?>
<script type="text/javascript">Menuon(0);</script>
<?php include tpl('footer');?>