<?php

class BaseAction extends Action
{
    // 站点名称
    protected $_title;
    // 分页使用
    protected $_page;
    protected $_item_count;
    // 来源类型
    protected $_from_type;
    protected $_type_wap;
    // 关注状态
    protected $_follow_status;
    // 当前URL
    protected $_self_url;
    // 用户信息
    public $profile;
    public function _initialize()
    {

        /* # 验证是否开启 */
        json_decode(json_encode(model('Xdata')->get('admin_Mobile:setting')), false)->switch or $this->error('手机版已经关闭无法访问', '3', U('public/Index/index'));

        // 登录验证
        if (!$this->mid) {
            $publicAccess = include APPS_PATH.'/w3g/Conf/access.inc.php';
            $publicAccess = $publicAccess['access'];
            if (!($publicAccess[APP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME] === true
                    || $publicAccess[APP_NAME.'/'.MODULE_NAME.'/*'] === true
                    || $publicAccess[APP_NAME.'/*/*'] === true)) {
                redirect(U('w3g/Public/login'));
                exit();
            }
        }
        global $ts;

        // 站点名称
        $this->_title = $ts ['site'] ['site_name'].' 3G版';
        $this->assign('site_name', $this->_title);

        // 分页
        $_GET ['page'] = $_POST ['page'] ? intval($_POST ['page']) : intval($_GET ['page']);
        $this->_page = $_GET ['page'] > 0 ? $_GET ['page'] : 1;
        $this->assign('page', $this->_page);
        $this->_item_count = 10;
        $this->assign('item_count', $this->_item_count);

        // 来源类型
        // if(is_iphone()){
        // $this->_type_wap = 3;
        // }elseif(is_android()){
        // $this->_type_wap = 2;
        // }else{
        $this->_type_wap = 1;
        // }

        $this->_from_type = array(
                '0' => '网站',
                '1' => '3G版',
                '2' => 'Android客户端',
                '3' => 'iPhone客户端',
        );
        $this->assign('from_type', $this->_from_type);

        // 关注状态
        $this->_follow_status = array(
                'eachfollow' => '相互关注',
                'havefollow' => '已关注',
                'unfollow' => '未关注',
        );
        $this->assign('follow_status', $this->_follow_status);

        // 当前URL
        $this->_self_url = 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER ['REQUEST_URI'];
        if (isset($_POST ['key'])) {
            $this->_self_url .= "&key={$_POST['key']}";
            $this->_self_url .= isset($_POST ['user']) ? '&user=1' : '&weibo=1';
        }
        $this->assign('self_url', $this->_self_url);

        // 是否为owner
        $this->assign('is_owner', ($this->uid == $this->mid) ? '1' : '0');

        $data ['user_id'] = $this->uid;
        $data ['page'] = $this->_page;

        // 用户资料
        $this->profile = api('User')->data($data)->show();
        $this->assign('profile', $this->profile);

        $logo = '';
        if (($logo = model('Xdata')->get('admin_Mobile:w3gLogo'))) {
            $logo = getAttachUrlByAttachId($logo['logo']);
        }
        $this->assign('logo', $logo);
    }
    public function need_login()
    {
        // 登录验证
        $passport = model('Passport');
        if (!$passport->isLogged()) {
            $_SESSION ['__forward__'] = $_SERVER ['REQUEST_URI'];
            redirect(U('wap/Public/login'));
        }
    }
    public function success($msg, $delay = 0, $url = '')
    {
        if (IS_AJAX) {
            echo $msg;
            exit();
        }
        if (!$url) {
            $url = $_SERVER ['HTTP_REFERER'];
        }

        if (!empty($msg) && $delay == 0) {
            $delay = 3;
        }

        redirect($url, $delay, $msg);
    }
    public function error($msg, $delay = 0, $url = '')
    {
        $this->success($msg, $delay, $url);
    }
}
