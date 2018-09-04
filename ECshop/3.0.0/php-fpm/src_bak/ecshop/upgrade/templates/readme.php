<html>
<head>
<title> <?php echo $lang['readme_title'];?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#logos { background: #278296; border-bottom: 1px solid #FFF; }
#submenu-div { background: #80BDCB; height: 24px; border-bottom: 1px solid #FFF; }
#wrapper { background: #F4FAFB; padding: 10px; border: 1px solid #BBDDE5; margin-top: 20px; width: 95%;}
</style>
</head>

<body>
<?php include ROOT_PATH . 'upgrade/templates/header.php';?>

<div id="wrapper" style="text-align:left;">

<form method="post" action="index.php?step=check" name="checkform">

<h3><?php echo $lang['method'];?></h3>
<p><?php printf($lang['notice'], $new_version);?></p>
<ol>
    <li><?php echo $lang['usage1'];?></li>
    <li><?php printf($lang['usage2'], $old_version);?></li>
    <li><?php printf($lang['usage3'], $new_version);?></li>
    <li><?php echo $lang['usage4'];?></li>
    <li><?php echo $lang['usage5'];?></li>
    <li><?php echo $lang['usage6'];?></li>
</ol>

<h3><?php echo $lang['charset'];?></h3>
<ul>
    <li><?php echo $lang['readme_charset'];?></li>
    <li><?php echo $lang['mysql_charset'];?><strong><?php echo empty($mysql_charset)?$lang['unknow_charset']:$mysql_charset;?></strong></li>
    <li><?php echo $lang['ecshop_charset'];?><strong><?php echo empty($ecshop_charset)?$lang['unknow_charset']:$ecshop_charset;?></strong></li>
    <li><?php if (empty($mysql_charset) && empty($ecshop_charset)) { ?>
    <?php echo sprintf($lang['sel_charset_1'], $lang['opt_charset']); ?>
    <?php } elseif (empty($mysql_charset) && !empty($ecshop_charset)) { ?>
    <?php echo sprintf($lang['sel_charset_2'], $lang['opt_charset']); ?>
    <?php } elseif (!empty($mysql_charset) && empty($ecshop_charset)) { ?>
    <?php echo sprintf($lang['sel_charset_3'], $lang['opt_charset']); ?>
    <?php } elseif ($mysql_charset != $ecshop_charset) { ?>
    <?php echo sprintf($lang['sel_charset_1'], $lang['opt_charset']); ?>
    <?php } else { ?>
    <?php echo $lang['sel_charset_0']; ?>
    <?php } ?>
    </li>
</ul>

<h3><?php echo $lang['faq'];?></h3>
<iframe src="templates/faq_<?php echo $updater_lang;?>.htm" width="730" height="350"></iframe>
<div align="center">
<input type="submit" id="js-submit" disabled="true" class="button" value="<?php echo $lang['next_step'];?><?php echo $lang['check_system_environment'];?>" />

</div>
</form>
</div>
<script type="text/javascript">
function _o(id){return document.getElementById(id);}
if (_o('select_charset')) {
    _o('select_charset').onchange = function() {
        if (this.value) {
            _o('js-submit').disabled = false;
        } else {
            _o('js-submit').disabled = true;
        }
    }
} else {
    _o('js-submit').disabled = false;
}
</script>
<?php include ROOT_PATH . 'upgrade/templates/copyright.php';?>
</body>
</html>
