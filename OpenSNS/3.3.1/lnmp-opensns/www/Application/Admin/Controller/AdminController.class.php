<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\AuthRuleModel;
use Admin\Model\AuthGroupModel;
use Vendor\requester;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AdminController extends Controller
{

    /**
     * 后台控制器初始化
     */
    protected function _initialize()
    {
        // 获取当前用户ID
        define('UID', is_login());
        if (!UID) {// 还没登录 跳转到登录页面
            $this->redirect('Public/login');
        }
        /* 读取数据库中的配置 */
        $config = S('DB_CONFIG_DATA');
        if (!$config) {
            $config = api('Config/lists');
            S('DB_CONFIG_DATA', $config);
        }
        C($config); //添加配置

        // 是否是超级管理员
        define('IS_ROOT', is_administrator());
        if (!IS_ROOT && C('ADMIN_ALLOW_IP')) {
            // 检查IP地址访问
            if (!in_array(get_client_ip(), explode(',', C('ADMIN_ALLOW_IP')))) {
                $this->error(L('_FORBID_403_'));
            }
        }
        // 检测访问权限
        $access = $this->accessControl();
        if ($access === false) {
            $this->error(L('_FORBID_403_'));
        } elseif ($access === null) {
            $dynamic = $this->checkDynamic();//检测分类栏目有关的各项动态权限
            if ($dynamic === null) {
                //检测非动态权限
                $rule = strtolower(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);
                if (!$this->checkRule($rule, array('in', '1,2'))) {
                    $this->error(L('_VISIT_NOT_AUTH_'));
                }
            } elseif ($dynamic === false) {
                $this->error(L('_VISIT_NOT_AUTH_'));
            }
        }
        $this->assign('__MANAGE_COULD__', $this->checkRule('admin/module/lists', array('in', '1,2')));

        $this->assign('__MENU__', $this->getMenus());
        $this->assign('__MODULE_MENU__', $this->getModules());

        $this->checkUpdate();
        //$this->getReport();
        import_lang(ucfirst(CONTROLLER_NAME));
    }

    /**
     * 权限检测
     * @param string $rule 检测的规则
     * @param string $mode check模式
     * @return boolean
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function checkRule($rule, $type = AuthRuleModel::RULE_URL, $mode = 'url')
    {
        if (IS_ROOT) {
            return true;//管理员允许访问任何页面
        }
        static $Auth = null;
        if (!$Auth) {
            $Auth = new \Think\Auth();
        }
        if (!$Auth->check($rule, UID, $type, $mode)) {
            return false;
        }
        return true;
    }

    /**
     * 检测是否是需要动态判断的权限
     * @return boolean|null
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限
     *
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    protected function checkDynamic()
    {
        if (IS_ROOT) {
            return true;//管理员允许访问任何页面
        }
        return null;//不明,需checkRule
    }


    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function accessControl()
    {
        if (IS_ROOT) {
            return true;//管理员允许访问任何页面
        }
        $allow = C('ALLOW_VISIT');
        $deny = C('DENY_VISIT');
        $check = strtolower(CONTROLLER_NAME . '/' . ACTION_NAME);
        if (!empty($deny) && in_array_case($check, $deny)) {
            return false;//非超管禁止访问deny中的方法
        }
        if (!empty($allow) && in_array_case($check, $allow)) {
            return true;
        }
        return null;//需要检测节点权限
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供M函数使用的参数
     * @param array $data 修改的数据
     * @param array $where 查询时的where()方法的参数
     * @param array $msg 执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function editRow($model, $data, $where, $msg)
    {
        $id = array_unique((array)I('id', 0));
        $id = is_array($id) ? implode(',', $id) : $id;
        $where = array_merge(array('id' => array('in', $id)), (array)$where);
        $msg = array_merge(array('success' => L('_OPERATION_SUCCESS_'), 'error' => L('_OPERATION_FAILED_'), 'url' => '', 'ajax' => IS_AJAX), (array)$msg);
        if (M($model)->where($where)->save($data) !== false) {
            $this->success($msg['success'], $msg['url'], $msg['ajax']);
        } else {
            $this->error($msg['error'], $msg['url'], $msg['ajax']);
        }

    }

    /**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array $where 查询时的 where()方法的参数
     * @param array $msg 执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function forbid($model, $where = array(), $msg = array('success' => '状态禁用成功！', 'error' => '状态禁用失败！'))
    {
        $data = array('status' => 0);
        $this->editRow($model, $data, $where, $msg);
    }

    /**
     * 恢复条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array $where 查询时的where()方法的参数
     * @param array $msg 执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function resume($model, $where = array(), $msg = array('success' => '状态恢复成功！', 'error' => '状态恢复失败！'))
    {
        $data = array('status' => 1);
        $this->editRow($model, $data, $where, $msg);
    }

    /**
     * 还原条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array $where 查询时的where()方法的参数
     * @param array $msg 执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author huajie  <banhuajie@163.com>
     */
    protected function restore($model, $where = array(), $msg = array('success' => '状态还原成功！', 'error' => '状态还原失败！'))
    {
        $data = array('status' => 1);
        $where = array_merge(array('status' => -1), $where);
        $this->editRow($model, $data, $where, $msg);
    }

    /**
     * 条目假删除
     * @param string $model 模型名称,供D函数使用的参数
     * @param array $where 查询时的where()方法的参数
     * @param array $msg 执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function delete($model, $where = array(), $msg = array('success' => '删除成功！', 'error' => '删除失败！'))
    {
        $data['status'] = -1;
        $data['update_time'] = NOW_TIME;
        $this->editRow($model, $data, $where, $msg);
    }

    /**
     * 设置一条或者多条数据的状态
     */
    public function setStatus($Model = CONTROLLER_NAME)
    {

        $ids = I('request.ids');
        $status = I('request.status');
        if (empty($ids)) {
            $this->error(L('_PLEASE_CHOOSE_THE_DATA_TO_BE_OPERATED_'));
        }

        $map['id'] = array('in', $ids);
        switch ($status) {
            case -1 :
                $this->delete($Model, $map, array('success' => L('_DELETE_SUCCESS_EXCLAMATION_'), 'error' => L('_DELETE_FAILED_EXCLAMATION_')));
                break;
            case 0  :
                $this->forbid($Model, $map, array('success' => L('_DISABLE_SUCCESS_'), 'error' => L('_DISABLE_FAIL_')));
                break;
            case 1  :
                $this->resume($Model, $map, array('success' => L('_ENABLE_SUCCESS_'), 'error' => L('_ENABLE_FAILED_')));
                break;
            default :
                $this->error(L('_PARAMETER_ERROR_'));
                break;
        }
    }

    /**获取模块列表，用于显示在左侧
     * @auth 陈一枭 <yixiao2020@qq.com>
     */
    public function getModules()
    {
        $tag = 'ADMIN_MODULES_' . is_login();
        $modules = S($tag);
        if ($modules === false) {
            $modules = D('Module')->getAll();
            foreach ($modules as $key => &$v) {
                if($v['menu_hide']==1){
                    unset($modules[$key]);
                    continue;
                }
                $rule = strtolower($v['admin_entry']);
                  /*if (!$this->checkRule($rule, array('in', '1,2'))) {
                      unset($modules[$key]);
                  }*/
                if ($rule) {
                    $menu = M('Menu')->where(array('module' => $v['name'], 'pid' => 0))->find();
                    if ($menu) {
                        $v['children'] = $this->getSubMenus($menu['id']);
                    }
                }
            }
            S($tag, $modules);
        }
        return $modules;
    }

    public function getSubMenus($pid)
    {
        $menus = array();
        //生成child树
        $groups = M('Menu')->where("pid = {$pid}")->distinct(true)->field("`group`")->order('sort asc')->select();

        if ($groups) {
            $groups = array_column($groups, 'group');
        } else {
            $groups = array();
        }
        //获取二级分类的合法url
        $where = array();
        $where['pid'] = $pid;
        $where['hide'] = 0;
        if (!C('DEVELOP_MODE')) { // 是否开发者模式
            $where['is_dev'] = 0;
        }
        $second_urls = M('Menu')->where($where)->getField('id,url');

        if (!IS_ROOT) {
            // 检测菜单权限
            $to_check_urls = array();
            foreach ($second_urls as $key => $to_check_url) {
                if (stripos($to_check_url, MODULE_NAME) !== 0) {
                    $rule = MODULE_NAME . '/' . $to_check_url;
                } else {
                    $rule = $to_check_url;
                }
                if ($this->checkRule($rule, AuthRuleModel::RULE_URL, null))
                    $to_check_urls[] = $to_check_url;
            }
        }
        // 按照分组生成子菜单树
        foreach ($groups as $g) {
            $map = array('group' => $g);
            if (isset($to_check_urls)) {
                if (empty($to_check_urls)) {
                    // 没有任何权限
                    continue;
                } else {
                    $map['url'] = array('in', $to_check_urls);
                }
            }
            $map['pid'] = $pid;
            $map['hide'] = 0;
            if (!C('DEVELOP_MODE')) { // 是否开发者模式
                $map['is_dev'] = 0;
            }
            $menuList = M('Menu')->where($map)->field('id,pid,title,url,tip')->order('sort asc')->select();
            $menus[$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $pid);
            if (empty($menus[$g])) {
                unset($menus[$g]);
            }
        }
        return $menus;

    }

    /**
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final public function getMenus($controller = CONTROLLER_NAME)
    {
        $tag = 'ADMIN_MENU_LIST' . is_login() . $controller;
        $menus = S($tag);
        if ($menus === false) {
            // 获取主菜单
            $where['pid'] = 0;
            
            if (!C('DEVELOP_MODE')) { // 是否开发者模式
                $where['is_dev'] = 0;
            }
            $menus['main'] = M('Menu')->where($where)->order('sort asc')->select();

            foreach ($menus['main'] as &$v) {
                $v['children'] = $this->getSubMenus($v['id']);
                if ($v['url'] == 'Cloud/index') {
                    extra_addons_menu($v);
                }
            }
            unset($v);
            $menus['child'] = array(); //设置子节点

            //高亮主菜单
            //$current = M('Menu')->where("url like '%{$controller}/" . ACTION_NAME . "%'")->field('id')->find();
            $current = M('Menu')->where("url like '{$controller}/" . ACTION_NAME . "%' OR url like '%/{$controller}/" . ACTION_NAME . "%'  ")->field('id')->find();
            if ($current) {
                $nav = D('Menu')->getPath($current['id']);
                $nav_first_title = $nav[0]['title'];

                foreach ($menus['main'] as $key => $item) {
                    if (!is_array($item) || empty($item['title']) || empty($item['url'])) {
                        $this->error(L('_CLASS_CONTROLLER_ERROR_PARAM_', array('menus' => $menus)));
                    }
                    if (stripos($item['url'], MODULE_NAME) !== 0) {
                        $item['url'] = MODULE_NAME . '/' . $item['url'];
                    }
                    // 判断主菜单权限
                    if (!IS_ROOT && !$this->checkRule($item['url'], AuthRuleModel::RULE_MAIN, null)) {
                        unset($menus['main'][$key]);
                        continue;//继续循环
                    }
                    // 获取当前主菜单的子菜单项
                    if ($item['title'] == $nav_first_title) {
                        $menus['main'][$key]['class'] = 'active';
                        //生成child树
                        $groups = M('Menu')->where("pid = {$item['id']}")->distinct(true)->field("`group`")->order('sort asc')->select();

                        if ($groups) {
                            $groups = array_column($groups, 'group');
                        } else {
                            $groups = array();
                        }

                        //获取二级分类的合法url
                        $where = array();
                        $where['pid'] = $item['id'];
                        $where['hide'] = 0;
                        if (!C('DEVELOP_MODE')) { // 是否开发者模式
                            $where['is_dev'] = 0;
                        }
                        $second_urls = M('Menu')->where($where)->getField('id,url');

                        if (!IS_ROOT) {
                            // 检测菜单权限
                            $to_check_urls = array();
                            foreach ($second_urls as $key => $to_check_url) {
                                if (stripos($to_check_url, MODULE_NAME) !== 0) {
                                    $rule = MODULE_NAME . '/' . $to_check_url;
                                } else {
                                    $rule = $to_check_url;
                                }
                                if ($this->checkRule($rule, AuthRuleModel::RULE_URL, null))
                                    $to_check_urls[] = $to_check_url;
                            }
                        }
                        // 按照分组生成子菜单树
                        foreach ($groups as $g) {
                            $map = array('group' => $g);
                            if (isset($to_check_urls)) {
                                if (empty($to_check_urls)) {
                                    // 没有任何权限
                                    continue;
                                } else {
                                    $map['url'] = array('in', $to_check_urls);
                                }
                            }
                            $map['pid'] = $item['id'];
                            $map['hide'] = 0;
                            if (!C('DEVELOP_MODE')) { // 是否开发者模式
                                $map['is_dev'] = 0;
                            }
                            $menuList = M('Menu')->where($map)->field('id,pid,title,url,tip')->order('sort asc')->select();
                            $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                        }
                        if ($menus['child'] === array()) {
                            //$this->error('主菜单下缺少子菜单，请去系统=》后台菜单管理里添加');
                        }
                    }
                }
            }
            S($tag, $menus);
        }
        return $menus;
    }

    /**
     * 返回后台节点数据
     * @param boolean $tree 是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    final protected function returnNodes($tree = true)
    {
        static $tree_nodes = array();
        if ($tree && !empty($tree_nodes[(int)$tree])) {
            return $tree_nodes[$tree];
        }
        if ((int)$tree) {
            $list = M('Menu')->field('id,pid,title,url,tip,hide')->order('sort asc')->select();
            foreach ($list as $key => $value) {
                if (stripos($value['url'], MODULE_NAME) !== 0) {
                    $list[$key]['url'] = MODULE_NAME . '/' . $value['url'];
                }
            }
            $nodes = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'operator', $root = 0);
            foreach ($nodes as $key => $value) {
                if (!empty($value['operator'])) {
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        } else {
            $nodes = M('Menu')->field('title,url,tip,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value) {
                if (stripos($value['url'], MODULE_NAME) !== 0) {
                    $nodes[$key]['url'] = MODULE_NAME . '/' . $value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree] = $nodes;
        return $nodes;
    }


    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  userList.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: userList.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: userList.html?r=5
     *
     * @param sting|Model $model 模型名或模型实例
     * @param array $where where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order 排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array $base 基本的查询条件
     * @param boolean $field 单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists($model, $where = array(), $order = '', $base = array('status' => array('egt', 0)), $field = true)
    {
        $options = array();
        $REQUEST = (array)I('request.');
        if (is_string($model)) {
            $model = M($model);
        }

        $OPT = new \ReflectionProperty($model, 'options');
        $OPT->setAccessible(true);

        $pk = $model->getPk();
        if ($order === null) {
            //order置空
        } else if (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array('desc', 'asc'))) {
            $options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
        } elseif ($order === '' && empty($options['order']) && !empty($pk)) {
            $options['order'] = $pk . ' desc';
        } elseif ($order) {
            $options['order'] = $order;
        }
        unset($REQUEST['_order'], $REQUEST['_field']);

        $options['where'] = array_filter(array_merge((array)$base, /*$REQUEST,*/
            (array)$where), function ($val) {
            //if ($val === '' || $val === null) {
            if ($val === null) {
                return false;
            } else {
                return true;
            }
        });
        if (empty($options['where'])) {
            unset($options['where']);
        }
        $options = array_merge((array)$OPT->getValue($model), $options);
        $total = $model->where($options['where'])->count();

        if (isset($REQUEST['r'])) {
            $listRows = (int)$REQUEST['r'];
        } else {
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\PageBack($total, $listRows, $REQUEST);
        if ($total > $listRows) {
            $page->setConfig('theme', '%UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %HEADER%');
        }
        $p = $page->show();
        $this->assign('_page', $p ? $p : '');
        $this->assign('_total', $total);
        $options['limit'] = $page->firstRow . ',' . $page->listRows;

        $model->setProperty('options', $options);

        return $model->field($field)->select();
    }

    public function _empty()
    {
        $this->error(L('_ERROR_404_2_'));
    }

    public function getReport()
    {

        $result = S('os_report');
        if (!$result) {
            $url = '/index.php?s=/report/index/check.html';
            $result = $this->visitUrl($url);
            S('os_report', $result, 60 * 60);
        }
        $report = json_decode($result[1], true);
        $ctime = filemtime("version.ini");
        $check_exists = file_exists('./Application/Admin/Data/' . $report['title'] . '.txt');
        if (!$check_exists) {
            $this_update = explode("\n", $report['this_update']);
            $future_update = explode("\n", $report['future_update']);
            $this->assign('this_update', $this_update);
            $this->assign('future_update', $future_update);
            $this->assign('report', $report);
        }

    }

    public function submitReport()
    {
        $aQ1 = $data['q1'] = I('post.q1', '', 'op_t');
        $aQ2 = $data['q2'] = I('post.q2', '', 'op_t');
        $aQ3 = $data['q3'] = I('post.q3', '', 'op_t');
        $aQ4 = $data['q4'] = I('post.q4', '', 'op_t');

        if (empty($aQ1) || empty($aQ2) || empty($aQ3) || empty($aQ4)) {
            $this->error(L('_INSURE_PLEASE_') . L('_WAVE_'));
        }

        $data['host'] = 'http://' . $_SERVER['HTTP_HOST'] . __ROOT__;
        $data['ip'] = get_client_ip(1);
        $url = '/index.php?s=/report/index/addFeedback.html';
        $result = $this->visitUrl($url, $data);
        $res = json_decode($result[1], true);
        if ($res['status']) {
            file_put_contents('./Application/Admin/Data/' . $res['data']['report_name'] . '.txt', $result[1]);
            $this->success(L('_THANK_YOU_FOR_YOUR_COOPERATION_'));
        } else {
            $this->error($res['info']);
        }

    }

    private function visitUrl($url, $data = '')
    {
        $host = 'http://demo.ocenter.cn';
        $url = $host . $url;
        $requester = new requester($url);
        $requester->charset = "utf-8";
        $requester->content_type = 'application/x-www-form-urlencoded';
        $requester->data = http_build_query($data);
        $requester->enableCookie = true;
        $requester->enableHeaderOutput = false;
        $requester->method = "post";
        $arr = $requester->request();
        return $arr;
    }

    protected function checkUpdate()
    {
        if (C('AUTO_UPDATE')) {
            $can_update = D('Version')->checkUpdate();
        } else {
            $can_update = 0;
        }
        $this->assign('update', $can_update);
    }
}
