<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台配置控制器
 * @author yangweijie <yangweijiester@gmail.com>
 */
class MenuController extends AdminController {

    /**
     * 后台菜单首页
     * @return none
     */
    public function index(){
        $pid  = I('get.pid',0);
        if($pid){
            $data = M('Menu')->where("id={$pid}")->field(true)->find();
            $this->assign('data',$data);
        }
        $title      =   trim(I('get.title'));
        $type       =   C('CONFIG_GROUP_LIST');
        $all_menu   =   M('Menu')->getField('id,title');
        $map['pid'] =   $pid;
        if($title)
            $map['title'] = array('like',"%{$title}%");
        $list       =   M("Menu")->where($map)->field(true)->order('sort asc,id asc')->select();
        int_to_string($list,array('hide'=>array(1=>L('_YES_'),0=>L('_NOT_')),'is_dev'=>array(1=>L('_YES_'),0=>L('_NOT_'))));
        if($list) {
            foreach($list as &$key){
                if($key['pid']){
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->meta_title = L('_MENU_LIST_');
        $this->display();
    }

    /**
     * 新增菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function add(){
        if(IS_POST){
            $Menu = D('Menu');
            $data = $Menu->create();
            if($data){
                $id = $Menu->add();
                if($id){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_menu', 'Menu', $id, UID);
                    $this->success(L('_SUCCESS_ADD_'), Cookie('__forward__'));
                } else {
                    $this->error(L('_FAIL_ADD_'));
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            if(I('pid')!=0){
                $modelName=D('Menu')->where(array('id'=>I('pid'),'module'=>array('neq','')))->find();
                $this->assign('modelName',$modelName);
            }

            $this->assign('info',array('pid'=>I('pid')));
            $menus = M('Menu')->field(true)->select();
            $menus = D('Common/Tree')->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>L('_MENU_TOP_'))), $menus);
            $this->assign('Modules',D('Module')->getAll());
            $this->assign('Menus', $menus);
            $this->meta_title = L('_MENU_ADD_');
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Menu = D('Menu');
            $data = $Menu->create();
            if($data){
                if($Menu->save()!== false){
                    // S('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_menu', 'Menu', $data['id'], UID);
                    $this->success(L('_SUCCESS_UPDATE_'), Cookie('__forward__'));
                } else {
                    $this->error(L('_FAIL_UPDATE_'));
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Menu')->field(true)->find($id);
            $menus = M('Menu')->field(true)->select();
            $menus = D('Common/Tree')->toFormatTree($menus);

            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>L('_MENU_TOP_'))), $menus);
            $this->assign('Menus', $menus);
            $this->assign('Modules',D('Module')->getAll());
            if(false === $info){
                $this->error(L('_ERROR_MENU_INFO_GET_'));
            }
            $this->assign('info', $info);
            $this->meta_title = L('_MENU_BG_EDIT_');
            $this->display();
        }
    }

    /**
     * 删除后台菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error(L('_ERROR_DATA_SELECT_').L('_EXCLAMATION_'));
        }

        $map = array('id' => array('in', $id) );
        if(M('Menu')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_menu', 'Menu', $id, UID);
            $this->success(L('_SUCCESS_DELETE_'));
        } else {
            $this->error(L('_FAIL_DELETE_'));
        }
    }

    public function toogleHide($id,$value = 1){
        $this->editRow('Menu', array('hide'=>$value), array('id'=>$id));
    }

    public function toogleDev($id,$value = 1){
        $this->editRow('Menu', array('is_dev'=>$value), array('id'=>$id));
    }

    public function importFile($tree = null, $pid=0){
        if($tree == null){
            $file = APP_PATH."Admin/Conf/Menu.php";
            $tree = require_once($file);
        }
        $menuModel = D('Menu');
        foreach ($tree as $value) {
            $add_pid = $menuModel->add(
                array(
                    'title'=>$value['title'],
                    'url'=>$value['url'],
                    'pid'=>$pid,
                    'hide'=>isset($value['hide'])? (int)$value['hide'] : 0,
                    'tip'=>isset($value['tip'])? $value['tip'] : '',
                    'group'=>$value['group'],
                )
            );
            if($value['operator']){
                $this->import($value['operator'], $add_pid);
            }
        }
    }

    public function import(){
        if(IS_POST){
            $tree = I('post.tree');
            $lists = explode(PHP_EOL, $tree);
            $menuModel = M('Menu');
            if($lists == array()){
                $this->error(L('_PLEASE_FILL_IN_THE_FORM_OF_A_BATCH_IMPORT_MENU,_AT_LEAST_ONE_MENU_'));
            }else{
                $pid = I('post.pid');
                foreach ($lists as $key => $value) {
                    $record = explode('|', $value);
                    if(count($record) == 2){
                        $menuModel->add(array(
                            'title'=>$record[0],
                            'url'=>$record[1],
                            'pid'=>$pid,
                            'sort'=>0,
                            'hide'=>0,
                            'tip'=>'',
                            'is_dev'=>0,
                            'group'=>'',
                        ));
                    }
                }
                $this->success(L('_IMPORT_SUCCESS_'),U('index?pid='.$pid));
            }
        }else{
            $this->meta_title = L('_BATCH_IMPORT_BACKGROUND_MENU_');
            $pid = (int)I('get.pid');
            $this->assign('pid', $pid);
            $data = M('Menu')->where("id={$pid}")->field(true)->find();
            $this->assign('data', $data);
            $this->display();
        }
    }

    /**
     * 菜单排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            $pid = I('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            $map['hide']=0;
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = M('Menu')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = L('_MENU_SORT_');
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Menu')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success(L('_SORT_OF_SUCCESS_'));
            }else{
                $this->eorror(L('_SORT_OF_FAILURE_'));
            }
        }else{
            $this->error(L('_ILLEGAL_REQUEST_'));
        }
    }
}
