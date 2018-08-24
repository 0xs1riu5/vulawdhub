<?php
define('zzcmsroot', str_replace("\\", '/', substr(dirname(__FILE__), 0, -3)));//-3½Ø³ýµ±Ç°Ä¿Â¼one
include("../inc/config.php");
include("../inc/function.php");
include("../inc/stopsqlin.php");
$kind = isset($_GET['kind'])?nohtml($_GET['kind']):"zs";
$keyword=isset($_GET['keyword'])?nohtml($_GET['keyword']):"";
header("location:/$kind/search.php?keyword=$keyword"); 
?>