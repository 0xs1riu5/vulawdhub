<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：common.fun.php
 * $author：lucks
 */
if(!defined('IN_BLUE'))
{
	die('Access Denied!');
}

function deep_addslashes($str)
{
	if(is_array($str))
	{
		foreach($str as $key=>$val)
		{
			$str[$key] = deep_addslashes($val);
		}
	}
	else
	{
		$str = addslashes($str);
	}
	return $str;
}

function deep_stripslashes($str)
{
 	if(is_array($str))
 	{
 		foreach($str as $key => $val)
 		{
 			$str[$key] = deep_stripslashes($val);
 		}
 	}
 	else
 	{
 		$str = stripslashes($str);
 	}
 	return $str;
}

function deep_htmlspecialchars($str)
{
 	if(is_array($str))
 	{
		foreach($str as $key => $val)
		{
			$str[$key] =  deep_htmlspecialchars($val);
		}
	}
	else
	{
		$str = htmlspecialchars($str);
	}
	return $str;
}

 /**
  *
  * 为数据表添加前缀
  *
  */
function table($table)
{
	global $pre;
	return  $pre .$table ;
}

if (!function_exists('file_put_contents'))
{
	function file_put_contents($file, $data, $flag = '')
	{
		$mode = $flag == 'FILE_APPEND' ? 'ab' : 'wb';
		$fp = @fopen($file, $mode) or die('can not open file '.$file);
		flock($fp, LOCK_EX);
		$len = @fwrite($fp, $data);
		flock($fp, LOCK_UN);
		@fclose($fp);
		return $len;
	}
}

function showmsg($msg,$gourl='goback', $is_write = false)
{
 	global $smarty;
 	$smarty->caching = false;
 	$smarty->assign("msg",$msg);
 	$smarty->assign("gourl",$gourl);
 	$smarty->display("showmsg.htm");
 	if($is_write)
 	{
 		write_log($msg, $_SESSION['admin_name']);
 	}
 	exit();
}

/**
  *
  * 获取用户IP
  *
  */
