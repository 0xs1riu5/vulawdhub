<?php
/**
 * 系统配置
 *
 * @version        $Id: sys_info.php 1 22:28 2010年7月20日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
if(empty($dopost)) $dopost = "";

$configfile = DEDEDATA.'/config.cache.inc.php';

//更新配置函数
function ReWriteConfig()
{
    global $dsql,$configfile;
    if(!is_writeable($configfile))
    {
        echo "配置文件'{$configfile}'不支持写入，无法修改系统配置参数！";
        exit();
    }
    $fp = fopen($configfile,'w');
    flock($fp,3);
    fwrite($fp,"<"."?php\r\n");
    $dsql->SetQuery("SELECT `varname`,`type`,`value`,`groupid` FROM `#@__sysconfig` ORDER BY aid ASC ");
    $dsql->Execute();
    while($row = $dsql->GetArray())
    {
        if($row['type']=='number')
        {
            if($row['value']=='') $row['value'] = 0;
            fwrite($fp,"\${$row['varname']} = ".$row['value'].";\r\n");
        }
        else
        {
            fwrite($fp,"\${$row['varname']} = '".str_replace("'",'',$row['value'])."';\r\n");
        }
    }
    fwrite($fp,"?".">");
    fclose($fp);
}

//保存配置的改动
if($dopost=="save")
{
    foreach($_POST as $k=>$v)
    {
        if(preg_match("#^edit___#", $k))
        {
            $v = cn_substrR(${$k}, 1024);
        }
        else
        {
            continue;
        }
        $k = preg_replace("#^edit___#", "", $k);
        $dsql->ExecuteNoneQuery("UPDATE `#@__sysconfig` SET `value`='$v' WHERE varname='$k' ");
    }
    ReWriteConfig();
    ShowMsg("成功更改站点配置！", "sys_info.php");
    exit();
}
//增加新变量
else if($dopost=='add')
{
    if($vartype=='bool' && ($nvarvalue!='Y' && $nvarvalue!='N'))
    {
        ShowMsg("布尔变量值必须为'Y'或'N'!","-1");
        exit();
    }
    if(trim($nvarname)=='' || preg_match("#[^a-z_]#i", $nvarname) )
    {
        ShowMsg("变量名不能为空并且必须为[a-z_]组成!","-1");
        exit();
    }
    $row = $dsql->GetOne("SELECT varname FROM `#@__sysconfig` WHERE varname LIKE '$nvarname' ");
    if(is_array($row))
    {
        ShowMsg("该变量名称已经存在!","-1");
        exit();
    }
    $row = $dsql->GetOne("SELECT aid FROM `#@__sysconfig` ORDER BY aid DESC ");
    $aid = $row['aid'] + 1;
    $inquery = "INSERT INTO `#@__sysconfig`(`aid`,`varname`,`info`,`value`,`type`,`groupid`)
    VALUES ('$aid','$nvarname','$varmsg','$nvarvalue','$vartype','$vargroup')";
    $rs = $dsql->ExecuteNoneQuery($inquery);
    if(!$rs)
    {
        ShowMsg("新增变量失败，可能有非法字符！", "sys_info.php?gp=$vargroup");
        exit();
    }
    if(!is_writeable($configfile))
    {
        ShowMsg("成功保存变量，但由于 $configfile 无法写入，因此不能更新配置文件！","sys_info.php?gp=$vargroup");
        exit();
    }else
    {
        ReWriteConfig();
        ShowMsg("成功保存变量并更新配置文件！","sys_info.php?gp=$vargroup");
        exit();
    }
}
// 搜索配置
else if ($dopost=='search')
{
    $keywords = isset($keywords)? strip_tags($keywords) : '';
    $i = 1;
    $configstr = <<<EOT
 <table width="100%" cellspacing="1" cellpadding="1" border="0" bgcolor="#cfcfcf" id="tdSearch" style="">
  <tbody>
   <tr height="25" bgcolor="#fbfce2" align="center">
    <td width="300">参数说明</td>
    <td>参数值</td>
    <td width="220">变量名</td>
   </tr>
EOT;
    echo $configstr;
    if ($keywords)
    {

        $dsql->SetQuery("SELECT * FROM `#@__sysconfig` WHERE info LIKE '%$keywords%' order by aid asc");
        $dsql->Execute();
       
        while ($row = $dsql->GetArray()) {
            $bgcolor = ($i++%2==0)? "#F9FCEF" : "#ffffff";
            $row['info'] = preg_replace("#{$keywords}#", '<font color="red">'.$keywords.'</font>', $row['info']);
?>
      <tr align="center" height="25" bgcolor="<?php echo $bgcolor?>">
       <td width="300"><?php echo $row['info']; ?>： </td>
       <td align="left" style="padding:3px;">
<?php
    if($row['type']=='bool')
    {
        $c1='';
        $c2 = '';
        $row['value']=='Y' ? $c1=" checked" : $c2=" checked";
        echo "<input type='radio' class='np' name='edit___{$row['varname']}' value='Y'$c1>是 ";
        echo "<input type='radio' class='np' name='edit___{$row['varname']}' value='N'$c2>否 ";
    }else if($row['type']=='bstring')
    {
        echo "<textarea name='edit___{$row['varname']}' row='4' id='edit___{$row['varname']}' class='textarea_info' style='width:98%;height:50px'>".htmlspecialchars($row['value'])."</textarea>";
    }else if($row['type']=='number')
    {
        echo "<input type='text' name='edit___{$row['varname']}' id='edit___{$row['varname']}' value='{$row['value']}' style='width:30%'>";
    }else
    {
        echo "<input type='text' name='edit___{$row['varname']}' id='edit___{$row['varname']}' value=\"".htmlspecialchars($row['value'])."\" style='width:80%'>";
    }
    ?>
</td>
       <td><?php echo $row['varname']?></td>
      </tr>
      <?php
}
?>
     </table>
      <?php
        exit;
    }
    if ($i == 1)
    {
        echo '      <tr align="center" bgcolor="#F9FCEF" height="25">
           <td colspan="3">没有找到搜索的内容</td>
          </tr></table>';
    }
    exit;
}

include DedeInclude('templets/sys_info.htm');