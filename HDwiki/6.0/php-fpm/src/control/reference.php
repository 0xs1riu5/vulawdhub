<?php
/**
词条编辑页面的参考资料
*/
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load("reference");
		$this->load("user");
	}
	
	/**
	添加、编辑参考资料
	编辑操作被整合到了add当中，由$_ENV['reference']->add()实现，
	如果 $data 当中包含 id 信息则执行edit操作，否则执行add操作。
	*/
	function doadd(){
		if($this->get[2] == 'checkable'){
			if ($this->checkable('reference-add')){
				if($this->setting['doc_verification_reference_code']){
					exit('CODE');
				}else{
					exit('OK');
				}
			}else{
				exit('0');
			}
		}
		
		$data=$this->post['data'];
		$data['name'] = htmlspecial_chars(string::stripscript($data['name']));
		$data['url'] = htmlspecial_chars(string::stripscript($data['url']));
		//检查验证码
		if($this->setting['checkcode']!=3 && $this->setting['doc_verification_reference_code'] && strtolower($data['code']) != $_ENV['user']->get_code()){
			exit('code.error');
		}
		
		if (WIKI_CHARSET == 'GBK'){
			$data['name']=string::hiconv($data['name']);
		}
		
		if (empty($data['name'])){
			exit('0');
		}
		$insert_id = $_ENV['reference']->add($data);
		if (is_int($insert_id)){
			echo $insert_id;
		}else{
			echo $insert_id? '1':'0';
		}
	}
	
	/**
	删除参考资料
	*/
	function doremove(){
		$id = $this->get[2];
		if(@is_numeric($id)){
			echo $_ENV['reference']->remove($id)?'1':'0';
		}else{
			echo '0';
		}
	}
}
?>