<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-14
 * Time: AM10:59
 */

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Think\Db;
use OT\Database;

class UpdateController extends AdminController
{
    protected $pack_sql_dir = 'QuickPacks/sqls';
    protected $mPackPath = 'QuickPacks/info';


    /*OneWX二次开发*/
    private function read_file($filename)
    {
        $db = '';
        if (!$file = fopen($filename, "r")) {
            $db = array();
        } else {
            //整读文件
            while (!feof($file)) {
                $db .= fgets($file);
            }
            fclose($file);
        }
        return $db;
    }

    public function all()
    {
        $db = $this->read_file($this->pack_db_path);
        $db = json_decode($db);
        $db = $this->toArray($db);
        foreach ($db['packs'] as &$pack) {
            $file = $this->pack_sql_dir . '/' . $pack['title'] . '.sql';
            $pack['mtime'] = date('Y-m-d H:i:s', $pack['mtime']);
            $pack['size'] = filesize($file) . ' bytes';
        }
        unset($pack);
        $this->assign('db', $db);
        $title = L('_FAST_OPERATION_'); //渲染模板
        $this->assign('meta_title', $title);
        $this->display();
    }

    public function quick()
    {

        $files = $this->getFile($this->mPackPath);
        $list = array();
        foreach ($files as $f) {
            $info = $this->toArray(json_decode($this->read_file($this->mPackPath . '/' . $f)));
            if(!$info){
                continue;
            }
            $file = $this->mPackPath . '/' . $info['ctime'] . '.sql';
            $info['file'] = $info['ctime'] . '.sql';
            $info['id'] = $info['ctime'];
            $info['ctime'] = friendlyDate($info['ctime']);
            $size=filesize($this->pack_sql_dir . '/' . $info['id'].'.sql' );
            if($size/1024>1){
                $info['size'] = number_format(($size*1.0/1024/1024),2) . 'MB';
            }else{
                $info['size'] = number_format(($size*1.0/1024),2) . 'KB';
            }

            if ($info['mtime'] != 0)
                $info['mtime'] = friendlyDate($info['mtime']);
            $list[] = $info;

        }
        /*        $listBuilder = new AdminListBuilder();*/
        /*      $listBuilder->keyText('title', L('_TITLE_'))->keyText('des', L('_INTRODUCTION_'))->keyText('author', L('_AUTHOR_'))->keyText('file', L('_SQL_FILE_NAME_'))->keyText('size', L('_SQL_SIZE_'))->keyText('ctime', L('_CREATE_TIME_'))->keyText('mtime', L('_CHANGE_TIME_'))
                  ->keyDoActionEdit('update/addpack?id=###', L('_EDIT_'));*/
        /*  $listBuilder->data($list);
          $listBuilder->display();
          dump($list);
          exit;*/

        /*
                dump($fiels);
                exit;

                $db = $this->read_file($this->pack_db_path);
                $db = json_decode($db);
                $db = $this->toArray($db);
                foreach ($db['packs'] as &$pack) {
                    $file = $this->pack_sql_dir . '/' . $pack['title'] . '.sql';
                    $pack['mtime'] = date('Y-m-d H:i:s', $pack['mtime']);
                    $pack['size'] = filesize($file) . ' bytes';
                }
                unset($pack);*/
        $this->assign('list', $list);
        $title = L('_FAST_OPERATION_'); //渲染模板
        $this->assign('meta_title', $title);
        $this->display();
    }

    public function view($title = '')
    {
        if (IS_POST) {
            if ($title == '') {
                exit;
            }
            exit($this->read_file($this->pack_sql_dir . "/{$title}.sql"));
        }
    }

    public function del_pack()
    {
        $title = trim($_GET['id']);
        if ($_GET['id']) {
            $myfile = $this->pack_sql_dir . "/{$title}.sql";
            $jsonFile = $this->mPackPath . '/' . $title . '.json';
            $result = unlink($myfile) || unlink($jsonFile);

            if ($result) {
                $this->success(L('_DELETE_FILE_SUCCESSFULLY_'));
                exit;
            } else {
                $this->error(L('_DELETE_FILE_FAILED_'));
            }
        } else {
            $this->error(L('_NO_CHOICE_PATCH_'));
        }


    }


