<?php

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

class AdvController extends AdminController
{

    public function pos($page = 1, $r = 20)
    {
        $aModule = I('module', '', 'text');
        $aTheme = I('theme', 'all', 'text');
        $aStatus = I('status', 1, 'intval');
        $_GET['status'] = $aStatus;
        if ($aModule == '') {
            $this->posModule();
            return;
        }

        $adminList = new AdminListBuilder();

        $map['path'] = array('like', ucfirst($aModule . '/%'));
        $map['status'] = $aStatus;
        if ($aTheme == 'all' || $aTheme == '') {

        } else {
            $map['theme'] = array('like', array("%,$aTheme", "%,$aTheme,%", "$aTheme,%"));

        }
        $advPosModel = D('AdvPos');
        $advModel = D('Adv');
        $advPoses = $advPosModel->where($map)->select();
        $themes = D('Common/Theme')->getThemeList();

        foreach ($advPoses as &$v) {
            switch ($v['type']) {
                case 1:
                    $v['type_html'] = '<span class="text-danger">单图</span>';
                    break;
                case 2:
                    $v['type_html'] = '<span class="text-warning">多图轮播</span>';
                    break;
                case 3:
                    $v['type_html'] = '<span class="text-success">文字链接</span>';
                    break;
                case 4:
                    $v['type_html'] = '<span class="text-error">代码块</span>';
                    break;
            }
            if ($v['theme'] != 'all') {
                $theme_names = explode(',', $v['theme']);
                foreach ($theme_names as $t) {
                    $temp_theme[] = $themes[$t]['title'];
                }
                $v['theme_html'] = implode('&nbsp;&nbsp;,&nbsp;&nbsp;', $temp_theme);
                //implode(',',array_map(array($this,'getValue'),$themes,$v['theme']));//   $themes[$v['theme']]['title'];
            } else {
                $v['theme_html'] = '全部主题';
            }

            $count = $advModel->where(array('pos_id' => $v['id'], 'status' => 1))->count();
            $v['do'] = '<a href="' . U('editPos?copy=' . $v['id']) . '"><i class="icon-copy"></i> 复制</a>&nbsp;&nbsp;&nbsp;&nbsp;'
                . '<a href="' . U('editPos?id=' . $v['id']) . '"><i class="icon-cog"></i> 设置</a>&nbsp;&nbsp;&nbsp;&nbsp;'
                . '<a href="' . U('adv?pos_id=' . $v['id']) . '" ><i class="icon-sitemap"></i> 管理广告(' . $count . ')</a>&nbsp;&nbsp;&nbsp;&nbsp;'
                . '<a href="' . U('editAdv?pos_id=' . $v['id']) . '"><i class="icon-plus"></i> 添加广告</a>&nbsp;&nbsp;&nbsp;&nbsp;'
                . '<a href="' . U($v['path']) . '#adv_' . $v['id'] . '" target="_blank"><i class="icon-share-alt"></i>到前台查看</a>&nbsp;&nbsp;';


        }
        unset($v);

        $adminList->title('广告位管理');
        $adminList->buttonNew(U('editPos'), '添加广告位');
        $adminList->buttonDelete(U('setPosStatus'));
        $adminList->buttonDisable(U('setPosStatus'));
        $adminList->buttonEnable(U('setPosStatus'));
        $adminList->keyId()->keyTitle()
            ->keyHtml('do', '操作', '320px')
            ->keyText('name', '广告位英文名')->keyText('path', '路径')->keyHtml('type_html', '广告类型')->keyStatus()->keyText('width', '宽度')->keyText('height', '高度')->keyText('margin', '边缘留白')->keyText('padding', '内部留白')->keyText('theme_html', '适用主题');
        $themes_array[] = array('id' => 'all', 'value' => '--全部主题--');
        foreach ($themes as $v) {
            $themes_array[] = array('id' => $v['name'], 'value' => $v['title']);
        }
        $status_array = array(array('id' => 1, 'value' => '正常'), array('id' => 0, 'value' => '禁用'), array('id' => -1, 'value' => '已删除'));
        $adminList->select('所属主题：', 'theme', 'select', '描述', '', '', $themes_array);
        $adminList->select('状态：', 'status', 'select', '广告位状态', '', '', $status_array);
        $adminList->data($advPoses);
        $adminList->display();
    }

