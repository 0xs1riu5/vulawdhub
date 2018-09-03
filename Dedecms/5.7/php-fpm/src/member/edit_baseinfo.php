<?php
/**
 * @version        $Id: edit_baseinfo.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if(!isset($dopost)) $dopost = '';

$pwd2=(empty($pwd2))? "" : $pwd2;
$row=$dsql->GetOne("SELECT  * FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
$face = $row['face'];
if($dopost=='save')
{
    $svali = GetCkVdValue();

    if(strtolower($vdcode) != $svali || $svali=='')
    {
        ReSETVdValue();
        ShowMsg('验证码错误！','-1');
        exit();
    }
    if(!is_array($row) || $row['pwd'] != md5($oldpwd))
    {
        ShowMsg('你输入的旧密码错误或没填写，不允许修改资料！','-1');
        exit();
    }
    if($userpwd != $userpwdok)
    {
        ShowMsg('你两次输入的新密码不一致！','-1');
        exit();
    }
    if($userpwd=='')
    {
        $pwd = $row['pwd'];
    }
    else
    {
        $pwd = md5($userpwd);
        $pwd2 = substr(md5($userpwd),5,20);
    }
    $addupquery = '';
    
    #api{{
    if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
    {
        $emailnew = $email != $row['email'] ? $email : '';
        $ucresult = uc_user_edit($cfg_ml->M_LoginID, $oldpwd, $userpwd, $emailnew);        
    }
    #/aip}}
    
    //修改安全问题或Email
    if($email != $row['email'] || ($newsafequestion != 0 && $newsafeanswer != ''))
    {
        if($row['safequestion']!=0 && ($row['safequestion'] != $safequestion || $row['safeanswer'] != $safeanswer))
        {
            ShowMsg('你的旧安全问题及答案不正确，不能修改Email或安全问题！','-1');
            exit();
        }

        //修改Email
        if($email != $row['email'])
        {
            if(!CheckEmail($email))
            {
                ShowMsg('Email格式不正确！','-1');
                exit();
            }
            else
            {
                $addupquery .= ",email='$email'";
            }
        }

        //修改安全问题
        if($newsafequestion != 0 && $newsafeanswer != '')
        {
            if(strlen($newsafeanswer) > 30)
            {
                ShowMsg('你的新安全问题的答案太长了，请保持在30字节以内！','-1');
                exit();
            }
            else
            {
                $addupquery .= ",safequestion='$newsafequestion',safeanswer='$newsafeanswer'";
            }
        }
    }

    //修改uname
    if($uname != $row['uname'])
    {
        $rs = CheckUserID($uname,'昵称或公司名称',FALSE);
        if($rs!='ok')
        {
            ShowMsg($rs,'-1');
            exit();
        }
        $addupquery .= ",uname='$uname'";
    }
    
    //性别
    if( !in_array($sex, array('男','女','保密')) )
    {
        ShowMsg('请选择正常的性别！','-1');
        exit();    
    }
    
    $query1 = "UPDATE `#@__member` SET pwd='$pwd',sex='$sex'{$addupquery} where mid='".$cfg_ml->M_ID."' ";
    $dsql->ExecuteNoneQuery($query1);

    //如果是管理员，修改其后台密码
    if($cfg_ml->fields['matt']==10 && $pwd2!="")
    {
        $query2 = "UPDATE `#@__admin` SET pwd='$pwd2' where id='".$cfg_ml->M_ID."' ";
        $dsql->ExecuteNoneQuery($query2);
    }
    // 清除会员缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
    ShowMsg('成功更新你的基本资料！','edit_baseinfo.php',0,5000);
    exit();
}
include(DEDEMEMBER."/templets/edit_baseinfo.htm");