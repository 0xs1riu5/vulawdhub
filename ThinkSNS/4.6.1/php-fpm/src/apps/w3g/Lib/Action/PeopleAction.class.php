<?php

// ini_set('display_errors', true);
// error_reporting(E_ALL);
/**
 * 找人首页控制器.
 */
class PeopleAction extends BaseAction
{
    public function index()
    {
        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
        if (!$conf['findPeople']) {
            $this->error('找人功能已关闭');
        }
        $interest = model('RelatedUser')->getRelatedUser(8);
        $this->assign('interest', $interest);
        $this->setTitle('找伙伴');
        $this->setKeywords('找伙伴');
        $this->setDescription(implode(',', $cate));

        $this->display();
    }

    /**
     * 找人首页换一批控制器.
     */
    public function refresh()
    {
        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
        if (!$conf['findPeople']) {
            $this->error('找人功能已关闭');
        }
        $interest = model('RelatedUser')->getRelatedUser(8);
        $this->assign('interest', $interest);
        $this->display();
    }

    /**
     * 找人结果页控制器.
     */
    public function search()
    {
        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
        if (!$conf['findPeople']) {
            $this->error('找人功能已关闭');
        }
        //$interest = model('RelatedUser')->getRelatedUser();
        //var_dump($interest);exit;
        $this->assign('findPeopleConfig', $conf['findPeople']);
        if (!isset($_GET['type']) || empty($_GET['type'])) {
            $_GET['type'] = $conf['findPeople'][0];
            //$_GET['type'] = 'interest';
        } else {
            if (!in_array(t($_GET['type']), $conf['findPeople'])) {
                $this->error('参数错误！');
            }
        }
        $_GET = array_merge($_GET, $_POST);
        $curType = intval($_GET['t']) ? intval($_GET['t']) : 1;
        $limit = intval($_GET['limit']) ? intval($_GET['limit']) : 20;
        $searchKey = t($_GET['k']);
        $lastUid = intval($_GET['lastUid']) ? intval($_GET['lastUid']) : 0;
        $page = $_GET['page'] ? intval($_GET['page']) : 1;
        $this->assign('page', $page);
        $this->assign('curType', $curType);
        $this->assign('limit', $limit);
        $this->assign('key', $searchKey);
        $this->assign('jsonKey', json_encode($searchKey));
        $this->assign('lastUid', $lastUid);
        $userList = D('People', 'people')->searchUser($searchKey, $lastUid, $curType, $limit, $page);
        //dump($userList);exit;
        $this->assign('count', $userList['count']);
        $this->assign('userList', $userList);
        $this->display();
    }

    /**
     * 添加关注操作.
     *
     * @return json 返回操作后的JSON信息数据
     */
    public function doFollow()
    {
        // 安全过滤
        $fid = intval($_POST['fid']);
        $res = model('Follow')->doFollow($this->mid, $fid);
        $this->ajaxReturn($res, model('Follow')->getError(), false !== $res);
    }

    /**
     * 取消关注操作.
     *
     * @return json 返回操作后的JSON信息数据
     */
    public function unFollow()
    {
        $fid = intval($_POST['fid']);
        // 安全过滤
        $res = model('Follow')->unFollow($this->mid, $fid);
        $this->ajaxReturn($res, model('Follow')->getError(), false !== $res);
    }

    /*
     * 搜索更多列表
     * @return string
     */
//	public function searchMore(){
//		$conf = model('Xdata')->get('admin_User:findPeopleConfig');
//		if(!$conf['findPeople']){
//			$this->error('找人功能已关闭');
//		}
//		//$interest = model('RelatedUser')->getRelatedUser();
//		//var_dump($interest);exit;
//		$this->assign('findPeopleConfig',$conf['findPeople']);
//		if(!isset($_GET['type']) || empty($_GET['type']) ){
//			$_GET['type'] = $conf['findPeople'][0];
//			//$_GET['type'] = 'interest';
//		}else{
//			if(!in_array(t($_GET['type']), $conf['findPeople'])) $this->error('参数错误！');
//		}
//		$_GET 		= array_merge($_GET,$_POST);
//		$curType 	= intval($_GET['t']) ? intval($_GET['t']) : 1;
//		$limit 	= intval($_GET['limit']) ? intval($_GET['limit']) : 20;
//		$searchKey  	= t($_GET['k']);
//		$lastUid = intval($_GET['lastUid']) ? intval($_GET['lastUid']) : 0;
//		$this->assign('curType', $curType);
//		$this->assign('limit', $limit);
//		$this->assign('key', $searchKey);
//		$this->assign('jsonKey',json_encode($searchKey));
//		$this->assign('lastUid', $lastUid);
//		$userList = D('People','people')->searchUser($searchKey, $lastUid, $curType, $limit);
//		$this->assign('userList', $userList);
//		$this->display();
//	}

