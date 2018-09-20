<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <yangweijiester@gmail.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Upload\Driver\Qiniu\QiniuStorage;

/**
 * 七牛扩展类测试控制器
 * @author yangweijie <yangweijiester@gmail.com>
 */
class QiniuController extends AdminController {

    public function _initialize(){
        parent:: _initialize();
        $config = array(
            'accessKey'=>'xNBzD_m7hoJrb60Qtyomdr2WVJSb4vnO0MkSYCym',
            'secrectKey'=>'g-p3P9vOWrg_WZOd_3OtL4FNTh8xhgBOeEVPJw3Y',
            'bucket'=>'tox-shang',
            'domain'=>'tox-shang.qiniudn.com'
        );
        $this->qiniu = new QiniuStorage($config);
    }

    //获取文件列表
    public function index(){
        $this->meta_title = L('_SEVEN_CATTLE_CLOUD_STORAGE_TEST_');
        $map = array();
        $prefix = trim(I('post.prefix'));
        if($prefix)
            $map['prefix'] = $prefix;
        $list = $this->qiniu->getList($map);
        if(!$list)
            trace($this->qiniu->error);
        $this->assign('qiniu', $this->qiniu);
        $this->assign('_list', $list['items']);
        $this->display();
    }

    public function del(){
        $file = trim(I('file'));
        if($file){
            $result = $this->qiniu->del($file);
            if(false === $result){
                $this->error($this->qiniu->errorStr);
            }else{
                $this->success(L('_DELETE_SUCCESS_'));
            }
        }else{
            $this->error(L('_WRONG_FILE_NAME_'));
        }
    }

    public function dealImage($key){
        $url = $this->qiniu->dealWithType($key, 'img') ;
        redirect($url);
    }

    public function dealDoc($key){
        $url = $this->qiniu->dealWithType($key, 'doc');
        redirect($url);
    }

    public function rename(){
        $key = I('get.file');
        $new = I('new_name');
        $result = $this->qiniu->rename($key, $new);
        if(false === $result){
            trace($this->qiniu->error);
            $this->error($this->qiniu->errorStr);
        }else{
            $this->success(L('_CHANGE_THE_NAME_'));
        }
    }

    public function batchDel(){
        $files = $_GET['key'];
        if(is_array($files) && $files !== array()){
            $files = array_column($files,'value');
            $result = $this->qiniu->delBatch($files);
            if(false === $result){
                $this->error($this->qiniu->errorStr);
            }else{
                $this->success(L('_DELETE_SUCCESS_'));
            }
        }else{
            $this->error(L('_PLEASE_SELECT_AT_LEAST_ONE_FILE_'));
        }
    }

    public function detail($key){
        $result = $this->qiniu->info($key);
        if($result){
            if(in_array($result['mimeType'], array('image/jpeg','image/png'))){
                $img = "<img src='{$this->qiniu->downlink($key)}?imageView/2/w/203/h/203'>";
            }else{
                $img = '<img class="file-prev" src="https://dn-portal-static.qbox.me/v104/static/theme/default/image/resource/no-prev.png">';
            }
            $time = date('Y-m-d H:i:s', bcmul(substr(strval($result['putTime']), 0, 11),"1000000000"));
            $filesize = format_bytes($result['fsize']);
            $tpl = <<<tpl
            <div class="right-head">
                {$key}
            </div>
            <div class="right-body">
                <div class="right-body-block">
                    <div class="prev-block">
                        {$img}
                    </div>
                    <p class="file-info-item">
                        外链地址：<input class="file-share-link" type="text" readonly="readonly" value="{$this->qiniu->downlink($key)}">
                    </p>
                    <p class="file-info-item">
                        最后更新时间：<span>{$time}</span>
                    </p>
                    <p class="file-info-item">
                        文件大小：<span class="file-size">{$filesize}</span>
                    </p>
                </div>
            </div>
tpl;
            $this->success('as', '', array('tpl'=>$tpl));
        }else{
            $this->error(L('_GET_FILE_INFO_FAILED_'));
        }

    }

    //上传单个文件 用uploadify
    public function uploadOne(){
        $file = $_FILES['qiniu_file'];
        $file = array(
            'name'=>'file',
            'fileName'=>$file['name'],
            'fileBody'=>file_get_contents($file['tmp_name'])
        );
        $config = array();
        $result = $this->qiniu->upload($config, $file);
        if($result){
            $this->success(L('_UPLOAD_SUCCESS_'),'', $result);
        }else{
            $this->error(L('_UPLOAD_FAILED_'),'', array(
                'error'=>$this->qiniu->error,
                'errorStr'=>$this->qiniu->errorStr
            ));
        }
        exit;
    }

}
