<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-8
 * Time: 上午9:06
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Common\Widget;


use Think\Controller;

class ShareWidget extends Controller{

    public function detailShare($data)
    {
        //支持参数“share_text”设置分享的文本内容
        $this->assign($data);
        $this->display(T('Application://Common@Widget/share'));
    }

} 