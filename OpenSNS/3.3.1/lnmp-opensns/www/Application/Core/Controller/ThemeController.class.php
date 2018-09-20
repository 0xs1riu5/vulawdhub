<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-24
 * Time: 下午5:10
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Core\Controller;


use Think\Controller;

class ThemeController extends Controller{
    /**
     * 临时查看主题（管理员预览用）
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function lookTheme()
    {
        $aTheme = I('theme', '', 'text');
        $themeModel = D('Common/Theme');
        $res=$themeModel->lookTheme($aTheme,1000);
        if($res){
            redirect(U('Home/Index/index'));
        }else{
            $this->error('请求失败！');
        }
    }
} 