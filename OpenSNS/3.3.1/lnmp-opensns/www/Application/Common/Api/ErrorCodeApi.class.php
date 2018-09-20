<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 4/9/14
 * Time: 4:46 PM
 */

namespace Common\Api;

/**
 * Class ErrorCodeApi
 * @package Common\Api
 *
 * 该类存放所有应用的API错误代码。注意是所有应用。
 *
 * 错误代码的一般格式例如 10004。
 * 1  - 没有含义，为了让错误代码保持5位数。
 * 00 - 模块编号。一般一个应用分配一个模块编号。
 * 04 - 具体的错误代码。
 */
class ErrorCodeApi {
    /**
     * 需要登录
     */
    const REQUIRE_LOGIN = 10000;
}