<?php

function callApi($apiName, $args = array())
{
    //
    $paths = explode('/', $apiName);
    $controllerName = "Api\\Controller\\$paths[0]Controller";
    $controller = new $controllerName();
    $controller->setInternalCallApi();
    $function = $paths[1];
    $method = new ReflectionMethod($controllerName, $function);
    try {
        $method->invokeArgs($controller, $args);
    } catch (Api\Exception\ReturnException $ex) {
        return $ex->getResult();
    }
}

function apiToAjax($result)
{
    $result['status'] = $result['success'];
    $result['info'] = $result['message'];
    unset($result['success']);
    unset($result['message']);
    return $result;
}

function ensureApiSuccess($apiResult)
{
    if (!$apiResult['success']) {
        api_show_error($apiResult['message']);
    }
}

/**
 * 显示错误消息，根据调用方式。如果是ajax调用，则返回ajax错误信息；
 * 如果是直接页面访问的话，直接显示错误消息
 * @param $message
 */
function api_show_error($message, $extra = array())
{
    if (IS_AJAX) {
        api_show_error_json($message, $extra);
    } else {
        api_show_error_html($message, $extra);
    }
}

function api_show_error_json($message, $extra = array())
{
    //生成错误信息
    $json['status'] = false;
    $json['info'] = $message;
    $json = array_merge($json, $extra);

    //返回
    header('Content-Type: application/json');
    echo json_encode($json);
}

function api_show_error_html($message, $extra = null)
{
    class EnsureApiSuccessController extends Think\Controller
    {
        public function showError($message)
        {
            $this->error($message);
        }
    }

    $controller = new EnsureApiSuccessController();
    $controller->showError($message);
}

function handle_exception($exception)
{
    // 显示错误消息
    $message = $exception->getMessage();
    if (method_exists($exception, 'getExtra')) {
        $extra = $exception->getExtra();
    } else {
        $extra = array();
    }
    $extra['error_code'] = $exception->getCode();
    api_show_error($message, $extra);
}

// 允许API抛出异常，将异常视为普通的Controller::error();
set_exception_handler('handle_exception');