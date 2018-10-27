<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
if(!$id) show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="mallid" value="<?php echo $mallid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<div class="tt">商品信息</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">订单单号</td>
<td><?php echo $td['itemid'];?> <?php if($DT['trade']) { ?>(<?php echo $DT['trade_nm'];?>订单单号:<a href="https://lab.alipay.com/consume/queryTradeDetail.htm?tradeNo=<?php echo $td['trade_no'];?>" target="_blank" class="t"><?php echo $td['trade_no'];?></a>)<?php } ?></td>
</tr>
<tr>
<td class="tl">商品名称</td>
<td class="tr"><a href="<?php echo $td['linkurl'];?>" target="_blank" class="t"><?php echo $td['title'];?></a></td>
</tr>
<tr>
<td class="tl">商品图片</td>
<td class="tr"><a href="<?php echo $td['linkurl'];?>" target="_blank"><img src="<?php if($td['thumb']) { ?><?php echo $td['thumb'];?><?php } else { ?><?php echo DT_SKIN;?>image/nopic60.gif<?php } ?>" width="60" height="60"/></a></td>
</tr>
<?php if($td['par']) { ?>
<tr>
<td class="tl">规格参数</td>
<td><?php echo $td['par'];?></td>
</tr>
<?php } ?>
<tr>
<td class="tl">卖家</td>
<td><?php if($DT['im_web']) { ?><?php echo im_web($td['seller']);?>&nbsp;<?php } ?><a href="javascript:_user('<?php echo $td['seller'];?>');" class="t"><?php echo $td['seller'];?></a></td>
</tr>
<tr>
<td class="tl">买家</td>
<td><?php if($DT['im_web']) { ?><?php echo im_web($td['buyer']);?>&nbsp;<?php } ?><a href="javascript:_user('<?php echo $td['buyer'];?>');" class="t"><?php echo $td['buyer'];?></a></td>
</tr>
</table>
<div class="tt">快递信息</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">邮编</td>
<td><?php echo $td['buyer_postcode'];?></td>
</tr>
<tr>
<td class="tl">地址</td>
<td><?php echo $td['buyer_address'];?></td>
</tr>
<tr>
<td class="tl">姓名</td>
<td><?php echo $td['buyer_name'];?></td>
</tr>
<tr>
<td class="tl">手机</td>
<td><?php echo $td['buyer_mobile'];?></td>
</tr>
<?php if($td['send_time'] > 0) { ?>
<tr>
<td class="tl">快递类型</td>
<td><a href="<?php echo DT_PATH;?>api/express/home.php?e=<?php echo urlencode($td['send_type']);?>&n=<?php echo $td['send_no'];?>" target="_blank"><?php echo $td['send_type'];?></a></td>
</tr>
<tr>
<td class="tl">快递单号</td>
<td><a href="<?php echo DT_PATH;?>api/express.php?e=<?php echo urlencode($td['send_type']);?>&n=<?php echo $td['send_no'];?>" target="_blank"><?php echo $td['send_no'];?></a></td>
</tr>
<?php if($td['send_type'] && $td['send_no']) { ?>
<tr>
<td class="tl">追踪结果</td>
<td style="line-height:200%;"><div id="express"><img src="<?php echo DT_SKIN;?>image/loading.gif" align="absmiddle"/> 正在查询...</div>
<script type="text/javascript">
$(function(){
	$('#express').load(AJPath+'?action=express&moduleid=2&auth=<?php echo encrypt('mall|'.$td['send_type'].'|'.$td['send_no'].'|'.$td['send_status'].'|'.$td['itemid'], DT_KEY.'EXPRESS');?>');
});
</script>
</td>
</tr>
<?php } ?>
<?php } ?>
<tr>
<td class="tl">买家留言</td>
<td><?php echo $td['note'];?></td>
</tr>
</table>
<div class="tt">价格信息</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">单价</td>
<td><?php echo $DT['money_sign'];?><?php echo $td['price'];?></td>
</tr>
<tr>
<td class="tl">数量</td>
<td><?php echo $td['number'];?></td>
</tr>
<?php if($td['fee']>0) { ?>
<tr>
<td class="tl"><?php echo $td['fee_name'];?></td>
<td><?php echo $DT['money_sign'];?><?php echo $td['fee'];?></td>
</tr>
<?php } ?>
<tr>
<td class="tl">总额</td>
<td class="f_red"><?php echo $DT['money_sign'];?><?php echo $td['money'];?></td>
</tr>
</table>
<div class="tt">订单状态</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">下单时间</td>
<td><?php echo $td['adddate'];?></td>
</tr>
<tr>
<td class="tl">更新时间</td>
<td><?php echo $td['updatedate'];?></td>
</tr>
<?php if($td['send_time']>0) { ?>
<tr>
<td class="tl">发货时间</td>
<td><?php echo $td['send_time'];?></td>
</tr>
<?php } ?>
<tr>
<td class="tl">交易状态</td>
<td><?php echo $_status[$td['status']];?></td>
</tr>
<?php if($td['buyer_reason']) { ?>
<tr>
<td class="tl">退款原因</td>
<td><?php echo $td['buyer_reason'];?></td>
</tr>
<?php } ?>
<tr>
<td class="tl">受理结果</td>
<td id="status">
<input type="radio" name="status" value="6"/> 将交易金额退还给买家<br/>
<input type="radio" name="status" value="4"/> 将交易金额支付给卖家 <span id="dstatus" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl">操作理由</td>
<td>
<textarea name="content" id="content" class="dsn"></textarea>
<?php echo deditor($moduleid, 'content', 'Simple', '100%', 200);?>
<br/>请在和买卖双方沟通后谨慎填写，一经提交将不可更改 <span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl">通知双方</td>
<td>
<input type="checkbox" name="msg" id="msg" value="1" onclick="Dn();" checked/><label for="msg"> 站内通知</label>
<input type="checkbox" name="eml" id="eml" value="1" onclick="Dn();"/><label for="eml"> 邮件通知</label>
<input type="checkbox" name="sms" id="sms" value="1" onclick="Dn();"/><label for="sms"> 短信通知</label>
<input type="checkbox" name="wec" id="wec" value="1" onclick="Dn();"/><label for="wec"> 微信通知</label>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 返 回 " class="btn" onclick="history.back(-1);"/></div>
</form>
<script type="text/javascript">
function check() {
	var l;
	l = checked_count('status');
	if(l == 0) {
		Dmsg('请选择受理结果', 'status');
		return false;
	}
	l = FCKLen();
	if(l < 5) {
		Dmsg('操作理由不能少于5个字，当前已输入'+l+'个字', 'content');
		return false;
	}
	return confirm('确定要进行此操作吗？提交后将不可恢复');
}
</script>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>