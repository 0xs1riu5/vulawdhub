<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{
	
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('gift');
		$this->load('user');
	}

	/*礼品商店浏览*/
	function dodefault(){
		if($this->setting['gift_close']!='0' && $this->setting['gift_close']!=''){
			header('Location:'.WIKI_URL);
			exit;
		}
		
		/*获取价格区间*/
		$gift_range=unserialize($this->setting['gift_range']);
		$minprice=array_keys($gift_range);
		$maxprice=array_values($gift_range);
		$this->view->assign("minprice",$minprice);
		$this->view->assign("maxprice",$maxprice);
		/*获取礼品列表*/
		$beginprice = $endprice = '';
		if(isset($this->get[2]) && $this->get[2]!==''){
			$beginprice =intval($this->get[2]); //价格起始值
			$endprice =$gift_range[$beginprice];//价格结束值
			$total=$this->db->fetch_total('gift'," available=1 AND credit BETWEEN  $beginprice AND  $endprice ");//总礼品记录数
		}else{
			$total=$this->db->fetch_total('gift','available=1');//总礼品记录数
		}
		$this->get[3] = empty($this->get[3]) ? 0 : $this->get[3];
		$page = max(1, intval($this->get[3])); //当前页面		
		$limit=10;//每页显示数
		//$page=($page - 1) * $limit>$total?1:$page; //移动端报错，当没有数据的时候不执行操作
	 	$start_limit = ($page - 1) * $limit;
		$giftlist=$_ENV['gift']->get_list($title='',$beginprice ,$endprice ,$begintime='',$endtime='',$start_limit,$limit);

		//调用判断是否移动端请求
		if($this->isMobile() && $page > 1){
			exit(json_encode($giftlist));
		}

		/*分页字符串*/
		$departstr=$this->multi($total, $limit, $page,'gift-default-'.$beginprice);
		$this->view->assign('beginprice',$beginprice);
		$this->view->assign('giftlist',$giftlist);
		$this->view->assign('departstr',$departstr);
		$this->view->assign('page',$page);
 		/*最新兑换动态*/
		$loglist=$_ENV['gift']->get_loglist();
		$this->view->assign('loglist',$loglist);
	//	$this->view->display('giftlist');
		if ($this->isMobile()){
			$_ENV['block']->view('wap-giftlist');
		} else {
			$_ENV['block']->view('giftlist');
		}
	}

	/*礼品申请*/
	function doapply(){
		/*获取用户提交参数*/
		$gid =$this->post['gid']; //礼品id
		$gid = is_numeric($gid) ? $gid : 0;
		$truename =$this->post['truename']; 
		$telephone =$this->post['telephone']; 
		$email =$this->post['email']; 
		$location =$this->post['location']; //用户地址
		$postcode =$this->post['postcode']; //邮编
		$extra =$this->post['extra']; //备注信息
		$qq =$this->post['qq']; 
		
		$gift=$_ENV['gift']->get($gid);//当前礼品
		if(!$gift) {
			$this->message($this->view->lang['gifNotExists'],'BACK',0);
		}		
		/*更新用户表相关数据*/
		$_ENV['user']->update_extra($this->user['uid'],$truename,$telephone,$email,$location,$postcode,$qq);
		/*自动扣除credit1 金币数，如果金币数不够给出错误提示，并终止执行下面*/
		if($gift['credit']>$this->user['credit1']){
			$this->message($this->view->lang['goldNotEnoughCheckGift'],'BACK',0);
		}
		$_ENV['user']->update_field('credit1',-$gift['credit'],$this->user['uid'],0); //扣除当前用的金币数
		/*插入申请礼品表记录 giftlog*/
		$_ENV['gift']->addlog($gid,$this->user['uid'],$extra);
		/*提示申请成功，请耐心等待管理员审批*/
		$this->message($this->view->lang['checkSuccess'],'BACK',0);
	}

}
?>