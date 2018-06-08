<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class docmodel {

	var $db;
	var $base;

	function docmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	/*Description: This method has already expired*/
	function get_doc($docid){
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."doc WHERE did='$docid' ");
		return $this->db->fetch_array($query);
	}
	function get_lastdoc($docid,$doctime){
		$query=$this->db->query("SELECT a.*,b.edits,b.editions,b.views,b.lastedit,b.lasteditor,b.lasteditorid,b.comments from ".DB_TABLEPRE."edition a," .DB_TABLEPRE."doc b WHERE a.did='$docid' AND a.time<'".$doctime."' AND a.did=b.did ORDER BY eid DESC");
		return $this->db->fetch_array($query);
	}
	
	/*Description: This method has already expired*/
	function get_doc_by_title($title) {
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."doc WHERE title='$title' ");
		return $this->db->fetch_array($query);
	}
	
	function get_doc_by_ids($id) {
		$doclist=array();
		$ids=implode(",",$id);
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."doc  WHERE did IN ($ids)");
		while($doc=$this->db->fetch_array($query)){
			$doc['rawtitle']=$doc['title'];
			$doc['title']=htmlspecial_chars($doc['title']);
			$doc['time']=$this->base->date($doc['time']);
			$doc['tag']=$this->spilttags($doc['tag']);
			$doclist[]=$doc;
		}
		return $doclist;
	}

	function update_field($field,$value,$did,$type=1){
		if($type){
			$sql="UPDATE ".DB_TABLEPRE."doc SET $field='$value' WHERE did= $did ";
		}else{
			$sql="UPDATE ".DB_TABLEPRE."doc SET $field=$field+$value WHERE did= $did ";
		}
		$this->db->query($sql);
	}
 
	function is_autosave($uid,$did){
 		$autosave=$this->db->fetch_first("SELECT * from ".DB_TABLEPRE."autosave WHERE uid = '$uid' AND did = '$did'");
 		return $autosave;
	}
 
 	function get_autosave_by_uid($uid,$start,$limit){
 		$query=$this->db->query("SELECT a.*,d.title from ".DB_TABLEPRE."autosave as a LEFT JOIN ".DB_TABLEPRE."doc as d ON a.did=d.did WHERE uid = '$uid' LIMIT $start,$limit");
		while($save=$this->db->fetch_array($query)){
			$save['rawtitle']=$save['title'];
			$save['time']=$this->base->date($save['time']);
			$savelist[]=$save;
		}
		return $savelist;
	}
	
  	function get_autosave_number($uid){
		return $this->db->result_first("SELECT count(*) num from ".DB_TABLEPRE."autosave WHERE uid = '$uid'");
	}
	
 	function update_autosave($uid,$did,$content,$id=-1,$notfirst=0){
 		if($notfirst>0){
 			$sql="UPDATE ".DB_TABLEPRE."autosave SET content='$content',time='".$this->base->time."' WHERE uid = '$uid' AND did = '$did'";
 			return $this->db->query($sql);
 		}else{
	 		$autosave=$this->is_autosave($uid,$did);
			if($autosave){
				$sql="UPDATE ".DB_TABLEPRE."autosave SET content='$content',time='".$this->base->time."' WHERE uid = '$uid' AND did = '$did'";
			}else{
				$sql = "INSERT INTO ".DB_TABLEPRE."autosave (uid, did, id, content,time) VALUES('$uid', '$did', '$id', '$content', '".$this->base->time."')";
			}
			return $this->db->query($sql);
		}
	} 
	
  	function del_autosave($aid,$uid='',$did=''){
 			if($aid != ''){
 				$sql="DELETE FROM ".DB_TABLEPRE."autosave WHERE aid in ($aid)";
 			}elseif($uid!='' && $did!=''){
 				$sql="DELETE FROM ".DB_TABLEPRE."autosave WHERE uid = '$uid' AND did = '$did'";
 			}else{
 				return false;
 			}
			return $this->db->query($sql);
	}
	
	function get_recenteditor($docid,$limit=5, $unuid=0) {
		$editionlist=array();
		$query=$this->db->query("SELECT MAX(`time`) time,e.authorid,e.author FROM ".DB_TABLEPRE."edition e WHERE e.authorid<>$unuid AND did='$docid' GROUP BY authorid ORDER BY time DESC LIMIT $limit");
		while($edition=$this->db->fetch_array($query)){
			$edition['time']=$this->base->date($edition['time']);
			$edition['title']=htmlspecial_chars($edition['title']);
			$editionlist[]=$edition;
		}
		return $editionlist;
	}
	
	function get_editor_num($did){
		$authorid = array();
		$query=$this->db->query("SELECT authorid FROM ".DB_TABLEPRE."edition WHERE did=$did");
		while($result=$this->db->fetch_array($query)){
			$authorid[] = $result['authorid'];
		}
		$authorid = array_unique($authorid);
		return count($authorid);
	}
	
	function get_similardoc($cid,$limit=6) {
		$doclist=array();
		$query=$this->db->query("SELECT title,did FROM ".DB_TABLEPRE."doc WHERE cid='$cid' ORDER BY lastedit DESC LIMIT $limit");
		while($doc=$this->db->fetch_array($query)){
			$doc['title']=htmlspecial_chars($doc['title']);
			$doclist[]=$doc;
		}
		return $doclist;
	}

	function get_docs_by_cid($cid,$start=0,$limit=5,$letter="0") {
		$doclist=array();
		if(is_array($cid)){
			$cid=implode(",",$cid);
		}
		$sql="SELECT distinct(a.did),title,author,authorid,`time`,tag,summary,edits,views FROM ".DB_TABLEPRE."doc a INNER JOIN ".DB_TABLEPRE."categorylink b WHERE a.did=b.did AND b.cid IN ($cid) ";
		if($letter!="0"){
			$sql .=" AND a.letter='".strtoupper($letter)."'";
		}
		$query=$this->db->query($sql."GROUP BY a.did ORDER BY a.lastedit DESC LIMIT $start,$limit");
		while($doc=$this->db->fetch_array($query)){
			$doc['tag']=$this->spilttags($doc['tag']);
			$doc['time']=$this->base->date($doc['time']);
			$doc['rawtitle']=$doc['title'];
			$doc['title']=htmlspecial_chars($doc['title']);
			$doclist[]=$doc;
		}
		return $doclist;
	}
	
	function get_totalnum_by_cid($cid,$letter="0") {
		if(is_array($cid)){
			$cid=implode(",",$cid);
		}
		$sql="SELECT COUNT(DISTINCT(a.did)) AS num FROM ".DB_TABLEPRE."doc a INNER JOIN ".DB_TABLEPRE."categorylink b WHERE a.did=b.did AND b.cid IN ($cid)";
		if($letter!="0"){
			$sql .=" AND a.letter='".strtoupper($letter)."'";
		}
		return $this->db->result_first($sql);
	}
	
	/*Description: This method has already expired*/
	function get_total_num() {
		return $this->db->result_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."doc WHERE 1");
	}

	/*Description: This method has already expired*/
	function get_edits_total_num() {
		return $this->db->result_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."doc WHERE edits>0");
	}

	/*Description: This method has already expired*/
	function get_letter_total_num($letter) {
		return $this->db->result_first("SELECT COUNT(*) num FROM ".DB_TABLEPRE."doc WHERE letter='$letter'");
	}
	
	function add_doc_placeholder($doc){
		$_doc=$this->get_doc_by_title($doc['title']);
		if(empty($_doc)){
			$t= time();
			$v= $this->base->setting['verify_doc']?'0':'1';;
			$sql = "INSERT INTO ".DB_TABLEPRE."doc (`author`,`authorid`,`lasteditor`,`lasteditorid`,`title`, `time`,`lastedit`,`visible`) "
				 ."VALUES('".$this->base->user['username']."','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->user['uid']."','".$doc['title']."', $t, $t, $v)";
			$this->db->query($sql);
			$did = $this->db->insert_id();
			$this->add_doc_category($did, $doc['cid']);
			return $did;
		}else{
			return $_doc['did'];
		}
	}

	function add_doc_category($dids, $cidstr){
		$sql = '';
		$dids = is_array($dids) ? $dids : array($dids);
		$cids = explode(",", $cidstr);
		foreach ($dids as $did){
			$sql = "INSERT INTO ".DB_TABLEPRE."categorylink (`did`,`cid`) VALUES ";
			for($i=0; $i<count($cids); $i++){
				$sql .= "('".$did."','".$cids[$i]."'),";
			}
			$sql = substr($sql,0,-1) . ';';
			$this->db->query($sql);
		}
		$this->db->query("UPDATE ".DB_TABLEPRE."category SET docs=docs+".count($dids)." WHERE cid IN ($cidstr)");
	}

	function del_doc_category($dids){
		$dids = is_array($dids) ? $dids : array($dids);
		$dids = implode(",", $dids);	
		$query=$this->db->query("SELECT cid FROM ".DB_TABLEPRE."categorylink WHERE did IN ($dids)");
		while($category=$this->db->fetch_array($query)){
			$cats[]=$category['cid'];
		}
		if($cats){
			$cats = implode(",", $cats);
			$this->db->query("UPDATE ".DB_TABLEPRE."category SET docs=docs-1 WHERE cid IN ($cats)");
		}
		$this->db->query("DELETE FROM ".DB_TABLEPRE."categorylink WHERE did IN ($dids)");
	}

	function add_doc($doc) {
		$editions = ($this->base->setting['base_createdoc']==1)?1:0;
		$doc['title'] = trim($doc['title']);
		if ($doc['did']){
			$this->db->query("REPLACE INTO ".DB_TABLEPRE."doc
			(did,letter,title,tag ,summary ,content,author,authorid,time,lastedit,lasteditor,lasteditorid,visible,editions)
			VALUES (".$doc['did'].",'".$doc['letter']."','".$doc['title']."','".$doc['tags']."','".$doc['summary']."','".$doc['content']."',
			'".$this->base->user['username']."','".$this->base->user['uid']."',
			".$doc['time'].",".$doc['time'].",'".$this->base->user['username']."','".$this->base->user['uid']."','".$doc['visible']."',$editions)");
			$did = $doc['did'];
			$this->db->query("DELETE FROM ".DB_TABLEPRE."autosave WHERE did=".$did." AND uid=".$this->base->user['uid']);
		}else{
			$this->db->query("INSERT INTO ".DB_TABLEPRE."doc
			(letter,title,tag ,summary ,content,author,authorid,time,lastedit,lasteditor,lasteditorid,visible,editions)
			VALUES ('".$doc['letter']."','".$doc['title']."','".$doc['tags']."','".$doc['summary']."','".$doc['content']."',
			'".$this->base->user['username']."','".$this->base->user['uid']."',
			".$doc['time'].",".$doc['time'].",'".$this->base->user['username']."','".$this->base->user['uid']."','".$doc['visible']."',$editions)");
			$did = $this->db->insert_id();
			$this->add_doc_category($did, $doc['category']);
			$this->db->query("DELETE FROM ".DB_TABLEPRE."autosave WHERE did=".$did." AND uid=".$this->base->user['uid']);
		}
		if($this->base->setting['base_createdoc']==1){
			$this->db->query("INSERT INTO ".DB_TABLEPRE."edition
			(did,author,authorid,time,ip,title,tag,summary,content,words,images )
			VALUES ($did,'".$this->base->user['username']."','".$this->base->user['uid']."',
			'".$doc['time']."','".$this->base->ip."','".$doc['title']."','".$doc['tags']."','".$doc['summary']."','".$doc['content']."','".$doc['words']."','".$doc['images']."')");
		}
		return $did;
	}
	
	function edit_doc($doc,$edittype='1',$increase_edition=true) {
		if($this->base->setting['base_createdoc']==1){
			$edition = $doc;
		}else{
			$edition=$this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."doc WHERE did=".$doc['did']);
			$edition=string::haddslashes($edition,1);
		}

		$edition_sql = $increase_edition ? 'edits=edits+1,editions=editions+1,' : '';
		$this->db->query("UPDATE ".DB_TABLEPRE."doc SET
		tag='".$doc['tags']."' ,summary='".$doc['summary']."' ,content='".$doc['content']."',lastedit='".$doc['time']."',
		lasteditor='".$this->base->user['username']."',lasteditorid='".$this->base->user['uid']."',{$edition_sql}visible='".$doc['visible']."' WHERE did=".$doc['did']);
		
		$words=string::hstrlen($edition['content']);
		$images=util::getimagesnum($edition['content']);
		if(!empty($this->base->setting['db_storage']) && $this->base->setting['db_storage']=='txt'){
			$content=stripslashes($edition['content']);
			$edition['content']='';
		}
		if($increase_edition == true) {
			$this->db->query("INSERT INTO ".DB_TABLEPRE."edition
			(did,author,authorid,time,ip,title,tag,summary,content,words,images,reason,`type`)
			VALUES ('".$edition['did']."','".$this->base->user['username']."','".$this->base->user['uid']."','".$edition['lastedit']."','".$this->base->ip."','".$edition['title']."','".$edition['tags']."','".$edition['summary']."','".$edition['content']."','$words','$images','".$doc['reason']."','$edittype')");
			$eid = $this->db->insert_id();
		} else {
			$last_edition = $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."edition WHERE did = {$doc['did']} AND authorid = {$this->base->user['uid']} ORDER BY eid DESC LIMIT 1");
			if(isset($last_edition['eid'])) {
				$eid = $last_edition['eid'];
				$sql = "UPDATE ".DB_TABLEPRE."edition SET `time`='".$edition['lastedit']."',";
				$sql.= "ip='".$this->base->ip."',";
				$sql.= "title='".$edition['title']."',";
				$sql.= "tag='".$edition['tags']."',";
				$sql.= "summary='".$edition['summary']."',";
				$sql.= "content='".$edition['content']."',";
				$sql.= "words='".$words."',";
				$sql.= "images='".$images."',";
				$sql.= "reason='".$doc['reason']."',";
				$sql.= "`type`='".$edittype."' where eid = {$eid}";
				$this->db->query($sql);
			} else {
				$eid = 0;
			}
		}
		if(!empty($this->base->setting['db_storage']) && $this->base->setting['db_storage']=='txt'){
			file::forcemkdir($this->get_edition_fileinfo($eid,'path'));
			file::writetofile($this->get_edition_fileinfo($eid,'file'),$content);
		}
	}
	
	function edit_unaudit_doc($doc){
		return $this->db->query("UPDATE ".DB_TABLEPRE."doc SET
		tag='".$doc['tags']."' ,summary='".$doc['summary']."' ,content='".$doc['content']."',lastedit='".$doc['time']."',
		lasteditor='".$this->base->user['username']."',lasteditorid='".$this->base->user['uid']."' WHERE did=".$doc['did']);
	}

	//function splithtml($html,$preg='/(<div\s+class=\"?hdwiki_tmml\"?\s*>.+?<\/div>)/i'){
	function splithtml($html,$preg='/(<h2>.+?<\/h2>)/i'){
		$arrhtml=preg_split($preg,$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		$count=count($arrhtml);
		for($i=0;$i<$count;$i++){
			if(preg_match($preg,$arrhtml[$i])){
				//preg_match('/hdwiki_tmm(l+)/i',$arrhtml[$i],$l_num);
				$resarr[$i]['value']=strip_tags($arrhtml[$i]);
				//$resarr[$i]['flag']=strlen($l_num[1]);
				$resarr[$i]['flag']=1;
				continue;
			}
			$resarr[$i]['value']=$arrhtml[$i];
			$resarr[$i]['flag']=0;
		}
		unset($arrhtml);
        //print_r($resarr);
		return $resarr;
	}

	function joinhtml($arrhtml){
		$html='';
		$count=count($arrhtml);
		for($i=0;$i<$count;$i++){
			if($arrhtml[$i]['flag']==1){
				$html.="<h2>".$arrhtml[$i]['value']."</h2>";
			}else {
				$html.=$arrhtml[$i]['value'];
			}
		}
		return $html;
	}

	function getsections($section){
		$sectionlist=array();
		$secounts = count($section);
		for($i=0;$i<$secounts;$i++){
			if($section[$i]['flag']==1){
				$sectionlist[]=array('key'=>$i,'value'=>$section[$i]['value']);
			}
		}
		unset($section);
		return $sectionlist;
	}
	
	function spilttags($tag){
		$taglist=array();
		$tags=split(';',$tag) ;
		foreach ($tags as $tag){
			if(!empty($tag)){
				$taglist[]=$tag;
			}
		}
		return $taglist;
	}

	function jointags($tags){
		$taglist=array();
		foreach ($tags as $tag){
			if(!empty($tag)){
				$taglist[]=strip_tags(trim($tag));
			}
		}
		return implode(';',array_unique($taglist));
	}
	
	function auto_picture($content, $did=0){
		set_time_limit(0);
		preg_match_all('/<img[^>\n]+?src=[^>\n]*?(http:\/\/[^<>\s]+?(?:gif|jpg|png|jpeg)).*?>/is',$content,$imgs);
		if((bool)$imgs){
			$this->base->load('attachment');
			$links=$imgs[0];
			$imgs=$imgs[1];
			$host=($parse=@parse_url(WIKI_URL))?$parse['host']:'';
			$i=0;
			foreach($imgs as $img){
				$parse=($parse=parse_url($img))?$parse['host']:'';
				if(strpos($host,$parse)!==false){
					$i++;
					continue;
				}
				$ishd=strpos($imgs[$i],'.hudong.com');
				if(false===$ishd){
					$replace="/<a[^>]+?href=[^>]*?>[\s]*?".str_replace('/','\/',addslashes($links[$i]))."[\s]*?<\/a>/is";
					preg_match($replace,$content,$linkimg);
					if((bool)$linkimg){
						$content=str_replace($linkimg[0],$links[$i],$content);
					}
					$tmpname=$_ENV['attachment']->makepath(file::extname($img));
				}else{
					$search = array("http://",".att.hudong.com/","/");
					$replace = array('','_','_');
					$newimg = str_replace($search, $replace, $img);
					$tmpname='uploads/hdpic/'.$newimg;
					if(file_exists($tmpname)){
						$content=str_replace($links[$i],str_replace($img,$tmpname,$links[$i]),$content);
						$i++;
						continue;
					}
				}
				if($pic_content=@util::hfopen($img)){
					file::forcemkdir(dirname($tmpname));
					if(file::writetofile($tmpname,$pic_content)){
						$iamge140=util::image_compress($tmpname,'',140,140,'_140');
						$content=str_replace($links[$i],str_replace($img,$tmpname,$links[$i]),$content);
						
						$ext=file::extname($tmpname);
						$filename = basename($tmpname);
						$uid = $this->base->user['uid'];
						$_ENV['attachment']->add_attachment($uid,$did,$filename,$tmpname, '', $ext);
					}
				}
				$i++;
			}
		}
		return $content;
	}
	
	function iseditlocked($did){
		$editlock=$this->get_editlock_instance($did);
		if(empty($editlock)){
			return 0;
		}else {
			if(  ($this->base->time-$editlock['time']) >180 ){
				return 0;
			}else {
				return $editlock['uid'];
			}
		}
	}

	function  refresheditlock($did,$uid){
		$editlock=$this->get_editlock_instance($did);
		$editlocktime=$this->base->time;
		if(empty($editlock)){
			$this->db->query("INSERT INTO ".DB_TABLEPRE."lock(did,uid,time) VALUES ($did,$uid,'$editlocktime') ");
		}else {
			$this->db->query("UPDATE ".DB_TABLEPRE."lock SET uid=$uid,time='$editlocktime' WHERE did=$did");
		}
	}

	function unset_editlock($did,$uid){
		$this->db->query(" DELETE FROM ".DB_TABLEPRE."lock WHERE did=$did AND uid=$uid");
		return true;
	}
	
	function get_editlock_instance($did){
		$query = $this->db->query(" SELECT * FROM ".DB_TABLEPRE."lock WHERE did=$did");
		return $this->db->fetch_array($query);
	}
	
	function get_list($type=1,$letter,$start=0,$limit=10){
		$doclist=array();
		$categorylist=array();
		$query=$this->db->query("SELECT name,cid FROM ".DB_TABLEPRE."category");
		while($category=$this->db->fetch_array($query)){
			$categorylist[$category['cid']]=$category['name'];
		}
		$sql=" SELECT d.did, d.cid,d.letter,d.title,d.tag,d.summary,d.content,d.author,d.authorid,d.time,d.lastedit,d.lasteditor,d.lasteditorid,d.views,d.edits,d.editions,d.visible,d.locked  FROM ".DB_TABLEPRE."doc d WHERE 1=1 ";
		if(1==$type){
			$sql .="  ORDER BY d.`lastedit` DESC LIMIT $start,$limit";
		}else{
			$sql .=" AND d.letter='$letter' ORDER BY d.`time` DESC LIMIT $start,$limit";
		}
		$query = $this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			
			$doc['iscreate']=($doc['time']==$doc['lastedit']);
			$doc['lastedit']=$this->base->date($doc['lastedit']);
			$doc['time']=$this->base->date($doc['time']);
			$doc['rawtitle']=$doc['title'];
			$doc['title']=htmlspecial_chars($doc['title']);
			$doc['shorttitle']=(string::hstrlen($doc['title'])>16)?string::substring($doc['title'],0,16)."...":$doc['title'];
			$doc['category']=empty($doc['cid']) ? NULL : $categorylist[$doc['cid']];
			$doc['img'] = util::getfirstimg($doc['content']);
			$doclist[]=$doc;
		}

		return $doclist;
	}
	
	function get_rss($type='doc',$start=0,$limit=10,$doctype=1){
		$doclist=array();
		$limit = $limit?$limit:10;
		if('doc'==$type){
			$sql ="SELECT did,title,content,author,time FROM ".DB_TABLEPRE."doc d WHERE 1=1 ORDER BY d.`lastedit` DESC LIMIT $start,$limit";
		}elseif('focus'==$type){
			$sql="SELECT d.did,d.title,d.author,d.content,d.time FROM ".DB_TABLEPRE."doc d ,".DB_TABLEPRE."focus f WHERE d.did=f.did AND f.type=$doctype ORDER BY F.time DESC LIMIT $start,$limit";
		}
		$query = $this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			$doc['time']=$this->base->date($doc['time']);
			$doc['title']=htmlspecial_chars($doc['title']);
			$doclist[]=$doc;
		}
		return $doclist;
	}
	
	function get_list_cache($type=1,$letter='',$start=0,$limit=10){
		$return = array();
		$i = 0;
		$cache=$this->base->cache->getcache("list_{$letter}_{$type}",$this->base->setting['list_cache_time']);
		if(!(bool)$cache){
			$list=$this->get_list($type,$letter,0,200);
			if(!empty($list)) {
				foreach($list as $doc){
					if($doc['visible']==1){
						$cache[]=$doc;
						if($i>=$start&&$i<$start+$limit){
							$return[]=$doc;
						}
						$i++;
					}
					if($i>=100){
						break;
					}
				}
			}
			$this->base->cache->writecache("list_{$letter}_{$type}",$cache);
		}else{
			foreach($cache as $doc){
				if($i>=$start&&$i<$start+$limit){
						$return[]=$doc;
				}
				$i++;
			}
		}
		return $return;
	}
	
	function get_list_total($type=1,$letter=''){
		$cache=(array) $this->base->cache->getcache("list_{$letter}_{$type}",$this->base->setting['list_cache_time']);
		return count($cache);
	}
	
	function get_focus_list($start=0,$limit=10,$type=0){
		$focuslist=array();
		if(empty($limit)){$limit=10;}
		$sql = " SELECT * FROM ".DB_TABLEPRE."focus WHERE 1=1 ";
		if($type!=0){
			$sql.=" AND `type`=$type ";
		}
		$sql.="ORDER BY displayorder ASC,time DESC LIMIT $start,$limit";
		$query = $this->db->query($sql);
		while($focus=$this->db->fetch_array($query)){
			$focus['time']=$this->base->date($focus['time']);
			if(empty($focus['image'])){
				if($type==3){
					$focus['image']='style/default/recommend.jpg';
				}else{
					$focus['image']='style/default/recommend_l.jpg';
				}
			}
			$focus['shorttitle']=(string::hstrlen($focus['title'])>12)?string::substring($focus['title'],0,12)."...":$focus['title'];
			$focus['rawtitle']=$focus['title'];
			$img=explode('/',$focus['image']);
			$focus['simage']=$img[0].'/'.$img[1].'/s_f_'.$img[2];
			$focuslist[]=$focus;
		}
		return $focuslist;
	}
	
	/*Description: This method has already expired*/
	function get_focus_total_num(){
		$total=$this->db->fetch_first(" SELECT COUNT(*) num FROM ".DB_TABLEPRE."focus WHERE 1=1 ");
		return $total['num'];
	}

	function set_focus_doc($dids,$doctype){
		$doclist=$this->get_doc_by_ids($dids);
		foreach($doclist as $key=>$doc){
			$title = string::haddslashes($doc['rawtitle'],1);
			$tag = string::haddslashes($this->jointags($doc['tag']),1);
			if($doctype==2){
				$summary =trim(string::convercharacter(string::substring(strip_tags($doc['content']),0,30)));
			}elseif($doctype==3){
				$summary =trim(string::convercharacter(string::substring(strip_tags($doc['content']),0,80)));
			}else{
				$summary =trim(string::convercharacter(string::substring(strip_tags($doc['content']),0,100)));
			}
			$summary = string::haddslashes($summary,1);
			$image = $this->setfocusimg(util::getfirstimg($doc['content']));
			$this->db->query("REPLACE INTO ".DB_TABLEPRE."focus (did,title,tag,summary,image,time,type)VALUES (".$doc['did'].",'$title','$tag','$summary','$image','".$this->base->time."','$doctype')");
		}
		return true;
	}

	
	function setfocusimg($img){
		if(''==$img){
			return '';
		}
		if(substr($img,0,strlen(WIKI_URL))==WIKI_URL){
			$img=substr($img,strlen(WIKI_URL)+1);
		}
		if("http://"==substr($img,0,7) && substr($img,0,strlen(WIKI_URL))!=WIKI_URL){
			$tmpname='uploads/'.date("Ym")."/".util::random().'.'.file::extname($img);
			if($pic_content=@util::hfopen($img)){
				file::forcemkdir(dirname($tmpname));
				if(file::writetofile($tmpname,$pic_content)){
					$img=$tmpname;
				}
			}
		}
		
		$compress=util::image_compress($img,'s_f_',100,75);
		if(!$compress['result']){
			return '';
		}
		util::image_compress($img,'f_',152,114);
		@unlink($tmpname);
		return $compress['tempurl'];
	}
	
	function change_category($dids,$cidstr){
		$this->del_doc_category($dids);
		$this->add_doc_category($dids, $cidstr);
		return true;
	}
	
	function change_name($did,$title){
		$letter=string::getfirstletter($title);
		if($this->db->query("UPDATE ".DB_TABLEPRE."doc SET title='$title',letter='$letter' WHERE did=$did")){
			$this->db->query("UPDATE ".DB_TABLEPRE."edition SET title='$title' WHERE did=$did");
			return true;
		}else{
			return false;
		}
	}
	
	function lock($dids,$lock=1){
		$dids = implode(',',$dids);
		if($this->db->query("UPDATE ".DB_TABLEPRE."doc SET locked=$lock WHERE did IN ($dids)")){
			return true;
		}else{
			return false;
		}
	}

	function get_edition_list($did, $field='*', $max_eid=0){
		$editionlist=array();
		$sql = " SELECT $field ,eid FROM ".DB_TABLEPRE."edition WHERE did=$did ";
		if ($max_eid){
			$sql .= " AND eid<=$max_eid ORDER BY eid DESC limit 2";
		}else{
			$sql .= " ORDER BY eid DESC";
		}
		
		$query=$this->db->query($sql);
		while($edition=$this->db->fetch_array($query)){
			$edition['time']=$this->base->date($edition['time']);
			$edition['rawtitle']=$edition['title'];
			$edition['title']=htmlspecial_chars($edition['title']);
			if(!$edition['content']){
				$edition['content']=file::readfromfile($this->get_edition_fileinfo($edition['eid'],'file'));
			}
			$editionlist[]=$edition;
		}
		return $editionlist;
	}
	
	function audit_doc($dids){
		foreach($dids as $did){
			$doc=$this->get_doc($did);
			if($doc['visible']==1){
				continue;
			}
			if($doc['authorid']&&$doc['editions']>0){
				$_ENV['user']->add_credit($doc['lasteditorid'],'doc-edit',$this->base->setting['credit_edit'], $this->base->setting['coin_create']);
			}elseif($doc['authorid']&&$doc['editions']==0){
				$_ENV['user']->add_credit($doc['authorid'],'doc-create',$this->base->setting['credit_create'], $this->base->setting['coin_edit']);
			}
		}
		$dids = implode(',',$dids);
		if($this->db->query("UPDATE ".DB_TABLEPRE."doc SET visible = '1' WHERE did IN ($dids) ")){
			return true;
		}else{
			return false;
		}
	}
	
	function remove_doc ($did){
		global $content,$keyword;
		$uids=array();
		$titles=array();
		if(!is_array($did)){
			$did = array($did);
		}
		$dids = implode(',',$did);
		$query=$this->db->query("SELECT authorid,title FROM ".DB_TABLEPRE."doc WHERE did IN ($dids)");
		while($row=$this->db->fetch_array($query)){
			$uids[] = $row['authorid'];
			$titles[] = addslashes($row['title']);
		}
		
		$query=$this->db->query("SELECT did,attachment FROM ".DB_TABLEPRE."attachment WHERE did IN ($dids)");
		while($row=$this->db->fetch_array($query)){
			if(empty($file[$row['did']])) $file[$row['did']]=array();
			$file[$row['did']][]=$row['attachment'];
			if (strpos($row['attachment'], 'hdpic') === false && preg_match("/(jpg|gif|png|bmp|jpeg)$/i", $row['attachment'])){
				$file[$row['did']][]=preg_replace("/(\w+)\.(\w+)$/i", "s_\$1.\$2", $row['attachment']);
				$file[$row['did']][]=preg_replace("/(\w+)\.(\w+)$/i", "\$1_140.\$2", $row['attachment']);
				$file[$row['did']][]=preg_replace("/(\w+)\.(\w+)$/i", "\$1_s.\$2", $row['attachment']);
			}
		}
		
		$query=$this->db->query("SELECT did,`image` FROM ".DB_TABLEPRE."focus WHERE did IN ($dids)");
		while($row=$this->db->fetch_array($query)){
			if($row['image']){
				if(empty($file[$row['did']])) $file[$row['did']]=array();
				$file[$row['did']][]=$row['image'];
				$file[$row['did']][]=str_replace('s_f_', 'f_', $row['image']);
			}
		}
		foreach($did as $id){
			$this->fetch_all('doc',$id,'title');
			$this->fetch_all('categorylink',$id,'');
			$this->fetch_all('edition',$id,'');
			$this->fetch_all('focus',$id,'');
			$this->fetch_all('comment',$id,'');
			$this->fetch_all('attachment',$id,'');
			$this->fetch_all('synonym',$id,'','destdid');
			$this->db->query("INSERT INTO ".DB_TABLEPRE."recycle (type,keyword,content,file,adminid,admin,dateline) values ('doc','".addslashes($keyword)."','".addslashes(serialize($content))."','".serialize($file[$id])."','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->time."'); ");
			$keyword="";
			$content="";
		}
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."doc WHERE did IN ($dids) ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."edition WHERE did IN ($dids) ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."focus WHERE did IN ($dids) ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."comment WHERE did IN ($dids) ");
		$this->db->query("DELETE FROM ".DB_TABLEPRE."attachment WHERE did IN ($dids) ");
		$this->db->query("DELETE FROM ".DB_TABLEPRE."synonym WHERE destdid IN ($dids) ");
		$this->del_doc_category($dids);
		$credit = -$this->base->setting['credit_create'];
		foreach($uids as $uid){
			$this->db->query("INSERT INTO ".DB_TABLEPRE."creditdetail(uid,operation,credit2,time) VALUES ($uid,'remove_doc',$credit,{$this->base->time}) ");
			$creates=$this->db->result_first("SELECT creates FROM ".DB_TABLEPRE."user WHERE uid=$uid");
			$creates=$creates?$creates-1:0;
			$this->db->query("UPDATE ".DB_TABLEPRE."user SET credit2=credit2+$credit,creates=$creates WHERE uid=$uid ");
		}
		
		$titles = join("','", $titles);
		$this->db->query("update ".DB_TABLEPRE."innerlinkcache set titleid='0' where title in('$titles')");
	}
	
	function recover($data){
		$data=string::haddslashes($data,1);
		for($i=0,$count=count($data['doc']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."doc (did,cid,letter,title,tag,summary,content,author,authorid,time,lastedit,lasteditor,lasteditorid,views,edits,editions,comments,votes,visible,locked) 
			VALUES ('".$data['doc'][$i]['did']."','".$data['doc'][$i]['cid']."','".$data['doc'][$i]['letter']."','".$data['doc'][$i]['title']."','".$data['doc'][$i]['tag']."','".$data['doc'][$i]['summary']."','".$data['doc'][$i]['content']."','".$data['doc'][$i]['author']."','".$data['doc'][$i]['authorid']."','".$data['doc'][$i]['time']."','".$data['doc'][$i]['lastedit']."','".$data['doc'][$i]['lasteditor']."','".$data['doc'][$i]['lasteditorid']."','".$data['doc'][$i]['views']."','".$data['doc'][$i]['edits']."','".$data['doc'][$i]['editions']."','".$data['doc'][$i]['comments']."','".$data['doc'][$i]['votes']."','".$data['doc'][$i]['visible']."','".$data['doc'][$i]['locked']."') ");
		}
		for($i=0,$count=count($data['categorylink']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."categorylink (did,cid) VALUES ('".$data['categorylink'][$i]['did']."','".$data['categorylink'][$i]['cid']."') ");
			$this->db->query("UPDATE ".DB_TABLEPRE."category SET docs=docs+1 WHERE cid='".$data['categorylink'][$i]['cid']."'");
		}
		for($i=0,$count=count($data['edition']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."edition (eid,cid,did,author,authorid,time,ip,title,tag,summary,content,words,images,type,excellent,big,reason) 
			VALUES ('".$data['edition'][$i]['eid']."','".$data['edition'][$i]['cid']."','".$data['edition'][$i]['did']."','".$data['edition'][$i]['author']."','".$data['edition'][$i]['authorid']."','".$data['edition'][$i]['time']."','".$data['edition'][$i]['ip']."','".$data['edition'][$i]['title']."','".$data['edition'][$i]['tag']."','".$data['edition'][$i]['summary']."','".$data['edition'][$i]['content']."','".$data['edition'][$i]['words']."','".$data['edition'][$i]['images']."','".$data['edition'][$i]['type']."','".$data['edition'][$i]['excellent']."','".$data['edition'][$i]['big']."','".$data['edition'][$i]['reason']."') ");
		}
		for($i=0,$count=count($data['focus']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."focus (did,title,tag,summary,image,time,displayorder) 
			VALUES ('".$data['focus'][$i]['did']."','".$data['focus'][$i]['title']."','".$data['focus'][$i]['tag']."','".$data['focus'][$i]['summary']."','".$data['focus'][$i]['image']."','".$data['focus'][$i]['time']."','".$data['focus'][$i]['displayorder']."')");
		}
		for($i=0,$count=count($data['comment']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."comment (id,did,comment,reply,author,authorid,oppose,aegis,time) 
			VALUES ('".$data['comment'][$i]['id']."','".$data['comment'][$i]['did']."','".$data['comment'][$i]['comment']."','".$data['comment'][$i]['reply']."','".$data['comment'][$i]['author']."','".$data['comment'][$i]['authorid']."','".$data['comment'][$i]['oppose']."','".$data['comment'][$i]['aegis']."','".$data['comment'][$i]['time']."')");
		}
		for($i=0,$count=count($data['attachment']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."attachment (id,did,time,filename,description,filetype,filesize,attachment,downloads,isimage,uid) 
			VALUES ('".$data['attachment'][$i]['id']."','".$data['attachment'][$i]['did']."','".$data['attachment'][$i]['time']."','".$data['attachment'][$i]['filename']."','".$data['attachment'][$i]['description']."','".$data['attachment'][$i]['filetype']."','".$data['attachment'][$i]['filesize']."','".$data['attachment'][$i]['attachment']."','".$data['attachment'][$i]['downloads']."','".$data['attachment'][$i]['isimage']."','".$data['attachment'][$i]['uid']."')");
		}
		for($i=0,$count=count($data['synonym']);$i<$count;$i++){
			$this->db->query("INSERT INTO  ".DB_TABLEPRE."synonym (id,srctitle,destdid,desttitle) VALUES ('".$data['synonym'][$i]['id']."','".$data['synonym'][$i]['srctitle']."','".$data['synonym'][$i]['destdid']."','".$data['synonym'][$i]['desttitle']."')");
		}
	}
	
	function fetch_all($table,$dids,$key,$fields='did'){
		global $content,$keyword;
		$query=$this->db->query("SELECT * FROM  ".DB_TABLEPRE."$table WHERE $fields IN ($dids) ");
		while($data=$this->db->fetch_array($query)){
			$content[$table][]=$data;
			$keyword.=$data[$key];
		}
	}

	function uncreate($did){
		$uid = $this->base->user['uid'];
		$this->db->query("DELETE FROM ".DB_TABLEPRE."doc WHERE did=$did AND authorid=$uid");
		$this->db->query("DELETE FROM ".DB_TABLEPRE."autosave WHERE did=$did AND uid=$uid");
		$this->del_doc_category($did);
	}
	
	function is_addcredit($did,$uid){
		$sql="SELECT time FROM ".DB_TABLEPRE."edition WHERE did = $did AND authorid=$uid ORDER BY time DESC LIMIT 1,1";
		$edittime=$this->db->result_first($sql);
		if($this->base->time-$edittime>2*60*60){
			return true;
		}else{
			return false;
		}		
	}
	
	function search_doc($start=0,$limit=10, $cid='', $title='',$author='', $starttime='', $endtime='',$typename){
		//$cnameadd = !empty($cid) ? 'c.name' : 'GROUP_CONCAT(c.name)';
		$sql="SELECT d.did, d.letter, d.title, d.tag, d.summary, d.author, d.authorid, d.time, d.lastedit, d.lasteditor, d.views, d.edits, d.editions, d.visible, d.locked, c.cid, f.did focus,f.type doctype FROM ".DB_TABLEPRE;
		if($typename!=1 && $typename!=2 && $typename!=3){
			$sql=$sql."doc d LEFT JOIN ".DB_TABLEPRE."categorylink ca ON d.did=ca.did LEFT JOIN ".DB_TABLEPRE."category c ON ca.cid = c.cid LEFT JOIN ".DB_TABLEPRE."focus f ON f.did = d.did where 1=1";
		}else{
			$sql=$sql."focus f LEFT JOIN ".DB_TABLEPRE."doc d ON f.did = d.did LEFT JOIN ".DB_TABLEPRE."categorylink ca ON d.did=ca.did LEFT JOIN ".DB_TABLEPRE."category c ON ca.cid = c.cid where 1=1";
		}
		if($title){
			$sql=$sql." AND d.title like '%$title%' ";
		}
		if($author){
			$sql=$sql." AND d.author='$author' ";
		}
		if($starttime){
			$sql=$sql." AND d.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND d.time<=$endtime ";
		}
		if($typename!=''){
			switch($typename){
				case 1:
					$sql=$sql." AND f.type=1 ";
					break;
				case 2:
					$sql=$sql." AND f.type=2 ";
					break;
				case 3:
					$sql=$sql." AND f.type=3 ";
					break;
				case 4:
					$sql=$sql." AND d.locked=1 ";
					break;
				case 5:
					$sql=$sql." AND d.visible=1 ";
					break;
				case 6:
					$sql=$sql." AND d.visible=0 ";
					break;		
			}
		}
		if($cid){
			$sql=$sql." AND c.cid=$cid ";
		}else{
			$sql .= " group by d.did";
		}
		$sql=$sql." order by d.lastedit desc limit $start,$limit ";
		$doclist = $didtocat =array();
		$query=$this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			$doc['time'] = $this->base->date($doc['time']);
			$doc['title'] = htmlspecial_chars($doc['title']);
			$dids[]= $doc['did'];
			$doclist[]=$doc;
		}
		if($dids){		
			$dids = implode(',', $dids);
			$sql = "SELECT ca.did,c.name as cname FROM ".DB_TABLEPRE."category c,".DB_TABLEPRE."categorylink ca WHERE c.cid=ca.cid AND ca.did IN ($dids) ORDER BY ca.did asc";
			$query=$this->db->query($sql);
			$first = 1;
			while($category=$this->db->fetch_array($query)){
				if($first == 1){
					$temp = $category['did'];
				}
				if($category['did'] == $temp){
					if(isset($didtocat[$category['did']])){
						$didtocat[$category['did']] .= ','.$category['cname'];
					}else{
						$didtocat[$category['did']] = $category['cname'];
					}
				}else{
					$temp = $category['did'];
					$didtocat[$category['did']] = $category['cname'];
				}
				$first = 0;
			}
			for($i=0; $i<count($doclist); $i++){
				if(isset($didtocat[$doclist[$i]['did']])) { 
					$doclist[$i]['category'] = $didtocat[$doclist[$i]['did']];
				}
			}
		}
		return $doclist;
	}
	
	function search_doc_num($cid='', $title='',$author='', $starttime='', $endtime='',$typename ){
		if($cid){
			$sqladd= "COUNT(*)";
		}else{
			$sqladd= "COUNT(DISTINCT(d.did))";
		}
		$sql="SELECT $sqladd FROM ".DB_TABLEPRE;
		if($typename!=1 && $typename!=2 && $typename!=3){
			$sql=$sql."doc d LEFT JOIN ".DB_TABLEPRE."categorylink ca ON d.did=ca.did LEFT JOIN ".DB_TABLEPRE."category c ON ca.cid = c.cid LEFT JOIN ".DB_TABLEPRE."focus f ON f.did = d.did where 1=1 ";
		}else{
			$sql=$sql."focus f LEFT JOIN ".DB_TABLEPRE."doc d ON f.did = d.did LEFT JOIN ".DB_TABLEPRE."categorylink ca ON d.did=ca.did LEFT JOIN ".DB_TABLEPRE."category c ON ca.cid = c.cid where 1=1 ";
		}
		if($title){
			$sql=$sql." AND d.title like '%$title%' ";
		}
		if($author){
			$sql=$sql." AND d.author='$author' ";
		}
		if($starttime){
			$sql=$sql." AND d.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND d.time<=$endtime ";
		}
		if($typename!=''){
			switch($typename){
				case 1:
					$sql=$sql." AND f.type=1 ";
					break;
				case 2:
					$sql=$sql." AND f.type=2 ";
					break;
				case 3:
					$sql=$sql." AND f.type=3 ";
					break;
				case 4:
					$sql=$sql." AND d.locked=1 ";
					break;
				case 5:
					$sql=$sql." AND d.visible=1 ";
					break;
				case 6:
					$sql=$sql." AND d.visible=0 ";
					break;		
			}
		}
		if($cid){
			$sql=$sql." AND c.cid=$cid ";
		}
		return $this->db->result_first($sql);
	} 

	function get_nav_edition($eid){
		$editionlist=array();
		$eidlist=array();
		
		$edition= $this->db->fetch_first("SELECT did FROM ".DB_TABLEPRE."edition WHERE eid=$eid");
		$did = $edition['did'];
		$query= $this->db->query("SELECT eid FROM ".DB_TABLEPRE."edition WHERE did=$did ORDER BY eid ASC");
		while($edition=$this->db->fetch_array($query)){
			$eidlist[] = $edition['eid'];
		}
		$count=count($eidlist);
		foreach($eidlist as $i => $eids){
			if ($eids == $eid){
				if($i==0){
					$editionlist['previous']=0;
					$editionlist['next']=$eidlist[1];
					$editionlist['latest']=array_pop($eidlist);
				}elseif(($i+1) == $count){
					$editionlist['previous']=$eidlist[$i-1];
					$editionlist['next']=0;
					$editionlist['latest']=0;
				}else {
					$editionlist['previous']=$eidlist[$i-1];
					$editionlist['next']=$eidlist[$i+1];
					$editionlist['latest']=array_pop($eidlist);
				}
			}
		}
		$editionlist['count']=$count;
		return $editionlist;
	}
	 
	function get_edition($eid){
		$editionlist=array();
		if(is_numeric($eid)){
			$edition= $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."edition WHERE eid=$eid");
			if($edition){
				$edition['comtime']=$edition['time'];
				$edition['time']=$this->base->date($edition['time']);
				$edition['rawtitle']=$edition['title'];
				$edition['title']=htmlspecial_chars($edition['title']);
				if(!$edition['content']){
					$edition['content']=file::readfromfile($this->get_edition_fileinfo($edition['eid'],'file'));
				}
			}
			return $edition;
		}else{
			$eid=implode(",",$eid);
			$query=$this->db->query(" SELECT * FROM ".DB_TABLEPRE."edition WHERE eid IN ($eid)");
			while($edition=$this->db->fetch_array($query)){
				$edition['time']=$this->base->date($edition['time']);
				$edition['rawtitle']=$edition['title'];
				$edition['title']=htmlspecial_chars($edition['title']);
				if(!$edition['content']){
					$edition['content']=file::readfromfile($this->get_edition_fileinfo($edition['eid'],'file'));
				}
				$editionlist[]=$edition;
			}
			return $editionlist;
		}
	}

	function remove_edition($eid, $did=0){
		if(is_array($eid)){
			$eid=implode(",",$eid);
		}
		$sql="INSERT INTO ".DB_TABLEPRE."recycle (type,keyword,content,file,adminid,admin,dateline) values ";
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."edition WHERE eid IN ($eid)");
		$delete_count = array();
		while($edition=$this->db->fetch_array($query)){
			$delete_count[$edition['did']]=0;
			$file=$this->get_edition_fileinfo($edition['eid'],'file');
			$file=($edition['content'])?"N;":serialize(array("$file"));
			$sql.="('edition','".$edition['title']."','".addslashes(serialize($edition))."','$file','".$this->base->user['uid']."','".$this->base->user['username']."','".$this->base->time."'),";
			$delete_count[$edition['did']]+=1;

			//首次编辑审核删除版本或者删除用户的时候恢复词条历史版本内容
			$updatesql = "UPDATE ".DB_TABLEPRE."doc SET summary='".$edition['summary']."',";
			$updatesql .= "content = '".$edition['content']."'";
			$updatesql .= " WHERE  did='".$edition['did']."' AND editions>0";
			$this->db->query($updatesql);
		}
		$this->db->query(substr($sql,0,-1));

		$this->db->query("DELETE FROM ".DB_TABLEPRE."edition WHERE eid IN ($eid)");
		foreach($delete_count as $did=>$count) {
			$this->db->query("UPDATE ".DB_TABLEPRE."doc SET editions=editions-$count,visible=1 WHERE  did=$did AND editions>0 ");
			$this->update_last_editor($did);
		}
		return true;
	}
	
	function recover_edition($data){
		$data=string::haddslashes($data,1);
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."edition (eid,cid,did,author,authorid,time,ip,title,tag,summary,content,words,images,type,judge,excellent,big,reason,coins) 
					VALUES ('".$data['eid']."','".$data['cid']."','".$data['did']."','".$data['author']."','".$data['authorid']."','".$data['time']."','".$data['ip']."','".$data['title']."','".$data['tag']."','".$data['summary']."','".$data['content']."','".$data['words']."','".$data['images']."','".$data['type']."','".$data['judge']."','".$data['excellent']."','".$data['big']."','".$data['reason']."','".$data['coins']."')");
		$this->db->query("UPDATE ".DB_TABLEPRE."doc SET editions=editions+1 WHERE did=".$data['did']);
		$this->update_last_editor($data['did']);
	}
	
	function update_last_editor($did) {
		$query = $this->db->query('SELECT author, authorid, `time` FROM '.DB_TABLEPRE.'edition WHERE did = '.$did.' ORDER BY `time` DESC LIMIT 1');
		$last_edition = $this->db->fetch_array($query);
		if($last_edition) {
			$this->db->query("UPDATE ".DB_TABLEPRE."doc SET lasteditor = '{$last_edition['author']}', lasteditorid = {$last_edition['authorid']}, lastedit = {$last_edition['time']} WHERE did = {$did}");
		} else {
			$this->db->query("UPDATE ".DB_TABLEPRE."doc SET lasteditor = author, lasteditorid = authorid, lastedit = `time` WHERE did = {$did}");
		}
	}
	
	
	function set_excellent_edition($eid,$type=1){
		if(is_array($eid)){
			$eid=implode(",",$eid);
		}
		$type=(bool)$type?1:0;
		$this->db->query("UPDATE ".DB_TABLEPRE."edition SET excellent=$type WHERE eid IN ($eid)");
		return true;
	}
	
	function copy_edition($eid){
		$edition=$this->get_edition($eid);
		if(!is_array($edition)){
			return false;
		}
		$edition['tags']=$data['tag'];
		$edition['time']=$this->base->time;
		$edition['visible']=$this->base->setting['verify_doc']?'0':'1';
		$this->edit_doc(string::haddslashes($edition,1));
		return true;
	}
	
	function update_to_latest_edition($doc){
		$this->db->query("UPDATE ".DB_TABLEPRE."doc SET
		tag='".$doc['tag']."' ,summary='".$doc['summary']."' ,content='".$doc['content']."',lastedit='".$this->base->time."',
		lasteditor='".$this->base->user['username']."' WHERE did=".$doc['did']);
	}
	
	function add_searchindex($did,$title,$tags,$content){
		return true;
	}

	function have_danger_word($content){
		$words=$this->base->cache->load('word');
		foreach($words as $key=>$word){
			if(strstr($content,$key)){
				return '1';
				break;
			}
		}
		return '0';
	}
	
	function replace_danger_word($content){
		$words=$this->base->cache->load('word');
		foreach($words as $key=>$word){
			$content=str_replace($key,$word,$content);
		}
		return $content;
	}


	/*Description: This method has already expired*/
	function get_focus_content($did){
		return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."focus WHERE did=$did");
	}
	
	/*Description: This method has already expired*/
	function save_focus_img($did,$img){
		$this->db->query("UPDATE ".DB_TABLEPRE."focus SET image='$img' WHERE did=$did");
		return true;
	}
	
	function save_focus_content($did,$summary,$image,$order,$type){
		$this->db->query("UPDATE ".DB_TABLEPRE."focus SET summary='$summary',image='$image',displayorder=$order,`type`='$type' WHERE did=$did");
		return true;
	}
	
	function remove_focus($dids){
		$did=implode(",",$dids);
		$this->db->query("DELETE FROM ".DB_TABLEPRE."focus WHERE did IN ($did)");
		return true;
	}
	
	/*Description: This method has already expired*/
	function order_focus($did,$order){
		$this->db->query("UPDATE ".DB_TABLEPRE."focus SET displayorder=$order WHERE did=$did");
		return true;
	}
	
	function get_hottags(){
		$taglist='';
		$query=$this->db->query("SELECT tag FROM ".DB_TABLEPRE."focus");
		while($tag=$this->db->fetch_array($query)){
			$taglist .=$tag['tag'].';';
		}
		if(!empty($taglist)){
			$taglist = explode(';',substr($taglist,0,-1));
			$taglist = array_keys(array_count_values($taglist));
			$taglist = implode(';',$taglist);
		}
		return $taglist;
	}
	
	function get_colortag($tags){
		$hottag=array();
		$tags = array_unique(explode(';',$tags));
		foreach($tags as $tag){
			$tag=trim($tag);
			if(''==$tag){continue;}
			$tag = stripslashes($tag);
			switch(rand(1,3)){
				case 1 :
					$hottag[]=array('tagname'=>$tag,'tagcolor'=>'red');
					break;
				default :
					$hottag[]=array('tagname'=>$tag,'tagcolor'=>'');
					break;
			}
		}
		$hottag = addslashes(serialize($hottag));
		return $hottag;
	}
	
	function get_maxid() {
		return $this->db->result_first("SELECT MAX(did) FROM ".DB_TABLEPRE."doc WHERE 1");
	}
	
	function get_random() {
		$maxdid=$this->db->result_first('SELECT MAX(did) FROM '.DB_TABLEPRE.'doc ');
		$did=0;
		if(!empty($maxdid)){
			do{
				$did=rand(1,$maxdid);
				$doc=$this->db->fetch_first('SELECT did FROM '.DB_TABLEPRE.'doc WHERE did='.$did);
			}while(! $doc);
		}
		return $did;
	}

	function add_randomstr(&$body,$random_text){
		$totalitem = count($random_text);
		if($totalitem==0){
			return $body;
		}
		$maxpos = 256;
		$fontColor = "#FFFFFF";
		$st=util::random(6,2);
		$rndstyleValue = ".{$st} { display:none; }";
		$rndstyleName = $st;
		$reString = "<style> $rndstyleValue </style>\r\n";

		$rndem[1] = 'font';
		$rndem[2] = 'div';
		$rndem[3] = 'span';
		$rndem[4] = 'p';

		$bodylen = strlen($body) - 1;
		$prepos = 0;
		for($i=0;$i<=$bodylen;$i++)
		{
			if($i+2 >= $bodylen || $i<50){
				$reString .= $body[$i];
			}else{
				$ntag = @strtolower($body[$i].$body[$i+1].$body[$i+2]);
				if($ntag=='</p' || ($ntag=='<br' && $i-$prepos>$maxpos) ){
					$dd = mt_rand(1,4);
					$emname = $rndem[$dd];
					$dd = mt_rand(0,$totalitem-1);
					$rnstr = $random_text[$dd];
					if($emname!='font'){
						$rnstr = " <$emname class='$rndstyleName'>$rnstr</$emname> ";
					}else{
						$rnstr = " <font color='$fontColor'>$rnstr</font> ";
					}
					$reString .= $rnstr.$body[$i];
					$prepos = $i;
				}else{
					$reString .= $body[$i];
				}
			}
		}
		return $reString;
	}
	
	function get_related_doc($did){
		$doclist=array();
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."relation WHERE did='$did' order by id");
		while($doc=$this->db->fetch_array($query)){
			$doclist[]=$doc['relatedtitle'];
		}
		return $doclist;
	}
	
	function add_relate_title($did,$title,$relatelist){
		$list = array_unique($relatelist);
		$this->db->query("delete from ".DB_TABLEPRE."relation where did='$did'");
		foreach($list as $relate){
			$relate=htmlspecial_chars(trim($relate));
			if($relate)$this->db->query("insert into ".DB_TABLEPRE."relation set did='$did',relatedtitle='$relate',title='$title' ");
		}
		return true;
	}
	
	function get_cids_by_did($did){
		$categorylist = array();
		$query=$this->db->query("SELECT b.* from ".DB_TABLEPRE."categorylink as a INNER JOIN ".DB_TABLEPRE."category as b ON a.cid=b.cid WHERE a.did = '$did' ORDER BY a.cid");
		while($category=$this->db->fetch_array($query)){
			$categorylist[]=$category;
		}
		return $categorylist;
	}
	
	function getnews(){
		$newslist = array();
		$query=$this->db->query("SELECT * from ".DB_TABLEPRE."user order by uid desc limit 10");
		while($userlist=$this->db->fetch_array($query)){
			$newslist[$userlist['regtime']]="<a href='".$this->base->setting['seo_prefix']."user-space-".$userlist['uid'].$this->base->setting['seo_suffix']."' class='red'>".$userlist['username']."</a>".$this->base->view->lang['news_join'].$this->base->setting['site_name'];
		}
		$query=$this->db->query("SELECT * from ".DB_TABLEPRE."edition order by eid desc limit 10");
		while($editionlist=$this->db->fetch_array($query)){
			$newslist[$editionlist['time']]="<a href='".$this->base->setting['seo_prefix']."user-space-".$editionlist['authorid'].$this->base->setting['seo_suffix']."' class='red'>".$editionlist['author'].'</a>'.$this->base->view->lang['news_edition_tip1'].'<a href="'.$this->base->setting['seo_prefix'].'doc-view-'.$editionlist['did'].$this->base->setting['seo_suffix'].'" target="_blank">'.htmlspecial_chars(stripslashes($editionlist['title'])).'</a>'.$this->base->view->lang['news_edition_tip2'];
		}
		$query=$this->db->query("SELECT l.id,g.title,g.image,u.uid,u.username,u.truename,u.location,u.postcode,u.telephone,u.qq,u.email,l.extra,l.time,l.status FROM ".DB_TABLEPRE."gift g,".DB_TABLEPRE."giftlog l,".DB_TABLEPRE."user u  WHERE g.id=l.gid AND u.uid=l.uid  limit 10");
		while($giftlog=$this->db->fetch_array($query)){
			$newslist[$giftlog['time']]='<a href="'.$this->base->setting['seo_prefix'].'user-space-'.$giftlog['uid'].$this->base->setting['seo_suffix'].'" class="red">'.$giftlog['username'].'</a>'.$this->base->view->lang['chineseDe'].'<font color="blue">'.$giftlog['title'].'</font>'.$this->base->view->lang['giftWaiteGive'];
		}
		krsort($newslist);
		return $newslist;
	}
	
	function search_edition($start=0,$limit=10, $cid='', $title='',$author='', $starttime='', $endtime='',$typename){
		$sql="SELECT d.eid,d.did, d.title, d.author, d.authorid, d.time,d.coins, d.judge, d.reason,d.excellent FROM ";
		$sql.= DB_TABLEPRE."edition d LEFT JOIN ".DB_TABLEPRE."categorylink ca ON ca.did=d.did LEFT JOIN ".DB_TABLEPRE."category c ON ca.cid = c.cid where 1=1 ";

		if($title){
			$sql=$sql." AND d.title like '%$title%' ";
		}
		if($author){
			$sql=$sql." AND d.author='$author' ";
		}
		if($starttime){
			$sql=$sql." AND d.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND d.time<=$endtime ";
		}
		
		switch($typename){
			case 1:
				$sql=$sql." AND d.excellent=1 ";
				break;
			case 2:
				$sql=$sql." AND d.judge=1 ";
				break;
			case 3:
				$sql=$sql." AND d.judge=0 ";
				break;	
		}
		if($cid){
			$sql=$sql." AND c.cid=$cid ";
		}else{
			$sql .= " GROUP BY d.eid";
		}
		$sql=$sql." ORDER BY d.eid DESC LIMIT $start,$limit ";
		$doclist=array();
		$query=$this->db->query($sql);
		while($doc=$this->db->fetch_array($query)){
			$doc['time'] = $this->base->date($doc['time']);
			$doc['title'] = htmlspecial_chars($doc['title']);
			$doclist[]=$doc;
		}
		$this->join_categories($doclist);
		
		return $doclist;
	}
	
	/**
	 * 为词条数组中的各项加入其所属分类，以,分割。
	 * @param &$doclist
	 * @return bool
	 */
	function join_categories(&$doclist) {
		$dids = array();
		$doclistcount = count($doclist);
		for($i = 0; $i < $doclistcount; $i++) {
			$dids[] = $doclist[$i]['did'];
		}
		if(empty($dids)) {
			return false;
		}
		$dids = implode(',', $dids);
		$sql = "SELECT ca.did,c.name as cname FROM ".DB_TABLEPRE."category c,".DB_TABLEPRE."categorylink ca WHERE c.cid=ca.cid AND ca.did IN ($dids) ORDER BY ca.did asc";
		$query=$this->db->query($sql);
		$first = 1;
		while($category=$this->db->fetch_array($query)){
			if($first == 1){
				$temp = $category['did'];
			}
			if($category['did'] == $temp){
				if($didtocat[$category['did']]){
					$didtocat[$category['did']] .= ','.$category['cname'];
				}else{
					$didtocat[$category['did']] = $category['cname'];
				}
			}else{
				$temp = $category['did'];
				$didtocat[$category['did']] = $category['cname'];
			}
			$first = 0;
		}
		for($i=0; $i<count($doclist); $i++){
			$doclist[$i]['category'] = $didtocat[$doclist[$i]['did']];
		}
		return true;
	}
	
	function search_edition_num($cid='', $title='',$author='', $starttime='', $endtime='',$typename){
		if($cid){
			$sqladd= "COUNT(*)";
		}else{
			$sqladd= "COUNT(DISTINCT(d.eid))";
		}
		$sql="SELECT $sqladd FROM ".DB_TABLEPRE."edition d LEFT JOIN ".DB_TABLEPRE."categorylink ca ON ca.did=d.did LEFT JOIN ".DB_TABLEPRE."category c ON ca.cid = c.cid WHERE 1=1 ";

		if($title){
			$sql=$sql." AND d.title LIKE '%$title%' ";
		}
		if($author){
			$sql=$sql." AND d.author='$author' ";
		}
		if($starttime){
			$sql=$sql." AND d.time>=$starttime ";
		}
		if($endtime){
			$sql=$sql." AND d.time<=$endtime ";
		}
		switch($typename){
			case 1:
				$sql=$sql." AND d.excellent=1 ";
				break;
			case 2:
				$sql=$sql." AND d.judge=1 ";
				break;
			case 3:
				$sql=$sql." AND d.judge=0 ";
				break;	
		}
		
		if($cid){
			$sql .= " AND c.cid=$cid ";
		}
		return $this->db->result_first($sql);
	}
	
	function cooperatedocs($num=0){
		$coopdoc = array();
		$cooperatedocs = explode(';',$this->base->setting['cooperatedoc']);
		if($num==0){
			$counts = count($cooperatedocs);
		}else{
			$counts = $num;
		}
		for($i=0;$i<$counts;$i++){
			if(empty($cooperatedocs[$i])){
				unset($cooperatedocs[$i]);
			}else{
				$coopdoc[$i]['shorttitle'] = (string::hstrlen($cooperatedocs[$i])>10)?string::substring($cooperatedocs[$i],0,5)."...":$cooperatedocs[$i];
				$coopdoc[$i]['title'] = $cooperatedocs[$i];
			}
		}
		return $coopdoc;
	}
	
	function add_edition_coin($eids, $coin){
		$sql="UPDATE ".DB_TABLEPRE."edition set coins=coins+ $coin,judge=1 WHERE eid IN($eids)";
		$this->db->query($sql);
	}
	
	function get_edition_user($eids){
		$userlist=array();
		$query=$this->db->query("SELECT did,author,authorid FROM ".DB_TABLEPRE."edition WHERE eid in ($eids)");
		while($edition=$this->db->fetch_array($query)){
			$userlist[]=$edition;
		}
		return $userlist;
	}
	
	function update_edition($eids, $arr){
		if (!is_array($arr) || empty($arr)){
			return;
		}
		$sql="UPDATE ".DB_TABLEPRE."edition set ";
		
		$list = array();
		foreach($arr as $key=>$value){
			$list[] = " $key='$value'";
		}
		$sql .= implode(',',$list);
		$sql .= " WHERE eid in($eids)";
		$this->db->query($sql);
	}
	
	function get_neighbor($did){
		$doclist=array();
		$query=$this->db->query("SELECT did,title FROM ".DB_TABLEPRE."doc WHERE did IN (".($did-1).",".($did+1).")");
		while($doc=$this->db->fetch_array($query)){
			$doc['rawtitle']=$doc['title'];
			if($did>$doc['did']){
				$doclist['predoc']=$doc;
			}else{
				$doclist['nextdoc']=$doc;
			}
			
		}
		return $doclist;
	}
	
	function get_edition_fileinfo($eid,$type='path'){
		$number=100;
		$fileinfo['path']="data/edition/".(ceil($eid/$number)*$number);
		$fileinfo['file']=$fileinfo['path']."/".$eid.".txt";
		return $fileinfo[$type];
	}
	
	function change_letter($did,$letter){
		if($this->db->query("UPDATE ".DB_TABLEPRE."doc SET letter='$letter' WHERE did=$did")){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 检查英文比例
	 * @param string $content
	 */
	function check_eng_pcnt($content) {
		$content = strip_tags($content);
		$strlen = strlen($content);
		
		$max_pcnt = isset($this->base->setting['eng_max_pcnt']) ? intval($this->base->setting['eng_max_pcnt']) : 100;
		if($max_pcnt == 100 || $strlen == 0) {
			return true;
		}

		$eng_words = 0;
		
		for ($i=0; $i < $strlen;){
			$value = ord($content{$i});
			if($value > 127){
				if($value >= 192 && $value <= 223)
					$i += 2;
				elseif($value >= 224 && $value <= 239)
					$i += 3;
				elseif($value >= 240 && $value <= 247)
					$i += 4;
			}else{
				$i += 1;
				if(($value >= 65 && $value <= 90) || ($value >= 97 && $value <= 122)) {
					$eng_words++;
				}
			}
		}
		
		$eng_pcnt = floor($eng_words / $strlen * 100);
		return $eng_pcnt <= $max_pcnt;
	}
	
	/**
	 * 检查外部链接比例
	 * @param string $content
	 */
	function check_extlink_pcnt($content) {
		$content = stripslashes($content); //不然无法匹配
		$strlen = strlen($content);
		
		$max_pcnt = isset($this->base->setting['extlink_max_pcnt']) ? intval($this->base->setting['extlink_max_pcnt']) : 100;
		if($max_pcnt == 100 || $strlen == 0) {
			return true;
		}
		
		$links = array();
		if(preg_match_all('/<\s*a\s[^>]*?href\s*=\s*[\"\']?https?:\/\/.*?>.*?<\/a.*?>/isx', $content, $links)) {
			if($max_pcnt == 0) { //如果不允许外部链接，直接返回false
				return false;
			} else {
				$extlink_pcnt = floor(strlen(implode('', $links[0]))/$strlen*100);
				return $extlink_pcnt <= $max_pcnt;
			}
		} else { //未找到任何外部链接
			return true;
		}
	}
	
	/**
	 * 检查词条发布间隔
	 * @param int $uid
	 */
	function check_submit_interval($uid) {
		$min_interval = isset($this->base->setting['submit_min_interval']) ? $this->base->setting['submit_min_interval'] : 0;
		if($min_interval == 0) {
			return true;
		}
		$sql = "SELECT `time` FROM  `".DB_TABLEPRE."edition` WHERE authorid = {$uid} ORDER BY eid DESC LIMIT 1";
		$edition_last_time = $this->db->result_first($sql);
		
		$doc_last_time = 0;
		if($this->base->setting['base_createdoc']!=1){
			$sql = "SELECT `time` FROM  `".DB_TABLEPRE."doc` WHERE authorid = {$uid} ORDER BY did DESC LIMIT 1";
			$doc_last_time = $this->db->result_first($sql);
		}
		
		$last_time = max($edition_last_time, $doc_last_time);
		return ($this->base->time - $last_time) > $min_interval;
	}
	
	function user_edited($uid, $did) {
		$sql = "SELECT d.did FROM  ".DB_TABLEPRE."edition  e, ".DB_TABLEPRE."doc d  WHERE e.authorid = $uid AND d.authorid !=$uid AND e.did = d.did GROUP BY d.did ";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query)) {
			if($row['did'] == $did) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 获取首次编辑审核词条列表
	 * @param int $start
	 * @param int $limit
	 * @param int $uid
	 * @param int &$total
	 * @return Array
	 */
	function get_newusernewdoc($start = 0, $limit = 10, $uid = NULL, &$total = NULL) {
		$condition = array(
			'd.visible =0',
			'u.newdocs != -1'
		);
		if($uid) {
			$condition[] = 'd.lasteditorid = '.$uid;  
		}
		$condition = implode(' AND ', $condition);
		$sql = "SELECT d.lasteditor, d.lasteditorid, d.title, d.did, d.lastedit, d.summary, u.newdocs
			FROM  ".DB_TABLEPRE."doc d
			LEFT JOIN  ".DB_TABLEPRE."user u ON d.lasteditorid = u.uid
			WHERE {$condition}
			ORDER BY lasteditorid DESC , lastedit ASC
			LIMIT {$start}, {$limit}";
		
		$query = $this->db->query($sql);
		$doclist = array();
		while($doc=$this->db->fetch_array($query)){
			$doc['lastedit'] = $this->base->date($doc['lastedit']);
			$doc['title'] = htmlspecial_chars($doc['title']);
			$edition = $this->db->fetch_first("SELECT MAX( eid ) AS eid, reason
				FROM  ".DB_TABLEPRE."edition
				WHERE did = ".$doc['did']."
				AND authorid = ".$doc['lasteditorid']." GROUP BY did");
			$doc['eid'] = $edition['eid'];
			$doc['reason'] = $edition['reason'];
			$doclist[]=$doc;
		}
		$this->join_categories($doclist);
		
		if(!is_null($total)) {
			$total = $this->db->result_first("SELECT COUNT(*)
			FROM  ".DB_TABLEPRE."doc d
			LEFT JOIN  ".DB_TABLEPRE."user u ON d.lasteditorid = u.uid
			WHERE {$condition}");
		}
		return $doclist;
	}
	
	/**
	 * 删除某用户的所有词条版本
	 * @param int $uid
	 */
	function remove_all_docs($uid) {
		$neweditions = $this->db->get_array('SELECT * FROM '.DB_TABLEPRE.'edition e WHERE e.authorid = '.$uid);
		foreach($neweditions as $edition) {
			$this->remove_edition($edition['eid'], $edition['did']);
		}
	}
	
	/**
	 * 审核某用户的所有待审核词条
	 * @param $uid
	 */
	function audit_all_docs($uid) {
		$newdocs = $this->db->get_array('SELECT did FROM '.DB_TABLEPRE.'doc WHERE lasteditorid = '.$uid.' AND visible = 0');
		$dids = array();
		foreach($newdocs as $doc) {
			$dids[] = $doc['did'];
		}
		if(!empty($dids)) {
			$this->audit_doc($dids);
		}
	}
	
	/*
	 * 取导航模块
	 * $param $docid
	 */
	function get_nav($docid){
		$navlist = array();
		$navids = $this->db->get_array("SELECT navid FROM ".DB_TABLEPRE."navlink WHERE did = '$docid'");
		foreach($navids as $navid) {
			$nav = $this->db->fetch_first("SELECT * from ".DB_TABLEPRE."nav WHERE id='".$navid["navid"]."'");
			$navlist[$nav['position']][] = $nav;
		}
		return $navlist;
	}
	
	/*
	 * 取保存未发布的词条
	 * $param $docid
	 */
	function get_unpubdoc($userid){
		$didrow = $this->db->fetch_first("SELECT did FROM ".DB_TABLEPRE."autosave WHERE uid = '$userid' ORDER BY time DESC");
		if($didrow){
			$row = $this->db->fetch_first("SELECT title FROM ".DB_TABLEPRE."doc WHERE did = '".$didrow['did']."'");
			if($row){
				return array('did'=>$didrow['did'], 'title'=>$row['title']);
			}			
		}
	}
	
}
?>