    private static function getValue($array, $index, $col = 'title')
    {
        return $array[$index][$col];
    }

    private function posModule()
    {
        $module = D('Common/Module')->getAll(1);
        $advPosModel = D('AdvPos');
        foreach ($module as $key => &$v) {
            $v['count'] = $advPosModel->where(array('status' => 1, 'path' => array('like', ucfirst($v['name']) . '/%')))->count();
            $v['count'] = $v['count'] == 0 ? $v['count'] : '<strong class="text-danger" style="font-size:18px">' . $v['count'] . '</strong>';
            $v['alias_html'] = '<a href="' . U('pos?module=' . $v['name']) . '">' . $v['alias'] . '</a>';
            $v['do'] = '<a href="' . U('pos?module=' . $v['name']) . '"><i class="icon-sitemap"></i>' . '管理内部广告位' . '</a>';
        }


        $adminList = new AdminListBuilder();
        $adminList->data($module);
        $adminList->title('广告位管理 - 按模块选择');
        $adminList->keyhtml('alias_html', '模块名')->keyHtml('do', '操作')->keyHtml('count', '模块内广告位数量');
        $adminList->display();
    }

    public function setPosStatus()
    {
        $aIds = I('ids', '', 'intval');
        $aStatus = I('get.status', '1', 'intval');
        $advPosModel = D('Common/AdvPos');
        $map['id'] = array('in', implode(',', $aIds));
        $result = $advPosModel->where($map)->setField('status', $aStatus);
        M('Adv')->where(array('pos_id' => array('in', implode(',', $aIds))))->setField('status', $aStatus);
        if ($result === false) {
            $this->error('设置状态失败。');
        } else {
            $this->success('设置状态成功。影响了' . $result . '条数据。');
        }
    }
    public function setAdvStatus()
    {
        $aIds = I('ids', '', 'intval');
        $aStatus = I('get.status', '1', 'intval');
        $advModel = D('Common/Adv');
        $map['id'] = array('in', implode(',', $aIds));
        $result = $advModel->where($map)->setField('status', $aStatus);

        if ($result === false) {
            $this->error('设置状态失败。');
        } else {
            $this->success('设置状态成功。影响了' . $result . '条数据。');
        }
    }
    public function editPos()
    {
        $aId = I('id', 0, 'intval');
        $aCopy = I('copy', 0, 'intval');
        $advPosModel = D('Common/AdvPos');
        if (IS_POST) {
            //是提交

            $pos['name'] = I('name', '', 'text');
            $pos['title'] = I('title', '', 'text');
            $pos['path'] = I('path', '', 'text');
            $pos['type'] = I('type', 1, 'intval');
            $pos['status'] = I('status', 1, 'intval');
            $pos['width'] = I('width', '', 'text');
            $pos['height'] = I('height', '', 'text');
            $pos['margin'] = I('margin', '', 'text');
            $pos['padding'] = I('padding', '', 'text');
            $pos['theme'] = I('theme', 'all', 'text');
            switch ($pos['type']) {
                case 2:
                    //todo 多图
                    $pos['data'] = json_encode(array('style' => I('style', 1, 'intval')));
            }

            if ($aId == 0) {
                $result = $advPosModel->add($pos);
            } else {
                $pos['id'] = $aId;
                $result = $advPosModel->save($pos);
            }

            if ($result === false) {
                $this->error('保存失败。');
            } else {
                S('adv_pos_by_pos_' . $pos['path'] . $pos['name'], null);
                $this->success('保存成功。');
            }

        } else {
            $builder = new AdminConfigBuilder();


            if ($aCopy != 0) {
                $pos = $advPosModel->find($aCopy);
                unset($pos['id']);
                $pos['name'] .= '   请重新设置!';
                $pos['title'] .= '   请重新设置!';
            } else {
                $pos = $advPosModel->find($aId);
            }
            if ($aId == 0) {

                if ($aCopy != 0) {
                    $builder->title('复制广告位——' . $pos['title']);
                } else {
                    $builder->title('新增广告位');
                }
            } else {
                $builder->title($pos['title'] . '【' . $pos['name'] . '】' . ' 设置——' . $advPosModel->switchType($pos['type']));
            }


            $themes = D('Common/Theme')->getThemeList();
            $themes_array['all'] = '<span class="text-success">全部主题</span>';
            foreach ($themes as $v) {
                $themes_array[$v['name']] = $v['title'];
            }
            $builder->keyId()->keyTitle()->keyText('name', '广告位英文名', '标识，同一个页面上不要出现两个同名的')->keyText('path', '路径', '模块名/控制器名/方法名，例如：Weibo/Index/detail')->keyRadio('type', '广告类型', '', array(1 => '单图广告', 2 => '多图轮播', 3 => '文字链接', 4 => '代码'))
                ->keyStatus()->keyText('width', '宽度', '支持各类长度单位，如px，em，%')->keyText('height', '高度', '支持各类长度单位，如px，em，%')
                ->keyText('margin', '边缘留白', '支持各类长度单位，如px，em，%；依次为：上  右  下  左，如 5px 2px 0 3px')->keyText('padding', '内部留白', '支持各类长度单位，如px，em，%；依次为：上  右  下  左，如 5px 2px 0 3px')->keyCheckBox('theme', '适用主题', '', $themes_array);
            $data = json_decode($pos['data'], true);
            if (!empty($data)) {
                $pos = array_merge($pos, $data);
            }

            if ($pos['type'] == 2) {

                $builder->keyRadio('style', '轮播_风格', '', array(1 => 'KinmaxShow 风格'))->keyDefault('style', 1);

            }


            $builder->keyDefault('type', 1)->keyDefault('status', 1);
            $builder->data($pos);
            $builder->buttonSubmit()->buttonBack();
            $builder->display();


        }


    }

