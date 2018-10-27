<?php
defined('IN_DESTOON') or exit('Access Denied');
header ( 'Content-type:text/html;charset=utf-8' );
#include_once 'log.class.php';

class PhpLog
	{
		const DEBUG = 1;// Most Verbose
		const INFO = 2;// ...
		const WARN = 3;// ...
		const ERROR = 4;// ...
		const FATAL = 5;// Least Verbose
		const OFF = 6;// Nothing at all.
		 
		const LOG_OPEN = 1;
		const OPEN_FAILED = 2;
		const LOG_CLOSED = 3;
		 
		/* Public members: Not so much of an example of encapsulation, but that's okay. */
		public $Log_Status = PhpLog::LOG_CLOSED;
		public $DateFormat= "Y-m-d G:i:s";
		public $MessageQueue;
		 
		private $filename;
		private $log_file;
		private $priority = PhpLog::INFO;
		 
		private $file_handle;
		
		/**
		 * AUTHOR:	gu_yongkang
		 * DATA:	20110322
		 * Enter description here ...
		 * @param $filepath
		 * 文件存储的路径
		 * @param $timezone
		 * 时间格式，此处设置为"PRC"（中国）
		 * @param $priority
		 * 设置运行级别
		 */
		 
		public function __construct( $filepath, $timezone, $priority )
		{
			if ( $priority == PhpLog::OFF ) return;
			 
			$this->filename = date('Y-m-d', time()) . '.log';	//默认为以时间＋.log的文件文件
			$this->log_file = $this->createPath($filepath, $this->filename);
			$this->MessageQueue = array();
			$this->priority = $priority;
			date_default_timezone_set($timezone);
			 
			if ( !file_exists($filepath) )	//判断文件路径是否存在
			{
				if(!empty($filepath))	//判断路径是否为空
				{
					if(!($this->_createDir($filepath)))
					{
						$this->Log_Status = PhpLog::OPEN_FAILED;
						$this->MessageQueue[] = "Create file failed.";
						return;
					}
					if ( !is_writable($this->log_file) )
					{
						$this->Log_Status = PhpLog::OPEN_FAILED;
						$this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
						return;
					}
				}
			}
		 
			if ( $this->file_handle = fopen( $this->log_file , "a+" ) )
			{
				$this->Log_Status = PhpLog::LOG_OPEN;
				$this->MessageQueue[] = "The log file was opened successfully.";
			}
			else
			{
				$this->Log_Status = PhpLog::OPEN_FAILED;
				$this->MessageQueue[] = "The file could not be opened. Check permissions.";
			}
			return;
		}
		 
		public function __destruct()
		{
			if ( $this->file_handle )
			fclose( $this->file_handle );
		}
		
		/**
	     *作用:创建目录
	     *输入:要创建的目录
	     *输出:true | false
	     */
		private  function _createDir($dir)
		{
		//	return is_dir($dir) or (self::_createDir(dirname($dir)) and mkdir($dir, 0777));
			return false;
		}
		
		/**
	     *作用:构建路径
	     *输入:文件的路径,要写入的文件名
	     *输出:构建好的路径字串
	     */
		private function createPath($dir, $filename)
		{
			if (empty($dir)) 
			{
				return $filename;
			} 
			else 
			{
				return $dir . "/" . $filename;
			}
		}
		 
		public function LogInfo($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * 增加打印函数和文件名的功能
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::INFO, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogDebug($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * 增加打印函数和文件名的功能
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::DEBUG, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogWarn($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * 增加打印函数和文件名的功能
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::WARN, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogError($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * 增加打印函数和文件名的功能
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::ERROR, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}
		 
		public function LogFatal($line)
		{
			/**
			 * AUTHOR : gu_yongkang
			 * 增加打印函数和文件名的功能
			 */
			$sAarray = array();
			$sAarray = debug_backtrace();
			$sGetFilePath = $sAarray[0]["file"];
			$sGetFileLine = $sAarray[0]["line"];
			$this->Log( $line, PhpLog::FATAL, $sGetFilePath, $sGetFileLine);
			unset($sAarray);
			unset($sGetFilePath);
			unset($sGetFileLine);
		}

		/**
		 * Author ： gu_yongkang
		 * Enter description here ...
		 * @param unknown_type $line
		 * content 内容
		 * @param unknown_type $priority
		 * 打印级别
		 * @param unknown_type $sFile
		 * 调用打印日志的文件名
		 * @param unknown_type $iLine
		 * 打印文件的位置（行数）
		 */
		public function Log($line, $priority, $sFile, $iLine)
		{
			if ($iLine > 0)
			{
				//$line = iconv('GBK', 'UTF-8', $line);
				if ( $this->priority <= $priority )
				{
					$status = $this->getTimeLine( $priority, $sFile, $iLine);
					$this->WriteFreeFormLine ( "$status $line \n" );
				}
			}
			else 
			{
				/**
				 * AUTHOR : gu_yongkang
				 * 增加打印函数和文件名的功能
				 */
				$sAarray = array();
				$sAarray = debug_backtrace();
				$sGetFilePath = $sAarray[0]["file"];
				$sGetFileLine = $sAarray[0]["line"];
				if ( $this->priority <= $priority )
				{
					$status = $this->getTimeLine( $priority, $sGetFilePath, $sGetFileLine);
					unset($sAarray);
					unset($sGetFilePath);
					unset($sGetFileLine);
					$this->WriteFreeFormLine ( "$status $line \n" );
				}
			}
		}
		 // 支持输入多个参数
		public function WriteFreeFormLine( $line )
		{
			if ( $this->Log_Status == PhpLog::LOG_OPEN && $this->priority != PhpLog::OFF )
			{
				if (fwrite( $this->file_handle , $line ) === false) 
				{
					$this->MessageQueue[] = "The file could not be written to. Check that appropriate permissions have been set.";
				}
			}
		}
		private function getRemoteIP()
		{
			foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
			{
				if (array_key_exists($key, $_SERVER) === true)
				{
					foreach (explode(',', $_SERVER[$key]) as $ip)
					{
						$ip = trim($ip);
						if (!empty($ip))
						{
							return $ip;
						}
					}
				}
			}
			return "_NO_IP";
		}
		 
		private function getTimeLine( $level, $FilePath, $FileLine)
		{			
			$time = date( $this->DateFormat );
			$ip = $this->getRemoteIP();
			switch( $level )
			{
				case PhpLog::INFO:
				return "$time, " . "INFO, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::WARN:
				return "$time, " . "WARN, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::DEBUG:
				return "$time, " . "DEBUG, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::ERROR:
				return "$time, " . "ERROR, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				case PhpLog::FATAL:
				return "$time, " . "FATAL, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
				default:
				return "$time, " . "LOG, " . "$ip, " . "File[ $FilePath ], " . "Line[$FileLine]" . "------";
			}
		}
	}

#include_once 'SDKConfig.php';
#include_once 'common.php';

class LogUtil
{
	private static $_logger = null;
	public static function getLogger()
	{
		if (LogUtil::$_logger == null ) {
			LogUtil::$_logger = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
		}
		return self::$_logger;
	}
}

/**
 * key1=value1&key2=value2转array
 * @param $str key1=value1&key2=value2的字符串
 * @param $$needUrlDecode 是否需要解url编码，默认不需要
 */
function parseQString($str, $needUrlDecode=false){
	$result = array();
	$len = strlen($str);
	$temp = "";
	$curChar = "";
	$key = "";
	$isKey = true;
	$isOpen = false;
	$openName = "\0";

	for($i=0; $i<$len; $i++){
		$curChar = $str[$i];
		if($isOpen){
			if( $curChar == $openName){
				$isOpen = false;
			}
			$temp = $temp . $curChar;
		} elseif ($curChar == "{"){
			$isOpen = true;
			$openName = "}";
			$temp = $temp . $curChar;
		} elseif ($curChar == "["){
			$isOpen = true;
			$openName = "]";
			$temp = $temp . $curChar;
		} elseif ($isKey && $curChar == "="){
			$key = $temp;
			$temp = "";
			$isKey = false;
		} elseif ( $curChar == "&" && !$isOpen){
			putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
			$temp = "";
			$isKey = true;
		} else {
			$temp = $temp . $curChar;
		}
	}
	putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
	return $result;
}


function putKeyValueToDictionary($temp, $isKey, $key, &$result, $needUrlDecode) {
	if ($isKey) {
		$key = $temp;
		if (strlen ( $key ) == 0) {
			return false;
		}
		$result [$key] = "";
	} else {
		if (strlen ( $key ) == 0) {
			return false;
		}
		if ($needUrlDecode)
			$result [$key] = urldecode ( $temp );
		else
			$result [$key] = $temp;
	}
}

/**
 * 字符串转换为 数组
 *
 * @param unknown_type $str
 * @return multitype:unknown
 */
function convertStringToArray($str) {
	return parseQString($str);
}

/**
 * 压缩文件 对应java deflate
 *
 * @param unknown_type $params        	
 */
function deflate_file(&$params) {
	$logger = LogUtil::getLogger();
	foreach ( $_FILES as $file ) {
		$logger->LogInfo ( "---------处理文件---------" );
		if (file_exists ( $file ['tmp_name'] )) {
			$params ['fileName'] = $file ['name'];
			
			$file_content = file_get_contents ( $file ['tmp_name'] );
			$file_content_deflate = gzcompress ( $file_content );
			
			$params ['fileContent'] = base64_encode ( $file_content_deflate );
			$logger->LogInfo ( "压缩后文件内容为>" . base64_encode ( $file_content_deflate ) );
		} else {
			$logger->LogInfo ( ">>>>文件上传失败<<<<<" );
		}
	}
}


/**
 * 讲数组转换为string
 *
 * @param $para 数组        	
 * @param $sort 是否需要排序        	
 * @param $encode 是否需要URL编码        	
 * @return string
 */
function createLinkString($para, $sort, $encode) {
	if($para == NULL || !is_array($para))
		return "";
	
	$linkString = "";
	if ($sort) {
		$para = argSort ( $para );
	}
	while ( list ( $key, $value ) = each ( $para ) ) {
		if ($encode) {
			$value = urlencode ( $value );
		}
		$linkString .= $key . "=" . $value . "&";
	}
	// 去掉最后一个&字符
	$linkString = substr ( $linkString, 0, count ( $linkString ) - 2 );
	
	return $linkString;
}

/**
 * 对数组排序
 *
 * @param $para 排序前的数组
 *        	return 排序后的数组
 */
function argSort($para) {
	ksort ( $para );
	reset ( $para );
	return $para;
}


#include_once 'cert_util.php';

class Cert
{
    public $cert;
    public $certId;
    public $key;
}


// 内存泄漏问题说明：
//     openssl_x509_parse疑似有内存泄漏，暂不清楚原因，可能和php、openssl版本有关，估计有bug。
//     windows下试过php5.4+openssl0.9.8，php7.0+openssl1.0.2都有这问题。mac下试过也有问题。
//     不过至今没人来反馈过这个问题，所以不一定真有泄漏？或者因为增长量不大所以一般都不会遇到问题？
//     也有别人汇报过bug：https://bugs.php.net/bug.php?id=71519
//
// 替代解决方案：
//     方案1. 所有调用openssl_x509_parse的地方都是为了获取证书序列号，可以尝试把证书序列号+证书/key以别的方式保存，
//            从其他地方（比如数据库）读序列号，而不直接从证书文件里读序列号。
//     方案2. 代码改成执行脚本的方式执行，这样执行完一次保证能释放掉所有内存。
//     方案3. 改用下面的CertSerialUtil取序列号，
//            此方法仅用了几个测试和生产的证书做过测试，不保证没bug，所以默认注释掉了。如发现有bug或者可优化的地方可自行修改代码。
//            注意用了bcmath的方法，*nix下编译时需要 --enable-bcmath。http://php.net/manual/zh/bc.installation.php


class CertUtil{

    private static $signCerts = array();
    private static $encryptCerts = array();
    private static $verifyCerts = array();

    private static function initSignCert($certPath, $certPwd){
        $logger = LogUtil::getLogger();
        $logger->LogInfo("读取签名证书……");
        $cert = new Cert();

        $pkcs12certdata = file_get_contents ( $certPath );
        if($pkcs12certdata === false ){
        	$logger->LogInfo($certPath . "读取失败。");
        }
        
        openssl_pkcs12_read ( $pkcs12certdata, $certs, $certPwd );
        $x509data = $certs ['cert'];

        openssl_x509_read ( $x509data );
        $certdata = openssl_x509_parse ( $x509data );
        $cert->certId = $certdata ['serialNumber'];

// 		$certId = CertSerialUtil::getSerial($x509data, $errMsg);
// 		if($certId === false){
//         	$logger->LogInfo("签名证书读取序列号失败：" . $errMsg);
//         	return;
// 		}
//         $cert->certId = $certId;
        
        $cert->key = $certs ['pkey'];
        $cert->cert = $x509data;

        $logger->LogInfo("签名证书读取成功，序列号：" . $cert->certId);
        CertUtil::$signCerts[$certPath] = $cert;
    }

    public static function getSignKeyFromPfx($certPath=SDK_SIGN_CERT_PATH, $certPwd=SDK_SIGN_CERT_PWD)
    {
        if (!array_key_exists($certPath, CertUtil::$signCerts)) {
            self::initSignCert($certPath, $certPwd);
        }
        return CertUtil::$signCerts[$certPath] -> key;
    }

    public static function getSignCertIdFromPfx($certPath=SDK_SIGN_CERT_PATH, $certPwd=SDK_SIGN_CERT_PWD)
    {
        if (!array_key_exists($certPath, CertUtil::$signCerts)) {
            self::initSignCert($certPath, $certPwd);
        }
        return CertUtil::$signCerts[$certPath] -> certId;
    }

    private static function initEncryptCert($cert_path)
    {
        $logger = LogUtil::getLogger();
        $logger->LogInfo("读取加密证书……");
        $cert = new Cert();
	    $x509data = file_get_contents ( $cert_path );
        if($x509data === false ){
        	$logger->LogInfo($cert_path . "读取失败。");
        }
	    
	    openssl_x509_read ( $x509data );
	    $certdata = openssl_x509_parse ( $x509data );
	    $cert->certId = $certdata ['serialNumber'];

// 	    $certId = CertSerialUtil::getSerial($x509data, $errMsg);
// 	    if($certId === false){
// 	    	$logger->LogInfo("签名证书读取序列号失败：" . $errMsg);
// 	    	return;
// 	    }
// 	    $cert->certId = $certId;
	    
        $cert->key = $x509data;
        CertUtil::$encryptCerts[$cert_path] = $cert;
        $logger->LogInfo("加密证书读取成功，序列号：" . $cert->certId);
    }

    public static function getEncryptCertId($cert_path=SDK_ENCRYPT_CERT_PATH){
        if(!array_key_exists($cert_path, CertUtil::$encryptCerts)){
            self::initEncryptCert($cert_path);
        }
        return CertUtil::$encryptCerts[$cert_path] -> certId;
    }

    public static function getEncryptKey($cert_path=SDK_ENCRYPT_CERT_PATH){
        if(!array_key_exists($cert_path, CertUtil::$encryptCerts)){
            self::initEncryptCert($cert_path);
        }
        return CertUtil::$encryptCerts[$cert_path] -> key;
    }

    private static function initVerifyCerts($cert_dir=SDK_VERIFY_CERT_DIR) {
        $logger = LogUtil::getLogger();
        $logger->LogInfo ( '验证签名证书目录 :>' . $cert_dir );
        $handle = opendir ( $cert_dir );
        if (!$handle) {
            $logger->LogInfo ( '证书目录 ' . $cert_dir . '不正确' );
            return;
        }
        while ($file = readdir($handle)) {
            clearstatcache();
            $filePath = $cert_dir . '/' . $file;
            if (is_file($filePath)) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'cer') {
                    $x509data = file_get_contents($filePath);
			        if($x509data === false ){
			        	$logger->LogInfo($filePath . "读取失败。");
			        }
                    $cert = new Cert();
                    openssl_x509_read($x509data);
                    $certdata = openssl_x509_parse($x509data);
                    $cert->certId = $certdata ['serialNumber'];

//                     $certId = CertSerialUtil::getSerial($x509data, $errMsg);
//                     if($certId === false){
//                     	$logger->LogInfo("签名证书读取序列号失败：" . $errMsg);
//                     	return;
//                     }
//                     $cert->certId = $certId;

                    $cert->key = $x509data;
                    CertUtil::$verifyCerts[$cert->certId] = $cert;
                    $logger->LogInfo($filePath . "读取成功，序列号：" . $cert->certId);
                }
            }
        }
        closedir ( $handle );
    }

    public static function getVerifyCertByCertId($certId){
        $logger = LogUtil::getLogger();
        if(count(CertUtil::$verifyCerts) == 0){
            self::initVerifyCerts();
        }
        if(count(CertUtil::$verifyCerts) == 0){
            $logger.LogInfo("未读取到任何证书……");
            return null;
        }
        if(array_key_exists($certId, CertUtil::$verifyCerts)){
            return CertUtil::$verifyCerts[$certId]->key;
        } else {
            $logger.LogInfo("未匹配到序列号为[" . certId . "]的证书");
            return null;
        }
    }
    
	public static function test() {
		
		$x509data = file_get_contents ( "d:/certs/acp_test_enc.cer" );
// 		$resource = openssl_x509_read ( $x509data );
		// $certdata = openssl_x509_parse ( $resource ); //<=这句尼玛内存泄漏啊根本释放不掉啊啊啊啊啊啊啊
		// echo $certdata ['serialNumber']; //<=就是需要这个数据啦
		// echo $x509data;
		// unset($certdata); //<=没有什么用
		// openssl_x509_free($resource); //<=没有什么用x2
		echo CertSerialUtil::getSerial ( $x509data, $errMsg ) . "\n";
	}
}

class CertSerialUtil {
	 
	private static function bytesToInteger($bytes) {
		$val = 0;
		for($i = 0; $i < count ( $bytes ); $i ++) {
// 			$val += (($bytes [$i] & 0xff) << (8 * (count ( $bytes ) - 1 - $i)));
			$val += $bytes [$i] * pow(256, count ( $bytes ) - 1 - $i);
// 			echo $val . "<br>\n";
		}
		return $val;
	}
	
	private static function bytesToBigInteger($bytes) {
		$val = 0;
		for($i = 0; $i < count ( $bytes ); $i ++) {
			$val = bcadd($val, bcmul($bytes [$i], bcpow(256, count ( $bytes ) - 1 - $i)));
// 			echo $val . "<br>\n";
		}
		return $val;
	}
	
	private static function toStr($bytes) {
		$str = '';
		foreach($bytes as $ch) {
			$str .= chr($ch);
		}
		return $str;
	}
	
	public static function getSerial($fileData, &$errMsg) {
		
// 		$fileData = str_replace('\n','',$fileData);
// 		$fileData = str_replace('\r','',$fileData);
		
		$start = "-----BEGIN CERTIFICATE-----";
		$end = "-----END CERTIFICATE-----";
		$data = trim ( $fileData );
		if (substr ( $data, 0, strlen ( $start ) ) != $start || 
		substr ( $data, strlen ( $data ) - strlen ( $end ) ) != $end) {
			// echo $fileData;
			$errMsg = "error pem data";
			return false;
		}
		
		$data = substr ( $data, strlen ( $start ), strlen ( $data ) - strlen ( $end ) - strlen ( $start ) );
		$bindata = base64_decode ( $data );
		$bindata = unpack ( 'C*', $bindata );
		
		$byte = array_shift ( $bindata );
		if ($byte != 0x30) {
			$errMsg = "1st tag " . $byte . " is not 30"; 
			return false;
		}
		
		$length = CertSerialUtil::readLength ( $bindata ); 
		$byte = array_shift ( $bindata );
		if ($byte != 0x30) {
			$errMsg = "2nd tag " . $byte . " is not 30";
			return false;
		}
		
		$length = CertSerialUtil::readLength ( $bindata );
		$byte = array_shift ( $bindata );
// 		echo $byte . "<br>\n";
		if ($byte == 0xa0) { //version tag.
			$length = CertSerialUtil::readLength ( $bindata );
			CertSerialUtil::readData ( $bindata, $length );
			$byte = array_shift ( $bindata );
		}

// 		echo $byte . "<br>\n";
		if ($byte != 0x02) { //x509v1 has no version tag, x509v3 has.
			$errMsg = "4th/3rd tag " . $byte . " is not 02";
			return false;
		}
		$length = CertSerialUtil::readLength ( $bindata );
		$serial = CertSerialUtil::readData ( $bindata, $length );
// 		echo bin2hex(CertSerialUtil::toStr( $serial ));
		return CertSerialUtil::bytesToBigInteger($serial);
	}
	
	private static function readLength(&$bindata) {
		$byte = array_shift ( $bindata );
		if ($byte < 0x80) {
			$length = $byte;
		} else {
			$lenOfLength = $byte - 0x80;
			for($i = 0; $i < $lenOfLength; $i ++) {
				$lenBytes [] = array_shift ( $bindata );
			}
			$length = CertSerialUtil::bytesToInteger ( $lenBytes );
		}
		return $length;
	}
	
	private static function readData(&$bindata, $length) {
		$data = array ();
		for($i = 0; $i < $length; $i ++) {
			$data [] = array_shift ( $bindata );
		}
		return $data;
	}
}

class AcpService {
	
	/**
	 * 签名
	 * @param req 请求要素
	 * @param resp 应答要素
	 * @return 是否成功
	 */
	static function sign(&$params, $cert_path=SDK_SIGN_CERT_PATH, $cert_pwd=SDK_SIGN_CERT_PWD) {

		$params ['certId'] = CertUtil::getSignCertIdFromPfx($cert_path, $cert_pwd); //证书ID

		$logger = LogUtil::getLogger();
		$logger->LogInfo ( '=====签名报文开始======' );

		if(isset($params['signature'])){
			unset($params['signature']);
		}
		// 转换成key=val&串
		$params_str = createLinkString ( $params, true, false );
		$logger->LogInfo ( "签名key=val&...串 >" . $params_str );

		$params_sha1x16 = sha1 ( $params_str, FALSE );
		$logger->LogInfo ( "摘要sha1x16 >" . $params_sha1x16 );

		$private_key = CertUtil::getSignKeyFromPfx( $cert_path, $cert_pwd );

		// 签名
		//exit($signature.'abc'.date('Y-m-d H:i:s'));
		$sign_falg = openssl_sign ( $params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1 );
		if ($sign_falg) {
			$signature_base64 = base64_encode ( $signature );
			$logger->LogInfo ( "签名串为 >" . $signature_base64 );
			$params ['signature'] = $signature_base64;
		} else {
			$logger->LogInfo ( ">>>>>签名失败<<<<<<<" );
		}
		$logger->LogInfo ( '=====签名报文结束======' );

	}
	
	/**
	 * 验签
	 * @param $params 应答数组
	 * @return 是否成功
	 */
	static function validate($params) {
		$logger = LogUtil::getLogger();
		// 公钥
		$public_key = CertUtil::getVerifyCertByCertId ( $params ['certId'] );
//	echo $public_key.'<br/>';
		// 签名串
		$signature_str = $params ['signature'];
		unset ( $params ['signature'] );
		$params_str = createLinkString ( $params, true, false );
		$logger->LogInfo ( '报文去[signature] key=val&串>' . $params_str );
		$signature = base64_decode ( $signature_str );
//	echo date('Y-m-d',time());
		$params_sha1x16 = sha1 ( $params_str, FALSE );
		$logger->LogInfo ( '摘要shax16>' . $params_sha1x16 );
		$isSuccess = openssl_verify ( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
		$logger->LogInfo ( $isSuccess ? '验签成功' : '验签失败' );
		return $isSuccess;
	}
	
	/**
	 * 对控件支付成功返回的结果信息中data域进行验签
	 * @param $jsonData json格式数据，例如：{"sign" : "J6rPLClQ64szrdXCOtV1ccOMzUmpiOKllp9cseBuRqJ71pBKPPkZ1FallzW18gyP7CvKh1RxfNNJ66AyXNMFJi1OSOsteAAFjF5GZp0Xsfm3LeHaN3j/N7p86k3B1GrSPvSnSw1LqnYuIBmebBkC1OD0Qi7qaYUJosyA1E8Ld8oGRZT5RR2gLGBoiAVraDiz9sci5zwQcLtmfpT5KFk/eTy4+W9SsC0M/2sVj43R9ePENlEvF8UpmZBqakyg5FO8+JMBz3kZ4fwnutI5pWPdYIWdVrloBpOa+N4pzhVRKD4eWJ0CoiD+joMS7+C0aPIEymYFLBNYQCjM0KV7N726LA==",  "data" : "pay_result=success&tn=201602141008032671528&cert_id=68759585097"}
	 * @return 是否成功
	 */
	static function validateAppResponse($jsonData) {

		$data = json_decode($jsonData);
		$sign = $data->sign;
		$data = $data->data;
		$dataMap = parseQString($data);
		$public_key = CertUtil::getVerifyCertByCertId( $dataMap ['cert_id'] );
		$signature = base64_decode ( $sign );
		$params_sha1x16 = sha1 ( $data, FALSE );
		$isSuccess = openssl_verify ( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
		return $isSuccess;
	}
	
	/**
	 * 后台交易 HttpClient通信
	 *
	 * @param unknown_type $params        	
	 * @param unknown_type $url        	
	 * @return mixed
	 */
	 static function post($params, $url) {
		 $logger = LogUtil::getLogger();
	 	
		 $opts = createLinkString ( $params, false, true );
		 $logger->LogInfo ( "后台请求地址为>" . $url );
		 $logger->LogInfo ( "后台请求报文为>" . $opts );
 	    
		 $ch = curl_init ();
		 curl_setopt ( $ch, CURLOPT_URL, $url );
		 curl_setopt ( $ch, CURLOPT_POST, 1 );
		 curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // 不验证证书
		 curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); // 不验证HOST
		 curl_setopt ( $ch, CURLOPT_SSLVERSION, 1 ); // http://php.net/manual/en/function.curl-setopt.php页面搜CURL_SSLVERSION_TLSv1
		 curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Content-type:application/x-www-form-urlencoded;charset=UTF-8' 
		 ) );
		 curl_setopt ( $ch, CURLOPT_POSTFIELDS, $opts );
		 curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		 $html = curl_exec ( $ch );
		 $logger->LogInfo ( "后台返回结果为>" . $html );
		
		 if(curl_errno($ch)){
			 $errmsg = curl_error($ch);
			 curl_close ( $ch );
			 $logger->LogInfo ( "请求失败，报错信息>" . $errmsg );
			 return null;
		 }
	     if( curl_getinfo($ch, CURLINFO_HTTP_CODE) != "200"){
			$errmsg = "http状态=" . curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close ( $ch );
			 $logger->LogInfo ( "请求失败，报错信息>" . $errmsg );
			return null;
	    }
		curl_close ( $ch );
		$result_arr = convertStringToArray ( $html );
		return $result_arr;
	}
	
	/**
	 * 后台交易 HttpClient通信
	 *
	 * @param unknown_type $params
	 * @param unknown_type $url
	 * @return mixed
	 */
	static function get($params, $url) {

		$logger = LogUtil::getLogger();
		
		$opts = createLinkString ( $params, false, true );
		$logger->LogDebug( "后台请求地址为>" . $url ); //get的日志太多而且没啥用，设debug级别
		$logger->LogDebug ( "后台请求报文为>" . $opts );
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // 不验证证书
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false ); // 不验证HOST
		curl_setopt ( $ch, CURLOPT_SSLVERSION, 1 ); // http://php.net/manual/en/function.curl-setopt.php页面搜CURL_SSLVERSION_TLSv1
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
		'Content-type:application/x-www-form-urlencoded;charset=UTF-8'
				) );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $opts );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$html = curl_exec ( $ch );
		$logger->LogInfo ( "后台返回结果为>" . $html );
		if(curl_errno($ch)){
			$errmsg = curl_error($ch);
			curl_close ( $ch );
			$logger->LogDebug ( "请求失败，报错信息>" . $errmsg );
			return null;
		}
		if( curl_getinfo($ch, CURLINFO_HTTP_CODE) != "200"){
			$errmsg = "http状态=" . curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close ( $ch );
			$logger->LogDebug ( "请求失败，报错信息>" . $errmsg );
			return null;
		}
		curl_close ( $ch );
		return $html;
	}
	
	static function createAutoFormHtml($params, $reqUrl) {
		// <body onload="javascript:document.pay_form.submit();">
		$encodeType = isset ( $params ['encoding'] ) ? $params ['encoding'] : 'UTF-8';
		$html = <<<eot
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$encodeType}" />
</head>
<body onload="javascript:document.pay_form.submit();">
    <form id="pay_form" name="pay_form" action="{$reqUrl}" method="post">
	
