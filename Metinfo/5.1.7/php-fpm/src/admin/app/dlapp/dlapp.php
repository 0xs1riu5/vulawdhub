<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$mytype[0]=$lang_usertype1;
$mytype[1]=$lang_usertype2;
$mytype[2]=$lang_usertype3;
$mytype[3]=$lang_usertype4;
$sysver=$met_app_sysver;
$info=$met_app_info;
	$info=explode('|',$info);
	$info[0]=ltrim($info[0],'metinfo');
	$query="select * from $met_app where download=1";
	$apptemp=$db->get_all($query);
	foreach($apptemp as $keyapptemp=>$valapptemp){
		$app[$valapptemp['no']]=$valapptemp;
	}
	$query="select * from $met_app where download=0";
	$apptemp=$db->get_all($query);
	foreach($apptemp as $keyapptemp=>$valapptemp){
		$str_apps[$valapptemp['no']][0]=$valapptemp['name'];
		$str_apps[$valapptemp['no']][1]=$valapptemp['no'];
		$str_apps[$valapptemp['no']][2]=$valapptemp['ver'];
		$str_apps[$valapptemp['no']][3]=$valapptemp['img'];
		$str_apps[$valapptemp['no']][4]=$valapptemp['info'];
		$str_apps[$valapptemp['no']][5]=$valapptemp['file'];
		$str_apps[$valapptemp['no']][6]=$valapptemp['power'];
		$str_apps[$valapptemp['no']][7]=$valapptemp['sys'];
		$str_apps[$valapptemp['no']][8]=$valapptemp['addtime'];
		$str_apps[$valapptemp['no']][9]=$valapptemp['updatetime'];
	}
	foreach ($str_apps as $keyapps=>$valapps){
		$rrr=metver($metcms_v,$valapps[7],$sysver);
		$valapps['xtype1']="<span class='color390'>{$mytype[$valapps[6]]}</span> {$lang_appdl1}";
		if($info[0]>=$valapps[6]){
			if(metver($metcms_v,$valapps[7],$sysver)>=2){
				if($app[$valapps[1]]['download']==0){
					$typetxt123=$valapps[6]>0?'':$mytype[$valapps[6]];
					$valapps['xtype']="<a href=\"http://$met_host/dl/app.php\" onclick=\"return olupdate('$valapps[1]','0','testc');\">".$typetxt123."{$lang_appinstall}</a>";
					if($valapps[6]>0)$valapps['xtype']="{$lang_dlapptips10}{$mytype[$valapps[6]]}{$lang_dlapptips9}&nbsp;".$valapps['xtype'];
					$valapps['ver_now']=0;
				}else{
					$valapps['ver_now']=$app[$valapps[1]]['ver'];
					if($valapps['ver_now']==$valapps[2]){
						$valapps['xtype']="{$lang_appdl2}</a>";
					}else{
						$valapps['xtype']="<a href='http://$met_host/dl/app.php' onclick=\"return olupdate('$valapps[1]','$valapps[ver_now]','testc');\">{$lang_appupgrade}</a>";
					}
				}
			}else{
				$valapps['xtype']="{$lang_appdl3}{$valapps[7]}{$lang_appdl4}";
			}
		}else{
			$valapps['xtype']="{$lang_dlapptips10}<a href='http://www.metinfo.cn/web/product.htm' target='_blank'>{$mytype[$valapps[6]]}</a>{$lang_dlapptips9}";
		}
		$newapplist[]=$valapps;
	}
function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
    if(is_array($multi_array)){
        foreach ($multi_array as $row_array){
            if(is_array($row_array)){
                $key_array[] = $row_array[$sort_key];
            }else{
                return -1;
            }
        }
    }else{
        return -1;
    }
    array_multisort($key_array,$sort,$multi_array);
    return $multi_array;
}
	$str_apps=$newapplist;
	$str_apps=multi_array_sort($str_apps,'9',SORT_DESC);
$authinfo=$db->get_one("SELECT * FROM $met_otherinfo where id=1");
$listclass[2]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/dlapp/dlapp');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>