<?php
/**
 * 中文转拼音类
 * */
namespace Common\Model;

class UserModel
{
    private $table_fields = array(
        //member
        'uid', 'nickname', 'sex', 'birthday', 'qq', 'signature', 'pos_province', 'pos_city', 'pos_district', 'pos_community', 'score1', 'score2', 'score3', 'score4','con_check','total_check',
        //ucmember
        'id', 'username', 'password', 'email', 'mobile'
    );

    private $avatar_fields = array('avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512');
    private $avatar_html_fields=array('avatar_html32', 'avatar_html64', 'avatar_html128', 'avatar_html256', 'avatar_html512');

    private function getFields($pFields)
    {
        //默认赋值
        if ($pFields === null) {
            return array('nickname', 'space_url', 'space_mob_url', 'avatar32', 'avatar64', 'avatar128', 'uid');
        }

        //如果fields不是数组，直接返回需要的值
        if (is_array($pFields)) {
            $fields = $pFields;
        } else {
            $fields = (array)explode(',', $pFields);
        }
        //替换score和score1
        if (array_intersect(array('score','score1'), $fields)) {
            $fields = array_diff($fields, array('score', 'score1'));
            $fields[] = 'score1';
        }
        if (in_array('title', $fields)) {
            if (!in_array('score1', $fields)) {
                $fields[] = 'score1';
            }
        }
        return $fields;

    }

    private function popGotFields($fiels, $gotFields)
    {
        if(count($gotFields)!=0){
            return array_diff($fiels, $gotFields);
        }
        return $fiels;

    }

    private function combineUserData($user_data, $values)
    {
        return array_merge($user_data, (array)$values);
    }

    /**从数据库获取需要检索的数据
     * @param $user_data
     * @param $fields
     * @return array
     */
    private function getNeedQueryData($user_data, $fields, $uid)
    {
        $need_query = array_intersect($this->table_fields, $fields);
        //如果有需要检索的数据
        if (!empty($need_query)) {
            $db_prefix=C('DB_PREFIX');
            $query_results = D('')->query('select ' . implode(',', $need_query) . " from `{$db_prefix}member`,`{$db_prefix}ucenter_member` where uid=id and uid={$uid} limit 1");
            $query_result = $query_results[0];
            $user_data = $this->combineUserData($user_data, $query_result);
            $fields = $this->popGotFields($fields, $need_query);
            $this->writeCache($uid, $query_result);
        }
        return array($user_data, $fields);
    }

    private function handleNickName($user_data, $uid)
    {
        $user_data['real_nickname'] = $user_data['nickname'];
        if (get_uid() != $uid && is_login()) {//如果已经登陆，并且获取的用户不是自己
            $alias = $this->getAlias($uid);
            if ($alias != '') {//如果设置了备注名
                $user_data['nickname'] = $alias;
                $user_data['alias'] = $alias;
            }
        }
        return $user_data;
    }

    private function debug($user_data, $fields)
    {
        return '';
        echo '————————————————————running query_user——————————————<br/>';
        echo('user_data:');
        dump($user_data);
        echo 'fields:';
        dump($fields);
    }


    /**
     * @param null $pFields
     * @param int $uid
     * @return array|mixed
     */
    function query_user($pFields = null, $uid = 0)
    {
        $user_data = array();//用户数据
        $fields = $this->getFields($pFields);//需要检索的字段
        $uid = (intval($uid) != 0 ? $uid : get_uid());//用户UID
        //获取缓存过的字段，尽可能在此处命中全部数据

        list($cacheResult, $fields) = $this->getCachedFields($fields, $uid);
        $user_data = $cacheResult;//用缓存初始用户数据
        //从数据库获取需要检索的数据，消耗较大，尽可能在此代码之前就命中全部数据
        list($user_data, $fields) = $this->getNeedQueryData($user_data, $fields, $uid);
        //必须强制处理昵称备注
        if (in_array('nickname', (array)$pFields))
            $user_data = $this->handleNickName($user_data, $uid);
        //获取昵称拼音 pinyin
        $user_data = $this->getPinyin($fields, $user_data);
        //如果全部命中，则直接返回数据


        if (array_intersect(array('score','score1'), $pFields)) {
            $user_data['score'] = $user_data['score1'];
        }
        if (empty($fields)) {
            return $user_data;
        }

        $this->debug($user_data, $fields);
        $user_data = $this->handleTitle($uid, $fields, $user_data);
        //获取头像Avatar数据
        $user_data = $this->getAvatars($user_data, $fields, $uid);
        $user_data = $this->getUrls($fields, $uid, $user_data);

        $user_data = $this->getRankLink($fields, $uid, $user_data);

        $user_data = $this->getExpandInfo($fields, $uid, $user_data);

        //认证状态
        $user_data = $this->getAttest($fields, $uid, $user_data);

        //获取头像，带认证图标的html代码
        $user_data = $this->getAvatarsHtml($fields, $uid, $user_data);

        //粉丝数、关注数、微博数
        if (in_array('fans', $fields)) {
            $user_data['fans'] = M('Follow')->where('follow_who=' . $uid)->count();
            $this->write_query_user_cache($uid, 'fans', $user_data['fans']);
        }
        if (in_array('following', $fields)) {
            $user_data['following'] = M('Follow')->where('who_follow=' . $uid)->count();
            $this->write_query_user_cache($uid, 'following', $user_data['following']);
        }
        if (in_array('weibocount', $fields)) {
            $user_data['weibocount'] = M('Weibo')->where('uid=' . $uid . ' and status >0')->count();
            $this->write_query_user_cache($uid, 'weibocount', $user_data['weibocount']);
        }
        //是否关注、是否被关注
        if (in_array('is_following', $fields)) {
            $follow = D('Follow')->where(array('who_follow' => get_uid(), 'follow_who' => $uid))->find();
            $user_data['is_following'] = $follow ? true : false;
            $this->write_query_user_cache($uid, 'is_following', $user_data['is_following']);
        }
        if (in_array('is_followed', $fields)) {
            $follow = D('Follow')->where(array('who_follow' => $uid, 'follow_who' => get_uid()))->find();
            $user_data['is_followed'] = $follow ? true : false;
            $this->write_query_user_cache($uid, 'is_followed', $user_data['is_following']);
        }

        return $user_data;

    }

