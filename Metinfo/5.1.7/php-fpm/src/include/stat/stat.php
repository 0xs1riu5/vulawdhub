<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../common.inc.php';
require_once 'global.func.php';
require_once ROOTPATH.'include/export.func.php';
if($type=='para' && $met_stat){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
	$ips=explode(',',$ip);
	$ips=$ip[0];
	$metinfo = "
jQuery.statcookie = function (name, value, options) { if (typeof value != 'undefined') { options = options || {}; if (value === null) { value = ''; options.expires = -1; } var expires = ''; if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) { var date; if (typeof options.expires == 'number') { date = new Date(); date.setTime(date.getTime() + (options.expires * 1000)); } else { date = options.expires; } expires = '; expires=' + date.toUTCString(); } var path = options.path ? '; path=' + options.path : ''; var domain = options.domain ? '; domain=' + options.domain : ''; var secure = options.secure ? '; secure' : ''; document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join(''); } else { var cookieValue = null; if (document.cookie && document.cookie != '') { var cookies = document.cookie.split(';'); for (var i = 0; i < cookies.length; i++) { var cookie = jQuery.trim(cookies[i]); if (cookie.substring(0, name.length + 1) == (name + '=')) { cookieValue = decodeURIComponent(cookie.substring(name.length + 1)); break; } } } return cookieValue; } };
jQuery.myPlugin={Client:function(){var a={ie:0,webkit:0,gecko:0,opera:0,khtml:0};var b={se360:0,se:0,maxthon:0,qq:0,tt:0,theworld:0,cometbrowser:0,greenbrowser:0,ie:0,chrome:0,netscape:0,firefox:0,opera:0,safari:0,konq:0};var c=navigator.userAgent.toLowerCase();for(var d in a){if(typeof d==='string'){var e='gecko'===d?/rv:([\w.]+)/:RegExp(d+'[ \\/]([\\w.]+)');if(e.test(c)){a.version=window.opera?window.opera.version():RegExp.$1;a[d]=parseFloat(a.version);a.type=d;break}}};for(var d in b){if(typeof d==='string'){var e=null;switch(d){case'se360':e=/360se(?:[ \/]([\w.]+))?/;break;case'se':e=/se ([\w.]+)/;break;case'qq':e=/qqbrowser\/([\w.]+)/;break;case'tt':e=/tencenttraveler ([\w.]+)/;break;case'safari':e=/version\/([\w.]+)/;break;case'konq':e=/konqueror\/([\w.]+)/;break;case'netscape':e=/navigator\/([\w.]+)/;break;default:e=RegExp(d+'(?:[ \\/]([\\w.]+))?')};if(e.test(c)){b.metversion=window.opera?window.opera.version():RegExp.$1?RegExp.$1:'';b[d]=parseFloat(b.metversion);b.type=d;break}}};return{engine:a,metshell:b}}};
function broversion(){
	var bro=jQuery.myPlugin.Client();
		t=bro.metshell.type;
		v=bro.metshell.metversion;
		//bro=t=='ie'?t+v:t;
		if(t=='ie'&&v==''){
			e=/ie(?:[ \\/]([\\w.]+))?/;	
			v=e.exec(navigator.userAgent.toLowerCase())[1];
		}
		bro=t=='ie'?t+v:t;
		if(typeof window.external !='undefined' && typeof window.external.twGetRunPath!='unknown'&& typeof window.external.twGetRunPath!='undefined'){
			var r=external.twGetRunPath();
			if(r&&r.toLowerCase().indexOf('360se') > -1) bro='se360';
		}
		if(t=='ie'&&typeof external.addChannel=='undefined'){
			bro='se360';
		}
	return bro;
}
function forcook(cd,u){
	cd = cd.split(',');
	cdm=cd.length;
	for(var i=0;i < cdm;i++){
		if(u!='' && cd[i]==u)return false;
	}
	return true;
}
function metstat(){
	var url=encodeURIComponent(window.location.href),lurl=encodeURIComponent(document.referrer),cookm=jQuery.statcookie('recordurl'),myDate = new Date();
	var dt = Date.UTC(myDate.getFullYear(),myDate.getMonth(),myDate.getDay(),myDate.getHours(),myDate.getMinutes(),myDate.getSeconds())/1000;
	var xt = Date.UTC(myDate.getFullYear(),myDate.getMonth(),myDate.getDay(),23,59,59)/1000;
	var ctime = xt - dt;
	//if(!cookm || forcook(cookm,url)){
		var cks = cookm?cookm:'';
		var cok = cookm?1:0;
		jQuery.statcookie('recordurl',cks+','+url, {expires: ctime, path: '/'});
		var murl ='{$u}include/stat/stat.php?type=submit';
			murl+='&ip={$ip}';
			murl+='&url='+url;
			murl+='&lurl='+lurl;
			murl+='&cook='+cok;
			murl+='&d={$d}';
			murl+='&browser='+broversion();
			murl+='&jsoncallback=?';
		//$.ajax({ type: 'POST', url: murl });
		jQuery.getJSON(murl);
	//}
}
metstat();
			";
	echo $metinfo;
}
if($type=='submit' && $met_stat){
		$lurl=str_replace("\\",'',$lurl);
		$url=str_replace("\\",'',$url);
		$ip=delete($ip);
		$url=delete($url);
		$lurl=delete($lurl);
		$browser=delete($browser);
		$d=delete($d);
		if(checkadd(1,$ip) && checkadd(2,$url) && $browser!=''){
			if(!$lurl || checkadd(2,$lurl)){
				$stime =strtotime(date("Y-m-d H:i:s"));
				/*访问的页面*/
				$d=explode('-',$d);
				$columnid=$d[0];
				$listid=$d[1];
				$lang=$d[2];
				$dizhi='';
				$dayquery ="INSERT INTO {$met_visit_day} SET
							ip           = '{$ip}',
							acctime      = '{$stime}',
							visitpage    = '{$url}',
							antepage     = '{$lurl}',
							columnid     = '{$columnid}',
							listid       = '{$listid}',
							browser      = '{$browser}',
							dizhi        = '{$dizhi}',
							network      = '{$network}',
							lang         = '{$lang}'";
				$dtime =strtotime(date("Y-m-d"));
				$visit=$db->get_one("SELECT * FROM {$met_visit_summary} WHERE stattime='{$dtime}'");
				$ztian0=strtotime(date("Y/m/d 00:00:00"));
				$ztian1=strtotime(date("Y/m/d 23:59:59"));
				$ipset=$ip;
				$met_stat_maxok=1;
				if($visit){
					$vsip=$db->get_one("SELECT * FROM {$met_visit_day} WHERE ip='{$ip}' and acctime >='{$ztian0}' and acctime<='{$ztian1}'"); 
					$pv = $visit['pv']+1;
					if($pv<$met_stat_max){
						$ip = $vsip?$visit['ip']:$visit['ip']+1;
						$alone = $vsip&&$cook?$visit['alone']:$visit['alone']+1;
						$parip = $vsip?0:1;
						$paral = $vsip&&$cook?0:1;
						$parttime = parttime(date('G'),$visit['parttime'],1,$parip,$paral);
						$query = "update {$met_visit_summary} SET
									pv         = '{$pv}',
									ip         = '{$ip}',
									alone      = '{$alone}',
									parttime   = '{$parttime}'
									where id = '{$visit['id']}'";
						$db->query($query);
					}else{
						$met_stat_maxok=0;
					}
				}else{
					$met='';for($i=0;$i<24;$i++)$met.= '|';
					$parttime = parttime(date('G'),$met,1,1,1);
					$query = "INSERT INTO {$met_visit_summary} SET
								pv         = '1',
								ip         = '1',
								alone      = '1',
								parttime   = '{$parttime}',
								stattime = '{$dtime}'";
					$db->query($query);
					if($met_stat_cr1)delet_estat_cr(1,$met_stat_cr1);
					if($met_stat_cr2)delet_estat_cr(2,$met_stat_cr2);
					if($met_stat_cr3)delet_estat_cr(3,$met_stat_cr3);
					if($met_stat_cr4)delet_estat_cr(4,$met_stat_cr4);
					if($met_stat_cr5)delet_estat_cr(5,$met_stat_cr5);
				}
				if($met_stat_maxok){
					$lurlkey=keytype($lurl);
					$lurlkey[0]=daddslashes($lurlkey[0]);
					$lurlkey[1]=daddslashes($lurlkey[1]);
					if($lurlkey && delete($lurlkey[0])!=''){
						$keyok=$db->get_one("SELECT * FROM {$met_visit_detail} WHERE name='{$lurlkey[0]}' and stattime = '{$dtime}' and type='1'");
						if($keyok){
							$keyok_remark=explode('|',$keyok['remark']);
							$remark='';
							$p=0;
							for($i=0;$i<count($keyok_remark);$i++){
								if($keyok_remark[$i]!=''){
									$rk=explode('-',$keyok_remark[$i]);
									if($rk[0]==$lurlkey[1]){
										$k=$rk[1]+1;
										$keyok_remark[$i]=$rk[0].'-'.$k;
										$p=1;
									}
									$remark.=$keyok_remark[$i].'|';
								}
							}
							if($p==0)$remark=$remark.'|'.$lurlkey[1].'-1|';
							$vsip=$db->get_one("SELECT * FROM {$met_visit_day} WHERE ip='{$ipset}' and acctime >='{$ztian0}' and acctime<='{$ztian1}' and antepage = '{$lurl}'"); 
							$pv = $keyok['pv']+1;
							$ip = !$vsip?$keyok['ip']+1:$keyok['ip'];
							$alone = $vsip&&$cook?$keyok['alone']:$keyok['alone']+1;
							$query = "update {$met_visit_detail} SET
										pv       = '{$pv}',
										ip       = '{$ip}',
										alone    = '{$alone}',
										remark   = '{$remark}',
										type     = '1'
										where id = '{$keyok['id']}'";
							$db->query($query);
						}else{
							if($lurlkey[0]!=''){
								$lurlkey[1]=$lurlkey[1].'-1|';
								$query = "INSERT INTO {$met_visit_detail} SET
									name     = '{$lurlkey[0]}',
									pv       = '1',
									ip       = '1',
									alone    = '1',
									remark   = '{$lurlkey[1]}',
									type     = '1',
									stattime = '{$dtime}'";
								$db->query($query);
							}
						}
					}
					$urlok=$db->get_one("SELECT * FROM {$met_visit_detail} WHERE name='{$url}' and stattime = '{$dtime}' and type='2'");
					if($urlok){
						$vsip=$db->get_one("SELECT * FROM {$met_visit_day} WHERE ip='{$ipset}' and acctime >='{$ztian0}' and acctime<='{$ztian1}' and visitpage ='{$url}'"); 
						$pv = $urlok['pv']+1;
						$ip = !$vsip?$urlok['ip']+1:$urlok['ip'];
						$alone = $vsip&&$cook?$urlok['alone']:$urlok['alone']+1;
						$query = "update {$met_visit_detail} SET
									pv       = '{$pv}',
									ip       = '{$ip}',
									alone    = '{$alone}',
									remark   = '',
									type     = '2'
									where id = '{$urlok['id']}'";
						$db->query($query);
					}else{
						$query = "INSERT INTO {$met_visit_detail} SET
							name     = '{$url}',
							pv       = '1',
							ip       = '1',
							alone    = '1',
							remark   = '',
							type     = '2',
							columnid = '{$columnid}',
							listid   = '{$listid}',
							stattime = '{$dtime}',
							lang     = '{$lang}'";
						$db->query($query);
					}
					$lurlok=$db->get_one("SELECT * FROM {$met_visit_detail} WHERE name='{$lurl}' and stattime = '{$dtime}' and type='3'");
					if($lurlok){
						$vsip=$db->get_one("SELECT * FROM {$met_visit_day} WHERE ip='{$ipset}' and acctime >='{$ztian0}' and acctime<='{$ztian1}' and antepage ='{$lurl}'"); 
						$pv = $lurlok['pv']+1;
						$ip = !$vsip?$lurlok['ip']+1:$lurlok['ip'];
						$alone = $vsip&&$cook?$lurlok['alone']:$lurlok['alone']+1;
						$query = "update {$met_visit_detail} SET
									pv       = '{$pv}',
									ip       = '{$ip}',
									alone    = '{$alone}',
									remark   = '',
									type     = '3'
									where id = '{$lurlok['id']}'";
						$db->query($query);
					}else{
						$query = "INSERT INTO {$met_visit_detail} SET
							name     = '{$lurl}',
							pv       = '1',
							ip       = '1',
							alone    = '1',
							remark   = '',
							type     = '3',
							stattime = '{$dtime}'";
						$db->query($query);
					}
					$db->query($dayquery);
				}
			}
		}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>