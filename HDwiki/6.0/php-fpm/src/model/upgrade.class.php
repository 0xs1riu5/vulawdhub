<?php

!defined('IN_HDWIKI') && exit('Access Denied');

define('UPGRADE_API', 'http://kaiyuan.hudong.com/autoupgrade/api.php');
define('UPGRADE_PATH', HDWIKI_ROOT.'/data/updates/');

class upgrademodel {

	var $db;
	var $base;
	var $package;
	function upgrademodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function start_install($package) {
		$this->package = $package;
	}
	
	function check_memory() {
		$mem_size_needed = 5*$this->package['size'];
		if(!util::is_mem_available($mem_size_needed)) {
			$memory_limit = @ini_get('memory_limit');
			$unit = strtolower(substr($memory_limit,strlen(0+$memory_limit),1));
			$units = array('g'=>1024*1024*1024, 'm'=>1024*1024, 'k'=>1024);
			$memory_limit *= $units[$unit];
			if(function_exists('memory_get_usage')){
				$used = memory_get_usage();
			} else {
				$used = 10*1024*1024; //假设已用10M
			}
			$free_memory = $memory_limit + $used;
			return !!@ini_set('memory_limit', ($mem_size_needed > $free_memory ? $mem_size_needed : $free_memory));
		} else {
			return true;
		}		
	}

	function download_package() {
		if(!is_dir(UPGRADE_PATH)) {
			file::forcemkdir(UPGRADE_PATH);
		}
		if(file::iswriteable(UPGRADE_PATH)) {
			$zip_file = UPGRADE_PATH.$this->package['release_code'].'.zip';
			file::writetofile($zip_file, @util::hfopen($this->package['url']));
			return file_exists($zip_file);
		} else {
			return false;
		}
	}

	function check_md5() {
		$zip_file = UPGRADE_PATH.$this->package['release_code'].'.zip';
		return md5(file::readfromfile($zip_file)) === trim(strtolower($this->package['md5']));
	}

	function extract_zip() {
		$zip_file = UPGRADE_PATH.$this->package['release_code'].'.zip';
		require HDWIKI_ROOT.'/lib/zip.class.php';
		$zip=new zip();
		$swap_dir = UPGRADE_PATH.$this->package['release_code'];
		if(!is_dir($swap_dir)) {
			file::forcemkdir($swap_dir);
		}
		if(file::iswriteable($swap_dir)) {
			$zip->extract($zip_file, $swap_dir);
		}
		unset($zip);
		return count(@scandir($swap_dir)) > 0;
	}

	function check_permission($source=false) {
		$source_root = UPGRADE_PATH.$this->package['release_code'].'/hdwiki';
		if(false === $source) {
			$source = $source_root;
		}
		$target = str_replace($source_root, HDWIKI_ROOT, $source);
		if($this->copyable($target, $source) && false !== ($curdir = opendir($source))) { //如果上级目录可写，
			while($sub_source = readdir($curdir)) {
				if($sub_source != '.' && $sub_source != '..') {
					$sub_source = $source.'/'.$sub_source; //源文件
					$target = str_replace($source_root, HDWIKI_ROOT, $sub_source); //相应的目标文件
					if(is_file($sub_source) && !$this->copyable($target, $sub_source)) { //如果是文件且文件不可写，直接返回 false
						return false;
					} elseif (is_dir($sub_source) && !$this->check_permission($sub_source)) { //如果是目录且目录不可写，直接返回 false
						return false;
					}
				}
			}
			closedir($curdir);
			
			return true; //如果循环中没有返回 false 退出，则此处返回 true
		} else { //如果上级目录不可写，返回 false
			return false;
		}
	}

	function copy_newfile() {
		file::copydir(UPGRADE_PATH.$this->package['release_code'].'/hdwiki', HDWIKI_ROOT);
		return true;
	}
	
	function make_clean() {
		file::removedir(UPGRADE_PATH.$this->package['release_code']);
		unlink(UPGRADE_PATH.$this->package['release_code'].'.zip');
		return true;
	}


	function get_available_count() {
		return  @util::hfopen($this->get_api_url('check'));//UPGRADE_API.'?action=check&client_charset='.WIKI_CHARSET.'&client_release='.HDWIKI_RELEASE);
	}
	

	function get_available_packages($get_first = false) {
		$packages =  @util::hfopen($this->get_api_url('getlist'));
		$packages = @unserialize($packages);

		if($get_first) {
			if(count($packages) > 0) {
				$first = each($packages);
				$first['value']['release_code'] = $first['key'];
				return $first['value'];
			} else {
				return array();
			}
		} else {
			return is_array($packages) ? $packages : false;
		}
	}


	function get_api_url($action) {
		return UPGRADE_API.'?action='.$action.'&client_charset='.WIKI_CHARSET.'&client_release='.HDWIKI_RELEASE;
	}

	function copyable($target, $source){
		$writeable=0;

		if(!file_exists($target)) { //如果目标文件不存在
			$parent_target = dirname($target);
			if(file_exists($parent_target)) { //如果目标文件的上级目录存在，则测试上级目录写权限
				if($fp=@fopen("$parent_target/test.txt",'w')){
					@fclose($fp);
					@unlink("$parent_target/test.txt");
					$writeable=1;
				}
			} else { //如果上级目录不存在，则设为可写，因为如果能执行到这儿，其上级目录肯定已经检测可写了。
				$writeable = 1;
			}
		} else {
			if(is_file($source)) {
				if($fp=@fopen($target,'a+')){
					@fclose($fp);
					$writeable=1;
				}
			} else {
				if($fp=@fopen("$target/test.txt",'w')){
					@fclose($fp);
					@unlink("$target/test.txt");
					$writeable=1;
				}
			}
		}

		return $writeable;
	}


	
}