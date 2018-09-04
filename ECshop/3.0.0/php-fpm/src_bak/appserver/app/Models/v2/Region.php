<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use Cache;


class Region extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'region';
    public    $timestamps = false;

    protected $appends = ['id', 'name', 'more'];
    protected $visible = ['id', 'name', 'more', 'regions'];

    public static function getRegionName($id)
    {
        $data = self::getRegionGroup($id);
        if (!empty($data)) {
            $array = array_pluck($data, 'name');
            if (!empty($array)) {
                return implode(" ", $array);
            }
        }

        return false;
    }

    //根据id 获取 国家 省 市 地区信息
    public static function getRegionGroup($id)
    {
        $body = [];
        while (true) {
            if($model = Region::where('region_id', $id)->first())
            {
                $id = $model->parent_id;
                $body[] = $model;
            } else {
                break;
            }
        }
        return array_reverse($body);
    }

    //根据id 获取 parent type and parent id
    public static function getParentId($id)
    {
        $body = [];
        while (true) {
            if($model = Region::where('region_id', $id)->first())
            {
                $id = $model->parent_id;

                switch ($model->region_type) {
                    case 0:
                        $body['country'] = $model->id;
                        break;

                    case 1:
                        $body['province'] = $model->id;
                        break;

                    case 2:
                        $body['city']  = $model->id;
                        break;

                    case 3:
                        $body['region'] = $model->id;
                        break;

                    default:
                        break;
                }

            } else {
                break;
            }
        }
        return $body;
    }

    public static function getList()
    {
        $key = 'region';

        if (!$model = Cache::get($key)) {
            $model = Region::with('regions')->where('parent_id', 0)->get()->toArray();
            Cache::put($key, $model, 10);
        }

        return self::formatBody(['regions' => $model]);
    }

    public function regions()
    {
        return $this->hasMany('App\Models\v2\Region', 'parent_id')->with('regions');
    }

    public function getIdAttribute()
    {
        return $this->attributes['region_id'];
    }

    public function getNameAttribute()
    {
        return $this->attributes['region_name'];
    }

    public function getMoreAttribute()
    {
       if (Region::where('parent_id', $this->region_id)->count()) {
            return 1;
        }
        return 0;
    }

}