    public function adv($r = 20)
    {
        $aPosId = I('pos_id', 0, 'intval');
        $advPosModel = D('Common/AdvPos');
        $pos = $advPosModel->find($aPosId);
        if ($aPosId != 0) {
            $map['pos_id'] = $aPosId;
        }
        $map['status'] = 1;
        $data = D('Adv')->where($map)->order('pos_id desc,sort desc')->findPage($r);

        foreach ($data['data'] as &$v) {
            $p = $advPosModel->find($v['pos_id']);
            $v['pos'] = '<a class="text-danger" href="' . U('adv?pos_id=' . $p['pos_id']) . '">' . $p['title'] . '</a>';
        }

        //todo 广告管理列表
        $builder = new AdminListBuilder();
        if ($aPosId == 0) {
            $builder->title('广告管理');
        } else {
            $builder->title($pos['title'] . '【' . $pos['name'] . '】' . ' 设置——' . $advPosModel->switchType($pos['type']));
        }
        $builder->keyId()->keyLink('title', '广告说明', 'editAdv?id=###');
        $builder->keyHtml('pos', '所属广告位');
        $builder->keyText('click_count', '点击量');
        $builder->buttonNew(U('editAdv?pos_id=' . $aPosId), '新增广告');
        $builder->buttonDelete(U('setAdvStatus'));
        if ($aPosId != 0) {
            $builder->button('广告排期查看', array('href' => U('schedule?pos_id=' . $aPosId)));
            $builder->button('设置广告位', array('href' => U('editPos?id=' . $aPosId)));
        }
        $builder->keyText('url', '链接地址')->keyTime('start_time', '开始生效时间', '不设置则立即生效')->keyTime('end_time', '失效时间', '不设置则一直有效')->keyText('sort', '排序')->keyCreateTime()->keyStatus();
        $builder->data($data['data']);
        $builder->pagination($data['count'], $r);
        $builder->display();
    }

