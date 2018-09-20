<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/20 0020
 * Time: 上午 10:18
 * @author 徐敏威<zzl@ourstu.com>
 */


/**
 * 查找推荐数据
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */

function _getRecommendList($num)
{

    $config = _getAddonsConfig();
    $config = $config['method'];

    $list = $none_recommend = array();
    do {
        $config = array_diff($config, $none_recommend);
        sort($config);
        $rand = rand(0, count($config));
        $rand = $config[$rand];

        switch ($rand) {
            case 'rand':
                $result = _randRecommend();//todo 随机推荐
                if ($result) {
                    $list[] = $result;
                } else {
                    $none_recommend[] = 'rand';
                }
                break;
            case 'city':
                $result = _sameCityRecommend();//todo 城市
                if ($result) {
                    $list[] = $result;
                } else {
                    $none_recommend[] = 'city';
                }
                break;
            case 'admin':
                $result = _adminRecommend();       //todo 管理员
                if ($result) {
                    $list[] = $result;
                } else {
                    $none_recommend[] = 'admin';
                }
                break;
            case 'data':
                $result = _sameDataRecommend();//todo 相同资料
                if ($result) {
                    $list[] = $result;
                } else {
                    $none_recommend[] = 'data';
                }
                break;
            case 'followfollow':
                $result = _followFollowId();//todo 关注的关注
                if ($result) {
                    $list[] = $result;
                } else {
                    $none_recommend[] = 'followfollow';
                }
                break;
            default:
                break;
        }
    } while (count($list) < $num && count($config));
    return $list;
}

/**
 * 获取后台配置参数
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */
function _getAddonsConfig()
{

    $config = S('RECOMMEND_ADDON_CONFIG');
    if (!$config) {
        $config = M('Addons')->where(array('name' => 'Recommend'))->find();
        $config = json_decode($config['config'], true);
        $config = array('method' => $config['howToRecommend'], 'number' => $config['howManyRecommend']);
        S('RECOMMEND_ADDON_CONFIG', $config, 600);
    }
    return $config;
}



/**
 * @return array|mixed
 * 随机推荐方法，返回otherfollow
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */

function _randRecommend()
{

    $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
    $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
    $already_show = S('recommend_follow_id_' . is_login());
    if ($already_show) {

        $other_follow = array_merge($follow_who_ids, $already_show);
        $other_follow = array_merge($other_follow, array(is_login()));
        $map_1['status']=array('eq',1);
        $map_1['uid'] = array('not in', $other_follow);//去除已关注的人
        $other_follow = D('Member')->where($map_1)->field('uid')->order('rand()')->limit(1)->select();
        $other_follow = array_column($other_follow, 'uid');
        S('recommend_follow_id_' . is_login(), array_merge($already_show, $other_follow), 600);

    } else {
        $other_follow = $follow_who_ids;
        $other_follow = array_merge($other_follow, array(is_login()));
        $map_2['status']=array('eq',1);
        $map_2['uid'] = array('not in', $other_follow);//去除已关注的人
        $other_follow = D('Member')->where($map_2)->field('uid')->order('rand()')->limit(1)->select();

        $other_follow = array_column($other_follow, 'uid');
        S('recommend_follow_id_' . is_login(), $other_follow, 600);

    }
    if ($other_follow) {
        $reason = ' <sapn style="color: #808080">随机推荐</sapn>';
        $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
        $other_follow['reason'] = $reason;
    }
    //输出
    return $other_follow;

}

