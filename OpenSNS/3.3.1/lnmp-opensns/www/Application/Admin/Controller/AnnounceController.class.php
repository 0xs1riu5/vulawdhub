<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-21
 * Time: 上午11:15
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

class AnnounceController extends AdminController{
    protected $announceModel,$announceArriveModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->announceModel=D('Common/Announce');
        $this->announceArriveModel=D('Common/AnnounceArrive');
    }

    public function announceList($page=1,$r=10)
    {
        $aOrder=I('get.order','create_time','text');
        $aOrder=$aOrder.' desc';
        $aStatus=I('get.status',0,'intval');
        switch($aStatus){
            case 1:
            case 2:
                $map['status']=$aStatus-1;
                break;
            case 3:
                $map['end_time']=array('gt',time());
                $map['status']=array('in','0,1');
                break;
            case 4:
                $map['end_time']=array('elt',time());
                $map['status']=array('in','0,1');
                break;
            default:
                $map['status']=array('in','0,1');
        }
        list($list,$totalCount)=$this->announceModel->getListPage($map,$page,$aOrder,$r);
        foreach($list as &$val){
            $val['content']=text($val['content']);
        }
        $builder=new AdminListBuilder();
        $builder->title('公告列表')
            ->buttonNew(U('add'))
            ->setStatusUrl(U('setStatus'))
            ->buttonEnable()
            ->buttonDisable()
            ->buttonDelete()
            ->setSelectPostUrl(U('announceList'))
            ->select('','status','select','','','',array(array('id'=>0,'value'=>'全部'),array('id'=>2,'value'=>'启用'),array('id'=>1,'value'=>'禁用'),array('id'=>3,'value'=>'未过期'),array('id'=>4,'value'=>'已过期')))
            ->select('排序方式：','order','select','','','',array(array('id'=>'create_time','value'=>'创建时间'),array('id'=>'sort','value'=>'排序值')))
            ->keyId()
            ->keyTitle()
            ->keyBool('is_force','是否强制推送')
            ->keyText('sort','排序值')
            ->keyText('link','链接地址')
            ->keyText('content','公告内容')
            ->keyStatus()
            ->keyCreateTime()
            ->keyTime('end_time','有效期至')
            ->keyText('arrive','已确认数')
            ->keyDoActionEdit('edit?id=###','设置')
            ->keyDoAction('arrive?announce_id=###','查看确认人')
            ->data($list)
            ->pagination($totalCount,$r)
            ->display();
    }

    public function add()
    {
        if(IS_POST){
            $data['title']=I('post.title','','text');
            if($data['title']==''){
                $this->error('公告标题不能为空！');
            }
            $data['content']=I('post.content','');
            if($data['content']==''){
                $this->error('公告内容不能为空！');
            }

            $data['link']=I('post.link');
            $data['create_time']=I('post.create_time',time(),'intval');
            $data['end_time']=I('post.end_time',time()+7*24*60*60,'intval');
            $data['status']=I('post.status',1,'intval');
            $data['is_force']=I('post.is_force',1,'intval');
            $data['sort']=I('post.sort',0,'intval');
            $res=$this->announceModel->addData($data);
            if($res){
                S('Announce_list',null);
                $this->_sendMessage($res);
                $this->success('公告发布成功！',U('announceList'));
            }else{
                $this->error('公告发布失败！');
            }
        }else{
            $data=array('status'=>1,'sort'=>0,'is_force'=>1,'end_time'=>(time()+7*24*60*60));
            $builder=new AdminConfigBuilder();
            $builder->title('新增公告')
                ->suggest('公告只能新增，无法修改，保存时请慎重！')
                ->keyId()
                ->keyTitle()
                ->keyText('link','链接','站外链接要以http://或https://开头')
                ->keyEditor('content','内容')
                ->keyTime('end_time','有效期至')
                ->keyBool('is_force','是否强制推送')
                ->keyText('sort','排序','前台数值大的先展示')
                ->keyCreateTime()
                ->keyStatus()
                ->buttonSubmit()
                ->buttonBack()
                ->data($data)
                ->display();
        }
    }

    public function setStatus($ids,$status=1)
    {
        $builder=new AdminListBuilder();
        $builder->doSetStatus('Announce',$ids,$status);
    }

    public function edit()
    {
        if(IS_POST){
            $data['id']=I('post.id',0,'intval');
            if($data['id']==0){
                $this->error('非法操作！');
            }
            $data['sort']=I('post.sort',0,'intval');
            $data['end_time']=I('post.end_time',time()+7*24*60*60,'intval');
            $res=$this->announceModel->saveData($data);
            if($res){
                S('Announce_list',null);
               $this->success('操作成功！',U('announceList'));
            }else{
                $this->error('操作失败！');
            }
        }else{
            $aId=I('get.id',0,'intval');
            $data=$this->announceModel->getData($aId);
            if(!$data){
                $this->error('非法操作！');
            }
            $builder=new AdminConfigBuilder();
            $builder->title('公告设置')
                ->keyId()
                ->keyReadOnly('title','标题')
                ->keyText('sort','排序','前台数值大的先展示')
                ->keyTime('end_time','有效期至')
                ->keyReadOnly('link','链接地址','不可修改')
                ->keyAreaReadOnly('content','推送内容','不可修改')
                ->buttonSubmit()
                ->buttonBack()
                ->data($data)
                ->display();
        }
    }

    public function arrive($page=1,$r=30)
    {
        $aOrder=I('get.order','create_time','text');
        $aOrder=$aOrder.' asc';
        $aAnnounceId=I('get.announce_id',0,'intval');
        $announce=$this->announceModel->getData($aAnnounceId);
        $map['announce_id']=$aAnnounceId;
        list($list,$totalCount)=$this->announceArriveModel->getListPage($map,$aOrder,$page,$r);
        $builder=new AdminListBuilder();
        $builder->title("公告<{$announce['title']}>确认记录")
            ->setSelectPostUrl(U('arrive',array('announce_id'=>$aAnnounceId)))
            ->button('返回',array('href'=>'javascript:history.go(-1)'))
            ->select('排序方式：','order','select','','','',array(array('id'=>'uid','value'=>'用户uid'),array('id'=>'create_time','value'=>'确认时间')))
            ->keyId()
            ->keyUid()
            ->keyCreateTime('create_time','确认时间')
            ->data($list)
            ->pagination($totalCount,$r)
            ->display();
    }

    private function _sendMessage($announce_id=0)
    {
        if($announce_id!=0){
            $time=time();
            $url = U('Core/Announce/sendAnnounceMessage', array('announce_id' => $announce_id,'time' => $time, 'token' => md5($time . C('DATA_AUTH_KEY'))), true, true);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);  //设置过期时间为1秒，防止进程阻塞
            curl_setopt($ch, CURLOPT_USERAGENT, '');
            curl_setopt($ch, CURLOPT_REFERER, 'b');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        }
        return true;
    }
} 