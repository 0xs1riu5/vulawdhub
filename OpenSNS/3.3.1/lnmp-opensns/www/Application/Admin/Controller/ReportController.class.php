<?php

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

require_once(ONETHINK_ADDON_PATH . 'Report/Common/function.php');


class ReportController extends AdminController
{

    public function lists($page = 1, $r = 20){
        $map['status'] = array('egt', 0);
        $list = M('Report')->where($map)->page($page, $r)->select();
        //   $simplify_list = array_column($list, 'id');
        $reportCount = M('Report')->where($map)->count();

        int_to_string($list);

        $builder = new AdminListBuilder();
        $builder->title(L('_REPORT_PROCESSING_LIST_'));

        $builder->setStatusUrl(U('setStatus'))
            ->buttonModalPopup(U('handleEject'), '', L('_PROCESS_'), $attr = array())
            ->buttonDisable('',L('_IGNORE_'))
            ->buttonDelete(U('deleteReport'),L('_DELETE_REPORT_'))
            ->keyId()
            ->keyLink('url',L('_REPORT_LINK_'),'{$url}')
            ->keyUid('uid',L('_REPORT_USERS_ID_'))
            ->keyText('reason',L('_REPORT_REASONS_'))
            ->keyText('content',L('_REPORT_REASONS_'))
            ->keyText('type',L('_REPORT_TYPE_'))
            ->keyCreateTime('create_time',L('_CREATE_TIME_'))
            ->keyUpdateTime('update_time',L('_UPDATE_TIME_'))
            ->keyUpdateTime('handle_time',L('_PROCESSING_TIME_'))
            ->keyDoActionModalPopup('handleEject?id=###',L('_PROCESS_'),L('_OPERATION_'),array('data-title'=>L('_MIGRATING_USER_TO_ANOTHER_IDENTITY_')))
            ->keyDoActionEdit(' ',L('_IGNORE_'))
            ->key('status', L('_STATUS_'), 'status',array('0'=>L('_IGNORE_'),'1'=>L('_HAS_BEEN_DEALT_WITH_'),'2'=>L('_BEING_PROCESSED_')));

        $builder->data($list);
        $builder->pagination($reportCount, $r);
        $builder->display();
    }
    public function setStatus($ids,$status=1){
        $ids=I('ids',array());
        $status=$_GET['status'];
        $status!=1&&$status=0;
        D('Addons://Report/Report')->processingTime();
        $builder = new AdminListBuilder;
        $builder->doSetStatus('Report', $ids, $status);
    }

    public function deleteReport(){
        $ids=I('ids',array());
        $map['id']=array('in',$ids);
        $result = D('Addons://Report/Report')->where($map)->delete();
        if ($result) {
            $this->success(L('_DELETE_SUCCESS_'), 0);
        } else {
            $this->error(L('_DELETE_FAILED_'));
        }
    }

    public function handleEject(){
        $this->display();
    }


}