function getip()
{
	if (getenv('HTTP_CLIENT_IP'))
	{
		$ip = getenv('HTTP_CLIENT_IP'); 
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR')) 
	{ //获取客户端用代理服务器访问时的真实ip 地址
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_X_FORWARDED')) 
	{ 
		$ip = getenv('HTTP_X_FORWARDED');
	}
	elseif (getenv('HTTP_FORWARDED_FOR'))
	{
		$ip = getenv('HTTP_FORWARDED_FOR'); 
	}
	elseif (getenv('HTTP_FORWARDED'))
	{
		$ip = getenv('HTTP_FORWARDED');
	}
	else
	{ 
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function do_task($name)
{
	 global $db, $timestamp;
	 $task = $db->getone("SELECT * FROM ".table('task')." WHERE name='$name'");
	 if ($task)
	 {
		 if ($task['last_time'] + $task['exp']*24*3600 < $timestamp)
		 {
			 @include BLUE_ROOT.'include/task/'.$name.'.php';
			 $db->query("UPDATE ".table('task')." SET last_time=".$timestamp." WHERE name='$name'");
		 }
	 }
}

function get_config()
{
 	global $db;
 	$config_arr = array();
 	$config_arr = read_static_cache('config.cache');
 	if($config_arr === false)
 	{
 		$sql = "SELECT * FROM ".table('config');
 		$arr = $db->getall($sql);
 		foreach($arr as $key=> $val)
 		{
 			$config_arr[$val['name']] = $val['value'];
 		}
 		write_static_cache('config.cache', $config_arr);
 	}
 	return $config_arr;
}

function get_bannedip()
{
	global $db, $timestamp;
	$bannedip = array();
	$bannedip = read_static_cache('bannedip.cache');
	if($bannedip === false)
	{
		$result = $db->query("SELECT ip FROM ".table('ipbanned')." WHERE add_time+exp*24*3600>$timestamp");
		while ($row = $db->fetch_array($result))
		{
			$bannedip[] = $row['ip'];
		}
		write_static_cache('bannedip.cache', $bannedip);
	 } 
	 return $bannedip;
}

/**
  *
  *取得所有分类下拉列表
  *
  *@param $lever 级别
  *
  *@param $selected 选中项
  *
  *@param $current 选中项，列表中不在显示
  *
  */
function get_option($lever, $selected='', $current='')
{
	global $db;
	$option = '';
	if($lever == 0)
	{
		$sql = "SELECT cat_id, cat_name FROM ".table('category')." WHERE parentid=0 ORDER BY show_order,cat_id";
		$result = $db->query($sql);
		while($row = $db->fetch_array($result))
		{
			if($row[cat_id] == $current)
			{
				continue;
			}
			$option .="<option value=\"".$row[cat_id]."\"";
			if($row[cat_id] == $selected)
			{
				$option .= " selected = \"selected\"";
			}
			$option .= ">$row[cat_name]</option>";
		}
	}
	else
	{
		$sql = "SELECT cat_id, cat_name FROM ".table('category')." WHERE parentid=0 ORDER BY show_order, cat_id";
		$result = $db->query($sql);
		while($row = $db->fetch_array($result))
		{
			$option .="<option value=\"".$row[catid]."\"";
			if($row[cat_id] == $selected)
			{
				$option .= " selected = \"selected\"";
			}
			$option .= ">$row[cat_name]</option>";
			$option .= get_child($row[cat_id], $selected);
		}
	}
	return $option;
}

function get_child($parentid, $selected = '')
{
	global $db;
	if($parentid == '')
	{
		echo 'function get_option param error';
	}
	$sql = "SELECT cat_id, cat_name, cat_indent FROM ".table('category')." WHERE parentid=".$parentid." ORDER BY show_order,cat_id";
	$result = $db->query($sql);
	while($row = $db->fetch_array($result))
	{
		$child_option .="<option value=\"".$row[cat_id]."\"";
		if($row[cat_id] == $selected)
		{
			$child_option .= " selected = \"selected\">";
		}
		else
		{
			$child_option .=">";
		}
		for($i=0;$i<$row[cat_indent];$i++)
		{
			$child_option .="&nbsp;&nbsp;";
		}
		$child_option .= $row[cat_name]."</option>";
	}
	return $child_option;
}


function get_area_option($level, $selected='', $current='')
{
	global $db;
	$option = '';
	if($level == 0)
	{
		$sql = "SELECT area_id, area_name FROM ".table('area')." WHERE parentid=0 ORDER BY show_order, area_id";
		$result = $db->query($sql);
		while($row = $db->fetch_array($result))
		{
			if($row[area_id] == $current)
			{
				continue;
			}
			$option .="<option value=\"".$row[area_id]."\"";
			if($row[area_id] == $selected)
			{
				$option .= " selected = \"selected\"";
			}
			$option .= ">$row[area_name]</option>";
		}
	}
	else
	{
		$sql = "SELECT area_id, area_name FROM ".table('area')." WHERE parentid=0 ORDER BY show_order, area_id";
		$result = $db->query($sql);
		while($row = $db->fetch_array($result))
		{
			$option .="<option value=\"".$row[area_id]."\"";
			if($row[area_id] == $selected)
			{
				$option .= " selected = \"selected\"";
			}
			$option .= ">$row[area_name]</option>";
			$option .= get_area_child($row[area_id], $selected);
		}
	}
	return $option;
}

function get_area_child($parentid, $selected='')
{
	global $db;
	if($parentid == '')
	{
		echo 'function get_option param error';
	}
	$sql = "SELECT area_id, area_name, area_indent FROM ".table('area')." WHERE parentid=".$parentid." ORDER BY show_order,area_id";
	$result = $db->query($sql);
	while($row = $db->fetch_array($result))
	{
		$child_option .="<option value=\"".$row[area_id]."\"";
		if($row[area_id] == $selected)
		{
			$child_option .= " selected = \"selected\">";
		}
		else
		{
			$child_option .=">";
		}
		for($i=0;$i<$row[area_indent];$i++)
		{
			$child_option .="&nbsp;&nbsp;";
		}
		$child_option .= $row[area_name]."</option>";
	}
	return $child_option;
}

function get_area_parentid($area_id)
{
 	global $db;
 	$area = $db->getone("SELECT parentid FROM ".table('area')." WHERE area_id=".$area_id);
 	return $area['parentid'];
}

/**
  * 为模板赋值
  */
function template_assign($val1='', $val2='')
{
 	global $smarty, $_CFG;
 	if(is_array($val1) && is_array($val2))
 	{
 		if(count($val1) != count($val2))
 		{
 			showmsg('数组变量不一致');
 		}
 		foreach($val1 as $key => $val)
 		{
 			$smarty->assign($val1[$key],$val2[$key]);
 		}
 	}
 	else
 	{
 		$smarty->assign($val1, $val2);
 	}
 	$smarty->assign('charset', BLUE_CHARSET);
 	$smarty->assign('site_name', $_CFG['site_name']);
 	$smarty->assign('site_url', $_CFG['site_url']);
 	$smarty->assign('right', $_CFG['right']);
 	$smarty->assign('icp', $_CFG['icp']);
 	$smarty->assign('count', $_CFG['count']);
 	$smarty->assign('version', BLUE_VERSION);
}

/**
  * 取得分类的模型
  */
function get_model_id($cid)
{
 	global $db;
 	$cat = $db->getone("SELECT model FROM ".table('category')." WHERE cat_id='$cid'");
 	return $cat['model'];
}


function insert_must_att($model_id, $value=false, $post_id = '')
{
 	global $db;
 	$html = '';
	$sql = "SELECT att_id, modelid, att_name, att_type, unit, att_val FROM ".table('attachment')." WHERE modelid='$model_id' and is_required = 1 ORDER BY show_order,att_id";
	$result = $db->query($sql);
	while($row = $db->fetch_array($result))
	{
		if($row['att_type'] == 0)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><input type=\"text\" name=\"att1[".$row['att_id']."]\" class=\"inputbox\" value=\"".$row1['value']."\" />&nbsp;".$row['unit']."</td></tr>";
		}
		elseif($row['att_type'] == 1)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><input type=\"text\" name=\"att1[".$row['att_id']."]\" class=\"inputbox\" value=\"".$row1['value']."\" />&nbsp;".$row['unit']."</td></tr>";
		}
		elseif($row['att_type'] == 2)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			if(empty($row['att_val'])) return false;
			$att_val = explode('|', $row['att_val']);
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><select name=\"att1[".$row['att_id']."]\" class=\"inputbox\" style=\"height:24px\">";
			foreach($att_val as $v)
			{
				if($v == $row1['value'])
				{
					$html .="<option value=\"$v\" selected=\"selected\">".$v."</option>";
				}
				else
				{
					$html .="<option value=\"$v\">".$v."</option>";
				}
			}
			$html .="</select></td></tr>";
		}
		elseif($row['att_type'] == 3)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			if(empty($row['att_val'])) return false;
			$att_val = explode('|', $row['att_val']);
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><input type=\"hidden\" name=\"att1[".$row['att_id']."][]\" value=\"\" />";
			foreach($att_val as $v)
			{
				if($v == $row1['value'])
				{
					$html .="<input type=\"radio\" name=\"att1[".$row['att_id']."]\" value=\"".$v."\" id=\"att".$row['att_id']."\" checked/><label for=\"att".$row['att_id']."\">".$v."</label>";
				}
				else
				{
					$html .="<input type=\"radio\" name=\"att1[".$row['att_id']."]\" value=\"".$v."\" id=\"att".$row['att_id']."\"/><label for=\"att".$row['att_id']."\">".$v."</label>";
				}
			}
			$html .="</td></tr>";
		}
		elseif($row['att_type'] == 4)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			$att_arr = explode(',', $row1['value']);
			if(empty($row['att_val'])) return false;
			$att_val = explode('|', $row['att_val']);
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><input type=\"hidden\" name=\"att1[".$row['att_id']."][]\" value=\"\" />";
			foreach($att_val as $k => $v)
			{
				if(in_array($v, $att_arr))
				{
					$html .="<span><input type=\"checkbox\" name=\"att1[".$row['att_id']."][]\" value=\"".$v."\" id=\"att".$row['att_id'].$k."\" checked/><label for=\"att".$row['att_id'].$k."\">".$v."</label></span>";
				}
				else
				{
					$html .="<span><input type=\"checkbox\" name=\"att1[".$row['att_id']."][]\" value=\"".$v."\" id=\"att".$row['att_id'].$k."\"/><label for=\"att".$row['att_id'].$k."\">".$v."</label></span>";
				}
			}
			$html .="</td></tr>";
		}
	}
	return $html;
}

