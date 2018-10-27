<?php
defined('IN_DESTOON') or exit('Access Denied');
$kf_qiao = preg_match("/^[0-9a-z]{32}$/", $kf) ? $kf : '';
?>
<tr id="kf_post_qiao" style="display:none;">
<td class="tl">在线客服帐号</td>
<td class="tr">
<input type="text" name="kf[qiao]" id="kf_qiao" value="<?php echo $kf_qiao;?>" size="40"/>&nbsp;&nbsp;
<?php if($kf_qiao) { ?>
<a href="http://qiao.baidu.com/" class="t" target="_blank">帐号管理</a>
<?php } else { ?>
<a href="http://qiao.baidu.com/" class="t" target="_blank">帐号申请</a>
<?php } ?><br/><br/>
提示：注册后获取的客服代码“...hm.baidu.com/h.js%3F<span class="f_red">321c361fa45809b610d5ec4ae9a392c2</span>' type=...”中<span class="f_red">321c361fa45809b610d5ec4ae9a392c2</span>即为客服帐号
</td>
</tr>