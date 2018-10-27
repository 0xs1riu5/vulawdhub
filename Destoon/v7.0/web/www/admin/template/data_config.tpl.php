<?php
defined('DT_ADMIN') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="type" value="<?php echo $type;?>"/>
<input type="hidden" name="mid" value="<?php echo $mid;?>"/>
<input type="hidden" name="tb" value="<?php echo $tb;?>"/>
<input type="hidden" name="data[type]" value="<?php echo $type;?>"/>
<input type="hidden" name="data[mid]" value="<?php echo $mid;?>"/>
<input type="hidden" name="data[tb]" value="<?php echo $tb;?>"/>
<input type="hidden" name="data[lasttime]" value="<?php echo $lasttime;?>"/>
<input type="hidden" name="data[lastid]" value="<?php echo $lastid;?>"/>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> 导入类型</td>
<td>
<?php
	if($type == 0) {
		echo $MODULE[$mid]['name'];
	} else if($type == 1) {
		echo '会员';
	} else if($type == 2) {
		echo $tb;
	}
?>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 配置名称</td>
<td class="f_gray">
<input type="text" name="name" size="30" value="<?php echo $name;?>" id="name"/><br/>
- 限数字、字母、下划线、中划线、点 系统将创建配置文件至 file/data/ 目录
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 配置说明</td>
<td class="f_gray">
<input type="text" name="data[title]" size="60" value="<?php echo $title;?>"/><br/>
- 配置的一些说明、备注文字 支持中文
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 数据源</td>
<td>
<input type="radio" name="data[database]" value="mysql" id="d_0" <?php echo $database == 'mysql' ? 'checked' : '';?>/><label for="d_0"/> MySQL</label>&nbsp;&nbsp;&nbsp;
<input type="radio" name="data[database]" value="mssql" id="d_1" <?php echo $database == 'mssql' ? 'checked' : '';?>/><label for="d_1"/> MSSQL2000</label>&nbsp;&nbsp;&nbsp;
<input type="radio" name="data[database]" value="access" id="d_2" <?php echo $database == 'access' ? 'checked' : '';?>/><label for="d_2"/> Access</label>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 主机地址</td>
<td class="f_gray">
<input type="text" name="data[db_host]" size="40" value="<?php echo $db_host;?>"/><br/>
- Access文件请传至网站目录 例如 file/data/access.mdb 然后 填写 file/data/access.mdb
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 户名</td>
<td><input type="text" name="data[db_user]" size="20" value="<?php echo $db_user;?>"/> </td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 密码</td>
<td><input type="text" name="data[db_pass]" size="20" value="<?php echo $db_pass;?>"/> </td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 数据库</td>
<td><input type="text" name="data[db_name]" size="20" value="<?php echo $db_name;?>"/></td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 数据表</td>
<td class="f_gray">
<input type="text" name="data[db_table]" size="60" value="<?php echo $db_table;?>" id="db_table"/><br/>
- 如果是单个表，填写表全名；如果是多个表，请填写例如 table_a a,table_b b<br/>
- MySQL数据库 如果导入的数据和当前系统在同一服务器的不同数据库，则填写 数据库名.表名 例如 name.table<br/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_red">*</span> 主键字段</td>
<td class="f_gray">
<input type="text" name="data[db_key]" size="20" value="<?php echo $db_key;?>" id="db_key"/><br/>
- 表的主键，如果没有，请先创建一个主键<br/>
- 如果多表联合请加联合表简称 例如 a.id<br/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 源数据字符集</td>
<td class="f_gray">
<input type="text" name="data[db_charset]" size="10" value="<?php echo $db_charset;?>" id="db_charset"/><br/>
- 如果字符集与当前系统一致，则无需填写，一般为UTF-8、GBK等<br/>
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 导入条件</td>
<td class="f_gray">
<input type="text" name="data[db_condition]" size="80" value="<?php echo $db_condition;?>"/><br/>
- 支持SQL语句，必须以AND开头，例如 AND id>1000、AND a.id=b.id
</td>
</tr>
<tr>
<td class="tl"><span class="f_hid">*</span> 上次导入ID</td>
<td class="f_gray">
<input type="text" name="data[lastid]" size="5" value="<?php echo $lastid ? $lastid : 0;?>"/><br/>
- 系统将记录上次导入ID，以免下次导入时重复导入
</td>
</tr>
</table>
<div class="tt">字段对应关系</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl" align="center"><span class="f_hid">*</span> 对应说明</td>
<td colspan="3" class="f_gray">
- PHP不支持MSSQL和Access的 ntext,nvarchar..类型，请在导入前修改为text,varchar..类型<br/>
- 值处理填写数字或者字符串代表字段的默认值，如果需要函数处理，请将参数设置为* <br/>
- 例 strtotime(*) 表示将2010-01-01日期格式转换为Unix时间戳<br/>
- 例 date('Y-m-d', *) 表示将Unix时间戳转换为类似2010-01-01日期格式<br/>
- 值处理支持变量或变量组合或变量+函数组合，源数据保存在 $F 数组，对应转换结果保存在 $T 数组<br/>
- 例 $F['a'].$F['b'] 表示连接两个源数据字段a和b<br/>
- 例 date('Y-m-d', $F['a']) 将源字段a转换为日期格式<br/>
- 如果导入会员数据，会员名或Email重复的数据将被自动丢弃<br/>
</td>
</tr>
<tr align="center">
<th>字段</th>
<th>名称(参考)</th>
<th>源字段</th>
<th>值处理</th>
</tr>
<?php foreach($fields as $k=>$f) { ?>
<tr align="center">
<td class="tl"><?php echo $k;?></td>
<td><?php echo isset($names[$k]) ? $names[$k] : '';?></td>
<td><input type="text" size="15" name="data[fields][<?php echo $k;?>][name]" value="<?php echo $f['name'];?>"/></td>
<td><input type="text" size="30" name="data[fields][<?php echo $k;?>][value]" value="<?php echo $f['value'];?>"/></td>
</tr>
<?php } ?>
<?php foreach($fields_d as $k=>$f) { ?>
<tr align="center">
<td class="tl"><?php echo $k;?></td>
<td><?php echo isset($names[$k]) ? $names[$k] : '';?></td>
<td><input type="text" size="15" name="data[fields][<?php echo $k;?>][name]" value="<?php echo $f['name'];?>"/></td>
<td><input type="text" size="30" name="data[fields][<?php echo $k;?>][value]" value="<?php echo $f['value'];?>"/></td>
</tr>
<?php } ?>
</table>
<div class="tt">PHP处理代码</div>
<table cellspacing="0" class="tb">
<tr>
<td class="tl"><span class="f_hid">*</span> PHP代码</td>
<td class="f_gray">
- 如果需要在导出过程中执行PHP代码，请FTP保存PHP代码至 file/data/配置名称.inc.php<br/>
- 代码将在源数据循环读出时执行，源数据保存在 $F 数组，对应转换结果保存在 $T 数组<br/>
</td>
</tr>
<tr>
<td class="tl"> </td>
<td height="30"><input type="submit" name="submit" value="保 存" class="btn-g"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="取 消" class="btn" onclick="Go('?file=<?php echo $file;?>');"/></td>
</tr>
</table>
</form>
<br/>
<script type="text/javascript">
function check() {
	if(Dd('name').value.length < 1) {
		alert('请填写配置名称');
		Dd('name').focus();
		return false;
	}
	if(Dd('db_table').value.length < 1) {
		alert('请填写数据表');
		Dd('db_table').focus();
		return false;
	}
	if(Dd('db_key').value.length < 1) {
		alert('请填写主键字段');
		Dd('db_key').focus();
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(6);</script>
<?php include tpl('footer');?>