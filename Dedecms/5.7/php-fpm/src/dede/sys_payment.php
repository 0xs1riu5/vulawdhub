<?php
/**
 * 支付接口
 *
 * @version        $Id: sys_info_mark.php 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC.'/datalistcp.class.php');
CheckPurview('sys_Data');

$dopost = (empty($dopost))? '' : $dopost;
$pid = (empty($pid))? 0 : preg_replace('/[^0-9]/','',$pid);
/*
下面数数组格式的例子:
*/
//一个简单的[数组<->表单]解析类
/*数组结构应该为:
  array(
    [name]=>array(
        [title]=>'当前表单项的名称',
        [type]=>'text|select',
        [description]=>'表单内容的介绍说明'
        [iterm]=>'1:使用标准双接口,使用担保交易接口', //如果含有":",则前面为value值,后面为显示内容
        [value]=>'使用担保交易接口',
    ),
    ...
  )
  使用方法:
  将上述的格式传入到数组中去,然后进行解析:
  1.声明类,并创建数组
  $af = new Array2form($config);
  
  2.设置一个表单模板(可选,如果不设置载入默认)
  $af->SetDefaultTpl($templets); $templets:为一个底册模板文件
  表单模板格式为:
  <p>~title~:~form~<small>~description~</small></p>
  
  3.获取特定项目表单
  $af->GetIterm('alipay', 1) //1.表示获取一个默认模板下的完整表单,2.仅获取一个表单项
  
  4.获取所有表单内容
  $af->GetAll() //获取表单所有解析后的内容
  
*/
class Array2form
{
    var $FormArray = array();
    var $ArrFromTPL = '';
    
    function __construct($formarray = array())
    {
        if(count($formarray) > 1)
        {
            $this->FormArray = $formarray;
          //var_dump($this->FormArray);
            $this->SetDefaultTpl();
        }
    }
    
    //析构函数,兼容PHP4
    /*
    function Array2form($formarray = array())
    {
        $this->__construct($formarray);
    }
    */
    
    //获取一个特定项目的表单
    function GetIterm($itermid = '', $itermtype = 1)
    {
        $reval = $reval_form =  $reval_title = $reval_des = $myformItem = '';
        if(is_array($this->FormArray))
        {
            foreach ($this->FormArray as $key => $val) {
                if($key == $itermid)
                {
                    $reval_title = $val['title'];
                    $reval_des = $val['description'];
                    $reval_form = $this->GetForm($key,$val, $val['type']);
                    //进行模板标签替换
                    if($itermtype == 1)
                        $reval = preg_replace(array("/~title~/","/~form~/","/~description~/"), 
                                                                array($reval_title, $reval_form, $reval_des), $this->ArrFromTPL);
                    else return $reval_form;    
                }
            }
        } else {
            return FALSE;
        }
        return empty($reval)? '' : $reval;    
    }
    
    function GetForm($key, $formarry = array(), $formtype='text')
    {
        switch ($formtype) 
        {
            case 'text':
                //生成文本编辑框
                $valstr=(empty($formarry['value']))? "value=''" : "value='{$formarry['value']}'";
                $reval_form = "<input type='text' name='{$key}' id='{$key}' style='width:300px' class='text'{$valstr}>";
            break;
            case 'select':
                //生成选择框
                $reval_title = $formarry['title'];
                $items = explode(',',$formarry['iterm']);
                $reval_form = "<select name='{$key}' class='text'>";
                if(is_array($items))
                {
                    foreach($items as $v)
                    {
                        $v = trim($v);
                        if($v=='') continue;
                        //统一将中文冒号转为英文
                        $v = str_replace("：", ":", $v);
                        if( preg_match("/[\:]/",$v) )
                        { 
                            list($value, $name) = preg_split('#:#', $v);
                            $reval_form .= ($formarry['value'] == $value)? "<option value='$value' selected>$name</option>\r\n" : "<option value='$value'>$name</option>\r\n";
                        } else {
                            $reval_form .= ($formarry['value'] == $v)? "<option value='$v' selected>$v</option>\r\n" : "<option value='$v'>$v</option>\r\n";
                        }
                    }
                }
                $reval_form .= "</select>\r\n";
            break;
        }
        return $reval_form;
    }
    
    
    //获取所有的表单内容
    function GetAll()
    {
        $reval=empty($reval)? '' : $reval;    
        if(is_array($this->FormArray))
        {
            foreach ($this->FormArray as $key => $val)
            {
                $reval .= $this->GetIterm($key);
            }
            return $reval;
        }else{
            return FALSE;    
        }
    }
    
