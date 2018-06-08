<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class attachmentmodel {

	var $db;
	var $base;

	function attachmentmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function makepath($extname,$format='Ym'){
		return 'uploads/'.gmdate($format, $this->base->time + $this->base->setting['time_offset'])."/{$this->base->time}".util::random(8).'.'.strtolower($extname);
	}
	
	function add_attachment($uid,$did,$filename,$attachment,$description,$filetype='.jpg',$isimage=1,$coindown=0){
		$filesize=filesize($attachment);
		if(empty($coindown) || !is_numeric($coindown)) {
			$coindown = 0;
		}
		$this->db->query("INSERT INTO ".DB_TABLEPRE."attachment(did,time,filename,description,filetype,filesize,attachment,coindown,isimage,uid)  VALUES ($did,{$this->base->time},'$filename','$description','$filetype','$filesize','$attachment',$coindown,$isimage,$uid)");
		return $this->db->insert_id();
	}
	
	function get_attachment($variable,$value,$isimage=''){
		$attachlist=array();
		$sql = "SELECT * FROM ".DB_TABLEPRE."attachment WHERE 1=1 ";
		if($variable && $value)$sql.=" AND $variable='$value'";
		if($isimage!=''){
			$sql.=" AND isimage='$isimage'";
		}
		$query=$this->db->query($sql);
		while($attach=$this->db->fetch_array($query)){
		//if($isimage==$attach['isimage']){
				if (in_array($attach['filetype'],array('doc','docx','xls','xlsx','ppt','pptx','mdb','accdb'))){
					$attach['icon'] = 'msoffice';
				}else if (in_array($attach['filetype'],array('jpg','jpeg','bmp','gif','ico','png'))){
					$attach['icon'] = 'image';
				}else if (in_array($attach['filetype'],array('pdf','rar','zip','swf','txt','wav'))){
					$attach['icon'] = $attach['filetype'];
				}else {
					$attach['icon'] = 'common';
				}
				$attachlist[]=$attach;
		//	}
		}
		return $attachlist;
	}
	
	function update_downloads($id,$downloads=1){
		return $this->db->query("UPDATE ".DB_TABLEPRE."attachment SET downloads=downloads+$downloads WHERE id=$id");
	}
	
	function remove($id, $uid=0){
		if(is_array($id)){
			$id=implode(',',$id);
		}
		if($uid){
			$query=$this->db->query("SELECT attachment FROM ".DB_TABLEPRE."attachment WHERE id IN ($id) AND uid=$uid");
			while($attach=$this->db->fetch_array($query)){
				$url = $attach['attachment'];
				@unlink(HDWIKI_ROOT.'/'.$url);
				if (preg_match("/(jpg|gif|png|bmp|jpeg)$/i", $url)){
					@unlink(HDWIKI_ROOT.'/'.preg_replace("/(\w+)\.(\w+)$/i", "\$1_140.\$2", $url));
					@unlink(HDWIKI_ROOT.'/'.preg_replace("/(\w+)\.(\w+)$/i", "\$1_s.\$2", $url));
					@unlink(HDWIKI_ROOT.'/'.preg_replace("/(\w+)\.(\w+)$/i", "s_\$1.\$2", $url));
				}
			}
			return $this->db->query("DELETE FROM ".DB_TABLEPRE."attachment WHERE id IN ($id) AND uid=$uid");
		}else{
			$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."attachment WHERE id IN ($id)");
			while($attach=$this->db->fetch_array($query)){
				$file[] = $attach['attachment'];
				if (preg_match("/(jpg|gif|png|bmp|jpeg)$/i", $attach['attachment'])){
					$file[]=preg_replace("/(\w+)\.(\w+)$/i", "\$1_140.\$2", $url);
					$file[]=preg_replace("/(\w+)\.(\w+)$/i", "\$1_s.\$2", $url);
					$file[]=preg_replace("/(\w+)\.(\w+)$/i", "s_\$1.\$2", $url);
				}
				$sqladd.=" ('attachment','".$attach['filename']."','".addslashes(serialize($attach))."','".serialize($file)."','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->time."'),";
				unset($file);
			}
			$this->db->query("INSERT INTO ".DB_TABLEPRE."recycle (type,keyword,content,file,adminid,admin,dateline) values ".substr($sqladd,0,-1));

			return $this->db->query("DELETE FROM ".DB_TABLEPRE."attachment WHERE id IN ($id)");		
		}
	}
	
	function recover($data){
		$data=string::haddslashes($data,1);
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."attachment (id,did,time,filename,description,filetype,filesize,attachment,downloads,isimage,uid,state,focus) 
					VALUES ('".$data['id']."','".$data['did']."','".$data['time']."','".$data['filename']."','".$data['description']."','".$data['filetype']."','".$data['filesize']."','".$data['attachment']."','".$data['downloads']."','".$data['isimage']."','".$data['uid']."','".$data['state']."','".$data['focus']."')");
	}
	
	function update_desc($ids,$descs){
		$count=count($ids);
		for($i=0;$i<$count;$i++){
			$this->db->query("UPDATE ".DB_TABLEPRE."attachment SET description='".$descs[$i]."' WHERE id=".$ids[$i]);
		}
	}
	
	function get_attachment_type(){
		$typelist=array();
		$attachmenttype=explode('|',$this->base->setting['attachment_type']) ;
		foreach ($attachmenttype as $type){
			if(!empty($type)){
				$typelist[]=strtolower(trim($type));
			}
		}
		return $typelist;
	}
	
	function upload_attachment($did){
		if(!$this->base->setting['attachment_open']){
			return false;
		}
		$count=count($_FILES['attachment']['name']);
		for($i=0;$i<$count;$i++){
			if(!(bool)$_FILES['attachment']['name'][$i]){
				continue;
			}
			$attachment_type=$this->get_attachment_type();
			$filetype=strtolower(substr($_FILES['attachment']['name'][$i],strrpos($_FILES['attachment']['name'][$i],".")+1));
			if(!in_array($filetype,$attachment_type)){
				$message .=$_FILES['attachment']['name'][$i].$this->base->view->lang['attachTypeError']."<br />";
				continue;
			}
			if($this->base->setting['attachment_size'] < $_FILES['attachment']['size'][$i]/1024){
				$message .=$_FILES['attachment']['name'][$i].$this->base->view->lang['attachSizeError']."<br />";
				continue;
			}
			$destfile='data/attachment/'.date('y-m').'/'.date('Y-m-d').'_'.util::random(10).'.attach';
			file::createaccessfile('data/attachment/'.date('y-m').'/');
			$result=file::uploadfile($_FILES['attachment']['tmp_name'][$i],$destfile,$this->base->setting['attachment_size'],0);
			if($result){
				$_ENV['attachment']->add_attachment($this->base->user['uid'] ,$did,$_FILES['attachment']['name'][$i] ,$destfile ,$this->base->post['attachmentdesc'][$i],$filetype,0 );
			}
		}
		return $message;
	}
	
	function search_attach_num($cid='', $did='',$authorid='', $starttime='', $endtime='',$type='',$isimage ='', $state =0){
		$sql="SELECT  count(*)  FROM ".DB_TABLEPRE."attachment m LEFT JOIN ".DB_TABLEPRE."doc d ON d.did = m.did where 1=1 ";
		if($cid){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink  WHERE cid = $cid");
			while($category=$this->db->fetch_array($query)){
				$dids[]=$category['did'];
			}
			$dids=is_array($dids)?implode(',',$dids):'';
			if($dids){
				$sql=$sql." AND m.did IN ($dids) ";
			}else{
				$sql=$sql." AND 1!=1 ";
			}
		}
		if($did){
			$sql=$sql." AND m.did ='$did' ";
		}
		if($authorid){
			$sql=$sql." AND m.uid='$authorid' ";
		}
		if($type){
			$sql=$sql." AND m.filetype='$type' ";
		}
		if($starttime){
			$sql=$sql." AND m.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND m.time<=$endtime ";
		}
		if($isimage){
			$sql=$sql." AND m.isimage=$isimage ";
		}
		$sql=$sql." AND m.state=$state ";
		return $this->db->result_first($sql);
	}
	
	function search_attachment($start=0,$limit=10, $cid='', $did='',$authorid='', $starttime='', $endtime='',$type='',$isimage ='', $state =0){
		$doclist=array();
		$attachlist=array();
		$sql="SELECT  m.id,m.did,m.description,m.attachment,m.downloads,m.filesize,m.isimage,m.filename,m.uid,m.time,m.state,m.focus,d.title title,u.username author FROM ".DB_TABLEPRE."attachment m LEFT JOIN ".DB_TABLEPRE."doc d ON d.did = m.did LEFT JOIN ".DB_TABLEPRE."user u ON u.uid=m.uid where 1=1 ";
		if($cid){
			$query=$this->db->query("SELECT did FROM ".DB_TABLEPRE."categorylink  WHERE cid = $cid");
			while($category=$this->db->fetch_array($query)){
				$dids[]=$category['did'];
			}
			$dids=is_array($dids)?implode(',',$dids):'';
			if($dids){
				$sql=$sql." AND m.did IN ($dids) ";
			}else{
				$sql=$sql." AND 1!=1 ";
			}
		}
		if($did){
			$sql=$sql." AND m.did ='$did' ";
		}
		if($authorid){
			$sql=$sql." AND m.uid='$authorid' ";
		}
		if($type){
			$sql=$sql." AND m.filetype='$type' ";
		}
		if($starttime){
			$sql=$sql." AND m.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND m.time<=$endtime ";
		}
		
		if($isimage){
			$sql=$sql." AND m.isimage=$isimage ";
		}
		$sql=$sql." AND m.state=$state ";
		$sql=$sql." ORDER BY m.time DESC LIMIT $start,$limit ";
		$query=$this->db->query($sql);
		while($attach=$this->db->fetch_array($query)){
			$attach['time'] = $this->base->date($attach['time']);
			$attach['title'] = htmlspecialchars($attach['title']);
			$attach['filename'] = string::substring($attach['filename'],0,28);
			$attach['filename'] .= (strlen($attach['filename'])>28)?"......":"";
			$attach['filesize']=sprintf('%.2f',$attach['filesize']/1024)."k";
			$attachlist[]=$attach;
		}
		return $attachlist;
	}
	
	function insert_image_js($images,$link=''){
		if (empty($link)) $link = $images;
		echo '<html>';
		echo '<head>';
		echo '<title>Insert Image</title>';
		echo "<meta http-equiv='content-type' content='text/html; charset=".WIKI_CHARSET."'>";
		echo '</head>';
		echo '<body>';
		echo '<script type="text/javascript">parent.jQEditor.plugin("HdImage").insert("'.$images.'","'.$link.'");</script>';
		echo '</body>';
		echo '</html>';
	}
	
	function showmsg($msg){
		echo '<html>';
		echo '<head>';
		echo '<title>error</title>';
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".WIKI_CHARSET."\">";
		echo '</head>';
		echo '<body>';
		//echo '<input id = "msg" type="hidden" value ="'.$msg.'">';
		echo '<script type="text/javascript">var plugin = parent.jQEditor.plugin("HdImage");plugin.error("'.$msg.'");plugin.staticAttr.uploadIsBusy = false</script>';
		echo '</body>';
		echo '</html>';
	}
	
	function editimage($id,$variable,$value){
		$ids = implode(',',$id);
		return $this->db->query("UPDATE ".DB_TABLEPRE."attachment SET $variable=$value WHERE id in ($ids)");
	}
	
}
?>