/**
 * @return array|mixed
 * 同城推荐方法，返回otherfollow
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */
function _sameCityRecommend()
{

    $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
    $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
    $my_place = query_user(array('uid', 'pos_province', 'pos_city', 'pos_district'));
    $already_show = S('recommend_follow_id_' . is_login());
    if ($already_show) {
        $follow_who_ids = array_merge($follow_who_ids, $already_show, array(is_login()));
    } else {
        $follow_who_ids = array_merge($follow_who_ids, array(is_login()));
    }
    $map_d['status']=array('eq',1);
    $map_d['uid'] = array('not in', $follow_who_ids);//去除已关注的人
    $map_d['pos_district'] = $my_place['pos_district'];
    $other_follow = D('Member')->where($map_d)->field('uid')->limit(1)->select();//符合以上条件的推荐
    $other_follow = array_column($other_follow, 'uid');

    if (count($other_follow)) {
        if ($already_show) {
            $map = array_merge($other_follow, $already_show);
        } else {
            $map = $other_follow;
        }
        S('recommend_follow_id_' . is_login(), $map, 600);
    } else {
        $map_c['status']=array('eq',1);
        $map_c['uid'] = array('not in', $follow_who_ids);//去除已关注的人
        $map_c['pos_city'] = $my_place['pos_city'];
        $other_follow = D('Member')->where($map_c)->field('uid')->limit(1)->select();//符合以上条件的推荐
        $other_follow = array_column($other_follow, 'uid');

        if (count($other_follow)) {
            if ($already_show) {
                $map = array_merge($other_follow, $already_show);
            } else {
                $map = $other_follow;
            }
            S('recommend_follow_id_' . is_login(), $map, 600);
        } else {
            $map_p['status']=array('eq',1);
            $map_p['uid'] = array('not in', $follow_who_ids);//去除已关注的人
            $map_p['pos_province'] = $my_place['pos_province'];
            $other_follow = D('Member')->where($map_p)->field('uid')->limit(1)->select();//符合以上条件的推荐
            $other_follow = array_column($other_follow, 'uid');
            if (count($other_follow)) {
                if ($already_show) {
                    $map = array_merge($other_follow, $already_show);
                } else {
                    $map = $other_follow;
                }
                S('recommend_follow_id_' . is_login(), $map, 600);
            } else {
                $map_o['status']=array('eq',1);
                $map_o['uid'] = array('not in', $follow_who_ids);//去除已关注的人
                $other_follow = D('Member')->where($map_o)->order('rand()')->field('uid')->limit(1)->select();//符合以上条件的推荐
                $other_follow = array_column($other_follow, 'uid');
                if ($other_follow) {
                    if ($already_show) {
                        $map = array_merge($other_follow, $already_show);
                    } else {
                        $map = $other_follow;
                    }
                    S('recommend_follow_id_' . is_login(), $map, 600);
                }
            }
        }
    }
    if ($other_follow) {
        $reason = ' <span style="color: #808080">同城推荐</span>';
        $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
        $other_follow['reason'] = $reason;
    }
    //输出
    return $other_follow;

}

/**
 * @return array|mixed
 * 管理员推荐方法，返回otherfollow
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */
function _adminRecommend()
{

    $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
    $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
    $follow_who_ids = array_merge($follow_who_ids, array(is_login()));

    $config = M('Addons')->where(array('name' => 'Recommend'))->find();//获取用户推荐ID
    $config = json_decode($config['config'], ture);
    $recommend_uid = $config['recommendUser'];
    $recommend_uid = explode(" ", $recommend_uid);//把字符串转换为数组

    $already_show = S('recommend_follow_id_' . is_login());

    if ($already_show) {
        $other_follow = array_merge($follow_who_ids, $already_show);
        $other_follow = array_unique($other_follow);
        $recommend_uid = array_diff($recommend_uid, $other_follow);//去除已经关注的人。
        $map['status']=array('eq',1);
        $map['uid'] = array('in', $recommend_uid);
        $other_follow = D('Member')->where($map)->limit(1)->select();
        $other_follow = array_column($other_follow, 'uid');
        S('recommend_follow_id_' . is_login(), array_merge($already_show, $other_follow), 600);
    } else {
        $other_follow = $follow_who_ids;
       // $other_follow = array_column($other_follow, 'uid');
        $recommend_uid = array_diff($recommend_uid, $other_follow);//去除已经关注的人。
        $map['status']=array('eq',1);
        $map['uid'] = array('in', $recommend_uid);
        $other_follow = D('Member')->where($map)->limit(1)->select();
        $other_follow = array_column($other_follow, 'uid');
        S('recommend_follow_id_' . is_login(), $other_follow, 600);
    }

    if ($other_follow) {
        $reason = ' <sapn style="color: #808080">管理员推荐</sapn>';
        $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
        $other_follow['reason'] = $reason;
    }
    //输出
    return $other_follow;

}

