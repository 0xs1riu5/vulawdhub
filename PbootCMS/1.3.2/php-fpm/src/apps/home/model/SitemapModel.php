<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年2月14日
 *  Sitemap模型
 */
namespace app\home\model;

use core\basic\Model;

class SitemapModel extends Model
{

    // 分类栏目列表
    public function getSorts()
    {
        $fields = array(
            'a.id',
            'a.pcode',
            'a.scode',
            'a.name',
            'b.type',
            'a.filename',
            'a.outlink'
        );
        $join = array(
            'ay_model b',
            'a.mcode=b.mcode',
            'LEFT'
        );
        $result = parent::table('ay_content_sort a')->field($fields)
            ->where("a.acode='" . get_lg() . "'")
            ->where('a.status=1')
            ->join($join)
            ->order('a.pcode,a.sorting,a.id')
            ->select();
        return $result;
    }

    // 指定列表内容
    public function getList($scode)
    {
        $fields = array(
            'a.id',
            'a.filename',
            'a.date',
            'c.type'
        );
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_model c',
                'b.mcode=c.mcode',
                'LEFT'
            )
        );
        
        $where = array(
            "a.acode='" . get_lg() . "'",
            'a.status=1',
            'c.type=2'
        );
        
        return parent::table('ay_content a')->field($fields)
            ->where("a.scode='$scode'")
            ->where($where)
            ->join($join)
            ->select();
    }
}