<?php 
defined('DT_ADMIN') or exit('Access Denied');
?>
<!doctype html>
<html lang="<?php echo DT_LANG;?>">
<head>
<meta charset="<?php echo DT_CHARSET;?>"/>
<title>提示信息</title>
<link rel="stylesheet" href="admin/image/style.css" type="text/css" />
<script type="text/javascript" src="<?php echo DT_STATIC;?>lang/<?php echo DT_LANG;?>/lang.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/config.js"></script>
<!--[if lte IE 9]><!-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/jquery-1.5.2.min.js"></script>
<!--<![endif]-->
<!--[if (gte IE 10)|!(IE)]><!-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/jquery-2.1.1.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/common.js"></script>
<script type="text/javascript" src="<?php echo DT_STATIC;?>file/script/admin.js"></script>
</head>
</body>
<div id="box" style="padding:16px 16px 0 16px;line-height:2.0;">
<?php echo $dcontent; ?>
</div>
<script type="text/javascript">
try{parent.Dd('dload').style.display='none';parent.Dd('diframe').style.height = Dd('box').scrollHeight+'px';} catch(e){}
</script>
</body>
</html>