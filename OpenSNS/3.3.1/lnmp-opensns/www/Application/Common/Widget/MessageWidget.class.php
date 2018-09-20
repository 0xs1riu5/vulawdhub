<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-28
 * Time: 下午2:48
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Common\Widget;

use Think\Controller;

class MessageWidget extends Controller{

    public function render($data=null)
    {
        if(!is_login()){
            return false;
        }
        $this->display(T('Application://Common@Widget/message'));
    }

} 