<?php
/**
 * Created by PhpStorm.
 * User: zzl
 * Date: 2016/9/13
 * Time: 15:30
 * @author:zzl(郑钟良) zzl@ourstu.com
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Think\Controller;

class SensitiveController extends AdminController
{
    public function config()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        !isset($data['OPEN_SENSITIVE']) && $data['OPEN_SENSITIVE'] = 0;
        $builder->title('敏感词过滤设置')
            ->keyRadio('OPEN_SENSITIVE', '开启敏感词过滤', '', array(0 => '关闭', 1 => '开启'))
            ->data($data)
            ->buttonSubmit()->buttonBack()
            ->display();
    }

    public function index($page = 1, $r = 10)
    {
        list($list, $totalCount) = D('Sensitive')->getListPage($page, $r);
        $builder = new AdminListBuilder();
        $builder->title('敏感词列表')
            ->setStatusUrl(U('Sensitive/setStatus'))
            ->buttonNew(U('Sensitive/edit'))->buttonEnable()->buttonDisable()->buttonDelete()->buttonNew(U('Sensitive/addMore'), '批量添加')
            ->keyId()
            ->keyTitle('title', '敏感词')
            ->keyStatus()
            ->keyCreateTime()
            ->keyDoActionEdit('Sensitive/edit?id=###', '编辑')
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function edit()
    {
        $aId = I('id', 0, 'intval');
        $title = $aId ? '编辑' : '新增';
        $sensitiveModel = D('Sensitive');
        if (IS_POST) {
            $aTitle = I('post.title', '', 'text');
            if ($aTitle == '') {
                $this->error('敏感词不能为空！');
            }
            $map['title'] = $aTitle;
            $map['status'] = array('in', '0,1');
            if ($sensitiveModel->where($map)->find()) {
                $this->error('该敏感词已经存在！');
            }
            $res = $sensitiveModel->editData();
            if ($res) {
                S('replace_sensitive_words', null);
                $this->success($title . '敏感词成功！', U('Sensitive/index'));
            } else {
                $this->error($title . '敏感词失败！');
            }
        } else {
            if ($aId) {
                $data = $sensitiveModel->find($aId);
            } else {
                $data['status'] = 1;
            }
            $builder = new AdminConfigBuilder();
            $builder->title($title . '敏感词')
                ->keyId()
                ->keyTitle('title', '敏感词')
                ->keyStatus()
                ->keyCreateTime()
                ->data($data)
                ->buttonSubmit()->buttonBack()
                ->display();
        }
    }

    public function setStatus($ids, $status = 1)
    {
        !is_array($ids) && $ids = explode(',', $ids);
        $builder = new AdminListBuilder();
        S('replace_sensitive_words', null);
        $builder->doSetStatus('Sensitive', $ids, $status);
    }

    /**
     * 批量添加敏感词
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function addMore()
    {
        if (IS_POST) {
            $sensitiveModel = D('Sensitive');
            $aTitles = I('post.titles', '', 'text');
            $qian = array(" ", "　", "\t", "\n", "\r");
            $hou = array("", "", "", "", "");
            $aTitles = str_replace($qian, $hou, $aTitles);
            $aTitles = explode('|', $aTitles);
            $data = array();
            $time = time();
            $map['status'] = array('in', '0,1');
            foreach ($aTitles as $v) {
                if ($v != '') {
                    $map['title'] = $v;
                    if (!($sensitiveModel->where($map)->find()))
                        $data[] = array('title' => $v, 'status' => 1, 'create_time' => $time);
                }
            }
            if (!count($data)) {
                $this->error('这些敏感词都已经存在！');
            }
            $res = $sensitiveModel->addAll($data);
            S('replace_sensitive_words', null);
            if ($res) {
                $this->success('批量添加成功', U('Admin/Sensitive/index'));
            } else {
                $this->error('批量添加失败');
            }
        } else {
            $builder = new AdminConfigBuilder();
            $builder->title('批量添加敏感词')
                ->keyTextArea('titles', '敏感词', '用“|”分隔')
                ->buttonSubmit()->buttonBack()
                ->display();
        }
    }
}