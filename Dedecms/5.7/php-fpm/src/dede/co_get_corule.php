<?php
/**
 * 导入采集规则
 *
 * @version        $Id: co_get_corule.php 1 17:13 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
CheckPurview('co_AddNote');
if(empty($job))
{
    $job='';
}
if($job=='')
{
    require_once(DEDEINC."/../include/oxwindow.class.php");
    $wintitle = "导入采集规则";
    $wecome_info = "<a href='co_main.php'><u>采集点管理</u></a>::导入采集规则";
    $win = new OxWindow();
    $win->Init("co_get_corule.php","js/blank.js","POST");
    $win->AddHidden("job","yes");
    $win->AddTitle("请在下面输入你要导入的文本配置：(建议用base64编码[支持不编码的规则，但不兼容旧版规则])");
    $win->AddMsgItem("<textarea name='notes' style='width:100%;height:300px'></textarea>");
    $winform = $win->GetWindow("ok");
    $win->Display();
    exit();
}
else
{
    CheckPurview('co_AddNote');
    require_once(DEDEINC."/dedetag.class.php");
    $notes = trim($notes);

    //对Base64格式的规则进行解码
    if(ereg('^BASE64:',$notes))
    {
        if(!ereg(':END$',$notes))
        {
            ShowMsg('该规则不合法，Base64格式的采集规则为：BASE64:base64编码后的配置:END !','-1');
            exit();
        }
        $notess = explode(':',$notes);
        $notes = $notess[1];
        $notes = base64_decode(ereg_replace("[\r\n\t ]",'',$notes)) OR die('配置字符串有错误！');
    }
    else
    {
        $notes = stripslashes($notes);
    }
    $dtp = new DedeTagParse();
    $dtp->LoadString($notes);
    if(!is_array($dtp->CTags))
    {
        ShowMsg('该规则不合法，无法导入!','-1');
        exit();
    }
    $ctag1 = $dtp->GetTagByName('listconfig');
    $ctag2 = $dtp->GetTagByName('itemconfig');
    $listconfig = $ctag1->GetInnerText();
    $itemconfig = addslashes($ctag2->GetInnerText());
    $dtp->LoadString($listconfig);
    $listconfig = addslashes($listconfig);
    $noteinfo = $dtp->GetTagByName('noteinfo');
    if(!is_object($noteinfo))
    {
        ShowMsg("该规则不合法，无法导入!","-1");
        exit();
    }
    foreach($noteinfo->CAttribute->Items as $k=>$v)
    {
        $$k = addslashes($v);
    }
    $uptime = time();
    if(empty($freq))
    {
        $freq = 1;
    }
    if(empty($extypeid))
    {
        $extypeid = 0;
    }
    if(empty($islisten))
    {
        $islisten = 0;
    }
    $inquery = " INSERT INTO `#@__co_note`(`channelid`,`notename`,`sourcelang`,`uptime`,`cotime`,`pnum`,`isok`,`usemore`,`listconfig`,`itemconfig`)
               VALUES ('$channelid','$notename','$sourcelang','$uptime','0','0','0','$usemore','$listconfig','$itemconfig'); ";
    $rs = $dsql->ExecuteNoneQuery($inquery);
    if(!$rs)
    {
        ShowMsg("保存信息时出现错误！".$dsql->GetError(),"-1");
        exit();
    }
    ShowMsg("成功导入一个规则!","co_main.php");
    exit();
}

?>