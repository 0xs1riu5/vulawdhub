<?php

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Vendor\requester;

/**
 * Class AuthorizeController  后台授权控制器
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class AuthorizeController extends AdminController
{

    public function ssoSetting()
    {

        $admin_config = new AdminConfigBuilder();
        $admin_config->callback("ssoCallback");
        $data = $admin_config->handleConfig();

        $admin_config->title(L('_SINGLE_POINT_LOGIN_CONFIGURATION_'))


            ->keyRadio('SSO_SWITCH_USER_CENTER', L('_SINGLE_SIGN_ON_SWITCH_'), L('_AS_THE_USER_CENTER_OF_THE_SINGLE_SIGN_ON_SWITCH_'), array(0 => L('_CLOSE_SINGLE_POINT_LOGIN_'), 1 => L('_AS_USER_CENTER_OPEN_SINGLE_SIGN_ON_')))
            ->keyTextArea('SSO_CONFIG', L('_SINGLE_POINT_LOGIN_CONFIGURATION_'), L('_SINGLE_POINT_LOGIN_CONFIGURATION_VICE_'))
            ->keyLabel('SSO_UC_AUTH_KEY', L('_USER_CENTER_ENCRYPTION_KEY_'), L('_THE_SYSTEM_HAS_BEEN_AUTOMATICALLY_WRITTEN_TO_THE_CONFIGURATION_FILE_'))
            ->keyLabel('SSO_UC_DB_DSN', L('_USER_CENTER_DATA_CONNECTION_'), L('_THE_SYSTEM_HAS_BEEN_AUTOMATICALLY_WRITTEN_TO_THE_CONFIGURATION_FILE_'))
            ->keyLabel('SSO_UC_TABLE_PREFIX', L('_USER_CENTER_TABLE_PREFIX_'), L('_THE_SYSTEM_HAS_BEEN_AUTOMATICALLY_WRITTEN_TO_THE_CONFIGURATION_FILE_'))

            ->group(L('_CONFIGURATION_AS_USER_CENTER_'),'SSO_SWITCH_USER_CENTER')
            ->group(L('_AS_AN_APPLICATION_CONFIGURATION_'),'SSO_CONFIG,SSO_UC_AUTH_KEY,SSO_UC_DB_DSN,SSO_UC_TABLE_PREFIX')
            ->buttonSubmit('', L('_SAVE_'))->data($data);
        $admin_config->display();
    }

    public function ssoCallback($config)
    {

        $str = "<?php \n return " . ($config['SSO_CONFIG']?$config['SSO_CONFIG']:'array();');
        file_put_contents('./OcApi/oc_config.php', $str);


        $add = array();
        $oc_config = include_once './OcApi/oc_config.php';
        $add['SSO_UC_AUTH_KEY'] = $config['SSO_UC_AUTH_KEY'] = $oc_config['SSO_DATA_AUTH_KEY'];
        $add['SSO_UC_DB_DSN'] = $config['SSO_UC_DB_DSN'] = 'mysqli://' . $oc_config['SSO_DB_USER'] . ':' . $oc_config['SSO_DB_PWD'] . '@' . $oc_config['SSO_DB_HOST'] . ':' . $oc_config['SSO_DB_PORT'] . '/' . $oc_config['SSO_DB_NAME'];
        $add['SSO_UC_TABLE_PREFIX'] = $config['SSO_UC_TABLE_PREFIX'] = $oc_config['SSO_DB_PREFIX'];

        if (!$config['SSO_CONFIG']) {

            $add['SSO_UC_AUTH_KEY'] = $config['SSO_UC_AUTH_KEY'] = C('DATA_AUTH_KEY');
            $add['SSO_UC_DB_DSN'] = $config['SSO_UC_DB_DSN'] = 'mysqli://' . C('DB_USER') . ':' . C('DB_PWD') . '@' . C('DB_HOST') . ':' . C('DB_PORT') . '/' . C('DB_NAME');
            $add['SSO_UC_TABLE_PREFIX'] = $config['SSO_UC_TABLE_PREFIX'] = C('DB_PREFIX');
        }

        $content = file_get_contents('./Conf/user.php');
        $content = preg_replace('/\'UC_AUTH_KEY\', \'.*?\'/i', '\'UC_AUTH_KEY\', \'' . $config['SSO_UC_AUTH_KEY'] . '\'', $content);
        $content = preg_replace('/\'UC_DB_DSN\', \'.*?\'/i', '\'UC_DB_DSN\', \'' . $config['SSO_UC_DB_DSN'] . '\'', $content);
        $content = preg_replace('/\'UC_TABLE_PREFIX\', \'.*?\'/i', '\'UC_TABLE_PREFIX\', \'' . $config['SSO_UC_TABLE_PREFIX'] . '\'', $content);
        file_put_contents('./Conf/user.php', $content);

        $configModel = D('Config');
        if (!empty($add)) {
            foreach ($add as $k => $v) {
                $data_config['name'] = '_' . strtoupper(CONTROLLER_NAME) . '_' . strtoupper($k);
                $data_config['type'] = 0;
                $data_config['title'] = '';
                $data_config['group'] = 0;
                $data_config['extra'] = '';
                $data_config['remark'] = '';
                $data_config['create_time'] = time();
                $data_config['update_time'] = time();
                $data_config['status'] = 1;
                $data_config['value'] = $v;
                $data_config['sort'] = 0;
                $configModel->add($data_config, null, true);
                $tag = 'conf_' . strtoupper(CONTROLLER_NAME) . '_' . strtoupper($k);
                S($tag, null);
            }
        }


    }

    private function check_link($url)
    {
        $requester = new requester($url);
        $requester->charset = "utf-8";
        $requester->content_type = 'application/x-www-form-urlencoded';
        $requester->data = "";
        $requester->enableCookie = true;
        $requester->enableHeaderOutput = false;
        $requester->method = "post";

        $arr = $requester->request();
        return $arr[1];
    }

    public function ssoList()
    {
        //读取规则列表
        $map = array('status' => array('EGT', 0));
        $model = M('sso_app');
        $appList = $model->where($map)->order('id asc')->select();

        foreach ($appList as &$v) {
            $url = $v['url'] . '/' . $v['path'] . '?code=' . urlencode(think_encrypt('action=test&time='.time()));
            $arr = $this->check_link($url);
            $v['link_status'] = $v['status'] == 1 ? ($arr === 'success' ? '<span style="color:green">'.L('_SUCCESS__LINK_').'</span>' : '<span style="color:red">'.L('_FAIL__LINK_').'</span>') : '<span style="color:red">'.L('_FAIL__LINK_LIMITED_').'</span>';
        }
        unset($v);
        //显示页面
        $builder = new AdminListBuilder();
        $builder->title(L('_SINGLE_POINT_LOGIN_APPLICATION_LIST_'))
            ->buttonNew(U('editSsoApp'))
            ->setStatusUrl(U('setSsoAppStatus'))->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()
            ->keyTitle()
            ->keyText('url', L('_WEBSITE_PATH_'))
            ->keyText('path', L('_CATEGORY_API_'))
            ->keyStatus()
            ->keyText('link_status', L('_CONNECTED_STATE_'))
            ->keyDoActionEdit('editSsoApp?id=###')
            ->data($appList)
            ->display();
    }

    public function editSsoApp()
    {
        $aId = I('id', 0, 'intval');
        $model = D('Sso');
        if (IS_POST) {
            $data['title'] = I('post.title', '', 'op_t');
            $data['status'] = I('post.status', 1, 'intval');
            $data['url'] = I('post.url', '', 'op_t');
            $data['path'] = I('post.path', '', 'op_t');
            $config = $this->getConfig();
            if ($aId != 0) {
                $data['id'] = $aId;
                $res = $model->editApp($data);
                $config['APP_ID'] = $aId;
            } else {
                $res = $model->addApp($data);
                $config['APP_ID'] = $res;
            }
            D('sso_app')->where(array('id' => $config['APP_ID']))->setField('config', serialize($config));
            $this->success(($aId == 0 ? L('_ADD_') : L('_EDIT_')) . L('_SUCCESS_'), $aId == 0 ? U('', array('id' => $res)) : '');
            /*            if ($res) {
                            $this->success(($aId == 0 ? L('_ADD_') : L('_EDIT_')) . L('_SUCCESS_'));
                        } else {
                            $this->error(($aId == 0 ? L('_ADD_') : L('_EDIT_')) . L('_FAILURE_'));
                        }*/
        } else {
            $builder = new AdminConfigBuilder();
            if ($aId != 0) {
                $app = $model->getApp(array('id' => $aId));
            } else {
                $app = array('status' => 1, 'path' => 'OcApi/oc.php');
            }
            $app['config'] = $this->parseConfigToString(unserialize($app['config']));
            $builder->title(($aId == 0 ? L('_NEW_') : L('_EDIT_')) . L('_APPLICATION_'))->keyId()->keyText('title', L('_NAME_'))
                ->keyText('url', L('_ROOT_DIRECTORY_'), L('_WRITE_NEED_TIP_'))
                ->keyText('path', L('_PATH_'))
                ->keyStatus()
                ->keyLabel('config', L('_CONFIGURATION_INFORMATION_'), L('_SAVE_THE_FOLLOWING_CONTENTS_TO_THE_APPLICATION_S_CONFIGURATION_FILE_'))
                ->data($app)
                ->buttonSubmit(U('editSsoApp'))->buttonBack()->display();
        }
    }


    public function setSsoAppStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        $builder->doSetStatus('sso_app', $ids, $status);
    }

    private function parseConfigToString($config = array())
    {

        $note['SSO_SWITCH'] = L('_SINGLE_SIGN_ON_SWITCH_');
        $note['SSO_DB_HOST'] = L('_USER_CENTER_HOST_');
        $note['SSO_DB_NAME'] = L('_USER_CENTER_DATABASE_NAME_');
        $note['SSO_DB_USER'] = L('_USER_CENTER_DATABASE_USER_NAME_');
        $note['SSO_DB_PWD'] = L('_USER_CENTER_DATABASE_PASSWORD_');
        $note['SSO_DB_PORT'] = L('_USER_CENTER_DATABASE_PORT_');
        $note['SSO_DB_PREFIX'] = L('_USER_CENTER_DATABASE_PREFIX_');
        $note['SSO_DATA_AUTH_KEY'] = L('_USER_CENTER_DATABASE_KEY_');
        $note['OC_HOST'] = L('_ADDRESS_HOST_');
        $note['APP_ID'] = '应用ID';
        $note['OC_SESSION_PRE'] = 'session前缀';

        $str = 'array(<br>';
        foreach ($config as $key => $val) {
            $str .= '\'' . $key . '\'=>\'' . $val . '\', //' . $note[$key] . '<br>';
        }
        $str .= ');';
        return $str;
    }

    private function getConfig()
    {
        $db_config = require('./Conf/common.php');
        $config = array(
            'SSO_SWITCH' => 1,
            'SSO_DB_HOST' => $db_config['DB_HOST'],
            'SSO_DB_NAME' => $db_config['DB_NAME'],
            'SSO_DB_USER' => $db_config['DB_USER'],
            'SSO_DB_PWD' => $db_config['DB_PWD'],
            'SSO_DB_PORT' => $db_config['DB_PORT'],
            'SSO_DB_PREFIX' => $db_config['DB_PREFIX'],
            'SSO_DATA_AUTH_KEY' => $db_config['DATA_AUTH_KEY'],
            'OC_HOST' => 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__,
            'OC_SESSION_PRE' => $db_config['SESSION_PREFIX'],
        );
        return $config;
    }


}
