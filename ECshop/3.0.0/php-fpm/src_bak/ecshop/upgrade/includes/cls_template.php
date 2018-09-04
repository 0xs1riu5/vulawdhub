<?php

/**
 * ECSHOP 模板类
 * ============================================================================

 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: paulgao $
 * $Date: 2007-01-30 15:37:32 +0800 (星期二, 30 一月 2007) $
 * $Id: index.php 4749 2007-01-30 07:37:32Z paulgao $
 */

class template
{
    /**
    * 用来存储变量的空间
    *
    * @access  private
    * @var     array      $vars
    */
    var $vars = array();

   /**
    * 模板存放的目录路径
    *
    * @access  private
    * @var     string      $path
    */
    var $path = '';

    /**
     * 构造函数
     *
     * @access  public
     * @param   string       $path
     * @return  void
     */
    function __construct($path)
    {
        $this->template($path);
    }

    /**
     * 构造函数
     *
     * @access  public
     * @param   string       $path
     * @return  void
     */
    function template($path)
    {
        $this->path = $path;
    }

    /**
     * 模拟smarty的assign函数
     *
     * @access  public
     * @param   string       $name    变量的名字
     * @param   mix           $value   变量的值
     * @return  void
     */
    function assign($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * 模拟smarty的fetch函数
     *
     * @access  public
     * @param   string       $file   文件相对路径
     * @return  string      模板的内容(文本格式)
     */
    function fetch($file)
    {
        extract($this->vars);
        ob_start();
        include($this->path . $file);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * 模拟smarty的display函数
     *
     * @access  public
     * @param   string       $file   文件相对路径
     * @return  void
     */
    function display($file)
    {
        echo $this->fetch($file);
    }
}

?>