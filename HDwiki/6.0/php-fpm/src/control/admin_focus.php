<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('setting');
		$this->load('doc');
		$this->view->setlang($this->setting['lang_name'],'back');
	}

	/*recommend doc*/
	function dofocuslist(){
		$page = max(1, intval($this->get[2]));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count=$this->db->fetch_total('focus','1');
		
		$lists=$_ENV['doc']->get_focus_list($start_limit,$num);
		$departstr=$this->multi($count, $num, $page,'admin_focus-focuslist');
		$this->view->assign('searchdata',urlencode('admin_focus-focuslist-'.$page));
		$this->view->assign('lists',$lists);
		$this->view->assign("docsum",$count);
		$this->view->assign("departstr",$departstr);
		$this->view->display("admin_focus");
	}

	/*remove recommend doc*/
	function doremove(){
		$did=isset($this->post['did'])?$this->post['did']:'';
		if(is_array($did)){
			if($_ENV['doc']->remove_focus($did)){
				$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
				$this->message($this->view->lang['docRemoveFocusSuccess'],'index.php?admin_focus-focuslist');
			}else{
				$this->message($this->view->lang['docRemoveFocusFail'],'index.php?admin_focus-focuslist');
			}
		}else{
			$this->message($this->view->lang['docRemoveDocNull']);
		}
	}

	/*doc order*/
	function doreorder(){
		$all_focus_did=$this->post['all_focus_did'];
		$count=count($all_focus_did);
		for($i=0;$i<$count;$i++){
			$focusorder='order'.$all_focus_did[$i];
			$order=$this->post[$focusorder];
			if(is_numeric($order)){
				$this->db->update_field('focus','displayorder',$order,"did=$all_focus_did[$i]");
			}
		}
		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		$this->header("admin_focus-focuslist");
	}

	/*id,view doc content and edit*/
	function doedit(){
		if(!isset($this->post['editsubmit'])){
			$did=isset($this->get[2])?$this->get[2]:'';
			if(!is_numeric($did)){
				$this->message($this->view->lang['docIdMustNum'],'BACK');
			}
			$focus=$this->db->fetch_by_field('focus','did',$did);
			$focus['time']=$this->date($focus['time']);
			$this->view->assign("focus",$focus);
			$this->view->display("admin_focus_content");
		}else{
			$did=isset($this->post['did'])?$this->post['did']:'';
			if(!is_numeric($did)){
				$this->message($this->view->lang['docIdMustNum'],'BACK');
			}
			$summary=string::hiconv($this->post['summary']);
			$image=string::hiconv($this->post['image']);
			$order=$this->post['displayorder'];
			$type=$this->post['doctype'];
			if(is_numeric($order)&&$_ENV['doc']->save_focus_content($did,$summary,$image,$order,$type)){
				$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
				$this->message($this->view->lang['docEditSuccess'],"index.php?admin_focus-edit-$did");
			}else{
				$this->message($this->view->lang['docEditFail'],'BACK');
			}
		}
	}
	/*update imgage*/
	function doupdateimg(){
		if(!empty($this->post['did']) && is_array($this->post['did'])) {
			foreach($this->post['did'] as $did) {
				$doc=$this->db->fetch_by_field('doc','did',$did);
				if(is_array($doc)){
					$img=$_ENV['doc']->setfocusimg(util::getfirstimg($doc['content']));
					if($img!=''){
						$doc=$this->db->update_field('focus','image',$img,"did=$did");
					}
				}
			}
		}

		$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
		if(isset($this->get[2])) {
			$searchdata = $this->get[2];
			$searchdata = str_replace(',', '-', $searchdata);
			$this->header(urldecode($searchdata));
		} else {
			$this->header("admin_focus-focuslist");
		}
	}
	
	/*doc number*/
	function donumset(){
		$this->view->display("admin_numset");
	}
}