/**
  * 取得附加属性类型
  */
function get_att_type($att_id)
{
 	global $db;
 	$sql = "SELECT att_type FROM ".table('attachment')." WHERE att_id='$att_id'";
 	$att = $db->getone($sql);
 	return $att[att_type];
}

/**
  * 取得附加属性名称
  */
function get_att_name($att_id)
{
	global $db;
 	$sql = "SELECT att_name FROM ".table('attachment')." WHERE att_id='$att_id'";
 	$att = $db->getone($sql);
 	return $att[att_name];
}

/**
  * 过滤表单提交的附加属性
  */
function get_att($model_id, $arr, $type='')
{
	global $db;
	$new_arr = array();
	if($arr)
	{
		foreach($arr as $k=>$v)
		{
			$att_type = get_att_type($k);
			if($att_type != 4 && $att_type != 3)
			{
				if(!empty($arr[$k]))
				{
					if($att_type == 0)
					{
						$new_arr[$k] = trim($arr[$k]);
					}
					elseif($att_type == 1)
					{
						$new_arr[$k] = intval($arr[$k]);
					}
					else $new_arr[$k] = $arr[$k];
				}
				elseif($type == 'must_att')
				{
					showmsg(get_att_name($k).'不能为空');
				}
			}
			elseif($att_type == 3)
			{
				if($arr[$k][0] != '')
				{
					$new_arr[$k] = trim($arr[$k]);
				}
				elseif($type == 'must_att')
				{
					showmsg(get_att_name($k).'不能为空');
				}
			}
			elseif($att_type == 4)
			{
				if(count($arr[$k]) > 1)
				{
					for($i=0;$i<count($arr[$k]);$i++)
					{
						$new_arr[$k][] = trim($arr[$k][$i]);
					}
				}
				elseif($type == 'must_att')
				{
					showmsg(get_att_name($k).'不能为空');
				}
			}
		}
	}
	return $new_arr;
}

