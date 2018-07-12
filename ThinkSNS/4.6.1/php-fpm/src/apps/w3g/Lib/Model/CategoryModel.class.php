<?php

class CategoryModel extends Model
{
    public $tableName = 'group_category';
    //生成分类Tree
    public function _makeTree($pid)
    {
        if ($pid == 0 && $cache = S('Cache_Group_Cate_'.$pid)) { // pid=0 才缓存
            return $cache;
        }

        if ($c = $this->where("pid='$pid'")->findAll()) {
            if ($pid == 0) {
                foreach ($c as $v) {
                    $cTree['t'] = $v['title'];
                    $cTree['a'] = $v['id'];
                    $cTree['d'] = $this->_makeTree($v['id']);
                    $cTrees[] = $cTree;
                }
            } else {
                foreach ($c as $v) {
                    $cTree['t'] = $v['title'];
                    $cTree['a'] = $v['id'];
                    $cTree['d'] = ''; //$v['id'];
                    $cTrees[] = $cTree;
                }
            }
        }
        $pid == 0 && S('Cache_Group_Cate_'.$pid, $cTrees);    // pid=0 才缓存
        return $cTrees;
    }

    //生成分类Tree - new
    public function _maskTreeNew($pid)
    {
        $c = $this->where("pid='$pid'")->findAll();
        if (!empty($c)) {
            foreach ($c as $v) {
                $cTree['t'] = $v['title'];
                $cTree['a'] = $v['id'];
                $maskInfo = $this->_maskTreeNew($v['id']);
                $cTree['d'] = empty($maskInfo) ? '' : $maskInfo;
                $cTrees[] = $cTree;
            }
        }

        return $cTrees;
    }

    //获取LI列表
    public function getCategoryList($pid = '0')
    {
        $list = $this->_makeLiTree($pid);

        return $list;
    }
    public function _makeLiTree($pid)
    {
        if ($c = $this->where("pid='$pid'")->findAll()) {
            $list .= '<ul>';
            foreach ($c as $p) {
                @extract($p);

                $ptitle = "<span id='category_".$id."' title='".$title."'><a href='javascript:void(0)' onclick=\"edit('".$id."')\">".$title.'</a></span>';
                $title = '['.$id.'] '.$ptitle;

                $list .= '
					<li id="li_'.$id.'">
					<span style="float:right;">
						<a href="javascript:void(0)" onclick="edit(\''.$id.'\')" style="font-size:9px">修改</a>
						<a href="javascript:void(0)" onclick="del(\''.$id.'\')" style="font-size:9px">删除</a>
					</span> '.$title.'
					</li>
					<hr style="height:1px;color:#ccc" />';

                $list .= $this->_makeLiTree($id);
            }
            $list .= '</ul>';
        }

        return $list;
    }
    //解析分类
    public function _digCate($array)
    {
        foreach ($array as $k => $v) {
            $nk = str_replace('pid', '', $k);
            if (is_numeric($nk) && !empty($v)) {
                $cates[$nk] = intval($v);
            }
        }
        $pid = is_array($cates) ? end($cates) : 0;

        unset($cates);

        return intval($pid);
    }

    //解析分类 - new
    public function _digCateNew($array)
    {
        foreach ($array as $k => $v) {
            $nk = str_replace('cid', '', $k);
            if (is_numeric($nk) && !empty($v)) {
                $cates[$nk] = intval($v);
            }
        }
        $pid = is_array($cates) ? end($cates) : 0;

        unset($cates);

        return intval($pid);
    }

    //解析分类树
    public function _digCateTree($array)
    {
        foreach ($array as $k => $v) {
            $nk = str_replace('pid', '', $k);
            if (is_numeric($nk) && !empty($v)) {
                $cates[$nk] = intval($v);
            }
        }
        if (is_array($cates)) {
            return implode(',', $cates);
        } else {
            return intval($cates);
        }
    }
    //生成分类树
    public function _makeParentTree($id, $onlyShowPid = false)
    {
        $tree = $this->_makeCateTree($id);
        if ($onlyShowPid) {
            $tree = preg_replace('/^'.$id.'|,'.$id.'$/', '', $tree);
        }

        return $tree;
    }
    public function _makeCateTree($id)
    {
        //$pid	=	$this->find($id,'pid')->pid;

        $pid = $this->getField('pid', 'id='.$id);
        if ($pid > 0) {
            $tree = $this->_makeCateTree($pid).','.$id;
        } else {
            $tree = $id;
        }

        return $tree;
    }

    //获取指定分类下的子分类ID
    public function getAllCateIdWithPid($pid)
    {
        static $result;
        $allPid = $this->field('id')->where('pid='.$pid)->findAll();
        if (!empty($allPid)) {
            foreach ($allPid as $value) {
                $this->getAllCateIdWithPid($value['id']);
                $result[] = $value['id'];
            }
        }

        return $result;
    }

    //通过子分类ID，获取完整路径
    public function getPathWithCateId($cid)
    {
        $pInfo = $this->where('id='.$cid)->find();
        if (!empty($pInfo)) {
            $path = $this->getPathWithCateId($pInfo['pid']);
            $path[] = $pInfo['title'];
        }

        return $path;
    }
}
