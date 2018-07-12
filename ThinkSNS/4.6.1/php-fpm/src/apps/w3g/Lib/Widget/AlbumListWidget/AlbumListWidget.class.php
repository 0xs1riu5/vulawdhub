<?php

class AlbumListWidget extends Widget
{
    //type:select
    public function render($data)
    {
        //初始化参数
        if (empty($data['type'])) {
            $data['type'] = 'select';
        }
        if (empty($data['form_name'])) {
            $data['form_name'] = 'albumlist';
        }
        if (empty($data['form_id'])) {
            $data['form_id'] = 'albumlist';
        }
        if (empty($data['gid'])) {
            $data['gid'] = 0;
        }

        //创建默认相册
        $pre = C('DB_PREFIX');
        if (D()->table("{$pre}group_album")->where("is_del=0 AND gid='".$data['gid']."'")->count() == 0) {
            $album['cTime'] = time();
            $album['mTime'] = time();
            $album['userId'] = $_SESSION['mid'];
            $album['gid'] = $data['gid'];
            $album['name'] = '群组默认相册';

            D()->table("{$pre}group_album")->add($album);
        }

        return $this->renderFile($data);
    }
    /**
     * renderFile
     * 重写renderFile.可以自由组合参数进行模板输出.
     *
     * @param string $templateFile
     * @param string $var
     * @param string $charset
     *
     * @return maxed
     */
    protected function renderFile($data, $charset = 'utf-8')
    {
        if (empty($data['type'])) {
            $data['type'] = 'select';
        }
        if (empty($data['form_name'])) {
            $data['form_name'] = 'albumlist';
        }
        if (empty($data['form_id'])) {
            $data['form_id'] = 'albumlist';
        }
        if (empty($data['gid'])) {
            $data['gid'] = 0;
        }

        $templateFile = 'AlbumListWidget/'.$data['type'];
        $pre = C('DB_PREFIX');
        if (D()->table("{$pre}group_album")->where("gid='".$data['gid']."'")->count() == 0) {
            //$album->createNewData($data['uid']);
        }

        $var['data'] = D()->table("{$pre}group_album")->where("is_del = 0 AND gid='".$data['gid']."'")->findAll();

        $var['form_name'] = $data['form_name'];
        $var['form_id'] = $data['form_id'];
        $var['selected'] = intval($data['selected']);

        return parent::renderFile($templateFile, $var, $charset);
    }
}
