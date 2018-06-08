<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
session_start();
		  $_SESSION['metinfo_admin_id'] ='';
		  $_SESSION['metinfo_admin_name'] ='';
          $_SESSION['metinfo_admin_pass'] ='';
		  $_SESSION['metinfo_admin_time'] ='';
		  $_SESSION['metinfo_admin_pop']  ='';
		  $_SESSION['metinfo_admin_type'] ='';
		  $_SESSION['languser'] ='';
		  if(isset($_COOKIE['PHPSESSID'])) setcookie("PHPSESSID", "", mktime()-86400*7, "/");
Header("Location: ../");
exit;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>