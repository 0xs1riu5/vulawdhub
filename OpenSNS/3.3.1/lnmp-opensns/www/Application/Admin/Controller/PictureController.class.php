<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/9/6
 * Time: 10:43
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

class PictureController extends AdminController
{
    /**
     * 图片水印设置
     */
    public function config()
    {


        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();

        $data['WATER_OPEN']===null&&$data['WATER_OPEN']=0;
        !is_file($data['WATER_IMAGE'])&& $data['WATER_IMAGE']='./Application/Admin/Static/images/water.png';
        $data['WATER_SPACE']===null&&$data['WATER_SPACE']=9;

        $builder->title('图片水印设置');
        /* ->keyRadio('WATER_OPEN', '是否开启添加水印', '默认关闭水印', array(1 => '开启', 0 => '关闭'))
                    ->keySingleImage('WATER_IMAGE', '水印图片')
                    ->keySelect('WATER_SPACE', '水印位置', '水印在原图的位置', array('1' => '左上', '2' => '中上', '3' => '右上', '4' => '左中', '5' => '中间', '6' => '右中', '7' => '左下', '8' => '中下', '9' => '右下',))
                    ->data($data)->buttonSubmit()->buttonBack()->display();*/
        $this->assign('data',$data);
        $this->display();


    }

    public function uploadWater(){
        $config = array(
            'maxSize'    =>    3145728,
            'rootPath'   =>    './Uploads/',
            'savePath'   =>    'water/',
            'saveName'   =>    'water',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    '',
            'replace'=> true,
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info   =   $upload->upload($_FILES);
        if($info){
            $return['status'] = 1;
            $return['url'] = './Uploads/water/'.$info['download']['savename'];
        }else{
            $return['status'] = 0;
            $return['info'] = '上传失败';
        }

        $this->ajaxReturn($return);
    }

    public function pictureList($page=1,$r=20)
    {
        list($list,$totalCount)=D('Picture')->getPictureList($page,$r);
        foreach($list as &$val){
            $val['image']=$val['id'];
        }
        $builder=new AdminListBuilder();
        $builder->title('图片列表')
            ->setStatusUrl(U('Picture/setStatus'))
            ->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()
            ->keyCreateTime('create_time','上传时间')
            ->keyText('type','存储空间')
            ->keyText('path','存储路径')
            ->keyText('url','图片链接')
            ->keyText('md5','文件md5编码')
            ->keyText('sha1','文件sha1编码')
            ->keyStatus()
            ->keyImage('image','图片')
            ->data($list)
            ->pagination($totalCount,$r)
            ->display();

    }

    public function setStatus($ids,$status=1)
    {
        $builder=new AdminListBuilder();
        !is_array($ids)&&$ids=explode(',',$ids);
        if($status==-1){
            $list=D('Picture')->getList(array('id'=>array('in',$ids)));
            foreach($list as $val){
                $path=$val['path'];
                if($val['type']=='local'){
                    $path='.'.$path;
                    @mkdir($path,777,true);
                    unlink($path);
                    $this->_deleteThumb($path);
                }else{
                    $file_name=explode('/',$path);
                    $file_name=$file_name[count($file_name)-1];
                    delete_driver_upload_file($file_name,$val['type']);
                }
            }
            $builder->doDeleteTrue('Picture',$ids);
        }else{
            $builder->doSetStatus('Picture',$ids,$status);
        }
    }

    private function _deleteThumb($path)
    {
        $file_name=explode('/',$path);
        $file_name=$file_name[count($file_name)-1];
        $dir=str_replace($file_name,'',$path);
        $file_name=explode('.',$file_name);
        $file_info['name']=$file_name[0];
        $file_info['ext']=$file_name[1];
        if(is_dir($dir)){
            if ($dh = opendir($dir)){
                while(($file=readdir($dh))!==false){
                    if(strpos($file,$file_info['name'])!==false){
                        $file_path=$dir.$file;
                        @mkdir($file_path,777,true);
                        unlink($file_path);
                    }
                }
                closedir($dh);
            }
        }
        return true;
    }
}