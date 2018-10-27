<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
if(!$id) show_menu($menus);
?>
<?php include template('goods', 'chip');?>
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
<td><a href="<?php echo DT_PATH;?>api/express.php?e=<?php echo urlencode($td['send_type']);?>&n=<?php echo $td['send_no'];?>" target="_blank"><?php echo $td['send_no'];?></a><?php if($td['send_type'] && $td['send_no']) { ?> &nbsp;<a href="###" class="t" onclick="Ds('express_t');$('#express').load(AJPath+'?action=express&moduleid=2&auth=<?php echo $auth;?>');">[快递追踪]</a><?php } ?></td>
</tr>
<tr id="express_t" style="display:none;">
<td class="tl">追踪结果</td>
<td style="line-height:200%;"><div id="express"><img src="<?php echo DT_SKIN;?>image/loading.gif" align="absmiddle"/> 正在查询...</div>
</td>
</tr>
<?php } ?>
</table>

<div class="tt">订单详情</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">卖家</td>
<td><?php if($DT['im_web']) { ?><?php echo im_web($td['seller']);?>&nbsp;<?php } ?><a href="javascript:_user('<?php echo $td['seller'];?>');" class="t"><?php echo $td['seller'];?></a></td>
</tr>
<tr>
<td class="tl">买家</td>
<td><?php if($DT['im_web']) { ?><?php echo im_web($td['buyer']);?>&nbsp;<?php } ?><a href="javascript:_user('<?php echo $td['buyer'];?>');" class="t"><?php echo $td['buyer'];?></a></td>
</tr>
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
<?php if($td['refund_reason']) { ?>
<tr>
<td class="tl">操作原因</td>
<td><?php echo $td['refund_reason'];?></td>
</tr>
<tr>
<td class="tl">操作人</td>
<td><?php echo $td['editor'];?></td>
</tr>
<tr>
<td class="tl">操作时间</td>
<td><?php echo $td['updatetime'];?></td>
</tr>
<?php } ?>
</table>

