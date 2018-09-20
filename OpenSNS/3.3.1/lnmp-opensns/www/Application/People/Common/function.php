<?php
/**
 * Created by 范佳炜
 * User: fjw@ourstu.com
 * Date: 2016/9/5 0005  下午 2:05
 */
/**
 * 随机推荐关注
 * 范佳炜 fjw@ourstu.com
 */
function rand_follow($count,$time){
    $myuid=is_login();
    $rand=S('rand_follow_cache_'.$myuid);
    if(empty($rand)){
        $lastUid=D('member')->where(array('status'=>1,))->order('reg_time desc')->getfield('uid');
        $followList=D('Common/Follow')->getFollowList();
        if($lastUid<100){
            $rand=null;
        }else{
            $allList=range(100,$lastUid);
            $uidList=array_diff($allList,$followList);
            $count=$count<=(count($uidList))?$count:(count($uidList));
            $uids=array_rand($uidList,$count);
            foreach ($uids as $u){
                $rand[]=query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'title', 'fans', 'following', 'rank_link', 'pos_province', 'pos_city', 'pos_district'), $uidList[$u]);
            }unset($u);
            foreach ($rand as &$ra){
                $ra['isfollow']=0;
            }
            unset($ra);

            S('rand_follow_cache_'.$myuid,$rand,$time);
        }

    }
    return $rand;
}

/**
 * 好友也关注
 * 范佳炜 fjw@ourst.com
 */
function friend_follow($count,$time){
    $uid=is_login();
    $arr=S('friend_follow_cache_'.$uid);
    if(empty($arr)){
        $info=D('Common/Follow')->getMyFriends($uid);
        $all=array();
        foreach ($info as $f){
            $ids=D('Common/Follow')->where(array('who_follow'=>$f))->field('follow_who')->select();
            $all=array_merge($all,$ids);
        }unset($f);

        $myfollow=D('Common/Follow')->getFollowList();

        foreach ($all as &$a){
            $friends[]=$a['follow_who'];
        }
        unset($a);
        $friends=array_unique($friends);
        $friends=array_diff($friends,$myfollow);
        foreach ($friends as $friend){
           $arrFriend[]=$friend;
        }

        $count=$count<count($arrFriend)?$count:count($arrFriend);
        for($k=0;$k<$count;$k++){
            $arr[]=query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'title', 'fans', 'following', 'rank_link', 'pos_province', 'pos_city', 'pos_district'), $arrFriend[$k]);
        }
        unset($k);

        foreach ($arr as &$ra){
            $ra['isfollow']=0;
        }
        unset($ra);
        S('friend_follow_cache_'.$uid,$arr,$time);
    }
    return $arr;
}

/**
 * 社群明星
 * @return mixed
 */
function star_follow(){
    $uid=is_login();
    $arr=S('star_follow_cache_'.$uid);
    if(empty($arr)){
        $star=D('member')->where(array('status'=>1))->order('fans desc')->limit(4)->field('uid')->select();
        foreach ($star as $s){
            $arr[]=query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'title', 'fans', 'following', 'rank_link', 'pos_province', 'pos_city', 'pos_district'), $s['uid']);
        }unset($s);

        foreach ($arr as &$ra){
            $ra['isfollow']=D('Common/Follow')->isFollow($uid,$ra['uid'])?1:0;
        }
        unset($ra);
        S('star_follow_cache_'.$uid,$arr,60*60);
    }
    return $arr;
}
function int2str($val){
    if($val>10000){
        $val=number_format($val/10000,1).'万';
    }
    return $val;
}
function other_follow($field,$count,$time,$order){
    $uid=is_login();
    $arr=S($field.'_follow_cache_'.$uid);
    if(empty($arr)){
        $myfollow=D('Common/Follow')->getFollowList();
        $map['status']=1;
        $map['uid']=array('not in',$myfollow);
        $ids=D('member')->where($map)->order($field,$order)->limit($count)->field('uid')->select();
        foreach ($ids as $i){
            $arr[]=query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'title', 'fans', 'following', 'rank_link', 'pos_province', 'pos_city', 'pos_district'), $i['uid']);
        }
        unset($i);

        foreach ($arr as &$ra){
            $ra['isfollow']=D('Common/Follow')->isFollow($uid,$ra['uid'])?1:0;
        }
        unset($ra);
        S($field.'_follow_cache_'.$uid,$arr,$time);
    }
    return $arr;
}
/**
 * @param $field 排序值
 * @param $type 0是右上侧展示  3是右下侧展示
 */
function show_side($field,$type){
    $count=modC('USER_SHOW_COUNT'.$type,4,'people');
    $time=modC('USER_SHOW_CACHE_TIME'.$type,'600','people');
    $order=modC('USER_SHOW_ORDER_TYPE'.$type,'desc','people');
    switch ($field){
        case 'rand':
            $data=rand_follow($count,$time);
            break;
        case 'friend':
            $data=friend_follow($count,$time);
            break;
        default:
            $data=other_follow($field,$count,$time,$order);
    }
    return $data;
}
