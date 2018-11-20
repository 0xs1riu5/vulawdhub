<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：cache.fun.php
 * $author：lucks
 */
 if(!defined('IN_BLUE'))
 {
 	die('Access Denied!');
 }

 //更新缓存
function update_data_cache()
{
	global $db;
 	
 	//站点设置缓存
 	$config = $db->getall("SELECT * FROM ".table('config'));

 	if(is_array($config))
 	{
 		foreach($config as $k => $v)
 		{
 			$config_arr[$v[name]] = $v[value];
 		}
 	}
 	write_static_cache('config.cache', deep_stripslashes($config_arr));
 	
 	$model_list = $db->getall("SELECT * FROM ".table('model'));
	if(is_array($model_list))
	{
		foreach($model_list as $k => $v)
		{
		    $cache_arr[$v['model_id']]['must'] = insert_must_att($v['model_id']);
		    $cache_arr[$v['model_id']]['nomust'] = insert_nomust_att($v['model_id']);
		}
	}
 	write_static_cache('model', $cache_arr);
 	//顶级栏目
 	$cat_list_0 = cat_nav();
 	for($i=0;$i<count($cat_list_0);$i++)
 	{
 		$cat_list_0[$i]['url'] = url_rewrite('category', array('cid'=>$cat_list_0[$i]['cat_id']));
 	}
 	write_static_cache('cat_list_0', $cat_list_0);
 	//子级栏目
	if($cat_list_0)
	{
		reset($cat_list_0);
		foreach($cat_list_0 as $k => $v)
		{
			$cat_list = $db->getall("SELECT cat_id, cat_name FROM ".table('category')." WHERE parentid = '$v[cat_id]'");
			if(is_array($cat_list))
			{
				foreach($cat_list as $k1 => $v1){
					$v1['url'] = url_rewrite('category', array('cid'=>$v1['cat_id']));
					$cat_list_1[$v['cat_id']][] = $v1;
				}
			}
 		}
	}
 	write_static_cache('cat_list_1', $cat_list_1);

	//新闻分类
	$arc_cat_list = $db->getall("SELECT cat_id, cat_name FROM ".table('arc_cat')." WHERE parent_id=0 ORDER BY show_order");
	if(is_array($arc_cat_list))
	{
		foreach($arc_cat_list as $k=>$v)
		{
			$arc_cat_list[$k]['url'] = url_rewrite('news_cat', array('cid'=>$v['cat_id']));
		}
	}
	write_static_cache('arc_cat_list', $arc_cat_list);
 	//栏目option
 	write_static_cache('cat_option0', get_option(1));
 	//顶级option
 	write_static_cache('cat_option1', get_option(0));

 	//地区列表
 	$cat_arr = $db->getall("SELECT cat_id FROM ".table('category'));
	if(is_array($cat_arr))
	{
		foreach($cat_arr as $k => $v)
		{
 		    $area_list[$v['cat_id']] = get_area_list($v['cat_id']);
 		}
	}
 	write_static_cache('area_list', $area_list);

	//顶部自定义导航
	write_static_cache('add_nav', add_nav_list());

 	//底部导航
 	$bot_nav = bot_nav();
 	write_static_cache('bot_nav', $bot_nav);

 	//首页公告
 	write_static_cache('ann', get_ann(0, 5));

 	//首页推荐信息
 	write_static_cache('index_rec', get_rec_info(8));

 	//首页电话广告位
 	write_static_cache('phone_ad', ad_phone_list());

 	//首页友情链接
 	write_static_cache('friend_link_text', $db->getall("SELECT * FROM ".table('link')." WHERE linklogo = '' ORDER BY showorder"));
 	write_static_cache('friend_link_img', $db->getall("SELECT * FROM ".table('link')." WHERE linklogo <> '' ORDER BY showorder"));

}

 //更新模板缓存
function update_tpl_cache()
{
 	global $smarty;
 	$smarty->clear_all_cache();
}

function write_to_file($file, $content)
{
 	if(file_exists($file))
 	{
 		showmsg('写文件错误，目标文件不存在');
 	}
 	if(is_writable($file))
 	{
 		showmsg('目标文件不可写');
 	}
	if (!$fp = @fopen($file, 'w'))
	{
        showmsg("不能打开文件 $file");
    }
    if (@fwrite($fp, $file) === FALSE)
    {
       showmsg("不能写入到文件 $filename");
    }
    @fclose($fp);
}

 //写入缓存文件
function write_static_cache($cache_name, $caches)
{
    $cache_file_path = BLUE_ROOT . 'data/' . $cache_name . '.php';
    $content = "<?php\r\n";
    $content .= "\$data = " . var_export($caches, true) . ";\r\n";
    $content .= "?>";
	$fp = @fopen($cache_file_path, 'wb+');
	if (!$fp)
	{
		showmsg('打开缓存文件失败');
	}
	if (!@fwrite($fp, $content))
	{
		showmsg('写入缓存文件失败');
	}
	@fclose($fp);
}

 //读缓存文件
function read_static_cache($cache_name)
{
    static $result_arr = array();
    if (!empty($result_arr[$cache_name]))
    {
        return $result_arr[$cache_name];
    }
    $cache_file_path = BLUE_ROOT . 'data/' . $cache_name . '.php';
    if (file_exists($cache_file_path))
    {
        include_once($cache_file_path);
        $result_arr[$cache_name] = $data;
        return $result_arr[$cache_name];
    }
    else
    {
        return false;
    }
}


?>
