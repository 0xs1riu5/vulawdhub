<?php !defined('IN_HDWIKI') && exit('Access Denied');
define('HDWIKI_CODE', WIKI_CHARSET.HDWIKI_VERSION.HDWIKI_RELEASE);
class filecheckmodel {
	var $base;
	var $db;
	var $dir;
	var $hdwiki_root;
	function filecheckmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->hdwiki_root = $this->formatpath(HDWIKI_ROOT);
		$this->dir = $this->hdwiki_root.'data/md5_file/';
		$this->url_cache='http://kaiyuan.hudong.com/verify/';
		$this->out_list = array();
		$this->deep = 0;
		$this->basepath = '';
	}

	function set($filename = '')
	{
		if($filename == '' || !file_exists($this->dir.$filename)) 
		{
			$this->file = $this->dir.date('Y-m-d');
		}
		else
		{
			$this->file = $this->dir.$filename;
		}
	}

	function check($dir, $exts = 'php|js|html', $require = 1)
	{
		$list = $this->lists($dir, $exts, $require);
		if(!$list) return false;
		$files = array();
		$data = file_get_contents($this->file);
		foreach($list as $v)
		{
			$md5 = md5_file($v);
			$filepath = str_replace(HDWIKI_ROOT, '', $v);
			$line = $md5.' '.$filepath."\n";
			if(strpos($data, $line) !== false) continue;
			if(strpos($data, ' '.$filepath."\n") !== false) $files['edited'][] = $filepath;
			else $files['unknow'][] = $filepath;
		}
		return $files;
	}

	function make($exts = 'php|js|html')
	{
		file::forcemkdir($this->dir);
		$list = $this->lists($this->hdwiki_root);
		if(!$list) return false;
		$data = '';
		foreach($list as $v)
		{
			$data .= md5_file($v).' '.str_replace($this->hdwiki_root, '', $v)."\n";
			
		}
		return file::writetofile($this->file, $data);
	}

	function lists($dir, $exts = 'php|js|html', $require = 1)
	{
		$files = array();
		if($require)
		{
			@set_time_limit(600);
			$list = $this->get_files_from_dir($this->hdwiki_root);
			if(!$list) return false;
			foreach($list as $v)
			{
				if(is_file($v) && preg_match("/\.($exts)$/i", $v)) $files[] = $v;
			}
		}
		else
		{
			$list = glob($dir.'*');
			foreach($list as $v)
			{
				if(is_file($v) && preg_match("/\.($exts)$/i", $v)) $files[] = $v;
			}
		}
		return $files;
	}
	
	function get_files_from_dir($path, $exts = '', $list= array())
	{
		$path = $this->formatpath($path);
		$files = glob($path.'*');
		foreach($files as $v)
		{	
			if((!$exts || preg_match("/\.($exts)/i", $v)))
			{
				$list[] = $v;
				if(is_dir($v)){	
					$list = $this->get_files_from_dir($v, $exts, $list);
				}
			}
		}
		return $list;
	}
	//把路径格式化为"/"形式的
	function formatpath($path){
		$path = str_replace('\\', '/', $path);
		if(substr($path, -1) != '/'){
			$path = $path.'/';
		}
		return $path;	
	}
	
	function dirs()
	{
		$dirs = array();
		$list = glob($this->hdwiki_root.'*');
		foreach($list as $v)
		{
			if(is_dir($v)) $dirs[] = str_replace($this->hdwiki_root, '', $v);
		}
		return $dirs;
	}

	function checked_dirs()
	{
		return array('block','control','data','install','js','lang','lib','model','plugins','style','uploads','view');
	}

	function md5_files()
	{
		return array_map('basename', glob($this->dir.'*'));
	}

	function scan_dir($dir, $option = array(), $file_list=array())
	{
		if($dir == './')$dir = '';
		$fp = dir($this->hdwiki_root.$dir);
		while (false !== ($en = $fp->read())) {
			if ($en != '.' && $en != '..') {
				if (is_dir($this->hdwiki_root.$dir.'/'.$en) && !empty($dir)) {
					$file_list = $this->scan_dir($dir.'/'.$en, $option, $file_list);
				}
				else 
				{
					$key = strrpos($dir.'/'.$en, '/') == 0 ? $en : $dir.'/'.$en;
					if(!empty($option))
					{
						$p = pathinfo($this->hdwiki_root.$dir.'/'.$en, PATHINFO_EXTENSION);
						if (in_array($p, $option)) {
							$file_list[$key] = md5_file($this->hdwiki_root.$dir.'/'.$en);
						}
					}
					else 
					{
						$file_list[$key] = md5_file($this->hdwiki_root.$dir.'/'.$en);
					}
				}
			}
		}
		return $file_list;
	}
	//处理数组用于显示结果页
	function getlist($filelists){
		if($filelists){
			foreach($filelists as $key=>$filelist){
				$filelists[$key]['funtimes'] = isset($filelist['func']) ? count($filelist['func']) : 0;
				$filelists[$key]['funstr'] = $this->get_func_code($filelist['func']);
				$filelists[$key]['codetimes'] = isset($filelist['code']) ? count($filelist['code']) : 0;
				$filelists[$key]['codestr'] = $this->get_func_code($filelist['code']);
				$filelists[$key]['key'] = $this->urlcode($key);
			}
		}
		return $filelists;
	}
	//得到函数和代码拼接的字符串
	function get_func_code($allvals){
		$str = '';
		if(isset($allvals)){
			foreach ($allvals as $keys=>$vals)
			{
				$d[$keys] = strtolower($vals[1]);
			}
			$d = array_unique($d);
			foreach ($d as $vals)
			{
				$str .= "<font color='red'>".$vals."</font>  ";
			}
		}
		return $str;
	}
	//把url里的-和.替换掉，以便于get传输
	function urlcode($url,$remove=0){
		$old = array('-','.');
		$new = array('###','^^^');
		if($remove){
			$url = urldecode(str_replace($new, $old, $url));
		}else{
			$url = str_replace($old, $new, urlencode($url));
		}
		return $url;
	}
	//编辑时 函数和代码的onclick事件
	function getjscode($func,$iscode = 0){
		 if ($func) {
		 	 $func = array_unique($func);
		 	 foreach ($func as $val)
			 {
			 	if($val)
			 	{
			 		$val = $iscode ? htmlentities($val) : $val;
			 		$out .= "<input type='button' onclick=\";findInPage(this.form.code,'$val');\" value='".$val."' class=\"inp_btn2 m-r10\"> ";
			 	}
			 }
		 }
		return $out;
	}

	/*****************文件检测**************/
	function file_check(){
		$bool = true;
		$html = 'OK:<table class="table"><CAPTION>检测结果：</CAPTION>
		<tr><td class="bold a-r" style="width:100px;">丢失的文件</td><td>[remove]</td></tr>
		<tr><td class="bold a-r">被修改的文件</td><td>[modify]</td></tr>
		<tr><td class="bold a-r">可能多余的文件</td><td>[unknown]</td></tr>
		<tr><td class="bold a-r">提示信息</td><td>[info]</td></tr>
		</table>';
		$checklist = array('remove'=>array(), 'modify'=>array(),'unknown'=>array());
		$filelist = $_ENV['filecheck']->get('Filelist'.HDWIKI_CODE);

		if (empty($filelist)){
			exit('没有 Filelist'.HDWIKI_CODE.'.php 校对文件。');
		}
		$params['skips']=array('data', 'uploads', 'install', 'plugins', 'config.php', 'api');
		$mylist = $this->md5list(HDWIKI_ROOT, $params);
		
		foreach($mylist as $filename => $md5str){
			$key = str_replace('/', '<b>/</b>', $filename);
			if (isset($filelist[$filename])){
				if ($filelist[$filename] !== $mylist[$filename]){
					$checklist['modify'][] = $key;
				}
			}else{
				$checklist['unknown'][] = $key;
			}
			unset($filelist[$filename]);
		}
		foreach($filelist as $filename => $md5str){
			$key = str_replace('/', '<b>/</b>', $filename);
			$checklist['remove'][] = $key;
		}

		foreach($checklist as $key => $value){
			$str = $this->_filecheck($checklist[$key]);
			$html = str_replace('['.$key.']', $str, $html);
			if ($str != '无'){$bool = false;}
		}

		if ($bool == true){
			$html = str_replace('[info]', '文件系统未发现异常。', $html);
		}else{
			$html = str_replace('[info]', '文件系统存在异常，请注意！', $html);
		}
		return  $html;
	}

	// 用于文件检测
	function md5list($path, $params=array(), $_deep=0){
		if ($_deep == 0){
			$this->list = array();
			$this->deep = 0;
			$this->basepath = $path;
		}
		if (empty($params['exts'])) $params['exts']='/\.(php|htm|js|css|jpg|gif|png)$/i';
		if (empty($params['skips'])) $params['skips']=array('data', 'uploads', 'install', 'tool');

		if ($handle = opendir($path)) {
			while(false !== ($entry = readdir($handle))) {
				$file = $path.DIRECTORY_SEPARATOR.$entry;
				$key = str_replace($this->basepath.DIRECTORY_SEPARATOR,'',$file);

				if($entry != '.' && $entry != '..' && $entry != '.svn' && !in_array($key, $params['skips'])) {
					if(is_dir($file)){
						$this->md5list($file, $params, ++$this->deep);
					}elseif(preg_match($params['exts'], $file)){
						if (DIRECTORY_SEPARATOR != '/'){
							$key = str_replace(DIRECTORY_SEPARATOR, '/', $key);
						}
						if (preg_match('/\.(php|htm|js|css)$/i', $file)){
							$content = file::readfromfile($file);
							$content = str_replace(array("\r\n", "\n", "\r"), '', $content);
							$this->out_list[$key] = md5($content);
						}else{
							$this->out_list[$key] = md5_file($file);
						}
					}
				}
			}
		}
		return $this->out_list;
	}



	function get($name, $expires = 0){
		$file = $this->dir.$name.'.php';
		
		if(!file_exists($file)){
			$data=util::hfopen($this->url_cache. rawurlencode($name) .'.php');
			if($data){
				file::forcemkdir($this->dir);
				$flag = file::writetofile($this->dir.$name.'.php', $data);
			}
			else return '';
		}

		if(file_exists($file)){
			$data = file::readfromfile($file);
			$data = str_replace('<?php exit;?>', '', $data);
			return unserialize(base64_decode($data));
		} else {
			return '';
		}
	}

	function _filecheck($datalist){
		if (!empty($datalist)){
			$bool = false;
			$str = '';
			foreach($datalist as $filename){
				$str .= $filename.'<br>';
			}
			if ($str == '') {$str = '无';}
		}else{
			$str = '无';
		}
		return $str;
	}
}