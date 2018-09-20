<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-9
 * Time: 上午8:59
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Widget;

use Think\Action;

/**input类型输入渲染
 * Class InputWidget
 * @package Usercenter\Widget
 * @郑钟良
 */
class FocusButtonWidget extends Action
{

    public function  display($uid)
    {
        $this->assign($uid);
        $this->display(T('Ucenter@Widget/focusbutton'));
    }

} 