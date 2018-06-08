<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class advmodel {
	var $db;
	var $base;
	
	function advmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function add_adv($type){
		$this->db->query("INSERT INTO ".DB_TABLEPRE."advertisement (available, type) VALUES ('1', '$type')");
		return $this->db->insert_id();
	}

	function update_adv($advid,$advnew){
		$this->db->query("UPDATE ".DB_TABLEPRE."advertisement SET title='$advnew[title]', position='$advnew[position]', targets='$advnew[targets]', parameters='$advnew[parameters]', code='$advnew[code]', starttime='$advnew[starttime]', endtime='$advnew[endtime]' WHERE advid='$advid'");
	}
	
	function search_adv_num($title='',$time='',$type=''){
		$sql="SELECT  count(*)  FROM ".DB_TABLEPRE."advertisement WHERE 1=1 ";
		if($title){
			$sql=$sql." AND title LIKE '%$title%' ";
		}
		if($time){
			$sql=$sql." AND endtime-starttime<=$time ";
		}
		if(''!==$type){
			$sql=$sql." AND type='$type' ";
		}
		return $this->db->result_first($sql);
	}
	
	function search_adv($start=0,$limit=10, $title='',$time='',$type='', $orderby='type' ){
		$advlist=array();
		$sql="SELECT * FROM ".DB_TABLEPRE."advertisement WHERE 1=1 ";
		if($title){
			$sql=$sql." AND title LIKE '%$title%' ";
		}
		if($time){
			$sql=$sql." AND endtime-starttime<=$time ";
		}
		if(''!==$type){
			$sql=$sql." AND type='$type' ";
		}
		$sql=$sql." ORDER BY $orderby DESC LIMIT $start,$limit ";
		
		$query=$this->db->query($sql);
		while($adv=$this->db->fetch_array($query)){
			if($adv['starttime']!=='0'){
				$adv['starttime']=date('Y-m-d',$adv['starttime']);
			}
			if($adv['endtime']!=='0'){
				$adv['endtime']=date('Y-m-d',$adv['endtime']);
			}
			$adv['parameters']=unserialize($adv['parameters']);
			$adv=$this->adv_filter($adv);
			$advlist[]=$adv;
		}
		return $advlist;
	}
	
	/*Description: This method has already expired*/
	function get_adv($advid){
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."advertisement WHERE advid='$advid' ");
		return $this->db->fetch_array($query);
	}
	
	function update_available($advid){
		$this->db->query("UPDATE ".DB_TABLEPRE."advertisement SET available=ABS(available-1) WHERE advid='$advid' ");
	}
	
	function advnew_filter($advnew,$type){
		$advnew['starttime'] = $advnew['starttime'] ? strtotime($advnew['starttime']) : 0;
		$advnew['endtime'] = $advnew['endtime'] ? strtotime($advnew['endtime']) : 0;
		
		if(!$advnew['title']){
			$this->base->message($this->base->view->lang['adv_title_invalid'],'BACK');
		}elseif(strlen($advnew['title']) > 50){
			$this->base->message($this->base->view->lang['adv_title_more'],'BACK');
		}elseif($advnew['endtime'] && ($advnew['endtime'] <= $this->time || $advnew['endtime'] <= $advnew['starttime'])) {
			$this->base->message($this->base->view->lang['adv_endtime_invalid'],'BACK');
		}elseif(($advnew['style'] == 'code' && !$advnew['code']['html'])
			|| ($advnew['style'] == 'text' && (!$advnew['text']['title'] || !$advnew['text']['link']))
			|| ($advnew['style'] == 'image' && (!$advnew['image']['url'] || !$advnew['image']['link']))
			|| ($advnew['style'] == 'flash' && (!$advnew['flash']['url'] || !$advnew['flash']['width'] || !$advnew['flash']['height']))){
				$this->base->message($this->base->view->lang['adv_parameter_invalid'],'BACK');
		}
	
		foreach($advnew[$advnew['style']] as $key => $val) {
			$advnew[$advnew['style']][$key] = stripslashes($val);
		}
		
		$targetsarray = array();
		if(is_array($advnew['targets'])){
			foreach($advnew['targets'] as $target) {
				if($target == 'all') {
					$targetsarray = array('all');
					break;
				} elseif(in_array($target, array('index','register', 'message', 'doc')) || preg_match("/^\d+$/", $target)) {
					$targetsarray[] = $target;
				}
			}
		}
		$advnew['targets'] = implode(";", $targetsarray);
		$advnew['position'] = isset($advnew['position']) ? $advnew['position'] : '';
		$advnew['displayorder'] = isset($advnew['displayorder']) ? $advnew['displayorder'] : 0;
		
		switch($advnew['style']) {
			case 'code':
				$advnew['code'] = $advnew['code']['html'];
				break;
			case 'text':
				$advnew['code'] = '<a href="'.$advnew['text']['link'].'" target="_blank" '.($advnew['text']['size'] ? 'style="font-size: '.$advnew['text']['size'].'"' : '').'>'.$advnew['text']['title'].'</a>';
				break;
			case 'image':
				$advnew['code'] = '<a href="'.$advnew['image']['link'].'" target="_blank"><img src="'.$advnew['image']['url'].'"'.($advnew['image']['height'] ? ' height="'.$advnew['image']['height'].'"' : '').($advnew['image']['width'] ? ' width="'.$advnew['image']['width'].'"' : '').($advnew['image']['alt'] ? ' alt="'.$advnew['image']['alt'].'"' : '').' border="0"></a>';
				break;
			case 'flash':
				$advnew['code'] = '<embed width="'.$advnew['flash']['width'].'" height="'.$advnew['flash']['height'].'" src="'.$advnew['flash']['url'].'" type="application/x-shockwave-flash" wmode="transparent"></embed>';
				break;
		}

		if($type == 6) {
			$sourcecode = $advnew['code'];
			$advnew['floath'] = $advnew['floath'] >= 40 && $advnew['floath'] <= 600 ? intval($advnew['floath']) : 200;
			$advnew['code'] = str_replace(array("\r\n", "\r", "\n"), '<br />', $advnew['code']);
			$advnew['code'] = addslashes($advnew['code'].'<br /><img src="style/default/advclose.gif" onMouseOver="this.style.cursor=\'pointer\'" onClick="closeBanner();">');
			$advnew['code'] = 'theFloaters.addItem(\'floatAdv1\',6,\'document.documentElement.clientHeight-'.$advnew['floath'].'\',\'<div style="position: absolute; left: 6px; top: 6px;">'.$advnew['code'].'</div>\');';
		} elseif($type == 5) {
			$sourcecode = $advnew['code'];
			$advnew['code'] = str_replace(array("\r\n", "\r", "\n"), '<br />', $advnew['code']);
			$advnew['code'] = addslashes($advnew['code'].'<br /><img src="style/default/advclose.gif" onMouseOver="this.style.cursor=\'pointer\'" onClick="closeBanner();">');
			$advnew['code'] = 'theFloaters.addItem(\'coupleBannerL\',6,0,\'<div style="position: absolute; left: 6px; top: 6px;">'.$advnew['code'].'</div>\');theFloaters.addItem(\'coupleBannerR\',\'winWidth-6\',0,\'<div style="position: absolute; right: 6px; top: 6px;">'.$advnew['code'].'</div>\');';
		} 
		$advnew['parameters'] = addslashes(serialize(array_merge(array('style' => $advnew['style']), $advnew['style'] == 'code' ? array() : $advnew[$advnew['style']], array('html' => $advnew['code']), array('position' => $advnew['position']), array('displayorder' => $advnew['displayorder']), ($sourcecode ? array('sourcecode' => $sourcecode) : array()), ($advnew['floath'] ? array('floath' => $advnew['floath']) : array()))));
		$advnew['code'] = addslashes($advnew['code']);
		return $advnew;
	}
	
	function adv_admin_filter($adv){
		if(!is_array($adv)){
			return array();
		}
		$adv['parameters']=unserialize($adv['parameters']);
		if(!empty($adv['targets'])){
			$adv['targets']=explode(';',$adv['targets']);
		}
		if($adv['starttime']!=='0'){
			$adv['starttime']=date('Y-m-d',$adv['starttime']);
		}
		if($adv['endtime']!=='0'){
			$adv['endtime']=date('Y-m-d',$adv['endtime']);
		}
		return $adv;
	}
	
	function view_filter($position){
		$view_type=$all_category=array();
		$this->base->load('category');
		if(!(bool)$all_category){
			$all_category = $_ENV['category']->get_all_category();
			$this->base->cache->writecache('category',$all_category);
		}
		$catstr = $_ENV['category']->get_categrory_tree($all_category);
		switch($position){
		case 0:
			$adv_range=$this->base->view->lang['in_range']."<optgroup label='".$this->base->view->lang['adv_cat']."'>".$catstr."</optgroup>";
			break;
		case 1:
			$adv_range=$this->base->view->lang['in_range']."<optgroup label='".$this->base->view->lang['adv_cat']."'>".$catstr."</optgroup>";
			break;
		case 3:
			$adv_range=$this->base->view->lang['in_range2']."<optgroup label='".$this->base->view->lang['adv_cat']."'>".$catstr."</optgroup>";
			$dis_pos=$this->base->view->lang['dis_pos_1'];
			break;
		case 4:
			$adv_range=$this->base->view->lang['in_range2']."<optgroup label='".$this->base->view->lang['adv_cat']."'>".$catstr."</optgroup>";
			$dis_pos=$this->base->view->lang['dis_pos_2'];
			break;
		case 6:
			$isfloat=true;
			break;
		default:
			break;
		}
		if(isset($adv_range)){
			$view_type['adv_range']=$adv_range;
		}
		if(isset($dis_pos)){
			$view_type['dis_pos']=$dis_pos;
		}
		if(isset($isfloat)){
			$view_type['isfloat']=$isfloat;
		}
		return $view_type;
	}
	
	function adv_filter($advlist){
		$newtargets=array();
		if(isset($advlist['type'])){
			$advlist['type']=$this->base->view->lang['adv_position_'.$advlist['type']];
		}
		if(isset($advlist['parameters']['style'])){
			$advlist['parameters']['style']=$this->base->view->lang['adv_style_'.$advlist['parameters']['style']];
		}
		if(isset($advlist['targets'])){
			$targets=explode(';',$advlist['targets']);
			foreach($targets as $target){
				if(is_numeric($target)){
					$category=$_ENV['category']->get_category($target);
					$newtargets[]=$category['name'];
				}else{
					$newtargets[]=$this->base->view->lang['adv_target_'.$target];
				}
			}
			$advlist['targets']=join(';',$newtargets);
		}
		return $advlist;
	}
	
	function removeadv($advids){
		if(is_array($advids)){
			$ids = implode(',',$advids);
			$this->db->query("DELETE FROM ".DB_TABLEPRE."advertisement WHERE advid IN ($ids) ");
		}else{
			$this->db->query("DELETE FROM ".DB_TABLEPRE."advertisement WHERE advid=$advids");
		}
		return $this->db->affected_rows();
	}
	
	function adv_doc_filter($advertisement,$categorys){
		$advlist=array();
		$categorys=array_merge(array('all','doc'),$categorys);
		if (!is_array($advertisement)){return $advlist;}
		foreach($advertisement as $adv){
			$adv['targets']=explode(';',$adv['targets']);
			$cat=array_intersect($adv['targets'], $categorys);
			if(!empty($cat))
			if(in_array($adv['type'],array('0','1','3','4'))&&($adv['starttime']=='0'||$adv['starttime']<=$this->base->time)&&($adv['endtime']=='0'||$adv['endtime']>=$this->base->time)){
				if($adv['type']>2){
					$advlist[$adv['type']][$adv['position']][]=$adv;
				}else{
					$advlist[$adv['type']][]=$adv;
				}
			}
		}
		$advlist=$_ENV['global']->rand_array($advlist);
		return $advlist;
	}
}
?>