/**
 * @return array|mixed
 * 相同资料推荐方法，返回otherfollow
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */

function _sameDataRecommend()
{

    $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
    $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。

    $my_data = query_user(array('birthday', 'sex', 'score'));
    $already_show = S('recommend_follow_id_' . is_login());
    if ($already_show) {
        $follow_who_ids = array_merge($follow_who_ids, $already_show, array(is_login()));
    } else {
        $follow_who_ids = array_merge($follow_who_ids, array(is_login()));
    }
    $map_b['status']=array('eq',1);
    $map_b['uid'] = array('not in', $follow_who_ids);//去除已关注的人
    $map_b['birthday'] = $my_data['birthday'];
    $other_follow = D('Member')->where($map_b)->field('uid')->limit(1)->select();//符合以上条件的推荐
    $other_follow = array_column($other_follow, 'uid');

    if (count($other_follow)) {
        if ($already_show) {
            $map = array_merge($other_follow, $already_show);
        } else {
            $map = $other_follow;
        }
        S('recommend_follow_id_' . is_login(), $map, 600);

    } else {
        $map_c['status']=array('eq',1);
        $map_c['uid'] = array('not in', $follow_who_ids);//去除已关注的人
        $map_c['score'] = $my_data['score'];
        $other_follow = D('Member')->where($map_c)->field('uid')->limit(1)->select();//符合以上条件的推荐
        $other_follow = array_column($other_follow, 'uid');
        if (count($other_follow)) {
            if ($already_show) {
                $map = array_merge($other_follow, $already_show);
            } else {
                $map = $other_follow;
            }
            S('recommend_follow_id_' . is_login(), $map, 600);
        } else {
            $map_p['status']=array('eq',1);
            $map_p['uid'] = array('not in', $follow_who_ids);//去除已关注的人
            $map_p['sex'] = $my_data['sex'];
            $other_follow = D('Member')->where($map_p)->field('uid')->limit(1)->select();//符合以上条件的推荐
            $other_follow = array_column($other_follow, 'uid');

            if (count($other_follow)) {
                if ($already_show) {
                    $map = array_merge($other_follow, $already_show);
                } else {
                    $map = $other_follow;
                }
                S('recommend_follow_id_' . is_login(), $map, 600);
            } else {
                $map_o['status']=array('eq',1);
                $map_o['uid'] = array('not in', $follow_who_ids);//去除已关注的人
                $other_follow = D('Member')->where($map_o)->order('rand()')->field('uid')->limit(1)->select();//符合以上条件的推荐
                $other_follow = array_column($other_follow, 'uid');
                if ($other_follow) {
                    if ($already_show) {
                        $map = array_merge($other_follow, $already_show);
                    } else {
                        $map = $other_follow;
                    }
                    S('recommend_follow_id_' . is_login(), $map, 600);
                }
            }
        }
    }

    if ($other_follow) {
        $reason = ' <sapn style="color: #808080">共同信息推荐</sapn>';
        $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
        $other_follow['reason'] = $reason;
    }
    return $other_follow;


}

/**
 * @return array|mixed
 * 我关注的人关注推荐方法，返回otherfollow
 * Date:2015/3/25
 * @author 徐敏威<zzl@ourstu.com>
 */

