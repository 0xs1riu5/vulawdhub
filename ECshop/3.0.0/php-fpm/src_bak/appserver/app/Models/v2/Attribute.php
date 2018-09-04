<?php

namespace App\Models\v2;
use App\Models\BaseModel;
use App\Helper\Token;
use Log;


class Attribute extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'attribute';
    public    $timestamps = false;
    protected $primaryKey = 'attr_id';



    protected $visible = ['id','name','attrs','is_multiselect'];
    protected $appends = ['id','name','attrs','is_multiselect'];

    // protected $with = ['goods'];


    /**
     * 是否存在规格
     * @access      public
     * @param       array       $goods_attr_id_array        一维数组
     * @return      string
     */
    public static function is_property($goods_attr_id_array, $sort = 'asc')
    {
        if (empty($goods_attr_id_array))
        {
            return $goods_attr_id_array;
        }

        //重新排序
        // $row = Attribute::where('attr_type',1)->with(['goodsattr' => function ($query) use ($goods_attr_id_array){
        //     $query->whereIn('goods_attr_id',$goods_attr_id_array);
        // }])->orderBy('attr_id',$sort)->get();
        $row = self::leftJoin('goods_attr','goods_attr.attr_id','=','attribute.attr_id')
            ->where(['attribute.attr_type' => 1])
            ->whereIn('goods_attr.goods_attr_id',$goods_attr_id_array)
            ->orderBy('attribute.attr_id',$sort)
            ->get(['attribute.attr_type','goods_attr.attr_value','goods_attr.goods_attr_id']);

        $return_arr = array();
        foreach ($row as $value)
        {
            $return_arr['sort'][]   = $value['goods_attr_id'];

            $return_arr['row'][$value['goods_attr_id']]    = $value;
        }

        if(!empty($return_arr))
        {
            return true;
        }
        else
        {
            return false;
        }
    }   


    /**
     * 获得指定的商品属性
     *
     * @access      public
     * @param       array       $arr        规格、属性ID数组
     * @param       type        $type       设置返回结果类型：pice，显示价格，默认；no，不显示价格
     *
     * @return      string
     */
    public static function get_goods_attr_info($arr, $type = 'pice')
    {
        $attr   = '';

        if (!empty($arr))
        {
            $fmt = "%s:%s[%s] \n";

            // $sql = "SELECT a.attr_name, ga.attr_value, ga.attr_price ".
            //         "FROM ".$GLOBALS['ecs']->table('goods_attr')." AS ga, ".
            //             $GLOBALS['ecs']->table('attribute')." AS a ".
            //         "WHERE " .db_create_in($arr, 'ga.goods_attr_id')." AND a.attr_id = ga.attr_id";
            // $res = $GLOBALS['db']->query($sql);

            $res = GoodsAttr::selectRaw('attr_price,attr_value,attr_name as name')->whereIn('goods_attr_id',$arr)->leftJoin('attribute',function ($query) use($arr){
                $query->on('attribute.attr_id','=','goods_attr.attr_id');
            })->get();
            foreach ($res as $key => $row) {

                $attr_price = round(floatval($row['attr_price']), 2);
                $attr .= sprintf($fmt, $row['name'], $row['attr_value'], $attr_price);
            }
            $attr = str_replace('[0]', '', $attr);
        }

        return $attr;
    }

    /**
     * 将 goods_attr_id 的序列按照 attr_id 重新排序
     *
     * 注意：非规格属性的id会被排除
     *
     * @access      public
     * @param       array       $goods_attr_id_array        一维数组
     * @param       string      $sort                       序号：asc|desc，默认为：asc
     *
     * @return      string
     */
    public static function sort_goods_attr_id_array($goods_attr_id_array, $sort = 'asc')
    {

        if (empty($goods_attr_id_array))
        {
            return $goods_attr_id_array;
        }

        //重新排序

        $row = self::leftJoin('goods_attr','goods_attr.attr_id','=','attribute.attr_id')
            ->where(['attribute.attr_type' => 1])
            ->whereIn('goods_attr.goods_attr_id',$goods_attr_id_array)
            ->orderBy('attribute.attr_id',$sort)
            ->get(['attribute.attr_type','goods_attr.attr_value','goods_attr.goods_attr_id']);

        $return_arr = array();

        foreach ($row as $value)
        {
            if ($value['goods_attr_id']) {
                $return_arr['sort'][]   = $value['goods_attr_id'];
                $return_arr['row'][$value['goods_attr_id']]    = $value;
            }
        }

        Log::debug('sort_goods_attr_id_array', ['token' => $return_arr]);
        return $return_arr;
    }

    public function getAttrsAttribute()
    {
        return GoodsAttr::where('goods_id',$this->pivot->goods_id)->where('attr_id',$this->pivot->attr_id)->get()->toArray();
    }


    public function getIdAttribute()
    {
        return $this->attr_id;
    }

    public function getNameAttribute()
    {
        return $this->attr_name;
    }

    public function getIsmultiselectAttribute()
    {
        return ($this->attr_type == 2) ? true :false;
    }

    public function goodsattr()
    {
        return $this->hasMany('App\Models\v2\GoodsAttr', 'attr_id', 'attr_id');
    }
}
