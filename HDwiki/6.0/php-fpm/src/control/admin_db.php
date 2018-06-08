<?php
!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( $get, $post);
		$this->load('db');
		$this->load('setting');
		$this->view->setlang($this->setting['lang_name'],'back');
	}
	/*db backup*/
	function dobackup(){
		set_time_limit(0);
		$filedir=HDWIKI_ROOT."/data/db_backup/";
		file::createaccessfile($filedir);
		if(!isset($this->post['backupsubmit'])&&!isset($this->get[9])){
			$sqlfilename=date("Ymd",$this->time)."_".util::random(8);
			$tables=$_ENV['db']->showtables();
			file::forcemkdir($filedir);
			$filename=$_ENV['db']->get_sqlfile_list($filedir);
			$this->view->assign('filename',$filename);
			$this->view->assign('filedir',$filedir);
			$this->view->assign('tables',$tables);
			$this->view->assign('sqlfilename',$sqlfilename);
			$this->view->display("admin_dbbackup");
		}else{
			$sqldump = '';
			$type=isset($this->post['type'])?$this->post['type']:$this->get[2];
			$sqlfilename=isset($this->post['sqlfilename'])?$this->post['sqlfilename']:rawurldecode($this->get[3]);
			$sizelimit=isset($this->post['sizelimit'])?$this->post['sizelimit']:intval($this->get[4]);
			$tableid = isset($this->get[5]) ? intval($this->get[5]) : 0;
			$startfrom = isset($this->get[6]) ? intval($this->get[6]) : 0;
			$volume = isset($this->get[7]) ? intval($this->get[7]) + 1 : 0;
			$compression=isset($this->post['compression'])?$this->post['compression']:intval($this->get[8]);
			$backupfilename=$filedir.$sqlfilename;
			$backupsubmit=1;
			$tables=array();
			if(substr(trim(ini_get('memory_limit')),0,-1)<32&&substr(trim(ini_get('memory_limit')),0,-1)>0){
				@ini_set('memory_limit','32M');
			}
			if(!util::is_mem_available($sizelimit*1024*3)){
				$this->message($sizelimit."KB".$this->view->lang['dblimitsizeBig'],'index.php?admin_db-backup');
			}
			switch($type){
				case "full":
					$tables=$_ENV['db']->showtables();
					break;
				case "stand":
					$tables=array(DB_TABLEPRE."category",DB_TABLEPRE."doc",DB_TABLEPRE."edition",DB_TABLEPRE."user");
					break;
				case "min":
					$tables=array(DB_TABLEPRE."doc",DB_TABLEPRE."user");
					break;
				case "custom":
					if(!(bool)$this->post['tables']){
						$tables=$this->cache->getcache('backup_tables','0');
					}else{
						$tables=$this->post['tables'];
						$this->cache->writecache('backup_tables', $tables);
					}
					break;
			}
			if($sizelimit<512){$this->message($this->view->lang['dblimitsizeSmall'],'BACK');}
			if(count($tables)==0){$this->message($this->view->lang['dbChooseOne'],'BACK');}
			if(!file_exists($filedir)) {file::forcemkdir($filedir);}
			if(!file::iswriteable($filedir)){$this->message($this->view->lang['dbFileNotWrite'],'index.php?admin_db-backup');}
			if(in_array(DB_TABLEPRE."usergroup",$tables)){
				$num=array_search(DB_TABLEPRE."usergroup",$tables);
				$tables[$num]=$tables[0];
				$tables[0]=DB_TABLEPRE."usergroup"; 
			}
			if(in_array(DB_TABLEPRE."user",$tables)){
				$num=array_search(DB_TABLEPRE."user",$tables);
				if($tables[0]==DB_TABLEPRE."usergroup"){
					$tables[$num]=$tables[1];
					$tables[1]=DB_TABLEPRE."user"; 
				}else{
					$tables[$num]=$tables[0];
					$tables[0]=DB_TABLEPRE."user"; 
				}
			}
			$complete = TRUE;
			for(; $complete && $tableid < count($tables) && strlen($sqldump) + 500 < $sizelimit * 1000; $tableid++) {
				$result=$_ENV['db']->sqldumptable($tables[$tableid],$complete,$sizelimit, $startfrom, strlen($sqldump));
				$sqldump .= $result['tabledump'];
				$complete=$result['complete'];
				if($complete) {
					$startfrom = 0;
				}else{
					$startfrom = $result['startfrom'];
				}
			}
			$dumpfile = $backupfilename."_%s".'.sql';
			!$complete && $tableid--;
			if(trim($sqldump)) {
				$result=$_ENV['db']->write_to_sql($sqldump,$dumpfile,$volume);
				if(!$result){
					$this->message($this->view->lang['dbBackupWriteSqlFiled'],'BACK');
				}else{
					$url="index.php?admin_db-backup-$type-".rawurlencode($sqlfilename)."-$sizelimit-$tableid-$startfrom-$volume-$compression-$backupsubmit";
					$this->message("<image src='style/default/loading.gif'><br />".$this->view->lang['dbBackupNext1'].$volume.$this->view->lang['dbBackupNext2']."<script type=\"text/JavaScript\">setTimeout(\"window.location.replace('$url');\", 2000);</script>",'');
				}
			}else{
				$volume--;
				if($compression && util::is_mem_available($sizelimit*1024*3*$volume)){
					$_ENV['db']->write_to_zip($backupfilename,$dumpfile,$volume);
				}
				$this->cache->removecache('backup_tables');
				$this->header("admin_db-backup");
			}
		}
	}
	
	/*db import*/
	function doimport(){
		set_time_limit(0);
		if(substr(trim(ini_get('memory_limit')),0,-1)<32&&substr(trim(ini_get('memory_limit')),0,-1)>0){
			@ini_set('memory_limit','32M');
		}
		$filename=str_replace('*','.',$this->get[2]);
		$filenum=$this->get[3]?$this->get[3]:0;
		$filedir="./data/db_backup/";
		$filetype=$this->get[4]?$this->get[4]:substr($filename,-3);
		if($filetype!='zip'&&$filetype!='sql'){
			$this->message($this->view->lang['dbBackupFormatError'],'BACK');
		}else{
			if($filenum==0){
				if($filetype=='zip'){
					require_once HDWIKI_ROOT.'/lib/zip.class.php';
					$zip=new zip();
					if(!$zip->chk_zip){
						$this->message($this->view->lang['chkziperror'],'');
					}
					$zip->Extract($filedir.$filename,$filedir);
					$filename=substr($filename,0,-4)."_0.sql";
				}else{
					$num=strrpos($filename,"_");
					$filename=substr($filename,0,$num)."_0.sql";
				}
			}
			if(file_exists($filedir.$filename)){
				$sqldump=file::readfromfile($filedir.$filename);
				preg_match('/#\sVersion:\shdwiki\s([^\n]+)\n/i',$sqldump,$hdversion);
				if($hdversion[1]!=HDWIKI_VERSION){
					$this->message($this->view->lang['dbSqlVersionError'],'index.php?admin_db-backup');
				}
				$sqlquery = $_ENV['db']->splitsql($sqldump);
				unset($sqldump);
				foreach($sqlquery as $sql) {
					$sql = $_ENV['db']->syntablestruct(trim($sql), $this->db->version() > '4.1', DB_CHARSET);
					if($sql != '') {
						$this->db->query($sql, 'SILENT');
						if(($sqlerror = $this->db->error()) && $this->db->errno() != 1062) {
							$this->db->halt('MySQL Query Error', $sql);
						}
					}
				}
				if($filetype=='zip'){
					@unlink($filedir.$filename);
				}
				$filenum++;
				$num=strrpos($filename,"_");
				$filename=str_replace('.','*',substr($filename,0,$num)."_".$filenum.".sql");
				$url="index.php?admin_db-import-$filename-$filenum-$filetype";
				$this->message("<image src='style/default/loading.gif'><br />".$this->view->lang['dbBackupNext1'].($filenum-1).$this->view->lang['dbBackupNext2']."<script type=\"text/JavaScript\">setTimeout(\"window.location.replace('$url');\", 2000);</script>",'');
			}else{
				$this->cache->removecache('import_files');
				$this->message($this->view->lang['dbSqlImportSuccess'],'index.php?admin_db-backup');
			}
		}
	}
	
	/*remove db file*/
	function doremove(){
		$filename=$this->get[2];
		$filename=str_replace('*','.',$filename);
		$filedir=HDWIKI_ROOT."/data/db_backup/".$filename;
		if(!file::iswriteable($filedir))$this->message($this->view->lang['dbFileCanNotWrite'],'index.php?admin_db-backup');
		if(file_exists($filedir)){
			unlink($filedir);
			$this->message($this->view->lang['dbSqlRemoveSuccess'],'index.php?admin_db-backup');
		}
	}
	
	/*db list*/
	function dotablelist(){
		$dbversion=mysql_get_server_info();
		$ret = $list = array();
		$chip=0;
		$ret=$_ENV['db']->show_table_status();
		$count=count($ret);
		for ($i=0;$i<$count;$i++ ){
			$res=$_ENV['db']->check_table($ret[$i]['Name']);
			$type = $dbversion>'4.1'?$ret[$i]['Engine']:$ret[$i]['Type'];
			$chartset = $dbversion>'4.1'?$ret[$i]['Collation']:'N/A';
			$list[] = array('table'=>$ret[$i]['Name'],
							'type'=>$type, 
							'rec_num'=>$ret[$i]['Rows'],
							'rec_index' =>sprintf(" %.2f KB",$ret[$i]['Data_length']/1024),
							'rec_chip'=>$ret[$i]['Data_free'] ,
							'status'=>$res['Msg_text'],'chartset'=>$chartset);
			$chip+= $ret[$i]['Data_free'];
			if($list[$i]['table']==DB_TABLEPRE."session"){
				$session_chip=$list[$i]['rec_chip'];
				$list[$i]['rec_chip']="0";
				$list[$i]['status']="OK";
			}
		}
		$number=$chip-$session_chip;
		$this->view->assign('num',$number);
		$this->view->assign('tablelist',$list);
		$this->view->display("admin_dboptimize");
	}
	
	/*db optimize*/
	function dooptimize(){
		$tables=$_ENV['db']->show_tables_like();
		$message='';
		foreach ($tables as $table){
			$tag=$_ENV['db']->optimize_table($table);
			if ($tag==0){
				$message .=str_replace('%s',$table,$this->view->lang['talbeOptimizeFail'])."<br>";
			}else{ 
				$message .=str_replace('%s',$table,$this->view->lang['talbeOptimizeSuccess'])."<br>";
			}
		}
		$this->message($message,'index.php?admin_db-tablelist');
	}
	
	/*db repair*/
	function dorepair(){
		$tables=$_ENV['db']->show_tables_like();
		$message='';
		foreach ($tables as $table){
			$tag=$_ENV['db']->repair_table($table);
			if ($tag==0){
				$message .=str_replace('%s',$table,$this->view->lang['talbeRepairFail'])."<br>";
			}else{ 
				$message .=str_replace('%s',$table,$this->view->lang['talbeRepairSuccess'])."<br>";
			}
		}
		$this->message($message,'index.php?admin_db-tablelist');
	}
	
	function dosqlwindow(){
		$this->view->assign('tablepre',DB_TABLEPRE);
		if(isset($this->post['sqlsubmit'])){
			echo '<meta http-equiv="Content-Type" content="text/html;charset='.WIKI_CHARSET.'">';
			$sql = trim(stripslashes($this->post['sqlwindow']));
			$sqltype = empty($this->post['sqltype']) ? 0 : 1;
			$this->view->assign('sql',$sql);
			if($sql==''){
				echo $this->view->lang['sqlNotice9'];
			}elseif(eregi("drop(.*)table",$sql) || eregi("drop(.*)database",$sql)){
				echo $this->view->lang['sqlNotice1'];
			}elseif(eregi("delete(.*)",$sql) || eregi("update(.*)",$sql) || eregi("insert into(.*)",$sql)){
				echo $this->view->lang['sqlNotice10'];
			}elseif(eregi("^select ",$sql) && $sqltype !== 1){
				$query=$this->db->query($sql);
				if(!$query){
					echo $this->view->lang['sqlNotice10'];
				}else{
					$num=$this->db->num_rows($query);
					if($num<=0){
						echo $this->view->lang['sqlNotice2'].$sql.$this->view->lang['sqlNotice3'];
					}else{
						echo $this->view->lang['sqlNotice2'].$sql.$this->view->lang['sqlNotice4'].$num.$this->view->lang['sqlNotice5'];
						$j = 0;
						while(($row=$this->db->fetch_array($query)) && $j<50){
							$j++;
							echo '<p style=" background-color:#d3e8fd;">'.$this->view->lang['sqlNotice52'].$j.'</p>';
							foreach($row as $k=>$v){
								echo "<font color='red'>{$k} : </font>{$v}<br/>\r\n";
							}
						}
					}
				}
			}else{
				if($sqltype==1){
					$sql = str_replace("\r","",$sql);
					$sqls = split(";[ \t]{0,}\n",$sql);
					$i=0;
					foreach($sqls as $q){
						$q = trim($q);
						if($q==""){
							continue;
						}
						if((bool)$this->db->query($q)){
							$i++;
							echo $q.'<br/>';
							echo $this->view->lang['sqlNotice8'];
						}else{
							echo $this->view->lang['sqlNotice6'].$q.$this->view->lang['sqlNotice7'];
						}
					}
				}else{
					if($query=$this->db->query($sql)){
						echo $this->view->lang['sqlNotice8'];
					}else{
						echo $this->view->lang['sqlNotice10'];
					}
				}
			}
			exit;
		}
		$this->view->display("admin_sqlwindow");
	}
	
	/*download file*/
	function dodownloadfile(){
		$filename=$this->get[2];
		$filename=str_replace('*','.',$filename);
		$filedir=HDWIKI_ROOT."/data/db_backup/".$filename;
		file::downloadfile($filedir);
	}
	
	function dostorage(){
		if(!isset($this->post['dbstoragesubmit'])){
			$this->view->display("admin_dbstorage");
		}else{
			if($this->post['db_storage']=='txt' || $this->post['db_storage']=='mysql'){
				$settings['db_storage'] = $this->post['db_storage'];
				$setting=$_ENV['setting']->update_setting($settings);
				$this->cache->removecache('setting');
				$this->message($this->view->lang['baseConfigSuccess'],'index.php?admin_db-storage');
			}else{
				$this->header('admin_db-storage');
			}
		}
	}
	
	function doconvert(){
		$this->get['3'] = isset($this->get['3']) ? $this->get['3'] : 0;
		$this->get['4'] = isset($this->get['4']) ? $this->get['4'] : '';
		if(!isset($this->post['dbconvertsubmit']) && $this->get['4']!='conversubmit'){
			$this->view->display("admin_dbconvert");
		}else{
			$type=isset($this->post['db_convert'])?$this->post['db_convert']:$this->get[2];
			$number=$this->get[3]?$this->get[3]:100;
			if(($type=='txt' || $type=='mysql') && $number%100==0){
				$complete=$_ENV['db']->editionconvert($type,$number);
				if($complete){
					$this->message($this->view->lang['convertcomplete'],'index.php?admin_db-convert');
				}else{
					$url="index.php?admin_db-convert-$type-".($number+100)."-conversubmit";
					$this->message("<image src='style/default/loading.gif'><br />".$this->view->lang['converttrip1'].($number-100).'--'.$number.$this->view->lang['converttrip2']."<script type=\"text/JavaScript\">setTimeout(\"window.location.replace('$url');\", 2000);</script>",'');
				}
			}else{
				$this->header('admin_db-convert');
			}
		}
	}
}
?>