    /**获取用户昵称
     * @param $uid
     * @return mixed|string
     */
    private function getAlias($uid)
    {
        //获取缓存的alias
        $tag = 'alias_' . get_uid() . '_' . $uid;
        $alias = S($tag);
        if ($alias === false) {
            //没有缓存
            $alias = '';
            $follow = D('Common/Follow')->getFollow(get_uid(), $uid);//获取关注情况
            if ($follow && $follow['alias'] != '') {//已关注
                $alias = $follow['alias'];
            }
            S($tag, $alias);
        }
        return $alias;
    }

    function read_query_user_cache($uid, $field)
    {
        return S("query_user_{$uid}_{$field}");
    }

    function write_query_user_cache($uid, $field, $value)
    {
        return S("query_user_{$uid}_{$field}", $value);
    }

    /**清理用户数据缓存，即时更新query_user返回结果。
     * @param $uid
     * @param $field
     * @auth 陈一枭
     */
    function clean_query_user_cache($uid, $field)
    {
        if (is_array($field)) {
            foreach ($field as $field_item) {
                S("query_user_{$uid}_{$field_item}", null);
            }
        } else {
            S("query_user_{$uid}_{$field}", null);
        }

    }

    /**
     * @param $fields
     * @param $uid
     * @return array
     */
    public function getCachedFields($fields, $uid)
    {
//查询缓存，过滤掉已缓存的字段
        $cachedFields = array();
        $cacheResult = array();
        if (array_intersect(array('space_url', 'space_link', 'space_mob_url'), $fields)) {

            $urls = $this->read_query_user_cache($uid, 'urls');
            if ($urls !== false) {

                $cacheResult = array_merge($urls, $cacheResult);
                $fields = $this->popGotFields($fields, array('space_url', 'space_link', 'space_mob_url'));
            }
        }

        if (array_intersect($this->avatar_fields, $fields)) {
            $avatars = $this->read_query_user_cache($uid, 'avatars');
            if ($avatars !== false) {
                $cacheResult = array_merge($avatars, $cacheResult);
                $fields = $this->popGotFields($fields, $this->avatar_fields);
            }
        }

        if (array_intersect($this->avatar_html_fields, $fields)) {
            $avatars_html = $this->read_query_user_cache($uid, 'avatars_html');
            if ($avatars_html !== false) {
                $cacheResult = array_merge($avatars_html, $cacheResult);
                $fields = $this->popGotFields($fields, $this->avatar_html_fields);
            }
        }

        foreach ($fields as $field) {
            $cache = $this->read_query_user_cache($uid, $field);
            if ($cache !== false) {
                $cacheResult[$field] = $cache;
                $cachedFields[] = $field;
            }
        }
        //去除已经缓存的字段
        if(count($cachedFields)!=0){
            $fields = array_diff($fields, $cachedFields);
        }

        return array($cacheResult, $fields);
    }

    /**
     * @param $fields
     * @param $homeFields
     * @param $ucenterFields
     * @return array
     */
    public function getSplittedFields($fields, $homeFields, $ucenterFields)
    {
        $avatarFields = $this->avatar_fields;
        $avatarFields = array_intersect($avatarFields, $fields);
        $homeFields = array_intersect($homeFields, $fields);
        $ucenterFields = array_intersect($ucenterFields, $fields);
        return array($avatarFields, $homeFields, $ucenterFields);
    }

