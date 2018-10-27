<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="sbox">
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<?php echo $fields_select;?>&nbsp;
<input type="text" size="20" name="kw" value="<?php echo $kw;?>" placeholder="请输入关键词" title="请输入关键词"/>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<select name="admin">
<option value="-1" <?php echo $admin == -1 ? 'selected' : '';?>>后台</option>
<option value="1" <?php echo $admin == 1 ? 'selected' : '';?>>是</option>
<option value="0" <?php echo $admin == 0 ? 'selected' : '';?>>否</option>
</select>&nbsp;
会员名：<input type="text" name="username" value="<?php echo $username;?>" size="10"/>&nbsp;
<input type="text" name="psize" value="<?php echo $pagesize;?>" size="2" class="t_c" title="条/页"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="Go('?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>');"/>
</form>
</div>
<table cellspacing="0" class="tb ls">
<tr>
<th>日志ID</th>
<th>会员名</th>
<th>密码[已加密]</th>
<th>时间</th>
<th>后台</th>
<th>结果</th>
<th>IP</th>
<th>地区</th>
<th>客户端</th>
</tr>
<?php foreach($logs as $k=>$v) {?>
<tr align="center">
<td class="px12"><?php echo $v['logid'];?></td>
<td class="px12"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&username=<?php echo $v['username'];?>"><?php echo $v['username'];?></a></td>
<td class="px12"><?php echo $v['password'];?></td>
<td class="px12"><?php echo $v['logintime'];?></td>
<td><?php echo $v['admin'] ? '<span class="f_blue">是</span>' : '否';?></td>
<td><?php echo $v['message'];?></td>
<td class="px12"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&ip=<?php echo $v['loginip'];?>"><?php echo $v['loginip'];?></a></td>
<td><?php echo ip2area($v['loginip']);?></td>
<td title="<?php echo $v['agent'];?>"><input type="text" value="<?php echo $v['agent'];?>" size="20" onmouseover="this.select();"/></td>
</tr>
<?php }?>
</table>
<form action="?">
<table cellspacing="0" class="tb">
<tr>
<td>&nbsp;
密码(明文)：<input type="text" size="15" name="password" id="password"/> &nbsp;日志ID：<input type="text" size="5" name="logid" id="logid"/>
&nbsp;<input type="button" value="对 比" class="btn-g" onclick="cp();"/>&nbsp;
<span id="cpr" class="f_red"></span>
</td>
</tr>
<tr>
<td>
&nbsp;&nbsp;工具说明：1、用于分析帐号登录异常情况。2、截获暴力破解IP。3、验证用户帐号申诉提供的历史密码是否匹配
</td>
</tr>
</table>
</form>
<?php echo $pages ? '<div class="pages">'.$pages.'</div>' : '';?>
<script type="text/javascript">
function cp() {
	Dd('cpr').innerHTML = '';
	if(Dd('password').value == '' || Dd('logid').value == '') {
		alert('请填写加密密码和需要对比的日志ID');
		return;
	}
	$.get('?file=<?php echo $file;?>&moduleid=<?php echo $moduleid;?>&action=cp&password='+Dd('password').value+'&logid='+Dd('logid').value, function(data) {
		if(data) Dd('cpr').innerHTML = data;
	});
}
Menuon(0);
</script>
<?php include tpl('footer');?>