/**
  * 存储附加属性的值
  */
function insert_att_value($att_val, $post_id)
{
 	global $db;
 	foreach($att_val as $k=>$v)
 	{
 		if(is_array($v))
 		{
 			$v = implode(',', $v);
 			if(preg_match('/^,/', $v))
 			{
				$v = substr($v, 1);
			}
			if(preg_match('/,$/', $v)){
				echo substr($v, 0, -2);
			}
 		}
 		$sql  = "INSERT INTO ".table('post_att')." (post_id, att_id, value) VALUES ('$post_id', '$k', '$v')";
 		$db->query($sql);
 	}
}

/**
  * 将选填附加属性插入模板
  */
function insert_nomust_att($model_id, $value=false, $post_id = '')
{
 	global $db;
 	$html = '';
 	$sql = "SELECT att_id, modelid, att_name, att_type, unit, att_val FROM ".table('attachment')." WHERE modelid='$model_id' and is_required = 0 ORDER BY show_order,att_id";
 	$result = $db->query($sql);
	while($row = $db->fetch_array($result))
	{
		if($row['att_type'] == 0)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><input type=\"text\" name=\"att2[".$row['att_id']."]\" class=\"inputbox\" value=\"".$row1['value']."\" />&nbsp;".$row['unit']."</td></tr>";
		}
		elseif($row['att_type'] == 1)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><input type=\"text\" name=\"att2[".$row['att_id']."]\" class=\"inputbox\" value=\"".$row1['value']."\" />&nbsp;".$row['unit']."</td></tr>";
		}
		elseif($row['att_type'] == 2)
		{
			if($value && $post_id){
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			if(empty($row['att_val'])) return false;
			$att_val = explode('|', $row['att_val']);
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td><select name=\"att2[".$row['att_id']."]\" class=\"inputbox\" style=\"height:24px\">";
			foreach($att_val as $v)
			{
				if($v == $row1['value'])
				{
					$html .="<option value=\"$v\" selected = \"selected\">".$v."</option>";
				}
				else
				{
					$html .="<option value=\"$v\">".$v."</option>";
				}

			}
			$html .="</select></td></tr>";
		}
		elseif($row['att_type'] == 3)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			if(empty($row['att_val'])) return false;
			$att_val = explode('|', $row['att_val']);
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td>";
			foreach($att_val as $v)
			{
				if($v == $row1['value'])
				{
					$html .="<input type=\"radio\" name=\"att2[".$row['att_id']."]\" value=\"".$v."\" id=\"att".$row['att_id']."\" checked/><label for=\"att".$row['att_id']."\">".$v."</label>";
				}
				else
				{
					$html .="<input type=\"radio\" name=\"att2[".$row['att_id']."]\" value=\"".$v."\" id=\"att".$row['att_id']."\"/><label for=\"att".$row['att_id']."\">".$v."</label>";
				}

			}
			$html .="</td></tr>";
		}
		elseif($row['att_type'] == 4)
		{
			if($value && $post_id)
			{
				$row1 = $db->getone("SELECT value FROM ".table('post_att')." WHERE post_id = ".$post_id." AND att_id=".$row['att_id']);
			}
			$att_arr = explode(',', $row1['value']);
			if(empty($row['att_val'])) return false;
			$att_val = explode('|', $row['att_val']);
			$html .="<tr><td class=\"left\">".$row['att_name']."：</td><td>";
			foreach($att_val as $k => $v)
			{
				if(in_array($v, $att_arr))
				{
					$html .="<span><input type=\"checkbox\" name=\"att2[".$row['att_id']."][]\" value=\"".$v."\" id=\"att".$row['att_id'].$k."\" checked/><label for=\"att".$row['att_id'].$k."\">".$v."</label></span>";
				}
				else
				{
					$html .="<span><input type=\"checkbox\" name=\"att2[".$row['att_id']."][]\" value=\"".$v."\" id=\"att".$row['att_id'].$k."\"/><label for=\"att".$row['att_id'].$k."\">".$v."</label></span>";
				}

			}
			$html .="</td></tr>";
		}
	}
	return $html;
}

