<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellspacing="0" class="tb ls">
<tr>
<th width="200">菜单名称</th>
<th>地址/事件</th>
</tr>
<?php foreach($menu as $k=>$v) { ?>
<?php foreach($v as $kk=>$vv) { ?>
<tr>
<td><?php echo $kk == 0 ? '' : '<img src="admin/image/tree.gif" align="absmiddle"/>';?><input name="post[<?php echo $k;?>][<?php echo $kk;?>][name]" type="text" style="width:<?php echo $kk == 0 ? 120 : 100;?>px;" value="<?php echo $vv['name'];?>" maxlength="<?php echo $kk == 0 ? 4 : 7;?>"/></td>
<td><input name="post[<?php echo $k;?>][<?php echo $kk;?>][key]" type="text" size="50" value="<?php echo $vv['key'];?>"/></td>
</tr>
<?php } ?>
<?php } ?>
</table>
<div class="btns">
<input type="submit" name="submit" value="提 交" class="btn-g"/>&nbsp;&nbsp; 提示：如果对菜单定义不熟悉，请勿修改 菜单说明<a href="http://mp.weixin.qq.com/wiki/13/43de8269be54a0a6f64413e4dfa94f39.html" target="_blank" class="t">请点这里</a>，菜单调试<a href="https://mp.weixin.qq.com/debug/cgi-bin/apiinfo?t=index&type=%E8%87%AA%E5%AE%9A%E4%B9%89%E8%8F%9C%E5%8D%95&form=%E8%87%AA%E5%AE%9A%E4%B9%89%E8%8F%9C%E5%8D%95%E5%88%9B%E5%BB%BA%E6%8E%A5%E5%8F%A3%20/menu/create&access_token=<?php echo $access_token;?>" target="_blank" class="t">请点这里</a>
</div>
</form>
<br/>
<script type="text/javascript">Menuon(4);</script>
<?php include tpl('footer');?>