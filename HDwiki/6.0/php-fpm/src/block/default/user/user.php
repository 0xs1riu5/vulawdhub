<?php
class user{
	
	var $db;
	var $base;

	function user(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function login($setting){
		$loginTip2 = $this->base->view->lang['loginTip2'];
		$loginTip2 = str_replace(array('3','15'),array($this->base->setting['name_min_length'],$this->base->setting['name_max_length']),$loginTip2);

		$userdata['checkcode'] = isset($this->base->setting['checkcode'])?$this->base->setting['checkcode']:0;
		$userdata['name_min_length'] = $this->base->setting['name_min_length'];
		$userdata['name_max_length'] = $this->base->setting['name_max_length'];
		$userdata['passport'] = defined('PP_OPEN')&&PP_OPEN;
		$userdata['loginTip2'] = $loginTip2;

		return array('config'=>$setting, 'data'=>$userdata);
	}
}
?>