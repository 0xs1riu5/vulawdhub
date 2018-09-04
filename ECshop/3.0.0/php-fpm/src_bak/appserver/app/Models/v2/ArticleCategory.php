<?php

namespace App\Models\v2;

use App\Models\BaseModel;

class ArticleCategory extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'article_cat';

    public    $timestamps = false;

    protected $hidden = [];

    protected $guarded = [];

    protected $appends = ['id', 'title', 'link', 'created_at', 'updated_at', 'more'];

    protected $visible = ['id', 'link', 'title', 'created_at', 'updated_at', 'more'];

    public static function getList(array $attributes)
    {
        extract($attributes);

        if(self::where('parent_id', $id)->count() > 0){
            $model = self::where('parent_id', $id);
        }else{
            $model = Article::where('cat_id', $id);
        }

        $total = $model->count();

        $data = $model
            ->orderBy('cat_id', 'DESC')
            ->paginate($per_page)
            ->toArray();

        return self::formatBody(['articles' => $data['data'], 'paged' => self::formatPaged($page, $per_page, $total)]);
    }




    public function getIdAttribute()
    {
        return $this->attributes['cat_id'];
    }

    public function getTitleAttribute()
    {
        return  $this->attributes['cat_name'];
    }
    public function getLinkAttribute()
    {
        return null;
    }

    public function getCreatedAtAttribute()
    {
        return time();
    }

    public function getUpdatedAtAttribute()
    {
        return time();
    }

    public function getMoreAttribute()
    {
        return true;
    }

}
