<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Model\AuthGroupModel;

/**
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 */
class ModelController extends AdminController {

    public function _initialize(){
        parent::_initialize();
        $this->getMenu();
    }
    /**
     * 显示左边菜单，进行权限控制
     * @author huajie <banhuajie@163.com>
     */
    protected function getMenu()
    {
        //获取动态分类
        $cate_auth = AuthGroupModel::getAuthCategories(UID); //获取当前用户所有的内容权限节点
        // dump($cate_auth);exit;
        $cate_auth = $cate_auth == null ? array() : $cate_auth;
        $cate = M('Category')->where(array('status' => 1))->field('id,title,pid,allow_publish')->order('pid,sort')->select();

        //没有权限的分类则不显示
        if (!IS_ROOT) {
            foreach ($cate as $key => $value) {
                if (!in_array($value['id'], $cate_auth)) {
                    unset($cate[$key]);
                }
            }
        }

        $cate = list_to_tree($cate); //生成分类树

        //获取分类id
        $cate_id = I('param.cate_id');
        $this->cate_id = $cate_id;

        //是否展开分类
        $hide_cate = true;

        //生成每个分类的url
        foreach ($cate as $key => &$value) {
            $value['url'] = 'Article/index?cate_id=' . $value['id'];
            if ($cate_id == $value['id'] && $hide_cate) {
                $value['current'] = true;
            } else {
                $value['current'] = false;
            }
            if (!empty($value['_child'])) {
                $is_child = false;
                foreach ($value['_child'] as $ka => &$va) {
                    $va['url'] = 'Article/index?cate_id=' . $va['id'];
                    if (!empty($va['_child'])) {
                        foreach ($va['_child'] as $k => &$v) {
                            $v['url'] = 'Article/index?cate_id=' . $v['id'];
                            $v['pid'] = $va['id'];
                            $is_child = $v['id'] == $cate_id ? true : false;
                        }
                    }
                    //展开子分类的父分类
                    if ($va['id'] == $cate_id || $is_child) {
                        $is_child = false;
                        if ($hide_cate) {
                            $value['current'] = true;
                            $va['current'] = true;
                        } else {
                            $value['current'] = false;
                            $va['current'] = false;
                        }
                    } else {
                        $va['current'] = false;
                    }
                }
            }
        }
        $this->assign('nodes', $cate);

        $this->assign('cate_id', $this->cate_id);

        //获取面包屑信息
        $nav = get_parent_category($cate_id);
        $this->assign('rightNav', $nav);
        //获取回收站权限
        $show_recycle = $this->checkRule('Admin/article/recycle');
        $this->assign('show_recycle', IS_ROOT || $show_recycle);
        //获取草稿箱权限
        $this->assign('show_draftbox', C('OPEN_DRAFTBOX'));
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
    protected function checkDynamic(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        //模型权限业务检查逻辑
        //
        //提供的工具方法：
        //$AUTH_GROUP = D('AuthGroup');
        // $AUTH_GROUP->checkModelId($mid);      //检查模型id列表是否全部存在
        // AuthGroupModel::getModelOfGroup($gid);//获取某个权限组拥有权限的模型id
        $model_ids = AuthGroupModel::getAuthModels(UID);
        $id        = I('id');
        switch(strtolower(ACTION_NAME)){
            case 'edit':    //编辑
            case 'update':  //更新
                if ( in_array($id,$model_ids) ) {
                    return true;
                }else{
                    return false;
                }
            case 'setstatus': //更改状态
                if ( is_array($id) && array_intersect($id,(array)$model_ids)==$id ) {
                    return true;
                }elseif( in_array($id,$model_ids) ){
                    return true;
                }else{
                    return false;
                }
        }

        return null;//不明,需checkRule
    }

    /**
     * 模型管理首页
     * @author huajie <banhuajie@163.com>
     */
    public function index(){

        $map = array('status'=>array('gt',-1));
        $list = $this->lists('Model',$map);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = L('_MODEL_MANAGEMENT_');
        $this->getMenu();
        $this->display();
    }

    /**
     * 新增页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function add(){
        //获取所有的模型
        $models = M('Model')->where(array('extend'=>0))->field('id,title')->select();

        $this->assign('models', $models);
        $this->meta_title = L('_NEW_MODEL_');
        $this->display();
    }

    /**
     * 编辑页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function edit(){
        $id = I('get.id','');
        if(empty($id)){
            $this->error(L('_PARAMETERS_CANT_BE_EMPTY_'));
        }

        /*获取一条记录的详细数据*/
        $Model = M('Model');
        $data = $Model->field(true)->find($id);
        if(!$data){
            $this->error($Model->getError());
        }

        $fields = M('Attribute')->where(array('model_id'=>$data['id']))->field('id,name,title,is_show')->select();
        //是否继承了其他模型
        if($data['extend'] != 0){
            $extend_fields = M('Attribute')->where(array('model_id'=>$data['extend']))->field('id,name,title,is_show')->select();
            $fields = array_merge($fields, $extend_fields);
        }

        /* 获取模型排序字段 */
        $field_sort = json_decode($data['field_sort'], true);
        if(!empty($field_sort)){
            /* 对字段数组重新整理 */
            $fields_f = array();
            foreach($fields as $v){
                $fields_f[$v['id']] = $v;
            }
            $fields = array();
            foreach($field_sort as $key => $groups){
                foreach($groups as $group){
                    $fields[$fields_f[$group]['id']] = array(
                        'id' => $fields_f[$group]['id'],
                        'name' => $fields_f[$group]['name'],
                        'title' => $fields_f[$group]['title'],
                        'is_show' => $fields_f[$group]['is_show'],
                        'group' => $key
                    );
                }
            }
            /* 对新增字段进行处理 */
            $new_fields = array_diff_key($fields_f,$fields);
            foreach ($new_fields as $value){
                if($value['is_show'] == 1){
                    array_unshift($fields, $value);
                }
            }
        }

        $this->assign('fields', $fields);
        $this->assign('info', $data);
        $this->meta_title = L('_EDIT_MODEL_');
        $this->display();
    }

    /**
     * 删除一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function del(){
        $ids = I('get.ids');
        empty($ids) && $this->error(L('_PARAMETERS_CANT_BE_EMPTY_'));
        $ids = explode(',', $ids);
        foreach ($ids as $value){
            $res = D('Model')->del($value);
            if(!$res){
                break;
            }
        }
        if(!$res){
            $this->error(D('Model')->getError());
        }else{
            $this->success(L('_DELETE_MODEL_SUCCESS_'));
        }
    }

    /**
     * 更新一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        $res = D('Model')->update();

        if(!$res){
            $this->error(D('Model')->getError());
        }else{
            $this->success($res['id']?L('_UPDATE_'):L('_NEW_SUCCESS_'), Cookie('__forward__'));
        }
    }

    /**
     * 生成一个模型
     * @author huajie <banhuajie@163.com>
     */
    public function generate(){
        if(!IS_POST){
            //获取所有的数据表
            $tables = D('Model')->getTables();

            $this->assign('tables', $tables);
            $this->meta_title = L('_GENERATIVE_MODEL_');
            $this->display();
        }else{
            $table = I('post.table');
            empty($table) && $this->error(L('_CHOOSE_THE_DATA_TABLE_TO_GENERATE_'));
            $res = D('Model')->generate($table);
            if($res){
                $this->success(L('_GENERATIVE_MODEL_SUCCESS_'), U('index'));
            }else{
                $this->error(D('Model')->getError());
            }
        }
    }
}