    public function schedule()
    {
        $aPosId = I('pos_id', 0, 'intval');
        if ($aPosId != 0) {
            $map['pos_id'] = $aPosId;
        }
        $map['status'] = 1;
        $data = D('Adv')->where($map)->select();


        foreach ($data as $v) {
            $events[] = array('title' => $v['title'], 'start' => date('Y-m-d h:i', $v['start_time']), 'end' => date('Y-m-d h:i', $v['end_time']), 'data' => array('id' => $v['id']));
        }
        //   dump($events);exit;
        //   echo(json_encode($events));exit;
        $this->assign('events', json_encode($events));
        $this->assign('pos_id', $aPosId);
        $this->display();
    }

    public function editAdv()
    {


        $advModel = D('Common/Adv');

        $aId = I('id', 0, 'intval');

        if ($aId != 0) {
            $adv = $advModel->find($aId);
            $aPosId = $adv['pos_id'];
        } else {
            $aPosId = I('get.pos_id', 0, 'intval');
        }

        $advPosModel = D('Common/AdvPos');
        $pos = $advPosModel->find($aPosId);

        if (IS_POST) {
            $adv['title'] = I('title', '', 'text');
            $adv['pos_id'] = $aPosId;
            $adv['url'] = I('url', '', 'text');
            $adv['sort'] = I('sort', 1, 'intval');
            $adv['status'] = I('status', 1, 'intval');
            $adv['create_time'] = I('create_time', '', 'intval');
            $adv['start_time'] = I('start_time', '', 'intval');
            $adv['end_time'] = I('end_time', '', 'intval');
            $adv['target'] = I('target', '', 'text');
            S('adv_list_' . $pos['name'] . $pos['path'], null);
            if ($pos['type'] == 2) {
                //todo 多图

                $aTitles = I('title', '', 'text');
                $aUrl = I('url', '', 'text');
                $aSort = I('sort', '', 'intval');
                $aStartTime = I('start_time', '', 'intval');
                $aEndTime = I('end_time', '', 'intval');
                $aTarget = I('target', '', 'text');
                $added = 0;
                $advModel->where(array('pos_id' => $aPosId))->delete();
                foreach (I('pic', 0, 'intval') as $key => $v) {
                    $data['pic'] = $v;

                    $data['target'] = $aTarget[$key];
                    $adv_temp['title'] = $aTitles[$key];
                    $adv_temp['pos_id'] = $adv['pos_id'];
                    $adv_temp['url'] = $aUrl[$key];
                    $adv_temp['sort'] = $aSort[$key];
                    $adv_temp['status'] = 1;
                    $adv_temp['create_time'] = time();
                    $adv_temp['start_time'] = $aStartTime[$key];
                    $adv_temp['end_time'] = $aEndTime[$key];
                    $adv_temp['target'] = $aTarget[$key];
                    $adv_temp['data'] = json_encode($data);

                    $result = $advModel->add($adv_temp);
                    if ($result !== false) {
                        $added++;
                    }
                    //todo添加
                }
                $this->success('成功改动' . $added . '个广告。');

            } else {
                switch ($pos['type']) {
                    case 1:
                        //todo 单图
                        $data['pic'] = I('pic', 0, 'intval');
                        $data['target'] = I('target', 0, 'text');
                        break;
                    case 3:
                        $data['text'] = I('text', '', 'text');
                        $data['text_color'] = I('text_color', '', 'text');
                        $data['text_font_size'] = I('text_font_size', '', 'text');
                        $data['target'] = I('target', 0, 'text');
                        //todo 文字
                        break;
                    case 4:
                        //todo 代码
                        $data['code'] = I('code', '', '');
                        break;
                }

                $adv['data'] = json_encode($data);


                if ($aId == 0) {
                    $result = $advModel->add($adv);
                } else {
                    $adv['id'] = $aId;
                    $result = $advModel->save($adv);
                }

                if ($result === false) {
                    $this->error('保存失败。');
                } else {
                    $this->success('保存成功。');
                }
            }


        } else {

            //快速添加广告位逻辑
            //todo 快速添加
            $builder = new AdminConfigBuilder();

            $adv['pos'] = $pos['title'] . '——' . $pos['name'] . '——' . $pos['path'];
            $adv['pos_id'] = $aPosId;
            $builder->keyReadOnly('pos', '所属广告位');
            $builder->keyReadOnly('pos_id', '广告位ID');
            $builder->keyId()->keyTitle('title', '广告说明');


            $builder->title($pos['title'] . '设置——' . $advPosModel->switchType($pos['type']));

            $builder->keyTime('start_time', '开始生效时间', '不设置则立即生效')->keyTime('end_time', '失效时间', '不设置则一直有效')->keyText('sort', '排序')->keyCreateTime()->keyStatus();

            $builder->buttonSubmit()->buttonLink('返回广告列表',array('href'=>U('adv?pos_id='.$aPosId),'class'=>'btn btn-danger'));


            $data = json_decode($adv['data'], true);
            if (!empty($data)) {
                $adv = array_merge($adv, $data);
            }
            if ($aId) {
                $builder->data($adv);
            } else {
                $builder->data(array('pos' => $adv['pos'], 'pos_id' => $aPosId));
            }
            switch ($pos['type']) {
                case 1:
                    //todo 单图
                    $builder->keySingleImage('pic', '图片', '选图上传，建议尺寸' . $pos['width'] . '*' . $pos['height']);
                    $builder->keyText('url', '链接地址');
                    $builder->keySelect('target', '打开方式', null, array('_blank' => '新窗口:_blank', '_self' => '当前层:_self', '_parent' => '父框架:_parent', '_top' => '整个框架:_top'));
                    break;
                case 2:
                    //todo 多图

                    break;
                case 3:
                    $builder->keyText('text', '文字内容', '广告展示文字');
                    $builder->keyText('url', '链接地址');
                    $builder->keyColor('text_color', '文字颜色', '文字颜色')->keyDefault('data[text_color]', '#000000');
                    $builder->keyText('text_font_size', '文字大小，需带单位，例如：14px')->keyDefault('data[text_font_size]', '12px');
                    $builder->keySelect('target', '打开方式', null, array('_blank' => '新窗口:_blank', '_self' => '当前层:_self', '_parent' => '父框架:_parent', '_top' => '整个框架:_top'));

                    //todo 文字
                    break;
                case 4:
                    //todo 代码
                    $builder->keyTextArea('code', '代码内容', '不对此字段进行过滤，可填写js、html');
                    break;
            }
            $builder->keyDefault('status', 1)->keyDefault('sort', 1);

            $builder->keyDefault('title', $pos['title'] . '的广告 ' . date('m月d日', time()) . ' 添加')->keyDefault('end_time', time() + 60 * 60 * 24 * 7);
            if ($pos['type'] == 2) {
                $this->_meta_title = $pos['title'] . '设置——' . $advPosModel->switchType($pos['type']);
                $adv['start_time'] = isset($adv['start_time']) ? $adv['start_time'] : time();
                $adv['end_time'] = isset($adv['end_time']) ? $adv['end_time'] : time() + 60 * 60 * 24 * 7;
                $adv['create_time'] = isset($adv['create_time']) ? $adv['create_time'] : time();
                $adv['sort'] = isset($adv['sort']) ? $adv['sort'] : 1;
                $adv['status'] = isset($adv['status']) ? $adv['status'] : 1;

                $advs = D('Adv')->where(array('pos_id' => $aPosId))->select();
                foreach ($advs as &$v) {
                    $data = json_decode($v['data'], true);
                    if (!empty($data)) {
                        $v = array_merge($v, $data);
                    }
                }
                unset($v);
                $this->assign('list', $advs);
                $this->assign('pos', $pos);
                $this->display('editslider');
            } else {
                $builder->display();
            }


        }


    }


}
