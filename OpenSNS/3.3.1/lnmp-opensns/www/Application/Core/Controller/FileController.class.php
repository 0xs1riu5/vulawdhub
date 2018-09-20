<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Core\Controller;
use Think\Controller;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class FileController extends Controller
{
    /* 文件上传 */
    public function upload()
    {
        $return = array('status' => 1, 'info' => L('_SUCCESS_UPLOAD_'), 'data' => '');
        /* 调用文件上传组件上传文件 */
        $File = D('Admin/File');
        $file_driver = C('DOWNLOAD_UPLOAD_DRIVER');
        $info = $File->upload(
            $_FILES,
            C('DOWNLOAD_UPLOAD'),
            C('DOWNLOAD_UPLOAD_DRIVER'),
            C("UPLOAD_{$file_driver}_CONFIG")
        );

        /* 记录附件信息 */
        if ($info) {
            $return['data'] = think_encrypt(json_encode($info['download']));
        } else {
            $return['status'] = 0;
            $return['info'] = $File->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }


    public function downloadFile($id=null){
        if (empty($id) || !is_numeric($id)) {
            $this->error(L('_ERROR_PARAM_').L('_EXCLAMATION_'));
        }
        $info = D('file')->find($id);
        if(empty($info)){
            $this->error( L('_DOCUMENT_ID_INEXISTENT_').L('_COLON_')."{$id}");
            return false;
        }
        $File = D('Admin/File');
        $root ='.';
        $call = '';
        if(false === $File->download($root, $info['id'], $call, $info['id'])){
            $this->error( $File->getError());
        }
    }


    /**用于表单自动上传图片的通用方法
     * @auth 陈一枭
     */
    public function uploadFile()
    {
        $return = array('status' => 1, 'info' => L('_SUCCESS_UPLOAD_'), 'data' => '');
        /* 调用文件上传组件上传文件 */
        $File = D('Admin/File');
        $driver = modC('DOWNLOAD_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);
        $uploadConfig = get_upload_config($driver);

        $info = $File->upload(
            $_FILES,
            C('DOWNLOAD_UPLOAD'),
            $driver,
            $uploadConfig
        );

        /* 记录附件信息 */
        if ($info) {
            $return['data'] = $info;
        } else {
            $return['status'] = 0;
            $return['info'] = $File->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }


    /**
     * 上传图片
     * @author huajie <banhuajie@163.com>
     */
    public function uploadPicture()
    {

        //TODO: 用户登录检测
        /* 返回标准数据 */
        $return = array('status' => 1, 'info' =>L('_SUCCESS_UPLOAD_'), 'data' => '');

        /* 调用文件上传组件上传文件 */
        $Picture = D('Admin/Picture');

        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);

        $uploadConfig = get_upload_config($driver);

        $info = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            $driver,
            $uploadConfig
        );
        //TODO:上传到远程服务器
        /* 记录图片信息 */
        if ($info) {
            $return['status'] = 1;
            if ($info['Filedata']) {
                $return = array_merge($info['Filedata'], $return);
            }
            if ($info['download']) {
                $return = array_merge($info['download'], $return);
            }
            /*适用于自动表单的图片上传方式*/
            if ($info['file'] || $info['files']) {
                $return['data']['file'] = $info['file']?$info['file']:$info['files'];
            }
            /*适用于自动表单的图片上传方式end*/
            $aWidth= I('get.width',0,'intval');
            $aHeight=   I('get.height',0,'intval');
            if($aHeight<=0){
                $aHeight='auto';
            }
            if($aWidth>0){
                $return['path_self']=getThumbImageById($return['id'],$aWidth,$aHeight);
            }
        } else {
            $return['status'] = 0;
            $return['info'] = $Picture->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }

    public function uploadPictureBase64()
    {

        $aData = $_POST['data'];

        if ($aData == '' || $aData == 'undefined') {
            $this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));
        }

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $aData, $result)) {
            $base64_body = substr(strstr($aData, ','), 1);
            empty($aExt) && $aExt = $result[2];
        } else {
            $base64_body = $aData;
        }


        if(!in_array($aExt,array('jpg','gif','png','jpeg'))){
            $this->ajaxReturn(array('status'=>0,'info'=>'非法操作,上传照片格式不符。'));
        }
        $hasPhp=base64_decode($base64_body);

        if (strpos($hasPhp, '<?php') !==false) {
            $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
        }




        $pictureModel = D('Picture');

        $md5 = md5($base64_body);
        $sha1 = sha1($base64_body);


        $check = $pictureModel->where(array('md5' => $md5, 'sha1' => $sha1))->find();

        if ($check) {
            //已存在则直接返回信息
            $return['id'] = $check['id'];
            $return['path'] = render_picture_path($check['path']);
            $this->ajaxReturn(array('status'=>1,'id'=>$return['id'],'path'=> $return['path']));
        } else {
            //不存在则上传并返回信息
            $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
            $driver = check_driver_is_exist($driver);
            $date = date('Y-m-d');
            $saveName = uniqid();
            $savePath = '/Uploads/Picture/' . $date . '/';

            $path = $savePath . $saveName . '.' . $aExt;
            if($driver == 'local'){
                //本地上传
                mkdir('.' . $savePath, 0777, true);
                $data = base64_decode($base64_body);
                $rs = file_put_contents('.' . $path, $data);
            }
            else{
                $rs = false;
                //使用云存储
                $name = get_addon_class($driver);
                if (class_exists($name)) {
                    $class = new $name();
                    if (method_exists($class, 'uploadBase64')) {
                        $path = $class->uploadBase64($base64_body,$path);
                        $rs = true;
                    }
                }
            }
            if ($rs) {
                $pic['type'] = $driver;
                $pic['path'] = $path;
                $pic['md5'] = $md5;
                $pic['sha1'] = $sha1;
                $pic['status'] = 1;
                $pic['create_time'] = time();
                $id = $pictureModel->add($pic);
                $this->ajaxReturn (array('status'=>1,'id' => $id, 'path' => render_picture_path($path)));
            } else {
                $this->ajaxReturn(array('status'=>0,'图片上传失败。'));
            }

        }
    }

    /**用于兼容UM编辑器的图片上传方法
     * @auth 陈一枭
     */
    public function uploadPictureUM()
    {
        header("Content-Type:text/html;charset=utf-8");
        //TODO: 用户登录检测
        /* 返回标准数据 */
        $return = array('status' => 1, 'info' => L('_SUCCESS_UPLOAD_'), 'data' => '');

        //实际有用的数据只有name和state，这边伪造一堆数据保证格式正确
        $originalName = 'u=2830036734,2219770442&fm=21&gp=0.jpg';
        $newFilename = '14035912861705.jpg';
        $filePath = 'upload\/20140624\/14035912861705.jpg';
        $size = '7446';
        $type = '.jpg';
        $status = 'success';
        $rs = array(
            "originalName" => $originalName,
            'name' => $newFilename,
            'url' => $filePath,
            'size' => $size,
            'type' => $type,
            'state' => $status,
            'original' => $_FILES['upfile']['name']
        );
        /* 调用文件上传组件上传文件 */
        $Picture = D('Admin/Picture');

        $setting = C('EDITOR_UPLOAD');
        $setting['rootPath']='./Uploads/Editor/Picture/';

        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);
        $uploadConfig = get_upload_config($driver);

        $info = $Picture->upload(
            $_FILES,
            $setting,
            $driver,
            $uploadConfig
        ); //TODO:上传到远程服务器

        /* 记录图片信息 */
        if ($info) {
            $return['status'] = 1;
            if ($info['Filedata']) {
                $return = array_merge($info['Filedata'], $return);
            }
            if ($info['download']) {
                $return = array_merge($info['download'], $return);
            }
            $rs['state'] = 'SUCCESS';
            $rs['url'] = $info['upfile']['path'];
            if ($type == 'ajax') {
                echo json_encode($rs);
                exit;
            } else {
                echo json_encode($rs);
                exit;
            }

        } else {
            $return['state'] = 0;
            $return['info'] = $Picture->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }


    public function uploadFileUE(){
        $return = array('status' => 1, 'info' => L('_SUCCESS_UPLOAD_'), 'data' => '');

        //实际有用的数据只有name和state，这边伪造一堆数据保证格式正确
        $originalName = 'u=2830036734,2219770442&fm=21&gp=0.jpg';
        $newFilename = '14035912861705.jpg';
        $filePath = 'upload\/20140624\/14035912861705.jpg';
        $size = '7446';
        $type = '.jpg';
        $status = 'success';
        $rs = array(
            'name' => $newFilename,
            'url' => $filePath,
            'size' => $size,
            'type' => $type,
            'state' => $status
        );

        /* 调用文件上传组件上传文件 */
        $File = D('Admin/File');

        $driver = modC('DOWNLOAD_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);
        $uploadConfig = get_upload_config($driver);

        $setting = C('EDITOR_UPLOAD');
        $setting['rootPath']='./Uploads/Editor/File/';


        $setting['exts'] = 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,xlsx,xls,ppt,pptx,pdf';
        $info = $File->upload(
            $_FILES,
            $setting,
            $driver,
            $uploadConfig
        );

        /* 记录附件信息 */
        if ($info) {
            $return['data'] = $info;

            $rs['original'] = $info['upfile']['name'];
            $rs['state'] = 'SUCCESS';
            $rs['url'] =  strpos($info['upfile']['savepath'], 'http://') === false ?  __ROOT__.$info['upfile']['savepath'].$info['upfile']['savename']:$info['upfile']['savepath'];
            $rs['size'] = $info['upfile']['size'];
            $rs['title'] = $info['upfile']['savename'];


            if ($type == 'ajax') {
                echo json_encode($rs);
                exit;
            } else {
                echo json_encode($rs);
                exit;
            }



        } else {
            $return['status'] = 0;
            $return['info'] = $File->getError();
        }

        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }


    public function uploadAvatar(){

        $aUid = I('get.uid',0,'intval');

        mkdir ("./Uploads/Avatar/".$aUid);


        $files = $_FILES;
        $setting  = C('PICTURE_UPLOAD');

        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);
        $uploadConfig = get_upload_config($driver);


        /* 上传文件 */
        $setting['rootPath'] = './Uploads/Avatar';
        $setting['saveName'] = array('uniqid', '/'.$aUid.'/');
        $setting['savepath'] = '';
        $setting['subName'] = '';
        $setting['replace'] = true;

        //sae下
        if (strtolower(C('PICTURE_UPLOAD_DRIVER'))  == 'sae') {
            // $config[]
            C(require_once(APP_PATH . 'Common/Conf/config_sae.php'));

            $Upload = new \Think\Upload($setting,C('PICTURE_UPLOAD_DRIVER'), array(C('UPLOAD_SAE_CONFIG')));
            $info = $Upload->upload($files);

            $config=C('UPLOAD_SAE_CONFIG');
            if ($info) { //文件上传成功，记录文件信息
                foreach ($info as $key => &$value) {
                    $value['path'] = $config['rootPath'] . 'Avatar/' . $value['savepath'] . $value['savename']; //在模板里的url路径

                }
                /* 设置文件保存位置 */
                $this->_auto[] = array('location', 'Ftp' === $driver ? 1 : 0, self::MODEL_INSERT);
            }
        }else{
            $Upload = new \Think\Upload($setting, $driver, $uploadConfig);
            $info = $Upload->upload($files);
        }
        if ($info) { //文件上传成功，不记录文件
            $return['status'] = 1;
            if ($info['Filedata']) {
                $return = array_merge($info['Filedata'], $return);
            }
            if ($info['download']) {
                $return = array_merge($info['download'], $return);
            }
            /*适用于自动表单的图片上传方式*/
            if ($info['file']) {
                $return['data']['file'] = $info['file'];

                $path = $info['file']['url'] ? $info['file']['url'] : "./Uploads/Avatar".$info['file']['savename'];
                $src = $info['file']['url'] ? $info['file']['url'] : __ROOT__."/Uploads/Avatar".$info['file']['savename'];
                // $return['data']['file']['path'] =;
                $return['data']['file']['path'] =$path;
                $return['data']['file']['src']=$src;
                $size =  getimagesize($path);
                $return['data']['file']['width'] =$size[0];
                $return['data']['file']['height'] =$size[1];
                $return['data']['file']['time'] =time();
            }
        } else {
            $return['status'] = 0;
            $return['info'] = $Upload->getError();
        }

        $this->ajaxReturn($return);
    }

    /**
     * 手机上传头像
     */
    public function uploadMobAvatarBase64()
    {
        $aData = $_POST['data'];

        if ($aData == '' || $aData == 'undefined') {
            $this->ajaxReturn(array('status'=>0,'info'=>'参数错误'));
        }

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $aData, $result)) {
            $base64_body = substr(strstr($aData, ','), 1);
            empty($aExt) && $aExt = $result[2];
        } else {
            $base64_body = $aData;
        }

        $avatarModel = M('avatar');
        if(!in_array($aExt,array('jpg','gif','png','jpeg'))){
            $this->ajaxReturn(array('status'=>0,'info'=>'非法操作,上传照片格式不符。'));
        }
        $hasPhp=base64_decode($base64_body);

        if (strpos($hasPhp, '<?php') !==false) {
            $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
        }

        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $driver = check_driver_is_exist($driver);
        $uid=is_login();
        $saveName = uniqid();


        $path = '/'.$uid .'/'. $saveName . '.' . $aExt;
        if($driver == 'local'){
            //本地上传
            mkdir ("./Uploads/Avatar/".$uid,0777, true);

            $data = base64_decode($base64_body);
            $rs = file_put_contents('./Uploads/Avatar' . $path, $data);
        }
        else{
            $rs = false;
            //使用云存储
            $name = get_addon_class($driver);
            if (class_exists($name)) {
                $class = new $name();
                if (method_exists($class, 'uploadBase64')) {
                    $path = $class->uploadBase64($base64_body,$path);
                    $rs = true;
                }
            }
        }
        if ($rs) {
            $count=$avatarModel->where(array('uid'=>$uid))->count();
            $pic['driver'] = $driver;
            $pic['path'] = $path;
            $pic['uid'] = $uid;
            $pic['status'] = 1;
            $pic['create_time'] = time();
            if($count>0){
                $id=$avatarModel->where(array('uid'=>$uid))->save($pic);
            }else{
                $id=$avatarModel->add($pic);
            }
            clean_query_user_cache($uid, 'avatars');
            $this->ajaxReturn (array('status'=>1,'id' => $id, 'path' => render_picture_path($path)));
        } else {
            $this->ajaxReturn(array('status'=>0,'info'=>'图片上传失败。'));
        }


    }
    
}
