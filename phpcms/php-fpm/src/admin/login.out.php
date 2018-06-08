<?php
session_start();
if(isset($_COOKIE["userid"])) {
	setcookie("userid", "", time() - 3600);
}
if(isset($_COOKIE["userflag"])) {
	setcookie("userflag", "", time() - 3600);
}
echo "<script language='javascript'>parent.window.location.href='login.php';</script>";
?>