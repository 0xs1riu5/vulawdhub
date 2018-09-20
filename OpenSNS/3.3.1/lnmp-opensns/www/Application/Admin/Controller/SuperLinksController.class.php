<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/25
 * Time: 14:30
 */

namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;

class SuperLinksController extends AdminController
{
    public function index()
    {
        $linkList = M('super_links')->select();

        $builder = new AdminListBuilder;
        $builder->meta_title = '友情链接';
        $builder->title('友情链接');
        $builder->buttonNew(U('SuperLinks/edit'))->setStatusUrl(U('savestatus'))->buttonEnable()->buttonDisable()
            ->button(L('_DELETE_'), array('class' => 'btn ajax-post confirm', 'url' => U('del'), 'target-form' => 'ids', 'confirm-info' => "确认删除友情链接？删除后不可恢复！"));
        $builder->keyId()
            ->keyText('title', '站点名称')
            ->keyText('link', '链接地址')
            ->keyText('type', '类型（1：图片， 2：普通）')
            ->keyStatus()
            ->keyText('level', '优先级')
            ->keyCreateTime()
            ->keyDoActionEdit('SuperLinks/edit?id=###')
            ->data($linkList)
            ->display();
    }

    /* 编辑友情链接 */
    public function edit(){
        $id     =   I('get.id','');
        if($id) {
            $this->meta_title = '修改友情链接';
            $current = U('/admin/superlinks/index');///
            $detail = D('Common/SuperLinks')->detail($id);
            $this->assign('info',$detail);
        } else {
            $this->meta_title = '添加友情链接';
            $current = U('/admin/superlinks/index');
            $this->assign('sign', 'add');
        }

        $this->assign('current',$current);
        $this->display();
    }

    /* 删除友情链接 */
    public function del($ids){
        $this->meta_title = '删除友情链接';
        if(D('Common/SuperLinks')->where(array('id' => array('in', $ids)))->del()){
            $this->success('删除成功');
        }else{
            $this->error(D('Common/SuperLinks')->getError());
        }
    }

    /* 更新友情链接 */
    public function update(){
        $this->meta_title = '更新友情链接';
        $res = D('Common/SuperLinks')->update();
        if(!$res){
            $this->error(D('Common/SuperLinks')->getError());
        }else{
            $this->success('更新成功',  U('Admin/SuperLinks/index'));
        }
    }
    /*多选改变启/禁用*/
    public function savestatus($ids, $status){
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        $map['id']=$ids;
        $count=count($ids);


        if($status==1){
            foreach($ids as $v){
                if(D('Common/SuperLinks')->off($v)){
                    $flag=1;
                }
                else{
                    $flag=0;
                }
            }
            if($flag==0) {
                $this->error(D('Common/SuperLinks')->getError());
            }else{
                $this->success('成功启用友情链接');
            }

        }
        else{
            foreach($ids as $v){
                if(D('Common/SuperLinks')->forbidden($v)){
                    $flag=1;
                }
                else{
                    $flag=0;
                }
            }
            if($flag==1){
                $this->success('成功禁用友情链接');
            }else{
                $this->error(D('Common/SuperLinks')->getError());
            }
        }
    }
}