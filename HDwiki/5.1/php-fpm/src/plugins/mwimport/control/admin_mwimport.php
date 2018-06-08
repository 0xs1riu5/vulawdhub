<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{
    
	var $wmdb;
	var $configfile;
	var $sumfile;
	var $processfile;

	function control(& $get,& $post){
		$this->configfile = HDWIKI_ROOT.'/plugins/mwimport/mw.config.php';
		$this->sumfile = HDWIKI_ROOT.'/plugins/mwimport/mw.sum.php';
		$this->processfile = HDWIKI_ROOT.'/plugins/mwimport/mw.process.php';
		$this->base($get, $post);
		$this->load("category");
		$this->load('plugin');
		$this->load('doc');
		$this->load('user');
		$this->loadplugin('mwimport');
		$this->view->setlang($this->setting['lang_name'], 'back');
	}
	
	function dodefault() {
		$pluginid = $this->plugin['mwimport']['pluginid'];
		$this->view->assign('pluginid',$pluginid);
		if(isset($this->post['submit'])){
		    $lnk = mysql_connect($this->post['dbhost'], $this->post['dbuser'], $this->post['dbpw']) or $this->message('Not connected : ' . mysql_error(), 'BACK');
		    mysql_select_db($this->post['dbname'], $lnk) or $this->message('不能连接数据库：' . $this->post['dbname'] . mysql_error(), 'BACK');
		    mysql_close($lnk);
		    
		    $configcontent = "<?php
			define('WDB_HOST', '".$this->post['dbhost']."');
			define('WDB_USER', '".$this->post['dbuser']."');
			define('WDB_PW', '".$this->post['dbpw']."');
			define('WDB_NAME', '".$this->post['dbname']."');
			define('WDB_CHARSET', '".$this->post['charset']."');
			define('WDB_TABLEPRE', '".$this->post['tablepre']."');
			define('WDB_CONNECT', 0);
		    ?>";
		    $_ENV['mwimport']->writefile($this->configfile,$configcontent);
		    if(file_exists($this->configfile)){
			include $this->configfile;
			$this->wmdb=new hddb(WDB_HOST, WDB_USER, WDB_PW, WDB_NAME , WDB_CHARSET ,WDB_CONNECT);
			$sql="SELECT count(*) sum FROM ".WDB_TABLEPRE."page a, ".WDB_TABLEPRE."text b WHERE a.page_namespace = 0 AND a.page_latest = b.old_id";
			$docsum = $this->wmdb->result_first($sql);
			$sql = "SELECT count(*) sum FROM ".WDB_TABLEPRE."user where 1 ";
			$usersum = $this->wmdb->result_first($sql);
			$sql = "SELECT count(*) sum FROM ".WDB_TABLEPRE."category where 1 ";
			$catsum = $this->wmdb->result_first($sql);
			$sql = "SELECT count(*) sum FROM ".WDB_TABLEPRE."categorylinks where 1 ";
			$clinksum = $this->wmdb->result_first($sql);

			$_ENV['mwimport']->writefile($this->sumfile,'<?php $usersum = '.$usersum.';$docsum = '.$docsum.'; $catsum = '.$catsum.'; $clinksum = '.$clinksum.';?>');
			$this->view->assign('usersum',$usersum);
			$this->view->assign('docsum',$docsum);
			$this->view->assign('catsum',$catsum);
			$this->view->assign('clinksum',$clinksum);
		    }else{
			$this->message('生成文件'.$this->configfile.'出错了。', 'BACK');
		    }
		}
		$this->view->display('file://plugins/mwimport/view/admin_mwimport');
	}
	
	function doimport(){
		//global $wmdb;
		if(file_exists($this->configfile) && file_exists($this->sumfile)){
		    include ($this->configfile);
		    include ($this->sumfile);
		}else{
		    $this->message('5|0','',2);
		}
		$this->wmdb=new hddb(WDB_HOST, WDB_USER, WDB_PW, WDB_NAME , WDB_CHARSET ,WDB_CONNECT);
		//$wmdb = $this->wmdb;
		//判断做到哪一步了.
		if(file_exists($this->processfile)){
		    include ($this->processfile);
		    list($type,$i) = explode('|', $process);
		    if($type >= 5) $this->message('5|0','',2);
		}else{
		    list($type,$i)= array(1,0);
		}
		$totalnum = array(1=>$catsum,2=>$usersum,3=>$docsum,4=>$clinksum);
		$totalnum = $totalnum[$type];
		$j=10;
		for(;$i<$totalnum;$i+=$j){
		    $msg =  $type.'|'.(($n=$i+$j)>=$totalnum ?$totalnum : $n);
		    if($type==1||$type==2||$type==4){
			//导入分类和用户。
			$hdsql = $_ENV['mwimport']->get_sql($this->wmdb,$type,$i,$j);
			if(!$hdsql || $this->db->query($hdsql)){
			    $_ENV['mwimport']->writefile($this->processfile,'<?php $process = "'.$msg.'"; ?>');
			}else{
			    $this->message('5|0','',2);
			}
		    }elseif($type==3){
			include (HDWIKI_ROOT.'/plugins/mwimport/text/Mediawiki.php');
			$parser = 'Mediawiki';
			$text_wiki = new Text_Wiki_Mediawiki();
			$sql="SELECT a.page_id, a.page_title, b.old_text, b.old_flags, b.old_id FROM ".WDB_TABLEPRE."page a, ".WDB_TABLEPRE."text b WHERE a.page_namespace = 0 AND a.page_latest = b.old_id limit ".$i.",$j";
			$query = $this->wmdb->query($sql);
			while($doc=$this->wmdb->fetch_array($query)){
			    if($_ENV['doc']->get_doc_by_title($doc['page_title'])){
				    continue;
			    }
			    $doc['did'] = $doc['page_id'];
			    $doc['title']=$doc['page_title'];
			    $doc['letter']=string::getfirstletter($doc['page_title']);

			    $wiki = $text_wiki->singleton($parser);
			    $result = $wiki->transform($source);
			    $doc['old_text'] = $wiki->transform($doc['old_text']);//加入对内容的处理和过滤。

			    $doc['old_text']=mysql_real_escape_string($doc['old_text']);
			    $doc['tags']='';
			    $doc['summary']=trim(string::convercharacter(string::substring(strip_tags($doc['old_text']),0,100)));
			    $doc['images']=util::getimagesnum($doc['old_text']);
			    $doc['time']=$this->time;
			    $doc['words']=string::hstrlen($doc['old_text']);
			    $doc['visible']='1';
			    $doc['cid'] = $_ENV['mwimport']->get_cid($this->wmdb,'',$doc['did']);

			    $sql="SELECT rev_user,rev_user_text FROM ".WDB_TABLEPRE."revision  WHERE rev_page = ".$doc['page_id']." ORDER BY  rev_id ";
			    $user = $this->wmdb->fetch_first($sql);

			    $this->db->query("REPLACE INTO ".DB_TABLEPRE."doc
			    (did,cid,letter,title,tag ,summary ,content,author,authorid,time,lastedit,lasteditor,lasteditorid,visible,editions)
			    VALUES (".$doc['did'].",".$doc['cid'].",'".$doc['letter']."','".$doc['title']."','".$doc['tags']."','".$doc['summary']."','".$doc['old_text']."',
			    '".$user['rev_user_text']."','".$user['rev_user']."',
			    ".$doc['time'].",".$doc['time'].",'".$user['rev_user_text']."','".$user['rev_user']."','".$doc['visible']."',1)");
			}
			$_ENV['mwimport']->writefile($this->processfile,'<?php $process = "'.$msg.'"; ?>');
		    }
		    $this->message($msg,'',2);
		}
		$msg = ($type+1).'|0';
		$_ENV['mwimport']->writefile($this->processfile,'<?php $process = "'.$msg.'"; ?>');
		$this->message($msg,'',2);
	}
}
	
?>