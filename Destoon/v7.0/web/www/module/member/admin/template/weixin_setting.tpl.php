<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">APPID</td>
<td><input name="setting[appid]" type="text" size="30" value="<?php echo $appid;?>"/></td>
</tr>
<tr>
<td class="tl">APPSECRET</td>
<td><input name="setting[appsecret]" type="text" size="60" value="<?php echo $appsecret;?>"/></td>
</tr>
<tr>
<td class="tl">URL</td>
<td><?php echo DT_PATH;?>api/weixin/index.php</td>
</tr>
<tr>
<td class="tl">TOKEN</td>
<td><input name="setting[apptoken]" type="text" size="30" value="<?php echo $apptoken;?>" id="apptoken"/> <a href="javascript:Dd('apptoken').value=RandStr();void(0);" class="t">[随机]</a></td>
</tr>
<tr>
<td class="tl">EncodingAESKey</td>
<td><input name="setting[aeskey]" type="text" size="30" value="<?php echo $aeskey;?>" id="aeskey"/></td>
</tr>
<tr>
<td class="tl">备注</td>
<td>以上信息在微信公众平台获取或设置</td>
</tr>
<tr>
<td class="tl">公众微信号</td>
<td><input name="setting[weixin]" type="text" size="20" value="<?php echo $weixin;?>"/><?php tips('一般为字母和数字的组合，不是中文名。系统会根据APPID自动生成对应的二维码(不带LOGO)，如果需要自定义，请上传到api/weixin/image/qrcode.png，系统会自动读取');?></td>
</tr>
<tr>
<td class="tl">用户关注欢迎信息</td>
<td><textarea name="setting[welcome]" style="width:400px;height:50px;"><?php echo $welcome;?></textarea>
</td>
</tr>
<tr>
<td class="tl">自动回复用户信息</td>
<td><textarea name="setting[auto]" style="width:400px;height:50px;"><?php echo $auto;?></textarea>
</td>
</tr>
<tr>
<td class="tl">绑定会员描述信息</td>
<td><textarea name="setting[bind]" style="width:400px;height:50px;"><?php echo $bind;?></textarea>
</td>
</tr>
<tr>
<td class="tl">每日签到赠送积分</td>
<td><input name="setting[credit]" type="text" size="5" value="<?php echo $credit;?>"/><?php tips('每日只积一次，建议设置以便鼓励用户经常打开，如果用户48小时没有打开过微信，系统将无法推送消息给会员');?></td>
</tr>
</table>
<div class="sbt">
<input type="submit" name="submit" value="保 存" class="btn-g"/>
</div>
</form>
<br/>
<script type="text/javascript">Menuon(5);</script>
<?php include tpl('footer');?>