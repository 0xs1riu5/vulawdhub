<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
$menus = array (
    array('更新数据', '?file='.$file),
    array('网站首页', DT_PATH, ' target="_blank"'),
);
switch($action) {
	case 'cache':
		cache_clear_tag(1);
		//cache_clear_sql(0);
		cache_clear('php', 'dir', 'tpl');
		cache_clear('cat');
		cache_category();
		cache_clear('area');
		cache_area();
		msg('缓存更新成功', '?file='.$file.'&action=module');
	break;
	case 'all':
		dmsg('全站更新成功', '?file='.$file);
	break;
	case 'index':
		tohtml('index');
		msg('网站首页生成成功', '?file='.$file.'&action=all');
	break;
	case 'back':
		$moduleids = 0;
		unset($MODULE[1]);
		unset($MODULE[2]);
		$KEYS = array_keys($MODULE);
		foreach($KEYS as $k => $v) {
			if($v == $mid) { $moduleids = $k; break; }
		}
		msg('['.$MODULE[$mid]['name'].'] 更新成功', '?file='.$file.'&action=module&moduleids='.($moduleids+1));
	break;
	case 'module':
		if(isset($moduleids)) {
			unset($MODULE[1]);
			unset($MODULE[2]);
			$KEYS = array_keys($MODULE);
			if(isset($KEYS[$moduleids])) {
				$bmoduleid = $moduleid = $KEYS[$moduleids];
				if(is_file(DT_ROOT.'/module/'.$MODULE[$moduleid]['module'].'/admin/html.inc.php')) {	
					msg('', '?moduleid='.$moduleid.'&file='.$file.'&action=all&one=1');
				} else {
					msg('['.$MODULE[$bmoduleid]['name'].'] 更新成功', '?file='.$file.'&action='.$action.'&moduleids='.($moduleids+1));
				}
			} else {
				msg('模块更新成功', '?file='.$file.'&action=index');
			}		
		} else {
			$moduleids = 0;
			msg('开始更新模块', '?file='.$file.'&action='.$action.'&moduleids='.$moduleids);
		}
	break;
	case 'start':
		msg('正在开始更新全站', '?file='.$file.'&action=cache');
	break;
	case 'cacheclear':
		if($CFG['cache'] == 'file') dheader('?file='.$file.'&action=fileclear');
		$dc->clear();
		msg('缓存更新成功', '?file='.$file);
	break;
	case 'fileclear':
		$job = 'php';
		if(isset($dir)) {
			isset($cf) or $cf = 0;
			isset($cd) or $cd = 0;
			if(preg_match("/^".$job."[0-9]{14}$/", $dir)) {
				$dirs = glob(DT_CACHE.'/'.$dir.'/*');
				if($dirs) {
					$sub = $dirs[array_rand($dirs)];
					file_del($sub.'/index.html');
					$files = glob($sub.'/*.php');
					if($files) {
						$i = 0;
						foreach($files as $f) {
							file_del($f);
							$cf++;
							$i++;
							if($i > 500) msg('已删除 '.$cd.' 个目录，'.$cf.' 个文件'.progress(0, $cd, $tt), '?file='.$file.'&action='.$action.'&dir='.$dir.'&cd='.$cd.'&cf='.$cf.'&job='.$job.'&tt='.$tt, 0);
						}
						dir_delete($sub);
						$cd++;
						msg('已删除 '.$cd.' 个目录，'.$cf.' 个文件'.progress(0, $cd, $tt), '?file='.$file.'&action='.$action.'&dir='.$dir.'&cd='.$cd.'&cf='.$cf.'&job='.$job.'&tt='.$tt, 0);
					} else {
						dir_delete($sub);
						$cd++;
						msg('已删除 '.$cd.' 个目录，'.$cf.' 个文件'.progress(0, $cd, $tt), '?file='.$file.'&action='.$action.'&dir='.$dir.'&cd='.$cd.'&cf='.$cf.'&job='.$job.'&tt='.$tt, 0);
					}
				} else {
					dir_delete(DT_CACHE.'/'.$dir);
					msg('缓存更新成功', '?file='.$file);
				}
			} else {
				msg('目录名错误');
			}
		} else {
			$dir = $job.timetodate($DT_TIME, 'YmdHis');
			if(rename(DT_CACHE.'/'.$job, DT_CACHE.'/'.$dir)) {
				dir_create(DT_CACHE.'/'.$job);
				file_del(DT_CACHE.'/'.$dir.'/index.html');
				$dirs = glob(DT_CACHE.'/'.$dir.'/*');
				$tt = count($dirs);
				msg('正在更新，此操作可能用时较长，请不要中断..', '?file='.$file.'&action='.$action.'&dir='.$dir.'&job='.$job.'&tt='.$tt);
			} else {
				msg('更新失败');
			}
		}
	break;
	case 'homepage':
		cache_clear_tag(1);
		$db->expires = $CFG['db_expires'] = 0;
		tohtml('index');
		$filename = $CFG['com_dir'] ? DT_ROOT.'/'.$DT['index'].'.'.$DT['file_ext'] : DT_CACHE.'/index.inc.html';
		msg('网站首页生成成功 '.(is_file($filename) ? dround(filesize($filename)/1024).'Kb ' : ''), '?file='.$file);
	break;
	case 'template':
		cache_clear_tag(1);
		cache_clear('php', 'dir', 'tpl');
		msg('模板缓存更新成功', '?file='.$file);
	break;
	case 'caches':
		isset($step) or $step = 0;
		if($step == 1) {
			cache_clear('module');
			cache_module();
			msg('系统设置更新成功', '?file='.$file.'&action='.$action.'&step='.($step+1));
		} else if($step == 2) {
			cache_clear_tag(1);
			msg('标签调用缓存更新成功', '?file='.$file.'&action='.$action.'&step='.($step+1));
		} else if($step == 3) {
			cache_clear('php', 'dir', 'tpl');
			msg('模板缓存更新成功', '?file='.$file.'&action='.$action.'&step='.($step+1));
		} else if($step == 4) {
			cache_clear('cat');
			cache_category();
			msg('分类缓存更新成功', '?file='.$file.'&action='.$action.'&step='.($step+1));
		} else if($step == 5) {
			cache_clear('area');
			cache_area();
			msg('地区缓存更新成功', '?file='.$file.'&action='.$action.'&step='.($step+1));
		} else if($step == 6) {
			cache_clear('fields');
			cache_fields();
			cache_clear('option');
			msg('自定义字段更新成功', '?file='.$file.'&action='.$action.'&step='.($step+1));
		} else if($step == 7) {
			cache_clear_ad();
			tohtml('index');
			msg('全部缓存更新成功', '?file='.$file);
		} else {
			cache_clear('group');
			cache_group();
			cache_clear('type');
			cache_type();
			cache_clear('keylink');
			cache_keylink();
			cache_pay();
			cache_weixin();
			cache_banip();
			cache_banword();
			cache_bancomment();
			msg('正在开始更新缓存', '?file='.$file.'&action='.$action.'&step='.($step+1));
		}
	break;
	default:
		include tpl('html');
	break;
}
?>