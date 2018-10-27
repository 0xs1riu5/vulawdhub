<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
if(!$id) show_menu($menus);
?>
<div class="tt">报名详情</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl">展会名称</td>
<td class="tr"><a href="<?php echo $item['linkurl'];?>" target="_blank" class="t f_b"><?php echo $item['title'];?></a></td>
</tr>
<tr>
<td class="tl">会员</td>
<td><a href="javascript:_user('<?php echo $item['username'];?>');" class="t"><?php echo $item['username'];?></a></td>
</tr>
<tr>
<td class="tl">公司</td>
<td><?php echo $item['company'];?></td>
</tr>
<tr>
<td class="tl">人数</td>
<td><?php echo $item['amount'];?></td>
</tr>
<tr>
<td class="tl">姓名</td>
<td><?php echo $item['truename'];?></td>
</tr>
<tr>
<td class="tl">手机</td>
<td><?php echo $item['mobile'];?></td>
</tr>
<tr>
<td class="tl">地址</td>
<td><?php echo area_pos($item['areaid'], '');?><?php echo $item['address'];?></td>
</tr>
<tr>
<td class="tl">邮编</td>
<td><?php echo $item['postcode'];?></td>
</tr>
<tr>
<td class="tl">邮件</td>
<td><?php echo $item['email'];?></td>
</tr>
<tr>
<td class="tl">QQ</td>
<td><?php echo $item['qq'];?></td>
</tr>
<tr>
<td class="tl">微信</td>
<td><?php echo $item['wx'];?></td>
</tr>
<tr>
<td class="tl">报名时间</td>
<td><?php echo $item['addtime'];?></td>
</tr>
<tr>
<td class="tl">备注说明</td>
<td><?php echo nl2br($item['content']);?></td>
</tr>
<tr>
<td class="tl"></td>
<td><input type="button" value="返 回" class="btn-g" onclick="window.history.back(-1);"/></td>
</tr>
</table>
<script type="text/javascript">Menuon(1);</script>
<?php include tpl('footer');?>