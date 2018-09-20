<?php
namespace Common\Widget;

use Think\Controller;

/**
 * Class UploadMultiImageWidget  上传多图组件
 * @package Common\Widget
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class UploadMultiImageWidget extends Controller
{

    public function render($attributes = array())
    {

        $attributes_id = $attributes['id'];
        $config = $attributes['config'];
        $class = $attributes['class'];
        $value = $attributes['value'];
        $images = explode(',', $value);
        $name = $attributes['name'];
        $attributes['width'] = $width = $attributes['width'] ? $attributes['width'] : 100;
        $attributes['height'] = $height = $attributes['height'] ? $attributes['height'] : 100;
        $isLoadScript = $attributes['isLoadScript'] ? 1 : 0;
        //$filetype = $this->rules['filetype'];

        $config = $config['config'];

        $id = $attributes_id;
        if (empty($attributes['config']['text']))
            $attributes['config'] = array('text' => L('_FILE_SELECT_')
            );


        if (intval($value) != 0) {
            $url = getThumbImageById($value, $width, $height);
            $img = '<img src="' . $url . '"/>';
        } else {
            $img = '';
        }

        $this->assign('isLoadScript', $isLoadScript);

        $this->assign('img', $img);
        $this->assign('images', $images);
        $this->assign($attributes);
        $this->display(T('Application://Common@Widget/uploadmultiimage'));
    }
} 