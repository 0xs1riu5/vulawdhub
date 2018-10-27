<div class="btns">
<textarea style="width:300px;height:16px;" name="reason" id="reason" onfocus="if(this.value=='操作原因')this.value='';"/>操作原因</textarea>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="msg" id="msg" value="1" onclick="Dn();"/><label for="msg"> 站内通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="eml" id="eml" value="1" onclick="Dn();"/><label for="eml"> 邮件通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="sms" id="sms" value="1" onclick="Dn();"/><label for="sms"> 短信通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="wec" id="wec" value="1" onclick="Dn();"/><label for="wec"> 微信通知</label>&nbsp;&nbsp;&nbsp;&nbsp;
<?php tips('仅发送点击下方通过审核、拒绝、回收站、彻底删除、上架、下架商品按钮的操作通知，如果填写了操作原因，默认会发送站内通知');?>
</div>