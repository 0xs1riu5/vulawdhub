<?php
!defined('EMLOG_ROOT') && exit('access deined!');
emLoadJQuery();
$album = isset($_GET['album']) ? intval($_GET['album']) : '';
global $CACHE;
$navis = $CACHE->readCache('navi');
$kl_album_title = '';
$kl_album_hide = true;
foreach($navis as $navi){
	if($navi['url'] == '?plugin=kl_album' && $navi['isdefault'] == 'y'){
		$kl_album_title = $navi['naviname'];
		$kl_album_hide = false;
		break;
	}
}
$options_cache = $CACHE->readCache('options');
$kl_album_config = unserialize($options_cache['kl_album_config']);
$DB = MySql::getInstance();
$blogname = $options_cache['blogname'];
$bloginfo = $options_cache['bloginfo'];
$site_title = $options_cache['blogname'];
$site_description = $options_cache['bloginfo'];
$site_key = $options_cache['site_key'];
$comments = array('commentStacks'=>array(), 'commentPageUrl'=>'');
$ckname = $ckmail = $ckurl = $verifyCode = false;
$icp = $options_cache['icp'];
$footer_info = $options_cache['footer_info'];
$allow_remark = 'n';
$log_content = $log_title = $logid = $content_info = '';
$site_title = empty($kl_album_title) ? $site_title : $kl_album_title.' - '.$site_title;
$log_title = $kl_album_title;

