<?php

namespace App\Models\v2;

use App\Models\BaseModel;

class Article extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'article';

    public    $timestamps = false;

    protected $hidden = [];

    protected $guarded = [];

    protected $appends = ['id', 'created_at', 'updated_at', 'url','more'];

    protected $visible = ['id', 'created_at', 'updated_at', 'url', 'title', 'more'];

    public static function getList(array $attributes)
    {
        extract($attributes);
        $model = Article::where('cat_id', $id);

        $total = $model->count();

        $data = $model->orderBy('add_time', 'DESC')
            ->orderBy('article_id', 'DESC')
            ->paginate($per_page)
            ->toArray();

        return self::formatBody(['articles' => $data['data'], 'paged' => self::formatPaged($page, $per_page, $total)]);
    }


    public static function getArticle($id)
    {
        $data = [];
        if($model = Article::where('article_id', $id)->first()){
            $data['title'] = $model->title;
            $data['content'] = $model->content;
            $data['add_time'] = $model->add_time;
        }

        return view('article.mobile', ['article' => $data]);
    }


    public function getIdAttribute()
    {
        return $this->attributes['article_id'];
    }

    public function getUrlAttribute()
    {
        return url('/v2/article.'.$this->attributes['article_id']);
    }

    public function getCreatedAtAttribute()
    {
        return $this->attributes['add_time'];
    }

    public function getUpdatedAtAttribute()
    {
        return $this->attributes['add_time'];
    }

    public function getMoreAttribute()
    {
        return false;
    }

}
