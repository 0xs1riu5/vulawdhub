<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: wangleisvn $
 * $Id: lead.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH."includes/cls_certificate.php");
$uri = $ecs->url();
$allow_suffix = array('gif', 'jpg', 'png', 'jpeg', 'bmp');

/*------------------------------------------------------ */
//-- 移动端应用配置
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'list')
{
    /* 检查权限 */
    admin_priv('h5_setting');
    $smarty->assign('ur_here', $_LANG['h5_setting']);
    $auth_sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = "authorize"';
    $auth = $GLOBALS['db']->getRow($auth_sql);
    $params = unserialize($auth['value']);
    if($params['authorize_code'] != 'NDE'){
        $url = $params['authorize_code']=='NCH'?'http://account.shopex.cn/order/confirm/goods_2460-946 ':'https://account.shopex.cn/order/confirm/goods_2540-1050 ';
        $smarty->assign('url', $url);
        $smarty->display('accredit.html');
        exit;
    }
    $cert = new certificate;
    $isOpenWap = $cert->is_open_sn('fy');
    if($isOpenWap==false && $_SESSION['yunqi_login'] && $_SESSION['TOKEN'] ){
        $result = $cert->getsnlistoauth($_SESSION['TOKEN'] ,array());
        if($result['status']=='success'){
            $cert->save_snlist($result['data']);
            $isOpenWap = $cert->is_open_sn('fy');
        }
    }
    $tab = !$isOpenWap ? 'open' : 'enter';
    $charset = EC_CHARSET == 'utf-8' ? "utf8" : 'gbk';
    $sql =  "SELECT * FROM " . $ecs->table('config')." WHERE 1";
    $group_items = $db->getAll($sql);
    $grouplist = get_params();
    foreach($grouplist as $key => $value){
        foreach($value['items'] as $k => $v){
            foreach($group_items as $item){
                if($item['code'] == $v['code']){
                    $config = json_decode($item['config'],1);
                    foreach($v['vars'] as $var_k => $var_v){
                        $grouplist[$key]['items'][$k]['vars'][$var_k]['value'] =$config[$var_v['code']];
                    }
                }
            }

        }
    }

    $playerdb = get_flash_xml();
    $num = 0;
    foreach ($playerdb as $key => $val)
    {
        $playerdb[$key]['id'] = $num;
        $num++;
        if (strpos($val['src'], 'http') === false)
        {
            $playerdb[$key]['src'] = $uri . $val['src'];
        }
    }

    assign_query_info();
    $flash_dir = ROOT_PATH . 'data/flashdata/';

    $smarty->assign('playerdb', $playerdb);
    $smarty->assign('group_list',$grouplist);
    $smarty->display('h5_config.html');
}elseif($_REQUEST['act']== 'post'){
    /* 检查权限 */
    admin_priv('mobile_setting');
    $links[] = array('text' => $_LANG['h5_setting'], 'href' => 'h5_setting.php?act=list');

    foreach($_POST['value'] as $key => $value){
        $_POST['value'][$key] = trim($value);
    }
    if(!empty($_FILES['value']['name'])){
        foreach($_FILES['value']['name'] as $k => $v){
            if($v){
                $cert = $_FILES['value']['tmp_name']['cert'];
                $PSize = filesize($cert);
                $cert_steam = (fread(fopen($cert, "r"), $PSize));
                $cert_steam = addslashes($cert_steam);
                $_POST['value']['cert'] =  $_FILES['value']['name']['cert'];
            }else{
                sys_msg('证书不能为空', 1, $links);
            }
        }
    }
    $sql = "SELECT * FROM " . $ecs->table('config')." WHERE `code` = '".$_POST['code']."'";
    $res = $db->getRow($sql);
    $items = get_items($_POST['code']);

    $type = $items['type'];
    $name = $items['name'];
    $code = $items['code'];
    $description = $items['description'];

    //微信支付获取微信登录里的app_id/app_secret
    if($code == 'wxpay.web'){
        $sql = "SELECT * FROM " . $ecs->table('config')." WHERE `code` = 'wechat.web'";
        $result = $db->getRow($sql);
        if($result){
            $wechat_config = json_decode($result['config'],1);
            $_POST['value']['app_id'] = $wechat_config['app_id']?$wechat_config['app_id']:'';
            $_POST['value']['app_secret'] = $wechat_config['app_secret']?$wechat_config['app_secret']:'';
        }
    }elseif($code == 'wechat.web'){
        $sql =  $sql = "SELECT * FROM " . $ecs->table('config')." WHERE `code` = 'wxpay.web'";
        $result = $db->getRow($sql);
        if($result){
            $wechat_config = json_decode($result['config'],1);
            $wechat_config['app_id'] = $_POST['value']['app_id']?$_POST['value']['app_id']:'';
            $wechat_config['app_secret'] = $_POST['value']['app_secret']?$_POST['value']['app_secret']:'';
            $sql = "UPDATE ".$ecs->table('config')." SET `config` = '".json_encode($wechat_config)."' WHERE `code` = 'wxpay.web'";
            $db->query($sql);
        }
    }

    $config = json_encode($_POST['value']);
    $status = $_POST['value']['status'];
    $time = date('Y-m-d H:i:s',time());

    if($res){
        $sql = "UPDATE ".$ecs->table('config')." SET `updated_at` = '$time',`status` = '$status' ,`config` = '".json_encode($_POST['value'])."' WHERE `code` = '$code'";
    }else{
        $sql = "INSERT INTO ".$ecs->table('config')." (`name`,`type`,`description`,`code`,`config`,`created_at`,`updated_at`,`status`) VALUES ('$name','$type','$description','$code','$config','$time','$time','$status')";
    }
    $db->query($sql);

    if($cert_steam){
        //处理文件
        $sql = "SELECT * FROM " . $ecs->table('config')." WHERE `code` = '".$_POST['code']."'";
        $setting = $db->getRow($sql);
        if($setting['id']){
            $id = $setting['id'];
            $cert_tmp = $db->getRow("SELECT * FROM " . $ecs->table('cert')." WHERE `config_id` = '$id'");
            if($cert_tmp){
                $db->query("UPDATE ".$ecs->table('cert')." SET `file` = '$cert_steam' WHERE `config_id` = '$id'");
            }else{
                $db->query("INSERT INTO ".$ecs->table('cert')." (`config_id`,`file`) VALUES ($id,'$cert_steam')");
            }
        }
    }

    sys_msg($_LANG['attradd_succed'], 0, $links);
} elseif($_REQUEST['act']== 'del') {
    admin_priv('flash_manage');

    $id = (int)$_GET['id'];
    $flashdb = get_flash_xml();
    if (isset($flashdb[$id]))
    {
        $rt = $flashdb[$id];
    }
    else
    {
        $links[] = array('text' => 'H5配置', 'href' => 'h5_setting.php?act=list');
//        $links[] = array('text' => $_LANG['go_url'], 'href' => 'h5_setting.php?act=list');
        sys_msg($_LANG['id_error'], 0, $links);
    }

    if (strpos($rt['src'], 'http') === false)
    {
        @unlink(ROOT_PATH . $rt['src']);
    }
    $temp = array();
    foreach ($flashdb as $key => $val)
    {
        if ($key != $id)
        {
            $temp[] = $val;
        }
    }
    put_flash_xml($temp);
    $error_msg = '';
    set_flash_data($_CFG['flash_theme'], $error_msg);
    ecs_header("Location: h5_setting.php?act=list\n");
    exit;
}
elseif ($_REQUEST['act'] == 'add')
{
    admin_priv('flash_manage');

    if (empty($_POST['step']))
    {
        $url = isset($_GET['url']) ? $_GET['url'] : 'http://';
        $src = isset($_GET['src']) ? $_GET['src'] : '';
        $sort = 0;
        $rt = array('act'=>'add','img_url'=>$url,'img_src'=>$src, 'img_sort'=>$sort);
        $rt = array('act'=>'add','img_url'=>$url,'img_src'=>$src, 'img_sort'=>$sort);
        $width_height = get_width_height();
        assign_query_info();
        if(isset($width_height['width'])|| isset($width_height['height']))
        {
            $smarty->assign('width_height', sprintf($_LANG['width_height'], $width_height['width'], $width_height['height']));
        }

        $smarty->assign('action_link', array('text' => $_LANG['go_url'], 'href' => 'h5_setting.php?act=list'));
        $smarty->assign('rt', $rt);
        $smarty->assign('ur_here', $_LANG['add_picad']);
        $smarty->display('flashplay_add.htm');
    }
    elseif ($_POST['step'] == 2)
    {
        if (!empty($_FILES['img_file_src']['name']))
        {
            if(!get_file_suffix($_FILES['img_file_src']['name'], $allow_suffix))
            {
                sys_msg($_LANG['invalid_type']);
            }
            $name = date('Ymd');
            for ($i = 0; $i < 6; $i++)
            {
                $name .= chr(mt_rand(97, 122));
            }
            $img_file_src_name_arr = explode('.', $_FILES['img_file_src']['name']);
            $name .= '.' . end($img_file_src_name_arr);
            $target = ROOT_PATH . DATA_DIR . '/afficheimg/' . $name;
            if (move_upload_file($_FILES['img_file_src']['tmp_name'], $target))
            {
                $src = DATA_DIR . '/afficheimg/' . $name;
            }
        }
        elseif (!empty($_POST['img_src']))
        {
            if(!get_file_suffix($_POST['img_src'], $allow_suffix))
            {
                sys_msg($_LANG['invalid_type']);
            }
            $src = $_POST['img_src'];
            if(strstr($src, 'http') && !strstr($src, $_SERVER['SERVER_NAME']))
            {
                $src = get_url_image($src);
            }
        }
        else
        {
            $links[] = array('text' => $_LANG['add_new'], 'href' => 'h5_setting.php?act=add');
            sys_msg($_LANG['src_empty'], 0, $links);
        }

        if (empty($_POST['img_url']))
        {
            $links[] = array('text' => $_LANG['add_new'], 'href' => 'h5_setting.php?act=add');
            sys_msg($_LANG['link_empty'], 0, $links);
        }

        // 获取flash播放器数据
        $flashdb = get_flash_xml();

        // 插入新数据
        array_unshift($flashdb, array('src'=>$src, 'url'=>$_POST['img_url'], 'text'=>$_POST['img_text'] ,'sort'=>$_POST['img_sort']));

        // 实现排序
        $flashdb_sort   = array();
        $_flashdb       = array();
        foreach ($flashdb as $key => $value)
        {
            $flashdb_sort[$key] = $value['sort'];
        }
        asort($flashdb_sort, SORT_NUMERIC);
        foreach ($flashdb_sort as $key => $value)
        {
            $_flashdb[] = $flashdb[$key];
        }
        unset($flashdb, $flashdb_sort);

        put_flash_xml($_flashdb);
        $error_msg = '';
        set_flash_data($_CFG['flash_theme'], $error_msg);
        $links[] = array('text' => $_LANG['go_url'], 'href' => 'h5_setting.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('flash_manage');

    $id = (int)$_REQUEST['id']; //取得id
    $flashdb = get_flash_xml(); //取得数据
    if (isset($flashdb[$id]))
    {
        $rt = $flashdb[$id];
    }
    else
    {
        $links[] = array('text' => $_LANG['go_url'], 'href' => 'h5_setting.php?act=list');
        sys_msg($_LANG['id_error'], 0, $links);
    }
    if (empty($_POST['step']))
    {
        $rt['act'] = 'edit';
        $rt['img_url'] = $rt['url'];
        $rt['img_src'] = $rt['src'];
        $rt['img_txt'] = $rt['text'];
        $rt['img_sort'] = empty($rt['sort']) ? 0 : $rt['sort'];

        $rt['id'] = $id;
        $smarty->assign('action_link', array('text' => $_LANG['go_url'], 'href' => 'h5_setting.php?act=list'));
        $smarty->assign('rt', $rt);
        $smarty->assign('ur_here', $_LANG['edit_picad']);
        $smarty->display('flashplay_add.htm');
    }
    elseif ($_POST['step'] == 2)
    {
        if (empty($_POST['img_url']))
        {
            //若链接地址为空
            $links[] = array('text' => $_LANG['return_edit'], 'href' => 'h5_setting.php?act=edit&id=' . $id);
            sys_msg($_LANG['link_empty'], 0, $links);
        }

        if (!empty($_FILES['img_file_src']['name']))
        {
            if(!get_file_suffix($_FILES['img_file_src']['name'], $allow_suffix))
            {
                sys_msg($_LANG['invalid_type']);
            }
            //有上传
            $name = date('Ymd');
            for ($i = 0; $i < 6; $i++)
            {
                $name .= chr(mt_rand(97, 122));
            }
            $img_file_src_name_arr = explode('.', $_FILES['img_file_src']['name']);
            $name .= '.' . end($img_file_src_name_arr);
            $target = ROOT_PATH . DATA_DIR . '/afficheimg/' . $name;

            if (move_upload_file($_FILES['img_file_src']['tmp_name'], $target))
            {
                $src = DATA_DIR . '/afficheimg/' . $name;
            }
        }
        else if (!empty($_POST['img_src']))
        {
            $src =$_POST['img_src'];
            if(!get_file_suffix($_POST['img_src'], $allow_suffix))
            {
                sys_msg($_LANG['invalid_type']);
            }
            if(strstr($src, 'http') && !strstr($src, $_SERVER['SERVER_NAME']))
            {
                $src = get_url_image($src);
            }
        }
        else
        {
            $links[] = array('text' => $_LANG['return_edit'], 'href' => 'h5_setting.php?act=edit&id=' . $id);
            sys_msg($_LANG['src_empty'], 0, $links);
        }

        if (strpos($rt['src'], 'http') === false && $rt['src'] != $src)
        {
            @unlink(ROOT_PATH . $rt['src']);
        }
        $flashdb[$id] = array('src'=>$src,'url'=>$_POST['img_url'],'text'=>$_POST['img_text'],'sort'=>$_POST['img_sort']);

        // 实现排序
        $flashdb_sort   = array();
        $_flashdb       = array();
        foreach ($flashdb as $key => $value)
        {
            $flashdb_sort[$key] = $value['sort'];
        }
        asort($flashdb_sort, SORT_NUMERIC);
        foreach ($flashdb_sort as $key => $value)
        {
            $_flashdb[] = $flashdb[$key];
        }
        unset($flashdb, $flashdb_sort);

        put_flash_xml($_flashdb);
        $error_msg = '';
        set_flash_data($_CFG['flash_theme'], $error_msg);
        $links[] = array('text' => $_LANG['go_url'], 'href' => 'h5_setting.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}

function get_flash_xml()
{
    $flashdb = array();
    if (file_exists(ROOT_PATH . DATA_DIR . '/flash_data.xml'))
    {

        // 兼容v2.7.0及以前版本
        if (!preg_match_all('/item_url="([^"]+)"\slink="([^"]+)"\stext="([^"]*)"\ssort="([^"]*)"/', file_get_contents(ROOT_PATH . DATA_DIR . '/flash_data.xml'), $t, PREG_SET_ORDER))
        {
            preg_match_all('/item_url="([^"]+)"\slink="([^"]+)"\stext="([^"]*)"/', file_get_contents(ROOT_PATH . DATA_DIR . '/flash_data.xml'), $t, PREG_SET_ORDER);
        }

        if (!empty($t))
        {
            foreach ($t as $key => $val)
            {
                $val[4] = isset($val[4]) ? $val[4] : 0;
                $flashdb[] = array('src'=>$val[1],'url'=>$val[2],'text'=>$val[3],'sort'=>$val[4]);
            }
        }
    }
    return $flashdb;
}

function put_flash_xml($flashdb)
{
    if (!empty($flashdb))
    {
        $xml = '<?xml version="1.0" encoding="' . EC_CHARSET . '"?><bcaster>';
        foreach ($flashdb as $key => $val)
        {
            $xml .= '<item item_url="' . $val['src'] . '" link="' . $val['url'] . '" text="' . $val['text'] . '" sort="' . $val['sort'] . '"/>';
        }
        $xml .= '</bcaster>';
        file_put_contents(ROOT_PATH . DATA_DIR . '/flash_data.xml', $xml);
    }
    else
    {
        @unlink(ROOT_PATH . DATA_DIR . '/flash_data.xml');
    }
}

function get_url_image($url)
{
    $url_arr = explode('.', $url);
    $ext = strtolower(end($url_arr));
    if($ext != "gif" && $ext != "jpg" && $ext != "png" && $ext != "bmp" && $ext != "jpeg")
    {
        return $url;
    }

    $name = date('Ymd');
    for ($i = 0; $i < 6; $i++)
    {
        $name .= chr(mt_rand(97, 122));
    }
    $name .= '.' . $ext;
    $target = ROOT_PATH . DATA_DIR . '/afficheimg/' . $name;

    $tmp_file = DATA_DIR . '/afficheimg/' . $name;
    $filename = ROOT_PATH . $tmp_file;

    $img = file_get_contents($url);

    $fp = @fopen($filename, "a");
    fwrite($fp, $img);
    fclose($fp);

    return $tmp_file;
}

function get_width_height()
{
    $curr_template = $GLOBALS['_CFG']['template'];
    $path = ROOT_PATH . 'themes/' . $curr_template . '/library/';
    $template_dir = @opendir($path);

    $width_height = array();
    while($file = readdir($template_dir))
    {
        if($file == 'index_ad.lbi')
        {
            $string = file_get_contents($path . $file);
            $pattern_width = '/var\s*swf_width\s*=\s*(\d+);/';
            $pattern_height = '/var\s*swf_height\s*=\s*(\d+);/';
            preg_match($pattern_width, $string, $width);
            preg_match($pattern_height, $string, $height);
            if(isset($width[1]))
            {
                $width_height['width'] = $width[1];
            }
            if(isset($height[1]))
            {
                $width_height['height'] = $height[1];
            }
            break;
        }
    }

    return $width_height;
}

function get_flash_templates($dir)
{
    $flashtpls = array();
    $template_dir        = @opendir($dir);
    while ($file = readdir($template_dir))
    {
        if ($file != '.' && $file != '..' && is_dir($dir . $file) && $file != '.svn' && $file != 'index.htm')
        {
            $flashtpls[] = get_flash_tpl_info($dir, $file);
        }
    }
    @closedir($template_dir);
    return $flashtpls;
}

function get_flash_tpl_info($dir, $file)
{
    $info = array();
    if (is_file($dir . $file . '/preview.jpg'))
    {
        $info['code'] = $file;
        $info['screenshot'] = '../data/flashdata/' . $file . '/preview.jpg';
        $arr = array_slice(file($dir . $file . '/cycle_image.js'), 1, 2);
        $info_name = explode(':', $arr[0]);
        $info_desc = explode(':', $arr[1]);
        $info['name'] = isset($info_name[1])?trim($info_name[1]):'';
        $info['desc'] = isset($info_desc[1])?trim($info_desc[1]):'';
    }
    return $info;
}

function set_flash_data($tplname, &$msg)
{
    $flashdata = get_flash_xml();
    if (empty($flashdata))
    {
        $flashdata[] = array(
            'src' => 'data/afficheimg/20081027angsif.jpg',
            'text' => 'ECShop',
            'url' =>'http://www.ecshop.com'
        );
        $flashdata[] = array(
            'src' => 'data/afficheimg/20081027wdwd.jpg',
            'text' => 'wdwd',
            'url' =>'http://www.wdwd.com'
        );
        $flashdata[] = array(
            'src' => 'data/afficheimg/20081027xuorxj.jpg',
            'text' => 'ECShop',
            'url' =>'http://help.ecshop.com/index.php?doc-view-108.htm'
        );
    }
    switch($tplname)
    {
        case 'uproll':
            $msg = set_flash_uproll($tplname, $flashdata);
            break;
        case 'redfocus':
        case 'pinkfocus':
        case 'dynfocus':
            $msg = set_flash_focus($tplname, $flashdata);
            break;
        case 'default':
        default:
            $msg = set_flash_default($tplname, $flashdata);
            break;
    }
    return $msg !== true;
}

function set_flash_uproll($tplname, $flashdata)
{
    $data_file = ROOT_PATH . DATA_DIR . '/flashdata/' . $tplname . '/data.xml';
    $xmldata = '<?xml version="1.0" encoding="' . EC_CHARSET . '"?><myMenu>';
    foreach ($flashdata as $data)
    {
        $xmldata .= '<myItem pic="' . $data['src'] . '" url="' . $data['url'] . '" />';
    }
    $xmldata .= '</myMenu>';
    file_put_contents($data_file, $xmldata);
    return true;
}

function set_flash_focus($tplname, $flashdata)
{
    $data_file = ROOT_PATH . DATA_DIR . '/flashdata/' . $tplname . '/data.js';
    $jsdata = '';
    $jsdata2 = array('url' => 'var pics=', 'txt' => 'var texts=', 'link' => 'var links=');
    $count = 1;
    $join = '';
    foreach ($flashdata as $data)
    {
        $jsdata .= 'imgUrl' . $count . '="' . $data['src'] . '";' . "\n";
        $jsdata .= 'imgtext' . $count . '="' . $data['text'] . '";' . "\n";
        $jsdata .= 'imgLink' . $count . '=escape("' . $data['url'] . '");' . "\n";
        if ($count != 1)
        {
            $join = '+"|"+';
        }
        $jsdata2['url'] .= $join . 'imgUrl' . $count;
        $jsdata2['txt'] .= $join . 'imgtext' . $count;
        $jsdata2['link'] .= $join . 'imgLink' . $count;
        ++$count;
    }
    file_put_contents($data_file, $jsdata . "\n" . $jsdata2['url'] . ";\n" . $jsdata2['link'] . ";\n" . $jsdata2['txt'] . ";");
    return true;
}

function set_flash_default($tplname, $flashdata)
{
    $data_file = ROOT_PATH . DATA_DIR . '/flashdata/' . $tplname . '/data.xml';
    $xmldata = '<?xml version="1.0" encoding="' . EC_CHARSET . '"?><bcaster>';
    foreach ($flashdata as $data)
    {
        $xmldata .= '<item item_url="' . $data['src'] . '" link="' . $data['url'] . '" />';
    }
    $xmldata .= '</bcaster>';
    file_put_contents($data_file, $xmldata);
    return true;
}

/**
 *  获取用户自定义广告列表信息
 *
 * @access  public
 * @param
 *
 * @return void
 */
function ad_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;
        $filter = array();
        $filter['sort_by'] = 'add_time';
        $filter['sort_order'] = 'DESC';

        /* 过滤信息 */
        $where = 'WHERE 1 ';

        /* 查询 */
        $sql = "SELECT ad_id, CASE WHEN ad_type = 0 THEN '图片'
                                   WHEN ad_type = 1 THEN 'Flash'
                                   WHEN ad_type = 2 THEN '代码'
                                   WHEN ad_type = 3 THEN '文字'
                                   ELSE '' END AS type_name, ad_name, add_time, CASE WHEN ad_status = 1 THEN '启用' ELSE '关闭' END AS status_name, ad_type, ad_status
                FROM " . $GLOBALS['ecs']->table("ad_custom") . "
                $where
                ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order']. " ";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $row = $GLOBALS['db']->getAll($sql);

    /* 格式化数据 */
    foreach ($row AS $key => $value)
    {
        $row[$key]['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $value['add_time']);
    }

    $arr = array('ad' => $row, 'filter' => $filter);

    return $arr;
}

/**
 * 修改自定义相状态
 *
 * @param   int     $ad_id       自定义广告 id
 * @param   int     $ad_status   自定义广告 状态 0，关闭；1，开启。
 * @access  private
 * @return  Bool
 */
 function modfiy_ad_status($ad_id, $ad_status = 0)
 {
     $return = false;

     if (empty($ad_id))
     {
         return $return;
     }

     /* 查询自定义广告信息 */
     $sql = "SELECT ad_type, content, url, ad_status FROM " . $GLOBALS['ecs']->table("ad_custom") . " WHERE ad_id = $ad_id LIMIT 0, 1";
     $ad = $GLOBALS['db']->getRow($sql);

     if ($ad_status == 1)
     {
         /* 如果当前自定义广告是关闭状态 则修改其状态为启用 */
         if ($ad['ad_status'] == 0)
         {
             $sql = "UPDATE " . $GLOBALS['ecs']->table("ad_custom") . " SET ad_status = 1 WHERE ad_id = $ad_id";
             $GLOBALS['db']->query($sql);
         }

         /* 关闭 其它自定义广告 */
         $sql = "UPDATE " . $GLOBALS['ecs']->table("ad_custom") . " SET ad_status = 0 WHERE ad_id <> $ad_id";
         $GLOBALS['db']->query($sql);

         /* 用户自定义广告开启 */
         $sql = "UPDATE " . $GLOBALS['ecs']->table("shop_config") . " SET value = 'cus' WHERE id =337";
         $GLOBALS['db']->query($sql);
     }
     else
     {
         /* 如果当前自定义广告是关闭状态 则检查是否存在启用的自定义广告 */
         /* 如果无 则启用系统默认广告播放器 */
         if ($ad['ad_status'] == 0)
         {
             $sql = "SELECT COUNT(ad_id) FROM " . $GLOBALS['ecs']->table("ad_custom") . " WHERE ad_status = 1";
             $ad_status_1 = $GLOBALS['db']->getOne($sql);
             if (empty($ad_status_1))
             {
                 $sql = "UPDATE " . $GLOBALS['ecs']->table("shop_config") . " SET value = 'sys' WHERE id =337";
                 $GLOBALS['db']->query($sql);
             }
             else
             {
                 $sql = "UPDATE " . $GLOBALS['ecs']->table("shop_config") . " SET value = 'cus' WHERE id =337";
                 $GLOBALS['db']->query($sql);
             }
         }
         else
         {
             /* 当前自定义广告是开启状态 关闭之 */
             /* 如果无 则启用系统默认广告播放器 */
             $sql = "UPDATE " . $GLOBALS['ecs']->table("ad_custom") . " SET ad_status = 0 WHERE ad_id = $ad_id";
             $GLOBALS['db']->query($sql);

             $sql = "UPDATE " . $GLOBALS['ecs']->table("shop_config") . " SET value = 'sys' WHERE id =337";
             $GLOBALS['db']->query($sql);
         }
     }

     return $return = true;
 }

function get_items($code){
    $params = get_params();
    foreach($params as $value){
        foreach($value['items'] as $val){
            if($val['code'] == $code)return $val;
        }
    }
}

function get_params(){

    $grouplist = array(
        0 => array(
            'name' => '社交配置',
            'code' => 'ouath',
            'items' => array(
                0 => array(
                    'title' => 'Wechat - 微信登录（用于H5）',
                    'submit' => '?act=post',
                    'url' => 'https://mp.weixin.qq.com',
                    'type' => 'oauth',
                    'name' => '微信登录',
                    'description' => '微信登录',
                    'code' => 'wechat.web',
                    'vars' => array(
                        0 => array(
                            'type' => 'radio',
                            'name' => '是否开启',
                            'code' => 'status',
                            'value' => '',
                        ),
                        1 => array(
                            'type' => 'text',
                            'name' => 'APP ID',
                            'code' => 'app_id',
                            'value' => '',
                        ),
                        2 => array(
                            'type' => 'text',
                            'name' => 'APP Secret',
                            'code' => 'app_secret',
                            'value' => '',
                        ),
                    )
                ),
            ),
        ),
        1 => array(
            'name' => '支付配置',
            'code' => 'payment',
            'items' => array(
                0 => array(
                    'title' => 'Wechat - 微信公众号支付（用于H5）',
                    'submit' => '?act=post',
                    'url' => 'https://pay.weixin.qq.com',
                    'type' => 'payment',
                    'name' => '微信公众号支付',
                    'description' => '微信公众号支付',
                    'code' => 'wxpay.web',
                    'vars' => array(
                        0 => array(
                            'type' => 'radio',
                            'name' => '是否开启',
                            'code' => 'status',
                            'value' => '',
                        ),
                        1 => array(
                            'type' => 'text',
                            'name' => 'MCH ID',
                            'code' => 'mch_id',
                            'value' => '',
                        ),
                        2 => array(
                            'type' => 'text',
                            'name' => 'MCH Key',
                            'code' => 'mch_key',
                            'value' => '',
                        ),
                    )
                ),
            ),
        ),
        2 => array(
            'name' => '支付宝wap支付',
            'code' => 'alipaywap',
            'items' => array(
                0 => array(
                    'title' => '支付宝手机网站支付',
                    'submit' => '?act=post',
                    'url' => '',
                    'type' => 'payment',
                    'name' => '支付宝手机网站支付',
                    'description' => '支付宝支付',
                    'code' => 'alipay.wap',
                    'vars' => array(
                        0 => array(
                            'type' => 'radio',
                            'name' => '是否开启',
                            'code' => 'status',
                            'value' => '',
                        ),
                        1 => array(
                            'type' => 'text',
                            'name' => 'Partner_ID',
                            'code' => 'partner_id',
                            'value' => '',
                        ),
                        2 => array(
                            'type' => 'text',
                            'name' => 'Seller_ID',
                            'code' => 'seller_id',
                            'value' => '',
                        ),
                        3 => array(
                            'type' => 'text',
                            'name' => 'Private_Key',
                            'code' => 'private_key',
                            'value' => '',
                        ),
                        4 => array(
                            'type' => 'text',
                            'name' => 'Public_Key',
                            'code' => 'public_key',
                            'value' => '',
                        ),
                        5 => array(
                            'type' => 'text',
                            'name' => 'Cert',
                            'code' => 'cert',
                            'value' => '',
                        ),
                    )
                ),
            ),
        ),
    );
    return $grouplist;
}

?>