    public function cate()
    {
        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
        if (!$conf['findPeople']) {
            $this->error('找人功能已关闭');
        }
        $this->assign('findPeopleConfig', $conf['findPeople']);
        if (!isset($_GET['type']) || empty($_GET['type'])) {
            $_GET['type'] = $conf['findPeople'][0];
        } else {
            if (!in_array(t($_GET['type']), $conf['findPeople'])) {
                $this->error('参数错误！');
            }
        }
        // 获取相关数据
        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $sex = isset($_GET['sex']) ? intval($_GET['sex']) : 0;
        $area = isset($_GET['area']) ? intval($_GET['area']) : 0;
        $verify = isset($_GET['verify']) ? intval($_GET['verify']) : 0;
        // 加载数据
        $this->assign('cid', $cid);
        $this->assign('sex', $sex);
        $this->assign('area', $area);
        $this->assign('verify', $verify);
        //页面类型
        $type = isset($_GET['type']) ? t($_GET['type']) : $conf['findPeople'][0];
        $this->assign('type', $type);
        //SEO
        switch ($type) {
            case 'verify':
                $title = '认证用户推荐';
                $cate = model('CategoryTree')->setTable('user_verified_category')->getNetworkList();  //TODO
                break;
            case 'official':
                $title = '官方推荐';
                $cate = model('CategoryTree')->setTable('user_official_category')->getNetworkList();
                break;
            case 'tag':
                $title = '按标签查找用户';
                $cate = model('UserCategory')->getNetworkList();
                break;
            case 'area':
                $title = '按地区查找用户';
                //上级id
                $pid = D('area')->where('area_id='.$area)->getField('pid');
                //同级
                $tongji = model('CategoryTree')->setTable('area')->getNetworkList($pid);
                //下级
                $cate = model('CategoryTree')->setTable('area')->getNetworkList($area);
                //dump($cate);exit;
                break;
        }
        $this->assign('cate', $cate);
        //$cate = getSubByKey($cate,'title');
        //dump($cate);exit;
        $this->setTitle($title);
        $this->setKeywords($title);
        $this->setDescription(implode(',', $cate));

        $this->display();
    }

    /**
     * 获取指定父分类的树形结构.
     *
     * @return int   $pid 父分类ID
     * @return array 指定父分类的树形结构
     */
    public function getNetworkList()
    {
        $pid = intval($_REQUEST['pid']);
        $list = model('CategoryTree')->setTable('area')->getNetworkList($pid);
        $id = $pid + 100;
        // exit($list[$id]['child']);
        //dump($list[$id]['child']);
        exit(json_encode($list[$id]['child']));
    }

