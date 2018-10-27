<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="make" value="1"/>
<input type="hidden" name="first" value="1"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_red">*</span> 数据表</td>
<td><input type="text" size="50" name="tb" id="tb" value="<?php echo $DT_PRE;?>member"/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> SQL条件语句</td>
<td class="f_gray"><input type="text" size="60" name="sql" id="sql" value="groupid>4"/>
<select onchange="mk(this.value);">
<option value="member|groupid>4|email">常用SQL语句</option>
<option value="member|logintime<<?php echo $DT_TIME;?>-3600*24*30|email">30天未登录会员</option>
<option value="member|regtime<<?php echo $DT_TIME;?>-3600*24*365|email">注册时间超过1年</option>
<option value="member|message>10|email">未读站内信超过10封</option>
<option value="member|money>1000|email">帐户可用<?php echo $DT['money_name'];?>多余1000<?php echo $DT['money_unit'];?></option>
<option value="member m,company c|m.userid=c.userid and c.vip>6|m.email"><?php echo VIP;?>指数大于6的企业</option>
<option value="member m,company c|m.userid=c.userid and c.totime><?php echo $DT_TIME;?>|m.email"><?php echo VIP;?>服务过期的企业</option>
<option value="member m,company c|m.userid=c.userid and c.totime><?php echo $DT_TIME;?>-3600*24*30|m.email"><?php echo VIP;?>服务30天内过期的企业</option>
<option value="member m,company c|m.userid=c.userid and c.validated=1|m.email">资料通过认证的企业</option>
<option value="member m,company c|m.userid=c.userid and c.domain<>''|m.email">绑定了顶级域名的的企业</option>
<?php foreach($GROUP as $k=>$v) { 
	if($v['groupid'] != 3) { 
?>
<option value="member|groupid=<?php echo $v['groupid'];?>|email"><?php echo $v['groupname'];?></option>
<?php 
	}
} 
?>
</select>
<br/>如果仅提取已通过验证的Email地址，可以加and vemail>0 例如 groupid=6 and vemail>0
<script type="text/javascript">
function mk(v) {
	var pre = '<?php echo $DT_PRE;?>';
	var arr = v.split("|");
	if(arr[0]) Dd('tb').value = pre+arr[0].replace(/,/, ','+pre);
	if(arr[1]) Dd('sql').value = arr[1];
	if(arr[2]) Dd('field').value = arr[2];
}
</script>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 每轮提取数目</td>
<td><input type="text" size="5" name="num" value="1000"/></td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 保存文件名</td>
<td class="f_gray"><input type="text" size="20" id="title" name="title"/><br/>可填中文(如果服务器支持)，不填则系统自动生成</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value="获 取" class="btn-g"/></div>
</form>
<script type="text/javascript">Menuon(2);</script>
<?php include tpl('footer');?>