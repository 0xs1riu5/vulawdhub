<?php
defined('IN_DESTOON') or exit('Access Denied');
?>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">QQ登录</td>
<td>
<input type="radio" name="oauth[qq][enable]" value="1" <?php if($qq['enable']) echo 'checked';?> onclick="Dd('oa_qq').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[qq][enable]" value="0" <?php if(!$qq['enable']) echo 'checked';?> onclick="Dd('oa_qq').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo DT_PATH;?>api/oauth/qq/ico.png" align="absmiddle"/> <a href="<?php echo DT_PATH;?>api/redirect.php?url=http://connect.qq.com/" target="_blank" class="t">帐号申请</a>
</td>
</tr>
<tbody style="display:<?php echo $qq['enable'] ? '' : 'none';?>" id="oa_qq">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="oauth[qq][name]" value="<?php echo $qq['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="oauth[qq][order]" value="<?php echo $qq['order'];?>"/></td>
</tr>
<tr>
<td class="tl">APP ID</td>
<td><input type="text" size="40" name="oauth[qq][id]" value="<?php echo $qq['id'];?>"/></td>
</tr>
<tr>
<td class="tl">APP KEY</td>
<td><input type="text" size="40" name="oauth[qq][key]" value="<?php echo $qq['key'];?>"/></td>
</tr>
</tbody>

<tr>
<td class="tl">新浪微博</td>
<td>
<input type="radio" name="oauth[sina][enable]" value="1" <?php if($sina['enable']) echo 'checked';?> onclick="Dd('oa_sina').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[sina][enable]" value="0" <?php if(!$sina['enable']) echo 'checked';?> onclick="Dd('oa_sina').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo DT_PATH;?>api/oauth/sina/ico.png" align="absmiddle"/> <a href="<?php echo DT_PATH;?>api/redirect.php?url=http://open.t.sina.com.cn/" target="_blank" class="t">帐号申请</a>
</td>
</tr>
<tbody style="display:<?php echo $sina['enable'] ? '' : 'none';?>" id="oa_sina">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="oauth[sina][name]" value="<?php echo $sina['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="oauth[sina][order]" value="<?php echo $sina['order'];?>"/></td>
</tr>
<tr>
<td class="tl">App Key</td>
<td><input type="text" size="40" name="oauth[sina][id]" value="<?php echo $sina['id'];?>"/></td>
</tr>
<tr>
<td class="tl">App Secret</td>
<td><input type="text" size="40" name="oauth[sina][key]" value="<?php echo $sina['key'];?>"/></td>
</tr>
<tr>
<td class="tl">发布信息同步</td>
<td>
<input type="radio" name="oauth[sina][sync]" value="1"  <?php if($sina['sync']) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[sina][sync]" value="0"  <?php if(!$sina['sync']) echo 'checked';?>/> 关闭
</td>
</tr>
</tbody>

<tr>
<td class="tl">百度登录</td>
<td>
<input type="radio" name="oauth[baidu][enable]" value="1" <?php if($baidu['enable']) echo 'checked';?> onclick="Dd('oa_baidu').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[baidu][enable]" value="0" <?php if(!$baidu['enable']) echo 'checked';?> onclick="Dd('oa_baidu').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo DT_PATH;?>api/oauth/baidu/ico.png" align="absmiddle"/> <a href="<?php echo DT_PATH;?>api/redirect.php?url=http://developer.baidu.com/ms/oauth/" target="_blank" class="t">帐号申请</a>
</td>
</tr>
<tbody style="display:<?php echo $baidu['enable'] ? '' : 'none';?>" id="oa_baidu">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="oauth[baidu][name]" value="<?php echo $baidu['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="oauth[baidu][order]" value="<?php echo $baidu['order'];?>"/></td>
</tr>
<tr>
<td class="tl">API Key</td>
<td><input type="text" size="40" name="oauth[baidu][id]" value="<?php echo $baidu['id'];?>"/></td>
</tr>
<tr>
<td class="tl">Secret Key</td>
<td><input type="text" size="40" name="oauth[baidu][key]" value="<?php echo $baidu['key'];?>"/></td>
</tr>
</tbody>

