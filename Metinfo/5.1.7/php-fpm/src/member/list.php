<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
 $methtl_membernav.="<ul>\n";
 $methtl_membernav.="<li><a target='main' onfocus='if(this.blur)this.blur();' href='basic.php?lang=".$lang."'>".$lang_memberIndex3."</a></li>\n";
 $methtl_membernav.="<li><a target='main' onfocus='if(this.blur)this.blur();' href='editor.php?lang=".$lang."'>".$lang_memberIndex4."</a></li>\n";
 $methtl_membernav.="<li><a target='main' onfocus='if(this.blur)this.blur();' href='feedback.php?lang=".$lang."'>".$lang_memberIndex5."</a></li>\n";
 $methtl_membernav.="<li><a target='main' onfocus='if(this.blur)this.blur();' href='message.php?lang=".$lang."'>".$lang_memberIndex6."</a></li>\n";
 $methtl_membernav.="<li><a target='main' onfocus='if(this.blur)this.blur();' href='cv.php?lang=".$lang."'>".$lang_memberIndex7."</a></li>\n";
 $methtl_membernav.="<li><a href='login_out.php?lang=".$lang."'>".$lang_memberIndex10."</a></li>\n";
 if(count($nav_list2[$classnow])){
 foreach($nav_list2[$classnow] as $key=>$val){
 $methtl_membernav.="<li><a href='$val[url]'>".$val[name]."</a></li>\n";
 }
 }
 $methtl_membernav.="</ul>\n";
 $methtml_membernav=$methtl_membernav;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>