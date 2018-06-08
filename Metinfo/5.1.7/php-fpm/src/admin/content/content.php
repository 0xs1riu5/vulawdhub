<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
$query="update $met_config set value='$met_content_type' where name='met_content_type' and lang='metinfo'";
$db->query($query);
if($met_content_type!=2){
	if($class1 && $module==1){
		if($met_class[$class1]['isshow'])$contentlistes[]=$met_class[$class1];
		foreach($met_class2[$class1] as $key=>$val2){
			if(!$val2['releclass']&&!$val2['if_in'])$contentlistes[] = $val2;
		}
		foreach($contentlistes as $key=>$val){
			$c2 = count($met_class3[$val['id']]);
			$classname = $c2?"class='lt'":'';
			$classname1 = $c2&&$val['isshow']?"class='rt'":'';
			$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
			$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&module=1&class2={$val['id']}":$val[url];
			$val['set']="<div>";
			if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
			if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
			if($c2)$val['set'].="<p {$classname1}><a href='?anyid={$anyid}&lang={$lang}&module=1&class2={$val['id']}'>{$lang_subpart}</a></p>";
			$val['set'].='</div>';
			$contentlist[] = $val;
		}
	}elseif($class2 && $module==1){
		$class1=$met_class[$class2]['bigclass'];
		if($met_class[$class2]['isshow'])$contentlistes[]=$met_class[$class2];
		foreach($met_class3[$class2] as $key=>$val2){
			if(!$val2['releclass']&&!$val2['if_in'])$contentlistes[] = $val2;
		}
		foreach($contentlistes as $key=>$val){
			$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
			$val['set']="<div><p><a href='{$val[conturl]}'>{$lang_eidtcont}</a></p></div>";
			$contentlist[] = $val;
		}
	}else{
		foreach ($met_class1 as $key=>$val){
			if($val['module']<9 && !$val['if_in']){
				$contentlistes[] = $val;
				foreach($met_class2[$val['id']] as $key=>$val2){
					if($val2['releclass']&&$val2['module']<9&&!$val2['if_in'])$contentlistes[] = $val2;
				}
			}
		}
		foreach($contentlistes as $key=>$val){
			$purview='admin_pop'.$val['id'];
			$purview=$$purview;
			$metcmspr=$metinfo_admin_pop=="metinfo" || $purview=='metinfo'?1:0;
			$metcmspr1=$val[classtype]==1 || $val[releclass]?1:0;
			$metcmspr=$metcmspr1?$metcmspr:1;
			if($metcmspr){
			switch($val['module']){
				case '1':
					$c2 = count($met_class2[$val['id']]);
					if($val['releclass'])$c2 = count($met_class3[$val['id']]);
					$classname = $c2?"class='lt'":'';
					$classname1 = $c2&&$val['isshow']?"class='rt'":'';
					$val['url']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div>";
					if($val['isshow'])$val['set'].="<p {$classname}><a href='{$val[url]}'>{$lang_eidtcont}</a></p>";
					$classx = 'class1';
					if($val['releclass'] && $c2)$classx = 'class2';
					$val['conturl']=$c2?"?anyid={$anyid}&lang={$lang}&module=1&{$classx}={$val['id']}":$val[url];
					if($val['isshow'] && $c2)$val['set'].='<span>-</span>';
					if($c2)$val['set'].="<p {$classname1}><a href='{$val[conturl]}'>{$lang_subpart}</a></p>";
					$val['set'].='</div>';
				break;
				case '2':
					$val['url']='article/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;	
					$val['conturl']='article/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div>
								<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
								</div>";				
				break;
				case '3':
					$val['url']='product/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
					$val['conturl']='product/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div>
								<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
								</div>";
				break;
				case '4':
					$val['url']='download/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
					$val['conturl']='download/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div>
								<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
								<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
								</div>";
				break;
				case '5':
					$val['url']='img/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
					$val['conturl']='img/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div>
								<p class='lt'><a href='{$val[url]}'>{$lang_addinfo}</a></p><span>-</span>
								<p class='rt'><a href='{$val[conturl]}'>{$lang_manager}</a></p>
								</div>";
				break;
				case '6':
					$val['url']='job/content.php?class1='.$val[id].'&action=add&lang='.$lang.'&anyid='.$anyid;
					$val['conturl']='job/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['incurl']='job/inc.php?lang='.$lang.'&anyid='.$anyid;
					$val['cvurl']='job/cv.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div>
								<p class='lt'><a href='{$val[conturl]}'>{$lang_manager}</a></p><span>-</span>
								<p class='rt'><a href='{$val[cvurl]}'>{$lang_cveditorTitle}</a></p>
								</div>
								";
				break;
				case '7':
					$val['incurl']='message/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['conturl']='message/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtmsg}</a></div>";
				break;
				case '8':
					$val['url']='feedback/inc.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['conturl']='feedback/index.php?class1='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$val['set']="<div><a href='{$val[conturl]}'>{$lang_eidtfed}</a></div>";
				break;
			}
			$contentlist[] = $val;
			}
		}
	}
}else{
//dump($met_classindex);
	if($module){
		if($class1){
			if($met_class1[$class1]['isshow']){
				$met_class1[$class1]['conturl']='about/about.php?id='.$met_class1[$class1][id].'&lang='.$lang.'&anyid='.$anyid;
				$contentlist[0] = $met_class1[$class1];
			}
			foreach($met_class2[$class1] as $key=>$val){
				if($val['module']==$module){
					$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					if(count($met_class3[$val['id']]))$val['conturl']="?anyid={$anyid}&lang={$lang}&module=1&class2={$val['id']}";
					$contentlist[] = $val;
				}
			}
		}elseif($class2){
			if($met_class[$class2]['isshow']){
				$met_class[$class2]['conturl']='about/about.php?id='.$met_class[$class2][id].'&lang='.$lang.'&anyid='.$anyid;
				$contentlist[0] = $met_class[$class2];
			}
			foreach($met_class3[$class2] as $key=>$val){
				if($val['module']==$module){
					$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
					$contentlist[] = $val;
				}
			}
		}else{
			switch($module){
				case 1:
					foreach($met_class1 as $key=>$val){
						if($val['module']==1){
							$val['conturl']='about/about.php?id='.$val[id].'&lang='.$lang.'&anyid='.$anyid;
							if(count($met_class2[$val['id']]))$val['conturl']="?anyid={$anyid}&lang={$lang}&module=1&class1={$val['id']}";
							$contentlist[] = $val;
						}
					}
				break;
			}
		}
	}else{
		foreach($met_class1 as $key=>$val){
			if($val['module']==1){
				$md1[]=$val;
			}
		}
		$contentlist[1]['name']=$lang_modulemanagement1;
		$contentlist[1]['module']='1';
		$contentlist[1]['conturl']="about/index.php?module=1&lang=$lang&anyid=$anyid";

		$contentlist[2]['name']=$lang_modulemanagement2;
		$contentlist[2]['module']='2';
		$contentlist[2]['conturl']="article/index.php?module=2&lang=$lang&anyid=$anyid";
		$contentlist[2]['url']="article/content.php?action=add&lang=$lang&anyid=$anyid";	
		$contentlist[2]['set']="<div>
			<p class='lt'><a href='{$contentlist[2][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[2][conturl]}'>{$lang_manager}</a></p>
			</div>";
		
		$contentlist[3]['name']=$lang_modulemanagement3;
		$contentlist[3]['module']='3';
		$contentlist[3]['conturl']="product/index.php?module=3&lang=$lang&anyid=$anyid";
		$contentlist[3]['url']="product/content.php?action=add&lang=$lang&anyid=$anyid";	
		$contentlist[3]['set']="<div>
			<p class='lt'><a href='{$contentlist[3][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[3][conturl]}'>{$lang_manager}</a></p>
			</div>";
		
		$contentlist[4]['name']=$lang_modulemanagement4;
		$contentlist[4]['module']='4';
		$contentlist[4]['conturl']="download/index.php?module=4&lang=$lang&anyid=$anyid";
		$contentlist[4]['url']="download/content.php?action=add&lang=$lang&anyid=$anyid";	
		$contentlist[4]['set']="<div>
			<p class='lt'><a href='{$contentlist[4][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[4][conturl]}'>{$lang_manager}</a></p>
			</div>";
		
		$contentlist[5]['name']=$lang_modulemanagement5;
		$contentlist[5]['module']='5';
		$contentlist[5]['conturl']="img/index.php?module=5&lang=$lang&anyid=$anyid";
		$contentlist[5]['url']="img/content.php?action=add&lang=$lang&anyid=$anyid";	
		$contentlist[5]['set']="<div>
			<p class='lt'><a href='{$contentlist[5][url]}'>{$lang_addinfo}</a></p><span>-</span><p class='rt'><a href='{$contentlist[5][conturl]}'>{$lang_manager}</a></p>
			</div>";
		
		$contentlist[6]['name']=$lang_modulemanagement6;
		$contentlist[6]['module']='6';
		$contentlist[6]['conturl']="job/index.php?class1={$met_classindex[6][0][id]}&lang={$lang}&anyid={$anyid}";
		$contentlist[6]['cvurl']="job/cv.php?class1={$met_classindex[6][0][id]}&lang={$lang}&anyid={$anyid}";
		$contentlist[6]['set']="<div>
			<p class='lt'><a href='{$contentlist[6]['conturl']}'>{$lang_manager}</a></p><span>-</span>
			<p class='rt'><a href='{$contentlist[6]['cvurl']}'>{$lang_cveditorTitle}</a></p>
			</div>
			";
		$contentlist[7]['name']=$lang_modulemanagement7;
		$contentlist[7]['module']='7';
		$contentlist[7]['conturl']="message/index.php?class1={$met_classindex[7][0][id]}&lang={$lang}&anyid={$anyid}";

		$contentlist[8]['name']=$lang_modulemanagement8;
		$contentlist[8]['module']='8';
		$contentlist[8]['conturl']="feedback/index.php?class1={$met_classindex[8][0][id]}&lang={$lang}&anyid={$anyid}";
	}
}
include template('content/content');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>