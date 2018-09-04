<?php

namespace App\Models\v2;
use App\Models\BaseModel;

use App\Helper\Token;

class GoodsAttr extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'goods_attr';
    public    $timestamps = false;


    protected $visible = ['id','attr_name','attr_price','is_multiselect'];

    protected $appends = ['id','attr_name', 'attr_price','is_multiselect'];

    protected $guarded = [];

    // protected $with = ['attribute'];

    public function getIdAttribute()
    {
        return $this->goods_attr_id;
    }

    public function getAttrNameAttribute()
    {
        return $this->attr_value;
    }

    public function getAttrPriceAttribute()
    {
        return ($this->attributes['attr_price']) ? floatval($this->attributes['attr_price']): 0;
    }

    public function getIsmultiselectAttribute()
    {
        return Attribute::where('attr_id',$this->attr_id)->value('attr_type') == 2 ? true :false;
    }



    /**
     * 获得指定的规格的价格
     *
     * @access  public
     * @param   mix     $property   规格ID的数组或者逗号分隔的字符串
     * @return  void
     */
    public static function property_price($property)
    {
        if (!empty($property))
        {
            if(is_array($property))
            {
                foreach($property as $key=>$val)
                {
                    if (strpos($val,',')) {
                        $property = explode(',',$val);
                    }else{
                        $property[$key]=addslashes($val);
                    }
                }
            }
            else
            {
                $property = addslashes($property);
            }
            // $where = db_create_in($property, 'goods_attr_id');

            // $sql = 'SELECT SUM(attr_price) AS attr_price FROM ' . $GLOBALS['ecs']->table('goods_attr') . " WHERE $where";
            // $price = floatval($GLOBALS['db']->getOne($sql));

            $price = self::whereIn('goods_attr_id',$property)->sum('attr_price');
        }
        else
        {
            $price = 0;
        }

        return $price;
    }


    public function attributes()
    {
        return $this->belongsTo('App\Models\v2\Attribute','attr_id','attr_id');
    }
}
