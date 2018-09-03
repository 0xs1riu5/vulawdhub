<?php
/**
 * 自定义表单列表
 *
 * @version        $Id: diy_list.php 1 18:31 2010年7月12日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
$diyid = isset($diyid) && is_numeric($diyid) ? $diyid : 0;
$action = isset($action) && in_array($action, array('post', 'list', 'edit', 'check', 'delete')) ? $action : '';
if(empty($diyid))
{
    showMsg("非法操作!", 'javascript:;');
    exit();
}
require_once DEDEINC.'/diyform.cls.php';
$diy = new diyform($diyid);
if($action == 'post')
{
    if(empty($do))
    {
        $postform = $diy->getForm('post','','admin');
        include DEDEADMIN.'/templets/diy_post.htm';
    }
    else if($do == 2)
    {
        $dede_fields = empty($dede_fields) ? '' : trim($dede_fields);
        $dede_fieldshash = empty($dede_fieldshash) ? '' : trim($dede_fieldshash);
        if(!empty($dede_fields))
        {
            if($dede_fieldshash != md5($dede_fields.$cfg_cookie_encode))
            {
                showMsg("数据校验不对，程序返回", '-1');
                exit();
            }
        }
        $diyform = $dsql->getOne("SELECT * FROM #@__diyforms WHERE diyid=$diyid");
        if(!is_array($diyform))
        {
            showmsg("自定义表单不存在", '-1');
            exit();
        }
        $addvar = $addvalue = '';
        if(!empty($dede_fields))
        {
            $fieldarr = explode(';', $dede_fields);
            if(is_array($fieldarr))
            {
                foreach($fieldarr as $field)
                {
                    if($field == '')
                    {
                        continue;
                    }
                    $fieldinfo = explode(',', $field);
                    if($fieldinfo[1] == 'htmltext' || $fieldinfo[1] == 'textdata')
                    {
                        ${$fieldinfo[0]} = filterscript(stripslashes(${$fieldinfo[0]}));
                        ${$fieldinfo[0]} = addslashes(${$fieldinfo[0]});
                        ${$fieldinfo[0]} = getFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','member');
                    }
                    else
                    {
                        ${$fieldinfo[0]} = getFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','member');
                    }
                    $addvar .= ', `'.$fieldinfo[0].'`';
                    $addvalue .= ", '".${$fieldinfo[0]}."'";
                }
            }
        }
        $query = "INSERT INTO `{$diy->table}` (`id`, `ifcheck` $addvar)  VALUES (NULL, 0 $addvalue)";
        if($dsql->ExecuteNoneQuery($query))
        {
            $goto = "diy_list.php?action=list&diyid={$diy->diyid}";
            showmsg('发布成功', $goto);
        }
        else
        {
            showmsg('对不起，发布不成功', '-1');
        }
    }
} else if ($action == 'list')
{
    include_once DEDEINC.'/datalistcp.class.php';
    $query = "SELECT * FROM {$diy->table} ORDER BY id DESC";
    $datalist = new DataListCP();
    $datalist->pageSize = 10;
    $datalist->SetParameter('action', 'list');
    $datalist->SetParameter('diyid', $diyid);
    $datalist->SetTemplate(DEDEADMIN.'/templets/diy_list.htm');
    $datalist->SetSource($query);
    $fieldlist = $diy->getFieldList();
    $datalist->Display();
} else if ($action == 'edit')
{
    if(empty($do))
    {
        $id = isset($id) && is_numeric($id) ? $id : 0;
        if(empty($id))
        {
            showMsg('非法操作！未指定id', 'javascript:;');
            exit();
        }
        $query = "SELECT * FROM {$diy->table} WHERE id=$id";
        $row = $dsql->GetOne($query);
        if(!is_array($row))
        {
            showmsg("你访问的记录不存在或未经审核", '-1');
            exit();
        }
        $postform = $diy->getForm('edit', $row, 'admin');
        $fieldlist = $diy->getFieldList();
        $c1 = $row['ifcheck'] == 1 ? 'checked' : '';
        $c2 = $row['ifcheck'] == 0 ? 'checked' : '';
        include DEDEADMIN.'/templets/diy_edit_content.htm';
    }
    else if($do == 2)
    {
        $dede_fields = empty($dede_fields) ? '' : trim($dede_fields);
        $diyform = $dsql->GetOne("SELECT * FROM #@__diyforms WHERE diyid=$diyid");
        $diyco = $dsql->GetOne("SELECT * FROM `$diy->table` WHERE id='$id'");
        if(!is_array($diyform))
        {
            showmsg("自定义表单不存在", '-1');
            exit();
        }
        $addsql = '';
        if(!empty($dede_fields))
        {
            $fieldarr = explode(';', $dede_fields);
            if(is_array($fieldarr))
            {
                foreach($fieldarr as $field)
                {
                    if($field == '')
                    {
                        continue;
                    }
                    $fieldinfo = explode(',', $field);
                    if($fieldinfo[1] == 'htmltext' || $fieldinfo[1] == 'textdata')
                    {
                        ${$fieldinfo[0]} = filterscript(stripslashes(${$fieldinfo[0]}));
                        ${$fieldinfo[0]} = addslashes(${$fieldinfo[0]});
                        ${$fieldinfo[0]} = GetFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','member');
                        ${$fieldinfo[0]} = empty(${$fieldinfo[0]}) ? $diyco[$fieldinfo[0]] : ${$fieldinfo[0]};
                    }
                    else
                    {

                        ${$fieldinfo[0]} = GetFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','diy', $fieldinfo[0]);
                        ${$fieldinfo[0]} = empty(${$fieldinfo[0]}) ? $diyco[$fieldinfo[0]] : ${$fieldinfo[0]};
                    }
                    $addsql .= !empty($addsql)?',`'.$fieldinfo[0]."`='".${$fieldinfo[0]}."'" : '`'.$fieldinfo[0]."`='".${$fieldinfo[0]}."'";
                }
            }
        }
        $query = "UPDATE `$diy->table` SET $addsql  WHERE id=$id";
        if($dsql->ExecuteNoneQuery($query))
        {
            $goto = "diy_list.php?action=list&diyid={$diy->diyid}";
            showmsg('编辑成功', $goto);
        }
        else
        {
            showmsg('编辑成功', '-1');
        }
    }
}elseif($action == 'check')
{
    if(is_array($id))
    {
        $ids = implode(',', $id);
    }
    else
    {
        showmsg('未选中要操作的内容', '-1');
        exit();
    }
    $query = "UPDATE `$diy->table` SET ifcheck=1 WHERE id IN ($ids)";
    if($dsql->ExecuteNoneQuery($query))
    {
        showmsg('审核成功', "diy_list.php?action=list&diyid={$diy->diyid}");
    }
    else
    {
        showmsg('审核失败', "diy_list.php?action=list&diyid={$diy->diyid}");
    }
}elseif($action == 'delete')
{
    if(empty($do))
    {
        if(is_array($id))
        {
            $ids = implode(',', $id);
        }else
        {
            showmsg('未选中要操作的内容', '-1');
            exit();
        }
        $query = "DELETE FROM `$diy->table` WHERE id IN ($ids)";
        if($dsql->ExecuteNoneQuery($query))
        {
            showmsg('删除成功', "diy_list.php?action=list&diyid={$diy->diyid}");
        }
        else
        {
            showmsg('删除失败', "diy_list.php?action=list&diyid={$diy->diyid}");
        }
    } else if($do=1){
        $row = $dsql->GetOne("SELECT * FROM `$diy->table` WHERE id='$id'");
        if(file_exists($cfg_basedir.$row[$name])){
            unlink($cfg_basedir.$row[$name]);
            $dsql->ExecuteNoneQuery("UPDATE `$diy->table` SET $name='' WHERE id='$id'");
            showmsg('文件删除成功',"diy_list.php?action=list&diyid={$diy->diyid}");
        }else{
            showmsg('文件不存在','-1');
        }
    }
}else
{
    showmsg('未定义操作', "-1");
}