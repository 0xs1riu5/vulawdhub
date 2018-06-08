<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base(  $get, $post);
		$this->load('attachment');
		$this->load('user');
		$this->load("watermark");
	}

	function douploadimg() {
		$imgname=$_FILES['photofile']['name'];
		$extname=file::extname($imgname);
		$destfile=$_ENV['attachment']->makepath($extname);
		$arrupload=file::uploadfile($_FILES['photofile'],$destfile);
		
		if($arrupload['result']==true){
			if(isset($this->setting['watermark'])){
				$_ENV['watermark']->image($destfile,$destfile);
			}
			$uid=intval($this->user['uid']);
			$did=intval($this->get['2']);
			$_ENV['attachment']->add_attachment($uid ,$did,$imgname ,$destfile ,htmlspecial_chars($this->post['picAlt']) ,$extname);
			
			$img_width_big=$this->setting['img_width_big'];
			$img_height_big=$this->setting['img_height_big'];
			$img_width_small=$this->setting['img_width_small'];
			$img_height_small=$this->setting['img_height_small'];
			
			$img_width_big=is_numeric($img_width_big)?$img_width_big:300;
			$img_height_big=is_numeric($img_height_big)?$img_height_big:300;
			$img_width_small=is_numeric($img_width_small)?$img_width_small:140;
			$img_height_small=is_numeric($img_height_small)?$img_height_small:140;

			$iamge300=util::image_compress($destfile,'',$img_width_big,$img_height_big,'_s');
			$iamge140=util::image_compress($destfile,'',$img_width_small,$img_height_small,'_140');
			$_ENV['user']->add_credit($uid,'attachment-upload',$this->setting['credit_upload'],$this->setting['coin_upload']);
		}else {
			$_ENV['attachment']->showmsg($arrupload['msg']);
			exit;
		}
		$images=($this->post['picWidthHeight']==1)?$iamge300['tempurl']:$iamge140['tempurl'];
		if (empty($_FILES) === false) {
			$_ENV['attachment']->insert_image_js($images, $destfile);
		}
	}
	
	function doupload(){
		@header('Content-type: text/html; charset='.WIKI_CHARSET);
		$did=intval($this->post['did']);
		if(!$this->setting['attachment_open']){
			exit;
		}
		$okfile='';
		$this->setting['coin_download'] = isset ($this->setting['coin_download'])?$this->setting['coin_download']:10;
		$count=count($_FILES['attachment']['name']);
		for($i=0;$i<$count;$i++){
			if(!(bool)$_FILES['attachment']['name'][$i]){
				continue;
			}
			$size=$_FILES['attachment']['size'][$i]/1024;
			$name=$_FILES['attachment']['name'][$i];
			$attachment_type=$_ENV['attachment']->get_attachment_type();
			$filetype=strtolower(substr($name, strrpos($name,".")+1));
			if($attachment_type[0] != '*' && !in_array($filetype,$attachment_type)){
				echo '<script>parent.Attachment.error("'.$name.': '.$this->view->lang['attachTypeError'].'");</script>';
				continue;
			}
			if($size > $this->setting['attachment_size']||empty($size)){
				echo '<script>parent.Attachment.error("'.$name.': '.$this->view->lang['attachSizeError2'].'");</script>';
				continue;
			}
			$destfile='data/attachment/'.date('y-m').'/'.date('Y-m-d').'_'.util::random(10).'.attach';
			file::createaccessfile('data/attachment/'.date('y-m').'/');
			$result=file::uploadfile($_FILES['attachment']['tmp_name'][$i],$destfile,$this->setting['attachment_size'],0);
			if($result){
				$okfile .= $name.'|';
				
				$coindown = $this->post['coin_download'][$i];
				if(empty($coindown) || !is_numeric($coindown)) {
					$coindown = 0;
				}else if( 0 > $coindown) {
					$coindown = 0;
				}else if($this->setting['coin_download'] < $coindown) {
					$coindown = $this->setting['coin_download'];
				}
				$_ENV['attachment']->add_attachment($this->user['uid'], $did, $name ,$destfile ,$this->post['attachmentdesc'][$i],$filetype,0,$coindown);
			}
		}
		
		echo '<script>';
		if($okfile)	echo 'parent.Attachment.addok("'.$okfile.'");';
		echo '</script>';
	}
	
	function doremove(){
		$id=$this->get[2];
		$id=is_numeric($id)?intval($id):0;
		if(!$id){
			exit;
		}
		$uid = $this->user['groupid']==4 ? 0 : $this->user['uid'];
		if($_ENV['attachment']->remove($id, $uid)){
			echo 'OK';
		}
	}
	
	function dodownload(){
		if(!isset($this->get[2]) || !is_numeric($this->get[2])){
			$this->message($this->view->lang['parameterError'],'BACK');
		}
		$result=$_ENV['attachment']->get_attachment('id',$this->get[2],0);
		if(!(bool)$attachment=$result[0]){
			$this->message($this->view->lang['attachIsNotExist'],'BACK');
		}
		if($this->user['uid'] != $attachment['uid']) {
			// 判断金币
			$credit1 = $this->user['credit1'];		// 拥有金币数
			$coindown = $attachment['coindown'];	// 下载此附件需要消耗金币数
			if(0 > $credit1 - $coindown) {
				// 金币不足
				$this->message($this->view->lang['goldNotEnough'],"index.php?doc-view-".$attachment['did'],0);
			}
			// 扣除金币
			$_ENV['user']->add_credit($this->user['uid'],'attachment-down',0,-$coindown);
			// 增加金币
			$_ENV['user']->add_credit($attachment['uid'],'attachment-down',0,$coindown);
		}
		$_ENV['attachment']->update_downloads($attachment['id']);
		file::downloadfile($attachment['attachment'],$attachment['filename']);
	}
}
?>