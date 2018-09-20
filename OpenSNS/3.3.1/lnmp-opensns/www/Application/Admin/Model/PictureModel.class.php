<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
use Think\Upload;

/**
 * 图片模型
 * 负责图片的上传
 */

class PictureModel extends Model{
    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'local', $config = null){
        /* 上传文件 */
        $setting['callback'] = array($this, 'isFile');
		$setting['removeTrash'] = array($this, 'removeTrash');
        $Upload = new Upload($setting, $driver, $config);
        foreach ($files as $key => $file) {
            if(!$file['ext']){
                $extend  =  pathinfo ( $file['name'] );
                $ext  =  strtolower ( $extend [ "extension" ]);
            }else{
                $ext = strtolower($file['ext']);
            }
            if(in_array($ext, array('jpg','jpeg','bmp','png'))){
                $this->_dealPicture($file['tmp_name']);
            }
        }

        $info   = $Upload->upload($files);

        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }

                /* 记录文件信息 */
                if(strtolower($driver)=='sae'){
                    $value['path'] = $config['rootPath'].'Picture/'.$value['savepath'].$value['savename']; //在模板里的url路径
                }else{
                    if(strtolower($driver) != 'local'){
                        $value['path'] =$value['url'];
                    }
                    else{
                        $value['path'] = (substr($setting['rootPath'], 1).$value['savepath'].$value['savename']);	//在模板里的url路径
                    }

                }

                $value['type'] = $driver;

                //图片宽高存入数据库
                $path=$value['path'];
                if($value['type']=='local'){
                    $path='.'.$path;
                }
                $hv = getimagesize($path);
                if($hv){
                    $value['width']=$hv[0];
                    $value['height']=$hv[1];
                }
                //图片宽高存入数据库end

                if($this->create($value) && ($id = $this->add())){
                    $value['id'] = $id;
                } else {
                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                    unset($info[$key]);
                }
            }

            foreach($info as &$t_info){
                if($t_info['type'] =='local'){
                    $t_info['path']=get_pic_src($t_info['path']);
                }
                else{
                    $t_info['path']=$t_info['path'];
                }


            }
          /*  dump(getRootUrl());
            dump($info);
            exit;*/

            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }

    private function _dealPicture($temp_name)
    {
        $waterOpen=modC('WATER_OPEN',0,'Picture');
        if ($waterOpen) {
            $waterImage=modC('WATER_IMAGE',0,'Picture');
            if ($waterImage) {
                $water = $waterImage;
            } else {
                $water = './Application/Admin/Static/images/water.png';
            }
            $water_open = file_exists($water);
            require_once("./Application/Common/Controller/WaterMark.class.php");
            if ($water_open) {
                $position=modC('WATER_SPACE',0,'Picture');
                $watermark = new \WaterMark($temp_name, $position);
                $watermark->setWaterImage($water);
                $watermark->imageWaterMark();
            }
        }
        return $temp_name;
    }
    /**
     * 下载指定文件
     * @param  number  $root 文件存储根目录
     * @param  integer $id   文件ID
     * @param  string   $args     回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    public function download($root, $id, $callback = null, $args = null){
        /* 获取下载文件信息 */
        $file = $this->find($id);
        if(!$file){
            $this->error = L('_NO_THIS_FILE_IS_NOT_THERE_WITH_EXCLAMATION_');
            return false;
        }

        /* 下载文件 */
        switch ($file['location']) {
            case 0: //下载本地文件
                $file['rootpath'] = $root;
                return $this->downLocalFile($file, $callback, $args);
            case 1: //TODO: 下载远程FTP文件
                break;
            default:
                $this->error = L('_UNSUPPORTED_FILE_STORAGE_TYPE_WITH_EXCLAMATION_');
                return false;

        }

    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
		$map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
        return $this->field(true)->where($map)->find();
    }

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  callable $callback 下载回调函数，一般用于增加下载次数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private function downLocalFile($file, $callback = null, $args = null){
        if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
            /* 调用回调函数新增下载数 */
            is_callable($callback) && call_user_func($callback, $args);

            /* 执行下载 */ //TODO: 大文件断点续传
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['type']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($file['rootpath'].$file['savepath'].$file['savename']);
            exit;
        } else {
            $this->error = L('_FILE_HAS_BEEN_DELETED_WITH_EXCLAMATION_');
            return false;
        }
    }

	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$this->where(array('id'=>$data['id'],))->delete();
	}

    /**
     * 获取图片列表
     * @param int $page
     * @param int $r
     * @return array
     */
    public function getPictureList($page=1,$r=10)
    {
        $map['status']=array('in','0,1');
        $totalCount=$this->where($map)->count();
        $list=$this->where($map)->page($page,$r)->order('create_time desc')->select();
        return array($list,$totalCount);
    }

    public function getList($map)
    {
        $list=$this->where($map)->select();
        return $list;
    }
}