$kl_album_info = $options_cache['kl_album_info'];
$kl_album_info = unserialize($kl_album_info);
if(is_array($kl_album_info)) krsort($kl_album_info);
if($kl_album_config !== false){
	if($kl_album_hide && !empty($kl_album_config['disabled']) && $kl_album_config['disabled'] == 'y') emMsg('不存在的页面！');
	if(!empty($kl_album_config['key'])) $site_key = $kl_album_config['key'];
	if(!empty($kl_album_config['description'])) $site_description = $kl_album_config['description'];
	//显示相册列表
	if($album === ''){
		if(empty($kl_album_info)){
			$log_content = "还没有创建相册！";
		}else{
			$query1 = $DB->query("select a.* from (select album, addtime, id from ".DB_PREFIX."kl_album order by album, addtime desc, id desc) a group by album");
			$new_str_arr = array();
			while($row1 = $DB->fetch_array($query1)){
				$new_str_arr[$row1['album']] = time() - $row1['addtime'] < 3600*24*15 ? ' background:url(./content/plugins/kl_album/images/new.gif) no-repeat;' : '';
			}
			$log_content .=	'<div id="kl_album_list"><ul style="list-style: none; font-size:12px;color: #666666;float:left;margin:3px;padding:0px;text-align:center;">';
			foreach ($kl_album_info as $val){
				if($val['name'] == '') continue;
				if(ROLE != 'admin'){
					if($val['restrict'] == 'private') continue;
				}
				if($val['restrict'] == 'private'){
					$coverPath = 'images/only_me.jpg';
				}else{
					if(isset($val['head']) && $val['head'] != 0){
						$iquery = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE id={$val['head']}");
						if($DB->num_rows($iquery) > 0){
							$irow = $DB->fetch_row($iquery);
							$coverPath = substr($irow[2], strpos($irow[2], 'upload/'), strlen($irow[2])-strpos($irow[2], 'upload/'));
						}else{
							$coverPath = 'images/no_cover_s.jpg';
						}
					}else{
						$iquery = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE album={$val['addtime']}");
						if($DB->num_rows($iquery) > 0){
							$irow = $DB->fetch_array($iquery);
							$coverPath = substr($irow['filename'], strpos($irow['filename'], 'upload/'), strlen($irow['filename'])-strpos($irow['filename'], 'upload/'));
						}else{
							$coverPath = 'images/no_cover_s.jpg';
						}
					}
				}
				$log_content .=	'
<li style="display:inline; width:130px; height:180px; float:left; margin: 5px 5px 5px; border:1px solid #ccc;padding:0px;">
<table border="0" style="border:0px solid #CCC;width:130px;">
<tr><td style="border:0px solid #CECECE; height:125px; vertical-align:middle; text-align:center; padding:0px;'.$new_str_arr[$val['addtime']].'"><a href="./?plugin=kl_album&album='.$val['addtime'].'" title="'.$val['description'].'"><img style="border:0px; padding:5px 5px 5px;" src="./content/plugins/kl_album/'.$coverPath.'"></a></td></tr>
<tr><td style="border:0px; height:20px;padding:0px; vertical-align:middle; text-align:center;">'.$val['name'].'</td></tr>
</table>
</li>';
			}
			$log_content .=	'</ul></div>';
		}

		addAction('index_head', 'kl_album_show_js');
		$allow_remark = 'n';
		$logid = '';

		include View::getView('header');
		include View::getView('page');
	}
	//显示单个相册里的照片
	if($album !== ''){
		$log_title = '';
		$exist_album = false;
		if(is_array($kl_album_info)){
			foreach ($kl_album_info as $val){
				if($val['addtime'] == $album){
					$albumrestrict = $val['restrict'];
					$albumname = $val['name'];
					$albumpwd = isset($val['pwd']) ? $val['pwd'] : '';
					$exist_album = true;
				}
			}
			if($exist_album === false || ($albumrestrict == 'private' && ROLE != 'admin')){
				$log_content .= '不存在的相册';
			}else{
				if($albumrestrict == 'protect' && ROLE != 'admin'){
					$postpwd = isset($_POST['albumpwd']) ? addslashes(trim($_POST['albumpwd'])) : '';
					$cookiepwd = isset($_COOKIE['kl_albumpwd_'.$album]) ? addslashes(trim($_COOKIE['kl_albumpwd_'.$album])) : '';
					kl_album_AuthPassword($postpwd, $cookiepwd, $albumpwd, $album, BLOG_URL.'?plugin=kl_album', 'kl_albumpwd_');
				}
				$kl_album = Option::get('kl_album_'.$album);
				if(is_null($kl_album)){
					$condition = " and album={$album} order by id desc";
				}else{
					$idStr = empty($kl_album) ? 0 : $kl_album;
					$condition = " and id in({$idStr}) order by substring_index('{$idStr}', id, 1)";
				}
				$query = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE 1 {$condition}");
				$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
				$page_all_no = $DB->num_rows($query);
				$page_num = isset($kl_album_config['num_rows']) ? $kl_album_config['num_rows'] : 20;
				$pageurl =  pagination($page_all_no, $page_num, $page, './?plugin=kl_album&album='.$album.'&page=');
				$start_num = !empty($page) ? ($page - 1) * $page_num : 0;
				$query = $DB->query("SELECT * FROM ".DB_PREFIX."kl_album WHERE 1 {$condition} LIMIT $start_num, $page_num");
				$photos = array();
				$log_content .= '
<div style="text-align:left; font-size:12px; padding:0px 20px;"> <a href="./?plugin=kl_album">&laquo;返回相册列表</a></div>
<div id="kl_album_photo_list" style="height:auto!important;min-height:150px;height:150px;margin-bottom:20px;"><ul style="list-style: none; font-size:12px;color: #666666;float:left;margin:5px; padding:0px; text-align:center;">';
				while($photo = $DB->fetch_array($query)){
					$log_content .=	'
<li style=" border:1px solid #CCC;display:inline; width:110px; height:120px; float:left; padding:5px; margin:10px 5px 5px;">
<a href="'.str_replace('thum-', '', substr($photo['filename'], 1, strlen($photo['filename']))).'" title="相片名称：'.$photo['truename'].'　相片描述：'.$photo['description'].'">
<img style="border:0px; padding:5px 5px 5px;" src="'.substr($photo['filename'], 1, strlen($photo['filename'])).'" /></a>
</li>';
				}
				$log_content .= '</ul></div><div id="pagenavi">'.$pageurl.'<span>(共有'.$page_all_no.'张相片)</span></div>';
			}
		}else{
			$log_content .= '参数错误。';
		}

		$allow_remark = 'n';
		$logid = '';

		addAction('index_head', 'kl_album_show_js');

		include View::getView('header');
		include View::getView('page');
	}
}else{
	emMsg('不存在的页面！');
}

function kl_album_show_js(){
	$active_plugins = Option::get('active_plugins');
	echo '<script type="text/javascript" src="./content/plugins/kl_album/js/jquery.lazyload.mini.js"></script>
<script type="text/javascript" src="./content/plugins/kl_album/js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="./content/plugins/kl_album/css/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript">
jQuery(function($){
$(\'img\').lazyload({effect:\'fadeIn\',placeholder:\'./content/plugins/kl_album/images/grey.gif\',threshold:200});
$(\'#kl_album_photo_list a\').lightBox();
$(\'#kl_album_list img, #kl_album_photo_list img\').mouseover(function(){ $(this).css(\'border\', \'1px solid green\')});
$(\'#kl_album_list img, #kl_album_photo_list img\').mouseout(function(){ $(this).css(\'border\', \'0px\')});
});
</script>';
}
?>