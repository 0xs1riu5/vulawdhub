<?php
require_once ("admin.inc.php");
$friendlink_list = $db->getList("select * from cms_message order by id desc");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文章管理</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
<script src="../include/js/jquery.js" type="text/javascript" ></script>
<script type="text/javascript">
function doAction(a,id,v){
	if(a=='validate'){
		$.ajax({
			url:'message.action.php',
			type: 'POST',
			data:'act=validate&id='+id+'&validate='+v,
			success: function(data){
				if(data) alert(data);
				window.location.href = window.location.href;
			}
		});
}
	if(a=='delete'){
		if(confirm('请确认是否删除！')){
			$.ajax({
				url:'message.action.php',
				type: 'POST',
				data:'act=delete&id='+id,
				success: function(data){
					if(data) alert(data);
					window.location.href = window.location.href;
				}
			});
		}
	}
}

function reply(id,reply){
	var str 	= "<hr>回复留言<br>";
	str			+= "<textarea id=\"reply_"+id+"\" style=\"width:300px;height:100px\">"+reply+"</textarea>";
	str			+= "&nbsp;<input type=\"button\" value=\"保存\" onclick=\"savereply("+id+")\">";
	document.getElementById('replyDiv'+id).innerHTML=str;
}

function savereply(id){
	var val = document.getElementById('reply_'+id).value;
	$.ajax({
		url:'message.action.php',
		type: 'POST',
		data:'act=reply&id='+id+"&reply="+val,
		success: function(data){
			if(data) alert(data);
			window.location.href = window.location.href;
		}
	});
}

//全选/取消
function checkAll(o,checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(o.checked){
			oc[i].checked=true;	
		}else{
			oc[i].checked=false;	
		}
	}
	checkDeleteStatus(checkBoxName)
}

//检查有选择的项，如果有删除按钮可操作
function checkDeleteStatus(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			document.getElementById('DeleteCheckboxButton').disabled=false;
			return;
		}
	}
	document.getElementById('DeleteCheckboxButton').disabled=true;
}

//获取所有被选中项的ID组成字符串
function getCheckedIds(checkBoxName){
	var oc = document.getElementsByName(checkBoxName);
	var CheckedIds = "";
	for(var i=0; i<oc.length; i++) {
		if(oc[i].checked){
			if(CheckedIds==''){
				CheckedIds = oc[i].value;	
			}else{
				CheckedIds +=","+oc[i].value;	
			}
			
		}
	}
	return CheckedIds;
}
</script>
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000
}
-->
</style>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top" style="padding:10px;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_head">
        <tr>
          <td height="30">留言管理 
            &nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table_form">
        <?php
	foreach ($friendlink_list as $list){
  ?>
        <tr>
          <td height="26" align="left" style="background-color:#EEEEEE">&nbsp; <font style="color:#009900"><?php echo $list['created_date'];?></font> &nbsp;&nbsp;<font style="color:#0009CC"><?php echo $list['name'];?></font> &nbsp;&nbsp;QQ：<?php echo $list['qq'];?> &nbsp;&nbsp;Email：<?php echo $list['email'];?> &nbsp;&nbsp;IP：<?php echo $list['ip'];?></td>
          <th width="140" align="center" style="background-color:#EEEEEE"> <?php
if($list['validate']==0){
?>
            <label style="cursor:pointer; color:#FF0000" onClick="doAction('validate',<?php echo $list['id'];?>,1)">未验证</label>
            <?php
}else{
?>
            <label style="cursor:pointer;" onClick="doAction('validate',<?php echo $list['id'];?>,0)">已验证</label>
            <?php
}
?>
            <label style="cursor:pointer" onClick="reply(<?php echo $list['id'];?>,'<?php echo $list['reply'];?>')">回复</label>
            <label style="cursor:pointer" onClick="doAction('delete',<?php echo $list['id'];?>)">删除</label></th>
        </tr>
        <tr class="row">
          <td height="26" colspan="2" style="line-height:20px" ><?php echo $list['content'];?>
            <div id="replyDiv<?php echo $list['id'];?>">
              <?php
if(!empty($list['reply'])){
?>
              <hr>
              <strong>管理员回复：</strong><font style="color:#009900"> <?php echo $list['reply_date']?> </font><br>
              <span class="STYLE1"><?php echo $list['reply'];?> </span><br>
              <?php
}
?>
            </div></td>
        </tr>
        <?php
	}
  ?>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table_footer">
        <tr>
          <td height="3" colspan="2" background="admin/images/20070907_03.gif"></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
