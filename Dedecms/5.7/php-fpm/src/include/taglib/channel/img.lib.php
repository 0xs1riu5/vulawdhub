<?php
if(!defined('DEDEINC'))
{
    exit("Request Error!");
}
/**
 * 图像标签
 *
 * @version        $Id:img.lib.php 1 9:33 2010年7月8日Z tianya $
 * @package        DedeCMS.Taglib
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */

function ch_img($fvalue,&$arcTag,&$refObj,$fname='')
{
    global $cfg_album_width,$cfg_album_row,$cfg_album_col,$cfg_album_pagesize,$cfg_album_style,$cfg_album_ddwidth,$cfg_basehost,$cfg_multi_site;
    $dtp = new DedeTagParse();
    $dtp->LoadSource($fvalue);
    if(!is_array($dtp->CTags))
    {
        $dtp->Clear();
        return "无图片信息！";
    }
    $pagestyle = $cfg_album_style;
    $maxwidth = $cfg_album_width;
    $ddmaxwidth = $cfg_album_ddwidth;
    $pagepicnum = $cfg_album_pagesize;
    $row = $cfg_album_row;
    $icol = $cfg_album_col;
    $ptag = $dtp->GetTag('pagestyle');
    if(is_object($ptag))
    {
        $pagestyle = $ptag->GetAtt('value');
        $maxwidth = $ptag->GetAtt('maxwidth');
        $ddmaxwidth = $ptag->GetAtt('ddmaxwidth');
        $pagepicnum = $ptag->GetAtt('pagepicnum');
        $irow = $ptag->GetAtt('row');
        $icol = $ptag->GetAtt('col');
        if(empty($maxwidth))
        {
            $maxwidth = $cfg_album_width;
        }
    }

    //遍历图片信息
    $mrow = 0;
    $mcol = 0;
    $images = array();
    $innerTmp = $arcTag->GetInnerText();
    if(trim($innerTmp)=='')
    {
        $innerTmp = GetSysTemplets("channel_article_image.htm");
    }

    if($pagestyle==1)
    {
        $pagesize = $pagepicnum;
    }
    else if($pagestyle==2)
    {
        $pagesize = 1;
    }
    else
    {
        $pagesize = $irow * $icol;
    }

    if(is_object($arcTag) && $arcTag->GetAtt('pagesize') > 0)
    {
        $pagesize = $arcTag->GetAtt('pagesize');
    }
    if(empty($pagesize))
    {
        $pagesize = 12;
    }
    $aid = $refObj->ArcID;
    $row = $refObj->dsql->GetOne("SELECT title FROM `#@__archives` WHERE `id` = '$aid';");
    $title = $row['title'];
    $revalue = '';
    $GLOBAL['photoid'] = 0;
    foreach($dtp->CTags as $ctag)
    {
        if($ctag->GetName()=="img")
        {
            $fields = $ctag->CAttribute->Items;
            $fields['text'] = str_replace("'","",$ctag->GetAtt('text'));
            $fields['title'] = $title;
            $fields['imgsrc'] = trim($ctag->GetInnerText());
            $fields['imgsrctrue'] = $fields['imgsrc'];
            if(empty($fields['ddimg']))
            {
                $fields['ddimg'] = $fields['imgsrc'];
            }
            if($cfg_multi_site=='Y')
            {
                //$cfg_basehost)
                if( !preg_match('#^http:#i', $fields['imgsrc']) ) {
                    $fields['imgsrc'] = $cfg_basehost.$fields['imgsrc'];
                }
                if( !preg_match('#^http:#i', $fields['ddimg']) ) {
                    $fields['ddimg'] = $cfg_basehost.$fields['ddimg'];
                }
            }
            if(empty($fields['width']))
            {
                $fields['width'] = $maxwidth;
            }
            //if($fields['text']=='')
            //{
                //$fields['text'] = '图片'.($GLOBAL['photoid']+1);
            //}
            $fields['alttext'] = str_replace("'",'',$fields['text']);
            $fields['pagestyle'] = $pagestyle;
            $dtp2 = new DedeTagParse();
            $dtp2->SetNameSpace("field","[","]");
            $dtp2->LoadSource($innerTmp);
            if($GLOBAL['photoid']>0 && ($GLOBAL['photoid'] % $pagesize)==0)
            {
                $revalue .= "#p#分页标题#e#";
            }
            if($pagestyle==1)
            {
                $fields['imgwidth'] = '';
                $fields['linkurl'] = $fields['imgsrc'];
                $fields['textlink'] = "<br /><a href='{$fields['linkurl']}' target='_blank'>{$fields['text']}</a>";
            }
            else if($pagestyle==2)
            {
                if($fields['width'] > $maxwidth) {
                    $fields['imgwidth'] = " width='$maxwidth' ";
                }
                else {
                    $fields['imgwidth'] = " width='{$fields['width']}' ";
                }
                $fields['linkurl'] = $fields['imgsrc'];
                if($fields['text']!='') {
                    $fields['textlink'] = "<br /><a href='{$fields['linkurl']}' target='_blank'>{$fields['text']}</a>\r\n";
                }
                else {
                    $fields['textlink'] = '';
                }
            }
            else if($pagestyle==3)
            {
                $fields['text'] = $fields['textlink'] = '';
                $fields['imgsrc'] = $fields['ddimg'];
                $fields['imgwidth'] = " width='$ddmaxwidth' ";
                $fields['linkurl'] = "{$GLOBALS['cfg_phpurl']}/showphoto.php?aid={$refObj->ArcID}&src=".urlencode($fields['imgsrctrue'])."&npos={$GLOBAL['photoid']}";
            }
            if(is_array($dtp2->CTags))
            {
                foreach($dtp2->CTags as $tagid=>$ctag)
                {
                    if(isset($fields[$ctag->GetName()]))
                    {
                        $dtp2->Assign($tagid,$fields[$ctag->GetName()]);
                    }
                }
                $revalue .= $dtp2->GetResult();
            }
            $GLOBAL['photoid']++;
        }
    }
    return $revalue;
}