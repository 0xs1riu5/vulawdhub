<?php
include ("admin.inc.php");
include ("../include/fckeditor/fckeditor.php");

$cid	= trim($_GET ['cid'])?trim($_GET ['cid']):0;
$id	= trim($_GET ['id'])?trim($_GET ['id']):0;
$act	= trim($_GET ['act'])?trim($_GET ['act']):'add';

$actName = $act == 'add'?'添加':'修改';
$article = $db->getOneRow ( "select * from cms_article where id=" . $id );
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<link href="images/css.css" rel="stylesheet" type="text/css">
<script src="../include/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
function doAction(a,id){
	ids = 0;
	if(a=='delpic'){
		$.ajax({
			url:'article.action.php',
			type: 'POST',
			data:'act=delpic&id='+id,
			success: function(data){
				document.getElementById('picdiv').innerHTML="";
			}
		});
	}
}
</script>
</head>
<body onLoad="document.getElementById('title').focus()">
    <form action="article.action.php" method="post" enctype="multipart/form-data" name="form1">
        <input type="hidden" name="act" value="<?php echo $act;?>">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
          <tr>
            <td height="31"><strong><?php echo $actName;?>文章</strong></td>
          </tr>
        </table>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10%" height="40" class="form_list">标题 ：</td>
    <td width="40%" class="form_list"><input name="title" type="text" class="form" style="width: 90%" value="<?php echo $article ['title'];?>"></td>
    <td width="10%" class="form_list">副标题：</td>
    <td width="40%" class="form_list"><input name="subtitle" type="text" class="form" style="width: 90%" value="<?php echo $article ['subtitle'];?>"></td>
  </tr>
  <tr>
    <td height="40" class="form_list">属性：</td>
    <td colspan="3" class="form_list">
    
    <input type="checkbox" name="att[]" value="a" <?php if(strstr($article['att'],"a")) echo "checked";?>> 头条[a]&nbsp;&nbsp;
    <input type="checkbox" name="att[]" value="b" <?php if(strstr($article['att'],"b")) echo "checked";?>> 推荐[b]&nbsp;&nbsp;
    <input type="checkbox" name="att[]" value="c" <?php if(strstr($article['att'],"c")) echo "checked";?>> 热门[c]&nbsp;&nbsp;
    <input type="checkbox" name="att[]" value="d" <?php if(strstr($article['att'],"d")) echo "checked";?>> 滚动[d]&nbsp;&nbsp;
    <input type="checkbox" name="att[]" value="e" <?php if(strstr($article['att'],"e")) echo "checked";?>> 自定义[e]&nbsp;&nbsp;
    <input type="checkbox" name="att[]" value="f" <?php if(strstr($article['att'],"f")) echo "checked";?>> 自定义[f]&nbsp;&nbsp;
    <input type="checkbox" name="att[]" value="g" <?php if(strstr($article['att'],"g")) echo "checked";?>> 自定义[g]&nbsp;&nbsp;
      </td>
  </tr>
  <tr>
    <td height="40" class="form_list">缩略图：</td>
    <td colspan="3" class="form_list"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="200"><input type="file" name="pic" id="pic"></td>
        <td><div id="picdiv">
          <?php 
                if(!empty($article ['pic'])){
                ?>
          <img src="../<?php echo $article ['pic'];?>" width="100" height="40" onMouseOver="document.getElementById('bigPic').style.display=''" onMouseOut="document.getElementById('bigPic').style.display='none'">
          <div id="bigPic" style="display:none; position:absolute;"><img src="../<?php echo $article ['pic'];?>"></div>
 
              <font style="cursor:pointer; font-size:12px" onclick="doAction('delpic',<?php echo $id;?>)">删除图片</font>
          <?php
                }
                ?>
        </div>           </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="40" class="form_list">出处：</td>
    <td class="form_list"><input name="source" type="text" class="form" style="width: 90%" value="<?php echo $article ['source'];?>"></td>
    <td class="form_list">作者：</td>
    <td class="form_list"><input name="author" type="text" class="form" value="<?php echo $article ['author'];?>"></td>
  </tr>
  <tr>
    <td height="40" class="form_list">所属栏目：</td>
    <td class="form_list"><input name="cid" type="hidden" value="<?php echo $id;?>">
        <select name="cid">
          <option value="0">--未分类--</option>
          <?php getCategorySelect ($cid)?>
        </select>
    <td class="form_list">摘要：</td>
    <td class="form_list"><textarea name="resume" class="form" style="width: 90%; height: 50px; overflow: auto"><?php echo trim ( $article ['resume'] );?></textarea></td>
  </tr>
  <tr>
    <td height="40" colspan="4" align="center" class="form_list">
	<?php
        $oFCKeditor = new FCKeditor ( 'content' );
        $oFCKeditor->BasePath = "../include/fckeditor/";
        $oFCKeditor->ToolbarSet = 'MyToolbar';
        $oFCKeditor->Value = $article ['content'];
        $oFCKeditor->Height = 350;
        $oFCKeditor->Create ();
    ?>    
	</td>
  </tr>
</table>
        
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_title">
          <tr>
            <td height="31" align="center"><strong><span class="form_footer">
              <input name="id" type="hidden" value="<?php echo $id;?>">
              <input type="submit" name="button" id="button" value=" 提 交 ">
              <input type="button" value=" 返 回 " onClick="window.history.go(-1)">
            </span></strong></td>
          </tr>
        </table>
</form>
</body>
</html>
