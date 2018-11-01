<?php
/**
 * @copyright   2008-2012 简好技术 <http://www.phpshe.com>
 * @creatdate   2010-1001 koyshe <koyshe@gmail.com>
 */
class upload {
	public $file;
	public $host;//url全路径
	public $path;//path全路径
	public $filehost;//url相对路径+文件
	public $filepath;//path相对路径+文件
	public $filehost_full;//url全路径+文件
	public $filepath_full;//path全路径+文件
	//上传的文件名
	public $filename = '';
	//允许上传的文件类型
	public $filetype = array('jpg','jpeg','gif','png','psd','wps','doc','xls','ppt','pdf','zip','rar','tar','txt','text');
	//文件上传大小控制(默认是2000kb)
	public $filesize = 2000000;
	function __construct($file, $path_save = null, $ext_arr = array())
	{
		global $pe;
		$this->file = $file;
		//配置存储路径（支持两种模式1：默认上传到默认附件目录里2：上传到自定义目录里）
		!$path_save && $path_save = 'data/attachment/'.date('Y-m').'/';

		$this->host = "{$pe['host_root']}{$path_save}";
		$this->path = "{$pe['path_root']}{$path_save}";
		$this->filename = $this->_filename($ext_arr['filename']);

		$this->filehost = $this->filepath = "{$path_save}{$this->filename}";
		$this->filehost_full = "{$this->host}{$this->filename}";
		$this->filepath_full = "{$this->path}{$this->filename}";

		$ext_arr['filetype'] && $this->filetype = $ext_arr['filetype'];
		$ext_arr['filesize'] && $this->filesize = $ext_arr['filesize'];
		//检测文件合法性
		$this->_filecheck();
		//上传移动
		$this->_filemove();
	}
	//检测文件的合法性
	function _filecheck()
	{
		if (!$this->file['name']) {
			$this->_alert("请选择文件");
		}
		if (@is_dir($this->path) === false) {
			mkdir($this->path, 0777, true);
		}
		if ($this->file['file_size'] > $this->filesize) {
			$this->_alert("上传文件大小超过限制");
		}
		if (!in_array($this->_filetail(), $this->filetype)) {
			$this->_alert("上传文件类型不被允许");
		}
	}
	//上传文件重命名
	function _filename($filename)
	{
		if ($filename) {
			return $filename . '.' . $this->_filetail();
		}
		else {
			$nametmp = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','d','u','v','w','x','y','z');
			return date("YmdHis") . $nametmp[array_rand($nametmp, 1)] . '.' . $this->_filetail();
		}
	}
	//获取文件扩展名
	function _filetail()
	{
		$filearr = explode('.', $this->file['name']);
		return strtolower($filearr[count($filearr) - 1]);
	}
	//上传文件移动到存储目录
	function _filemove()
	{
		if (move_uploaded_file($this->file['tmp_name'], $this->filepath_full) === false) {
			$this->_alert('上传失败...');
		}
	}
	function _alert($msg) {
		echo "<script type='text/javascript'>alert('{$msg}');</script>";
	}
}
?>