<tr>
<td class="tl">网易通行证</td>
<td>
<input type="radio" name="oauth[netease][enable]" value="1" <?php if($netease['enable']) echo 'checked';?> onclick="Dd('oa_netease').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[netease][enable]" value="0" <?php if(!$netease['enable']) echo 'checked';?> onclick="Dd('oa_netease').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo DT_PATH;?>api/oauth/netease/ico.png" align="absmiddle"/> <a href="<?php echo DT_PATH;?>api/redirect.php?url=http://reg.163.com/help/help_oauth2.html" target="_blank" class="t">帐号申请</a>
</td>
</tr>
<tbody style="display:<?php echo $netease['enable'] ? '' : 'none';?>" id="oa_netease">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="oauth[netease][name]" value="<?php echo $netease['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="oauth[netease][order]" value="<?php echo $netease['order'];?>"/></td>
</tr>
<tr>
<td class="tl">Client ID</td>
<td><input type="text" size="40" name="oauth[netease][id]" value="<?php echo $netease['id'];?>"/></td>
</tr>
<tr>
<td class="tl">Client secret</td>
<td><input type="text" size="40" name="oauth[netease][key]" value="<?php echo $netease['key'];?>"/></td>
</tr>
</tbody>

<tr>
<td class="tl">微信登录</td>
<td>
<input type="radio" name="oauth[wechat][enable]" value="1" <?php if($wechat['enable']) echo 'checked';?> onclick="Dd('oa_wechat').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[wechat][enable]" value="0" <?php if(!$wechat['enable']) echo 'checked';?> onclick="Dd('oa_wechat').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo DT_PATH;?>api/oauth/wechat/ico.png" align="absmiddle"/> <a href="<?php echo DT_PATH;?>api/redirect.php?url=https://open.weixin.qq.com/cgi-bin/frame?t=home/web_tmpl&lang=zh_CN" target="_blank" class="t">帐号申请</a>
</td>
</tr>
<tbody style="display:<?php echo $wechat['enable'] ? '' : 'none';?>" id="oa_wechat">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="oauth[wechat][name]" value="<?php echo $wechat['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="oauth[wechat][order]" value="<?php echo $wechat['order'];?>"/></td>
</tr>
<tr>
<td class="tl">AppID</td>
<td><input type="text" size="40" name="oauth[wechat][id]" value="<?php echo $wechat['id'];?>"/></td>
</tr>
<tr>
<td class="tl">AppSecret</td>
<td><input type="text" size="40" name="oauth[wechat][key]" value="<?php echo $wechat['key'];?>"/></td>
</tr>
</tbody>

<tr>
<td class="tl">淘宝登录</td>
<td>
<input type="radio" name="oauth[taobao][enable]" value="1" <?php if($taobao['enable']) echo 'checked';?> onclick="Dd('oa_taobao').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="oauth[taobao][enable]" value="0" <?php if(!$taobao['enable']) echo 'checked';?> onclick="Dd('oa_taobao').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo DT_PATH;?>api/oauth/taobao/ico.png" align="absmiddle"/> <a href="<?php echo DT_PATH;?>api/redirect.php?url=http://open.taobao.com/" target="_blank" class="t">帐号申请</a>
</td>
</tr>
<tbody style="display:<?php echo $taobao['enable'] ? '' : 'none';?>" id="oa_taobao">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="oauth[taobao][name]" value="<?php echo $taobao['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="oauth[taobao][order]" value="<?php echo $taobao['order'];?>"/></td>
</tr>
<tr>
<td class="tl">AppID</td>
<td><input type="text" size="40" name="oauth[taobao][id]" value="<?php echo $taobao['id'];?>"/></td>
</tr>
<tr>
<td class="tl">AppSecret</td>
<td><input type="text" size="40" name="oauth[taobao][key]" value="<?php echo $taobao['key'];?>"/></td>
</tr>
</tbody>
</table>