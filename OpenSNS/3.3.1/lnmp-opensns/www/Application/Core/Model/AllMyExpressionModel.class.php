<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-10
 * Time: PM9:01
 */

namespace Core\Model;

use Think\Model;

class AllMyExpressionModel extends Model
{
    protected $ROOT_PATH = '';
    public $pkg = '';
    public  function _initialize()
    {
        parent:: _initialize();
        $this->pkg = modC('EXPRESSION','miniblog','EXPRESSION');
        $this->ROOT_PATH = str_replace('/Application/Core/Model/AllMyExpressionModel.class.php', '', str_replace('\\', '/', __FILE__));
    }

    /**
     * 获取当前主题包下所有的表情
     * @param boolean $flush 是否更新缓存，默认为false
     * @return array 返回表情数据
     */
    public function getAllExpression()
    {

        $pkg = $this->pkg;
        if($pkg =='all'){
            return $this->getAll();
        }else{
            return $this->getExpression($pkg);
        }
    }

    public function getExpression($pkg){


        if($pkg == 'miniblog'){
            $filepath =  "/Application/Core/Static/images/expression/" . $pkg;
            $list = $this->myreaddir($this->ROOT_PATH .$filepath);
            $res = array();
            foreach ($list as $value) {
                $file = explode(".", $value);
                $temp['title'] = $file[0];
                $temp['emotion'] = $pkg=='miniblog'?'['.$file[0].']': '[' . $file[0] . ':' . $pkg . ']';
                $temp['filename'] = $value;
                $temp['type'] = $pkg;
                $temp['src'] = __ROOT__ . $filepath . '/' . $value;
                // $temp['src'] = $filepath . '/' . $value;
                $res[$temp['emotion']] = $temp;
            }
        }
        else{
            if ($pkg == 'face') {
                $uid = is_login();
                $iexpression = M('iexpression');
                $iexplist = $iexpression->select();
                $temp['title'] = 'add_img';
                $temp['emotion'] ='add_img';
                $temp['type'] ='add_img';
                $temp['src'] = __ROOT__.'/Public/images/add.png';
                $res[$temp['emotion']] = $temp;


                foreach ($iexplist as $v) {
                    $temp['title'] = $v['id'];
                    $temp['emotion'] = $pkg== 'miniblog' ? '[' .  $v['id'] . ']' : '['.$pkg .  ':' .$v['id'] . ']';
                    $temp['filename'] = $v['id'];
                    $temp['type'] ='face';
                    $temp['src'] = get_pic_src($v['path']);
                    // $temp['src'] = $v['path'];
                    $res[$temp['emotion']] = $temp;

                }


            } else {
                $exppkg = M('expression_pkg');
                $expression = M('expression');
                $map['pkg_name'] = $pkg;
                $id = $exppkg->where($map)->getField('id');
                $map1['expression_pkg_id'] = $id;
                $res = array();
                $filelist = $expression->where($map1)->select();
                if($filelist) {

                    foreach ($filelist as $v) {
                        $temp['title'] = $v['name'];
                        $temp['emotion'] = $pkg == 'miniblog' ? '[' . $v['name'] . ']' : '[' . $v['name'] . ':' . $pkg . ']';
                        $temp['filename'] = $v['id'];
                        $temp['type'] = $pkg;
                        $temp['src'] = get_pic_src($v['path']);
                        // $temp['src'] = $v['path'];
                        $res[$temp['emotion']] = $temp;
                    }
                }

            }
        }


        return $res;
    }

    /**
     * getAll 获取所有主题的所有表情
     * @return array
     * @author:xjw129xjt xjt@ourstu.com
     */
    public function getAll()
    {

        $res = $this->getExpression('miniblog');
        $ExpressionPkg = $this->ROOT_PATH  . "/Uploads/Expression";
        $pkgList = $this->myreaddir($ExpressionPkg);
        foreach ($pkgList as $v) {
            $res =array_merge($res,$this->getExpression($v));
        }
        return $res;
    }

    public function myreaddir($dir)
    {
        $file = scandir($dir, 0);
        $i = 0;
        foreach ($file as $v) {
            if (($v != ".") and ($v != "..") and ($v != "README.md") and ($v != ".listing")  ) {
                $list[$i] = $v;
                $i = $i + 1;
            }
        }
        return $list;
    }


    /**
     * 将表情格式化成HTML形式
     * @param string $data 内容数据
     * @return string 转换为表情链接的内容
     */
    public function parse($data)
    {
        $data = preg_replace("/img{data=([^}]*)}/", "<img src='$1'  data='$1' >", $data);
        return $data;
    }


    public function getCount($dir){
        $list = $this->myreaddir($dir);
        return count($list);
    }


    public function getPkgList($checkStatus = 1){

        $config = get_kanban_config('PKGLIST','enable',array('miniblog'),'EXPRESSION');
        if(!$checkStatus || in_array('miniblog',$config)){
            $pkg['miniblog']['status'] =in_array('miniblog',$config)?1:0;
            $pkg['miniblog']['title'] = L('_DEFAULT_');
            $pkg['miniblog']['name'] = 'miniblog';
            $pkg['miniblog']['count'] = $this->getCount($this->ROOT_PATH . '/Application/Core/Static/images/Expression/miniblog');
        }
        $exppkg=M('expression_pkg');
        $pkgList=$exppkg->select();
        foreach($pkgList as $v){
            $pkg[$v['pkg_title']]['status']=$v['status'];
            $pkg[$v['pkg_title']]['title']=$v['pkg_title'];
            $pkg[$v['pkg_title']]['name']=$v['pkg_name'];

        }
       
        return $pkg;

    }
    public function getPkgInfo($pkg ='miniblog'){
        $ExpressionPkg = $this->ROOT_PATH . "/Uploads/Expression";
        if($pkg =='miniblog'){
            $result['title'] = L('_DEFAULT_');
            $result['name'] = 'miniblog';
            $result['count'] = $this->getCount($this->ROOT_PATH . '/Application/Core/Static/images/Expression/miniblog');
        }else{
            
           
                $exppkg=M('expression_pkg');
                $map['pkg_name']=$pkg;
                $name=$exppkg->where($map)->getField('pkg_title');
                $result['title'] =$name;
                $result['name'] = $pkg;
                $result['count'] =$this->getCount($ExpressionPkg.'/'.$pkg);
           
        }

        return $result;

    }
}















