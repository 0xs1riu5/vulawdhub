<?php
namespace app\common;

use core\basic\Controller;
use core\basic\Config;

class HomeController extends Controller
{

    public function __construct()
    {
        // 自动缓存基础信息
        cache_config();
        
        // 手机自适应主题
        if ($this->config('open_wap') && (is_mobile() || $this->config('wap_domain') == get_http_host())) {
            $this->setTheme($this->config('theme') . '/wap');
        } else {
            $this->setTheme($this->config('theme'));
        }
    }
}