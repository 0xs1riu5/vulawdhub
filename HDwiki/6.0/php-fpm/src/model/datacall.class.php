<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class datacallmodel {

	var $db;
	var $base;
	var $configarr;
	var $datatag;
	var $dataid;

	function datacallmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->cache = $base->cache;
	}

	function call($datatag, $calltype=1){
		$this->dataid = $datatag;
		$this->get_config();
		$datastr = $this->cache->getcache('datacall_'.$this->configarr['id'], $this->configarr['cachetime']);
		if(empty($datastr)){
			if(in_array($this->configarr['type'], array('sql', 'fun'))){
				$method = 'datacall_'.$this->configarr['type'];
				$data = $this->$method();
				$datastr = $this->parse_template($data);
				$this->cache->writecache('datacall_'.$this->configarr['id'], array($datastr));
			}else {
				$datastr = $this->base->view->lang['functionNotExist'];
			}
		} else {
			$datastr = $datastr[0];
		}
		if(1 == $calltype) {
			echo  $datastr;
		} else {
			return $datastr;
		}
	}

	function get_config(){
		$temarr = array();
		if(isset($this->dataid) && is_numeric($this->dataid)) {
			$wherestr = "id = '".$this->dataid."'";
		} else {
			$wherestr = "name = '".$this->dataid."'";
		}
		$query=$this->db->query("SELECT * from ".DB_TABLEPRE."datacall  WHERE ".$wherestr." AND available = 1");
		$config = $this->db->fetch_array($query);
		if(empty($config)) {
			$config['id'] = 99999999;
			$config['cachetime'] = 99999999;
		} else {
			$config['param'] = unserialize($config['param']);
		}
		return $this->configarr = $config;
	}

	function parse_template($dataarr){
		if(empty($dataarr)){
			return $this->configarr['param']['empty_tplcode'];
		} else {
			$tpl = $this->configarr['param']['tplcode'];
			if(empty($tpl)){
				$newtpl = var_export($dataarr, true);
			} else {
				$tpl = nl2br($tpl);
				$newtpl = '';
				preg_match_all("/\[(.+?)\]/s", $tpl, $tem);
				foreach($dataarr as $value){
					$temtpl=$tpl;
					foreach($tem[0] as $k => $v) {
						$temtpl = str_replace($v, $value[$tem[1][$k]], $temtpl);
					}
						$newtpl .= $temtpl;
				}
			}
			return $newtpl;
		}
	}

	function datacall_sql(){
		$result = array();
		$params = $this->configarr['param'];
		if(empty($params)){
			return false;
		}
		if(empty($params['sql'])) {
			$params['from'] = str_replace("{DB_TABLEPRE}", DB_TABLEPRE, $params['from']);
			$params['select'] = !trim($params['select']) ? '*' : $params['select'];
			$params['where'] = !trim($params['where']) ? '1=1' : $params['where'];
			$params['orderby'] = !trim($params['orderby']) ? '' : " ORDER BY ".$params['orderby'];
			$params['limit'] = !trim($params['limit']) ? '' : " LIMIT  ".$params['limit'];
			$sql = "SELECT ".$params['select']." FROM ".$params['from']." WHERE ".$params['where']." ".$params['other']." ".$params['orderby']." ".$params['limit'];
		} else {
			$sql = str_replace("{DB_TABLEPRE}", DB_TABLEPRE, $params['sql']);
		}
		// 为SQL 添加默认 LIMIT
		if(!preg_match("/\blimit\b/i", $sql)) {
			$sql .= ' LIMIT 0,30';
		}
		$query = $this->db->query($sql);	
		while($row=$this->db->fetch_array($query)) {
			$result[] = $row;
		}
		return $result;
	}

	function datacall_fun(){
		$outarr = array();
		$classname = $this->configarr['classname'];
		if(!class_exists($classname)) {
			$this->base->load($classname);
		}
		$method = $this->configarr['function'];
		if(method_exists($_ENV[$classname], $method)) {
			$outarr = $_ENV[$classname]->$method();
		}
		return $outarr;
	}

	function get_datacall_num($params) {
		if(empty ($params)) {
			return false;
		}else {
			$sql = ' SELECT count(*) as num FROM '.DB_TABLEPRE.'datacall WHERE 1=1 ';
			if(isset($params['available'])){
				$sql .= " AND available = '".$params['available']."'";
			} else {
				$sql .= " AND available = '1' ";
			}
			if(!empty($params['name'])) {
				$sql .= " AND name = '".$params['name']."'";
			}
			if(!empty($params['id'])) {
				$sql .= " AND id = '".$params['id']."'";
			}
			if(!empty($params['category'])) {
				$sql .= " AND category = '".$params['category']."'";
			}
			if(!empty($params['search_name'])) {
				$sql .= " AND name like '%".$params['search_name']."%'";
			}
		}
		$totalnum = $this->db->result_first($sql);
		return $totalnum;
	}
	
	function get_datacall_info($params){
		if(empty ($params)) {
			return false;
		}else {
			$sql = ' SELECT  * FROM '.DB_TABLEPRE.'datacall WHERE 1=1 ';
			if(isset($params['available'])){
				$sql .= " AND available = '".$params['available']."'";
			} else {
				$sql .= " AND available = '1' ";
			}
			if(!empty($params['name'])) {
				$sql .= " AND name = '".$params['name']."'";
			}
			if(!empty($params['id'])) {
				$sql .= " AND id = '".$params['id']."'";
			}
			if(!empty($params['category'])) {
				$sql .= " AND category = '".$params['category']."'";
			}
			if(!empty($params['search_name'])) {
				$sql .= " AND name like '%".$params['search_name']."%'";
			}
			if(isset ($params['limit'])) {
				$sql .= "limit ".$params['start'].",".$params['limit'];
			} else {
				$sql .= "limit 0,30";
			}
		}
		$query = $this->db->query($sql);
		$datalist = array();
		while($data=$this->db->fetch_array($query)) {
			$datalist[] = $data;
		}
		return $datalist;
	}
	
	function editsql($datacall){
		if(empty($datacall)) {
			return false;
		}else {
			$datacall['desc'] = !trim($datacall['desc']) ? $this->base->view->lang['sqlcall'] : (trim($datacall['desc']));
			$datacall['desc'] = string::substring($datacall['desc'], 0,80);
			$datacall['param']['tplcode'] = !trim($datacall['param']['tplcode']) ? '' : (trim($datacall['param']['tplcode']));
			$datacall['param']['empty_tplcode'] = !trim($datacall['param']['empty_tplcode']) ? '' : (trim($datacall['param']['empty_tplcode']));
			$param_str = string::haddslashes(serialize(string::hstripslashes($datacall['param'])),1);
			$classname = 'sql';
			$function = 'sql';
			$type = 'sql';
			if(isset ($datacall['editflag'])) {
				$sql = "UPDATE `".DB_TABLEPRE."datacall` SET ";
				$sql .= "`name`='".$datacall['name']."',`category`='".$datacall['category']."', `classname`='".$classname."', `function`='".$function."', `desc`='".$datacall['desc']."', `param`='".$param_str."', `cachetime`='".$datacall['cachetime']."'";
				$sql .= " WHERE `id`='".$datacall['id']."'";
			} else{
				$sql = 'INSERT INTO '.DB_TABLEPRE.'datacall (`name`,`type`, `category`, `classname`, `function`, `desc`, `param`, `cachetime`) ';
				$sql .= " SELECT '".$datacall['name']."','".$type."','".$datacall['category']."','".$classname."','".$function."', ";
				$sql .= "'".$datacall['desc']."', '".$param_str."', '".$datacall['cachetime']."'";
				$sql .= " FROM dual WHERE not exists (SELECT * FROM ".DB_TABLEPRE."datacall WHERE name= '".$datacall['name']."' )";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_datacall_category($params=array()){
		$categoryarray = array(
			'user' => $this->base->view->lang['user'],
			'doc' => $this->base->view->lang['doc'],
			'category' => $this->base->view->lang['category'],
			'comment' => $this->base->view->lang['commentCom'],
		);
		if(isset($params['is_str'])) {
			$categorystr = '';
			foreach($categoryarray as $k => $v) {
				$categorystr .= " <option value=\"$k\" ";
				if($k == $params['category']) {
					$categorystr .= " selected=\"selected\" ";
				}
				$categorystr .= " >$v</option>";
			}
			return $categorystr;
		}
		return $categoryarray;
	}

	function remove_call($id){
		if(empty($id)){
			return false;
		}else {
			$this->db->query("DELETE FROM `".DB_TABLEPRE."datacall` WHERE `id` IN ($id) ");
		}
	}
}
?>
