<?php
defined('IN_DESTOON') or exit('Access Denied');
?>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">提示信息：</td>
<td>
以下接口需要申请的是<span class="f_red">即时到帐</span>交易，为了提升用户支付体验，不建议设置手续费，但是可以在用户提现时适当收费
</td>
</tr>
<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.alipay.com" target="_blank"><strong>支付宝 Alipay</strong></a></td>
<td>
<input type="radio" name="pay[alipay][enable]" value="1"  <?php if($alipay['enable']) echo 'checked';?> onclick="Dd('alipay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[alipay][enable]" value="0"  <?php if(!$alipay['enable']) echo 'checked';?> onclick="Dd('alipay').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://b.alipay.com/signing/productDetail.htm?productId=I1011000290000001000" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $alipay['enable'] ? '' : 'none';?>" id="alipay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[alipay][name]" value="<?php echo $alipay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[alipay][order]" value="<?php echo $alipay['order'];?>"/></td>
</tr>
<tr>
<td class="tl">支付宝帐号</td>
<td><input type="text" size="30" name="pay[alipay][email]" value="<?php echo $alipay['email'];?>"/><?php tips('仅支持即时到账接口');?></td>
</tr>
<tr>
<td class="tl">合作者身份(partnerID)</td>
<td><input type="text" size="60" name="pay[alipay][partnerid]" value="<?php echo $alipay['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">交易安全校验码(key)</td>
<td><input type="text" size="60" name="pay[alipay][keycode]" value="<?php echo $alipay['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[alipay][notify]" value="<?php echo $alipay['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/alipay/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[alipay][percent]" value="<?php echo $alipay['percent'];?>"/> %</td>
</tr>
</tbody>
<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.alipay.com" target="_blank"><strong>支付宝手机支付 Alipay</strong></a></td>
<td>
<input type="radio" name="pay[aliwap][enable]" value="1"  <?php if($aliwap['enable']) echo 'checked';?> onclick="Dd('aliwap').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[aliwap][enable]" value="0"  <?php if(!$aliwap['enable']) echo 'checked';?> onclick="Dd('aliwap').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://b.alipay.com/signing/productDetail.htm?productId=I1011000290000001001" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $aliwap['enable'] ? '' : 'none';?>" id="aliwap">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[aliwap][name]" value="<?php echo $aliwap['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[aliwap][order]" value="<?php echo $aliwap['order'];?>"/></td>
</tr>
<tr>
<td class="tl">合作者身份(partnerID)</td>
<td><input type="text" size="60" name="pay[aliwap][partnerid]" value="<?php echo $aliwap['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">交易安全校验码(key)</td>
<td><input type="text" size="60" name="pay[aliwap][keycode]" value="<?php echo $aliwap['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[aliwap][notify]" value="<?php echo $aliwap['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/aliwap/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[aliwap][percent]" value="<?php echo $aliwap['percent'];?>"/> %</td>
</tr>
</tbody>