    //获取一个特定项目的表单
    function SetDefaultTpl($tplname = '')
    {
        if(empty($tplname)) 
        {
            $this->ArrFromTPL = '<p>~title~：~form~<small>~description~</small></p>';
        } else {
            if(file_exists($tplname)) $this->ArrFromTPL = file_get_contents($tplname);
          else $this->ArrFromTPL = $tplname;
        }
    }
}
$tplstring = "
            <tr>
              <td height='25' align='center'>~title~：</td>
              <td>~form~ <small>~description~</small></td>
            </tr>
";

//安装支付接口
if($dopost=='install')
{
    $row = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$pid'");
    if(is_array($row))
    {
        if($cfg_soft_lang == 'utf-8')
        {
            $config_row = AutoCharset(unserialize(utf82gb($row['config'])));
        }else if($cfg_soft_lang == 'gb2312'){
            $config_row = unserialize($row['config']);
        }
        //print_r($config_row);exit;
        $af = new Array2form($config_row);
        $af->SetDefaultTpl($tplstring);
        $reval = $af->GetAll();
    }
    include DedeInclude('templets/sys_payment_install.htm');
    exit;
} 
//配置支付接口
else if($dopost=='config')
{
    if($pay_name=="" || $pay_desc=="" || $pay_fee=="")
    {
        ShowMsg("您有未填写的项目！","-1");
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$pid'");
    if($cfg_soft_lang == 'utf-8')
    {
        $config = AutoCharset(unserialize(utf82gb($row['config'])));
    }else if($cfg_soft_lang == 'gb2312'){
        $config = unserialize($row['config']);
    }
    $payments = "'code' => '".$row['code']."',";
    foreach ($config as $key => $v)
    {
        $config[$key]['value'] = ${$key};
        $payments .= "'".$key."' => '".$config[$key]['value']."',";
    }
    $payments = substr($payments, 0, -1);
    $payment = "\$payment=array(".$payments.")";
    $configstr = "<"."?php\r\n".$payment."\r\n?".">\r\n";
    if(!empty($payment))
    {
        $m_file = DEDEDATA."/payment/".$row['code'].".php";
        $fp = fopen($m_file,"w") or die("写入文件 $safeconfigfile 失败，请检查权限！");
        fwrite($fp,$configstr);
        fclose($fp);
    }
    if($cfg_soft_lang == 'utf-8')
    {
        $config = AutoCharset($config,'utf-8','gb2312');
        $config = serialize($config);
        $config = gb2utf8($config);
    }else{
        $config = serialize($config);
    }
    
    $query = "UPDATE `#@__payment` SET name = '$pay_name',fee='$pay_fee',description='$pay_desc',config='$config',enabled='1' WHERE id='$pid'";
    $dsql->ExecuteNoneQuery($query);
    if($pm=='edit') $msg="保存修改成功";
    else $msg="安装成功！";
    ShowMsg($msg, "sys_payment.php");
    exit();
}

//删除支付接口
else if($dopost=='uninstall')
{
    $row = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$pid'");
    if($cfg_soft_lang == 'utf-8')
    {
        $config = AutoCharset(unserialize(utf82gb($row['config'])));
    }else if($cfg_soft_lang == 'gb2312'){
        $config = unserialize($row['config']);
    }
    foreach ($config as $key => $v) $config[$key]['value']="";
    if($cfg_soft_lang == 'utf-8')
    {
        $config = AutoCharset($config,'utf-8','gb2312');
        $config = serialize($config);
        $config = gb2utf8($config);
    }else{
        $config = serialize($config);
    }
    $query = "UPDATE `#@__payment` SET fee='',config='$config',enabled='0' WHERE id='$pid'";
    $dsql->ExecuteNoneQuery($query);
    //同时需要删除对应的缓存
    $m_file = DEDEDATA."/payment/".$row['code'].".php";
    @unlink($m_file);
    ShowMsg("删除成功！", "sys_payment.php");
    exit();
}
$sql = "SELECT * FROM `#@__payment` ORDER BY rank ASC";
$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/sys_payment.htm");
$dlist->SetSource($sql);
$dlist->display();