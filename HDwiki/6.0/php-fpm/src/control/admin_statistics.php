<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load("statistics");
		$this->view->setlang($this->setting['lang_name'],'back');
		$this->cache_time=60*60*3;
	}
	
	function dostand(){
		$stand=$this->cache->getcache('stand',$this->cache_time);
		if(!(bool)$stand){
			$stand=array();
			$doc_num=$_ENV['statistics']->count_num('doc');
			$edition_num=$_ENV['statistics']->count_num('edition');
			$user_day_num=($this->time-$_ENV['statistics']->first_register_time())/(24*3600);

			$doc_num = $doc_num > 1? $doc_num :1;
			$user_day_num = $user_day_num > 1? $user_day_num :1;
			
			$doc_day_num=($this->time-$_ENV['statistics']->first_create_time())/(24*3600);
		
			$stand['register']=$_ENV['statistics']->count_num('user');
			$stand['register'] = $stand['register'] > 1? $stand['register'] :1;
			$stand['edit_user']=$_ENV['statistics']->count_num("user","creates<>0 or edits<>0");
			$stand['manage_user']=$_ENV['statistics']->count_num("user","groupid=3 or groupid=4");
			$stand['unedit_user']=$stand['register']-$stand['edit_user'];
			$stand['new_user']=$_ENV['statistics']->new_user();
			$stand['edit_user_percent']=round($stand['edit_user']*100/$stand['register'],2)."%";
			$stand['star_today']=$_ENV['statistics']->star_today();
			$stand['average_edition']=round($edition_num/$stand['register'],2);
			
			$stand['category_num']=$_ENV['statistics']->count_num('category');
			$stand['average_day_doc']=round($doc_num/$user_day_num,2);
			$stand['hot_category']=$_ENV['statistics']->hot_category();
			$stand['create_doc_num']=$doc_num;
			$stand['average_day_user']=round($stand['register']/$user_day_num,2);
			$stand['doc_comment_num']=$_ENV['statistics']->count_num('comment');
			$stand['edition_num']=$edition_num;
			$stand['day_recent_doc']=$_ENV['statistics']->count_num("doc","time >= ".($this->time-3600*24));
			$stand['average_doc_edition']=$doc_num?round($edition_num/$doc_num,2):$doc_num;
			$stand['day_recent_user']=$_ENV['statistics']->count_num("user","regtime >= ".($this->time-3600*24));
			$stand['activity_index']=($stand['unedit_user']?round($stand['edit_user']*100/$stand['register'],2):$stand['unedit_user'])."%";
			
			$stand['last_cache_time']=$this->date($this->time);
			$stand['cache_time']=$this->date($this->time+$this->cache_time);			
			$this->cache->writecache('stand',$stand);
		}
		//统计信息
		if($this->setting['wk_count']) {
			$wk_count = unserialize($this->setting['wk_count']);
			$count = @util::hfopen('http://kaiyuan.hudong.com/count2/count.php?m=count&a=getcount&key='.$wk_count['key'], 0);
			if($count) {
				$count = explode('|', $count);
				$this->view->assign('count',$count);
			}
		}
		$this->view->assign('stand',$stand);
		$this->view->display('admin_stand');
	}
	
	function docat_toplist(){
		$cat_toplist=$this->cache->getcache('cat_toplist',$this->cache_time);
		if(!(bool)$cat_toplist){
			$cat_toplist=array();
			$cat_toplist['create_toplist']=$_ENV['statistics']->category_toplist(1);
			$cat_toplist['edit_toplist']=$_ENV['statistics']->category_toplist(2);
			$cat_toplist['last_month_toplist']=$_ENV['statistics']->category_toplist(3);
			$cat_toplist['last_day_toplist']=$_ENV['statistics']->category_toplist(4);
			
			$cat_toplist['last_cache_time']=$this->date($this->time);
			$cat_toplist['cache_time']=$this->date($this->time+$this->cache_time);
			
			$this->cache->writecache('cat_toplist',$cat_toplist);
		}
		$this->view->assign('cat_toplist',$cat_toplist);
		$this->view->display('admin_cat_toplist');
	}
	
	function dodoc_toplist(){
		$doc_toplist=$this->cache->getcache('doc_toplist',$this->cache_time);
		if(!(bool)$doc_toplist){
			$doc_toplist=array();
			$doc_toplist['doc_view_toplist']=$_ENV['statistics']->doc_toplist('views');
			$doc_toplist['doc_edit_toplist']=$_ENV['statistics']->doc_toplist('edits');
			$doc_toplist['doc_comment_toplist']=$_ENV['statistics']->doc_toplist('comments');
			
			$doc_toplist['last_cache_time']=$this->date($this->time);
			$doc_toplist['cache_time']=$this->date($this->time+$this->cache_time);
			
			$this->cache->writecache('doc_toplist',$doc_toplist);
		}
		$this->view->assign('doc_toplist',$doc_toplist);
		$this->view->display('admin_doc_toplist');
	}
	
	function doedit_toplist(){
		$edit_toplist=$this->cache->getcache('edit_toplist',$this->cache_time);
		if(!(bool)$edit_toplist){
			$edit_toplist=array();
			$edit_toplist['user_create_toplist']=$_ENV['statistics']->user_toplist('creates');
			$edit_toplist['user_edit_toplist']=$_ENV['statistics']->user_toplist('edits');
			$edit_toplist['user_month_toplist']=$_ENV['statistics']->user_toplist($this->time-3600*24*30,2);
			$edit_toplist['user_day_toplist']=$_ENV['statistics']->user_toplist($this->time-3600*24,2);
			
			$edit_toplist['last_cache_time']=$this->date($this->time);
			$edit_toplist['cache_time']=$this->date($this->time+$this->cache_time);
			
			$this->cache->writecache('edit_toplist',$edit_toplist);
		}
		$this->view->assign('edit_toplist',$edit_toplist);
		$this->view->display('admin_edit_toplist');
	}
	
	function docredit_toplist(){
		$credit_toplist=$this->cache->getcache('credit_toplist',$this->cache_time);
		if(!(bool)$credit_toplist){
			$credit_toplist=array();
			$this->load('user');
			$credit_toplist['user_credit_toplist']=$_ENV['user']->get_top_user(0,20);
			$credit_toplist['user_week_credit']=$_ENV['user']->get_week_user(0,20, $this->time-3600*24*7);
			$credit_toplist['user_views_toplist']=$_ENV['statistics']->user_toplist('views');
			
			$credit_toplist['last_cache_time']=$this->date($this->time);
			$credit_toplist['cache_time']=$this->date($this->time+$this->cache_time);
			
			$this->cache->writecache('credit_toplist',$credit_toplist);
		}
		$this->view->assign('credit_toplist',$credit_toplist);
		$this->view->display('admin_credit_toplist');
	}
	
	function doadmin_team(){
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count = $_ENV['statistics']->count_num("user","groupid=3 or groupid=4 or groupid=15");
		$admin_team=$_ENV['statistics']->get_admin_team($start_limit,$num);
		$departstr=$this->multi($count, $num, $page,'admin_statistics-admin_team');
		foreach($admin_team as $key => $user){
			if($user['lasttime']){
				$admin_team[$key]['lasttime']=$this->date($user['lasttime']);
				$admin_team[$key]['leave_day']=round(($this->time-$user['lasttime'])/(3600*24));
			}elseif($user['regtime']){
				$admin_team[$key]['lasttime']=$this->date($user['regtime']);
				$admin_team[$key]['leave_day']=round(($this->time-$user['regtime'])/(3600*24));
			}else{
				$admin_team[$key]['lasttime']=$this->view->lang['never_login'];
				$admin_team[$key]['leave_day']=$this->view->lang['never_login'];
			}
			$admin_team[$key]['groupid']=$user['groupid']==4?$this->view->lang['super_admin']:$this->view->lang['wiki_admin'];
		}
		$this->view->assign('admin_team',$admin_team);
		$this->view->assign("departstr",$departstr);
		$this->view->display('admin_admin_team');
	}
}
?>