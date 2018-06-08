<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../include/common.inc.php';
if(!$met_addlinkopen)okinfo('javascript:history.back();',$lang_ApplyLinkNO);
    $link_list=$db->get_one("select * from $met_column where module='9' and lang='$lang'");
    $metaccess=$link_list[access];
    $class1=$link_list[id];
require_once ROOTPATH.'include/head.php';
	$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
	$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
    $navtitle=$link_list[name];
	$addlink_url=$met_pseudo?'addlink-'.$lang.'.html':($met_webhtm?"addlink".$met_htmtype:"addlink.php?lang=".$lang);
    if($action=="add"){
		if($met_memberlogin_code==1){
			require_once ROOTPATH."{$met_adminfile}/include/captcha.class.php";
			$Captcha= new  Captcha();
			if(!$Captcha->CheckCode($code)){
			echo("<script type='text/javascript'> alert('$lang_membercode');window.history.back();</script>");
			   exit;
			}
		}
		$webname=strip_tags($webname);
		$weburl=strip_tags($weburl);
		$weblogo=strip_tags($weblogo);
		$info=strip_tags($info);
		$contact=strip_tags($contact);
        $ip=$m_user_ip;
        $addtime=$m_now_date;
        $ipok=$db->get_one("select * from $met_link where ip='$ip' order by addtime desc");
        if($ipok)
            $time1 = strtotime($ipok[addtime]);
        else
            $time1 = 0;
            $time2 = strtotime($m_now_date);
            $timeok= (float)($time2-$time1);
			$timeok2=(float)($time2-$_COOKIE['submit']);
        if($timeok<=120&&$timeok2<=120){
            $fd_time="{$lang_Feedback1} 120 {$lang_Feedback2}";
            okinfo('javascript:history.back();',$fd_time);
        }
		setcookie('submit',$time2);
		require_once '../include/jmail.php';
/*短信提醒*/
if($met_nurse_link){
require_once ROOTPATH.'include/export.func.php';
if(maxnurse()<$met_nurse_max){
$domain = strdomain($met_weburl);
$message="您网站[{$domain}]收到了新的友情链接申请[".strdomain($weburl)."]，请尽快登录网站后台查看";
sendsms($met_nurse_link_tel,$message,4);
}
}
/**/
        $query = "INSERT INTO $met_link SET
                      webname              = '$webname',
					  info                 = '$info',
					  link_type            = '$link_type',
					  weburl               = '$weburl',
					  weblogo              = '$weblogo',
					  contact              = '$contact',
					  orderno              = '$orderno',
					  lang                 = '$lang', 
					  ip                   = '$ip', 
					  addtime              = '$m_now_date'";
					  
        $db->query($query);
        $returnurl=$module_listall[9][0][url];
        okinfo($returnurl,$lang_MessageInfo2);
    }else{
        $class2=$class_list[$class1][releclass]?$class1:$class2;
        $class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
        $class_info=$class2?$class2_info:$class1_info;
        if($class2!="")$class_info[name]=$class2_info[name]."--".$class1_info[name];
        $show[description]=$class_info[description]?$class_info[description]:$met_keywords;
        $show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
		$met_title=$met_title?$navtitle.'-'.$met_title:$navtitle;
		if($link_list['ctitle']!='')$met_title=$link_list['ctitle'];
        if(count($nav_list2[$link_list[id]])){
            $k=count($nav_list2[$class1]);
            $nav_list2[$class1][$k]=$class1_info;
            $k++;
            $nav_list2[$class1][$k]=array('url'=>$addlink_url,'name'=>$lang_ApplyLink);
        }else{
            $k=count($nav_list2[$class1]);
            if(!$k){
				$ankok=1;
                $nav_list2[$class1][0]=array('id'=>10010,'url'=>$addlink_url,'name'=>$lang_ApplyLink);
                $nav_list2[$class1][1]=$class1_info;
            }
        }
		$class_list[10010]=array('id'=>10010,'url'=>$addlink_url,'name'=>$lang_ApplyLink);
        $fdjs="<script language='javascript'>";
        $fdjs=$fdjs."function Checklink(){ ";
        $fdjs=$fdjs."if (document.myform.webname.value.length == 0) {";
        $fdjs=$fdjs."alert('{$lang_LinkInfo2}');";
        $fdjs=$fdjs."document.myform.webname.focus();";
        $fdjs=$fdjs."return false;}";
        $fdjs=$fdjs."if (document.myform.weburl.value.length == 0 || document.myform.weburl.value == 'http://') {";
        $fdjs=$fdjs."alert('{$lang_LinkInfo3}');";
        $fdjs=$fdjs."document.myform.weburl.focus();";
        $fdjs=$fdjs."return false;}";
        $fdjs=$fdjs."}</script>";
require_once '../public/php/methtml.inc.php';
		if($ankok==1)$cvidnow=10010;
        $methtml_addlink.=$fdjs;
        $methtml_addlink.="<table width='90%' cellpadding='2' cellspacing='1' bgcolor='#F2F2F2' align='center' class='addlink_table'>\n";
        $methtml_addlink.="
		    <tr class='addlink_tr'>
			    <td width='20%' height='25' align='left' bgcolor='#FFFFFF' colspan='3' class='addlink_title'>
				    <b>".   $lang_Info4."</b>&nbsp;
				</td>
			</tr>\n";
        $methtml_addlink.="<tr class='addlink_tr'>\n";
        $methtml_addlink.="<td width='20%' height='25' align='right' bgcolor='#FFFFFF' class='addlink_td1'><b>".$lang_OurWebName."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_td2'>".$met_linkname."&nbsp;</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000'></td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td align='right' bgcolor='#FFFFFF' class='addlink_td1'><b>".$lang_OurWebUrl."</b>&nbsp;</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' class='addlink_td2'>".$met_weburl."</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000'></td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td align='right' bgcolor='#FFFFFF' class='addlink_td1'><b>".$lang_OurWebLOGO."</b>&nbsp;</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' class='addlink_td2'><img src='".$met_logo."' height='33' /></td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000'></td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td align='right' bgcolor='#FFFFFF' class='addlink_td1'><b>".$lang_OurWebKeywords."</b>&nbsp;</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' class='addlink_td2'>".$met_title_keywords."</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000'></td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="</table>\n";
$methtml_addlink.="<form method='POST' name='myform' onSubmit='return Checklink();' action='addlink.php?action=add' target='_self'>\n";
$methtml_addlink.="<table width='90%' cellpadding='2' cellspacing='1' bgcolor='#F2F2F2' align='center' class=addlink_table >\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td width='20%' height='25' align='right' bgcolor='#FFFFFF' class='addlink_td1'><b>".$lang_YourWebName."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><input name='webname' type='text' size='30' /></td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'>*</td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td class='addlink_td1' align='right' bgcolor='#FFFFFF'><b>".$lang_YourWebUrl."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><input name='weburl' type='text' size='30' value='http://' /></td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'>*</td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td class='addlink_td1' align='right' bgcolor='#FFFFFF'><b>".$lang_LinkType."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><input name='link_type' type='radio' value='0'  checked='checked' style='border:0px;' />{$lang_TextLink}  <input name='link_type' type='radio' value='1' style='border:0px;' />{$lang_PictureLink}</td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'>*</td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td class='addlink_td1' align='right' bgcolor='#FFFFFF'><b>".$lang_YourWebLOGO."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><input name='weblogo' type='text' size='30' value='http://'/></td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'></td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td class='addlink_td1' align='right' bgcolor='#FFFFFF'><b>".$lang_YourWebKeywords."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><input name='info' type='text' size='30' /></td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'></td>\n";
$methtml_addlink.="</tr>\n";
$methtml_addlink.="<tr class='addlink_tr'>\n";
$methtml_addlink.="<td class='addlink_td1' align='right' bgcolor='#FFFFFF'><b>".$lang_Contact."</b>&nbsp;</td>\n";
$methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><textarea name='contact' cols='50' rows='6'></textarea></td>\n";
$methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'></td>\n";
$methtml_addlink.="</tr>\n";
if($met_memberlogin_code==1){  
	 $methtml_addlink.="<tr class='addlink_tr'><td class='addlink_td1' align='right' bgcolor='#FFFFFF'>".$lang_memberImgCode."</td>\n";
     $methtml_addlink.="<td width='70%' bgcolor='#FFFFFF' class='addlink_input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $methtml_addlink.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $methtml_addlink.="</td>\n";
	 $methtml_addlink.="<td bgcolor='#FFFFFF' style='color:#990000' class='addlink_info'>*</td>\n";
     $methtml_addlink.="</tr>\n";
}
$methtml_addlink.="<tr class='addlink_tr'><td colspan='3' bgcolor='#FFFFFF' align='center' class='addlink_submit'>\n";
$methtml_addlink.="<input type='submit' name='Submit' value='".$lang_Submit."' class='tj'>\n";
$methtml_addlink.="<input type='hidden' name='lang' value='".$lang."'>\n";
$methtml_addlink.="<input type='reset' name='Submit' value='".$lang_Reset."' class='tj'></td></tr>\n";
$methtml_addlink.="</table>\n";
$methtml_addlink.="</form>\n";
$csnow='10010';
include template('addlink');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>