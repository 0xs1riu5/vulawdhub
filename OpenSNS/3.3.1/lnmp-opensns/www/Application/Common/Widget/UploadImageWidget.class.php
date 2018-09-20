<?php
namespace Common\Widget;
use Think\Controller;

/**
 * Class UploadImageWidget  上传图片组件
 * @package Common\Widget
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class UploadImageWidget extends Controller
{

    public function render($attributes = array())
    {

        $attributes_id = $attributes['id'];
        $config = $attributes['config'];
        $class = $attributes['class'];
        $value = $attributes['value'];
        $name = $attributes['name'];
        $width = $attributes['width'] ? $attributes['width'] : 100;
        $height = $attributes['height'] ? $attributes['height'] : 100;

        //$filetype = $this->rules['filetype'];

        $config = $config['config'];

        $id = $attributes_id;
        $attributes['config'] = array('text' => L('_FILE_SELECT_')
        );


        if (intval($value) != 0) {
            $url = getThumbImageById($value, $width, $height);
            $img = '<img src="' . $url . '"/>';
        } else {
            $img = '';
        }

        $this->assign('img',$img);
        $this->assign($attributes);
        $this->display(T('Application://Common@Widget/uploadimage'));
    }
} 