    /**
     * @param $fields
     * @param $uid
     * @return array
     */
    public function getSplittedFieldsValue($fields, $uid)
    {
//获取两张用户表格中的所有字段
        $homeFields = M('Member')->getDBFields();
        $ucenterFields = M('UcenterMember')->getDBFields();

        //分析每个表格分别要读取哪些字段
        list($avatarFields, $homeFields, $ucenterFields) = $this->getSplittedFields($fields, $homeFields, $ucenterFields);


        //查询需要的字段
        $homeResult = array();
        $ucenterResult = array();
        if ($homeFields) {
            $homeResult = M('Member')->where(array('uid' => $uid))->field($homeFields)->find();
        }
        if ($ucenterFields) {
            $model = M('UcenterMember');
            $ucenterResult = $model->where(array('id' => $uid))->field($ucenterFields)->find();
            return array($avatarFields, $homeResult, $ucenterResult);
        }
        return array($avatarFields, $homeResult, $ucenterResult);
    }

    /**
     * @param $uid
     * @param $avatarFields
     * @return array
     */
    public function getAvatars($user_data, $fields, $uid)
    {
        //读取头像数据
        if (array_intersect($fields, $this->avatar_fields)) {
            $avatarFields = $this->avatar_fields;
            //如果存在需要检索的头像
            $avatarObject = new \Ucenter\Widget\UploadAvatarWidget();

            foreach ($avatarFields as $e) {
                $avatarSize = intval(substr($e, 6));
                $avatarUrl = $avatarObject->getAvatar($uid, $avatarSize);
                $check = file_exists('./api/uc_login.lock');
                if ($check) {
                    include_once './api/uc_client/client.php';
                    $avatarUrl = UC_API . '/avatar.php?uid=' . $uid . '&size=big';
                }
                $avatars[$e] = $avatarUrl;
            }

            $user_data = array_merge($user_data, $avatars);
            $this->write_query_user_cache($uid, 'avatars', $avatars);
            $this->popGotFields($fields, $avatarFields);

        }
        return $user_data;
    }

    /**
     * 认证状态
     * @param $fields
     * @param $uid
     * @param $result
     * @return mixed
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function getAttest($fields, $uid, $result)
    {
        //获取用户头衔链接
        if (in_array('attest', $fields)) {
            $attest = D('attest')->where(array('uid' => $uid, 'status' => 1))->find();
            if($attest){
                $attest['type']=D('attest_type')->where(array('id'=>$attest['attest_type_id']))->find();
                if($attest['type']['logo']){
                    $attest['logo']=get_cover($attest['type']['logo'],'path');
                }else{
                    $attest['logo']=__ROOT__.'/Public/images/attest/'.$attest['type']['id'].'-logo.png';
                }
                $result['attest']=array('has'=>1,'info'=>$attest,'logo'=>$attest['logo']);
            }else{
                $result['attest']=array('has'=>0);
            }
            $this->write_query_user_cache($uid, 'attest', $result['attest']);
        }
        return $result;
    }

    /**
     * 带认证图标的头像
     * @param $fields
     * @param $uid
     * @param $result
     * @return array
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function getAvatarsHtml($fields, $uid, $result)
    {
        //读取头像数据
        if (array_intersect($fields, $this->avatar_html_fields)) {
            $avatarHtmlFields = $this->avatar_html_fields;
            //如果存在需要检索的头像html
            $user_info=$this->query_user(array('','avatar32','attest'),$uid);
            foreach ($avatarHtmlFields as $e) {
                $avatarSize = intval(substr($e, 11));
                if($user_info['attest']['has']){
                    $one_avatar_html='<span style="position:relative;display: inline-block;">
                                 <img src="'.$user_info['avatar'.$avatarSize].'" class="avatar-img">
                                 <img src="'.$user_info['attest']['logo'].'" class="attest-logo" style="position: absolute;width: 30%;right: 0;bottom: 0;" title="'.$user_info['attest']['info']['child_type'].'">
                              </span>';
                }else{
                    $one_avatar_html='<span style="position: relative;display: inline-block;">
                                    <img src="'.$user_info['avatar'.$avatarSize].'" class="avatar-img">
                                  </span>';
                }
                $avatars_html[$e] = $one_avatar_html;
            }

            $result = array_merge($result, $avatars_html);
            $this->write_query_user_cache($uid, 'avatars_html', $avatars_html);
            $this->popGotFields($fields, $avatarHtmlFields);

        }
        return $result;
    }

    /**
     * @param $fields
     * @param $uid
     * @param $result
     * @return array
     */
    public function getUrls($fields, $uid, $result)
    {
//获取个人中心地址
        $spaceUrlResult = array();
        if (array_intersect(array('space_url', 'space_link', 'space_mob_url'), $fields)) {
            $short_url=D('Ucenter/UcenterShort')->getShortUrl($uid);
            if($short_url['short']){
                $url="http://".$_SERVER['HTTP_HOST'].__ROOT__.'/u/'.$short_url['short'];
            }else{
                $url=U('Ucenter/Index/index', array('uid' => $uid));
            }

            $urls['space_url'] = $url;
            $urls['space_link'] = '<a ucard="' . $uid . '" target="_blank" href="' . $url . '">' . $result['nickname'] . '</a>';
            $urls['space_mob_url'] = U('Mob/User/index', array('uid' => $uid));
            $result = array_merge($result, $urls);
            $this->write_query_user_cache($uid, 'urls', $urls);
        }
        return $result;
    }

