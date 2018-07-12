<?php
/**
 * 用户展示Widget.
 *
 * @author zivss <guolee226@gmail.com>
 *
 * @version TS3.0
 */
class UserListWidget extends Widget
{
    /**
     * 模板渲染.
     *
     * @param array $data 相关数据
     *
     * @return string 用户展示列表
     */
    public function render($data)
    {
        // 设置模板
        $template = '';
        if (in_array($data['type'], array('tag', 'area'))) {
            $template = 'filter';
        } else {
            $template = 'user';
        }
        // 获取一级行业分类
        $var['cid'] = intval($data['cid']);
        $var['sex'] = intval($data['sex']);
        $var['area'] = intval($data['area']);
        $var['verify'] = intval($data['verify']);
        $var['type'] = t($data['type']);
        $var['uids'] = t($data['uids']);
        $var['pid'] = intval($data['pid']);
        $var['app'] = t($data['app']);
        $var['lastUid'] = intval($data['lastUid']) ? intval($data['lastUid']) : 0;
        $var['limit'] = intval($data['limit']) ? intval($data['limit']) : 30;
        $var['page'] = intval($data['page']) ? intval($data['page']) : 1;
        $var['userList'] = D('People', 'people')->getPeople($var, $var['type']);
        $var['count'] = $var['userList']['totalRows'];
        $var['cur_lastid'] = -1;
        $uids = array();
        empty($var['userList']['data']) or (($uids = end($var['userList']['data'])) and ($var['cur_lastid'] = $uids['uid']));
        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__).'/'.$template.'.html', $var);

        // 输出数据
        return $content;
    }
}
