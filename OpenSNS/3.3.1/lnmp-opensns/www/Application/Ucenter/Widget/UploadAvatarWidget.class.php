<?php
/**
 * 所属项目 商业版.
 * 开发者: 陈一枭
 * 创建日期: 14-9-11
 * 创建时间: 下午1:09
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Ucenter\Widget;

require_once('ThinkPHP/Library/Vendor/PHPImageWorkshop/ImageWorkshop.php');
use Think\Controller;
use PHPImageWorkshop\Core\ImageWorkshopLayer;
use PHPImageWorkshop\ImageWorkshop;

class UploadAvatarWidget extends Controller
{

    public function render($uid = 0)
    {
        $this->assign('user', query_user(array('avatar256', 'avatar128', 'avatar64','avatar32','avatar512'), $uid));
        $this->assign('uid', $uid);
        $this->display(T('Application://Ucenter@Widget/uploadavatar'));
    }


    public function getAvatar($uid = 0, $size = 256)
    {
        $avatar = M('avatar')->where(array('uid' => $uid, 'status' => 1, 'is_temp' => 0))->find();
        if ($avatar) {

            if($avatar['driver'] == 'local'){
                $avatar_path = "/Uploads/Avatar".$avatar['path'];
                return $this->getImageUrlByPath($avatar_path, $size);
            }else{
                $new_img = $avatar['path'];
                $name = get_addon_class($avatar['driver']);
                if (class_exists($name)) {
                    $class = new $name();
                    if (method_exists($class, 'thumb')) {
                        $new_img =  $class->thumb($avatar['path'],$size,$size);
                    }
                }
                return $new_img;
            }
        } else {
            //如果没有头像，返回默认头像
           /* if($uid==session('temp_login_uid')||$uid==is_login()){
                $role_id = session('temp_login_role_id') ? session('temp_login_role_id') : get_role_id();
            }else{
                $role_id=query_user('show_role',$uid);
            }*/
            $role_id=M('member')->where('uid='.$uid)->field('show_role')->select();
            return $this->getImageUrlByRoleId($role_id[0]['show_role'], $size);
        }
    }




    private function getImageUrlByPath($path, $size,$isReplace = true)
    {
        $thumb = getThumbImage($path, $size, $size, 0, $isReplace);
        return get_pic_src($thumb['src']);
    }

    /**
     * 根据角色获取默认头像
     * @param $role_id
     * @param $size
     * @return mixed|string
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function getImageUrlByRoleId($role_id, $size)
    {
        $avatar_id=S('Role_Avatar_Id_'.$role_id);
        if(!$avatar_id){
            $map = getRoleConfigMap('avatar', $role_id);
            $avatar_id = D('RoleConfig')->where($map)->field('value')->find();
            S('Role_Avatar_Id_'.$role_id,$avatar_id,600);
        }
        if ($avatar_id) {
            if ($size != 0) {
                $path=getThumbImageById($avatar_id['value'], $size, $size);
            }else{
                $path=getThumbImageById($avatar_id['value']);
            }
        }else{//角色没有默认
            if ($size != 0) {
                $default_avatar = "Public/images/default_avatar.jpg";
                $path=$this->getImageUrlByPath($default_avatar, $size, false);
            } else {
                $path= get_pic_src("Public/images/default_avatar.jpg");
            }
        }
        return $path;
    }

    public function cropPicture($crop = null,$path)
    {
        //如果不裁剪，则发生错误
        if (!$crop) {
            $this->error('必须裁剪');
        }
        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        if (strtolower($driver) == 'local') {
            //解析crop参数
            $crop = explode(',', $crop);
            $x = $crop[0];
            $y = $crop[1];
            $width = $crop[2];
            $height = $crop[3];
            //本地环境
            $image = ImageWorkshop::initFromPath($path);
            //生成将单位换算成为像素
            $x = $x * $image->getWidth();
            $y = $y * $image->getHeight();
            $width = $width * $image->getWidth();
            $height = $height * $image->getHeight();
            //如果宽度和高度近似相等，则令宽和高一样
            if (abs($height - $width) < $height * 0.01) {
                $height = min($height, $width);
                $width = $height;
            }
            //调用组件裁剪头像
            $image = ImageWorkshop::initFromPath($path);
            $image->crop(ImageWorkshopLayer::UNIT_PIXEL, $width, $height, $x, $y);
            $image->save(dirname($path), basename($path));
            //返回新文件的路径
            return  cut_str('/Uploads/Avatar',$path,'l');
        }else{
            $name = get_addon_class($driver);
            $class = new $name();
            $new_img = $class->crop($path,$crop);
            return $new_img;
        }


    }


} 