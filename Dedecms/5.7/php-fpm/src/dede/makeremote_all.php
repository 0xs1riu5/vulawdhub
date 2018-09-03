<?php
/**
 * 远程发布
 *
 * @version        $Id: makeremote_all.php 1 11:17 2010年7月19日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");

if($cfg_remote_site=='N') exit('Error:$cfg_remote_site is OFF,Check it!');

//生成必须同步服务器的列表
if(file_exists(DEDEDATA.'/config.file.inc.php'))
{
    require_once(DEDEDATA.'/config.file.inc.php');
}

if(empty($dopost)) $dopost = '';

$step = !isset($step)? 1 : $step;
$sta = !isset($sta)? 0 : $sta;
$totalnum = !isset($totalnum)? 0 : $totalnum;
$maketype = empty($maketype)? '' : $maketype;

//获取同步状态
function GetState($val)
{
    $color = ($val == 0)? 'red' : 'green';
    $signer = ($val == 0)? '未同步' : '已同步';
    return '<font color="'.$color.'">'.$signer.'</font>';
}

//生成远程目录数组
function addDir($filedir='', $description='', $dfserv=0, $state=0, $issystem=0)
{
    return array(
        'filedir' => $filedir,
        'description' => $description,
        'dfserv' => $dfserv,
        'state' => $state,
        'issystem' => $issystem
    );
}

//将配置数组生成配置内容
function makeConfig($dirarray=array())
{
    $config_str = '';
    foreach($dirarray as $k => $val)
    {
        $config_str .= '$remotefile['.$k.'] = array('."\n";
        $config_str .= '  \'filedir\'=>\''.$val['filedir']."',\n";
        $config_str .= '  \'description\'=>\''.$val['description']."',\n";
        $config_str .= '  \'dfserv\'=>'.$val['dfserv'].",\n";
        $config_str .= '  \'state\'=>'.$val['state'].",\n";
        $config_str .= '  \'issystem\'=>'.$val['issystem']."\n";
        $config_str .= ");\n";
    }
    return ($config_str == '')? '' : $config_str;
}

//递归获取目录 by:tianya
function getDirs($directory,$exempt = array('.','..','.ds_store','.svn'),&$files = array()) 
{ 
    //没有则创建
    if(is_dir($directory) && !opendir($directory)) mkdir($directory,0777,TRUE);
    $handle = opendir($directory); 
  
    while(false !== ($resource = readdir($handle)))
    { 
        if(!in_array(strtolower($resource),$exempt)) 
        {
            //排除目录
            if(is_dir($directory.$resource.'/'))
            { 
                array_merge($files, 
                getDirs($directory.$resource.'/',$exempt,$files)); 
            } else {
              //if(!is_file($directory.'/'.$resource))
              //{
              $files[] = $directory.'/'.$resource; 
              //}
            }
        } 
    }
    closedir($handle); 
    return $files; 
} 

function updateConfig($dirarray=array())
{
    //将其转换为配置写入文件
    $configfile = DEDEDATA.'/config.file.inc.php';
    $old_config = @file_get_contents($configfile);
    $config_str = makeConfig($dirarray);
    //替换配置项
    $new_config = preg_replace("/#<s_config>(.*)#<e_config>/s", "#<s_config>\n\n{$config_str}#<e_config>", $old_config);
    file_put_contents($configfile, $new_config);
}

if($dopost == '')
{

}
/*
function Updateremote()
*/
else if($dopost == 'updateremote')
{
    $dirbox = array(); // 定义一个目录容器,确保目录的唯一性
    //获取所有HTML生成文件夹列表
    $query = "SELECT id,typedir,ispart FROM #@__arctype WHERE ispart <> '3'";
    $dsql->SetQuery($query);
    $dsql->Execute('al');
    $dirarray = array();

    $i = 0;
    while ($row = $dsql->GetArray("al"))
    {
        $darray = explode('/', preg_replace('/{cmspath}/', '', $row['typedir']));
        //仅获取顶级目录作为文件同步更新目录
        if(!in_array($darray[1], $dirbox))
        {
            $dirarray[$i] = addDir('/'.$darray[1], '文档HTML默认保存路', 0, 0, 1);
            $dirbox[] = $darray[1];
            $i++;
        }
    }
    //系统附件存放目录
    $dirarray[$i++] = addDir($cfg_medias_dir, '图片/上传文件默认路径', 0, 0, 1);
    //专题目录
    $dirarray[$i++] = addDir('/special', '专题目录', 0, 0, 1);
    //data/js目录
    $dirarray[$i++] = addDir('/data/js', '生成js目录', 0, 0, 1);
    //$remotefile = array();
    //把非系统目录的内容提取出来
    foreach ($remotefile as $key => $value)
    {
        //处理用户自定义配置
        if($value['issystem'] == 0)
        {
            $dirarray[$i++] = addDir($value['filedir'], $value['description'],
                                     $value['dfserv'], $value['state'], $value['issystem']);
        }
    }
    
    updateConfig($dirarray);
    
    ShowMsg("成功更新同步目录,请重新对目录进行同步操作!","makeremote_all.php");
    exit;
}
/*
function Make()&MakeAll()
*/
else if($dopost == 'make')
{
    if($step == 1)
    {
        if($maketype == 'makeall')
        {
            //如果更新所有,则需要重新组合$Iterm
            foreach($remotefile as $key => $val)
            {
                $Iterm[] = $val['filedir'];
            }
        } else {
            //初始化配置
            $Iterm = !isset($Iterm)? array(): $Iterm;
        }

        $serviterm = !isset($serviterm)? array(): $serviterm;
        $cacheMakeFile = DEDEDATA.'/cache/filelist.inc.php';
        $dirlist = $alllist = $updir = array();
        $dirindex = 0;//目录统一索引
        
        //采用比较人性化的更新方式进行提示更新
        //初始化本地文件夹底层的子集目录
        
        if(count($Iterm) > 0)
        {
            //获取远程文件(夹)列表
            foreach($Iterm as $key => $val)
            {
                $config = $serviterm[$key];
                if(is_array($dirlist = getDirs(DEDEROOT.$val)))
                {
                    foreach($dirlist as $k => $v)
                    {
                        $alllist[] = $v.'|'.$config;
                        if(!in_array($val, array_values($updir))) $updir[] = $val;
                    }
                }
            }
            //遍历文件夹列表,如果存在子集文件夹大于3的则需要进行细分
            //将列表写入缓存
            $cachestr = "<?php \n  global \$dirlist,\$upremote;\n  \$dirlist=array();\n";
            foreach($alllist as $key => $val)
            {
                list($filename,$fileconfig) = explode('|', $val); 
                if(is_dir($filename))
                {
                    $deepDir = getDirs($filename);
                    $dd = 0;
                    //先遍历一遍确定子集文件夹数目
                    foreach($deepDir as $k => $v)
                    {
                        if(is_dir($v)) $dd++;
                    }
                    if($dd > 3)
                    {
                        //如果自己文件夹数目大于3则
                        foreach($deepDir as $k => $v)
                        {
                            $v .= '|'.$fileconfig;
                            $cachestr .= "  \$dirlist['$dirindex']='$v';\n";
                            $dirindex++;
                        }
                    }else{
                        $cachestr .= "  \$dirlist['$dirindex']='$val';\n";
                        $dirindex++;
                    }
                }
            }
            
            foreach($updir as $key => $val)
            {
                $cachestr .= "  \$upremote['$key']='$val';\n";
            }
            $cachestr .= "?>";
            file_put_contents($cacheMakeFile, $cachestr);
            $tnum = count($alllist);
            ShowMsg("成功获取远程列表,下面进行文件远程发布!","makeremote_all.php?dopost=make&step=2&sta=1&totalnum=$tnum");
            exit;
        } else {
            echo '您没有选择,请先选择再点击更新!';
        }
        exit;    
    } elseif ($step == 2)
    {
        if(file_exists(DEDEDATA.'/cache/filelist.inc.php'))
        {
            require_once(DEDEDATA.'/cache/filelist.inc.php');
        }
        if(is_array($dirlist))
        {
            if($sta > 0 && $sta < $totalnum)
            {
                list($dirname, $ftpconfig) = explode('|', $dirlist[$sta-1]); 
                list($servurl, $servuser, $servpwd) = explode(',', $ftpconfig);
                $config=array( 'hostname' => $servurl, 'username' => $servuser,
                               'password' => $servpwd,'debug' => 'TRUE');
                if($ftp->connect($config))
                {
                    //var_dump(is_dir($dirname));exit;
                    if(is_dir($dirname))
                    {
                        //如果是文件目录
                        $remotedir = str_replace(DEDEROOT, '', $dirname).'/';
                        $localdir = '..'.$remotedir.'/';
                        $ftp->rmkdir($remotedir);
                        if( $ftp->mirror($localdir, $remotedir))
                        {
                            $sta++;
                            ShowMsg("成功同步文件夹$remotedir,进入下一个任务","makeremote_all.php?dopost=make&step=2&sta={$sta}&totalnum=$totalnum");
                            exit;
                        }
                    } else {
                        $remotefile = str_replace(DEDEROOT, '', $dirname);
                        $localfile = '..'.$remotefile;
                        //创建远程文件夹
                        $remotedir = preg_replace('/[^\/]*\.(\w){0,}/', '', $remotefile);
                        
                        //如果是文件则需要智能处理
                        $remotebox = array();
                        $ftp->rmkdir($remotedir);
                        foreach($dirlist as $key => $val)
                        {
                            list($filename,$fileconfig) = explode('|', $val); 
                            if(preg_replace('/[^\/]*\.(\w){0,}/', '', str_replace(DEDEROOT, '', $filename)) == $remotedir)
                            {
                                //如果这些文件都在同一目录,则统计这些记录的id项目
                                $remotebox[] = $key;
                            }
                        }
                        //print_r($remotebox);
                        //if(count($remotebox) > 1 && count($remotebox) < 20)
                        if(count($remotebox) > 1)
                        {
                            //如果大于1,则说明有多条记录在同一文件夹内
                            $localdir = '..'.$remotedir;
                            if( $ftp->mirror($localdir, $remotedir))
                            {
                                $sta = end($remotebox) + 1;
                                ShowMsg("成功同步文件夹$remotedir,进入下一个任务","makeremote_all.php?dopost=make&step=2&sta={$sta}&totalnum=$totalnum");
                                exit;
                            }
                        } else {
                            if( $ftp->upload($localfile, $remotefile) )
                            {
                                $sta++;
                                ShowMsg("成功同步文件$remotefile,进入下一个任务","makeremote_all.php?dopost=make&step=2&sta={$sta}&totalnum=$totalnum");
                                exit;
                            }
                        }
                    }
                }
            } else {
                //否则成功更新完毕
                foreach($remotefile as $key => $val)
                {
                    if(in_array($val['filedir'],array_values($upremote)))
                    {
                        $remotefile[$key]['state'] = 1;
                    }
                }
                updateConfig($remotefile);
                @unlink(DEDEDATA.'/cache/filelist.inc.php');
                echo '全部同步完毕!';exit;
            }
        } else {
            exit('Error:None remote cache file exist!');
        }
        exit;
    }
}
include DedeInclude('templets/makeremote_all.htm');