    /**
     * @param $fields
     * @param $result
     * @return mixed
     */
    public function getPinyin($fields, $result)
    {
//读取用户名拼音
        if (in_array('pinyin', $fields)) {

            $result['pinyin'] = D('Pinyin')->pinYin($result['nickname']);
            return $result;
        }
        return $result;
    }

    /**
     * @param $fields
     * @param $ucenterResult
     * @return mixed
     */
    public function getNickname($fields, $ucenterResult)
    {
        if (in_array('nickname', $fields)) {
            $ucenterResult['nickname'] = text($ucenterResult['nickname']);
            return $ucenterResult;
        }
        return $ucenterResult;
    }

    /**
     * @param $fields
     * @param $uid
     * @param $val
     * @param $result
     * @return array
     */
    public function getRankLink($fields, $uid, $result)
    {
        //获取用户头衔链接
        if (in_array('rank_link', $fields)) {
            $rank_List = D('rank_user')->where(array('uid' => $uid, 'status' => 1))->select();
            $num = 0;
            foreach ($rank_List as &$val) {
                $rank = M('rank')->where('id=' . $val['rank_id'])->find();
                $val['title'] = $rank['title'];
                $val['logo_url'] = get_pic_src(M('picture')->where('id=' . $rank['logo'])->field('path')->getField('path'));
                $val['label_content'] = $rank['label_content'];
                $val['label_bg'] = $rank['label_bg'];
                $val['label_color'] = $rank['label_color'];
                if ($val['is_show']) {
                    $num = 1;
                }
            }

            if ($rank_List) {
                $rank_List[0]['num'] = $num;
                $result['rank_link'] = $rank_List;
            } else {
                $result['rank_link'] = array();
            }
            $this->write_query_user_cache($uid, 'rank_link', $result['rank_link']);
        }
        return $result;
    }

    /**
     * @param $fields
     * @param $uid
     * @param $map
     * @param $map_field
     * @param $result
     * @return mixed
     */
    public function getExpandInfo($fields, $uid, $result)
    {
        if (in_array('expand_info', $fields)) {
            $map['status'] = 1;
            $field_group = D('field_group')->where($map)->select();
            $field_group_ids = array_column($field_group, 'id');
            $map['profile_group_id'] = array('in', $field_group_ids);
            $fields_list = D('field_setting')->where($map)->getField('id,field_name,form_type,visiable');
            $fields_list = array_combine(array_column($fields_list, 'field_name'), $fields_list);
            $map_field['uid'] = $uid;
            foreach ($fields_list as $key => $val) {
                $map_field['field_id'] = $val['id'];
                $field_data = D('field')->where($map_field)->getField('field_data');
                if ($field_data == null || $field_data == '') {
                    unset($fields_list[$key]);
                } else {
                    if ($val['form_type'] == "checkbox") {
                        $field_data = explode('|', $field_data);
                    }
                    $fields_list[$key]['data'] = $field_data;
                }
            }
            $result['expand_info'] = $fields_list;
            $this->write_query_user_cache($uid, 'expand_info', $fields_list);
        }
        return $result;
    }

    /**
     * @param $uid
     * @param $result
     * @return mixed
     */
    public function writeCache($uid, $result)
    {
//写入缓存
        foreach ($result as $field => $value) {
            if (!in_array($field, array('rank_link', 'expand_info','attest'))) {
                $value = str_replace('"', '', text($value));
            }

            $result[$field] = $value;
            write_query_user_cache($uid, $field, str_replace('"', '', $value));
        }
        return $result;
    }

    /**
     * @param $uid
     * @param $fields
     * @param $user_data
     * @return mixed
     */
    protected function handleTitle($uid, $fields, $user_data)
    {
//读取等级数据
        if (in_array('title', $fields)) {
            $titleModel = D('Ucenter/Title');
            $title = $titleModel->getTitleByScore($user_data['score1']);
            $user_data['title'] = $title;
            $this->write_query_user_cache($uid, 'title', $title);
            return $user_data;
        }
        return $user_data;
    }

}