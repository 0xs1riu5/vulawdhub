<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 提示窗口对话框类
 *
 * @version        $Id: oxwindow.class.php 2 13:53 2010-11-11 tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEINC."/dedetag.class.php");

/**
 * 提示窗口对话框类
 *
 * @package          OxWindow
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class OxWindow
{
    var $myWin = "";
    var $myWinItem = "";
    var $checkCode = "";
    var $formName = "";
    var $tmpCode = "//checkcode";
    var $hasStart = false;

    /**
     *  初始化为含表单的页面
     *
     * @param     string  $formaction  表单操作action
     * @param     string  $checkScript  检测验证js
     * @param     string  $formmethod  表单类型
     * @param     string  $formname  表单名称
     * @return    void
     */
    function Init($formaction="", $checkScript="js/blank.js", $formmethod="POST", $formname="myform")
    {
        $this->myWin .= "<script language='javascript'>\r\n";
        if($checkScript!="" && file_exists($checkScript))
        {
            $fp = fopen($checkScript,"r");
            $this->myWin .= fread($fp,filesize($checkScript));
            fclose($fp);
        }
        else
        {
            $this->myWin .= "<!-- function CheckSubmit()\r\n{ return true; } -->";
        }
        $this->myWin .= "</script>\r\n";
        $this->formName = $formname;
        $this->myWin .= "<form name='$formname' method='$formmethod' onSubmit='return CheckSubmit();' action='$formaction'>\r\n";
    }

    //
    /**
     *  增加隐藏域
     *
     * @param     string  $iname  隐藏域名称
     * @param     string  $ivalue  隐藏域值
     * @return    void
     */
    function AddHidden($iname,$ivalue)
    {
        $this->myWin .= "<input type='hidden' name='$iname' value='$ivalue'>\r\n";
    }

    /**
     *  开始创建窗口
     *
     * @return    void
     */
    function StartWin()
    {
        $this->myWin .= "<table width='100%'  border='0' cellpadding='3' cellspacing='1' bgcolor='#DADADA'>\r\n";
    }

    /**
     *  增加一个两列的行
     *
     * @access    public
     * @param     string  $iname  名称
     * @param     string  $ivalue  值
     * @return    string
     */
    function AddItem($iname, $ivalue)
    {
        $this->myWinItem .= "<tr bgcolor='#FFFFFF'>\r\n";
        $this->myWinItem .= "<td width='25%'>$iname</td>\r\n";
        $this->myWinItem .= "<td width='75%'>$ivalue</td>\r\n";
        $this->myWinItem .= "</tr>\r\n";
    }

    /**
     *  增加一个单列的消息行
     *
     * @access    public
     * @param     string  $ivalue  短消息值
     * @param     string  $height  消息框高度
     * @param     string  $col  显示列数
     * @return    void
     */
    function AddMsgItem($ivalue, $height="100", $col="2")
    {
        if($height!=""&&$height!="0")
        {
            $height = " height='$height'";
        }
        else
        {
            $height="";
        }
        if($col!=""&&$col!=0)
        {
            $colspan="colspan='$col'";
        }
        else
        {
            $colspan="";
        }
        $this->myWinItem .= "<tr bgcolor='#FFFFFF'>\r\n";
        $this->myWinItem .= "<td $colspan $height> $ivalue </td>\r\n";
        $this->myWinItem .= "</tr>\r\n";
    }

    /**
     *  增加单列的标题行
     *
     * @access    public
     * @param     string  $title  标题
     * @param     string  $col  列
     * @return    string
     */
    function AddTitle($title, $col="2")
    {
        global $cfg_plus_dir;
        if($col!=""&&$col!="0")
        {
            $colspan="colspan='$col'";
        }
        else
        {
            $colspan="";
        }
        $this->myWinItem .= "<tr bgcolor='#DADADA'>\r\n";
        $this->myWinItem .= "<td $colspan background='{$cfg_plus_dir}/img/wbg.gif' height='26'><font color='#666600'><b>$title</b></font></td>\r\n";
        $this->myWinItem .= "</tr>\r\n";
    }

    /**
     *  结束Window
     *
     * @param     bool   $isform
     * @return    void
     */
    function CloseWin($isform=true)
    {
        if(!$isform)
        {
            $this->myWin .= "</table>\r\n";
        }
        else
        {
            $this->myWin .= "</table></form>\r\n";
        }
    }

    /**
     *  增加自定义JS脚本
     *
     * @param     string  $scripts
     * @return    void
     */
    function SetCheckScript($scripts)
    {
        $pos = strpos($this->myWin,$this->tmpCode);
        if($pos > 0)
        {
            $this->myWin = substr_replace($this->myWin,$scripts,$pos,strlen($this->tmpCode));
        }
    }

    /**
     *  获取窗口
     *
     * @param     string  $wintype  菜单类型
     * @param     string  $msg  短消息
     * @param     bool  $isform  是否是表单
     * @return    string
     */
    function GetWindow($wintype="save", $msg="", $isform=true)
    {
        global $cfg_plus_dir;
        $this->StartWin();
        $this->myWin .= $this->myWinItem;
        if($wintype!="")
        {
            if($wintype!="hand")
            {
                $this->myWin .= "
<tr>
<td colspan='2' bgcolor='#F9FCEF'>
<table width='270' border='0' cellpadding='0' cellspacing='0'>
<tr align='center' height='28'>
<td width='90'><input name='imageField1' type='image' class='np' src='{$cfg_plus_dir}/img/button_".$wintype.".gif' width='60' height='22' border='0' /></td>
<td width='90'><a href='#'><img class='np' src='{$cfg_plus_dir}/img/button_reset.gif' width='60' height='22' border='0' onClick='this.form.reset();return false;' /></a></td>
<td><a href='#'><img src='{$cfg_plus_dir}/img/button_back.gif' width='60' height='22' border='0' onClick='history.go(-1);' /></a></td>
</tr>
</table>
</td>
</tr>";
            }
            else
            {
                if($msg!='')
                {
                    $this->myWin .= "<tr><td bgcolor='#F5F5F5'>$msg</td></tr>";
                }
                else
                {
                    $this->myWin .= '';
                }
            }
        }
        $this->CloseWin($isform);
        return $this->myWin;
    }

    /**
     *  显示页面
     *
     * @access    public
     * @param     string  $modfile  模型模板
     * @return    string
     */
    function Display($modfile="")
    {
        global $cfg_templets_dir,$wecome_info,$cfg_basedir;
        if(empty($wecome_info))
        {
            $wecome_info = "DedeCMS OX 通用对话框：";
        }
        $ctp = new DedeTagParse();
        if($modfile=='')
        {
            $ctp->LoadTemplate($cfg_basedir.$cfg_templets_dir.'/plus/win_templet.htm');
        }
        else
        {
            $ctp->LoadTemplate($modfile);
        }
        $emnum = $ctp->Count;
        for($i=0;$i<=$emnum;$i++)
        {
            if(isset($GLOBALS[$ctp->CTags[$i]->GetTagName()]))
            {
                $ctp->Assign($i,$GLOBALS[$ctp->CTags[$i]->GetTagName()]);
            }
        }
        $ctp->Display();
        $ctp->Clear();
    }
} //End Class

/**
 *  显示一个不带表单的普通提示
 *
 * @access    public
 * @param     string   $msg  消息提示信息
 * @param     string   $title  提示标题
 * @return    string
 */
function ShowMsgWin($msg, $title)
{
    $win = new OxWindow();
    $win->Init();
    $win->mainTitle = "DeDeCMS系统提示：";
    $win->AddTitle($title);
    $win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
    $winform = $win->GetWindow("hand");
    $win->Display();
}