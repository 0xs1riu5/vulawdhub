<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/9 0009
 * Time: 上午 9:23
 */
namespace Addons\Recommend\Controller;

use Think\Controller;

require_once(ONETHINK_ADDON_PATH . 'Recommend/Common/function.php');

class RecommendController extends Controller
{
    /**
     * 实现推荐数据的关注功能
     * 关注一个数据并调用查找再找一个数据显示
     * Date:2015/3/25
     * @author 徐敏威<zzl@ourstu.com>
     */
    public function recommend()
    {

        $aUid = I('post.uid', 0, 'intval');
        $result = D('Follow')->follow($aUid);//进行关注

        $data['status'] = 0;
        if ($result) {
            $list = _getRecommendList(1);                   //查找一个数据
            if ($list) {
                $data['other_follow'] = $list[0];          //赋值
                $data['status'] = 1;
            } else {
                $data['status'] = 1;
                $data['info'] = '获取关注失败！';
            }
        } else {
            $data['info'] = '关注失败！';
        }
        $this->ajaxReturn($data);
    }

    /**
     * 实现推荐关注换一换功能
     * 调用查找，找两个数据传显示
     * Date:2015/3/25
     * @author 徐敏威<zzl@ourstu.com>
     */
    public function changeRecommend()
    {
        $config = _getAddonsConfig();
        $number=$config['number'];          //获取配置，得到number（查找数量）字段。

        $data['status'] = 0;
        $list = _getRecommendList($number);     //查找并赋值

        if ($list){
            $data['other_follow'] = $list;
            $this->assign('recommend', $list);
            $data['html'] = $this->fetch(T('Addons://Recommend@Recommend/_user'));  //如果有数据，返回显示数据。
            $data['status'] = 1;
        } else {
            S('recommend_follow_id_' . is_login(), null);       //没有数据，清空缓存。
            $list = _getRecommendList($number);
            if ($list) {
                $data['other_follow'] = $list;
                $this->assign('recommend', $list);
                $data['html'] = $this->fetch(T('Addons://Recommend@Recommend/_user'));//重新查找后显示数据
                $data['status'] = 1;
            }else{
                $data['info'] = '换一换失败！';
            }
        }
        $this->ajaxReturn($data);
    }
}