function _followFollowId()
{
    //得到follow who的ID
    $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
    $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
    if (count($follow_who_ids)) {
        $map['status']=array('eq',1);
        $map['who_follow'] = array('in', $follow_who_ids);
        $follow_follow_ids = D('Follow')->where($map)->field('follow_who')->select();  //where的查询条件,得到我关注的人，关注的人的ID。
        $follow_follow_ids = array_column($follow_follow_ids, 'follow_who');
        if (count($follow_follow_ids)) {

            $already_show = S('recommend_follow_id_' . is_login());
            if ($already_show) {
                $other_follow = array_merge($follow_who_ids, $already_show, array(is_login()));
                $other_follow = array_diff($follow_follow_ids, $other_follow);
                $map_1['status']=array('eq',1);
                $map_1['uid'] = array('in', $other_follow);
                $other_follow = D('Member')->where($map_1)->order('score desc')->field('uid')->limit(1)->select();
                $other_follow = array_column($other_follow, 'uid');
                if (!is_null($other_follow)) {
                    S('recommend_follow_id_' . is_login(), array_merge($already_show, $other_follow), 600);
                }
            } else {
                $other_follow = $follow_who_ids;
                $other_follow = array_merge($other_follow, array(is_login()));
                $other_follow = array_diff($follow_follow_ids, $other_follow);
                $map_1['status']=array('eq',1);
                $map_1['uid'] = array('in', $other_follow);
                $other_follow = D('Member')->where($map_1)->order('score desc')->field('uid')->limit(1)->select();
                $other_follow = array_column($other_follow, 'uid');
                if (!is_null($other_follow)) {
                    S('recommend_follow_id_' . is_login(), $other_follow, 600);
                }
            }
            if ($other_follow) {
                $reason = ' <sapn style="color: #808080">好友的好友推荐</sapn>';
                $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
                $other_follow['reason'] = $reason;
            }
        } else {
            $already_show = S('recommend_follow_id_' . is_login());
            if ($already_show) {
                $other_follow = array_merge($follow_who_ids, $already_show, array(is_login()));
                $map_2['status']=array('eq',1);
                $map_2['uid'] = array('not in', $other_follow);
                $other_follow = D('Member')->where($map_2)->order('score desc')->field('uid')->limit(1)->select();
                $other_follow = array_column($other_follow, 'uid');
                if (!is_null($other_follow)) {
                    S('recommend_follow_id_' . is_login(), array_merge($already_show, $other_follow), 600);
                }
            } else {
                $other_follow = $follow_who_ids;
                $other_follow = array_merge($other_follow, array(is_login()));
                $map_2['status']=array('eq',1);
                $map_2['uid'] = array('not in', $other_follow);
                $other_follow = D('Member')->where($map_2)->order('score desc')->field('uid')->limit(1)->select();
                $other_follow = array_column($other_follow, 'uid');
                if (!is_null($other_follow)) {
                    S('recommend_follow_id_' . is_login(), $other_follow, 600);
                }
            }
            if ($other_follow) {
                $reason = ' <sapn style="color: #808080">随机推荐</sapn>';
                $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
                $other_follow['reason'] = $reason;
            }
        }

    } else {
        $already_show = S('recommend_follow_id_' . is_login());
        if ($already_show) {
            $other_follow = array_merge($follow_who_ids, $already_show, array(is_login()));
            $map_2['status']=array('eq',1);
            $map_2['uid'] = array('not in', $other_follow);
            $other_follow = D('Member')->where($map_2)->order('score desc')->field('uid')->limit(1)->select();
            $other_follow = array_column($other_follow, 'uid');
            if (!is_null($other_follow)) {
                S('recommend_follow_id_' . is_login(), array_merge($already_show, $other_follow), 600);
            }
        } else {
            $other_follow = $follow_who_ids;
            $other_follow = array_merge($other_follow, array(is_login()));
            $map_2['status']=array('eq',1);
            $map_2['uid'] = array('not in', $other_follow);
            $other_follow = D('Member')->where($map_2)->order('score desc')->field('uid')->limit(1)->select();
            $other_follow = array_column($other_follow, 'uid');
            if (!is_null($other_follow)) {
                S('recommend_follow_id_' . is_login(), $other_follow, 600);
            }
        }
        if ($other_follow) {
            $reason = ' <sapn style="color: #808080">随机推荐</sapn>';
            $other_follow = query_user(array('uid', 'avatar64', 'nickname', 'signature', 'space_url'), $other_follow[0]);
            $other_follow['reason'] = $reason;
        }

    }

    //输出
    return $other_follow;

}
