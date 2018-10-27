<?php
defined('IN_DESTOON') or exit('Access Denied');
$stats_baidu = preg_match("/^[a-z0-9]{32}$/", $stats) ? $stats : '';
?>
<tr id="stats_post_baidu" style="display:none;">
<td class="tl">流量统计帐号</td>
<td class="tr">
<input type="text" name="stats[baidu]" id="stats_baidu" value="<?php echo $stats_baidu;?>" size="40"/>&nbsp;&nbsp;
<?php if($stats_baidu) { ?>
<a href="http://tongji.baidu.com/" class="t" target="_blank">查看统计</a>
<?php } else { ?>
<a href="http://tongji.baidu.com/" class="t" target="_blank">帐号申请</a>
<?php } ?><br/><br/>
提示：注册后获取的统计代码“...hm.baidu.com/hm.js?<span class="f_red">394a7f9c9b18fdf6cc887f7176ac0123</span>...”中<span class="f_red">394a7f9c9b18fdf6cc887f7176ac0123</span>即为统计帐号
</td>
</tr>