    /**
     * 找人结果页面.
     * */
    public function findResult()
    {
        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
        if (!$conf['findPeople']) {
            $this->error('找人功能已关闭');
        }
        $this->assign('findPeopleConfig', $conf['findPeople']);
        if (!isset($_GET['type']) || empty($_GET['type'])) {
            $_GET['type'] = $conf['findPeople'][0];
        } else {
            if (!in_array(t($_GET['type']), $conf['findPeople'])) {
                $this->error('参数错误！');
            }
        }
        // 获取相关数据
        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $sex = isset($_GET['sex']) ? intval($_GET['sex']) : 0;
        $area = isset($_GET['area']) ? intval($_GET['area']) : 0;
        $verify = isset($_GET['verify']) ? intval($_GET['verify']) : 0;
        // 加载数据
        $this->assign('cid', $cid);
        $this->assign('sex', $sex);
        $this->assign('area', $area);
        $this->assign('verify', $verify);
        $this->assign('limit', 20);
        $this->assign('lastuid', 0);
        $this->assign('page', intval($_GET['p']));
        // 页面类型
        $type = isset($_GET['type']) ? t($_GET['type']) : $conf['findPeople'][0];
        $this->assign('type', $type);
        // 获取后台配置用户
        if (in_array($type, array('verify', 'official'))) {
            switch ($type) {
                case 'verify':
                    $conf = model('Xdata')->get('admin_User:verifyConfig');
                    $this->assign('pid', intval($_GET['pid']));
                    break;
                case 'official':
                    $conf = model('Xdata')->get('admin_User:official');
                    break;
            }
            $this->assign('topUser', $conf['top_user']);
        }
        // 获取相应类型
        switch ($type) {
            case 'tag':
                //$areaList = model('Area')->getNetworkList();
                // 获取面包屑
                $_title = '全部标签';
                if ($cid) {
                    $category = model('UserCategory')->where('user_category_id='.$cid)->find();
                    if ($category) {
                        $_title = $category['title'];
                    }
                }
                break;
            case 'area':
                //$tag = model('UserCategory')->getNetworkList();
                // 获取面包屑
                $_title = '全部地区';
                if ($area) {
                    //$pid = model('Area')->where('area_id='.$area)->find();
                    //if($pid){
                        $pInfo = model('Area')->where('area_id='.$area)->find();
                    $_title = $pInfo['title'];
                    //}
                }
                break;
        }
        $this->assign('_title', $_title);
        $this->setTitle($_title);
        $this->setKeywords($_title);
        $this->display();
    }
//    public function findMore(){
//        $conf = model('Xdata')->get('admin_User:findPeopleConfig');
//        if(!$conf['findPeople']){
//            $this->error('找人功能已关闭');
//        }
//		//$interest = model('RelatedUser')->getRelatedUser();
//		//var_dump($interest);exit;
//		$this->assign('findPeopleConfig',$conf['findPeople']);
//		if(!isset($_GET['type']) || empty($_GET['type']) ){
//			$_GET['type'] = $conf['findPeople'][0];
//			//$_GET['type'] = 'interest';
//		}else{
//			if(!in_array(t($_GET['type']), $conf['findPeople'])) $this->error('参数错误！');
//		}

//		$lastuid = isset($_GET['lastuid']) ? intval($_GET['lastuid']) : 0;
//		$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
//		// 获取相关数据
//		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
//		$sex = isset($_GET['sex']) ? intval($_GET['sex']) : 0;
//		$area = isset($_GET['area']) ? intval($_GET['area']) : 0;
//		$verify = isset($_GET['verify']) ? intval($_GET['verify']) : 0;
//		// 加载数据
//		$this->assign('lastuid', $lastuid);
//		$this->assign('cid', $cid);
//		$this->assign('sex', $sex);
//		$this->assign('area', $area);
//		$this->assign('verify', $verify);
//		$this->assign('limit', $limit);
//		$this->assign('lastuid', $lastuid);
//		// 页面类型
//		$type = isset($_GET['type']) ? t($_GET['type']) : $conf['findPeople'][0];
//		$this->assign('type', $type);
//		// 获取后台配置用户
//		if(in_array($type, array('verify', 'official'))) {
//			switch($type) {
//				case 'verify':
//					$conf = model('Xdata')->get('admin_User:verifyConfig');
//					$this->assign('pid',intval($_GET['pid']));
//					break;
//				case 'official':
//					$conf = model('Xdata')->get('admin_User:official');
//					break;
//			}
//			$this->assign('topUser', $conf['top_user']);
//		}
//				// 获取相应类型
//        switch($type) {
//        	case 'tag':
//        		$areaList = model('Area')->getNetworkList();
//                // 获取面包屑
//                $_title = '全部标签';
//                if($cid){
//                    $category = model('UserCategory')->where('user_category_id='.$cid)->find();
//                    if($category){
//                    $_title = $category['title'];
//                    }
//                }
//        		break;
//        	case 'area':
//        		$tag = model('UserCategory')->getNetworkList();
//                // 获取面包屑
//	            $_title = '全部地区';
//                if($area){
//                    $pid = model('Area')->where('area_id='.$area)->find();
//                    if($pid){
//                        $pInfo =  model('Area')->where('area_id='.$var['area'])->find();
//                        $_title = $pInfo['title'];
//                    }
//                }
//        		break;
//        }
//        $this->assign('_title', $_title);
//		$this->setTitle( $_title );
//		$this->setKeywords( $_title );
//		$this->display();
//    }
}
