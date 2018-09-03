<?php
/**
 * 会员短消息,发送到一个
 *
 * @version        $Id: member_pmone.php 1 11:24 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Pm');
//检查用户名的合法性
function CheckUserID($uid,$msgtitle='用户名',$ckhas=true)
{
    global $cfg_mb_notallow,$cfg_mb_idmin,$cfg_md_idurl,$cfg_soft_lang,$dsql;
    if($cfg_mb_notallow != '')
    {
        $nas = explode(',', $cfg_mb_notallow);
        if(in_array($uid, $nas))
        {
            return $msgtitle.'为系统禁止的标识！';
        }
    }
    if($cfg_md_idurl=='Y' && preg_match("#[^a-z0-9]#i", $uid))
    {
        return $msgtitle.'必须由英文字母或数字组成！';
    }

    if($cfg_soft_lang=='utf-8') $ck_uid = utf82gb($uid);
    else $ck_uid = $uid;
    
    for($i=0;isset($ck_uid[$i]);$i++)
    {
        if(ord($ck_uid[$i]) > 0x80)
        {
            if(isset($ck_uid[$i+1]) && ord($ck_uid[$i+1])>0x40)
            {
                $i++;
            }
            else
            {
                return $msgtitle.'可能含有乱码，建议你改用英文字母和数字组合！';
            }
        }
        else
        {
            if(preg_match("#[^0-9a-z@\.-]i#", $ck_uid[$i]))
            {
                return $msgtitle.'不能含有 [@]、[.]、[-]以外的特殊符号！';
            }
        }
    }
    if($ckhas)
    {
        $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid LIKE '$uid' ");
        if(is_array($row)) return $msgtitle."已经存在！";
    }
    return 'ok';
}

if(!isset($action)) $action = '';
if($action=="post")
{
    $floginid = $cuserLogin->getUserName();
    $fromid = $cuserLogin->getUserID();
    if($subject=='')
    {
        ShowMsg("请填写信息标题!","-1");
        exit();
    }
    $msg = CheckUserID($msgtoid,"用户名",false);
    if($msg!='ok')
    {
        ShowMsg($msg,"-1");
        exit();
    }
    $row = $dsql->GetOne("Select * From `#@__member` where userid like '$msgtoid' ");
    if(!is_array($row))
    {
        ShowMsg("你指定的用户不存在,不能发送信息!","-1");
        exit();
    }
    $subject = cn_substrR(HtmlReplace($subject,1),60);
    $message = cn_substrR(HtmlReplace($message,0),1024);
    $sendtime = $writetime = time();

    //发给收件人(收件人可管理)
    $inquery = "INSERT INTO `#@__member_pms` (`floginid`,`fromid`,`toid`,`tologinid`,`folder`,`subject`,`sendtime`,`writetime`,`hasview`,`isadmin`,`message`)
      VALUES ('$floginid','$fromid','{$row['mid']}','{$row['userid']}','inbox','$subject','$sendtime','$writetime','0','0','$message'); ";

    $dsql->ExecuteNoneQuery($inquery);
    ShowMsg('短信已成功发送','member_pmone.php');
    exit();
}
require_once(DEDEADMIN."/templets/member_pmone.htm");