<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=https://pay.weixin.qq.com/" target="_blank"><strong>微信支付 Weixin</strong></a></td>
<td>
<input type="radio" name="pay[weixin][enable]" value="1"  <?php if($weixin['enable']) echo 'checked';?> onclick="Dd('weixin').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[weixin][enable]" value="0"  <?php if(!$weixin['enable']) echo 'checked';?> onclick="Dd('weixin').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=http://kf.qq.com/faq/120911VrYVrA150906yUZze6.html" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $weixin['enable'] ? '' : 'none';?>" id="weixin">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[weixin][name]" value="<?php echo $weixin['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[weixin][order]" value="<?php echo $weixin['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="60" name="pay[weixin][partnerid]" value="<?php echo $weixin['partnerid'];?>"/><?php tips('详见开户邮件');?></td>
</tr>
<tr>
<td class="tl">公众号APPID</td>
<td><input type="text" size="60" name="pay[weixin][appid]" value="<?php echo $weixin['appid'];?>"/><?php tips('详见开户邮件');?></td>
</tr>
<tr>
<td class="tl">交易密钥</td>
<td><input type="text" size="60" name="pay[weixin][keycode]" value="<?php echo $weixin['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://pay.weixin.qq.com/index.php/account/api_cert" target="_blank" class="t">[密钥设置]</a></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[weixin][notify]" value="<?php echo $weixin['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/weixin/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[weixin][percent]" value="<?php echo $weixin['percent'];?>"/> %</td>
</tr>
</tbody>
<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.tenpay.com" target="_blank"><strong>财付通 TenPay</strong></a></td>
<td>
<input type="radio" name="pay[tenpay][enable]" value="1"  <?php if($tenpay['enable']) echo 'checked';?> onclick="Dd('tenpay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[tenpay][enable]" value="0"  <?php if(!$tenpay['enable']) echo 'checked';?> onclick="Dd('tenpay').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=http://mch.tenpay.com/market/opentrans_immediately.shtml" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $tenpay['enable'] ? '' : 'none';?>" id="tenpay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[tenpay][name]" value="<?php echo $tenpay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[tenpay][order]" value="<?php echo $tenpay['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="60" name="pay[tenpay][partnerid]" value="<?php echo $tenpay['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">支付密钥</td>
<td><input type="text" size="60" name="pay[tenpay][keycode]" value="<?php echo $tenpay['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[tenpay][notify]" value="<?php echo $tenpay['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/tenpay/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[tenpay][percent]" value="<?php echo $tenpay['percent'];?>"/> %</td>
</tr>
</tbody>
<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=http://cn.unionpay.com/" target="_blank"><strong>中国银联 UnionPay</strong></a></td>
<td>
<input type="radio" name="pay[upay][enable]" value="1"  <?php if($upay['enable']) echo 'checked';?> onclick="Dd('upay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[upay][enable]" value="0"  <?php if(!$upay['enable']) echo 'checked';?> onclick="Dd('upay').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://open.unionpay.com/ajweb/product/detail?id=1" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $upay['enable'] ? '' : 'none';?>" id="upay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[upay][name]" value="<?php echo $upay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[upay][order]" value="<?php echo $upay['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="60" name="pay[upay][partnerid]" value="<?php echo $upay['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">证书文件</td>
<td><input type="text" size="60" name="pay[upay][cert]" value="<?php echo $upay['cert'];?>"/> <?php tips('请将.pfx证书文件上传至 api/pay/upay/，并在这里填写文件名，例如zhengshu.pfx');?></td>
</tr>
<tr>
<td class="tl">证书密码</td>
<td><input type="text" size="60" name="pay[upay][keycode]" value="<?php echo $upay['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[upay][notify]" value="<?php echo $upay['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/upay/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[upay][percent]" value="<?php echo $upay['percent'];?>"/> %</td>
</tr>
</tbody>
<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.chinabank.com.cn" target="_blank"><strong>网银在线 ChinaBank</strong></a></td>
<td>
<input type="radio" name="pay[chinabank][enable]" value="1"  <?php if($chinabank['enable']) echo 'checked';?> onclick="Dd('chinabank').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[chinabank][enable]" value="0"  <?php if(!$chinabank['enable']) echo 'checked';?> onclick="Dd('chinabank').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=http://www.chinabank.com.cn/product/payment_gateway.jsp" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $chinabank['enable'] ? '' : 'none';?>" id="chinabank">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[chinabank][name]" value="<?php echo $chinabank['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[chinabank][order]" value="<?php echo $chinabank['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="60" name="pay[chinabank][partnerid]" value="<?php echo $chinabank['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">支付密钥</td>
<td><input type="text" size="60" name="pay[chinabank][keycode]" value="<?php echo $chinabank['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[chinabank][notify]" value="<?php echo $chinabank['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/chinabank/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[chinabank][percent]" value="<?php echo $chinabank['percent'];?>"/> %</td>
</tr>
</tbody>

<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.yeepay.com" target="_blank"><strong>易宝支付 YeePay</strong></a></td>
<td>
<input type="radio" name="pay[yeepay][enable]" value="1"  <?php if($yeepay['enable']) echo 'checked';?> onclick="Dd('yeepay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[yeepay][enable]" value="0"  <?php if(!$yeepay['enable']) echo 'checked';?> onclick="Dd('yeepay').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=http://www.yeepay.com/productCenter/internetBankingPayment" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $yeepay['enable'] ? '' : 'none';?>" id="yeepay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[yeepay][name]" value="<?php echo $yeepay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[yeepay][order]" value="<?php echo $yeepay['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="60" name="pay[yeepay][partnerid]" value="<?php echo $yeepay['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">商户密钥</td>
<td><input type="text" size="60" name="pay[yeepay][keycode]" value="<?php echo $yeepay['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[yeepay][percent]" value="<?php echo $yeepay['percent'];?>"/> %</td>
</tr>
</tbody>

<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.99bill.com" target="_blank"><strong>快钱支付 99bill</strong></a></td>
<td>
<input type="radio" name="pay[kq99bill][enable]" value="1"  <?php if($kq99bill['enable']) echo 'checked';?> onclick="Dd('kq99bill').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[kq99bill][enable]" value="0"  <?php if(!$kq99bill['enable']) echo 'checked';?> onclick="Dd('kq99bill').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=https://www.99bill.com/z/pay_net_bank.html" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $kq99bill['enable'] ? '' : 'none';?>" id="kq99bill">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[kq99bill][name]" value="<?php echo $kq99bill['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[kq99bill][order]" value="<?php echo $kq99bill['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="60" name="pay[kq99bill][partnerid]" value="<?php echo $kq99bill['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">证书文件</td>
<td><input type="text" size="60" name="pay[kq99bill][cert]" value="<?php echo $kq99bill['cert'];?>"/> <?php tips('请将证书文件，上传至 api/pay/kq99bill/，证书文件名类似99bill[1].cert.rsa.20140803.cer，pcarduser.pem文件也上传至此目录');?></td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="text" size="30" name="pay[kq99bill][notify]" value="<?php echo $kq99bill['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/kq99bill/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[kq99bill][percent]" value="<?php echo $kq99bill['percent'];?>"/> %</td>
</tr>
</tbody>

<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.chinapay.com" target="_blank"><strong>银联在线 ChinaPay</strong></a></td>
<td>
<input type="radio" name="pay[chinapay][enable]" value="1"  <?php if($chinapay['enable']) echo 'checked';?> onclick="Dd('chinapay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[chinapay][enable]" value="0"  <?php if(!$chinapay['enable']) echo 'checked';?> onclick="Dd('chinapay').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=http://www.chinapay.com/web2016/concern/index.jsp" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $chinapay['enable'] ? '' : 'none';?>" id="chinapay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[chinapay][name]" value="<?php echo $chinapay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[chinapay][order]" value="<?php echo $chinapay['order'];?>"/></td>
</tr>
<tr>
<td class="tl">私钥文件</td>
<td><input type="text" size="60" name="pay[chinapay][partnerid]" value="<?php echo $chinapay['partnerid'];?>"/> <?php tips('银联提供的Mer开头的.key文件名，例如MerPrK_808080808080808_20101111222333.key，请将银联提供的两个key文件上传至api/pay/chinapay/目录，另一个key文件名为PgPubk.key<br/>本接口需要 mcrypt 和 bcmath 两个PHP扩展库的支持，请先确认您安装并启用了这两个库');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[chinapay][percent]" value="<?php echo $chinapay['percent'];?>"/> %</td>
</tr>
</tbody>

<tr>
<td class="tl"><a href="<?php echo DT_PATH;?>api/redirect.php?url=www.paypal.com" target="_blank"><strong>贝&nbsp;&nbsp;&nbsp;宝 PayPal</strong></a></td>
<td>
<input type="radio" name="pay[paypal][enable]" value="1"  <?php if($paypal['enable']) echo 'checked';?> onclick="Dd('paypal').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[paypal][enable]" value="0"  <?php if(!$paypal['enable']) echo 'checked';?> onclick="Dd('paypal').style.display='none';"/> 禁用&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo DT_PATH;?>api/redirect.php?url=www.paypal.com" target="_blank" class="t">[帐号申请]</a>
</td>
</tr>
<tbody style="display:<?php echo $paypal['enable'] ? '' : 'none';?>" id="paypal">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[paypal][name]" value="<?php echo $paypal['name'];?>"/></td>
</tr>
<tr>
<td class="tl">显示顺序</td>
<td><input type="text" size="2" name="pay[paypal][order]" value="<?php echo $paypal['order'];?>"/></td>
</tr>
<tr>
<td class="tl">商户帐号</td>
<td><input type="text" size="30" name="pay[paypal][partnerid]" value="<?php echo $paypal['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">IPN 通知文件名</td>
<td><input type="text" size="30" name="pay[paypal][notify]" value="<?php echo $paypal['notify'];?>"/> <?php tips('默认为notify.php 保存于 api/pay/paypal/notify.php<br/>建议你修改此文件名，然后在此填写新文件名');?></td>
</tr>
<tr>
<td class="tl">PDT Token</td>
<td><input type="text" size="60" name="pay[paypal][keycode]" value="<?php echo $paypal['keycode'];?>" onfocus="if(this.value.indexOf('**')!=-1)this.value='';"/> <?php tips('系统默认使用IPN方式通知，如果在Paypal开启了PDT，请在此填写对应的Token，否则请留空');?></td>
</tr>
<tr>
<td class="tl">支付币种</td>
<td><input type="text" size="3" name="pay[paypal][currency]" value="<?php echo $paypal['currency'];?>"/> 值可以为 "CNY"、"USD"、"EUR"、"TWD"、"JPY"等</td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[paypal][percent]" value="<?php echo $paypal['percent'];?>"/> %</td>
</tr>
</tbody>
</table>