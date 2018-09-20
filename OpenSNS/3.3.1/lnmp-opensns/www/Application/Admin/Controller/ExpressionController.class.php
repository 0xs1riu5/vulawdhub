<?php

namespace Admin\Controller;

use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminConfigBuilder;


/**
 * Class ConfigController   后台表情管理
 * @package Admin\Controller
 * @author:xjw129xjt xjt@ourstu.com
 */
class ExpressionController extends AdminController
{
    protected $ROOT_PATH = '';
    protected $expressionModel = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->ROOT_PATH = str_replace('/Application/Admin/Controller/ExpressionController.class.php', '', str_replace('\\', '/', __FILE__));
        $this->expressionModel = D('Core/Expression');
    }

    public function index()
    {
        $pkgList = $this->expressionModel->getPkgList(0);
        $admin_config = new AdminConfigBuilder();
        $data = $admin_config->handleConfig();
        $tab = array();
        foreach ($pkgList as $key => $v) {
            $tab[] = array('data-id' => $v['name'], 'title' => $v['title']);
        }
        $default = array( array('data-id' => 'disable', 'title' => L('_DISABLE_'), 'items' =>array() ),array('data-id' => 'enable', 'title' => L('_ENABLE_'), 'items' => $tab));
        $data['PKGLIST'] = $admin_config->parseKanbanArray($data['PKGLIST'],$tab,$default);
        $arr0=$data["PKGLIST"][0]["items"];
        $arr1=$data["PKGLIST"][1]["items"];
        foreach($arr0 as $v0){
            $sta0[]=$v0['data-id'];
        }
        foreach($arr1 as $v1){
            $sta1[]=$v1['data-id'];
        }
       $key = array_search('miniblog', $sta1);
        if ($key !== false)
        { array_splice($sta1, $key, 1);}
        $key0 = array_search('miniblog', $sta0);
        if ($key0 !== false)
        { array_splice($sta0, $key0, 1);}
        unset($v0);unset($v1);
        $exppkg=M('expression_pkg');
        $data0['status']=0;
        $data1['status']=1;
        $map0['pkg_name']=array('in',$sta0);
        $map1['pkg_name']=array('in',$sta1);
        $exppkg->where($map0)->save($data0);
        $exppkg->where($map1)->save($data1);
        $admin_config->title(L('_FACIAL_EXPRESSIONS_'))
            ->keyKanban('PKGLIST',L('_EXPRESSION_PACKAGE_STATUS_AND_SORT_'))
            ->buttonSubmit('', L('_SAVE_'))->data($data);
        $admin_config->display();
    }

    public function add()
    {
        $this->display('add');

    }

    public function openExZip(){
        $rp= $this-> ROOT_PATH = str_replace('/Application/Admin/Controller/ExpressionController.class.php', '', str_replace('\\', '/', __FILE__));
        $aName=I('post.name','','op_t');
        $path0 = $rp . "/Uploads/Expression";
        $file0 = scandir($path0, 0);
        $flag=1;
        foreach($file0 as $f){
            if (($f != ".") and ($f != "..") ) {
                if($f==$aName){
                    echo json_encode('0');
                    $flag=0;
                }

            }
        }
        if($flag==1) {
            $config = array(
                'maxSize' => 3145728,
                'rootPath' => './Uploads/',
                'savePath' => 'Expression/',
                'saveName' => '',
                'exts' => array('zip', 'rar'),
                'autoSub' => true,
                'subName' => '',
                'replace' => true,
            );
            $uptypes = array(
                'jpg',
                'jpeg',
                'png',
                'gif'

            );
            $aTitle = I('post.title', '', 'op_t');
            $upload = new \Think\Upload($config); // 实例化上传类
            $info = $upload->upload($_FILES);
            if (!$info) { // 上传错误提示错误信息
                $this->error($upload->getError());
            } else { // 上传成功
                $this->unCompression($info['pkg']['savename']);
                $zipname = $info['pkg']['savename'];
                $name = basename($zipname, ".zip");
                $path1 = __ROOT__ . "/Uploads/Expression/" . $name;
                $path2 = $rp . "/Uploads/Expression/" . $name;
                $file = scandir($path2, 0);
                $i = 0;
                foreach ($file as $v) {
                    if (($v != ".") and ($v != "..")) {
                        if (in_array(pathinfo($v, PATHINFO_EXTENSION), $uptypes)) {
                            $list[$i] = $path1 . '/' . $v;
                            $i = $i + 1;
                        }

                    }
                }

            }
            echo json_encode($list);
        }
    }
    public function upload()
    {
        $aPkg=I('post.pkg_name','','op_t');
        $aTitle = I('post.title', '', 'op_t');
        $aImgNameSuf=I('post.imgnamesuf','','op_t');
        $aImgName=I('post.imgname','','op_t');
        $expression=M('expression');
        $expression_pkg=M('expression_pkg');
        $count=count($aImgName);
        $pkgpath="/Uploads/Expression/".$aPkg;
        //c表情包
        $data1['pkg_title']=$aTitle;
        $data1['pkg_name']=$aPkg;
        $data1['path']=$pkgpath;
        $data1['driver']='local';
        $data1['status']=1;
        $data1['create_time']=time();
        $pkginfo=$expression_pkg->data($data1)->add();
        if($pkginfo==false){
            $this->error('表情包插入失败');
        }
        //表情
        $flag=1;
        for($i=0;$i<$count;$i++){
            $exppath=$pkgpath.'/'.$aImgNameSuf[$i];
            $data['name']=$aImgName[$i];
            $data['path']=$exppath;
            $data['driver']='local';
            $data['create_time']=time();
            $data['expression_pkg_id']=$pkginfo;
            $expinfo= $expression->add($data);
            if($expinfo==false){
                $flag=0;
            }

        }
        if($flag==1){
       $newpkg=array('data-id'=>$aPkg,'title'=>$aTitle);
           $config=M('config');
            $map['name']='_EXPRESSION_PKGLIST';
            $val=$config->where($map)->field('value')->find();
           $arr= json_decode($val['value'],true);
            array_push($arr[1]['items'],$newpkg);
            $val1=json_encode($arr);
           $data3['value']=$val1;
          $config->where($map)->data($data3)->save();
       $this->success('上传成功');
        }
        else{
            $this->error('表情插入失败');
        }
    }


    /*
        private function writeInfo($filename, $aTitle)
        {

            // $aTitle = I('post.title','','op_t');
            $ExpressionPkg = $this->ROOT_PATH . "/Uploads/Expression/";
            $filename = explode('.', $filename);
            array_pop($filename);
            $filename = implode('.', $filename);
            $pkg = $ExpressionPkg . $filename;
            file_put_contents($pkg . '/info.txt', json_encode(array('title' => $aTitle)));
            return true;
        }*/
    private function unCompression($filename)
    {
        $ExpressionPkg = $this->ROOT_PATH . "/Uploads/Expression/";
        require_once("./ThinkPHP/Library/OT/PclZip.class.php");
        $pcl = new \PclZip($ExpressionPkg . $filename);
        if ($pcl->extract($ExpressionPkg)) {
            $result = $this->delFile($ExpressionPkg . $filename);
            if ($result) {
                return true;
            }
        }
        return false;
    }

    private function delFile($path)
    {
        $result = @unlink($path);
        if ($result) {
            return true;
        } else {
            return false;
        }

    }


    public function package()
    {
        $pkg_list=M('expression_pkg');
        $exp_list=M('expression');
        $pkgList=$pkg_list->field('id,pkg_title,pkg_name,status')->select();
        $count=$exp_list->query("select expression_pkg_id,count(expression_pkg_id) from ocenter_expression group by expression_pkg_id");
        foreach($pkgList as $f){

            foreach($count as $v){
                if($f['id']==$v['expression_pkg_id'])  {
                    $f['count']=$v['count(expression_pkg_id)'];
                    $ff[]=$f;
                }
            }
        };
        $this->assign('list', $ff);
        $this->display();

    }


    public function editPackage()
    {
        $exppkg=M('expression_pkg');
        if (IS_POST) {
            $aName = I('post.name', '', 'op_t');
            $aTitle = I('post.title', '', 'op_t');
            if ($aName == 'miniblog') {
                $this->error(L('_DEFAULT_ITEMS_CANNOT_BE_EDITED_'));
            }
            //$this->write($aName, array('title' => $aTitle));
            $map0['pkg_name']=$aName;
            $data['pkg_title']=$aTitle;
            $exppkg->where($map0)->save($data);
            $this->success(L('_CHANGE_'));
        } else {
            $aName = I('get.name', '', 'op_t');
            if (empty($aName)) {
                $this->error(L('_PARAMETER_ERROR_'));
            }
            if ($aName == 'miniblog') {
                $this->error(L('_DEFAULT_ITEMS_CANNOT_BE_EDITED_'));
            }


            $map['pkg_name']=$aName;
            $filetitle=$exppkg->where($map)->getField('pkg_title');
            //$file = $this->read($aName);
            //$this->assign('title', $file['title']);
            $this->assign('title',$filetitle);
            $this->assign('name', $aName);
            $this->display('edit');
        }
    }

    /*  private function  read($file)
     {
         $ExpressionPkg = $this->ROOT_PATH . "/Uploads/Expression/";
         $file = file_get_contents($ExpressionPkg . $file . '/info.txt');
         return json_decode($file, true);

     private function  write($file, $data)
     {
         $ExpressionPkg = $this->ROOT_PATH . "/Uploads/Expression/";
         $pkg = $ExpressionPkg . $file;
         file_put_contents($pkg . '/info.txt', json_encode($data));
     }*/



    public function expressionList($page=1,$r=20)
    {
        $aName = I('get.name', '', 'op_t');
        if (empty($aName)) {
            $this->error(L('_PARAMETER_ERROR_'));
        }
        $exppkg=M('expression_pkg');
        $expression=M('expression');
        $map['pkg_name']=$aName;
        $id = $exppkg->where($map)->getField('id');

        $list=$expression->where('expression_pkg_id='.$id)->page($page,$r)->order('id asc')->field('id as filem,name,path')->select();
        $totalCount = $expression->where('expression_pkg_id='.$id)->count();
        foreach ($list as &$v) {
            $v['image'] = '<img src="' .get_pic_src($v['path']) . '"/>';
            $v['title']=$v['name'];
        }
        unset($v);
        $builder = new AdminListBuilder();
        $builder
            ->title(L('_EXPRESSION_LIST_'))
            ->keyText('title', L('_TITLE_'))
            ->keyText('image', L('_FACIAL_EXPRESSION_'))->keyDoAction('Admin/Expression/delExpression?name={$filem}&pkg='.$aName, L('_DELETE_'))
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();

    }

    public function delPackage()
    {
        if(IS_POST){
            $aName = I('post.name', '', 'op_t');
            $path = $this->ROOT_PATH . "/Uploads/Expression/" . $aName . '/';
            $res = $this->deldir($path);
            if ($res) {
                $this->ajaxReturn('1');
            } else {
                $this->ajaxReturn('0');
            }
        }
        else{
            $aName = I('get.name', '', 'op_t');
            if (empty($aName)) {
                $this->error(L('_PARAMETER_ERROR_'));
            }
            $exppkg=M('expression_pkg');
            $expression=M('expression');
            $map['pkg_name']=$aName;
            $id=$exppkg->where($map)->getField('id');
            $map1['expression_pkg_id']=$id;
            $res1=$expression->where($map1)->delete();
            $res=$exppkg->where($map)->delete();
            $path = $this->ROOT_PATH . "/Uploads/Expression/" . $aName . '/';
            $res2 = $this->deldir($path);
            if ($res&&$res1&&$res2) {
                $this->success(L('_DELETE_SUCCESS_'));
            } else {
                $this->error(L('_DELETE_FAILED_'));
            }}
    }

    public function delExpression()
    {
        $aName = I('get.name', '', 'op_t');
        $pkg = I('get.pkg', '', 'op_t');

        if (empty($aName) || empty($pkg)) {
            $this->error(L('_PARAMETER_ERROR_'));
        }

        /*  if ($pkg == 'miniblog') {
            $path = $this->ROOT_PATH . '/Application/Core/Static/images/expression/miniblog/' . $aName;
        } else {
            $path = $this->ROOT_PATH . "/Uploads/Expression/" . $pkg . '/' . $aName;
        }*/
        $expression=M('expression');
        $pathname=$expression->where('id='.$aName)->getField('path');
        $name=substr($pathname,strrpos($pathname,'/'),strlen($pathname)- strrpos($pathname,'/'));
        $res= $expression->where('id='.$aName)->delete();
        $path = $this->ROOT_PATH . "/Uploads/Expression/" . $pkg  . $name;
        $res1 = $this->delFile($path);
        if ($res&&$res1) {
            $this->success(L('_DELETE_SUCCESS_'));
        } else {
            $this->error(L('_DELETE_FAILED_'));
        }
        /*  $res = $this->delFile($path);
          if ($res) {
              $this->success(L('_DELETE_SUCCESS_'));
          } else {
              $this->error(L('_DELETE_FAILED_'));
          }*/


    }


    private function deldir($dir)
    {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }

        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
    public function iexpressionList($page=1,$r=15){
        $list=M('iexpression')->page($page,$r)->order('create_time desc')->select();
        foreach ($list as &$l){
            if($l['driver']=='local'){
                $l['path']=__ROOT__.'/'.$l['path'];
            } else{
                $l['path']=$l['path'];
            }
        }
        $totalCount=M('iexpression')->count();
        $builder = new AdminListBuilder();
        $builder
            ->title('自定义表情列表')
            ->keyText('id','id')
            ->keyImage('path','表情')
            ->keyDoAction('Admin/Expression/delIexpression?id=###',L('_DELETE_'))
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
    public function delIexpression(){
         $aId=I('get.id',0,'intval');
        $path=M('iexpression')->where(array('id'=>$aId))->field('path,driver')->find();
        M('iexpression')->where(array('id'=>$aId))->delete();
        M('iexpression_link')->where(array('iexpression_id'=>$aId))->delete();
        if($path['driver']=='local'){
            $res=$this->delFile( $this->ROOT_PATH .$path['path']);
        }else{
            $file_name=explode('/',$path['path']);
            $file_name=$file_name[count($file_name)-1];
            delete_driver_upload_file($file_name,'qiniu');
        }
        if($res!==false){
            $this->success(L('_DELETE_SUCCESS_'));
        }else{
            $this->error(L('_DELETE_FAILED_'));
        }


    }

}
