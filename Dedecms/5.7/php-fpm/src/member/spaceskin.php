<?php
/**
 * 空间皮肤
 * 
 * @version        $Id: spaceskin.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if($cfg_mb_lit=='Y')
{
    ShowMsg("由于系统开启了精简版会员空间，你访问的功能不可用！","-1");
    exit();
}
if(empty($dopost)) $dopost = '';


if($dopost=="use")
{
    AjaxHead();
    $t = preg_replace("#[^a-z0-9-]#i", "", $t);
    $dsql->ExecuteNoneQuery("UPDATE `#@__member_space` SET spacestyle='$t' WHERE mid='".$cfg_ml->M_ID."';");
    ShowMsg('成功更新空间样式！', 'spaceskin.php');
}
//默认界面
else
{
    $userrow = $dsql->GetOne("SELECT spacestyle FROM `#@__member_space` WHERE mid='".$cfg_ml->M_ID."' ");
    require_once(dirname(__FILE__)."/templets/spaceskin.htm");
    exit();
}

/**
 *  检查样式是否使用
 *
 * @access    public
 * @param     string  $type  样式类型
 * @return    string
 */
function checkuse($type)
{
    global $cfg_ml, $userrow;
    if($userrow['spacestyle'] == $type)
    {
        return '<a href="#"><font color=red>使用中...</font></a>';
    }
    else
    {
        return '<a href="spaceskin.php?t='.$type.'&dopost=use" title="使用此风格">使用</a>';
    }
}

/**
 *  提取预览小图
 *
 * @access    public
 * @param     string  $dir  目录
 * @param     string  $dirname  目录名称
 * @return    string
 */
function showdemopic($dir, $dirname)
{
    if (file_exists("$dir/$dirname/demo.png")) {
        $demopic = "$dir/$dirname/demo.png";
    } else if (file_exists("$dir/$dirname/demo.jpg")) {
        $demopic = "$dir/$dirname/demo.jpg";
    } else if (file_exists("$dir/$dirname/demo.jpeg")) {
        $demopic = "$dir/$dirname/demo.jpeg";
    } else if (file_exists("$dir/$dirname/demo.gif")) {
        $demopic = "$dir/$dirname/demo.gif";
    }
    return $demopic;
}

/**
 *  列出风格目录
 *
 * @access    public
 * @param     string
 * @return    string
 */
function ListSkin()
{
    global $cfg_ml;
    $dir = 'space';
    $allskins = array();
    //读取文件夹
    if(file_exists($dir.'/skinlist.inc'))
    {
        $ds = file($dir.'/skinlist.inc');
        foreach($ds as $d)
        {
            $d = trim($d);
            if(empty($d) || substr($d, 0, 2)=='//') continue;
            if(!is_dir($dir.'/'.$d)) continue;
            $dirs[] = $d;
        }
    }
    else
    {
        $fp = opendir($dir);
        while ($sysname = readdir($fp))
        {
            $dirs[] = $sysname;
        }
        closedir($dh);
    }
    //获得模板摘要信息
    foreach($dirs as $sysname)
    {
            if ($sysname=='.' || $sysname=='..' || $sysname=='CVS'
             || !file_exists("$dir/$sysname/info.txt"))
            {
                continue;
            }
            $demopic = showdemopic($dir, $sysname);
            $date = MyDate('Y-m-d', filemtime("$dir/$sysname"));
            $listdb = array(
                'sign' => $sysname,
                'demo' => $demopic,
                'name' => '',
                'author' => 'Unkown',
                'date' => ''
            );
            $infodatas = file("$dir/$sysname/info.txt");
            foreach($infodatas as $d)
            {
                $d = trim($d);
                if(empty($d)) continue;
                $ds = explode(':', $d);
                $listdb[trim($ds[0])] = trim($ds[1]);
            }
            if($listdb['type'] != 'default' && $listdb['type'] != $cfg_ml->M_MbType)
            {
                continue;
            }
            $allskins[] = $listdb;
    }
    //输出模板列表
    $num = 0;
    print '<tr class="head" height="25"><td colspan="2">&nbsp; &nbsp;<b></b></td></tr>';
    foreach ($allskins as $value)
    {
        if($num==0) { print '<tr height="20">'; }
        $num++;
        print '<td class="b"><img src="'.$value['demo'].'" width="150" height="150" border="0" /><br />';
        print '风格名称：'.$value['name']."({$value['sign']})".'<br />';
        print '风格作者：'.$value['author'].'<br />';
        //print '建立时间：'.$value['date'].'<br />';
        print '操作：'.checkuse($value['sign']).'';
        if($num==4)
        {
            $num=0;
            print '</tr>';
        }
    }
    if($num != 0)
    {
        for($i=$num; $num < 4; $num++)
        {
            print' <td class="b">&nbsp;</td>';
        }
        print '</tr>';
    }
    print '</td>';
}