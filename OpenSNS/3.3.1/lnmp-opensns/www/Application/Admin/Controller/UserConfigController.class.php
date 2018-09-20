<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;

use Ucenter\Widget\RegStepWidget;

/**
 * Class UserConfigController  后台用户配置控制器
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class UserConfigController extends AdminController
{


    public function index()
    {

        $admin_config = new AdminConfigBuilder();
        $data = $admin_config->handleConfig();

        $mStep = A('Ucenter/RegStep', 'Widget')->mStep;
        $step = array();
        foreach ($mStep as $key => $v) {
            $step[] = array('data-id' => $key, 'title' => $v);
        }

        $default = array(array('data-id' => 'disable', 'title' => L('_DISABLE_'), 'items' => $step), array('data-id' => 'enable', 'title' => L('_ENABLE_'), 'items' => array()));
        //$default=array(L('_DISABLE_')=>$step,L('_ENABLE_AND_SKIP_')=>array(),L('_ENABLE_BUT_NOT_SKIP_')=>array());
        $data['REG_STEP'] = $admin_config->parseKanbanArray($data['REG_STEP'],$step,$default);


        $modules = D('Common/Module')->getAll();
        $menu = array();
        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/UcenterBlockWidget.class.php')) {
                    $menu[] = array('data-id' => $m['name'], 'title' => $m['alias'], 'sort' => $m['sort'], 'key' => strtolower($m['name']));
                }
            }
        }

        $apps[] = array('data-id' => 'info', 'sort' => '0', 'title' => '资料', 'key' => 'info');
        $apps[] = array('data-id' => 'rank_title', 'sort' => '0', 'title' => L('_RANK_TITLE_'), 'key' => 'rank_title');
        $apps[] = array('data-id' => 'follow', 'sort' => '0', 'title' => L('_FOLLOWERS_NO_SPACE_') . '/粉丝', 'key' => 'follow');
        $apps[] = array('data-id' => 'topic_list', 'sort' => '0', 'title' => '关注的话题', 'key' => 'topic_list');
        $apps = $this->sortApps($apps);

        $default1 = array(array('data-id' => 'disable', 'title' => L('_DISABLE_'), 'items' => $menu), array('data-id' => 'enable', 'title' =>L('_ENABLE_'), 'items' => $apps));
        $menu = array_merge($menu, $apps);
        $data['UCENTER_KANBAN'] = $admin_config->parseKanbanArray($data['UCENTER_KANBAN'],$menu,$default1);

        empty($data['LEVEL']) && $data['LEVEL'] = <<<str
0:Lv1 实习
50:Lv2 试用
100:Lv3 转正
200:Lv4 助理
400:Lv5 经理
800:Lv6 董事
1600:Lv7 董事长
str;
        empty($data['OPEN_QUICK_LOGIN']) && $data['OPEN_QUICK_LOGIN'] = 0;


        empty($data['LOGIN_SWITCH']) && $data['LOGIN_SWITCH'] = 'username';



        $addons = \Think\Hook::get('sms');
        $opt = array('none' => L('_NONE_'));
        foreach ($addons as $name) {
            if (class_exists($name)) {
                $class = new $name();
                $config = $class->getConfig();
                if ($config['switch']) {
                    $opt[$class->info['name']] = $class->info['title'];
                }
            }
        }

        $admin_config->title(L('_USER_CONFIGURATION_'))->data($data)
            ->keyCheckBox('REG_SWITCH', L('_REGISTRATION_SWITCH_'), L('_THE_REGISTRATION_OPTION_THAT_ALLOWS_THE_USE_OF_THE_REGISTRATION_IS_CLOSED_'), array( 'email' => L('_MAILBOX_'), 'mobile' => L('_MOBILE_PHONE_')))
            ->keyRadio('EMAIL_VERIFY_TYPE', L('_MAILBOX_VERIFICATION_TYPE_'), L('_TYPE_MAILBOX_VERIFICATION_'), array(0 => L('_NOT_VERIFIED_'), 1 => L('_POST_REGISTRATION_ACTIVATION_MAIL_'), 2 => L('_EMAIL_VERIFY_SEND_BEFORE_REG_')))
            ->keyRadio('MOBILE_VERIFY_TYPE', L('_MOBILE_VERIFICATION_TYPE_'), L('_TYPE_OF_CELL_PHONE_VERIFICATION_'), array(0 => L('_NOT_VERIFIED_'), 1 => L('_REGISTER_BEFORE_SENDING_A_VALIDATION_MESSAGE_')))
            ->keyText('NEW_USER_FOLLOW', L('_NEW_USER_ATTENTION_'), L('_ID_INPUT_SEPARATE_COMMA_'))
            ->keyText('NEW_USER_FANS', L('_NEW_USER_FANS_'), L('_ID_INPUT_SEPARATE_COMMA_'))
            ->keyText('NEW_USER_FRIENDS', L('_NEW_FRIENDS_'), L('_ID_INPUT_SEPARATE_COMMA_'))

            ->keyKanban('REG_STEP', L('_REGISTRATION_STEP_'), L('_STEPS_TO_BE_MADE_AFTER_REGISTRATION_'))//看板

            ->keyCheckBox('REG_CAN_SKIP', L('_WHETHER_THE_REGISTRATION_STEP_CAN_BE_SKIPPED_'), L('_CHECK_TO_SKIP_AND_YOU_CANT_SKIP_THE_DEFAULT_'),$mStep)

            ->keyEditor('REG_EMAIL_VERIFY', L('_MAILBOX_VERIFICATION_TEMPLATE_'), L('_PLEASE_EMAIL_VERIFY_'),'all')
            ->keyEditor('REG_EMAIL_ACTIVATE', L('_MAILBOX_ACTIVATION_TEMPLATE_'), L('_PLEASE_USER_ACTIVE_'))

            ->keySelect('SMS_HOOK', L('_SMS_SENDING_SERVICE_PROVIDER_'), L('_SMS_SEND_SERVICE_PROVIDERS_NEED_TO_INSTALL_THE_PLUG-IN_'), $opt)
            ->keyText('SMS_RESEND', L('_THE_MESSAGE_RETRANSMISSION_TIME_'), L('_THE_MESSAGE_RETRANSMISSION_TIME_'))
            //->keyText('SMS_HTTP', L('_SMS_PLATFORM_HTTP_'), '短信平台HTTP，可用服务商：<a href="http://www.yunsms.cn/" target="_blank">云短信</a>，<a href="http://v2.opensns.cn/index.php?s=/news/index/detail/id/52.html" target="_blank">使用帮助</a>')
            ->keyText('SMS_UID', L('_SMS_PLATFORM_ACCOUNT_NUMBER_'), L('_SMS_PLATFORM_ACCOUNT_NUMBER_'))
            ->keyText('SMS_PWD', L('_SMS_PLATFORM_PASSWORD_'), L('_SMS_PLATFORM_PASSWORD_'))
            ->keyTextArea('SMS_CONTENT', L('_MESSAGE_CONTENT_'), L('_MSG_VERICODE_ACCOUNT_'))
            ->keyCheckBox('RANK_LIST', '排行榜', '排行榜显示', array('fans' =>'粉丝', 'con_check' =>'连签', 'total_check' =>'累签', 'score' =>'积分'))->keyDefault('RANK_LIST', 'fans,con_check,total_check,score')
            ->keyTextArea('LEVEL', L('_HIERARCHY_'), L('_ONE_PER_LINE_BETWEEN_THE_NAME_AND_THE_INTEGRAL_BY_A_COLON_'))
            ->keyInteger('NICKNAME_MIN_LENGTH', L('_NICKNAME_LENGTH_MINIMUM_'))->keyDefault('NICKNAME_MIN_LENGTH',2)
            ->keyInteger('NICKNAME_MAX_LENGTH', L('_NICKNAME_LENGTH_MAXIMUM_'))->keyDefault('NICKNAME_MAX_LENGTH',32)
            ->keyInteger('USERNAME_MIN_LENGTH', L('_USERNAME_LENGTH_MINIMUM_'))->keyDefault('USERNAME_MIN_LENGTH',2)
            ->keyInteger('USERNAME_MAX_LENGTH', L('_USERNAME_LENGTH_MAXIMUM_'))->keyDefault('USERNAME_MAX_LENGTH',32)
            ->keyKanban('UCENTER_KANBAN', L('_UCENTER_KANBAN_'), L('_SET_TO_SHOW_UCENTER_'))//基础配置 个人主页看板

            ->keyRadio('OPEN_QUICK_LOGIN',L('_QUICK_LOGIN_'),L('_BY_DEFAULT_AFTER_THE_USER_IS_LOGGED_IN_THE_USER_IS_LOGGED_IN_'), array(0 => L('_OFF_'), 1 => L('_OPEN_')))


            ->keyCheckBox('LOGIN_SWITCH', L('_LOGIN_PROMPT_SWITCH_'), L('_JUST_THE_TIP_OF_THE_LOGIN_BOX_'), array('email' => L('_MAILBOX_'), 'mobile' => L('_MOBILE_PHONE_')))
            ->keyText('SYNC_LOGIN_EMAIL_SUFFIX','第三方登录邮箱后缀','格式:@xx.xxx')
            ->group(L('_REGISTER_CONFIGURATION_'), 'REG_SWITCH,EMAIL_VERIFY_TYPE,MOBILE_VERIFY_TYPE,REG_STEP,REG_CAN_SKIP,NEW_USER_FOLLOW,NEW_USER_FANS,NEW_USER_FRIENDS')
            ->group(L('_LOGIN_CONFIGURATION_'), 'OPEN_QUICK_LOGIN,LOGIN_SWITCH,SYNC_LOGIN_EMAIL_SUFFIX')
            ->group(L('_MAILBOX_VERIFICATION_TEMPLATE_'), 'REG_EMAIL_VERIFY')
            ->group(L('_MAILBOX_ACTIVATION_TEMPLATE_'), 'REG_EMAIL_ACTIVATE')
            ->group(L('_SMS_CONFIGURATION_'), 'SMS_HTTP,SMS_UID,SMS_PWD,SMS_CONTENT,SMS_HOOK,SMS_RESEND')
            ->group(L('_BASIC_SETTINGS_'), 'RANK_LIST,LEVEL,NICKNAME_MIN_LENGTH,NICKNAME_MAX_LENGTH,USERNAME_MIN_LENGTH,USERNAME_MAX_LENGTH,UCENTER_KANBAN')
            ->buttonSubmit('', L('_SAVE_'))
            ->keyDefault('REG_EMAIL_VERIFY',L('_VERICODE_ACCOUNT_').L('_PERIOD_'))
            ->keyDefault('REG_EMAIL_ACTIVATE',L('_LINK_ACTIVE_IS_'))
            ->keyDefault('SYNC_LOGIN_EMAIL_SUFFIX','@ocenter.com')
            ->keyDefault('SMS_CONTENT',L('_VERICODE_ACCOUNT_'))
            ->keyDefault('SMS_RESEND','60');
        $admin_config->display();
    }

    private function sortApps($apps)
    {
        return $this->multi_array_sort($apps, 'sort', SORT_DESC);
    }

    function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC)
    {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }
}
