<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-7
 * Time: 下午1:25
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;

/**
 * 后台头衔控制器
 * Class RankController
 * @package Admin\Controller
 * @郑钟良
 */
class RankController extends AdminController
{

    /**
     * 头衔管理首页
     * @param int $page
     * @param int $r
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function index($page = 1, $r = 20)
    {
        //读取数据
        $model = D('Rank');
        $list = $model->page($page, $r)->select();
        foreach ($list as &$val) {
            $val['u_name'] = D('member')->where('uid=' . $val['uid'])->getField('nickname');
            $val['types'] = $val['types'] ? L('_YES_') : L('_NO_');
            $val['label']='<span class="label" style="border-radius: 20px;background-color:'.$val['label_bg'].';color:'.$val['label_color'].';">'.$val['label_content'].'</span>';
            if($val['logo']==0){
                $val['logo']='';
            }
        }
        $totalCount = $model->count();
        //显示页面
        $builder = new AdminListBuilder();
        $builder
            ->title(L('_TITLE_LIST_'))
            ->buttonNew(U('Rank/editRank'))
            ->keyId()->keyTitle()->keyText('u_name', L('_UPLOAD_'))->keyImage('logo',L('_PICTURE_TITLE_'))->keyHtml('label',L('_WORD_TITLE_'))->keyCreateTime()->keyLink('types', L('_RECEPTION_IS_AVAILABLE_'), 'changeTypes?id=###')->keyDoActionEdit('editRank?id=###')->keyDoAction('deleteRank?id=###', L('_DELETE_'))
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    /**
     * 设置头衔前台是否可申请
     * @param null $id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function changeTypes($id = null)
    {
        if (!$id) {
            $this->error(L('_PLEASE_CHOOSE_THE_TITLE_'));
        }
        $types = D('rank')->where('id=' . $id)->getField('types');
        $types = $types ? 0 : 1;
        $result = D('rank')->where('id=' . $id)->setField('types', $types);
        if ($result) {
            $this->success(L('_SET_UP_'));
        } else {
            $this->error(L('_SET_FAILURE_'));
        }
    }

    /**
     * 删除头衔
     * @param null $id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function deleteRank($id = null)
    {
        if (!$id) {
            $this->error(L('_PLEASE_CHOOSE_THE_TITLE_'));
        }
        $result = D('rank')->where('id=' . $id)->delete();
        $result1 = D('rank_user')->where('rank_id=' . $id)->delete();
        if ($result) {
            $this->success(L('_DELETE_SUCCESS_'));
        } else {
            $this->error(L('_DELETE_FAILED_'));
        }
    }

    /**
     * 编辑头衔
     * @param null $id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function editRank($id = null)
    {
        //判断是否为编辑模式
        $isEdit = $id ? true : false;
        if (IS_POST) {
            $data['title']=I('post.title','','text');
            $data['logo']=I('post.logo',0,'intval');
            $data['label_content']=I('post.label_content','','text');
            $data['label_color']=I('post.label_color','','text');
            $data['label_bg']=I('post.label_bg','','text');
            $data['types'] = I('post.types',1,'intval');
            $model = D('rank');
            if ($data['title'] == '') {
                $this->error(L('_PLEASE_FILL_IN_THE_TITLE_'));
            }

            if($data['logo']==''&&$data['label_content']==''){
                $this->error(L('_THE_TITLE_OF_THE_PICTURE_AND_THE_TITLE_OF_THE_TITLE_'));
            }
            if ($isEdit) {
                $result = $model->where('id=' . $id)->save($data);
                if (!$result) {
                    $this->error(L('_CHANGE_FAILED_'));
                }
            } else {
                $data = $model->create($data);
                $data['uid'] = is_login();
                $data['create_time'] = time();
                $result = $model->add($data);
                if (!$result) {
                    $this->error(L('_CREATE_FAILURE_'));
                }
            }
            $this->success($isEdit ? L('_EDIT_SUCCESS_') : L('_ADD_SUCCESS_'), U('Rank/index'));
        } else {
            $rank['types'] = '1';//默认前台可以申请
            //如果是编辑模式
            if ($isEdit) {
                $rank = M('rank')->where(array('id' => $id))->find();
            }
            //显示页面
            $builder = new AdminConfigBuilder();
            $options = array(
                '0' => L('_NO_'),
                '1' => L('_YES_')
            );
            $builder
                ->title($isEdit ? L('_EDIT_TITLE_') : L('_NEW_TITLE_'))
                ->keyId()
                ->keyTitle()
                ->keySingleImage('logo', L('_PICTURE_TITLE_'), L('_THE_ICON_WHICH_DOES_NOT_SET_THE_TEXT_TITLE_THE_SETTING_IS_USEFUL_'))
                ->keyText('label_content',L('_WORD_TITLE_'))
                ->keyColor('label_color',L('_TITLE_COLOR_'))
                ->keyColor('label_bg',L('_TEXT_TITLE_TAG_BACKGROUND_COLOR_'))
                ->keyRadio('types', L('_RECEPTION_IS_AVAILABLE_'), null, $options)
                ->data($rank)
                ->buttonSubmit(U('editRank'))->buttonBack()
                ->display();
        }
    }

    /**
     * 用户列表
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function userList()
    {
        $nickname = I('nickname','','text');
        $map['status'] = array('egt', 0);
        if (is_numeric($nickname)) {
            $map['uid|nickname'] = array(intval($nickname), array('like', '%' . $nickname . '%'), '_multi' => true);
        } else {
            if ($nickname !== '')
                $map['nickname'] = array('like', '%' . (string)$nickname . '%');
        }
        $list = $this->lists('Member', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = L('_USER_LIST_');
        $this->display();
    }

    /**
     * 用户头衔列表
     * @param null $id
     * @param int $page
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function userRankList($id = null, $page = 1)
    {
        if (!$id) {
            $this->error(L('_PLEASE_SELECT_THE_USER_'));
        }
        $u_name = D('member')->where('uid=' . $id)->getField('nickname');
        $model = D('rank_user');
        $rankList = $model->where(array('uid' => $id, 'status' => 1))->page($page, 20)->order('create_time asc')->select();
        $totalCount = $model->where(array('uid' => $id, 'status' => 1))->count();
        foreach ($rankList as &$val) {
            $val['title'] = D('rank')->where('id=' . $val['rank_id'])->getField('title');
            $val['is_show'] = $val['is_show'] ? L('_SHOW_') : L('_NOT_SHOW_');
        }
        $builder = new AdminListBuilder();
        $builder
            ->title($u_name . '的头衔列表')
            ->buttonNew(U('Rank/userAddRank?id=' . $id), L('_RELATED_NEW_TITLE_'))
            ->keyId()->keyText('title', L('_TITLE_NAME_'))->keyText('reason', L('_CAUSE_'))->keyText('is_show', L('_IS_SHOWN_ON_THE_RIGHT_SIDE_OF_THE_NICKNAME_'))->keyCreateTime()->keyDoActionEdit('Rank/userChangeRank?id=###')->keyDoAction('Rank/deleteUserRank?id=###', L('_DELETE_'))
            ->data($rankList)
            ->pagination($totalCount, 20)
            ->display();
    }

    /**
     * 新增用户头衔关联
     * @param null $id
     * @param string $uid
     * @param string $reason
     * @param string $is_show
     * @param string $rank_id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function userAddRank($id = null, $uid = '', $reason = '', $is_show = '', $rank_id = '')
    {
        if (IS_POST) {
            $is_Edit = $id ? true : false;
            $data = array('uid' => $uid, 'reason' => $reason, 'is_show' => $is_show, 'rank_id' => $rank_id);
            $model = D('rank_user');
            if ($is_Edit) {
                $data = $model->create($data);
                $data['create_time'] = time();
                $result = $model->where('id=' . $id)->save($data);
                if (!$result) {
                    $this->error(L('_RELATED_FAILURE_'));
                }
            } else {
                $rank_user = $model->where(array('uid' => $uid, 'rank_id' => $rank_id))->find();
                if ($rank_user) {
                    $this->error(L('_THE_USER_ALREADY_HAS_THE_TITLE_PLEASE_CHOOSE_ANOTHER_TITLE_'));
                }
                $data = $model->create($data);
                $data['create_time'] = time();
                $data['status'] = 1;
                $result = $model->add($data);
                if (!$result) {
                    $this->error(L('_RELATED_FAILURE_'));
                } else {
                    $rank = D('rank')->where('id=' . $data['rank_id'])->find();
                    //$logoUrl=getRootUrl().D('picture')->where('id='.$rank['logo'])->getField('path');
                    //$u_name = D('member')->where('uid=' . $uid)->getField('nickname');
                    $content = L('_TITLE_AWARD_BY_ADMIN_').L('_COLON_').'[' . $rank['title'] . ']'; //<img src="'.$logoUrl.'" title="'.$rank['title'].'" alt="'.$rank['title'].'">';

                    $user = query_user(array('username', 'space_link'), $uid);

                    $content1 = L('_TITLE_AWARD_ADMIN_PARAM_',array('nickname'=>$user['nickname'],'title'=>$rank['title'])) . $reason; //<img src="'.$logoUrl.'" title="'.$rank['title'].'" alt="'.$rank['title'].'">';
                    clean_query_user_cache($uid, array('rank_link'));
                    $this->sendMessage($data, $content);
                    if (D('Common/Module')->isInstalled('Weibo')) { //安装了微博模块
                        //写入数据库
                        $model = D('Weibo/Weibo');
                        $result = $model->addWeibo(is_login(), $content1);
                    }
                }
            }
            $this->success($is_Edit ? L('_EDIT_ASSOCIATED_SUCCESS_') : L('_ADD_ASSOCIATED_SUCCESS_'), U('Rank/userRankList?id=' . $uid));
        } else {
            if (!$id) {
                $this->error(L('_PLEASE_SELECT_THE_USER_'));
            }
            $data['uid'] = $id;
            $ranks = D('rank')->select();
            if (!$ranks) {
                $this->error(L('_THERE_IS_NO_TITLE_PLEASE_ADD_A_TITLE_'));
            }
            foreach ($ranks as $val) {
                $rank_ids[$val['id']] = $val['title'];
            }
            $data['rank_id'] = $ranks[0]['id'];
            $data['is_show'] = 1;
            $builder = new AdminConfigBuilder();
            $builder
                ->title(L('_ADD_TITLE_ASSOCIATION_'))
                ->keyId()->keyReadOnly('uid', L('_USER_ID_'))->keyText('reason', L('_RELATED_REASONS_'))->keyRadio('is_show', L('_IS_SHOWN_ON_THE_RIGHT_SIDE_OF_THE_NICKNAME_'), null, array(1 => L('_YES_'), 0 => L('_NO_')))->keySelect('rank_id', L('_TITLE_NUMBER_'), null, $rank_ids)
                ->data($data)
                ->buttonSubmit(U('userAddRank'))->buttonBack()
                ->display();
        }
    }

    /**
     * 编辑用户头衔关联
     * @param null $id
     * @param string $uid
     * @param string $reason
     * @param string $is_show
     * @param string $rank_id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function userChangeRank($id = null, $uid = '', $reason = '', $is_show = '', $rank_id = '')
    {
        if (IS_POST) {
            $is_Edit = $id ? true : false;
            $data = array('uid' => $uid, 'reason' => $reason, 'is_show' => $is_show, 'rank_id' => $rank_id);
            $model = D('rank_user');
            if ($is_Edit) {
                $data = $model->create($data);
                $data['create_time'] = time();
                $result = $model->where('id=' . $id)->save($data);
                if (!$result) {
                    $this->error(L('_RELATED_FAILURE_'));
                }
            } else {
                $rank_user = $model->where(array('uid' => $uid, 'rank_id' => $rank_id))->find();
                if ($rank_user) {
                    $this->error(L('_THE_USER_ALREADY_HAS_THE_TITLE_PLEASE_CHOOSE_ANOTHER_TITLE_'));
                }
                $data = $model->create($data);
                $data['create_time'] = time();
                $result = $model->add($data);
                if (!$result) {
                    $this->error(L('_RELATED_FAILURE_'));
                } else {
                    $rank = D('rank')->where('id=' . $data['rank_id'])->find();
                    //$logoUrl=getRootUrl().D('picture')->where('id='.$rank['logo'])->getField('path');
                    //$u_name = D('member')->where('uid=' . $uid)->getField('nickname');
                    $content = L('_TITLE_AWARD_BY_ADMIN_').L('_COLON_').'[' . $rank['title'] . ']'; //<img src="'.$logoUrl.'" title="'.$rank['title'].'" alt="'.$rank['title'].'">';

                    $user = query_user(array('username', 'space_link'), $uid);

                    $content1 = L('_TITLE_AWARD_ADMIN_PARAM_',array('nickname'=>$user['nickname'],'title'=>$rank['title'])) . $reason; //<img src="'.$logoUrl.'" title="'.$rank['title'].'" alt="'.$rank['title'].'">';
                    clean_query_user_cache($uid, array('rank_link'));
                    $this->sendMessage($data, $content);
                    if (D('Common/Module')->isInstalled('Weibo')) { //安装了微博模块
                        //写入数据库
                        $model = D('Weibo/Weibo');
                        $result = $model->addWeibo(is_login(), $content1);
                    }
                }
            }
            $this->success($is_Edit ? L('_EDIT_ASSOCIATED_SUCCESS_') : L('_ADD_ASSOCIATED_SUCCESS_'), U('Rank/userRankList?id=' . $uid));
        } else {
            if (!$id) {
                $this->error(L('_PLEASE_CHOOSE_THE_TITLE_TO_CHANGE_'));
            }
            $data = D('rank_user')->where('id=' . $id)->find();
            if (!$data) {
                $this->error(L('_THE_TITLE_IS_NOT_ASSOCIATED_WITH_THE_TITLE_'));
            }
            $ranks = D('rank')->select();
            if (!$ranks) {
                $this->error(L('_THERE_IS_NO_TITLE_PLEASE_ADD_A_TITLE_'));
            }
            foreach ($ranks as $val) {
                $rank_ids[$val['id']] = $val['title'];
            }
            $builder = new AdminConfigBuilder();
            $builder
                ->title(L('_EDIT_TITLE_ASSOCIATION_'))
                ->keyId()->keyReadOnly('uid', L('_USER_ID_'))->keyText('reason', L('_RELATED_REASONS_'))->keyRadio('is_show', L('_IS_SHOWN_ON_THE_RIGHT_SIDE_OF_THE_NICKNAME_'), null, array(1 => L('_YES_'), 0 => L('_NO_')))->keySelect('rank_id', L('_TITLE_NUMBER_'), null, $rank_ids)
                ->data($data)
                ->buttonSubmit(U('userChangeRank'))->buttonBack()
                ->display();
        }
    }

    /**
     * 删除用户头衔管理
     * @param null $id
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function deleteUserRank($id = null)
    {
        if (!$id) {
            $this->error(L('_PLEASE_CHOOSE_THE_TITLE_LINK_'));
        }
        $result = D('rank_user')->where('id=' . $id)->delete();
        if ($result) {
            $this->success(L('_DELETE_SUCCESS_'));
        } else {
            $this->error(L('_DELETE_FAILED_'));
        }
    }

    public function sendMessage($data, $content, $type = '头衔颁发')
    {
        D('Message')->sendMessage($data['uid'], $type, $content, 'Ucenter/Message/message',array(),is_login(), 1);
    }

    /**
     * 待审核
     * @param int $page
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function rankVerify($page = 1)
    {
        $model = D('rankUser');
        $rankList = $model->where(array('status' => 0))->page($page, 20)->order('create_time asc')->select();
        $totalCount = $model->where(array('status' => 0))->count();
        foreach ($rankList as &$val) {
            $val['title'] = D('rank')->where('id=' . $val['rank_id'])->getField('title');
            $val['is_show'] = $val['is_show'] ? L('_SHOW_') : L('_NOT_SHOW_');
            //获取用户信息
            $u_user = D('member')->where('uid=' . $val['uid'])->getField('nickname');
            $val['u_name'] = $u_user;
        }
        unset($val);
        $builder = new AdminListBuilder();
        $builder
            ->title(L('_LIST_OF_TITLES_TO_BE_REVIEWED_'))
            ->buttonSetStatus(U('setVerifyStatus'), '1', L('_AUDIT_THROUGH_'), null)->buttonDelete(U('setVerifyStatus'), L('_AUDIT_NOT_THROUGH_'))
            ->keyId()->keyText('uid', L('_USER_ID_'))->keyText('u_name', L('_USER_NAME_'))->keyText('title', L('_TITLE_NAME_'))->keyText('reason', L('_REASONS_FOR_APPLICATION_'))->keyText('is_show', L('_IS_SHOWN_ON_THE_RIGHT_SIDE_OF_THE_NICKNAME_'))->keyCreateTime()->keyDoActionEdit('Rank/userChangeRank?id=###')
            ->data($rankList)
            ->pagination($totalCount, 20)
            ->display();
    }

    /**
     * 审核不通过
     * @param int $page
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function rankVerifyFailure($page = 1)
    {
        $model = D('rankUser');
        $rankList = $model->where(array('status' => -1))->page($page, 20)->order('create_time asc')->select();
        $totalCount = $model->where(array('status' => -1))->count();
        foreach ($rankList as &$val) {
            $val['title'] = D('rank')->where('id=' . $val['rank_id'])->getField('title');
            $val['is_show'] = $val['is_show'] ? L('_SHOW_') : L('_NOT_SHOW_');
            //获取用户信息
            $u_user = D('member')->where('uid=' . $val['uid'])->getField('nickname');
            $val['u_name'] = $u_user;
        }
        unset($val);
        $builder = new AdminListBuilder();
        $builder
            ->title(L('_THE_TITLE_OF_THE_APPLICATION_FOR_THE_LIST_'))
            ->buttonSetStatus(U('setVerifyStatus'), '1', L('_AUDIT_THROUGH_'), null)
            ->keyId()->keyText('uid', L('_USER_ID_'))->keyText('u_name', L('_USER_NAME_'))->keyText('title', L('_TITLE_NAME_'))->keyText('reason', L('_REASONS_FOR_APPLICATION_'))->keyText('is_show', L('_IS_SHOWN_ON_THE_RIGHT_SIDE_OF_THE_NICKNAME_'))->keyCreateTime()->keyDoActionEdit('Rank/userChangeRank?id=###')
            ->data($rankList)
            ->pagination($totalCount, 20)
            ->display();
    }

    public function setVerifyStatus($ids, $status)
    {

        $model_user = D('rankUser');
        $model = D('rank');
        if ($status == 1) {
            foreach ($ids as $val) {
                $rank_user = $model_user->where('id=' . $val)->field('uid,rank_id,reason')->find();
                $rank = $model->where('id=' . $rank_user['rank_id'])->find();
                $content = l('_RECEPTION_TITLE_PASSED_BY_ADMIN_').L('_COLON_').'[' . $rank['title'] . ']';

                $user = query_user(array('nickname', 'space_link'), $rank_user['uid']);

                $content1 = L('_RECEPTION_PASSED_BY_ADMIN_PARAM_',array('nickname'=>$user['nickname'],'title'=>$rank['title'])) . $rank_user['reason'];
                clean_query_user_cache($rank_user['uid'], array('rank_link'));
                $this->sendMessage($rank_user, $content, L('_TITLE_APPLICATION_FOR_APPROVAL_'));
                if (D('Common/Module')->isInstalled('Weibo')) { //安装了微博模块
                    //发微博
                    $model_weibo = D('Weibo/Weibo');
                    $result = $model_weibo->addWeibo(is_login(), $content1);
                }
            }
        } else if ($status = -1) {
            foreach ($ids as $val) {
                $rank_user = $model_user->where('id=' . $val)->field('uid,rank_id')->find();
                $rank = $model->where('id=' . $rank_user['rank_id'])->find();
                $content = L('_ASK_REFUSED_BY_ADMIN_').L('_COLON_').'[' . $rank['title'] . ']';
                $this->sendMessage($rank_user, $content, L('_THE_TITLE_OF_THE_APPLICATION_FOR_APPROVAL_IS_NOT_PASSED_'));
            }
        }
        $builder = new AdminListBuilder();
        $builder->doSetStatus('rankUser', $ids, $status);
    }
}
