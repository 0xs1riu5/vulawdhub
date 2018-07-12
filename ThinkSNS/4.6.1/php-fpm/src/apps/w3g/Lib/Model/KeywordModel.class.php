<?php

class KeywordModel extends Model
{
    public function addKeyword($keyword, $module, $pid = 0, $data = array())
    {
        $data ['pid'] = intval($pid);
        $data ['module'] = t($module);

        $keyword = t($keyword);
        $keyword = explode(',', $keyword);
        $keyword = array_filter($keyword);

        $has = false;
        if (!empty($keyword)) {
            $map ['keyword'] = array(
                    'in',
                    $keyword,
            );
            $has = $this->where($map)->getField('id');
        }
        if ($has) {
            return '-1';
        }

        $res = false;
        foreach ($keyword as $kw) {
            $data ['keyword'] = t($kw);
            $res = $this->add($data);
        }

        return $res;
    }
    public function editKeyword($keyword, $module, $pid = 0, $data = array())
    {
        $map ['pid'] = intval($pid);
        $map ['module'] = t($module);
        $this->where($map)->delete();

        return $this->addKeyword($keyword, $module, $pid, $data);
    }
    public function checkKeyword($keyword, $pid = 0)
    {
        $map ['keyword'] = t($keyword);

        $t_type ['type'] = substr($keyword, 0, 2);
        $t_type ['team'] = (int) substr($keyword, 2, 3);
        $area = M('area')->where($t_type)->find();
        if (empty($area) && $map ['token'] == TOKEN) {
            return 1;
        }
        $info = $this->where($map)->find();

        return (!$info || $info ['pid'] == intval($pid)) ? 0 : 1;
    }
}
