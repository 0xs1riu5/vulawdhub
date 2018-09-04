<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;

class Brand extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'brand';
    public    $timestamps = false;

    protected $appends = ['id', 'name', 'logo'];

    protected $visible = ['id', 'name', 'logo'];

    public static function getBrandByName($name)
    {
        $model = Brand::where('brand_name', $name)->first();
        if ($model) {
            return [
                'id'   => $model->brand_id,
                'name' => $model->brand_name,
                'logo' => formatPhoto($model->brand_logo, null)
            ];
        } else {
            return [
                'id'   => null,
                'name' => $name,
                'logo' => null
            ];
        }

    }

    public static function getBrandById($id)
    {
        return Brand::where('brand_id', $id)->pluck('brand_name');
    }



    public static function getList(array $attributes)
    {
        extract($attributes);

        $total = Brand::count();

        $data = Brand::paginate($per_page)
                ->toArray();
        return self::formatBody(['brands' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    public static function getListByOrder(array $attributes)
    {
        extract($attributes);

        $total = Brand::count();

        $data = Brand::orderBy('sort_order', 'ASC')
            ->paginate($per_page)
            ->toArray();

        return self::formatBody(['brands' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    public function getIdAttribute()
    {
        return $this->attributes['brand_id'];    }

    public function getNameAttribute()
    {
        return $this->attributes['brand_name'];
    }

    public function getLogoAttribute()
    {
        return formatPhoto($this->attributes['brand_logo'], null);
    }


}