<div class="tt">买家评价<a name="comment1"></a></div>
<table cellspacing="0" class="tb">
<?php foreach($lists as $k => $v) { ?>
<tr>
<td class="tl">商品名称</td>
<td class="tr"><a href="<?php echo $v['linkurl'];?>" target="_blank" class="t"><?php echo $v['title'];?></a></td>
</tr>
<?php if($comments[$k]['seller_star']) { ?>
<tr>
<td class="tl">买家评分</td>
<td>
<span class="f_r"><a href="#comment" onclick="Ds('c_edit');" class="t">[修改]</a></span>
<img src="<?php echo DT_PATH;?>file/image/star<?php echo $comments[$k]['seller_star'];?>.gif" width="36" height="12" alt="" align="absmiddle"/> <?php echo $STARS[$comments[$k]['seller_star']];?>
</td>
</tr>
<tr>
<td class="tl">买家评论</td>
<td><?php echo nl2br($comments[$k]['seller_comment']);?></td>
</tr>
<tr>
<td class="tl">评论时间</td>
<td class="px12"><?php echo timetodate($comments[$k]['seller_ctime'], 6);?></td>
</tr>
<?php if($comments[$k]['buyer_reply']) { ?>
<tr>
<td class="tl">卖家解释</td>
<td style="color:#D9251D;"><?php echo nl2br($comments[$k]['buyer_reply']);?></td>
</tr>
<tr>
<td class="tl">解释时间</td>
<td class="px12"><?php echo timetodate($comments[$k]['buyer_rtime'], 6);?></td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td class="tl">买家评论</td>
<td>暂未评论</td>
</tr>
<?php } ?>
<?php } ?>
</table>

<div class="tt">卖家评价<a name="comment2"></a></div>
<table cellspacing="0" class="tb">
<?php foreach($lists as $k => $v) { ?>
<tr>
<td class="tl">商品名称</td>
<td class="tr"><a href="<?php echo $v['linkurl'];?>" target="_blank" class="t"><?php echo $v['title'];?></a></td>
</tr>
<?php if($comments[$k]['buyer_star']) { ?>
<tr>
<td class="tl">卖家评分</td>
<td>
<span class="f_r"><a href="#comment" onclick="Ds('c_edit');" class="t">[修改]</a></span>
<img src="<?php echo DT_PATH;?>file/image/star<?php echo $comments[$k]['buyer_star'];?>.gif" width="36" height="12" alt="" align="absmiddle"/> <?php echo $STARS[$comments[$k]['buyer_star']];?>
</td>
</tr>
<tr>
<td class="tl">卖家评论</td>
<td><?php echo nl2br($comments[$k]['buyer_comment']);?></td>
</tr>
<tr>
<td class="tl">评论时间</td>
<td class="px12"><?php echo timetodate($comments[$k]['buyer_ctime'], 6);?></td>
</tr>
<?php if($comments[$k]['seller_reply']) { ?>
<tr>
<td class="tl">买家解释</td>
<td style="color:#D9251D;"><?php echo nl2br($comments[$k]['seller_reply']);?></td>
</tr>
<tr>
<td class="tl">解释时间</td>
<td class="px12"><?php echo timetodate($comments[$k]['seller_rtime'], 6);?></td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td class="tl">卖家评论</td>
<td>暂未评论</td>
</tr>
<?php } ?>
<?php } ?>
</table>

<div id="c_edit" style="display:none;">
<div class="tt">修改评价<a name="comment"></a></div>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="comment"/>
<input type="hidden" name="mallid" value="<?php echo $mallid;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<table cellspacing="0" class="tb">
<?php foreach($lists as $k => $v) { ?>
<tr>
<td class="tl">买家评分</td>
<td>
<input type="radio" name="post[<?php echo $v['itemid'];?>][seller_star]" value="3"<?php echo $comments[$k]['seller_star'] == 3 ? ' checked' : '';?>/> 好评 
<input type="radio" name="post[<?php echo $v['itemid'];?>][seller_star]" value="2"<?php echo $comments[$k]['seller_star'] == 2 ? ' checked' : '';?>/> 中评 
<input type="radio" name="post[<?php echo $v['itemid'];?>][seller_star]" value="1"<?php echo $comments[$k]['seller_star'] == 1 ? ' checked' : '';?>/> 差评 
<input type="radio" name="post[<?php echo $v['itemid'];?>][seller_star]" value="0"<?php echo $comments[$k]['seller_star'] == 0 ? ' checked' : '';?>/> 待评
</td>
</tr>
<tr>
<td class="tl">买家评论</td>
<td><textarea name="post[<?php echo $v['itemid'];?>][seller_comment]" style="width:300px;height:60px;"><?php echo $comments[$k]['seller_comment'];?></textarea></td>
</tr>
<tr>
<td class="tl">评论时间</td>
<td><input type="text" style="width:150px;" name="post[<?php echo $v['itemid'];?>][seller_ctime]" value="<?php echo $comments[$k]['seller_ctime'] ? timetodate($comments[$k]['seller_ctime'], 6) : '';?>"/></td>
</tr>
<tr>
<td class="tl">卖家解释</td>
<td><textarea name="post[<?php echo $v['itemid'];?>][buyer_reply]" style="width:300px;height:60px;"><?php echo $comments[$k]['buyer_reply'];?></textarea></td>
</tr>
<tr>
<td class="tl">解释时间</td>
<td><input type="text" style="width:150px;" name="post[<?php echo $v['itemid'];?>][buyer_rtime]" value="<?php echo $comments[$k]['buyer_rtime'] ? timetodate($comments[$k]['buyer_rtime'], 6) : '';?>"/></td>
</tr>

<tr>
<td class="tl">卖家评分</td>
<td>
<input type="radio" name="post[<?php echo $v['itemid'];?>][buyer_star]" value="3"<?php echo $comments[$k]['buyer_star'] == 3 ? ' checked' : '';?>/> 好评 
<input type="radio" name="post[<?php echo $v['itemid'];?>][buyer_star]" value="2"<?php echo $comments[$k]['buyer_star'] == 2 ? ' checked' : '';?>/> 中评 
<input type="radio" name="post[<?php echo $v['itemid'];?>][buyer_star]" value="1"<?php echo $comments[$k]['buyer_star'] == 1 ? ' checked' : '';?>/> 差评 
<input type="radio" name="post[<?php echo $v['itemid'];?>][buyer_star]" value="0"<?php echo $comments[$k]['buyer_star'] == 0 ? ' checked' : '';?>/> 待评
</td>
</tr>
<tr>
<td class="tl">卖家评论</td>
<td><textarea name="post[<?php echo $v['itemid'];?>][buyer_comment]" style="width:300px;height:60px;"><?php echo $comments[$k]['buyer_comment'];?></textarea></td>
</tr>
<tr>
<td class="tl">评论时间</td>
<td><input type="text" style="width:150px;" name="post[<?php echo $v['itemid'];?>][buyer_ctime]" value="<?php echo $comments[$k]['buyer_ctime'] ? timetodate($comments[$k]['buyer_ctime'], 6) : '';?>"/></td>
</tr>
<tr>
<td class="tl">买家解释</td>
<td><textarea name="post[<?php echo $v['itemid'];?>][seller_reply]" style="width:300px;height:60px;"><?php echo $comments[$k]['seller_reply'];?></textarea></td>
</tr>
<tr>
<td class="tl">解释时间</td>
<td><input type="text" style="width:150px;" name="post[<?php echo $v['itemid'];?>][seller_rtime]" value="<?php echo $comments[$k]['seller_rtime'] ? timetodate($comments[$k]['seller_rtime'], 6) : '';?>"/></td>
</tr>
<?php } ?>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 修 改 " class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" 取 消 " class="btn" onclick="$('#c_edit').hide();"/></div>
</form>
</div>
<script type="text/javascript">
function check() {
	return confirm('确定要修改该订单的评论吗？');
}
</script>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>