    /**
     * 新增补丁
     * @author 奕潇 <yixiao2020@qq.com>
     */
    public function addpack($title_old = '', $title = '', $sql = '', $des = '', $author = '')
    {

        if (IS_POST) {
            $aId = I('post.id', 0, 'intval');
            if ($aId != 0) {
                //编辑逻辑，取到原有数据
                $info = $this->getJsonFile($aId);
            }
            //dump($this->mPackPath . '/' . $aId . '.json');exit;
            $aTitle = I('post.title');
            $aDes = I('post.des');
            $aAuthor = I('post.author');
            $aSql = I('post.sql');
            if ($aSql == '') {
                $this->error(L('_SQL_STATEMENTS_MUST_BE_FILLED_OUT_'));
            }
            $info['title'] = $aTitle;
            $info['des'] = $aDes;
            $info['author'] = $aAuthor;
            if ($aId == 0) {
                //新增逻辑
                $time = time();
                if ($title == '')
                    $title = $time;
                $info['title'] = $title;
                $fh = $this->writeSql($sql, $time);

                $info['ctime'] = time();
                $info['mtime'] = '0';
                $fh = $this->writeJsonFile($time, $info);
                $this->success(L('_NEW_PATCH_SUCCESS_'));

            } else {
                $info['mtime'] = time();
                //打开文件
                $this->writeJsonFile($aId, $info);
                fclose($fh);
                $this->writeSql($aSql, $aId);
                $this->success(L('_EDIT_PATCH_SUCCESS_'));
                exit;
            }
        } else {
            $aId = I('get.id', 0, 'intval');
            if ($aId != 0) {
                $info = $this->getJsonFile($aId);
                $info['sql'] = $this->read_file($this->pack_sql_dir . '/' . $aId . '.sql');
            }

            $formBuilder = new AdminConfigBuilder();
            $formBuilder->title(L('_NEW_PATCH_'))->keyText('title', L('_PATCH_NAME_'))->keyTextArea('des', L('_INTRODUCTION_'))->keyTextArea('sql', L('_SQL_STATEMENT_'))->keyText('author', L('_AUTHOR_'))
                ->buttonSubmit();
            if ($aId != 0) {
                $info['id'] = $aId;
                $formBuilder->keyHidden('id');
            }
            $formBuilder->data($info);
            $formBuilder->display();
        }
    }


    public function use_pack($id = '')
    {
        if (IS_GET && $id != '') {


            //  $db = new Database(array('', $this->pack_sql_dir . "/{$title}.sql"), array(), 'import');
            $error = D('')->executeSqlFile($this->pack_sql_dir . "/{$id}.sql");
            if ($error['error_code'] != '') {
                $this->error($error['error_code']);
                exit;
            } else {
                clean_all_cache();
                $this->success(L('_USING_THE_PATCH_TO_SUCCEED_'));
            }
        } else {
            $this->error(L('_PLEASE_SELECT_THE_PATCH_'));
        }
    }

    /*OneWX二次开发end*/
    /**
     * 获取文件列表
     */
    private function getFile($folder)
    {
        //打开目录
        $fp = opendir($folder);
        //阅读目录
        while (false != $file = readdir($fp)) {
            //列出所有文件并去掉'.'和'..'
            if ($file != '.' && $file != '..') {
                //$file="$folder/$file";
                $file = "$file";

                //赋值给数组
                $arr_file[] = $file;

            }
        }
        //输出结果
        if (is_array($arr_file)) {
            while (list($key, $value) = each($arr_file)) {
                $files[] = $value;
            }
        }
        //关闭目录
        closedir($fp);
        return $files;


    }

    /**
     * @param $sql
     * @param $time
     * @return resource
     * @auth 陈一枭
     */
    private function writeSql($sql, $time)
    {
//打开文件
        if (!$fh = fopen($this->pack_sql_dir . '/' . $time . '.sql', 'w')) {
            $this->error(L('_CANNOT_TO_CREATE_')." " . $this->pack_sql_dir . '/' . $time);
            exit;
        }
        // 写入内容
        if (fwrite($fh, $sql) === FALSE) {
            $this->error(L('_CANNOT_WRITE_TO_FILE_') . $this->pack_sql_dir . '/' . $time);
            exit;
        }
        return $fh;
    }

    /**
     * @param $aId
     * @return mixed
     * @auth 陈一枭
     */
    private function getJsonFile($aId)
    {
        return $this->toArray(json_decode($this->read_file($this->mPackPath . '/' . $aId . '.json')));
    }

    /**json转换为数组
     * @param $stdclassobject
     * @return mixed
     */
    private function toArray($stdclassobject)
    {

        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;

        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? $this->toArray($value) : $value;
            $array[$key] = $value;
        }

        return $array;

    }

    /**
     * @param $time
     * @param $info
     * @return resource
     * @auth 陈一枭
     */
    private function writeJsonFile($time, $info)
    {
//打开文件
        if (!$fh = fopen($this->mPackPath . '/' . $time . '.json', 'w')) {
            $this->error(L('_CANNOT_TO_OPEN_')." $this->mPackPath" . '/' . $time . '.json');
            exit;
        }
        // 写入内容
        if (fwrite($fh, json_encode($info)) === FALSE) {
            $this->error(L('_CANNOT_TO_WRITE_')." $this->mPackPath" . '/' . $time . '.json');
            exit;
        }
        fclose($fh);
        return $fh;
    }

}