<?php
/**
 * 自定义模型管理
 *
 * @version        $Id: mychannel_edit.php 1 14:49 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_Edit');
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/oxwindow.class.php");

if(empty($dopost)) $dopost="";
$id = isset($id) && is_numeric($id) ? $id : 0;

/*----------------
function __ShowHide()
-----------------*/
if($dopost=="show")
{
    $dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET isshow=1 WHERE id='$id' ");
    ShowMsg("操作成功！", "mychannel_main.php");
    exit();
}
else if($dopost=="hide")
{
    $dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET isshow=0 WHERE id='$id'");
    ShowMsg("操作成功！", "mychannel_main.php");
    exit();
}
/*----------------
function __CopyStart()
-----------------*/
else if($dopost=="copystart")
{
    if($id==-1)
    {
        ShowMsg("专题模型不支持复制！","-1");
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$id'");
    if($row['id'] > -1)
    {
        $nrow = $dsql->GetOne("SELECT MAX(id) AS id FROM `#@__channeltype` LIMIT 0,1 ");
        $newid = $nrow['id'] + 1;
        if($newid < 10)
        {
            $newid = $newid + 10;
        }
        $idname = $newid;
    } else {
        $nrow = $dsql->GetOne("SELECT MIN(id) AS id FROM `#@__channeltype` LIMIT 0,1 ");
        $newid = $nrow['id'] - 1;
        if($newid < -10)
        {
            $newid = $newid - 10;
        }
        $idname = 'w'.($newid * -1);
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$id'");
    $wintitle = "频道管理-模型复制";
    $wecome_info = "&nbsp;<a href='mychannel_main.php'>频道管理</a> - 模型复制";
    $win = new OxWindow();
    $win->Init("mychannel_edit.php", "js/blank.js", "post");
    $win->AddTitle("&nbsp;被复制频道： [<font color='red'>".$row['typename']."</font>]");
    $win->AddHidden("cid", $id);
    $win->AddHidden("id", $id);
    $win->AddHidden("dopost", 'copysave');
    $msg = "
        <table width='460' border='0' cellspacing='0' cellpadding='0'>
        <tr>
        <td width='170' height='24' align='center'>新频道id：</td>
        <td width='230'><input name='newid' type='text' id='newid' size='6' value='{$newid}' /></td>
        </tr>
        <tr>
        <td height='24' align='center'>新频道名称：</td>
        <td><input name='newtypename' type='text' id='newtypename' value='{$row['typename']}{$idname}' style='width:250px' /></td>
        </tr>
        <tr>
        <td height='24' align='center'>新频道标识：</td>
        <td><input name='newnid' type='text' id='newnid' value='{$row['nid']}{$idname}' style='width:250px' /></td>
        </tr>
        <tr>
        <td height='24' align='center'>新附加表：</td>
        <td><input name='newaddtable' type='text' id='newaddtable' value='{$row['addtable']}{$idname}' style='width:250px' /></td>
        </tr>
        <tr>
        <td height='24' align='center'>复制模板：</td>
        <td>
        <input name='copytemplet' type='radio' id='copytemplet' value='1' class='np' checked='checked' /> 复制
        &nbsp;
        <input name='copytemplet' type='radio' id='copytemplet' class='np' value='0' /> 不复制
        </td>
        </tr>
        </table>
        ";
    $win->AddMsgItem("<div style='padding:20px;line-height:300%'>$msg</div>");
    $winform = $win->GetWindow("ok", "");
    $win->Display();
    exit();
}
/*----------------
function __Export()
-----------------*/
else if($dopost=="export")
{
    if($id==-1)
    {
        ShowMsg("专题模型不支持导出！","-1");
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$id' ");
    $channelconfig = '';
    $row['maintable'] = preg_replace('#dede_#', '#@__', $row['maintable']);
    $row['addtable'] = preg_replace('#dede_#', '#@__', $row['addtable']);
    foreach($row as $k=>$v)
    {
        if($k=='fieldset') $v = "\r\n$v\r\n";
        $channelconfig .= "<channel:{$k}>$v</channel:{$k}>\r\n";
    }
    $wintitle = "导出内容模型规则";
    $wecome_info = "<a href='mychannel_main.php'><u>内容模型管理</u></a>::导出内容模型规则";
    $win = new OxWindow();
    $win->Init();
    $win->AddTitle("以下为规则 [{$row['typename']}] 的模型规则，你可以共享给你的朋友：");
    $winform = $win->GetWindow("hand","<textarea name='config' style='width:99%;height:450px;word-wrap: break-word;word-break:break-all;'>".$channelconfig."</textarea>");
    $win->Display();
    exit();
}
/*----------------
function __ExportIn()
-----------------*/
else if($dopost=="exportin")
{
    $wintitle = "导入内容模型规则";
    $wecome_info = "<a href='mychannel_main.php'>内容模型管理</a>::导入内容模型规则";
    $win = new OxWindow();
    $win->Init("mychannel_edit.php", "js/blank.js", "post");
    $win->AddHidden("dopost", "exportinok");
    $win->AddTitle("输入规则内容：(导入模型会和原有模型冲突，不过可以在导入后修改)");
    $win->AddMsgItem("<textarea name='exconfig' style='width:99%;height:450px;word-wrap: break-word;word-break:break-all;'></textarea>");
    $winform = $win->GetWindow("ok");
    $win->Display();
    exit();
}
/*----------------
function __ExportInOk()
-----------------*/
else if($dopost=="exportinok")
{
    require_once(DEDEADMIN."/inc/inc_admin_channel.php");
    function GotoStaMsg($msg)
    {
        global $wintitle,$wecome_info,$winform;
        $wintitle = "导入内容模型规则";
        $wecome_info = "<a href='mychannel_main.php'>内容模型管理</a>::导入内容模型规则";
        $win = new OxWindow();
        $win->Init();
        $win->AddTitle("操作状态提示：");
        $win->AddMsgItem($msg);
        $winform = $win->GetWindow("hand");
        $win->Display();
        exit();
    }

    $msg = "无信息";
    $exconfig = stripslashes($exconfig);

    $dtp = new DedeTagParse();
    $dtp->SetNameSpace('channel', '<', '>');
    $dtp->LoadSource($exconfig);

    if(!is_array($dtp->CTags)) GotoStaMsg("模型规则不是合法的Dede模型规则！");

    $fields = array();
    foreach($dtp->CTags as $ctag)
    {
        $fname = $ctag->GetName('name');
        $fields[$fname] = trim($ctag->GetInnerText());
    }

    if(!isset($fields['nid']) || !isset($fields['fieldset']))
    {
        GotoStaMsg("模型规则不是合法的Dede模型规则！");
    }

    //正常的导入过程
    $mysql_version = $dsql->GetVersion(true);

    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE nid='{$fields['nid']}' ");
    if(is_array($row))
    {
        GotoStaMsg("系统中已经存在相同标识 {$fields['nid']} 的规则！");
    }

    //创建表
    if($fields['issystem'] != -1)
    {
        $tabsql = "CREATE TABLE IF NOT EXISTS `{$fields['addtable']}`(
                  `aid` int(11) NOT NULL default '0',
                `typeid` int(11) NOT NULL default '0',
                `redirecturl` varchar(255) NOT NULL default '',
                `templet` varchar(30) NOT NULL default '',
                `userip` char(15) NOT NULL default '',";
    }
    else
    {
        $tabsql = "CREATE TABLE IF NOT EXISTS `{$fields['addtable']}`(
                  `aid` int(11) NOT NULL default '0',
                `typeid` int(11) NOT NULL default '0',
                `channel` SMALLINT NOT NULL DEFAULT '0',
                `arcrank` SMALLINT NOT NULL DEFAULT '0',
                `mid` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
                `click` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
                `title` varchar(60) NOT NULL default '',
                `senddate` int(11) NOT NULL default '0',
                `flag` set('c','h','p','f','s','j','a','b') default NULL,";
    }
    if($mysql_version < 4.1)
    {
        $tabsql .= "    PRIMARY KEY  (`aid`), KEY `typeid` (`typeid`)\r\n) TYPE=MyISAM; ";
    }
    else
    {
        $tabsql .= "    PRIMARY KEY  (`aid`), KEY `typeid` (`typeid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
    }
    $rs = $dsql->ExecuteNoneQuery($tabsql);
    if(!$rs)
    {
        GotoStaMsg("创建表失败!".$dsql->GetError());
        exit();
    }

    if($fields['issystem']==1) $fields['issystem'] = 0;
    if($fields['issystem'] == 0)
    {
        $row = $dsql->GetOne("SELECT id FROM `#@__channeltype` ORDER BY id DESC ");
        $fields['newid'] = $row['id'] + 1;
    }
    else
    {
        $row = $dsql->GetOne("SELECT id FROM `#@__channeltype` ORDER BY id ASC ");
        $fields['newid'] = $row['id'] - 1;
    }

    $fieldset = $fields['fieldset'];
    $fields['fieldset'] = addslashes($fields['fieldset']);

    $inquery = " INSERT INTO `#@__channeltype`(`id` , `nid` , `typename` , `addtable` , `addcon` ,
     `mancon` , `editcon` , `useraddcon` , `usermancon` , `usereditcon` ,
      `fieldset` , `listfields` , `issystem` , `isshow` , `issend` ,
       `arcsta`,`usertype` , `sendrank` )
    VALUES('{$fields['newid']}' , '{$fields['nid']}' , '{$fields['typename']}' , '{$fields['addtable']}' , '{$fields['addcon']}' ,
     '{$fields['mancon']}' , '{$fields['editcon']}' , '{$fields['useraddcon']}' , '{$fields['usermancon']}' , '{$fields['usereditcon']}' ,
      '{$fields['fieldset']}' , '{$fields['listfields']}' , '{$fields['issystem']}' , '{$fields['isshow']}' , '{$fields['issend']}' ,
       '{$fields['arcsta']}' , '{$fields['usertype']}' , '{$fields['sendrank']}' ); ";

    $rs = $dsql->ExecuteNoneQuery($inquery);

    if(!$rs) GotoStaMsg("导入模型时发生错误！".$dsql->GetError());
    $dtp = new DedeTagParse();
    $dtp->SetNameSpace("field","<",">");
    $dtp->LoadSource($fieldset);
    $allfields = '';
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $ctag)
        {
            //检测被修改的字段类型
            $dtype = $ctag->GetAtt('type');
            $fieldname = $ctag->GetName();
            $dfvalue = $ctag->GetAtt('default');
            $islist = $ctag->GetAtt('islist');
            $mxlen = $ctag->GetAtt('maxlength');
            $fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
            $ntabsql = $fieldinfos[0];
            $buideType = $fieldinfos[1];
            if($islist!='')
            {
                $allfields .= ($allfields=='' ? $fieldname : ','.$fieldname);
            }
            $dsql->ExecuteNoneQuery(" ALTER TABLE `{$fields['addtable']}` ADD  $ntabsql ");
        }
    }

    if($allfields!='')
    {
        $dsql->ExecuteNoneQuery("UPDATE `#@__channeltype` SET listfields='$allfields' WHERE id='{$fields['newid']}' ");
    }
    GotoStaMsg("成功导入一个模型！");
}
/*----------------
function __SaveCopy()
-----------------*/
else if($dopost=="copysave")
{
    $cid = intval($cid);
    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$cid' ", MYSQL_ASSOC);
    foreach($row as $k=>$v)
    {
        ${strtolower($k)} = addslashes($v);
    }
    $inquery = " INSERT INTO `#@__channeltype`(`id` , `nid` , `typename` , `addtable` , `addcon` ,
                `mancon` , `editcon` , `useraddcon` , `usermancon` , `usereditcon` , `fieldset` , `listfields` ,
                 `issystem` , `isshow` , `issend` , `arcsta`,`usertype` , `sendrank` )
              VALUES('$newid' , '$newnid' , '$newtypename' , '$newaddtable' , '$addcon' ,
               '$mancon' , '$editcon' , '$useraddcon' , '$usermancon' , '$usereditcon' , '$fieldset' , '$listfields' ,
               '$issystem' , '$isshow' , '$issend' , '$arcsta','$usertype' , '$sendrank' );
  ";
    $mysql_version = $dsql->GetVersion(TRUE);
    if(!$dsql->IsTable($newaddtable))
    {
        $dsql->Execute('me', "SHOW CREATE TABLE {$dsql->dbName}.{$addtable}");
        $row = $dsql->GetArray('me', MYSQL_BOTH);
        $tableStruct = $row[1];
        $tb = str_replace('#@__', $cfg_dbprefix, $addtable);
        $tableStruct = preg_replace("/CREATE TABLE `$addtable`/iU","CREATE TABLE `$newaddtable`",$tableStruct);
        $dsql->ExecuteNoneQuery($tableStruct);
    }
    if($copytemplet==1)
    {
        $tmpletdir = $cfg_basedir.$cfg_templets_dir.'/'.$cfg_df_style;
        copy("{$tmpletdir}/article_{$nid}.htm","{$tmpletdir}/{$newnid}_article.htm");
        copy("{$tmpletdir}/list_{$nid}.htm","{$tmpletdir}/{$newnid}_list.htm");
        copy("{$tmpletdir}/index_{$nid}.htm","{$tmpletdir}/{$newnid}_index.htm");
    }
    $rs = $dsql->ExecuteNoneQuery($inquery);
    if($rs)
    {
        ShowMsg("成功复制模型，现转到详细参数页... ","mychannel_edit.php?id={$newid}&dopost=edit");
        exit();
    }
    else
    {
        $errv = $dsql->GetError();
        ShowMsg("系统出错，请把错误代码发送到官方论坛，以检查原因！<br /> 错误代码：mychannel_edit.php?dopost=savecopy $errv","javascript:;");
        exit();
    }
}
/*------------
function __SaveEdit()
------------*/
else if($dopost=="save")
{
    $fieldset = preg_replace("#[\r\n]{1,}#", "\r\n", $fieldset);
    $usertype = empty($usertype)? '' : $usertype;

    $query = "Update `#@__channeltype` set
    typename = '$typename',
    addtable = '$addtable',
    addcon = '$addcon',
    mancon = '$mancon',
    editcon = '$editcon',
    useraddcon = '$useraddcon',
    usermancon = '$usermancon',
    usereditcon = '$usereditcon',
    fieldset = '$fieldset',
    listfields = '$listfields',
    issend = '$issend',
    arcsta = '$arcsta',
    usertype = '$usertype',
    sendrank = '$sendrank',
    needdes = '$needdes',
    needpic = '$needpic',
    titlename = '$titlename',
    onlyone = '$onlyone',
    dfcid = '$dfcid'
    where id='$id' ";
    if(trim($fieldset)!='')
    {
        $dtp = new DedeTagParse();
        $dtp->SetNameSpace("field", "<", ">");
        $dtp->LoadSource(stripslashes($fieldset));
        if(!is_array($dtp->CTags))
        {
            ShowMsg("文本配置参数无效，无法进行解析！","-1");
            exit();
        }
    }
    $trueTable = str_replace("#@__", $cfg_dbprefix, $addtable);
    if(!$dsql->IsTable($trueTable))
    {
        ShowMsg("系统找不到你所指定的表 $trueTable ，请手工创建这个表！","-1");
        exit();
    }
    $dsql->ExecuteNoneQuery($query);
    ShowMsg("成功更改一个模型！","mychannel_main.php");
    exit();
}
/*--------------------
function __GetTemplate()
--------------------*/
else if($dopost=="gettemplets")
{
    require_once(DEDEINC."/oxwindow.class.php");
    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$id'");
    $wintitle = "&nbsp;频道管理-查看模板";
    $wecome_info = "<a href='mychannel_main.php'>频道管理</a>::查看模板";
    $win = new OxWindow();
    $win->Init("", "js/blank.js", "");
    $win->AddTitle("&nbsp;频道：（".$row['typename']."）默认模板文件说明：");
    $defaulttemplate = $cfg_templets_dir.'/'.$cfg_df_style;
    $msg = "
        文档模板：{$defaulttemplate}/article_{$row['nid']}.htm
        <a href='tpl.php?acdir={$cfg_df_style}&action=edit&filename=article_{$row['nid']}.htm'>[修改]</a><br/>
        列表模板：{$defaulttemplate}/list_{$row['nid']}.htm
        <a href='tpl.php?acdir={$cfg_df_style}&action=edit&filename=list_{$row['nid']}.htm'>[修改]</a>
        <br/>
        频道封面模板：{$defaulttemplate}/index_{$row['nid']}.htm
        <a href='tpl.php?acdir={$cfg_df_style}&action=edit&filename=index_{$row['nid']}.htm'>[修改]</a>
    ";
    $win->AddMsgItem("<div style='padding:20px;line-height:300%'>$msg</div>");
    $winform = $win->GetWindow("hand","");
    $win->Display();
    exit();
}
/*--------------------
function __Delete()
--------------------*/
else if($dopost=="delete")
{
    CheckPurview('c_Del');
    $row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$id'");
    if($row['issystem'] == 1)
    {
        ShowMsg("系统模型不允许删除！","mychannel_main.php");
        exit();
    }
    if(empty($job)) $job="";

    if($job == "") //确认提示
    {
        require_once(DEDEINC."/oxwindow.class.php");
        $wintitle = "频道管理-删除模型";
        $wecome_info = "<a href='mychannel_main.php'>频道管理</a>::删除模型";
        $win = new OxWindow();
        $win->Init("mychannel_edit.php","js/blank.js","POST");
        $win->AddHidden("job","yes");
        $win->AddHidden("dopost",$dopost);
        $win->AddHidden("id",$id);
        $win->AddTitle("你确实要删除 (".$row['typename'].") 这个频道？");
        $winform = $win->GetWindow("ok");
        $win->Display();
        exit();
    } else if($job=="yes") //操作
    {
        require_once(DEDEINC."/typeunit.class.admin.php");
        $myrow = $dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id='$id'",MYSQL_ASSOC);
        if(!is_array($myrow))
        {
            ShowMsg('你所指定的频道信息不存在!','-1');
            exit();
        }

        //检查频道的表是否独占数据表
        $addtable = str_replace($cfg_dbprefix,'',str_replace('#@__',$cfg_dbprefix,$myrow['addtable']));
        $row = $dsql->GetOne("SELECT COUNT(id) AS dd FROM `#@__channeltype` WHERE  addtable like '{$cfg_dbprefix}{$addtable}' OR addtable LIKE CONCAT('#','@','__','$addtable') ; ");
        $isExclusive2 = ($row['dd']>1 ? 0 : 1 );

        //获取与频道关连的所有栏目id
        $tids = '';
        $dsql->Execute('qm',"SELECT id FROM `#@__arctype` WHERE channeltype='$id'");
        while($row = $dsql->GetArray('qm'))
        {
            $tids .= ($tids=='' ? $row['id'] : ','.$row['id']);
        }

        //删除相关信息
        if($tids!='')
        {
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctiny` WHERE typeid IN($tids); ");
            $dsql->ExecuteNoneQuery("DELETE FROM `{$myrow['maintable']}` WHERE typeid IN($tids); ");
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__spec` WHERE typeid IN ($tids); ");
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__feedback` WHERE typeid IN ($tids); ");
            $dsql->ExecuteNoneQuery("DELETE FROM `#@__arctype` WHERE id IN ($tids); ");
        }

        //删除附加表或附加表内的信息
        if($isExclusive2==1)
        {
            $dsql->ExecuteNoneQuery("DROP TABLE IF EXISTS `{$cfg_dbprefix}{$addtable}`;");
        }
        else
        {
            if($tids!='' && $myrow['addtable']!='')
            {
                $dsql->ExecuteNoneQuery("DELETE FROM `{$myrow['addtable']}` WHERE typeid IN ($tids); ");
            }
        }

        //删除频道配置信息
        $dsql->ExecuteNoneQuery("DELETE FROM `#@__channeltype` WHERE id='$id' ");

        //更新栏目缓存
        UpDateCatCache($dsql);
        ShowMsg("成功删除一个模型！","mychannel_main.php");
        exit();
    }
}//del
/*----------------
function __modifysearch()
-----------------*/
else if($dopost == 'modifysearch')
{
    if(!isset($step)) $step=0;
    if(empty($step))
    {
        $step = 1;
        $mid = intval($mid);
        $query = "SELECT mainfields, addonfields, template FROM #@__advancedsearch WHERE mid='$mid'";
        $searchinfo = $dsql->GetOne($query);
        if(!is_array($searchinfo))
        {
            $searchinfo['mainfields'] = $searchinfo['addonfields'] = $searchinfo['template'] = '';
        }
        $searchinfo['mainfields'] = explode(',', $searchinfo['mainfields']);
        $searchinfo['addonfields'] = explode(',', $searchinfo['addonfields']);
        $addonfieldsarr = array();
        foreach($searchinfo['addonfields'] as $k)
        {
            $karr = explode(':', $k);
            $addonfieldsarr[] = $karr[0];
        }
        $template = $searchinfo['template'] == '' ? 'advancedsearch.htm' : $searchinfo['template'];
        $c1 = in_array('iscommend', $searchinfo['mainfields']) ? 'checked' : '';
        $c2 = in_array('typeid', $searchinfo['mainfields']) ? 'checked' : '';
        $c3 = in_array('writer', $searchinfo['mainfields']) ? 'checked' : '';
        $c4 = in_array('source', $searchinfo['mainfields']) ? 'checked' : '';
        $c5 = in_array('senddate', $searchinfo['mainfields']) ? 'checked' : '';

        $mainfields = '<label><input type="checkbox" name="mainfields[]" '.$c1.' value="iscommend" class="np" />是否推荐</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c2.' value="typeid" class="np" />栏目</label>';

        $mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c3.' value="writer" class="np" />作者</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c4.' value="source" class="np" />来源</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c5.' value="senddate" class="np" />发布时间</label>';
        /*
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" value="description" />摘要</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" value="keywords" />关键词</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" value="smalltypeid" />小分类</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" value="area" />地区</label>';
        $mainfields .= '<label><input type="checkbox" name="mainfields[]" value="sector" />行业</label>';
        */
        $query = "SELECT * FROM `#@__channeltype` WHERE id='$mid'";
        $channel = $dsql->GetOne($query);

        $searchtype = array('int', 'datetime', 'float', 'textdata', 'textchar', 'text', 'htmltext', 'multitext', 'select', 'radio', 'checkbox');
        $addonfields = '';
        $dtp = new DedeTagParse();
        $dtp->SetNameSpace("field", "<", ">");
        $dtp->LoadSource($channel['fieldset']);
        if($channel['issystem'] < 0)
        {
            $checked = in_array('typeid', $addonfieldsarr) ? 'checked' : '';
            $addonfields .= '<label><input type="checkbox" name="addonfields[]" '.$checked.' value="typeid" class="np" />栏目</label>';
            $checked = in_array('senddate', $addonfieldsarr) ? 'checked' : '';
            $addonfields .= '<label><input type="checkbox" name="addonfields[]" '.$checked.' value="senddate" class="np" />发布时间</label>';
        }
        if(is_array($dtp->CTags) && !empty($dtp->CTags))
        {
            foreach($dtp->CTags as $ctag)
            {
                $datatype = $ctag->GetAtt('type');
                $value = $ctag->GetName();
                if($channel['issystem'] < 0)
                {
                    $_oo = array('channel','arcrank', 'title', 'senddate', 'mid', 'click', 'flag', 'litpic', 'userip', 'lastpost', 'scores', 'goodpost', 'badpost', 'endtime');
                    if(in_array($value, $_oo)) continue;
                }

                $label = $ctag->GetAtt('itemname');
                if(in_array($datatype, $searchtype)){
                    $checked = in_array($value, $addonfieldsarr) ? 'checked' : '';
                    $addonfields .= "<label><input type=\"checkbox\" name=\"addonfields[]\" $checked value=\"$value\" class='np' />$label</label>";
                }
            }
        }
        require_once(dirname(__FILE__)."/templets/mychannel_modifysearch.htm");
    } else if ($step == 1)
    {
        $query = "SELECT * FROM `#@__channeltype` WHERE id='$mid'";
        $channel = $dsql->GetOne($query);
        if(empty($addonfields))
        {
            $addonfields = '';
        }
        $template = trim($template);
        $forms = '<form action="'.$cfg_cmspath.'/plus/advancedsearch.php" method="post">';
        $forms .= "<input type=\"hidden\" name=\"mid\" value=\"$mid\" />";
        $forms .= "<input type=\"hidden\" name=\"dopost\" value=\"search\" />";
        $forms .= "关键词：<input type=\"text\" name=\"q\" /><br />";
        $mainstring = '';
        if(!empty($mainfields) && is_array($mainfields))
        {
            $mainstring = implode(',', $mainfields);
            foreach($mainfields as $mainfield)
            {
                if($mainfield == 'typeid')
                {
                    require_once(dirname(__FILE__)."/../include/typelink.class.php");
                    $tl = new TypeLink(0);
                    $typeOptions = $tl->GetOptionArray(0,0,$mid);
                    $forms .= "<br />栏目：<select name='typeid' style='width:200'>\r\n";
                    $forms .= "<option value='0' selected>--不限栏目--</option>\r\n";
                    $forms .= $typeOptions;
                    $forms .= "</select>";
                    $forms .= "<label><input type=\"checkbox\" name=\"includesons\" value=\"1\" />包含子栏目</label><br />";
                }else if ($mainfield == 'iscommend')
                {
                    $forms .= "<label><input type=\"checkbox\" name=\"iscommend\" value=\"1\" />推荐</label><br />";
                }else if ($mainfield == 'writer')
                {
                    $forms .= "作者： <input type=\"text\" name=\"writer\" value=\"\" /><br />";
                }else if ($mainfield == 'source')
                {
                    $forms .= "来源： <input type=\"text\" name=\"source\" value=\"\" /><br />";
                }else if ($mainfield == 'senddate')
                {
                    $forms .= "开始时间：<input type=\"text\" name=\"startdate\" value=\"\" /><br />";
                    $forms .= "结束时间：<input type=\"text\" name=\"enddate\" value=\"\" /><br />";
                }

            }
        }

        $addonstring = '';
        $intarr = array('int','float');
        $textarr = array('textdata','textchar','text','htmltext','multitext');

        if($channel['issystem'] < 0)
        {
            foreach($addonfields as $addonfield)
            {
                if($addonfield == 'typeid'){
                    require_once(dirname(__FILE__)."/../include/typelink.class.php");
                    $tl = new TypeLink(0);
                    $typeOptions = $tl->GetOptionArray(0,0,$mid);
                    $forms .= "<br />栏目：<select name='typeid' style='width:200'>\r\n";
                    $forms .= "<option value='0' selected>--不限栏目--</option>\r\n";
                    $forms .= $typeOptions;
                    $forms .= "</select>";
                    $forms .= "<label><input type=\"checkbox\" name=\"includesons\" value=\"1\" />包含子栏目</label><br />";
                    $addonstring .= 'typeid:int,';
                } elseif($addonfield == 'senddate') {
                    $forms .= "开始时间：<input type=\"text\" name=\"startdate\" value=\"\" /><br />";
                    $forms .= "结束时间：<input type=\"text\" name=\"enddate\" value=\"\" /><br />";
                    $addonstring .= 'senddate:datetime,';
                }
            }
        }

        if(is_array($addonfields) && !empty($addonfields))
        {
            $query = "SELECT * FROM #@__channeltype WHERE id='$mid'";
            $channel = $dsql->GetOne($query);

            $dtp = new DedeTagParse();
            $dtp->SetNameSpace("field", "<", ">");
            $dtp->LoadSource($channel['fieldset']);
            $fieldarr = $itemarr = $typearr = array();
            foreach($dtp->CTags as $ctag)
            {
                foreach($addonfields as $addonfield)
                {

                    if($ctag->GetName() == $addonfield)
                    {
                        if($addonfield == 'typeid' || $addonfield == 'senddate') continue;

                        $fieldarr[] = $addonfield;
                        $itemarr[] = $ctag->GetAtt('itemname');
                        $typearr[] = $ctag->GetAtt('type');
                        $valuearr[] = $ctag->GetAtt('default');
                    }
                }
            }

            foreach($fieldarr as $k=>$field)
            {
                $itemname = $itemarr[$k];
                $name = $field;
                $type = $typearr[$k];
                $tmp = $name.':'.$type;
                if(in_array($type, $intarr))
                {
                    $forms .= "<br />$itemname : <input type=\"text\" name=\"start".$name."\" value=\"\" /> 到 <input type=\"text\" name=\"end".$name."\" value=\"\" /><br />";
                } else if (in_array($type, $textarr))
                {
                    $forms .= "$itemname : <input type=\"text\" name=\"$name\" value=\"\" /><br />";

                } else if ($type == 'select')
                {
                    $values = explode(',', $valuearr[$k]);
                    if(is_array($values) && !empty($values))
                    {
                        $forms .= "<br />$itemname : <select name=\"$name\" ><option value=\"\">不限</option>";
                        foreach($values as $value)
                        {
                            $forms .= "<option value=\"$value\">$value</option>";
                        }
                        $forms .= "</select>";
                    }
                } else if ($type == 'radio')
                {
                    $values = explode(',', $valuearr[$k]);
                    if(is_array($values) && !empty($values)){
                        $forms .= "<br />$itemname : <label><input type=\"radio\" name=\"".$name."\" value=\"\" checked />不限</label>";
                        foreach($values as $value){
                            $forms .= "<label><input type=\"radio\" name=\"".$name."\" value=\"$value\" />$value</label>";
                        }
                    }
                } else if ($type == 'checkbox')
                {
                    $values = explode(',', $valuearr[$k]);
                    if(is_array($values) && !empty($values))
                    {
                        $forms .= "<br />$itemname : ";
                        foreach($values as $value)
                        {
                            $forms .= "<label><input type=\"checkbox\" name=\"".$name."[]\" value=\"$value\" />$value</label>";
                        }
                    }

                }elseif($type == 'datetime'){
                    $forms .= "<br />开始时间：<input type=\"text\" name=\"startdate\" value=\"\" /><br />";
                    $forms .= "结束时间：<input type=\"text\" name=\"enddate\" value=\"\" /><br />";
                }else{
                    $tmp = '';
                }
                $addonstring .= $tmp.',';
            }
        }
        $forms .= '<input type="submit" name="submit" value="开始搜索" /></form>';
        $formssql = addslashes($forms);
        $query = "REPLACE INTO #@__advancedsearch(mid, maintable, mainfields, addontable, addonfields, forms, template) VALUES('$mid','$maintable','$mainstring','$addontable','$addonstring','$formssql', '$template')";
        $dsql->ExecuteNoneQuery($query);
        $formshtml = htmlspecialchars($forms);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
        echo "下面为生成的html表单，请自行复制，根据自己需求修改样式后粘贴到对应的模板中<br><br><textarea cols=\"100\"  rows=\"10\">".$forms."</textarea>";
        echo '<br />预览：<br /><hr>';
        echo $forms;
    }
    exit;
}
//删除自定义搜索；
else if($dopost == 'del')
{
    $mid = intval($mid);
    $dsql->ExecuteNoneQuery("DELETE FROM `#@__advancedsearch` WHERE mid = '$mid'; ");
    ShowMsg("成功删除一个自定义搜索！","mychannel_main.php");
    exit();
}
$row = $dsql->GetOne("SELECT * FROM `#@__channeltype` WHERE id='$id' ");
require_once(DEDEADMIN."/templets/mychannel_edit.htm");