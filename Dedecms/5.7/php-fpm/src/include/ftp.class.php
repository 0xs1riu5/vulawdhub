<?php   if(!defined('DEDEINC')) exit('dedecms');
/**
 * FTP 操作类
 * 不支持 SFTP 和 SSL FTP 协议, 仅支持标准 FTP 协议.
 * 需要传递一个数组配置
 * 示例:
 * $config['hostname'] = 'ftp.example.com';
 * $config['username'] = 'your-username';
 * $config['password'] = 'your-password';
 * $config['debug'] = TRUE;
 *
 * @version        $Id: ftp.class.php 1 2010-07-05 11:43:09Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
@set_time_limit(1000);
class FTP {
    var $hostname    = '';
    var $username    = '';
    var $password    = '';
    var $port        = 21;
    var $passive    = TRUE;
    var $debug        = FALSE;
    var $conn_id    = FALSE;

    /**
     * 析构函数 - 设置参数
     *
     * 构造函数则传递一个配置数组
     */
    function FTP($config = array())
    {
        if (count($config) > 0)
        {
            $this->initialize($config);
        }
    }

    /**
     * 初始化设置
     *
     * @access    public
     * @param    array
     * @return    void
     */
    function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }

        // 准备主机名
        $this->hostname = preg_replace('|.+?://|', '', $this->hostname);
    }

    /**
     * FTP 链接
     *
     * @access    public
     * @param    array     链接值
     * @return    bool
     */
    function connect($config = array())
    {
        if (count($config) > 0)
        {
            $this->initialize($config);
        }

        if (FALSE === ($this->conn_id = @ftp_connect($this->hostname, $this->port)))
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法链接');
            }
            return FALSE;
        }

        if ( ! $this->_login())
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法登录');
            }
            return FALSE;
        }

        // 如果需要则设置传输模式
        if ($this->passive == TRUE)
        {
            ftp_pasv($this->conn_id, TRUE);
        }

        return TRUE;
    }

    /**
     * FTP 登录
     *
     * @access    private
     * @return    bool
     */
    function _login()
    {
        return @ftp_login($this->conn_id, $this->username, $this->password);
    }

    /**
     * 验证连接ID
     *
     * @access    private
     * @return    bool
     */
    function _is_conn()
    {
        if ( ! is_resource($this->conn_id))
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法链接');
            }
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 更改目录
     * 第二个参数可以让我们暂时关闭，以便调试
     * 此功能可用于检测是否存在一个文件夹
     * 抛出一个错误。没有什么的FTP相当于is_dir()
     * 因此，我们试图改变某一特定目录。
     *
     * @access    public
     * @param    string
     * @param    bool
     * @return    bool
     */
    function changedir($path = '', $supress_debug = FALSE)
    {
        if ($path == '' OR ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_chdir($this->conn_id, $path);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE AND $supress_debug == FALSE)
            {
                $this->_error('无法更改目录');
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 创建一个目录
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function mkdir($path = '', $permissions = NULL)
    {
        if ($path == '' OR ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_mkdir($this->conn_id, $path);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法创建文件夹');
            }
            return FALSE;
        }

        // 如果需要设置权限
        if ( ! is_null($permissions))
        {
            $this->chmod($path, (int)$permissions);
        }

        return TRUE;
    }

    /**
     * 创建深级目录
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function rmkdir($path = '', $pathsymbol = '/')
    {
        $pathArray = explode($pathsymbol,$path);
        $pathstr = $pathsymbol;
        foreach($pathArray as $val)
        {
            if(!empty($val))
            {
                //构建文件夹路径
                $pathstr = $pathstr.$val.$pathsymbol;
                if (! $this->_is_conn())
                {
                    return FALSE;
                }
                $result = @ftp_chdir($this->conn_id, $pathstr);
                if($result === FALSE)
                {
                    //如果不存在这个目录则创建
                    if(!$this->mkdir($pathstr))
                    {
                        return FALSE;
                    }
                }
            }
        }
        return TRUE;
    }

    /**
     * 上传一个文件到服务器
     *
     * @access    public
     * @param    string
     * @param    string
     * @param    string
     * @return    bool
     */
    function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
    {
        if (!$this->_is_conn())
        {
            return FALSE;
        }

        if (!file_exists($locpath))
        {
            $this->_error('不存在源文件');
            return FALSE;
        }

        // 未指定则设置模式
        if ($mode == 'auto')
        {
            // 获取文件扩展名，以便本类上传类型
            $ext = $this->_getext($locpath);
            $mode = $this->_settype($ext);
        }

        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

        $result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法上传');
            }
            return FALSE;
        }

        // 如果需要设置文件权限
        if ( ! is_null($permissions))
        {
            $this->chmod($rempath, (int)$permissions);
        }

        return TRUE;
    }

    /**
     * 重命名(或者移动)一个文件
     *
     * @access    public
     * @param    string
     * @param    string
     * @param    bool
     * @return    bool
     */
    function rename($old_file, $new_file, $move = FALSE)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_rename($this->conn_id, $old_file, $new_file);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $msg = ($move == FALSE) ? '无法重命名' : '无法移动';

                $this->_error($msg);
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 移动一个文件
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    bool
     */
    function move($old_file, $new_file)
    {
        return $this->rename($old_file, $new_file, TRUE);
    }

    /**
     * 重命名或者移动一个文件
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function delete_file($filepath)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_delete($this->conn_id, $filepath);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法删除');
            }
            return FALSE;
        }

        return TRUE;
    }
    
    /**
     * 删除一个文件夹，递归删除一切（包括子文件夹）中内容
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function delete_dir($filepath)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        // 如果需要在尾部加上尾随"/"
        $filepath = preg_replace("/(.+?)\/*$/", "\\1/",  $filepath);

        $list = $this->list_files($filepath);

        if ($list !== FALSE AND count($list) > 0)
        {
            foreach ($list as $item)
            {
                // 如果我们不能删除该项目,它则可能是一个文件夹
                // 将调用 delete_dir()
                if ( ! @ftp_delete($this->conn_id, $item))
                {
                    $this->delete_dir($item);
                }
            }
        }

        $result = @ftp_rmdir($this->conn_id, $filepath);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法删除');
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 设置文件权限
     *
     * @access    public
     * @param    string     文件地址
     * @param    string    权限
     * @return    bool
     */
    function chmod($path, $perm)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        // 仅PHP5才能运行
        if ( ! function_exists('ftp_chmod'))
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法更改权限');
            }
            return FALSE;
        }

        $result = @ftp_chmod($this->conn_id, $perm, $path);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('无法更改权限');
            }
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 在指定的目录的FTP文件列表
     *
     * @access    public
     * @return    array
     */
    function list_files($path = '.')
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        return ftp_nlist($this->conn_id, $path);
    }
    
    /**
     * 返回指定目录下文件的详细列表
     *
     * @access    public
     * @return    array
     */
    function list_rawfiles($path = '.', $type='dir')
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }
        
        $ftp_rawlist = ftp_rawlist($this->conn_id, $path, TRUE);
      foreach ($ftp_rawlist as $v) {
        $info = array();
        $vinfo = preg_split("/[\s]+/", $v, 9);
        if ($vinfo[0] !== "total") {
          $info['chmod'] = $vinfo[0];
          $info['num'] = $vinfo[1];
          $info['owner'] = $vinfo[2];
          $info['group'] = $vinfo[3];
          $info['size'] = $vinfo[4];
          $info['month'] = $vinfo[5];
          $info['day'] = $vinfo[6];
          $info['time'] = $vinfo[7];
          $info['name'] = $vinfo[8];
          $rawlist[$info['name']] = $info;
        }
      }
      
      $dir = array();
      $file = array();
      foreach ($rawlist as $k => $v) {
        if ($v['chmod']{0} == "d") {
          $dir[$k] = $v;
        } elseif ($v['chmod']{0} == "-") {
          $file[$k] = $v;
        }
      }

      return ($type == 'dir')? $dir : $file;
    }

    /**
     * 检索一个本地目录下的所有内容(包括子目录和所有文件)，并通过FTP为这个目录创建一份镜像。
     * 源路径下的任何结构都会被创建到服务器上。你必须给出源路径和目标路径
     *
     * @access    public
     * @param    string    含有尾随"/"的源路径
     * @param    string    目标路径 - 含有尾随"/"的文件夹
     * @return    bool
     */
    function mirror($locpath, $rempath)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        // 打开本地文件路径
        if ($fp = @opendir($locpath))
        {
            // 尝试打开远程文件的路径.
            if ( ! $this->changedir($rempath, TRUE))
            {
                // 如果不能打开则创建
                if ( ! $this->rmkdir($rempath) OR ! $this->changedir($rempath))
                {
                    return FALSE;
                }
            }

            // 递归读取本地目录
            while (FALSE !== ($file = readdir($fp)))
            {
                if (@is_dir($locpath.$file) && substr($file, 0, 1) != '.')
                {
                    $this->mirror($locpath.$file."/", $rempath.$file."/");
                }
                elseif (substr($file, 0, 1) != ".")
                {
                    // 获取文件扩展名，以便本类上传类型
                    $ext = $this->_getext($file);
                    $mode = $this->_settype($ext);

                    $this->upload($locpath.$file, $rempath.$file, $mode);
                }
            }
            return TRUE;
        }

        return FALSE;
    }

    /**
     * 取出文件扩展名
     *
     * @access    private
     * @param    string
     * @return    string
     */
    function _getext($filename)
    {
        if (FALSE === strpos($filename, '.'))
        {
            return 'txt';
        }

        $x = explode('.', $filename);
        return end($x);
    }

    /**
     * 设置上传类型
     *
     * @access    private
     * @param    string
     * @return    string
     */
    function _settype($ext)
    {
        $text_types = array(
                            'txt',
                            'text',
                            'php',
                            'phps',
                            'php4',
                            'js',
                            'css',
                            'htm',
                            'html',
                            'phtml',
                            'shtml',
                            'log',
                            'xml'
                            );


        return (in_array($ext, $text_types)) ? 'ascii' : 'binary';
    }

    /**
     * 关闭连接
     *
     * @access    public
     * @param    string    源路径
     * @param    string    目的地路径
     * @return    bool
     */
    function close()
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        @ftp_close($this->conn_id);
    }
    
    /**
     * 显示错误信息
     *
     * @access    private
     * @param    string
     * @return    bool
     */
    function _error($msg)
    {
        $errorTrackFile = dirname(__FILE__).'/../data/ftp_error_trace.inc';
        $emsg = '';
        $emsg .= "<div><h3>DedeCMS Error Warning!</h3>\r\n";
        $emsg .= "<div><a href='http://bbs.dedecms.com' target='_blank' style='color:red'>Technical Support: http://bbs.dedecms.com</a></div>";
        $emsg .= "<div style='line-helght:160%;font-size:14px;color:green'>\r\n";
        $emsg .= "<div style='color:blue'><br />Error page: <font color='red'>".$this->GetCurUrl()."</font></div>\r\n";
        $emsg .= "<div>Error infos: {$msg}</div>\r\n";
        $emsg .= "<br /></div></div>\r\n";
        
        echo $emsg;
        
        $savemsg = 'Page: '.$this->GetCurUrl()."\r\nError: ".$msg;
        //保存错误日志
        $fp = @fopen($errorTrackFile, 'a');
        @fwrite($fp, '<'.'?php  exit();'."\r\n/*\r\n{$savemsg}\r\n*/\r\n?".">\r\n");
        @fclose($fp);
    }

    /**
     * 获得当前的脚本网址
     *
     * @access    public
     * @return    string
     */
    function GetCurUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $scriptName = $_SERVER["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $_SERVER["PHP_SELF"];
            if(empty($_SERVER["QUERY_STRING"])) {
                $nowurl = $scriptName;
            }
            else {
                $nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
            }
        }
        return $nowurl;
    }

}//End Class