eot;
		foreach ( $params as $key => $value ) {
			$html .= "    <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
		}
		$html .= <<<eot
   <!-- <input type="submit" type="hidden">-->
    </form>
</body>
</html>
eot;
		$logger = LogUtil::getLogger();
		$logger->LogInfo ( "自动跳转html>" . $html );
		return $html;
	}
	
	
	
	static function getCustomerInfo($customerInfo) {
	  if($customerInfo == null || count($customerInfo) == 0 )
	  	return "";
		return base64_encode ( "{" . createLinkString ( $customerInfo, false, false ) . "}" );
	}
	
	/**
	 * map转换string，按新规范加密
	 *
	 * @param
	 *        	$customerInfo
	 */
	static function getCustomerInfoWithEncrypt($customerInfo) {
	  if($customerInfo == null || count($customerInfo) == 0 )
	  	return "";
		$encryptedInfo = array();
		foreach ( $customerInfo as $key => $value ) {
			if ($key == 'phoneNo' || $key == 'cvn2' || $key == 'expired' ) {
			//if ($key == 'phoneNo' || $key == 'cvn2' || $key == 'expired' || $key == 'certifTp' || $key == 'certifId') {
				$encryptedInfo [$key] = $customerInfo [$key];
				unset ( $customerInfo [$key] );
			}
		}
		if( count ($encryptedInfo) > 0 ){
			$encryptedInfo = createLinkString ( $encryptedInfo, false, false );
			$encryptedInfo = AcpService::encryptData ( $encryptedInfo, SDK_ENCRYPT_CERT_PATH );
			$customerInfo ['encryptedInfo'] = $encryptedInfo;
		}
		return base64_encode ( "{" . createLinkString ( $customerInfo, false, false ) . "}" );
	}
	
	
	/**
	 * 解析customerInfo。
	 * 为方便处理，encryptedInfo下面的信息也均转换为customerInfo子域一样方式处理，
	 * @param unknown $customerInfostr
	 * @return array形式ParseCustomerInfo
	 */
	static function parseCustomerInfo($customerInfostr) {
		$customerInfostr = base64_decode($customerInfostr);
		$customerInfostr = substr($customerInfostr, 1, strlen($customerInfostr) - 2);
		$customerInfo = parseQString($customerInfostr);
		if(array_key_exists("encryptedInfo", $customerInfo)) {
			$encryptedInfoStr = $customerInfo["encryptedInfo"];
			unset ( $customerInfo ["encryptedInfo"] );
			$encryptedInfoStr = AcpService::decryptData($encryptedInfoStr);
			$encryptedInfo = parseQString($encryptedInfoStr);
			foreach ($encryptedInfo as $key => $value){
				$customerInfo[$key] = $value;
			}
		}
		return $customerInfo;
	}
	
	
	static function getEncryptCertId($cert_path=SDK_ENCRYPT_CERT_PATH) {
		return CertUtil::getEncryptCertId( $cert_path );
	}
	
	/**
	 * 加密数据
	 * @param string $data数据
	 * @param string $cert_path 证书配置路径
	 * @return unknown
	 */
	static function encryptData($data, $cert_path=SDK_ENCRYPT_CERT_PATH) {
		$public_key = CertUtil::getEncryptKey( $cert_path );
		openssl_public_encrypt ( $data, $crypted, $public_key );
		return base64_encode ( $crypted );
	}
	
	/**
	 * 解密数据
	 * @param string $data数据
	 * @param string $cert_path 证书配置路径
	 * @return unknown
	 */
	static function decryptData($data, $cert_path=SDK_SIGN_CERT_PATH, $cert_pwd=SDK_SIGN_CERT_PWD) {
		$data = base64_decode ( $data );
		$private_key = CertUtil::getSignKeyFromPfx ( $cert_path, $cert_pwd);
		openssl_private_decrypt ( $data, $crypted, $private_key );
		return $crypted;
	}
	
	
	/**
	 * 处理报文中的文件
	 *
	 * @param unknown_type $params
	 */
	static function deCodeFileContent($params, $fileDirectory=SDK_FILE_DOWN_PATH) {
		$logger = LogUtil::getLogger();
		if (isset ( $params ['fileContent'] )) {
			$logger->LogInfo ( "---------处理后台报文返回的文件---------" );
			$fileContent = $params ['fileContent'];
	
			if (empty ( $fileContent )) {
				$logger->LogInfo ( '文件内容为空' );
				return false;
			} else {
				// 文件内容 解压缩
				$content = gzuncompress ( base64_decode ( $fileContent ) );
				$filePath = null;
				if (empty ( $params ['fileName'] )) {
					$logger->LogInfo ( "文件名为空" );
					$filePath = $fileDirectory . $params ['merId'] . '_' . $params ['batchNo'] . '_' . $params ['txnTime'] . '.txt';
				} else {
					$filePath = $fileDirectory . $params ['fileName'];
				}
				$handle = fopen ( $filePath, "w+" );
				if (! is_writable ( $filePath )) {
					$logger->LogInfo ( "文件:" . $filePath . "不可写，请检查！" );
					return false;
				} else {
					file_put_contents ( $filePath, $content );
					$logger->LogInfo ( "文件位置 >:" . $filePath );
				}
				fclose ( $handle );
			}
			return true;
		} else {
			return false;
		}
	}
	
	
	static function enCodeFileContent($path){
	
		$file_content_base64 = '';
		if(!file_exists($path)){
			echo '文件没找到';
			return false;
		}
	
		$file_content = file_get_contents ( $path );
		//UTF8 去掉文本中的 bom头
		$BOM = chr(239).chr(187).chr(191);
		$file_content = str_replace($BOM,'',$file_content);
		$file_content_deflate = gzcompress ( $file_content );
		$file_content_base64 = base64_encode ( $file_content_deflate );
		return $file_content_base64;
	}

}