function get_cat_html()
{
 	global $db;
 	$html = '';
 	$sql1 = "SELECT cat_id, cat_name, is_havechild FROM ".table('category')." WHERE parentid = 0";
 	$result1 = $db->query($sql1);
 	while($row1 = $db->fetch_array($result1))
 	{
 		$html .= "<div class=\"cat_list\"><h3>".$row1['cat_name']."</h3>";
 		if($row1['is_havechild'])
 		{
 			$html .= "<ul>";
			$sql2 = "SELECT cat_id, cat_name FROM ".table('category')." WHERE parentid = ".$row1['cat_id'];
			$result2 = $db->query($sql2);
			while($row2 = $db->fetch_array($result2))
			{
				$html .= "<li><a href=\"publish.php?act=step2&cid=".$row2['cat_id']."\">".$row2['cat_name']."</a></li>";
			}
			$html .="</ul>";
 		}
 		$html .="</div><div class=\"clear\"></div>";
 	}
 	return $html;
}

function mb_sub($str, $sta=0, $len)
{
 	if(strlen($str)<$len*2)
		return $str;
	$str=mb_substr($str, $sta, $len, gb2312);
	return $str;
}

function url_rewrite($act, $arr)
{
 	global $_CFG;
 	$url = $id = $cid = $aid = $page_id = $ann_id = '';
    extract($arr);
 	if($act == 'category')
 	{
 		if(empty($cid))
 			return false;
 		else
 		{
 			if($_CFG['urlrewrite'])
 			{
 				$url = 'category-' . intval($cid);
 				if(!empty($aid)) $url .= '-' . intval($aid);
 				else $url .= '-'.'0';
 				if(!empty($page_id)) $url .= '-' . intval($page_id);
 				$url .= '.html';
 			}
 			else
 			{
 				$url = 'category.php?cid=' . intval($cid);
 				if(!empty($aid)) $url .= '&amp;aid=' . intval($aid);
 				if(!empty($page_id)) $url .= '&amp;page_id=' . intval($aid);
 			}
 		}
 	}
 	elseif($act == 'post')
 	{
 		if(empty($id)) return false;
 		else{
 			if($_CFG['urlrewrite'])
 			{
 				$url = 'info-' . intval($id) .'.html';
 			}
 			else
 			{
 				$url = 'info.php?id=' . intval($id);
 			}
 		}
 	}
 	elseif($act == 'news_cat')
 	{
 		if(empty($cid))
 		{
 			if($_CFG['urlrewrite'])
 			{
 				if(!empty($page_id)) $url = 'news_cat-' . intval($page_id) . '.html';
 				else $url = 'news_cat.html';
 			}
 			else
 			{
				if(!empty($page_id)) $url = 'news_cat.php?page_id='.intval($page_id);
				else $url = 'news_cat.php';
			}
 		}
 		else
 		{
 			if($_CFG['urlrewrite'])
 			{
 				$url = 'news_cat-' . intval($cid);
 				if(!empty($page_id)) $url .= '-' . intval($page_id);
				else $url .= '-1';
 				$url .= '.html';
 			}
 			else
 			{
 				$url = 'news_cat.php?cid=' . intval($cid);
 				if(!empty($page_id)) $url .= '&page_id=' . intval($page_id);
				else $url .= '&page_id=1';
 			}
 		}
 	}
 	elseif($act == 'news')
 	{
 		if(empty($id))
 		{
 			if($_CFG['urlrewrite'])
 			{
 				if(!empty($page_id)) $url = 'newslist-' . intval($page_id) . '.html';
 				else $url = 'news.html';
 			}
 			else
 			{
 				if(!empty($page_id)) $url = 'news.php?page_id=' . intval($page_id);
 				else $url = 'news.php';
 			}
 		}
 		else
 		{
 			if($_CFG['urlrewrite'])
 			{
 				if(!empty($id)) $url = 'news-' . intval($id) . '.html';
 			}
 			else
 			{
 				if(!empty($id)) $url = 'news.php?id=' . intval($id);
 			}
 		}

 	}
 	/*elseif($act == 'ann'){
 		if(empty($ann_id)){
 			if($_CFG['urlrewrite']){
 				if(!empty($page_id)) $url = 'annlist-' . intval($page_id) . '.html';
 				else $url = 'ann.html';
 			}else{
 				if(!empty($page_id)) $url = 'ann.php?page_id=' . intval($page_id);
 				else $url = 'ann.php';
 			}
 		}
 		else{
 			if($_CFG['urlrewrite']){
 				$url = 'ann-'.intval($ann_id).'.html';
 			}else{
 				$url = 'ann.php?ann_id='.intval($ann_id);
 			}
 		}
 	}*/
	elseif($act == 'info_index')
	{
		if($_CFG['urlrewrite'])
		{
			$url = 'info_index.html';
		}
		else
		{
			$url = 'info_index.php';
		}
	}
	elseif($act == 'guest_book')
	{
		if($_CFG['urlrewrite'])
		{
			$url = 'guest_book.html';
		}
		else
		{
			$url = 'guest_book.php';
		}
	}
 	elseif($act == 'company_list')
 	{
 		if(empty($cid))
 		{
 			return false;
 		}
 		else
 		{
 			if($_CFG['urlrewrite'])
 			{
				if(!empty($page_id))
				{
					$url = 'company_list-' . intval($cid) . '-' . intval($page_id) . '.html';
				}
				else
				{
 					$url = 'company_list-' . intval($cid) . '.html';
				}
 			}
 			else
 			{
				if(!empty($page_id))
				{
					$url = 'company_list.php?cid=' . intval($cid).'&page_id='.intval($page_id);
				}
				else
				{
 					$url = 'company_list.php?cid=' . intval($cid);
				}
 			}
 		}
 	}
 	elseif($act == 'view_c_detail')
 	{
 		if(empty($id))
 		{
 			return false;
 		}
 		else
 		{
 			if($_CFG['urlrewrite'])
 			{
 				$url = 'view_c_detail-' . intval($id) . '.html';
 			}
 			else
 			{
 				$url = 'view_c_detail.php?id=' . intval($id);
 			}
 		}
 	}
 	return $url;
}

