<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{
	
	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('sitemap');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	

	function dosetting() {
		if(!isset($this->post['submit'])){
			$sitemap_config=unserialize(isset($this->setting['sitemap_config']) ? $this->setting['sitemap_config'] : '');
			$sitemap_config['auto_baiduxml'] = $this->setting['auto_baiduxml'];
			$this->view->assign('config',$sitemap_config);
 			$this->view->display("admin_sitemap_setting");
 		}else{
 			$setting['sitemap_config']=serialize($this->post['sitemap_conf']);
 			$setting['auto_baiduxml'] = $this->post['auto_baiduxml'];
 			$this->load('setting');
 			$setting=$_ENV['setting']->update_setting($setting);
 			$this->cache->removecache('setting');
 			$this->message('操作成功','BACK');
 		}
	}

	function dodefault() {
		$this->view->assign('baidu_update', $_ENV['sitemap']->get_last_update('baidu.xml') ? $this->date($_ENV['sitemap']->get_last_update('baidu.xml')) : '暂未更新');
		$this->view->assign('sitemap_update', $_ENV['sitemap']->get_last_update(HDWIKI_ROOT.'/data/sitemap_last_page.log') ? $this->date($_ENV['sitemap']->get_last_update(HDWIKI_ROOT.'/data/sitemap_last_page.log')) : '暂未更新');
		$this->view->display('admin_sitemap');
	}
	
	function docreatedoc() {
		$_ENV['sitemap']->rebuild();
		$this->message('正在重建词条Sitemap，请稍候','index.php?admin_sitemap-updatedoc');
	}
	
	function doupdatedoc() {
		if(($next_offset = $_ENV['sitemap']->create_doc_page()) !== false) {
			$next_offset ++;
			$end_offset = $next_offset + 999;
			$this->message("已完成第{$next_offset}-{$end_offset}条，正在继续，请稍候",'index.php?admin_sitemap-updatedoc');
		} else {
			$this->message('Sitemap索引文件已生成。全部已完成。', 'index.php?admin_sitemap');
		}
	}
	
	function dosubmit() {
		$rs = $_ENV['sitemap']->submit();
		if($rs === false) {
			$this->message('相应的sitemap不存在，请先刷新sitemap再提交', 'index.php?admin_sitemap');
		} else {
			$message = '';
			foreach ($rs as $site=>$response) {
				if(strpos($response, '200') !== false) {
					$message .= $site.' 提交成功，返回状态：'.$response.'<br />';
				} else {
					$message .= $site.' 提交失败，返回状态：'.$response.'<br />';
				}
			}
				
			$this->message($message, 'index.php?admin_sitemap');
		}
	}
	
	function dobaiduxml() {
		$_ENV['sitemap']->create_baiduxml();
		$this->message('操作成功', 'index.php?admin_sitemap');
	}
}

?>
