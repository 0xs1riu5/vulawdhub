<?php

namespace Addons\Report\Controller;

use Admin\Controller\AddonsController;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminTreeListBuilder;

require_once(ONETHINK_ADDON_PATH . 'Report/Common/function.php');


class AdminController extends AddonsController
{

    public function buildList($page = 1, $r = 20)
    {
        $map['status'] = array('egt', 0);
        $list = M('Report')->where($map)->page($page, $r)->order('id asc')->select();
        $reportCount = M('Report')->where($map)->count();
        int_to_string($list);

        $builder = new AdminListBuilder();
        $builder->title("举报处理列表");

        $builder->buttonModalPopup(addons_url('Report://Admin/handleEject'), '', '批量处理', array('target-form' => 'ids'))
            ->buttonDisable(addons_url('Report://Admin/ignoreReport'), '忽略处理')
            ->buttonDelete(addons_url('Report://Admin/deleteReport'), '删除举报')
            ->keyId()
            ->keyLink('url', "举报链接", '{$url}')
            ->keyNickname('uid', "举报用户")
            ->keyText('reason', "举报原因")
            ->keyText('content', "举报描述")
            ->keyText('type', "举报类型")
            ->keyCreateTime('create_time', "创建时间")
            ->keyUpdateTime('handle_time', "处理时间")
            ->keyText('handle_result', "处理结果")
            ->keyDoActionModalPopup('Report://Admin/handleEject?ids=###|addons_url', '处理', '操作')
            ->keyDoActionEdit('Report://Admin/ignoreReport?ids=###|addons_url', '忽略处理')
            ->key('status', '状态', 'status', array('0' => '未处理', '1' => '处理中', '2' => '已处理'));

        $builder->data($list);
        $builder->pagination($reportCount, $r);
        $builder->display();
    }


    /**
     * 删除举报操作
     */
    public function deleteReport()
    {
        $ids = I('ids', array());
        $map['id'] = array('in', $ids);
        $result = D('Addons://Report/Report')->where($map)->delete();
        if ($result) {
            $this->success('删除成功', 0);
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 处理举报页面的弹窗实现
     */
    public function handleEject()
    {
        $ids = I('ids');                        //获取点击的ids
        if (is_array($ids)) {
            $ids = implode(',', $ids);              //数组转换成字符串，传到页面
        }
        $this->assign('ids', $ids);
        $this->display(T('Addons://Report@Report/handleeject'));
    }


    /**
     * 管理员处理后进行的操作
     */

    public function handleReport()
    {
        $ids = I('ids');                        //接收页面传出的ids
        $ids = explode(',', $ids);              //字符串转换成数组
        $map['id'] = array('in', $ids);

        $time = time();                       //获取当前时间时间戳
        $handleresult = I('post.handleresult', '', 'op_t');     //接收处理结果
        $handlepeople = is_login();                             //操作人的ID

        $data['handle_result'] = $handleresult;        //  处理结果
        $data['handle_time'] = $time;
        $data['handle_uid'] = $handlepeople;


        $handlestatus = I('post.user_choose', '', 'op_t');      //接收处理状态
        $data['status'] = $handlestatus;

        $uid = D('Common/Report')->where($map)->field('uid')->select();
        $uid = array_column($uid, 'uid');
        $name = query_user(array('uid', 'nickname'));

        $result = D('Addons://Report/Report')->where($map)->save($data);

        if ($result) {

            D('Message')->sendMessageWithoutCheckSelf($uid, '您的举报已被处理。',  '处理时间：' . friendlyDate($data['handle_time']) . '。' . '处理状态：' . '已处理' . '。' . '处理结果：' . $data['handle_result'] . '。' . '处理人：' . $name['nickname']);

            $this->success('处理成功', 0);
        } else {
            $this->error('处理失败');
        }
    }

    /**
     * 管理员忽略举报操作
     */

    public function ignoreReport()
    {
        $ids = I('ids');                        //获取点击的ids
        $map['id'] = array('in', $ids);

        $time = time();                       //获取当前时间
        $data['handle_time'] = $time;
        $data['handle_result'] = '忽略处理';

        $handlepeople = is_login();                             //操作人的ID
        $data['status'] = '2';
        $data['handle_uid'] = $handlepeople;

        $uid = D('Common/Report')->where($map)->field('uid')->select();
        $uid = array_column($uid, 'uid');
        $name = query_user(array('uid', 'nickname'));                       //获取用户名

        $result = D('Addons://Report/Report')->where($map)->save($data);

        if ($result) {

            D('Message')->sendMessageWithoutCheckSelf($uid,'您的举报已被处理。',  '处理时间：' . friendlyDate($data['handle_time']) . '。' . '处理状态：' . '已处理' . '。' . '处理结果：' . $data['handle_result'] . '。' . '处理人：' . $name['nickname']);


            $this->success('处理成功', 0);
        } else {
            $this->error('处理失败');
        }
    }


}
