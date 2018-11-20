<?php
/*
 * [bluecms]版权所有 标准网络，保留所有权利
 * This is not a freeware, use is subject to license terms
 *
 * $Id：pay.php
 * $author：lucks
 */
if(!defined('IN_BLUE'))
{
	die('Access Denied!');
}

/**
  *
  * 获取模型的附加属性
  */
function getatt()
{
	global $db;
	$sql = "SELECT att.*,model_name 
			FROM ".table('attachment')." AS att, ".table('model')." WHERE att.model=model_id 
 			ORDER BY model";
	return $db->getall($sql);
}

/**
  *
  * 返回模型列表
  */
function model()
{
	global $db;
	$sql = "SELECT model_id, model_name 
			FROM ".table('model').
			" ORDER BY show_order";
	$model_arr = $db->getall($sql);
	return $model_arr;
}

/**
  *
  * 写日志文件
  *
  * @param $str 操作事件
  *
  * @param $user 操作者
  */
function write_log($str, $user)
{
 	global $db, $timestamp;
 	$sql = "INSERT INTO ".table('admin_log')." (log_id, admin_name, add_time, log_value) 
 			VALUES ('', '$user', '$timestamp', '$str')";
 	if(!$db->query($sql))
 	{
 		showmsg('写入日志失败');
 	}
}

/**
 *
 *
 */
function get_info_deep($cat_id, $sta='', $len='')
{
	global $db;
	if(isset($sta)&&!empty($len))
	{
		$limit = " LIMIT $sta, $len";
	}
	else
	{
		$limit = '';
	}
	$sql = "SELECT post_id, cat_id, title, pub_date, is_check, is_recommend, top_type, click, comment 
 			FROM ".table('post').
 			" WHERE cat_id IN (SELECT cat_id 
 								FROM ".table('category').
 								" WHERE parentid=".$cat_id.") 
 								ORDER BY pub_date DESC".$limit;
 	return $db->getall($sql);
}

function get_version()
{
	global $php_self;
	if(!file_exists(BLUE_ROOT.DATA.'update_log.txt'))
	{
		echo '该系统的更新记录文件已经丢失，这样无法通知您正确的更新';
		exit;
	}
	$fp = @fopen(BLUE_ROOT.DATA.'update_log.txt', 'rb');
	if(!$fp)
	{
		echo '打开更新日志文件失败,请重试';
		exit;
	}
	if(!$str = @fread($fp, 10))
	{
		echo '读取更新日志文件失败，请重试';
		exit;
	}
	@fclose($fp);
 	$version_info = '';
 	$version_info .= 'version='.BLUE_VERSION;
 	$version_info .= '&update_no='.$str;
 	$version_info .= '&url='.$_SERVER['SERVER_NAME'].substr(dirname($php_self), 0, -5);
 	$version_info .= '&addr='.$_SERVER['SERVER_ADDR'];
 	return base64_encode($version_info);
}

function get_log_total()
{
 	global $db;
 	$row = $db->getone("SELECT COUNT(*) AS num FROM ".table(admin_log));
 	return $row['num'];
}

function get_log($sta = '', $len = '')
{
 	global $db;
 	if(isset($sta) && !empty($len))
 	{
 		$limit = " LIMIT $sta, $len";
 	}
 	else
 	{
 		$limit = "";
 	}
 	$sql = "SELECT log_id, admin_name, add_time, log_value FROM ".table('admin_log')." ORDER BY add_time DESC".$limit;
 	return $db->getall($sql);
}

function get_total($sql)
{
	global $db;
	if(empty($sql))
	{
		return false;
	}
	$row = $db->getone($sql);
	return $row['num'];
}

function get_list($sql, $sta='', $len='')
{
 	global $db;
 	if(isset($sta) && !empty($len))
 	{
 		$limit = " LIMIT $sta, $len";
 	}
 	else
 	{
 		$limit = "";
 	}
 	$sql = $sql.$limit;
 	return $db->getall($sql	);
}

