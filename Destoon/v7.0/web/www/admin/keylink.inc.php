<?php
/*
	[DESTOON B2B System] Copyright (c) 2008-2018 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/
defined('DT_ADMIN') or exit('Access Denied');
isset($item) or msg();
$menus = array(
    array('关联链接', '?file='.$file.'&item='.$item),
);
$do = new keylink;
$do->item = $item;
switch($action) {
	case 'add':
		$i = 0;
		if($content) {
			$data = $do->merge($item);
			$content = stripslashes($content);
			foreach(explode("\n", $content) as $v) {
				$t = explode('|', $v);
				if($t[0] && $t[1]) {
					$post = array();
					$post['title'] = trim($t[0]);
					$post['url'] = trim($t[1]);
					if(strpos($data, $post['title'].'|'.$post['url']) === false) {
						$post = daddslashes($post);
						if($do->add($post)) $i++;
					}
				}
			}
		}
		if($i) cache_keylink($item);
		dmsg('添加成功'.$i.'条', '?file='.$file.'&item='.$item);
	break;
	case 'export':
		file_down('', 'keylink-'.$item.'.txt', $do->merge($item));
	break;
	default:
		if($submit) {
			if($do->update($post)) {
				dmsg('更新成功', '?file='.$file.'&item='.$item);
			} else {
				msg($do->errmsg);
			}
		} else {
			$condition = '';
			if($kw) $condition .= " AND (title LIKE '%$keyword%' OR url LIKE '%$keyword%')";
			$lists = $do->get_list($condition);
			$fid = isset($fid) ? intval($fid) : 0;
			$content = $fid ? $do->merge($fid) : '';
			include tpl('keylink');
		}
	break;
}
class keylink {
	var $item;
	var $table;
	var $errmsg = errmsg;

	function __construct() {
		$this->table = DT_PRE.'keylink';
	}

	function keylink() {
		$this->__construct();
	}

	function get_list($condition) {
		global $pages, $page, $pagesize, $offset, $pagesize, $sum;
		if($page > 1 && $sum) {
			$items = $sum;
		} else {
			$r = DB::get_one("SELECT COUNT(*) AS num FROM {$this->table} WHERE item='$this->item'$condition");
			$items = $r['num'];
		}
		$pages = pages($items, $page, $pagesize);
		$lists = array();
		$result = DB::query("SELECT * FROM {$this->table} WHERE item='$this->item'$condition ORDER BY listorder DESC,itemid DESC LIMIT $offset,$pagesize");
		while($r = DB::fetch_array($result)) {
			$lists[] = $r;
		}
		return $lists;
	}

	function update($post) {
		$this->add($post[0]);
		unset($post[0]);
		foreach($post as $k=>$v) {
			if(isset($v['delete'])) {
				$this->delete($k);
				unset($post[$k]);
			}
		}
		$this->edit($post);
		cache_keylink($this->item);
		return true;
	}

	function add($post) {
		if(strlen($post['title']) < 2 || strlen($post['url']) < 12) return false;
		$post['listorder'] = strlen($post['title']);
		DB::query("INSERT INTO {$this->table} (listorder,title,url,item) VALUES('$post[listorder]','$post[title]','$post[url]','$this->item')");
		return true;
	}

	function edit($post) {
		foreach($post as $k=>$v) {
			if(strlen($v['title']) < 2 || strlen($v['url']) < 12) return false;
			$v['listorder'] = strlen($v['title']);
			DB::query("UPDATE {$this->table} SET listorder='$v[listorder]',title='$v[title]',url='$v[url]' WHERE itemid='$k' AND item='$this->item'");
		}
	}

	function delete($itemid) {
		DB::query("DELETE FROM {$this->table} WHERE itemid=$itemid AND item='$this->item'");
		cache_keylink($this->item);
	}

	function merge($item) {
		$KEYLINK = cache_read('keylink-'.$item.'.php');
		$data = '';
		foreach($KEYLINK as $k=>$v) {
			$data .= $v['title'].'|'.$v['url']."\r\n";
		}
		return $data;
	}
}
?>