function get_lit_pic($post_id)
{
 	global $db;
 	$pic = $db->getone("SELECT pic_path FROM ".table('post_pic')." WHERE post_id = ".intval($post_id));
 	return $pic['pic_path'];
}

function sub_day($endday,$staday)
{
	$value = $endday - $staday;
	if($value < 0)
	{
		return '';
	}
	elseif($value >= 0 && $value < 59)
	{
		return ($value+1)."秒前";
	}
	elseif($value >= 60 && $value < 3600)
	{
		$min = intval($value / 60);
		return $min."分钟前";
	}
	elseif($value >=3600 && $value < 86400)
	{
		$h = intval($value / 3600);
		return $h."小时前";
	}
	elseif($value >= 86400 && $value < 86400*30)
	{
		$d = intval($value / 86400);
		return $d."天前";
	}
	elseif($value >= 86400*30 && $value < 86400*30*12)
	{
		$mon  = intval($value / (86400*30));
		return $mon."个月前";
	}
	else{
		$y = intval($value / (86400*30*12));
		return $y."年前";
	}
}

function html2text($str)
{
	$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
	$alltext = "";
	$start = 1;
	for($i=0;$i<strlen($str);$i++)
	{
		if($start==0 && $str[$i]==">")
		{
			$start = 1;
		}
		else if($start==1)
		{
			if($str[$i]=="<")
			{
				$start = 0;
				$alltext .= " ";
			}
			else if(ord($str[$i])>31)
			{
				$alltext .= $str[$i];
			}
		}
	}
	$alltext = str_replace("　"," ",$alltext);
	$alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
	$alltext = preg_replace("/[ ]+/s"," ",$alltext);
	return $alltext;
}

function filter_data($str)
{
	$str = preg_replace("/<(\/?)(script|i?frame|meta|link)(\s*)[^<]*>/", "", $str);
	return $str;
}

function create_editor($name, $value = '', $config = '')
{
	global $smarty;
	include BLUE_ROOT . 'include/fckeditor/fckeditor.php';
	$editor = new FCKeditor($name);
	$editor -> BasePath = $config['BasePath'] ? $config['BasePath'] : './include/fckeditor/';
	$editor -> ToolbarSet = $config['ToolbarSet'] ? $config['ToolbarSet'] : 'User';
	$editor -> Width = $config['Width'] ? $config['Width'] : '100%';
	$editor -> Height = $config['Height'] ? $config['Height'] : '300';
	$editor -> Value = $value ? $value : '';
	$editor_html = $editor -> CreateHtml();
	$smarty -> assign('editor_html', $editor_html);
}

?>