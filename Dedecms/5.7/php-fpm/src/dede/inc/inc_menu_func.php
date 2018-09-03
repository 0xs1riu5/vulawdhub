<?php
/**
 * 管理菜单函数
 *
 * @version        $Id: inc_menu_func.php 1 10:32 2010年7月21日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../config.php");
require_once(DEDEINC."/dedetag.class.php");

$headTemplet = "<dl class='bitem' id='sunitems~cc~'><dt onClick='showHide(\"items~cc~\")'><b>~channelname~</b></dt>
<dd style='display:~display~' class='sitem' id='items~cc~'>
<ul class='sitemu'>\r\n";

$footTemplet = "</ul>\r\n</dd>\r\n</dl>\r\n";

$itemTemplet = "<li>~link~</li>\r\n";

function GetMenus($userrank,$topos='main')
{
    global $openitem,$headTemplet,$footTemplet,$itemTemplet;
    if($topos=='main')
    {
        $openitem = (empty($openitem) ? 1 : $openitem);
        $menus = $GLOBALS['menusMain'];
    }
    else if($topos=='module')
    {
        $openitem = 100;
        $menus = $GLOBALS['menusMoudle'];
    }
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace('m','<','>');
    $dtp->LoadSource($menus);
    $dtp2 = new DedeTagParse();
    $dtp2->SetNameSpace('m','<','>');
    $m = 0;
    foreach($dtp->CTags as $i=>$ctag)
    {
        if($ctag->GetName()=='top' && ($ctag->GetAtt('rank')=='' || TestPurview($ctag->GetAtt('rank')) ))
        {
            if($openitem!=999 && !preg_match("#".$openitem.'_'."#", $ctag->GetAtt('item')) && $openitem!=100) continue;
            $m++;
            echo "<!-- Item ".($m+1)." Strat -->\r\n";
            $htmp = str_replace("~channelname~",$ctag->GetAtt("name"),$headTemplet);
            if(empty($openitem) || $openitem==100)
            {
                if($ctag->GetAtt('notshowall')=='1') continue;
                $htmp = str_replace('~display~',$ctag->GetAtt('display'),$htmp);
            }
            else
            {
                if($openitem==$ctag->GetAtt('item') || preg_match("#".$openitem.'_'."#", $ctag->GetAtt('item')) || $openitem=='-1')
                    $htmp = str_replace('~display~','block',$htmp);
                else
                    $htmp = str_replace('~display~','none',$htmp);
            }
            $htmp = str_replace('~cc~',$m.'_'.$openitem,$htmp);
            echo $htmp;
            $dtp2->LoadSource($ctag->InnerText);
            foreach($dtp2->CTags as $j=>$ctag2)
            {
                $ischannel = trim($ctag2->GetAtt('ischannel'));
                if($ctag2->GetName()=='item' && ($ctag2->GetAtt('rank')=='' || TestPurview($ctag2->GetAtt('rank')) ) )
                {
                    $link = "<a href='".$ctag2->GetAtt('link')."' target='".$ctag2->GetAtt('target')."'>".$ctag2->GetAtt('name')."</a>";
                    if($ischannel=='1')
                    {
                        if($ctag2->GetAtt('addalt')!='') {
                            $addalt = $ctag2->GetAtt('addalt');
                        }
                        else {
                            $addalt = '录入新内容';
                        }
                        
                        if($ctag2->GetAtt('addico')!='') {
                            $addico = $ctag2->GetAtt('addico');
                        }
                        else {
                            $addico = 'images/gtk-sadd.png';
                        }
                        
//an add icos , small items use att ischannel='1' addico='ico' addalt='msg' linkadd=''
$link = "        <div class='items'>
            <div class='fllct'>$link</div>\r\n
            <div class='flrct'>
                <a href='".$ctag2->GetAtt('linkadd')."' target='".$ctag2->GetAtt('target')."'><img src='$addico' alt='$addalt' title='$addalt'/></a>
            </div>
        </div>\r\n";
        
                    }
                    else
                    {
                        $link .= "\r\n";
                    }
                    $itemtmp = str_replace('~link~',$link,$itemTemplet);
                    echo $itemtmp;
                }
            }
            echo $footTemplet;
            echo "<!-- Item ".($m+1)." End -->\r\n";
        }
    }
}
//End Function