function model_has_child($model_id)
{
 	global $db;
 	$result = $db->getone("SELECT COUNT(*) AS num FROM ".table('category')." WHERE model = ".intval($model_id));
 	if($result['num']>0)
 	{
 		return true;
 	}
 	else
 	{
 		return false;
 	}
 }



function check_admin($name, $pwd)
{
	global $db;
	$row = $db->getone("SELECT COUNT(*) AS num FROM ".table('admin')." WHERE admin_name='$name' and pwd = md5('$pwd')");
 	if($row['num'] > 0)
 	{
 		return true;
 	}
 	else
 	{
 		return false;
 	}
}

/**
 * 检验后台登录用户是否拥有某项权限
 *
 * @param $p 用户权限
 *
 * @param $q  某项操作所需权限
 *
 */
function check_purview($q)
{
 	$p = $_SESSION['admin_purview'];
 	if((is_array($p) && in_array($q, $p)) || $p[0] == 'all')
 	{
 		return true;
 	}
 	else
 	{
 		return false;
 	}
}


function get_cat_html2()
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
			while($row2 = $db->fetch_array($result2)){
				$html .= "<li><a href=\"info.php?act=add2&cid=".$row2['cat_id']."\">".$row2['cat_name']."</a></li>";
			}
			$html .="</ul>";
 		}
 		$html .="</div><div class=\"clear\"></div>";
 	}
 	return $html;
}

function get_arc_cat($lever, $selected = '', $current = '')
{
 	global $db;
 	$option = '';
	if ($lever == 0)
	{
		$sql = "SELECT cat_id, cat_name FROM ".table('arc_cat')." WHERE parent_id=0 ORDER BY show_order,cat_id";
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
		$sql = "SELECT cat_id, cat_name FROM ".table('arc_cat')." WHERE parentid=0 ORDER BY show_order, cat_id";
		$result = $db->query($sql);
		while($row = $db->fetch_array($result))
		{
			$option .="<option value=\"".$row[catid]."\"";
			if($row[cat_id] == $selected)
			{
				$option .= " selected = \"selected\"";
			}
			$option .= ">$row[cat_name]</option>";
			$option .= get_arc_child($row[cat_id], $selected);
		}
	}
	return $option;
}

function get_arc_child($parentid, $selected = '')
{
 	global $db;
	if($parentid == '')
	{
		echo 'function get_option param error';
	}
	$sql = "SELECT cat_id, cat_name, cat_indent FROM ".table('arc_cat')." WHERE parentid=".$parentid." ORDER BY show_order,cat_id";
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

function get_key ($array, $key)
 {
	 for ($i = 0 ; $i < count($array); $i++)
	 {
		 if($array[$i]['code'] == $key)
		 {
			 return $i;
		 }
	 }
}

function update_pay_cache()
{
	global $db;
	$pay_list = $db->getall("SELECT * 
							FROM ".table('pay').
							" WHERE is_open=1 ORDER BY show_order,id");
	$cache_file = BLUE_ROOT . 'data/pay.cache.php';
    $content = "<?php\r\n";
	$content .= "if(!defined('IN_BLUE')){\r\ndie(\"Acess Denied!\");\r\n}\r\n";
    $content .= "\$data = " . var_export($pay_list, true) . ";\r\n";
    $content .= "?>";
	if (function_exists('file_put_contents'))
	{
		file_put_contents($cache_file, $content, LOCK_EX);
	}
	else
	{
		if (!$fp = @fopen($pay_list, 'wb+'))
		{
			showmsg('打开支付接口缓存文件失败');
		}
		if (!fwrite($fp, $content))
		{
			showmsg('写入支付接口缓存文件失败');
		}
		@fclose($fp);
	}
}

function check_admin_name($name)
{
	global $db;
	$sql = "SELECT COUNT(*) FROM " . table('admin') . " WHERE admin_name='$name'";
	if ($db->getfirst($sql) == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function get_name($user_id)
{
	global $db;
	$sql = "SELECT user_name FROM " . table('user') . " WHERE user_id=" . intval($user_id);
	return $db->getfirst($sql);
}

?>
